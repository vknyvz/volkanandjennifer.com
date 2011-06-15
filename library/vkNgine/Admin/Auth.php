<?php
class vkNgine_Admin_Auth extends vkNgine_Auth
{	
    /**
     * Returns the Auth Adapter class in use for this configuration.
     * 
     * @param	array	$params Array of input username and password
     * @return	Zend_Auth_Adapter_Interface
     */
    static function getAuthAdapter(array $params)
    {		
    	$adapter = new vkNgine_Admin_Auth_Adapter();
    	$adapter->setEmail($params['username']);
    	$adapter->setPassword($params['password']);
    	
    	if (array_key_exists('noCredentialTreatment', $params)) {
			$adapter->setNoCredentialTreatment($params['noCredentialTreatment']);
		}
				
		return $adapter;
    }

    
    /**
     * Attempts login with input data.
     * 
     * @param	array	$values	Array of input login data to pass to the auth adapter.
     * @return	Zend_Auth_Result
     */
    public static function attemptLogin($values)
    {    	
		$adapter = self::getAuthAdapter($values);		
        $auth    = Zend_Auth::getInstance();
        $result  = $auth->authenticate($adapter);
		
        if ($result->isValid())
        {        	
        	$modelUsers = new Model_Admin_Users();        	
        	$user = $modelUsers->fetchWithEmail($values['username']);
        	
        	// checks the branding
        	if (!$user instanceof Model_User) {
        		return false;
        	}
        	
        	$userInfo = $user->toArray();
        	$userInfo['type'] = $user->type;
        	
           	$storage = $auth->getStorage();
        	$storage->write($userInfo);
        	return true;
        }
        
        return false;
    }
    
   
    /**
     * revalidates the user 
     */
    public static function revalidate() {
       	$user = vkNgine_Auth::getIdentity();

       	// revalidate the user
       	$modelAdminUsers = new Model_Admin_Users();
       	$dbUser = $modelAdminUsers->fetchWithEmail($user['email']);
						
		return $dbUser;
    }
}
