<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/functions.php');
// require_once('./sviews/layout.php');

class Certificado_Public{

	private $Parm;
    public  function __construct($Parm=null)
	{
		
		
        $key =  DCDesencriptar($_GET["key"]);
		
		$Query = "
		SELECT EGC.Id_Suscripcion
		, EGC.Nota
		, EGC.Estado_Academico  
		, EGC.Id_Edu_Almacen  
		, EGC.Id_Edu_Pais  
		, EGC.Id_Edu_Certificado  
		, EGC.Tipo_Producto  
		, EGC.Entity  
		FROM 
		edu_certificado EGC
		WHERE EGC.Codigo_Sistema = :Codigo_Sistema 
		";
		$Where = ["Codigo_Sistema" => $key];
		$Rows = ClassPdo::DCRow($Query,$Where,$Conection);					
		$Id_Suscripcion_User = $Rows->Id_Suscripcion;		
		$Id_Edu_Almacen = $Rows->Id_Edu_Almacen;		
		$Id_Edu_Certificado = $Rows->Id_Edu_Certificado;		
		$Id_Edu_Almacen = $Rows->Id_Edu_Almacen;		
		$Tipo_Producto = $Rows->Tipo_Producto;
		$Entity = $Rows->Entity;
		
        if(empty($Tipo_Producto	)){
			$Tipo_Producto = "curso";
		}
		
		
		
		
		if( ! empty( $Id_Edu_Certificado 	)){
			
			$_SESSION['Entity'] = $Entity;

			$DCPanelTitle_Msj = "<iframe width='100%' height='600px' src='/sadministrator/edu-certificado/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/interface/List/tipo-producto/".$tipo_producto."/tipo_visualizacion/demo/request/on/'></iframe>";
			// $DCPanelTitle_Msj = "<iframe width='100%' height='600px' src='/sadministrator/sviews/edu-genera-certificado.php?key=".$Id_Edu_Almacen."&Id_Edu_Certificado=".$Id_Edu_Certificado."&interface=List&tipo-producto=".$Tipo_Producto."&tipo_visualizacion=demo&request=on'></iframe>";
			echo $DCPanelTitle_Msj;	
			
		}else{
			
			echo "El linnk no es correcto";	
		}
	}
	
}	
// key

?>