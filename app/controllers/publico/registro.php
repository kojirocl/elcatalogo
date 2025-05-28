<?php
namespace Publico;

class Registro extends General{
    const ruta = 'frontend/templates/registro.html';

    function inicio(){
        $f3 = \Base::instance();
               
        $f3->set('contenido', \Template::instance()->render(self::ruta));
        //echo \Template::instance()->render('frontend/default.html');
    }
    
    function procesar_registro(){

        $f3 = \Base::instance();

        $claves = array('email', 'password', 'captcha');
        $valores = array_combine($claves, $f3->get('POST.dato'));

        $audit = \Audit::instance();
        $error = 0;

        if ($valores['captcha'] == $f3->get('SESSION.captcha_code')) {
            
            if ($f3->get('DEBUG')!=0) $en_serio= FALSE;
            else $en_serio= TRUE;

            if ($audit->email($valores['email'], $en_serio)) {
                $user = new \MUsers;
                $user->load(array('email=?', $valores['email']));

                if ($user->dry()) {

                    $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                    //$valores['password'] = $f3->get('SALT').$valores['password'];
                    $user->crear_nuevo($valores, $codigo);

                    $textos= \Elcatalogo::mail_confirmacion($f3, $codigo, $valores);

                    $error = 0;
                } else {
                    $f3->set('mensaje', 'el correo ya existe');
                    $error = 1;
                }
            } else {
                $f3->set('mensaje', 'direccion de correo erronea');
                $error = 1;
            }
        } else {
            $f3->set('apodo', $valores['apodo']);
            $f3->set('email', $valores['email']);
            $f3->set('mensaje_captcha', 'reintente, el captcha no coincide');
            $error = 1;
        }

        if ($error == 1) $pagina = self::ruta;
        else{
            $f3->set('codigo',$codigo);

            $f3->set('mensaje_correo', $textos);
            $pagina = 'frontend/templates/registro2.html';

        }
        
        $f3->set('contenido', \Template::instance()->render($pagina));
        //echo \Template::instance()->render('frontend/default.html');
    }

    function confirmar_mail(){
        
        $f3 = \Base::instance();
       
        $f3->copy('CSRF','SESSION.csrf');
        $pagina = 'frontend/templates/email_confirmacion.html';
        
        $f3->set('contenido', \Template::instance()->render($pagina));
        
    }

    function validar_mail(){

        $f3 = \Base::instance();
        $email="";
        $codigo="";

        if ($f3->VERB == 'POST') {
            \Elcatalogo::verificar_csrf($f3->get('POST.token'), $f3->get('SESSION.csrf')); // exit aqui si no se verifica el token
        }
        
        $email = $f3->get($f3->VERB.'.mail');
        $codigo = $f3->get($f3->VERB.'.codigo');

        $email = $f3->clean($email); // Limpia el input
        $codigo = (int)$codigo; // Fuerza tipo numérico

        $user = new \mUsers;

        $resultado= $user->confirmar_mail([$email,$codigo]); // mail y codigo

        //echo $resultado;

        switch ($resultado) {
            case 1:
                $f3->set('mail_verificado', 1);
                $perfil = new \mPerfiles;
                $perfil->crear_nuevo($user->idUser, $email);
                $f3->set('mensaje', '¡Correo validado! Ya puedes iniciar sesión.');
                $f3->copy('CSRF','SESSION.csrf');
                $pagina = 'frontend/templates/login.html';
                break;
            case 0:
                $f3->set('mensaje', 'Usuario no existe.');
                $pagina = 'frontend/templates/error.html';
                break;
            case 2:
                $f3->set('mensaje', 'Usuario ya verificado.');
                $pagina = 'frontend/templates/error.html';
                break;
            default:
                $f3->set('mensaje', 'Error desconocido.');
                $pagina = 'frontend/templates/error.html';
        }

        $f3->set('contenido', \Template::instance()->render($pagina));
     
    }
}

?>