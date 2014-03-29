<?php

namespace Backend\Modules\Links\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\DatagridDB as BackendDataGridDB;
use Backend\Core\Engine\DatagridFunctions as BackendDataGridFunctions;
use Backend\Modules\Links\Engine\Model as BackendLinksModel;

/**
 * This is the configuration-object for the slideshow module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class Categories extends BackendBaseActionIndex
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// load datagrids
		$this->loadDataGrid();

		// parse page
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * Loads the datagrid
	 *
	 * @return void
	 */
	private function loadDataGrid()
	{
		// create datagrid
		$this->dataGrid = new BackendDataGridDB(BackendLinksModel::QRY_DATAGRID_CAT, BL::getWorkingLanguage());

		// disable paging
		$this->dataGrid->setPaging(false);

		// set hidden columns
		$this->dataGrid->setColumnsHidden(array('language', 'sequence', 'extra_id'));

		// set column URLs
		$this->dataGrid->setColumnURL('title', BackendModel::createURLForAction('edit_category') . '&amp;id=[id]');

		// add drag and dropp stuff
		$this->dataGrid->enableSequenceByDragAndDrop();

		// add columns
		$this->dataGrid->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_category') . '&amp;id=[id]', BL::lbl('Edit'));
	}

	/**
	 * Parse & display the page
	 *
	 * @return void
	 */
	protected function parse()
	{
		$this->tpl->assign('dataGrid', ($this->dataGrid->getNumResults() != 0) ? $this->dataGrid->getContent() : false);
	}
}