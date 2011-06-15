<?php

class Dashboard_IndexController extends vkNgine_Public_Controller
{
	public function init()
	{
		parent::init();

		$acl = Zend_Registry::get('acl');
		if (!$acl->isAllowed('public')) {
			throw new vkNgine_Exception('Permission error');
		}    
	}
	
    public function indexAction()
    {    
    	$pageVars = array('body' 		=> 'body_home video_background',
    					  'idHome'		=> 'home',
    					  'idAbout' 	=> null,
    					  'idPortfolio' => null,
    					  'background' 	=> 'random');    	
    	$this->view->pageVars = $pageVars;
    }	
    
    public function aboutAction()
    {
    	$pageVars = array('body' 	 	=> 'body_about',
    					  'idHome'	 	=> null,
    					  'idAbout'	 	=> 'about',
    					  'idPortfolio' => null,
    					  'background' 	=> null	);    	
    	$this->view->pageVars = $pageVars;
    }
    
    public function galleryAction()
    {
    	$modelAlbums = new Model_Albums();    	
    	$modelPictures = new Model_Pictures();
    	$modelPicturesTokens = new Model_Pictures_Tokens();
    	
    	$pageVars = array('body' 	 	=> 'body_portfolio body_prettyphoto body_gallery_4col_pp',
    					  'idHome'	 	=> null,
    					  'idAbout'	 	=> null,
    					  'idPortfolio' => 'portfolio',
    					  'background' 	=> null);    	
    	
    	$albumId = $this->_getParam('album');
		$albumId = (int) $albumId;
		
    	$albumTotal = array();
		foreach ($modelAlbums->fetchAlll('ALL', 'PUBLIC') as $album) {
			$albumTotal[$album['albumId']] = $modelAlbums->fetchTotalPicturesByAlbum($album['albumId']);		
		}
		
    	$randomPictureId = array();
		foreach ($modelPictures->fetchByAlbum($albumId) as $picture) {
			 $randomPictureId[$picture['pictureId']] = $modelPicturesTokens->insert($picture);
		}
		
		$this->view->randomPictureId = $randomPictureId;		
		$this->view->albumTotal = $albumTotal;    	
    	$this->view->album = $modelAlbums->fetch($albumId);
    	$this->view->pictures = $modelPictures->fetchByAlbum($albumId);
    	$this->view->pageVars = $pageVars;    	    	
    }
    
    public function showAction()
    {    	
    	$modelPicturesTokens = new Model_Pictures_Tokens();
    	$modelPictures = new Model_Pictures();
    		
    	$image = $this->_getParam('image');
		$thumbnail = $this->_getParam('thumbnail');
		
		if($image) {
			$pictureInfo = $modelPicturesTokens->fetch($image);
		}
		else {
			$pictureInfo = $modelPicturesTokens->fetch($thumbnail);
		} 
		
		$picture = $modelPictures->fetch($pictureInfo['pictureId']);
		
		if($picture instanceof Model_Picture) {
			header("Content-type: image/jpeg");
			
			$file = fopen('_dB/' . $picture['albumId'] . '/' . ($image ? $picture['pictureName'] : $picture['thumbnailName']), 'r');
			
			print(fread($file, 10000000));
			
			fclose($file);		
			
			exit;
		}
		
		$this->view->settings = Zend_Registry::get('settings');
    }
    
	public function pictureCountAction()
    {
    	$modelPicturesTokens = new Model_Pictures_Tokens();
    	$modelPictures = new Model_Pictures();
    		
    	$token = $this->_getParam('token');
		
		$pictureInfo = $modelPicturesTokens->fetch($token);
		
		$modelPictures->update($pictureInfo['pictureId'], array('count' => new Zend_Db_Expr('count + 1')));
		
		echo 'Picture ID#' . $pictureInfo['pictureId'] . ' is being viewed.';
		exit;
    }
    
    public function notPermittedAction()
    {    	  	
    }
}