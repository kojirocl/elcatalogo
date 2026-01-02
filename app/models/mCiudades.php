<?php

class mCiudades extends \DB\Jig\Mapper{
    protected $tabla = 'cl.json';

    function __construct() {
        $db = new \DB\Jig('../app/db/');
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
        
        return $capitales;
    }

    function GetCiudades($region='Todas'){

        $filtro = null;

        if ($region != 'Todas') $filtro = array('@admin_name=:region', ':region'=> $region);
        
        return $this->find($filtro, array('order'=>'city SORT_ASC'));
        

    }

    function GetRegion($ciudad){
        $filtro = array('@city=:ciudad', ':ciudad'=> $ciudad);
        $this->load($filtro);


        return $this->get('admin_name');
    }

}

?>