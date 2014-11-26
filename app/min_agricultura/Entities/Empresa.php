<?php
class Empresa {

	private $id_empresa;
	private $digito_cheq;
	private $empresa;
	private $representante;
	private $id_departamentos;
	private $departamentos;
	private $id_ciudad;
	private $ciudad;
	private $direccion;
	private $telefono;
	private $telefono2;
	private $telefono3;
	private $fax;
	private $fax2;
	private $fax3;
	private $email;
	private $clase;
	private $uap;
	private $altex;
	private $web;
	private $contacto1;
	private $id_tipo_empresa;

	public function setId_empresa($id_empresa){
		$this->id_empresa = $id_empresa;
	}

	public function getId_empresa(){
		return $this->id_empresa;
	}

	public function setDigito_cheq($digito_cheq){
		$this->digito_cheq = $digito_cheq;
	}

	public function getDigito_cheq(){
		return $this->digito_cheq;
	}

	public function setEmpresa($empresa){
		$this->empresa = $empresa;
	}

	public function getEmpresa(){
		return $this->empresa;
	}

	public function setRepresentante($representante){
		$this->representante = $representante;
	}

	public function getRepresentante(){
		return $this->representante;
	}

	public function setId_departamentos($id_departamentos){
		$this->id_departamentos = $id_departamentos;
	}

	public function getId_departamentos(){
		return $this->id_departamentos;
	}

	public function setDepartamentos($departamentos){
		$this->departamentos = $departamentos;
	}

	public function getDepartamentos(){
		return $this->departamentos;
	}

	public function setId_ciudad($id_ciudad){
		$this->id_ciudad = $id_ciudad;
	}

	public function getId_ciudad(){
		return $this->id_ciudad;
	}

	public function setCiudad($ciudad){
		$this->ciudad = $ciudad;
	}

	public function getCiudad(){
		return $this->ciudad;
	}

	public function setDireccion($direccion){
		$this->direccion = $direccion;
	}

	public function getDireccion(){
		return $this->direccion;
	}

	public function setTelefono($telefono){
		$this->telefono = $telefono;
	}

	public function getTelefono(){
		return $this->telefono;
	}

	public function setTelefono2($telefono2){
		$this->telefono2 = $telefono2;
	}

	public function getTelefono2(){
		return $this->telefono2;
	}

	public function setTelefono3($telefono3){
		$this->telefono3 = $telefono3;
	}

	public function getTelefono3(){
		return $this->telefono3;
	}

	public function setFax($fax){
		$this->fax = $fax;
	}

	public function getFax(){
		return $this->fax;
	}

	public function setFax2($fax2){
		$this->fax2 = $fax2;
	}

	public function getFax2(){
		return $this->fax2;
	}

	public function setFax3($fax3){
		$this->fax3 = $fax3;
	}

	public function getFax3(){
		return $this->fax3;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setClase($clase){
		$this->clase = $clase;
	}

	public function getClase(){
		return $this->clase;
	}

	public function setUap($uap){
		$this->uap = $uap;
	}

	public function getUap(){
		return $this->uap;
	}

	public function setAltex($altex){
		$this->altex = $altex;
	}

	public function getAltex(){
		return $this->altex;
	}

	public function setWeb($web){
		$this->web = $web;
	}

	public function getWeb(){
		return $this->web;
	}

	public function setContacto1($contacto1){
		$this->contacto1 = $contacto1;
	}

	public function getContacto1(){
		return $this->contacto1;
	}

	public function setId_tipo_empresa($id_tipo_empresa){
		$this->id_tipo_empresa = $id_tipo_empresa;
	}

	public function getId_tipo_empresa(){
		return $this->id_tipo_empresa;
	}

}