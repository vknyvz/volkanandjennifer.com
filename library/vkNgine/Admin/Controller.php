<?php

class vkNgine_Admin_Controller extends Zend_Controller_Action
{	
	private $acl;
	protected $user = null;
	
    public function init()
    {
    	parent::init();
    	
 		$view = Zend_Registry::get('view');
		$view->headTitle('Master Admin', Zend_View_Helper_Placeholder_Container_Abstract::SET);
    	
		$helper = new vkNgine_View_Helper_AdminUrl();    	
    	$this->view->registerHelper($helper, 'adminUrl');
    	
        $helper = new vkNgine_View_Helper_Dateformat();
        $this->view->registerHelper($helper, 'dateFormat');
        
        $helper = new vkNgine_View_Helper_FileSizeFormat();
        $this->view->registerHelper($helper, 'fileSizeFormat');
        
        if (!vkNgine_Auth::isAuthenticated()) {
            header("location:/auth/login");
            exit;
        }        
        
	    $user = vkNgine_Admin_Auth::revalidate();
	    		
	    Zend_Registry::set('user', $user);
	    $this->view->assign('user', $user);
       		    
    	$this->user = Zend_Registry::get('user');		
		$this->config = Zend_Registry::get('config');
		
		$modelTrafficLogins = new Model_Traffic_Logins(); 
		$lastLoggedInInfo = $modelTrafficLogins->fetchLastLoggedInInfo($this->user);
		$this->view->assign('lastLoggedInInfo', $lastLoggedInInfo);
		
		if (!vkNgine_Admin_Auth::isAuthenticated())
		{
	        header("location:/auth/login");
	        exit;
		}			
		
		$this->view->action = array (
			'controller' => $this->_request->controller,
			'action' 	 => $this->_request->action
		);
				
		$acl = new vkNgine_Admin_Acl();
		$this->acl = $acl;
		Zend_Registry::set('acl', $acl);
				
		parent::init();
    }
    
    /**
     * get all params in the query string
     */
    public function getQueryStringParams() 
    {
    	$params = $this->getRequest()->getParams();
    	unset($params['module']);
    	unset($params['controller']);
    	unset($params['action']);
    	
    	return $params;
    }  
}