<?php

class Form_ChangePassword extends Zend_Form
{

    public function init()
    {
    	$filter_trim = new Zend_Filter_StringTrim();
        $id = new Zend_Form_Element_Hidden("id");
        //$id->setLabel("Id: ");
        $id->setRequired(true);
        $intValidator = new Zend_Validate_Int();
        $this->addElement($id, "id");
        
        $passwd = new Zend_Form_Element_Password("passwd");
        $passwd->setRequired(true);
        $passwd->setLabel("Password: ");
        require_once 'Artdepot/Validate/PasswordConfirmation.php';
        $passwordValidator = new Artdepot_Validate_PasswordConfirmation();
        $passwd->addValidator($passwordValidator);
        $this->addElement($passwd, "passwd");
        
        $repasswd = new Zend_Form_Element_Password("password_confirm");
        $repasswd->setLabel("Confirm password: ");
//        require_once 'Artdepot/Validate/PasswordConfirmation.php';
//        $passwordValidator = new Artdepot_Validate_PasswordConfirmation();
//        $repasswd->addValidator($passwordValidator);
        $this->addElement($repasswd, "password_confirm");
        
        $submit = new Zend_Form_Element_Submit("submit");
        $this->addElement($submit, "submit");
    }


}

