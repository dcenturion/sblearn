<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once './sviews/layout.php';
require_once('./sbookstores/php/conection.php');
require_once('./sbookstores/php/functions.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class yachai{
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

		$datos = array();
		$datos["Img_Logo"] = "";
		$datos["Url"] = "yachai";
		$datos["Color"] = "#000000";
	
	    
		$BodyPage = $layout->render('./sviews/conten_page.phtml',$datos);

		echo $layout->main($BodyPage,$datos);
	}
	

}