<?php
class Sobordoimp {

	private $id;
	private $anio;
	private $periodo;
	private $fecha;
	private $id_paisprocedencia;
	private $id_capitulo;
	private $id_partida;
	private $id_subpartida;
	private $peso_neto;

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setAnio($anio){
		$this->anio = $anio;
	}

	public function getAnio(){
		return $this->anio;
	}

	public function setPeriodo($periodo){
		$this->periodo = $periodo;
	}

	public function getPeriodo(){
		return $this->periodo;
	}

	public function setFecha($fecha){
		$this->fecha = $fecha;
	}

	public function getFecha(){
		return $this->fecha;
	}

	public function setId_paisprocedencia($id_paisprocedencia){
		$this->id_paisprocedencia = $id_paisprocedencia;
	}

	public function getId_paisprocedencia(){
		return $this->id_paisprocedencia;
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

	public function setPeso_neto($peso_neto){
		$this->peso_neto = $peso_neto;
	}

	public function getPeso_neto(){
		return $this->peso_neto;
	}

}