<?php
class Pais {

	private $pais_id;
	private $pais;

	public function setPais_id($pais_id){
		$this->pais_id = $pais_id;
	}

	public function getPais_id(){
		return $this->pais_id;
	}

	public function setPais($pais){
		$this->pais = $pais;
	}

	public function getPais(){
		return $this->pais;
	}

}