<?php
namespace Privado;

class Indicadores extends \Seguridad{
    const pagina = 'privado/templates/indicadores.html';

    function inicio(){
        $f3 = \Base::instance();
        $assets = \Assets::instance();
        
        $idUser = $f3->get('SESSION.usuario.id');
        
        $datos = \Elcatalogo::datos_estadisticos($idUser);

        $f3->set('datos', json_encode($datos));
       
        //$assets->addJs('bootstrap/js/bootstrap.min.js',5,'head');
        $assets->addJs('js/vendor/chart2.js',4,'head');
        $assets->addJs('js/user/ax_indicadores.js');

        $f3->set('vista',\Template::instance()->render(self::pagina));


    }
   
}

?>