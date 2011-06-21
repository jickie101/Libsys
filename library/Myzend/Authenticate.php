<?php

class Myzend_Authenticate
{

	private $acl;

	private $role;

	private $controller;

	private $action;

	public function __construct($request)
	{
		
		$this->acl = Zend_Registry::getInstance()->acl;

		$this->role = Zend_Auth::getInstance()->getIdentity()->role;

		$this->controller = $request->controller;

		$this->action = $request->action;

	} 

	public function isAllowed($controller = null, $action = null)
	{
		//$this->acl->isAllowed(Zend_Auth::getInstance()->getIdentity()->role, $this->getRequest()->controller, $this->getRequest()->action) ? "allowed" : "denied";
		
		if($controller != null)
		$this->controller = $controller;
		
		if($action != null)
		$this->action = $action;
		
		if($this->acl->isAllowed($this->role, $this->controller, $this->action))
		return true;
		else
		return false;
	}
}
