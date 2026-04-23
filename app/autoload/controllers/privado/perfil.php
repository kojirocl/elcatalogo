<?php
namespace Privado;

class Perfil extends \Seguridad{
    const pagina = 'privado/templates/perfil.html';
    

    function inicio(){
        $f3 = \Base::instance();
        $assets = \Assets::instance();

        $perfil = new \mPerfiles;
        $tags = new \mTags;
        $city = new \mCiudades;

        
        $user = $f3->get('SESSION.usuario.id');

        $perfil->GetPerfilById($user);

        $lista = $tags->GetTagsByUserId($user);

        $ciudades = $city->GetCiudades();

        $cadena = "";
        foreach($lista as $value){
            if ($cadena=="") $cadena= $value->tag;
            else $cadena = $cadena." ".$value->tag;
        }
        //$assets->addJs('recursos/bootstrap/js/bootstrap.min.js',5,'head');
        //$assets->addJs('recursos/js/axios.min.js',4,'head');

        $f3->set('perfil',$perfil);
        $f3->set('etiquetas',$cadena);
        $f3->set('ciudades', $ciudades);

        $f3->set('vista',\Template::instance()->render(self::pagina));

	
	}

    function guardar(){

        $f3 = \Base::instance();
        $id= $f3->get('SESSION.usuario.id');

        
        $perfil_keys= ['realname', 'nickname', 'ciudad', 'wsp', 'descripcion'];
        $tags_keys= ['tags'];

        $ciudades = new \mCiudades;
        
        $region = $ciudades->GetRegion($f3->get('POST.ciudad'));

        $filtered_perfil_data = array_intersect_key($f3->get('POST'), array_flip($perfil_keys));
        $perfil = new \mPerfiles($id);

        $filtered_tags_data = array_intersect_key($f3->get('POST'), array_flip($tags_keys));
        $tags = new \mTags;
        $enlace = new \mTag_Perfil;

        $perfil->Guardar($id, $filtered_perfil_data, $region);
        
        if (strlen($filtered_tags_data['tags'])>0) $tags->Agregar($filtered_tags_data);

        $enlace->DeleteByIdUser($id);
        $enlace->AgregarTags($id, $tags->GetId($filtered_tags_data['tags']));

        \Elcatalogo::actualizarSession($perfil);
// TODO: actualizar session con los nuevos datos

        $f3->set('mensaje', array(
            'tipo'=>'alert-success',
            'rol'=>'alert',
            'titulo'=>'Actualizacion de perfil',
            'contenido'=>'Tu Perfil ha sido actualizado correctamente')
        );


        //$f3->set('vista',\Template::instance()->render(self::pagina));
        $f3->reroute('/privado/perfil');
  

    }
// TODO: incoprorar comentarios en perfil
// TODO: incorporar registro usuarios para poder comentar




}



?>