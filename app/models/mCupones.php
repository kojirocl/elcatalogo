<?php

class mCupones extends \DB\SQL\Mapper{

    protected $tabla = 'cupones';
    
    function __construct(){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );
    }

    static function get_cupones_validos_de_usuario($id){
        //return $this->find(array('idUser=? AND valido=1', $id));
        $db = \Base::instance()->get('BD');
        $result = $db->exec('SELECT cupones.id AS idCupon, descuentos.* FROM cupones LEFT JOIN descuentos ON cupones.idDescuento = descuentos.id WHERE cupones.idUser=? AND cupones.valido=1 ORDER BY descuentos.descuento ASC', $id);

        return $result;
    }
    
    

}

?>