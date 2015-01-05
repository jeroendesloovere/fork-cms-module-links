<?php

namespace Backend\Modules\Links\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Modules\Links\Engine\Model as BackendLinksModel;

/**
 * This is the edit_category action for the links module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class EditCategory extends BackendBaseActionEdit
{
	/**
	 * Is the user God?
	 *
	 * @var	bool
	 */
	private $god;
	
	/**
	 * Category data
	 *
	 * @var	array
	 */
	private $category;
	
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exists?
		if($this->id !== null && BackendLinksModel::existsCategory($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get all data for the item we want to edit
			$this->getData();

			// load the form
			$this->loadForm();

			// validate the form
			$this->validateForm();

			// parse the form
			$this->parse();

			// display the page
			$this->display();
		}

		// no item found, throw an exceptions, because somebody is fucking with our URL
		else $this->redirect(BackendModel::createURLForAction('Categories') . '&error=non-existing');
	}

	/**
	 * Get the data
	 *
	 * @return void
	 */
	private function getData()
	{
		$this->category = BackendLinksModel::getCategoryFromId($this->id);
	}

	/**
	 * Load the form
	 *
	 * @return void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('edit_category');

		// get values for the form
		$rbtHiddenValues = array();
		$rbtHiddenValues[] = array(
			'label' => BL::lbl('Hidden'), 
			'value' => 'Y'
		);
		$rbtHiddenValues[] = array(
			'label' => BL::lbl('Published'), 
			'value' => 'N'
		);

		// create elements
		$this->frm->addText('title', $this->category['title']);
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, $this->category['hidden']);
		
		// check if user is almighty
		$this->god = BackendAuthentication::getUser()->isGod();
		
		// if he is show an image field
		if($this->god)
		{
			$this->frm->addImage('logo');
			$this->frm->addCheckbox('delete_logo');
		}
	}

	/**
	 * Parse the form
	 *
	 * @return void
	 */
	protected function parse()
	{
		// call parent
		parent::parse();

		// assign the category
		$this->tpl->assign('category', $this->category);
		$this->tpl->assign('isGod', $this->god);

		// can the category be deleted?
		if(BackendLinksModel::deleteCategoryAllowed($this->id)) $this->tpl->assign('showDelete', true);
	}

	/**
	 * Validate the form
	 *
	 * @return void
	 */
	private function validateForm()
	{
		// is the form submitted?
		if($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// validate fields
			$this->frm->getField('title')->isFilled(BL::err('TitleIsRequired'));

			// no errors?
			if($this->frm->isCorrect())
			{
				// first, build the category array
				$item = array();
				$item['id'] = (int) $this->id;
				$item['extra_id'] = (int) $this->category['extra_id'];
				$item['title'] = (string) $this->frm->getField('title')->getValue();
				$item['language'] = (string) BL::getWorkingLanguage();
				$item['hidden'] = (string) $this->frm->getField('hidden')->getValue();
				
				if($this->god)
				{
					$item['logo'] = $this->category['logo'];
					
					// the image path
					$imagePath = FRONTEND_FILES_PATH . '/links/images';

					// create folders if needed
					$fs = new Filesystem();
					if(!$fs->exists($imagePath . '/source')) $fs->mkdir($imagePath . '/source');
					if(!$fs->exists($imagePath . '/128x128')) $fs->mkdir($imagePath . '/128x128');
					
					// if the image should be deleted
					if($this->frm->getField('delete_logo')->isChecked())
					{
						// delete the image
						$fs = new Filesystem();
						$fs->remove($imagePath . '/source/' . $item['logo']);
						$fs->remove($imagePath . '/128x128/' . $item['logo']);

						// reset the name
						$item['logo'] = null;
					}

					// new image given?
					if($this->frm->getField('logo')->isFilled())
					{
						// delete the old image
						$fs = new Filesystem();
						$fs->remove($imagePath . '/source/' . $item['logo']);
						$fs->remove($imagePath . '/128x128/' . $item['logo']);

						// build the image name
						$item['logo'] = time() . '.' . $this->frm->getField('logo')->getExtension();
						
						// upload the image & generate thumbnails
						$this->frm->getField('logo')->generateThumbnails($imagePath, $item['logo']);
					}
					
					// rename the old image
					elseif($item['logo'] != null)
					{
						// get the old file extension
						$imageExtension = new File($imagePath . '/source/' . $item['logo']);
						
						// build the image name
						$newName = time() . '.' . $imageExtension;

						// only change the name if there is a difference
						if($newName != $item['logo'])
						{
							// loop folders
							foreach(BackendModel::getThumbnailFolders($imagePath, true) as $folder)
							{
								// move the old file to the new name
								$fs = new Filesystem();
								$fs->rename($folder['path'] . '/' . $item['logo'], $folder['path'] . '/' . $newName);
							}

							// assign the new name to the database
							$item['logo'] = $newName;
						}
					}
				}
				
				// otherwise nullify the logofield
				else $item['logo'] = null;
				
				// update the item
				$update = BackendLinksModel::updateCategory($item);
				
				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_edit_category', array('item' => $item));
				
				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('Categories') . '&report=edited-category&var=' . urlencode($item['title']) . '&highlight=row-' . $update);
			}
		}
	}
}
