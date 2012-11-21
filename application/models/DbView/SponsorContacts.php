<?php

class Model_DbView_SponsorContacts extends Zend_Db_Table
{

    protected $_name = 'sponsor_contacts_view';
	protected $_primary  = 'id';
    protected $_sequence = false;
    
    protected $_rowClass = 'Model_SponsorContact';


}

