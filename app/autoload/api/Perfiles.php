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

        $region = isset($body['region']) ? trim($body['region']) : '';
        $ciudad = isset($body['ciudad']) ? trim($body['ciudad']) : '';
        $tags   = isset($body['tags']) ? (array)$body['tags'] : [];

        $datos_ciudades = new \Modelos\mCiudades;
        $datos_perfiles = new \Modelos\mPerfiles;

        // traes ciudades según la región
        $ciudades = $region ? $datos_ciudades->GetCiudades($region) : $datos_ciudades->GetCiudades();

        $perfiles = $datos_perfiles->getPerfiles(
            $region ?: null,
            $ciudad ?: null,
            $tags
        );

        $this->f3->set('usuarios', $perfiles);
        $html = \Template::instance()->render('frontend/templates/tarjetas_contenido.html');

        \Base::instance()->set('respuestaApi', [
            'ciudades' => $ciudades,
            'html' => $html
        ]);
    }

}

?>