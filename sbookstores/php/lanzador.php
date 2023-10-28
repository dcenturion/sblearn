<?php
	if (isset($_SERVER['HTTP_ORIGIN'])) {  
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");  
		header('Access-Control-Allow-Credentials: true');  
		header('Access-Control-Max-Age: 86400');   
	}  
	  
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {  
	  
		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))  
			header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");  
	  
		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))  
			header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");  
	}  
	require_once('function_b.php');

	$DCTimeHour = DCGet();
	$Conection = Conection();	

    $Id_Edu_Almacen = DCPost("Id_Edu_Almacen");
    $Id_Edu_Articulo = DCPost("Id_Edu_Articulo");
    $User = DCPost("User");
    $Entity = DCPost("Entity");
	

	$return = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];
	// var_dump($return);
	echo json_encode($return);
	echo "ahaha";
	
// $mensaje = "<img src='https://www.owlecomerce.com/_vistas/g_email.php?tipo=leido&Codigo=$Codigo&codcmailling=$Codcmailling' width='100%'/>";
// echo EMail("stiklei@americaventa.com","defs.centurion@gmail.com","Mensaje nuevo","Mensaje nuevo".$mensaje);
// echo "Se envió el correo";
		

?>