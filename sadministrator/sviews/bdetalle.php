<?php

require_once './_vistas/layout.php';

class BlogDetalle{

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
		
	    
	
	
		$sql = "
		SELECT 
		Bl.Codigo, Bl.Titulo, Bl.Descripcion, DATE_FORMAT(Bl.FechaHoraCreacion, '%d %b %Y') AS Fecha 
		, Bl.Imagen, Bl.Embebido
		FROM
		blog AS Bl
		INNER JOIN sectorarticulos AS SA ON Bl.Sector = SA.Codigo
		WHERE Bl.Codigo = ".$id."
		ORDER BY Bl.FechaHoraCreacion DESC
		
		";    
		// echo "<br><br><br>".$sql;
		$blog_array = fetchAll($sql,$cnOwlPDO);

		$sql = "
		SELECT 
		SA.Codigo, SA.Descripcion
		FROM
		blog AS Bl
		INNER JOIN sectorarticulos AS SA ON Bl.Sector = SA.Codigo
		GROUP BY SA.Codigo
		ORDER BY Bl.FechaHoraCreacion DESC
		
		";    
		$categoria_array = fetchAll($sql,$cnOwlPDO);		
		
		$layout->blog_array = $blog_array;
		$layout->categoria_array = $categoria_array;
		
		return $layout->render('./_vistas/blogdetalle.phtml',$datosEvento);
	}		

	

	public function formContacto($arg) {

		

		return $arg;

	}		

	

}