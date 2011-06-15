<?php
class Model_Settings extends Model_DbTable_Abstract
{
	protected $_name	 = 'settings';
	protected $_primary	 = 'settingId';
	
	protected $_rowClass = 'Model_vkNgine_Settings';
	
	protected $_saveUpdateDate = true;

	/**
	 * fetch all settings
	 */
	public function fetchAllSettings()
	{
		$select = $this->select();
		
		return $this->fetchAll($select);
	}
	
	/**
	 * fetch a setting
	 *
	 * @param int $settingId
	 */
	public function fetch($settingId)
	{
		$select = $this->select();
		$select->where($this->getPrimary() . ' = ?', (int) $settingId);
			
		return $this->fetchRow($select);
	}
	
	/**
	 * 
	 * fetch default settings
	 *
	 */
	public function fetchDefaultSettings()
	{
		$select = $this->select();
		$select->where($this->getPrimary() . ' = ?', 1);
			
		return $this->fetchRow($select);
	}	
	
	/**
	 * update a setting
	 *
	 * @param int $settingId
	 * @param array $data
     */
	public function update($settingId, $data) 
	{
		$where = $this->getAdapter()->quoteInto($this->getPrimary() . ' = ?', (int) $settingId);
		
		parent::update($data, $where);
	}	
}