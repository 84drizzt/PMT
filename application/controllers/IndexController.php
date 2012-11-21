<?php

class IndexController extends My_Controller_Action {
	
	/**
	 * @var Zend_Log
	 */
	protected $logger = null;
	
	public function init() {
		parent::init();
	}
	
	public function setCurrentMenu($action) {
		$this->view->adminMenuClass ["$action"] = 'class="current"';
	}
	
	public function indexAction() {
		$this->_forward ( "manage-artwork" );
	}
	
	public function addArtistAction() {
		$this->setCurrentMenu ( "manage-artist" );
		$form = new Form_Artist ();
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Artists ();
				$table->insert ( $values );
				$this->_redirect ( "/admin/manage-artist" );
			}
		}
		
		$form->setAction ( "/admin/add-artist" )->setMethod ( "POST" );
		$this->view->form = $form;
	}
	
	public function editArtistAction() {
		if (! ( int ) $this->_request->getParam ( "id" )) {
			$this->_redirect ( "/admin/manage-artist" );
		} else {
			$artistId = ( int ) $this->_request->getParam ( "id" );
		}
		$this->setCurrentMenu ( "manage-artist" );
		$form = new Form_Artist ();
		$form->setAction ( "/admin/edit-artist/id/$artistId" )->setMethod ( "POST" );
		$form->getElement ( "photo" )->setRequired ( false );
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Artists ();
				$artist = $table->fetchRow ( "id = $artistId" );
				if (! $artist) {
					throw new Zend_Exception ( "指定的艺术家不存在" );
				}
				if ($values ["photo"] == null) {
					unset ( $values ["photo"] );
				}
				$table->update ( $values, "id=$artistId" );
				$this->_redirect ( "/admin/manage-artist" );
			} else {
				$this->logger->debug ( "No" );
				$this->logger->debug ( $values );
			}
		} else {
			$table = new Model_DbTable_Artists ();
			$artist = $table->fetchRow ( "id = $artistId" );
			if ($artist) {
				$form->setDefaults ( $artist->toArray () );
			} else {
				throw new Zend_Exception ( "指定的艺术家不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	public function manageArtistAction() {
		$this->view->title = "艺术家管理";
		$this->setCurrentMenu ( "manage-artist" );
		$table = new Model_DbTable_Artists ();
		$this->view->artists = $table->fetchAll ();
	}
	
	public function manageBannerAction() {
		$this->view->title = "Banner管理";
		$this->setCurrentMenu ( "manage-banner" );
		$table = new Model_DbTable_Banners ();
		$this->view->banners = $table->fetchAll ();
	}
	
	public function manageArtworkAction() {
		$this->setCurrentMenu ( "manage-artwork" );
		$table = new Model_DbTable_ArtWorkInfoView();
		$this->view->artworks = $table->fetchAll ( null, "artist ASC" );
	}
	
	public function manageBannerPicAction() {
		$this->setCurrentMenu ( "manage-banner" );
		$id = $this->_getParam("id");
		if (!$id){
			$this->_redirect("/admin");
		}
		$bannerTable = new Model_DbTable_Banners();
		$this->view->banner = $bannerTable->fetchRow("id = $id");
		$table = new Model_DbTable_BannerPic();
		$this->view->pics = $table->fetchAll ( "banner_id = $id" );
	}
	
	public function manageNewsAction() {
		$this->setCurrentMenu ( "manage-news" );
		$table = new Model_DbTable_News ();
		$this->view->news = $table->fetchAll ();
	}
	
	public function manageInterviewAction() {
		$this->setCurrentMenu ( "manage-interview" );
		$table = new Model_DbTable_Interviews();
		$this->view->interviews = $table->fetchAll ();
	}
	
	public function manageExhibitionAction() {
		$this->setCurrentMenu ( "manage-exhibition" );
		$table = new Model_DbTable_Exhibitions ();
		$this->view->exhibitions = $table->fetchAll ();
	}
	
	public function manageCustomerAction() {
		$this->setCurrentMenu ( "manage-customer" );
		$this->view->title = "客户管理";
		
		$table = new Model_DbTable_Customers ();
		$this->view->customers = $table->fetchAll ( null, "register_on DESC" );
	}
	
	public function manageAppAction() {
		$this->setCurrentMenu ( "manage-app" );
		$this->view->title = "App管理";
		$table = new Model_DbTable_Apps();
		$apps = $table->fetchAll ();
		$this->view->apps = $apps;
	}
	
	public function addArtworkAction() {
		$this->view->title = "艺术品管理";
		$this->setCurrentMenu ( "manage-artwork" );
		
		$form = new Form_ArtWork ();
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_ArtWorks ();
				
				
				$matches = array();
    			if(preg_match('/^(\d+(?:\.\d)?)\s*x\s*(\d+(?:\.\d)?)(?:\s*cm)?(?:\s*x\s*(\d+(?:\.\d)?)(?:\s*cm))?$/', $values["size"], $matches)){
					$newValue = $matches[1] . " x " . $matches[2] . ($matches[3] ? (" x " .$matches[3] . " cm") : " cm");
					$inchValue = round($matches[1] * 0.3937, 1) . " x " . round($matches[2] * 0.3937, 1) 
										. ($matches[3] ? (" x " .round($matches[3] * 0.3937, 1) . " in") : " in");
					
					$values["c_size"] = $newValue;
					$values["e_size"] = $inchValue;
    			}else{
    				$values["e_size"] = "";
    			}
				
    			$styles = $values[style];
    			unset($values[style]);
    			
    			$subjects = $values[subject];
    			unset($values[subject]);
    			
				$id = $table->insert ( $values );
				$subjectTable = new Model_DbTable_ArtworkToSubject();
				$styleTable = new Model_DbTable_ArtworkToStyle();
				
				
				foreach ($subjects as $subject){
					$subjectTable->insert(array("artwork_id" => $id, "subject_id" => $subject));
				}
				
				foreach ($styles as $style){
					$styleTable->insert(array("artwork_id" => $id, "style_id" => $style));
				}
				
				
				$this->_redirect ( "/admin/manage-artwork" );
			}
		}
		
		$form->setAction ( "/admin/add-artwork" )->setMethod ( "POST" );
		$form->getElement ( "artist" )->setAttrib ( "onchange", "getRelationalArtworks();" );
		$this->view->form = $form;
	}
	
	public function editArtworkAction() {
		if (! ( int ) $this->_request->getParam ( "id" )) {
			$this->_redirect ( "/admin/manage-artwork" );
		} else {
			$artworkId = ( int ) $this->_request->getParam ( "id" );
		}
		$this->setCurrentMenu ( "manage-artwork" );
		$form = new Form_ArtWork ();
		$form->setAction ( "/admin/edit-artwork/id/$artworkId" )->setMethod ( "POST" );
		$form->getElement ( "photo" )->setRequired ( false );
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_ArtWorks ();
				$artwork = $table->fetchRow ( "id = $artworkId" );
				if (! $artwork) {
					throw new Zend_Exception ( "指定的艺术品不存在" );
				}
				if ($values ["photo"] == null) {
					unset ( $values ["photo"] );
				}
				$subjectTable = new Model_DbTable_ArtworkToSubject();
				$styleTable = new Model_DbTable_ArtworkToStyle();
				$subjectTable->delete("artwork_id = $artworkId");
				$styleTable->delete("artwork_id = $artworkId");
				
				
				foreach ($values["subject"] as $subject){
					$subjectTable->insert(array("artwork_id" => $artworkId, "subject_id" => $subject));
				}
				unset($values[subject]);
				foreach ($values["style"] as $style){
					$styleTable->insert(array("artwork_id" => $artworkId, "style_id" => $style));
				}
				unset($values[style]);
				
				$matches = array();
    			if(preg_match('/^(\d+(?:\.\d)?)\s*x\s*(\d+(?:\.\d)?)(?:\s*cm)?(?:\s*x\s*(\d+(?:\.\d)?)(?:\s*cm))?$/', $values["c_size"], $matches)){
					$newValue = $matches[1] . " x " . $matches[2] . ($matches[3] ? (" x " .$matches[3] . " cm") : " cm");
					$inchValue = round($matches[1] * 0.3937, 1) . " x " . round($matches[2] * 0.3937, 1) 
										. ($matches[3] ? (" x " .round($matches[3] * 0.3937, 1) . " in") : " in");
					$values["c_size"] = $newValue;
					$values["e_size"] = $inchValue;
    			}else{
    				$values["e_size"] = "";
    			}
				
				$table->update ( $values, "id=$artworkId" );
				$this->_redirect ( "/admin/manage-artwork" );
			} else {
				//				$this->logger->debug ( "No" );
			//				$this->logger->debug ( $values );
			}
		} else {
			$table = new Model_DbTable_ArtWorks ();
			$artwork = $table->fetchRow ( "id = $artworkId" );
			if ($artwork) {
				$values = $artwork->toArray ();
				
				$styleTable = new Model_DbTable_ArtworkToStyle();
				$styles = $styleTable->fetchAll("artwork_id = $artworkId");
				foreach ($styles as $style){
					$values ["style"][] = $style->style_id;
				}
				$subjectTable = new Model_DbTable_ArtworkToSubject();
				$subjects = $subjectTable->fetchAll("artwork_id = $artworkId");
				foreach ($subjects as $subject){
					$values ["subject"][] = $subject->subject_id;
				}
				
				$form->setDefaults ( $values );
			} else {
				throw new Zend_Exception ( "指定的艺术品不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	public function editExhibitionAction() {
		$this->setCurrentMenu ( "manage-exhibition" );
		$form = new Form_Exhibition ();
		if (! ( int ) $this->_request->getParam ( "id" )) {
			$this->_redirect ( "/admin/manage-exhibition" );
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
		}
		
		$form->setAction ( "/admin/edit-exhibition" )->setMethod ( "POST" );
		$form->getElement("homepage_cover")->setRequired(false);
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Exhibitions ();
				$exhibition = $table->fetchRow ( "id = id" );
				if (! $exhibition) {
					throw new Zend_Exception ( "指定的展览不存在" );
				}
				
				if(empty($values["homepage_cover"])){
					unset($values["homepage_cover"]);
				}
				
				
				
				$table->update ( $values, "id=$id" );
				$this->_redirect ( "/admin/manage-exhibition" );
			} else {
				$this->logger->debug ( "No" );
			}
		} else {
			$table = new Model_DbTable_Exhibitions ();
			$exhibition = $table->fetchRow ( "id = $id" );
			if ($exhibition) {
				$form->setDefaults ( $exhibition->toArray () );
			} else {
				throw new Zend_Exception ( "指定的展览不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	public function addExhibitionAction() {
		$this->setCurrentMenu ( "manage-exhibition" );
		$form = new Form_Exhibition ();
		$form->setAction ( "/admin/add-exhibition" )->setMethod ( "POST" );
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Exhibitions ();
				
				$table->insert ( $values );
				//				$this->logger->debug("Yes");
				//				$this->logger->debug($form->getValues());
				$this->_redirect ( "/admin/manage-exhibition" );
			}
		}
		$this->view->form = $form;
	}
	
	public function addNewsAction() {
		$this->setCurrentMenu ( "manage-news" );
		$form = new Form_News ();
		$form->setAction ( "/admin/add-news" )->setMethod ( "POST" );
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_News ();
				$table->insert ( $values );
				$this->_redirect ( "/admin/manage-news" );
			}
		}
		
		$this->view->form = $form;
	}
	
	public function addInterviewAction() {
		$this->setCurrentMenu ( "manage-interview" );
		$form = new Form_Interview();
		$form->setAction ( "/admin/add-interview" )->setMethod ( "POST" );
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$values["updated_on"] = date("Y-m-d H:i:s");
				$table = new Model_DbTable_Interviews();
				$table->insert ( $values );
				$this->_redirect ( "/admin/manage-interview" );
			}
		}
		
		$this->view->form = $form;
	}
	
	public function editNewsAction() {
		if (! ( int ) $this->_request->getParam ( "id" )) {
			$this->_redirect ( "/admin/manage-news" );
		} else {
			$newsId = ( int ) $this->_request->getParam ( "id" );
		}
		
		$this->setCurrentMenu ( "manage-news" );
		$form = new Form_News ();
		$form->setAction ( "/admin/edit-news" )->setMethod ( "POST" );
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_News ();
				$news = $table->fetchRow ( "id = $newsId" );
				if (! $news) {
					throw new Zend_Exception ( "指定的新闻不存在" );
				}
				if ($values ["photo"] == null) {
					unset ( $values ["photo"] );
				}
				$table->update ( $values, "id=$newsId" );
				$this->_redirect ( "/admin/manage-news" );
			} else {
			}
		} else {
			$table = new Model_DbTable_News ();
			$news = $table->fetchRow ( "id = $newsId" );
			if ($news) {
				$form->setDefaults ( $news->toArray () );
			} else {
				throw new Zend_Exception ( "指定的新闻不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	public function editInterviewAction() {
		if (! ( int ) $this->_request->getParam ( "id" )) {
			$this->_redirect ( "/admin/manage-interview" );
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
		}
		
		$this->setCurrentMenu ( "manage-interview" );
		$form = new Form_Interview();
		$form->setAction ( "/admin/edit-interview" )->setMethod ( "POST" );
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Interviews();
				$interview = $table->fetchRow ( "id = $id" );
				if (! $interview) {
					throw new Zend_Exception ( "指定的访谈不存在" );
				}
				
				if ($values ["photo"] == null) {
					unset ( $values ["photo"] );
				}
				$values["updated_on"] = date("Y-m-d H:i:s");
				$table->update ( $values, "id=$id" );
				$this->_redirect ( "/admin/manage-interview" );
			} else {
			}
		} else {
			$table = new Model_DbTable_Interviews();
			$interview = $table->fetchRow ( "id = $id" );
			if ($interview) {
				$form->setDefaults ( $interview->toArray () );
			} else {
				throw new Zend_Exception ( "指定的访谈不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	public function editBannerAction() {
		if (! ( int ) $this->_request->getParam ( "id" )) {
			$this->_redirect ( "/admin/manage-banner" );
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
		}
		
		$this->setCurrentMenu ( "manage-banner" );
		$form = new Form_Banner();
		$form->setAction ( "/admin/edit-banner" )->setMethod ( "POST" );
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Banners();
				$banner = $table->fetchRow ( "id = $id" );
				if (! $banner) {
					throw new Zend_Exception ( "指定的Banner不存在" );
				}
				
				$table->update ( $values, "id=$id" );
				$this->_redirect ( "/admin/manage-banner" );
			} else {
			}
		} else {
			$table = new Model_DbTable_Banners();
			$banner = $table->fetchRow ( "id = $id" );
			if ($banner) {
				$form->setDefaults ( $banner->toArray () );
			} else {
				throw new Zend_Exception ( "指定的Banner不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	
	
	public function editAppAction() {
		
		if (! ( int ) $this->_request->getParam ( "id" )) {
			$this->_redirect ( "/admin/manage-app" );
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
		}
		$this->setCurrentMenu ( "manage-app" );
		$form = new Form_App();
		$form->setAction ( "/admin/edit-app" )->setMethod ( "POST" );
		$form->getElement ( "snapshot" )->setRequired ( false );
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Apps();
				$app = $table->fetchRow ( "id = $id" );
				if (! $app) {
					throw new Zend_Exception ( "指定的App不存在" );
				}
				if ($values ["snapshot"] == null) {
					unset ( $values ["snapshot"] );
				}
				$values["updated_on"] = date("Y-m-d H:i:s");
				$table->update ( $values, "id=$id" );
				$this->_redirect ( "/admin/manage-app" );
			} else {
			}
		} else {
			$table = new Model_DbTable_Apps();
			$app = $table->fetchRow ( "id = $id" );
			if ($app) {
				$form->setDefaults ( $app->toArray () );
			} else {
				throw new Zend_Exception ( "指定的App不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	public function editVideoAction() {
		
		if (! ( int ) $this->_request->getParam ( "id" )) {
			$this->_redirect ( "/admin/manage-video" );
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
		}
		$this->setCurrentMenu ( "manage-video" );
		$form = new Form_Videos();
		$form->setAction ( "/admin/edit-video" )->setMethod ( "POST" );
		$form->getElement ( "snapshot" )->setRequired ( false );
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Videos();
				$video = $table->fetchRow ( "id = $id" );
				if (! $video) {
					throw new Zend_Exception ( "指定的视频不存在" );
				}
				if ($values ["snapshot"] == null) {
					unset ( $values ["snapshot"] );
				}
				$table->update ( $values, "id=$id" );
				$this->_redirect ( "/admin/manage-video" );
			} else {
			}
		} else {
			$table = new Model_DbTable_Videos();
			$video = $table->fetchRow ( "id = $id" );
			if ($video) {
				$form->setDefaults ( $video->toArray () );
			} else {
				throw new Zend_Exception ( "指定的视频不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	public function editBannerPicAction() {
		
		if (! ( int ) $this->_request->getParam ( "id" )) {
			$this->_redirect ( "/admin/manage-banner" );
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
		}
		$this->setCurrentMenu ( "manage-banner" );
		$form = new Form_BannerPic();
		$form->setAction ( "/admin/edit-banner-pic" )->setMethod ( "POST" );
		$form->getElement ( "image_file" )->setRequired ( false );
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_BannerPic();
				$banner = $table->fetchRow ( "id = $id" );
				if (! $banner) {
					throw new Zend_Exception ( "指定的Banner图片不存在" );
				}
				if ($values ["snapshot"] == null) {
					unset ( $values ["image_file"] );
				}
				$table->update ( $values, "id=$id" );
				$this->_redirect ( "/admin/manage-banner-pic/id/". $values['banner_id'] );
			} else {
			}
		} else {
			$table = new Model_DbTable_BannerPic();
			$banner = $table->fetchRow ( "id = $id" );
			if ($banner) {
				$form->setDefaults ( $banner->toArray () );
			} else {
				throw new Zend_Exception ( "指定的Banner图片不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	public function editCustomerAction() {
		$this->setCurrentMenu ( "manage-customer" );
		$id = ( int ) $this->_request->getParam ( "id" );
		if ($id <= 0) {
			throw new Zend_Exception ( "请指定客户ID" );
		}
		
		$form = new Form_Customer ();
		$form->setAction ( "/admin/edit-customer" )->setMethod ( "POST" );
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Customers ();
				$theCustomer = $table->fetchRow ( "id = $id" );
				if (! $theCustomer) {
					throw new Zend_Exception ( "指定的客户不存在" );
				}
				
				$table->update ( $values, "id=$id" );
				$this->_redirect ( "/admin/manage-customer" );
			} else {
				//$this->logger->debug ( "No" );
			}
		} else {
			$table = new Model_DbTable_Customers ();
			$theCustomer = $table->fetchRow ( "id = $id" );
			if ($theCustomer) {
				$form->setDefaults ( $theCustomer->toArray () );
			} else {
				throw new Zend_Exception ( "指定的客户不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	public function addAppAction() {
		$this->setCurrentMenu ( "manage-app" );
		$form = new Form_App();
		$form->setAction ( "/admin/add-app" )->setMethod ( "POST" );
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$values["updated_on"] = date("Y-m-d H:i:s");
				$table = new Model_DbTable_Apps();
				$table->insert ( $values );
				$this->_redirect ( "/admin/manage-app" );
			}
		}
		
		$this->view->form = $form;
	}
	
	public function addVideoAction() {
		$this->setCurrentMenu ( "manage-video" );
		$form = new Form_Videos();
		$form->setAction ( "/admin/add-video" )->setMethod ( "POST" );
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Videos();
				$table->insert ( $values );
				$this->_redirect ( "/admin/manage-video" );
			}
		}
		
		$this->view->form = $form;
	}
	
	public function addBannerPicAction() {
		$this->setCurrentMenu ( "manage-video" );
		$id = (int) $this->_getParam("id");
		$form = new Form_BannerPic();
		$form->setAction ( "/admin/add-banner-pic/id/$id" )->setMethod ( "POST" );
		if (!$id){
			$this->_redirect("/admin/manage-banner");
		}
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_BannerPic();
				$table->insert ( $values );
				$this->_redirect ( "/admin/manage-banner-pic/id/$id" );
			}
		}
		
		$this->view->form = $form;
	}
	
	public function loginAction() {
		if ($this->auth->getIdentity ()) {
			$this->_redirect ( "/index/" );
		} else {
			
//			$loginForm = new Form_AdminLogin ();
//			
//			if ($this->_request->isPost ()) {
//				if ($loginForm->isValid ( $_POST )) {
//					$adapter = new Zend_Auth_Adapter_DbTable ( Zend_Db_Table::getDefaultAdapter (), 'users', 'name', 'passwd', 'MD5(?)' );
//					
//					$adapter->setIdentity ( $loginForm->getValue ( 'name' ) );
//					
//					$adapter->setCredential ( $loginForm->getValue ( 'passwd' ) );
//					
//					$result = $this->auth->authenticate ( $adapter );
//					if ($result->isValid ()) {
//						$theUser = $adapter->getResultRowObject ( null, "passwd" );
//						
//						$this->auth->getStorage ()->write ( $theUser );
//						
//						
//						$this->view->message = '登录成功';
//						
//						$this->_redirect ( '/admin/' );
//						
//						return;
//					
//					} else {
//						
//						$this->view->message = '用户名或者密码错误！';
//					
//					}
//				
//				} else {
//					
//					$this->view->message = '请填写用户名和密码！';
//				
//				}
//			
//			}
//			
//			$this->view->form = $loginForm;
		}
	}
	
	public function logoutAction() {
		$this->auth->clearIdentity ();
		$this->_redirect ( "/admin/" );
	}
	
	public function testAction() {
		
	}
	
	public function deleteArtistAction() {
		if (( int ) $this->_request->getParam ( "id" )) {
			$artistId = ( int ) $this->_request->getParam ( "id" );
			$table = new Model_DbTable_Artists ();
			$artist = $table->fetchRow ( "id = $artistId" );
			if ($artist) {
				$artist->delete ();
			}
		}
		
		$this->_redirect ( "/admin/manage-artist" );
	}
	
	public function deleteAppAction() {
		if (( int ) $this->_request->getParam ( "id" )) {
			$id = ( int ) $this->_request->getParam ( "id" );
			$table = new Model_DbTable_Apps();
			$app = $table->fetchRow ( "id = $id" );
			if ($app) {
				$app->delete ();
			}
		}
		
		$this->_redirect ( "/admin/manage-app" );
	}
	
	public function deleteArtworkAction() {
		if (( int ) $this->_request->getParam ( "id" )) {
			$artworkId = ( int ) $this->_request->getParam ( "id" );
			$table = new Model_DbTable_ArtWorks();
			$artwork = $table->fetchRow ( "id = $artworkId" );
			if ($artwork) {
				$artwork->delete ();
			}
		}
		
		$this->_redirect ( "/admin/manage-artwork" );
	}
	
	public function manageArtworkCategoryAction() {
		$table = new Model_DbTable_Categories ();
		$this->view->categories = $table->fetchAll ( );
		
	}
	
	public function manageArtworkStyleAction() {
		$form = new Form_Styles ();
		$form->setAction ( "/admin/save-artwork-style" );
		$form->setMethod ( "POST" );
		
		$table = new Model_DbTable_Styles ();
		$this->view->styles = $table->fetchAll ();
		
		$this->view->form = $form;
	}
	
	public function manageArtworkTagAction() {
		$form = new Form_Tags ();
		$form->setAction ( "/admin/save-artwork-tag" );
		$form->setMethod ( "POST" );
		
		$table = new Model_DbTable_Tags ();
		$items = $table->fetchAll (null, "id DESC");
		$maxId = $items->current()->id;
		$this->view->tags = new Zend_Dojo_Data('id', $items);
		$this->view->maxId = $maxId;
		$this->view->form = $form;
	}
	
	public function saveArtworkTagAction() {
		$form = new Form_Tags ();
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Tags ();
				$tag = $table->createRow ( $values );
				if ($tag->id > 0) {
					$table->update ( $tag->toArray (), "id = $tag->id" );
				} else {
					$table->insert ( $tag->toArray () );
				}
				//				$this->logger->debug("Yes");
				//				$this->logger->debug($form->getValues());
				$this->_redirect ( "/admin/manage-artwork-tag" );
			}
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
			if ($id) {
				$table = new Model_DbTable_Tags ();
				$tag = $table->fetchRow ( "id = $id" );
				$form->setDefaults ( $tag->toArray () );
			}
		}
		$form->setAction ( "/admin/save-artwork-tag" );
		$form->setMethod ( "POST" );
		$this->view->form = $form;
	}
	
	public function deleteArtworkTagAction() {
		$id = ( int ) $this->_request->getParam ( "id" );
		if ($id > 0) {
			$table = new Model_DbTable_Tags ();
			$table->delete ( "id = $id" );
		}
		$this->_redirect ( "/admin/manage-artwork-tag" );
	}
	
	public function manageArtworkSubjectAction() {
		
		$table = new Model_DbTable_Subjects ();
		$this->view->subjects = $table->fetchAll ();
		
	}
	
	public function deleteArtworkSubjectAction() {
		$id = ( int ) $this->_request->getParam ( "id" );
		if ($id > 0) {
			$table = new Model_DbTable_Subjects ();
			$table->delete ( "id = $id" );
		}
		$this->_redirect ( "/admin/manage-artwork-subject" );
	}
	
	public function deleteArtworkStyleAction() {
		$id = ( int ) $this->_request->getParam ( "id" );
		if ($id > 0) {
			$table = new Model_DbTable_Styles ();
			$table->delete ( "id = $id" );
		}
		$this->_redirect ( "/admin/manage-artwork-style" );
	}
	
	public function saveArtworkStyleAction() {
		$form = new Form_Styles ();
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Styles ();
				$style = $table->createRow ( $values );
				if ($style->id > 0) {
					$table->update ( $style->toArray (), "id = $style->id" );
				} else {
					$table->insert ( $style->toArray () );
				}
				//				$this->logger->debug("Yes");
				//				$this->logger->debug($form->getValues());
				$this->_redirect ( "/admin/manage-artwork-style" );
			}
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
			if ($id) {
				$table = new Model_DbTable_Styles ();
				$style = $table->fetchRow ( "id = $id" );
				$form->setDefaults ( $style->toArray () );
			}
		}
		$form->setAction ( "/admin/save-artwork-style" );
		$form->setMethod ( "POST" );
		$this->view->form = $form;
	}
	
	public function deleteArtworkCategoryAction() {
		$id = ( int ) $this->_request->getParam ( "id" );
		if ($id > 0) {
			$table = new Model_DbTable_Categories ();
			$table->delete ( "id = $id" );
		}
		$this->_redirect ( "/admin/manage-artwork-category" );
	}
	
	public function saveArtworkCategoryAction() {
		$form = new Form_Categories ();
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Categories();
				$category = $table->createRow ( $values );
				if ($category->id > 0) {
					$table->update ( $category->toArray (), "id = $category->id" );
				} else {
					$table->insert ( $category->toArray () );
				}
				$this->_redirect ( "/admin/manage-artwork-category" );
			}
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
			if ($id) {
				$table = new Model_DbTable_Categories();
				$category = $table->fetchRow ( "id = $id" );
				$form->setDefaults ( $category->toArray () );
			}
		}
		$form->setAction ( "/admin/save-artwork-category" );
		$form->setMethod ( "POST" );
		$this->view->form = $form;
	}
	
	public function saveArtworkSubjectAction() {
		$form = new Form_Subjects ();
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Subjects ();
				$subject = $table->createRow ( $values );
				if ($subject->id > 0) {
					$table->update ( $subject->toArray (), "id = $subject->id" );
				} else {
					$table->insert ( $subject->toArray () );
				}
				//				$this->logger->debug("Yes");
				//				$this->logger->debug($form->getValues());
				$this->_redirect ( "/admin/manage-artwork-subject" );
			}
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
			if ($id) {
				$table = new Model_DbTable_Subjects ();
				$subject = $table->fetchRow ( "id = $id" );
				$form->setDefaults ( $subject->toArray () );
			}
		}
		$form->setAction ( "/admin/save-artwork-subject" );
		$form->setMethod ( "POST" );
		$this->view->form = $form;
	}
	
	public function getRelatedArtworksAction() {
		Zend_Layout::getMvcInstance ()->disableLayout ();
		$artistId = ( int ) $this->_request->getParam ( "artistId" );
		$artworkId = ( int ) $this->_request->getParam ( "artworkId" );
		if ($artistId > 0) {
			$form = new Form_RelatedArtworks ();
			$table = new Model_DbTable_ArtWork ();
			$relatedArtworks = $table->fetchAll ( "artist = $artistId" );
			$elementRelatedArtwork = $form->getElement ( "relational_works" );
			foreach ( $relatedArtworks as $relatedArtwork ) {
				/** @var $elementRelatedArtwork Zend_Form_Element_MultiCheckbox */
				$elementRelatedArtwork->addMultiOption ( $relatedArtwork->id, $relatedArtwork->name );
			}
			
			if ($artworkId > 0) {
				$theArtwork = $table->fetchRow ( "id = $artworkId" );
				if ($theArtwork) {
					$originalRelatedArtworks = explode ( ",", $theArtwork->relational_works );
					$elementRelatedArtwork->setValue ( $originalRelatedArtworks );
					$this->view->form = $elementRelatedArtwork->render ();
				}
			} else {
				$this->view->form = $elementRelatedArtwork->render ();
			}
			
		//$this->logger->debug($this->view->form);
		

		} else {
			throw new Zend_Exception ( "需要指定艺术家ID来匹配相关艺术品" );
		}
	}
	
	
	
	
	
	public function deleteNewsAction() {
		if (( int ) $this->_request->getParam ( "id" )) {
			$newsId = ( int ) $this->_request->getParam ( "id" );
//			if ($newsId <= 30) {
//				throw new Zend_Exception ( "该新闻是系统保留文章，请勿删除！" );
//			}
			$table = new Model_DbTable_News ();
			$news = $table->fetchRow ( "id = $newsId" );
			if ($news) {
				$news->delete ();
			}
		}
		
		$this->_redirect ( "/admin/manage-news" );
	}
	
	public function deleteInterviewAction() {
		if (( int ) $this->_request->getParam ( "id" )) {
			$id = ( int ) $this->_request->getParam ( "id" );
//			if ($newsId <= 30) {
//				throw new Zend_Exception ( "该新闻是系统保留文章，请勿删除！" );
//			}
			$table = new Model_DbTable_Interviews();
			$interview = $table->fetchRow ( "id = $id" );
			if ($interview) {
				$interview->delete ();
			}
		}
		
		$this->_redirect ( "/admin/manage-interview" );
	}
	
	public function deleteExhibitionAction() {
		if (( int ) $this->_request->getParam ( "id" )) {
			$id = ( int ) $this->_request->getParam ( "id" );
			$table = new Model_DbTable_Exhibitions ();
			$item = $table->fetchRow ( "id = $id" );
			if ($item) {
				$item->delete ();
			}
		}
		
		$this->_redirect ( "/admin/manage-exhibition" );
	}
	
	public function getArtworksAction() {
		Zend_Layout::getMvcInstance ()->disableLayout ();
		
		$condition = array ("category", "style", "subject", "tags", "artist" );
		$by = $this->_request->getParam ( "by" );
		$id = ( int ) $this->_request->getParam ( "id" );
		
		if (! in_array ( $by, $condition )) {
			throw new Zend_Exception ( "条件错误" );
		}
		
		if ($id <= 0) {
			throw new Zend_Exception ( "条件错误" );
		}
		
		$returns = array ();
		
		$table = new Model_DbTable_ArtWork ();
		$allRecords = $table->fetchAll ();
		
		foreach ( $allRecords as $artwork ) {
			$conditionValues = explode ( ",", $artwork->$by );
			if (in_array ( $id, $conditionValues )) {
				array_push ( $returns, $artwork );
			}
		}
		$this->view->artworks = $returns;
	}
	
	public function updatePriceAction() {
		throw new Zend_Exception ( "access denied" );
		$table = new Model_DbTable_ArtWork ();
		$works = $table->fetchAll ();
		foreach ( $works as $work ) {
			$rmb = $work->rmb_price;
			$usd = ceil ( $rmb / 6 );
			$uro = ceil ( $rmb / 7 );
			
			$usd = ceil ( $rmb / 6 ) % 10 ? ((ceil ( $usd / 10 + 10 )) * 10) : $usd;
			$uro = ceil ( $rmb / 7 ) % 10 ? ((ceil ( $uro / 10 + 10 )) * 10) : $uro;
			
			$work->usd_price = $usd;
			$work->uro_price = $uro;
			
			$work->save ();
		}
	}
	
	public function getOtherPriceAction() {
		$rmb = ( int ) $this->_request->getParam ( "price" );
		$usd = ceil ( $rmb / 6 );
		
		$uro = ceil ( $rmb / 7 );
		$uro = ceil ( $rmb / 7 ) % 10 ? ((ceil ( $uro / 10 + 10 )) * 10) : $uro;
		$return ["uro_price"] = $uro;
		$usd = ceil ( $rmb / 6 ) % 10 ? ((ceil ( $usd / 10 + 10 )) * 10) : $usd;
		$return ["usd_price"] = $usd;
		$this->_helper->json ( $return );
	}
	
	public function deleteCustomerAction() {
		if (( int ) $this->_request->getParam ( "id" )) {
			$id = ( int ) $this->_request->getParam ( "id" );
			$table = new Model_DbTable_Customers ();
			$customer = $table->fetchRow ( "id = $id" );
			if ($customer) {
				$customer->delete ();
			}
		}
		
		$this->_redirect ( "/admin/manage-customer" );
	}
	
	public function manageFriendLinkAction() {
		$this->setCurrentMenu ( "manage-friend-link" );
		$this->view->title = "友情链接管理";
		$table = new Model_DbTable_FriendLinks();
		$this->view->links = $table->fetchAll ();
	}
	
	public function manageVideoAction() {
		$this->setCurrentMenu ( "manage-video" );
		$this->view->title = "视频管理";
		$table = new Model_DbTable_Videos();
		$this->view->videos = $table->fetchAll ();
	}
	
	public function deleteFriendLinkAction() {
		if (( int ) $this->_request->getParam ( "id" )) {
			$id = ( int ) $this->_request->getParam ( "id" );
			$table = new Model_DbTable_FriendLinks();
			$row = $table->fetchRow ( "id = $id" );
			if ($row) {
				$row->delete ();
			}
		}
		
		$this->_redirect ( "/admin/manage-ad" );
	}
	
	public function deleteBannerPicAction() {
		if (( int ) $this->_request->getParam ( "id" )) {
			$id = ( int ) $this->_request->getParam ( "id" );
			$table = new Model_DbTable_BannerPic();
			$row = $table->fetchRow ( "id = $id" );
			if ($row) {
				$row->delete ();
			}
		}
		
		$this->_redirect ( "/admin/manage-banner-pic" );
	}
	
	public function addFriendLinkAction() {
		$this->setCurrentMenu ( "manage-friend-link" );
		$this->view->title = "添加友情链接";
		$form = new Form_FriendLink();
		
		$form->setAction ( "/admin/add-friend-link" )->setMethod ( "POST" );
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_FriendLinks ();
				$table->insert ( $values );
				$this->_redirect ( "/admin/manage-friend-link" );
			}
		}
		
		$this->view->form = $form;
	}
	
	public function editFriendLinkAction() {
		$this->setCurrentMenu ( "manage-friend-link" );
		$this->view->title = "友情链接管理";
		if (! ( int ) $this->_request->getParam ( "id" )) {
			$this->_redirect ( "/admin/manage-friend-link" );
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
		}
		$form = new Form_FriendLink ();
		$form->setAction ( "/admin/edit-friend-link/id/$id" )->setMethod ( "POST" );
		$form->getElement ( "image" )->setRequired ( false );
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_FriendLinks();
				$row = $table->fetchRow ( "id = $id" );
				if (! $row) {
					throw new Zend_Exception ( "指定的友情链接不存在" );
				}
				if ($values ["image"] == null) {
					unset ( $values ["image"] );
				}
				$table->update ( $values, "id=$id" );
				$this->_redirect ( "/admin/manage-friend-link" );
			} else {
				$this->logger->debug ( "No" );
				$this->logger->debug ( $values );
			}
		} else {
			$table = new Model_DbTable_FriendLinks();
			$row = $table->fetchRow ( "id = $id" );
			if ($row) {
				$form->setDefaults ( $row->toArray () );
			} else {
				throw new Zend_Exception ( "指定的友情链接不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	public function manageAdAction() {
		$this->setCurrentMenu ( "manage-ad" );
		$this->view->title = "广告管理";
		$table = new Model_DbTable_Advertisements();
		$this->view->ads = $table->fetchAll ();
	}
	
	public function deleteAdAction() {
		if (( int ) $this->_request->getParam ( "id" )) {
			$id = ( int ) $this->_request->getParam ( "id" );
			$table = new Model_DbTable_Advertisements();
			$row = $table->fetchRow ( "id = $id" );
			if ($row) {
				$row->delete ();
			}
		}
		
		$this->_redirect ( "/admin/manage-ad" );
	}
	
	public function addAdAction() {
		$this->setCurrentMenu ( "manage-ad" );
		$this->view->title = "广告管理";
		$form = new Form_Advertisement();
		
		$form->setAction ( "/admin/add-ad" )->setMethod ( "POST" );
		
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Advertisements ();
				$table->insert ( $values );
				$this->_redirect ( "/admin/manage-ad" );
			}
		}
		
		$this->view->form = $form;
		
	}
	
	public function editAdAction() {
		$this->setCurrentMenu ( "manage-ad" );
		$this->view->title = "广告管理";
		if (! ( int ) $this->_request->getParam ( "id" )) {
			$this->_redirect ( "/admin/manage-ad" );
		} else {
			$id = ( int ) $this->_request->getParam ( "id" );
		}
		$form = new Form_Advertisement ();
		$form->setAction ( "/admin/edit-ad/id/$id" )->setMethod ( "POST" );
		$form->getElement ( "c_image" )->setRequired ( false );
		$form->getElement ( "e_image" )->setRequired ( false );
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $_POST )) {
				$values = $form->getValues ();
				$table = new Model_DbTable_Advertisements ();
				$row = $table->fetchRow ( "id = $id" );
				if (! $row) {
					throw new Zend_Exception ( "指定的广告不存在" );
				}
				if ($values ["c_image"] == null) {
					unset ( $values ["c_image"] );
				}
			if ($values ["e_image"] == null) {
					unset ( $values ["e_image"] );
				}
				$table->update ( $values, "id=$id" );
				$this->_redirect ( "/admin/manage-ad" );
			} else {
				$this->logger->debug ( "No" );
				$this->logger->debug ( $values );
			}
		} else {
			$table = new Model_DbTable_Advertisements ();
			$row = $table->fetchRow ( "id = $id" );
			if ($row) {
				$form->setDefaults ( $row->toArray () );
			} else {
				throw new Zend_Exception ( "指定的广告不存在" );
			}
		
		}
		
		$this->view->form = $form;
	}
	
	

}







