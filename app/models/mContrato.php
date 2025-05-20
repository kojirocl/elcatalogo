<?php

class mContrato extends \DB\SQL\Mapper{

    protected $tabla = 'contrato';
    
    function __construct(){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );
    }

    function crear_nuevo($datos){
        $this->copyFrom($datos);
        $this->save();

        return 1;

    }
    

}

?>