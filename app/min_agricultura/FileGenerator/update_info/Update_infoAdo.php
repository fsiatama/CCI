<?php

require_once ('BaseAdo.php');

class Update_infoAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'update_info';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'update_info_id';
	}

	protected function setData()
	{
		$update_info = $this->getModel();

		$update_info_id = $update_info->getUpdate_info_id();
		$update_info_product = $update_info->getUpdate_info_product();
		$update_info_trade = $update_info->getUpdate_info_trade();
		$update_info_from = $update_info->getUpdate_info_from();
		$update_info_to = $update_info->getUpdate_info_to();

		$this->data = compact(
			'update_info_id',
			'update_info_product',
			'update_info_trade',
			'update_info_from',
			'update_info_to'
		);
	}

	public function create($update_info)
	{
		$conn = $this->getConnection();
		$this->setModel($update_info);
		$this->setData();

		$sql = '
			INSERT INTO update_info (
				update_info_id,
				update_info_product,
				update_info_trade,
				update_info_from,
				update_info_to
			)
			VALUES (
				"'.$this->data['update_info_id'].'",
				"'.$this->data['update_info_product'].'",
				"'.$this->data['update_info_trade'].'",
				"'.$this->data['update_info_from'].'",
				"'.$this->data['update_info_to'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function buildSelect()
	{
		$filter        = [];
		$primaryFilter = [];
		$operator      = $this->getOperator();
		$joinOperator  = ' AND ';

		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($key == 'update_info_id') {
					$primaryFilter[] = $key . ' = "' . $data . '"';
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

		$sql = 'SELECT
			 update_info_id,
			 update_info_product,
			 update_info_trade,
			 update_info_from,
			 update_info_to
			FROM update_info
		';

		$whereAssignment = false;

		if(!empty($primaryFilter)){
			$sql            .= ' WHERE ('. implode( ' AND ', $primaryFilter ).')';
			$whereAssignment = true;
		}
		if(!empty($filter)){
			$sql .= ($whereAssignment) ? ' AND ' : ' WHERE ' ;
			$sql .= '  ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
