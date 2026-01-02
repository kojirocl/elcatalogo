<?php

// ../../../bin/php.exe -S 127.0.0.1:3000
$baseDir = dirname(__DIR__);

$f3 = require($baseDir . '/lib/base.php');

$f3->config($baseDir. '/app/config/config.ini');

$f3->set('SITIO', array(
    'titulo'=>"El Catalogo",
    'nombre'=>"TuCatalogo.cl",
    'url' => $f3->GET('SCHEME').'://'.$f3->GET('HOST').$f3->GET('BASE').'/',
    'favicon' => 'img/favicon.ico'
));

//date_default_timezone_set('America/Santiago');
$que_bd = 'sqlite';
//echo 'sqlite:' .$baseDir . '/app/db/elcatalogo.sqlite';
if($que_bd === 'maria')
    $f3->BD = new DB\SQL('mysql:host=127.0.0.1;port=3306;dbname=elcatalogo','elcatalogo','pistolas'); 

if($que_bd ==='sqlite')
    //echo "sqlite:$baseDir/app/db/elcatalogo.sqlite\n";
    $f3->BD = new \DB\SQL('sqlite:' .$baseDir . '/app/db/elcatalogo.sqlite');


new \DB\SQL\Session($f3->BD,'sessions',TRUE,NULL,'CSRF');


$f3->config($baseDir. '/app/rutas/rutas.ini');


\Assets::instance();

$origin = $_SERVER['HTTP_ORIGIN'] ?? "http://{$_SERVER['HTTP_HOST']}";
$allowedOrigins = [
    'http://localhost:8080',
    'http://127.0.0.1:8080'
];

//echo $origin;

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}

/* // Revisar si viene HTTP_ORIGIN
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Vary: Origin"); // importante para caches
}

// Métodos y headers permitidos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Manejo preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
} */


$f3->run();

?>