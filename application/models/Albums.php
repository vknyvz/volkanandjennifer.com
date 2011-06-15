<?php
class Model_Albums extends Model_DbTable_Abstract
{
    protected $_name = 'albums';
	protected $_primary	= 'albumId';
	protected $_rowClass = 'Model_Album';
	
	protected $_saveInsertDate	= true;
	protected $_saveUpdateDate	= true;
	
	/**
	 * adds an album
	 * 
	 * @param array $data
	 * @return int
	 */
	public function insert($data)
	{					
		$id = parent::insert($data);
		return $id;		
	}
	
	/**
	 * fetch a single album
	 * 
	 * @param int $albumId
	 */
	public function fetch($albumId) 
	{		
		$select = $this->select();
		$select->where('albumId = ?', (int) $albumId);		
		
		return $this->fetchRow($select);			
	}
	
	/**
	 * fetches all albums
	 * 
	 * @param int $year
	 */
	public function fetchAlll($year = 'ALL', $private = 'ALL')
	{	
		$select = $this->select();		
		
		if($year != 'ALL'){
			$select->where('albumYear = ?', (int) $year);			
		}
		
		if($private != 'ALL'){
			$select->where('isPrivate = ?', (string) $private);
		}
		
		$select->order('albumName ASC');	
		
		return parent::fetchAll($select);		
	}
	
	/**
	 * fetch total number of pictures in an album
	 * 
	 * @param int $albumId
	 */
	public function fetchTotalPicturesByAlbum($albumId)
	{
		$result = $this->_db->query(
			"SELECT count(*) as totals FROM albums a, pictures p WHERE a.albumId = " . (int) $albumId . " and p.albumId = a.albumId \n"
		);

		$total = $result->fetchAll();		
		return $total[0]['totals'];
	}
	
	/**
	 * fetch all albums with pagination support 
	 * 
	 * @param int $page
	 * @param string $orderBy
	 * @param string $orderBySort
	 * @return Zend_Paginator
	 */
	public function fetchAllWithPagination($page, $orderBy = 'albumId', $orderBySort = 'ASC')
	{
		$select = $this->select();		
		$select->order($orderBy . ' ' . $orderBySort);	
			
		$paginator = Zend_Paginator::factory($select);
				
		$config = Zend_Registry::get('config'); 
			
		if ($page != 'ALL') {
			$paginator->setItemCountPerPage($config->admin->albums->perPage)->setCurrentPageNumber($page);
		} else {
			$paginator->setItemCountPerPage(9999);
		}
		
		return $paginator;
	}

	/** 
	 * update an album
	 * 
	 * @param int $albumId
	 * @param array $data
	 */
	public function update($albumId, $data) 
	{
		$where = $this->getAdapter()->quoteInto('albumId = ?', (int) $albumId);
		parent::update($data, $where);
	}
	
	/**
	 * delete an album
	 *
	 * @param int $albumId
	 */	
	public function delete($albumId)
	{
		$where = $this->getAdapter()->quoteInto('albumId = ?', (int) $albumId);
		parent::delete($where);

	}	
}	