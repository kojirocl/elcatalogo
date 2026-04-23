<?php
namespace Helpers;

class Elcatalogo{
    
    public static function tengoComentario($idMio, $comentarios){
        foreach($comentarios as $info){
            if ($info['idUserOrigen'] === $idMio)
                return $info; 
        }

        return null;

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

        $logger = new \Log('smtp.log');
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

     


}
?>