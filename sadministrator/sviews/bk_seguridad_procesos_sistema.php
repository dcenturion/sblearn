<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Procesos_Sistema{

    private $Parm;

    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		$UrlFile = "/sadministrator/procesos_sistema";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];		
		$Process = $Parm["Process"];
		$interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];
        switch ($Process) {
            case "":
                //$t = "Hola Mundo";
                //var_dump($t);
                //exit;
			
				$layout  = new Layout();
				//$btn .= "<i class='zmdi zmdi-edit'></i> Crear Usuario]" .$UrlFile."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("GESTIÓN DE PROCESOS ","Administre los distintos Procesos",$btn);
					
				$urlLinkB = "";
				$Pestanas = "";		
				
				//$btn = "<i class='zmdi zmdi-search-in-file'></i> ESTUDIO <br>]" .$Pro_Estudio."]ScreenRight]HXM_SP]btn btn-primary ladda-button}";
				$btn .= "<i class='zmdi zmdi-storage'></i>MD5 ]" .$UrlFile."/Process/Update_MD5]HXM]btn btn-primary ladda-button}";
				//$btn .= "<i class='zmdi zmdi-puzzle-piece'></i>DESARROLLO]" .$Edu_Testimnio."/Interface/List]animatedModal5]HXM]btn btn-primary ladda-button}";
				//$btn .= "<i class='zmdi zmdi-rss'></i>MARKETING ]" .$Edu_Sub_Sector."/Interface/List]animatedModal5]HXM]btn btn-primary ladda-button}";
				//$btn .= "<i class='zmdi zmdi-shopping-cart'></i>VENTA ]" .$Edu_Sub_Sector."/Interface/List]animatedModal5]HXM]btn btn-primary ladda-button}";
				//$btn .= "<i class='zmdi zmdi-library'></i> DICTANDO]" .$Edu_Especialista."/Interface/List]animatedModal5]HXM]btn btn-primary ladda-button}";
				//$btn .= "<i class='zmdi zmdi-comments'></i> FEEDBACK ]" .$Edu_Beneficio."/Interface/List]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones_icono', 'sys_form_b');
				
				
				$Html = $btn;
				  
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Html .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
					
            break;

            case "ENTRY":

				switch ($Obj) {
					case "Edu_Detalle_Vendedor_Crud":
					
                            $Data = array();
							$Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];						
							$Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Detalle_Vendedor_Evento"],"Id_Edu_Detalle_Vendedor_Evento",$Data);  
							
							$Settings["interface"] = "List";
							$Settings["REDIRECT"] = $Redirect;
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Edu_Detalle_Vendedor_Evento($Settings);
							DCExit();	
							
						// if($Redirect == "articulo"){
							// $Settings = array();
							// $Settings['Url'] = "/sadministrator/articulo";
							// $Settings['Screen'] = "ScreenRight";
							// $Settings['Type_Send'] = "";
							// DCRedirectJS($Settings);
						// }
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
							$Settings['Url'] = "/sadministrator/det_admin_object/interface/Details/Id_Object/".$Id_Object;
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
					case "Edu_Detalle_Vendedor_Crud":
						
						$this->ObjectDelete($Parm);
									
						$Settings["interface"] = "List";
						$Settings["REDIRECT"] = $Redirect;
						// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
						new Edu_Detalle_Vendedor_Evento($Settings);
						DCExit();
					
						
						break;	
						
				}	
				
                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":

                break;

			case "Update_MD5":

                    $Query = "SELECT Id_User,
                    ET.Password, 
                    ET.Nombre,
					ET.Encript
                    FROM user ET
                    ";
                    $Where = [];	
                    $Rows = ClassPdo::DCRows($Query,$Where,$Conection);

                    foreach($Rows AS $Field)
                    {   
                        if($Field->Encript == 0){   
							$Pass = DCEncriptar($Field->Password);

                            $Data = array(
								'Password' => $Pass,
								'Encript' => 1
                                );
                                $where = array('Id_User' =>$Field->Id_User);
                                $Reg = ClassPDO::DCUpdate("user", $Data , $where, $Conection);		
                                continue;
                                DCExit();
                        } else{
                            DCExit();  
                        }			
                    } 
                break;    

        }
		
		
		
        switch ($interface) {
        
            case "List":
			
		       $Id_Pro_Evento = $Parm["Id_Pro_Evento"];
				
				$Name_Interface = "Listado de Vendedores Para Eventos ";	
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/interface/Create/Id_Pro_Evento/".$Id_Pro_Evento."]animatedModal5]HXMS]]btn btn-primary ladda-button}";
				$btn .= " Borrar ]" .$UrlFile."/interface/Eliminar/Id_Pro_Evento/".$Id_Pro_Evento."]Msj-Accion]HXMS]]btn}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Estos son los procesos seleccionados ",$btn);
				
			
				
				$Query = "
				    SELECT EDV.Id_Edu_Detalle_Vendedor_Evento AS CodigoLink
					, UM.Nombre
					FROM edu_detalle_vendedor_evento EDV
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = EDV.Id_usuario
					WHERE EDV.Entity = :Entidad AND EDV.Id_Pro_Evento = :Id_Pro_Evento
				";    
				
				 // SELECT Id_Edu_Sub_Sector AS CodigoLink, Nombre AS Name FROM edu_sub_sector
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Detalle_Vendedor_Evento';
				$Link = $UrlFile."/interface/Create";
				$Screen = 'animatedModal5';
				$where = ["Entidad"=>$Entity,"Id_Pro_Evento"=>$Id_Pro_Evento];
				// var_dump($where);
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'proceso-detalle', 'checks', '','PS');				
			    $msj = "<div id='Msj-Accion'></div>";
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $msj . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;	

            case "Update_MD5":
                $data = "Hola Mundo";
                var_dump($data);
                exit;


                //$conexion = mysqli_connect('localhost', 'root', '', 'scabc');

                //$query    = mysqli_query($conexion, "SELECT valStockI FROM movimientos where idMovimiento >= " . $idMovimiento . " and idStockI= " . $idStockI . "");
                $Query = "SELECT ET.Password FROM user ET ";	
                $Rows = ClassPdo::DCRows($Query,$Conection);
                echo $Rows;
                exit;

                $stockvalorplus = 0;
                $stockvalorplus = $valornuevo - $valorantiguo;

                foreach ($query as $dato) {
                $a       = $dato['valStockI'];
                $conver  = str_replace(",", "", $a);
                $total   = $conver + $stockvalorplus;
                $conver2 = number_format($total, 2, '.', ',');
                echo $conver2;
                echo '</br>';

                mysqli_query($conexion, 'UPDATE movimientos set valStockI="  ' . $conver2 . ' "  where idMovimiento >=' . $idMovimiento . ' and idStockI= ' . $idStockI . ' ');

                }
    
                $Id_Edu_Sector = $Parm["Id_Edu_Sector"];
                $Query = "SELECT 
                            ET.Password 
                            FROM user ET 
                            WHERE ET.Id_Edu_Sub_Sector = :Id_Edu_Sub_Sector
                            ";	
                $Where = ["Id_Edu_Sub_Sector" => $Id_Edu_Sector];
                
                $Rows = ClassPdo::DCRows($Query,$Where,$Conection);
                $Html ="";
                $Count = 0;
                foreach($Rows AS $Field){
                    $Count += 1;			
                    
                    $Html .= '<option value="'.$Field->Id_Edu_Area_Conocimiento.'">'.$Field->Nombre.'</option>'; 
                    
                }			
                DCWrite($Html);
                DCExit();
                    
            break;

            
            case "Eliminar":
			// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[FORM[warehouse{";
		        $Id_Pro_Evento = $Parm["Id_Pro_Evento"];					
				$btn = "Confirmar ]" .$UrlFile."/interface/Eliminar-R/Id_Pro_Evento/".$Id_Pro_Evento."]animatedModal5]FORM]proceso-detalle]btn btn-default dropdown-toggle]}";				
				$btn .= "Cancelar ]" .$UrlFile ."/interface/List/Id_Pro_Evento/".$Id_Pro_Evento."]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'elimina_detalle_vendedor');					
				
			    $Html = DCModalFormMsjInterno("Confirma que deseas eliminar",$Form,$Button,"bg-info");
                DCWrite($Html);
				DCExit();
            break;	
				
				

            case "Create":
		        $Id_Pro_Evento = $Parm["Id_Pro_Evento"];
		        $Perfil_Escuela = 9;
				
				$btn = "Atrás]" .$UrlFile."/interface/List/Id_Pro_Evento/".$Id_Pro_Evento."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= " Agregar ]" .$UrlFile."/interface/agrega-sleccion/Id_Pro_Evento/".$Id_Pro_Evento."]Msj-Accion]HXMS]]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Selecciona y Agrega a tu lista ",$btn);
				
 
				$Query = "
				     SELECT UM.Id_User_Miembro AS CodigoLink, UM.Nombre  AS Name 
				     FROM user_miembro UM
				     INNER JOIN user U ON U.Id_User = UM.Id_User_Creation    
					 Where U.Id_Perfil_Escuela= :Perfil_Escuela

				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_User_Miembro';
				$Link = $UrlFile."/interface/Create";
				$Screen = 'animatedModal5';
				$where = ["Perfil_Escuela"=>$Perfil_Escuela];
				$Form1 = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'proceso-detalle', 'checks', '','PS');				

			    $msj = "<div id='Msj-Accion'></div>";
				$msj .= "<style>
				.bg-primary{    background-color: #000!important;
				border-color: #000!important;
				color: #fff!important;	
				} </style>";
			    $Html = DCModalForm("Listado de Procesos Objetivos",$DCPanelTitle . $msj .$Form1,"");
                DCWrite($Html . $Js);
                DCExit();
                break;		

            case "agrega-sleccion":
			// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[FORM[warehouse{";
		        $Id_Pro_Evento = $Parm["Id_Pro_Evento"];					
				$btn = "Confirmar ]" .$UrlFile."/interface/agrega-sleccion-R/Id_Pro_Evento/".$Id_Pro_Evento."]animatedModal5]FORM]proceso-detalle]btn btn-default dropdown-toggle]}";				
				$btn .= "Cancelar ]" .$UrlFile ."/interface/Create/Id_Pro_Evento/".$Id_Pro_Evento."]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form_confirma');					
				
			    $Html = DCModalFormMsjInterno("Confirma que deseas agregar",$Form,$Button,"bg-info");
                DCWrite($Html);
				DCExit();
                break;	
				
            case "agrega-sleccion-R":
			
		        $Id_Pro_Evento = $Parm["Id_Pro_Evento"];			
				$Id_Warehouse = DCPost("ky");
				$columnas='';
				for ($j = 0; $j < count($Id_Warehouse); $j++) {
					
				
					// DCWrite("Warehouse:: ".$Id_Warehouse[$j]."<br>");
					
					$data = array(
					'Id_Usuario' =>  $Id_Warehouse[$j],
					'Id_Pro_Evento' =>  $Id_Pro_Evento,
					'Entity' => $Entity,
					'Id_User_Update' => $User,
					'Id_User_Creation' => $User,
					'Date_Time_Creation' => $DCTimeHour,
					'Date_Time_Update' => $DCTimeHour
					);
					$Return = ClassPDO::DCInsert("edu_detalle_vendedor_evento", $data, $Conection,"");					
					
					
				}
				// DCCloseModal();	
				
				$Settings["interface"] = "List";
				$Settings["Id_Pro_Evento"] = $Id_Pro_Evento;
				new Edu_Detalle_Vendedor_Evento($Settings);
				DCExit();		
			
                break;			
							
							
				case "Eliminar-R":
			
		        $Id_Pro_Evento = $Parm["Id_Pro_Evento"];			
				$Id_Warehouse = DCPost("ky");
				$columnas='';
				for ($j = 0; $j < count($Id_Warehouse); $j++) {
					

					$where = array('Id_Edu_Detalle_Vendedor_Evento' =>$Id_Warehouse[$j]);
					$rg = ClassPDO::DCDelete('edu_detalle_vendedor_evento', $where, $Conection);				
								
					
				}
				
				$Settings["interface"] = "List";
				$Settings["Id_Pro_Evento"] = $Id_Pro_Evento;
				new Edu_Detalle_Vendedor_Evento($Settings);
				DCExit();		
			
                break;			

            case "Datos_Tipo_Empresa":
		
		        $Id_Edu_Sector = $Parm["Id_Edu_Sector"];
				
				$Query = "SELECT 
						  ET.Id_Edu_Area_Conocimiento
						  , ET.Nombre 
						  FROM edu_area_conocimiento ET 
						  WHERE ET.Id_Edu_Sub_Sector = :Id_Edu_Sub_Sector
						  ";	
				$Where = ["Id_Edu_Sub_Sector" => $Id_Edu_Sector];
				
				$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
				$Html ="";
				$Count = 0;
				foreach($Rows AS $Field){
					$Count += 1;			
					
					$Html .= '<option value="'.$Field->Id_Edu_Area_Conocimiento.'">'.$Field->Nombre.'</option>'; 
					
				}			
                DCWrite($Html);
				DCExit();
				
                break;					

            case "Datos_Fase":
		
		        $Id_Edu_Sector = $Parm["Id_Edu_Sector"];
		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				
				$Query = "
				SELECT al.Id_Edu_Articulo, al.Fase_Trabajo
				FROM edu_articulo  al
				WHERE al.Id_Edu_Articulo = :Id_Edu_Articulo ";
				$Where = ["Id_Edu_Articulo" => $Id_Edu_Articulo];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);
				$Fase_Trabajo = $Row->Fase_Trabajo;					
				
				
				$Query = "SELECT 
						  ET.Id_Edu_Area_Conocimiento AS Id
                          , ET.Nombre AS Name
						  FROM edu_area_conocimiento ET 
						  WHERE ET.Id_Edu_Sub_Sector = :Id_Edu_Sub_Sector
						  ";	
				$Where = ["Id_Edu_Sub_Sector" => $Id_Edu_Sector];
				
				$Rows = ClassPdo::DCRows($Query,$Where,$Conection);

				$return = Array();
				$return["Result"] = $Rows;
				$return["Result_Seleccionado"] = $Fase_Trabajo;
				$JsonJs = json_encode($return);	
                echo $JsonJs;
				DCExit();
				
                break;
				
            case "DeleteMassive":
		
		        $Id_Pro_Proceso_Detalle = $Parm["Id_Pro_Proceso_Detalle"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Pro_Proceso_Detalle/".$Id_Pro_Proceso_Detalle."/Obj/Pro_Proceso_Detalle_Crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/List]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;			

        }
	
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Pro_Proceso_Detalle = $Settings["Id_Pro_Proceso_Detalle"];
			
		$where = array('Id_Pro_Proceso_Detalle' =>$Id_Pro_Proceso_Detalle);
		$rg = ClassPDO::DCDelete('edu_formato', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		
	
	
	
}
