<?php
namespace Publico;

class Login extends General{


    function inicio(){
        $f3 = \Base::instance();

        $paginaError = 'frontend/templates/error.html';
        $salida ='frontend/templates/login.html';

        if (\Elcatalogo::revisaSiConectado()){
            $salida = $paginaError;
            $f3->set('error', [
                'titulo' => 'Error de sesión',
                'mensaje' => 'Ya tienes una sesión activa, por favor cierra la sesión antes de iniciar otra.'
            ]);
        }else $f3->copy('CSRF','SESSION.csrf');
        
        $f3->set('contenido', \Template::instance()->render($salida));
    }

    function login_user(){
        $f3 = \Base::instance();
        
        if ($f3->get('SESSION.usuario.id') == NULL) {
            $f3->set('contenido', \Template::instance()->render('frontend/templates/login.html'));
            //echo \Template::instance()->render('frontend/default.html');
        } else {
            $f3->reroute('/privado/indicadores');
            exit();
        }
    }

    function logout_user(){
        $f3 = \Base::instance();
        $f3->clear('SESSION');
        $f3->reroute('/');
    }

    
    function authenticate(){
        $f3 = \Base::instance();

        print_r($f3->get('POST'), $f3->get('SESSION.csrf'));
        
        \Elcatalogo::verificar_csrf($f3->get('POST.token'), $f3->get('SESSION.csrf')); 

        $email = $f3->get('POST.email');
        $password = $f3->get('SALT').$f3->get('POST.password');

        $user = new \MUsers;

        $login_result = $user->autenticar($email, $password);

        if ($login_result == 1) {
            $perfil = new \mPerfiles;
            $perfil->load(array('idUser=?', $user->idUser));

            \Elcatalogo::actualizarSession($perfil);
            
            //$f3->reroute('/privado/inicio');
        } 
        
        $f3->reroute('/');

    }

}

?>