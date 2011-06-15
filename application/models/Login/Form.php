<?php
class Model_Login_Form extends Model_Form_Abstract
{
    public function init()
    {    
    	$this->setName('login_form');
    	
        $username = new Zend_Form_Element_Text('username');
        $username->addFilter('StringTrim')
        		 ->addValidator('StringLength', false, array(3, 512))        		   		 
        		 ->setAttrib('class', 'textbox')
        		 ->setLabel('Enter your email')
	             ->setDecorators(
	                array(
	                  array('ViewHelper', array('helper' => 'formText')),
	                  'Errors',
	                  array('Label', array('class' => 'form_label')),
	                  array('HtmlTag', array('tag' => 'p')),
	                  
	                )
	              ); 
       	
        $password = new Zend_Form_Element_Password('password');
       	$password->addValidator('Alnum')
       			 ->addValidator('StringLength', false, array(3, 15))
       			 ->setLabel('Enter your password')
       			 ->setAttrib('class', 'textbox')
       			 ->setDecorators(
	                array(
	                  array('ViewHelper', array('helper' => 'formText')),
	                  'Errors',
	                  array('Label', array('class' => 'form_label')),
	                  array('HtmlTag', array('tag' => 'p')),	                  
	                )
	              ); 
       		
       	$submit = new Zend_Form_Element_Submit('submit');
       	$submit->setAttrib('id', 'form_submit')       		   
       		   ->setLabel('Log-in')
       		   ->removeDecorator('DtDdWrapper');
       		   
       	$this->addElements(array(	
			$username,
			$password,			
			$submit,
		));	   

        $this->setDecorators(array(
	        'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),           
            array('Description', array('placement' => 'append')),
            'Form'
        ));
    }    
}
