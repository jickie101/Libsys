<?php

class BookController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
		$auth = Zend_Auth::getInstance();
		
		if(!$auth->hasIdentity()) 
		{
			$this->_redirect('/user/login');
			//return $this->_forward('login', 'user');
		}
    }

    public function indexAction()
    {
        // action body
		$books = new Application_Model_DbTable_Books();
		$this->view->books = $books->fetchAll();
    }

    public function addAction()
    {
        // action body
		$form = new Application_Form_Book();
		$form->submit->setLabel('Add');
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) 
		{
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) 
			{
				$title = $form->getValue('title');
				$author = $form->getValue('author');
				//$branch = $form->getValue('branch');
				$branch = 1;

				$books = new Application_Model_DbTable_Books();
				$books->addBook($title, $author, $branch);
				
				$this->_helper->redirector('index');
			} 
			else 
			{
				$form->populate($formData);
			}
		}
    }

    public function editAction()
    {
        // action body
		$form = new Application_Form_Book();
		$form->submit->setLabel('Save');
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost())
		{
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) 
			{
				$id = (int)$form->getValue('id');
				$title = $form->getValue('title');
				$author = $form->getValue('author');
				//$branch = $form->getValue('branch');
				$branch = 1;

				$books = new Application_Model_DbTable_Books();
				$books->updateBook($id, $title, $author,  $branch);
				
				$this->_helper->redirector('index');
			} 
			else 
			{
				$form->populate($formData);
			}
		} 
		else 
		{
			$id = $this->_getParam('id', 0);
			if ($id > 0) 
			{
				$books = new Application_Model_DbTable_Books();
				$form->populate($books->getBook($id));
			}
		}
    }

    public function deleteAction()
    {
        // action body
    }

    public function loanAction()
    {
        // action body
        // action body

			$id = $this->_getParam('id', 0);
			
			$form = new Zend_Form();
			
			$id = new Zend_Form_Element_Hidden('id');
			$id->addFilter('Int');
			
			$bookDb = new Application_Model_DbTable_Books();
			$bookData = $bookDb->getBook($id);
			
			$book = new Zend_Form_Element_Text('book');
			$book->setLabel('Book')
						->setValue($bookData['title'])
						->setAttrib('readonly', 'true')
						->setRequired(true)
						->addFilter('StripTags')
						->addFilter('StringTrim')
						->addValidator('NotEmpty');
			
			$user = new Zend_Form_Element_Select('user');
			$user->setLabel('user')
				->setRequired()
				->addErrorMessage('User required!');
				
			$user->addMultiOption(1, 'one');	
			$user->addMultiOption(2, 'two');

			$submit = new Zend_Form_Element_Submit('submit');
			$submit->setAttrib('id', 'submitbutton');		
		
			$form->addElements(array($id, $book, $user, $submit));    
			
			$form->submit->setLabel('Save');
			$this->view->form = $form;

    }


}









