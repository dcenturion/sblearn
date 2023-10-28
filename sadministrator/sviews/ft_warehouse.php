<?php
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Warehouse{

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

	
	public function Tabs_Empresa($arg){
		
		$menu = "Empresas]/sadministrator/admin-empresa]ScreenRight]Marca}";
		$menu .= "Objects]/sadministrator/admin_object]ScreenRight}";
		$menu .= "Editor]/sadministrator/skeditor]ScreenRight}";
		
		$pestanas = DCTabs($menu, 'nav nav-tabs nav-tabs-custom m-b-15','Menu_One',$arg);
		return $pestanas;
	}

	
}

class WarehouseDetail{

	public function MainData($Parm) {
        
		global $Conection;
		
		$Query = "SELECT WHD.Id_Warehouse_Detail, WHD.Name, WHD.Id_Data_Type, WHD.Pk ,WHD.Length, WHD.Id_Warehouse
		FROM warehouse_detail WHD 
		WHERE WHD.Id_Warehouse_Detail = :Id_Warehouse_Detail
		";	
		$Where = ["Id_Warehouse_Detail" => $Parm["Id_Warehouse_Detail"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				// var_dump($Parm["Id_Warehouse_Detail"]);
				// var_dump($Row);
		return $Row;
        
	}
	
}



