<?php
// session_start();
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class User_Perfil{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/pedido";
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
							
					case "Entity_Crud":
					
							$Data = array();
							$Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];						
							$Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Entity"],"Id_Entity",$Data);  
							
							$Settings["Interface"] = "";
							// $Settings["Id_Entity"] = $Parm["Id_Entity"];
							new SysSettingSite($Settings);
							DCExit();				
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

				$DCPanelTitle = DCPanelTitle("SITIO","Administración del sitio",$btn);

				$Pestanas = SetttingsSite::Tabs(array(
				"".$urlLinkB."]Marca"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]","".$urlLinkB."]",""));					
				
				// $listMn .= "<i class='icon-chevron-right'></i> Save Details [".$UrlFile."/Process/ENTRY/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."/Obj/Obj_Object_detail_grilla[ScreenRight[FORM[Obj_Object_detail_grilla{";	
				
				$Id = 4;
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Entity_Crud/Id/".$Id;				
				
				$Combobox = array(
				     array("Tipo"," SELECT Id_Data_Type AS Id, Name FROM data_type ",[]),
				     array("Familia"," SELECT Id_Field_Type AS Id, Name FROM field_type ",[])
				);
				
				$PathImage = array(
				     array("Nameb","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array("Guardar",$DirecctionA,"ScreenRight","Form","Entity_Crud")
				);	
		        $Form1 = BFormVertical("Entity_Crud",$Class,$Id,$PathImage,$Combobox,$Buttons,"Id_articulo_b");
				
				$AcordeonA = array(
				                  array("Acordeon_PanelA","Datos Principales",$Form1)
								  ,array("Acordeon_PanelB","Logo Interno","Contenido B")
								  );
				$Acordeones = DCAcordeon($AcordeonA);
				$PanelA =  $Acordeones;
				
				
				$AcordeonB = array(
				                  array("Acordeon_PanelC","Logo Externo",$Form1)
								  ,array("Acordeon_PanelD","Colores","Contenido B")
								  );
				$Acordeones = DCAcordeon($AcordeonB);
				$PanelB =  $Acordeones;				
				
				
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
				$btn .= "<i class='zmdi zmdi-edit'></i> Añadir Menú ]" .$UrlFile. $Redirect ."/Interface/Create_Detail/Id_Pedido/".$Parm["Id_Pedido"]."]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');
				
			
				$Query = "
				
				    SELECT CL.Nombre AS Cliente, PC.Estado
					, PC.Date_Time_Creation 
					, PC.Fecha_Hora_Entrega_Programada 
					FROM pedido_cab PC
					INNER JOIN cliente CL ON CL.Id_Cliente = PC.Id_Cliente
					WHERE PC.Id_Pedido_Cab = :Id_Pedido_Cab
				"; 
				$Where = ["Id_Pedido_Cab"=>$Parm["Id_Pedido"]];
				$Reg = ClassPDO::DCRow($Query,$Where ,$Conection);		
								

				$DCPanelTitle = DCPanelTitle("Pedido #".$Parm["Id_Pedido"].""," CLIENTE: ".$Reg->Cliente.",  ESTADO: ".$Reg->Estado.",
				F. CREACIÓN: ".$Reg->Date_Time_Creation.",  F. PROGRAMADA: ".$Reg->Fecha_Hora_Entrega_Programada." ",$btn);

			
				$Query = "
				
				    SELECT 
					CONCAT(
					' 
					  <div>
					    <div>',AR.Nombre,'</div>
					    <div>',ARB.Nombre,'</div>
					    <div>',ARC.Nombre,'</div>
					
					  </div>
					'
					) AS Menu
					, CONCAT(
					' 
					  <div>
					    <div style=color:green;font-weight:bold; >',PD.Observacion,'</div>
					  </div>
					'
					) AS 'Observación'							
					, PD.Cantidad
					, PD.Precio
					, PD.Total
					, PD.Id_Pedido_Det AS CodigoLink
					FROM pedido_det PD
					INNER JOIN movimiento_almacen MA ON PD.Id_Movimiento_Almacen = MA.Id_Movimiento_Almacen
					INNER JOIN articulo AR ON AR.Id_Articulo = MA.Id_Articulo
					
					INNER JOIN movimiento_almacen MAB ON PD.Id_Movimiento_Almacen_B = MAB.Id_Movimiento_Almacen
					INNER JOIN articulo ARB ON ARB.Id_Articulo = MAB.Id_Articulo
				
					INNER JOIN movimiento_almacen MAC ON PD.Id_Movimiento_Almacen_C = MAC.Id_Movimiento_Almacen
					INNER JOIN articulo ARC ON ARC.Id_Articulo = MAC.Id_Articulo
										
					WHERE PD.Id_Pedido_Cab = :Id_Pedido_Cab
				";    
				$Class = 'table';
				$LinkId = 'Id_Pedido_Det';
				$Link = $UrlFile."/Interface/Create_Detail/Id_Pedido/".$Parm["Id_Pedido"]."";
				$Screen = 'animatedModal5';
				
				$where = ["Id_Pedido_Cab"=>$Parm["Id_Pedido"]];
				
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
			 
			     
				$btn .= "<i class='zmdi zmdi-arrow-left'></i> Atrás ]" .$UrlFile_Admin_Home. $Redirect ."/Interface/Create]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'Crear_Pedido');

				$DCPanelTitle = DCPanelTitle("","Crear cliente y generar pedido",$btn);	
				
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Cliente_CRUD/Id/".$Id;				
				
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
	
	
            case "Create_Detail":
			
				
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Pedido_Det_Crud/Id_Pedido/".$Parm["Id_Pedido"]."/Id_Pedido_Det/".$Parm["Id_Pedido_Det"];				
				
			
				$Query = "
				  SELECT 
					CD.Id_Movimiento_Almacen AS Id
					, AR.Nombre AS Name
					FROM catalogo_det CD
					INNER JOIN movimiento_almacen MA ON CD.Id_Movimiento_Almacen = MA.Id_Movimiento_Almacen
					INNER JOIN articulo AR ON AR.Id_Articulo = MA.Id_Articulo
					INNER JOIN catalogo CT ON CD.Id_Catalogo = CT.Id_Catalogo 
					WHERE  SUBSTR(CT.Date_Time_Creation,1,10) =  :Date_Time_Creation AND CT.Estado = :Estado
					AND AR.Id_Tipo_Articulo = :Id_Tipo_Articulo
				";    
				 
		
				$Date = new DateTime($DCTimeHour);
				$DateF = $Date->format('Y-m-d');					 
				// $DateF = "2017-10-13";					 
				
				$Where = ["Date_Time_Creation"=>$DateF,"Estado"=>"Activo","Id_Tipo_Articulo"=>1];//Pltos de fondo
				$WhereB = ["Date_Time_Creation"=>$DateF,"Estado"=>"Activo","Id_Tipo_Articulo"=>3];//Pltos de Entrada
				$WhereC = ["Date_Time_Creation"=>$DateF,"Estado"=>"Activo","Id_Tipo_Articulo"=>4];//Refrezco
			
				
				$Combobox = array(
				     array("Id_Movimiento_Almacen",$Query,$Where,""),
				     array("Id_Movimiento_Almacen_B",$Query,$WhereB,""),
				     array("Id_Movimiento_Almacen_B",$Query,$WhereC,""),
				);
				
				$PathImage = array(
				     array("Nameb","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array("Guardar ".$DateF."",$DirecctionA,"ScreenRight","Form","Pedido_Det_Crud"),
				     array("Eliminar",$DirecctionA,"ScreenRight","Form","Pedido_Det_Crud")
				);
				
				if(!empty($Parm["Id_Pedido_Det"])){
					$Titulo = "Actualizar Menu";
				}else{
					$Titulo = "Crear Menu";					
				}
				
		        $Form = BFormVertical("Pedido_Det_Crud",$Class,$Parm["Id_Pedido_Det"],$PathImage,$Combobox,$Buttons,"Id_Pedido_Det");
						
			    $Html = DCModalForm($Titulo,$Form);
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