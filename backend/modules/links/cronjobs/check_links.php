<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This cronjob will check the database for broken links
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class BackendLinksCronjobCheckLinks extends BackendBaseCronjob
{
	/**
	 * The links
	 * 
	 * @var array
	 */
	private $links;
	
	/**
	 * The autodelete status
	 * 
	 * @var bool
	 */
	private $deleteSetting;
	
	/**
	 * Check if all links are valid
	 */
	private function checkLinks()
	{
		foreach($this->links AS $link)
		{
			// check if links is active
			$result = BackendLinksModel::urlExists($link['url']);
			
			if(!$result)
			{
				// check if autodelete is enabled
				$autodelete = BackendModel::getModuleSetting($this->module(), 'autodelete');
				
				// delete link if autodelete is enabled
				if($autodelete)
				{
					// delete the link when it's allowed to be deleted
					if(BackendLinksModel::deleteLinkAllowed($link['id']))
					{
						BackendLinksModel::deleteLinkById($link['id']);
					}
				}
				
				else
				{
					// report the link as dead when autodelete is disabled
					$item = array();
					$item['id'] = (int) $link['id'];
					$item['language'] = (string) $link['language'];
					$item['category_id'] = (string) $link['category_id'];
					$item['url'] = (string) $link['url'];
					$item['title'] = (string) $link['title'];
					$item['description'] = (string) $link['description'];
					$item['hidden'] = (string) $link['hidden'];
					$item['alive'] = (string) 'N';
				
					// now the item is built, update the database
					BackendLinksModel::updateLink($item);
				}
			}
		}
		
		// delete busy status
		$this->clearBusyFile();
	}

	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();
		
		// check if this cronjob is allowed to run
		$this->deleteSetting = BackendModel::getModuleSetting('links', 'autodelete');
		
		// run the job because deleteSetting is TRUE
		if($this->deleteSetting)
		{
			// load links
			$this->getData();
		
			// check links
			$this->checkLinks();
		}
	}

	/**
	 * Get the data
	 * 
	 * @return void
	 */
	private function getData()
	{
		// set to busy
		$this->setBusyFile();
		
		// init var
		$this->links = array();
		
		// get all links from DB
		$this->links = BackendLinksModel::getLinks();
	}
}