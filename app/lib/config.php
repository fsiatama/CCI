<?php
ini_set('session.cookie_domain', '.sicex.com' );

if(strpos($_SERVER['HTTP_HOST'],$_SERVER['SERVER_NAME'])===false){
    header('Content-Type:text/plain');
    header('X-Robots-Tag:none',true);
    header('Status:400 Bad Request',true,400);
    exit('400 Bad Request');
}
//header("Content-Type: text/html; charset=iso-8859-1");

define("SL", "\r\n");
define("BR", "<BR>".SL);
//define("URL_RAIZ", "http://".$_SERVER['HTTP_HOST']."/www2/");
define("URL_RAIZ", "http://".$_SERVER['HTTP_HOST']."/");

define("URL_APP", "http://appnew.sicex.com/");

define("URL_INGRESO", URL_RAIZ."index.php");
define("URL_ERROR", URL_RAIZ."error.php");
define("URL_EXT", URL_RAIZ."js/ext-3.3.0/");
define("EXT_TEMA", "xtheme-tp.css");
define("JQUERYUI_TEMA", "smoothness");
//define("PATH_RAIZ", "/Library/WebServer/Documents/www2/");
define("PATH_RAIZ", "/var/www/html/sicexJQ/sicex_models/");
define("PATH_APP", "/var/www/html/sicexJQ/appnew/");
//Ruta de las facturas
define("PATH_FACTURA", "C:/AppServ/www/sicexJQ/facturas/");
define("PATH_OCEXT", "C:/AppServ/www/sicexJQ/ocext/");
//Ruta al content manager
define("PATH_CM", PATH_RAIZ."cm/");
define("URL_CM", URL_RAIZ."cm/");
define("PATH_REPORTES", PATH_RAIZ."rep/");
define("IDIOMA", "colombia.dic.php");
define("PASS_UPDATE", "364");
define("DIASPARAMORA", "30"); // SE UTLIZA PARA CALCULAR LAS FACTURAS QUE SE ENCUENTRAN EN MORA
define("USER_NO_MONITOR", "11,1511,1936,1977,8,937,1513,858,1512,1818,1748");
define("MONEDA_NAL", "COP");
//Tipos de Bases de datos usado en lib_bd.php
define("BDMYSQL", 201); //BD MySQL normal MySAM
define("BDMYSQLI", 202); //BD innoDB

//Configuración para enviar los reportes especiales
define("MAIL_FROM", "sales@sicex.com");
define("MAIL_FROMNAME", "Pablo Castano");
define("MAIL_REPLY", MAIL_FROM);
define("MAIL_HOST", "172.16.1.7");
define("MAIL_MAILER", "smtp");
define("AUMENTO_ANUAL", "4");

//mail para informar venta dolares
define("MAIL_USD", "czuluaga@royal-tec.com");
//mail para notificaR FACTURA NUEVA
define("MAIL_SVR_GRAL", "serviciosgenerales@sicex.com");
//mail bonificaciones
define("MAIL_RH", "jvera@sicex.com");
//mail contabilidad
define("MAIL_CONT", "lcupitra@sicex.com");
//Id del vendedor Online para la creacion de empresas y cotizaciones
define("VENDEDOR_ONLINE", "2251");

//Configuración al servidor central
$config['tipoBD'] = BDMYSQLI;
$config['server'] = "192.168.15.3";
$config['bd'] = "sicex_r";
$config['login'] = "root";
$config['password'] = "";

//Configuración al servidor central
$config['tipoBD'] = BDMYSQLI;
$config['server'] = "192.168.15.3";
$config['bd'] = "sicex_r";
$config['login'] = "root";
$config['password'] = "";

//Configuración para conectarse al Multipaís
$configBDMP['tipoBD'] = BDMYSQLI;
$configBDMP['server'] = "192.168.15.3";
$configBDMP['bd'] = "multipais";
$configBDMP['login'] = "root";
$configBDMP['password'] = "";

$configMuestrasSeo['tipoBD'] = BDMYSQLI;
$configMuestrasSeo['server'] = "192.168.15.3";
$configMuestrasSeo['bd'] = "muestrasSeo";
$configMuestrasSeo['login'] = "root";
$configMuestrasSeo['password'] = "";

$configMarketing['tipoBD'] = BDMYSQLI;
$configMarketing['server'] = "192.168.15.3";
$configMarketing['bd'] = "marketing";
$configMarketing['login'] = "root";
$configMarketing['password'] = "";

$configCfgSisduan['tipoBD'] = BDMYSQLI;
$configCfgSisduan['server'] = "192.168.15.3";
$configCfgSisduan['bd'] = "cfg_sisduan";
$configCfgSisduan['login'] = "root";
$configCfgSisduan['password'] = "";

$configBDPortal['tipoBD'] = BDMYSQLI;
$configBDPortal['server'] = "192.168.15.3";
$configBDPortal['bd'] = "portal";
$configBDPortal['login'] = "root";
$configBDPortal['password'] = "";

//Configuración para conectarse a sisduan de Colombia
$configBDSisduanCol['tipoBD'] = BDMYSQLI;
$configBDSisduanCol['server'] = "192.168.15.3";
$configBDSisduanCol['bd'] = "aduanas_col";
$configBDSisduanCol['login'] = "root";
$configBDSisduanCol['password'] = "";

$configBDSisduanCan['tipoBD'] = BDMYSQLI;
$configBDSisduanCan['server'] = "192.168.15.3";
$configBDSisduanCan['bd'] = "aduanas_can";
$configBDSisduanCan['login'] = "root";
$configBDSisduanCan['password'] = "";

$configBDSisduanEcu['tipoBD'] = BDMYSQLI;
$configBDSisduanEcu['server'] = "192.168.15.3";
$configBDSisduanEcu['bd'] = "aduanas_ecu";
$configBDSisduanEcu['login'] = "root";
$configBDSisduanEcu['password'] = "";

$configBDSisduanPer['tipoBD'] = BDMYSQLI;
$configBDSisduanPer['server'] = "192.168.15.3";
$configBDSisduanPer['bd'] = "aduanas_per";
$configBDSisduanPer['login'] = "root";
$configBDSisduanPer['password'] = "";

$configBDSisduanPry['tipoBD'] = BDMYSQLI;
$configBDSisduanPry['server'] = "192.168.15.3";
$configBDSisduanPry['bd'] = "aduanas_pry";
$configBDSisduanPry['login'] = "root";
$configBDSisduanPry['password'] = "";

$configBDSisduanArg['tipoBD'] = BDMYSQLI;
$configBDSisduanArg['server'] = "192.168.15.3";
$configBDSisduanArg['bd'] = "aduanas_arg";
$configBDSisduanArg['login'] = "root";
$configBDSisduanArg['password'] = "";

$configBDSisduanArgNew['tipoBD'] = BDMYSQLI;
$configBDSisduanArgNew['server'] = "192.168.15.3";
$configBDSisduanArgNew['bd'] = "aduanas_arg";
$configBDSisduanArgNew['login'] = "root";
$configBDSisduanArgNew['password'] = "";

$configBDSisduanCle['tipoBD'] = BDMYSQLI;
$configBDSisduanCle['server'] = "192.168.15.3";
$configBDSisduanCle['bd'] = "aduanas_cle";
$configBDSisduanCle['login'] = "root";
$configBDSisduanCle['password'] = "";

$configBDSisduanUru['tipoBD'] = BDMYSQLI;
$configBDSisduanUru['server'] = "192.168.15.3";
$configBDSisduanUru['bd'] = "aduanas_uru";
$configBDSisduanUru['login'] = "root";
$configBDSisduanUru['password'] = "";

$configBDSisduanEsp['tipoBD'] = BDMYSQLI;
$configBDSisduanEsp['server'] = "192.168.15.3";
$configBDSisduanEsp['bd'] = "aduanas_esp";
$configBDSisduanEsp['login'] = "root";
$configBDSisduanEsp['password'] = "";

$configBDSisduanBra['tipoBD'] = BDMYSQLI;
$configBDSisduanBra['server'] = "192.168.15.3";
$configBDSisduanBra['bd'] = "aduanas_bra";
$configBDSisduanBra['login'] = "root";
$configBDSisduanBra['password'] = "";

$configBDSisduanCri['tipoBD'] = BDMYSQLI;
$configBDSisduanCri['server'] = "192.168.15.3";
$configBDSisduanCri['bd'] = "aduanas_cri";
$configBDSisduanCri['login'] = "root";
$configBDSisduanCri['password'] = "";

$configBDSisduanCriNew['tipoBD'] = BDMYSQLI;
$configBDSisduanCriNew['server'] = "192.168.15.3";
$configBDSisduanCriNew['bd'] = "aduanascri_new";
$configBDSisduanCriNew['login'] = "root";
$configBDSisduanCriNew['password'] = "";

$configBDSisduanVen['tipoBD'] = BDMYSQLI;
$configBDSisduanVen['server'] = "192.168.15.3";
$configBDSisduanVen['bd'] = "aduanas_ven";
$configBDSisduanVen['login'] = "root";
$configBDSisduanVen['password'] = "";

$configBDSisduanPan['tipoBD'] = BDMYSQLI;
$configBDSisduanPan['server'] = "192.168.15.3";
$configBDSisduanPan['bd'] = "aduanas_pan";
$configBDSisduanPan['login'] = "root";
$configBDSisduanPan['password'] = "";

$configBDSisduanMex['tipoBD'] = BDMYSQLI;
$configBDSisduanMex['server'] = "192.168.15.3";
$configBDSisduanMex['bd'] = "aduanas_mex";
$configBDSisduanMex['login'] = "root";
$configBDSisduanMex['password'] = "";

$configBDSisduanIng['tipoBD'] = BDMYSQLI;
$configBDSisduanIng['server'] = "192.168.15.3";
$configBDSisduanIng['bd'] = "aduanas_ing";
$configBDSisduanIng['login'] = "root";
$configBDSisduanIng['password'] = "";

$configBDSisduanSlv['tipoBD'] = BDMYSQLI;
$configBDSisduanSlv['server'] = "192.168.15.3";
$configBDSisduanSlv['bd'] = "aduanas_slv";
$configBDSisduanSlv['login'] = "root";
$configBDSisduanSlv['password'] = "";

$configBDSisduanInd['tipoBD'] = BDMYSQLI;
$configBDSisduanInd['server'] = "192.168.15.3";
$configBDSisduanInd['bd'] = "aduanas_ind";
$configBDSisduanInd['login'] = "root";
$configBDSisduanInd['password'] = "";

$configBDSisduanIdn['tipoBD'] = BDMYSQLI;
$configBDSisduanIdn['server'] = "192.168.15.3";
$configBDSisduanIdn['bd'] = "aduanas_idn";
$configBDSisduanIdn['login'] = "root";
$configBDSisduanIdn['password'] = "";

$configBDSisduanTha['tipoBD'] = BDMYSQLI;
$configBDSisduanTha['server'] = "192.168.15.3";
$configBDSisduanTha['bd'] = "aduanas_tha";
$configBDSisduanTha['login'] = "root";
$configBDSisduanTha['password'] = "";

$configBDSisduanKor['tipoBD'] = BDMYSQLI;
$configBDSisduanKor['server'] = "192.168.15.3";
$configBDSisduanKor['bd'] = "aduanas_kor";
$configBDSisduanKor['login'] = "root";
$configBDSisduanKor['password'] = "";

$configBDSisduanInu['tipoBD'] = BDMYSQLI;
$configBDSisduanInu['server'] = "192.168.15.3";
$configBDSisduanInu['bd'] = "aduanas_inu";
$configBDSisduanInu['login'] = "root";
$configBDSisduanInu['password'] = "";

$configBDSisduanUsa['tipoBD'] = BDMYSQLI;
$configBDSisduanUsa['server'] = "192.168.15.3";
$configBDSisduanUsa['bd'] = "aduanas_usa";
$configBDSisduanUsa['login'] = "root";
$configBDSisduanUsa['password'] = "";

$configBDSisduanJpn['tipoBD'] = BDMYSQLI;
$configBDSisduanJpn['server'] = "192.168.15.3";
$configBDSisduanJpn['bd'] = "aduanas_jpn";
$configBDSisduanJpn['login'] = "root";
$configBDSisduanJpn['password'] = "";

$configBDSisduanBol['tipoBD'] = BDMYSQLI;
$configBDSisduanBol['server'] = "192.168.15.3";
$configBDSisduanBol['bd'] = "aduanas_bol";
$configBDSisduanBol['login'] = "root";
$configBDSisduanBol['password'] = "";

$configBDSisduanHnd['tipoBD'] = BDMYSQLI;
$configBDSisduanHnd['server'] = "192.168.15.3";
$configBDSisduanHnd['bd'] = "aduanas_hnd";
$configBDSisduanHnd['login'] = "root";
$configBDSisduanHnd['password'] = "";

$configBDSisduanGtm['tipoBD'] = BDMYSQLI;
$configBDSisduanGtm['server'] = "192.168.15.3";
$configBDSisduanGtm['bd'] = "aduanas_gtm";
$configBDSisduanGtm['login'] = "root";
$configBDSisduanGtm['password'] = "";

$configBDSisduanFra['tipoBD'] = BDMYSQLI;
$configBDSisduanFra['server'] = "192.168.15.3";
$configBDSisduanFra['bd'] = "aduanas_fra";
$configBDSisduanFra['login'] = "root";
$configBDSisduanFra['password'] = "";

$configBDSisduanDeu['tipoBD'] = BDMYSQLI;
$configBDSisduanDeu['server'] = "192.168.15.3";
$configBDSisduanDeu['bd'] = "aduanas_deu";
$configBDSisduanDeu['login'] = "root";
$configBDSisduanDeu['password'] = "";


/*****************union europea****************************/

$configBDAduanas_fi['tipoBD'] = BDMYSQLI;
$configBDAduanas_fi['server'] = "192.168.15.3";
$configBDAduanas_fi['bd'] = "aduanas_fi";
$configBDAduanas_fi['login'] = "root";
$configBDAduanas_fi['password'] = "";

$configBDAduanas_fr['tipoBD'] = BDMYSQLI;
$configBDAduanas_fr['server'] = "192.168.15.3";
$configBDAduanas_fr['bd'] = "aduanas_fr";
$configBDAduanas_fr['login'] = "root";
$configBDAduanas_fr['password'] = "";

$configBDAduanas_gb['tipoBD'] = BDMYSQLI;
$configBDAduanas_gb['server'] = "192.168.15.3";
$configBDAduanas_gb['bd'] = "aduanas_gb";
$configBDAduanas_gb['login'] = "root";
$configBDAduanas_gb['password'] = "";

$configBDAduanas_gr['tipoBD'] = BDMYSQLI;
$configBDAduanas_gr['server'] = "192.168.15.3";
$configBDAduanas_gr['bd'] = "aduanas_gr";
$configBDAduanas_gr['login'] = "root";
$configBDAduanas_gr['password'] = "";

$configBDAduanas_hu['tipoBD'] = BDMYSQLI;
$configBDAduanas_hu['server'] = "192.168.15.3";
$configBDAduanas_hu['bd'] = "aduanas_hu";
$configBDAduanas_hu['login'] = "root";
$configBDAduanas_hu['password'] = "";

$configBDAduanas_ie['tipoBD'] = BDMYSQLI;
$configBDAduanas_ie['server'] = "192.168.15.3";
$configBDAduanas_ie['bd'] = "aduanas_ie";
$configBDAduanas_ie['login'] = "root";
$configBDAduanas_ie['password'] = "";

$configBDAduanas_it['tipoBD'] = BDMYSQLI;
$configBDAduanas_it['server'] = "192.168.15.3";
$configBDAduanas_it['bd'] = "aduanas_it";
$configBDAduanas_it['login'] = "root";
$configBDAduanas_it['password'] = "";

$configBDAduanas_li['tipoBD'] = BDMYSQLI;
$configBDAduanas_li['server'] = "192.168.15.3";
$configBDAduanas_li['bd'] = "aduanas_li";
$configBDAduanas_li['login'] = "root";
$configBDAduanas_li['password'] = "";

$configBDAduanas_lt['tipoBD'] = BDMYSQLI;
$configBDAduanas_lt['server'] = "192.168.15.3";
$configBDAduanas_lt['bd'] = "aduanas_lt";
$configBDAduanas_lt['login'] = "root";
$configBDAduanas_lt['password'] = "";

$configBDAduanas_lu['tipoBD'] = BDMYSQLI;
$configBDAduanas_lu['server'] = "192.168.15.3";
$configBDAduanas_lu['bd'] = "aduanas_lu";
$configBDAduanas_lu['login'] = "root";
$configBDAduanas_lu['password'] = "";

$configBDAduanas_mt['tipoBD'] = BDMYSQLI;
$configBDAduanas_mt['server'] = "192.168.15.3";
$configBDAduanas_mt['bd'] = "aduanas_mt";
$configBDAduanas_mt['login'] = "root";
$configBDAduanas_mt['password'] = "";

$configBDAduanas_nl['tipoBD'] = BDMYSQLI;
$configBDAduanas_nl['server'] = "192.168.15.3";
$configBDAduanas_nl['bd'] = "aduanas_nl";
$configBDAduanas_nl['login'] = "root";
$configBDAduanas_nl['password'] = "";

$configBDAduanas_pl['tipoBD'] = BDMYSQLI;
$configBDAduanas_pl['server'] = "192.168.15.3";
$configBDAduanas_pl['bd'] = "aduanas_pl";
$configBDAduanas_pl['login'] = "root";
$configBDAduanas_pl['password'] = "";

$configBDAduanas_pt['tipoBD'] = BDMYSQLI;
$configBDAduanas_pt['server'] = "192.168.15.3";
$configBDAduanas_pt['bd'] = "aduanas_pt";
$configBDAduanas_pt['login'] = "root";
$configBDAduanas_pt['password'] = "";

$configBDAduanas_ro['tipoBD'] = BDMYSQLI;
$configBDAduanas_ro['server'] = "192.168.15.3";
$configBDAduanas_ro['bd'] = "aduanas_ro";
$configBDAduanas_ro['login'] = "root";
$configBDAduanas_ro['password'] = "";

$configBDAduanas_se['tipoBD'] = BDMYSQLI;
$configBDAduanas_se['server'] = "192.168.15.3";
$configBDAduanas_se['bd'] = "aduanas_se";
$configBDAduanas_se['login'] = "root";
$configBDAduanas_se['password'] = "";

$configBDAduanas_at['tipoBD'] = BDMYSQLI;
$configBDAduanas_at['server'] = "192.168.15.3";
$configBDAduanas_at['bd'] = "aduanas_at";
$configBDAduanas_at['login'] = "root";
$configBDAduanas_at['password'] = "";

$configBDAduanas_be['tipoBD'] = BDMYSQLI;
$configBDAduanas_be['server'] = "192.168.15.3";
$configBDAduanas_be['bd'] = "aduanas_be";
$configBDAduanas_be['login'] = "root";
$configBDAduanas_be['password'] = "";

$configBDAduanas_bg['tipoBD'] = BDMYSQLI;
$configBDAduanas_bg['server'] = "192.168.15.3";
$configBDAduanas_bg['bd'] = "aduanas_bg";
$configBDAduanas_bg['login'] = "root";
$configBDAduanas_bg['password'] = "";

$configBDAduanas_cs['tipoBD'] = BDMYSQLI;
$configBDAduanas_cs['server'] = "192.168.15.3";
$configBDAduanas_cs['bd'] = "aduanas_cs";
$configBDAduanas_cs['login'] = "root";
$configBDAduanas_cs['password'] = "";

$configBDAduanas_cy['tipoBD'] = BDMYSQLI;
$configBDAduanas_cy['server'] = "192.168.15.3";
$configBDAduanas_cy['bd'] = "aduanas_cy";
$configBDAduanas_cy['login'] = "root";
$configBDAduanas_cy['password'] = "";

$configBDAduanas_de['tipoBD'] = BDMYSQLI;
$configBDAduanas_de['server'] = "192.168.15.3";
$configBDAduanas_de['bd'] = "aduanas_de";
$configBDAduanas_de['login'] = "root";
$configBDAduanas_de['password'] = "";

$configBDAduanas_dk['tipoBD'] = BDMYSQLI;
$configBDAduanas_dk['server'] = "192.168.15.3";
$configBDAduanas_dk['bd'] = "aduanas_dk";
$configBDAduanas_dk['login'] = "root";
$configBDAduanas_dk['password'] = "";

$configBDAduanas_el['tipoBD'] = BDMYSQLI;
$configBDAduanas_el['server'] = "192.168.15.3";
$configBDAduanas_el['bd'] = "aduanas_el";
$configBDAduanas_el['login'] = "root";
$configBDAduanas_el['password'] = "";

$configBDAduanas_en['tipoBD'] = BDMYSQLI;
$configBDAduanas_en['server'] = "192.168.15.3";
$configBDAduanas_en['bd'] = "aduanas_en";
$configBDAduanas_en['login'] = "root";
$configBDAduanas_en['password'] = "";

$configBDAduanas_es['tipoBD'] = BDMYSQLI;
$configBDAduanas_es['server'] = "192.168.15.3";
$configBDAduanas_es['bd'] = "aduanas_es";
$configBDAduanas_es['login'] = "root";
$configBDAduanas_es['password'] = "";

$configBDAduanas_ev['tipoBD'] = BDMYSQLI;
$configBDAduanas_ev['server'] = "192.168.15.3";
$configBDAduanas_ev['bd'] = "aduanas_ev";
$configBDAduanas_ev['login'] = "root";
$configBDAduanas_ev['password'] = "";

$configManifiestos['tipoBD'] = BDMYSQLI;
$configManifiestos['server'] = "192.168.15.3";
$configManifiestos['bd'] = "manifiestos";
$configManifiestos['login'] = "root";
$configManifiestos['password'] = "";

$configDiclogistica['tipoBD'] = BDMYSQLI;
$configDiclogistica['server'] = "192.168.15.3";
$configDiclogistica['bd'] = "diclogistica";
$configDiclogistica['login'] = "root";
$configDiclogistica['password'] = "";

$configVigente['tipoBD'] = BDMYSQLI;
$configVigente['server'] = "192.168.15.3";
$configVigente['bd'] = "vigente";
$configVigente['login'] = "root";
$configVigente['password'] = "";

$configDiarioscol['tipoBD'] = BDMYSQLI;
$configDiarioscol['server'] = "192.168.15.3";
$configDiarioscol['bd'] = "sismar_col";
$configDiarioscol['login'] = "root";
$configDiarioscol['password'] = "";

$configDiariosusa['tipoBD'] = BDMYSQLI;
$configDiariosusa['server'] = "192.168.15.3";
$configDiariosusa['bd'] = "diariosusa";
$configDiariosusa['login'] = "root";
$configDiariosusa['password'] = "";

$configTransbordos['tipoBD'] = BDMYSQLI;
$configTransbordos['server'] = "192.168.15.3";
$configTransbordos['bd'] = "transbordos";
$configTransbordos['login'] = "root";
$configTransbordos['password'] = "";

$configNewsicex['tipoBD'] = BDMYSQLI;
$configNewsicex['server'] = "192.168.15.3";
$configNewsicex['bd'] = "newsicex";
$configNewsicex['login'] = "root";
$configNewsicex['password'] = "";


$configArancel['tipoBD']   = BDMYSQLI;
$configArancel['server']   = "192.168.15.3";
$configArancel['bd']       = "arancel_aduana";
$configArancel['login']    = "root";
$configArancel['password'] = "";

$configSitt['tipoBD']   = BDMYSQLI;
$configSitt['server']   = "192.168.15.3";
$configSitt['bd']       = "sitt";
$configSitt['login']    = "root";
$configSitt['password'] = "";

$config_bd_worldatatrade['tipoBD']	= BDMYSQLI;
$config_bd_worldatatrade['server']	= "192.168.15.3";
$config_bd_worldatatrade['bd']		= "worldatatrade";
$config_bd_worldatatrade['login']	= "root";
$config_bd_worldatatrade['password']= "";

//$configNewsicex['tipoBD'] = BDMYSQLI;
//$configNewsicex['server'] = "192.168.15.3";
//$configNewsicex['bd'] = "newsicex";
//$configNewsicex['login'] = "root";
//$configNewsicex['password'] = "";

$coneccion['newsicex'] = $configNewsicex;
$coneccion['aduanas_col'] = $configBDSisduanCol;
$coneccion['aduanas_can'] = $configBDSisduanCan;
$coneccion['aduanas_ecu'] = $configBDSisduanEcu;
$coneccion['aduanas_per'] = $configBDSisduanPer;
$coneccion['aduanas_pry'] = $configBDSisduanPry;
$coneccion['aduanas_arg'] = $configBDSisduanArg;
$coneccion['aduanas_cle'] = $configBDSisduanCle;
$coneccion['aduanas_uru'] = $configBDSisduanUru;
$coneccion['aduanas_esp'] = $configBDSisduanEsp;
$coneccion['aduanas_bra'] = $configBDSisduanBra;
$coneccion['aduanas_cri'] = $configBDSisduanCri;
$coneccion['aduanas_cri_new'] = $configBDSisduanCriNew;
$coneccion['aduanas_ven'] = $configBDSisduanVen;
$coneccion['aduanas_pan'] = $configBDSisduanPan;
$coneccion['aduanas_mex'] = $configBDSisduanMex;
$coneccion['aduanas_ing'] = $configBDSisduanIng;
$coneccion['aduanas_slv'] = $configBDSisduanSlv;
$coneccion['aduanas_ind'] = $configBDSisduanInd;
$coneccion['aduanas_idn'] = $configBDSisduanIdn;
$coneccion['aduanas_tha'] = $configBDSisduanTha;
$coneccion['aduanas_kor'] = $configBDSisduanKor;
$coneccion['aduanas_inu'] = $configBDSisduanInu;
$coneccion['aduanas_usa'] = $configBDSisduanUsa;
$coneccion['aduanas_jpn'] = $configBDSisduanJpn;
$coneccion['aduanas_bol'] = $configBDSisduanBol;
$coneccion['aduanas_hnd'] = $configBDSisduanHnd;
$coneccion['aduanas_gtm'] = $configBDSisduanGtm;
$coneccion['aduanas_arg_new'] = $configBDSisduanArgNew;
$coneccion['aduanas_deu'] = $configBDSisduanDeu;
$coneccion['aduanas_fra'] = $configBDSisduanFra;
$coneccion['aduanas_fi'] = $configBDAduanas_fi;
$coneccion['aduanas_fr'] = $configBDAduanas_fr;
$coneccion['aduanas_gb'] = $configBDAduanas_gb;
$coneccion['aduanas_gr'] = $configBDAduanas_gr;
$coneccion['aduanas_hu'] = $configBDAduanas_hu;
$coneccion['aduanas_ie'] = $configBDAduanas_ie;
$coneccion['aduanas_it'] = $configBDAduanas_it;
$coneccion['aduanas_li'] = $configBDAduanas_li;
$coneccion['aduanas_lt'] = $configBDAduanas_lt;
$coneccion['aduanas_lu'] = $configBDAduanas_lu;
$coneccion['aduanas_mt'] = $configBDAduanas_mt;
$coneccion['aduanas_nl'] = $configBDAduanas_nl;
$coneccion['aduanas_pl'] = $configBDAduanas_pl;
$coneccion['aduanas_pt'] = $configBDAduanas_pt;
$coneccion['aduanas_ro'] = $configBDAduanas_ro;
$coneccion['aduanas_se'] = $configBDAduanas_se;
$coneccion['aduanas_at'] = $configBDAduanas_at;
$coneccion['aduanas_be'] = $configBDAduanas_be;
$coneccion['aduanas_bg'] = $configBDAduanas_bg;
$coneccion['aduanas_cs'] = $configBDAduanas_cs;
$coneccion['aduanas_cy'] = $configBDAduanas_cy;
$coneccion['aduanas_de'] = $configBDAduanas_de;
$coneccion['aduanas_dk'] = $configBDAduanas_dk;
$coneccion['aduanas_el'] = $configBDAduanas_el;
$coneccion['aduanas_en'] = $configBDAduanas_en;
$coneccion['aduanas_es'] = $configBDAduanas_es;
$coneccion['aduanas_ev'] = $configBDAduanas_ev;


$coneccion['portal'] = $configBDPortal;
$coneccion['multipais'] = $configBDMP;
$coneccion['currency'] = $configCurrency;
$coneccion['sicex_r'] = $config;
$coneccion['manifiestos'] = $configManifiestos;
$coneccion['diglogistica'] = $configDiclogistica;
$coneccion['transbordos'] = $configTransbordos;
$coneccion['sismar_col'] = $configDiarioscol;
$coneccion['diariosusa'] = $configDiariosusa;
$coneccion['vigente'] = $configVigente;
$coneccion[''] = $configBDMP;
$coneccion['arancel_aduana'] = $configArancel;
$coneccion['sitt'] = $configSitt;
$coneccion['muestrasSeo'] = $configMuestrasSeo;
$coneccion['marketing'] = $configMarketing;
$coneccion['cfg_sisduan'] = $configCfgSisduan;
$coneccion['worldatatrade'] = $config_bd_worldatatrade;

//Id Perfil WEB
define(IDWEBPERFIL, 4);

//Opciones del menú del footer
$menu_footer[_HOME] = 'index.php';
$menu_footer[_NOTICIAS] = 'contenido.php?ac=304&menu=26';
$menu_footer[_CONTACTENOS] = 'contactenos.php';
$menu_footer[_CORREO] = 'https://www.royal-tec.com/webmail/src/login.php';

//No se puede cambiar el orden ya que afectaría al javascript del login y password
$arr_productos_login['multipais'] = _SICEX; // Indice 0
//$arr_productos_login['diario/validar_ingreso.php?ir_a=reporte1.php'] = _DIARIOPUERTOS; // Indice 1
//$arr_productos_login['sisduanv2/validar_ingreso.php?ir_a=reporte.php'] = _SISDUANONLINE; // Indice 2
$arr_productos_login['arancel/validar_ingreso.php'] = _ARANCELONLINE; // Indice 3
//$arr_productos_login['diariodemo/validar_ingreso.php?ir_a=reporte1.php'] = _DIARIOPUERTOSDEMO; // Indice 4
//$arr_productos_login['sisduanv2demo/validar_ingreso.php?ir_a=reporte.php'] = _SISDUANONLINEDEMO; // Indice 5
$arr_productos_login['newsitt/validar_ingreso.php'] = _SITT; // Indice 6

//Configuración para conexión por defecto de lib_db
$configBD = $config;

//Hack para recibir las variables que son enviados por POST o GET
foreach($_POST as $var => $val)
{
    $$var = $val;
}
foreach($_GET as $var => $val)
{
    $$var = $val;
}

//Acciones permitidas en el sistema
define("FORGOT", 300);      //recordar password
define("ADD", 301);         //Adicionar
define("DEL", 302);         //Borrar
define("EDIT", 303);        //Editar
define("LISTAR", 304);      //Listar
define("LOGOUT", 305);      //Logout
define("LOGIN", 306);       //Login
define("DELCHECKBOX", 307); //CheckBox
define("CANCEL", 308);      //Cancelar
define("ACEPTADD", 309);    //Aceptar Adicionar
define("ACEPTEDIT", 310);   //Aceptar Editar
define("EXCEL", 311);       //Excel
define("COPY", 312);        //Copiar

$acciones[LISTAR] = 'Reporte en pantalla';
$acciones[EXCEL] = 'Excel';
$acciones[LOGIN] = 'Login';
$acciones[LOGOUT] = 'Logout';

//$ac es la variable que contendrá este valor,
//se debe usar de la siguiente manera $ac = ADD;

//Cantidad de registros a mostrar por pantalla
define("MAXREG", 30);


//Intercambio
$_intercambio[0] = _IMPORTACION;
$_intercambio[1] = _EXPORTACION;

//Rangos
$rango['='] = "=";
$rango['>='] = "&gt;=";
$rango['>'] = "&gt;";
$rango['<='] = "&lt;=";
$rango['<'] = "&lt;";

//Años disponibles en la BD
//$_ano[2004] = 2004;
//$_ano[2005] = 2005;
$_ano[2006] = 2006;
$_ano[2007] = 2007;
$_ano[2008] = 2008;
$_ano[2009] = 2009;
$_ano[2010] = 2010;
$_ano[2011] = 2011;
$_ano[2012] = 2012;
$_anoDiarios[2009] = 2009;
$_anoDiarios[2010] = 2010;
$_anoDiarios[2011] = 2011;
$_anoDiarios[2012] = 2012;
//Periodo
$_periodo[1] = _ENERO;
$_periodo[2] = _FEBRERO;
$_periodo[3] = _MARZO;
$_periodo[4] = _ABRIL;
$_periodo[5] = _MAYO;
$_periodo[6] = _JUNIO;
$_periodo[7] = _JULIO;
$_periodo[8] = _AGOSTO;
$_periodo[9] = _SEPTIEMBRE;
$_periodo[10] = _OCTUBRE;
$_periodo[11] = _NOVIEMBRE;
$_periodo[12] = _DICIEMBRE;

//Reportes
$_reportespais[0]["./reporte_pais_empresa.php"] = _REPORTEPAISIMP1;
$_reportespais[0]["./reporte_pais_posicion.php"] = _REPORTEPAISIMP2;
$_reportespais[0]["./path/rep3"] = _REPORTEPAISIMP3;
$_reportespais[1]["./path/rep4"] = _REPORTEPAISEXP1;
$_reportespais[1]["./path/rep5"] = _REPORTEPAISEXP2;
$_reportespais[1]["./path/rep6"] = _REPORTEPAISEXP3;

$_reportesmulti[0]["./path/rep7"] = _REPORTEMULTIIMP1;
$_reportesmulti[0]["./path/rep8"] = _REPORTEMULTIIMP2;
$_reportesmulti[0]["./path/rep9"] = _REPORTEMULTIIMP3;
$_reportesmulti[0]["./path/rep10"] = _REPORTEMULTIIMP4;
$_reportesmulti[0]["./path/rep11"] = _REPORTEMULTIIMP5;
$_reportesmulti[0]["./path/rep12"] = _REPORTEMULTIIMP6;
$_reportesmulti[0]["./path/rep13"] = _REPORTEMULTIIMP7;
$_reportesmulti[0]["./path/rep14"] = _REPORTEMULTIIMP8;

$_reportesmulti[1]["./path/rep15"] = _REPORTEMULTIEXP1;
$_reportesmulti[1]["./path/rep16"] = _REPORTEMULTIEXP2;
$_reportesmulti[1]["./path/rep17"] = _REPORTEMULTIEXP3;
$_reportesmulti[1]["./path/rep18"] = _REPORTEMULTIEXP4;
$_reportesmulti[1]["./path/rep19"] = _REPORTEMULTIEXP5;

//Orden de los filtros
$_ordenfiltros[] = "pais_id";
$_ordenfiltros[] = "intercambio";
$_ordenfiltros[] = "anio";
$_ordenfiltros[] = "periododesde";
$_ordenfiltros[] = "periodohasta";
$_ordenfiltros[] = "banco";
$_ordenfiltros[] = "sucursal";
$_ordenfiltros[] = "id_deposito";
$_ordenfiltros[] = "nomconsignador";
$_ordenfiltros[] = "nit";
$_ordenfiltros[] = "proveedor";
$_ordenfiltros[] = "posicion";
$_ordenfiltros[] = "codarmonizado";
$_ordenfiltros[] = "depositoaduanero";
$_ordenfiltros[] = "transportador";
$_ordenfiltros[] = "via";
$_ordenfiltros[] = "deptorigen";
$_ordenfiltros[] = "deptodestino";
$_ordenfiltros[] = "deptodom";
$_ordenfiltros[] = "paisorigen";
$_ordenfiltros[] = "paisdestino";
$_ordenfiltros[] = "paisprocedencia";
$_ordenfiltros[] = "paiscompra";
$_ordenfiltros[] = "ciudadingreso";
$_ordenfiltros[] = "ciudadsalida";
$_ordenfiltros[] = "ciudaddom";
$_ordenfiltros[] = "ciiu";
$_ordenfiltros[] = "cuode";
$_ordenfiltros[] = "sia";
$_ordenfiltros[] = "formapagoimp";
$_ordenfiltros[] = "formapagoexp";
$_ordenfiltros[] = "numdeclara";
$_ordenfiltros[] = "aduana";
$_ordenfiltros[] = "aduanas";
$_ordenfiltros[] = "documentotransporte";
$_ordenfiltros[] = "num_manifiesto";
$_ordenfiltros[] = "pesonetoini";
$_ordenfiltros[] = "pesobrutoini";
$_ordenfiltros[] = "valorfobini";
$_ordenfiltros[] = "valorcifini";
$_ordenfiltros[] = "valaduanaini";
$_ordenfiltros[] = "nomconsignatario";
$_ordenfiltros[] = "paispartida";
$_ordenfiltros[] = "nompuertopartida";
$_ordenfiltros[] = "paisembarque";
$_ordenfiltros[] = "paisdesembarque";
$_ordenfiltros[] = "nomtransportador";
$_ordenfiltros[] = "tipodoctransporte";

/*
 * Formulario contáctenos
 */
//Arreglo de tipos de contacto
$_arrTipoContacto[_SOLICITUDINFORMACION] = _SOLICITUDINFORMACION;
$_arrTipoContacto[_SUGERENCIA] = _SUGERENCIA;
$_arrTipoContacto[_PROPUESTA] = _PROPUESTA;
$_arrTipoContacto[_RECLAMO] = _RECLAMO;

//Arreglo de Productos
$_arrProducto[''] = _SELECCIONE;
$_arrProducto[_SISMAR] = _SISMAR;
$_arrProducto[_DIARIOPUERTOS] = _DIARIOPUERTOS;
$_arrProducto[_SISDUANONLINE] = _SISDUANONLINE;
$_arrProducto['optgroup']['label'] = _SITT;
$_arrGroup[_BOLETINTERRESTRE] = _BOLETINTERRESTRE;
$_arrGroup[_ESTADISTICASCARGA] = _ESTADISTICASCARGA;
$_arrGroup[_OPORTUNIDADESCARGA] = _OPORTUNIDADESCARGA;
$_arrGroup[_BOLSATRANSPORTE] = _BOLSATRANSPORTE;
$_arrProducto['optgroup']['options'] = $_arrGroup;
$_arrProducto[_MULTIPAIS] = _MULTIPAIS;
$_arrProducto[_ARANCEL] = _ARANCEL;

/*MULTIPAIS campos*/
//{campo: 'pais_nombre', nombre: 'País'},

$camposImp[] = Array('campo'=>'DATE_FORMAT(decl.fecha, "%Y-%m")', 'alias'=>'periodo', 'nombre'=>_TABFECHA, 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.aduana', 'nombre'=>_TABADUANA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.via', 'nombre'=>_TABVIA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.nit', 'nombre'=>_TABNIT, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.razon', 'nombre'=>_TABRAZONSOCIAL, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.direccion', 'nombre'=>_TABDIRECCION, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.ciudad', 'nombre'=>_TABCIUDAD, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.subpartida', 'nombre'=>_TABCODARMONIZADO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.desc_subpartida', 'nombre'=>_TABDESCRIPCION, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.banco', 'nombre'=>_TABBANCO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>"decl.unidadcom", 'nombre'=>_TABUNIDADCOMERCIAL, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>"decl.flete", 'nombre'=>_TABFLETE, 'alias'=>'', 'tipo'=> 'n', 'groupby'=>0);
$camposImp[] = Array('campo'=>"decl.num_manifiesto", 'nombre'=>_TABNUMEROMANIFIESTO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.paisorigen', 'nombre'=>_TABPAISORIGEN, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>"decl.paiscompra", 'nombre'=>_TABPAISCOMPRA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>"decl.valorunimp", 'nombre'=>_TABVALUNIMP, 'alias'=>'valorunimp', 'tipo'=> 'n', 'groupby'=>1);
$camposImp[] = Array('campo'=>"decl.cantidad", 'nombre'=>_TABCANTIDAD, 'alias'=>'cantidad', 'tipo'=> 'n', 'groupby'=>0);
$camposImp[] = Array('campo'=>"decl.peso_neto", 'nombre'=>_TABPESONETO, 'alias'=>'peso_neto', 'tipo'=> 'n', 'groupby'=>0);
$camposImp[] = Array('campo'=>"decl.peso_bruto", 'nombre'=>_TABPESOBRUTO, 'alias'=>'peso_bruto', 'tipo'=> 'n', 'groupby'=>0);
$camposImp[] = Array('campo'=>"decl.valorfobimp", 'nombre'=>_TABVALORFOB, 'alias'=>'valorfobimp', 'tipo'=> 'n', 'groupby'=>0);
$camposImp[] = Array('campo'=>"decl.valorcifimp", 'nombre'=>_TABVALORCIF, 'alias'=>'valorcifimp', 'tipo'=> 'n', 'groupby'=>0);
$camposImp[] = Array('campo'=>"decl.val_aduana", 'nombre'=>_TABVALADUANA, 'alias'=>'val_aduana', 'tipo'=> 'n', 'groupby'=>0);
/* //Campos originales no borrar
$camposImp[] = Array('campo'=>'DATE_FORMAT(decl.fecha, "%Y-%m")', 'alias'=>'periodo', 'nombre'=>_TABFECHA, 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.aduana', 'nombre'=>_TABADUANA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.via', 'nombre'=>_TABVIA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.nit', 'nombre'=>_TABNIT, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.razon', 'nombre'=>_TABRAZONSOCIAL, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.direccion', 'nombre'=>_TABDIRECCION, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.ciudad', 'nombre'=>_TABCIUDAD, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.subpartida', 'nombre'=>_TABCODARMONIZADO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.desc_subpartida', 'nombre'=>_TABDESCRIPCION, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.banco', 'nombre'=>_TABBANCO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>"decl.unidadcom", 'nombre'=>_TABUNIDADCOMERCIAL, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>"decl.flete", 'nombre'=>_TABFLETE, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>"decl.num_manifiesto", 'nombre'=>_TABNUMEROMANIFIESTO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>'decl.paisorigen', 'nombre'=>_TABPAISORIGEN, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>"decl.paiscompra", 'nombre'=>_TABPAISCOMPRA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposImp[] = Array('campo'=>"decl.valorunimp", 'nombre'=>_TABVALUNIMP, 'alias'=>'valorunimp', 'tipo'=> 'n', 'groupby'=>1);
$camposImp[] = Array('campo'=>"SUM(decl.cantidad)", 'nombre'=>_TABCANTIDAD, 'alias'=>'cantidad', 'tipo'=> 'n', 'groupby'=>0);
$camposImp[] = Array('campo'=>"SUM(decl.peso_neto)", 'nombre'=>_TABPESONETO, 'alias'=>'peso_neto', 'tipo'=> 'n', 'groupby'=>0);
$camposImp[] = Array('campo'=>"SUM(decl.peso_bruto)", 'nombre'=>_TABPESOBRUTO, 'alias'=>'peso_bruto', 'tipo'=> 'n', 'groupby'=>0);
$camposImp[] = Array('campo'=>"SUM(decl.valorfobimp)", 'nombre'=>_TABVALORFOB, 'alias'=>'valorfobimp', 'tipo'=> 'n', 'groupby'=>0);
$camposImp[] = Array('campo'=>"SUM(decl.valorcifimp)", 'nombre'=>_TABVALORCIF, 'alias'=>'valorcifimp', 'tipo'=> 'n', 'groupby'=>0);
$camposImp[] = Array('campo'=>"SUM(decl.val_aduana)", 'nombre'=>_TABVALADUANA, 'alias'=>'val_aduana', 'tipo'=> 'n', 'groupby'=>0);
*/

//PENDIENTE EXPO
//$camposExp[] = Array('campo'=>'decl.id_pais', 'nombre'=>_TABPAIS, 'alias'=>'', 'groupby'=>1);
$camposExp[] = Array('campo'=>'DATE_FORMAT(decl.fecha, "%Y-%m")', 'alias'=>'periodo', 'nombre'=>_TABFECHA, 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.aduana', 'nombre'=>_TABADUANA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.via', 'nombre'=>_TABVIA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.nit', 'nombre'=>_TABNIT, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.razon', 'nombre'=>_TABRAZONSOCIAL, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.direccion', 'nombre'=>_TABDIRECCION, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.ciudad', 'nombre'=>_TABCIUDAD, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.subpartida', 'nombre'=>_TABCODARMONIZADO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.desc_subpartida', 'nombre'=>_TABDESCRIPCION, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.unidadcom", 'nombre'=>_TABUNIDADCOMERCIAL, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.flete", 'nombre'=>_TABFLETE, 'alias'=>'', 'tipo'=> 'n', 'groupby'=>0);
$camposExp[] = Array('campo'=>"decl.num_manifiesto", 'nombre'=>_TABNUMEROMANIFIESTO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.paisdestino", 'nombre'=>_TABPAISDESTINO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.valorunexp", 'nombre'=>_TABVALUNIMP, 'alias'=>'valorunexp', 'tipo'=> 'n', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.cantidad", 'nombre'=>_TABCANTIDAD, 'alias'=>'cantidad', 'tipo'=> 'n', 'groupby'=>0);
$camposExp[] = Array('campo'=>"decl.peso_neto", 'nombre'=>_TABPESONETO, 'alias'=>'peso_neto', 'tipo'=> 'n', 'groupby'=>0);
$camposExp[] = Array('campo'=>"decl.peso_bruto", 'nombre'=>_TABPESOBRUTO, 'alias'=>'peso_bruto', 'tipo'=> 'n', 'groupby'=>0);
$camposExp[] = Array('campo'=>"decl.valorfobexp", 'nombre'=>_TABVALORFOB, 'alias'=>'valorfobexp', 'tipo'=> 'n', 'groupby'=>0);
$camposExp[] = Array('campo'=>"decl.valorcifexp", 'nombre'=>_TABVALORCIF, 'alias'=>'valorcifexp', 'tipo'=> 'n', 'groupby'=>0);
/*
$camposExp[] = Array('campo'=>'DATE_FORMAT(decl.fecha, "%Y-%m")', 'alias'=>'periodo',  'nombre'=>_TABFECHA, 'tipo'=> 's','groupby'=>1);
$camposExp[] = Array('campo'=>'decl.aduana', 'nombre'=>_TABADUANA, 'alias'=>'', 'tipo'=> 's','groupby'=>1);
$camposExp[] = Array('campo'=>'decl.via', 'nombre'=>_TABVIA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.nit', 'nombre'=>_TABNIT, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.razon', 'nombre'=>_TABRAZONSOCIAL, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.direccion', 'nombre'=>_TABDIRECCION, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.posicion', 'nombre'=>_TABCODARMONIZADO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.descripcion', 'nombre'=>_TABDESCRIPCION, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>'decl.paisorigen', 'nombre'=>_TABPAISORIGEN, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
//$camposExp[] = Array('campo'=>'decl.sia', 'nombre'=>_TABSIA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.banco", 'nombre'=>_TABBANCO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.prove", 'nombre'=>_TABPROVEEDOR, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.paiscompra", 'nombre'=>_TABPAISCOMPRA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.paisprocedencia", 'nombre'=>_TABPAISPROCENDENCIA, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.fecha_manifiesto", 'nombre'=>_TABFECHAMANIFIESTO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.num_manifiesto", 'nombre'=>_TABNUMEROMANIFIESTO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.ciudad_ing", 'nombre'=>_TABCIUDADINGRESO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.descripcion_prod", 'nombre'=>_TABDESCRIPCIONPRODUCTO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.cantidad", 'nombre'=>_TABCANTIDAD, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.unidadcom", 'nombre'=>_TABUNIDADCOMERCIAL, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.tasa", 'nombre'=>_TABTASACAMBIO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.forma_pago_imp", 'nombre'=>_TABFORMAPAGO, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"decl.flete", 'nombre'=>_TABFLETE, 'alias'=>'', 'tipo'=> 's', 'groupby'=>1);
$camposExp[] = Array('campo'=>"SUM(decl.cantidad)", 'nombre'=>_TABCANTIDAD, 'alias'=>'cantidad', 'tipo'=> 'n', 'groupby'=>0);
$camposExp[] = Array('campo'=>"SUM(decl.peso_neto)", 'nombre'=>_TABPESONETO, 'alias'=>'peso_neto', 'tipo'=> 'n', 'groupby'=>0);
$camposExp[] = Array('campo'=>"SUM(decl.peso_bruto)", 'nombre'=>_TABPESOBRUTO, 'alias'=>'peso_bruto', 'tipo'=> 'n', 'groupby'=>0);
$camposExp[] = Array('campo'=>"SUM(decl.valorfobexp)", 'nombre'=>_TABVALORFOB, 'alias'=>'valorfobimp', 'tipo'=> 'n', 'groupby'=>0);
$camposExp[] = Array('campo'=>"SUM(decl.valorcifexp)", 'nombre'=>_TABVALORCIF, 'alias'=>'valorcifimp', 'tipo'=> 'n', 'groupby'=>0);
*/
$camposIntercambio[0] = $camposImp;
$camposIntercambio[1] = $camposExp;
/*MULTIPAIS filtros*/
/*
define("FILTRO_INTERCAMBIO", 401);
    define("FILTRO_PAIS", 403);

    define("FILTRO_PERIODO", 402);
    define("FILTRO_ANIO", 404);
    define("FILTRO_PERIODODESDE", 405);
    define("FILTRO_PERIODOHASTA", 406);
    define("FILTRO_ADUANA", 407);
    define("FILTRO_VIA", 408);
    define("FILTRO_RAZONSOCIAL", 410);
    define("FILTRO_PAISORIGEN", 411);
    define("FILTRO_POSICION", 412);
    define("FILTRO_CIIU", 413);
    define("FILTRO_CUODE", 414);
    define("FILTRO_NUMMANIFIESTO", 415);
    define("FILTRO_PESONETO", 416);
    define("FILTRO_PESOBRUTO", 417);
    define("FILTRO_VALORFOB", 418);
    define("FILTRO_VALORCIF", 419);
    define("FILTRO_NIT", 409);

    var filtrosIntercambio = {
                                0 : [
                                        {campo: 'id_pais', filtro: '403', opcional: true},
                                        {campo: 'nit', filtro: '409', opcional: true},
                                        {campo: 'id_aduana', filtro: '407', opcional: true},
                                        {campo: 'id_via', filtro: '408', opcional: true},
                                        {campo: 'id_paisorigen', filtro: '411', opcional: true}
                                    ],
                                1 : [
                                        {campo: 'id_pais', filtro: '403', opcional: true},
                                        {campo: 'nit', filtro: '409', opcional: true}
                                    ]
                            };
*/
$filtrosImp[] = Array('campo'=>"pais_id", 'filtro'=>FILTRO_PAISMULTIPLE, 'opcional'=>0);
$filtrosImp[] = Array('campo'=>"anio", 'filtro'=>FILTRO_ANIO, 'opcional'=>0);
$filtrosImp[] = Array('campo'=>"periododesde", 'filtro'=>FILTRO_PERIODODESDE, 'opcional'=>0);
$filtrosImp[] = Array('campo'=>"periodohasta", 'filtro'=>FILTRO_PERIODOHASTA, 'opcional'=>0);
$filtrosImp[] = Array('campo'=>"codarmonizado", 'filtro'=>FILTRO_CODARMONIZADO, 'opcional'=>0);
$filtrosImp[] = Array('campo'=>"posicion", 'filtro'=>FILTRO_POSICION, 'opcional'=>0);
$filtrosImp[] = Array('campo'=>"nit", 'filtro'=>FILTRO_NIT, 'opcional'=>1);
//$filtrosImp[] = Array('campo'=>"aduana", 'filtro'=>FILTRO_ADUANA, 'opcional'=>1);
$filtrosImp[] = Array('campo'=>"via", 'filtro'=>FILTRO_VIA, 'opcional'=>1);
$filtrosImp[] = Array('campo'=>"paisorigen", 'filtro'=>FILTRO_PAISORIGEN, 'opcional'=>1);
//$filtrosImp[] = Array('campo'=>"ciiu", 'filtro'=>FILTRO_CIIU, 'opcional'=>1);
//$filtrosImp[] = Array('campo'=>"cuode", 'filtro'=>FILTRO_CUODE, 'opcional'=>1);
//$filtrosImp[] = Array('campo'=>"num_manifiesto", 'filtro'=>FILTRO_NUMMANIFIESTO, 'opcional'=>1);
$filtrosImp[] = Array('campo'=>"pesoneto", 'filtro'=>FILTRO_PESONETO, 'opcional'=>1);
$filtrosImp[] = Array('campo'=>"pesobruto", 'filtro'=>FILTRO_PESOBRUTO, 'opcional'=>1);
$filtrosImp[] = Array('campo'=>"valorfob", 'filtro'=>FILTRO_VALORFOB, 'opcional'=>1);
$filtrosImp[] = Array('campo'=>"valorcif", 'filtro'=>FILTRO_VALORCIF, 'opcional'=>1);
$filtrosImp[] = Array('campo'=>"proveedor", 'filtro'=>FILTRO_PROVEEDOR, 'opcional'=>1);

$filtrosExp[] = Array('campo'=>"pais_id", 'filtro'=>FILTRO_PAISMULTIPLE, 'opcional'=>0);
$filtrosExp[] = Array('campo'=>"anio", 'filtro'=>FILTRO_ANIO, 'opcional'=>0);
$filtrosExp[] = Array('campo'=>"periododesde", 'filtro'=>FILTRO_PERIODODESDE, 'opcional'=>0);
$filtrosExp[] = Array('campo'=>"periodohasta", 'filtro'=>FILTRO_PERIODOHASTA, 'opcional'=>0);
$filtrosExp[] = Array('campo'=>"codarmonizado", 'filtro'=>FILTRO_CODARMONIZADO, 'opcional'=>0);
$filtrosExp[] = Array('campo'=>"posicion", 'filtro'=>FILTRO_POSICION, 'opcional'=>0);
$filtrosExp[] = Array('campo'=>"nit", 'filtro'=>FILTRO_NIT, 'opcional'=>1);
//$filtrosExp[] = Array('campo'=>"aduana", 'filtro'=>FILTRO_ADUANA, 'opcional'=>1);
$filtrosExp[] = Array('campo'=>"via", 'filtro'=>FILTRO_VIA, 'opcional'=>1);
$filtrosExp[] = Array('campo'=>"paisdestino", 'filtro'=>FILTRO_PAISDESTINO, 'opcional'=>1);
//$filtrosExp[] = Array('campo'=>"ciiu", 'filtro'=>FILTRO_CIIU, 'opcional'=>1);
//$filtrosExp[] = Array('campo'=>"cuode", 'filtro'=>FILTRO_CUODE, 'opcional'=>1);
//$filtrosExp[] = Array('campo'=>"num_manifiesto", 'filtro'=>FILTRO_NUMMANIFIESTO, 'opcional'=>1);
$filtrosExp[] = Array('campo'=>"pesoneto", 'filtro'=>FILTRO_PESONETO, 'opcional'=>1);
$filtrosExp[] = Array('campo'=>"pesobruto", 'filtro'=>FILTRO_PESOBRUTO, 'opcional'=>1);
$filtrosExp[] = Array('campo'=>"valorfob", 'filtro'=>FILTRO_VALORFOB, 'opcional'=>1);
$filtrosExp[] = Array('campo'=>"valorcif", 'filtro'=>FILTRO_VALORCIF, 'opcional'=>1);

$filtrosIntercambio[0] = $filtrosImp;
$filtrosIntercambio[1] = $filtrosExp;

//Configuracion menu video ayuda
//Español colombia
$helpvideo[1] = array(
  array('url' => 'cm/download/videos_ayuda_ing/import_export_data_1/import_export_data_1.html', 'texto' => _VIDEOHOME1),
  array('url' => 'cm/download/videos_ayuda_ing/import_export_data_2/import_export_data_2.html', 'texto' => _VIDEOHOME2)
);
//Inglés
$helpvideo[4] = array(
  array('url' => 'cm/download/videos_ayuda_ing/import_export_data_1/import_export_data_1.html', 'texto' => _VIDEOHOME1),
  array('url' => 'cm/download/videos_ayuda_ing/import_export_data_2/import_export_data_2.html', 'texto' => _VIDEOHOME2)
);

//Inglés
$helpvideo[2] = array(
  array('url' => 'cm/download/videos_ayuda_ing/import_export_data_1/import_export_data_1.html', 'texto' => _VIDEOHOME1),
  array('url' => 'cm/download/videos_ayuda_ing/import_export_data_2/import_export_data_2.html', 'texto' => _VIDEOHOME2)
);

$helpvideosisduan[1] = array(
  array('url' => 'cm/download/videos_ayuda_sisduan/manual-sisduan.pps', 'texto' => 'Presentación')
);
//Inglés
$helpvideosisduan[4] = array(
   array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_1.html', 'texto' => '	01.   BY SELECTING THE COUNTRIES	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_2.html', 'texto' => '	02.   CREATING THE REPORT	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_3.html', 'texto' => '	03.   SELECTING FILTERS	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_4.html', 'texto' => '	04.   SEVERAL CODES AT A TIME	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_5.html', 'texto' => '	05.   BY PRODUCT NAME	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_6.html', 'texto' => '	06.   BY COMPANY NAME	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_7.html', 'texto' => '	07.   BY ID NUMBER	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_8.html', 'texto' => '	08.   BY SEVERAL FILTERS AT A TIME	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_9.html', 'texto' => '	09.   THE CREATED REPORT	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_10.html', 'texto' => '	10.   USING THE ACCRUED TAB	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_11.html', 'texto' => '	11.   USING THE COMPARATIVE TAB	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_12.html', 'texto' => '	12.   DOWNLOADING EXCEL FORMAT	'),
	array('url' => 'cm/download/videos_ayuda_sisduan/english/HOW_TO_13.html', 'texto' => '	13.   SELECTING EXCEL IN THE HTML FORMAT	')

);
$medio_pag_pol = array("10"=>"VISA","11" => "MASTERCARD", "12" => "AMEX", "22" => "DINERS", "24" => "Verified by VISA", "25" => "PSE", "27" => "VISA Debito", "30" => "Efecty", "31" => "Pago referenciado");

$bd_sindirectorio[0] = array(
	'sisduanbol',
	'sisduanarg_new',
	'sisduanarg',
	'sisduancle',
	'sisduanuru',
	'sisduanesp',
	'sisduanbra',
	'sisduancri',
	'sisduanven',
	'sisduanpan',
	'sisduanmex',
	'sisduaning',
	'sisduanslv',
	'sisduanind',
	'sisduanidn',
	'sisduantha',
	'sisduankor',
	'sisduaninu',
	'sisduanusa',
	'sisduanjpn',
	'sisduanhnd',
	'sisduangtm',
	'sisduanfra',
	'sisduandeu'
);

$bd_sindirectorio[1] = array(
	'sisduanesp',
	'sisduanbra',
	'sisduanbol',
	'sisduanarg_new',
	'sisduancri',
	'sisduanven',
	'sisduanmex',
	'sisduaning',
	'sisduanslv',
	'sisduanind',
	'sisduanidn',
	'sisduantha',
	'sisduankor',
	'sisduaninu',
	'sisduanusa',
	'sisduanjpn',
	'sisduanhnd',
	'sisduangtm',
	'sisduanfra',
	'sisduandeu'
);


$campos_suscriptores = array('dir.email','dir.fax','dir.telefono','dir.direccion');

?>