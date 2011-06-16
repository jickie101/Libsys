<?php

class Application_Model_DbTable_Books extends Zend_Db_Table_Abstract
{

    protected $_name = 'books';

	public function getBook($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
		if (!$row) 
		{
			throw new Exception("Could not find row $id");
		}
		return $row->toArray();
	}

	public function addBook($title, $author, $branch)
	{
		$data = array(
			'title' => $title,
			'author' => $author,
			'branch_id' => $branch
		);
		$this->insert($data);
	}

	public function updateBook($id, $title, $author, $branch)
	{
		$data = array(
			'title' => $title,
			'author' => $author,
			'branch_id' => $branch
		);
		$this->update($data, 'id = '. (int)$id);
	}
	
	public function deleteBook($id)
	{
		$this->delete('id =' . (int)$id);
	}

}

