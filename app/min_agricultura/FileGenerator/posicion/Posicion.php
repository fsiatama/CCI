<?php
class Posicion {

	private $id_posicion;
	private $posicion;
	private $id_capitulo;
	private $id_partida;
	private $id_subpartida;

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

	public function setId_capitulo($id_capitulo){
		$this->id_capitulo = $id_capitulo;
	}

	public function getId_capitulo(){
		return $this->id_capitulo;
	}

	public function setId_partida($id_partida){
		$this->id_partida = $id_partida;
	}

	public function getId_partida(){
		return $this->id_partida;
	}

	public function setId_subpartida($id_subpartida){
		$this->id_subpartida = $id_subpartida;
	}

	public function getId_subpartida(){
		return $this->id_subpartida;
	}

}