<?php

require_once ('BaseAdo.php');

class EmpresaAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'empresa';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id_empresa';
	}

	protected function setData()
	{
		$empresa = $this->getModel();

		$id_empresa = $empresa->getId_empresa();
		$digito_cheq = $empresa->getDigito_cheq();
		$empresa = $empresa->getEmpresa();
		$representante = $empresa->getRepresentante();
		$id_departamentos = $empresa->getId_departamentos();
		$departamentos = $empresa->getDepartamentos();
		$id_ciudad = $empresa->getId_ciudad();
		$ciudad = $empresa->getCiudad();
		$direccion = $empresa->getDireccion();
		$telefono = $empresa->getTelefono();
		$telefono2 = $empresa->getTelefono2();
		$telefono3 = $empresa->getTelefono3();
		$fax = $empresa->getFax();
		$fax2 = $empresa->getFax2();
		$fax3 = $empresa->getFax3();
		$email = $empresa->getEmail();
		$clase = $empresa->getClase();
		$uap = $empresa->getUap();
		$altex = $empresa->getAltex();
		$web = $empresa->getWeb();
		$contacto1 = $empresa->getContacto1();
		$id_tipo_empresa = $empresa->getId_tipo_empresa();

		$this->data = compact(
			'id_empresa',
			'digito_cheq',
			'empresa',
			'representante',
			'id_departamentos',
			'departamentos',
			'id_ciudad',
			'ciudad',
			'direccion',
			'telefono',
			'telefono2',
			'telefono3',
			'fax',
			'fax2',
			'fax3',
			'email',
			'clase',
			'uap',
			'altex',
			'web',
			'contacto1',
			'id_tipo_empresa'
		);
	}

	public function create($empresa)
	{
		$conn = $this->getConnection();
		$this->setModel($empresa);
		$this->setData();

		$sql = '
			INSERT INTO empresa (
				id_empresa,
				digito_cheq,
				empresa,
				representante,
				id_departamentos,
				departamentos,
				id_ciudad,
				ciudad,
				direccion,
				telefono,
				telefono2,
				telefono3,
				fax,
				fax2,
				fax3,
				email,
				clase,
				uap,
				altex,
				web,
				contacto1,
				id_tipo_empresa
			)
			VALUES (
				"'.$this->data['id_empresa'].'",
				"'.$this->data['digito_cheq'].'",
				"'.$this->data['empresa'].'",
				"'.$this->data['representante'].'",
				"'.$this->data['id_departamentos'].'",
				"'.$this->data['departamentos'].'",
				"'.$this->data['id_ciudad'].'",
				"'.$this->data['ciudad'].'",
				"'.$this->data['direccion'].'",
				"'.$this->data['telefono'].'",
				"'.$this->data['telefono2'].'",
				"'.$this->data['telefono3'].'",
				"'.$this->data['fax'].'",
				"'.$this->data['fax2'].'",
				"'.$this->data['fax3'].'",
				"'.$this->data['email'].'",
				"'.$this->data['clase'].'",
				"'.$this->data['uap'].'",
				"'.$this->data['altex'].'",
				"'.$this->data['web'].'",
				"'.$this->data['contacto1'].'",
				"'.$this->data['id_tipo_empresa'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function buildSelect()
	{
		$filter = array();
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
			 id_empresa,
			 digito_cheq,
			 empresa,
			 representante,
			 id_departamentos,
			 departamentos,
			 id_ciudad,
			 ciudad,
			 direccion,
			 telefono,
			 telefono2,
			 telefono3,
			 fax,
			 fax2,
			 fax3,
			 email,
			 clase,
			 uap,
			 altex,
			 web,
			 contacto1,
			 id_tipo_empresa
			FROM empresa
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
