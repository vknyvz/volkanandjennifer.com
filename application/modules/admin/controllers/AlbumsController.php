<?php
class AlbumsController extends vkNgine_Admin_Controller
{
	public function init()
	{
		parent::init();
		
		$view = Zend_Registry::get('view');
		$view->headTitle('Album Management');	

		$acl = Zend_Registry::get('acl');
		if (!$acl->isAllowed('albums')) {
			throw new vkNgine_Exception('Permission error');
		} 
	}
	
    public function indexAction()
    {	
    	$modelAlbums = new Model_Albums();   
    	$modelPictures = new Model_Pictures();
    	    	
    	$searchParams = $this->getQueryStringParams();
    	
    	// ordering 
    	$page = $this->_getParam('page', 1);
		$searchParams['page'] = $page;
    	$orderBy = $this->_getParam('orderBy', 'albumName');
    	$searchParams['orderBy'] = $orderBy;
    	$orderBySort = $this->_getParam('orderBySort', 'ASC');
    	$searchParams['orderBySort'] = $orderBySort;   

    	$albumTotal = array();
		foreach ($modelAlbums->fetchAlll() as $album) {
			$albumTotal[$album['albumId']] = $modelAlbums->fetchTotalPicturesByAlbum($album['albumId']);
			$albumPrivacy[$album['albumId']] = ($album['isPrivate'] == 'PUBLIC' ? 'Public' : 'Private');		
		}	
		
		$this->view->albumPrivacy = $albumPrivacy;
		$this->view->albumTotal = $albumTotal;
    	$this->view->params = $searchParams; 
    	$this->view->albums = $modelAlbums->fetchAllWithPagination($page, $orderBy, $orderBySort, $searchParams);
    }
    
    public function editAction()
    {
    	$modelAlbums = new Model_Albums();
    	
    	$form = self::getAdminAlbumEditForm();    
    	
    	$albumId = $this->_getParam('albumId');
		$albumId = (int) $albumId;
		
    	if($albumId) {
			$populateData = array();
			
			$album = $modelAlbums->fetch($albumId);
			
			if (count($album) > 0) {					
				$populateData = $album->toArray();
			}
			
			$form->setHidden($albumId);
	    	$form->populate($populateData);
	    	$this->view->albumId = $albumId;
		}
		
    	$request = $this->getRequest();
    	
    	if ($request->isPost()) {
			$post = $request->getPost();
		
			if ($form->isValid($post)) {
		        $values = $form->getValues();		        
		        
		        if($albumId) {
		        	$modelAlbums->update($albumId, $values);
		        }
		        else {
		        	$insert = $modelAlbums->insert($values);		        	
		        	if($insert) {
		        		$config = Zend_Registry::get('config');
		        		
		        		mkdir($config->uploadDir->path . '/' . $insert);
		        		
		        		$dummyFile = "index.html";
						$createFile = fopen($config->uploadDir->path . '/' . $insert . '/' . $dummyFile, 'w');
						fclose($createFile);
		        	}
		        }
		        $this->view->infoMessage = 'Album was added successfully';

		        $this->_helper->redirector('index');
			}
		}	 
    	
    	$this->view->errors = $form->errors();
    	$this->view->form = $form;       	
    }
    
    public function browseAction()
    {
    	$modelPictures = new Model_Pictures();
    	$modelAlbums = new Model_Albums();
    	
    	$albumId = $this->_getParam('albumId');
		$albumId = (int) $albumId;
		
		$album = $modelAlbums->fetch($albumId);
		
		$request = $this->getRequest();
    	
    	if ($request->isPost()) {
			$post = $request->getPost();
			
			foreach($post['pictureDescription'] as $pictureId => $text){
				$data = array('pictureDescription' => $text, 
							  'dateUpdated' 	   => date('Y-m-d H:i:s'));
				$modelPictures->update($pictureId, $data);
			}

			$this->_helper->redirector('index');
    	}
    	
    	$this->view->albumName = $album->getAlbumName();
		$this->view->pictures = $modelPictures->fetchByAlbum($albumId);    	
    }
    
    public function deleteAction()
    {
    	$modelAlbums = new Model_Albums();
    	
    	$albumIds = $this->_getParam('albumIds');
    	
    	$albumIdsArray = explode(',', $albumIds);
    	    
    	foreach($albumIdsArray as $albumId) {
    		if ($albumId) {
								
				$album = $modelAlbums->fetch($albumId);
    	
		    	if(!$album instanceof Model_Album) { 
		    		throw new vkNgine_Exception('Album not found!'); 
				}
						
				$modelAlbums->delete($albumId);
			}	
		}
		
		exit;		    	
    }
    
    private function getAdminAlbumEditForm()
    {
    	$form = new Model_Admin_Albums_Edit_Form(array(
			'method' => 'post',
			'action' => $this->_helper->url('edit', 'albums'),
		));		
		
		$form->getYears();
				
		return $form;
    } 
}