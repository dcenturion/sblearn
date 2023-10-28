<?php
require_once('./sviews/layout.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/functions.php');
class ComponentePage{

	private $Parm;
    public  function __construct($Parm=null)
	{
	
	    $layout  = new Layout();


		
	
		$Contenido = "Componentes de la página";
		if($Parm["Request"] == "On"){
		    
			$MenuLateralAdministrator = MenuLateralAdministrator();	
		
			$PanelRight = '<div class="site-content" id="ScreenRight"> '.$Contenido.'  </div> ';
			$BodyPage = '<div class="site-main"  > '.$MenuLateralAdministrator. $PanelRight .' </div> ';
				
		    DCWrite($layout->main($BodyPage,$datos));		
			
		}else{
			
		    DCWrite($Contenido);			
		}
		

	}

	
}