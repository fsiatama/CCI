<?php
class Mercado {

	private $mercado_id;
	private $mercado_nombre;
	private $mercado_paises;
	private $mercado_bandera;
	private $mercado_uinsert;
	private $mercado_finsert;
	private $mercado_uupdate;
	private $mercado_fupdate;

	public function setMercado_id($mercado_id){
		$this->mercado_id = $mercado_id;
	}

	public function getMercado_id(){
		return $this->mercado_id;
	}

	public function setMercado_nombre($mercado_nombre){
		$this->mercado_nombre = Inflector::cleanInputString($mercado_nombre);
	}

	public function getMercado_nombre(){
		return $this->mercado_nombre;
	}

	public function setMercado_paises($mercado_paises){
		$this->mercado_paises = $mercado_paises;
	}

	public function getMercado_paises(){
		return $this->mercado_paises;
	}

	public function setMercado_bandera($mercado_bandera){
		$this->mercado_bandera = $mercado_bandera;
	}

	public function getMercado_bandera(){
		return $this->mercado_bandera;
	}

	public function setMercado_uinsert($mercado_uinsert){
		$this->mercado_uinsert = $mercado_uinsert;
	}

	public function getMercado_uinsert(){
		return $this->mercado_uinsert;
	}

	public function setMercado_finsert($mercado_finsert){
		$this->mercado_finsert = $mercado_finsert;
	}

	public function getMercado_finsert(){
		return $this->mercado_finsert;
	}

	public function setMercado_uupdate($mercado_uupdate){
		$this->mercado_uupdate = $mercado_uupdate;
	}

	public function getMercado_uupdate(){
		return $this->mercado_uupdate;
	}

	public function setMercado_fupdate($mercado_fupdate){
		$this->mercado_fupdate = $mercado_fupdate;
	}

	public function getMercado_fupdate(){
		return $this->mercado_fupdate;
	}

}