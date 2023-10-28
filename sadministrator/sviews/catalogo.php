<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_pedido.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Catalogo{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/catalogo";
		$UrlFile_Cliente = "/sadministrator/cliente";
		$UrlFile_Catalogo = "/sadministrator/catalogo";
		// $UrlFileDet = "/sadministrator/det_admin_tools_site";
		// $Redirect = "/REDIRECT/admin_tool_site";	
		
        // $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
			
					case "Catalogo_Crud":
					case "Catalogo_Crud_B":
					
							$Data = array();
							if(!empty(DCPost("Estado"))){	
							    $Data['Estado'] = DCPost("Estado");	
                            }else{
							    $Data['Estado'] = "Desactivo";								
							}						
							
							// $Data['Id_User_Creation'] = 1;						
							// $Data['Id_User_Update'] = 1;						
							// $Data['Date_Time_Creation'] = $DCTimeHour;						
							// $Data['Date_Time_Update'] = $DCTimeHour;		
							DCCloseModal();	
							$Id_Catalogo = DCSave($Obj,$Conection,$Parm["Id_Catalogo"],"Id_Catalogo",$Data); 
                            
							if(!empty($Parm["Id_Catalogo"])){
								$Id_Catalogo = $Parm["Id_Catalogo"];
							}else{
								$Id_Catalogo = $Id_Catalogo["lastInsertId"];
							}							
							
							// $Settings = array();
							// $Settings['Url'] = "/sadministrator/catalogo/Interface/Details/Id_Catalogo/".$Id_Catalogo;
							// $Settings['Screen'] = "ScreenRight";
							// $Settings['Type_Send'] = "";
							// DCRedirectJS($Settings);
							$Settings["Interface"] = "Details";							
							$Settings["Id_Catalogo"] = $Id_Catalogo;							
							new Catalogo($Settings);
							DCExit();	

						break;		

					case "Catalogo_Det_Crud":
					
					
							$Id_Movimiento_Almacen = $this->Insert_Movimiento_Almacen($Parm);
					        
							// DCVd($Id_Movimiento_Almacen);
							$Data = array();
							// $Data['Nameb'] = "daniel";						
							$Data['Id_Catalogo'] = $Parm["Id_Catalogo"];						
							$Data['Id_Movimiento_Almacen'] = $Id_Movimiento_Almacen;						
							// $Data['Id_User_Update'] = 1;						
							// $Data['Date_Time_Creation'] = $DCTimeHour;						
							// $Data['Date_Time_Update'] = $DCTimeHour;		
						    DCCloseModal();							
							$Id_Catalogo = DCSave($Obj,$Conection,$Parm["Id_Catalogo_Det"],"Id_Catalogo_Det",$Data);  
						    
							$Settings["Interface"] = "Details";							
							$Settings["Id_Catalogo"] = $Parm["Id_Catalogo"];							
							new Catalogo($Settings);
							DCExit();							
						
							
						break;							
						
							
				}			
				
				DCExit();
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "Catalogo_Det_Crud":
						
						$this->ObjectDelete_Det($Parm);
						
						DCCloseModal();
									
						$Settings["Interface"] = "Details";
						$Settings["Id_Catalogo"] = $Parm["Id_Catalogo"];
					    new Catalogo($Settings);
						
						DCExit();							
											
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
				// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[HXM[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Editar Áreas[".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				// $btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Carta ]" .$UrlFile."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("CARTA","Listado de Cartas",$btn);
					
				$urlLinkB = "";
				$Pestanas = Pedido::Tabs(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]Marca"
				,"".$urlLinkB."]","".$urlLinkB."]",""));					
						
					
				$Query = "
				
				    SELECT 
						CT.Nombre
						,CT.Date_Time_Creation
						,CT.Id_Catalogo AS CodigoLink
					FROM catalogo CT
	
					WHERE SUBSTR(CT.Date_Time_Creation,1,10) = :Date_Time_Creation
				"; 
				
				$Date = new DateTime($DCTimeHour);
				$DateF = $Date->format('Y-m-d');	
		        // $DateF = "2017-10-13";	
				$Class = 'table';
				$LinkId = 'Id_Catalogo';
				$Link = $UrlFile_Catalogo."/Interface/Details";
				$Screen = 'ScreenRight';
				
				$where = ["Date_Time_Creation"=>$DateF];
				
				$Content = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','HREF');
				
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Content .  $Plugin ,"panel panel-default");
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;
				
            case "Details":
			
				$layout  = new Layout();
				
				$listMn = "<i class='icon-chevron-right'></i> Editar Carta [".$UrlFile."/Interface/Create/Id_Catalogo/".$Parm["Id_Catalogo"]."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Editar Áreas [".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Añadir Artículo ]" .$UrlFile. $Redirect ."/Interface/Create_Detail/Id_Catalogo/".$Parm["Id_Catalogo"]."]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');
				
					
				$Query = " SELECT Nombre, Fecha_Inicio, Estado FROM catalogo WHERE Id_Catalogo = :Id_Catalogo ";
				$Where = ["Id_Catalogo"=>$Parm["Id_Catalogo"]];
				$Reg = ClassPDO::DCRow($Query,$Where ,$Conection);					
					
				$urlLinkB = "";
				$Pestanas = Pedido::Tabs(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]Marca"
				,"".$urlLinkB."]","".$urlLinkB."]",""));					
											

				$DCPanelTitle = DCPanelTitle("<b style='color:#2196F3'> DETALLE DE LA CARTA # ".$Parm["Id_Catalogo"]." | ".$Reg->Nombre." </b> "
				," FECHA : ".$Reg->Fecha_Inicio.", Estado : ".$Reg->Estado." "
				,$btn);
					
				$Query = "
				    SELECT 
					AR.Nombre
					, CD.Cantidad_Ingreso
					, CD.Id_Catalogo_Det AS CodigoLink
					FROM catalogo_det CD
					INNER JOIN movimiento_almacen MA ON CD.Id_Movimiento_Almacen = MA.Id_Movimiento_Almacen
					INNER JOIN articulo AR ON AR.Id_Articulo = MA.Id_Articulo
					WHERE CD.Id_Catalogo = :Id_Catalogo
				";    
				
				$Class = 'table';
				$LinkId = 'Id_Catalogo_Det';
				$Link = $UrlFile."/Interface/Panel_Accion/Id_Catalogo/".$Parm["Id_Catalogo"]."";
				$Screen = 'animatedModal5';
	
				$where = ["Id_Catalogo"=>$Parm["Id_Catalogo"]];
				
				$Content = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','');
				 
				 
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle, $Pestanas . $Content .  $Plugin);
				
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;				

            case "Panel_Accion":
			
				// $listMn = "<i class='icon-chevron-right'></i> Eliminar Artículo[".$UrlFile."/Interface/DeleteMassive[animatedModal5[HXM[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Editar Áreas[".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				// $btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn = "<i class='zmdi zmdi-edit'></i> Editar ]" .$UrlFile."/Interface/Create_Detail/Id_Catalogo/".$Parm["Id_Catalogo"]."/Id_Catalogo_Det/".$Parm["Id_Catalogo_Det"]."]animatedModal5]HXMS]]btn btn-primary m-w-120}";
				$btn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar ]" .$UrlFile."/Interface/Delete_Register_Det/Id_Catalogo/".$Parm["Id_Catalogo"]."/Id_Catalogo_Det/".$Parm["Id_Catalogo_Det"]."]animatedModal5]HXMS]]btn btn-default m-w-120}";
				// $btn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar ]" .$UrlFile."/Interface/Create]animatedModal5]HXM]]btn btn-default m-w-120}";
				// $btn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar ]" .$UrlFile."/Interface/Create]animatedModal5]HXM]]btn btn-default m-w-120}";
				$btn = DCButton($btn, 'botones1', 'Panel_Accion_1');
				$DCPanelTitle = DCPanelTitle("Elige una opción","",$btn);
					
				// $Redirect = "";
				// $DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Articulo_CRUD/Id/".$Id;				
		
				// $DivOculto = "<div id='Div_Oculto'></div>";
			    $Html = DCModalForm("Acción por realizar en el registro",$DCPanelTitle ,"");
                DCWrite($Html);
                DCExit();
                break;
				
				
				
            case "Create":
			
			
				// $listMn = "<i class='icon-chevron-right'></i> Editar Estados [".$enlaceEstado."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Editar Áreas [".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				// $btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				
				$Redirect = "";
				$Id_Catalogo = $Parm["Id_Catalogo"];
				if(!empty($Id_Catalogo)){
				    $Id_Form = "Catalogo_Crud_B";					
				}else{
				    $Id_Form = "Catalogo_Crud";					
				}

				
				
				
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/".$Id_Form."/Id_Catalogo/".$Id_Catalogo;				
				
				$Combobox = array(
				     array("Id_Cliente"," SELECT Id_Cliente AS Id, Nombre AS Name FROM cliente ",[])
				);
				
				$PathImage = array(
				     array("Nameb","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array("Guardar",$DirecctionA,"ScreenRight","Form",$Id_Form)
				);	
		        $Form1 = BFormVertical($Id_Form,$Class,$Id_Catalogo,$PathImage,$Combobox,$Buttons,"Id_Catalogo");
				
				// $DivOculto = "<div id='Div_Oculto'></div>";
			    $Html = DCModalForm("Carta",$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                break;
				
			
            case "Create_Detail":
			
			
				// $listMn = "<i class='icon-chevron-right'></i> Editar Estados [".$enlaceEstado."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Editar Áreas [".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				// $btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				
				$Redirect = "";
				$Id_Catalogo_Det = $Parm["Id_Catalogo_Det"];			
				
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Catalogo_Det_Crud/Id_Catalogo/".$Parm["Id_Catalogo"]."/Id_Catalogo_Det/".$Parm["Id_Catalogo_Det"];				
				
				$Combobox = array(
				     array("Id_Articulo"," SELECT Id_Articulo AS Id, Nombre AS Name FROM articulo ",[])
				);
				
				$PathImage = array(
				     array("Nameb","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array("Guardar",$DirecctionA,"ScreenRight","Form","Catalogo_Det_Crud")
				);	
		        $Form1 = BFormVertical("Catalogo_Det_Crud",$Class,$Id_Catalogo_Det,$PathImage,$Combobox,$Buttons,"Id_Catalogo_Det");
				
				// $DivOculto = "<div id='Div_Oculto'></div>";
			    $Html = DCModalForm("Articulo",$DCPanelTitle . $Form1 ,"");
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
				
            case "Delete_Register_Det":
			// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[FORM[warehouse{";
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Obj/Catalogo_Det_Crud/Id_Catalogo/".$Parm["Id_Catalogo"]."/Id_Catalogo_Det/".$Parm["Id_Catalogo_Det"]."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;					
			
			
			
        }
				
		
		
	}
	

	public function FormUser() {		
		
		$Html = '		  
		    <div class="modal-body">
				<form>
				
				  <div class="form-group">
					<label for="form-control-1" class="control-label">Username</label>
					<div class="input-group">
					  <span class="input-group-addon">@</span>
					  <input type="text" class="form-control" id="form-control-1" placeholder="Username">
					</div>
				  </div>
				  
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Email</label>
					<input type="email" class="form-control" id="form-control-2" placeholder="Email">
				  </div>
				  <div class="form-group">
					<label for="form-control-3" class="control-label">Choose counrty</label>
					<select id="form-control-3" class="custom-select">
					  <option value="" selected="selected">Choose counrty</option>
					  <option value="1">Denmark</option>
					  <option value="2">Iceland</option>
					  <option value="3">Republic of Macedonia</option>
					  <option value="4">Saint Kitts and Nevis</option>
					  <option value="5">Vanuatu</option>
					  <option value="6">Yemen</option>
					  <option value="7">Zimbabwe</option>
					</select>
				  </div>
				  <div class="form-group">
					<label for="form-control-4" class="control-label">About You</label>
					<textarea id="form-control-4" class="form-control" rows="3"></textarea>
					<div class="help-block with-errors">Write some details about yourself.</div>
				  </div>
				  <div class="form-group">
					<label for="form-control-5" class="control-label">Password</label>
					<div class="row">
					  <div class="col-sm-6">
						<input type="password" class="form-control" id="form-control-5" placeholder="Password">
						<div class="help-block with-errors m-b-0">Minimum of 6 characters</div>
					  </div>
					  <div class="col-sm-6">
						<input type="password" class="form-control" id="form-control-6" placeholder="Confirm">
						<div class="help-block with-errors m-b-0"></div>
					  </div>
					</div>
				  </div>
				</form>
			  </div>
			  
			  <div class="modal-footer text-center">
				<button type="button" data-dismiss="modal" class="btn btn-primary">Continue</button>
			  </div>   
	    ';
		
		return $Html;
	}
	
	public function ObjectDelete_Det($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		
		$where = array('Id_Catalogo_Det' =>$Settings["Id_Catalogo_Det"]);
		$rg = ClassPDO::DCDelete('catalogo_det', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
				
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
	
	
	public function Insert_Movimiento_Almacen($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$data = array(
		'Id_Articulo' => DCPost("Id_Articulo"),
		'Cantidad' => DCPost("Cantidad_Ingreso"),
		'Date_Time_Creation' => $DCTimeHour,
		'Date_Time_Update' => $DCTimeHour
		);
		$ResultB = ClassPDO::DCInsert("movimiento_almacen", $data, $Conection);	
		
		// DCWrite(Message("Process executed correctly","C"));
		
		return $ResultB["lastInsertId"];
						
	}
}