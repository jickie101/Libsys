<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
		$auth = Zend_Auth::getInstance();
		
		if($auth->hasIdentity()) 
		{
			$this->view->identity = $auth->getIdentity();
		}
    }

    public function loginAction()
    {
	
		$auth = Zend_Auth::getInstance();

		if($auth->hasIdentity()) 
		{
			return $this->_forward('index'); 
		}
        // action body
		$userForm = new Application_Form_User();
		$userForm->setAction('/libsys/user/login');
		$userForm->submit->setLabel('login');
		
		$userForm->removeElement('firstname');
		$userForm->removeElement('firstname');
		$userForm->removeElement('lastname');
		$userForm->removeElement('role');
		
		if ($this->_request->isPost() && $userForm->isValid($_POST)) 
		{
			$data = $userForm->getValues();
			
			//set up the auth adapter
			// get the default db adapter
			$db = Zend_Db_Table::getDefaultAdapter();
			
			//create the auth adapter
			$authAdapter = new Zend_Auth_Adapter_DbTable($db, 'users', 'id', 'password');
			
			//set the id and password
			$authAdapter->setIdentity($data['id']);
			$authAdapter->setCredential(md5($data['password']));
			
			//authenticate
			$result = $authAdapter->authenticate();
			
			if ($result->isValid())
			{
				$auth = Zend_Auth::getInstance();
				$storage = $auth->getStorage();
				$storage->write($authAdapter->getResultRowObject(array('id', 'firstname' , 'lastname', 'role')));
				
				return $this->_forward('index'); 
			} 
			else 
			{
				$this->view->loginMessage = "Sorry, your I.D number or password was incorrect";
			}
		}
		
		$this->view->form = $userForm;

    }

    public function logoutAction()
    {
        // action body
		$authAdapter = Zend_Auth::getInstance();
		$authAdapter->clearIdentity();
		
		return $this->_forward('login', 'user');
    }

    public function profileAction()
    {
        // action body
		$auth = Zend_Auth::getInstance();
		
		if(!$auth->hasIdentity()) 
		{
			return $this->_forward('login', 'user');
		}

		$users = new Application_Model_DbTable_Users();
		$this->view->identity = $users->getUser($auth->getIdentity()->id);
		
    }

    public function editAction()
    {
        // action body
		$form = new Application_Form_User();
		$form->removeElement('currentpassword');
		$form->removeElement('password');
		$form->removeElement('confirmpassword');
		//$form->removeElement('role');
		$form->submit->setLabel('Save');
		
		$this->view->form = $form;

		if ($this->getRequest()->isPost())
		{
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) 
			{
				$id = (int)$form->getValue('id');
				$firstname = $form->getValue('firstname');
				$lastname = $form->getValue('lastname');
				$role = $form->getValue('role');
				
				$users = new Application_Model_DbTable_Users();
				$users->updateUser($id, $firstname, $lastname, $role);
				
				return $this->_forward('profile', 'user');
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
				$users = new Application_Model_DbTable_Users();
				$form->populate($users->getUser($id));
			}
		}
    }

    public function passwordAction()
    {
        // action body

		$form = new Application_Form_User();
		
		$form->removeElement('role');
		$form->removeElement('firstname');
		$form->removeElement('lastname');
		$form->submit->setLabel('Save');
		
		$this->view->form = $form;

		if ($this->getRequest()->isPost())
		{
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) 
			{
				$id = (int)$form->getValue('id');
				$password = $form->getValue('password');
				
				$users = new Application_Model_DbTable_Users();
				$users->updatePassword($password);
				
				return $this->_forward('profile', 'user');
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
				$users = new Application_Model_DbTable_Users();
				$form->populate($users->getUser($id));
			}
		}

    }


}











