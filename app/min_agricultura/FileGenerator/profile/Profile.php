<?php
class Profile {

	private $profile_id;
	private $profile_name;

	public function setProfile_id($profile_id){
		$this->profile_id = $profile_id;
	}

	public function getProfile_id(){
		return $this->profile_id;
	}

	public function setProfile_name($profile_name){
		$this->profile_name = $profile_name;
	}

	public function getProfile_name(){
		return $this->profile_name;
	}

}