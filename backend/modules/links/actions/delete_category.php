<?php

/**
 * This is the delete_category action for the links module
 *
 * @package		backend
 * @subpackage	links
 *
 * @author		John Poelman <john.poelman@bloobz.be>
 * @since		1.0.0
 */
class BackendLinksDeleteCategory extends BackendBaseActionDelete
{
	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exist
		if($this->id !== null && BackendLinksModel::existsCategory($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get category
			$this->record = BackendLinksModel::getCategoryFromId($this->id);
			
			//get id from the locale and widget
			$ids		= BackendLinksModel::getExtraIdsForCategory($this->id);
			$localeID 	= array($ids['locale_id']); //BackendLocaleModel::delete needs an array to function
			
		//delete all stuff
			
			//delete the category
				BackendLinksModel::deleteCategoryById($this->id);
				
			//delete the widget
				BackendLinksModel::deleteWidgetById($ids['widget_id']);
				
			//delete the locale
				BackendLocaleModel::delete($localeID);
				
			//delete the id's
				BackendLinksModel::deleteIdsByCatId($this->id);
				
			// item was deleted, so redirect
			$this->redirect(BackendModel::createURLForAction('categories') . '&report=deleted&var=' . urlencode($this->record['title']));
		}

		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('categories') . '&error=non-existing');
	}
}

?>