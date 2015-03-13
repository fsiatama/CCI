<?php

require_once PATH_APP.'lib/connection/Connection.php';
require_once PATH_RAIZ.'../vendor/adodb/adodb-php/adodb-active-record.inc.php';


class ComtradeTempAdo extends Connection {

	private $tableName;
	private $arrFields;
	private $arrdata;
	private $conn;
	private $model;


	protected $pivotRowFields        = '';
	protected $pivotColumnFields     = '';
	protected $pivotTotalFields      = [];
	protected $pivotGroupingFunction = '';
	protected $pivotSortColumn       = '';

	public function __construct($tableName, array $arrFields, array $arrdata )
	{
		parent::__construct('min_agricultura');

		if ( empty($arrFields) ) {
			throw new Exception('arrayFields is Empty');
		}

		$this->tableName = ( !empty($tableName) ) ? $tableName : 'tempTable' ;
		$this->arrFields = $arrFields;
		$this->arrdata   = $arrdata;
		$this->conn      = $this->getConnection();
		
		//$this->conn->debug=1;
		ADOdb_Active_Record::SetDatabaseAdapter($this->conn);
		//$ADODB_ASSOC_CASE = 2;
		$this->createTable();
		$this->model = $this->getModel();
		$this->loadData();

	}

	private function createTable()
	{
		$sql    = 'DROP TEMPORARY TABLE IF EXISTS ' . $this->getTableName() . ';';
		$result = $this->conn->Execute($sql);
		$sql    = $this->getSqlCreateTable();
		$result = $this->conn->Execute($sql);
		if ( $result === false) {
			throw new Exception('Error creating the Temp Table!');
		}
	}

	private function getModel()
	{
		return new ADOdb_Active_Record($this->getTableName());
	}

	private function loadData()
	{
		$arrAttributes = $this->model->getAttributeNames();
		foreach ($this->arrdata as $row) {
			$this->model = $this->getModel();
			foreach ($row as $key => $value) {
				$field = strtolower($key);
				if ( in_array($field, $arrAttributes) ) {
					$this->model->$field = $value;
				}
			}
			$this->model->save();
			//echo "<p>The Insert ID generated:"; print_r($this->model->id);
		}
	}

	private function getSqlCreateTable()
	{
		$sql = '
		CREATE TEMPORARY TABLE ' . $this->getTableName() . ' (
			id int(10) unsigned NOT NULL auto_increment,
		';
		foreach ($this->getArrFields() as $field => $config) {
			if ( $field != 'id' ) {
				$sql .= '	' . $field . ' ' . $config . ', ';
			}
		}
		$sql .= '
			PRIMARY KEY (id)
		) ENGINE=MyISAM;
		';
		return $sql;
	}

	private function getTableName()
	{
		return $this->tableName;
	}

	private function getArrFields()
	{
		return $this->arrFields;
	}

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
		$table = 'declaraexp AS decl';
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
			'pais'     => 'decl.id_paisdestino = pais.id_pais',
		];
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id';
	}

	public function pivotSearch()
	{

		$sql       = $this->buildPivotSelect();
		$resultSet = $this->conn->Execute($sql);
		//var_dump($resultSet);
		$result = $this->buildResult($resultSet);

		return $result;
	}

	public function buildPivotSelect()
	{
		require_once PATH_APP.'lib/pivottable.inc.php';
		
		//$where = $this->buildSelectWhere();

		$sql = PivotTableSQL(
		 	$this->conn,  							# adodb connection
		 	$this->getTableName(),					# tables
			$this->pivotRowFields,					# row fields
			$this->pivotColumnFields,				# column fields
			'', 								# joins/where
			$this->pivotTotalFields, 				# SUM fields
			'',										# Function Label
			$this->pivotGroupingFunction,			# Function (SUM, COUNT, AGV)
			false
		);

		$sql .= ' ORDER BY ';
		$sql .= (empty($this->pivotSortColumn)) ? 'id' : $this->pivotSortColumn ;

		//echo '<pre>'.$sql.'</pre>';

		return $sql;
	}

	protected function buildResult(&$resultSet)
	{
		$result = array();
		
		if(!$resultSet){
			$result['success'] = false;
			$result['error']  = $conn->ErrorMsg();
		}
		else{
			$result['success'] = true;
			$result['total']   = $resultSet->RecordCount();
			$result['data']    = [];
			while(!$resultSet->EOF){
				$result['data'][] = $resultSet->fields;
				$resultSet->MoveNext();
			}
			$resultSet->Close();
		}

		return $result;
	}

	public function buildSelect()
	{
		$sql = 'SELECT
			 
			FROM 
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
					$filter[] = 'decl.' . $key . ' ' . $operator . ' "' . $data . '"';
				}
				elseif ($operator == 'IN') {
					if ($key == 'id_capitulo' || $key == 'id_partida' || $key == 'id_subpartida' || $key == 'id_posicion') {
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
