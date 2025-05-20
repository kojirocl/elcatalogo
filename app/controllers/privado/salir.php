<?php
namespace Privado;

class Salir extends \Seguridad{

    function inicio(){
        $f3=\Base::instance();

        $f3->clear('SESSION');
        $f3->reroute("/");
        exit();
        
    }
}