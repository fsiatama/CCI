<?php
class Declaraimp {

	private $id;
	private $anio;
	private $periodo;
	private $id_empresa;
	private $id_paisorigen;
	private $id_paiscompra;
	private $id_paisprocedencia;
	private $id_capitulo;
	private $id_partida;
	private $id_subpartida;
	private $id_posicion;
	private $id_ciiu;
	private $valorcif;
	private $valorfob;
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

	public function setId_paisorigen($id_paisorigen){
		$this->id_paisorigen = $id_paisorigen;
	}

	public function getId_paisorigen(){
		return $this->id_paisorigen;
	}

	public function setId_paiscompra($id_paiscompra){
		$this->id_paiscompra = $id_paiscompra;
	}

	public function getId_paiscompra(){
		return $this->id_paiscompra;
	}

	public function setId_paisprocedencia($id_paisprocedencia){
		$this->id_paisprocedencia = $id_paisprocedencia;
	}

	public function getId_paisprocedencia(){
		return $this->id_paisprocedencia;
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

		$arrCapitulo   = array();
		$arrPartida    = array();
		$arrSubpartida = array();
		$arrPosicion   = array();

		$arrValues = explode(',', $id_posicion);

		foreach ($arrValues as $value) {
			switch (strlen($value)){
				case 2:
					$arrCapitulo[] = $value;
				break;
				case 4:
					$arrPartida[] = $value;
				break;
				case 6:
					$arrSubpartida[] = $value;
				break;
				default:
					$arrPosicion[] = $value;
			}
		}
		if (!empty($arrCapitulo)) {
			$this->setId_capitulo(implode(',', $arrCapitulo));
		}
		if (!empty($arrPartida)) {
			$this->setId_partida(implode(',', $arrPartida));
		}
		if (!empty($arrSubpartida)) {
			$this->setId_subpartida(implode(',', $arrSubpartida));
		}
		if (!empty($arrPosicion)) {
			$this->id_posicion = implode(',', $arrPosicion);
		}

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

	public function setValorcif($valorcif){
		$this->valorcif = $valorcif;
	}

	public function getValorcif(){
		return $this->valorcif;
	}

	public function setValorfob($valorfob){
		$this->valorfob = $valorfob;
	}

	public function getValorfob(){
		return $this->valorfob;
	}

	public function setPeso_neto($peso_neto){
		$this->peso_neto = $peso_neto;
	}

	public function getPeso_neto(){
		return $this->peso_neto;
	}

}