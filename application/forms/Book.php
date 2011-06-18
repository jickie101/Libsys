<?php

class Application_Form_Book extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
		$this->setName('book');
		
		$id = new Zend_Form_Element_Hidden('id');
		$id->addFilter('Int');
		
		$branch = new Zend_Form_Element_Hidden('branch');
		$branch->addFilter('Int');

		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Title')
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');

		$author = new Zend_Form_Element_Text('author');
		$author->setLabel('Author')
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');
					
		$status = new Zend_Form_Element_Select('status');
		$status->setLabel('Status')
			->setRequired()
			->addErrorMessage('User required!');
			
		$status->addMultiOption(0, 'available');	
		$status->addMultiOption(1, 'not available');
			
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		
		$this->addElements(array($id, $branch, $title, $author, $status, $submit));    
	}


}

