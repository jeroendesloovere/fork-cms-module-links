<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * Installer for the links module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class LinksInstaller extends ModuleInstaller
{
	/**
	 * Insert an empty admin dashboard sequence
	 */
	private function insertWidget()
	{
		$invalidLinks = array(
			'column' => 'middle',
			'position' => 2,
			'hidden' => false,
			'present' => true
		);
		
		// insert the dashboardwidget
		$this->insertDashboardWidget('links', 'invalid_links', $invalidLinks);
	}

	/**
	 * Install the module
	 */
	public function install()
	{
		// load install.sql
		$this->importSQL(dirname(__FILE__) . '/data/install.sql');

		// add 'links' as a module
		$this->addModule('links');

		// import locale
		$this->importLocale(dirname(__FILE__) . '/data/locale.xml');

		// module rights
		$this->setModuleRights(1, 'links');

		// action rights
		$this->setActionRights(1, 'links', 'add');
		$this->setActionRights(1, 'links', 'edit');
		$this->setActionRights(1, 'links', 'delete');
		$this->setActionRights(1, 'links', 'add_category');
		$this->setActionRights(1, 'links', 'edit_category');
		$this->setActionRights(1, 'links', 'delete_category');
		$this->setActionRights(1, 'links', 'check_links');

		// set navigation
		$navigationModulesId = $this->setNavigation(null, 'Modules');
		$navigationLinksId = $this->setNavigation($navigationModulesId, 'Links');

		$this->setNavigation($navigationLinksId, 'Overview', 'links/index', array(
			'links/add',
			'links/edit'
		));

		$this->setNavigation($navigationLinksId, 'Categories', 'links/categories', array(
			'links/add_category',
			'links/edit_category'
		));
		
		// settings
		$this->setSetting('links', 'autodelete', false);
		
		// settings navigation
		$navigationSettingsId = $this->setNavigation(null, 'Settings');
		$navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
		$this->setNavigation($navigationModulesId, 'Links', 'links/settings');
		
		// add extra
		$linksID = $this->insertExtra('links', 'block', 'Links', null, null, 'N', 1000);

		// loop languages
		foreach($this->getLanguages() as $language)
		{
			// check if a page for links already exists in this language
			// @todo refactor this if statement
			if((int) $this->getDB()->getVar(
									'SELECT COUNT(id)
									 FROM pages AS p
									 INNER JOIN pages_blocks AS b ON b.revision_id = p.revision_id
									 WHERE b.extra_id = ? AND p.language = ?', 
									array($linksID, $language)) == 0)
			{
				// insert links page
				$this->insertPage(array(
					'title' => 'Links', 
					'type' => 'root', 
					'language' => $language), 
					null, 
					array('extra_id' => $linksID, 'position' => 'main'));
			}
		}
		
		// insert dashboard widgets
		$this->insertWidget();
	}
}