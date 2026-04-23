<?php
namespace Controllers\Publico;

class Inicio {
    const ruta = 'frontend/skeleton.html';

    public function beforeRoute() {
        $f3 = \Base::instance();

        $f3->set('saludo','');
        
        if (\Helpers\Logueado::estaLogeado())
			$f3->set('saludo', '/ Bienvenid@ '.$f3->get('SESSION.usuario.nombre'));

        $f3->set('items_menu', \Helpers\Menu::Simple());
        
    }

	public function afterRoute() {
		echo \Template::instance()->render(self::ruta);

    }

    public function inicio(){
        
        $f3 = \Base::instance();

        $datos_ciudades = new \Modelos\mCiudades;
        
        $combo['regiones'] = array(
            'aria-label' => "Regiones",
            'id' => "region",
            'name' => "region",
            'datos' => $datos_ciudades->GetCapitales()
        );

        $combo['ciudades'] = array(
            'aria-label' => "Ciudades",
            'id' => "ciudad",
            'name' => "ciudad",
            'datos' => $datos_ciudades->GetCiudades()
        );

        $f3->set('combo', $combo);

        $datos_tags = new \Modelos\mTags;
        $f3->set('tags', $datos_tags->getTop(5));

        $perfiles = new \Modelos\mPerfiles;
        $f3->set('perfiles', $perfiles->getPerfiles());


        $f3->set('contenido', 'frontend/templates/home.html');

        
    }

    public function ver_pagina(){

        echo "aqui! ver pagina: ".$_GET['page'];

    }
    
    function cargando(){
        $f3 = \Base::instance();
        echo \Template::instance()->render('components/loading.html');
        exit();
    }
}

?>