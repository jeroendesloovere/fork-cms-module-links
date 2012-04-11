<?php

/**
 * This is the widget widget
 *
 * @package		frontend
 * @subpackage	links
 *
 * @author		John Poelman <john.poelman@bloobz.be>
 * @since		2.0.0
 */

class FrontendLinksWidgetWidget extends FrontendBaseWidget
{
	
	/**
 	* The links
 	*
 	* @var	array
 	*/
	private $links;

	/**
 	* The category
 	*
 	* @var	array
 	*/
	private $category;

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
		//get the category
		$this->category = FrontendLinksModel::getCategory($this->data['id']);
		
		if (!empty($this->category))
			{
				//grab all images for the selected category
				$this->links = FrontendLinksModel::getLinksForCategory($this->category['id']);
			}
		
		else
			{
				$category = array();
			}
	}
	
	/**
 	* Parse the data into the template
 	*
 	* @return	void
 	*/
	private function parse()
	{
		$this->tpl->assign('category', $this->category);
		$this->tpl->assign('links', $this->links);
	}
}
