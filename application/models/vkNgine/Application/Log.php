<?php
class Model_vkNgine_Application_Log extends Model_DbTable_Abstract 
{
	protected $_name = "log";
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
	 * fetch a log
	 *  
	 * @param int $logId
	 */
	public function fetch($logId)
	{
		$select = $this->select();
		$select->where($this->getPrimary() . ' = ?', (int) $logId);
		
		return $this->fetchRow($select);
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