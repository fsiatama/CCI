<?php
class Posicion {

	private $posicion_id;
	private $posicion;

	public function setPosicion_id($posicion_id){
		$this->posicion_id = $posicion_id;
	}

	public function getPosicion_id(){
		return $this->posicion_id;
	}

	public function setPosicion($posicion){
		$this->posicion = $posicion;
	}

	public function getPosicion(){
		return $this->posicion;
	}

}