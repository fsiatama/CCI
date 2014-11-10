<?php 
 class Session{
  var $session_usuario_id;
  var $session_php_id;
  var $session_date;
  var $session_activa;
  function session(){
        	//constructor vacio
  }
  function setSession_usuario_id($session_usuario_id){
	  $this->session_usuario_id = $session_usuario_id;
  }
  function getSession_usuario_id(){
	  return $this->session_usuario_id;
  }
  function setSession_php_id($session_php_id){
	  $this->session_php_id = $session_php_id;
  }
  function getSession_php_id(){
	  return $this->session_php_id;
  }
  function setSession_date($session_date){
	  $this->session_date = $session_date;
  }
  function getSession_date(){
	  return $this->session_date;
  }
  function setSession_activa($session_activa){
	  $this->session_activa = $session_activa;
  }
  function getSession_activa(){
	  return $this->session_activa;
  }
}
?>