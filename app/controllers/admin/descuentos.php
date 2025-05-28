<?php
namespace Admin;

class Descuentos extends Comun{
    
    function inicio(){
        $f3 = \Base::instance();
        
        $descuentos = new \mDescuentos;

        $datos = [
            'campos' => $descuentos->fields(),
            'result' => $descuentos->find()
        ];


        $f3->set('datos', $datos);

        $f3->set('contenido', \Template::instance()->render('admin/templates/descuentos.html'));
    }

}

?>