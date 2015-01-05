<?php

namespace Backend\Modules\Links\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

 use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
 use Backend\Core\Engine\Model as BackendModel;
 use Backend\Core\Engine\Form as BackendForm;
 use Backend\Core\Engine\Language as BL;
 use Backend\Modules\Links\Engine\Model as BackendLinksModel;
 use Backend\Modules\Tags\Engine\Model as BackendTagsModel;

/**
 * This is the add-action for the links module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class Add extends BackendBaseActionAdd
{
	/**
	 * The available categories
	 *
	 * @var	array
	 */
	private $categories;

	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// get all data
		$this->getData();

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
	 * Get the data for a question
	 *
	 * @return void
	 */
	private function getData()
	{
		// get categories
		$this->categories = BackendLinksModel::getCategoriesForDropdown();

		if(empty($this->categories))
		{
			$this->redirect(BackendModel::createURLForAction('AddCategory'));
		}
	}

	/**
	 * Load the form
	 *
	 * @return void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('add');

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
		$this->frm->addText('title')->setAttribute('id', 'title');
		$this->frm->getField('title')->setAttribute('class', 'title ' . $this->frm->getField('title')->getAttribute('class'));
		$this->frm->addText('url')->setAttribute('id', 'url');
		$this->frm->getField('url')->setAttribute('class', 'title ' . $this->frm->getField('url')->getAttribute('class'));
		$this->frm->addText('description')->setAttribute('id', 'description');
		$this->frm->getField('description')->setAttribute('class', 'title ' . $this->frm->getField('description')->getAttribute('class'));
		$this->frm->addText('tags', null, null, 'inputText tagBox', 'inputTextError tagBox');
		$this->frm->addDropdown('categories', $this->categories);
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, 'N');
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

		// assign categories
		$this->tpl->assign('categories', $this->categories);
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
			$this->frm->getField('url')->isFilled(BL::err('UrlIsRequired'));
			
			// check if url is well formed
			$this->frm->getField('url')->isURL(BL::err('InvalidUrl'));
			$this->frm->getField('description')->isFilled(BL::err('DescriptionIsRequired'));
			$this->frm->getField('categories')->isFilled(BL::err('CategoryIsRequired'));
			
			// no errors?
			if($this->frm->isCorrect())
			{
				// build item
				$item = array();
				$item['id'] = BackendLinksModel::getMaximumId()+1;
				$item['category_id'] = $this->frm->getField('categories')->getValue();
				$item['language'] = BL::getWorkingLanguage();
				$item['title'] = $this->frm->getField('title')->getValue();
				$item['url'] = $this->frm->getField('url')->getValue();
				$item['description'] = $this->frm->getField('description')->getValue(true);
				$item['hidden'] = $this->frm->getField('hidden')->getValue();
				$item['created_on'] = BackendModel::getUTCDate();

				// insert the item
				$insert = BackendLinksModel::insertLink($item);
				
				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_add', array('item' => $item));

				// save the tags
				BackendTagsModel::saveTags($item['id'], $this->frm->getField('tags')->getValue(), $this->URL->getModule());
				
				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('Index') . '&report=link-saved&var=' . urlencode($item['title']) . '&highlight=row-' . $insert);
			}
		}
	}
}
