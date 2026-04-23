<?php
namespace Helpers;

class Logueado {

    public static function estaLogeado(): bool {
        $f3 = \Base::instance();

        // Verifica si existe la sesión del usuario
        if (!$f3->exists('SESSION.usuario')) return false;

        return true;
            
    }


}



?>