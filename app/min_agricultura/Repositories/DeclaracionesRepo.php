<?php

//require PATH_APP.'min_agricultura/Ado/DeclaracionesAdo.php';

require_once ('BaseRepo.php');

class DeclaracionesRepo extends BaseRepo {

	public function getModel()
	{

	}
	
	public function getModelAdo()
	{

	}

	public function getPrimaryKey()
	{

	}

	public function setData($params, $action)
	{

	}

	public function setFiltersValues($arrFiltersValues, $filtersConfig, $trade)
	{
		foreach ($filtersConfig as $filter) {

			if (array_key_exists($filter['field'], $arrFiltersValues)) {
				$fieldName = ($trade == 'impo') ? $filter['field_impo'] : $filter['field_expo'] ;

				$filterValue = $arrFiltersValues[$filter['field']];

				$methodName = $this->getColumnMethodName('set', $fieldName);

				if (method_exists($this->model, $methodName)) {
					call_user_func_array([$this->model, $methodName], compact('filterValue'));
				}
			}
		}
	}

	public function executeBalanza($rowIndicador, $filtersConfig)
	{
		extract($rowIndicador);

		require PATH_MODELS.'Entities/Declaraimp.php';
		require PATH_MODELS.'Ado/DeclaraimpAdo.php';

		$this->model = new Declaraimp;
		$this->modelAdo = new DeclaraimpAdo;

		$arrFiltersValues = Helpers::filterValuesToArray($indicador_filtros);

		$this->setFiltersValues($arrFiltersValues, $filtersConfig, 'impo');

		$result = $this->modelAdo->inSearch($this->model);

		var_dump($result);

	}
}	

