<?php

class Pruebas{

    function inicio($f3, $params){
        echo '<p>im here</p>';

        //$f3->set('title','El catalago');
        echo 'html/'. $params['pagina'] . '.html';
        //echo \Template::instance()->render('html/'. $params['page'] . '.html');

    }
    function Regiones(){
        $f3 = \Base::instance();

        $city = new mCiudades;

        $f3->set('regiones',$city->GetCapitales());

        echo \Template::instance()->render('public/test.html');

    }

    function Ciudades($f3){

        //Revisar::volcar_info($f3->get('POST'));
        $city = new mCiudades;

        $f3->set('region',$f3->get('POST.region'));
        $f3->set('ciudades',$city->GetCiudades($f3->get('POST.region')));

        echo \Template::instance()->render('public/test copy.html');

    } 

    function testSqlite($f3, $params){

        $test = new mPruebas;
        $test->load();
                
        echo 'id: '.$test->_id." /nombre: ".$test->nombre;

        $test->Agregar($params['nombre']);
        echo "agregado: ".'id';

        exit();
    }
    
    function comprime(){
        echo \Template::instance()->render('public/pruebas/test2.html');

    }

    function comprimir_archivos($f3, $args) {
		$path = 'assets/'.$args['type'].'/';
        $files = preg_replace('/(\.+\/)/','',$_GET['files']); // close potential hacking attempts  
        echo Web::instance()->minify($files,null,true);
	}

    function comprimir($f3, $args) {
		$path = 'ui/'.$args['type'].'/';
        $files = preg_replace('/(\.+\/)/','',$_GET['files']); // close potential hacking attempts  
        echo Web::instance()->minify($files,null,true);
	}

    function loremipsum(){
        $lipsum = new LoremIpsum;

        echo $lipsum->sentences(4, 'p');

    }

    function confirmacion_mail($f3){
        
        $pagina = 'public/templates/confirmacion_email.html';
        
        $f3->set('vista', \Template::instance()->render($pagina));
        echo \Template::instance()->render('public/default.html');
    }

    function confirmar_mail($f3){

        $user = new mUsers;
        $user->load(array('email=?', $f3->get('POST.mail')));
        if ($user->dry()) return 0;

        if (($user->codigo_verificacion != 1) && ($user->codigo_verificacion != $f3->get('POST.codigo'))) return 0;
        
        $user->confirmar_mail();

        Revisar::volcar_info($f3->get('POST'));
        Revisar::volcar_info($user->cast());

        return 1;

    }

    function comentarios(){
        $comentarios = new mComentarios();
        $result = $comentarios->find(array('idUserDestino=?', 2),array('order'=>'fecha DESC', 'limit'=>3));

        \Base::instance()->set('comentarios', $result);
        $html = \Template::instance()->render('public/templates/comentarios_listado.html');
        

        \Base::instance()->set('comentarios', $html);
        echo \Template::instance()->render('public/templates/comentarios.html');
    }

    function comentar(){

        $data = json_encode($_POST);

        $comentario = new mComentarios;
        //$comentario->addNew($data);
        
        $comentario->addNew($data,2);
        

        $result = $comentario->find(array('idUserDestino=?', 2),array('order'=>'fecha DESC', 'limit'=>3));

        \Base::instance()->set('comentarios', $result);
        
        echo \Template::instance()->render('public/templates/comentarios_listado.html');
        
    }

    // tailwind_@function
    function tailwind_inicio(){


        $f3 = \Base::instance();
        $assets = \Assets::instance();

        $contenido= "";

        $city = new \mCiudades;        
        $f3->set('regiones',$city->GetCapitales());
        $f3->set('ciudades',$city->GetCiudades('Todas'));
        $contenido .= \Template::instance()->render('frontend/templates/barra_regiones.html').'<br>';

        $tags = new \mTags;
        $etiquetas = $tags->GetTags();
        $f3->set('tags', $etiquetas);
        $contenido .= \Template::instance()->render('frontend/templates/barra_tags.html').'<br>';

        $result = \Elcatalogo::getDatosPerfil(null, null);
        $f3->set('usuarios', $result);
        $contenido .= '<div id="tarjetas">'.\Template::instance()->render('frontend/templates/tarjetas_contenido.html').'</div>';
        
        $assets->addCss('css/bootstrap-icons.css',5,'head');
        $assets->addCss('css/tw_estilo.css',4,'head');
        
        /* AXIOS */
        $assets->addJs('js/axios.min.js',4,'head');
        //$assets->addJs('js/ax_frontend.js'); 
        
        /* ALPINEJS */
        //$assets->addJs('js/alpine.cdn.min.js', 3,'head');
        //$assets->addJs('js/regiones copy.js');
        //$assets->addJs('js/filtrar.js');

        \Elcatalogo::armarMenu();

        //var_dump($assets->getAssets());
        $f3->set('contenido', $contenido);

        echo \Template::instance()->render('pruebas/inicio.html');

    }

}


?>