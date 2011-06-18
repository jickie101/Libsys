<?php

class Application_Model_DbTable_Histories extends Zend_Db_Table_Abstract
{

    protected $_name = 'histories';
	
	public function getHistory($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
		if (!$row) 
		{
			throw new Exception("Could not find row $id");
		}
		return $row->toArray();
	}
	
	public function addHistory($book, $user, $branch, $return_date)
	{
		$data = array(
			'book_id' => $book,
			'user_id' => $user,
			'branch_id' => $branch,
			'return_date' => $return_date
		);
		
		return $this->insert($data);

	}
	
	public function updateHistory($id, $returned_date)
	{
		$data = array(
			'returned_date' => $returned_date
		);
		$this->update($data, 'id = '. (int)$id);
	}
}

