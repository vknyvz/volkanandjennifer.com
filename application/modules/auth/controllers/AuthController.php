<?php
class AuthController extends vkNgine_Auth_Controller
{
	private $loggedin = false;
	
    public function init()
    {
    	parent::init();
    	
    	$this->config = Zend_Registry::get('config');    	
    }
    
    public function indexAction()
    {
        $this->_redirect("/auth/login");
		exit;
    }

	public function preDispatch()
	{
		if ('logout' == $this->getRequest()->getActionName()) {
			if (!Zend_Auth::getInstance()->hasIdentity()) {
				$this->_helper->redirector('index');
			}
		}
	}    
    
    public function loginAction()
    {    	
    	if (vkNgine_Auth::isAuthenticated()){
            header("location:/dashboard");
            exit;
        }
    	
    	$logger = Zend_Registry::get('logger');
    	
    	$form = $this->getLoginForm();    
		$request = $this->getRequest();
		
    	if ($request->isPost()) {
    		if ($form->isValid($request->getPost())){
    			$info = $form->getValues();
				
				$user = null;
    			if (vkNgine_Public_Auth::attemptLogin($info)){    				
    				$user = vkNgine_Auth::revalidate();    				
    			} else if (vkNgine_Admin_Auth::attemptLogin($info)){
    				$user = vkNgine_Auth::revalidate();    				
    			} else {
    				echo Zend_Json::encode(array('error' => 'Wrong Email Address or Password!', 'success' => 0));
    				exit;
    			}
    			
    			$user = vkNgine_Auth::revalidate();
    			
    			$logger->log('LOGIN_REQUEST', print_r($info, true), vkNgine_Log::INFO, $user['userId']);
    			
    			if ($user != null) { 
    				 				
	            	if(isset($info['remember']) AND ($info['remember'])) {
	            		$config = Zend_Registry::get('config');
		        		if(isset($config->login->remember))
    						$rememberMeHowLong = $config->login->remember;
    					else
    						$rememberMeHowLong = 60 * 60 * 24 * 14; // 14 days    						
		    			Zend_Session::rememberMe($rememberMeHowLong);
	        		} else
		        		Zend_Session::forgetMe();
    				    			  	
    				$modelUsers = new Model_Users();    				
					$modelTrafficActivity = new Model_Traffic_Activity();
					$modelTrafficLogins = new Model_Traffic_Logins();
					
					$modelTrafficActivity->processActivity($user, $request, 'Logged in to Site');
   					$modelTrafficLogins->insertTrafficLogin($user->userId, $user->type); 

					$modelUsers->update($user['userId'], array('lastLogin' => date('Y-m-d H:i:s')));	
					
					$session = new Zend_Session_Namespace('redirectUrl');
									
					if (isset($session->redirectUrl)) {
						$redirectUrl = $session->redirectUrl;
						unset($session->redirectUrl);
						$this->_redirect($redirectUrl);
						echo Zend_Json::encode(array('redirectUrl' => $redirectUrl, 'success' => 1)); 
						exit;   					 
					} else {
						echo Zend_Json::encode(array('redirectUrl' => '/dashboard', 'success' => 1));
						exit;   
					}
					
				}
	    		else {
	    			echo Zend_Json::encode(array('error' => 'Wrong Email Address or Password!', 'success' => 0));
	    			exit;
	    		}
    		}
    		else {
    			echo Zend_Json::encode(array('error' => 'Wrong Email Address or Password!', 'success' => 0));
    			exit;
    		}
    	}
    	
    	$pageVars = array('body' 	 	=> 'body_contact',    					  
    					  'background' 	=> null	);   
    	 	
    	$this->view->pageVars = $pageVars;
    	$this->view->form = $form;
    }

    public function logoutAction()
    {
		Zend_Auth::getInstance()->clearIdentity();
		
		return $this->_helper->redirector('login');
    }
	
    private function getLoginForm()
    {
    	$form = new Model_Login_Form(array(
    		'method' => 'post',
    		'action' => $this->_helper->url('login'),
    	));
    	    	
    	return $form;
    }
}
