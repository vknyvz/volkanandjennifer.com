<?php
class Model_Admin_Users_Search_Form extends Model_Form_Abstract
{
    public function init()
    {
    	$this->setName('usersSearch');
    	
    	$emailSearch = new Zend_Form_Element_Text('emailSearch');
    	$emailSearch->addFilter('StringTrim')
				    ->addFilter('StripTags') 	
				    ->setLabel('Email:')			 	
				    ->setAttrib('class', 'text');	

		$name = new Zend_Form_Element_Text('name');
    	$name->addFilter('StringTrim')  
			 ->addFilter('StripTags')
			 ->setLabel('Name:') 				 	
			 ->setAttrib('class', 'text');	

		$active = new Zend_Form_Element_Select('active');
       	$active->setLabel('Active')
       		   ->addMultiOption(null, 'Select One') 
       		   ->addMultiOption('Y', 'Yes')
       		   ->addMultiOption('N', 'No');
       	
    	$dateInsertedFrom = new Zend_Form_Element_Text('dateInsertedFrom');
    	$dateInsertedFrom->setLabel('Date Registered From:')
    					 ->addFilter('StringTrim')
					 	 ->addFilter('StripTags') 				 	
					 	 ->setAttribs(array('class' => 'text date', 'readonly' => '1'));
		
		$dateInsertedTo = new Zend_Form_Element_Text('dateInsertedTo');
    	$dateInsertedTo->setLabel('Date Registered To:')
    				   ->addFilter('StringTrim')
					   ->addFilter('StripTags') 				 	
					   ->setAttribs(array('class' => 'text date', 'readonly' => '1'));					 	 

		$dateLastLoginFrom = new Zend_Form_Element_Text('dateLastLoginFrom');
    	$dateLastLoginFrom->setLabel('Date Last Login From:')
    					 ->addFilter('StringTrim')
					 	 ->addFilter('StripTags') 				 	
					 	 ->setAttribs(array('class' => 'text date', 'readonly' => '1'));
		
		$dateLastLoginTo = new Zend_Form_Element_Text('dateLastLoginTo');
    	$dateLastLoginTo->setLabel('Date Last Login To:')
    				   ->addFilter('StringTrim')
					   ->addFilter('StripTags') 				 	
					   ->setAttribs(array('class' => 'text date', 'readonly' => '1'));
								   					   
		$this->addElements(array($emailSearch, $name, $active, $dateInsertedFrom, $dateInsertedTo, $dateLastLoginFrom, $dateLastLoginTo));
    }
}