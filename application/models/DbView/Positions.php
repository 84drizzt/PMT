<?php

class Model_DbView_Positions extends Zend_Db_Table
{

    protected $_name = 'positions_view';
	protected $_primary  = 'id';
    protected $_sequence = false;
    
    protected $_rowClass = 'Model_Position';


}

