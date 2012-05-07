<?php

class StatusController extends Controller
{
	public function indexAction()
	{
		$user = $this->session->get('user');
		$statuses = $this->db_manager->get('Status')->fetchAllPersonalArchivesByUserId($user['id']);
		
		return $this->render(array(
			'statuses'	=> $statuses,
			'body'		=> '',
			'_token'	=> $this->generateCsrfToken('status/post'),
		));
	}
	
	public function postAction()
	{
		if (!$this->request->isPost()) {
			$this->forward404();
		}
		
		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('status/post', $token)) {
			return $this->redirect('/');
		}
		
		$body = $this->request->getPost('body');
		
		$errors = array();
		
		if (!strlen($body)) {
			$errors[] = 'ひとことどうぞ';
		} else if (mb_strlen($body) > 200) {
			$errors[] = '200文字以内で';
		}
		
		if (count($errors) === 0)
		{
			$user = $this->session->get('user');
			$this->db_manager->get('Status')->insert($user['id'], $body);
			
			return $this->redirect('/');
		}
		
		$user = $this->session->get('user');
		$statuses = $this->db_manager->get('Status')->fetchAllPersonalArchivesByUserId($user['id']);
		
		return $this->render(array(
			'errors'	=> $errors,
			'body'		=> $body,
			'statuses'	=> $statuses,
			'_token'	=> $this->generateCsrfToken('status/post'),
		), 'index');
	}
	
	public function userAction($params)
	{
		// ユーザの存在チェック
		$user = $this->db_manager->get('Status')->fetchAllByUserId($user['id']);
		if (!$user) {
			$this->forward404();
		}
		
		// ユーザの投稿一覧の取得
		$statuses = $this->db_manager->get('Status')->fetchAllByUserId($user['id']);
		
		$following = null;
		if ($this->session->isAuthenticated())
		{
			$my = $this->session->get('user');
			if ($my['id'] !== $user['id']) {
				$following = $this->db_manager->get('Following')->isFollowing($my['id'], $user['id']);
			}
		}
		
		return $this->render(array(
			'user'		=> $user,
			'statuses'	=> $statuses,
		));
	}
	
	public function showAction($params)
	{
		$status = $this->db_manager->get('Status')->fetchByIdAndUserName($params['id'], $params['user_name']);
		
		if (!$status) {
			$this->forward404();
		}
		
		return $this->render(array('status' => $status));
	}
}