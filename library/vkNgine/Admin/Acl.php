<?php 

class vkNgine_Admin_Acl extends Zend_Acl
{	
	private $role;

    public function __construct()
    {
    	// add the roles
        $this->addRole(new Zend_Acl_Role('ADMIN'));
        $this->addRole(new Zend_Acl_Role('STANDARD'));
        $this->addRole(new Zend_Acl_Role('LIMITED'));
        
        // add the modules
        $this->add(new Zend_Acl_Resource('albums'));
        $this->add(new Zend_Acl_Resource('index'));
        $this->add(new Zend_Acl_Resource('logs'));
        $this->add(new Zend_Acl_Resource('pictures'));
        $this->add(new Zend_Acl_Resource('settings'));
        $this->add(new Zend_Acl_Resource('users'));
        
         // admin can do everything for now
        $this->allow('ADMIN');       

        // deny standard users 
    	$this->deny('STANDARD');
    	$this->deny('LIMITED');
    	    	
    	$user = Zend_Registry::get('user');
    	$this->role = $user->level;
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