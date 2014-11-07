<?php
class Indicator {

	private $indicator_id;
	private $indicator_name;
	public function setIndicator_id($indicator_id){
		$this->indicator_id = $indicator_id;
	}

	public function getIndicator_id(){
		return $this->indicator_id;
	}

	public function setIndicator_name($indicator_name){
		$this->indicator_name = $indicator_name;
	}

	public function getIndicator_name(){
		return $this->indicator_name;
	}

}
?>