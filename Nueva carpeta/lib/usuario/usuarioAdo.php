<?php
include('usuario.php');
class UsuarioAdo extends Conexion{
	var $conn;
	function UsuarioAdo($_bd){
		parent::Conexion($_bd);
	}
	function lista_grilla($start, $count, $sort, $dir, $query, $filtro){
		$conn = $this->conn;
		$arr_filtro = array();
		$result = array();
		$query = strtolower($query);
		if($filtro && $query != ""){
			foreach($filtro as $key => $data){
				if($data == "activo"){
					if($query == "active"){
						$arr_filtro[] = "usr.usuario_activo = '1'";
					}
					elseif($query == "inactive"){
						$arr_filtro[] = "usr.usuario_activo = '0'";
					}
				}
				elseif($data == 'reclutador_nombre'){
					$arr_filtro[] = "CONCAT(reclutador.usuario_pnombre,' ', reclutador.usuario_papellido) LIKE '%" . $query ."%'";
				}
				elseif($data == 'perfil_nombre' ||
					   $data == 'Country'
				){
					$arr_filtro[] = $data." LIKE '%" . $query ."%'";
				}
				else{
					$arr_filtro[] = "usr.".$data . " LIKE '%" . $query ."%'";
				}
			}
		}
		
		$sql  = 'SELECT COUNT(usr.usuario_id) AS total  FROM usuario AS usr ';
		$sql .= 'LEFT JOIN perfil ON usr.usuario_perfil_id = perfil_id ';
		$sql .= 'LEFT JOIN countries ON usr.usuario_CountryId = CountryId ';
		$sql .= 'LEFT JOIN usuario AS reclutador ON usr.usuario_reclutador_id = reclutador.usuario_id ';
		if(!empty($arr_filtro)){
			$sql .= ' WHERE '. implode(' OR ', $arr_filtro);
		}
		//print $sql;
		$rs   = $conn->Execute($sql);
		if (!$rs){
        	print $conn->ErrorMsg();
		}
        else{
			while (!$rs->EOF) {
				$result["total"] = $rs->fields["total"];
				$rs->MoveNext();
			}
		}
        $rs->Close();
		
		$sql  = 'SELECT usr.usuario_id, usr.usuario_pnombre, usr.usuario_snombre, usr.usuario_papellido, usr.usuario_activo, ';
		$sql .= 'usr.usuario_sapellido, usr.usuario_email, usr.usuario_root, ';
		$sql .= 'IF(usr.usuario_activo="1","ACTIVE","INACTIVE") AS activo, ';
		$sql .= 'usr.usuario_SkypeId, usr.usuario_documento_ident, ';
		$sql .= 'CONCAT(reclutador.usuario_pnombre," ", reclutador.usuario_papellido) AS reclutador_nombre,';
		$sql .= 'usr.usuario_finsert, usr.usuario_CityId, usr.usuario_CountryId, Country, perfil_nombre ';
		$sql .= 'FROM usuario AS usr ';
		$sql .= 'LEFT JOIN perfil ON usr.usuario_perfil_id = perfil_id ';
		$sql .= 'LEFT JOIN usuario AS reclutador ON usr.usuario_reclutador_id = reclutador.usuario_id ';
		$sql .= 'LEFT JOIN countries ON CountryId = usr.usuario_CountryId ';
		if(!empty($arr_filtro)){
			$sql .= ' WHERE '. implode(' OR ', $arr_filtro);
		}
		if ($sort != "") {
			$sql .= " ORDER BY ".$sort." ".$dir;
		}
		$sql .= " LIMIT ".$start.",".$count;
		//print $sql;
		$rs   = $conn->Execute($sql);
		if(!$rs){
			print $conn->ErrorMsg();
		}
		else{
			while(!$rs->EOF){
				$result["data"][] = $rs->fields;
				$rs->MoveNext();
			}
			$rs->Close();
 		}
		return $result;		
	}
 	function lista($usuario){
		$conn = $this->conn;
		$filtro = array();
		foreach($usuario as $key => $data){
			if ($data <> ''){
				if($key == "usuario_perfil_id"){
					$filtro[] = $key . " IN (" . $data .")";
				}
				else{
					$filtro[] = $key . " = '" . $data ."'";
				}
			}
		}
		$sql  = 'SELECT * FROM usuario';
		if(!empty($filtro)){
			$sql .= ' WHERE '. implode(' AND ', $filtro);
		}
		//print $sql;
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
	function listaCombo($usuario){
		$conn = $this->conn;
		$filtro = array();
		foreach($usuario as $key => $data){
			if ($data <> ''){
				if($key == "usuario_perfil_id"){
					$filtro[] = $key . " IN (" . $data .")";
				}
				else{
					$filtro[] = $key . " = '" . $data ."'";
				}
			}
		}
		$sql  = 'SELECT usuario_id, CONCAT(usuario_pnombre," ",usuario_papellido) AS nombre FROM usuario ';
		$sql .= 'WHERE NOT usuario_id IN (1,30,36)';
		if(!empty($filtro)){
			$sql .= ' AND '. implode(' AND ', $filtro);
		}
		//print $sql;
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
	function lista_reclutadores_countries($start, $count, $sort, $dir, $query, $filtro){
		$conn = $this->conn;
		$arr_filtro = array();
		$result = array();
		$query = strtolower($query);
		if($filtro && $query != ""){
			foreach($filtro as $key => $data){
				$arr_filtro[] = $data . " LIKE '%" . $query ."%'";
			}
		}		
		$sql  = 'SELECT COUNT(usuario_id) total
				 FROM usuario LEFT JOIN reclutador_countries ON usuario_id = reclutador_countries_usuario_id 
				 WHERE usuario_perfil_id IN ('.PERFIL_RECLUTADOR.')
				 AND NOT usuario_id IN (1,30,36)';
		if(!empty($arr_filtro)){
			$sql .= ' AND ('. implode(' OR ', $arr_filtro).")";
		}
		//print $sql;
		$rs   = $conn->Execute($sql);
		if (!$rs){
        	print $conn->ErrorMsg();
		}
        else{
			while (!$rs->EOF) {
				$result["total"] = $rs->fields["total"];
				$rs->MoveNext();
			}
		}
		//print "=======".$result["total"];
        $rs->Close();
		
		$sql  = 'SELECT usuario_id, usuario_pnombre, usuario_snombre, usuario_papellido, 
				 reclutador_countries_usuario_id, reclutador_countries_CountryId 
				 FROM usuario LEFT JOIN reclutador_countries ON usuario_id = reclutador_countries_usuario_id 
				 WHERE usuario_perfil_id IN ('.PERFIL_RECLUTADOR.')';
		if(!empty($arr_filtro)){
			$sql .= ' AND ('. implode(' OR ', $arr_filtro).")";
		}
		if ($sort != "") {
			$sql .= " ORDER BY ".$sort." ".$dir;
		}
		$sql .= " LIMIT ".$start.",".$count;
		//print $sql;
		$rs   = $conn->Execute($sql);
		if(!$rs){
			print $conn->ErrorMsg();
		}
		else{
			while(!$rs->EOF){
				$result["data"][] = $rs->fields;
				$rs->MoveNext();
			}
			$rs->Close();
 		}
		return $result;		
	}
	/*function lista_reclutadores_countries($usuario){
		$conn = $this->conn;
		$filtro = array();
		foreach($usuario as $key => $data){
			if ($data <> ''){
				if($key == "usuario_perfil_id"){
					$filtro[] = $key . " IN (" . $data .")";
				}
				else{
					$filtro[] = $key . " = '" . $data ."'";
				}
			}
		}
		$sql  = 'SELECT usuario_id, usuario_pnombre, usuario_snombre, usuario_papellido, 
				 reclutador_countries_usuario_id, reclutador_countries_CountryId 
				 FROM usuario LEFT JOIN reclutador_countries ON usuario_id = reclutador_countries_usuario_id ';
		if(!empty($filtro)){
			$sql .= ' WHERE '. implode(' AND ', $filtro);
		}
		//print $sql;
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
	}*/
	function listaId($usuario){
		$conn = $this->conn;
		$usuario_id = $usuario->getUsuario_id();
		
		$sql  = 'SELECT usr.*,tipos_identificacion.tipos_identificacion_nombre AS tipo_identificacion,
				country1.Country AS pais,country2.Country AS pais2,
				IF(usr.usuario_genero = "0","Male", "Female") AS genero,
				CONCAT(reclutador.usuario_pnombre," ",reclutador.usuario_papellido) AS reclutador_nombre,
				reclutador.usuario_email as reclutador_email
				FROM usuario  AS usr
				LEFT JOIN tipos_identificacion ON usr.usuario_tipos_identificacion_id = tipos_identificacion_id
				LEFT JOIN countries AS country1 ON usr.usuario_CountryId = country1.CountryId
				LEFT JOIN countries AS country2 ON usr.usuario_CountryId2 = country2.CountryId
				LEFT JOIN usuario AS reclutador ON usr.usuario_reclutador_id = reclutador.usuario_id				
				 ';
		$sql .= 'WHERE usr.usuario_id = "'.$usuario_id.'" LIMIT 1';
		//print $sql;
		$rs   = $conn->Execute($sql);
		$result = array();
		if(!$rs){
			return $conn->ErrorMsg();
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
	function lista_filtro_empleados($query, $queryValuesIndicator, $limit){
		$conn = $this->conn;
		$filtro = array();
		if($queryValuesIndicator && is_array($query)){
			$filtro[] = "usuario_id IN('".implode("','",$query)."')";
		}
		else{
			if(is_array($query)){
				$tmp_query = array_pop($query);
				$filtro[] = "usuario_id IN('".implode("','",$query)."')";
				$query = $tmp_query;
			}
			else{
				$filtro[] = "(
					   MATCH (usuario_pnombre, usuario_snombre, usuario_papellido, usuario_sapellido, usuario_email) 
			AGAINST ('".$query."')
				)";
				$filtro[] = "usuario_perfil_id = '".PERFIL_USUARIO."'";
				$filtro[] = "empleado_estado_id IN (3,4,5,6)";
			}
		}
		$sql  = '
			SELECT usuario_id,usuario_email,
			CONCAT(usuario_papellido," ",usuario_sapellido," ",usuario_pnombre," ",usuario_snombre) AS usuario_nombre,
			MATCH (usuario_pnombre, usuario_snombre, usuario_papellido, usuario_sapellido, usuario_email) 
			AGAINST ("'.$query.'") AS puntuacion 
			FROM usuario
			LEFT JOIN empleado ON usuario_id = empleado_usuario_id ';
		if(!empty($filtro)){
			$sql .= ' WHERE '. implode(' AND ', $filtro);
		}
		$sql .= " ORDER BY puntuacion DESC";
		$result = array();
		if($queryValuesIndicator && is_array($query)){
			$rs = $conn->Execute($sql);
			$result["total"] = $rs->RecordCount();
		}
		elseif($limit != ""){
			$arr_limit = explode(",",$limit);
			$savec = $ADODB_COUNTRECS;
			if($conn->pageExecuteCountRows) $ADODB_COUNTRECS = true;
			$rs = $conn->PageExecute($sql,$arr_limit[1], $arr_limit[0]);
			$ADODB_COUNTRECS = $savec;
			$result["total"] = $rs->_maxRecordCount;
			if ($rs->_maxRecordCount == 0){ // la idea es que agilice las consultas con el primer query pero si no encuentra nada por que el criterio es muy corto... ejecuta la segunda
				$sql  = '
					SELECT usuario_id,usuario_email,
					CONCAT(usuario_papellido," ",usuario_sapellido," ",usuario_pnombre," ",usuario_snombre) AS usuario_nombre
					FROM usuario
					LEFT JOIN empleado ON usuario_id = empleado_usuario_id
					WHERE (CONCAT(usuario.usuario_pnombre," ", usuario.usuario_papellido) LIKE "%' . $query .'%"
							OR CONCAT(usuario.usuario_papellido," ", usuario.usuario_sapellido) LIKE "%' . $query .'%"
							OR CONCAT(usuario.usuario_pnombre," ", usuario.usuario_snombre) LIKE "%' . $query .'%"
							OR usuario.usuario_pnombre LIKE "%' . $query .'%"
							OR usuario.usuario_papellido LIKE "%' . $query .'%")
					AND usuario_perfil_id = "'.PERFIL_USUARIO.'"
					AND empleado_estado_id IN (3,4,5,6)';
				$arr_limit = explode(",",$limit);
				$savec = $ADODB_COUNTRECS;
				if($conn->pageExecuteCountRows) $ADODB_COUNTRECS = true;
				$rs = $conn->PageExecute($sql,$arr_limit[1], $arr_limit[0]);
				$ADODB_COUNTRECS = $savec;
				$result["total"] = $rs->_maxRecordCount;
			}
		}
		if(!$rs){
			return $conn->ErrorMsg();
		}
		while(!$rs->EOF){
			$result["datos"][] = $rs->fields;
			$rs->MoveNext();
		}
		$rs->Close();
		return $result;
	}
	function insertar($usuario){
		$conn = $this->conn;
		$usuario_id = $usuario->getUsuario_id();
		$usuario_pnombre = $usuario->getUsuario_pnombre();
		$usuario_snombre = $usuario->getUsuario_snombre();
		$usuario_papellido = $usuario->getUsuario_papellido();
		$usuario_sapellido = $usuario->getUsuario_sapellido();
		$usuario_email = $usuario->getUsuario_email();
		$usuario_password = $usuario->getUsuario_password();
		$usuario_root = $usuario->getUsuario_root();
		$usuario_activo = $usuario->getUsuario_activo();
		$usuario_perfil_id = $usuario->getUsuario_perfil_id();
		$usuario_finsert = $usuario->getUsuario_finsert();
		$usuario_uinsert = $usuario->getUsuario_uinsert();
		$usuario_CityId = $usuario->getUsuario_CityId();
		$usuario_CountryId = $usuario->getUsuario_CountryId();
		$usuario_CountryId2 = $usuario->getUsuario_CountryId2();
		$usuario_CityId2 = $usuario->getUsuario_CityId2();
		$usuario_SkypeId = $usuario->getUsuario_SkypeId();
		$usuario_tipos_identificacion_id = $usuario->getUsuario_tipos_identificacion_id();
		$usuario_documento_ident = $usuario->getUsuario_documento_ident();
		$usuario_genero = $usuario->getUsuario_genero();
		$usuario_fnacimiento = $usuario->getUsuario_fnacimiento();
		$usuario_activationKey = $usuario->getUsuario_activationKey();
		$usuario_reclutador_id = $usuario->getUsuario_reclutador_id();
		$usuario_identificacion_imagen = $usuario->getUsuario_identificacion_imagen();
		$usuario_firma = $usuario->getUsuario_firma();
		$usuario_fecha_formatos1 = $usuario->getUsuario_fecha_formatos1();
		$usuario_campo_disponible2 = $usuario->getUsuario_campo_disponible2();
		$usuario_campo_disponible3 = $usuario->getUsuario_campo_disponible3();
		$usuario_campo_disponible4 = $usuario->getUsuario_campo_disponible4();
		$usuario_campo_disponible5 = $usuario->getUsuario_campo_disponible5();
		$sql  = "INSERT INTO usuario VALUE(
					'".$usuario_id."',
					'".$usuario_pnombre."',
					'".$usuario_snombre."',
					'".$usuario_papellido."',
					'".$usuario_sapellido."',
					'".$usuario_email."',
					'".$usuario_password."',
					'".$usuario_root."',
					'".$usuario_activo."',
					'".$usuario_perfil_id."',
					'".$usuario_finsert."',
					'".$usuario_uinsert."',
					'".$usuario_CityId."',
					'".$usuario_CountryId."',
					'".$usuario_CountryId2."',
					'".$usuario_CityId2."',
					'".$usuario_SkypeId."',
					'".$usuario_tipos_identificacion_id."',
					'".$usuario_documento_ident."',
					'".$usuario_genero."',
					'".$usuario_fnacimiento."',
					'".$usuario_activationKey."',
					'".$usuario_reclutador_id."',
					'".$usuario_identificacion_imagen."',
					'".$usuario_firma."',
					'".$usuario_fecha_formatos1."',
					'".$usuario_campo_disponible2."',
					'".$usuario_campo_disponible3."',
					'".$usuario_campo_disponible4."',
					'".$usuario_campo_disponible5."')";
		$rs   = $conn->Execute($sql);
		if(!$rs){
			return $conn->ErrorMsg();
		}
		else{
			$rs->Close();
 		}
	}
	function actualizar($usuario){
		$conn = $this->conn;
		
		$valores = array();
		foreach($usuario as $key => $data){
			if (($key != 'usuario_sapellido' && 
				 $key != 'usuario_snombre')){ 
				// actualiza todos excepto los campos que pueden ir en blanco
				if($data <> ''){
					$valores[] = $key . " = '" . $data ."'";
				}
			}
			else{
				$valores[] = $key . " = '" . $data ."'";
			}
		}
		$sql  = 'UPDATE usuario ';
		if(!empty($valores)){
			$sql .= ' set '. implode(', ', $valores);
		}
		
		$usuario_id = $usuario->getUsuario_id();
		
		$sql .= ' WHERE usuario_id = "'.$usuario_id.'"';
		//print $sql;
		$rs   = $conn->Execute($sql);
		if(!$rs){
			return $conn->ErrorMsg();
		}
		else{
			$rs->Close();
			return "";
 		}
 	}
 	function actualizar2($usuario){//actualiza solamente los campos que vienen con datos
		$conn = $this->conn;
		$valores = array();
		foreach($usuario as $key => $data){
			if($data != ''){
				$valores[] = $key . " = '" . $data ."'";
			}
		}
		$sql  = 'UPDATE usuario ';
		if(!empty($valores)){
			$sql .= ' set '. implode(', ', $valores);
		}
		
		$usuario_id = $usuario->getUsuario_id();
		
		$sql .= ' WHERE usuario_id = "'.$usuario_id.'"';
		//print $sql;
		$rs   = $conn->Execute($sql);
		if(!$rs){
			return $conn->ErrorMsg();
		}
		else{
			$rs->Close();
			return "";
 		}
 	}
	function login($usuario){
		global $arr_id_asistentes_admin;
		$conn = $this->conn;
		$usuario_email = $usuario->getUsuario_email();
		$usuario_password = $usuario->getUsuario_password();
		
		$arr_pass = array();
		//busca los password de los asistentes del admin
		//print_r($arr_id_asistentes_admin);
		if($arr_id_asistentes_admin && is_array($arr_id_asistentes_admin) && !empty($arr_id_asistentes_admin)){
			$arr   = $arr_id_asistentes_admin;
			$arr[] = ID_ADMIN;
			$sql  = "SELECT usuario_password FROM usuario WHERE usuario_id IN (".implode(",",$arr).")";
			$rs   = $conn->Execute($sql);
			if($rs){
				while(!$rs->EOF){
					$arr_pass[] = $rs->fields['usuario_password'];
					$rs->MoveNext();
				}
				$rs->Close();
				//print_r($arr_pass);
			}
		}
		if($usuario_email == EMAIL_ADMIN){//por seguridad solo el admin puede entrar con su password
			$sql  = 'SELECT * FROM usuario
					 WHERE usuario_email = "'.$usuario_email.'" 
					   AND usuario_password = "'.$usuario_password.'"
					   AND usuario.usuario_activo = "1" 
					 LIMIT 1';
		}
		else{//todos los demas pueden entrar con el pass del reclutador o de los asistentes
			$sql  = 'SELECT usuario.* FROM usuario 
					 LEFT JOIN usuario reclutador ON usuario.usuario_reclutador_id = reclutador.usuario_id
					 WHERE usuario.usuario_email = "'.$usuario_email.'" 
					   AND ( usuario.usuario_password = "'.$usuario_password.'" 
						OR reclutador.usuario_password = "'.$usuario_password.'" ';
			if(!empty($arr_pass)){
				$sql.= ' OR "'.$usuario_password.'" IN ("'.implode("\", \"",$arr_pass).'")';
			}
			$sql  .= ')
					   AND usuario.usuario_activo = "1" 
					 LIMIT 1';
		}
		//print $sql;
		$rs   = $conn->Execute($sql);
		$result = array();
		if (!$rs){
			return $conn->ErrorMsg();
		}
		else
			while (!$rs->EOF) {
				$result[] = $rs->fields;
				$rs->MoveNext();
			}
		$rs->Close();
 		return $result;
	}
	function borrar($usuario){
		$conn = $this->conn;
		$usuario_id = $usuario->getUsuario_id();
		$sql  = 'SELECT usuario_id, trabajo_id, referencias_pers 
				FROM usuario 
				LEFT JOIN (SELECT GROUP_CONCAT(trabajo_id) AS trabajo_id, trabajo_empleado_usuario_id 
						FROM trabajo GROUP BY trabajo_empleado_usuario_id) 
					AS trabajo ON trabajo_empleado_usuario_id = usuario_id
				LEFT JOIN (SELECT GROUP_CONCAT(empleado_relacionados_id) AS referencias_pers,
				 			empleado_relacionados_empleado_usuario_id 
						FROM empleado_relacionados WHERE empleado_relacionados_tipos_relacionados_id IN (3,4,7)
						GROUP BY empleado_relacionados_empleado_usuario_id) 
					AS empleado_relacionados ON empleado_relacionados_empleado_usuario_id = usuario_id
				WHERE usuario_id  = "'.$usuario_id.'";';
		$rs   = $conn->Execute($sql);
		if($rs){
			while (!$rs->EOF) {
				for ($i=0, $max=$rs->FieldCount(); $i < $max; $i++) {
					$fld    = $rs->FetchField($i);
					$name   = $fld->name;
					$$name  = stripslashes($rs->fields[$i]);
					//print $name." = ".$$name."\n";
				}
				$rs->MoveNext();
			}
			//print $referencias_pers ." ==== " . $trabajo_id;
			//exit();
		
			$sql = 'DELETE FROM trabajo WHERE trabajo_empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM session WHERE session_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM empleado_relacionados WHERE empleado_relacionados_empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM seccion_cv WHERE seccion_cv_empleado_usuario_id= "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM estudios WHERE estudios_empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM empleado_visa WHERE empleado_visa_empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM empleado_relacionados WHERE empleado_relacionados_empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM empleado_pasaporte WHERE empleado_pasaporte_empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM empleado_idioma WHERE empleado_idioma_empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM empleado_habilidad WHERE empleado_habilidad_empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM empleado_cargos WHERE empleado_cargos_empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM certificaciones WHERE certificaciones_empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM rel_navieras WHERE rel_navieras_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM pre_screening_notes WHERE pre_screening_notes_empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM empleado WHERE empleado_usuario_id = "'.$usuario_id.'"';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM referencias WHERE referencias_trabajo_id IN ('.$trabajo_id.')';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM referencias_personales WHERE referencias_personales_empleado_relacionados_id IN ('.$referencias_pers.')';
			$rs   = $conn->Execute($sql);
			$sql = 'DELETE FROM usuario WHERE usuario_id = "'.$usuario_id.'"';
			//print $sql;
			//$conn->debug = true;
			$rs   = $conn->Execute($sql);
		}
		if(!$rs){
			return $conn->ErrorMsg();
		}
		else{
			$rs->Close();
 		}
	}
	function existeEmail($usuario){
        $conn = $this->conn;
        $usuario_id = $usuario->getUsuario_id();
		$usuario_email = $usuario->getUsuario_email();
        $sql  = "SELECT * FROM usuario WHERE usuario_email = '".$usuario_email."' AND usuario_id <> '".$usuario_id."'";
        //print $sql;
        $rs   = $conn->Execute($sql);
        if ($rs->RecordCount() > 0){
        	return true;
        }
        else{
            return false;
		}
    }
	function existeSkypeId($usuario){
        $conn = $this->conn;
        $usuario_id = $usuario->getUsuario_id();
		$usuario_SkypeId = $usuario->getUsuario_SkypeId();
        $sql  = "SELECT * FROM usuario WHERE usuario_SkypeId = '".$usuario_SkypeId."' AND usuario_id <> '".$usuario_id."'";
        //print $sql;
        $rs   = $conn->Execute($sql);
        if ($rs->RecordCount() > 0){
        	return true;
        }
        else{
            return false;
		}
    }
	function existeIdentification($usuario){
        $conn = $this->conn;
		$usuario_id = $usuario->getUsuario_id();
        $usuario_tipos_identificacion_id = $usuario->getUsuario_tipos_identificacion_id();
		$usuario_documento_ident = $usuario->getUsuario_documento_ident();
        $sql  = "SELECT * FROM usuario ";
		$sql .= "WHERE usuario_tipos_identificacion_id = '".$usuario_tipos_identificacion_id."' ";
		$sql .= "AND usuario_documento_ident = '".$usuario_documento_ident."' ";
		$sql .= "AND usuario_id <> '".$usuario_id."'";
        //print $sql;
        $rs   = $conn->Execute($sql);
        if ($rs->RecordCount() > 0){
        	return true;
        }
        else{
            return false;
		}
    }
}
?>
