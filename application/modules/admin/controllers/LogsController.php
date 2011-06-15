<?php
class LogsController extends vkNgine_Admin_Controller
{
	public function init()
	{
		parent::init();
		
		$view = Zend_Registry::get('view');
		$view->headTitle('Logs');

		$acl = Zend_Registry::get('acl');
		if (!$acl->isAllowed('logs')) {
			throw new vkNgine_Exception('Permission error');
		} 
	}
	
	public function indexAction()
    {
    	$this->_forward('framework');     	
    }
    
    public function frameworkAction()
    {
    	$modelLogs = new Model_vkNgine_Application_Log();
    	
    	$searchParams = $this->getQueryStringParams();
    	
    	// ordering 
    	$page = $this->_getParam('page', 1);		
    	$orderBy = $this->_getParam('orderBy', 'dateInserted');
    	$searchParams['orderBy'] = $orderBy;
    	$orderBySort = $this->_getParam('orderBySort', 'DESC');
    	$searchParams['orderBySort'] = $orderBySort;    	
    	
    	$searchParams['action'] = 'framework';
    	
    	$this->view->params = $searchParams;
    	$this->view->logs = $modelLogs->fetchAllWithPagination($page, $orderBy, $orderBySort);
    }
    
	public function vkngineAction()
    {
    	$modelTrafficLog = new Model_Traffic_Log();
    	
    	$searchParams = $this->getQueryStringParams();
    	
    	// ordering 
    	$page = $this->_getParam('page', 1);		
    	$orderBy = $this->_getParam('orderBy', 'dateInserted');
    	$searchParams['orderBy'] = $orderBy;
    	$orderBySort = $this->_getParam('orderBySort', 'DESC');
    	$searchParams['orderBySort'] = $orderBySort;    	
    	
    	$searchParams['action'] = 'vkngine';
    	
    	$this->view->params = $searchParams;
    	$this->view->logs = $modelTrafficLog->fetchAllWithPagination($page, $orderBy, $orderBySort);
    }
    
	public function activityAction()
    {
    	$modelTrafficActivity = new Model_Traffic_Activity();
    	
    	$searchParams = $this->getQueryStringParams();
    	
    	// ordering 
    	$page = $this->_getParam('page', 1);		
    	$orderBy = $this->_getParam('orderBy', 'date');
    	$searchParams['orderBy'] = $orderBy;
    	$orderBySort = $this->_getParam('orderBySort', 'DESC');
    	$searchParams['orderBySort'] = $orderBySort;    	
    	
    	$searchParams['action'] = 'activity';
    	
    	$this->view->params = $searchParams;
    	$this->view->logs = $modelTrafficActivity->fetchAllWithPagination($page, $orderBy, $orderBySort);
    }
    
	public function loginAction()
    {
    	$modelTrafficLogins = new Model_Traffic_Logins();
    	
    	$searchParams = $this->getQueryStringParams();
    	
    	// ordering 
    	$page = $this->_getParam('page', 1);		
    	$orderBy = $this->_getParam('orderBy', 'dateInserted');
    	$searchParams['orderBy'] = $orderBy;
    	$orderBySort = $this->_getParam('orderBySort', 'DESC');
    	$searchParams['orderBySort'] = $orderBySort;    	
    	
    	$searchParams['action'] = 'login';
    	
    	$this->view->params = $searchParams;
    	$this->view->logs = $modelTrafficLogins->fetchAllWithPagination($page, $orderBy, $orderBySort);
    }
    
    public function showDetailAction()
    {
    	$field = $this->_getParam('field');
    	$id = $this->_getParam('id');
    	
    	$modelLogs = new Model_vkNgine_Application_Log();
    	$logs = $modelLogs->fetch($id);
    	
    	$this->view->logDetail = $logs[$field];
    	$this->_helper->layout->disableLayout();  	
    }
    
    
    public function deleteAction()
    {
    	$type = $this->_getParam('type');
    	
    	if($type == 'framework') {
    		$modelLogs = new Model_vkNgine_Application_Log();
    	}
    	elseif($type == 'vkngine') {
    		$modelLogs = new Model_Traffic_Log();
    	}
    	elseif($type == 'activity') {
    		$modelLogs = new Model_Traffic_Activity();
    	}
    	elseif($type == 'login') {
    		$modelLogs = new Model_Traffic_Logins();
    	}
    	
    	$logIds = $this->_getParam('logIds');
    	
    	$logIdsArray = explode(',', $logIds);
    	    
    	foreach($logIdsArray as $logId) {
    		$modelLogs->delete($logId);
		}	
			
    	exit;
    }
}