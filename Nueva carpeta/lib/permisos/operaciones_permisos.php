<?php
session_start();
include_once ('../../../lib/config.php');
include_once (PATH_RAIZ.'lib/lib_sesion.php');
include_once (PATH_RAIZ.'lib/excel.php');
include_once (PATH_RAIZ.'lib/conexion/conexion.php');
include(PATH_RAIZ.'ssgroup/lib/permisos/permisosAdo.php');
$permisosAdo = new PermisosAdo('ssgroup');
$permisos    = new Permisos;
if(isset($accion)){
	switch ($accion){
		case 'act':
			$permisos->setPermisos_id($_POST['id']);
			$permisosAdo->actualizar($permisos);
		break;
		case 'del':
			$permisos->setPermisos_id($_POST['id']);
			$permisosAdo->borrar($permisos);
		break;
		case 'crea':
			$permisos->setPermisos_id($permisos_id);
			$permisos->setPermisos_perfil_id($permisos_perfil_id);
			$permisos->setPermisos_opc_menu_id($permisos_opc_menu_id);
			$permisos->setPermisos_listar($permisos_listar);
			$permisos->setPermisos_modificar($permisos_modificar);
			$permisos->setPermisos_crear($permisos_crear);
			$permisos->setPermisos_borrar($permisos_borrar);
			$permisos->setPermisos_exportar($permisos_exportar);
			$permisosAdo->insertar($permisos);
		break;
		case 'lista':
			$arr = array();
			$permisos->setPermisos_id($permisos_id);
			$permisos->setPermisos_perfil_id($permisos_perfil_id);
			$permisos->setPermisos_opc_menu_id($permisos_opc_menu_id);
			$permisos->setPermisos_listar($permisos_listar);
			$permisos->setPermisos_modificar($permisos_modificar);
			$permisos->setPermisos_crear($permisos_crear);
			$permisos->setPermisos_borrar($permisos_borrar);
			$permisos->setPermisos_exportar($permisos_exportar);
			$result = $permisosAdo->lista($permisos);
			foreach($result as $key => $data){
				$arr[] = filtro_grid($data);
			}
			if(isset($formato)){
				$head   = json_decode(stripslashes($campos));
				$total = 'Total Reg: '. $total;
				$result = 'file_'.time();
				$archivo = CreaExcel($arr, $formato, $head, $total, $result);
				echo '{success: true, msg:'.json_encode($archivo).'}';
				exit();
			}
			$data = json_encode($arr); 
			print('{"total":"'.count($result).'", "datos":'.$data.'}');
		break;
	}
}
function filtro_grid($contenido){
  $contenido = str_replace('¡','', $contenido);
  $contenido = str_replace('á','a', $contenido);
  $contenido = str_replace('é','e', $contenido);
  $contenido = str_replace('í','i', $contenido);
  $contenido = str_replace('ó','o', $contenido);
  $contenido = str_replace('ú','u', $contenido);
  $contenido = str_replace('ñ','n', $contenido);
  $contenido = str_replace('Á','A', $contenido);
  $contenido = str_replace('É','E', $contenido);
  $contenido = str_replace('Í','I', $contenido);
  $contenido = str_replace('Ó','O', $contenido);
  $contenido = str_replace('Ú','U', $contenido);
  $contenido = str_replace('Ñ','N', $contenido);
  return $contenido;
}
?>
