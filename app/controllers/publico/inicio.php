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
        
        $f3->set('filtro', \Elcatalogo::armarFiltros());

        $datos = \Helpers\ShowData::getAll(null, null,null,0);

        $f3->set('usuarios', $datos['subset'] );
        
        $barra_paginacion = \Helpers\Paginacion::barra_paginacion(
            $datos['pagina_actual'],
            $datos['total_paginas'],
            $datos['filtros']
        );
        
        $f3->set('paginas', $barra_paginacion);
        
        
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