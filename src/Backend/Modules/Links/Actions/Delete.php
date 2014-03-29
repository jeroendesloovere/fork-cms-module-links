<?php

namespace Backend\Modules\Links\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Links\Engine\Model as BackendLinksModel;

/**
 * This is the delete action for the links module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class Delete extends BackendBaseActionDelete
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
			
			// trigger event
			BackendModel::triggerEvent($this->getModule(), 'after_delete', array('id' => $this->id));

			// item was deleted, so redirect
			$this->redirect(BackendModel::createURLForAction('index') . '&report=link-deleted');
		}

		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}
}