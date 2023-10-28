<?php
session_start();
require_once './_vistas/layout.php';
require_once('./_librerias/php/conexiones.php');
require_once('./_librerias/php/funciones.php');

class evaluacion{

	private $_parm;
    public  function __construct($_parm=null)
	{
		
		$Servicio = $_parm["servicio"];
		
		$componente = $_parm["componente"];
		$Usuario = $_parm["user"];
		$Pregunta = $_parm["preg"];
		
		$cnOwlPDO = PDOConnection();
	    $layout  = new Layout();
		
        $Estado = validacionEstado($Servicio,$componente,$Usuario,$cnPDO);
		if($Estado == "Finalizado"){
			rd("/validacion/servicio/".$Servicio."/componente/".$componente."/user/".$Usuario."");
		}	
		
        $reg_datos_Servicio = datosAlmacenMovimientos($Servicio,$cnPDO);
        $reg_datos_usuario = datosUsuario($Usuario,$cnPDO);
		
        $datosPregunta = datosPregunta($componente,$Pregunta,$cnPDO);
		
        $primera_pregunta = pra_pregunta($componente,$cnPDO);
        $ultima_pregunta = ult_pregunta($componente,$cnPDO);

        $nroPregunta = nroPregunta($componente,$Pregunta,$cnPDO);	
		
        $pregunta_siguiente = pregunta_siguiente($componente,$Pregunta,$nroPregunta,$cnPDO);

        $pregunta_anterior = pregunta_anterior($componente,$Pregunta,$nroPregunta,$cnPDO);

		$propiedadesEntidad = array();
        $propiedadesEntidad['reg_datos_Servicio'] = $reg_datos_Servicio;		
        $propiedadesEntidad['reg_datos_usuario'] = $reg_datos_usuario;		
        $propiedadesEntidad['datosUser'] = $datosUser;	
        $propiedadesEntidad['empresa'] = $empresa;		

	    $datos = array();
        $datos['propiedadesEntidad'] = $propiedadesEntidad;  		
				
		$Datos_Evaluacion = array();
        $Datos_Evaluacion['primera_pregunta'] = $primera_pregunta;  
        $Datos_Evaluacion['ultima_pregunta'] = $ultima_pregunta;  
        $Datos_Evaluacion['nroPregunta'] = $nroPregunta;  
        $Datos_Evaluacion['pregunta_siguiente'] = $pregunta_siguiente;  
        $Datos_Evaluacion['pregunta_anterior'] = $pregunta_anterior;  
		
		echo $layout->main($this->viewHome($reg_datos_Servicio,$Servicio,$componente,$Usuario,$datosPregunta,$Datos_Evaluacion,$cnOwlPDO),$datos);

	}


	public function viewHome($reg_datos_Servicio,$Servicio,$componente,$Usuario,$datosPregunta,$Datos_Evaluacion, $cnOwlPDO) {

		$layout  = new Layout();
		$busqueda = get("busqueda");
		
		$NroParticipantes = NroParticipantes($Servicio,$componente,$Usuario,$cnOwlPDO);
		$ParticipantesEncuesta = ParticipantesEncuesta($Servicio,$componente,$datosPregunta->Codigo,$Usuario,$cnOwlPDO);
		$PreguntaConRespuestas = PreguntaConRespuestas($Servicio,$componente,$datosPregunta->Codigo,$Usuario,$cnOwlPDO);
		$RespuestaGenerada = RespuestaGenerada($Servicio,$componente,$datosPregunta->Codigo,$Usuario,$cnOwlPDO);
	   
		$datos = array();		
		$datos['reg_datos_Servicio'] = $reg_datos_Servicio;
		$datos['Servicio'] = $Servicio;
		$datos['componente'] = $componente;
		$datos['Pregunta'] = $datosPregunta->Codigo;
		$datos['NroParticipantes'] = $NroParticipantes;
		$datos['Usuario'] = $Usuario;
		$datos['datosPregunta'] = $datosPregunta;
		$datos['ParticipantesEncuesta'] = $ParticipantesEncuesta;
		$datos['PreguntaConRespuestas'] = $PreguntaConRespuestas;
		$datos['Datos_Evaluacion'] = $Datos_Evaluacion;
		$datos['RespuestaGenerada'] = $RespuestaGenerada;
		 
		return $layout->render('./_vistas/evaluacion.phtml',$datos);

	}		

	

	public function formContacto($arg) {

	
		return $arg;

	}		

	

}