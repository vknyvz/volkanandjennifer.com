<?php 
class Model_vkNgine_Settings extends Zend_Db_Table_Row
{	
	/**
	 * get setting title
	 * 
	 * @return Model_vkNgine_Settings
	 */
	public function getTitle()
	{
		return $this['title'];
	}
	
	/**
	 * get description meta tag info
	 * @return Model_vkNgine_Settings
	 */
	public function getDescription()
	{
		return $this['description'];
	}
	
	/**
	 * get keywords meta tag info
	 * 
	 * @return Model_vkNgine_Settings
	 */
	public function getKeywords()
	{
		return $this['keywords'];
	}
	
	/**
	 * get google analytics code
	 * 
	 * @return Model_vkNgine_Settings
	 */
	public function getGoogleAnalytics()
	{
		return $this['googleAnalytics'];
	}
	
	/**
	 * fetch full address of the application
	 * 
	 * @return string  
	 */
	public function fetchAddress() {

		if (!empty($this['domain'])) {
		
			$this['domain'] = str_replace('www.', '', $this['domain']);
			
			$address = 'http://www.' . $this['domain'] . '/';			 
		}
		return $address;
	}
	
	/**
	 * fetch short address of the application
	 * 
	 * @return string  
	 */
	public function fetchShortAddress() {

		$url = $this->fetchAddress($this['domain']);
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		
		$url = substr($url, 0, -1);
		
		return $url;
	}	
}