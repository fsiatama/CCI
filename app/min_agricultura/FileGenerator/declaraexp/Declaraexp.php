<?php
class Declaraexp {

	private $id;
	private $anio;
	private $periodo;
	private $id_empresa;
	private $id_paisdestino;
	private $id_capitulo;
	private $id_partida;
	private $id_subpartida;
	private $id_posicion;
	private $id_ciiu;
	private $valorfob;
	private $valorcif;
	private $peso_neto;

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setAnio($anio){
		$this->anio = $anio;
	}

	public function getAnio(){
		return $this->anio;
	}

	public function setPeriodo($periodo){
		$this->periodo = $periodo;
	}

	public function getPeriodo(){
		return $this->periodo;
	}

	public function setId_empresa($id_empresa){
		$this->id_empresa = $id_empresa;
	}

	public function getId_empresa(){
		return $this->id_empresa;
	}

	public function setId_paisdestino($id_paisdestino){
		$this->id_paisdestino = $id_paisdestino;
	}

	public function getId_paisdestino(){
		return $this->id_paisdestino;
	}

	public function setId_capitulo($id_capitulo){
		$this->id_capitulo = $id_capitulo;
	}

	public function getId_capitulo(){
		return $this->id_capitulo;
	}

	public function setId_partida($id_partida){
		$this->id_partida = $id_partida;
	}

	public function getId_partida(){
		return $this->id_partida;
	}

	public function setId_subpartida($id_subpartida){
		$this->id_subpartida = $id_subpartida;
	}

	public function getId_subpartida(){
		return $this->id_subpartida;
	}

	public function setId_posicion($id_posicion){
		$this->id_posicion = $id_posicion;
	}

	public function getId_posicion(){
		return $this->id_posicion;
	}

	public function setId_ciiu($id_ciiu){
		$this->id_ciiu = $id_ciiu;
	}

	public function getId_ciiu(){
		return $this->id_ciiu;
	}

	public function setValorfob($valorfob){
		$this->valorfob = $valorfob;
	}

	public function getValorfob(){
		return $this->valorfob;
	}

	public function setValorcif($valorcif){
		$this->valorcif = $valorcif;
	}

	public function getValorcif(){
		return $this->valorcif;
	}

	public function setPeso_neto($peso_neto){
		$this->peso_neto = $peso_neto;
	}

	public function getPeso_neto(){
		return $this->peso_neto;
	}

}