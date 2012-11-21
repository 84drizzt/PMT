<?php

class Model_DbView_Candidates extends Zend_Db_Table
{

    protected $_name = 'candidates_view';
    protected $_primary  = 'id';
    protected $_sequence = false;
    
    protected $_rowClass = 'Model_Candidate';


}

