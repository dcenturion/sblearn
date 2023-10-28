<?php
require_once './sviews/layout.php';
require_once('./sbookstores/php/conection.php');
require_once('./sbookstores/php/functions.php');
class ServiceAll{

	private $_parm;
    public  function __construct($_parm=null)
	{
		$Servicio = $_parm["servicio"];
		$componente = $_parm["componente"];
		$Usuario = $_parm["user"];
		$Pregunta = $_parm["preg"];
		$TipoError = $_parm["te"];
		
		$Cn = Conection();
	    $layout  = new Layout();
	
		$Servicios = $layout->render('./sviews/serviceall_list.phtml',"");	
		$Indicadores = $layout->render('./sviews/indicadores.phtml',"");	
		$Projects = $layout->render('./sviews/projects.phtml',"");	
		$Teams = $layout->render('./sviews/list_team.phtml',"");	
		$Testimonial = $layout->render('./sviews/testimonial.phtml',"");	
		$Clients = $layout->render('./sviews/clients.phtml',"");	
		
		$datos = array();
		$datos["Servicios"] = $Servicios;
		$datos["Indicadores"] = $Indicadores;
		$datos["Projects"] = $Projects;
		$datos["Teams"] = $Teams;
		$datos["Testimonial"] = $Testimonial;
		$datos["Clients"] = $Clients;
		
		$BodyPage = $layout->render('./sviews/serviceall.phtml',$datos);		
		echo $layout->main($BodyPage,$datos);

	}

		

	

}