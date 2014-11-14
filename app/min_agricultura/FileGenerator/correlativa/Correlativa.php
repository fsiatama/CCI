<?php
class Correlativa {

	private $correlativa_id;
	private $correlativa_origen_posicion_id;
	private $correlativa_destino_posicion_id;
	private $correlativa_fvigente;
	private $correlativa_decreto;
	private $correlativa_observacion;
	private $correlativa_uinsert;
	private $correlativa_finsert;

	public function setCorrelativa_id($correlativa_id){
		$this->correlativa_id = $correlativa_id;
	}

	public function getCorrelativa_id(){
		return $this->correlativa_id;
	}

	public function setCorrelativa_origen_posicion_id($correlativa_origen_posicion_id){
		$this->correlativa_origen_posicion_id = $correlativa_origen_posicion_id;
	}

	public function getCorrelativa_origen_posicion_id(){
		return $this->correlativa_origen_posicion_id;
	}

	public function setCorrelativa_destino_posicion_id($correlativa_destino_posicion_id){
		$this->correlativa_destino_posicion_id = $correlativa_destino_posicion_id;
	}

	public function getCorrelativa_destino_posicion_id(){
		return $this->correlativa_destino_posicion_id;
	}

	public function setCorrelativa_fvigente($correlativa_fvigente){
		$this->correlativa_fvigente = $correlativa_fvigente;
	}

	public function getCorrelativa_fvigente(){
		return $this->correlativa_fvigente;
	}

	public function setCorrelativa_decreto($correlativa_decreto){
		$this->correlativa_decreto = $correlativa_decreto;
	}

	public function getCorrelativa_decreto(){
		return $this->correlativa_decreto;
	}

	public function setCorrelativa_observacion($correlativa_observacion){
		$this->correlativa_observacion = $correlativa_observacion;
	}

	public function getCorrelativa_observacion(){
		return $this->correlativa_observacion;
	}

	public function setCorrelativa_uinsert($correlativa_uinsert){
		$this->correlativa_uinsert = $correlativa_uinsert;
	}

	public function getCorrelativa_uinsert(){
		return $this->correlativa_uinsert;
	}

	public function setCorrelativa_finsert($correlativa_finsert){
		$this->correlativa_finsert = $correlativa_finsert;
	}

	public function getCorrelativa_finsert(){
		return $this->correlativa_finsert;
	}

}