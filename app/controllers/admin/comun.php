<?php
namespace Admin;

class Comun{
    const rutaBase = 'admin/layout.html';
    const grupo = 3; // Grupo de administradores

    function beforeRoute(){
        $f3 = \Base::instance();
      
        if (!\Elcatalogo::revisaSiConectado()) $f3->reroute('/');
        if ($f3->get('SESSION.usuario.grupo') != self::grupo) $f3->reroute('/');
    }

    function afterRoute(){
        $f3 = \Base::instance();
        $assets = \Assets::instance();

        $assets->addCss('recursos/bootstrap/css/bootstrap.css',5,'head');
        $assets->addCss('recursos/css/bootstrap-icons.css');
		$assets->addCss('recursos/css/elcatalogo.css',4,'head');
        //$assets->addCss('recursos/css/animate.min.css');


        $f3->set('items_menu', \Elcatalogo::armarMenu());
        
        echo \Template::instance()->render(self::rutaBase);

    }



}

?>