<?php
class Acuerdo_det {

	private $acuerdo_det_id;
	private $acuerdo_det_arancel_base;
	private $acuerdo_det_productos;
	private $acuerdo_det_productos_desc;
	private $acuerdo_det_administracion;
	private $acuerdo_det_administrador;
	private $acuerdo_det_nperiodos;
	private $acuerdo_det_acuerdo_id;
	private $acuerdo_det_contingente_acumulado_pais;
	private $acuerdo_det_desgravacion_igual_pais;

	public function setAcuerdo_det_id($acuerdo_det_id){
		$this->acuerdo_det_id = $acuerdo_det_id;
	}

	public function getAcuerdo_det_id(){
		return $this->acuerdo_det_id;
	}

	public function setAcuerdo_det_arancel_base($acuerdo_det_arancel_base){
		$this->acuerdo_det_arancel_base = $acuerdo_det_arancel_base;
	}

	public function getAcuerdo_det_arancel_base(){
		return $this->acuerdo_det_arancel_base;
	}

	public function setAcuerdo_det_productos($acuerdo_det_productos){
		$this->acuerdo_det_productos = $acuerdo_det_productos;
	}

	public function getAcuerdo_det_productos(){
		return $this->acuerdo_det_productos;
	}

	public function setAcuerdo_det_productos_desc($acuerdo_det_productos_desc){
		$this->acuerdo_det_productos_desc = $acuerdo_det_productos_desc;
	}

	public function getAcuerdo_det_productos_desc(){
		return $this->acuerdo_det_productos_desc;
	}

	public function setAcuerdo_det_administracion($acuerdo_det_administracion){
		$this->acuerdo_det_administracion = $acuerdo_det_administracion;
	}

	public function getAcuerdo_det_administracion(){
		return $this->acuerdo_det_administracion;
	}

	public function setAcuerdo_det_administrador($acuerdo_det_administrador){
		$this->acuerdo_det_administrador = $acuerdo_det_administrador;
	}

	public function getAcuerdo_det_administrador(){
		return $this->acuerdo_det_administrador;
	}

	public function setAcuerdo_det_nperiodos($acuerdo_det_nperiodos){
		$this->acuerdo_det_nperiodos = $acuerdo_det_nperiodos;
	}

	public function getAcuerdo_det_nperiodos(){
		return $this->acuerdo_det_nperiodos;
	}

	public function setAcuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id){
		$this->acuerdo_det_acuerdo_id = $acuerdo_det_acuerdo_id;
	}

	public function getAcuerdo_det_acuerdo_id(){
		return $this->acuerdo_det_acuerdo_id;
	}

	public function setAcuerdo_det_contingente_acumulado_pais($acuerdo_det_contingente_acumulado_pais){
		$this->acuerdo_det_contingente_acumulado_pais = $acuerdo_det_contingente_acumulado_pais;
	}

	public function getAcuerdo_det_contingente_acumulado_pais(){
		return $this->acuerdo_det_contingente_acumulado_pais;
	}

	public function setAcuerdo_det_desgravacion_igual_pais($acuerdo_det_desgravacion_igual_pais){
		$this->acuerdo_det_desgravacion_igual_pais = $acuerdo_det_desgravacion_igual_pais;
	}

	public function getAcuerdo_det_desgravacion_igual_pais(){
		return $this->acuerdo_det_desgravacion_igual_pais;
	}

}