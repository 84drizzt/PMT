<?php

class Model_DbView_Interviews extends Zend_Db_Table
{

    protected $_name = 'interviews_view';
    protected $_primary  = 'id';
    protected $_sequence = false;

    protected $_rowClass = 'Model_Interview';


}

