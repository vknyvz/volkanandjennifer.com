<?php
class Model_Admin_Website_Settings_Form extends Model_Form_Abstract
{
    public function init()
    {
    	$this->setAttribs(
    		array('name'  => 'generalSettings', 
    			  'class' => 'search_form general_form')
    	);
    	 	 
    	$title = new Zend_Form_Element_Text('title');
    	$title->setRequired(true)
    	      ->setLabel('Site title:')
    		  ->addFilter('StringTrim')
			  ->addFilter('StripTags') 				 	
			  ->setAttrib('class', 'text medium_input');

		$description = new Zend_Form_Element_Textarea('description');
    	$description->setLabel('Site description:')
    			    ->addFilter('StringTrim')
			        ->addFilter('StripTags')
	 			    ->setAttribs(array('cols' => 50, 'rows' => 5, 'class' => 'text'))
	 			    ->addValidator('stringLength', false, array(3, 255));
			 	
		$keywords = new Zend_Form_Element_Textarea('keywords');
    	$keywords->setLabel('Site keywords:')
    			 ->addFilter('StringTrim')
				 ->addFilter('StripTags')				 	  
				 ->setAttribs(array('cols' => 50, 'rows' => 5, 'class' => 'text'))
				 ->addValidator('stringLength', false, array(3, 255));
					     
		$domain = new Zend_Form_Element_Text('domain');
    	$domain->setRequired(true)
    	       ->setLabel('Domain:')
    		   ->addFilter('StringTrim')
			   ->addFilter('StripTags') 				 	
			   ->setAttrib('class', 'text medium_input');
			   	 		 			    
    	$googleAnalytics = new Zend_Form_Element_Textarea('googleAnalytics');
    	$googleAnalytics->setLabel('Google Analytics Code:')
    					->addFilter('StringTrim')
					    ->addFilter('StripTags')			   
					    ->setAttribs(array('cols' => 70, 'rows' => 10, 'class' => 'text'))
					    ->addValidator('stringLength', false, array(3, 500));
			  
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setDecorators(array(new vkNgine_Admin_Form_Decorator_Submit(array('id' => 'submit', 'type' => 'send_form_btn', 'name' => 'Save'))));
		
    	$this->addElements(array(	
					$title,
					$description,
					$keywords,
					$domain,
					$googleAnalytics,					
					$submit
		));		
    }
}