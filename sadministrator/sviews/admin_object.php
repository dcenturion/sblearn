<?php
require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class AdminObject{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/admin_object";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Obj_Object":
					
						$Id_Object = $this->ObjectEntry($Parm);
						
						if($Redirect == "det_admin_object"){
							$Settings = array();
							$Settings['Url'] = "/sadministrator/det_admin_object/Interface/Details/Id_Object/".$Id_Object;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "";
							DCRedirectJS($Settings);
						}
						break;	
					case "Form_Suscripcion":
						// $this->Form_Suscripcion($Transaction);
						break;							
				}			
				
                break;
            case "CHANGE":

				switch ($Obj) {
					case "Obj_Object":
						
						$Id_Object = $this->ObjectChange($Parm);
						
						if($Redirect == "det_admin_object"){
							$Settings = array();
							$Settings['Url'] = "/sadministrator/det_admin_object/Interface/Details/Id_Object/".$Id_Object;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "";
							DCRedirectJS($Settings);
						}
						
					
						break;	
					case "Form_Suscripcion":
						// $this->Form_Suscripcion($Transaction);
						break;							
				}
				

                break;
				
            case "DELETE":
			
				switch ($Obj) {
					case "Warehouse":
						
						$this->ObjectDeleteBlock($Parm);
						
						DCCloseModal();
									
						$Settings["Interface"] = "";
					    new DetAdminToolsSite($Settings);
						
						
						break;	
						
				}	
				
                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":

                break;				
        }
		
		
		
        switch ($Interface) {
            case "":
			
				$layout  = new Layout();
				$listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Editar Áreas[".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Warehouse ]" .$UrlFile_admin_warehouse."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("TOOLS","Adminitrator of Objects",$btn);
				
				$urlLinkB = "";
				$Pestanas = Warehouse::Tabs(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]Marca"
				,"".$urlLinkB."]","".$urlLinkB."]",""));					
										
					
				$Query = "
				    SELECT OB.Id_Object AS CodigoLink, OB.Name, OB.Date_Time_Creation FROM object OB
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Object';
				$Link = $UrlFileDet."/Interface/Details";
				$Screen = 'ScreenRight';
				$where = ["Entidad"=>$Entidad];
				$Content = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','HREF');
				 
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle,$Pestanas ."<BR>".$Content .  $Plugin);
				
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;
				
            case "Details":
			
				$layout  = new Layout();
				$listMn = "<i class='icon-chevron-right'></i> Editar Estados [".$enlaceEstado."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				$listMn .= "<i class='icon-chevron-right'></i> Editar Áreas [".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Warehouse ]" .$UrlFile_admin_warehouse. $Redirect ."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("TOOLS","Adminitrator of interfaces and objects | DETAILS ",$btn);
					
				$Query = "
				    SELECT WR.Id_Warehouse, WR.Name, WR.Date_Time_Creation FROM warehouse WR
				";    
				$Class = 'table table-hover';
				$LinkId = 'codigoAlmacen';
				$Link = $enlace."?Articulos=EditaArticulos";
				$Screen = 'layoutV';
				$where = ["Entidad"=>$Entidad];
				$Content = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','');
				 
				 
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle,$Content .  $Plugin);
				
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;				
				
				
            case "Create":
			
			    $Form = $this->FormLocal($Parm,$UrlFile);
				
			    $Html = DCModalForm("Create Object",$Form);
                DCWrite($Html);
                DCExit();
                break;

            case "Edit":
			
			    $Form = $this->FormLocal($Parm,$UrlFile);
				
			    $Html = DCModalForm("Edit Warehouse",$Form);
                DCWrite($Html);
                DCExit();
                break;	
				
            case "DeleteMassive":
			// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[FORM[warehouse{";
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Obj/Warehouse]ScreenRight]FORM]warehouse]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;				
				
			
        }
				
		
		
	}
	

	public function FormLocal($Settings,$UrlFile) {
		
        $Id_Warehouse = $Settings["Id_Warehouse"];	
        $Id_Object = $Settings["Id_Object"];	
	
		
        $IdForm = "Obj_Object";		
        $IdButton = "button_animatedModal5";	
        $Screen = "ScreenRight";	
        $Class = "btn btn-primary";	
		$NameButton = "Guardar";
		$Redirect = "/REDIRECT/det_admin_object";
			
		if(!empty($Id_Object)){
	        $RowObject = Objects::MainData($Settings);	
            $Direcction = $UrlFile.$Redirect."/Process/CHANGE/Obj/".$IdForm."/Id_Warehouse/".$Id_Warehouse."/Id_Object/".$Id_Object;							
			}else{
			
            $Direcction = $UrlFile.$Redirect."/Process/ENTRY/Obj/".$IdForm."/Id_Warehouse/".$Id_Warehouse;			
		}
		
			$QueryA = " SELECT Id_Type_Form AS Id, Name FROM type_form ";
			$Where = [];
			$ComboBoxData_Type = DCComboBoxSelect($QueryA,$Where,$RowObject->Id_Type_Form,"Id_Type_Form","custom-select");
			
		$Html = '	
			<form id="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >		
		        <div class="modal-body">
				
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Name</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Name" name="Name" value="'.$RowObject->Name.'">
				  </div>
				  
				  <div class="form-group">
				  <label for="form-control-2" class="control-label">Type Form</label>
				  '.$ComboBoxData_Type.'
   				  </div>				  
		
			    </div>
			  
			    <div class="modal-footer text-center">
					<button type="button" onclick=SaveForm(this); direction="'.$Direcction.'" screen="'.$Screen.'"  class="'.$Class.'"  id="'.$IdButton.'" form="'.$IdForm.'">'.$NameButton.'</button>;
				</div>  
		    </form>
	    ';
		
		return $Html;
	}
	
	public function ObjectDeleteBlock($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Object = DCPost("ky");
		$columnas='';
		for ($j = 0; $j < count($Id_Object); $j++) {
			
			// $Query = " SELECT Id_Object FROM object WHERE Id_Object = :Id_Object ";
			// $Where = ["Id_Object"=>$Id_Object[$j]];
			
			// $Reg = ClassPDO::DCRow($Query,$Where ,$Conection);			
			// DCWrite("Warehouse:: ".$Reg->Warehouse."<br>");
			// ClassPDO::DCDrop($Reg->Warehouse,$Conection);		

			$where = array('Id_Object' =>$Id_Object[$j]);
			$rg = ClassPDO::DCDelete('object_detail', $where, $Conection);
			

			$where = array('Id_Object' =>$Id_Object[$j]);
			$rg = ClassPDO::DCDelete('object', $where, $Conection);
			
						
			
		}

		DCWrite(Message("Process executed correctly","C"));
						
	}	
	
	
	public function ObjectEntry($Settings) {
       	global $Conection, $DCTimeHour;
        
		$Object = DCPost("Name");
		$Id_Type_Form = DCPost("Id_Type_Form");

		$data = array(
		'Name' => $Object,
		'Id_Type_Form' => $Id_Type_Form,
		'Id_Warehouse' => $Settings["Id_Warehouse"],
		'State' => "A",
		'Date_Time_Creation' => $DCTimeHour,
		'Date_Time_Update' => $DCTimeHour
		);
		$Result = ClassPDO::DCInsert("object", $data, $Conection);
		$Return = $Result["lastInsertId"];	

        $Query = "SELECT WD.Id_Warehouse_Detail, WD.Name, WD.Id_Data_Type, WD.Length, WD.Pk  
		          FROM warehouse_detail WD 
				  WHERE WD.Id_Warehouse = :Id_Warehouse
				  ";	
		$Where = ["Id_Warehouse" => $Settings["Id_Warehouse"]];
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		foreach($Rows AS $Field){
		    $Count += 1;
			
			$Visibility = "SI";	
			
			if( $Field->Name =="Date_Time_Update" ||  $Field->Name =="Date_Time_Creation"  ||  $Field->Name =="Id_User_Update"   ||  $Field->Name =="Entity"  ||  $Field->Name =="Id_User_Creation" ){
				$Visibility = "NO";
			}
			
			if($Field->Pk == "SI"){
				$Visibility = "NO";			
			}
			
			if($Field->Name =="Date_Time_Creation"  ||  $Field->Name =="Entity"  ||  $Field->Name =="Id_User_Creation" ){
				$Type_Process_BD = "INSERT";
			}else{
				$Type_Process_BD = "UPDATE";				
			}
			
			$data = array(
			'Id_Warehouse_Detail' => $Field->Id_Warehouse_Detail,
			'Alias' => $Field->Name,
			'Id_Object' => $Return,
			'Id_Data_Type' => $Field->Id_Data_Type,
			'Id_Field_Type' => 1,
			'Pk' => $Field->Pk,
			'Visibility' => $Visibility,
			'State' => "SI",
			'OrderP' => $Count,
			'Type_Process_BD' => $Type_Process_BD,
			'Read_Only' => "SI",
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			$ResultB = ClassPDO::DCInsert("object_detail", $data, $Conection);	
		
        }			
	
		DCWrite(Message("Process executed correctly","C"));	
				
		return $Return;
						
	}	

	public function ObjectChange($Settings) {
       	global $Conection, $DCTimeHour;

		$reg = array(
		'Name' => DCPost("Name"),
		'Id_Type_Form' => DCPost("Id_Type_Form")
		);
		$where = array('Id_Object' =>$Settings["Id_Object"]);
		$rg = ClassPDO::DCUpdate("object", $reg , $where, $Conection);

		DCWrite(Message("Process executed correctly ".$Settings["Id_Object"]."","C"));
		return $Settings["Id_Object"];						
	}			


	
}