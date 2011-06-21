<?php

class Myzend_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // set up acl
        $acl = new Zend_Acl();

        // add the roles

        $acl->addRole(new Zend_Acl_Role('guest'));
		$acl->addRole(new Zend_Acl_Role('librarian'));
		$acl->addRole(new Zend_Acl_Role('administrator'));

        $acl->add(new Zend_Acl_Resource('index'));
		$acl->add(new Zend_Acl_Resource('book'));
		$acl->add(new Zend_Acl_Resource('user'));

		$acl->allow('librarian', 'book', array('index', 'loan', 'return'));
		$acl->allow('librarian', 'user', array('index', 'profile', 'edit', 'password', 'login', 'logout'));
        
		$acl->deny('guest', 'book');
		$acl->allow('guest', 'user', array('login', 'logout'));

		$acl->allow('administrator', null);
        
        // fetch the current user
        $auth = Zend_Auth::getInstance();
		
        if($auth->hasIdentity()) 
		{
            $identity = $auth->getIdentity();
            $role = strtolower($identity->role);
        }
		else
		{
            $role = 'guest';
        }

		//echo $role;
		
        $controller = $request->controller;
        $action = $request->action;

        if (!$acl->isAllowed($role, $controller, $action)) 
		{
            if ($role == 'guest') 
			{
                $request->setControllerName('user');
                $request->setActionName('login');
            } 
			else 
			{
               $request->setControllerName('error');
               $request->setActionName('noauth');
			}
        }
		
		$registry = Zend_Registry::getInstance();
		$registry->set('acl', $acl);
		
    }    
}
