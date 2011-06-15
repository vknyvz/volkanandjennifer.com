<?php
class Model_Pictures_Tokens extends Model_DbTable_Abstract
{
	protected $_name = 'pictures_tokens';
	protected $_primary = 'tokenId';
	protected $_saveInsertDate = true;	
	
	/**
	 * fetch a token
	 * 
	 * @param Model_Picture $picture
	 * @param string $token
	 */
	public function fetch($token) {
		$select = $this->select();
		
		$select->where('token = ?', $token);
		
		return $this->fetchRow($select);
	}	
	
	/**
	 * Check if a token exist for the picture
	 * 
	 * @param Model_Picture $picture
	 * @return Ambiguous
	 */
	public function isValid(Model_Picture $picture)
	{
		$select = $this->select();
		
		$select->where('pictureId = ?', (int) $picture->getId());
		$select->where('dateExpires >= ?', date('Y-m-d H:00:00'));
		
		$data = $this->fetchRow($select);
		return $data['token'];		
	}
	
	/**
	 * check if there's a valid token first, if so, return it
	 * or create a random token and return that
	 * 
	 * @param Model_Picture $picture
	 */
	public function insert(Model_Picture $picture) 
	{
		$token = $this->isValid($picture);
		
		if($token) {
			return $token;
		}
		
		// make random token
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
    	srand((double)microtime()*1000000);
    	$i = 0;
    	$token = '' ;

    	while ($i < 40) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$token = $token . $tmp;
        	$i++;
    	}
    	
    	$config = Zend_Registry::get('config');
    	
    	$interval = new Zend_Date();
    	$interval->add($config->token->length, Zend_Date::TIMES);
    	
    	$data = array (
    		'token' 		=> $token,
    		'pictureId' 	=> $picture->getId(),
    		'dateExpires'	=> $interval->toString('YYYY-MM-dd HH:mm:ss'),
    	);
    			
		parent::insert($data);
		
		return $token;
	}
	
	/**
	 * update a token
	 * 
	 * @param int $tokenId
	 * @param array $data
	 */
	public function update($tokenId, $data) 
	{
		$where = $this->getAdapter()->quoteInto($this->getPrimary() . ' = ?', $tokenId);
				
		return parent::update($data, $where);
	}
	
	/**
	 * delete a package
	 * 
	 * @param int $tokenId
	 */
	public function delete($tokenId)
	{
		$where = $this->getAdapter()->quoteInto($this->getPrimary() . ' = ?', (int) $tokenId);	
		
		parent::delete($where);
	}	
}