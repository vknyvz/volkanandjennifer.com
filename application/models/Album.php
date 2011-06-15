<?php
class Model_Album extends Zend_Db_Table_Row
{	

	/**
	 * get album id
	 * 
	 * @return Model_Album
	 */
	public function getId()
	{
		return $this['albumId'];
	}
	
	/**
	 * get album name
	 * 
	 * @return Model_Album
	 */
	public function getAlbumName()
	{
		return $this['albumName'];
	}		
	
	/**
	 * get album name with year
	 * 
	 * @return string
	 */
	public function getAlbumNamewithYear()
	{
		return sprintf('%s (%s)', $this['albumName'], $this['albumYear']);
	}
	
	/**
	 * get album's privacy level
	 * 
	 * @return Model_Album
	 */
	public function getPrivacy()
	{
		return $this['isPrivate'];
	}
}