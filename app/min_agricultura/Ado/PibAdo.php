<?php

require_once ('BaseAdo.php');

class PibAdo extends BaseAdo {

	protected $pivotRowFields        = '';
	protected $pivotColumnFields     = '';
	protected $pivotTotalFields      = [];
	protected $pivotGroupingFunction = '';
	protected $pivotSortColumn       = '';

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
		$this->table = 'pib';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'pib_id';
	}

	protected function setData()
	{
		$pib = $this->getModel();

		$pib_id = $pib->getPib_id();
		$pib_anio = $pib->getPib_anio();
		$pib_periodo = $pib->getPib_periodo();
		$pib_agricultura = $pib->getPib_agricultura();
		$pib_nacional = $pib->getPib_nacional();
		$pib_finsert = $pib->getPib_finsert();
		$pib_uinsert = $pib->getPib_uinsert();
		$pib_fupdate = $pib->getPib_fupdate();
		$pib_uupdate = $pib->getPib_uupdate();

		$this->data = compact(
			'pib_id',
			'pib_anio',
			'pib_periodo',
			'pib_agricultura',
			'pib_nacional',
			'pib_finsert',
			'pib_uinsert',
			'pib_fupdate',
			'pib_uupdate'
		);
	}

	public function create($pib)
	{
		$conn = $this->getConnection();
		$this->setModel($pib);
		$this->setData();

		$sql = '
			INSERT INTO pib (
				pib_id,
				pib_anio,
				pib_periodo,
				pib_agricultura,
				pib_nacional,
				pib_finsert,
				pib_uinsert,
				pib_fupdate,
				pib_uupdate
			)
			VALUES (
				"'.$this->data['pib_id'].'",
				"'.$this->data['pib_anio'].'",
				"'.$this->data['pib_periodo'].'",
				"'.$this->data['pib_agricultura'].'",
				"'.$this->data['pib_nacional'].'",
				"'.$this->data['pib_finsert'].'",
				"'.$this->data['pib_uinsert'].'",
				"'.$this->data['pib_fupdate'].'",
				"'.$this->data['pib_uupdate'].'"
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
		$sql .= (empty($this->pivotSortColumn)) ? $this->primaryKey : $this->pivotSortColumn ;

		//echo '<pre>'.$sql.'</pre>';

		return $sql;
	}

	public function buildSelect()
	{

		$sql = 'SELECT
			 pib_id,
			 pib_anio,
			 pib_periodo,
			 pib_agricultura,
			 pib_nacional,
			 pib_finsert,
			 pib_uinsert,
			 pib_fupdate,
			 pib_uupdate
			FROM pib
		';
		$sql .= $this->buildSelectWhere();

		return $sql;
	}

	public function buildSelectWhere()
	{
		$filter = array();
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

		$sql = '';

		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
