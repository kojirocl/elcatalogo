<?php

namespace Helpers;

class Paginacion{

    public static function paginar($condicion, $params, $pagina){
        
        $sql = '';
        $limit = 10;
        $offset = ($pagina-1) * $limit;
        $db = \Base::instance()->get('BD');
        
        $sql= "SELECT perfil.*, media.ubicacion AS foto_perfil,
            COUNT(*) OVER() AS total_registros 
            FROM perfil 
            LEFT JOIN media ON perfil.idFotoPerfil = media.id
            WHERE $condicion
            ORDER BY perfil.idUser
            LIMIT ? OFFSET ?";

        $params = array_merge($params, array($limit, $offset));

        $result = $db->exec($sql, $params);
        return $result;

    }

    
    static function barra_paginacion($pagina_actual, $total_paginas, $filtros_url){
		
		$paginas = [];

		for ($i=0; $i < $total_paginas; $i++ ){
			$query = http_build_query(array_merge($filtros_url, ['page' => $i + 1]));

			$habilitada = '';
			if ($pagina_actual == ($i+1)) $habilitada = 'disabled';
			$paginas[] = [
				'enlace' => '/ver?' . $query,
				'pagina' => ($i+1),
				'habilitada' => $habilitada
			];
		}

		return $paginas;
		
    }

}

?>