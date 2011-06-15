<?php

class IndexController extends vkNgine_Admin_Controller
{
	public function init()
	{
		parent::init();
		
		$view = Zend_Registry::get('view');
		$view->headTitle('Dashboard');
	}	
	
	public function indexAction() 
	{		
	}
}