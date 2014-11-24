<?php
class Indicador {

	private $indicador_id;
	private $indicador_nombre;
	private $indicador_tipo_indicador_id;
	private $indicador_campos;
	private $indicador_filtros;
	private $indicador_leaf;
	private $indicador_uinsert;
	private $indicador_finsert;
	private $indicador_fupdate;

	public function setIndicador_id($indicador_id){
		$this->indicador_id = $indicador_id;
	}

	public function getIndicador_id(){
		return $this->indicador_id;
	}

	public function setIndicador_nombre($indicador_nombre){
		$this->indicador_nombre = $indicador_nombre;
	}

	public function getIndicador_nombre(){
		return $this->indicador_nombre;
	}

	public function setIndicador_tipo_indicador_id($indicador_tipo_indicador_id){
		$this->indicador_tipo_indicador_id = $indicador_tipo_indicador_id;
	}

	public function getIndicador_tipo_indicador_id(){
		return $this->indicador_tipo_indicador_id;
	}

	public function setIndicador_campos($indicador_campos){
		$this->indicador_campos = $indicador_campos;
	}

	public function getIndicador_campos(){
		return $this->indicador_campos;
	}

	public function setIndicador_filtros($indicador_filtros){
		$this->indicador_filtros = $indicador_filtros;
	}

	public function getIndicador_filtros(){
		return $this->indicador_filtros;
	}

	public function setIndicador_leaf($indicador_leaf){
		$this->indicador_leaf = $indicador_leaf;
	}

	public function getIndicador_leaf(){
		return $this->indicador_leaf;
	}

	public function setIndicador_uinsert($indicador_uinsert){
		$this->indicador_uinsert = $indicador_uinsert;
	}

	public function getIndicador_uinsert(){
		return $this->indicador_uinsert;
	}

	public function setIndicador_finsert($indicador_finsert){
		$this->indicador_finsert = $indicador_finsert;
	}

	public function getIndicador_finsert(){
		return $this->indicador_finsert;
	}

	public function setIndicador_fupdate($indicador_fupdate){
		$this->indicador_fupdate = $indicador_fupdate;
	}

	public function getIndicador_fupdate(){
		return $this->indicador_fupdate;
	}

}