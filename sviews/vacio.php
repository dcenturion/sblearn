<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once './sviews/layout.php';
require_once('./sbookstores/php/conection.php');
require_once('./sbookstores/php/functions.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class vacio{
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
		, ET.Color_Texto
		, ET.Url
		FROM entity ET 

		WHERE 
		ET.Url = :Url 
		"; 		
		$Where = ["Url"=>"esgep"];				
		$Row = ClassPDO::DCRow($Query,$Where,$Conection);	
         // var_dump($Row->Logo_Interno);		

		$datos = array();
		$datos["Img_Logo"] = $Row->Logo_Interno;
		$datos["Url"] = $Row->Url;
		$datos["Color"] = $Row->Color_Menu_Horizontal;
		$datos["Color_Texto"] = $Row->Color_Texto;
	
	    
		$BodyPage = $layout->render('./sviews/conten_page.phtml',$datos);

		$html = '<script>
			
			localStorage.setItem("Session_Entidad", ""); 
			
		     </script> ';
		echo $html;
					
		echo $layout->main($BodyPage,$datos);
	}
	

}