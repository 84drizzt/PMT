<?php

class candidateController extends My_Controller_Rest
{

	// list all items - /candidate
    public function indexAction()
	{
		$table = new Model_DbView_Candidates();
		$result = $table->fetchAll();
		$result = $result->count() > 0 ? $result->toArray() : array();
		$this->getHelper('json')->sendJson( $result );
	}


    // create item - /candidate , ?
	public function postAction()
	{
		$item = Zend_Json::decode($this->getRequest()->getRawBody());
        if (! $item) {
            throw new Exception("Please check your data and try again...");
        }
        
        $table = new Model_DbTable_Candidates();

        $this->getHelper('json')->sendJson( $table->insert($item) );
	}
	
	// read item - /candidate/id/?
    public function getAction()
    {
		$id = $this->_getParam('id');
		$table = new Model_DbView_Candidates();
		$result = $table->fetchRow( "id = $id" );
		$result = $result ? $result->toArray() : array();
		$this->getHelper('json')->sendJson( $result );
    }

	// update item - /candidate/id/? , ?
    public function putAction()
    {
		if (! $id = $this->_getParam('id', false)) {
            throw new Exception("Please provide a specific id...");
        }
        
		$item = Zend_Json::decode($this->getRequest()->getRawBody());
        if (! $item) {
            throw new Exception("Please check your data and try again...");
        }
        
        $table = new Model_DbTable_Candidates();

        $this->getHelper('json')->sendJson( $table->update($item, "id = $id") );
    }

	// delete item - /candidate/id/?
    public function deleteAction()
    {
        if (! $id = $this->_getParam('id', false)) {
            throw new Exception("Please provide a specific id...");
        }
        
        $table = new Model_DbTable_Candidates();

        $this->getHelper('json')->sendJson( $table->delete("id = $id") );
    }


}