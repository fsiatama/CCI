<?php
class Permissions {

	private $permissions_id;
	private $permissions_profile_id;
	private $permissions_menu_id;
	private $permissions_list;
	private $permissions_modify;
	private $permissions_create;
	private $permissions_delete;
	private $permissions_export;

	public function setPermissions_id($permissions_id){
		$this->permissions_id = $permissions_id;
	}

	public function getPermissions_id(){
		return $this->permissions_id;
	}

	public function setPermissions_profile_id($permissions_profile_id){
		$this->permissions_profile_id = $permissions_profile_id;
	}

	public function getPermissions_profile_id(){
		return $this->permissions_profile_id;
	}

	public function setPermissions_menu_id($permissions_menu_id){
		$this->permissions_menu_id = $permissions_menu_id;
	}

	public function getPermissions_menu_id(){
		return $this->permissions_menu_id;
	}

	public function setPermissions_list($permissions_list){
		$this->permissions_list = $permissions_list;
	}

	public function getPermissions_list(){
		return $this->permissions_list;
	}

	public function setPermissions_modify($permissions_modify){
		$this->permissions_modify = $permissions_modify;
	}

	public function getPermissions_modify(){
		return $this->permissions_modify;
	}

	public function setPermissions_create($permissions_create){
		$this->permissions_create = $permissions_create;
	}

	public function getPermissions_create(){
		return $this->permissions_create;
	}

	public function setPermissions_delete($permissions_delete){
		$this->permissions_delete = $permissions_delete;
	}

	public function getPermissions_delete(){
		return $this->permissions_delete;
	}

	public function setPermissions_export($permissions_export){
		$this->permissions_export = $permissions_export;
	}

	public function getPermissions_export(){
		return $this->permissions_export;
	}

}