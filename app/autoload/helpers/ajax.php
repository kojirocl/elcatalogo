<?php

use function PHPSTORM_META\type;

class Ajax{

    private $mensaje_error='<div class="container alert alert-info" role="alert">No hay resultados para la selección!</div>';
    private $paginaError = 'frontend/templates/error.html';

/*     function login(){
        $f3 = \Base::instance();
        
        $salida ='frontend/templates/login.html';

        if (\Elcatalogo::revisaSiConectado()){
            $salida = $this->paginaError;
            $f3->set('error', [
                'titulo' => 'Error de sesión',
                'mensaje' => 'Ya tienes una sesión activa, por favor cierra la sesión antes de iniciar otra.'
            ]);
        };

        echo \Template::instance()->render($salida);
    } */




    function filtrar_usuarios($region=null, $ciudad=null){

        //$result = \Elcatalogo::getDatosPerfil(null, $region , $ciudad);
        $result = self::tag_get_usuarios(0,null,$region, $ciudad);

        return $result;

    }

    function filtrar_ciudad($f3){
        //\Revisar::volcar_info($f3->get('POST'));

        $ciudad = $f3->get('POST.ciudad');
        if ($ciudad == 'Todas') $ciudad = NULL;
        
        //$result = \Elcatalogo::getDatosPerfil(null, null, $ciudad);
        $result = self::tag_get_usuarios(0,null,null, $ciudad);

        if (empty($result)) {
            echo $this->mensaje_error;
            return;
        }
        //echo $ciudad."|".json_encode(['usuarios' => $result]);
        $f3->set('usuarios', $result);
        echo \Template::instance()->render('frontend/templates/tarjetas_contenido.html');
        
    }

    function filtrar_tags($f3){

        $tags = $f3->get('POST.tag');
        $ciudad = $f3->get('POST.ciudad');
        if ($ciudad == 'Seleccione la ciudad') $ciudad = NULL;

        // Realiza la lógica para obtener los usuarios filtrados por tags
        $usuarios = $this->get_usuarios_x_tag($tags, $ciudad);
    
        if (empty($usuarios)) {
            echo $this->mensaje_error;
            return;
        }

        echo $f3->get('POST');


        $f3->set('usuarios', $usuarios);
        echo \Template::instance()->render('frontend/templates/tarjetas_contenido.html');
        //json_encode(['usuarios' => $usuarios]);
    
    }

    function filtrar(){ //} tag_filtrar(){  #### VIGENTE

        $params = [];
        
        $pagina = $_GET['page'] ?? 1;

        $region = $_POST['region'] ?? 'Todas';
        $ciudad = $_POST['ciudad'] ?? 'Todas';
        $tags = $_POST['tag'] ? explode(',', $_POST['tag']) : [];

        $condicion = [];
        if ($ciudad !== 'Todas') $condicion[] = "perfil.ciudad = '$ciudad'";
        if ($region !== 'Todas') $condicion[] = "perfil.region = '$region'";

        $resultado = Paginacion::buscarPerfiles([
                        'pagina' => $pagina,
                        'tags' => $tags, // IDs de tags
                        'condicion' => implode(' AND ', $condicion)
                    ]);

        if (empty($resultado['subset'])) {
            echo $this->mensaje_error;
            return;
        }

        $info['usuarios'] = $resultado['subset'];
        echo \Template::instance()->render('frontend/templates/tarjetas_contenido.html',
                                            'text/html', 
                                            $info
                                        );
        
        // PENDIENTE ACTUALIZAR BARRA PAGINACION        
    
    }

    function tag_get_usuarios($cuantos, $tags, $region, $ciudad){
        
            $db = \Base::instance()->get('BD');

            $condicion = '';
            if ($ciudad) $condicion = " AND perfil.ciudad = '$ciudad'";
            if ($region) $condicion = " AND perfil.region = '$region'";
    
            // Asegúrate de que $tags sea un array de enteros
            //$tags = array_map('intval', (array)$tags);
            
            //var_dump($tags,$region,$ciudad, $condicion);
            
            //$cuantos = count($tags); // Cuántos tags se han seleccionado
            // Crear la cadena de tags para la consulta SQL
            //$tags = implode(',', $tags);  // Esto generará "1,5,6"

            if ($cuantos == 0)
                $sql = "SELECT perfil.* , media.ubicacion AS foto_perfil
                        FROM perfil
                        LEFT JOIN media ON perfil.idFotoPerfil = media.id
                        WHERE perfil.activo=1 AND perfil.idGrupo=5$condicion
                        GROUP BY perfil.idUser";
            else
                $sql="SELECT perfil.* , media.ubicacion AS foto_perfil
                        FROM perfil
                        LEFT JOIN media ON perfil.idFotoPerfil = media.id
                        LEFT JOIN tag_perfil ON perfil.idUser = tag_perfil.idUser 
                        WHERE tag_perfil.idTag IN ($tags) AND perfil.activo=1 AND perfil.idGrupo=5$condicion
                        GROUP BY perfil.idUser
                        HAVING COUNT(DISTINCT idTag) = $cuantos";


            $result = $db->exec($sql);
            // SELECT DISTINCT perfil.* FROM perfil LEFT JOIN tag_perfil ON perfil.idUser=tag_perfil.idUser WHERE tag_perfil.idTag IN (1,4) AND perfil.idGrupo=5


            return $result;


    }

    function get_usuarios_x_tag($tags, $ciudad){ 
        // Lógica para obtener los usuarios filtrados según los tags
        // Esto es solo un ejemplo, ajusta según tu lógica de negocio
        $db = \Base::instance()->get('BD');

        $sql_ciudad = '';
        if ($ciudad){
            $sql_ciudad = "AND perfil.ciudad = '$ciudad'";
        }

        $cuantos = count((array)$tags);

        $tags = array_map('intval', (array)$tags);
        $tags = implode(',', $tags);
        
        $result = $db->exec("SELECT perfil.* , media.ubicacion AS foto_perfil
            FROM perfil
            LEFT JOIN media ON perfil.idFotoPerfil = media.id
            LEFT JOIN tag_perfil ON perfil.idUser = tag_perfil.idUser 
            WHERE tag_perfil.idTag IN ($tags) AND perfil.activo=1 AND perfil.idGrupo=5 $sql_ciudad
            GROUP BY perfil.idUser
            HAVING COUNT(DISTINCT idTag) = $cuantos");
        // SELECT DISTINCT perfil.* FROM perfil LEFT JOIN tag_perfil ON perfil.idUser=tag_perfil.idUser WHERE tag_perfil.idTag IN (1,4) AND perfil.idGrupo=5


        return $result;
    }

    public function ciudadesPorRegion(){

        $ciudades = new \mCiudades();

        $aux = $ciudades->GetCiudades($_POST['region']);

        $respuesta = "<option selected>Todas</option>";

        foreach($aux as $ciudad){
            $respuesta .= "<option value='$ciudad->city'>$ciudad->city</option>";
            //$respuesta .= "<option value=''>algo</option>";

        }

        echo $respuesta;
        //exit();

    }



}

?>