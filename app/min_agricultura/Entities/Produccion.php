<?php
class Produccion {

	private $produccion_id;
	private $produccion_sector_id;
	private $produccion_anio;
	private $produccion_peso_neto;
	private $produccion_finsert;
	private $produccion_uinsert;
	private $produccion_fupdate;
	private $produccion_uupdate;

	public function setProduccion_id($produccion_id){
		$this->produccion_id = $produccion_id;
	}

	public function getProduccion_id(){
		return $this->produccion_id;
	}

	public function setProduccion_sector_id($produccion_sector_id){
		$this->produccion_sector_id = $produccion_sector_id;
	}

	public function getProduccion_sector_id(){
		return $this->produccion_sector_id;
	}

	public function setProduccion_anio($produccion_anio){
		$this->produccion_anio = $produccion_anio;
	}

	public function getProduccion_anio(){
		return $this->produccion_anio;
	}

	public function setProduccion_peso_neto($produccion_peso_neto){
		$this->produccion_peso_neto = $produccion_peso_neto;
	}

	public function getProduccion_peso_neto(){
		return $this->produccion_peso_neto;
	}

	public function setProduccion_finsert($produccion_finsert){
		$this->produccion_finsert = $produccion_finsert;
	}

	public function getProduccion_finsert(){
		return $this->produccion_finsert;
	}

	public function setProduccion_uinsert($produccion_uinsert){
		$this->produccion_uinsert = $produccion_uinsert;
	}

	public function getProduccion_uinsert(){
		return $this->produccion_uinsert;
	}

	public function setProduccion_fupdate($produccion_fupdate){
		$this->produccion_fupdate = $produccion_fupdate;
	}

	public function getProduccion_fupdate(){
		return $this->produccion_fupdate;
	}

	public function setProduccion_uupdate($produccion_uupdate){
		$this->produccion_uupdate = $produccion_uupdate;
	}

	public function getProduccion_uupdate(){
		return $this->produccion_uupdate;
	}

}