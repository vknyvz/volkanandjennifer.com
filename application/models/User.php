<?php
class Model_User extends Zend_Db_Table_Row
{
	public $type;
	public $level;
		
	/**
	 * get user id
	 * @return Model_User
	 */
	public function getId()
	{
		return $this['userId'];
	}
		
	/**
	 * get user full name
	 * 
	 * @return string
	 */
	public function getFullName()
	{
		return sprintf('%s %s', $this['firstName'], $this['lastName']);
	}
	
	/**
	 * get user's first name
	 * 
	 * @return Model_User
	 */
	public function getFirstName()
	{
		return $this['firstName'];
	}	
}