<?php
namespace Privado;

class Media extends \Seguridad{
    const pagina = 'privado/templates/media.html';private $template = 'private/inicio.html';
    
    function inicio(){
        
        $f3 = \Base::instance();

        $archivos = new \mMedia;
        $medios = $archivos->GetByIdUser($f3->get('SESSION.usuario.id'));
        
        $f3->set('medios', $medios);

        $f3->set('vista',\Template::instance()->render(self::pagina));

	}
	
    function subir_archivos(){
        
        $f3 = \Base::instance();
        \Elcatalogo::procesar_fotos();

        $f3->reroute('privado/media/inicio');
        
    }

    function borrar($f3, $params){
        
        $id = $params['id'];
        $media = new \mMedia($id);
        $registro = $media->ubicacion;
        
        if (unlink($registro)){
            $media->BorrarById($id);
            //unlink($registro->ubicacion);
            //verificar si se borra la foto de perfil
            $idFotoPerfil = $f3->get('SESSION.usuario.idFotoPerfil');
            if ($idFotoPerfil == $id){
                $perfil = new \mPerfiles($f3->get('SESSION.usuario.id'));
                $perfil->idFotoPerfil = 0;
                $perfil->save();
                \Elcatalogo::actualizarSession($perfil);
            }
        }
        
        $f3->reroute('privado/media');
        
    }

    function fijar_foto_perfil($f3,$params){
        $idUsuario= $f3->get('SESSION.usuario.id');
        $idFoto = $params['id'];
        
        $perfil = new \mPerfiles($idUsuario);
        
        $perfil->idFotoPerfil=$idFoto;
        $perfil->save();
//ACTUALIZA FOTO PERFIL
        \Elcatalogo::actualizarSession($perfil);
        
        $f3->reroute('privado/media');
    }

}

?>