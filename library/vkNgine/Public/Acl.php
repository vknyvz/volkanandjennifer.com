<?php 

class vkNgine_Public_Acl extends vkNgine_Acl
{		
    public function __construct()
    {
    	parent::__construct();
    	
        $this->addRole(new Zend_Acl_Role('ADMIN'));
        $this->addRole(new Zend_Acl_Role('STANDARD'));
        $this->addRole(new Zend_Acl_Role('LIMITED'));
        
        $this->allow('ADMIN');   
		
        $this->allow('STANDARD');       
    	
        $this->allow('LIMITED');     	
    	$this->deny('LIMITED', array('public'));
    	
    	$user = Zend_Registry::get('user');
    	
    	if ($user->type == 'ADMIN') {    		
			$this->role = 'ADMIN';   
		}	
    }
    
    /**
     * checks if a user is allowed a privilege
     * 
     * @param string $resource
     * @param string $privilege
     */
    public function isAllowed($resource = null, $privilege = null)
    {
    	return parent::isAllowed($this->role, $resource, $privilege);
    }
    
}