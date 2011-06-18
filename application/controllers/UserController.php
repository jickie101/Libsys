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
			$authAdapter = new Zend_Auth_Adapter_DbTable($db, 'users', 'username', 'password');
			
			//set the username and password
			$authAdapter->setIdentity($data['username']);
			$authAdapter->setCredential(md5($data['password']));
			
			//authenticate
			$result = $authAdapter->authenticate();
			
			if ($result->isValid())
			{
				$auth = Zend_Auth::getInstance();
				$storage = $auth->getStorage();
				$storage->write($authAdapter->getResultRowObject(array('username' , 'firstname' , 'lastname', 'role')));
				
				return $this->_forward('index'); 
			} 
			else 
			{
				$this->view->loginMessage = "Sorry, your username or password was incorrect";
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


}





