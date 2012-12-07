<?php

class vendorcontactController extends My_Controller_Rest
{

	// list all items - /vendorContact
    public function indexAction()
	{
		$table = new Model_DbView_VendorContacts();
		$result = $table->fetchAll();
		$result = $result->count() > 0 ? $result->toArray() : array();
		$this->getHelper('json')->sendJson( $result );
	}


    // create item - /vendorContact , ?
	public function postAction()
	{
		$item = Zend_Json::decode($this->getRequest()->getRawBody());
        if (! $item) {
            //throw new Exception("Please check your data and try again...");
            header("HTTP/1.0 400 Bad Request"); exit;
        }
        
        $table = new Model_DbTable_VendorContacts();

        $this->getHelper('json')->sendJson( $table->insert($item) );
	}
	
	// read item - /vendorContact/id/?
    public function getAction()
    {
		$id = $this->_getParam('id');
		$table = new Model_DbView_VendorContacts();
		$result = $table->fetchRow( "id = $id" );
		$result = $result ? $result->toArray() : array();
		$this->getHelper('json')->sendJson( $result );
    }

	// update item - /vendorContact/id/? , ?
    public function putAction()
    {
		if (! $id = $this->_getParam('id', false)) {
            throw new Exception("Please provide a specific id...");
        }       
        
		$item = Zend_Json::decode($this->getRequest()->getRawBody());
        if (! $item) {
            throw new Exception("Please check your data and try again...");
        }
        
        $table = new Model_DbTable_VendorContacts();

        $this->getHelper('json')->sendJson( $table->update($item, "id = $id") );
    }

	// delete item - /vendorContact/id/?
    public function deleteAction()
    {
        if (! $id = $this->_getParam('id', false)) {
            throw new Exception("Please provide a specific id...");
        }
        
        $table = new Model_DbTable_VendorContacts();

        $this->getHelper('json')->sendJson( $table->delete("id = $id") );
    }


}