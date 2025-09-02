<?php

// ../../../bin/php.exe -S 127.0.0.1:3000
$baseDir = dirname(__DIR__);

$f3 = require($baseDir . '/lib/base.php');

$f3->config($baseDir. '/app/config/config.ini');

$f3->set('SITIO', array(
    'titulo'=>"Tu Catalogo",
    'nombre'=>"TuCatalogo.cl",
    'url' => $f3->GET('SCHEME').'://'.$f3->GET('HOST').$f3->GET('BASE').'/',
    'favicon' => 'img/favicon.ico'
));

//date_default_timezone_set('America/Santiago');

$que_bd = 'sqlite';

if($que_bd === 'maria')
    $f3->BD = new DB\SQL('mysql:host=127.0.0.1;port=3306;dbname=elcatalogo','elcatalogo','pistolas'); 

if($que_bd ==='sqlite')
    $f3->BD = new \DB\SQL('sqlite:' .$baseDir . '/app/db/elcatalogo.sqlite');


new  \DB\SQL\Session($f3->BD,'sessions',TRUE,NULL,'CSRF');


$f3->config($baseDir. '/app/rutas/rutas.ini');


\Assets::instance();

/* // Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
    // whitelist of safe domains
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

} */


$f3->run();

?>