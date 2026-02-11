<?php 


$baseDir = dirname(__DIR__);

$f3 = require($baseDir . '/lib/base.php');

//$f3->set('AUTOLOAD', $baseDir . '/lib/');

$condicion = [];

echo empty($condicion);


$condicion [] = "perfil.ciudad = 'Aysén'";

$extra = "";

$where = ["perfil.ciudad = 'Aysén'", $extra];
/*
$condicion [] = "perfil.ciudad = 'Rancagua'";

var_dump($condicion);*/


$x = implode(' AND ', $where);

var_dump(array_filter($where));




?>

