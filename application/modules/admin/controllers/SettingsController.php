<?php

class SettingsController extends vkNgine_Admin_Controller
{
	public function init()
	{
		parent::init();
		
		$view = Zend_Registry::get('view');
		$view->headTitle('Website Settings');	
		
		$acl = Zend_Registry::get('acl');
		if (!$acl->isAllowed('settings')) {
			throw new vkNgine_Exception('Permission error');
		} 
	}
	
    public function indexAction()
    {    	 	
    	$form = self::getGeneralSettingsForm();    
    	
    	self::saveSettings($form);
    	
    	$this->view->errors = $form->errors();
    	$this->view->form = $form;
    }	
    
    private function saveSettings($form)
    {    	
    	$modelSettings = new Model_Settings();
    	    	    	
    	$settingId = 1;
    	$settings = $modelSettings->fetch($settingId);
    	
    	$populateData = array();
    	
		if (count($settings) > 0) {
			$populateData = $settings->toArray();	
		}
		
		$form->populate($populateData);
    	
    	$request = $this->getRequest();
    	
    	if ($request->isPost()) {
			$post = $request->getPost();
		
			if ($form->isValid($post)) {
		        $values = $form->getValues();		        
		        
		    	$modelSettings->update($settingId, $values);
				
		    	$this->view->infoMessage = 'Web site settings were updated successfully';		    	
			}
		}	    	
    }
    
    private function getGeneralSettingsForm()
    {    	
    	$form = new Model_Admin_Website_Settings_Form(array(
			'method' => 'post',
			'action' => $this->_helper->url(null, 'settings'),
		));		
    	
		return $form;
    }    
}