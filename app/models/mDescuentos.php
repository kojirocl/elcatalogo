<?php

class mDescuentos extends \DB\SQL\Mapper{

    protected $tabla = 'descuentos';
    
    function __construct(){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );
    }
  

}

?>