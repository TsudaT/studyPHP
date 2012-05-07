<?php

class UserRepository extends DbRepository
{
	public function insert($userName, $password)
	{
		$password = $this->hashPassword($password);
		$now = new DateTime();
		
		$sql = "
			insert into user(user_name, password, created_at) 
			values(:user_name, :password, :created_at)
		";
		
		$stmt = $this->execute($sql, array(
			':user_name'	=> $userName,
			':password'		=> $password,
			':created_at'	=> $now->format('Y-m-d H:i:s'),
		));
	}
	
	public function hashPassword($password)
	{
		return sha1($password . 'hoge');
	}
	
	public function fetchByUserName($userName)
	{
		$sql = "select * from user where user_name = :user_name";
		
		return $this->fetch($sql, array('user_name' => $userName));
	}
	
	public function isUniqueUserName($userName)
	{
		$sql = "select count(id) as count from user where user_name = :user_name";
		
		$row = $this->fetch($sql, array(':user_name' => $userName));
		if ($row['count'] === '0'){
			return true;
		}
		
		return false;
	}
}