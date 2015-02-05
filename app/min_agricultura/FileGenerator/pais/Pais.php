<?php
class Pais {

	private $id_pais;
	private $pais;
	private $pais_iata;

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

	public function setPais_iata($pais_iata){
		$this->pais_iata = $pais_iata;
	}

	public function getPais_iata(){
		return $this->pais_iata;
	}

}