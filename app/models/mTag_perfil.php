<?php
/**
 * Class Tag_Peril
 *
 * Represents a mapper for the 'tag_perfil' table in the database.
 *
 * @package DB\SQL
 */
 
 class mTag_Perfil extends \DB\SQL\Mapper{
    
    protected $tabla= 'tag_perfil';

    function __construct(){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );
    }

    function GetByIdUser($id){
        return $this->find(array('idUser=?', $id));
    }

    function DeleteByIdUser($id){
        return $this->erase(array('idUser=?', $id));

    }

    function AgregarTags($id, $tags){

        foreach($tags as $etiqueta){
            $this->reset();
            $this->idTag = $etiqueta['idTag'];
            $this->idUser = $id;
            $this->save();
            
        }
    }

}


?>