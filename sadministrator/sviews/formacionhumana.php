<?php

require_once './_vistas/layout.php';

class FormacionHumana{

	private $_parm;
    public  function __construct($_parm=null)
	{
		$id = $_parm["id"];
        $site = "Formacion";
	    $layout  = new Layout();

		echo $layout->mainB($this->viewHome($id),$id,$site);

	}

	

	public function viewHome($id) {

		$cnOwlPDO = PDOConnection();
		$FechaHoraSrv = FechaHoraSrv();
		$empresa = $_SESSION['empresa'];
		$user = $_SESSION['user'];	

		$layout  = new Layout();

		$datos = array();
        $datos['numeros'] = "996 614 532 / 999 777 768 ";

        $datos['direccion'] = "Lima - Perú ";

        $datos['email'] = "informes@episodiosplanning.com";
		
	    $sql = "SELECT AR.ImagenPresentacionA, MA.Codigo AS CodigoMov
		, AR.Nombre, FA.Codigo AS Familia, FA.Descripcion as FamiliaDesc
		, AR.Descripcion, LA.Descripcion as LineaDescripcion, MA.Referencias
		, MA.Inversion, MA.FormaPago, MA.FormaPago, MA.	Detalles, MA.InformesInscripciones,MA.DatosBanner
		, AR.ImagenPresentacionB
		FROM  articulos AR
		INNER JOIN movimiento_almacen MA ON AR.Codigo = MA.Articulo
		INNER JOIN lineaarticulo LA ON AR.Lineaarticulo = LA.Codigo
		INNER JOIN familiaarticulo FA ON AR.Familiaarticulo = FA.Codigo
		WHERE LA.Codigo = 4 AND MA.Codigo = {$id}
		GROUP BY MA.Codigo
		";    
		
		$rg = fetch($sql,$cnOwlPDO);
		$ImagenPresentacionA = $rg["ImagenPresentacionA"];		
		$ImagenPresentacionB = $rg["ImagenPresentacionB"];		
		$Descripcion = $rg["Descripcion"];		
		$Nombre = $rg["Nombre"];		
		$Familia = $rg["Familia"];		
		$FamiliaDesc = $rg["FamiliaDesc"];		
		$LineaDescripcion = $rg["LineaDescripcion"];		
		$Referencias = $rg["Referencias"];		
		$Inversion = $rg["Inversion"];		
		$FormaPago = $rg["FormaPago"];		
		$Detalles = $rg["Detalles"];		
		$InformesInscripciones = $rg["InformesInscripciones"];		
		$DatosBanner = $rg["DatosBanner"];		
		
	
		$sql = "SELECT AR.ImagenPresentacionA, MA.Codigo AS CodigoMov
		, AR.Nombre, FA.Codigo AS Familia
		, AR.Descripcion
		FROM articulos AR
		INNER JOIN movimiento_almacen MA ON AR.Codigo = MA.Articulo
		INNER JOIN lineaarticulo LA ON AR.Lineaarticulo = LA.Codigo
		INNER JOIN familiaarticulo FA ON AR.Familiaarticulo = FA.Codigo
		WHERE LA.Codigo = 4 AND AR.Familiaarticulo = {$Familia}
		GROUP BY MA.Codigo
		";    
		$productos_familia = fetchAll($sql,$cnOwlPDO);
		

		$sql = "
		SELECT 
			AR.ImagenPresentacionA, MA.Codigo AS CodigoMov
			, AR.Nombre, FA.Codigo AS Familia
			, AR.Descripcion
			, CU.Descripcion As Sesion
			, CU.DescripcionExtendido As DescSesion
			, CU.Temas
		FROM curricula CU
		INNER JOIN articulos AR ON CU.Articulo = AR.Codigo   
		INNER JOIN curricula_docentes CD ON CU.Codigo = CD.Curricula 
		INNER JOIN movimiento_almacen MA ON MA.Articulo = CU.Articulo
		INNER JOIN lineaarticulo LA ON AR.Lineaarticulo = LA.Codigo
		INNER JOIN familiaarticulo FA ON AR.Familiaarticulo = FA.Codigo
		WHERE  MA.Codigo = {$id}
		GROUP BY CD.Curricula 
		";    
		$curricula = fetchAll($sql,$cnOwlPDO);
		
		$sql = "
		SELECT 
			AR.ImagenPresentacionA, MA.Codigo AS CodigoMov
			, AR.Nombre, FA.Codigo AS Familia
			, AR.Descripcion
			, CU.Descripcion As Sesion
			, CU.DescripcionExtendido As DescSesion
			, CU.Temas
			, CD.Docente
			,DC.Nombres
			,DC.Descripcion
			,MA.DatosBanner
		FROM curricula_docentes CD
		INNER JOIN curricula CU ON CU.Codigo = CD.Curricula
		INNER JOIN articulos AR ON CU.Articulo = AR.Codigo   
		INNER JOIN movimiento_almacen MA ON MA.Articulo = CU.Articulo
		INNER JOIN lineaarticulo LA ON AR.Lineaarticulo = LA.Codigo
		INNER JOIN familiaarticulo FA ON AR.Familiaarticulo = FA.Codigo
		INNER JOIN docentes DC ON DC.Codigo = CD.Docente
		WHERE  MA.Codigo = {$id}
		";    
		$facultad = fetchAll($sql,$cnOwlPDO);	
		
		$datosEvento = array();
        $datosEvento['img'] = "/system/_articulos/".$ImagenPresentacionA;			
        $datosEvento['ImagenPresentacionB'] = $ImagenPresentacionB;			
        $datosEvento['NombreArticulo'] = $Nombre;			
        $datosEvento['DescripcionArticulo'] = $Descripcion;			
        $datosEvento['DatosBanner'] = $DatosBanner;			
        $datosEvento['FamiliaDesc'] = $FamiliaDesc;			
        $datosEvento['LineaDescripcion'] = $LineaDescripcion;			
        $datosEvento['Inversion'] = $Inversion;		
        $datosEvento['FormaPago'] = $FormaPago;		
        $datosEvento['Detalles'] = $Detalles;		
        $datosEvento['Referencias'] = $Referencias;	
        $datosEvento['InformesInscripciones'] = $InformesInscripciones;	
				
		// $layout->productos_familia = $productos_familia;
		$layout->curricula = $curricula;
		$layout->facultad = $facultad;
		$layout->formContacto = $layout->render("./_vistas/form_contactos.phtml",$datos); 

		return $layout->render('./_vistas/formacionhumana.phtml',$datosEvento);

		

	}		

	

	public function formContacto($arg) {

		

		return $arg;

	}		

	

}