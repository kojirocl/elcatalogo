<?php
namespace Publico;

class Perfil extends General{
    const ruta = 'frontend/templates/perfil.html';

    function verPerfil(){
        $f3 = \Base::instance();
        $assets = \Assets::instance();
        
        $datos =[];
        $idPerfil = $f3->get('PARAMS.idPerfil');
        $perfil = new \mPerfiles($idPerfil);
        
        $datos['perfil'] = $perfil;

        $medios = \mMedia::get_user_media_with_likes_and_vote($idPerfil, $f3->get('SESSION.usuario.id') ?? 0);
        $datos['medios'] = $medios;

        $comentarios = new \mComentarios();
        $comentarios_temporal= $comentarios->getAll($idPerfil);

        $info = [
            'conectado' => 0,
            'propietario' => 0,
            'id_usuario' => $f3->get('SESSION.usuario.id') ?? 0
        ];
        

        if($f3->get('SESSION.usuario.id')){
            $info['conectado'] =1;
            if($idPerfil === $f3->get('SESSION.usuario.id')){
                $info['propietario'] =1;
            }
        }

        $datos['info'] = $info;
        $datos['mi_comentario'] = null;
        $datos['comentarios'] = [];

        
        foreach($comentarios_temporal as $comentario){
            if ($comentario['idUserOrigen'] === $info['id_usuario']){
                $datos['mi_comentario'] = $comentario->cast();
            }else{
                $datos['comentarios'][] = $comentario->cast();
            }
        }

        $f3->set('datos', $datos);
        //$f3->set('carrusel', 'frontend/templates/perfil_carrusel.html');
        // aqui voy

/*
        $assets->addJs('js/vendor/axios.min.js',4,'head');


        $assets->addJs('js/user/me_gusta.js');
        $assets->addJs('js/user/comentarios.js');
*/

        $f3->set('contenido_perfil', \Template::instance()->render(self::ruta));

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

    public function pruebaLikes(){
        echo 'llega!';
        exit;
    }
}

?>