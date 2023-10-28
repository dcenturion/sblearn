<?php
require_once './sviews/layout.php';
require_once('./sbookstores/php/conection.php');
require_once('./sbookstores/php/functions.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Contact{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour;
		
		$Transaction = $Parm["Transaction"];

        switch ($Transaction) {
            case "INSERT":
		        $this->Form_Call($Transaction);
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

		
		$Servicio = $_parm["servicio"];
		$componente = $_parm["componente"];
		$Usuario = $_parm["user"];
		$Pregunta = $_parm["preg"];
		$TipoError = $_parm["te"];
	    $layout  = new Layout();
	    $datos = array();
     
		$BodyPage = $layout->render('./sviews/contact.phtml',$datos);		
		echo $layout->main($BodyPage,$datos);

	}

	public function Form_Call($Transaction) {
       	global $Conection, $DCTimeHour;

		$data = array(
		'Nombre' => DCPost("username"),
		'Email' => DCPost("email"),
		'Telefono' => DCPost("phone"),
		'Asunto' => "Contactar",
		'Mensaje' => DCPost("message"),
		'Fecha_Hora_Creacion' => $DCTimeHour,
		'Fecha_Hora_Actualizacion' => $DCTimeHour
		);
		$rg = ClassPDO::DCInsert('contactado', $data, $Conection);	
		CleanForm("Form_Call");
		TemplateEmailWhite(DCPost("email"),DCPost("username"));
		DCWrite(Message("La consulta fue enviada","C"));
		
						
	}		
		

	

}