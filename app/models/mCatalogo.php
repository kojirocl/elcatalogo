<?php
class mCatalogo extends \DB\SQL\Mapper{
  

    function __construct($tabla){
        parent::__construct(\Base::instance()->get('BD'), $tabla );
    }

    
    }



?>