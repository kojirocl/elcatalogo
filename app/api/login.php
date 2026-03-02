<?php

namespace Api;

class Login{

    public function entrada(){
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(array('cabecera' => 10, 'cuerpo' => 'algo'));

    }

    public static function revisaSiConectado(){
        $f3 = \Base::instance();

        return $f3->exists('SESSION.usuario');
    }

    public static function actualizarSession(\mPerfiles $perfil){

        $fotoPerfilsrc = $perfil->GetUbicacionFotoPerfil();
        
        $usuario  = array(
            'id' => $perfil->idUser,
            'realName' => $perfil->realname,
            'nickname' => $perfil->nickname,
            'region' => $perfil->region,
            'ciudad' => $perfil->ciudad,
            'activo' => $perfil->activo,
            'idFotoPerfil' => $perfil->idFotoPerfil,
            'fotoPerfilLocation' => $fotoPerfilsrc,
            'idGrupo' => $perfil->idGrupo
        );

        $f3 = \Base::instance();
        $f3->set('SESSION.usuario', $usuario);
        return 1;
    }    

}



?>