<?php
class Audit {

	private $audit_id;
	private $audit_table;
	private $audit_script;
	private $audit_method;
	private $audit_parameters;
	private $audit_uinsert;
	private $audit_finsert;

	public function setAudit_id($audit_id){
		$this->audit_id = $audit_id;
	}

	public function getAudit_id(){
		return $this->audit_id;
	}

	public function setAudit_table($audit_table){
		$this->audit_table = $audit_table;
	}

	public function getAudit_table(){
		return $this->audit_table;
	}

	public function setAudit_script($audit_script){
		$this->audit_script = $audit_script;
	}

	public function getAudit_script(){
		return $this->audit_script;
	}

	public function setAudit_method($audit_method){
		$this->audit_method = $audit_method;
	}

	public function getAudit_method(){
		return $this->audit_method;
	}

	public function setAudit_parameters($audit_parameters){
		$this->audit_parameters = $audit_parameters;
	}

	public function getAudit_parameters(){
		return $this->audit_parameters;
	}

	public function setAudit_uinsert($audit_uinsert){
		$this->audit_uinsert = $audit_uinsert;
	}

	public function getAudit_uinsert(){
		return $this->audit_uinsert;
	}

	public function setAudit_finsert($audit_finsert){
		$this->audit_finsert = $audit_finsert;
	}

	public function getAudit_finsert(){
		return $this->audit_finsert;
	}

}