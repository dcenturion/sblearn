<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('./sviews/layout.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/functions.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Home{
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
		$MenuLateralAdministrator = MenuLateralAdministrator();	
		
		
		$PanelRight = '<div class="site-content" id="ScreenRight"> Hoolalala </div> ';
		$BodyPage = '<div class="site-main"  > '.$MenuLateralAdministrator. $PanelRight .' </div> ';

		echo $layout->main($BodyPage,"");
	}
	


	public function Form_Call($Transaction) {
       	global $Conection, $DCTimeHour;

		$data = array(
		'Nombre' => DCPost("username"),
		'Email' => DCPost("email"),
		'Telefono' => DCPost("phone"),
		'Asunto' => DCPost("subject"),
		'Mensaje' => DCPost("message"),
		'Fecha_Hora_Creacion' => $DCTimeHour,
		'Fecha_Hora_Actualizacion' => $DCTimeHour
		);
		$rg = ClassPDO::DCInsert('contactado', $data, $Conection);	
		CleanForm("Form_Call");
		TemplateEmailWhite(DCPost("email"),DCPost("username"));
		DCWrite(Message("La consulta fue enviada","C"));
		
						
	}		

	

	public function Form_Suscripcion($Transaction) {
       	global $Conection, $DCTimeHour;

		$data = array(
		'Email' => DCPost("email"),
		);
		$rg = ClassPDO::DCInsert('contactado', $data, $Conection);
        // DCWrite(DCPost("email")." oooo");		
		CleanForm("Form_Suscripcion");
		TemplateEmailWhite(DCPost("email"),"");
		DCWrite(Message("La consulta fue enviada","C"));
						
	}		

}