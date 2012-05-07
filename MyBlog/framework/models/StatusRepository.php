<?php

class StatusRepository extends DbRepository
{
	public function insert($user_id, $body)
	{
		$now = new DateTime();
		
		$sql = "insert into status(user_id, body, created_at) values(:user_id, :body, :created_at)";
		
		$stmt = $this->execute($sql, array(
			':user_id'		=> $user_id,
			':body'			=> $body,
			':created_at'	=> $now->format('Y-m-d H:i:s'),
		));
	}
	
	public function fetchAllPersonalArchivesByUserId($user_id)
	{
		$sql = "
			select a.*, u.user_name
			from status a left join user u on a.user_id = u.id
			where u.id = :user_id order by a.created_at desc
		";
		
		return $this->fetchAll($sql, array(':user_id' => $user_id));
	}
	
	public function fetchAllByUserId($user_id)
	{
		$sql = "SELECT a.*, u.user_name FROM status a LEFT JOIN user u ON a.user_id 
					WHERE a.id = :id AND u.user_name = :user_name";
		
		return $this->fetch($sql, array(
			':id'			=> $id,
			':user_name'	=> $user_name,
		));
	}
}