<?php
namespace Helpers;

class Seguridad{
    const rutaBase= 'privado/layout.html';
    const grupo = 5;

    function beforeRoute(){

        $f3 = \Base::instance();
        
        if (!\Helpers\Elcatalogo::revisaSiConectado()) $f3->reroute('/');

        //if ($f3->get('SESSION.usuario.idGrupo') !== self::grupo) $f3->reroute('/');
        
        
    }

    function afterRoute(){
        $f3 = \Base::instance();
        $assets = \Helpers\Assets::instance();

        $assets->addCss('bootstrap/css/bootstrap.css',5,'head');
        $assets->addCss('css/bootstrap-icons.css');
		$assets->addCss('css/elcatalogo.css',4,'head');
        $assets->addJs('js/vendor/axios.min.js',3,'head');

        $f3->set('items_menu', \Helpers\Elcatalogo::armarMenu());

        echo \Template::instance()->render(self::rutaBase);

    }



}

?>