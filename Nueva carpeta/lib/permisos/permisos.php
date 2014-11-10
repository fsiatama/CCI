<?php 
 class Permisos{
  var $permisos_id;
  var $permisos_perfil_id;
  var $permisos_opc_menu_id;
  var $permisos_listar;
  var $permisos_modificar;
  var $permisos_crear;
  var $permisos_borrar;
  var $permisos_exportar;
  function permisos(){
        	//constructor vacio
  }
  function setPermisos_id($permisos_id){
	  $this->permisos_id = $permisos_id;
  }
  function getPermisos_id(){
	  return $this->permisos_id;
  }
  function setPermisos_perfil_id($permisos_perfil_id){
	  $this->permisos_perfil_id = $permisos_perfil_id;
  }
  function getPermisos_perfil_id(){
	  return $this->permisos_perfil_id;
  }
  function setPermisos_opc_menu_id($permisos_opc_menu_id){
	  $this->permisos_opc_menu_id = $permisos_opc_menu_id;
  }
  function getPermisos_opc_menu_id(){
	  return $this->permisos_opc_menu_id;
  }
  function setPermisos_listar($permisos_listar){
	  $this->permisos_listar = $permisos_listar;
  }
  function getPermisos_listar(){
	  return $this->permisos_listar;
  }
  function setPermisos_modificar($permisos_modificar){
	  $this->permisos_modificar = $permisos_modificar;
  }
  function getPermisos_modificar(){
	  return $this->permisos_modificar;
  }
  function setPermisos_crear($permisos_crear){
	  $this->permisos_crear = $permisos_crear;
  }
  function getPermisos_crear(){
	  return $this->permisos_crear;
  }
  function setPermisos_borrar($permisos_borrar){
	  $this->permisos_borrar = $permisos_borrar;
  }
  function getPermisos_borrar(){
	  return $this->permisos_borrar;
  }
  function setPermisos_exportar($permisos_exportar){
	  $this->permisos_exportar = $permisos_exportar;
  }
  function getPermisos_exportar(){
	  return $this->permisos_exportar;
  }
}
?>