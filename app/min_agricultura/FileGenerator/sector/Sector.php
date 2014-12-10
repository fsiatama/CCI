<?php
class Sector {

	private $sector_id;
	private $sector_nombre;
	private $sector_productos;
	private $sector_uinsert;
	private $sector_finsert;
	private $sector_uupdate;
	private $sector_fupdate;

	public function setSector_id($sector_id){
		$this->sector_id = $sector_id;
	}

	public function getSector_id(){
		return $this->sector_id;
	}

	public function setSector_nombre($sector_nombre){
		$this->sector_nombre = $sector_nombre;
	}

	public function getSector_nombre(){
		return $this->sector_nombre;
	}

	public function setSector_productos($sector_productos){
		$this->sector_productos = $sector_productos;
	}

	public function getSector_productos(){
		return $this->sector_productos;
	}

	public function setSector_uinsert($sector_uinsert){
		$this->sector_uinsert = $sector_uinsert;
	}

	public function getSector_uinsert(){
		return $this->sector_uinsert;
	}

	public function setSector_finsert($sector_finsert){
		$this->sector_finsert = $sector_finsert;
	}

	public function getSector_finsert(){
		return $this->sector_finsert;
	}

	public function setSector_uupdate($sector_uupdate){
		$this->sector_uupdate = $sector_uupdate;
	}

	public function getSector_uupdate(){
		return $this->sector_uupdate;
	}

	public function setSector_fupdate($sector_fupdate){
		$this->sector_fupdate = $sector_fupdate;
	}

	public function getSector_fupdate(){
		return $this->sector_fupdate;
	}

}