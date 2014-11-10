<?php
include('session.php');
class SessionAdo extends Conexion{
	var $conn;
	function SessionAdo($_bd){
		parent::Conexion($_bd);
	}
	function lista($session){
		$conn = $this->conn;
		$filtro = array();
		foreach($session as $key => $data){
			if ($data <> ''){
				$filtro[] = $key . " = '" . $data ."'";
			}
		}
		$sql  = 'SELECT * FROM session';
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
	function insertar($session){
		$conn = $this->conn;
		$session_usuario_id = $session->getSession_usuario_id();
		$session_php_id = $session->getSession_php_id();
		$session_date = $session->getSession_date();
		$session_activa = $session->getSession_activa();
		$sql  = 'INSERT INTO session VALUE( "'.$session_usuario_id.'", "'.$session_php_id.'", "'.$session_date.'", "'.$session_activa.'")';
		$rs   = $conn->Execute($sql);
		if(!$rs){
			print $conn->ErrorMsg();
		}
		else{
			$rs->Close();
 		}
	}
	function actualizar($session){
		$conn = $this->conn;
		$session_usuario_id = $session->getSession_usuario_id();
		$session_php_id = $session->getSession_php_id();
		$session_date = $session->getSession_date();
		$session_activa = $session->getSession_activa();
		$sql  = 'UPDATE session SET session_php_id = "'.$session_php_id.'", session_date = "'.$session_date.'", session_activa = "'.$session_activa.'" WHERE session_usuario_id = '.$session_usuario_id.'';
		$rs   = $conn->Execute($sql);
		if(!$rs){
			print $conn->ErrorMsg();
		}
		else{
			$rs->Close();
 		}
	}
	function logout($session){
		$conn = $this->conn;
		$session_usuario_id = $session->getSession_usuario_id();
		$session_php_id = $session->getSession_php_id();
		$session_activa = $session->getSession_activa();
		$sql  = 'UPDATE session SET session_activa = "'.$session_activa.'" WHERE session_usuario_id = "'.$session_usuario_id.'" ';
		$sql .= 'AND session_php_id = "'.$session_php_id.'"';
		$rs   = $conn->Execute($sql);
		if (!$rs)
		print $conn->ErrorMsg();
		else
		$rs->Close();
 	}
	function borrar($session){
		$conn = $this->conn;
		$session_usuario_id = $session->getSession_usuario_id();
		$sql  = 'DELETE FROM session WHERE session_usuario_id = "'.$session_usuario_id.'"';
		$rs   = $conn->Execute($sql);
		if(!$rs){
			print $conn->ErrorMsg();
		}
		else{
			$rs->Close();
 		}
	}
}
?>
