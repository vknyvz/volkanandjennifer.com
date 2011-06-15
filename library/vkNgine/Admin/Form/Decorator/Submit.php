<?php
class vkNgine_Admin_Form_Decorator_Submit extends Zend_Form_Decorator_Abstract 
{
	var $_id;
	var $_type;
	var $_name;
	
	public function __construct($options = null)
	{	
		$this->_id = $options['id'];		
		$this->_type = $options['type'];
		$this->_name = $options['name'];
	}
	
	public function buildInput() 
	{
        $element = $this->getElement();
        $helper  = $element->helper;
              
        return $element->getView()->$helper(        	   
	           $this->_id,
	           $element->getValue(),
	           $element->getAttribs(),	 
	           $element->options
        );
	}	
	
	public function render($content)
    {
    	$element = $this->getElement();
        
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        if (null === $element->getView()) {
            return $content;
        }

        $input = $this->buildInput();		
        
        $output = '<div id="row-submit" class="row">
        		   <div class="buttons buttonsSubmit save">
							<ul style="clear:left">
								<li class="list_no_item">
									<span class="button ' . $this->_type . '"><span>
									<span>' . $this->_name . '</span></span>
										' . $input . '
									</span>
								</li>
							</ul>
				   </div>
				   </div>
				   <br />				   
				   ';
        
        return $output;        
    }
}
	