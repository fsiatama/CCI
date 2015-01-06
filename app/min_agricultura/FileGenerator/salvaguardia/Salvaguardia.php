<?php
class Salvaguardia {

	private $salvaguardia_id;
	private $salvaguardia_msalvaguardia;
	private $salvaguardia_contingente_id;
	private $salvaguardia_contingente_acuerdo_det_id;
	private $salvaguardia_contingente_acuerdo_det_acuerdo_id;

	public function setSalvaguardia_id($salvaguardia_id){
		$this->salvaguardia_id = $salvaguardia_id;
	}

	public function getSalvaguardia_id(){
		return $this->salvaguardia_id;
	}

	public function setSalvaguardia_msalvaguardia($salvaguardia_msalvaguardia){
		$this->salvaguardia_msalvaguardia = $salvaguardia_msalvaguardia;
	}

	public function getSalvaguardia_msalvaguardia(){
		return $this->salvaguardia_msalvaguardia;
	}

	public function setSalvaguardia_contingente_id($salvaguardia_contingente_id){
		$this->salvaguardia_contingente_id = $salvaguardia_contingente_id;
	}

	public function getSalvaguardia_contingente_id(){
		return $this->salvaguardia_contingente_id;
	}

	public function setSalvaguardia_contingente_acuerdo_det_id($salvaguardia_contingente_acuerdo_det_id){
		$this->salvaguardia_contingente_acuerdo_det_id = $salvaguardia_contingente_acuerdo_det_id;
	}

	public function getSalvaguardia_contingente_acuerdo_det_id(){
		return $this->salvaguardia_contingente_acuerdo_det_id;
	}

	public function setSalvaguardia_contingente_acuerdo_det_acuerdo_id($salvaguardia_contingente_acuerdo_det_acuerdo_id){
		$this->salvaguardia_contingente_acuerdo_det_acuerdo_id = $salvaguardia_contingente_acuerdo_det_acuerdo_id;
	}

	public function getSalvaguardia_contingente_acuerdo_det_acuerdo_id(){
		return $this->salvaguardia_contingente_acuerdo_det_acuerdo_id;
	}

}