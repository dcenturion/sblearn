<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_objects.php');
require_once('./sviews/ft_warehouse.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();


class DetAdminObject{

	private $Parm;
	
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$Redirect;
		
		$UrlFile = "/sadministrator/det_admin_object";
		$Redirect = "/REDIRECT/admin_tool_site";
		
        $UrlFile_admin_object = "/sadministrator/admin_object";
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
						
					case "Obj_Object_detail_grilla":
						
						$this->ObjectEntryMassive($Parm);
						DCCloseModal();
						
						$Settings["Interface"] = "Details";
						$Settings["Id_Object"] = $Parm["Id_Object"];
					    new DetAdminObject($Settings);

						break;	
						
					case "Obj_Warehouse_detail_grilla":
						// Obj_Warehouse_detail_grilla
						$this->ObjectEntryMassiveWarehouse($Parm);
						DCCloseModal();
						
						$Settings["Interface"] = "Details";
						$Settings["Id_Object"] = $Parm["Id_Object"];
					    new DetAdminObject($Settings);

						break;	
						
						
					case "Obj_Object":
						// Obj_Warehouse_detail_grilla
						$this->ObjectDuplicate($Parm);
						DCCloseModal();
						
						$Settings["Interface"] = "Details";
						$Settings["Id_Object"] = $Parm["Id_Object"];
					    new DetAdminObject($Settings);

						break;							
						
				}			
				
                break;
				
            case "CHANGE":
			
				switch ($Obj) {
					case "Obj_Warehouse_detail":
						
						$this->ObjectChange($Parm);
						DCCloseModal();
						
						$Settings["Interface"] = "Details";
						$Settings["Id_Warehouse"] = $Parm["Id_Warehouse"];
					    new DetAdminToolsSite($Settings);

						break;	
						
					case "Obj_object_detail":
						
						$this->ObjectChange($Parm);
						DCCloseModal();
						
						$Settings["Interface"] = "Details";
						$Settings["Id_Object"] = $Parm["Id_Object"];
						
					    new DetAdminObject($Settings);

						break;							
						
					case "Obj_Warehouse_detail_grilla":
						
						$this->ObjectEntryMassive($Parm);
						DCCloseModal();
						
						$Settings["Interface"] = "Details";
						$Settings["Id_Warehouse"] = $Parm["Id_Warehouse"];
					    new DetAdminToolsSite($Settings);

						break;	

						
				}		
                break;
            case "DELETE":
			
				switch ($Obj) {
					case "Obj_Object_detail_grilla":
						
						$this->ObjectDeleteBlock($Parm);
						$Settings["Interface"] = "Details";
						$Settings["Id_Object"] = $Parm["Id_Object"];
						// var_dump($Settings);
					    new DetAdminObject($Settings);
						
						break;	
						
					case "Warehouse_Detail":
						// var_dump($Parm);
						$this->ObjectDeleteOne($Parm);
						
						$Settings["Interface"] = "Details";
						$Settings["Id_Warehouse"] = $Parm["Id_Warehouse"];
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

            case "Details":
			
				$layout  = new Layout();
				
				$RowWarehouse = Objects::MainData($Parm);	
		        
				$UrlFileObjectCreate = "/sadministrator/admin_object";
		
				$listMn = "<i class='icon-chevron-right'></i> Editar Object [".$UrlFile_admin_object."/Interface/Edit/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Save Details [".$UrlFile."/Process/ENTRY/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."/Obj/Obj_Object_detail_grilla[ScreenRight[FORM[Obj_Object_detail_grilla{";
				$listMn .= "<i class='icon-chevron-right'></i> Delete Details [".$UrlFile."/Process/DELETE/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."/Obj/Obj_Object_detail_grilla[ScreenRight[FORM[Obj_Object_detail_grilla{";
				$listMn .= "<i class='icon-chevron-right'></i> Publications [".$UrlFile."/Interface/Publications/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Duplicate [".$UrlFile."/Interface/Duplicate/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."[animatedModal5[HXM[{";

				$btn = "<i class='zmdi zmdi-edit'></i> Create Field ]" .$UrlFile ."/Interface/Create/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn .= "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("TOOLS"," Details of Objects | <b>".$RowWarehouse->Name."  | ".$RowWarehouse->Type_Form_Name."</b> | DETAILS ",$btn);
					
				$GrillaLocal = $this->Grilla_Local($Parm,$UrlFile);
				$Contenido = DCPage($DCPanelTitle,$GrillaLocal);
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;				
			
            case "Create":
			
			    $Form = $this->FormLocal($Parm,$UrlFile);
				$RowWarehouse = Objects::MainData($Parm);					
                // $listMn = "<i class='icon-chevron-right'></i> Editar Object [".$UrlFile_admin_object."/Interface/Edit/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."[animatedModal5[HXM[{";
               // $listMn .= "<i class='icon-chevron-right'></i> Save Details [".$UrlFile."/Process/ENTRY/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."/Obj/Obj_Object_detail_grilla[ScreenRight[FORM[Obj_Object_detail_grilla{";
								
				$btn = " Include ]" .$UrlFile ."/Process/ENTRY/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."/Obj/Obj_Warehouse_detail_grilla]ScreenRight]FORM]Obj_Warehouse_detail_grilla]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'Obj_Warehouse_detail_grilla');
				$btn = "<div style='width:100%;padding:7px 20px;'>". $btn ."</div>";
			    $Html = DCModalForm("Call Field", $btn . $Form);
                DCWrite($Html);
                DCExit();
                break;
				
            case "Edit":
			
			    $Form = $this->FormLocal_Edit($Parm,$UrlFile);
			    $Html = DCModalForm("Edit Field",$Form);
                DCWrite($Html);
                DCExit();
                break;	

            case "Duplicate":
			
			    $Form = $this->FormLocal_Object($Parm,$UrlFile);
			    $Html = DCModalForm("Duplicate Object",$Form);
                DCWrite($Html);
                DCExit();
                break;	
				

            case "DeleteOne":
			
		
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Obj/Warehouse_Detail/Id_Warehouse_Detail/".$Parm["Id_Warehouse_Detail"]."/Id_Warehouse/".$Parm["Id_Warehouse"]."]ScreenRight]HXM]btn btn-default dropdown-toggle}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/Create/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Item",$Form,$Button,"bg-info");
                DCWrite($Html);
                DCExit();
                break;	
				
            case "Publications":
			    
				
			    $Form = $this->ProcessJson($Parm);
			    $Form = $this->FormPublished($Parm,$UrlFile);
				
			    $Html = DCModalForm("Object Published",$Form);
                DCWrite($Html);
                DCExit();
                break;					
				
			
        }
				
		
		
	}
	

	public function ProcessJson($Settings){
	    global $Conection,$DCTimeHour;
		

        $Query = "SELECT OB.Id_Object, OB.Name
		          FROM object OB 
				  WHERE OB.Id_Object = :Id_Object

				  ";	
		$Where = ["Id_Object" => $Settings["Id_Object"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);
		
		
        $Query = "SELECT OD.Id_Object_Detail, OD.Name , OD.Id_Data_Type, OD.Id_Field_Type
		           , OD.Pk, OD.Visibility, OD.OrderP, OD.Alias, OD.Id_Warehouse_Detail, OB.Id_Warehouse, 
				   TF.Name AS TypeForm, WD.Name AS WDName, FT.Name AS FieldType
				   , OD.Visibility
				   , OD.Matriz_Data_Select
				   , OD.Type_Process_BD
				   , OD.Extencions
				   , OD.Archive_Server_Aplication
				   , OD.Archive_Server_Warehouse
				   , OD.Archive_Server_YouTube
				   , OD.Size_File
				   , OD.Path_Image
				   , OD.Id_Field_Type_Client
				   , OD.Format_Field
		          FROM object_detail OD 
				  INNER JOIN object OB ON OD.Id_Object = OB.Id_Object
				  INNER JOIN type_form TF ON TF.Id_Type_Form = OB.Id_Type_Form
				  INNER JOIN warehouse_detail WD ON WD.Id_Warehouse_Detail = OD.Id_Warehouse_Detail
				  INNER JOIN field_type FT ON FT.Id_Field_Type = OD.Id_Field_Type
				  WHERE OD.Id_Object = :Id_Object
				  ORDER BY OD.OrderP DESC
				  ";	
		$Where = ["Id_Object" => $Settings["Id_Object"]];
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		
				// DCWrite("hm");
				// DCVd($Rows);
		// DCExit();
		
		// DCVd("hhaaha");
		// DCVd($Rows);
		//Creamos el JSON
		$json_string = json_encode($Rows);
		$file = 'sbd_json/'.$Row->Name.'.json';
		file_put_contents($file, $json_string);
		
		
	
	}
	
	public function Grilla_Local($Settings,$UrlFile) {	
	    global $Conection,$DCTimeHour;
		
        $IdForm = "Obj_Object_detail_grilla";	
		
        $Query = "SELECT OD.Id_Object_Detail, OD.Name , OD.Id_Data_Type, OD.Id_Field_Type, OD.Id_Field_Type_Client
		           , OD.Pk, OD.Visibility, OD.OrderP, OD.Alias, OD.Id_Warehouse_Detail
				   , OB.Id_Warehouse
		          FROM object_detail OD 
				  INNER JOIN object OB ON OD.Id_Object = OB.Id_Object
				  WHERE OD.Id_Object = :Id_Object
				  ORDER BY OD.OrderP DESC
				  ";	
		$Where = ["Id_Object" => $Settings["Id_Object"]];
		
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		foreach($Rows AS $Field){
		    $Count += 1;		

			$listMn = "<i class='icon-chevron-right'></i> Edit Details [".$UrlFile."/Interface/Edit/Id_Object/".$Settings["Id_Object"]."/Id_Object_Detail/".$Field->Id_Object_Detail."[animatedModal5[HXM[{";
			$listMn .= "<i class='icon-chevron-right'></i> Delete [".$UrlFile."/Interface/DeleteOne/Id_Warehouse/".$Settings["Id_Warehouse"]."/Id_Warehouse_Detail/".$Field->Id_Warehouse_Detail."[animatedModal5[HXM[{";
			// $listMn .= "<i class='icon-chevron-right'></i> Delete [".$UrlFile."/Process/ENTRY/Id_Warehouse/".$Settings["Id_Warehouse"]."/Obj/Obj_Warehouse_detail_grilla[ScreenRight[FORM[Obj_Warehouse_detail_grilla{";
			
			$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Actions ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
			$btn = DCButton($btn, 'botones1', 'object_detail_grilla'.$Count);

			$QueryA = " SELECT Id_Data_Type AS Id, Name FROM data_type ";
			$Where = [];
			$ComboBoxData_Type = DCComboBoxSelect($QueryA,$Where,$Field->Id_Data_Type,$Count."-Id_Data_Type","custom-select");
			
			$QueryA = " SELECT Id_Field_Type_Client AS Id, Name FROM field_type_client ";
			$Where = [];
			$ComboBox_Field_Type_Client = DCComboBoxSelect($QueryA,$Where,$Field->Id_Field_Type_Client,$Count."-Id_Field_Type_Client","custom-select");
			

			$QueryA = " SELECT Id_Field_Type AS Id, Name FROM field_type ";
			$Where = [];
			$ComboBox_Field_Type = DCComboBoxSelect($QueryA,$Where,$Field->Id_Field_Type,$Count."-Id_Field_Type","custom-select");
			
			$QueryA = " SELECT Id_Warehouse_Detail AS Id, Name FROM warehouse_detail 
			          WHERE Id_Warehouse = :Id_Warehouse 	";
		    $Where = ["Id_Warehouse" => $Field->Id_Warehouse];
			$ComboBox_Id_Warehouse_Detail = DCComboBoxSelect($QueryA,$Where,$Field->Id_Warehouse_Detail,$Count."-Id_Warehouse_Detail","custom-select");
			
						
			
		    if( $Field->Pk == "SI"){
			    $Checked = 'checked="checked" ';
			}else{
			    $Checked = '';			
			}
			
		    if( $Field->Visibility == "SI"){
			    $Checked_Visibility = 'checked="checked"';
			    // $Value_Visibility = 'SI';
			}else{
			    $Checked_Visibility = '';	
			    // $Value_Visibility = 'NO';				
			}			
			
			
		    $Fields .= '
				<tr>
				  <td>
					 <label class="custom-control custom-control-primary custom-checkbox active">
                      <input class="custom-control-input" type="checkbox" name="Id_Object_Detail[]" value="'.$Field->Id_Object_Detail.'">
                      <span class="custom-control-indicator"></span>
                    </label>
				  </td>
                  <td>
				  '.$ComboBox_Id_Warehouse_Detail.'
				  </td>
                  <td><input type="text" value="'.$Field->Alias.'"  name="'.$Count.'-Alias" ></td>
				  
                  <td>
				  '.$ComboBoxData_Type.'
				  </td>
				  				  
                  <td>
				  '.$ComboBox_Field_Type.'
				  </td>
				  
				  
                  <td>
				  '.$ComboBox_Field_Type_Client.'
				  </td>
				  <td>
					<label class="switch switch-primary">
					  <input type="checkbox" name="Visibility[]" class="s-input" value="'.$Field->Id_Object_Detail.'"  '.$Checked_Visibility.'>
					  <span class="s-content">
						<span class="s-track"></span>
						<span class="s-handle"></span>
					  </span>
					</label>
                  </td>
                  <td>
					  <div class="col-xs-2">
					  <input type="text" value="'.$Field->OrderP.'"  name="'.$Count.'-OrderP" >
					  </div>				  
				  </td>				  
                  <td>
				    <label class="custom-control custom-control-primary custom-radio">
                          <input class="custom-control-input" type="radio" name="Pk" value="'.$Field->Id_Object_Detail.'" '.$Checked.'>
                          <span class="custom-control-indicator"></span>
                    </label>
				  </td>
                  <td><input type="hidden" value="'.$Field->Id_Object_Detail.'"  name="'.$Count.'-Id_Object_Detail"></td>
                  <td>
				     '.$btn.'
				  </td>
                </tr>
				';
			
		}
		
		$Html = '
        <div class="table-responsive">
		    <form id="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >	
            <table class="table table-hover">
              <thead>
                <tr>
				  <th style="width: 32px"></th>
                  <th>Name</th>
                  <th>Alias</th>
                  <th>Data Type</th>
                  <th>Field Type</th>
                  <th>Field Type Client</th>
                  <th>Visibility</th>
                  <th>Order</th>
                  <th>P. Key</th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
               '.$Fields.'
            
              </tbody>
            </table>
		    </form>			
		</div>			
			
			
	    ';
		
		return $Html;
	}
	
	
	public function FormLocal($Settings,$UrlFile) {
        
	    global $Conection,$DCTimeHour;
		
        $IdForm = "Obj_Warehouse_detail_grilla";	
		
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

			$listMn = "<i class='icon-chevron-right'></i> Edit Details [".$UrlFile."/Interface/Edit/Id_Warehouse/".$Settings["Id_Warehouse"]."/Id_Warehouse_Detail/".$Field->Id_Warehouse_Detail."[animatedModal5[HXM[{";
			$listMn .= "<i class='icon-chevron-right'></i> Delete [".$UrlFile."/Interface/DeleteOne/Id_Warehouse/".$Settings["Id_Warehouse"]."/Id_Warehouse_Detail/".$Field->Id_Warehouse_Detail."[animatedModal5[HXM[{";
			// $listMn .= "<i class='icon-chevron-right'></i> Delete [".$UrlFile."/Process/ENTRY/Id_Warehouse/".$Settings["Id_Warehouse"]."/Obj/Obj_Warehouse_detail_grilla[ScreenRight[FORM[Obj_Warehouse_detail_grilla{";
			
			$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Actions ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
			$btn = DCButton($btn, 'botones1', 'warehouse_detail_grilla'.$Count);

			$QueryA = " SELECT Id_Data_Type AS Id, Name FROM data_type ";
			$Where = [];
			$ComboBoxData_Type = DCComboBoxSelect($QueryA,$Where,$Field->Id_Data_Type,$Count."-Id_Data_Type","custom-select");

			
		    $Fields .= '
				<tr>
				  <td>
					 <label class="custom-control custom-control-primary custom-checkbox active">
                      <input class="custom-control-input" type="checkbox" name="Id_Warehouse_Detail[]" value="'.$Field->Id_Warehouse_Detail.'">
                      <span class="custom-control-indicator"></span>
                    </label>
				  </td>
                  <td><input type="text" value="'.$Field->Name.'"  name="'.$Count.'-Name" ></td>
                  <td>
				  '.$ComboBoxData_Type.'
				  </td>
                  <td><input type="hidden" value="'.$Field->Id_Warehouse_Detail.'"  name="'.$Count.'-Id_Warehouse_Detail"></td>
				  
                </tr>
				';
			
		}
		
		$Html = '
        <div class="table-responsive">
		    <form id="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >	
            <table class="table table-hover">
              <thead>
                <tr>
				  <th style="width: 32px"></th>
                  <th>Name</th>
                  <th>Data Type</th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
               '.$Fields.'
              </tbody>
            </table>
		    </form>			
		</div>			
			
			
	    ';
		return $Html;
	}
	
	public function FormPublished($Settings,$UrlFile) {
        
	    global $Conection,$DCTimeHour;
		
		$Form = BFormVertical("form_994599",$Class,$IdRow,$PathImage,$Combobox,"","Id_form");
		
        $Html = $Form;
		return $Html;
	}	
	
	
	public function FormLocal_Object($Settings,$UrlFile) {
		
        $Id_Warehouse = $Settings["Id_Warehouse"];	
        $Id_Object = $Settings["Id_Object"];	
	    
		// var_dump($Settings);
		
        $IdForm = "Obj_Object";		
        $IdButton = "button_animatedModal5";	
        $Screen = "ScreenRight";	
        $Class = "btn btn-primary";	
		$NameButton = "Guardar";
		$Redirect = "";
			
		if(!empty($Id_Object)){
	        $RowObject = Objects::MainData($Settings);	
			
            $Direcction = $UrlFile.$Redirect."/Process/ENTRY/Obj/".$IdForm."/Id_Warehouse/".$Id_Warehouse."/Id_Object/".$Id_Object;							
			}else{
			
            $Direcction = $UrlFile.$Redirect."/Process/ENTRY/Obj/".$IdForm."/Id_Warehouse/".$Id_Warehouse;			
		}
		
	
			
		$Html = '	
			<form id="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >		
		        <div class="modal-body">
				
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Name</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Name" name="Name" value="'.$RowObject->Name.'" readonly >
				  </div>

				  <div class="form-group">
					<label for="form-control-2" class="control-label">Name New</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Name New" name="Name_New" value="">
				  </div>
						  						  
		
			    </div>
			  
			    <div class="modal-footer text-center">
					<button type="button" onclick=SaveForm(this); direction="'.$Direcction.'" screen="'.$Screen.'"  class="'.$Class.'"  id="'.$IdButton.'" form="'.$IdForm.'">'.$NameButton.'</button>;
				</div>  
		    </form>
	    ';
		
		return $Html;
	}
		
	
	
	public function FormLocal_Edit($Settings,$UrlFile) {
        
	    global $Conection,$DCTimeHour;
		
        $Redirect = "";	
		$Id_Object = $Settings["Id_Object"];
		
        $IdForm = "Obj_object_detail";	
        $IdButton = "button_animatedModal5";	
		
		$Id_Object_Detail = $Settings["Id_Object_Detail"];
		if(!empty($Id_Object_Detail)){
			
            $Direcction = $UrlFile.$Redirect."/Process/CHANGE/Obj/".$IdForm."/Id_Object/".$Id_Object."/Id_Object_Detail/".$Id_Object_Detail;				
			$Objects = ObjectsDetail::MainData($Settings);		
			
		}else{
			
            $Direcction = $UrlFile.$Redirect."/Process/ENTRY/Obj/".$IdForm."/Id_Warehouse/".$Id_Warehouse;	
			
		}
		// Type_Process_BD
        $Screen = "ScreenRight";	
        $Class = "btn btn-primary";	
		$NameButton = "Guardar";
		
		
		$QueryA = " SELECT Id_Field_Type AS Id, Name FROM field_type ";
		$Where = [];
		$ComboBox_Field_Type = DCComboBoxSelect($QueryA,$Where,$Objects->Id_Field_Type,"Id_Field_Type","custom-select");
		
		
		$QueryA = " SELECT Id_Warehouse_Detail AS Id, Name FROM warehouse_detail WHERE Id_Warehouse =:Id_Warehouse ";
		$Where = ["Id_Warehouse"=>$Objects->Id_Warehouse];
		
		$ComboBoxData_Id_Warehouse_Detail = DCComboBoxSelect($QueryA,$Where,$Objects->Id_Warehouse_Detail,"Id_Warehouse_Detail","custom-select");
		
		
		$QueryA = " SELECT Id_Archive_Type AS Id, Name FROM archive_type ";
		$Where = [];
		$ComboBoxData_Archive_Type = DCComboBoxSelect($QueryA,$Where,$Objects->Id_Warehouse_Detail,"Id_Warehouse_Detail","custom-select");
		
				
		
		
		if( $Objects->Type_Process_BD == "UPDATE" ){
			$Checked_Type_Process_BD_UP = ' checked="checked"';
		}
		if( $Objects->Type_Process_BD == "INSERT" ){
			$Checked_Type_Process_BD = ' checked="checked"';
		}		
		
		
		if( $Objects->Archive_Server_Aplication == "SI" ){
			$Checked_Archive_Server_Aplication = ' checked="checked"';
		}
		if( $Objects->Archive_Server_Warehouse == "SI" ){
			$Checked_Archive_Server_Warehouse = ' checked="checked"';
		}		
		if( $Objects->Archive_Server_YouTube == "SI" ){
			$Checked_Archive_Server_YouTube = ' checked="checked"';
		}		
				
	
		$Html = '	

			<form id="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >		
		        <div class="modal-body">
						
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Field</label>
					'.$ComboBoxData_Id_Warehouse_Detail.'
				  </div>		

				  <div class="form-group">
					<label for="form-control-2" class="control-label">Alias</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Alias" name="Alias" value="'.$Objects->Alias.'" >
				  </div>
				  
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Data Type</label>
					'.$ComboBox_Field_Type.'
				  </div>		
				  
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Width</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Width" name="Width" value="'.$Objects->Width.'" >
				  </div>

				  <div class="form-group">
					<label for="form-control-2" class="control-label">Matriz_Data_Select</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Matriz_Data_Select" name="Matriz_Data_Select" value="'.$Objects->Matriz_Data_Select.'" >
				  </div>
				  
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Type_Process_BD '.$Objects->Type_Process_BD.'</label>
                    <div class="custom-controls-stacked">
                        <label class="custom-control custom-control-primary custom-radio">
                          <input class="custom-control-input" type="radio" name="Type_Process_BD" value="UPDATE" '.$Checked_Type_Process_BD_UP.' >
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-label">UPDATE</span>
                        </label>
                        <label class="custom-control custom-control-primary custom-radio">
                          <input class="custom-control-input" type="radio" name="Type_Process_BD" value="INSERT" '.$Checked_Type_Process_BD.' >
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-label">INSERT</span>
                        </label>

                    </div>
				  </div>	
				  
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Size_File</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Size_File" name="Size_File" value="'.$Objects->Size_File.'" >
				  </div>

				  <div class="form-group">
					<label for="form-control-2" class="control-label">Extencions</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Extencions" name="Extencions" value="'.$Objects->Extencions.'" >
				  </div>	
				  
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Path_Image</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Path Image" name="Path_Image" value="'.$Objects->Path_Image.'" >
				  </div>				  
				  				  
				  
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Format_Field</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Format_Field" name="Format_Field" value="'.$Objects->Format_Field.'" >
				  </div>				  
				  
				  <div class="form-group">
                    <label class="control-label">Save Archive in Server Aplications</label>
                    <div class="custom-controls-stacked">
                        <label class="switch switch-primary">
                          <input type="checkbox" name="Archive_Server_Aplication" value="SI" class="s-input" '.$Checked_Archive_Server_Aplication.'>
                          <span class="s-content">
                            <span class="s-track"></span>
                            <span class="s-handle"></span>
                          </span>
                        </label>
                    </div>
                  </div>

				  <div class="form-group">
                    <label class="control-label">Save Archive in Server Warehouse</label>
                    <div class="custom-controls-stacked">
                        <label class="switch switch-primary">
                          <input type="checkbox" name="Archive_Server_Warehouse" value="SI" class="s-input" '.$Checked_Archive_Server_Warehouse.'>
                          <span class="s-content">
                            <span class="s-track"></span>
                            <span class="s-handle"></span>
                          </span>
                        </label>
                    </div>
                  </div>

				  <div class="form-group">
                    <label class="control-label">Save Archive en YouTube</label>
                    <div class="custom-controls-stacked">
                        <label class="switch switch-primary">
                          <input type="checkbox" name="Archive_Server_YouTube"  value="SI" class="s-input" '.$Checked_Archive_Server_YouTube.'>
                          <span class="s-content">
                            <span class="s-track"></span>
                            <span class="s-handle"></span>
                          </span>
                        </label>
                    </div>
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

		$data = array(
		'Name' => DCPost("Name"),
		'Id_Data_Type' => DCPost("Id_Data_Type"),
		'Length' => DCPost("Length"),
		'Id_Warehouse' => $Settings["Id_Warehouse"],
		'Date_Time_Creation' => $DCTimeHour,
		'Date_Time_Update' => $DCTimeHour
		);
		$Return = ClassPDO::DCInsert("warehouse_detail", $data, $Conection);	
		
		$Query = " SELECT Name FROM warehouse WHERE Id_Warehouse = :Id_Warehouse ";
		$Where = ["Id_Warehouse"=>$Settings["Id_Warehouse"]];
		$RegWH = ClassPDO::DCRow($Query,$Where ,$Conection);			
	
	    $Length = DCPost("Length");
	    $Id_Data_Type = DCPost("Id_Data_Type");
	    $Name = DCPost("Name");
		
		if($Id_Data_Type == 5){ //Data time
			$Length = 0;
		}else{
			$Length = $Length;				
		}	
		
		if($Id_Data_Type == 1){ //Data time
			$TipoCampo = "VARCHAR(".$Length.") CHARACTER SET utf8";
		}			
		
		if($Id_Data_Type == 2){ //Data time
			$TipoCampo = "INT(".$Length.")";
		}

		if($Id_Data_Type == 3){ //Data time
			$TipoCampo = " TEXT CHARACTER SET utf8 NOT NULL";
		}			
					
		if($Id_Data_Type == 4 ){ //Data time
			$TipoCampo = "DATE";
		}			

		if($Id_Data_Type == 5){ //Data time
			$TipoCampo = "DATETIME";
		}			
												
		if($Id_Data_Type == 6){ //Data time
			$TipoCampo = "TIME";
		}			
							
        $Query = "ALTER TABLE " . $RegWH->Name . " ADD " . $Name . " " . $TipoCampo . " ";
        ClassPDO::DCExecute($Query,$Conection);
				
		DCWrite(Message("Process executed correctly","C"));
		return $Return["lastInsertId"];
						
	}	

	
	public function ObjectDeleteOne($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		
		$Id_Warehouse_Detail = $Settings["Id_Warehouse_Detail"];

		$Query = " 
		           SELECT 
		           WH.Name
				   , WHD.Name AS Field  
				   FROM warehouse WH
		           INNER JOIN warehouse_detail WHD ON WH.Id_Warehouse = WHD.Id_Warehouse
		           WHERE WHD.Id_Warehouse_Detail = :Id_Warehouse_Detail

				";
		$Where = ["Id_Warehouse_Detail"=>$Settings["Id_Warehouse_Detail"]];
		$RegWH = ClassPDO::DCRow($Query,$Where ,$Conection);
		
		$Query = " ALTER TABLE " . $RegWH->Name . " DROP " . $RegWH->Field . "";
		$rg = ClassPDO::DCExecute($Query,$Conection);
		
		$where = array('Id_Warehouse_Detail' =>$Id_Warehouse_Detail);
		$rg = ClassPDO::DCDelete('warehouse_detail', $where, $Conection);

		DCWrite(Message("Process executed correctly Id_Warehouse_Detail ".$Id_Warehouse_Detail ,"C"));
		
	}
	
	public function ObjectDeleteBlock($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Object_Detail = DCPost("Id_Object_Detail");
		$columnas='';
		for ($j = 0; $j < count($Id_Object_Detail); $j++) {
						
			$where = array('Id_Object_Detail' =>$Id_Object_Detail[$j]);
			$rg = ClassPDO::DCDelete('object_detail', $where, $Conection);
			
		}

		DCWrite(Message("Process executed correctly","C"));
						
	}	

		
	public function ObjectEntryMassive($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		
        $Query = "SELECT 
		          OD.Id_Object_Detail
				  , OD.Name
				  , OD.Id_Data_Type
				  , OD.Visibility
				  , O.Name AS Object  
		          FROM object_detail OD 
				  INNER JOIN object O ON  OD.Id_Object = O.Id_Object
				  WHERE OD.Id_Object = :Id_Object
				  ";	
		$Where = ["Id_Object" => $Settings["Id_Object"]];
		
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
				
		foreach($Rows AS $Field){
		    $Count += 1;			
			
		    $Name = DCPost($Count."-Name");
		    $Alias = DCPost($Count."-Alias");
		    $Id_Data_Type = DCPost($Count."-Id_Data_Type");
		    $Id_Field_Type = DCPost($Count."-Id_Field_Type");
		    $Id_Field_Type_Client = DCPost($Count."-Id_Field_Type_Client");
		    $OrderP = DCPost($Count."-OrderP");
		    $Visibility = DCPost("Visibility");
		    $Pk = DCPost("Pk");
		    $Id_Object_Detail = DCPost($Count."-Id_Object_Detail");
			
			if($Pk == $Id_Object_Detail){
				$Pk_v = "SI";
			}else{
				$Pk_v = "NO";				
			}
			
			// DCWrite($Id_Object_Detail."<br>");
		    // $Id_Object_Detail = $Id_Object_Detail; 
			$Visibility_Value = "NO";
			
			for ($j = 0; $j < count($Visibility); $j++) {
				
			    if($Visibility[$j] == $Id_Object_Detail){
					$Visibility_Value = "SI";
				}
				
			}
			
			$reg = array(
			'Name' => $Name,
			'Alias' => $Alias,
			'Visibility' => $Visibility_Value,
			'Id_Data_Type' => $Id_Data_Type,
			'Id_Field_Type' => $Id_Field_Type,
			'Id_Field_Type_Client' => $Id_Field_Type_Client,
			'Pk' => $Pk_v,
			'OrderP' => $OrderP
			);
			$where = array('Id_Object_Detail' =>$Id_Object_Detail);
			$rg = ClassPDO::DCUpdate("object_detail", $reg , $where, $Conection);
					
		}

		DCWrite(Message("Process executed correctly 9999","C"));
						
	}	
	
	
	public function ObjectEntryMassiveWarehouse($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
	
		$Id_Warehouse_Detail = DCPost("Id_Warehouse_Detail");
		
		$columnas='';
		$Count= 0;
		for ($j = 0; $j < count($Id_Warehouse_Detail); $j++) {
			
		    $Count += 1;		  		
			$Query = "SELECT 
					  WD.Id_Warehouse_Detail, WD.Name, WD.Id_Data_Type, WD.Length, WD.Pk 
					  FROM warehouse_detail WD 
					  INNER JOIN warehouse W ON WD.Id_Warehouse = W.Id_Warehouse
					  WHERE WD.Id_Warehouse_Detail = :Id_Warehouse_Detail
					  ";	
			$Where = ["Id_Warehouse_Detail" => $Id_Warehouse_Detail[$j]];
			$Field = ClassPDO::DCRow($Query,$Where ,$Conection);
			
						
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
			'Id_Object' => $Settings["Id_Object"],
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
						
	}	
	
	
	public function ObjectChange($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		
		$reg = array(
		'Id_Warehouse_Detail' => DCPost("Id_Warehouse_Detail"),
		'Alias' => DCPost("Alias"),
		'Id_Field_Type' => DCPost("Id_Field_Type"),
		'Width' => DCPost("Width"),
		'Matriz_Data_Select' => DCPost("Matriz_Data_Select"),
		'Type_Process_BD' => DCPost("Type_Process_BD"),
		'Archive_Server_Aplication' => DCPost("Archive_Server_Aplication"),
		'Archive_Server_Warehouse' => DCPost("Archive_Server_Warehouse"),
		'Archive_Server_YouTube' => DCPost("Archive_Server_YouTube"),
		'Extencions' => DCPost("Extencions"),
		'Format_Field' => DCPost("Format_Field"),
		'Size_File' => DCPost("Size_File"),
		'Path_Image' => DCPost("Path_Image"),
		);
		
		$where = array('Id_Object_Detail' =>$Settings["Id_Object_Detail"]);
		$rg = ClassPDO::DCUpdate("object_detail", $reg , $where, $Conection);	
			
	}
	
	
	
	
	public function ObjectDuplicate($Settings){
       	global $Conection, $DCTimeHour,$NameTable;
		
        $Id_Warehouse = $Settings["Id_Warehouse"];	
        $Id_Object = $Settings["Id_Object"];

        $Name = DCPost("Name");		
        $Name_New = DCPost("Name_New");		

		$Query = " 
				  SELECT Name FROM object WHERE Name =:Name
				";
		$Where = ["Name"=>$Name_New];
		$RegWH = ClassPDO::DCRow($Query,$Where ,$Conection);		
		$NameBD  = $RegWH->Name;		
		if(empty($NameBD)){
			
			
			    ///Tabla Cabezera
		
				$Sql = "
				INSERT INTO object_temp SELECT * FROM object WHERE Id_Object = ".$Id_Object."
				";
				ClassPDO::DCExecute($Sql, $Conection);
				
				$Query = " 
						  SELECT MAX(Id_Object) AS Ultimo_Reg FROM object 
						";
				$Where = ["Id_Object"=>$Id_Object];
				$RegWH = ClassPDO::DCRow($Query,$Where ,$Conection);		
				$Ultimo_Reg  = $RegWH->Ultimo_Reg + 1;		
				
				$User = $_SESSION['User'];
					
				$reg = array(
				'Id_User_Creation' => $User,
				'Id_User_Update' => $User,
				'Name' => $Name_New,
				);
				
				$where = array('Id_Object' =>$Id_Object);
				$rg = ClassPDO::DCUpdate("object_temp", $reg , $where, $Conection);	
				
				
				$Sql = "
					  UPDATE object_temp SET 
					  Id_Object = ".$Ultimo_Reg." 
					  WHERE Id_Object = ".$Id_Object."
				";
				ClassPDO::DCExecute($Sql, $Conection);
				
		
					
				$Sql = "
				INSERT INTO object SELECT * FROM object_temp WHERE Id_Object = ".$Ultimo_Reg."
				";
				ClassPDO::DCExecute($Sql, $Conection);
				
				$where = array('Id_Object' =>$Ultimo_Reg);
				$rg = ClassPDO::DCDelete('object_temp', $where, $Conection);	
				
				
                //Tabla de detalle

				$Sql = "
				INSERT INTO object_detail_temp SELECT * FROM object_detail WHERE Id_Object = ".$Id_Object."
				";
				ClassPDO::DCExecute($Sql, $Conection);

				
				$Query = " 
						  SELECT MAX(Id_Object_Detail) AS Ultimo_Reg_Det FROM object_detail 
						";
				$Where = [];
				$RegWH = ClassPDO::DCRow($Query,$Where ,$Conection);		
				$Ultimo_Reg_Det  = $RegWH->Ultimo_Reg_Det + 1;					


				$Query = "SELECT 
						  WD.Id_Object_Detail
						  FROM object_detail_temp WD 
						  
						  ";	
				$Where = ["Id_Object" => $Settings["Id_Object"]];
				
				$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
				$Fields ="";
				$Count = 0;
				foreach($Rows AS $Field){
					
					$Ultimo_Reg_Det += 1;	
				    $Id_Object_Detail = $Field->Id_Object_Detail;
				
					$Sql = "
						  UPDATE object_detail_temp SET 
						  Id_Object_Detail = ".$Ultimo_Reg_Det." , Id_Object = ".$Ultimo_Reg."
						  WHERE Id_Object_Detail = ".$Id_Object_Detail."   
					";
					// var_dump($Sql);
					ClassPDO::DCExecute($Sql, $Conection);
				
				}
				
				$Sql = "
				INSERT INTO object_detail SELECT * FROM object_detail_temp WHERE Id_Object = ".$Ultimo_Reg."
				";
				ClassPDO::DCExecute($Sql, $Conection);

				$where = array('Id_Object' =>$Ultimo_Reg);
				$rg = ClassPDO::DCDelete('object_detail_temp', $where, $Conection);	
				


        }else{
		        DCWrite(Message("Ya existe el archivo","C"));			
		}

		
			
	}
		
}