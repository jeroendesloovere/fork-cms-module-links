<?php

namespace Backend\Modules\Links\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Modules\Links\Engine\Model as BackendLinksModel;

/**
 * This is the add category action for the links module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class AddCategory extends BackendBaseActionAdd
{
	/**
	 * Is the user God?
	 *
	 * @var	bool
	 */
	private $god;
	
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// load the form
		$this->loadForm();

		// validate the form
		$this->validateForm();

		// parse
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * Load the form
	 *
	 * @return void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('add_category');

		// set hidden values
		$rbtHiddenValues = array();
		$rbtHiddenValues[] = array(
			'label' => BL::lbl('Hidden', $this->URL->getModule()), 
			'value' => 'Y'
		);
		$rbtHiddenValues[] = array(
			'label' => BL::lbl('Published'), 
			'value' => 'N'
		);

		// create elements
		$this->frm->addText('title');
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, 'N');
		
		// check if user is almighty
		$this->god = BackendAuthentication::getUser()->isGod();
		
		// if he is show an image field
		if($this->god)
		{
			$this->frm->addImage('logo');
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

		// assign godstatus
		$this->tpl->assign('isGod', $this->god);
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
				// build category array
				$item = array();
				$item['language'] = BL::getWorkingLanguage();
				$item['title'] = (string) $this->frm->getField('title')->getValue();
				$item['sequence'] = (int) BackendLinksModel::getMaximumCategorySequence() + 1;
				$item['hidden'] = (string) $this->frm->getField('hidden')->getValue();
				
				// check for filetype
				if($this->frm->getField('logo')->isFilled())
				{
					// image extension and mime type
					$this->frm->getField('logo')->isAllowedExtension(array('jpg', 'png', 'gif', 'jpeg'), BL::err('JPGGIFAndPNGOnly'));
					$this->frm->getField('logo')->isAllowedMimeType(array('image/jpg', 'image/png', 'image/gif', 'image/jpeg'), BL::err('JPGGIFAndPNGOnly'));
				}
					
				// the image path
				$imagePath = FRONTEND_FILES_PATH . '/links/images';

				// create folders if needed
				$fs = new Filesystem();
				if(!$fs->exists($imagePath . '/source')) $fs->mkdir($imagePath . '/source');
				if(!$fs->exists($imagePath . '/128x128')) $fs->mkdir($imagePath . '/128x128');

				// image provided?
				if($this->frm->getField('logo')->isFilled())
				{
					// build the image name
					$item['logo'] = time() . '.' . $this->frm->getField('logo')->getExtension();

					// upload the image & generate thumbnails
					$this->frm->getField('logo')->generateThumbnails($imagePath, $item['logo']);
				}
				
				// insert the item
				$insert = BackendLinksModel::insertCategory($item);
				
				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_add_category', array('item' => $item));
				
				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('categories') . '&report=added-category&var=' . urlencode($item['title']) . '&highlight=row-' . $insert);
			}
		}
	}
}