<?php
namespace Privado;

class Suscripcion extends \Seguridad{
    const pagina = 'privado/templates/suscripcion.html';
    
    
    function inicio(){
        $f3 = \Base::instance();

        $idUser = $f3->get('SESSION.usuario.id');

        //CONTRATOS
        $contratos = new \mContrato;
        $f3->set('contratos',$contratos->find(array('idUser=?',$idUser), array('order' => 'fecha_registro DESC')));

        //CUPONES
        $f3->set('cupones', \mCupones::get_cupones_validos_de_usuario($idUser));

        //SUSCRIPCIONES DISPONIBLES
        $suscripciones = new \mSuscripcion;
        $f3->set('suscripciones', $suscripciones->find());

        $f3->set('vista', \Template::instance()->render(self::pagina));


    }

    function detalle($f3, $params){

        $suscripcion = new \mSuscripcion;
        $suscripcion->load(array('id=?',$params['id']));
        if ($suscripcion->dry()) $f3->reroute('/privado/suscripcion/inicio');
        else{
            $f3->set('suscripcion',$suscripcion);
            

            
            $f3->set('vista','private/templates/suscripcion_detalle.html');
            //echo \Template::instance()->render('private/inicio.html');
        }

    }

    function contratar(){
        
        $f3 = \Base::instance();

/*         $f3->set('mensaje', array(
            'tipo'=>'alert-info',
            'rol'=>'alert',
            'titulo'=>'Pruebas',
            'contenido'=>'No se ha podido realizar la contratación')
        ); 
*/
        $id_cupon_seleccionado = $_POST['cupon'];
        $idSuscripcion = $_POST['boton'];

       
        if (intval($id_cupon_seleccionado) > 0){
            $cupon = new \mCupones;
            $cupon->load(array('id=?', $id_cupon_seleccionado));
            if (!$cupon->dry()){
                $descuento = new \mDescuentos;
                $f3->set('cupon', $id_cupon_seleccionado);
                $f3->set('descuento',$descuento->load(array('id=?',$cupon->idDescuento))->cast());
            };//else $f3->set('cupones', 'no encontramos el descuento solicitado, lo sentimos, vuelve a intentar');
                        
            
        
        };//else $f3->set('cupones','no hay cupones seleccionados');

        $suscripcion = new \mSuscripcion;
        $f3->set('suscripcion',$suscripcion->load(array('id=?',$idSuscripcion))->cast());
        
        $vista = "";
        $vista .= \Template::instance()->render('privado/templates/suscripcion_contratar.html');
        //$html .= \Revisar::volcar_info($_POST);
        
        $f3->set('vista', $vista);
        //echo \Template::instance()->render('private/inicio.html');
    }

    function guardar($f3){
        
        if ($f3->get('DEBUG') == 0 ) $f3->reroute('/privado/suscripcion/inicio');

        $info = array();
        $html = "";
        $valor_pagado = 0;

        if ($_POST['cod_cupon'] !=null){
            $cupon = new \mCupones;
            $cupon->load(array('id=?',$_POST['cod_cupon']));
            
            $descuento = new \mDescuentos;
            $descuento->load(array('id=?',$cupon->idDescuento));
            
        }
        else array_push($info, array('cupon' => "sin cupon"));
            
        $suscripcion = new \mSuscripcion;
        $suscripcion->load(array('id=?',$_POST['cod_suscripcion']));
        if (isset($cupon)) $valor_pagado = $suscripcion->valor * (100 - $descuento->descuento) / 100;
        else{
            $valor_pagado = $suscripcion->valor;
            $cupon = new \mCupones;
            $cupon->factory([0,0,0,0]);

            $descuento = new \mDescuentos;
            $descuento->factory([0,0,0,0]);
            
        } 

        $datos_contrato= array(
            'idUser' => $_SESSION['usuario']['id'],
            'idSuscripcion' => $_POST['cod_suscripcion'],
            'fecha_registro' => time(),
            'vigencia' => $suscripcion->dias,
            'info_cupon' => json_encode($cupon->cast()),
            'info_descuento' => json_encode($descuento->cast()),
            'info_suscripcion' => json_encode($suscripcion->cast()),
            'valor_pagado' => $valor_pagado,
            'vigente' => 1
        );

        try{
            $contrato = new \mContrato;
            if($contrato->crear_nuevo($datos_contrato)==1){
                //ACTUALIZAR PERFIL: GRUPO 5, ACTIVO 1
                $perfil = new \mPerfiles;
                $perfil->ActualizarGrupo($_SESSION['usuario']['id'],5);
                //actualizar session
                \Elcatalogo::actualizarSession($perfil);
            };

            if ($_POST['cod_cupon'] !=null){
                $cupon->valido = 0;
                $cupon->save();
            };
            
            $f3->reroute('/privado/suscripcion/inicio');
            
            
        }catch(\Exception $e){
            var_dump($e);
        }
    }

}
?>