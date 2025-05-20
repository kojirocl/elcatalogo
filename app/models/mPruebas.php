<?php
class mPruebas extends \DB\SQL\Mapper{
    protected 
        $tabla='test';

    function __construct(){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla);
    }

    function Agregar($nombre){
        $this->nombre= $nombre;
        $this->save();
        return 1;
    }

    function GetAll(){
        return $this->load();
    }

}

?>