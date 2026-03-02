<?php

class Filtros{

    static function armarFiltros(){
        
        $chile = new \mCiudades;
        $filtros['regiones'] = $chile->GetCapitales();
        $filtros['ciudades'] = $chile->GetCiudades('Todas');

        $tags = new \mTags;
        $filtros['tags']= $tags->getTop20();

        return $filtros;
    }

}

?>