<?php
class Desgravacion_det {

	private $desgravacion_det_id;
	private $desgravacion_det_anio_ini;
	private $desgravacion_det_anio_fin;
	private $desgravacion_det_tasa_intra;
	private $desgravacion_det_tasa_extra;
	private $desgravacion_det_tipo_operacion;
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

	public function setDesgravacion_det_tasa_intra($desgravacion_det_tasa_intra){
		$this->desgravacion_det_tasa_intra = $desgravacion_det_tasa_intra;
	}

	public function getDesgravacion_det_tasa_intra(){
		return $this->desgravacion_det_tasa_intra;
	}

	public function setDesgravacion_det_tasa_extra($desgravacion_det_tasa_extra){
		$this->desgravacion_det_tasa_extra = $desgravacion_det_tasa_extra;
	}

	public function getDesgravacion_det_tasa_extra(){
		return $this->desgravacion_det_tasa_extra;
	}

	public function setDesgravacion_det_tipo_operacion($desgravacion_det_tipo_operacion){
		$this->desgravacion_det_tipo_operacion = $desgravacion_det_tipo_operacion;
	}

	public function getDesgravacion_det_tipo_operacion(){
		return $this->desgravacion_det_tipo_operacion;
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