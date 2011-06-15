<?php
class Model_Admin_Login_Form extends Model_Form_Abstract
{
    public function init()
    {
    	$this->setName('adminLoginForm');
    	    	
    	$username = new Zend_Form_Element_Text('username');
        $username->addFilter('StringTrim')
        		 ->addValidator('StringLength', false, array(3, 256))
        		 ->removeDecorator('HtmlTag')
        		 ->removeDecorator('Label');
        			  	
		$password = new Zend_Form_Element_Password('password');
       	$password->addValidator('Alnum')
       			 ->addValidator('StringLength', false, array(6, 256))       			      			 
       			 ->removeDecorator('HtmlTag')
        		 ->removeDecorator('Label');
       	
	    $remember = new Zend_Form_Element_Checkbox('remember');
       	$remember->removeDecorator('HtmlTag')
        		 ->removeDecorator('Label');
        
        $submit = new Zend_Form_Element_Submit('submit');
       	$submit->removeDecorator('DtDdWrapper');		 
       	
       	$this->addElements(array(	
					$username,
					$password,
					$remember,
					$submit,
		));	        			  
    }
}