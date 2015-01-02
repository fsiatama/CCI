<?php
class Subpartida {

	private $id_subpartida;
	private $subpartida;
	private $id_capitulo;
	private $id_partida;

	public function setId_subpartida($id_subpartida){
		$this->id_subpartida = $id_subpartida;
	}

	public function getId_subpartida(){
		return $this->id_subpartida;
	}

	public function setSubpartida($subpartida){
		$this->subpartida = $subpartida;
	}

	public function getSubpartida(){
		return $this->subpartida;
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

}