<?php
require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();
$UrlFile = "/sadministrator/admin_warehouse";

class AdminWarehouse{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile;
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Form = $Parm["Obj"];
		$Redirect = $Parm["REDIRECT"];

        switch ($Process) {
            case "ENTRY":

				switch ($Form) {
					case "Obj_Warehouse":

							$Id_Warehouse = $this->ObjectEntry();
							
							DCCloseModal();
							
							if($Id_Warehouse !== false ){
								
								if($Redirect == "det_admin_tool_site"){
									$Settings = array();
									$Settings['Url'] = "/sadministrator/det_admin_tools_site/Interface/Details/Id_Warehouse/".$Id_Warehouse;
									$Settings['Screen'] = "ScreenRight";
									$Settings['Type_Send'] = "";
									DCRedirectJS($Settings);
								}
							}
							
						break;							
				}			
				
                break;
            case "CHANGE":

							$Id_Warehouse = $this->ObjectChange($Parm);
							
							if($Redirect == "det_admin_tool_site"){
								$Settings = array();
								$Settings['Url'] = "/sadministrator/det_admin_tools_site/Interface/Details/Id_Warehouse/".$Id_Warehouse;
								$Settings['Screen'] = "ScreenRight";
								$Settings['Type_Send'] = "";
								DCRedirectJS($Settings);
							}
                break;
            case "DELETE":

                break;
            case "SUPPRESS":

                break;
            case "SEARCH":

                break;				
        }
		
		
		
        switch ($Interface) {				
            case "Create":
			
			    $Form = $this->FormLocal();
				
			    $Html = DCModalForm("Create Warehouse",$Form);
                DCWrite($Html);
                DCExit();
                break;
				
            case "Edit":
			
			    $Form = $this->FormLocal($Parm);
				
			    $Html = DCModalForm("Edit Warehouse",$Form);
                DCWrite($Html);
                DCExit();
                break;				
			
        }
				
		
		
	}
	

	public function FormLocal($Settings) {
        
	    global $Conection,$DCTimeHour,$UrlFile;
		
        $IdForm = "Obj_Warehouse";			
		$Id_Warehouse = $Settings["Id_Warehouse"];
		$Redirect = "/REDIRECT/det_admin_tool_site";
		
		if(!empty($Id_Warehouse)){
			
			$RowWarehouse = Warehouse::MainData($Settings);	
			
            $Direcction = $UrlFile.$Redirect."/Process/CHANGE/Obj/".$IdForm."/Id_Warehouse/".$Id_Warehouse;				
		}else{
            $Direcction = $UrlFile.$Redirect."/Process/ENTRY/Obj/".$IdForm;				
		}
		
        $IdButton = "button_animatedModal5";	

        $Screen = "animatedModal5";	
        $Class = "btn btn-primary";	
		$NameButton = "Guardar";
		
		$Html = '	
			<form id="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >		
		        <div class="modal-body">
				
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Name</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Name" name="Name" value="'.$RowWarehouse->Name.'">
				  </div>
		
			    </div>
			  
			    <div class="modal-footer text-center">
					<button type="button" onclick=SaveForm(this); direction="'.$Direcction.'" screen="'.$Screen.'"  class="'.$Class.'"  id="'.$IdButton.'" form="'.$IdForm.'">'.$NameButton.'</button>;
				</div>  
		    </form>
	    ';
		
		return $Html;
	}
	
	public function ObjectEntry($Settings) {
       	global $Conection, $DCTimeHour;
        
		$Warehouse = DCPost("Name");
		// DCVd($Warehouse);
		// DCExit();
		$Query = " SELECT COUNT(*) AS TotReg FROM  ".$Warehouse." ";
		$where = [];
		$reg = ClassPDO::DCRow($Query,$where,$Conection);

 
		if($reg == false){
			
			$data = array(
			'Name' => $Warehouse,
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			$Result = ClassPDO::DCInsert("warehouse", $data, $Conection);
			$Return = $Result["lastInsertId"];		

			$Query = " CREATE TABLE " . $Warehouse . " (";
			$Query .= " Id_" . $Warehouse . " INT(20) NOT NULL AUTO_INCREMENT, ";
			$Query .= " PRIMARY KEY (Id_" . $Warehouse . ")";
			$Query .= " ); ";
			ClassPDO::DCExecute($Query,$Conection);			
		    DCWrite(Message("Process executed correctly","C"));	
			
			
			$Query = "ALTER TABLE " . $Warehouse . " ADD Entity  INT(20) NOT NULL ";
			ClassPDO::DCExecute($Query,$Conection);	

			$Query = "ALTER TABLE " . $Warehouse . " ADD Id_User_Creation  INT(20) NOT NULL ";
			ClassPDO::DCExecute($Query,$Conection);				

			$Query = "ALTER TABLE " . $Warehouse . " ADD Id_User_Update  INT(20) NOT NULL ";
			ClassPDO::DCExecute($Query,$Conection);				
						
			$Query = "ALTER TABLE " . $Warehouse . " ADD Date_Time_Creation datetime NOT NULL ";
			ClassPDO::DCExecute($Query,$Conection);	
			
			$Query = "ALTER TABLE " . $Warehouse . " ADD Date_Time_Update datetime NOT NULL ";
			ClassPDO::DCExecute($Query,$Conection);		

			$data = array(
			'Id_Warehouse' => $Result["lastInsertId"],
			'Name' => "Id_".$Warehouse."",
			'Id_Data_Type' => 2,
			'Length' => 20,
			'Pk' => "SI",
			'Autoincrement' => "SI",
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			ClassPDO::DCInsert("warehouse_detail", $data, $Conection);			
			
			$data = array(
			'Id_Warehouse' => $Result["lastInsertId"],
			'Name' => "Entity",
			'Id_Data_Type' => 2,
			'Length' => 20,
			'Pk' => "NO",
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			ClassPDO::DCInsert("warehouse_detail", $data, $Conection);				
			
			$data = array(
			'Id_Warehouse' => $Result["lastInsertId"],
			'Name' => "Id_User_Creation",
			'Id_Data_Type' => 2,
			'Length' => 20,
			'Pk' => "NO",
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			ClassPDO::DCInsert("warehouse_detail", $data, $Conection);	

			$data = array(
			'Id_Warehouse' => $Result["lastInsertId"],
			'Name' => "Id_User_Update",
			'Id_Data_Type' => 2,
			'Length' => 20,
			'Pk' => "NO",
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			ClassPDO::DCInsert("warehouse_detail", $data, $Conection);			
			
			$data = array(
			'Id_Warehouse' => $Result["lastInsertId"],
			'Name' => "Date_Time_Creation",
			'Id_Data_Type' => 5,
			'Length' => 0,
			'Pk' => "NO",
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			ClassPDO::DCInsert("warehouse_detail", $data, $Conection);	

			$data = array(
			'Id_Warehouse' => $Result["lastInsertId"],
			'Name' => "Date_Time_Update",
			'Id_Data_Type' => 5,
			'Pk' => "NO",
			'Length' => 0,
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			ClassPDO::DCInsert("warehouse_detail", $data, $Conection);	

		}else{
			
			$Return = false;
		    DCWrite(Message("Error ".$totReg,"C"));	
			
		}
	
		return $Return;
						
	}	

	
	public function ObjectChange($Settings) {
       	global $Conection, $DCTimeHour;

		$reg = array(
		'Name' => DCPost("Name")
		);
		$where = array('Id_Warehouse' =>$Settings["Id_Warehouse"]);
		$rg = ClassPDO::DCUpdate("warehouse", $reg , $where, $Conection);

		DCWrite(Message("Process executed correctly ".$Settings["Id_Warehouse"]."","C"));
		return $Settings["Id_Warehouse"];						
	}		
	

}