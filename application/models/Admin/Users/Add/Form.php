<?php
class Model_Admin_Users_Add_Form extends Model_Form_Abstract
{
	static $emailMessages = array(
		Zend_Validate_EmailAddress::INVALID				=> "Invalid e-mail address given",
		Zend_Validate_EmailAddress::INVALID_FORMAT	 	=> "Invalid e-mail address given",
		Zend_Validate_EmailAddress::INVALID_HOSTNAME    => "Invalid e-mail address given",
		Zend_Validate_EmailAddress::INVALID_MX_RECORD  	=> "Invalid e-mail address given",
		Zend_Validate_EmailAddress::DOT_ATOM		   	=> "Invalid e-mail address given",
		Zend_Validate_EmailAddress::QUOTED_STRING	  	=> "Invalid e-mail address given",
		Zend_Validate_EmailAddress::INVALID_LOCAL_PART 	=> "Invalid e-mail address given",
		Zend_Validate_EmailAddress::LENGTH_EXCEEDED		=> "Invalid e-mail address given",
	);
		
    public function init()
    {
    	$this->setAttribs(
    		array('name'  => 'usersAdd', 
    			  'class' => 'search_form general_form')
    	);
    	
    	$userId = new Zend_Form_Element_Hidden('userId');
		$userId->removeDecorator('HtmlTag')		
			   ->removeDecorator('Label')
  			   ->setLabel(null);
    	
		$email = new Zend_Form_Element_Text('email');
    	$email->setRequired(true)
    		  ->addFilter('StringTrim')
			  ->addFilter('StripTags') 				 	
			  ->setAttrib('class', 'text medium_input')
			  ->setLabel('Email:')
			  ->addValidator('NotEmpty', false, array('messages'=>'Email address can\'t be empty'))
			  ->addValidator('EmailAddress', null, array('messages' => self::$emailMessages))
			  ->addValidator(new vkNgine_Validate_NotUser());
			  						 
		$password = new Zend_Form_Element_Text('password');
    	$password->setRequired(true)
    			 ->addFilter('StringTrim')
				 ->addFilter('StripTags') 				 	
				 ->setAttrib('class', 'text medium_input')
				 ->setLabel('Password:')			 
				 ->addValidator('NotEmpty', false, array('messages'=>'Password can\'t be empty'))
		   		 ->addValidator('Alnum')
       			 ->addValidator('StringLength', false, array(4, 25));

       	$this->addElements(array($email, $password));
		$groupName = 'loginInformation';							 
		$this->addDisplayGroup(array('email', 'password'), $groupName, array('legend' => 'Login Information'));
		$this->getDisplayGroup($groupName)->setDecorators(array('FormElements','Fieldset', array('HtmlTag', array('tag' => 'div', 'class'=>'row zendRow'))));
		
       	$firstName = new Zend_Form_Element_Text('firstName');
    	$firstName->setRequired(true)
    			  ->addFilter('StringTrim')
				  ->addFilter('StripTags') 				 	
				  ->setAttrib('class', 'text medium_input')
				  ->setLabel('First Name:')
				  ->addValidator('NotEmpty', false, array('messages' => 'First name can\'t be empty')); 
    	
		$lastName = new Zend_Form_Element_Text('lastName');
    	$lastName->setRequired(true)
    			 ->addFilter('StringTrim')
				 ->addFilter('StripTags') 				 	
				 ->setAttrib('class', 'text medium_input')
				 ->setLabel('Last Name:')
				 ->addValidator('NotEmpty', false, array('messages' => 'Last name can\'t be empty')); 							 

		$companyName = new Zend_Form_Element_Text('companyName');
    	$companyName->addFilter('StringTrim')
					->addFilter('StripTags') 				 	
					->setAttrib('class', 'text medium_input')
					->setLabel('Company Name:'); 										 

		$mailingAddress = new Zend_Form_Element_Textarea('mailingAddress');
    	$mailingAddress->addFilter('StringTrim')
					   ->addFilter('StripTags')
					   ->setAttribs(array('cols' => 50, 'rows' => 5, 'class' => 'text medium_input'))
					   ->setLabel('Mailing Address:');

		$phone = new Zend_Form_Element_Text('phone');
    	$phone->addFilter('StringTrim')
			  ->addFilter('StripTags') 				 	
			  ->setAttrib('class', 'text medium_input')
			  ->setAttrib('maxLength', 10)
			  ->addValidator('StringLength', false, array(3, 10))
			  ->setLabel('Phone:');
			 					   
		$city = new Zend_Form_Element_Text('city');
    	$city->addFilter('StringTrim')
			 ->addFilter('StripTags') 				 	
			 ->setAttrib('class', 'text medium_input')
			 ->setLabel('City:');
					 
		$state = new Zend_Form_Element_Select('state');
    	$state->addFilter('StringTrim')
			  ->addFilter('StripTags') 				 	
			  ->setLabel('State:');
		
		$zip = new Zend_Form_Element_Text('zip');
       	$zip->addFilter('StringTrim')
			->addFilter('StripTags') 				 	
			->setAttrib('class', 'text medium_input')
			->setAttrib('maxLength', 5)
			->setLabel('Zip:');
			   		   
		$active = new Zend_Form_Element_Checkbox('active');
       	$active->setCheckedValue('Y')
       		   ->setUncheckedValue('N')
       		   ->setValue('N')
       		   ->setLabel('Active?');
       		    	
       	$level = new Zend_Form_Element_Select('level');
    	$level->setRequired(true)
    		  ->addFilter('StringTrim')
    		  ->addFilter('StripTags') 				 	
			  ->setLabel('Level:')
			  ->addMultiOption(null, 'Select one')   
			  ->addMultiOption('ADMIN', 'ADMIN')			
			  ->addMultiOption('STANDARD', 'STANDARD')
			  ->addMultiOption('LIMITED', 'LIMITED');
		
       	$this->addElements(array(
    				$firstName,	
    				$lastName,
    				$companyName,
    				$mailingAddress, 
    				$phone,
    				$city,
    				$state,
    				$zip,
    				$active,   		
    				$level,		
		));			
		
		$groupName = 'generalInformation';							 
		$this->addDisplayGroup(array('firstName', 'lastName', 'companyName', 'mailingAddress', 'phone', 'city', 'state', 'zip', 'active', 'level'), $groupName, array('legend' => 'General Information'));
		$this->getDisplayGroup($groupName)->setDecorators(array('FormElements','Fieldset', array('HtmlTag', array('tag' => 'div', 'class'=>'row zendRow'))));
			   
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setDecorators(array(new vkNgine_Admin_Form_Decorator_Submit(array('id' => 'submit', 'type' => 'send_form_btn', 'name' => 'Save'))));
									 
		$this->addElements(array($userId,$submit));		
    }
    
	public function setStates()
    {
    	$element = $this->getElement('state');
		$element->addMultiOption(null, 'Select one');
		
    	foreach(vkNgine_States::getStates() as $abbr => $name) {	    		
    		$element->addMultiOption($abbr, $name);
    	}
    }
    
    public function setHidden($value)
    {
    	$this->getElement('userId')->setValue($value);
    }

    public function adminMode($email) 
    {
    	$this->getElement('password')->setRequired(false);
    	$this->getElement('password')->setDescription('Only fill in if you would like to change the password of the user');    	
    	
    	$this->getElement('email')->removeValidator('vkNgine_Validate_NotUser');    	
    	$this->getElement('email')->addValidator(new vkNgine_Validate_NotUser($email), false);    	
    	$this->getElement('email')->setDescription('If you change the email of the user, and he is currently logged in, he will be logged out');
    }
}