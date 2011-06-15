<?php
class vkNgine_Cache
{
	private $_cacheObject = null;
	
	/* CACHE TYPES */
	const FILE = 'File';
	const MEMCACHED = 'Memcached';
	
	/* 
	 * CONSTANTS - PER ITEM
	 * 
	 */
	const USER = 'user_';
	const USER_MANAGER = 'user_manager_';
	const USER_RESIDENT = 'user_resident_';
	
	const NEIGHBORS_BY_PROPERTY = 'neighbors_property_';
	const PROPERTY = 'property_';
	const PROPERTY_SETTINGS = 'property_settings_';
	const PROPERTY_SETTINGS_IMAGES = 'property_settings_images_';
	const COMPANY_DOMAIN = 'company_domain_';
	const PROFILE = 'profile_';
	const UNIT = 'unit_';
	const UNIT_BY_USERID = 'unit_by_userid_';
	const UNITS_COUNT = 'units_count_property_%s';
	const COMMENTS_ON_POST_ = 'comments_on_post_';
	const REQUEST_COUNT = 'request_count_property_%s_status_%s';
	const RESIDENT_PRIVACY_PERMISSION = 'resident_privacy_permission_'; 
	
	/* 
	 * CONSTANTS - PER LIST
	 * 
	 */
	const WALL = 'wall';
	
	
	public function __construct($useCache = null, $cacheType = null, $frontendName = 'Core')
	{
		if(!isset($useCache))
			$useCache = false;
		if(!isset($cacheType))
			$cacheType = 'File';
			
		$automaticSerialization = true;
		
		if($cacheType == self::FILE) {
			
			$cacheDir = APPLICATION_PATH . '/../tmp/cache/';
			if(!file_exists($cacheDir)) 
				mkdir($cacheDir);
				
			$backendName = $cacheType;
			$backendOptions = array('cache_dir' => $cacheDir);
			$frontendOptions = array('caching' => $useCache, 'lifetime' => 7200, 'automatic_serialization' => $automaticSerialization);
			$this->_cacheObject = Zend_Cache::factory($frontendName, $backendName, $frontendOptions, $backendOptions);
			
		} elseif($cacheType == self::MEMCACHED) {
			
			$backendName = $cacheType;
			$backendOptions = array( 'servers' => array( array( 'host' => '127.0.0.1', 'port' => '11211') ), 'compression' => true);
			$frontendOptions = array('caching' => $useCache, 'write_control' => true, 'automatic_serialization' => $automaticSerialization, 'ignore_user_abort' => true );
			
			$this->_cacheObject = Zend_Cache::factory($frontendName, $backendName, $frontendOptions, $backendOptions );
			
		} else {
			throw new Exception($cacheType.' - cache type is not supported');
		}
		
	}
	
	public function save($key, $value)
	{
		return $this->_cacheObject->save($value, $key);
	}
	
	public function load($key)
	{
		$value = $this->_cacheObject->load($key);
		return $value;
	}
	
	public function remove($key)
	{
		return $this->_cacheObject->remove($key);
	}
	
	public function getCacheObject()
	{	
		return $this->_cacheObject;
	}
	
	
}