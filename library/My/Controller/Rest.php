<?php
class My_Controller_Rest extends Zend_Rest_Controller {
	
	public function init() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$authNamespace = new Zend_Session_Namespace('Auth');
		if( ! $authNamespace->userinfo ) {
		    //header("HTTP/1.0 401 Unauthorized"); exit;
		}
		
	}
	
	
	public function indexAction()
	{
		
	}
	
	
	public function postAction()
	{
		
	}
	
	
	public function getAction()
	{
		
	}
	
	
	public function putAction()
	{
		
	}
	
	
	public function deleteAction()
	{
		
	}
}