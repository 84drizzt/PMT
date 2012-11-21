<?php

class Model_DbView_VendorContacts extends Zend_Db_Table
{

    protected $_name = 'vendor_contacts_view';
    protected $_primary  = 'id';
    protected $_sequence = false;

    protected $_rowClass = 'Model_VendorContact';

	
}

