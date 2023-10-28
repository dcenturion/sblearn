<?php
    

	require_once('sbookstores/php/conection.php');
	require_once('sbookstores/php/functions.php');
	require_once 'sbookstores/php/route.php';
	
	require_once 'sviews/home.php';
	// require_once 'sviews/about.php';
	// require_once 'sviews/serviceall.php';
	// require_once 'sviews/contact.php';
    // require_once 'sadministrator/sviews/edu-blog.php';	
    // require_once 'sadministrator/sviews/edu-imagen.php';	
    // require_once 'sadministrator/sviews/edu-articulo-det.php';
	// require_once 'sviews/recuperar_password.php';	
	// require_once 'sviews/certificado.php';	
	// echo "22";
	// require_once 'sviews/evaluacion.php';
	
	// require_once 'sviews/comentario.php';

	// require_once 'sviews/finalf.php';
	// require_once 'sviews/validacion.php';
	// require_once 'sviews/tabladinamica.php';
	


	error_reporting(E_ERROR);
	$route  = new Route();
	$route->add('/home','Home');
	// $route->add('/about','About');
	// $route->add('/serviceall','ServiceAll');
	
	// $route->add_subdirectorio('/edu-blog','Edu_Blog');
	// $route->add_subdirectorio('/edu-articulo-det','Edu_Articulo_Det');
	// $route->add_subdirectorio('/edu-imagen','Edu_Imagen');

	// $route->add('/evaluacion','evaluacion');
	// $route->add('/comentario','comentario');
	// $route->add('/finalf','finalf');
	// $route->add('/validacion','validacion');
	// $route->add('/tabladinamica','tabladinamica');
    // $route->add('/recuperar-password','RecuperarPasword');
	$route->submit();


?>