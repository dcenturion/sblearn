<?php
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Objects{

	public function MainData($Parm) {
        
		global $Conection;
		
		$Query = "
		
			SELECT OB.Id_Object, OB.Name, OB.Id_Warehouse, OB.Id_Type_Form, TF.Name as Type_Form_Name
			FROM object OB 
			INNER JOIN type_form TF ON OB.Id_Type_Form = TF.Id_Type_Form
			WHERE OB.Id_Object = :Id_Object
			
		";	
		$Where = ["Id_Object" => $Parm["Id_Object"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		return $Row;
        
	}
	
}

class ObjectsDetail{

	public function MainData($Parm) {
        
		global $Conection;
		
		$Query = "
		SELECT OD.Id_Object_Detail, OD.Name
		, OD.Id_Data_Type, OD.Pk ,OD.Width
		, OD.Alias, OB.Id_Warehouse
		, OD.Id_Warehouse_Detail
		, OD.Id_Field_Type
		, OD.Matriz_Data_Select
		, OD.Type_Process_BD
		, OD.Archive_Server_Aplication
		, OD.Archive_Server_Warehouse
		, OD.Archive_Server_YouTube
		, OD.Extencions
		, OD.Format_Field
		, OD.Path_Image
		, OD.Size_File
		FROM object_detail OD 
		INNER JOIN object OB ON OD.Id_Object = OB.Id_Object
		WHERE OD.Id_Object_Detail = :Id_Object_Detail
		";	
		$Where = ["Id_Object_Detail" => $Parm["Id_Object_Detail"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				// var_dump($Parm["Id_Warehouse_Detail"]);
				// var_dump($Row);
		return $Row;
        
	}
	
}



