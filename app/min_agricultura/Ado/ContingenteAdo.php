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
		$contingente_acuerdo_det_id = $contingente->getContingente_acuerdo_det_id();
		$contingente_acuerdo_det_acuerdo_id = $contingente->getContingente_acuerdo_det_acuerdo_id();

		$this->data = compact(
			'contingente_id',
			'contingente_id_pais',
			'contingente_mcontingente',
			'contingente_desc',
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
				contingente_acuerdo_det_id,
				contingente_acuerdo_det_acuerdo_id
			)
			VALUES (
				"'.$this->data['contingente_id'].'",
				"'.$this->data['contingente_id_pais'].'",
				"'.$this->data['contingente_mcontingente'].'",
				"'.$this->data['contingente_desc'].'",
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
		$filter = [];
		$operator = $this->getOperator();
		$joinOperator = ' AND ';
		foreach($this->data as $key => $data){
			if ($data <> ''){
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

		$sql = 'SELECT
			 contingente_id,
			 contingente_id_pais,
			 contingente_mcontingente,
			 contingente_desc,
			 contingente_acuerdo_det_id,
			 contingente_acuerdo_det_acuerdo_id
			FROM contingente
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
