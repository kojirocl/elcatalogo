<?php

class mSuscripcion extends \DB\SQL\Mapper{

    protected $tabla = 'suscripcion';
    
    function __construct(){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );
    }

    
    

}

?>