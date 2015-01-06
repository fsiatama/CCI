<?php
class Contingente_det {

	private $contingente_det_id;
	private $contingente_det_anio_ini;
	private $contingente_det_anio_fin;
	private $contingente_det_peso_neto;
	private $contingente_det_contingente_id;
	private $contingente_det_contingente_acuerdo_det_id;
	private $contingente_det_contingente_acuerdo_det_acuerdo_id;

	public function setContingente_det_id($contingente_det_id){
		$this->contingente_det_id = $contingente_det_id;
	}

	public function getContingente_det_id(){
		return $this->contingente_det_id;
	}

	public function setContingente_det_anio_ini($contingente_det_anio_ini){
		$this->contingente_det_anio_ini = $contingente_det_anio_ini;
	}

	public function getContingente_det_anio_ini(){
		return $this->contingente_det_anio_ini;
	}

	public function setContingente_det_anio_fin($contingente_det_anio_fin){
		$this->contingente_det_anio_fin = $contingente_det_anio_fin;
	}

	public function getContingente_det_anio_fin(){
		return $this->contingente_det_anio_fin;
	}

	public function setContingente_det_peso_neto($contingente_det_peso_neto){
		$this->contingente_det_peso_neto = $contingente_det_peso_neto;
	}

	public function getContingente_det_peso_neto(){
		return $this->contingente_det_peso_neto;
	}

	public function setContingente_det_contingente_id($contingente_det_contingente_id){
		$this->contingente_det_contingente_id = $contingente_det_contingente_id;
	}

	public function getContingente_det_contingente_id(){
		return $this->contingente_det_contingente_id;
	}

	public function setContingente_det_contingente_acuerdo_det_id($contingente_det_contingente_acuerdo_det_id){
		$this->contingente_det_contingente_acuerdo_det_id = $contingente_det_contingente_acuerdo_det_id;
	}

	public function getContingente_det_contingente_acuerdo_det_id(){
		return $this->contingente_det_contingente_acuerdo_det_id;
	}

	public function setContingente_det_contingente_acuerdo_det_acuerdo_id($contingente_det_contingente_acuerdo_det_acuerdo_id){
		$this->contingente_det_contingente_acuerdo_det_acuerdo_id = $contingente_det_contingente_acuerdo_det_acuerdo_id;
	}

	public function getContingente_det_contingente_acuerdo_det_acuerdo_id(){
		return $this->contingente_det_contingente_acuerdo_det_acuerdo_id;
	}

}