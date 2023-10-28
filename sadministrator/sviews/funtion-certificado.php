<?php
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Ft_Certificado{

	public function MainData($Parm) {
        
		global $Conection;
		
		$Query = "SELECT WH.Id_Warehouse, WH.Name  
		FROM warehouse WH 
		WHERE WH.Id_Warehouse = :Id_Warehouse
		";	
		$Where = ["Id_Warehouse" => $Parm["Id_Warehouse"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		return $Row;
        
	}
	
	public function Tabs($arg){
		
		$menu = " Warehouse ]/sadministrator/admin_tools_site]ScreenRight]Marca}";
		$menu .= "Objects]/sadministrator/admin_object]ScreenRight}";
		$menu .= "Editor]/sadministrator/skeditor]ScreenRight}";
		
		$pestanas = DCTabs($menu, 'nav nav-tabs nav-tabs-custom m-b-15','Menu_One',$arg);
		return $pestanas;
	}

	
	public function Tabs_Principal($arg){
		
		$menu = "Gestión de Plantilla]/sadministrator/edu-gestion-certificado]ScreenRight]Marca}";
		$menu .= "Cursos en Vivo]/sadministrator/edu-gestion-certificado]ScreenRight}";
		$menu .= "Cursos Grabados]/sadministrator/edu-gestion-certificado]ScreenRight}";
		// $menu .= "Grabado - C. Digitales]/sadministrator/edu-gestion-certificado]ScreenRight}";
		// $menu .= "Grabado - C. Físicos]/sadministrator/edu-gestion-certificado]ScreenRight}";
		
		$pestanas = DCTabs($menu, 'nav nav-tabs nav-tabs-custom m-b-15','Menu_One',$arg);
		return $pestanas;
	}

	
}




