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
			return $this->_helper->redirector('index');
		}

		$userForm = new Application_Form_User();
		$userForm->setAction('/libsys/user/login');
		$userForm->submit->setLabel('login');
		
		$userForm->removeElement('firstname');
		$userForm->removeElement('firstname');
		$userForm->removeElement('lastname');
		$userForm->removeElement('currentpassword');
		$userForm->removeElement('confirmpassword');
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
				
				return $this->_helper->redirector('index');
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
		
		return $this->_helper->redirector('login', 'user');
    }

    public function profileAction()
    {
        // action body
		$auth = Zend_Auth::getInstance();
		
		if(!$auth->hasIdentity()) 
		{
			return $this->_helper->redirector('login', 'user');
		}

		$users = new Application_Model_DbTable_Users();
		$this->view->identity = $users->getUser($auth->getIdentity()->id);
		
    }

    public function editAction()
    {
		$form = new Application_Form_User();
		
		$form->removeElement('id');
		
		$id = new Zend_Form_Element_Hidden('id');
		$id->addFilter('Int')->setOrder(0)->setDecorators(array('ViewHelper'));
		$form->addElement($id);
		
		if( !(Zend_Auth::getInstance()->getIdentity()->role == "administrator") )
		$form->removeElement('role');
		
		$form->removeElement('currentpassword');
		$form->removeElement('password');
		$form->removeElement('confirmpassword');
		$form->submit->setLabel('Save');

		$this->view->form = $form;

		if ($this->getRequest()->isPost())
		{
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) 
			{
				if( !(Zend_Auth::getInstance()->getIdentity()->role == "administrator") )
				{
					$id = Zend_Auth::getInstance()->getIdentity()->id;
					$role = null;
				}
				else
				{
					$id = (int)$form->getValue('id');
					$role = $form->getValue('role');
				}
				
				$firstname = $form->getValue('firstname');
				$lastname = $form->getValue('lastname');
				
				$users = new Application_Model_DbTable_Users();
				$users->updateUser($id, $firstname, $lastname, $role);
				
				return $this->_helper->redirector('profile', 'user');
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
		
		$form->removeElement('id');
		
		$id = new Zend_Form_Element_Hidden('id');
		$id->addFilter('Int')->setOrder(0)->setDecorators(array('ViewHelper'));
		/*
		$users = new Application_Model_DbTable_Users();
		$userData = $users->getUser($id);
				
		$currentPasswordValidator = Myzend_Validate_CurrentPassword($userData['password']);
		$form->currentpassword->addValidator($currentPasswordValidator);
		*/
		$form->addElement($id);
		$form->removeElement('role');
		$form->removeElement('firstname');
		$form->removeElement('lastname');
		$form->submit->setLabel('Save');
		
		$this->view->form = $form;

		if ($this->getRequest()->isPost())
		{
			$formData = $this->getRequest()->getPost();
			
			if( !(Zend_Auth::getInstance()->getIdentity()->role == "administrator") )
			$id = Zend_Auth::getInstance()->getIdentity()->id;
			else
			$id = (int)$form->getValue('id');
				
			$users = new Application_Model_DbTable_Users();
			$userData = $users->getUser($id);

			$currentPasswordValidator = new Myzend_Validate_CurrentPassword($userData['password']);
			$form->currentpassword->addValidator($currentPasswordValidator);
			
			if ($form->isValid($formData)) 
			{
			
				$currentpassword = $form->getValue('currentpassword');
				$password = $form->getValue('password');

				$userData = $users->getUser($id);
				
				if($userData['password'] == md5($currentpassword))
				{
					$users->updatePassword($id, $password);
					return $this->_helper->redirector('profile', 'user');
				}
				else
				return $this->_helper->redirector('password', 'user');
				

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











