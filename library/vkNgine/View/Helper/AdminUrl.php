<?php
class vkNgine_View_Helper_AdminUrl extends Zend_View_Helper_Url
{
    /**
     * construct the admin url
     * 
     * @param string $action
     * @param string $controller
     * @param array $args
     * @return string
     */
    public function adminUrl($action = 'index', $controller = 'index', $args = array())
    {
    	$_args = array_merge($args, array(
    		'action' => $action,
    		'controller' => $controller
    	));
    	
    	return $this->url(
    		$_args,
    		null,
    		true
    	);
    }
}
