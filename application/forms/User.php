<?php

class Form_User extends Zend_Form
{

    public function init()
    {
        $id = new Zend_Dojo_Form_Element_TextBox("id");
        $id->setLabel("Id: ");
        $id->setRequired(true);
        $intValidator = new Zend_Validate_Int();
        $this->addElement($id, "id");
        
        
        $name = new Zend_Dojo_Form_Element_TextBox("name");
        $name->setLabel("Name: ");
        $intValidator = new Zend_Validate_Int();
        $this->addElement($name, "name");
        
        
        $email = new Zend_Dojo_Form_Element_TextBox("email");
        $email->setLabel("Email: ");
        $email->setRequired(true);
        $intValidator = new Zend_Validate_Int();
        $this->addElement($email, "email");
        
        
        $passwd = new Zend_Dojo_Form_Element_TextBox("passwd");
        $passwd->setLabel("Passwd: ");
        $passwd->setRequired(true);
        $intValidator = new Zend_Validate_Int();
        $this->addElement($passwd, "passwd");
        
        $submit = new Zend_Form_Element_Submit("submit");
        $this->addElement($submit, "submit");
    }


}

