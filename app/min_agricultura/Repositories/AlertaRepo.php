<?php

require PATH_MODELS.'Entities/Alerta.php';
require PATH_MODELS.'Ado/AlertaAdo.php';
require_once ('BaseRepo.php');

class AlertaRepo extends BaseRepo {

	public function getModel()
	{
		return new Alerta;
	}
	
	public function getModelAdo()
	{
		return new AlertaAdo;
	}

	public function getPrimaryKey()
	{
		return 'alerta_contingente_acuerdo_det_acuerdo_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($alerta_contingente_acuerdo_det_acuerdo_id);

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

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($alerta_contingente_acuerdo_det_acuerdo_id);

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
			empty($alerta_id) ||
			empty($alerta_contingente_verde) ||
			empty($alerta_contingente_amarilla) ||
			empty($alerta_contingente_roja) ||
			empty($alerta_salvaguardia_verde) ||
			empty($alerta_salvaguardia_amarilla) ||
			empty($alerta_salvaguardia_roja) ||
			empty($alerta_emails) ||
			empty($alerta_contingente_id) ||
			empty($alerta_contingente_acuerdo_det_id) ||
			empty($alerta_contingente_acuerdo_det_acuerdo_id) ||
			empty($alerta_disp1) ||
			empty($alerta_disp2) ||
			empty($alerta_disp3) ||
			empty($alerta_disp4) ||
			empty($alerta_disp5) ||
			empty($alerta_disp6)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setAlerta_id($alerta_id);
		$this->model->setAlerta_contingente_verde($alerta_contingente_verde);
		$this->model->setAlerta_contingente_amarilla($alerta_contingente_amarilla);
		$this->model->setAlerta_contingente_roja($alerta_contingente_roja);
		$this->model->setAlerta_salvaguardia_verde($alerta_salvaguardia_verde);
		$this->model->setAlerta_salvaguardia_amarilla($alerta_salvaguardia_amarilla);
		$this->model->setAlerta_salvaguardia_roja($alerta_salvaguardia_roja);
		$this->model->setAlerta_emails($alerta_emails);
		$this->model->setAlerta_contingente_id($alerta_contingente_id);
		$this->model->setAlerta_contingente_acuerdo_det_id($alerta_contingente_acuerdo_det_id);
		$this->model->setAlerta_contingente_acuerdo_det_acuerdo_id($alerta_contingente_acuerdo_det_acuerdo_id);
		$this->model->setAlerta_disp1($alerta_disp1);
		$this->model->setAlerta_disp2($alerta_disp2);
		$this->model->setAlerta_disp3($alerta_disp3);
		$this->model->setAlerta_disp4($alerta_disp4);
		$this->model->setAlerta_disp5($alerta_disp5);
		$this->model->setAlerta_disp6($alerta_disp6);

		if ($action == 'create') {
		} elseif ($action == 'modify') {
		}
		$result = ['success' => true];
		return $result;
	}

	public function listAll($params)
	{
		extract($params);
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : MAXREGEXCEL;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($valuesqry) && $valuesqry) {
			$query = explode('|',$query);
			$this->model->setAlerta_id(implode('", "', $query));
			$this->model->setAlerta_contingente_verde(implode('", "', $query));
			$this->model->setAlerta_contingente_amarilla(implode('", "', $query));
			$this->model->setAlerta_contingente_roja(implode('", "', $query));
			$this->model->setAlerta_salvaguardia_verde(implode('", "', $query));
			$this->model->setAlerta_salvaguardia_amarilla(implode('", "', $query));
			$this->model->setAlerta_salvaguardia_roja(implode('", "', $query));
			$this->model->setAlerta_emails(implode('", "', $query));
			$this->model->setAlerta_contingente_id(implode('", "', $query));
			$this->model->setAlerta_contingente_acuerdo_det_id(implode('", "', $query));
			$this->model->setAlerta_contingente_acuerdo_det_acuerdo_id(implode('", "', $query));
			$this->model->setAlerta_disp1(implode('", "', $query));
			$this->model->setAlerta_disp2(implode('", "', $query));
			$this->model->setAlerta_disp3(implode('", "', $query));
			$this->model->setAlerta_disp4(implode('", "', $query));
			$this->model->setAlerta_disp5(implode('", "', $query));
			$this->model->setAlerta_disp6(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setAlerta_id($query);
			$this->model->setAlerta_contingente_verde($query);
			$this->model->setAlerta_contingente_amarilla($query);
			$this->model->setAlerta_contingente_roja($query);
			$this->model->setAlerta_salvaguardia_verde($query);
			$this->model->setAlerta_salvaguardia_amarilla($query);
			$this->model->setAlerta_salvaguardia_roja($query);
			$this->model->setAlerta_emails($query);
			$this->model->setAlerta_contingente_id($query);
			$this->model->setAlerta_contingente_acuerdo_det_id($query);
			$this->model->setAlerta_contingente_acuerdo_det_acuerdo_id($query);
			$this->model->setAlerta_disp1($query);
			$this->model->setAlerta_disp2($query);
			$this->model->setAlerta_disp3($query);
			$this->model->setAlerta_disp4($query);
			$this->model->setAlerta_disp5($query);
			$this->model->setAlerta_disp6($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
