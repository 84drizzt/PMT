<?php

class Model_DbView_Projects extends Zend_Db_Table
{

    protected $_name = 'projects_view';
    protected $_primary  = 'id';
    protected $_sequence = false;

    protected $_rowClass = 'Model_Project';

	
}

