<?php

require_once ('BaseAdo.php');

class SubpartidaAdo extends BaseAdo {

	protected $selectedValues = NULL;

	protected function setTable()
	{
		$this->table = 'subpartida';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id_subpartida';
	}

	protected function setData()
	{
		$subpartida = $this->getModel();

		$id_subpartida = $subpartida->getId_subpartida();
		$id_capitulo   = $subpartida->getId_capitulo();
		$id_partida    = $subpartida->getId_partida();
		$subpartida    = $subpartida->getSubpartida();

		$this->data = compact(
			'id_subpartida',
			'subpartida',
			'id_capitulo',
			'id_partida'
		);
	}

	public function setSelectedValues($selectedValues)
	{
		$this->setSelectedValues = $selectedValues;
	}

	public function getSelectedValues()
	{
		return $this->selectedValues;
	}

	public function create($subpartida)
	{
		$conn = $this->getConnection();
		$this->setModel($subpartida);
		$this->setData();

		$sql = '
			INSERT INTO subpartida (
				id_subpartida,
				subpartida,
				id_capitulo,
				id_partida
			)
			VALUES (
				"'.$this->data['id_subpartida'].'",
				"'.$this->data['subpartida'].'",
				"'.$this->data['id_capitulo'].'",
				"'.$this->data['id_partida'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function buildSelect()
	{
		$filter = array();
		$operator = $this->getOperator();
		$joinOperator = ' AND ';
		$selectedValues = $this->getSelectedValues();
		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($operator == '=') {
					$filter[] = $key . ' ' . $operator . ' "' . $data . '"';
				} elseif ($operator == 'IN') {
					$filter[] = $key . ' ' . $operator . '("' . $data . '")';
					$joinOperator = ' OR ';
				} elseif ($operator == 'NOTIN') {
					$filter[] = 'NOT' . $key . ' IN ("' . $data . '")';
				}
				else {
					if (is_numeric($data)) {
						$filter[] = $key . ' ' . $operator . ' "' . $data . '%"';
					} else {
						$filter[] = $key . ' ' . $operator . ' "%' . $data . '%"';
					}
					$joinOperator = ' OR ';
				}
			}
		}

		$conn = $this->getConnection();
		$sql = '
			SELECT GROUP_CONCAT(DISTINCT id_capitulo SEPARATOR "\',\'") AS capitulos,
			GROUP_CONCAT(DISTINCT id_partida SEPARATOR "\',\'") AS partidas
			FROM subpartida 
		';
		$sqlFilter = '';
		if (!empty($selectedValues)) {
			$sqlFilter = '
			WHERE ( NOT '.$this->primaryKey.' IN ('.$selectedValues.')
				AND NOT id_capitulo IN ('.$selectedValues.')
				AND NOT id_partida IN ('.$selectedValues.')
			)';
			if (!empty($filter)) {
				$sqlFilter .= ' AND ('. implode( $joinOperator, $filter ).')';
			}
		} elseif (!empty($filter)) {
			$sqlFilter  = ' WHERE ('. implode( $joinOperator, $filter ).')';
		}
		$sql       .= $sqlFilter;
		$arrArancel = $conn->getRow($sql);

		$sql = '
		SELECT * FROM (
			SELECT * FROM (	
				SELECT id_subpartida, subpartida
				FROM subpartida 
				'.$sqlFilter.'
			  ) AS subpartidas ';
		if (!empty($arrArancel['capitulos'])) {
			$sql .= '
			UNION SELECT * FROM (
				SELECT CONCAT("",cod_capitulo) AS id_subpartida, descripcion
				FROM arancel
				WHERE cod_capitulo IN (\''.$arrArancel['capitulos'].'\')
				  AND cod_partida    = "00"
				  AND cod_subpartida = "00"
				  AND cod_posicion   = "0000" 
			  ) AS capitulos 
			';
			if (!empty($arrArancel['partidas'])) {
				$sql .= '
					UNION SELECT * FROM (
						SELECT CONCAT(cod_capitulo,cod_partida)  AS id_subpartida, descripcion
						FROM arancel
						WHERE CONCAT(cod_capitulo,cod_partida) IN (\''.$arrArancel['partidas'].'\')
						AND cod_subpartida = "00"
						AND cod_posicion  = "0000"
					  ) AS partidas 
				';
			}
		}
		
		$sql .= '
		) AS qry
		ORDER BY id_subpartida 
		';

		$sql = str_replace("''","'",$sql);

		return $sql;
	}

}
