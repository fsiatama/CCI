<?php
class Session {

	private $session_user_id;
	private $session_php_id;
	private $session_date;
	private $session_active;

	public function setSession_user_id($session_user_id){
		$this->session_user_id = $session_user_id;
	}

	public function getSession_user_id(){
		return $this->session_user_id;
	}

	public function setSession_php_id($session_php_id){
		$this->session_php_id = $session_php_id;
	}

	public function getSession_php_id(){
		return $this->session_php_id;
	}

	public function setSession_date($session_date){
		$this->session_date = $session_date;
	}

	public function getSession_date(){
		return $this->session_date;
	}

	public function setSession_active($session_active){
		$this->session_active = $session_active;
	}

	public function getSession_active(){
		return $this->session_active;
	}

}