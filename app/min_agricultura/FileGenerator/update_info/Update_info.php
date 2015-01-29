<?php
class Update_info {

	private $update_info_id;
	private $update_info_product;
	private $update_info_trade;
	private $update_info_from;
	private $update_info_to;

	public function setUpdate_info_id($update_info_id){
		$this->update_info_id = $update_info_id;
	}

	public function getUpdate_info_id(){
		return $this->update_info_id;
	}

	public function setUpdate_info_product($update_info_product){
		$this->update_info_product = $update_info_product;
	}

	public function getUpdate_info_product(){
		return $this->update_info_product;
	}

	public function setUpdate_info_trade($update_info_trade){
		$this->update_info_trade = $update_info_trade;
	}

	public function getUpdate_info_trade(){
		return $this->update_info_trade;
	}

	public function setUpdate_info_from($update_info_from){
		$this->update_info_from = $update_info_from;
	}

	public function getUpdate_info_from(){
		return $this->update_info_from;
	}

	public function setUpdate_info_to($update_info_to){
		$this->update_info_to = $update_info_to;
	}

	public function getUpdate_info_to(){
		return $this->update_info_to;
	}

}