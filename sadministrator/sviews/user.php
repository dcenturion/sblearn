<?php
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class User{

	public function MainData($Parm) {
        
		global $Conection;
		
		// var_dump($Parm);
				
		$Id_Entity = $_SESSION['Entity'];
		$Id_User = $_SESSION['User'];
						   // var_dump($_SESSION['User']);
		if(empty($Id_Entity)){
			
			// $Query = "
			// SELECT 
			// ET.Id_Entity
			// , ET.Logo_Externo
			// , ET.Logo_Interno
			// , ET.Url
			// FROM entity ET 
			// WHERE 
			// ET.Url = :Url 
			// ";
 			// $Where = ["Url"=>$Parm['Ie']];	
		    // $Row = ClassPDO::DCRow($Query,$Where,$Conection);	
			// $_SESSION['Entity'] = $Row->Id_Entity;
			
		}else{
			
			if(!empty($_SESSION['Entity']) && !empty($_SESSION['User'])){
				
				$Query = "
				SELECT 
				
					US.Usuario_Login
					, US.Password
					, UM.Nombre
					, ET.Id_Entity
					, US.Id_User
					, UM.Id_Perfil
					, UM.Email
					, UM.Foto
					, ET.Logo_Externo
					, ET.Logo_Interno
					, ET.Color_Menu_Horizontal
					, ET.Color_Texto
					FROM user_miembro UM
					INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
					INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
					
				WHERE 
				UM.Entity = :Entity 
				AND UM.Id_User_Miembro = :Id_User_Miembro
				
				"; 		

				$Where = ["Entity"=>$Id_Entity,"Id_User_Miembro"=>$Id_User];				
				$Row = ClassPDO::DCRow($Query,$Where,$Conection);
				
				
				
            }else{
				
				$Query = "
				SELECT 
				
					US.Usuario_Login
					, US.Password
					, ET.Id_Entity
					, US.Id_User
					, ET.Logo_Externo
					, ET.Logo_Interno
					, UM.Email
					, ET.Color_Menu_Horizontal
					, ET.Color_Texto
					FROM user_miembro UM
					INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
					INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
					
				WHERE 
				US.Id_Entity = :Id_Entity 
				"; 		
				$Where = ["Id_Entity"=>$Id_Entity];				
				$Row = ClassPDO::DCRow($Query,$Where,$Conection);
	
			}			
		}
		
		return $Row;
        
	}
	
	public function MainDataUserRegistrado($Parm) {
		global $Conection;	
		
		$Email = DCPost("Email");
		
		$Query = "
		SELECT 
		
			US.Usuario_Login
			, US.Password
			, ET.Id_Entity
			, US.Id_User
			, ET.Logo_Externo
			, ET.Logo_Interno
			, UM.Email
			, UM.Nombre
			, UM.Id_User_Miembro
		    , ET.Color_Menu_Horizontal
			FROM user_miembro UM
			INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
			INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
			
		WHERE 
		UM.Email = :Email 
		"; 		
		$Where = ["Email"=>$Email];				
		$Row = ClassPDO::DCRow($Query,$Where,$Conection);
		return $Row;
				
	}


	public function MainDataEntity_Id_Session($Parm) {
        
		global $Conection;
		$Id_Entity = $Parm['Entity'];

		$Query = "
		SELECT 
		  ET.Id_Entity
		, ET.Logo_Externo
		, ET.Logo_Interno
		, ET.Color_Menu_Horizontal
		FROM entity ET 
		WHERE 
		ET.Id_Entity = :Id_Entity 
		"; 
		$Where = ["Id_Entity"=>$Id_Entity];
		$Row = ClassPDO::DCRow($Query,$Where,$Conection);	
		return $Row;
        
	}		
	
	public function MainDataEntity($Parm) {
        
		global $Conection;
		$Id_Entity = $Parm['Entity'];

		$Query = "
		SELECT 
		  ET.Id_Entity
		, ET.Logo_Externo
		, ET.Logo_Interno
		, ET.Color_Menu_Horizontal
		FROM entity ET 
		WHERE 
		ET.Url = :Id_Entity 
		"; 
		$Where = ["Id_Entity"=>$Id_Entity];
		$Row = ClassPDO::DCRow($Query,$Where,$Conection);	
		return $Row;
        
	}	
	
		
}
