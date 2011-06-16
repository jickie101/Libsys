<?php

class Application_Form_User extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
		$this->setName('user');
		
		$id = new Zend_Form_Element_Hidden('id');
		$id->addFilter('Int');
		
		$role = new Zend_Form_Element_Hidden('role');
		$role->addFilter('Int');
		
		$username = new Zend_Form_Element_Text('username');
		$username->setLabel('Username')
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');

		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password')
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');
					
		$firstname = new Zend_Form_Element_Text('firstname');
		$firstname->setLabel('Firstname')
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');
					
		$lastname = new Zend_Form_Element_Text('lastname');
		$lastname->setLabel('Lastname')
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');
					
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		
		$this->addElements(array($id, $role, $username, $password, $firstname, $lastname, $submit));
    }


}

