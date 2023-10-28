<?php

	 
	require_once('sbookstores/php/conection.php');
	require_once('sbookstores/php/functions.php');
	require_once 'sbookstores/php/route.php';
	
	require_once 'sviews/home.php';
    require_once 'sviews/inicio.php';	
    require_once 'sviews/vacio.php';	
    require_once 'sviews/catalogo-productos.php';
    require_once 'sviews/nosotros.php';
    require_once 'sviews/recuperar_password.php';	
    require_once 'sviews/certificado.php';	


	error_reporting(E_ERROR);
	$route  = new Route();
	$route->add('/home','Home');
	$route->add('/inicio','inicio');
	$route->add('/vacio','vacio');

	$route->add('/catalogo-productos','CatalogoProductos');
	$route->add('/nosotros','Nosotros');
	$route->add('/recuperar-password','RecuperarPasword');
	$route->add('/certificado','certificado');


	// $route->add_subdirectorio('/edu-blog','Edu_Blog');
	// $route->add_subdirectorio('/edu-articulo-det','Edu_Articulo_Det');
	// $route->add_subdirectorio('/edu-imagen','Edu_Imagen');

	$route->submit();


?>