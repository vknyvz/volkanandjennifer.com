<?php
class vkNgine_View_Helper_Dateformat extends Zend_View_Helper_HtmlElement
{
    /**
     * formats a given date with the specific options
     * 
     * @param string $date
     * @param bool $time
     * @param bool $long
     * @return string
     */
    public function dateFormat($date, $time = false, $long = false)
    {
    	if (!empty($date))
    	{
	    	// get config for date format
	    	$config = Zend_Registry::get('config');
    	
	    	// convert date string to valid time value
	    	if (is_string($date))
	    	{
	    		if (!preg_match('/-00/', $date))
	    		{
	    			$sdate = $date;
	    			$date = strtotime($sdate);
	    		}
	    	}
	    	
	    	if (is_int($date))
	    	{
	    		// figure out format to use
	    		$_config = $config->dateformat;
	    		$format = $time ? $_config->datetime : $_config->date;
	    		$format = $long ? $format->long : $format->short;
	    		
	    		// hide year? (year = 0000)
	    		if (!empty($sdate) && preg_match('/^0{2,4}\-/', $sdate))
	    		{
	    			$format = preg_replace('/[^\w]*y/i', '', $format);
	    		}
	    		
	    		return date($format, $date);
	    	}
    	}
    	
    	return '';
    }
}