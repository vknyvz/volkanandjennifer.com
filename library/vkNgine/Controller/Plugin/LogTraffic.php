<?php 
class vkNgine_Controller_Plugin_LogTraffic extends Zend_Controller_Plugin_Abstract
{
    /**
     * log the current request in the traffic log 
     */
    public function postDispatch($request)
    {
		$log = new Model_Traffic_Log();
		$log->logHit($request);    	
    }
}