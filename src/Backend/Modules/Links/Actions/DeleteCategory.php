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

/**
 * This is the delete_category action for the links module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class BackendLinksDeleteCategory extends BackendBaseActionDelete
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
		if($this->id !== null && BackendLinksModel::existsCategory($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get category
			$this->category = BackendLinksModel::getCategoryFromId($this->id);

			// is this category allowed to be deleted?
			if(!BackendLinksModel::deleteCategoryAllowed($this->id))
			{
				$this->redirect(BackendModel::createURLForAction('categories') . '&error=category-not-deletable');
			}
			else
			{
				// define imagePath
				$imagePath = FRONTEND_FILES_PATH . '/links/images';
				
				// delete the images
				$fs = new Filesystem();
				$fs->remove($imagePath . '/source/' . $this->category['logo']);
				$fs->remove($imagePath . '/128x128/' . $this->category['logo']);
				
				// delete the item
				BackendLinksModel::deleteCategoryById($this->id);

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_delete_category', array('id' => $this->id));

				// item was deleted, so redirect
				$this->redirect(BackendModel::createURLForAction('categories') . '&report=category-deleted&var=' . urlencode($this->category['title']));
			}
		}
		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('categories') . '&error=non-existing');
	}
}