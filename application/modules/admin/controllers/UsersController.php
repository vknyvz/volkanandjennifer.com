<?php

class UsersController extends vkNgine_Admin_Controller
{
	public function init()
	{
		parent::init();
		
		$view = Zend_Registry::get('view');
		$view->headTitle('User Management');	

		$acl = Zend_Registry::get('acl');
		if (!$acl->isAllowed('users')) {
			throw new vkNgine_Exception('Permission error');
		}   
	}
	
    public function indexAction()
    {
    	$modelAdminUsers = new Model_Admin_Users();
    	
    	$searchParams = $this->getQueryStringParams();
    	
    	// ordering 
    	$page = $this->_getParam('page', 1);
		$searchParams['page'] = $page;
    	$orderBy = $this->_getParam('orderBy', 'userId');
    	$searchParams['orderBy'] = $orderBy;
    	$orderBySort = $this->_getParam('orderBySort', 'ASC');
    	$searchParams['orderBySort'] = $orderBySort;    	
    	
    	// searching
    	$query = $this->_getParam('query', null);
    	$searchParams['query'] = $query; 
    	$emailSearch = $this->_getParam('emailSearch', null);
    	$searchParams['emailSearch'] = $emailSearch;  
    	$name = $this->_getParam('name', null);
    	$searchParams['name'] = $name;
    	$active = $this->_getParam('active', null);
    	$searchParams['active'] = $active;   
    	$dateInsertedFrom = $this->_getParam('dateInsertedFrom', null);
    	$searchParams['dateInsertedFrom'] = $dateInsertedFrom;
    	$dateInsertedTo = $this->_getParam('dateInsertedTo', null);
    	$searchParams['dateInsertedTo'] = $dateInsertedTo;
    	$dateLastLoginFrom = $this->_getParam('dateLastLoginFrom', null);
    	$searchParams['dateLastLoginFrom'] = $dateLastLoginFrom;
    	$dateLastLoginTo = $this->_getParam('dateLastLoginTo', null);
    	$searchParams['dateLastLoginTo'] = $dateLastLoginTo;
    	
    	$level = array();
		foreach ($modelAdminUsers->fetchAll() as $user) {
			$userInfo = $modelAdminUsers->fetch($user['userId']);
			$level[$user['userId']] = $userInfo->level;			
		}
		
    	$this->view->level = $level;
    	$this->view->params = $searchParams;    	
    	$this->view->users = $modelAdminUsers->fetchAllWithPagination($page, $orderBy, $orderBySort, $searchParams);
    }	
    
    public function editAction()
    {   
    	$modelAdminUsers = new Model_Admin_Users();
    	
    	$form = self::getAdminUsersAddForm();    
    	
    	$userId = $this->_getParam('userId');
		$userId = (int) $userId;
		
    	if($userId) {
			$populateData = array();
			
			$user = $modelAdminUsers->fetch($userId);
			
			if (count($user) > 0) {					
				$populateData = $user->toArray();
				$populateData['password'] = null;
				$populateData['level'] = $user->level;				
			}
			
			$form->adminMode($user['email']);
			$form->setHidden($userId);
	    	$form->populate($populateData);
	    	$this->view->userId = $userId;
		}
		
    	$request = $this->getRequest();
    	
    	if ($request->isPost()) {
			$post = $request->getPost();
		
			if ($form->isValid($post)) {
		        $values = $form->getValues();		        
		        
		        if($userId) {
		        	
		        	if($values['password'] == false) {
		        		unset($values['password']);
		        	}
		        	else {
		        		$values['password'] = md5($values['password']);
		        	}
		        	
		        	$values['zip'] = $values['zip'] ? $values['zip'] : null;
		        	
		        	$modelAdminUsers->update($userId, $values);
		        }
		        else {
		        	$modelAdminUsers->insert($values);
		        }
		        $this->view->infoMessage = 'User was added successfully';

		        $this->_helper->redirector('index');
			}
		}	 
    	
    	$this->view->errors = $form->errors();
    	$this->view->form = $form;    	
    }
    
    public function emailAction()
    {
    	$settings = Zend_Registry::get('settings');
    	
	    $type = $this->_getParam('type');
	    $userId = $this->_getParam('userId');
	    $userId = (int) $userId;
	    
	    $modelUsers = $modelUsers = new Model_Users();
	    
    	$user = $modelUsers->fetch($userId);
    	
    	if(!$user instanceof Model_User) { 
    		throw new vkNgine_Exception('User not found!'); 
		}
		
		$modelUsersTokens = new Model_Users_Tokens();
		$token = $modelUsersTokens->add($user);

		$modelSettings = new Model_Settings();
		$siteSettings = $modelSettings->fetchDefaultSettings();
		
		if($type == 'FORGOT_PASS') {
			$params = array (    
				'FULL_NAME'	 => $user->getFullName(),				
	    		'RESET_LINK' => $siteSettings->getAddress() . 'support/resetpassword/token/' . $token
	    	);			
		}
		if($type == 'REGISTER') {
			$params = array (	
				'FULL_NAME'	 => $user->getFullName(),
				'SITE_NAME'	 => $siteSettings['title']
			);
		}		
		    	
    	$modelEmails = new Model_Emails();
		$modelEmails->sendToUser($user, $type, $params);
		
		$this->_helper->layout->disableLayout();
		exit;
    }
    
    public function searchAction()
    {
    	$form = self::getAdminUserSearch();
    	
    	$this->view->form = $form;  
	    $this->_helper->layout->disableLayout();
    }
    
	public function searchAutoAction()
    {    	
    	$query = $this->_getParam('term');
    	
    	$modelUsers = new Model_Users();
    	
    	$users = $modelUsers->fetchAuto($query);
    	
    	$response = array();
    	foreach ($users as $user) {    		
    		$returnArray['id'] 	  	= $user['userId']; 
			$returnArray['label'] 	= $user['firstName'] . ' ' . $user['lastName'];
			$returnArray['value'] 	= $user['firstName'] . ' ' . $user['lastName'];
			
			array_push($response, $returnArray);
    	}
    	
		echo Zend_Json::encode($response);
    	exit;
    }
    
    public function deleteAction()
    {    	
    	$modelUsers = new Model_Users();
    	
    	$userIds = $this->_getParam('userIds');
    	
    	$userIdsArray = explode(',', $userIds);
    	    
    	foreach($userIdsArray as $userId) {
    		if ($userId) {
								
				$user = $modelUsers->fetch($userId);
    	
		    	if(!$user instanceof Model_User) { 
		    		throw new vkNgine_Exception('User not found!'); 
				}
						
				$modelUsers->delete($userId);
			}	
		}		
    }
    
	private function getAdminUsersAddForm()
    {
    	$form = new Model_Admin_Users_Add_Form(array(
			'method' => 'post',
			'action' => $this->_helper->url('add', 'users'),
		));		
		
		$form->setStates();
		
		return $form;
    } 
    
    private function getAdminUserSearch()
    {
    	$form = new Model_Admin_Users_Search_Form(array(
			'method' => 'post',
    		'class' => 'search_form general_form',
			'action' => $this->_helper->url(null, 'users'),
		));		
		
		return $form;
    }
}