<?php

/**
 * This is the delete action for the links module
 *
 * @package backend
 * @subpackage links
 *
 * @author John Poelman <john.poelman@bloobz.be>
 * @since 1.0.0
 */
class BackendLinksDelete extends BackendBaseActionDelete
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

		// does the item exist
		if($this->id !== null && BackendLinksModel::existsLink($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get item
			$this->record = BackendLinksModel::getLinkById($this->id);

			// delete item
			BackendLinksModel::deleteLinkById($this->id);

			// item was deleted, so redirect
			$this->redirect(BackendModel::createURLForAction('index') . '&report=link-deleted');
		}

		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}
}
