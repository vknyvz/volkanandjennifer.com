<?php
class Model_Picture extends Zend_Db_Table_Row
{	

	/**
	 * get picture id 
	 * 
	 * @return Model_Picture
	 */
	public function getId()
	{
		return $this['pictureId'];
	}

	/**
	 * get picture file name
	 * 
	 * @return Model_Picture
	 */
	public function getFileName()
	{
		return $this['pictureName'];
	}
}