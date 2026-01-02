<?php

namespace Helpers;

class ShowData{

    public static function getAll(){

        $condicion = "perfil.activo=1";
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
            'url_filtros' => http_build_query($filtros),
            //'total_registros' => $total_registros,
            //'limit' => $limit,
            'pagina_actual' => $pagina
            //'total_paginas' => ceil($total_registros/$limit),
        ]);    

    }

}
?>