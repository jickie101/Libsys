<?php

class BookController extends Zend_Controller_Action
{
	
    public function init()
    {
		$this->view->auth = new Myzend_Authenticate($this->getRequest());
    }

    public function indexAction()
    {

		$books = new Application_Model_DbTable_Books();

		$page = $this->_getParam('page',1);

		$adapter = new Zend_Paginator_Adapter_DbSelect($books->select()->from('books'));

		$paginator = new Zend_Paginator($adapter);
		$paginator->setItemCountPerPage(10);
		$paginator->setCurrentPageNumber($page);
		$this->view->books = $paginator;
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
				$status = 0;
				$branch = 1;

				$books = new Application_Model_DbTable_Books();
				$books->addBook($title, $author, $branch, $status);
				
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
				$status = $form->getValue('status');
				
				//$branch = $form->getValue('branch');
				$branch = 1;

				$books = new Application_Model_DbTable_Books();
				$books->updateBook($id, $title, $author, $branch, $status);
				
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
		if ($this->getRequest()->isPost()) 
		{
			$del = $this->getRequest()->getPost('del');
			
			if ($del == 'Yes') 
			{
				$id = $this->getRequest()->getPost('id');
				$books = new Application_Model_DbTable_Books();
				$books->deleteBook($id);
			}
			$this->_helper->redirector('index');
		} 
		else 
		{
			$id = $this->_getParam('id', 0);
			$books = new Application_Model_DbTable_Books();
			$this->view->book = $books->getBook($id);
		}
    }

    public function loanAction()
    {
        // action body
		$bookId = $this->_getParam('id', 0);
		
		$form = new Zend_Form();
		
		$form->addElementPrefixPath('Myzend_Form_Decorator','Myzend/Form/Decorator','decorator');
		
		$bookDb = new Application_Model_DbTable_Books();
		$bookData = $bookDb->getBook($bookId);
		
		$id = new Zend_Form_Element_Hidden('id');
		$id->addFilter('Int')
					->setValue($bookData['id']);

		$book = new Zend_Form_Element_Text('book');
		$book->setLabel('Book')
					->setDecorators(array('Custom'))
					->setValue($bookData['title'])
					->setAttrib('readonly', 'true')
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');
		
		$user = new Zend_Form_Element_Text('user');
		$user->setLabel('User ID')
					->setDecorators(array('Custom'))
					->setRequired(true)
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty');

		$datepicker = new ZendX_JQuery_Form_Element_DatePicker("return_date", array("label" => "Return Date"));
		$datepicker->setJQueryParam('dateFormat', 'yy-mm-dd');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');		
	
		$form->addElements(array($id, $book, $user, $datepicker, $submit));    
		
		$form->submit->setLabel('Save');
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) 
		{
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) 
			{
				//$branch = $form->getValue('branch');
				$book = $form->getValue('id');
				$user = $form->getValue('user');
				$branch = 1;
				$return_date = $form->getValue('return_date');
				
				$histories = new Application_Model_DbTable_Histories();
				$status = $histories->addHistory($book, $user, $branch, $return_date);
				
				$books = new Application_Model_DbTable_Books();
				$books->updateBookStatus($book, $status);
				
				$this->_helper->redirector('index', 'book');
			} 
			else 
			{
				$form->populate($formData);
			}
		}
    }

    public function returnAction()
    {
        // action body
		if ($this->getRequest()->isPost()) 
		{
			$ret = $this->getRequest()->getPost('ret');
			
			if ($ret == 'Yes') 
			{
				$id = $this->getRequest()->getPost('id');
				
				$histories = new Application_Model_DbTable_Histories();
				$histories->updateHistory($id, date("Y-m-d"));
				$history = $histories->getHistory($id);
				
				$books = new Application_Model_DbTable_Books();
				$books->updateBookStatus($history['book_id'], 0);

			}
			
			$this->_helper->redirector('index', 'book');
		} 
		else 
		{
			$id = $this->_getParam('id', 0);
			$books = new Application_Model_DbTable_Books();
			$this->view->book = $books->getBook($id);
		}
    }
}











