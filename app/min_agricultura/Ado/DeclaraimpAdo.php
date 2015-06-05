<?php

/*include_once(PATH_APP.'adodb5/toexport.inc.php');
include_once(PATH_APP.'lib/pivottable.inc.php'); */

require_once ('BaseAdo.php');

class DeclaraimpAdo extends BaseAdo {

	protected $pivotRowFields        = '';
	protected $pivotColumnFields     = '';
	protected $pivotTotalFields      = [];
	protected $pivotGroupingFunction = '';
	protected $pivotSortColumn       = '';
	protected $arrJoins       		 = [];

	public function setPivotRowFields($pivotRowFields)
	{
		$this->pivotRowFields = $pivotRowFields;
	}

	public function setPivotColumnFields($pivotColumnFields)
	{
		$this->pivotColumnFields = $pivotColumnFields;
	}

	public function setPivotTotalFields($pivotTotalFields)
	{
		$this->pivotTotalFields = (is_array($pivotTotalFields)) ? $pivotTotalFields : [$pivotTotalFields];
	}

	public function setPivotGroupingFunction($pivotGroupingFunction)
	{
		$this->pivotGroupingFunction = $pivotGroupingFunction;
	}

	public function setPivotSortColumn($pivotSortColumn)
	{
		$this->pivotSortColumn = $pivotSortColumn;
	}

	protected function setTable()
	{
		$table = 'declaraimp AS decl';
		$this->setJoins();
		foreach ($this->arrJoins as $tbl => $join) {
			$table .= ' LEFT JOIN ' . $tbl . ' ON ' . $join;
		}
		$this->table = $table;
	}

	protected function setJoins()
	{
		$this->arrJoins = [
			'posicion' => 'decl.id_posicion = posicion.id_posicion',
			'subpartida' => 'decl.id_subpartida = subpartida.id_subpartida',
			'pais'     => 'decl.id_paisprocedencia = pais.id_pais',
		];
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id';
	}

	protected function setData()
	{
		$declaraimp = $this->getModel();

		$id = $declaraimp->getId();
		$anio = $declaraimp->getAnio();
		$periodo = $declaraimp->getPeriodo();
		$fecha = $declaraimp->getFecha();
		$id_empresa = $declaraimp->getId_empresa();
		$id_paisorigen = $declaraimp->getId_paisorigen();
		$id_paiscompra = $declaraimp->getId_paiscompra();
		$id_paisprocedencia = $declaraimp->getId_paisprocedencia();
		$id_deptorigen = $declaraimp->getId_deptorigen();
		$id_capitulo = $declaraimp->getId_capitulo();
		$id_partida = $declaraimp->getId_partida();
		$id_subpartida = $declaraimp->getId_subpartida();
		$id_posicion = $declaraimp->getId_posicion();
		$id_ciiu = $declaraimp->getId_ciiu();
		$valorcif = $declaraimp->getValorcif();
		$valorfob = $declaraimp->getValorfob();
		$peso_neto = $declaraimp->getPeso_neto();
		$arancel_pagado = $declaraimp->getArancel_pagado();
		$valorarancel = $declaraimp->getValorarancel();

		$this->data = compact(
			'id',
			'anio',
			'periodo',
			'fecha',
			'id_empresa',
			'id_paisorigen',
			'id_paiscompra',
			'id_paisprocedencia',
			'id_deptorigen',
			'id_capitulo',
			'id_partida',
			'id_subpartida',
			'id_posicion',
			'id_ciiu',
			'valorcif',
			'valorfob',
			'peso_neto',
			'arancel_pagado',
			'valorarancel'
		);
	}

	public function create($declaraimp)
	{
		$conn = $this->getConnection();
		$this->setModel($declaraimp);
		$this->setData();

		$sql = '
			INSERT INTO declaraimp (
				id,
				anio,
				periodo,
				fecha,
				id_empresa,
				id_paisorigen,
				id_paiscompra,
				id_paisprocedencia,
				id_deptorigen,
				id_capitulo,
				id_partida,
				id_subpartida,
				id_posicion,
				id_ciiu,
				valorcif,
				valorfob,
				peso_neto,
				arancel_pagado,
				valorarancel
			)
			VALUES (
				"'.$this->data['id'].'",
				"'.$this->data['anio'].'",
				"'.$this->data['periodo'].'",
				"'.$this->data['fecha'].'",
				"'.$this->data['id_empresa'].'",
				"'.$this->data['id_paisorigen'].'",
				"'.$this->data['id_paiscompra'].'",
				"'.$this->data['id_paisprocedencia'].'",
				"'.$this->data['id_deptorigen'].'",
				"'.$this->data['id_capitulo'].'",
				"'.$this->data['id_partida'].'",
				"'.$this->data['id_subpartida'].'",
				"'.$this->data['id_posicion'].'",
				"'.$this->data['id_ciiu'].'",
				"'.$this->data['valorcif'].'",
				"'.$this->data['valorfob'].'",
				"'.$this->data['peso_neto'].'",
				"'.$this->data['arancel_pagado'].'",
				"'.$this->data['valorarancel'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function pivotSearch($model)
	{
		$this->setModel($model);
		$this->setOperator('IN');

		$conn = $this->getConnection();
		$this->setData();

		$sql = $this->buildPivotSelect();

		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet);

		return $result;
	}

	public function buildPivotSelect()
	{
		require_once PATH_APP.'lib/pivottable.inc.php';

		$conn  = $this->getConnection();
		$table = $this->getTable();
		$where = $this->buildSelectWhere();

		$sql = PivotTableSQL(
		 	$conn,  										# adodb connection
		 	$table,									  		# tables
			$this->pivotRowFields,							# row fields
			$this->pivotColumnFields,						# column fields
			$where, 										# joins/where
			$this->pivotTotalFields, 						# SUM fields
			'',												# Function Label
			$this->pivotGroupingFunction,					# Function (SUM, COUNT, AGV)
			false
		);

		$sql .= ' ORDER BY ';
		$sql .= (empty($this->pivotSortColumn)) ? 'id' : $this->pivotSortColumn ;

		echo '<pre>'.$sql.'</pre>';

		return $sql;
	}

	public function buildSelect()
	{
		$sql = 'SELECT
			 decl.id,
			 decl.anio,
			 decl.periodo,
			 decl.id_empresa,
			 decl.id_paisorigen,
			 decl.id_paiscompra,
			 decl.id_paisprocedencia,
			 decl.id_capitulo,
			 decl.id_partida,
			 decl.id_subpartida,
			 decl.id_posicion,
			 decl.id_ciiu,
			 decl.valorcif,
			 decl.valorfob,
			 decl.peso_neto
			FROM declaraimp AS decl
		';

		$sql .= $this->buildSelectWhere();

		return $sql;
	}

	public function buildSelectWhere()
	{
		$filter = array();
		$filterPosicion = array();
		$operator = $this->getOperator();
		$joinOperator = ' AND ';
		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($operator == '=') {
					$filter[] = 'decl.' . $key . ' ' . $operator . ' "' . $data . '"';
				}
				elseif ($operator == 'IN') {
					if ($key == 'fecha') {
						
						$filter[] = 'decl.' . $key . ' BETWEEN ' . $data;

					} elseif ($key == 'id_capitulo' || $key == 'id_partida' || $key == 'id_subpartida' || $key == 'id_posicion') {
						//debe colocarle comillas a cada valor dentro del IN
						$arr              = explode(',', $data);
						$filterPosicion[] = 'decl.' . $key . ' ' . $operator . '("' . implode('","', $arr) . '")';
					} else {
						$arr      = explode(',', $data);
						$filter[] = 'decl.' . $key . ' ' . $operator . '("' . implode('","', $arr) . '")';
					}
				}
				else {
					$filter[] = 'decl.' . $key . ' ' . $operator . ' "%' . $data . '%"';
					$joinOperator = ' OR ';
				}
			}
		}

		$sql             = '';
		$whereAssignment = false;

		/*if (!empty($this->arrJoins)) {
			$sql            .= ' WHERE ('. implode( ' AND ', $this->arrJoins ).')';
			$whereAssignment = true;
		}*/

		if(!empty($filter)){
			$sql 			.= ($whereAssignment) ? ' AND ' : ' WHERE ' ;
			$sql            .= ' ('. implode( $joinOperator, $filter ).')';
			$whereAssignment = true;
		}
		if(!empty($filterPosicion)){
			$sql .= ($whereAssignment) ? ' '.$joinOperator : ' WHERE ' ;
			$sql .= ' ('. implode( ' OR ', $filterPosicion ).')';
		}
		return $sql;
	}

}
