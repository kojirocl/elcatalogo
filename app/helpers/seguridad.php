<?php
class Seguridad{
    const rutaBase= 'privado/layout.html';

    function beforeRoute(){

        $f3 = \Base::instance();
      
        if (!Elcatalogo::revisaSiConectado()) $f3->reroute('/');
        
    }

    function afterRoute(){
        $f3 = \Base::instance();
        $assets = \Assets::instance();

        $assets->addCss('recursos/bootstrap/css/bootstrap.css',5,'head');
        $assets->addCss('recursos/css/bootstrap-icons.css');
		$assets->addCss('recursos/css/elcatalogo.css',4,'head');
        $assets->addJs('js/vendor/axios.min.js',3,'head');

        $f3->set('items_menu', \Elcatalogo::armarMenu());

        echo \Template::instance()->render(self::rutaBase);

    }



}

?>