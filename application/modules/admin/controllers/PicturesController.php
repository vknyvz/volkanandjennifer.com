<?php
class PicturesController extends vkNgine_Admin_Controller
{
	public function init()
	{
		parent::init();
		
		$view = Zend_Registry::get('view');
		$view->headTitle('Picture Management');	

		$acl = Zend_Registry::get('acl');
		if (!$acl->isAllowed('pictures')) {
			throw new vkNgine_Exception('Permission error');
		} 
	}
	
    public function indexAction()
    {
    	$modelPictures = new Model_Pictures();    	
    	
    	$searchParams = $this->getQueryStringParams();
    	
    	// ordering 
    	$page = $this->_getParam('page', 1);
		$searchParams['page'] = $page;
    	$orderBy = $this->_getParam('orderBy', 'pictureId');
    	$searchParams['orderBy'] = $orderBy;
    	$orderBySort = $this->_getParam('orderBySort', 'DESC');
    	$searchParams['orderBySort'] = $orderBySort;   
		
    	$modelAlbums = new Model_Albums();
    	
    	$albumName = array();
		foreach ($modelAlbums->fetchAll() as $album) {
			$albumName[$album['albumId']] = '<b>Album:</b> ' . $album['albumName'].' <b>Year:</b> ' . $album['albumYear'];			
		}
		
		$config = Zend_Registry::get('config');
		
		$pictureInfo = array();
    	foreach ($modelPictures->fetchAll() as $picture) {    		
			$pictureInfo['filesize'][$picture['pictureId']] = filesize($config->uploadDir->path . '/' . $picture['albumId'] . '/' . $picture->pictureName);			
			$pictureInfo['dimensions'][$picture['pictureId']] = getimagesize($config->uploadDir->path . '/' . $picture['albumId'] . '/' . $picture->pictureName);	
			$pictureInfo['filesizeThumbnail'][$picture['pictureId']] = filesize($config->uploadDir->path . '/' . $picture['albumId'] . '/' . $picture->thumbnailName);			
			$pictureInfo['dimensionsThumbnail'][$picture['pictureId']] = getimagesize($config->uploadDir->path . '/' . $picture['albumId'] . '/' . $picture->thumbnailName);			
		}
    			
		$this->view->albumName = $albumName;
		$this->view->pictureInfo = $pictureInfo;
    	$this->view->params = $searchParams; 
    	$this->view->pictures = $modelPictures->fetchAllWithPagination($page, $orderBy, $orderBySort, $searchParams);    		
    }
    
    public function editAction()
    {    	    	
    	$modelPictures = new Model_Pictures();
    	
    	$form = self::getAdminPictureEditForm();    
    	
    	$request = $this->getRequest();
    	
    	if ($request->isPost()) {
    		
			$post = $request->getPost();
		
			if($form->isValid($post))
			{
			    $values = $form->getValues();		        
			    
		        if($pictureId) {
		        	$values['dateUpdated'] = date('Y-m-d H:i:s');	
		        	$modelPictures->update($pictureId, $values);
		        	
		        	$this->view->infoMessage = 'Picture was edited successfully';
		        }
		        else {		        	
					$config = Zend_Registry::get('config');
					
		        	$pictureIds = explode(' ', $values['pictureids']);
		        	
		        	foreach($pictureIds as $pictureId){
		        		$picture = $modelPictures->fetch($pictureId);
		        		$name = vkNgine_CleanFileNames::clean($picture->getFileName());
		        		
		        		// original image
		        		$originalSize = 'aId_' . $values['albumId'] .'_original_'.  $name; 						
						$filterFileRename = new Zend_Filter_File_Rename(array('target' => $config->uploadDir->path . '/' . $values['albumId'] . '/' . $originalSize, 'overwrite' => true));
 						$filterFileRename->filter($config->uploadDir->public . $picture->getFileName());

 						// medium image
						$thumber = new vkNgine_Thumbnail($config->uploadDir->path . '/' . $values['albumId'] . '/' . $originalSize);
						$thumber->adaptiveResize(160, 110);
						$mediumSize = 'aId_' . $values['albumId'] .'_medium_'.  $name; 
						$thumber->save($config->uploadDir->path . '/' . $values['albumId'] . '/' . $mediumSize);
						
						$data['albumId'] = $values['albumId'];
						$data['pictureName'] = $originalSize;
						$data['thumbnailName'] = $mediumSize;
						$data['dateUpdated'] = date('Y-m-d H:i:s');	
						$modelPictures->update($pictureId, $data);
		        	}	
		        	
		        	$this->_helper->redirector('index');
					
		        	$this->view->infoMessage = 'Picture was added successfully';
		        }
			}
		}	 
    	
    	$this->view->errors = $form->errors();
    	$this->view->form = $form;       	
    }      
    
    public function deleteAction()
    {
    	$modelPictures = new Model_Pictures();
    	
    	$pictureIds = $this->_getParam('pictureIds');
    	
    	$pictureIdsArray = explode(',', $pictureIds);
    	    
    	foreach($pictureIdsArray as $pictureId) {
    		if ($pictureId) {
								
				$picture = $modelPictures->fetch($pictureId);
    	
		    	if(!$picture instanceof Model_Picture) { 
		    		throw new vkNgine_Exception('Picture not found!'); 
				}
						
				$modelPictures->delete($pictureId);
			}	
		}		
		exit;		    	
    }
    
    private function getAdminPictureEditForm()
    {
    	$form = new Model_Admin_Pictures_Edit_Form(array(
			'method' => 'post',
			'action' => $this->_helper->url('edit', 'pictures'),
		));		
		
		$modelAlbums = new Model_Albums();

		$form->getAlbums($modelAlbums);
		
		return $form;
    } 
    
	protected function _findexts($filename)
	{		
		$filename = strtolower($filename) ;		
		$exts = @split('[/\\.]', $filename) ;		
		$n = count($exts)-1;		
		$exts = $exts[$n];
		
		return $exts;	
	}
}