<?php
class Acuerdo {

	private $acuerdo_id;
	private $acuerdo_nombre;
	private $acuerdo_descripcion;
	private $acuerdo_intercambio;
	private $acuerdo_fvigente;
	private $acuerdo_ffirma;
	private $acuerdo_ley;
	private $acuerdo_decreto;
	private $acuerdo_url;
	private $acuerdo_tipo_acuerdo;
	private $acuerdo_uinsert;
	private $acuerdo_finsert;
	private $acuerdo_uupdate;
	private $acuerdo_fupdate;
	private $acuerdo_mercado_id;
	private $acuerdo_id_pais;

	public function setAcuerdo_id($acuerdo_id){
		$this->acuerdo_id = $acuerdo_id;
	}

	public function getAcuerdo_id(){
		return $this->acuerdo_id;
	}

	public function setAcuerdo_nombre($acuerdo_nombre){
		$this->acuerdo_nombre = Inflector::cleanInputString($acuerdo_nombre);
	}

	public function getAcuerdo_nombre(){
		return $this->acuerdo_nombre;
	}

	public function setAcuerdo_descripcion($acuerdo_descripcion){
		$this->acuerdo_descripcion = Inflector::cleanInputString($acuerdo_descripcion);
	}

	public function getAcuerdo_descripcion(){
		return $this->acuerdo_descripcion;
	}

	public function setAcuerdo_intercambio($acuerdo_intercambio){
		$this->acuerdo_intercambio = $acuerdo_intercambio;
	}

	public function getAcuerdo_intercambio(){
		return $this->acuerdo_intercambio;
	}

	public function setAcuerdo_fvigente($acuerdo_fvigente){
		$this->acuerdo_fvigente = $acuerdo_fvigente;
	}

	public function getAcuerdo_fvigente(){
		return $this->acuerdo_fvigente;
	}

	public function getAcuerdoFvigenteTitleAttribute($key)
	{
		$date = Helpers::getDate($key);
		return Lang::get('shared.months.'.$date->format('m')).' - '.$date->format('Y');
	}
	
	public function setAcuerdo_ffirma($acuerdo_ffirma){
		$this->acuerdo_ffirma = $acuerdo_ffirma;
	}

	public function getAcuerdoFfirmaTitleAttribute($key)
	{
		$date = Helpers::getDate($key);
		return Lang::get('shared.months.'.$date->format('m')).' - '.$date->format('Y');
	}

	public function getAcuerdo_ffirma(){
		return $this->acuerdo_ffirma;
	}

	public function setAcuerdo_ley($acuerdo_ley){
		$this->acuerdo_ley = Inflector::cleanInputString($acuerdo_ley);
	}

	public function getAcuerdo_ley(){
		return $this->acuerdo_ley;
	}

	public function setAcuerdo_decreto($acuerdo_decreto){
		$this->acuerdo_decreto = Inflector::cleanInputString($acuerdo_decreto);
	}

	public function getAcuerdo_decreto(){
		return $this->acuerdo_decreto;
	}

	public function setAcuerdo_url($acuerdo_url){
		$this->acuerdo_url = $acuerdo_url;
	}

	public function getAcuerdo_url(){
		return $this->acuerdo_url;
	}

	public function setAcuerdo_tipo_acuerdo($acuerdo_tipo_acuerdo){
		$this->acuerdo_tipo_acuerdo = Inflector::cleanInputString($acuerdo_tipo_acuerdo);
	}

	public function getAcuerdo_tipo_acuerdo(){
		return $this->acuerdo_tipo_acuerdo;
	}

	public function setAcuerdo_uinsert($acuerdo_uinsert){
		$this->acuerdo_uinsert = $acuerdo_uinsert;
	}

	public function getAcuerdo_uinsert(){
		return $this->acuerdo_uinsert;
	}

	public function setAcuerdo_finsert($acuerdo_finsert){
		$this->acuerdo_finsert = $acuerdo_finsert;
	}

	public function getAcuerdo_finsert(){
		return $this->acuerdo_finsert;
	}

	public function setAcuerdo_uupdate($acuerdo_uupdate){
		$this->acuerdo_uupdate = $acuerdo_uupdate;
	}

	public function getAcuerdo_uupdate(){
		return $this->acuerdo_uupdate;
	}

	public function setAcuerdo_fupdate($acuerdo_fupdate){
		$this->acuerdo_fupdate = $acuerdo_fupdate;
	}

	public function getAcuerdo_fupdate(){
		return $this->acuerdo_fupdate;
	}

	public function setAcuerdo_mercado_id($acuerdo_mercado_id){
		$this->acuerdo_mercado_id = $acuerdo_mercado_id;
	}

	public function getAcuerdo_mercado_id(){
		return $this->acuerdo_mercado_id;
	}

	public function setAcuerdo_id_pais($acuerdo_id_pais){
		$this->acuerdo_id_pais = $acuerdo_id_pais;
	}

	public function getAcuerdo_id_pais(){
		return $this->acuerdo_id_pais;
	}

	public function getAcuerdoIntercambioTitleAttribute($key)
	{
		return Lang::get('indicador.reports.' . $key);
	}

}