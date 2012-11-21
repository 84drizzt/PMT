<?php

class loginController extends Zend_Rest_Controller
{

    public function init()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	// list user info
    public function indexAction()
	{
		$authNamespace = new Zend_Session_Namespace('Auth');
		$result = $authNamespace->userinfo ? $authNamespace->userinfo : array();
		$this->getHelper('json')->sendJson( $result );
	}


    // validate user email & password
	public function postAction()
	{
		$item = Zend_Json::decode($this->getRequest()->getRawBody());
		if (! $item) {
			throw new Exception("Please check your data and try again...");
		}
	
		$email = $item['email'];
		$password = $item['password'];
	
		$table = new Model_DbTable_users();
		$result = $table->fetchRow( "email = '$email'" );
		$result = $result ? $result->toArray() : array();
	
		if($password == $result['password']){
			$authNamespace = new Zend_Session_Namespace('Auth');
			$authNamespace->userinfo = $result;
			print_r($authNamespace->userinfo['email']);
		} else {
			header("HTTP/1.0 303 See Other"); exit;
		}
	
		$this->getHelper('json')->sendJson( $result );
	}
	
	
    public function getAction()
    {
		
    }

	
    public function putAction()
    {
		
    }

    
	// logout 
    public function deleteAction()
    {
        $authNamespace = new Zend_Session_Namespace('Auth');
		$authNamespace->__unset('userinfo');	//destroy session
		echo "logout";
    }


}