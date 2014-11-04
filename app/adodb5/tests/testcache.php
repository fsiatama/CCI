<html>
<body>
<?php
/* 
V4.80 8 Mar 2006  (c) 2000-2010 John Lim (jlim#natsoft.com). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence. 
  Set tabs to 4 for best viewing.
	
  Latest version is available at http://adodb.sourceforge.net
*/

$ADODB_CACHE_DIR = dirname(tempnam('/tmp',''));
include("../adodb.inc.php");

if (isset($access)) {
	$db=ADONewConnection('access');
	$db->PConnect('nwind');
} else {
	$db = ADONewConnection('mysql');
	$db->PConnect('localhost','root','','sicex_r');
}
if (isset($cache)) {
	print 123456789;
	$rs = $db->CacheExecute(120,'select * from usuario');
	
}
else $rs = $db->Execute('select * from usuario');

$arr = $rs->GetArray();
var_dump($arr);
?>