<?php
class Desgravacion {

	private $desgravacion_id;
	private $desgravacion_id_pais;
	private $desgravacion_mdesgravacion;
	private $desgravacion_desc;
	private $desgravacion_acuerdo_det_id;
	private $desgravacion_acuerdo_det_acuerdo_id;

	public function setDesgravacion_id($desgravacion_id){
		$this->desgravacion_id = $desgravacion_id;
	}

	public function getDesgravacion_id(){
		return $this->desgravacion_id;
	}

	public function setDesgravacion_id_pais($desgravacion_id_pais){
		$this->desgravacion_id_pais = $desgravacion_id_pais;
	}

	public function getDesgravacion_id_pais(){
		return $this->desgravacion_id_pais;
	}

	public function setDesgravacion_mdesgravacion($desgravacion_mdesgravacion){
		$this->desgravacion_mdesgravacion = $desgravacion_mdesgravacion;
	}

	public function getDesgravacion_mdesgravacion(){
		return $this->desgravacion_mdesgravacion;
	}

	public function setDesgravacion_desc($desgravacion_desc){
		$this->desgravacion_desc = $desgravacion_desc;
	}

	public function getDesgravacion_desc(){
		return $this->desgravacion_desc;
	}

	public function setDesgravacion_acuerdo_det_id($desgravacion_acuerdo_det_id){
		$this->desgravacion_acuerdo_det_id = $desgravacion_acuerdo_det_id;
	}

	public function getDesgravacion_acuerdo_det_id(){
		return $this->desgravacion_acuerdo_det_id;
	}

	public function setDesgravacion_acuerdo_det_acuerdo_id($desgravacion_acuerdo_det_acuerdo_id){
		$this->desgravacion_acuerdo_det_acuerdo_id = $desgravacion_acuerdo_det_acuerdo_id;
	}

	public function getDesgravacion_acuerdo_det_acuerdo_id(){
		return $this->desgravacion_acuerdo_det_acuerdo_id;
	}

}