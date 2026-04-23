<?php
namespace Helpers;

class Filtros{

    static function armarFiltros(){
        
        $chile = new \Modelos\mCiudades;
        $filtros['regiones'] = $chile->GetCapitales();
        $filtros['ciudades'] = $chile->GetCiudades('Todas');

        $tags = new \Modelos\mTags;
        $filtros['tags']= $tags->getTop20();

        return $filtros;
    }

}

?>