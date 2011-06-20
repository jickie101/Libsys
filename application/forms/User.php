<?php

class Application_Form_User extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
		$this->setName('user');
		
		$this->addElementPrefixPath('Myzend_Form_Decorator','Myzend/Form/Decorator','decorator');
		
		$id = new Zend_Form_Element_Text('id');
		$id->setLabel('I.D number')
					->setDecorators(array('Custom'))
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');
		
		$role = new Zend_Form_Element_Select('role');
		$role->setLabel('Role')
					->setDecorators(array('Custom'))
					->setRequired(true);

		$role->addMultiOption('administrator', 'Administrator');
		$role->addMultiOption('librarian', 'Librarian');
		$role->addMultiOption('patron', 'Patron');
		
		$currentpassword = new Zend_Form_Element_Password('currentpassword');
		$currentpassword->setLabel('Current Password')
					->setDecorators(array('Custom'))
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');

		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password')
					->setDecorators(array('Custom'))
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');

		$confirmpassword = new Zend_Form_Element_Password('confirmpassword');
		$confirmpassword->setLabel('Confirm Password')
					->setDecorators(array('Custom'))
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('StringLength', false, array(6,24))
					->addValidator(new Zend_Validate_Identical('password'))
					->setRequired(true);

			
		$firstname = new Zend_Form_Element_Text('firstname');
		$firstname->setLabel('Firstname')
					->setDecorators(array('Custom'))
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');
					
		$lastname = new Zend_Form_Element_Text('lastname');
		$lastname->setLabel('Lastname')
					->setDecorators(array('Custom'))
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');
					
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')
					->setDecorators(array( 'ViewHelper', 'Errors'));
		
		$this->setDecorators(array(
									'FormElements',
									array('HtmlTag',array('tag'=>'div','class'=>'formLayout')),
									'Form'
		));

		$this->addElements(array($id, $role, $currentpassword, $password, $confirmpassword, $firstname, $lastname, $submit));
    }


}

