<?php
class Elcatalogo{
    
    public static function tengoComentario($idMio, $comentarios){
        foreach($comentarios as $info){
            if ($info['idUserOrigen'] === $idMio)
                return $info; 
        }

        return null;

    } 

    function estaLogeado(){
        $f3 = \Base::instance();

        // Verifica si existe la sesión del usuario
        if ($f3->exists('SESSION.usuario')) {
            // Devuelve un JSON indicando que el usuario está logueado
            header('Content-Type: application/json');
            echo json_encode(['logueado' => true]);
            
        } else {
            // Devuelve un JSON indicando que el usuario no está logueado
            header('Content-Type: application/json', true, 401); // Código 401 Unauthorized
            echo json_encode(['logueado' => false]);
            
        }
    }

    static function mail_confirmacion($f3,$codigo, $valores){
        $host="smtp.elcatalogo.cl";
        $port=587;
        $scheme="tls";
        $user="no-reply@elcatalogo.cl";
        $pw="password-elcatalogo";

        $smtp = new \SMTP( $host, $port, $scheme, $user, $pw );

        $smtp->set('Errors-to', '<contacto@elcatalogo.cl>');
        $smtp->set('To', '"'.$valores['apodo']? $valores['apodo'] :'' .'" <'.$valores['email'].'>');
        $smtp->set('From', $user);
        $smtp->set('Subject', 'Codigo verificacion ElCatalogo.cl');

        
        $f3->set('datos', array('codigo'=>$codigo, 'email'=>$valores['email']));
        $mensaje = \Template::instance()->render('frontend/templates/correo.html');
        
        $smtp->send($mensaje,true, true);
        $salida=[
            'mensaje' => $smtp->log(),
            'textos'=> $mensaje,
        ];

        $logger = new Log('smtp.log');
        $logger->write(json_encode($salida));

        return $mensaje;

    }

    static function datos_estadisticos($idUser){
        $db = \Base::instance()->get('BD');
  
        //SQLITE
        $result = $db->exec("
        SELECT 
            strftime('%Y-%m', datetime(fecha, 'unixepoch')) AS periodo,
        SUM(visita) AS total_visitas,
        SUM(contacto) AS total_contactos
        FROM trafico
        WHERE idUsuario = ?
        GROUP BY periodo
        ORDER BY periodo ASC
        LIMIT 10", $idUser); 

        //MARIADB
/*         $result = $db->exec("
            SELECT DATE_FORMAT(FROM_UNIXTIME(fecha), '%Y-%m') AS periodo,
            SUM(visita) AS total_visitas,
            SUM(contacto) AS total_contactos
            FROM trafico
            WHERE idUsuario = ?
            GROUP BY periodo
            ORDER BY periodo ASC
            LIMIT 10", $idUser); */
        
        return $result;

    }

    static function armarMenu(){
        $f3 = \Base::instance();

        if (!$f3->exists('SESSION.usuario')){
            $menu = array(
                'home' => array('url'=>'/','icono'=>'bi-house'),
                'login' => array('url'=>'/login','icono'=>'bi-person-circle'),
                'registro' => array('url'=>'/registro','icono'=>'bi-person-plus'),
                'validar mail' => array('url'=>'/validar','icono'=>'bi-person-check'),
                'contacto' => array('url'=>'/contacto','icono'=>'bi-envelope-at'),
            );

        }else{
            $grupo = $f3->get('SESSION.usuario.idGrupo');

            switch ($grupo){
                case 1: //usuario registrado con suscripcion
                    $menu = array(
                        'home' => array('url'=>'/home','icono'=>'bi-house'),
                        'perfil' => array('url'=>'/privado/perfil','icono'=>'bi-person-circle'),
                        'suscripcion' => array('url'=>'/privado/suscripcion','icono'=>'bi-cash-coin'),
                        'contacto' => array('url'=>'/privado/contacto','icono'=>'bi-envelope-at'),
                        'logout' => array('url'=>'/privado/salir','icono'=>'bi-box-arrow-left')
                    );
                    break;
                case 5: //usuario registrado sin suscripcion
                    $menu = array(
                        'home' => array('url'=>'/home','icono'=>'bi-house'),
                        'indicadores' => array('url'=>'/privado/indicadores','icono'=>'bi-speedometer'),
                        'perfil' => array('url'=>'/privado/perfil','icono'=>'bi-person-circle'),
                        'media' => array('url'=>'/privado/media','icono'=>'bi-image'),
                        'suscripcion' => array('url'=>'/privado/suscripcion','icono'=>'bi-cash-coin'),
                        'contacto' => array('url'=>'/privado/contacto','icono'=>'bi-envelope-at'),
                        'logout' => array('url'=>'/privado/salir','icono'=>'bi-box-arrow-left')
                    );
                    break;
                case 3: // administrador
                    $menu = array(
                        'home' => array('url'=>'/','icono'=>'bi-house'),
                        'descuentos' => array('url'=>'/admin/descuentos','icono'=>'bi-ticket'),
                        'cupones' => array('url'=>'/admin/cupon','icono'=>'bi-ticket'),
                        'logout' => array('url'=>'/privado/salir','icono'=>'bi-box-arrow-left')
                    );
                    break;
                default:
                    $menu = array(
                        'home' => array('url'=>'/home','icono'=>'bi-house'),
                        'login' => array('url'=>'/login','icono'=>'bi-person-circle'),
                        'registro' => array('url'=>'/registro','icono'=>'bi-person-plus'),
                        'validar mail' => array('url'=>'/validar','icono'=>'bi-person-check'),
                        'contacto' => array('url'=>'/contacto','icono'=>'bi-envelope-at')
                    );
                    break;
            }
            

            $f3->set('saludo', '/ Bienvenid@ '.$f3->get('SESSION.usuario.nickname'));
        }
        return $menu;
    }

   
    static function filtrarUsuarios($tags = [], $ciudad = null, $region = null){
        $db = \Base::instance()->get('BD');

        $sql = "SELECT perfil.*, media.ubicacion AS foto_perfil
                FROM perfil
                LEFT JOIN media ON perfil.idFotoPerfil = media.id
                LEFT JOIN tag_perfil ON perfil.idUser = tag_perfil.idUser
                WHERE perfil.activo = 1 AND perfil.idGrupo = 5";

    
        $condiciones = [];
        $params = [];

        // Filtro por ciudad (tiene prioridad sobre región)
        if (!empty($ciudad) && $ciudad !== 'Todas') {
            $condiciones[] = "perfil.ciudad = ?";
            $params[] = $ciudad;
        } elseif (!empty($region) && $region !== 'Todas') {
            $condiciones[] = "perfil.region = ?";
            $params[] = $region;
        }

        // Filtro por tags
        if (!empty($tags)) {
            $tags = array_map('intval', (array)$tags);
            $cuantos = count($tags);
            $in = implode(',', array_fill(0, $cuantos, '?'));

            $condiciones[] = "tag_perfil.idTag IN ($in)";
            $params = array_merge($params, $tags);
        }

        // Aplicar condiciones extra
        if (!empty($condiciones)) {
            $sql .= " AND " . implode(" AND ", $condiciones);
        }

        // Agrupar y contar los tags (para que cumpla TODOS los tags)
        if (!empty($tags)) {
            $sql .= " GROUP BY perfil.idUser
                    HAVING COUNT(DISTINCT idTag) = ?";
            $params[] = count($tags);
        }

        return $db->exec($sql, $params);
    }

    static function verificar_csrf($token, $csrf){ 

        if (empty($token) || empty($csrf) || $token !== $csrf) {
            \Base::instance()->error(403, 'Token CSRF inválido o ausente'.'<br>token: '.$token.'<br>csrf: '.$csrf);
        }
        return 1;
    }


    function en_construccion(){
        echo \Base::instance()->error('501','En construccion...');
    }

    static function make_password($pwd){
        $f3 = \Base::instance();
        $salt = $f3->get('SALT');

        $pwd = $f3->get('SALT').$pwd;
        return password_hash($pwd, PASSWORD_DEFAULT);
    }

    static function GetContenido($condiciones = null){
		
		$db = \Base::instance()->get('DB');

        
        $sql = "SELECT perfil.*, media.ubicacion AS foto_perfil 
            FROM perfil 
            LEFT JOIN media ON perfil.idFotoPerfil = media.id
            WHERE perfil.activo = 1 ".$condiciones;

        $result = $db->exec($sql);

        return $result;
    }

    /*
    static function ArmarCondiciones(){
		$datos = $mapper->paginate($f3->get('GET.page')-1, self::por_pagina, $filtro_f3);

		$seo = [
            'title' => ' El Catalogo ',
        	//'description' => ucwords($datos['titulo']),
			'url' => $f3->get('REALM')
		];

		$f3->set('seo', $seo);	
		$f3->set('titulo_de_pagina', ucwords($texto));



		$f3->set('barra_back_home', \Template::instance()->render('barra_back_home.html'));
		$f3->set('tarjetas' , \Componentes::tarjetas_frontend($datos));
		$f3->set('barra_paginacion', \Componentes::barra_paginacion($f3->get('GET.page'), $datos['count'], \Gealib::filtros_url($filtro)));
	
/*
        $sql= "SELECT perfil.*, media.ubicacion AS foto_perfil 
            FROM perfil 
            LEFT JOIN media ON perfil.idFotoPerfil = media.id
            WHERE perfil.activo = 1".$condicion;


        $result = $db->exec($sql);



    }
*/
        


}
?>