<?php

class User {

	private $user_id;
	private $user_full_name;
	private $user_email;
	private $user_password;
	private $user_active;
	private $user_profile_id;
	private $user_uinsert;
	private $user_finsert;
	private $user_fupdate;

	public function setUser_id($user_id){
		$this->user_id = $user_id;
	}

	public function getUser_id(){
		return $this->user_id;
	}

	public function setUser_full_name($user_full_name){
		$this->user_full_name = Inflector::cleanInputString($user_full_name);
	}

	public function getUser_full_name(){
		return $this->user_full_name;
	}

	public function setUser_email($user_email){
		$this->user_email = Inflector::cleanInputEmail($user_email);
	}

	public function getUser_email(){
		return $this->user_email;
	}

	public function setUser_password($user_password){
		$this->user_password = $user_password;
	}

	public function getUser_password(){
		return $this->user_password;
	}

	public function setUser_active($user_active){
		$this->user_active = $user_active;
	}

	public function getUser_active(){
		return $this->user_active;
	}

	public function setUser_profile_id($user_profile_id){
		$this->user_profile_id = $user_profile_id;
	}

	public function getUser_profile_id(){
		return $this->user_profile_id;
	}

	public function setUser_uinsert($user_uinsert){
		$this->user_uinsert = $user_uinsert;
	}

	public function getUser_uinsert(){
		return $this->user_uinsert;
	}

	public function setUser_finsert($user_finsert){
		$this->user_finsert = $user_finsert;
	}

	public function getUser_finsert(){
		return $this->user_finsert;
	}

	public function setUser_fupdate($user_fupdate){
		$this->user_fupdate = $user_fupdate;
	}

	public function getUser_fupdate(){
		return $this->user_fupdate;
	}

	public function getUserActiveTitleAttribute($key)
	{
		return Lang::get('user.user_active.' . $key);
	}

}