<?php
namespace Privado;

class Inicio extends \Seguridad{
    //const inicio = 'privado/templates/indicadores.html';
    

    function inicio(){

        $f3 = \Base::instance();
    
        $f3->reroute('/privado/indicadores');

 }
   
}

?>