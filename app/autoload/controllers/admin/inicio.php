<?php
namespace Admin;

class Inicio extends Comun{
    
    function afterRoute(){

    
    }
    function inicio(){
        $f3 = \Base::instance();
        
        $f3->set('contenido', \Template::instance()->render('admin/templates/inicio.html'));
    }
    
    function error(){
        $f3 = \Base::instance();
        
        $f3->set('contenido', \Template::instance()->render('admin/error.html'));
    }
    
}