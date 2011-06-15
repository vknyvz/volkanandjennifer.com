<?php
class vkNgine_Auth
{
   /**
     * Returns true if session is authenticated (an identity exists).
     * 
     * @return bool
     */
    static function isAuthenticated()
    {
    	return Zend_Auth::getInstance()->hasIdentity();
    }
    
    /**
     * gets an identity
     */
    static function getIdentity()
    {
    	return Zend_Auth::getInstance()->getIdentity();
    }
            
    /**
     * Clears the session data and logs out the current user.
     * 
     * @return void
     */
    public function logout()
    {
    	return Zend_Auth::getInstance()->clearIdentity();
    }
        
    /**
     * revalidates the given user
     * 
     * @return bool  
     */
    public static function revalidate() {
       	$user = vkNgine_Auth::getIdentity();
       	
       	if ($user['type'] == 'STANDARD') {
	   		return vkNgine_Public_Auth::revalidate();
	    } else if ($user['type'] == 'ADMIN') {
	   		return vkNgine_Admin_Auth::revalidate();
		} else {
	    	return false;
	    }
    }		
}