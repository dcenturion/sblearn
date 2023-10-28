<?php

	 
	require_once('sbookstores/php/conection.php');
	require_once('sbookstores/php/functions.php');
	require_once 'sbookstores/php/route.php';
	
	require_once 'sviews/home.php';
	require_once 'sviews/about.php';
	require_once 'sviews/serviceall.php';
	require_once 'sviews/contact.php';
    require_once 'sadministrator/sviews/edu-blog.php';	
    require_once 'sadministrator/sviews/edu-imagen.php';	
    require_once 'sadministrator/sviews/edu-articulo-det.php';	
    require_once 'sviews/petroperu.php';	
    require_once 'sviews/yachai.php';	
    require_once 'sviews/ecopetroleum.php';	
    require_once 'sviews/esgep.php';	
    require_once 'sviews/elda.php';	
    require_once 'sviews/edgem.php';	
    require_once 'sviews/amaya.php';	
    require_once 'sviews/vacio.php';	
    require_once 'sviews/andinaglobal.php';	
    require_once 'sviews/unidossipodemos.php';	
    require_once 'sviews/catalogo-productos.php';
    require_once 'sviews/nosotros.php';
    require_once 'sviews/recuperar_password.php';	
    require_once 'sviews/egac.php';	
    require_once 'sviews/abescuela.php';	
    require_once 'sviews/praestantia.php';	


	error_reporting(E_ERROR);
	$route  = new Route();
	$route->add('/home','Home');
	$route->add('/about','About');
	$route->add('/serviceall','ServiceAll');
	$route->add('/petroperu','petroperu');
	$route->add('/yachai','yachai');
	$route->add('/ecopetroleum','ecopetroleum');
	$route->add('/esgep','esgep');
	$route->add('/vacio','vacio');
	$route->add('/andinaglobal','andinaglobal');
	$route->add('/edgem','edgem');
	$route->add('/elda','elda');
	$route->add('/amaya','amaya');
	$route->add('/egac','egac');
	$route->add('/abescuela','abescuela');
	$route->add('/praestantia','praestantia');
	$route->add('/unidossipodemos','unidossipodemos');
	$route->add('/catalogo-productos','CatalogoProductos');
	$route->add('/nosotros','Nosotros');
	$route->add('/recuperar-password','RecuperarPasword');


	// $route->add_subdirectorio('/edu-blog','Edu_Blog');
	// $route->add_subdirectorio('/edu-articulo-det','Edu_Articulo_Det');
	// $route->add_subdirectorio('/edu-imagen','Edu_Imagen');

	$route->submit();


?>