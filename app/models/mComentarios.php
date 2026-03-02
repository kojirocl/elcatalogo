<?php

class mComentarios extends \DB\SQL\Mapper{
    protected $tabla = 'comentarios';
    
    function __construct(){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );

    }

    static function crear_muestras(){
        $texto = new \LoremIpsum;

        $texto->paragraphs(1,'<p>');

    }

    function addNew($data){ //$idUserOrigen, $idUserDestino, $comentario
        
        $this->load(array('idUserOrigen=? AND idUserDestino=?', intval($data['idUserOrigen']), intval($data['idUserDestino'])));

        if ($this->dry()){
            $this->reset();

            $this->comentario = $data['comentario'];
            $this->fecha = time(); 

            $this->idUserOrigen = $data['idUserOrigen'];
            $this->idUserDestino = $data['idUserDestino'];
            
            $this->save();

            return $this->id;

        }

        return 0;
        
    }

    function get_ultimos_tres($idPerfil){
        return $this->find(array('idUserDestino=?', $idPerfil),array('order'=>'fecha DESC', 'limit'=>3));

    }

    function get_comentarios($idPerfil, $cuantos=0){
    
        if ($cuantos>0)
            $r = $this->find(array('idUserDestino=?', $idPerfil),array('order'=>'fecha DESC', 'limit'=>$cuantos));
        else
            $r = $this->find(array('idUserDestino=?', $idPerfil),array('order'=>'fecha DESC'));

        return $r;

    }

    public function getAll($idPerfil){
        $x = $this->find(array('idUserDestino=?', $idPerfil),array('order'=>'fecha DESC'));
        
        return $x;

    }

    function get_comentario($idUserOrigen, $idUserDestino){

        return $this->load(array('idUserOrigen=? AND idUserDestino=?', $idUserOrigen, $idUserDestino));
    }

}
?>