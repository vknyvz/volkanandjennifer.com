<?php

class Model_Traffic_Log extends Model_DbTable_Abstract 
{
	protected $_name = "traffic_log";
	protected $_primary	= 'id';
	
	/**
	 * Fetch all with pagination support
	 * 
	 * @param int $page
	 * @param string $orderBy
	 * @param string $orderBySort
	 * 
	 * @return Zend_Paginator
	 */
	public function fetchAllWithPagination($page, $orderBy = 'dateInserted', $orderBySort = 'DESC')
	{
		$select = $this->select();		
		$select->order($orderBy . ' ' . $orderBySort);	
		
		$paginator = Zend_Paginator::factory($select);
				
		$config = Zend_Registry::get('config'); 
			
		if ($page != 'ALL') {
			$paginator->setItemCountPerPage($config->admin->logs->perPage)->setCurrentPageNumber($page);
		} else {
			$paginator->setItemCountPerPage(9999);
		}
		
		return $paginator;
	}
	
	/**
	 * add the current request to the traffic log
	 * 
	 * @param $request
	 */
	public function logHit($request)
	{
		
		$logger = Zend_Registry::get('logger');
		
		$data['page'] = $_SERVER['REQUEST_URI'];
		$data['ip'] = $_SERVER['REMOTE_ADDR'];
		$data['dateInserted'] = date('Y-m-d H:m:s');
		$data['userType'] = '';
		$data['module'] = $request->getModuleName();
		$data['controller'] = $request->getControllerName();
		$data['action'] = $request->getActionName();
		
		try {
			$user = Zend_Registry::get('user');			
			if (is_numeric($user['userId'])) {
				$data['userType'] = $user->type;
				$data['userId'] = $user['userId'];
			}
			try {
				$this->insert($data);
			} catch (Zend_Db_Statement_Exception $e) {
	   			$logger->log('TRAFFIC_LOG_ERROR', $e->getMessage(), 5);
	   		}
		} catch(Exception $e) {
		}   		
	}
	
	/** 
	 * delete a log
	 * 
	 * @param int $logId
	 */
	public function delete($logId)
	{
		$where = $this->getAdapter()->quoteInto($this->getPrimary() . ' = ?', (int) $logId);
		parent::delete($where);
	}	
}