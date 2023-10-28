<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_warehouse.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();


class DetAdminToolsSite{

	private $Parm;
	
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$Redirect;
		
		$UrlFile = "/sadministrator/det_admin_tools_site";
		$Redirect = "/REDIRECT/admin_tool_site";
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Obj_Warehouse_detail":
						
						$this->ObjectEntry($Parm);
						DCCloseModal();
						
						$Settings["Interface"] = "Details";
						$Settings["Id_Warehouse"] = $Parm["Id_Warehouse"];
					    new DetAdminToolsSite($Settings);

						break;	
						
					case "Obj_Warehouse_detail_grilla":
						
						$this->ObjectEntryMassive($Parm);
						DCCloseModal();
						
						$Settings["Interface"] = "Details";
						$Settings["Id_Warehouse"] = $Parm["Id_Warehouse"];
					    new DetAdminToolsSite($Settings);

						break;	
						
					case "Obj_Warehouse_Cab":
						
						$this->ObjectDuplicateWarehouse($Parm);
						DCCloseModal();
						
						$Settings["Interface"] = "Details";
						$Settings["Id_Warehouse"] = $Parm["Id_Warehouse"];
					    new DetAdminToolsSite($Settings);

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
					case "Obj_Warehouse_detail_grilla":
						
						$this->ObjectDeleteBlock($Parm);
						$Settings["Interface"] = "Details";
						$Settings["Id_Warehouse"] = $Parm["Id_Warehouse"];
					    new DetAdminToolsSite($Settings);
						
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
				$RowWarehouse = Warehouse::MainData($Parm);	
		        
				$UrlFileObjectCreate = "/sadministrator/admin_object";
		
				$listMn = "<i class='icon-chevron-right'></i> Editar Warehouse [".$UrlFile_admin_warehouse."/Interface/Edit/Id_Warehouse/".$Parm["Id_Warehouse"]."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Save Details [".$UrlFile."/Process/ENTRY/Id_Warehouse/".$Parm["Id_Warehouse"]."/Obj/Obj_Warehouse_detail_grilla[ScreenRight[FORM[Obj_Warehouse_detail_grilla{";
				$listMn .= "<i class='icon-chevron-right'></i> Delete Details [".$UrlFile."/Process/DELETE/Id_Warehouse/".$Parm["Id_Warehouse"]."/Obj/Obj_Warehouse_detail_grilla[ScreenRight[FORM[Obj_Warehouse_detail_grilla{";
				$listMn .= "<i class='icon-chevron-right'></i> Create Object [".$UrlFileObjectCreate."/Interface/Create/Id_Warehouse/".$Parm["Id_Warehouse"]."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Duplicate Warehouse [".$UrlFile."/Interface/Duplicate_Warehouse/Id_Warehouse/".$Parm["Id_Warehouse"]."[animatedModal5[HXM[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Duplicate Warehouse [".$UrlFileObjectCreate."/Interface/Copy_Warehouse/Id_Warehouse/".$Parm["Id_Warehouse"]."[animatedModal5[HXM[{";

				$btn = "<i class='zmdi zmdi-edit'></i> Create Field ]" .$UrlFile ."/Interface/Create/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn .= "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("TOOLS"," Details of warehouse <b>".$RowWarehouse->Name."</b> | DETAILS ",$btn);
					
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
				
			    $Html = DCModalForm("Create Field",$Form);
                DCWrite($Html);
                DCExit();
                break;
				
            case "Edit":
			
			    $Form = $this->FormLocal($Parm,$UrlFile);
				
			    $Html = DCModalForm("Edit Field",$Form);
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
				
				
		
            case "Duplicate_Warehouse":
			
			    $Form = $this->FormLocal_Warehouse($Parm,$UrlFile);
			    $Html = DCModalForm("Duplicate Warehouse",$Form);
                DCWrite($Html);
                DCExit();
                break;			
				
				
			
        }
				
		
		
	}
	

	
	public function FormLocal_Warehouse($Settings,$UrlFile) {
		
        $Id_Warehouse = $Settings["Id_Warehouse"];	
        $Id_Object = $Settings["Id_Object"];	
	    
		// var_dump($Settings);
		
        $IdForm = "Obj_Warehouse_Cab";		
        $IdButton = "button_animatedModal5";	
        $Screen = "ScreenRight";	
        $Class = "btn btn-primary";	
		$NameButton = "Guardar";
		$Redirect = "";

		$Query = " SELECT Name FROM warehouse WHERE Id_Warehouse = :Id_Warehouse ";
		$Where = ["Id_Warehouse"=>$Settings["Id_Warehouse"]];
		$RowObject = ClassPDO::DCRow($Query,$Where ,$Conection);
		
		if(!empty($Id_Object)){
	        // $RowObject = Objects::MainData($Settings);	
			
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
		
	

	public function Grilla_Local($Settings,$UrlFile) {	
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
			
		    if( $Field->Pk == "SI"){
			    $Checked = 'checked="checked" ';
			    $Value = 'SI';
			}else{
			    $Checked = '';	
			    $Value = 'NO';				
			}
			
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
                  <td><input type="text" value="'.$Field->Length.'" name="'.$Count.'-Length" ></td>
                  <td>
				    <label class="custom-control custom-control-primary custom-radio">
                          <input class="custom-control-input" type="radio" name="Pk" value="'.$Field->Id_Warehouse_Detail.'" '.$Checked.'>
                          <span class="custom-control-indicator">'.$Field->Pk.'</span>
                    </label>
				  </td>
                  <td><input type="hidden" value="'.$Field->Id_Warehouse_Detail.'"  name="'.$Count.'-Id_Warehouse_Detail"></td>
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
                  <th>Field</th>
                  <th>Data Type</th>
                  <th>Length</th>
                  <th>Primary Key</th>
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
		
        $Redirect = "";	
		$Id_Warehouse = $Settings["Id_Warehouse"];
		
        $IdForm = "Obj_Warehouse_detail";	
        $IdButton = "button_animatedModal5";	
		
		$Id_Warehouse_Detail = $Settings["Id_Warehouse_Detail"];
		// var_dump($Settings);
		if(!empty($Id_Warehouse_Detail)){
            $Direcction = $UrlFile.$Redirect."/Process/CHANGE/Obj/".$IdForm."/Id_Warehouse/".$Id_Warehouse."/Id_Warehouse_Detail/".$Id_Warehouse_Detail;				
        
			$WarehouseDetail = WarehouseDetail::MainData($Settings);	
			
		}else{
            $Direcction = $UrlFile.$Redirect."/Process/ENTRY/Obj/".$IdForm."/Id_Warehouse/".$Id_Warehouse;				
		}
		
        $Screen = "ScreenRight";	
        $Class = "btn btn-primary";	
		$NameButton = "Guardar";
		
		
		$QueryA = " SELECT Id_Data_Type AS Id, Name FROM data_type ";
		$Where = [];
		$ComboBoxData_Type = DCComboBoxSelect($QueryA,$Where,$WarehouseDetail->Id_Data_Type,"Id_Data_Type","custom-select");
		

		$Html = '	

			<form id="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >		
		        <div class="modal-body">
				
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Name</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Name" name="Name" value="'.$WarehouseDetail->Name.'" >
				  </div>
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Data Type</label>
					'.$ComboBoxData_Type.'
				  </div>		
				  
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Length</label>
					<input type="text" class="form-control" id="form-control-2" placeholder="Length" name="Length" value="'.$WarehouseDetail->Length.'" >
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
		'Pk' => "NO",
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
			$TipoCampo = "INT(".$Length.") NOT NULL";
		}

		if($Id_Data_Type == 3){ //Data time
			$TipoCampo = " TEXT CHARACTER SET utf8 NOT NULL";
		}			
					
		if($Id_Data_Type == 4 ){ //Data time
			$TipoCampo = "DATE NOT NULL";
		}			

		if($Id_Data_Type == 5){ //Data time
			$TipoCampo = "DATETIME NOT NULL";
		}			
												
		if($Id_Data_Type == 6){ //Data time
			$TipoCampo = "TIME NOT NULL";
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

		$Warehouse_Detail = DCPost("Id_Warehouse_Detail");
		$columnas='';
		for ($j = 0; $j < count($Warehouse_Detail); $j++) {
			
			$Query = " 
					   SELECT 
					   WH.Name
					   , WHD.Name AS Field  
					   FROM warehouse WH
					   INNER JOIN warehouse_detail WHD ON WH.Id_Warehouse = WHD.Id_Warehouse
					   WHERE WHD.Id_Warehouse_Detail = :Id_Warehouse_Detail

					";
			$Where = ["Id_Warehouse_Detail"=>$Warehouse_Detail[$j]];
			$RegWH = ClassPDO::DCRow($Query,$Where ,$Conection);
			
			$Query = " ALTER TABLE " . $RegWH->Name . " DROP " . $RegWH->Field . "";
			$rg = ClassPDO::DCExecute($Query,$Conection);			
			
			$where = array('Id_Warehouse_Detail' =>$Warehouse_Detail[$j]);
			$rg = ClassPDO::DCDelete('warehouse_detail', $where, $Conection);
			
		}

		DCWrite(Message("Process executed correctly","C"));
						
	}	

		
	public function ObjectEntryMassive($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		
		// $Warehouse_Detail = DCPost("Id_Warehouse_Detail");
        $Query = "SELECT 
		          WD.Id_Warehouse_Detail
				  , WD.Name
				  , WD.Id_Data_Type
				  , WD.Length  
				  , W.Name AS warehouse  
		          FROM warehouse_detail WD 
				  INNER JOIN warehouse W ON WD.Id_Warehouse = W.Id_Warehouse
				  WHERE WD.Id_Warehouse = :Id_Warehouse
				  ";	
		$Where = ["Id_Warehouse" => $Settings["Id_Warehouse"]];
		
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		foreach($Rows AS $Field){
		    $Count += 1;			
			
		    $Name = DCPost($Count."-Name");
		    $Id_Data_Type = DCPost($Count."-Id_Data_Type");
		    $Length = DCPost($Count."-Length");
			
		    $Pk = DCPost("Pk");
		    $Id_Warehouse_Detail = $Field->Id_Warehouse_Detail; 
			
			if($Pk == $Id_Warehouse_Detail){
				$Pk = "SI";
				$Auto_Increment = " AUTO_INCREMENT ";
				
			}else{
				$Pk = "NO";	
				$Auto_Increment = " ";				
			}
			
			// 'Pk' => $Pk
			
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
								
			$Query = " ALTER TABLE " . $Field->warehouse . " ";
			$Query .= " CHANGE " . $Field->Name  . "  " . $Name . " " . $TipoCampo . "  ".$Auto_Increment." ";
			
			// DCWrite($Query."<br>");
			ClassPDO::DCExecute($Query,$Conection);
			
			
			$reg = array(
			'Name' => $Name,
			'Id_Data_Type' => $Id_Data_Type,
			'Length' => $Length
			);
			$where = array('Id_Warehouse_Detail' =>$Id_Warehouse_Detail);
			$rg = ClassPDO::DCUpdate("warehouse_detail", $reg , $where, $Conection);
			
		}
		DCWrite(Message("Process executed correctly","C"));
						
	}	
	
	public function ObjectChange($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		

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

        // var_dump($Settings);		
	
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
							
        // $Query = "ALTER TABLE " . $RegWH->Name . " ADD " . $Name . " " . $TipoCampo . " ";
		
		$Query = " ALTER TABLE " . $RegWH->Name . " ";
		$Query .= " CHANGE " . $RegWH->Field  . " " . $Name . " " . $TipoCampo . " ";
        ClassPDO::DCExecute($Query,$Conection);
		
		$reg = array(
		'Name' => DCPost("Name"),
		'Id_Data_Type' => DCPost("Id_Data_Type"),
		'Length' => DCPost("Length"),
		);
		
		$where = array('Id_Warehouse_Detail' =>$Settings["Id_Warehouse_Detail"]);
		$rg = ClassPDO::DCUpdate("warehouse_detail", $reg , $where, $Conection);	
			
	}
	
    public function ObjectDuplicateWarehouse($Settings){
       	global $Conection, $DCTimeHour,$NameTable;
		
        $Id_Warehouse = $Settings["Id_Warehouse"];	
        $Id_Object = $Settings["Id_Object"];

        $Name = DCPost("Name");		
        $Name_New = DCPost("Name_New");		

		$Query = " 
				  SELECT Name FROM warehouse WHERE Name =:Name
				";
		$Where = ["Name"=>$Name_New];
		$RegWH = ClassPDO::DCRow($Query,$Where ,$Conection);		
		$NameBD  = $RegWH->Name;		
		if(empty($NameBD)){
			
			
			    ///Tabla Cabezera
		
				$Sql = "
				INSERT INTO warehouse_temp SELECT * FROM warehouse WHERE Id_Warehouse = ".$Id_Warehouse."
				";
				ClassPDO::DCExecute($Sql, $Conection);
				
				$Query = " 
						  SELECT MAX(Id_Warehouse) AS Ultimo_Reg FROM warehouse 
						";
				$Where = ["Id_Warehouse"=>$Id_Warehouse];
				$RegWH = ClassPDO::DCRow($Query,$Where ,$Conection);		
				$Ultimo_Reg  = $RegWH->Ultimo_Reg + 1;		
				
				$User = $_SESSION['User'];
					
				$reg = array(
				'Id_User_Creation' => $User,
				'Id_User_Update' => $User,
				'Name' => $Name_New,
				);
				
				$where = array('Id_Warehouse' =>$Id_Warehouse);
				$rg = ClassPDO::DCUpdate("warehouse_temp", $reg , $where, $Conection);	
				
				
				$Sql = "
					  UPDATE warehouse_temp SET 
					  Id_Warehouse = ".$Ultimo_Reg." 
					  WHERE Id_Warehouse = ".$Id_Warehouse."
				";
				ClassPDO::DCExecute($Sql, $Conection);
				
				// var_dump($Ultimo_Reg);
					
				$Sql = "
				INSERT INTO warehouse SELECT * FROM warehouse_temp WHERE Id_Warehouse = ".$Ultimo_Reg."
				";
				ClassPDO::DCExecute($Sql, $Conection);
				
				$where = array('Id_Warehouse' =>$Ultimo_Reg);
				$rg = ClassPDO::DCDelete('warehouse_temp', $where, $Conection);	
				
				
                //Tabla de detalle

				$Sql = "
				INSERT INTO warehouse_detail_temp SELECT * FROM warehouse_detail WHERE Id_Warehouse = ".$Id_Warehouse."
				";
				ClassPDO::DCExecute($Sql, $Conection);

				
				$Query = " 
						  SELECT MAX(Id_Warehouse_Detail) AS Ultimo_Reg_Det FROM warehouse_detail 
						";
				$Where = [];
				$RegWH = ClassPDO::DCRow($Query,$Where ,$Conection);		
				$Ultimo_Reg_Det  = $RegWH->Ultimo_Reg_Det + 1;					

				$Query = "SELECT 
						  WD.Id_Warehouse_Detail
						  FROM warehouse_detail WD 
						  
						  ";	
				$Where = ["Id_Warehouse" => $Settings["Id_Warehouse"]];
				
				$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
				$Fields ="";
				$Count = 0;
				foreach($Rows AS $Field){
					$Ultimo_Reg_Det += 1;	
				    $Id_Warehouse_Detail = $Field->Id_Warehouse_Detail;
				
					$Sql = "
						  UPDATE warehouse_detail_temp SET 
						  Id_Warehouse_Detail = ".$Ultimo_Reg_Det." , Id_Warehouse = ".$Ultimo_Reg."
						  WHERE Id_Warehouse_Detail = ".$Id_Warehouse_Detail."   
					";
					ClassPDO::DCExecute($Sql, $Conection);
				
				}
				
				$Sql = "
				INSERT INTO warehouse_detail SELECT * FROM warehouse_detail_temp WHERE Id_Warehouse = ".$Ultimo_Reg."
				";
				ClassPDO::DCExecute($Sql, $Conection);

				$where = array('Id_Warehouse' =>$Ultimo_Reg);
				$rg = ClassPDO::DCDelete('warehouse_detail_temp', $where, $Conection);	
				


				$Sql = "
				CREATE TABLE ".$Name_New." LIKE ".$Name."
				";
				ClassPDO::DCExecute($Sql, $Conection);
				

        }else{
		        DCWrite(Message("Ya existe el archivo","C"));			
		}

		
	}
	
}