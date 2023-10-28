<?php
// session_start();
require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Cliente{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/cliente";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$UrlFile_Admin_Home = "/sadministrator/admin_home";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];
		
        $_SESSION['User'] = 1;
		$_SESSION['Entity'] = 21;
		
        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Cliente_CRUD":
					        			
							$Data = array();
							// $Data['Nameb'] = "daniel";						
							// $Data['Id_User_Creation'] = 1;						
							// $Data['Id_User_Update'] = 1;						
							// $Data['Date_Time_Creation'] = $DCTimeHour;						
							// $Data['Date_Time_Update'] = $DCTimeHour;		
							
							$Result = DCSave("Cliente_CRUD",$Conection,$Parm["Id"],"Id_Cliente",$Data);  
							$Id_Cliente = $Result["lastInsertId"];
							
							$Id_Pedido = $this->ObjectEntry($Id_Cliente);
							
						if($Redirect == "pedido"){
							$Settings = array();
							$Settings['Url'] = "/sadministrator/pedido/Interface/Details/Id_Pedido/".$Id_Pedido;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "";
							DCRedirectJS($Settings);
						}
						break;	
					case "Form_Suscripcion":
						// $this->Form_Suscripcion($Transaction);
						break;		
						
					case "form_994599":
					
							$Data = array();
							// $Data['Nameb'] = "daniel";						
							// $Data['Id_User_Creation'] = 1;						
							// $Data['Id_User_Update'] = 1;						
							// $Data['Date_Time_Creation'] = $DCTimeHour;						
							// $Data['Date_Time_Update'] = $DCTimeHour;		
							
							DCSave($Obj,$Conection,$Parm["Id"],"Id_articulo_b",$Data);  
							
							$Settings["Interface"] = "";
							new Skeditor($Settings);
							
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

				$Pestanas = Warehouse::Tabs(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]Marca","".$urlLinkB."]",""));					
				
				// $listMn .= "<i class='icon-chevron-right'></i> Save Details [".$UrlFile."/Process/ENTRY/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."/Obj/Obj_Object_detail_grilla[ScreenRight[FORM[Obj_Object_detail_grilla{";	
				
				// $Id = 4;
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/form_994599/Id/".$Id;				
				
				$Combobox = array(
				     array("Tipo"," SELECT Id_Data_Type AS Id, Name FROM data_type ",[]),
				     array("Familia"," SELECT Id_Field_Type AS Id, Name FROM field_type ",[])
				);
				
				$PathImage = array(
				     array("Nameb","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array("Guardar",$DirecctionA,"ScreenRight","Form","form_994599")
				);	
		        $Form1 = BFormVertical("form_994599",$Class,$Id,$PathImage,$Combobox,$Buttons,"Id_articulo_b");
				
				$AcordeonA = array(array("Acordeon_PanelA","Form Edit",$Form1),array("Acordeon_PanelB","col-md-6","Contenido B"));
				$Acordeones = DCAcordeon($AcordeonA);
				
				$PanelA =  $Acordeones;
		
				$Layout = array(array("PanelA","col-md-6",$PanelA),array("PanelB","col-md-6",$PanelB));
				$Content = DCLayout($Layout);
	
				$Contenido = DCPage($DCPanelTitle,$Pestanas ."<BR>".$Content .  $Plugin);
				
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				exit();
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
			 
				$Redirect = "/REDIRECT/pedido";			    
				
				$btn .= "<i class='zmdi zmdi-arrow-left'></i> Atrás ]" .$UrlFile_Admin_Home. $Redirect ."/Interface/Create]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'Crear_Pedido');

				$DCPanelTitle = DCPanelTitle("","Crear cliente y generar pedido",$btn);	
				
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Cliente_CRUD";				
				
				$Combobox = array(
				     array("Id_Cliente"," SELECT Id_Cliente AS Id, Nombre AS Name FROM cliente ",[])
				);
				
				$PathImage = array(
				     array("Nameb","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array("Crear",$DirecctionA,"ScreenRight","Form","Cliente_CRUD")
				);	
		        $Form = BFormVertical("Cliente_CRUD",$Class,$Id,$PathImage,$Combobox,$Buttons,"Id_articulo_b");
						
			    $Html = DCModalForm("Crear Cliente",$DCPanelTitle . $Form);
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

		$Id_Warehouse = DCPost("ky");
		$columnas='';
		for ($j = 0; $j < count($Id_Warehouse); $j++) {
			
			$Query = " SELECT Name AS Warehouse FROM warehouse WHERE Id_Warehouse = :Id_Warehouse ";
			$Where = ["Id_Warehouse"=>$Id_Warehouse[$j]];
			$Reg = ClassPDO::DCRow($Query,$Where ,$Conection);			
			// DCWrite("Warehouse:: ".$Reg->Warehouse."<br>");
			ClassPDO::DCDrop($Reg->Warehouse,$Conection);		

			$where = array('Id_Warehouse' =>$Id_Warehouse[$j]);
			$rg = ClassPDO::DCDelete('warehouse', $where, $Conection);
			
		}

		DCWrite(Message("Process executed correctly","C"));
						
	}	
	
	
	public function ObjectEntry($Id_Cliente) {
       	global $Conection, $DCTimeHour;
        
		$Fecha_Hora_Entrega_Programada = DCPost("Fecha_Hora_Entrega_Programada");

		$data = array(
		'Id_Cliente' => $Id_Cliente,
		'Estado' => "Pendiente",
		'Fecha_Hora_Entrega_Programada' => $Fecha_Hora_Entrega_Programada,
		'Date_Time_Creation' => $DCTimeHour,
		'Date_Time_Update' => $DCTimeHour
		);
		$Result = ClassPDO::DCInsert("pedido_cab", $data, $Conection);
		$Return = $Result["lastInsertId"];	
	
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