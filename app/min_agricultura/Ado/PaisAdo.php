<?php

require_once ('BaseAdo.php');

class PaisAdo extends BaseAdo {

	protected $selectedValues = NULL;

	protected function setTable()
	{
		$this->table = 'pais';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id_pais';
	}

	protected function setData()
	{
		$pais = $this->getModel();

		$id_pais = $pais->getId_pais();
		$pais_iata = $pais->getPais_iata();
		$pais = $pais->getPais();

		$this->data = compact(
			'id_pais',
			'pais',
			'pais_iata'
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

	public function create($pais)
	{
		$conn = $this->getConnection();
		$this->setModel($pais);
		$this->setData();

		$sql = '
			INSERT INTO pais (
				id_pais,
				pais,
				pais_iata
			)
			VALUES (
				"'.$this->data['id_pais'].'",
				"'.$this->data['pais'].'",
				"'.$this->data['pais_iata'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function buildSelect()
	{

		$sql = 'SELECT
			 id_pais,
			 pais,
			 pais_iata
			FROM pais
		';

		$sql .= $this->buildSelectWhere();

		return $sql;
	}

	public function buildInAgreementSelect()
	{

		$sql = 'SELECT DISTINCT id_pais, pais, pais_iata FROM pais
			  	WHERE EXISTS (SELECT 1
								FROM acuerdo 
								LEFT JOIN mercado ON acuerdo_mercado_id = mercado_id
								WHERE FIND_IN_SET(pais.id_pais, mercado_paises) OR pais.id_pais = acuerdo_id_pais)
		';
		$this->setWhereAssignment( true );
		$sql .= $this->buildSelectWhere();

		return $sql;
	}

	public function buildSelectWhere()
	{
		$filter        = [];
		$primaryFilter = [];
		$operator      = $this->getOperator();
		$joinOperator  = ' AND ';

		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($key == 'id_pais') {
					$primaryFilter[] = $key . ' IN ("' . $data . '")';
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

		$sql             = '';

		if(!empty($primaryFilter)){
			$sql            .= ($this->getWhereAssignment()) ? ' AND ' : ' WHERE ' ;
			$sql            .= ' ('. implode( ' AND ', $primaryFilter ).')';
			$this->setWhereAssignment( true );
		}
		if(!empty($filter)){
			$sql .= ($this->getWhereAssignment()) ? ' AND ' : ' WHERE ' ;
			$sql .= '  ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

	public function listInAgreement($model)
	{
		$this->setModel($model);
		$this->setOperator('LIKE');
		$conn = $this->getConnection();
		$this->setData();

		$sql       = $this->buildInAgreementSelect();

		$resultSet = $conn->Execute($sql);
		$result    = $this->buildResult($resultSet);

		return $result;
	}

}
