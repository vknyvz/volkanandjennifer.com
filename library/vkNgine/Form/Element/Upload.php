<?php
class vkNgine_Form_Element_Upload extends Zend_Form_Element_Hidden
{	
	public function render(Zend_View_Interface $view = null)
	{
        if (null !== $view) {
           $this->setView($view);
        }
		
        $content = '';
        foreach ($this->getDecorators() as $decorator) {
            $decorator->setElement($this);
                    
            $content = $decorator->render($content);
        }
        
        return $content;
	}
}