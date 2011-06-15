<?php

class vkNgine_Filter_DateArray implements Zend_Filter_Interface
{
	protected $_dateFormat = 'yyyy-mm-dd';
	
	public function __construct($format = null)
	{
		if ($format){
			$this->setDateFormat($format);
		}
	}
	
	public function setDateFormat($format)
	{
		$this->_dateFormat = $format;
		return $this;
	}
	
	public function getDateFormat()
	{
		return $this->_dateFormat;
	}
	
	public function getEmptyDate()
	{
		return preg_replace('/[y|m|d]/', '0', $this->getDateFormat());
	}
	
	public function filter($value)
	{
		if (is_array($value)){
			$value = vkNgine_Form_Element_Date::getDatestring($value, $this->getDateFormat());
		}
		
		if ($value == $this->getEmptyDate()){
			$value = null;
		}
		
		return $value;
	}
}

?>