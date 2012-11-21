<?php

class Model_DbView_Feedback extends Zend_Db_Table
{

    protected $_name = 'feedback_view';
	protected $_primary  = 'id';
    protected $_sequence = false;
    
    protected $_rowClass = 'Model_Feedback';


}

