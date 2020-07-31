<?php
require_once("db/db.php");
require_once("router.php");
require_once("helpers/FuncionesComunes.php");
require_once("controllers/Inicio_controller.php");
require_once("controllers/Proyecto_controller.php");
require_once("controllers/Usuario_controller.php");
require_once("controllers/ProyectoLista_controller.php");
require_once("controllers/Persona_controller.php");
require_once("controllers/Familia_controller.php");
require_once("controllers/Reporte_controller.php");

date_default_timezone_set('America/Lima');

$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$requestUri = parse_url('http://example.com' . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
$virtualPath = '/' . ltrim(substr($requestUri, strlen($scriptName)), '/');
$hostName = (stripos($_SERVER['REQUEST_SCHEME'], 'https') === 0 ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];

define('HOST', $hostName);
define('URI', $requestUri);
define('URL_PATH', rtrim($scriptName,'/'));
define('URL',$virtualPath);

session_start();

set_error_handler('exceptions_error_handler');

$router = new Router();
try {
  $conectarBD = new ConectarBD();
	$controller = new $router->controller($conectarBD->GetConexion());
  // $controller->EstablecerConexion($conectarBD->GetConexion());
	$method = $router->method;
	$controller->$method();
} catch (Exception $e) {
  echo 'ErrorIndex |' . $e->getMessage();
	$ipClient='';
	$ipProxy='';
	$ipServer='';

	if (isset($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
  {
    $ipClient=$_SERVER['HTTP_CLIENT_IP'];
  }
  
  if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
  {
    $ipProxy=$_SERVER['HTTP_X_FORWARDED_FOR'];
  }

  if (isset($_SERVER['REMOTE_ADDR']))
  {
    $ipServer=$_SERVER['REMOTE_ADDR'];
  }

  $error = 'PHP Fatal error | URL : '.$_SERVER['REQUEST_URI']."\n".'IP : '.$ipClient.' | '.$ipProxy.' | '.$ipServer."\n".' ERROR index : '. $e->getMessage()."\n".$e->getTraceAsString()."\n\n";
	error_log($error);
}
?>