<?php
namespace Publico;

class Perfil extends General{
    const ruta = 'frontend/templates/perfil.html';


    function verPerfil(){
        $f3 = \Base::instance();
        $assets = \Assets::instance();
        
        $idPerfil = $f3->get('PARAMS.idPerfil');
        $perfil = new \mPerfiles($idPerfil);
        
        $f3->set('perfil', $perfil);
        $f3->set('mensaje','');

        $medios = \mMedia::get_user_media_with_likes_and_vote($idPerfil, $f3->get('SESSION.usuario.id'));

        $comentarios = new \mComentarios();
        $result = $comentarios->get_comentarios($idPerfil,10);
        
        $habilitado ='disabled';
        $muestra = 0;
        $icono_me_gusta= 'bi-heart';

        $info = ['conectado'=>0,
                'puedo_comentar'=>0,
                'sessionId' => 0,
                'tengo_comentario' =>0];
                
        if($f3->exists('SESSION.usuario')){
            $info['conectado'] = 1;
            $info['sessionId'] = $f3->get('SESSION.usuario.id');

            if ($f3->get('SESSION.usuario.id') != $idPerfil){
                //$f3->set("habilitado","");
                $info['puedo_comentar'] = 1;
                $info['tengo_comentario'] = $comentarios->get_comentario($f3->get('SESSION.usuario.id'), $idPerfil);
                $habilitado ='';
                $muestra = 1;
                //$f3->set('tengoComentario', array($comentarios->get_comentario($f3->get('SESSION.usuario.id'), $idPerfil), $idPerfil));
            }
        }

        $f3->set('info_comentarios', $info);

        $f3->set('estados', array('habilitado'=>$habilitado, 'icono_me_gusta'=> $icono_me_gusta, 'muestra' => $muestra));

        if(count($medios)>0){
            $f3->set('medios', $medios);
        }else{
            $f3->set('mensaje',['clases'=>'alert alert-info', 'icono'=>'bi-info-circle-fill', 'contenido'=>' no hay medios cargados aun...']);
            $f3->set('medios','');
        }

        if(!$result) $f3->set('mensaje',['clases'=>'alert alert-info', 'icono'=>'bi-info-circle-fill', 'contenido'=>' no hay comentarios aun...']);
        
        if (count($result)>0){
            $f3->set('comentarios', $result);
            $contenido_comentarios= 'frontend/templates/comentario_lista.html';
        }else{
            //$f3->set('mensaje',['clases'=>'alert alert-info', 'icono'=>'bi-info-circle-fill', 'contenido'=>' no hay comentarios aun...']);
            //$contenido_comentarios = 'frontend/templates/mensaje.html';

        };
        $contenido_comentarios= 'frontend/templates/comentario_lista.html';
        
        $f3->set('carrusel', \Template::instance()->render('frontend/templates/perfil_carrusel.html'));
        
        $f3->set('lista_comentarios', \Template::instance()->render($contenido_comentarios));


        $assets->addJs('bootstrap/js/bootstrap.min.js',5,'head');
        $assets->addJs('js/vendor/axios.min.js',4,'head');

        $assets->addJs('js/user/me_gusta.js');
        $assets->addJs('js/user/comentarios.js');

        $f3->set('contenido', \Template::instance()->render(self::ruta));

    }

    function ver_perfil_2(){
        $f3 = \Base::instance();
        $assets = \Assets::instance();

        $assets->addJs('bootstrap/js/bootstrap.min.js',5,'head');
        $assets->addJs('js/vendor/axios.min.js',4,'head');

        $assets->addJs('js/user/me_gusta.js');
        $assets->addJs('js/user/comentarios.js');

    }

    function contacto(){

        $f3 = \Base::instance();

        $id = $f3->get('PARAMS.id');

        $perfil = new \mPerfiles;

        $trafico = new \mTrafico;
        $trafico->addContacto($id);

        $telefono = $perfil->get_telefono($id);

        $mensaje = urlencode('Hola, encontré tu numero en elCatalogo.cl, estoy interesado en su servicio...');
        // Redireccionar a WhatsApp Web o App
        $whatsappUrl = "https://wa.me/$telefono?text=$mensaje";
        $f3->reroute($whatsappUrl, false);


    }


    function cargar_comentarios(){

        $f3 = \Base::instance();
        $idPerfil = $f3->get('GET.idUser');
        //echo $idPerfil;

        $comentario = new \mComentarios;
        $result = $comentario->get_comentarios($idPerfil,10);

        if (count($result)>0){
            $f3->set('comentarios', $result);
            echo \Template::instance()->render('frontend/templates/comentario_lista.html');
        }else{
            echo json_encode([
                'success' => false,
                'message' => 'No hay comentarios aun...']);
        }

        exit;

    }


}

?>