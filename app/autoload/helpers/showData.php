<?php
namespace Helpers;

class ShowData{

    public static function getAll(){

        //$condicion = "perfil.activo=1";
        $condicion = "1=1";
        $params = [];
        $filtros = [];
             
        $pagina = filter_var(
                    $_GET['page']?? null, 
                    FILTER_VALIDATE_INT, 
                    ['options' => ['default' => 1, 'min_range'=> 1]]
        );
        $result = \Helpers\Paginacion::paginar(condicion:$condicion, params: $params, pagina:$pagina);

        return ([
            'subset' => $result,
            'filtros' => $filtros,
            'pagina_actual' => $pagina,
            'total_paginas' => ceil($result[0]['total_registros'] / \Helpers\Paginacion::LIMITE)
        ]);    
    }

    public static function getSome(){

        //$condicion = "perfil.activo=1";
        $condicion = "1=1";
        $params = [];
        $filtros = [];

        $pagina = filter_var(
                    $_GET['page']?? null, 
                    FILTER_VALIDATE_INT, 
                    ['options' => ['default' => 1, 'min_range'=> 1]]
        );

        $ciudad = isset($_GET['ciudad']) ? trim($_GET['ciudad']) : '';
        $region = isset($_GET['region']) ? trim($_GET['region']) : '';

        // Lógica: ciudad tiene prioridad sobre región
        if (!empty($ciudad) && $ciudad !== 'Todas') {
            $condicion .= " AND perfil.ciudad = ?";
            $params[] = $ciudad;
            $filtros['ciudad'] = $ciudad;
        } elseif (!empty($region) && $region !== 'Todas') {
            $condicion .= " AND perfil.region = ?";
            $params[] = $region;
            $filtros['region'] = $region;
        }
       
        $result = \Helpers\Paginacion::paginar(condicion:$condicion, params: $params, pagina:$pagina);

        //$total_registros = $result?$result[0]['total_registros']:0;


        return ([
            'subset' => $result,
            'filtros' => $filtros,
            //'total_registros' => $total_registros,
            //'limit' => $limit,
            'pagina_actual' => $pagina,
            'total_paginas' => ceil($result[0]['total_registros'] / \Helpers\Paginacion::LIMITE)
        ]);    

    }    



    public static function showOne($id){



    }

    public static function get_tarjetas($tags, $region, $ciudad){
        
            $db = \Base::instance()->get('BD');

            $condicion = '';
            if ($ciudad) $condicion = " AND perfil.ciudad = '$ciudad'";
            if ($region) $condicion = " AND perfil.region = '$region'";

            //$cuantos_tags = sizeof(explode(',',$tags));

            $cuantos_tags = strlen($tags) > 0 ? substr_count($tags, ',') + 1 : 0;

            if ($cuantos_tags == 0)
                $sql = "SELECT perfil.* , media.ubicacion AS foto_perfil
                        FROM perfil
                        LEFT JOIN media ON perfil.idFotoPerfil = media.id
                        WHERE perfil.activo=1 AND perfil.idGrupo=5 $condicion
                        GROUP BY perfil.idUser";
            else
                $sql="SELECT perfil.* , media.ubicacion AS foto_perfil
                        FROM perfil
                        LEFT JOIN media ON perfil.idFotoPerfil = media.id
                        LEFT JOIN tag_perfil ON perfil.idUser = tag_perfil.idUser 
                        WHERE tag_perfil.idTag IN ($tags) AND perfil.activo=1 AND perfil.idGrupo=5 $condicion
                        GROUP BY perfil.idUser
                        HAVING COUNT(DISTINCT idTag) = $cuantos_tags";


            $result = $db->exec($sql);

            return $result;

    }    

}
?>