<?php
class Sobordoexp {

	private $id;
	private $anio;
	private $periodo;
	private $fecha;
	private $id_paisdestino;
	private $id_capitulo;
	private $id_partida;
	private $id_subpartida;
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

	public function setFecha($fecha){
		$this->fecha = $fecha;
	}

	public function getFecha(){
		return $this->fecha;
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
		$arrCapitulo   = array();
		$arrPartida    = array();
		$arrSubpartida = array();

		$arrValues = explode(',', $id_subpartida);

		foreach ($arrValues as $value) {
			switch (strlen($value)){
				case 2:
					$arrCapitulo[] = $value;
				break;
				case 4:
					$arrPartida[] = $value;
				break;
				default:
					//descarla los demas digitos despues del 6to, ya que en sobordos solo se tiene hasta subpartida
					$arrSubpartida[] = substr($value, 0, 6);
			}
		}
		//if (!empty($arrCapitulo)) {
			$this->setId_capitulo(implode(',', $arrCapitulo));
		//}
		//if (!empty($arrPartida)) {
			$this->setId_partida(implode(',', $arrPartida));
		//}
		//if (!empty($arrSubpartida)) {
			$this->id_subpartida = implode(',', $arrSubpartida);
		//}
	}

	public function getId_subpartida(){
		return $this->id_subpartida;
	}

	public function setPeso_neto($peso_neto){
		$this->peso_neto = $peso_neto;
	}

	public function getPeso_neto(){
		return $this->peso_neto;
	}

}