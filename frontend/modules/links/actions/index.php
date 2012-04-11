<?php

/**
 * This is the index -action
 *
 * @package		frontend
 * @subpackage	links
 *
 * @author		John Poelman <john.poelman@bloobz.be>
 * @since		1.0.0
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
	 * @return	void
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
	 * @return	void
	 */
	private function getData()
	{
		//load al categories
		$categories = FrontendLinksModel::getCategories();
		
		//no categories found? 
		if(empty($categories)) 
		
		{
			$this->categories = array();
		}
		
		else
		{
			//if there are categories found, grab their links!
				foreach ($categories as $cat )
					{
						$cat['links'] = FrontendLinksModel::getLinksForCategory($cat['id']);
						$this->categories[] = $cat;
						
					}
			}
	}
	
	/**
	 * Parse the data into the template
	 *
	 * @return	void
	 */
	private function parse()
	{
		// assign links
		$this->tpl->assign('categories', $this->categories);
		//spoon::dump($this->categories);
	}
}

?>