<?php

class mMegusta extends \DB\SQL\Mapper{
    protected $tabla='me_gusta';
/*
CREATE TABLE "me_gusta" (
	`id`	INTEGER UNIQUE,
	`idUser_destino`	INTEGER,
	`idUser_origen`	INTEGER,
	`idMedia`	INTEGER DEFAULT 0,
	`fecha`	NUMERIC,
	PRIMARY KEY(`id` AUTOINCREMENT)
)
*/    

    function __construct(){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );
    }
    
    function addNew($idOrigen, $idFoto){

        $this->load(array('idUSer_origen=? AND idMedia=?', $idOrigen, $idFoto));
        if ($this->dry()){
            $this->reset();
            $this->idUser_origen = $idOrigen;
            $this->idMedia=$idFoto;
            $this->fecha = time(); 
            $this->save();
            return 1;
        } else {
            $this->erase();
            return -1;
            
        }
    }   

    function conteo_votos($idFoto){
        return $this->count(array('idMedia=?', $idFoto));
    }

    function vote_me_gusta($idOrigen, $idFoto){
        $cuantos = $this->count(array('idUSer_origen=? AND idMedia=?', $idOrigen, $idFoto));

        return boolval($cuantos);

    }
}

?>