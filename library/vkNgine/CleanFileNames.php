<?php
class vkNgine_CleanFileNames extends Zend_Controller_Plugin_Abstract
{
	/**
	 * cleans a file name
	 * 
	 * @param mixed $value
	 * @return mixed
	 */
	public function clean($value)
	{
		$patternCounter=0;
		$patterns[$patternCounter] = '/[\x21-\x2d]/u'; // remove range of shifted characters on keyboard - !"#$%&'()*+,-
		$patternCounter++;
		
		$patterns[$patternCounter] = '/[\x5b-\x60]/u'; // remove range including brackets - []\^_`
		$patternCounter++;
		
		$patterns[$patternCounter] = '/[\x7b-\xff]/u'; // remove all characters above the letter z.  This will eliminate some non-English language letters
		$patternCounter++;
		
		$patterns[$patternCounter] = '/\@/u'; // remove @
		$patternCounter++;
		
		$patterns[$patternCounter] = '/\ /u'; // replace spaces
		$patternCounter++;
		
		$replacement ="_";
		
		return preg_replace($patterns, $replacement, $value);
	}
}	