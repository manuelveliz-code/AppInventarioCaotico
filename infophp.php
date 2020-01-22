<?php
session_name('app_caotico');
if (!isset($_SESSION)) {
	session_start();
}
print_r($_SERVER);
//echo $_SERVER['HTTP_HOST'];
//phpversion();
//phpinfo();


//$url_origen = parse_url($_SERVER['HTTP_REFERER']);
//$url_redireccion = ($url_origen['host'] == 'midominio.com' ? $url_origen['path'] : 'index.php');
//echo $url_origen;
//echo $url_redireccion;
?>
