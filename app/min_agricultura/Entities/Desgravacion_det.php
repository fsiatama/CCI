<?php
class Desgravacion_det {

	private $desgravacion_det_id;
	private $desgravacion_det_anio_ini;
	private $desgravacion_det_anio_fin;
	private $desgravacion_det_tasa;
	private $desgravacion_det_desgravacion_id;
	private $desgravacion_det_desgravacion_acuerdo_det_id;
	private $desgravacion_det_desgravacion_acuerdo_det_acuerdo_id;

	public function setDesgravacion_det_id($desgravacion_det_id){
		$this->desgravacion_det_id = $desgravacion_det_id;
	}

	public function getDesgravacion_det_id(){
		return $this->desgravacion_det_id;
	}

	public function setDesgravacion_det_anio_ini($desgravacion_det_anio_ini){
		$this->desgravacion_det_anio_ini = $desgravacion_det_anio_ini;
	}

	public function getDesgravacion_det_anio_ini(){
		return $this->desgravacion_det_anio_ini;
	}

	public function setDesgravacion_det_anio_fin($desgravacion_det_anio_fin){
		$this->desgravacion_det_anio_fin = $desgravacion_det_anio_fin;
	}

	public function getDesgravacion_det_anio_fin(){
		return $this->desgravacion_det_anio_fin;
	}

	public function getDesgravacionDetAnioFinTitleAttribute($key)
	{
		$value = ($key == _UNDEFINEDYEAR) ? Lang::get('desgravacion_det.undefined_year') : $key ;
		return $value;
	}

	public function setDesgravacion_det_tasa($desgravacion_det_tasa){
		$this->desgravacion_det_tasa = $desgravacion_det_tasa;
	}

	public function getDesgravacion_det_tasa(){
		return $this->desgravacion_det_tasa;
	}

	public function setDesgravacion_det_desgravacion_id($desgravacion_det_desgravacion_id){
		$this->desgravacion_det_desgravacion_id = $desgravacion_det_desgravacion_id;
	}

	public function getDesgravacion_det_desgravacion_id(){
		return $this->desgravacion_det_desgravacion_id;
	}

	public function setDesgravacion_det_desgravacion_acuerdo_det_id($desgravacion_det_desgravacion_acuerdo_det_id){
		$this->desgravacion_det_desgravacion_acuerdo_det_id = $desgravacion_det_desgravacion_acuerdo_det_id;
	}

	public function getDesgravacion_det_desgravacion_acuerdo_det_id(){
		return $this->desgravacion_det_desgravacion_acuerdo_det_id;
	}

	public function setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id($desgravacion_det_desgravacion_acuerdo_det_acuerdo_id){
		$this->desgravacion_det_desgravacion_acuerdo_det_acuerdo_id = $desgravacion_det_desgravacion_acuerdo_det_acuerdo_id;
	}

	public function getDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id(){
		return $this->desgravacion_det_desgravacion_acuerdo_det_acuerdo_id;
	}

}