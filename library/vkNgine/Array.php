<?php
class vkNgine_Array extends Zend_Controller_Plugin_Abstract
{	
	/**
	 * prints out an array nicely
	 * 
	 * @param array $arr
	 */
	public static function x($arr)
	{
		echo '===============';
			echo '<pre>';
				print_r($arr);
			echo '</pre>';
		echo '===============';
	}
}
?>