<?php
class Model_Users extends Model_DbTable_Abstract
{
    protected $_name = 'users';
	protected $_primary	= 'userId';
	protected $_rowClass = 'Model_User';
	
	protected $_saveInsertDate	= true;
	protected $_saveUpdateDate	= true;
	
	/**
	 * add a data user
	 * 
	 * @param array $data
	 * @return int
	 */
	public function insert($data)
	{
		$data['password'] = md5($data['password']);
		$data['zip'] = $data['zip'] ? $data['zip'] : null;
					
		return parent::insert($data);			
	}
	
	/**
	 * fetch user by email
	 * 
	 * @param string $email
	 */
	public function fetchWithEmail($email)
	{
		$select = $this->select();
		$select->where('email = ?', $email);
		
		return $this->fetchRow($select);
	}

	/**
	 * fetch a single user
	 * 
	 * @param int $userId
	 */
	public function fetch($userId) 
	{
		$select = $this->select();
		$select->where('userId = ?', (int) $userId);
		$row = $this->fetchRow($select);
		return $row;			
	}
	
	/**
	 * auto complete user search
	 * 
	 * @param string $query
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchAuto($query)
	{
		$select = $this->select();
		
		$select->from($this->_name)->columns(array('userId', 'email'))->limit(10);
		
		if (!empty($query)) {
			$select->where('userId = ?', $query);
			$select->orWhere('email LIKE ?', '%' . $query . '%');
			$select->orWhere('firstName LIKE ?', '%' . $query . '%');
			$select->orWhere('lastName LIKE ?', '%' . $query . '%');
		}
		
		return $this->fetchAll($select);
	}
	
	/**
	 * fetch all users with pagination support
	 * 
	 * @param int $page
	 * @param string $orderBy
	 * @param string $orderBySort
	 * @param array $searchParams
	 * @return Zend_Paginator
	 */
	public function fetchAllWithPagination($page, $orderBy = 'userId', $orderBySort = 'ASC', $searchParams = array())
	{
		$select = $this->select();		
		$select->order($orderBy . ' ' . $orderBySort);	
		
		if($searchParams['query']){			
			$name = explode(' ', $searchParams['query']);
			if(count($name) == 2) {
				list($firstName, $lastName) = $name;
				$select->where('firstName = ?', $firstName);	
				$select->where('lastName = ?', $lastName);
			}								
		}
		else {	
			if($searchParams['emailSearch']){
				$select->where('email LIKE ?', '%' . $searchParams['emailSearch'] . '%');
			}
			if($searchParams['name']){
				$select->where('firstName LIKE ?', '%' . $searchParams['name'] . '%');
			}
			if($searchParams['active']){
				$select->where('active = ?', $searchParams['active']);
			}
			if($searchParams['dateInsertedFrom'] and $searchParams['dateInsertedTo']){
				$dateParts = explode('/', $searchParams['dateInsertedFrom']);
				$searchParams['dateInsertedFrom'] = $dateParts[2].'-'.$dateParts[0].'-'.$dateParts[1];
				$dateParts = explode('/', $searchParams['dateInsertedTo']);
				$searchParams['dateInsertedTo'] = $dateParts[2].'-'.$dateParts[0].'-'.$dateParts[1];
								
				$select->where('dateInserted >= ?', $searchParams['dateInsertedFrom']);
				$select->where('dateInserted <= ?', $searchParams['dateInsertedTo']);
			}
			if($searchParams['dateLastLoginFrom'] and $searchParams['dateLastLoginTo']){
				$dateParts = explode('/', $searchParams['dateLastLoginFrom']);
				$searchParams['dateLastLoginFrom'] = $dateParts[2].'-'.$dateParts[0].'-'.$dateParts[1];
				$dateParts = explode('/', $searchParams['dateLastLoginTo']);
				$searchParams['dateLastLoginTo'] = $dateParts[2].'-'.$dateParts[0].'-'.$dateParts[1];
								
				$select->where('lastLogin >= ?', $searchParams['dateLastLoginFrom']);
				$select->where('lastLogin <= ?', $searchParams['dateLastLoginTo']);
			}
		}
	
		$paginator = Zend_Paginator::factory($select);
				
		$config = Zend_Registry::get('config'); 
			
		if ($page != 'ALL') {
			$paginator->setItemCountPerPage($config->admin->user->perPage)->setCurrentPageNumber($page);
		} else {
			$paginator->setItemCountPerPage(9999);
		}
		
		return $paginator;
	}
	
	/**
	 * fetch an active user
	 * 
	 * @param int $userId
	 */
	public function fetchActive($userId) 
	{
		$select = $this->select();
		$select->where('userId = ?', (int) $userId);
		$select->where('active = ?', 'Y');
		$row = $this->fetchRow($select);
		
		return $row;		
	}
		
	/**
	 * update a user
	 * 
	 * @param int $userId
	 * @param array $data
	 */
	public function update($userId, $data) 
	{
		$where = $this->getAdapter()->quoteInto('userId = ?', (int)$userId);
		parent::update($data, $where);
	}	
	
	/**
	 * delete a user
	 *
	 * @param int $userId
	 */	
	public function delete($userId)
	{			
		$where = $this->getAdapter()->quoteInto('userId = ?', (int)$userId);
		parent::delete($where);

	}	
}