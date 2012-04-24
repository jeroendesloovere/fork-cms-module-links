<?php

/**
 * This is the edit_category action for the links module
 *
 * @package backend
 * @subpackage links
 *
 * @author John Poelman <john.poelman@bloobz.be>
 * @since 1.0.0
 */
class BackendLinksEditCategory extends BackendBaseActionEdit
{
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
		else $this->redirect(BackendModel::createURLForAction('categories') . '&error=non-existing');
	}

	/**
	 * Get the data
	 *
	 * @return void
	 */
	private function getData()
	{
		$this->record 	= BackendLinksModel::getCategoryFromId($this->id);
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
		$rbtHiddenValues[] = array('label' => BL::lbl('Hidden'), 'value' => 'Y');
		$rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');
		
		// create elements
		$this->frm->addText('title', $this->record['title']);
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, $this->record['hidden']);
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
		$this->tpl->assign('category', $this->record);

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
				$category['id'] = (int) $this->id;
				$category['title'] = (string) $this->frm->getField('title')->getValue();
				$category['language'] = (string) BL::getWorkingLanguage();
				$category['hidden'] = (string) $this->frm->getField('hidden')->getValue();
				
				// ... then, update the category
				$category_update = BackendLinksModel::updateCategory($category);
				
				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_edit_category', array('item' => $category));
				
				// get extra ids for this category
				$ids = BackendLinksModel::getExtraIdsForCategory($this->id);
					
				// let's build the widget array
				$widget['id']		= (int) $ids['widget_id'];
				$widget['label']	= (string) BackendLinksModel::createWidgetLabel($category['title']);
				$widget['module'] 	= (string) $this->getModule();
				$widget['type']		= (string) 'widget';
				$widget['action']	= (string) 'widget';
				$widget['hidden']	= (string) $category['hidden'];
				$widget['data'] 	= (string) serialize(array('id' => $this->id));
						
				// update the widget
				$update_widget = BackendLinksModel::updateWidget($widget);
			
				// now we'll be building the locale array
				$locale['id']			= (int) $ids['locale_id'];
				$locale['name']			= (string) BackendLinksModel::createWidgetLabel($category['title']);
				$locale['value']		= (string) $category['title'];
				$locale['edited_on']	= BackendModel::getUTCDate();
				$locale['user_id']		= (int) '1';
				$locale['language']		= (string) BL::getWorkingLanguage();
				$locale['application']	= (string) 'backend';
				$locale['module']		= (string) 'pages';
				$locale['type']			= (string) 'lbl';
												
				// ...and store it
				$updatelocale	= BackendLocaleModel::update($locale);
				$buildcache		= BackendLocaleModel::buildCache(BL::getWorkingLanguage(),$locale['application']);
		
				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('categories') . '&report=edited-category&var=' . urlencode($category['title']) . '&highlight=row-' . $category['id']);			
			}	
		}
	}
}
