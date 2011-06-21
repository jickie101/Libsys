<?php

class Application_Form_Book extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
		$this->setName('book');

		$this->addElementPrefixPath('Myzend_Form_Decorator','Myzend/Form/Decorator','decorator');
		
		$id = new Zend_Form_Element_Hidden('id');
		$id->addFilter('Int')
					->setDecorators(array('ViewHelper'));
		
		$branch = new Zend_Form_Element_Hidden('branch');
		$branch->addFilter('Int')
					->setDecorators(array('ViewHelper'));

		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Title')
					->setDecorators(array('Custom'))
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');

		$author = new Zend_Form_Element_Text('author');
		$author->setLabel('Author')
					->setDecorators(array('Custom'))
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');
					
		$status = new Zend_Form_Element_Select('status');
		$status->setLabel('Status')
					->setDecorators(array('Custom'))
					->setRequired()
					->addErrorMessage('User required!');
			
		$status->addMultiOption(0, 'available');	
		$status->addMultiOption(1, 'not available');
			
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')
						->setDecorators(array( 'ViewHelper', 'Errors'));
		
		$this->setDecorators(array(
									'FormElements',
									array('HtmlTag',array('tag'=>'div','class'=>'formLayout')),
									'Form'
		));
		
		$this->addElements(array($id, $branch, $title, $author, $status, $submit));    
	}


}

