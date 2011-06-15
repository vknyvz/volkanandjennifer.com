<?php 

class vkNgine_Acl extends Zend_Acl
{	
	public $role;
	
    public function __construct()
    {   
        $this->add(new Zend_Acl_Resource('public'));
        $this->add(new Zend_Acl_Resource('albums'));
        
    	$user = Zend_Registry::get('user');
    	$this->role = $user->level;        	
    }
}