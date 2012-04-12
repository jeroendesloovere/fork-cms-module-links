<?php
/**
 * This is the add category action for the links module
 *
 * @package		backend
 * @subpackage	links
 *
 * @author		John Poelman <john.poelman@bloobz.be>
 * @since		1.0.0
 */
class BackendLinksAddCategory extends BackendBaseActionAdd
{
	/**
	 * Execute the action
	 *
	 * @return	void
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
	 * @return	void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('add_category');

		// create elements
		$this->frm->addText('title');
	}

	/**
	 * Validate the form
	 *
	 * @return	void
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
				$category['language'] = BL::getWorkingLanguage();
				$category['title'] = (string) $this->frm->getField('title')->getValue();
				
				// first, insert the category
				$cat_id = BackendLinksModel::insertCategory($category);
				
				if ($cat_id)
					{
						// then build the widget array...
						$widget['module'] 	= (string) $this->getModule();
						$widget['type']		= (string) 'widget';
						$widget['label']	= (string) BackendLinksModel::createWidgetLabel($category['title']);
						$widget['action']	= (string) 'widget';
						$widget['hidden']	= (string) 'N';
						$widget['data'] 	= (string) serialize(array('id' => $cat_id));
						
						// ...to save it in the database
						$widgetID	= BackendLinksModel::insertWidget($widget);
						
						if ($widgetID)	
							{
								// then build the locale array ...
								$locale['user_id']		= (int) '1';
								$locale['language']		= (string) BL::getWorkingLanguage();
								$locale['application']	= (string) 'backend';
								$locale['module']		= (string) 'pages';
								$locale['type']			= (string) 'lbl';
								$locale['name']			= (string)BackendLinksModel::createWidgetLabel($category['title']);
								$locale['value']		= (string) $category['title'];
								$locale['edited_on']	= BackendModel::getUTCDate();
								
								// ... and store it
								$localeID = BackendLocaleModel::insert($locale);
								
								// build the ids array...
								$ids['category_id']		= (int) $cat_id;
								$ids['widget_id']		= (int) $widgetID;
								$ids['locale_id']		= (int) $localeID;
								
								// ... and store it
								$stored = BackendLinksModel::storeAllIds($ids);
								
								// everything is saved, so redirect to the overview
								$this->redirect(BackendModel::createURLForAction('categories') . '&report=added-category&var=' . 
								urlencode($category['title']) . '&highlight=row-' . 			$category['id']);
							}
					}
			}
		}
	}
}
