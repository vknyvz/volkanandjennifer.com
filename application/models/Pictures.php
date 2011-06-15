<?php
class Model_Pictures extends Model_DbTable_Abstract
{
    protected $_name = 'pictures';
	protected $_primary	= 'pictureId';
	protected $_rowClass = 'Model_Picture';
	
	protected $_saveInsertDate	= true;	
	
	/**
	 * adds a picture 
	 * 
	 * @param array $data
	 * @return int
	 */
	public function insert($data)
	{		
		return parent::insert($data);			
	}
	
	/**
	 * fetch a single album
	 * 
	 * @param int $userId
	 */
	public function fetch($pictureId) 
	{		
		$select = $this->select();
		$select->where($this->getPrimary() . ' = ?', (int) $pictureId);
		$row = $this->fetchRow($select);
				
		return $row;			
	}
	
	/**
	 * fetch a picture by album
	 * 
	 * @param int $albumId
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchByAlbum($albumId)
	{
		$select = $this->select();
		$select->where('albumId = ?', (int) $albumId);
				
		return $this->fetchAll($select);
	}
	
	/**
	 * fetch all pictures with pagination support
	 * @param int $page
	 * @param string $orderBy
	 * @param string $orderBySort
	 * @return Zend_Paginator
	 */
	public function fetchAllWithPagination($page, $orderBy = 'pictureId', $orderBySort = 'DESC')
	{
		$select = $this->select();		
		$select->order($orderBy . ' ' . $orderBySort);	
			
		$paginator = Zend_Paginator::factory($select);
				
		$config = Zend_Registry::get('config'); 
			
		if ($page != 'ALL') {
			$paginator->setItemCountPerPage($config->admin->pictures->perPage)->setCurrentPageNumber($page);
		} else {
			$paginator->setItemCountPerPage(9999);
		}
		
		return $paginator;
	}
	
    /**
     * fetch total number of pictures (or by album year)
     * 
     * @param int $year
     */
    public function fetchTotals($year = null)
    {
    	if($year) {
	    	$result = $this->_db->query(
				"SELECT count(*) as totals FROM albums a, pictures p WHERE a.albumYear = " . (int) $year . " and p.albumId = a.albumId \n"
			);    		
    	}
    	else {
    		$result = $this->_db->query(
				"SELECT count(*) as totals FROM pictures \n"
			); 
    	}
		
		$total = $result->fetchAll();		
		return $total[0]['totals'];
	}
	
	/**
	 * update an album
	 * 
	 * @param int $pictureId
	 * @param array $data
	 */
	public function update($pictureId, $data) 
	{
		$where = $this->getAdapter()->quoteInto($this->getPrimary() . ' = ?', (int) $pictureId);
		parent::update($data, $where);
	}
	
	/**
	 * delete an album
	 *
	 * @param int $albumId
	 */	
	public function delete($pictureId)
	{
		$where = $this->getAdapter()->quoteInto($this->getPrimary() . ' = ?', (int) $pictureId);
		parent::delete($where);
	}	
}	