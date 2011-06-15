<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => dirname(__FILE__),
        ));

        $autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('vkNgine_');
		
        return $autoloader;
    }
	
	protected function _initConfig()
	{		
		$config = new Zend_Config($this->getOptions());
		Zend_Registry::set('config', $config);
		
		error_reporting(E_ALL);
	}
	
	public function _initCache() 
	{
		$config = Zend_Registry::get('config');
		$cache = new vkNgine_Cache($config->cache->use, $config->cache->type);
		Zend_Registry::set('cache', $cache);
		Zend_Db_Table_Abstract::setDefaultMetadataCache($cache->getCacheObject());
	}
	
	protected function _initTimezone()
	{
		$config = Zend_Registry::get('config');
		date_default_timezone_set($config->timezone);
	}
	
	protected function _initViewHelpers()
	{
		// get layout, view instances
		$this->bootstrap('layout');
		$this->bootstrap('view');
		$layout	= $this->getResource('layout');
		$view	= $this->getResource('view');

		// save them for later
		Zend_Registry::set('layout', $layout);
		Zend_Registry::set('view', $view);
				
		// set doctype, content type
		$view->doctype('XHTML1_TRANSITIONAL');
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
		
		// set title
		$view->headTitle()->setSeparator(' - ');				
	}
	
	protected function _initLogger() {
		
		$logger = new vkNgine_Log();
		Zend_Registry::set('logger', $logger);
		
		$resource = $this->getPluginResource('db');
		$db = $resource->getDbAdapter();	
		
		$columnMapping = array('url' => 'url', 'userAgent' => 'userAgent', 'info' => 'info', 'reffer' => 'reffer', 'userId' => 'userId', 'priority' => 'priority','dateInserted' => 'dateInserted', 'message' => 'message');
		
		$writer_db = new Zend_Log_Writer_Db($db, 'log', $columnMapping);		
        $writer_firebug = new Zend_Log_Writer_Firebug();
        
		$logger->addWriter($writer_db);
		$logger->addWriter($writer_firebug);
		
		Zend_Registry::set('logger', $logger);		
	}
	
	protected function _initSession()
	{
		$resource = $this->getPluginResource('db');
		$db = $resource->getDbAdapter();	
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		
		$config = array('name' 			 => 'sessions',
						'primary'        => 'sessionId',
						'modifiedColumn' => 'modified',
						'dataColumn'     => 'data',
						'lifetimeColumn' => 'lifetime',
						'lifetime' 		 => 60 * 60 * 24 * 14 // 14 days
		);	
						
		Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($config));				
	}
	
	/**
	 * initialize all settings
	 *
	 */
	protected function _initSettings()
	{
		$this->_bootstrap('db');
		
		$modelSettings = new Model_Settings();
		$siteSettings = $modelSettings->fetchAllSettings(); 
		
		$settings = array();		
		foreach ($siteSettings as $setting) {
			$settings['siteSettings'] = $setting;
		}
		
		Zend_Registry::set('settings', $settings);
	}
	
	/**
	 * 
	 * init the sections of the system (public, admin)
	 */	 
	protected function _initModules() 
	{	
		$frontController = Zend_Controller_Front::getInstance();
		
		// admin module
		if (($_SERVER['HTTP_HOST'] == 'admin.vnj.com')) {
			$frontController->setControllerDirectory(array(
			    'default' => APPLICATION_PATH . '/modules/admin/controllers',
			));	
		} 
		// front-end site
		else {
			$frontController->setControllerDirectory(array(
				'default' => APPLICATION_PATH . '/modules/auth/controllers',
			));	
		}

		$layout = Zend_Layout::startMvc(); 
		
		// dashboard module
		$frontController->addControllerDirectory(APPLICATION_PATH . '/modules/dashboard/controllers', 'dashboard');
		
		// traffic logger
		$frontController->registerPlugin(new vkNgine_Controller_Plugin_LogTraffic());
		
		// precious functions
		$frontController->registerPlugin(new vkNgine_Array());  
	}	
}