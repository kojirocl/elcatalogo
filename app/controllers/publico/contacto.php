<?php
namespace Publico;

class Contacto{
    const rutaBase = 'frontend/default.html';
    const ruta = 'frontend/templates/contacto.html';

    function inicio(){
        echo \Template::instance()->render(self::ruta);

        exit();

    }

}



?>