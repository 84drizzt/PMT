<?php

class Form_AdminLogin extends Zend_Form {
	
	public function init() {
		
		$this->setMethod ( 'post' );
		
		$this->addElement ( 
		'text', 'name', array (
		'label' => 'Username:', 
		'required' => true, 
		'filters' => array ('StringTrim','StripTags' ) )
		 );
		
		$this->addElement ( 'password', 'passwd', array (
		'label' => 'Password:', 
		'required' => true )
		 );
		 
		$this->addElement ( 'submit', 'submit', array (
		'ignore' => true, 
		'label' => 'Login' )
		 );
	}

}

