<?php
class MUsers extends \DB\SQL\Mapper{
    protected 
        //$db_driver = 'sqlite:app/db/elcatalogo.sqlite', 
        $tabla = 'users',
        $users;

    
    function __construct(){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );
    }

    function GetUserByName($nombre){
        $this->load(array('email=?', $nombre));
    
    }

    function GetUserById($id){
        $this->load(array('idUser=?', $id));
        return $this->query;
    
    }


    function autenticar($email, $password){
        $this->GetUserByName($email);
        
        if (!$this->dry() && password_verify($password, $this->password) && $this->verificado ==1 ) return 1;
        else return 0;

    }

    function confirmar_mail($datos): int{
        $resp = 0;
        $this->load(['email = ? AND codigo_verificacion = ?', $datos[0], $datos[1]]);
     
        if (!$this->dry()){
        
            if ($this->verificado == 0 ){
                $this->verificado = 1;
                $this->codigo_verificacion=1;
                $this->save();
                $resp= 1;
            }
            else $resp= 2;
        }

        return $resp;
    }

    function crear_nuevo($datos, $codigo){ //ESTE
        
        $this->email = $datos['email'];
        $this->password = \Elcatalogo::make_password($datos['password']);
        $this->fecha_ingreso = time();
        $this->codigo_verificacion = $codigo;
        $this->save();
        $this->reset();

        return 1;
    }

    function actualizar_password($pwd){
        $this->password = password_hash($pwd, PASSWORD_DEFAULT);
        $this->save();
        return 1;
    }

}


?>