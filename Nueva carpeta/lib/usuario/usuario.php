<?php 
 class Usuario{
  var $usuario_id;
  var $usuario_pnombre;
  var $usuario_snombre;
  var $usuario_papellido;
  var $usuario_sapellido;
  var $usuario_email;
  var $usuario_password;
  var $usuario_root;
  var $usuario_activo;
  var $usuario_perfil_id;
  var $usuario_finsert;
  var $usuario_uinsert;
  var $usuario_CityId;
  var $usuario_CountryId;
  var $usuario_CountryId2;
  var $usuario_CityId2;
  var $usuario_SkypeId;
  var $usuario_tipos_identificacion_id;
  var $usuario_documento_ident;
  var $usuario_genero;
  var $usuario_fnacimiento;
  var $usuario_activationKey;
  var $usuario_reclutador_id;
  var $usuario_identificacion_imagen;
  var $usuario_firma;
  var $usuario_fecha_formatos1;
  var $usuario_campo_disponible2;
  var $usuario_campo_disponible3;
  var $usuario_campo_disponible4;
  var $usuario_campo_disponible5;
  function usuario(){
        	//constructor vacio
  }
  function setUsuario_id($usuario_id){
	  $this->usuario_id = $usuario_id;
  }
  function getUsuario_id(){
	  return $this->usuario_id;
  }
  function setUsuario_pnombre($usuario_pnombre){
	  $this->usuario_pnombre = $usuario_pnombre;
  }
  function getUsuario_pnombre(){
	  return $this->usuario_pnombre;
  }
  function setUsuario_snombre($usuario_snombre){
	  $this->usuario_snombre = $usuario_snombre;
  }
  function getUsuario_snombre(){
	  return $this->usuario_snombre;
  }
  function setUsuario_papellido($usuario_papellido){
	  $this->usuario_papellido = $usuario_papellido;
  }
  function getUsuario_papellido(){
	  return $this->usuario_papellido;
  }
  function setUsuario_sapellido($usuario_sapellido){
	  $this->usuario_sapellido = $usuario_sapellido;
  }
  function getUsuario_sapellido(){
	  return $this->usuario_sapellido;
  }
  function setUsuario_email($usuario_email){
	  $this->usuario_email = $usuario_email;
  }
  function getUsuario_email(){
	  return $this->usuario_email;
  }
  function setUsuario_password($usuario_password){
	  $this->usuario_password = $usuario_password;
  }
  function getUsuario_password(){
	  return $this->usuario_password;
  }
  function setUsuario_root($usuario_root){
	  $this->usuario_root = $usuario_root;
  }
  function getUsuario_root(){
	  return $this->usuario_root;
  }
  function setUsuario_activo($usuario_activo){
	  $this->usuario_activo = $usuario_activo;
  }
  function getUsuario_activo(){
	  return $this->usuario_activo;
  }
  function setUsuario_perfil_id($usuario_perfil_id){
	  $this->usuario_perfil_id = $usuario_perfil_id;
  }
  function getUsuario_perfil_id(){
	  return $this->usuario_perfil_id;
  }
  function setUsuario_finsert($usuario_finsert){
	  $this->usuario_finsert = $usuario_finsert;
  }
  function getUsuario_finsert(){
	  return $this->usuario_finsert;
  }
  function setUsuario_uinsert($usuario_uinsert){
	  $this->usuario_uinsert = $usuario_uinsert;
  }
  function getUsuario_uinsert(){
	  return $this->usuario_uinsert;
  }
  function setUsuario_CityId($usuario_CityId){
	  $this->usuario_CityId = $usuario_CityId;
  }
  function getUsuario_CityId(){
	  return $this->usuario_CityId;
  }
  function setUsuario_CountryId($usuario_CountryId){
	  $this->usuario_CountryId = $usuario_CountryId;
  }
  function getUsuario_CountryId(){
	  return $this->usuario_CountryId;
  }
  function setUsuario_CountryId2($usuario_CountryId2){
	  $this->usuario_CountryId2 = $usuario_CountryId2;
  }
  function getUsuario_CountryId2(){
	  return $this->usuario_CountryId2;
  }
  function setUsuario_CityId2($usuario_CityId2){
	  $this->usuario_CityId2 = $usuario_CityId2;
  }
  function getUsuario_CityId2(){
	  return $this->usuario_CityId2;
  }
  function setUsuario_SkypeId($usuario_SkypeId){
	  $this->usuario_SkypeId = $usuario_SkypeId;
  }
  function getUsuario_SkypeId(){
	  return $this->usuario_SkypeId;
  }
  function setUsuario_tipos_identificacion_id($usuario_tipos_identificacion_id){
	  $this->usuario_tipos_identificacion_id = $usuario_tipos_identificacion_id;
  }
  function getUsuario_tipos_identificacion_id(){
	  return $this->usuario_tipos_identificacion_id;
  }
  function setUsuario_documento_ident($usuario_documento_ident){
	  $this->usuario_documento_ident = $usuario_documento_ident;
  }
  function getUsuario_documento_ident(){
	  return $this->usuario_documento_ident;
  }
  function setUsuario_genero($usuario_genero){
	  $this->usuario_genero = $usuario_genero;
  }
  function getUsuario_genero(){
	  return $this->usuario_genero;
  }
  function setUsuario_fnacimiento($usuario_fnacimiento){
	  $this->usuario_fnacimiento = $usuario_fnacimiento;
  }
  function getUsuario_fnacimiento(){
	  return $this->usuario_fnacimiento;
  }
  function setUsuario_activationKey($usuario_activationKey){
	  $this->usuario_activationKey = $usuario_activationKey;
  }
  function getUsuario_activationKey(){
	  return $this->usuario_activationKey;
  }
  function setUsuario_reclutador_id($usuario_reclutador_id){
	  $this->usuario_reclutador_id = $usuario_reclutador_id;
  }
  function getUsuario_reclutador_id(){
	  return $this->usuario_reclutador_id;
  }
  function setUsuario_identificacion_imagen($usuario_identificacion_imagen){
	  $this->usuario_identificacion_imagen = $usuario_identificacion_imagen;
  }
  function getUsuario_identificacion_imagen(){
	  return $this->usuario_identificacion_imagen;
  }
  function setUsuario_firma($usuario_firma){
	  $this->usuario_firma = $usuario_firma;
  }
  function getUsuario_firma(){
	  return $this->usuario_firma;
  }
  function setUsuario_fecha_formatos1($usuario_fecha_formatos1){
	  $this->usuario_fecha_formatos1 = $usuario_fecha_formatos1;
  }
  function getUsuario_fecha_formatos1(){
	  return $this->usuario_fecha_formatos1;
  }
  function setUsuario_campo_disponible2($usuario_campo_disponible2){
	  $this->usuario_campo_disponible2 = $usuario_campo_disponible2;
  }
  function getUsuario_campo_disponible2(){
	  return $this->usuario_campo_disponible2;
  }
  function setUsuario_campo_disponible3($usuario_campo_disponible3){
	  $this->usuario_campo_disponible3 = $usuario_campo_disponible3;
  }
  function getUsuario_campo_disponible3(){
	  return $this->usuario_campo_disponible3;
  }
  function setUsuario_campo_disponible4($usuario_campo_disponible4){
	  $this->usuario_campo_disponible4 = $usuario_campo_disponible4;
  }
  function getUsuario_campo_disponible4(){
	  return $this->usuario_campo_disponible4;
  }
  function setUsuario_campo_disponible5($usuario_campo_disponible5){
	  $this->usuario_campo_disponible5 = $usuario_campo_disponible5;
  }
  function getUsuario_campo_disponible5(){
	  return $this->usuario_campo_disponible5;
  }
}
?>