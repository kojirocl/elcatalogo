<?php

class mPerfiles extends \DB\SQL\Mapper{
    protected $tabla='perfil';

    function __construct($id = null){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );
        
        if ($id !== null) {
            $this->load(['idUser = ?', $id]);

            if ($this->dry()) {
                throw new \Exception("Perfil con ID {$id} no encontrado.");
            }
        }
    }

    function GetInfo(){
		return $this->fields();
	}
	
	function GetPerfilById($id){
        return $this->load(array('idUser=?', $id));
        //return $this->query;
    }

    function GetActivos(){
        return $this->find(array('activo=?',1));
        //return $this->query;
    }
	
    function Guardar($id, $datos, $region){

        $this->load(array('idUser=?', $id));
        
        $this->copyfrom($datos);
        $this->region = $region;
        $this->save();

    }

    function crear_nuevo($id, $email){
        $this->idUser = $id;
        $this->nickname = strstr($email, '@', true);
        $this->save();

    }

    function ActualizarGrupo($id, $grupo_nuevo){
        $this->load(array('idUser=?', $id));
        $this->idGrupo = $grupo_nuevo;
        
        if ($grupo_nuevo == 5) $this->activo = 1;
        else $this->activo = 0;

        $this->save();
        return 1;
    }

    function get_telefono($id){
        $this->load(array('idUser=?', $id));
        return $this->wsp;
    }

    function GetUbicacionFotoPerfil(){
        // Devuelve la ubicacion de la foto de perfil
        if ($this->dry() || $this->idFotoPerfil == 0 || $this->idFotoPerfil == null) return '';
        
        $foto = new \mMedia;
        $fotoPerfilsrc = $foto->GetUbicacion($this->idFotoPerfil);

        return $fotoPerfilsrc;

    }
    

}

?>