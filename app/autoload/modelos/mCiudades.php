<?php

namespace Modelos;

class mCiudades extends \DB\Jig\Mapper{
    protected $tabla = 'cl.json';

    function __construct() {
        $db = new \DB\Jig('../database/');
        parent::__construct($db, $this->tabla );

    }

    function Campos(){
        return $this->load();
    }

    function GetCapitales(){
        
        $result = $this->find(null, array('order'=>'admin_name SORT_ASC')) ?? [];

        $capitales = [];
        $seen = [];
        
        foreach($result as $ciudad) {
            if (!in_array($ciudad['admin_name'], $seen)){
                $capitales[] = $ciudad;
                $seen[] = $ciudad['admin_name'];
            }
        }
        
        return $seen;
    }

    function GetCiudades($region='Todas'){

        $condicion = null;

        if ($region != 'Todas') $condicion = array('@admin_name=:region', ':region'=> $region);
        
        $ciudades = $this->find($condicion, array('order'=>'city SORT_ASC'));

        $respuesta = [];
        foreach($ciudades as $ciudad){
            $respuesta[]= $ciudad->city;
        }

        return $respuesta;
    }

    function GetRegion($ciudad){
        $filtro = array('@city=:ciudad', ':ciudad'=> $ciudad);
        $this->load($filtro);

        return $this->get('admin_name');
    }

}

?>