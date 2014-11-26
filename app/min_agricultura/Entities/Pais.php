<?php
class Pais {

	private $id_pais;
	private $pais;

	public function setId_pais($id_pais){
		$this->id_pais = $id_pais;
	}

	public function getId_pais(){
		return $this->id_pais;
	}

	public function setPais($pais){
		$this->pais = $pais;
	}

	public function getPais(){
		return $this->pais;
	}

}