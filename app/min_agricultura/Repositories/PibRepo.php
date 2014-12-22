<?php

require PATH_APP.'min_agricultura/Entities/Pib.php';
require PATH_APP.'min_agricultura/Ado/PibAdo.php';
require_once ('BaseRepo.php');

class PibRepo extends BaseRepo {

	public function getModel()
	{
		return new Pib;
	}
	
	public function getModelAdo()
	{
		return new PibAdo;
	}

	public function getPrimaryKey()
	{
		return 'pib_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($pib_id);

		if (!$result['success']) {
			$result = [
				'success'  => false,
				'closeTab' => true,
				'tab'      => 'tab-'.$module,
				'error'    => $result['error']
			];
		}
		return $result;
	}

	public function grid($params)
	{
		extract($params);

		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($query)) {
			if (!empty($fullTextFields)) {
				
				$fullTextFields = json_decode($fullTextFields);
				
				foreach ($fullTextFields as $value) {
					$methodName = $this->getColumnMethodName('set', $value);
					
					if (method_exists($this->model, $methodName)) {
						call_user_func_array([$this->model, $methodName], compact('query'));
					}
				}
			} else {
				$this->model->setPib_id($query);
				$this->model->setPib_anio($query);
				$this->model->setPib_periodo($query);
				$this->model->setPib_agricultura($query);
				$this->model->setPib_nacional($query);
			}
			
		}
		$this->modelAdo->setColumns([
			'pib_id',
			'pib_anio',
			'pib_periodo',
			'pib_periodo_title',
			'pib_agricultura',
			'pib_nacional',
		]);

		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		return $result;
	}

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($pib_id);

			if (!$result['success']) {
				$result = [
					'success'  => false,
					'closeTab' => true,
					'tab'      => 'tab-'.$module,
					'error'    => $result['error']
				];
				return $result;
			}
		}

		if (
			empty($pib_anio) ||
			empty($pib_periodo) ||
			empty($pib_agricultura) ||
			empty($pib_nacional)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}

		$this->model->setPib_id($pib_id);
		$this->model->setPib_anio($pib_anio);
		$this->model->setPib_periodo($pib_periodo);
		$this->model->setPib_agricultura($pib_agricultura);
		$this->model->setPib_nacional($pib_nacional);

		if ($action == 'create') {
			$this->model->setPib_finsert(Helpers::getDateTimeNow());
			$this->model->setPib_uinsert($_SESSION['user_id']);
		}
		elseif ($action == 'modify') {
			$this->model->setPib_fupdate(Helpers::getDateTimeNow());
			$this->model->setPib_uupdate($_SESSION['user_id']);
		}
		$result = ['success' => true];
		return $result;
	}

    /**
     * listPeriod
     * 
     * @param array $params Contiene: anio de declaraciones, periodo(mes) de declaraciones, periodo(period) seleccionado por el usuario para el reporte.
     *
     * @access public
     *
     * @return array array con los valores del pib nacional y agricola trimenstral o acumulados por semestre o anual.
     */
	public function listPeriod($params)
	{
		extract($params);
		if (empty($anio) || empty($period)) {
			return [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
		}

		$this->model->setPib_anio($anio);
		$rowField    = $this->getPibPeriodColumnSql($period);
		$row         = 'pib_anio AS id';
		$arrRowField = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields(['pib_agricultura', 'pib_nacional']);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		return $this->modelAdo->pivotSearch($this->model);
	}

	public function getPibPeriodColumnSql($period, $withPeriodName = true)
	{
		$fieldPeriodName = 'pib_periodo' ;
		$column          = 'pib_anio AS ' . $fieldPeriodName;
		$periodName      = '""';
		switch ($period) {
			case 6:
				if ($withPeriodName) {
					$periodName = 'pib_anio, " '.Lang::get('indicador.reports.semester').' "';
				}
				$column = '
					(CASE
					   WHEN 0 < ' . $fieldPeriodName . ' AND ' . $fieldPeriodName . ' <= 2 THEN CONCAT('.$periodName.', "1")
					   WHEN 2 < ' . $fieldPeriodName . ' THEN CONCAT('.$periodName.', "2")
					 END
					) AS ' . $fieldPeriodName . '
				';
			break;
			case 3:
				if ($withPeriodName) {
					$periodName = 'pib_anio, " '.Lang::get('indicador.reports.quarter').' "';
				}
				$column = '
					(CASE
					   WHEN 1  = ' . $fieldPeriodName . ' THEN CONCAT('.$periodName.', "1")
					   WHEN 2  = ' . $fieldPeriodName . ' THEN CONCAT('.$periodName.', "2")
					   WHEN 3  = ' . $fieldPeriodName . ' THEN CONCAT('.$periodName.', "3")
					   WHEN 4  = ' . $fieldPeriodName . ' THEN CONCAT('.$periodName.', "4")
					 END
					) AS ' . $fieldPeriodName . '
				';
			break;
		}
		return $column;
	}

}	

