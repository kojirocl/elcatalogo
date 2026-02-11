<?php
namespace Publico;

class General{
	const ruta = 'frontend/default_copy.html';

	function afterRoute(){

		$f3 = \Base::instance();
		$assets = \Assets::instance(); 
        //CSS
        $assets->addCss('bootstrap/css/bootstrap.css',5,'head');
        $assets->addCss('css/bootstrap-icons.css');
		$assets->addCss('css/elcatalogo.css',4,'head');
		//$assets->addCss('css/elcatalogo_oscuro.css',3,'head');

		// JS
		$assets->addJs('bootstrap/js/bootstrap.min.js',5,'head');
		

		$f3->set('items_menu', \Elcatalogo::armarMenu());

		echo \Template::instance()->render(self::ruta);
		

    }

}
