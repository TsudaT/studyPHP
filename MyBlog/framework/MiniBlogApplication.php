<?php

class MiniBlogApplication extends Application
{
	protected $_mLoginAcction = array('account', 'signin');
	
	public function getRootDir()
	{
		return dirname(__FILE__);
	}
	
	protected function registerRoutes()
	{
		return array(
			'/'				=> array('controller' => 'status', 'action' => 'index'),
			'/status/post'	=> array('controller' => 'status', 'action' => 'post'),
			'/account'		=> array('controller' => 'account', 'action' => 'index'),
			'/account/:action' => array('controller' => 'account'),
			'/follow' 		=> array('controller' => 'account', 'action' => 'follow')
		);
	}
	
	protected function configure()
	{
		$this->db_manager->connect('master', array(
			'dsn'		=> 'mysql:dbname=myblog;host=localhost',
			'user'		=> 'root',
			'password'	=> 'root',
		));
	}
}