<?php
class Posicion {

	private $id_posicion;
	private $posicion;

	public function setId_posicion($id_posicion){
		$this->id_posicion = $id_posicion;
	}

	public function getId_posicion(){
		return $this->id_posicion;
	}

	public function setPosicion($posicion){
		$this->posicion = $posicion;
	}

	public function getPosicion(){
		return $this->posicion;
	}

}