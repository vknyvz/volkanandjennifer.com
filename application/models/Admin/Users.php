<?php

class Model_Admin_Users extends Model_Users {
    
    protected $_dependentTables = array('Model_Users_Admins');
	
	/**
	 * fetch admin user by email
	 * 
	 * @param string $email
	 */
	public function fetchWithEmail($email) {
		$user = parent::fetchWithEmail($email);
		
		// add the admin info
		if (!$user instanceof Model_User) {
			return false;
		}
		
		$adminInfo = $user->findDependentRowset('Model_Users_Admins', 'Admin');
		
		if (count($adminInfo)>0) {
			$row = $adminInfo->current();
			
			$user->type = 'ADMIN';
			$user->level = $row->level;
		} else {
			$user = false;
		}		
		
		return $user;
	}
		
	/** 
	 * fetch all admin users with pagination
	 * 
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
	 * fills up a user object to a admin user
	 * 
	 * @param Model_User $user
	 */
	private function makeAdmin(Model_User $user) {
		// add the admin info
		$adminInfo = $user->findDependentRowset('Model_Users_Admins', 'Admin');
		
		if (count($adminInfo)>0) {
			$row = $adminInfo->current();
			
			$user->type = 'ADMIN';
			$user->level = $row->level;
		} else {
			$user = false;
		}
				
		return $user;
	}

	/** 
	 * fetch an admin user
	 * 
	 * @param int $userId
	 */
	public function fetch($userId)
	{
		$select = $this->select();		
		$select->where('userId = ?', $userId);
		
		$user = $this->fetchRow($select);
		$user = $this->makeAdmin($user);

		return $user;
	}	

	/**
	 * insert a new admin user
	 * 
	 * @param array $data
	 */
	public function insert($data) {

		$level = $data['level'];
		unset($data['userId']);
		unset($data['level']);
		$userId = parent::insert($data);

		$modelUsersAdmins = new Model_Users_Admins();
		$modelUsersAdmins->insert(array('userId' => $userId, 'level' => $level));		
				
		return $userId;
	}		

	/**
	 * update an admin user
	 * 
	 * @param int $userId
	 * @param array $data
	 */
	public function update($userId, $data) {

		// reset the admin's level
		if (!empty($data['level'])) {
			$modelUsersAdmins = new Model_Users_Admins();
			$modelUsersAdmins->update($userId, array('level' => $data['level']));
			unset($data['level']);
		}
		
		return parent::update($userId, $data);		
	}	
	
	/**
	 * delete an admin user
	 *
	 * @param int $userId
	 */	
	public function delete($userId)
	{
		$modelUsersAdmins = new Model_Users_Admins();
		$modelUsersAdmins->delete($userId);
				
		parent::delete($userId);
	}	
}