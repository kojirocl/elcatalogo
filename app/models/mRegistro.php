<?php
    class mRegistro extends \DB\SQL\Mapper{
        protected 
            $tabla='registro';    

            function __construct(){
                parent::__construct(\Base::instance()->get('BD'), $this->tabla );
            }
        }

?>