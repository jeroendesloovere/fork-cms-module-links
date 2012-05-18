<?php

/*
 * This file is part of Fork CMS.
 * 
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the index -action
 * 
 * @author John Poelman <john.poelman@bloobz.be>
 */
class FrontendLinksIndex extends FrontendBaseBlock
{
	/**
	 * The categories
	 *
	 * @var	array
	 */
	private $categories;

	/**
	 * Execute the extra
	 *
	 * @return void
	 */
	public function execute()
	{
		// call the parent
		parent::execute();

		// load template
		$this->loadTemplate();

		// load the data
		$this->getData();

		// parse
		$this->parse();
	}

	/**
	 * Load the data, don't forget to validate the incoming data
	 *
	 * @return void
	 */
	private function getData()
	{	
		// make sure $this->categories is an array
		$this->categories = array();
		
		// get the categories
		$categories = FrontendLinksModel::getCategories();
				
		// grab links for the categories
		foreach($categories as $category)
		{
			// get links for category
			$category['links'] = FrontendLinksModel::getLinksForCategory($category['id']);
			$this->categories[] = (array) $category;
		}
	}

	/**
	 * Parse the data into the template
	 *
	 * @return void
	 */
	private function parse()
	{
		// assign links
		$this->tpl->assign('categories', $this->categories);
	}
}