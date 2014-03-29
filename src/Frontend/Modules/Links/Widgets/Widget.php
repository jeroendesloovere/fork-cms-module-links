<?php

namespace Frontend\Modules\Links\Widgets;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\Links\Engine\Model as FrontendLinksModel;

/**
 * This is the widget widget
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class Widget extends FrontendBaseWidget
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
		// get the category
		$this->category = FrontendLinksModel::getCategory($this->data['id']);

		if(!empty($this->category))
		{
			// grab all images for the selected category
			$this->links = FrontendLinksModel::getLinksForCategory($this->category['id']);
		}

		else
		{
			$this->links = array();
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