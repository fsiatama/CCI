<?php

require_once ('BaseAdo.php');

class PosicionAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'posicion';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id_posicion';
	}

	protected function setData()
	{
		$posicion = $this->getModel();

		$id_posicion = $posicion->getId_posicion();
		$posicion = $posicion->getPosicion();

		$this->data = compact(
			'id_posicion',
			'posicion'
		);
	}

	public function create($posicion)
	{
		$conn = $this->getConnection();
		$this->setModel($posicion);
		$this->setData();

		$sql = '
			INSERT INTO posicion (
				id_posicion,
				posicion
			)
			VALUES (
				"'.$this->data['id_posicion'].'",
				"'.$this->data['posicion'].'"
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
		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($operator == '=') {
					$filter[] = $key . ' ' . $operator . ' "' . $data . '"';
				} elseif ($operator == 'IN') {
					$filter[] = $key . ' ' . $operator . '("' . $data . '")';
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
			SELECT GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,2)) AS capitulos,
			GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,4)) AS partidas,
			GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,6)) AS subpartidas 
			FROM posicion 
			LEFT JOIN arancel ON SUBSTRING(id_posicion,1,6) = CONCAT(cod_capitulo, cod_partida, cod_subpartida)
		';
		$sqlFilter = '';
		if(!empty($filter)){
			$sqlFilter  = ' WHERE ('. implode( $joinOperator, $filter ).')';
			$sql       .= $sqlFilter;
		}
		$arrArancel = $conn->getRow($sql);

		$sql = '
		SELECT * FROM (
			SELECT * FROM (	
				SELECT id_posicion, posicion
				FROM posicion 
				'.$sqlFilter.'
			  ) AS posiciones ';
		if (!empty($arrArancel['capitulos'])) {
			$sql .= '
			UNION SELECT * FROM (
				SELECT cod_capitulo AS id_posicion, descripcion
				FROM arancel
				WHERE cod_capitulo IN ('.$arrArancel['capitulos'].')
				  AND cod_partida    IS NULL
				  AND cod_subpartida IS NULL
				  AND cod_posicion   IS NULL 
			  ) AS capitulos 
			';
			if (!empty($arrArancel['partidas'])) {
				$sql .= '
					UNION SELECT * FROM (
						SELECT CONCAT(cod_capitulo,cod_partida)  AS id_posicion, descripcion
						FROM arancel
						WHERE CONCAT(cod_capitulo,cod_partida) IN ('.$arrArancel['partidas'].')
						AND cod_subpartida IS NULL
						AND cod_posicion  IS NULL
					  ) AS partidas 
				';
				if (!empty($arrArancel['subpartidas'])) {
					$sql .= '
						UNION SELECT * FROM (
							SELECT CONCAT(cod_capitulo,cod_partida,cod_subpartida)  AS id_posicion, descripcion
							FROM arancel
							WHERE CONCAT(cod_capitulo,cod_partida,cod_subpartida) IN ('.$arrArancel['subpartidas'].')
							AND cod_posicion  IS NULL
						  ) AS subpartidas
					';
				}
			}
		}
		
		$sql .= '
		) AS qry
		ORDER BY id_posicion 
		';

		//print($sql);

		return $sql;
	}

}
