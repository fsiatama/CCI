<?php
class Tipo_indicador {

	private $tipo_indicador_id;
	private $tipo_indicador_nombre;
	private $tipo_indicador_abrev;
	private $tipo_indicador_activador;
	private $tipo_indicador_calculo;
	private $tipo_indicador_definicion;
	private $tipo_indicador_html;

	public function setTipo_indicador_id($tipo_indicador_id){
		$this->tipo_indicador_id = $tipo_indicador_id;
	}

	public function getTipo_indicador_id(){
		return $this->tipo_indicador_id;
	}

	public function setTipo_indicador_nombre($tipo_indicador_nombre){
		$this->tipo_indicador_nombre = $tipo_indicador_nombre;
	}

	public function getTipo_indicador_nombre(){
		return $this->tipo_indicador_nombre;
	}

	public function setTipo_indicador_abrev($tipo_indicador_abrev){
		$this->tipo_indicador_abrev = $tipo_indicador_abrev;
	}

	public function getTipo_indicador_abrev(){
		return $this->tipo_indicador_abrev;
	}

	public function setTipo_indicador_activador($tipo_indicador_activador){
		$this->tipo_indicador_activador = $tipo_indicador_activador;
	}

	public function getTipo_indicador_activador(){
		return $this->tipo_indicador_activador;
	}

	public function setTipo_indicador_calculo($tipo_indicador_calculo){
		$this->tipo_indicador_calculo = $tipo_indicador_calculo;
	}

	public function getTipo_indicador_calculo(){
		return $this->tipo_indicador_calculo;
	}

	public function setTipo_indicador_definicion($tipo_indicador_definicion){
		$this->tipo_indicador_definicion = $tipo_indicador_definicion;
	}

	public function getTipo_indicador_definicion(){
		return $this->tipo_indicador_definicion;
	}

	public function setTipo_indicador_html($tipo_indicador_html){
		$this->tipo_indicador_html = $tipo_indicador_html;
	}

	public function getTipo_indicador_html(){
		return $this->tipo_indicador_html;
	}

}