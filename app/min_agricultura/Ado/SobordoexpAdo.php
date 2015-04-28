<?php

require_once ('BaseAdo.php');

class SobordoexpAdo extends BaseAdo {

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
		$table = 'sobordoexp AS sob';
		$this->setJoins();
		foreach ($this->arrJoins as $tbl => $join) {
			$table .= ' LEFT JOIN ' . $tbl . ' ON ' . $join;
		}
		$this->table = $table;
	}

	protected function setJoins()
	{
		$this->arrJoins = [
			'subpartida' => 'sob.id_subpartida  = subpartida.id_subpartida',
			'pais'       => 'sob.id_paisdestino = pais.pais_iata',
		];
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id';
	}

	protected function setData()
	{
		$sobordoexp = $this->getModel();

		$id = $sobordoexp->getId();
		$anio = $sobordoexp->getAnio();
		$periodo = $sobordoexp->getPeriodo();
		$fecha = $sobordoexp->getFecha();
		$id_paisdestino = $sobordoexp->getId_paisdestino();
		$id_capitulo = $sobordoexp->getId_capitulo();
		$id_partida = $sobordoexp->getId_partida();
		$id_subpartida = $sobordoexp->getId_subpartida();
		$peso_neto = $sobordoexp->getPeso_neto();

		$this->data = compact(
			'id',
			'anio',
			'periodo',
			'fecha',
			'id_paisdestino',
			'id_capitulo',
			'id_partida',
			'id_subpartida',
			'peso_neto'
		);
	}

	public function create($sobordoexp)
	{
		$conn = $this->getConnection();
		$this->setModel($sobordoexp);
		$this->setData();

		$sql = '
			INSERT INTO sobordoexp (
				id,
				anio,
				periodo,
				fecha,
				id_paisdestino,
				id_capitulo,
				id_partida,
				id_subpartida,
				peso_neto
			)
			VALUES (
				"'.$this->data['id'].'",
				"'.$this->data['anio'].'",
				"'.$this->data['periodo'].'",
				"'.$this->data['fecha'].'",
				"'.$this->data['id_paisdestino'].'",
				"'.$this->data['id_capitulo'].'",
				"'.$this->data['id_partida'].'",
				"'.$this->data['id_subpartida'].'",
				"'.$this->data['peso_neto'].'"
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
		
		$this->setJoins();
		
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

		//echo '<pre>'.$sql.'</pre>';

		return $sql;
	}

	public function buildSelect()
	{
		$sql = 'SELECT
			 id,
			 anio,
			 periodo,
			 fecha,
			 id_paisdestino,
			 id_capitulo,
			 id_partida,
			 id_subpartida,
			 peso_neto
			FROM sobordoexp
		';

		$sql .= $this->buildSelectWhere();

		return $sql;
	}

	public function buildSelectWhere()
	{
		$filter         = [];
		$filterPosicion = [];
		$operator       = $this->getOperator();
		$joinOperator   = ' AND ';

		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($operator == '=') {
					$filter[] = 'sob.' . $key . ' ' . $operator . ' "' . $data . '"';
				}
				elseif ($operator == 'IN') {
					if ($key == 'fecha') {
						
						$filter[] = 'sob.' . $key . ' BETWEEN ' . $data;

					} elseif ($key == 'id_paisprocedencia') {

						$filter[] = 'pais.id_pais ' . $operator . '(' . $data . ')';

					} elseif ($key == 'id_capitulo' || $key == 'id_partida' || $key == 'id_subpartida') {

						//debe colocarle comillas a cada valor dentro del IN
						$arr              = explode(',', $data);
						$filterPosicion[] = 'sob.' . $key . ' ' . $operator . '("' . implode('","', $arr) . '")';
					} else {
						$arr      = explode(',', $data);
						$filter[] = 'sob.' . $key . ' ' . $operator . '("' . implode('","', $arr) . '")';
					}
				}
				else {
					$filter[] = 'sob.' . $key . ' ' . $operator . ' "%' . $data . '%"';
					$joinOperator = ' OR ';
				}
			}
		}

		$sql             = '';
		$whereAssignment = false;

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
