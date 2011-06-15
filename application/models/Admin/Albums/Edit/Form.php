<?php
class Model_Admin_Albums_Edit_Form extends Model_Form_Abstract
{
    public function init()
    {
    	$this->setAttribs(
    		array('name'  => 'albumManagement', 
    			  'class' => 'search_form general_form')
    	);
    	
    	$albumId = new Zend_Form_Element_Hidden('albumId');
		$albumId->removeDecorator('HtmlTag')		
			    ->removeDecorator('Label')
  			    ->setLabel(null);
    	 	 
    	$albumName = new Zend_Form_Element_Text('albumName');
    	$albumName->setRequired(true)
	    	      ->setLabel('Album Name:')
	    		  ->addFilter('StringTrim')
				  ->addFilter('StripTags') 				 	
				  ->setAttrib('class', 'text medium_input');
						  
    	$albumYear = new Zend_Form_Element_Select('albumYear');
    	$albumYear->setRequired(true)
	    	      ->setLabel('Album Year:')
	    		  ->addFilter('StringTrim')
				  ->addFilter('StripTags')  	
				  ->setAttrib('class', 'text medium_input');
		
		$albumLocation = new Zend_Form_Element_Text('albumLocation');
    	$albumLocation->setRequired(true)
		    	      ->setLabel('Album Location:')
		    		  ->addFilter('StringTrim')
					  ->addFilter('StripTags') 				 	
					  ->setAttrib('class', 'text medium_input');
					  
		$isPrivate = new Zend_Form_Element_Radio('isPrivate');		
		$isPrivate->setLabel('Album Private:')
				  ->addFilter('StringTrim')
				  ->addFilter('StripTags')
				  ->addMultiOptions(array(
			         'PRIVATE' => 'Private',
			         'PUBLIC' => 'Public'
			      ))			      
			      ->setDecorators(array(
				     'ViewHelper',
				     array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'tightLabel')),
    				 array('Label', array('tag' => 'div'),
				  )))
				  ->setSeparator('');
						 				  				  
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setDecorators(array(new vkNgine_Admin_Form_Decorator_Submit(array('id' => 'submit', 'type' => 'send_form_btn', 'name' => 'Save'))));
		
    	$this->addElements(array(
    				$albumId,	
					$albumName,	
					$albumYear,	
					$albumLocation,	
					$isPrivate,					
					$submit
		));		
    }
    
    public function setHidden($value)
    {
    	$this->getElement('albumId')->setValue($value);
    }
    
    public function getYears()
	{
		$year = new Zend_Date();
		
    	$element = $this->getElement('albumYear');
		$element->addMultiOption(null, 'Select one');
		
    	for($i=2007;$i<=$year->get('Y'); $i++){		
    		$element->addMultiOption($i, $i);
    	}
    }     
}