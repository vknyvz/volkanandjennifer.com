<?php
class Model_Admin_Pictures_Edit_Form extends Model_Form_Abstract
{
    public function init()
    {
    	$this->setAttribs(
    		array('action'  => '/pictures/edit',
    			  'name'  	=> 'pictureManagement', 
    			  'class' 	=> 'search_form general_form',
    		 	  'enctype' => Zend_Form::ENCTYPE_MULTIPART)
    	);
    	
    	$pictureId = new Zend_Form_Element_Hidden('pictureId');
		$pictureId->removeDecorator('HtmlTag')		
			      ->removeDecorator('Label')
  			      ->setLabel(null);
    	 	 
    	$albumId = new Zend_Form_Element_Select('albumId');
    	$albumId->setRequired(true)
	    	    ->setLabel('Album:')
	    		->addFilter('StringTrim')
				->addFilter('StripTags') 				 	
				->setAttrib('class', 'text medium_input');

		$upload = new vkNgine_Form_Element_Upload('upload');			   
		$upload->clearDecorators()
               ->addDecorator('viewScript', array(
                 'viewScript' => '_upload.phtml',
                 'placement'  => '',
               ));
	               
	    $pictureids = new Zend_Form_Element_Hidden('pictureids');
		$pictureids->removeDecorator('HtmlTag')		
			       ->removeDecorator('Label')
  			       ->setLabel(null);           	               
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setDecorators(array(new vkNgine_Admin_Form_Decorator_Submit(array('id' => 'submit', 'type' => 'send_form_btn', 'name' => 'Save'))));
		
    	$this->addElements(array(
    				$pictureId,	
					$albumId,					
					$upload,					
					$pictureids,
					$submit
		));		
    }
    
    public function setHidden($value)
    {
    	$this->getElement('pictureId')->setValue($value);
    }

    public function getAlbums(Model_Albums $album)
	{
    	$element = $this->getElement('albumId');
		$element->addMultiOption(null, 'Select one');
		
    	foreach($album->fetchAlll() as $albums) {	    		
    		$element->addMultiOption($albums['albumId'], $albums['albumName'] . ' (' . $albums['albumYear'] . ')');
    	}
    } 
}