<?php
class Comtrade_country {

	private $id_country;
	private $country;

	public function setId_country($id_country){
		$this->id_country = $id_country;
	}

	public function getId_country(){
		return $this->id_country;
	}

	public function setCountry($country){
		$this->country = $country;
	}

	public function getCountry(){
		return $this->country;
	}

}