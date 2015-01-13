<?php
class Contingente {

	private $contingente_id;
	private $contingente_id_pais;
	private $contingente_mcontingente;
	private $contingente_desc;
	private $contingente_msalvaguardia;
	private $contingente_salvaguardia_sobretasa;
	private $contingente_acuerdo_det_id;
	private $contingente_acuerdo_det_acuerdo_id;

	public function setContingente_id($contingente_id){
		$this->contingente_id = $contingente_id;
	}

	public function getContingente_id(){
		return $this->contingente_id;
	}

	public function setContingente_id_pais($contingente_id_pais){
		$this->contingente_id_pais = $contingente_id_pais;
	}

	public function getContingente_id_pais(){
		return $this->contingente_id_pais;
	}

	public function setContingente_mcontingente($contingente_mcontingente){
		$this->contingente_mcontingente = $contingente_mcontingente;
	}

	public function getContingente_mcontingente(){
		return $this->contingente_mcontingente;
	}

	public function setContingente_desc($contingente_desc){
		$this->contingente_desc = $contingente_desc;
	}

	public function getContingente_desc(){
		return $this->contingente_desc;
	}

	public function setContingente_msalvaguardia($contingente_msalvaguardia){
		$this->contingente_msalvaguardia = $contingente_msalvaguardia;
	}

	public function getContingente_msalvaguardia(){
		return $this->contingente_msalvaguardia;
	}

	public function setContingente_salvaguardia_sobretasa($contingente_salvaguardia_sobretasa){
		$this->contingente_salvaguardia_sobretasa = $contingente_salvaguardia_sobretasa;
	}

	public function getContingente_salvaguardia_sobretasa(){
		return $this->contingente_salvaguardia_sobretasa;
	}

	public function setContingente_acuerdo_det_id($contingente_acuerdo_det_id){
		$this->contingente_acuerdo_det_id = $contingente_acuerdo_det_id;
	}

	public function getContingente_acuerdo_det_id(){
		return $this->contingente_acuerdo_det_id;
	}

	public function setContingente_acuerdo_det_acuerdo_id($contingente_acuerdo_det_acuerdo_id){
		$this->contingente_acuerdo_det_acuerdo_id = $contingente_acuerdo_det_acuerdo_id;
	}

	public function getContingente_acuerdo_det_acuerdo_id(){
		return $this->contingente_acuerdo_det_acuerdo_id;
	}

	public function getContingenteMcontingenteTitleAttribute($key)
	{
		return Lang::get('contingente.contingente_mcontingente.' . $key);
	}

	public function getContingenteMsalvaguardiaTitleAttribute($key)
	{
		return Lang::get('contingente.contingente_msalvaguardia.' . $key);
	}

}