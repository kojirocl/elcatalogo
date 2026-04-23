<?php
namespace Helpers;

class Menu {

    static function Simple(): array {
        $f3 = \Base::instance();

        $menu['home'] = array('url'=>'/','icono'=>'bi-house');

        if (\Helpers\Logueado::estaLogeado()) {
            $menu['perfil'] = array('url'=>'/perfil','icono'=>'bi-person-circle');
            $menu['logout'] = array('url'=>'/privado/salir','icono'=>'bi-box-arrow-left');

        }else {
            $menu['login'] = array('url'=>'/login','icono'=>'bi-person-circle');
            $menu['registro'] = array('url'=>'/registro','icono'=>'bi-person-plus');
            $menu['validar mail'] = array('url'=>'/validar','icono'=>'bi-person-check');
        }

        $menu['contacto'] = array('url'=>'/contacto','icono'=>'bi-envelope-at');
        
        return $menu;        
            
    }

}