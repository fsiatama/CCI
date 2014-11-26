<?php

require PATH_APP.'min_agricultura/Entities/Empresa.php';
require PATH_APP.'min_agricultura/Ado/EmpresaAdo.php';
require_once ('BaseRepo.php');

class EmpresaRepo extends BaseRepo {

	public function getModel()
	{
		return new Empresa;
	}
	
	public function getModelAdo()
	{
		return new EmpresaAdo;
	}

	public function getPrimaryKey()
	{
		return 'id_empresa';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($id_empresa);

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
			$result = $this->findPrimaryKey($id_empresa);

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
			empty($id_empresa) ||
			empty($digito_cheq) ||
			empty($empresa) ||
			empty($representante) ||
			empty($id_departamentos) ||
			empty($departamentos) ||
			empty($id_ciudad) ||
			empty($ciudad) ||
			empty($direccion) ||
			empty($telefono) ||
			empty($telefono2) ||
			empty($telefono3) ||
			empty($fax) ||
			empty($fax2) ||
			empty($fax3) ||
			empty($email) ||
			empty($clase) ||
			empty($uap) ||
			empty($altex) ||
			empty($web) ||
			empty($contacto1) ||
			empty($id_tipo_empresa)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
			$this->model->setId_empresa($id_empresa);
			$this->model->setDigito_cheq($digito_cheq);
			$this->model->setEmpresa($empresa);
			$this->model->setRepresentante($representante);
			$this->model->setId_departamentos($id_departamentos);
			$this->model->setDepartamentos($departamentos);
			$this->model->setId_ciudad($id_ciudad);
			$this->model->setCiudad($ciudad);
			$this->model->setDireccion($direccion);
			$this->model->setTelefono($telefono);
			$this->model->setTelefono2($telefono2);
			$this->model->setTelefono3($telefono3);
			$this->model->setFax($fax);
			$this->model->setFax2($fax2);
			$this->model->setFax3($fax3);
			$this->model->setEmail($email);
			$this->model->setClase($clase);
			$this->model->setUap($uap);
			$this->model->setAltex($altex);
			$this->model->setWeb($web);
			$this->model->setContacto1($contacto1);
			$this->model->setId_tipo_empresa($id_tipo_empresa);
		

		if ($action == 'create') {
		}
		elseif ($action == 'modify') {
		}
		$result = array('success' => true);
		return $result;
	}

}	

