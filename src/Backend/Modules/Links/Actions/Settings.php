<?php

namespace Backend\Modules\Links\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

 use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
 use Backend\Core\Engine\Authentication as BackendAuthentication;
 use Backend\Core\Engine\Model as BackendModel;
 use Backend\Core\Engine\Form as BackendForm;
 use Backend\Core\Engine\Language as BL;

/**
 * This is settings file for the links module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class BackendLinksSettings extends BackendBaseActionEdit
{
	/**
	 * Does the user has God status
	 * 
	 * @var bool
	 */
	protected $isGod = false;
	
	/**
	 * Links settings
	 *
	 * @var	array
	 */
	private $settings = array();
	
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();
		$this->loadForm();
		$this->validateForm();
		$this->parse();
		$this->display();
	}

	/**
	 * load the form
	 */
	private function loadForm()
	{
		// check if user is almighty
		$this->isGod = BackendAuthentication::getUser()->isGod();
		
		// create form instance
		$this->frm = new BackendForm('settings');
		
		// fetch module settings
		$settings = BackendModel::getModuleSettings();
		
		// store settings for this module
		$this->settings = $settings['links'];
		
		// add the formfields
		$this->frm->addCheckbox('autodelete', $this->settings['autodelete']);
		
		// only enable if user is almighty
		if(!$this->isGod) $this->frm->addCheckbox('autodelete')->setAttribute ('disabled', 'disabled');
	}

	/**
	 * Parse the form
	 */
	protected function parse()
	{
		parent::parse();

		// parse additional variables
		$this->tpl->assign('isGod', $this->isGod);
	}

	/**
	 * Validate the form
	 */
	private function validateForm()
	{
		if($this->frm->isSubmitted())
		{
			if($this->frm->isCorrect())
			{
				// get the settings
				$settings = array();
				$settings['autodelete'] = $this->frm->getField('autodelete')->getValue();
				
				// save the new settings
				BackendModel::setModuleSetting($this->getModule(), 'autodelete', $settings['autodelete']);
				
				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_saved_settings');

				// redirect to the settings page
				$this->redirect(BackendModel::createURLForAction('settings') . '&report=saved');
			}
		}
	}
}