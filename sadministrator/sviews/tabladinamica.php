<?php
session_start();
require_once './_vistas/layout.php';
require_once('./_librerias/php/conexiones.php');
require_once('./_librerias/php/funciones.php');

class tabladinamica{

	private $_parm;
    public  function __construct($_parm=null)
	{
		
		$Servicio = $_parm["servicio"];
		
		$componente = $_parm["componente"];
		$Usuario = $_parm["user"];
		$Pregunta = $_parm["preg"];
		
		$cnOwlPDO = PDOConnection();
	    $layout  = new Layout();
		
		echo $layout->main($this->viewHome(),"");

	}


	public function viewHome() {

		$layout  = new Layout();
		$busqueda = get("busqueda");
		
	
		 
		return $layout->render('./_vistas/tabladinamica.phtml',$datos);

	}		

	

	public function formContacto($arg) {

	
		return $arg;

	}		

	

}