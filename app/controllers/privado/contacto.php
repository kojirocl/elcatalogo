<?php
namespace Privado;

class Contacto extends \Seguridad{
    const pagina = 'privado/templates/contacto.html';
    
    function inicio(){

        $f3 = \Base::instance();
        $f3->set('vista', \Template::instance()->render(self::pagina));

        
    }
}
?>