<?php

namespace Backend\Modules\Links\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\DatagridArray as BackendDataGridArray;
use Backend\Core\Engine\DatagridDB as BackendDataGridDB;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Links\Engine\Model as BackendLinksModel;

/**
 * This is the index-action (default), it will display the overview
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class Index extends BackendBaseActionIndex
{
	/**
	 * The dataGrids
	 *
	 * @var	array
	 */
	private $dataGrids, $emptyDatagrid;

	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();
		$this->loadDatagrids();
		$this->parse();
		$this->display();
	}

	/**
	 * Loads the dataGrids
	 */
	private function loadDatagrids()
	{
		// load all categories
		$categories = BackendLinksModel::getCategories();

		// no categories are found
		if(!$categories)
		{
			$this->emptyDatagrid = new BackendDataGridArray(array(
				array(
					'title' => BL::lbl('NoLinksInCategory'), 
					'edit' => ''
				)
			));
		}

		// categories are found
		else
		{
			// loop all categories and create a datagrid containing the links
			foreach($categories as $category)
			{
				// create a datagrid for every category to display its links
				$dataGrid = new BackendDataGridDB(BackendLinksModel::QRY_DATAGRID_BROWSE, 
					array(BL::getWorkingLanguage(), $category['id']));
				$dataGrid->setColumnsHidden(array(
					'id', 
					'language', 
					'category_id', 
					'created_on', 
					'alive'
				));
				$dataGrid->setRowAttributes(array('id' => '[id]'));

				// check if this action is allowed
				if(BackendAuthentication::isAllowedAction('edit'))
				{
					$dataGrid->setColumnURL('title', BackendModel::createURLForAction('edit') . '&amp;id=[id]');

					// add column with edit button
					$dataGrid->addColumn('edit', null, BL::lbl('Edit'),
					BackendModel::createURLForAction('Edit') . '&amp;id=[id]', BL::lbl('Edit'));
				}

				// add dataGrid to list
				$this->dataGrids[] = array(
					'id' => $category['id'], 
					'catname' => $category['title'], 
					'content' => $dataGrid->getContent()
				);

				// set empty datagrid
				$this->emptyDatagrid = new BackendDataGridArray(array(
					array(
						'title' => BL::lbl('NoLinksInCategory'), 
						'edit' => ''
					)
				));
			}
		}
	}

	/**
	 * Parse the dataGrids and the reports
	 */
	protected function parse()
	{
		parent::parse();

		// parse dataGrids
		if(!empty($this->dataGrids)) $this->tpl->assign('dataGrids', $this->dataGrids);
		$this->tpl->assign('emptyDatagrid', $this->emptyDatagrid->getContent());

	}
}
