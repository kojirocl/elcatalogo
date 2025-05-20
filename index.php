<?php

// ../../../bin/php.exe -S 127.0.0.1:3000

$f3 = require('lib/base.php');

$f3->set('SITIO', array(
    'titulo'=>"El Catalogo",
    'nombre'=>"ElCatalogo.cl",
    'url' => $f3->GET('SCHEME').'://'.$f3->GET('HOST').$f3->GET('BASE').'/',
    'favicon' => 'recursos/img/favicon.ico'
));

date_default_timezone_set('America/Santiago');

$f3->config('config/config.ini');

$f3->BD = new DB\SQL($f3->get('BD_DRIVER'));

new DB\SQL\Session($f3->BD,'sessions',TRUE,NULL,'CSRF');


$f3->config('config/rutas.ini');

\Assets::instance();

// Allow from any origin
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

}


$f3->run();

?>