<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once './sviews/layout.php';
require_once('./sbookstores/php/conection.php');
require_once('./sbookstores/php/functions.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class inicio{
	private $Parm;

    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour;
		
		
		$Transaction = $Parm["Transaction"];
		$Form = $Parm["Form"];

        switch ($Transaction) {
            case "INSERT":

				switch ($Form) {
					case "Form_Call":
						$this->Form_Call($Transaction);
						break;	
					case "Form_Suscripcion":
						$this->Form_Suscripcion($Transaction);
						break;							
				}			
				
				DCExit();
                break;
            case "UPDATE":

                break;
            case "DELETE":

                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":

                break;				
        }		

	    $layout  = new Layout();

		$Query = "
		SELECT 
		ET.Id_Entity
		, ET.Logo_Externo
		, ET.Logo_Interno
		, ET.Color_Menu_Horizontal
		, ET.Color_Fondo_Boton
		, ET.Color_Texto_Boton
		, ET.Color_Texto_Menu
		, ET.Color_Texto
		, ET.Url
		, ET.Nosotros
		, ET.Estado_Nosotros
		FROM entity ET 

		WHERE 
		ET.Url = :Url 
		"; 		
		$Url_Link = "inicio";
		$Where = ["Url"=>$Url_Link];				
		$Row = ClassPDO::DCRow($Query,$Where,$Conection);	

		$datos = array();
		$datos["Img_Logo"] = $Row->Logo_Externo;
		$datos["Url"] = $Row->Url;
		$datos["Color"] = $Row->Color_Menu_Horizontal;
		$datos["Color_Texto"] = $Row->Color_Texto;
		$datos["Color_Fondo_Boton"] = $Row->Color_Fondo_Boton;
		$datos["Color_Texto_Boton"] = $Row->Color_Texto_Boton;
		$datos["Color_Texto_Menu"] = $Row->Color_Texto_Menu;
		$datos["Nosotros"] = $Row->Nosotros;
		$datos["Estado_Nosotros"] = $Row->Estado_Nosotros;
		$datos["Id_Entity"] = $Row->Id_Entity;
		$datos["Url"] = $Url_Link;
	
	    
		$BodyPage = $layout->render('./sviews/conten_page.phtml',$datos);

		echo $layout->main($BodyPage,$datos);
	}
	

}