<?php
class mTrafico extends \DB\SQL\Mapper{
    protected $tabla='trafico';

/*     CREATE TABLE "trafico" (
        `id`	INTEGER NOT NULL DEFAULT 1 UNIQUE,
        `idUsuario_visita`	INTEGER NOT NULL,
        `idUsuario_visitado`	INTEGER NOT NULL,
        `fecha`	NUMERIC,
        `visita`	INTEGER,
        `contacto`	INTEGER,
        PRIMARY KEY(`id` AUTOINCREMENT)
    ) */
    function __construct(){
        parent::__construct(\Base::instance()->get('BD'), $this->tabla );

    }

    function addVisita($idPerfil){
        $this->add($idPerfil,1,0);

    }

    function addContacto($idPerfil){
        $this->add($idPerfil,0,1);

    }


    private function add($idPerfil, $visita=0, $contacto=0){
        $this->reset();
        $this->idUsuario = $idPerfil;
        $this->fecha = time(); 
        $this->visita=$visita;
        $this->contacto=$contacto;
        $this->save();

    }

/* SELECT 
strftime('%Y', datetime(fecha, 'unixepoch')) AS anio, 
strftime('%m', datetime(fecha, 'unixepoch')) AS mes, 
SUM(visita) AS total_visitas
FROM trafico
WHERE idUsuario = 2  -- Reemplaza con el ID del usuario
GROUP BY anio, mes
ORDER BY anio, mes; */


/* SELECT 
    strftime('%Y-%m', datetime(fecha, 'unixepoch')) AS mes, 
    SUM(visita) AS total_visitas
FROM trafico
WHERE idUsuario = 2  -- Reemplaza con el ID del usuario
GROUP BY mes
ORDER BY mes; */


/* SELECT 
    strftime('%Y', datetime(fecha, 'unixepoch')) AS anio, 
    strftime('%m', datetime(fecha, 'unixepoch')) AS mes, 
    SUM(visita) AS total_visitas,
    SUM(contacto) AS total_contactos
FROM trafico
WHERE idUsuario = ?  -- Reemplaza con el ID del usuario
GROUP BY anio, mes
ORDER BY anio, mes; */

}

?>