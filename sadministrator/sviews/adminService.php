<?php
session_start();
require_once './_vistas/layout.php';
require_once('./_librerias/php/conexiones.php');
require_once('./_librerias/php/funciones.php');
	
class AdminService{

	
    public  function __construct($_parm=null){
		
		$layout  = new Layout();
		
		$cnOwlPDO = PDOConnection();
		$FechaHoraSrv = FechaHoraSrv();
		$empresa = $_SESSION['empresa'];
		$user = $_SESSION['user'];
		
		$transacion = get("transacion");
		
		$value = $_parm["form"];
		$id = $_parm["id"];
		
		if( $value == "accion" && $id == ""){
            			
			$Nombre = post("Nombre");
			$Descripcion = post("Descripcion");
			$Tipoarticulos = post("Tipoarticulos");
			$Sectorarticulos = post("Sectorarticulos");
			$Estado = post("Estado");
						
			$tableValue 	=	[];
			$tableValue["Nombre"] =   $Nombre;
			$tableValue["Descripcion"] =   $Descripcion;
			$tableValue["Tipoarticulos"] =   $Tipoarticulos;
			$tableValue["Sectorarticulos"] =   $Sectorarticulos;
			$tableValue["Estado"] =   $Estado;
			$tableValue["FechaHoraActualizacion"] =   $FechaHoraSrv;
			$tableValue["FechaHoraCreacion"] =   $FechaHoraSrv;
			$tableValue["UsuarioCreacion"] =   $user;
			$tableValue["UsuarioActualizacion"] =   $user;
			$tableValue["Empresa"] =   $empresa;
			
			$return 			= 	insertPDO("articulos",$tableValue,$cnOwlPDO);
			
			$viewdata = array();
			$viewdata['mensaje'] = "correcto";		
			echo json_encode($viewdata);
			
		}elseif($value == "crear" && $id == ""){

			$sql = "SELECT Codigo, Descripcion
			FROM tipoarticulos
			";
			$tipo_articulos = fetchAll($sql,$cnOwlPDO);	

			$sql = "SELECT Codigo, Descripcion
			FROM sectorarticulos
			";
			$sectorarticulos = fetchAll($sql,$cnOwlPDO);	
	
			$layout->sectorarticulos = $sectorarticulos; 
			$layout->tipo_articulos = $tipo_articulos; 
			$layout->tituloPopup = "Crear Servicio";	
			$vista =  $layout->render("./_vistas/form_service.phtml"); 
		
			echo $vista;	
			
		}elseif($value == "update" && $id !== ""){

			$sql = "SELECT Codigo, Descripcion
			FROM tipoarticulos
			";
			$tipo_articulos = fetchAll($sql,$cnOwlPDO);	

			$sql = "SELECT Codigo, Descripcion
			FROM sectorarticulos
			";
			$sectorarticulos = fetchAll($sql,$cnOwlPDO);	

			
			$sql = "SELECT 
			Codigo, Nombre,Descripcion, Estado, FechaHoraActualizacion
			, UsuarioActualizacion, Tipoarticulos, Precio
			FROM articulos 
			WHERE Codigo = ".$id."
			ORDER BY Codigo DESC
			";
		    $articulos = fetch($sql,$cnOwlPDO);		
			
			$layout->sectorarticulos = $sectorarticulos; 
			$layout->tipo_articulos = $tipo_articulos; 
			$layout->articulos_reg = $articulos; 
			$layout->tituloPopup = "Editar Servicio";	
			$vista =  $layout->render("./_vistas/form_service.phtml"); 
		
			echo $vista;	
			
		}else{
			
	
			
			$sql = "SELECT 
			Codigo, Nombre, Estado, FechaHoraActualizacion
			, UsuarioActualizacion, Tipoarticulos, Precio
			FROM articulos 
			WHERE Empresa = 25
			ORDER BY Codigo DESC
			";
		    $articulos = fetchAll($sql,$cnOwlPDO);	
			
			$sql = "SELECT Codigo, Descripcion
			FROM tipoarticulos
			";
		    $tipo_articulos = fetchAll($sql,$cnOwlPDO);				

			$sql = "SELECT Codigo, Descripcion
			FROM sectorarticulos
			";
		    $sectorarticulos = fetchAll($sql,$cnOwlPDO);				
			
			echo $layout->dashboard($this->viewHome($articulos,$tipo_articulos,$sectorarticulos,$transacion));			
				
		}
	}

	public function viewHome($articulos,$tipo_articulos,$sectorarticulos,$transacion){
		
		$layout  = new Layout();
	    $layout->listado = $articulos; 
	    $layout->tipo_articulos = $tipo_articulos; 
	    $layout->sectorarticulos = $sectorarticulos; 
	    $layout->transacion = $transacion; 

		return $layout->render('./_vistas/adminservice.phtml');
	}		
	
}

