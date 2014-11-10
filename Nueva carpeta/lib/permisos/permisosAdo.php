<?php
include('permisos.php');
class PermisosAdo extends Conexion{
	var $conn;
	function PermisosAdo($_bd){
		parent::Conexion($_bd);
	}
	function lista($permisos){
		$conn = $this->conn;
		$filtro = array();
		foreach($permisos as $key => $data){
			if ($data <> ''){
				$filtro[] = $key . " = '" . $data ."'";
			}
		}
		$sql  = 'SELECT * FROM permisos';
		if(!empty($filtro)){
			$sql .= ' WHERE '. implode(' AND ', $filtro);
		}
		$rs   = $conn->Execute($sql);
		$result = array();
		if(!$rs){
			print $conn->ErrorMsg();
		}
		else{
			while(!$rs->EOF){
				$result[] = $rs->fields;
				$rs->MoveNext();
			}
			$rs->Close();
 		}
		return $result;
	}
	function insertar($permisos){
		$conn = $this->conn;
		$permisos_id = $permisos->getPermisos_id();
		$permisos_perfil_id = $permisos->getPermisos_perfil_id();
		$permisos_opc_menu_id = $permisos->getPermisos_opc_menu_id();
		$permisos_listar = $permisos->getPermisos_listar();
		$permisos_modificar = $permisos->getPermisos_modificar();
		$permisos_crear = $permisos->getPermisos_crear();
		$permisos_borrar = $permisos->getPermisos_borrar();
		$permisos_exportar = $permisos->getPermisos_exportar();
		$sql  = 'INSERT INTO permisos VALUE( "'.$permisos_id.'", "'.$permisos_perfil_id.'", "'.$permisos_opc_menu_id.'", "'.$permisos_listar.'", "'.$permisos_modificar.'", "'.$permisos_crear.'", "'.$permisos_borrar.'", "'.$permisos_exportar.'")';
		$rs   = $conn->Execute($sql);
		if(!$rs){
			print $conn->ErrorMsg();
		}
		else{
			$rs->Close();
 		}
	}
	function actualizar($permisos){
		$conn = $this->conn;
		$permisos_id = $permisos->getPermisos_id();
		$permisos_perfil_id = $permisos->getPermisos_perfil_id();
		$permisos_opc_menu_id = $permisos->getPermisos_opc_menu_id();
		$permisos_listar = $permisos->getPermisos_listar();
		$permisos_modificar = $permisos->getPermisos_modificar();
		$permisos_crear = $permisos->getPermisos_crear();
		$permisos_borrar = $permisos->getPermisos_borrar();
		$permisos_exportar = $permisos->getPermisos_exportar();
		$sql  = 'UPDATE permisos SET permisos_id = "'.$permisos_id.'", permisos_perfil_id = "'.$permisos_perfil_id.'", permisos_opc_menu_id = "'.$permisos_opc_menu_id.'", permisos_listar = "'.$permisos_listar.'", permisos_modificar = "'.$permisos_modificar.'", permisos_crear = "'.$permisos_crear.'", permisos_borrar = "'.$permisos_borrar.'", permisos_exportar = "'.$permisos_exportar.'" WHERE permisos_id = "$permisos_id"';
		$rs   = $conn->Execute($sql);
		if(!$rs){
			print $conn->ErrorMsg();
		}
		else{
			$rs->Close();
 		}
	}
	function borrar($permisos){
		$conn = $this->conn;
		$sql  = 'DELETE FROM permisos WHERE permisos_id = "'.$permisos_id.'"';
		$rs   = $conn->Execute($sql);
		if(!$rs){
			print $conn->ErrorMsg();
		}
		else{
			$rs->Close();
 		}
	}
	function valida_permiso($permisos, $url){
        $conn = $this->conn;
        $permisos_opc_menu_id = $permisos->getPermisos_opc_menu_id();
        $sql  = "SELECT * FROM permisos LEFT JOIN usuario ON usuario_perfil_id = permisos_perfil_id ";
		$sql .= "LEFT JOIN opc_menu ON permisos_opc_menu_id = opc_menu_id ";
		$sql .= "WHERE usuario_id = ".$_SESSION['session_usuario_id']." AND permisos_opc_menu_id = '".$permisos_opc_menu_id."' ";
		$sql .= "AND opc_menu_url ='".$url."'";
        //print $sql;
        $rs   = $conn->Execute($sql);
        if ($rs->RecordCount() > 0){
        	return true;
        }
        else
            return false;
    }
}
?>
