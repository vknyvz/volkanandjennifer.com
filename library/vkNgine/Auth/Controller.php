<?php
class vkNgine_Auth_Controller extends Zend_Controller_Action
{
    public function init()
    {    	
        $view = Zend_Registry::get('view');
		
		$helper = new vkNgine_View_Helper_PublicUrl();    	
    	$this->view->registerHelper($helper, 'publicUrl');
    	
        $modelSettings = new Model_Settings();
		$domain = $modelSettings->fetchDefaultSettings();
		
		if ($domain) {
			Zend_Registry::set('domain', $domain);	
		
			$this->domain = $domain;
			$view->assign('domain', $domain);
			$view->headTitle($domain->getTitle(), Zend_View_Helper_Placeholder_Container_Abstract::SET);
		}   
		
    	parent::init();
    }
}