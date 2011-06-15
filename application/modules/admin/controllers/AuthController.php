<?php

class AuthController extends Zend_Controller_Action
{
    public function init()
    {
    	parent::init();
    	
		// preload helpers
		$helper = new vkNgine_View_Helper_AdminUrl();    	
    	$this->view->registerHelper($helper, 'adminUrl');
    	    	
    	$this->config = Zend_Registry::get('config');
		$this->view->headTitle('Master Admin')->setSeparator(' - ');
    	
       	$this->_helper->layout->setLayout('layout-auth');    	
    }
    
    public function indexAction()
    {
        $this->_redirect("/auth/login");
		exit;
    }

    public function loginAction()
    {
    	$form = $this->getAdminLoginForm();
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {    		
    		$username = $this->_getParam('username');
    		$password = $this->_getParam('password');
    		$remember = $this->_getParam('remember');
    		
    		if ((!empty($username)) && (!empty($password))) { 
    			$info = array (
    				'username' => $username,
    				'password' => $password,
    				'remember' => $remember
    			);
    			
    			if (vkNgine_Admin_Auth::attemptLogin($info)) {
    				
    				$this->user = vkNgine_Admin_Auth::revalidate();
    				
    				// remember me feature
    				if(isset($info['remember']) and ($info['remember'])) {
	            		$config = Zend_Registry::get('config');
		        		if(isset($config->login->remember)) {
    						$rememberMeHowLong = $config->login->remember;
		        		}
    					else {
    						$rememberMeHowLong = 60 * 60 * 24 * 14; // 14 days
    					}
		    			Zend_Session::rememberMe($rememberMeHowLong);
	        		} else {
		        		Zend_Session::forgetMe();
	        		}
    				
    				// log all activity
    				$logger = Zend_Registry::get('logger');
    				$logger->log('ADMIN_LOGIN_REQUEST', print_r($info, true), vkNgine_Log::INFO, $this->user['userId']);
    				
    				$modelTrafficLogins = new Model_Traffic_Logins();
    				$modelTrafficLogins->insertTrafficLogin($this->user['userId'], 'ADMIN');

    				$modelTrafficActivity = new Model_Traffic_Activity();
    				$modelTrafficActivity->processActivity($this->user, $request, 'Logged in to Admin Panel');
    				
    				$modelUsers = new Model_Admin_Users();
					$modelUsers->update($this->user['userId'], array('lastLogin' => date('Y-m-d H:i:s')));
    									
    				return $this->_helper->redirector('index', 'index');
    			}
    			else
    			{
    				$this->view->failureMessage = 'Wrong Login/Password!';
    			}
    		}
    		else
    		{
    			$this->view->failureMessage = 'Username or Password is Invalid!';
    		}
    	}    

    	$this->view->form = $form;
    }
    
    private function getAdminLoginForm()   
	{
		$form = new Model_Admin_Login_Form(array(
			'method' => 'post',
			'action' => $this->_helper->url('login', 'auth'),
		));		
    	
		return $form;
	}  
    
    public function logoutAction()
    {
    	Zend_Auth::getInstance()->clearIdentity();
        	
		return $this->_helper->redirector('login'); 
    }    
}