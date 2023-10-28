<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_warehouse.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();
$DCTime=DCDate();

class AdminToolsSite{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect,$DCTime;
		
		$UrlFile = "/sadministrator/admin_tools_site";
		$UrlFileDet = "/sadministrator/det_admin_tools_site";
		$Redirect = "/REDIRECT/admin_tool_site";	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];

        switch ($Process) {
            case "INSERT":

				switch ($Obj) {
					case "Form_Call":
						// $this->Form_Call($Transaction);
						break;	
					case "Form_Suscripcion":
						// $this->Form_Suscripcion($Transaction);
						break;							
				}			
				
				DCExit();
                break;
            case "UPDATE":
            	switch ($Obj) {
            		case 'Process_Update_Time_Suscriptores':
            			$Query = "	SELECT SUS.Id_User 
            						FROM suscripcion SUS 
            						WHERE SUS.Entity =:Entity AND SUS.Fecha_Fin=:Fecha_Fin AND SUS.Producto_Origen=:Producto_Origen";
						$Where = ["Entity"=>$Entity,"Fecha_Fin"=>$DCTime,"Producto_Origen"=>"CURSO"];
						$Reg = ClassPDO::DCRows($Query,$Where ,$Conection);
						$Countuser=0;
						foreach ($Reg as $result) {
							$Id_User_Foreach=$result->Id_User;
							if (!empty($Id_User_Foreach)) {
								$data = array('Visibilidad' =>"Inactivo");
								$where = array('Entity' =>$Entity,'Fecha_Fin'=>$DCTime,"Id_User"=>$Id_User_Foreach);
								ClassPDO::DCUpdate("suscripcion", $data , $where, $Conection);
								$Countuser+=1;

							}
							
						}
						//Program
						$Query = "	SELECT PA.Id_User 
            						FROM programa_alumno PA 
            						WHERE PA.Entity =:Entity AND PA.Fecha_Fin=:Fecha_Fin ";
						$Where = ["Entity"=>$Entity,"Fecha_Fin"=>$DCTime];
						$Reg = ClassPDO::DCRows($Query,$Where ,$Conection);
						$CountuserProgram=0;
						foreach ($Reg as $result) {
							$Id_User_ForeachPA=$result->Id_User;
							if (!empty($Id_User_ForeachPA)) {
								$data = array('Visibilidad' =>"Inactivo");
								$where = array('Entity' =>$Entity,'Fecha_Fin'=>$DCTime,"Id_User"=>$Id_User_ForeachPA,"Producto_Origen"=>"Programa");
								ClassPDO::DCUpdate("suscripcion", $data , $where, $Conection);

								$data2 = array('Estado' =>"Inactivo");
								$where2 = array('Entity' =>$Entity,'Fecha_Fin'=>$DCTime,"Id_User"=>$Id_User_ForeachPA);
								ClassPDO::DCUpdate("programa_alumno", $data2 , $where2, $Conection);
								$CountuserProgram+=1;

							}
							
						}
						if (($Countuser==0)AND($CountuserProgram==0)) {
							$Mensaje="No hay suscriptores general por desabilitar";
						}else if($Countuser==0){
							$Mensaje="No hay suscriptores por desabilitar";
							if ($CountuserProgram!=0) {
								DCWrite(Message("Se ha desabilitado a ".$CountuserProgram." suscripciones de programa.","C"));	
							}
						}else{
							$Mensaje="Se desabilitaron ".$Countuser." suscripciones <br> y ".$CountuserProgram." suscripciones de programa";
						}

						DCWrite(Message($Mensaje,"C"));	


            			
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
				$listMn .= "<i class='icon-chevron-right'></i> Ejecutar Procesos[".$UrlFile."/Interface/Procesos[animatedModal5[HXM[{";

				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Warehouse ]" .$UrlFile_admin_warehouse."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("TOOLS","Adminitrator of Warehouses",$btn);
					
				$urlLinkB = "";
				$Pestanas = Warehouse::Tabs(array(
				"".$urlLinkB."]Marca"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]","".$urlLinkB."]",""));					
						
					
				$Query = "
				    SELECT WR.Id_Warehouse AS CodigoLink, WR.Name, WR.Date_Time_Creation FROM warehouse WR
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Warehouse';
				$Link = $UrlFileDet."/Interface/Details";
				$Screen = 'ScreenRight';
				$where = ["Entidad"=>$Entidad];
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
				
				
            case "CreateUser":
			
			    $Form = $this->FormUser();
				
			    $Html = DCModalForm("Crear Usuario",$Form);
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
            case "Procesos":
			// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[FORM[warehouse{";
				
				$btn = "Culminacion de Suscriptores ]" .$UrlFile ."/Process/UPDATE/Obj/Process_Update_Time_Suscriptores]ScreenRight]FORM]warehouse]btn btn-default dropdown-toggle]}";				
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