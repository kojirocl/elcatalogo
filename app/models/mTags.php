<?php
/**
 * Class Tags
 *
 * This class is responsible for handling the tags in the database.
 */
class mTags extends \DB\SQL\Mapper{
    protected 
        $tabla='tags',
        $vista_tags = 'lista_tags';

    function __construct(){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );
    }

    function GetInfo(){
		return $this->fields();
	}
	
	function GetTagsByUserId($id){
        //$tags = parent::__construct(\Base::instance()->get('BD'), 'tags' );
        $lista = new \DB\SQL\Mapper(\Base::instance()->get('BD'), $this->vista_tags );
        
        return $lista->find(array('iduser=?', $id));

    }
	
    function GetTags(){
        return $this->find();
    }

    function GetId($lista){

        $etiquetas= explode(" ", $lista);

        $placeholders = implode(',', array_fill(0, count($etiquetas), '?'));

        $result= \Base::instance()->get('BD')->exec("SELECT idTag FROM tags WHERE tag IN (".$placeholders.")", $etiquetas);
        
        return $result;
     }   

    function Agregar($etiquetas){

        $arreglo = explode(" ",$etiquetas['tags']);

        //Revisar::volcar_info($arreglo);

        foreach($arreglo as $tag){
            $this->load(array('tag=?',$tag));
            if ($this->dry()){
                $this->reset();
                $this->tag= $tag;
                $this->save();
            }


        }


    }

}

?>