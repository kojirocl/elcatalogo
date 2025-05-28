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
	
    function GetTags($n = null){

        $condicion = null;
        if ($n) $condicion = array('limit' => $n);
        return $this->find(null, $condicion);
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

    function getTop20(){
        $db = \Base::instance()->get('BD');
        $result = $db->exec("SELECT t.idTag, t.tag, COUNT(*) AS frecuencia FROM tag_perfil tp 
                            JOIN tags t ON tp.idTag = t.idTag
                            JOIN perfil ON tp.idUser = perfil.idUser AND perfil.activo=1
                            GROUP BY t.idTag, t.tag
                            ORDER BY frecuencia DESC
                            LIMIT 20;");

        return $result;

    }

}

?>