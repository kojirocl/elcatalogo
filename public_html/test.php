<?php 


$baseDir = dirname(__DIR__);

$f3 = require($baseDir . '/lib/base.php');

//$f3->set('AUTOLOAD', $baseDir . '/lib/');
//$f3->BD = new \DB\SQL('sqlite:' .$baseDir . '/app/db/elcatalogo.sqlite');

//require($baseDir . '\app\models\mComentarios.php');
require($baseDir . '\app\controllers\publico\general.php');
require($baseDir . '\app\controllers\publico\perfil.php');


$algo = new \Publico\Perfil;

$algo->pruebaLikes();








?>

