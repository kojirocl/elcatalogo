<?php
namespace Api;

class Perfiles {
    private $db;
    private $f3;
    
    function __construct() {
        $this->f3 = \Base::instance();
        $this->db = $this->f3->get('DB');
    }

    function afterRoute() {
        header('Content-Type: application/json');
        echo json_encode(\Base::instance()->get('respuestaApi'));

    }   

    public function get(){

        $body = json_decode($this->f3->get('BODY'), true);

        $region = $body['region'] ?? '';
        $ciudad = $body['ciudad'];
        $tags   = $body['tags'];

        $datos_ciudades = new \Modelos\mCiudades;

        // traes ciudades según la región
        $ciudades = $region ? $datos_ciudades->GetCiudades($region) : $datos_ciudades->GetCiudades();
        \Base::instance()->set('respuestaApi', $ciudades);

    }

}

?>