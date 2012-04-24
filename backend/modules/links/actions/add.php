<?php

/**
 * This is the add-action for the links module
 *
 * @package backend
 * @subpackage links
 *
 * @author John Poelman <john.poelman@bloobz.be>
 * @since 1.0.0
 */
class BackendLinksAdd extends BackendBaseActionAdd
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
			$this->redirect(BackendModel::createURLForAction('add_category'));
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
		$rbtHiddenValues[] = array('label' => BL::lbl('Hidden', $this->URL->getModule()), 'value' => 'Y');
		$rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');
		
		// protocols
		$protocols = array('http://' => 'http://','https://' => 'https://','news://' => 'news://','ftp://' => 'ftp://');

		// create elements
		$this->frm->addText('title')->setAttribute('id', 'title');
		$this->frm->getField('title')->setAttribute('class', 'title ' . $this->frm->getField('title')->getAttribute('class'));
		$this->frm->addText('url')->setAttribute('id', 'url');
		$this->frm->addText('description')->setAttribute('id', 'description');
		$this->frm->getField('description')->setAttribute('class', 'title ' . $this->frm->getField('description')->getAttribute('class'));
		$this->frm->addDropdown('categories', $this->categories);
		$this->frm->addDropdown('protocol', $protocols);
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
			$this->frm->getField('url')->isFilled(BL::err('urlIsRequired'));
			$this->frm->getField('description')->isFilled(BL::err('DescriptionIsRequired'));
			$this->frm->getField('categories')->isFilled(BL::err('CategoryIsRequired'));
			$this->frm->getField('protocol')->isFilled(BL::err('ProtocolIsRequired'));

			// no errors?
			if($this->frm->isCorrect())
			{
				// build item
				$item['category_id'] = $this->frm->getField('categories')->getValue();
				$item['language'] = BL::getWorkingLanguage();
				$item['title'] = $this->frm->getField('title')->getValue();	
				$item['protocol'] = $this->frm->getField('protocol')->getValue();
				$item['url'] = $this->frm->getField('url')->getValue();
				$item['description'] = $this->frm->getField('description')->getValue(true);
				$item['hidden'] = $this->frm->getField('hidden')->getValue();
				$item['created_on'] = BackendModel::getUTCDate();

				// insert the item
				$item['id'] = BackendLinksModel::addLink($item);
				
				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_add', array('item' => $item));

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('index') . '&report=link-saved&var=' . urlencode($item['title']) . '&highlight=row-' . $item['id']);
			}
		}
	}
}
