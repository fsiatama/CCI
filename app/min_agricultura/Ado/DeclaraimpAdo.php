<?php

/*include_once(PATH_APP.'adodb5/toexport.inc.php');
include_once(PATH_APP.'adodb5/pivottable.inc.php'); */

require_once ('BaseAdo.php');

class DeclaraimpAdo extends BaseAdo {

	protected $pivotRowFields        = '';
	protected $pivotColumnFields     = '';
	protected $pivotTotalFields      = '';
	protected $pivotGroupingFunction = '';

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
		$this->pivotTotalFields = $pivotTotalFields;
	}

	public function setPivotGroupingFunction($pivotGroupingFunction)
	{
		$this->pivotGroupingFunction = $pivotGroupingFunction;
	}

	protected function setTable()
	{
		$this->table = 'declaraimp';
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
		$id_empresa = $declaraimp->getId_empresa();
		$id_paisorigen = $declaraimp->getId_paisorigen();
		$id_paiscompra = $declaraimp->getId_paiscompra();
		$id_paisprocedencia = $declaraimp->getId_paisprocedencia();
		$id_capitulo = $declaraimp->getId_capitulo();
		$id_partida = $declaraimp->getId_partida();
		$id_subpartida = $declaraimp->getId_subpartida();
		$id_posicion = $declaraimp->getId_posicion();
		$id_ciiu = $declaraimp->getId_ciiu();
		$valorcif = $declaraimp->getValorcif();
		$valorfob = $declaraimp->getValorfob();
		$peso_neto = $declaraimp->getPeso_neto();

		$this->data = compact(
			'id',
			'anio',
			'periodo',
			'id_empresa',
			'id_paisorigen',
			'id_paiscompra',
			'id_paisprocedencia',
			'id_capitulo',
			'id_partida',
			'id_subpartida',
			'id_posicion',
			'id_ciiu',
			'valorcif',
			'valorfob',
			'peso_neto'
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
				id_empresa,
				id_paisorigen,
				id_paiscompra,
				id_paisprocedencia,
				id_capitulo,
				id_partida,
				id_subpartida,
				id_posicion,
				id_ciiu,
				valorcif,
				valorfob,
				peso_neto
			)
			VALUES (
				"'.$this->data['id'].'",
				"'.$this->data['anio'].'",
				"'.$this->data['periodo'].'",
				"'.$this->data['id_empresa'].'",
				"'.$this->data['id_paisorigen'].'",
				"'.$this->data['id_paiscompra'].'",
				"'.$this->data['id_paisprocedencia'].'",
				"'.$this->data['id_capitulo'].'",
				"'.$this->data['id_partida'].'",
				"'.$this->data['id_subpartida'].'",
				"'.$this->data['id_posicion'].'",
				"'.$this->data['id_ciiu'].'",
				"'.$this->data['valorcif'].'",
				"'.$this->data['valorfob'].'",
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
		require_once PATH_APP.'adodb5/pivottable.inc.php';
		
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

		//var_dump($sql);

		return $sql;
	}

	public function buildSelect()
	{
		$sql = 'SELECT
			 id,
			 anio,
			 periodo,
			 id_empresa,
			 id_paisorigen,
			 id_paiscompra,
			 id_paisprocedencia,
			 id_capitulo,
			 id_partida,
			 id_subpartida,
			 id_posicion,
			 id_ciiu,
			 valorcif,
			 valorfob,
			 peso_neto
			FROM declaraimp
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
					$filter[] = $key . ' ' . $operator . ' "' . $data . '"';
				}
				elseif ($operator == 'IN') {
					if ($key == 'id_capitulo' || $key == 'id_partida' || $key == 'id_subpartida' || $key == 'id_posicion') {
						//debe colocarle comillas a cada valor dentro del IN
						$arr              = explode(',', $data);
						$filterPosicion[] = $key . ' ' . $operator . '("' . implode('","', $arr) . '")';
					} else {
						$arr      = explode(',', $data);
						$filter[] = $key . ' ' . $operator . '("' . implode('","', $arr) . '")';
					}
				}
				else {
					$filter[] = $key . ' ' . $operator . ' "%' . $data . '%"';
					$joinOperator = ' OR ';
				}
			}
		}

		$sql             = '';
		$whereAssignment = false;

		if(!empty($filter)){
			$sql            .= ' WHERE ('. implode( $joinOperator, $filter ).')';
			$whereAssignment = true;
		}
		if(!empty($filterPosicion)){
			$sql .= ($whereAssignment) ? ' '.$joinOperator : ' WHERE ' ;
			$sql .= ' ('. implode( ' OR ', $filterPosicion ).')';
		}
		return $sql;
	}

}
