<?php

class mCiudades extends \DB\Jig\Mapper{
    protected $tabla = 'cl.json';

    function __construct() {
        $db = new \DB\Jig('db/');
        parent::__construct($db, $this->tabla );

    }

    function Campos(){
        return $this->load();
    }

    function GetCapitales(){
        $filtro = null;

        $result = $this->find($filtro, array('group'=> 'admin_name','order'=>'admin_name'));

        return $result;
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