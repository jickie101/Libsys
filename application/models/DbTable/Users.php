<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

	public function getUser($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
		if (!$row) 
		{
			throw new Exception("Could not find row $id");
		}
		return $row->toArray();
	}
	
	public function addUser($password, $firstname, $lastname, $role)
	{
		$data = array(
			'password' => md5($password),
			'firstname' => $firstname,
			'lastname' => $lastname,
			'role' => $role
		);
		$this->insert($data);
	}
	
	public function updateUser($id, $firstname, $lastname, $role=null)
	{
		if($role==null)
		{
			$data = array(
				'firstname' => $firstname,
				'lastname' => $lastname
			);
		}
		else
		{
			$data = array(
				'firstname' => $firstname,
				'lastname' => $lastname,
				'role' => $role
			);
		}
		$this->update($data, 'id = '. (int)$id);
	}
	
	public function updatePassword($id, $password)
	{
		$data = array(
			'password' => md5($password)
		);
		$this->update($data, 'id = '. (int)$id);
	}
}

