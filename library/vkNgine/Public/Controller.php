<?php

class vkNgine_Public_Controller extends Zend_Controller_Action
{	
	protected $user = null;
	
	public function init()
    {    	
    	parent::init();
    	
		$helper = new vkNgine_View_Helper_PublicUrl();    	
    	$this->view->registerHelper($helper, 'publicUrl');
    		
    	$modelSettings = new Model_Settings();
		$domain = $modelSettings->fetchDefaultSettings();
		
		if ($domain) {
			Zend_Registry::set('domain', $domain);	
		
			$view = Zend_Registry::get('view');
			$this->domain = $domain;
			$view->assign('domain', $domain);
			$view->headTitle($domain->getTitle(), Zend_View_Helper_Placeholder_Container_Abstract::SET);
		}    	 
		
		$modelAlbums = new Model_Albums();
		
		$this->albums = $modelAlbums;
		$view->assign('albums', $modelAlbums);
		
		$albumId = $this->_getParam('album');
		$albumId = (int) $albumId;
		
		if($albumId){
			$album = $modelAlbums->fetch($albumId);
			
			if($album->getPrivacy() == 'PRIVATE') {
				$this->_forward('not-permitted');
			}
		}
		
        if (!vkNgine_Auth::isAuthenticated()) {
        	
        	$session = new Zend_Session_Namespace('redirectUrl');
            
			if (isset($_SERVER['REDIRECT_URL'])) { 
				$session->redirectUrl = $_SERVER['REDIRECT_URL'];	
			}
			
            header("location:/auth/login");
            exit;
        }        
        
	    $user = vkNgine_Public_Auth::revalidate();
	    		
	    Zend_Registry::set('user', $user);
	    $this->view->assign('user', $user);
       		    
    	$this->user = Zend_Registry::get('user');		
		$this->config = Zend_Registry::get('config');
		
        if ($this->user->type == 'STANDARD') {
        	$this->setPublicAcl();
        }
        else {
        	$this->setAdminAcl();
        }
        
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
    	
		parent::init();
    }	
    
	/**
     * set the ACL for public
     */
    private function setPublicAcl()
    {    	
    	$acl = new vkNgine_Public_Acl();
    	
 		Zend_Registry::set('acl', $acl);
    }
    
	/**
     * set the ACL for admin
     */
    private function setAdminAcl()
    {    	
    	$acl = new vkNgine_Admin_Acl();
    	
 		Zend_Registry::set('acl', $acl);
    }
}