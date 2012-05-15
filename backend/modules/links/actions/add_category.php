<?php
/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the add category action for the links module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class BackendLinksAddCategory extends BackendBaseActionAdd
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

		// load the form
		$this->loadForm();

		// validate the form
		$this->validateForm();

		// parse
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * Load the form
	 *
	 * @return void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('add_category');

		// set hidden values
                $rbtHiddenValues = array();
		$rbtHiddenValues[] = array('label' => BL::lbl('Hidden', $this->URL->getModule()), 'value' => 'Y');
		$rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

		// create elements
		$this->frm->addText('title');
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, 'N');
	}

	/**
	 * Validate the form
	 *
	 * @return void
	 */
	private function validateForm()
	{
		// is the form submitted?
		if($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// validate fields
			$this->frm->getField('title')->isFilled(BL::err('TitleIsRequired'));

			// no errors?
			if($this->frm->isCorrect())
			{
				// build category array
                                $item = array();
                                $item['language'] = BL::getWorkingLanguage();
				$item['title'] = (string) $this->frm->getField('title')->getValue();
				$item['sequence'] = (int) BackendLinksModel::getMaximumCategorySequence() + 1;
				$item['hidden'] = (string) $this->frm->getField('hidden')->getValue();

                                //insert the item
                                $insert = BackendLinksModel::insertCategory($item);

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('categories') . '&report=added-category&var=' . urlencode($item['title']) . '&highlight=row-' . $insert);
			}
		}
	}
}