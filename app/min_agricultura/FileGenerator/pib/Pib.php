<?php
class Pib {

	private $pib_id;
	private $pib_anio;
	private $pib_periodo;
	private $pib_valor;
	private $pib_finsert;
	private $pib_uinsert;
	private $pib_fupdate;
	private $pib_uupdate;

	public function setPib_id($pib_id){
		$this->pib_id = $pib_id;
	}

	public function getPib_id(){
		return $this->pib_id;
	}

	public function setPib_anio($pib_anio){
		$this->pib_anio = $pib_anio;
	}

	public function getPib_anio(){
		return $this->pib_anio;
	}

	public function setPib_periodo($pib_periodo){
		$this->pib_periodo = $pib_periodo;
	}

	public function getPib_periodo(){
		return $this->pib_periodo;
	}

	public function setPib_valor($pib_valor){
		$this->pib_valor = $pib_valor;
	}

	public function getPib_valor(){
		return $this->pib_valor;
	}

	public function setPib_finsert($pib_finsert){
		$this->pib_finsert = $pib_finsert;
	}

	public function getPib_finsert(){
		return $this->pib_finsert;
	}

	public function setPib_uinsert($pib_uinsert){
		$this->pib_uinsert = $pib_uinsert;
	}

	public function getPib_uinsert(){
		return $this->pib_uinsert;
	}

	public function setPib_fupdate($pib_fupdate){
		$this->pib_fupdate = $pib_fupdate;
	}

	public function getPib_fupdate(){
		return $this->pib_fupdate;
	}

	public function setPib_uupdate($pib_uupdate){
		$this->pib_uupdate = $pib_uupdate;
	}

	public function getPib_uupdate(){
		return $this->pib_uupdate;
	}

}