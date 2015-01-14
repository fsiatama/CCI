<?php

require_once ('BaseAdo.php');

class AuditAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'audit';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'audit_id';
	}

	protected function setData()
	{
		$audit = $this->getModel();

		$audit_id = $audit->getAudit_id();
		$audit_table = $audit->getAudit_table();
		$audit_script = $audit->getAudit_script();
		$audit_method = $audit->getAudit_method();
		$audit_parameters = $audit->getAudit_parameters();
		$audit_uinsert = $audit->getAudit_uinsert();
		$audit_finsert = $audit->getAudit_finsert();

		$this->data = compact(
			'audit_id',
			'audit_table',
			'audit_script',
			'audit_method',
			'audit_parameters',
			'audit_uinsert',
			'audit_finsert'
		);
	}

	public function create($audit)
	{
		$conn = $this->getConnection();
		$this->setModel($audit);
		$this->setData();

		$sql = '
			INSERT INTO audit (
				audit_id,
				audit_table,
				audit_script,
				audit_method,
				audit_parameters,
				audit_uinsert,
				audit_finsert
			)
			VALUES (
				"'.$this->data['audit_id'].'",
				"'.$this->data['audit_table'].'",
				"'.$this->data['audit_script'].'",
				"'.$this->data['audit_method'].'",
				"'.$this->data['audit_parameters'].'",
				"'.$this->data['audit_uinsert'].'",
				"'.$this->data['audit_finsert'].'"
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
			 audit_id,
			 audit_table,
			 audit_script,
			 audit_method,
			 audit_parameters,
			 audit_uinsert,
			 audit_finsert
			FROM audit
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
