<?php

require_once ('BaseAdo.php');

class ContingenteAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'contingente';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'contingente_id';
	}

	protected function setData()
	{
		$contingente = $this->getModel();

		$contingente_id = $contingente->getContingente_id();
		$contingente_id_pais = $contingente->getContingente_id_pais();
		$contingente_mcontingente = $contingente->getContingente_mcontingente();
		$contingente_desc = $contingente->getContingente_desc();
		$contingente_msalvaguardia = $contingente->getContingente_msalvaguardia();
		$contingente_salvaguardia_sobretasa = $contingente->getContingente_salvaguardia_sobretasa();
		$contingente_acuerdo_det_id = $contingente->getContingente_acuerdo_det_id();
		$contingente_acuerdo_det_acuerdo_id = $contingente->getContingente_acuerdo_det_acuerdo_id();

		$this->data = compact(
			'contingente_id',
			'contingente_id_pais',
			'contingente_mcontingente',
			'contingente_desc',
			'contingente_msalvaguardia',
			'contingente_salvaguardia_sobretasa',
			'contingente_acuerdo_det_id',
			'contingente_acuerdo_det_acuerdo_id'
		);
	}

	public function create($contingente)
	{
		$conn = $this->getConnection();
		$this->setModel($contingente);
		$this->setData();

		$sql = '
			INSERT INTO contingente (
				contingente_id,
				contingente_id_pais,
				contingente_mcontingente,
				contingente_desc,
				contingente_msalvaguardia,
				contingente_salvaguardia_sobretasa,
				contingente_acuerdo_det_id,
				contingente_acuerdo_det_acuerdo_id
			)
			VALUES (
				"'.$this->data['contingente_id'].'",
				"'.$this->data['contingente_id_pais'].'",
				"'.$this->data['contingente_mcontingente'].'",
				"'.$this->data['contingente_desc'].'",
				"'.$this->data['contingente_msalvaguardia'].'",
				"'.$this->data['contingente_salvaguardia_sobretasa'].'",
				"'.$this->data['contingente_acuerdo_det_id'].'",
				"'.$this->data['contingente_acuerdo_det_acuerdo_id'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function buildSelect()
	{
		$filter        = [];
		$primaryFilter = [];
		$operator      = $this->getOperator();
		$joinOperator  = ' AND ';
		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($key == 'contingente_acuerdo_det_id' || $key == 'contingente_acuerdo_det_acuerdo_id') {
					$primaryFilter[] = $key . ' = "' . $data . '"';
				} else {
					if ($operator == '=') {
						$filter[] = $key . ' ' . $operator . ' "' . $data . '"';
					}
					elseif ($operator == 'IN') {
						$filter[] = $key . ' ' . $operator . '("' . $data . '")';
					}
					else {
						$filter[] = $key . ' ' . $operator . ' "%' . $data . '%"';
						$joinOperator = ' OR ';
					}
				}
			}
		}

		$sql = 'SELECT
			 contingente_id,
			 contingente_id_pais,
			 contingente_mcontingente,
			 contingente_desc,
			 contingente_msalvaguardia,
			 contingente_salvaguardia_sobretasa,
			 contingente_acuerdo_det_id,
			 contingente_acuerdo_det_acuerdo_id,
			 acuerdo_mercado_id,
			 mercado_nombre,
			 acuerdo_id_pais,
			 pais
			FROM contingente
			LEFT JOIN acuerdo ON contingente_acuerdo_det_acuerdo_id = acuerdo_id
			LEFT JOIN mercado ON acuerdo_mercado_id = mercado_id
			LEFT JOIN pais ON acuerdo_id_pais = id_pais
		';

		$whereAssignment = false;

		if(!empty($primaryFilter)){
			$sql            .= ' WHERE ('. implode( ' AND ', $primaryFilter ).')';
			$whereAssignment = true;
		}
		if(!empty($filter)){
			$sql .= ($whereAssignment) ? ' AND ' : ' WHERE ' ;
			$sql .= '  ('. implode( $joinOperator, $filter ).')';
		}

		//echo "<pre>$sql</pre>";

		return $sql;
	}

}
