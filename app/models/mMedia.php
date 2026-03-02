<?php
class mMedia extends \DB\SQL\Mapper{
    private $tabla='media';

    public function __construct($id = null, $ubicacion = null) {   
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );
        
        
        if ($id !== null && $ubicacion === null) {
            $this->load(['id = ?', $id]);
            return !$this->dry();
        }elseif ($id !== null && $ubicacion !== null) {
            $this->reset();
            $this->idUser = $id;
            $this->ubicacion = $ubicacion;
            $this->save();
            return true;
        }
        return false;
    }
    
    function GetByIdUser($id){
        return $this->find(array('idUser=?', $id));
    }

    function BorrarById($id){
        return $this->erase(array('id=?', $id));
    }

    function GetUbicacion($id){
        $this->load(array('id=?',$id));
        return  $this->ubicacion;
    }

    function es_mi_foto($idMedia, $idUser){
        $this->load(array('id=?', $idMedia));
        
        if ($this->idUser == $idUser){
            return true;
        }
        return false;

    }

    function media_por_usuario($idUser){
        return $this->find(array('idUser=?', $idUser));
    }

    static function get_user_media_with_likes_and_vote($user_id, $viewer_id = 0) {
        // Código para ejecutar la consulta SQL...
        $db = \Base::instance()->get('BD');

        $consulta= "SELECT media.*, 
            COUNT(me_gusta.id) AS total_me_gusta,
            MAX(CASE WHEN me_gusta.idUser_origen = ? THEN 1 ELSE 0 END) AS si_me_gusta
            FROM media
            LEFT JOIN me_gusta ON media.id = me_gusta.idMedia
            WHERE media.idUser = ?
            GROUP BY media.id";

        $result = $db->exec($consulta, [$viewer_id, $user_id]);

        return $result;
    }

}