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
		
		$this->conn->debug=1;
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
			echo "<p>The Insert ID generated:"; print_r($this->model->id);
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

	protected function setData()
	{
		$declaraexp = $this->getModel();

		$id = $declaraexp->getId();
		$anio = $declaraexp->getAnio();
		$periodo = $declaraexp->getPeriodo();
		$id_empresa = $declaraexp->getId_empresa();
		$id_paisdestino = $declaraexp->getId_paisdestino();
		$id_capitulo = $declaraexp->getId_capitulo();
		$id_partida = $declaraexp->getId_partida();
		$id_subpartida = $declaraexp->getId_subpartida();
		$id_posicion = $declaraexp->getId_posicion();
		$id_ciiu = $declaraexp->getId_ciiu();
		$valorfob = $declaraexp->getValorfob();
		$valorcif = $declaraexp->getValorcif();
		$peso_neto = $declaraexp->getPeso_neto();

		$this->data = compact(
			'id',
			'anio',
			'periodo',
			'id_empresa',
			'id_paisdestino',
			'id_capitulo',
			'id_partida',
			'id_subpartida',
			'id_posicion',
			'id_ciiu',
			'valorfob',
			'valorcif',
			'peso_neto'
		);
	}

	public function create($declaraexp)
	{
		$conn = $this->getConnection();
		$this->setModel($declaraexp);
		$this->setData();

		$sql = '
			INSERT INTO declaraexp (
				id,
				anio,
				periodo,
				id_empresa,
				id_paisdestino,
				id_capitulo,
				id_partida,
				id_subpartida,
				id_posicion,
				id_ciiu,
				valorfob,
				valorcif,
				peso_neto
			)
			VALUES (
				"'.$this->data['id'].'",
				"'.$this->data['anio'].'",
				"'.$this->data['periodo'].'",
				"'.$this->data['id_empresa'].'",
				"'.$this->data['id_paisdestino'].'",
				"'.$this->data['id_capitulo'].'",
				"'.$this->data['id_partida'].'",
				"'.$this->data['id_subpartida'].'",
				"'.$this->data['id_posicion'].'",
				"'.$this->data['id_ciiu'].'",
				"'.$this->data['valorfob'].'",
				"'.$this->data['valorcif'].'",
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
			 decl.id,
			 decl.anio,
			 decl.periodo,
			 decl.id_empresa,
			 decl.id_paisdestino,
			 decl.id_capitulo,
			 decl.id_partida,
			 decl.id_subpartida,
			 decl.id_posicion,
			 decl.id_ciiu,
			 decl.valorfob,
			 decl.valorcif,
			 decl.peso_neto
			FROM declaraexp as decl
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
