<?php 

$baseDir = dirname(__DIR__);
header('Content-Type: application/json');
$f3 = require($baseDir . '/lib/base.php');

//$f3->BD = new \DB\SQL('sqlite:' .$baseDir . '/app/db/elcatalogo.sqlite');

/* 
include('../app/helpers/elcatalogo.php');
include('../app/helpers/showData.php');
include('../app/helpers/paginacion.php');

        $datos = \Helpers\ShowData::getAll(null, null,null,0);

        $barra_paginacion = \Helpers\Paginacion::barra_paginacion(
            $datos['pagina_actual'],
            $datos['total_paginas'],
            $datos['filtros']
        ); 
*/
/*
default
Valor a devolver en caso de que el filtro falle.
min_range
El valor solo es válido si es mayor o igual que el valor proporcionado.
max_range
El valor solo es válido si es menor o igual que el valor proporcionado.
*/
include('../app/helpers/showData.php');
include('../app/helpers/paginacion.php');


header('Content-Type: application/json');

$filtros = [
    'url1' => 'algo',
    'url2' => 'mas',
    'url3' => 'ultima'

];

echo json_encode([
     'datos' => http_build_query($filtros)
    ]);

exit;
