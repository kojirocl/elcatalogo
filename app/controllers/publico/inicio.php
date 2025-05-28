<?php
namespace Publico;

class Inicio extends General{
    const ruta = 'frontend/templates/inicio.html';

    function inicio(){
        $f3 = \Base::instance();
        $assets = \Assets::instance();
        
        $f3->set('saludo','');
        
        if ($f3->get('SESSION.usuario.id') != NULL){
			$usuario = $f3->get('SESSION.usuario.nombre');
			$f3->set('saludo', '/ Bienvenid@ '.$usuario);
		}

        $assets->addJs('js/vendor/axios.min.js',4,'head');
        $assets->addJs('js/user/inicio2.js');
        
        $f3->set('filtro', \Elcatalogo::armarFiltros());
        $f3->set('usuarios', \Elcatalogo::getDatosPerfil(null, null));

        $f3->set('contenido', \Template::instance()->render(self::ruta));

    }

    
    function comboCiudades(){
        $f3 = \Base::instance();  
        
        try{
            $region = $f3->get('POST.region');

            $city = new \mCiudades;
            //$result = $city->GetCiudades($region);

            $f3->set('ciudades',$city->GetCiudades($region));
            echo \Template::instance()->render('frontend/templates/combo_ciudades.html');

        } catch (\Exception $e) {   
            echo $e->getMessage();
        }

        exit();

    }

        function cargando(){
            $f3 = \Base::instance();
            echo \Template::instance()->render('components/loading.html');
            exit();

        }
    

}

?>