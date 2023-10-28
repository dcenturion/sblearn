<?php
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class BlogObject{

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
		
		$menu = "Publicaciones]/sadministrator/blog]ScreenRight]Marca]}";
		$menu .= "Categorías ]/sadministrator/categoria_blog]ScreenRight]]}";
		// $menu .= "Artículo ]/sadministrator/articulo]ScreenRight]]}";
		// $menu .= "Clientes]/sadministrator/admin_object]ScreenRight]]}";
		
		$pestanas = DCTabs($menu, 'nav nav-tabs nav-tabs-custom m-b-15','Menu_One',$arg);
		return $pestanas;
	}


	
}

