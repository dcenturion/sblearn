<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_warehouse.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class AdminEmpresa{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/admin-empresa";
		$UrlFileDet = "/sadministrator/det_admin_tools_site";
		$Redirect = "/REDIRECT/admin_tool_site";	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Entity_Register_Crud":
						
                        $Email_Contacto = DCPost("Email_Contacto");						
                        $Nombre_Contacto = DCPost("Nombre_Contacto");						
                        $Celular_Contacto = DCPost("Celular_Contacto");						
                        $Nombre = DCPost("Nombre");						
                        $Url = DCPost("Url");						
                        $Password = DCPost("Password");	
					
						if(empty($Nombre)){
							DCWrite(Message("Debe insertar el nombre de la empresa","C")); DCExit();
						}						
						if(empty($Url)){
							DCWrite(Message("Debe insertar la ulr de la empresa","C")); DCExit();
						}								
						if(empty($Nombre_Contacto)){
							DCWrite(Message("Debe insertar el Nombre del Contacto","C")); DCExit();
						}				
						
						if(empty($Email_Contacto)){
							DCWrite(Message("Debe insertar el Email del Contacto","C")); DCExit();
						}							
						
						if(empty($Password)){
							DCWrite(Message("Debe insertar el Password","C")); DCExit();
						}							


						$Query = "
							SELECT 
							ET.Id_Entity, ET.Url 
							FROM entity ET
							WHERE ET.Url = :Url			
						";	
						$Where = ["Url" => $Url];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Url_Bd = $Row->Url;
						

                        if(empty($Url_Bd)){
						
							// var_dump($_POST);
							
							$data = array(
							'Email_Contacto' =>  $Email_Contacto,
							'Nombre_Contacto' =>  $Nombre_Contacto,
							'Celular_Contacto' =>  $Celular_Contacto,
							'Nombre' => $Nombre,
							'Url' => $Url,
							'Password' => $Password,
							'Id_User_Update' => $User,
							'Id_User_Creation' => $User,
							'Date_Time_Creation' => $DCTimeHour,
							'Date_Time_Update' => $DCTimeHour
							);
							$Return = ClassPDO::DCInsert("entity", $data, $Conection,"");
							
							$_SESSION['Entity'] = $Return["lastInsertId"];	
							
							$_POST["Nombre"] = $Nombre_Contacto;
							$_POST["Email"] = $Email_Contacto;
							$Id_User = Edu_Register::Register_User($Parm);
							

							$Query = "
								SELECT 
								ET.Id_User_Creation
								FROM user_miembro ET
								WHERE ET.Id_User_Miembro = :Id_User_Miembro			
							";	
							$Where = ["Id_User_Miembro" => $Id_User];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Id_User_Creation = $Row->Id_User_Creation;
						
							
							
							if(!empty($Id_User)){
								
								$reg = array(
								'Id_Perfil' => 2
								);
								$where = array('Id_User' =>$Id_User_Creation);
								$rg = ClassPDO::DCUpdate("user", $reg , $where, $Conection,"");								
								
							}
							
							DCCloseModal();		
							
						}else{
							
							DCWrite(Message("La empresa ya se encuentra registrada ","C")); DCExit();							
							
						}
							
							
		                    $Settings["interface"] = "";
							new AdminEmpresa($Settings);							
						
						
		
						// $this->Form_Call($Transaction);
						break;	
					case "Form_Suscripcion":
						// $this->Form_Suscripcion($Transaction);
						break;							
				}			
				
				DCExit();
                break;
            case "UPDATE":

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
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Empresa ]" .$UrlFile."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("EMPRESAS","Administrador de Empresas ",$btn);
					
				$urlLinkB = "";
				
				$Pestanas = Warehouse::Tabs_Empresa(array(
				"".$urlLinkB."]Marca"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]","".$urlLinkB."]",""));					
						
					
				$Query = "
				    SELECT WR.Id_Entity AS CodigoLink, WR.Nombre, WR.Nombre_Contacto, WR.Email_Contacto FROM entity WR
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Warehouse';
				$Link = $UrlFileDet."/Interface/Details";
				$Screen = 'ScreenRight';
				$where = ["Entidad"=>$Entidad];
				$Content = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','HREF');
				 
				 
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Content .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
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
			
				$btn .= "Atrás]" .$UrlFile."/REDIRECT/".$Redirect."/interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Sub_Linea = $Parm["Id_Edu_Sub_Linea"];
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Entity_Register_Crud/";
				$DirecctionDelete = $UrlFile."/REDIRECT/".$Redirect."/interface/DeleteMassive/Id_Edu_Sub_Linea/".$Id_Edu_Sub_Linea;
				
				if(!empty($Id_Edu_Sub_Linea)){
				    $Name_Interface = "Editar Sub Línea";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Sub_Linea_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Empresa";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Edu_Area_Conocimiento"," SELECT Id_Edu_Area_Conocimiento AS Id, Nombre AS Name FROM edu_area_conocimiento ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Entity_Register_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Entity_Register_Crud",$Class,$Id_Edu_Sub_Linea,$PathImage,$Combobox,$Buttons,"Id_Edu_Sub_Linea");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
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
}