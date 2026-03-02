<?php
namespace Publico;

class Inicio extends General{
    const ruta = 'frontend/templates/inicio_copy.html';

    function beforeRoute($f3){
        $f3->set('saludo','');
        
        if ($f3->get('SESSION.usuario.id') != NULL){
			$usuario = $f3->get('SESSION.usuario.nombre');
			$f3->set('saludo', '/ Bienvenid@ '.$usuario);
		}

        $filtros = \Filtros::armarFiltros();

        $contenido_html ='';
        $f3->set('filtros', \Template::instance()->render(
                                                'frontend/templates/filtros.html', 
                                                'text/html', 
                                                $filtros)
        );
    }

    function inicio($f3){
        $assets = \Assets::instance();
      
        $pagina = $_GET['page'] ?? 1;
        $params = array(
            'pagina' => $pagina
        );

        $datos = \Paginacion::buscarPerfiles($params);
        $info['usuarios'] = $datos['subset'];

        $contenido_html ='';
        $f3->set('tarjetas', \Template::instance()->render(
                                                    'frontend/templates/tarjetas_contenido.html', 
                                                    'text/html', 
                                                    $info)
        );

        $barra_paginacion = \Paginacion::barra_paginacion($pagina, $datos['paginas'], []);
        $aux['paginas'] = $barra_paginacion;
        $f3->set('paginacion', \Template::instance()->render(
                                                    'frontend/templates/barra_paginacion.html', 
                                                    'text/html', 
                                                    $aux)
        ); 
        //$f3->concat('contenido_html', $contenido_html);
    }

    function cargando(){
        $f3 = \Base::instance();
        echo \Template::instance()->render('components/loading.html');
        exit();
    }
}

?>