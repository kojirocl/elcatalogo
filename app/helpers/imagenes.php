<?php
/*
/uploads/
  /original/     - Imagen original sin tocar
  /profile/      - 150x150px para perfiles  
  /carousel/     - 800x600px para carrusel
  /thumb/        - 300x200px para listados

*/
class Imagenes{
    const ruta = 'uploads/';
    const ruta_original = 'original/';
    const ruta_profile = self::ruta.'profile/';
    const ruta_carrusel = self::ruta.'carrusel/';
    const ruta_thumb = self::ruta.'thumbs/';

    const dim = array(
        'profile' => array(150,150),
        'carrusel' => array(800,600),
        'thumb' => array(300,200)
    );

    /////////////////////////////////////////////////////////////////
    static function img_seguridad(){
        $img= new \Image;
        $img->captcha('fonts/CHILLER.TTF',35,5,'SESSION.captcha_code');
        $img->render();
    }

    static function actualiza_foto_perfil($id_foto_perfil){
        
        $media = new \mMedia($id_foto_perfil);
        
        if ($media->dry()){
            $ubicacion ="";
        }else{
            //$ubicacion = $media->ubicacion; //nombre de archivo;
            self::borrar_profile($id_foto_perfil); // borra la foto de perfil anterior
            self::crear_profile($id_foto_perfil); // crea la nueva foto de perfil
            $ubicacion = self::ruta_profile . $media->ubicacion; // ruta completa a la foto de perfil
        }
        \Base::instance()->set('SESSION.foto.ubicacion', $ubicacion);
    }

    function generar_perfiles(){
        $perfil = new \mPerfiles();

        $result = $perfil->find();

        foreach ($result as $usuario){
            if ($usuario->idFotoPerfil !==0) 
                self::actualiza_foto_perfil($usuario->idFotoPerfil);

        }


    }

    static function procesar_fotos(){
    
        $web = \Web::instance();
        
        $callback = function($archivo){ // QUE SE HACE DESPUES DE SUBIR LA FOTO
            $nombreArchivo = basename($archivo['name']); //guarda en base de datos

            $id = \Base::instance()->get('SESSION.usuario.id'); 
            $result = new \mMedia($id, $nombreArchivo);
            return true; // allows the file to be moved from php tmp dir to your defined upload dir
        };

        $overwrite = true; // set to true, to overwrite an existing file; Default: false
        
        $slug = function($fileBaseName){  //$slug = true; // rename file to filesystem-friendly version
            $id = \Base::instance()->get('SESSION.usuario.id');
            $fileBaseName = hash('md5', $id."_".$fileBaseName).'.'.pathinfo($fileBaseName,PATHINFO_EXTENSION); 
            return self::ruta_original.$fileBaseName; // desde uploads/
        };

        $archivos = $web->receive($callback, $overwrite, $slug);
        
        // MUESTRA LOS ARCHIVOS QUE SE SUBIERON
        //crea la thumb, carrusel
        foreach ($archivos as $rutaCompleta => $resultado) {
            $nombreArchivo = basename($rutaCompleta);
            self::transformar_fotografia($nombreArchivo, 'thumb');
            self::transformar_fotografia($nombreArchivo, 'carrusel');
        }
        
    }

    static function transformar_fotografia($archivo, $tipo){ // ruta completa al archivo
        /*
        /original/     - Imagen original sin tocar
        /profile/      - 150x150px para perfiles  
        /carousel/     - 800x600px para carrusel
        /thumb/        - 300x200px para listados */
        
        switch ($tipo){
            case 'profile':
                $ruta_destino = self::ruta_profile;
                $x = self::dim['profile'][0]; 
                $y = self::dim['profile'][1];
                break;
            case 'carrusel':
                $ruta_destino = self::ruta_carrusel;
                $x = self::dim['carrusel'][0]; 
                $y = self::dim['carrusel'][1];
                break;
            case 'thumb':
                $ruta_destino = self::ruta_thumb;
                $x = self::dim['thumb'][0]; 
                $y = self::dim['thumb'][1];                
                break;
            default: // perfil
                $ruta_destino = self::ruta_profile;
                $x = self::dim['profile'][0]; 
                $y = self::dim['profile'][1];
                break;
        }

        $ruta = \Base::instance()->get('UPLOADS'). self::ruta_original; // ruta del archivo original

        $img = new \Image($archivo); //paso ubicacion completa del archivo origen

        $img->resize($x, $y ,false,false);

        $nombre_archivo = $ruta_destino.pathinfo($archivo,PATHINFO_BASENAME); // ruta destino + nombre archivo
        $extension = pathinfo($archivo,PATHINFO_EXTENSION);
        
        if ($extension == 'jpg' || $extension == 'jpeg') $extension = 'jpeg';

        \Base::instance()->write($nombre_archivo, $img->dump($extension));

    }

    function generar_todas_miniaturas(){

        $directorio = 'uploads/original/';
        $archivos = scandir($directorio); // solo nombre de archivo

        foreach ($archivos as $archivo)
            if ($archivo != '.' && $archivo != '..'){
                self::transformar_fotografia($directorio.$archivo, 'thumb'); //paso ubicacion completa
                self::transformar_fotografia($directorio.$archivo, 'carrusel'); //paso ubicacion completa
                echo $directorio.$archivo.'<br>';
            }
        echo '<pre>';
        var_dump('estos son los archivos', $archivos);
        echo '</pre>';

        echo '<br><h1>miniaturas creadas con exito!</h1>';

    }

    static function crear_profile($idImagen){ // id de la imagen

        $imagen = new \mMedia($idImagen);
        $ruta_origen = self::ruta.self::ruta_original;
        self::transformar_fotografia($ruta_origen.$imagen->ubicacion , 'profile');


    }

    static function borrar_profile($idImagen){ // id de la imagen
        $imagen = new \mMedia($idImagen);
        $ruta = self::ruta_profile . $imagen->ubicacion;
        if (file_exists($ruta)){
            unlink($ruta);
        }
    }

}

?>