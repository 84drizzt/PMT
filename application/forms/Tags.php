<?php

class Form_Tags extends Zend_Form
{

    public function init()
    {
    	$id = new Zend_Form_Element_Hidden("id");
        $id->setValue(0);
        $id->setRequired(true);
        $idValidator = new Zend_Validate_Int();
        $id->addValidator($idValidator);
        $this->addElement($id, "id");
        
        
        $c_name = new Zend_Dojo_Form_Element_TextBox("c_name");
        $c_name->setLabel("中文名: ");
        $c_name->setRequired(true);
        $nameValidator = new Zend_Validate_Alnum(false);
        $c_name->addValidator($nameValidator);
        $this->addElement($c_name, "c_name");
        
        $e_name = new Zend_Dojo_Form_Element_TextBox("e_name");
        $e_name->setLabel("英文名: ");
        $e_name->setRequired(true);
        $nameValidator = new Zend_Validate_Alnum(false);
        $c_name->addValidator($nameValidator);
        $this->addElement($e_name, "e_name");
        
        
        $submit = new Zend_Form_Element_Submit("submit");
        $this->addElement($submit, "submit");
    }


}

