<?php 
class vkNgine_Public_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
	/**
	 * user's email
	 * @var string
	 */
	protected $_email = null;
	
	/**
	 * 
	 * user's password
	 * @var string
	 */	
	protected $_password = null;
		
	/**
	 * 
	 * Enter credential treatment here ...
	 * @var bool
	 */	
	protected $_noCredentialTreatment = FALSE;
	
   /**
     * $_authenticateResultInfo
     *
     * @var array
     */
    protected $_authenticateResultInfo = null;
    	
    /**
     * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to
     * attempt an authentication.  Previous to this call, this adapter would have already
     * been configured with all necessary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     *
     * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
    	$modelUsers = new Model_Public_Users(); 
    	$user = $modelUsers->fetchWithEmail($this->_email);
    	
    	
        if (!$user instanceof Model_User) {
            $this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
            $this->_authenticateResultInfo['messages'][] = '2Supplied credential is invalid.';        	
        } 
        elseif ($user->active != 'Y') {
      		$this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
            $this->_authenticateResultInfo['messages'][] = '34Supplied credential is invalid.';
        }
        elseif ($user->type == 'ADMIN') {    	
      		$this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
            $this->_authenticateResultInfo['messages'][] = '34Supplied credential is invalid.';            
        }
        elseif ($user->password != md5($this->_password)) {    	
			if ($this->_noCredentialTreatment && ($user->password == $this->_password)) {
        		$this->_authenticateResultInfo['code'] = Zend_Auth_Result::SUCCESS;
				$this->_authenticateResultInfo['messages'][] = 'Authentication successful.';
			}
			else {
				$this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
            	$this->_authenticateResultInfo['messages'][] = 'S4upplied credential is invalid.'; 
			}	             	
        }
        else {
           	$this->_authenticateResultInfo['code'] = Zend_Auth_Result::SUCCESS;
			$this->_authenticateResultInfo['messages'][] = 'Authentication successful.';
        }
        
        return $this->_authenticateCreateAuthResult();
    }
    
    /**
     * setEmail() - set the user's email
     *
     * @param string $email
     * @return vkNgine_Auth_Adapter Provides a fluent interface
     */
    public function setEmail($email)
    {
        $this->_email = $email;
        return $this;
    }   

    /**
     * setPassword() - set the user's password
     *
     * @param string $password
     vkNgine_Auth_Adapter Provides a fluent interface
     */
    public function setPassword($password)
    {
        $this->_password = $password;
        return $this;
    }   
    
    /**
     * setNoCredentialTreatment() - set the credential treatment.
     *
     * @param string $noCredentialTreatment
     * @return vkNgine_Auth_Adapter Provides a fluent interface
     */
    public function setNoCredentialTreatment($noCredentialTreatment)
    {
        $this->_noCredentialTreatment = $noCredentialTreatment;
        return $this;
    }
    
    /**
     * _authenticateCreateAuthResult() - Creates a Zend_Auth_Result object from
     * the information that has been collected during the authenticate() attempt.
     *
     * @return Zend_Auth_Result
     */
    protected function _authenticateCreateAuthResult()
    {
        return new Zend_Auth_Result(
            $this->_authenticateResultInfo['code'],
            null,
            $this->_authenticateResultInfo['messages']
            );
    }
}
