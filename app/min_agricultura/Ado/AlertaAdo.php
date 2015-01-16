<?php

require_once ('BaseAdo.php');

class AlertaAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'alerta';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'alerta_id';
	}

	protected function setData()
	{
		$alerta = $this->getModel();

		$alerta_id = $alerta->getAlerta_id();
		$alerta_contingente_verde = $alerta->getAlerta_contingente_verde();
		$alerta_contingente_amarilla = $alerta->getAlerta_contingente_amarilla();
		$alerta_contingente_roja = $alerta->getAlerta_contingente_roja();
		$alerta_salvaguardia_verde = $alerta->getAlerta_salvaguardia_verde();
		$alerta_salvaguardia_amarilla = $alerta->getAlerta_salvaguardia_amarilla();
		$alerta_salvaguardia_roja = $alerta->getAlerta_salvaguardia_roja();
		$alerta_emails = $alerta->getAlerta_emails();
		$alerta_contingente_id = $alerta->getAlerta_contingente_id();
		$alerta_contingente_acuerdo_det_id = $alerta->getAlerta_contingente_acuerdo_det_id();
		$alerta_contingente_acuerdo_det_acuerdo_id = $alerta->getAlerta_contingente_acuerdo_det_acuerdo_id();
		$alerta_disp1 = $alerta->getAlerta_disp1();
		$alerta_disp2 = $alerta->getAlerta_disp2();
		$alerta_disp3 = $alerta->getAlerta_disp3();
		$alerta_disp4 = $alerta->getAlerta_disp4();
		$alerta_disp5 = $alerta->getAlerta_disp5();
		$alerta_disp6 = $alerta->getAlerta_disp6();

		$this->data = compact(
			'alerta_id',
			'alerta_contingente_verde',
			'alerta_contingente_amarilla',
			'alerta_contingente_roja',
			'alerta_salvaguardia_verde',
			'alerta_salvaguardia_amarilla',
			'alerta_salvaguardia_roja',
			'alerta_emails',
			'alerta_contingente_id',
			'alerta_contingente_acuerdo_det_id',
			'alerta_contingente_acuerdo_det_acuerdo_id',
			'alerta_disp1',
			'alerta_disp2',
			'alerta_disp3',
			'alerta_disp4',
			'alerta_disp5',
			'alerta_disp6'
		);
	}

	public function create($alerta)
	{
		$conn = $this->getConnection();
		$this->setModel($alerta);
		$this->setData();

		$sql = '
			INSERT INTO alerta (
				alerta_id,
				alerta_contingente_verde,
				alerta_contingente_amarilla,
				alerta_contingente_roja,
				alerta_salvaguardia_verde,
				alerta_salvaguardia_amarilla,
				alerta_salvaguardia_roja,
				alerta_emails,
				alerta_contingente_id,
				alerta_contingente_acuerdo_det_id,
				alerta_contingente_acuerdo_det_acuerdo_id,
				alerta_disp1,
				alerta_disp2,
				alerta_disp3,
				alerta_disp4,
				alerta_disp5,
				alerta_disp6
			)
			VALUES (
				"'.$this->data['alerta_id'].'",
				"'.$this->data['alerta_contingente_verde'].'",
				"'.$this->data['alerta_contingente_amarilla'].'",
				"'.$this->data['alerta_contingente_roja'].'",
				"'.$this->data['alerta_salvaguardia_verde'].'",
				"'.$this->data['alerta_salvaguardia_amarilla'].'",
				"'.$this->data['alerta_salvaguardia_roja'].'",
				"'.$this->data['alerta_emails'].'",
				"'.$this->data['alerta_contingente_id'].'",
				"'.$this->data['alerta_contingente_acuerdo_det_id'].'",
				"'.$this->data['alerta_contingente_acuerdo_det_acuerdo_id'].'",
				"'.$this->data['alerta_disp1'].'",
				"'.$this->data['alerta_disp2'].'",
				"'.$this->data['alerta_disp3'].'",
				"'.$this->data['alerta_disp4'].'",
				"'.$this->data['alerta_disp5'].'",
				"'.$this->data['alerta_disp6'].'"
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
			 alerta_id,
			 alerta_contingente_verde,
			 alerta_contingente_amarilla,
			 alerta_contingente_roja,
			 alerta_salvaguardia_verde,
			 alerta_salvaguardia_amarilla,
			 alerta_salvaguardia_roja,
			 alerta_emails,
			 alerta_contingente_id,
			 alerta_contingente_acuerdo_det_id,
			 alerta_contingente_acuerdo_det_acuerdo_id,
			 alerta_disp1,
			 alerta_disp2,
			 alerta_disp3,
			 alerta_disp4,
			 alerta_disp5,
			 alerta_disp6
			FROM alerta
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		//echo "<pre>$sql</pre>";

		return $sql;
	}

}
