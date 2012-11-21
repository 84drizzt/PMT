<?php

class Model_DbView_PositionToSkill extends Zend_Db_Table
{

    protected $_name = 'position_to_skill_view';
    protected $_primary  = 'id';
    protected $_sequence = false;

    protected $_rowClass = 'Model_PositionToSkill';


}

