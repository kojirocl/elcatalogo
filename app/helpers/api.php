<?php

class Api{


    function filtrar_usuarios($region=null, $ciudad=null){

        $result = \Elcatalogo::getDatosPerfil(null, $region , $ciudad);

        return json_encode($result);

    }

    function comentar(){

        $f3 = \Base::instance();

        $data = json_decode($f3->BODY, true);
        
        $comentario = new \mComentarios;

/*         if (!$f3->exists('SESSION.usuario')) $_POST['idUserOrigen'] = rand(10,100); // Si no hay sesión, genera un id aleatorio para pruebas
        else $_POST['idUserOrigen'] = $f3->get('SESSION.usuario.id'); */


        $resp = $comentario->addNew($data);

        if ($resp){
            echo json_encode([
                'success' => true,
                'message' => 'Comentario guardado',
                'idComentario' => $resp]);
        }else{
            echo json_encode([
                'success' => false,
                'message' => 'Comentario no guardado']);

        }

        /* RESPUESTA TIPO
        {
            "success": true/false,
            "message": "Mensaje de feedback",
            "idComentario": 123  // Opcional
        }
        */
    }

    function me_gusta(){
        
        $f3= \Base::instance();

        $data = json_decode($f3->BODY, true);
        
        $idViewer = $f3->get('SESSION.usuario.id');
        $idFoto = $data['id_medio'];

        $medio = new \mMedia;

        if ($medio->es_mi_foto($idFoto, $idViewer)){
            echo json_encode([
                'success' => false,
                'error' => 'No puedes votar por tus propias fotos'
            ]);
            return 0;
        }

        $votos = new \mMegusta;

        try{
            $resultado = $votos->addNew($idViewer, $idFoto);

            $conteo = $votos->conteo_votos($idFoto);
            
            echo json_encode([
                'success' => $resultado,
                'nuevo_total' => $conteo]);
            
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()]);
        }
    }

    function mi_comentario(){

        $f3 = \Base::instance();

        $idOrigen = $f3->get('PARAMS.idUserOrigen');
        $idDestino = $f3->get('PARAMS.idUserDestino');
        $comentario = $f3->get('PARAMS.comentario');

        $comentario = new \mComentarios;
        $resp = $comentario->get_comentario($idOrigen, $idDestino);
        //$resp = json_encode(array('origen'=> $idOrigen, 'destino'=>$idDestino, 'comentario'=>$comentario));
        
        header('Content-Type: application/json');

        if ($resp){
            echo json_encode([
                'success' => true,
                'registro' => $resp]);
        }else{
            echo json_encode([
                'success' => false,
                'registro' => null,
                'message' => 'No se encontró el comentario']);
        }
    }

    function actualizar_comentario($f3, $params){

        $f3 = \Base::instance();

        $id = $params['id'];

        // Decodifica el cuerpo JSON manualmente
        $input = json_decode($f3->get('BODY'), true);
        $texto = $input['texto'] ?? '';

        // Validar + actualizar en DB
        // TODO: agregar comentario a la BD
        $comentario = new \mComentarios;
        $comentario->load(array('id=?', $id));

        if ($comentario->dry()){
            $texto = 'No se pudo actualizar el comentario';
            $success= false;
        }else{
            try {
                //code...
                $comentario->comentario = $texto;
                $comentario->fecha = time();
                $comentario->update();
                $comentario->save();
                $success = true;

            } catch (Exception $e) {
                //throw $th;
                $success = false;
                $texto = $e->getCode().': '.$e->getMessage();
            }

        } 

        echo json_encode([
                'success' => $success,
                'id' => $id,
                'texto' => $texto]);
        
    }


    function nuevo_comentario($f3, $params){

        $f3 = \Base::instance();

        // Decodifica el cuerpo JSON manualmente
        $input = json_decode($f3->get('BODY'), true);
        $texto = $input['texto'] ?? '';

        $id = $params['id'];
        $idOrigen = $f3->get('SESSION.usuario.id');
        $success = '';

        // Validar + actualizar en DB
        // TODO: agregar comentario a la BD
        //addNew $idUserOrigen, $idUserDestino, $comentario

        $comentario = new \mComentarios;
        $result = $comentario->addNew(array(
            'idUserOrigen' => $idOrigen,
            'idUserDestino' => $id,
            'comentario' => $texto
        ));

        if (!$result){
            $texto = 'No se pudo actualizar el comentario';
            $success= false;
        }else{

            $texto = 'Comentario guardado con id: '.$result;;
            $success = true;
        }  

        // preparar html con lista de comentarios
        $comentarios = new \mComentarios();
        $result = $comentarios->get_comentarios($id,10);

        $info = ['conectado'=>0,
                'puedo_comentar'=>0,
                'sessionId' => 0,
                'tengo_comentario' =>0];
                
        if($f3->exists('SESSION.usuario')){
            $info['conectado'] = 1;
            $info['sessionId'] = $f3->get('SESSION.usuario.id');

            if ($f3->get('SESSION.usuario.id') != $id){
                $info['puedo_comentar'] = 1;
                $info['tengo_comentario'] = $comentarios->get_comentario($f3->get('SESSION.usuario.id'), $id);

            }
        }

        $f3->set('info_comentarios', $info);

        $f3->set('comentarios', $result);
        $contenido_comentarios= 'frontend/templates/comentario_lista.html';
        
        $html = \Template::instance()->render($contenido_comentarios);


        echo json_encode([
                'success' => $success,
                'id' => $id,
                'idOrigen' => $idOrigen,
                'texto' => $texto,
                'html' => $html
            ]);
        
    }
}

?>