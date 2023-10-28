<?php
session_start();
require_once './_vistas/layout.php';
require_once('./_librerias/php/conexiones.php');
require_once('./_librerias/php/funciones.php');

class validacion{

	private $_parm;
    public  function __construct($_parm=null)
	{
		$Servicio = $_parm["servicio"];
		$componente = $_parm["componente"];
		$Usuario = $_parm["user"];
		$Pregunta = $_parm["preg"];
		$TipoError = $_parm["te"];
		
		$cnOwlPDO = PDOConnection();
	    $layout  = new Layout();
		
		
        $reg_datos_Servicio = datosAlmacenMovimientos($Servicio,$cnPDO);
        $reg_datos_usuario = datosUsuario($Usuario,$cnPDO);
		
   
		$propiedadesEntidad = array();
        $propiedadesEntidad['reg_datos_Servicio'] = $reg_datos_Servicio;		
        $propiedadesEntidad['reg_datos_usuario'] = $reg_datos_usuario;		
        $propiedadesEntidad['datosUser'] = $datosUser;	
        $propiedadesEntidad['empresa'] = $empresa;		

	    $datos = array();
        $datos['propiedadesEntidad'] = $propiedadesEntidad;  		
				
	
		
		echo $layout->main($this->viewHome($reg_datos_Servicio,$Servicio,$componente,$Usuario,$TipoError,$reg_datos_usuario,$cnOwlPDO),$datos);

	}


	public function viewHome($reg_datos_Servicio,$Servicio,$componente,$Usuario,$TipoError,$reg_datos_usuario, $cnOwlPDO) {

		$layout  = new Layout();
		$busqueda = get("busqueda");
		
	    // vd($reg_datos_usuario);
		
		$datos = array();		
		$datos['reg_datos_Servicio'] = $reg_datos_Servicio;
		$datos['Servicio'] = $Servicio;
		$datos['componente'] = $componente;
		$datos['TipoError'] = $TipoError;
		$datos['NroParticipantes'] = $NroParticipantes;
		$datos['Usuario'] = $Usuario;
		$datos['reg_datos_usuario'] = $reg_datos_usuario;

		 
		return $layout->render('./_vistas/validacion.phtml',$datos);

	}		

	

	public function formContacto($arg) {

	
		return $arg;

	}		

	

}