<?php
namespace Helpers;

class Paginacion{

    public const LIMITE = 4;

    static function barra_paginacion($pagina_actual, $total_paginas, $filtros_url = []){
		
		$paginas = [];

		for ($i=0; $i < $total_paginas; $i++ ){
			$query = http_build_query(array_merge(['page' => $i + 1], $filtros_url));

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


    public static function paginar($condicion, $params, $pagina){
        
        $sql = '';
        $limit = self::LIMITE;
        $offset = ($pagina-1) * $limit;

        $db = \Base::instance()->get('BD');
        
        $sql= "SELECT perfil.*, media.ubicacion AS foto_perfil,
            COUNT(*) OVER() AS total_registros 
            FROM perfil 
            LEFT JOIN media ON perfil.idFotoPerfil = media.id
            WHERE perfil.activo=1 AND $condicion
            ORDER BY perfil.idUser
            LIMIT ? OFFSET ?";

/*
        $sql="SELECT perfil.* , media.ubicacion AS foto_perfil
                FROM perfil
                LEFT JOIN media ON perfil.idFotoPerfil = media.id
                LEFT JOIN tag_perfil ON perfil.idUser = tag_perfil.idUser 
                WHERE tag_perfil.idTag IN ($tags) AND perfil.activo=1 AND perfil.idGrupo=5 $condicion
                GROUP BY perfil.idUser
                HAVING COUNT(DISTINCT idTag) = $cuantos_tags";
*/

        $params = array_merge($params, array($limit, $offset));

        $result = $db->exec($sql, $params);

        return $result;

    }

    public static function buscarPerfiles($params) {
        
    /* LLAMADA 
    // Con tags (todos los que tengan estos tags)
    $resultado = buscarPerfiles([
        'pagina' => 1,
        'porPagina' => 10,
        'tags' => [1, 5, 7], // IDs de tags
        'condicion' => "perfil.edad > 18"
    ]);

    */

        $db = \Base::instance()->get('BD');
        $a_mostrar = 4;
        
        // Parámetros básicos
        $pagina = $params['pagina'] ?? 1;
        $porPagina = $params['porPagina'] ?? $a_mostrar;
        $offset = ($pagina - 1) * $porPagina;
        
        // Parámetros opcionales
        $tags = $params['tags'] ?? []; // Array de tags IDs
        $cuantos_tags = $params['cuantos_tags'] ?? null; // Todos o exactos
        $condicion = $params['condicion'] ?? "1=1"; // Condiciones adicionales
        
        // Construir consulta dinámica
        $where = ["perfil.activo = 1", "perfil.idGrupo=5", $condicion];
        $where = array_filter($where);

        $joins = [];
        $groupBy = "";
        $having = "";
        
        // Si hay tags, añadir JOIN y condiciones
        if (!empty($tags)) {
            $joins[] = "LEFT JOIN tag_perfil ON perfil.idUser = tag_perfil.idUser";
            
            // Crear placeholders seguros para IN()
            $placeholders = implode(',', array_fill(0, count($tags), '?'));
            $where[] = "tag_perfil.idTag IN ($placeholders)";
            
            $groupBy = "GROUP BY perfil.idUser";
            // GROUP BY y HAVING si se especifican cuántos tags
            if ($cuantos_tags !== null) {
                $having = "HAVING COUNT(DISTINCT tag_perfil.idTag) = ?";
            }
        }
        
        // Construir SQL final
        $sql = "
            SELECT perfil.*, media.ubicacion AS foto_perfil,
                COUNT(*) OVER() AS total_registros
            FROM perfil 
            LEFT JOIN media ON perfil.idFotoPerfil = media.id
            " . implode(' ', $joins) . "
            WHERE " . implode(' AND ', $where) . "
            $groupBy
            $having
            ORDER BY perfil.idUser
            LIMIT ? OFFSET ?
        ";
        
        // Preparar parámetros
        $parametros = [];
        
        // Añadir tags a parámetros si existen
        if (!empty($tags)) {
            $parametros = array_merge($parametros, $tags);
            if ($cuantos_tags !== null) {
                $parametros[] = $cuantos_tags;
            }
        }
        // Añadir paginación
        $parametros[] = $porPagina;
        $parametros[] = $offset;
 
        // Ejecutar
        $stmt = $db->prepare($sql);
        $stmt->execute($parametros);
        $resultados = $stmt->fetchAll();
        
        // Extraer total_registros (está en cada fila)
        $total = $resultados[0]['total_registros'] ?? 0;
        
        return [
            'total' => $total,
            'paginas' => ceil($total / $porPagina),
            'subset' => $resultados,
            'filtros' => $params['condicion'] ?? "",
            'limit' => $porPagina,
            'offset' => $offset,
            'pagina' => $pagina
        ];
    }


}

?>