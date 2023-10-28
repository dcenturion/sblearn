<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class AdminHome{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/admin_home";
		$UrlFile_Cliente = "/sadministrator/cliente";
		$UrlFile_Pedido = "/sadministrator/pedido";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

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
				// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[HXM[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Editar Áreas[".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				// $btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				// $btn .= "<i class='zmdi zmdi-edit'></i> Crear Pedido ]" .$UrlFile."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				// $btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("Mi Oficina","Programas de capacitación",$btn);
					
				$urlLinkB = "";
				$Pestanas = Biblioteca::Tabs(array(
				"".$urlLinkB."]Marca"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]","".$urlLinkB."]",""));		
					
				$Query = "
				    SELECT 
					PC.Id_Pedido_Cab 
					, CL.Nombre AS Cliente
					, PC.Estado
					, PC.Date_Time_Creation AS 'Fecha Hora Creación'
					, PC.Fecha_Hora_Entrega_Programada AS 'Entrega Programada'
					
					FROM pedido_cab PC
					INNER JOIN cliente CL ON CL.Id_Cliente = PC.Id_Cliente
					WHERE SUBSTR(PC.Date_Time_Creation,1,10) = :Date_Time_Creation
				";    
				
				$Date = new DateTime($DCTimeHour);
				$DateF = $Date->format('Y-m-d');	
				$Where = ["Date_Time_Creation"=>$DateF];
				$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
				$Fields ="";
				$IdReporte ="Pedidos";
				$Count = 0;
				$Table = "<table width='100%' class='Report_Cab' >";

				foreach($Rows AS $Field){
					
					$Table .= "<tr>";
					$Table .= "<th>Codigo</th>";
					$Table .= "<th>Cliente</th>";
					$Table .= "<th> Estado</th>";
					$Table .= "<th> Total</th>";
					$Table .= "</tr>";
					
					
						$QueryB = "

						SELECT 
										
						 AR.Nombre AS Plato
						, ARB.Nombre AS Entrada
						, ARC.Nombre AS Refresco
						, PD.Observacion
						, PD.Cantidad
						, PD.Precio
						, PD.Total
						, PD.Id_Pedido_Cab
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
						$WhereB = ["Id_Pedido_Cab"=>$Field->Id_Pedido_Cab];
						$RowsB = ClassPdo::DCRows($QueryB,$WhereB,$Conection);

						$TableB = "<table width='100%' class='Report_Det'>";
						$TableB .= "<tr>";
						$TableB .= "<th>Menu </th>";
						$TableB .= "<th>Observación </th>";
						$TableB .= "<th>Cantidad </th>";
						$TableB .= "<th>Precio </th>";
						$TableB .= "<th>Total </th>";
						$TableB .= "</tr>";
						
						$Total = 0;
						foreach($RowsB AS $FieldB){
							
							$TableB .= "<tr>";
							$TableB .= "<td> ".$FieldB->Plato." <br> ".$FieldB->Entrada." <br> ".$FieldB->Refresco."</td>";
							$TableB .= "<td> ".$FieldB->Observacion."</td>";
							$TableB .= "<td> ".$FieldB->Cantidad."</td>";
							$TableB .= "<td> ".$FieldB->Precio."</td>";
							$TableB .= "<td> ".$FieldB->Total."</td>";
							$TableB .= "</tr>";

                            $Total += $FieldB->Total;						
							
						}	
						$TableB .= "</table>";	

						
					$Table .= "<tr 
						id='".$IdReporte.$Field->Id_Pedido_Cab."' 
						onclick=LoadPage(this); 
						role='row'
						direction='/sadministrator/pedido/Interface/Details/Id_Pedido/".$Field->Id_Pedido_Cab."'
						screen='ScreenRight'
						type_send=''
					> ";
					
					$Table .= "<td> <b style='color:#2196F3;font-size:1.3em;'> ".$Field->Id_Pedido_Cab." </b></td>";
					$Table .= "<td> ".$Field->Cliente."</td>";					
					$Table .= "<td> ".$Field->Estado."</td>";					
					$Table .= "<td> ".$Total."</td>";					
					$Table .= "</tr>";						

					
					$Table .= "<tr>";
					$Table .= "<td colspan='5' style='padding:0px 0px 60px 0px;'> ".$TableB."</td>";
					$Table .= "</tr>";	
					
				}		
				
				$Table .= "</table>";						
						
				
				
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Table .  $Plugin ,"panel panel-default");
				
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
			
			
				// $listMn = "<i class='icon-chevron-right'></i> Editar Estados [".$enlaceEstado."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Editar Áreas [".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				// $btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				
				$Redirect = "/REDIRECT/pedido";
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Cliente ]" .$UrlFile_Cliente. $Redirect ."/Interface/Create]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'Crear_Pedido');

				$DCPanelTitle = DCPanelTitle("","Crea al cliente si no Existe ",$btn);			
			
				$DirecctionA = $UrlFile_Pedido."/REDIRECT/pedido_cab/Process/ENTRY/Obj/pedido_cab_crud";				
				
				$Combobox = array(
				     array("Id_Cliente"," SELECT Id_Cliente AS Id, Nombre AS Name FROM cliente ",[])
				);
				
				$PathImage = array(
				     array("Nameb","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array("Crear",$DirecctionA,"ScreenRight","Form","pedido_cab_crud")
				);	
		        $Form1 = BFormVertical("pedido_cab_crud",$Class,$Id,$PathImage,$Combobox,$Buttons,"Id_articulo_b");
				
				// $DivOculto = "<div id='Div_Oculto'></div>";
			    $Html = DCModalForm("Crear Pedido",$DCPanelTitle . $Form1 ,"");
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