<?php
namespace Privado;

class User extends \Publico\General{
    const ruta = 'private/templates/user.html';
    
    function inicio(){
        
        $f3 = \Base::instance();

        $usuario = new \mUsers;
        $usuario->load(array('idUser=?', $f3->get('SESSION.id')));

        $f3->set('usuario',$usuario->email);
        
        $assets = \Assets::instance();       
        $assets->addJs('./recursos/js/contrasenas_iguales.js');

        $vista = \Template::instance()->render(self::ruta);
        
        $f3->set('vista', $vista);

        
    }

    function actualizar(){

        $f3 = \Base::instance();
        $usuario = new \mUsers;
        $usuario->load(array('idUser=?', $f3->get('SESSION.id')));

        $usuario->actualizar_password($_POST['password']);

        $f3->set('mensaje', array(
            'tipo'=>'alert-success',
            'rol'=>'alert',
            'titulo'=>'Actualizacion de contraseña',
            'contenido'=>'La contraseña ha sido actualizada correctamente')
        );

        $f3->set('usuario',$usuario->email);
        
        $assets = \Assets::instance();       
        $assets->addJs('js/contrasenas_iguales.js');

        $vista = \Template::instance()->render(self::ruta);
        
        $f3->set('vista', $vista);

        
    }


}

?>