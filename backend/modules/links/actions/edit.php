<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the edit action for the links module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class BackendLinksEdit extends BackendBaseActionEdit
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
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exists
		if($this->id !== null && BackendLinksModel::existsLink($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get all data for the item we want to edit
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

		// no item found, throw an exception, because somebody is fucking with our URL
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}

	/**
	 * Get the data for a question
	 *
	 * @return void
	 */
	private function getData()
	{
		// get the record
		$this->record = BackendLinksModel::getLinkById($this->id);

		// get categories
		$this->categories = BackendLinksModel::getCategoriesForDropdown();
	}

	/**
	 * Load the form
	 *
	 * @return void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('edit');

		// set hidden values
		$rbtHiddenValues = array();
		$rbtHiddenValues[] = array(
			'label' => BL::lbl('Hidden'), 
			'value' => 'Y'
		);
		$rbtHiddenValues[] = array(
			'label' => BL::lbl('Published'), 
			'value' => 'N' 
		);

		// protocols
		$protocols = array(
			'http://' => 'http://', 
			'https://' => 'https://', 
			'news://' => 'news://', 
			'ftp://' => 'ftp://'
		);

		// create elements
		$this->frm->addText('title', $this->record['title'])->setAttribute('id', 'title');
		$this->frm->getField('title')->setAttribute('class', 'title ' . $this->frm->getField('title')->getAttribute('class'));
		$this->frm->addText('url', $this->record['url'])->setAttribute('id', 'title');
		$this->frm->addText('description', $this->record['description'])->setAttribute('id', 'title');
		$this->frm->getField('description')->setAttribute('class', 'title ' . $this->frm->getField('description')->getAttribute('class'));
		$this->frm->addDropdown('categories', $this->categories, $this->record['category_id']);
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, $this->record['hidden']);
		$this->frm->addDropdown('protocol', $protocols, $this->record['protocol']);
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

		// assign the active record and additional variables
		$this->tpl->assign('item', $this->record);

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
			$this->frm->getField('description')->isFilled(BL::err('DescriptionIsRequired'));
			$this->frm->getField('categories')->isFilled(BL::err('CategoryIsRequired'));
			$this->frm->getField('protocol')->isFilled(BL::err('ProtocolIsRequired'));

			// no errors?
			if($this->frm->isCorrect())
			{
				// build item
				$item = array();
				$item['id'] = (int) $this->id;
				$item['language'] = (string) $this->record['language'];
				$item['category_id'] = (string) $this->frm->getField('categories')->getValue();
				$item['protocol'] = (string) $this->frm->getField('protocol')->getValue();
				$item['url'] = (string) $this->frm->getField('url')->getValue();
				$item['title'] = (string) $this->frm->getField('title')->getValue();
				$item['description'] = (string) $this->frm->getField('description')->getValue(true);
				$item['hidden'] = (string) $this->frm->getField('hidden')->getValue();

				// update link values in database
				$update = BackendLinksModel::updateLink($item);

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('index') . '&report=link-saved&var=' . urlencode($item['title']) . '&highlight=row-' . $update);
			}
		}
	}
}