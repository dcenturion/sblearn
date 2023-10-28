<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
require_once('./sviews/user.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Car_Det{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$UrlFile_edu_articulo_det;
        
		$UrlFile = "/sadministrator/edu_car_det";		
		$UrlFile_Edu_Tipo_Componente = "/sadministrator/edu_tipo_componente";
		$UrlFile_Edu_Tipo_Estructura = "/sadministrator/edu_tipo_estructura";
		$UrlFile_Edu_Area_Conocimiento = "/sadministrator/edu_area_conocimiento";
		$UrlFile_edu_articulo_det = "/sadministrator/edu_articulo_det";
		// $Url_B2 = "/sadministrator/edu_articulo_det";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					
					case "Edu_Datos_Entrega_Crud":
					
					        $Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];							
							$Nombre = DCPost("Nombre");
							$Apellidos = DCPost("Apellidos");
							$Telefono = DCPost("Telefono");
							$Direccion = DCPost("Direccion");
							$Departamento = DCPost("Departamento");
							$Provincia = DCPost("Provincia");
							$Distrito = DCPost("Distrito");
							
							if(empty($Nombre)){
								DCWrite(Message("Debe llenar el campo Nombre","C"));
								DCExit();
							}
							
							if(empty($Apellidos)){
								DCWrite(Message("Debe llenar el campo DNI o CARNET","C"));
								DCExit();
							}			
							
							if(empty($Telefono)){
								DCWrite(Message("Debe llenar el campo Telefono","C"));
								DCExit();
							}	
							
							if(empty($Telefono)){
								DCWrite(Message("Debe llenar el campo Telefono","C"));
								DCExit();
							}			

							if(empty($Direccion)){
								DCWrite(Message("Debe llenar el campo Dirección","C"));
								DCExit();
							}	
							
							if(empty($Departamento)){
								DCWrite(Message("Debe llenar el campo Departamento","C"));
								DCExit();
							}		
							if(empty($Provincia)){
								DCWrite(Message("Debe llenar el campo Provincia","C"));
								DCExit();
							}	
							if(empty($Distrito)){
								DCWrite(Message("Debe llenar el campo Distrito","C"));
								DCExit();
							}								
					        $Data = array();
							
							$Data['Destino_Entrega'] = $Parm["Destino_Entrega"]; 
							
							$Row = DCSave($Obj,$Conection,$Parm["Id_Edu_Datos_Entrega"],"Id_Edu_Datos_Entrega",$Data);
                            $Id_Edu_Datos_Entrega = $Row["lastInsertId"];
							
							if(empty($Parm["Id_Edu_Datos_Entrega"])){
						    	$Parm["Id_Edu_Datos_Entrega"] = $Id_Edu_Datos_Entrega;								
							}

							$this->Vincula_Proforma($Parm);

					
							$Settings = array();
							$Settings['Url'] = "/sadministrator/edu_car_det/Interface/Entrega/Id_Edu_Pedido/".$Id_Edu_Pedido."";
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "HXMS";
							$Settings['Type_Popup'] = "";
				
							DCRedirectJS($Settings);
							DCCloseModal();	
							DCExit();		
							
					    break;
						
											
					case "Edu_Datos_Tipo_Entrega_Crud":
					
					        $Destino_Entrega = $Parm["Destino_Entrega"];
					        $Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
					
							if( $Destino_Entrega =="Entrega_Tiendab" ){
								
								$Row = DCSave($Obj,$Conection,$Parm["Id_Edu_Datos_Entrega"],"Id_Edu_Datos_Entrega",$Data);
								$Id_Edu_Datos_Entrega = $Row["lastInsertId"];	

								$Query = "
									SELECT 
										EP.Nombre,
										EP.Direccion_Oficina,
										EP.Telefono_Soporte_Cliente
									FROM  entity EP
									WHERE 
									EP.Id_Entity = :Id_Entity
								";    
								$Where = ["Id_Entity"=>$Entity];	
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								
								$Nombre = $Row->Nombre;									
								$Telefono_Soporte_Cliente = $Row->Telefono_Soporte_Cliente;									
								$Direccion_Oficina = $Row->Direccion_Oficina;									
													
								$Reg = array(
									'Nombre' => $Nombre,
									'Telefono' => $Telefono_Soporte_Cliente,
									'Direccion' => $Direccion_Oficina
								);
								$Where = array('Id_Edu_Datos_Entrega' => $Id_Edu_Datos_Entrega);
								$rg = ClassPDO::DCUpdate('edu_datos_entrega', $Reg , $Where, $Conection);
									
								$Parm["Id_Edu_Datos_Entrega"] = $Id_Edu_Datos_Entrega;	
								
								$this->Vincula_Proforma($Parm);
								
								DCCloseModal();	
								$Settings["Interface"] = "Entrega";
								$Settings["Id_Edu_Pedido"] = $Parm["Id_Edu_Pedido"];
								new Edu_Car_Det($Settings);
								DCExit();	
	
							}
							
							DCExit();		
							
					    break;
						
						
						
					case "Edu_Datos_Comprobante_Crud":
					case "Edu_Datos_Comprobante_Crud_Factura":
	                        
		                    $Razon_Social = DCPost("Razon_Social");
		                    $Ruc = DCPost("Ruc");
		                    $Direccion = DCPost("Direccion");
		                    $Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
							
							if(empty($Razon_Social)){
								
								if($Obj == "Edu_Datos_Comprobante_Crud_Factura"){
								    $Nombre = "Razón Social";
								}else{
								    $Nombre = "Nombres y Apellidos";									
								}		
								
								DCWrite(Message("Debe llenar el campo ".$Nombre,"C"));
								DCExit();
							}	
							
							if(empty($Direccion)){
								DCWrite(Message("Debe llenar el campo Dirección","C"));
								DCExit();
							}		
							
							if(empty($Ruc)){
								
								if($Obj == "Edu_Datos_Comprobante_Crud_Factura"){
								    $Nombre = "RUC";
								}else{
								    $Nombre = "DNI O CARNET DE EXTRANGERÍA";									
								}
								DCWrite(Message("Debe llenar el campo ".$Nombre,"C"));
								DCExit();
							}	
							
							$Data = array();
							$Data['Id_Edu_Comprobante'] = $Parm["Id_Edu_Comprobante"]; 						

							$Row = DCSave($Obj,$Conection,$Parm["Id_Edu_Datos_Comprobante"],"Id_Edu_Datos_Comprobante",$Data);
                            $Id_Edu_Datos_Comprobante = $Row["lastInsertId"];
							
							if(empty($Parm["Id_Edu_Datos_Comprobante"])){
						    	$Parm["Id_Edu_Datos_Comprobante"] = $Id_Edu_Datos_Comprobante;								
							}

							$this->Vincula_Proforma_Comprobante($Parm);

					
							$Settings = array();
							$Settings['Url'] = "/sadministrator/edu_car_det/Interface/Comprobante/Id_Edu_Pedido/".$Id_Edu_Pedido."";
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "HXMS";
							$Settings['Type_Popup'] = "";
				
							DCRedirectJS($Settings);
							DCCloseModal();	
							
							DCExit();		
							
					    break;
						
						
					case "Proforma":
							
							$this->Editar_Pedido($Parm,"INSERT");
			
							$Settings["Interface"] = "y";
							$Settings["Id_Edu_Pedido"] = $Parm["Id_Edu_Pedido"];
							new Edu_Car_Det($Settings);
							DCExit();	
						
						break;	
						
					// case "Boleta":
					// case "Factura":
							
							// if($Obj == "Boleta"){$Id_Edu_Comprobante = 1;}
							// if($Obj == "Factura"){$Id_Edu_Comprobante = 2;}
							
							// $Reg = array(
							    // 'Id_Edu_Comprobante' => $Id_Edu_Comprobante
							// );
							// $Where = array('Id_Edu_Pedido' => $Parm["Id_Edu_Pedido"]);
							// $rg = ClassPDO::DCUpdate('edu_pedido', $Reg , $Where, $Conection);
			
							// $Settings["Interface"] = "Comprobante";
							// $Settings["Id_Edu_Pedido"] = $Parm["Id_Edu_Pedido"];
							// new Edu_Car_Det($Settings);
							// DCExit();	
						
						// break;	

					case "Solicitud_Pago_Crud":
						
						    
							//Proceso de validación en la pasarela
							//Proceso de Generación de Pedido
							$this->Procea_Pago($Parm);
							
							$Settings["Interface"] = "Pago";
							$Settings["Id_Edu_Proforma"] = $Parm["Id_Edu_Proforma"];
							new Edu_Car_Det($Settings);
							DCExit();	
							
						
						break;	

						
					case "Solicitud_Pago_Deposito_Crud":
					
					        // $Imagen_Voucher = DCPost("Imagen_Voucher");

					
						    if($Parm["Estado"] =="Confirmar"){
								
							    $Estado = "Por_Confirmar";
							    $Interface = "Pago";
								
								$Reg = array(
									'Estado_Pago' => $Estado
								);
								$Where = array('Id_Edu_Pedido' => $Parm["Id_Edu_Pedido"]);
								$rg = ClassPDO::DCUpdate('edu_pedido', $Reg , $Where, $Conection);	
					
								
							}else{

								$Imagen_Voucher = $_FILES["Imagen_Voucher"];
								$FileName       = $Imagen_Voucher["name"];
								
								if(empty($FileName)){
									
									DCWrite(Message("Debes adjuntar la imagen ".$fileName,"C"));
									// DCVd($fileName);
									DCExit();								
								}
								
							    $Interface = "confirma_pago_deposito";								
							    $Estado = "En_Proceso";	
							    $this->Procea_Pago($Parm);
								
								$Reg = array(
									'Estado_Pago' => $Estado
								);
								$Where = array('Id_Edu_Pedido' => $Parm["Id_Edu_Pedido"]);
								$rg = ClassPDO::DCUpdate('edu_pedido', $Reg , $Where, $Conection);										
							}
							
							$Data = array();
							$Data['Id_Edu_Pedido'] = $Parm["Id_Edu_Pedido"]; 								
							$Data['Estado'] = $Estado; 								
							$Data['Fecha_Hora_Pago'] = $DCTimeHour; 								
							$Row = DCSave($Obj,$Conection,$Parm["Id_Solicitud_Pago"],"Id_Solicitud_Pago",$Data);
                            $Id_Solicitud_Pago = $Row["lastInsertId"];  
							
							if(empty($Id_Solicitud_Pago)){
								 $Id_Solicitud_Pago = $Parm["Id_Solicitud_Pago"];
							}
							
						    if($Parm["Estado"] =="Confirmar"){	
								
								// DCCloseModal();	
								// $Settings = array();
								$Url = "/sadministrator/edu_car_det/Interface/".$Interface."/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Id_Solicitud_Pago/".$Id_Solicitud_Pago."/Request/On";
								// $Settings['Screen'] = "ScreenRight";
								// $Settings['Type_Send'] = "HXMS";
								// $Settings['Type_Popup'] = "";
								// DCRedirectJS($Settings);
								
						        DCRedirectHREF($Url);
							

 							}else{       
							
								$Settings = array();
								$Settings['Url'] = "/sadministrator/edu_car_det/Interface/".$Interface."/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Id_Solicitud_Pago/".$Id_Solicitud_Pago;
								$Settings['Screen'] = "animatedModal5";
								$Settings['Type_Send'] = "HXM";
								$Settings['Type_Popup'] = "";
								DCRedirectJS($Settings);
                            }							

							DCExit();	
							
						break;	
						

						
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "Proforma":
					
			                $this->Editar_Pedido($Parm,"DELETE");
							
							$Settings["Interface"] = "y";
							$Settings["Id_Edu_Pedido"] = $Parm["Id_Edu_Pedido"];
							new Edu_Car_Det($Settings);
							DCExit();	
						
						break;						
					
					case "Proforma_Item":
							
			                $this->Editar_Pedido($Parm,"DELETE_ALL");
							$Settings["Interface"] = "y";
							$Settings["Id_Edu_Pedido"] = $Parm["Id_Edu_Pedido"];
							new Edu_Car_Det($Settings);
							DCExit();	
						
						break;		
						
						
					case "edu_datos_entrega":
							
			                $this->Eliminar_Direccion($Parm);
							$Settings["Interface"] = "Entrega";
							$Settings["Id_Edu_Pedido"] = $Parm["Id_Edu_Pedido"];
							new Edu_Car_Det($Settings);
							DCExit();	
						
						break;		

					case "edu_datos_comprobante":
							
			                $this->Eliminar_Datos_Comprobante($Parm);
							$Settings["Interface"] = "Comprobante";
							$Settings["Id_Edu_Pedido"] = $Parm["Id_Edu_Pedido"];
							new Edu_Car_Det($Settings);
							DCExit();	
						
						break;		

						
				}	
				
                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":

						$this->Search($Parm);
						DCExit();						
                break;				
        }
		
		
		
        switch ($Interface) {
            case "y":
					
				$layout  = new Layout();
				$Redirect = "/REDIRECT/articulo";
	            $Id_Edu_Pedido_BD = $Parm["Id_Edu_Pedido"];
	            $Nro_Pedidos = $Parm["Nro_Pedidos"];
                $Key = $Parm["Key"];
				
				$Row = Biblioteca::Pedido_Datos($Parm,$Id_Edu_Pedido_BD);			
				$Total_Cantidad = $Row->Total_Cantidad;					
				$Total_Precio = $Row->Total_Precio;					
				$Estado_Pedido = $Row->Estado;
				$Id_Edu_Pedido = $Row->Id_Edu_Pedido;
				
				// DCWrite("Hola mundo". $Nro_Pedidos);
				// DCExit();
				if(!empty($Nro_Pedidos)){
					
					if($Total_Cantidad == 1){
						
						// $Settings = array();
						// $Settings['Url'] = "/sadministrator/edu_car_det/Interface/Pedidos/Id_Edu_Pedido/".$Id_Edu_Pedido."/Nro_Pedidos/".$Nro_Pedidos."";
						// $Settings['Screen'] = "ScreenRight";
						// $Settings['Type_Send'] = "";
						// DCRedirectJS($Settings);
						// break;					
						
					}else{					
						
						$Settings = array();
						$Settings['Url'] = "/sadministrator/edu_car_det/Interface/Pedidos/Id_Edu_Pedido/".$Id_Edu_Pedido."/Nro_Pedidos/".$Nro_Pedidos."";
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);
						break;				
					}
					
				}
				
		        $User = $_SESSION['User'];				
				if(empty($Id_Edu_Pedido_BD) && !empty($User)){
					
	
					if($Total_Cantidad == 1){
						
						$Settings = array();
						$Settings['Url'] = "/sadministrator/edu_car_det/Interface/Pedidos/Id_Edu_Pedido/".$Id_Edu_Pedido."/Nro_Pedidos/".$Nro_Pedidos."";
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);
						break;					
						
					}else{
						
						$Settings = array();
						$Settings['Url'] = "/sadministrator/edu_car_det/Interface/Pedidos";
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);
						break;	
					}
					
				}
				
				if(empty($Id_Edu_Pedido_BD) && empty($User)){
					
					if(!empty($Id_Edu_Pedido) ){

						if($Total_Cantidad == 1){
							
							// $Settings = array();
							// $Settings['Url'] = "/sadministrator/edu_car_det/Interface/Pedidos/Id_Edu_Pedido/".$Id_Edu_Pedido."";
							// $Settings['Screen'] = "ScreenRight";
							// $Settings['Type_Send'] = "";
							// DCRedirectJS($Settings);
							// break;	
							$Parm["Id_Edu_Pedido"] = $Id_Edu_Pedido;
							
						}else{
							
							$Settings = array();
							$Settings['Url'] = "/sadministrator/edu_car_det/Interface/Pedidos";
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "";
							DCRedirectJS($Settings);
							break;	
						}					
					

						
					}else{
						
						$Settings = array();
						$Settings['Url'] = "/sadministrator/edu_car_det/Interface/NoHayPedido";
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);
						break;							
					}	
						
				}
				
				
				if(empty($Id_Edu_Pedido) && empty($User)){					
					
					$Settings = array();
					$Settings['Url'] = "/sadministrator/edu_car_det/Interface/NoHayPedido";
					$Settings['Screen'] = "ScreenRight";
					$Settings['Type_Send'] = "";
					DCRedirectJS($Settings);
                    break;			
					
				}				
				
			
				
				if($Estado_Pedido == "Cerrado"){
					$Subtitulo = "El pedido está cerrado, ya no es posible editarlo";
				}
				
				$DCPanelTitle = DCPanelTitle("<h4><b>Carrito de Compras</b></h4> ".$Subtitulo,"",$btn);

				
                $Grilla_Pedido = $this->Grilla_Pedido($Parm,$Estado_Pedido);
				
				if(!empty($Id_Edu_Datos_Entrega)){
				    $Form_Entrega = $this->Direcciones_Entrega($Parm);
				}else{
				    $Form_Entrega = $this->Form_Entrega($Parm);
				}
				$Form_Comprobante = $this->Form_Comprobante($Parm,$Id_Edu_Comprobante);				
				$Form_Pago = $this->Form_Pago($Parm);			
				
				
				$Url_Btn = $UrlFile."/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"];
					 
				$Step_Interface = array(
				"Botones" => array( array("Pedido","Active",$Url_Btn."/Interface/y")
				                  , array("Entrega","",$Url_Btn."/Interface/Entrega")
				                  , array("Comprobante","",$Url_Btn."/Interface/Comprobante")
								  , array("Pago","",$Url_Btn."/Interface/Pago")
								  ) ,
				"Paneles" => array($Grilla_Pedido,$Form_Entrega,$Form_Comprobante,$Form_Pago)
				);

				$Step_Interface =  Step_Interface($Step_Interface);
				$Resumen =  $this->Resumen_Pedido($Parm);
				
						
		
				if($Estado_Pedido == "Cerrado"){
                     $btn = " Continuar con la vista siguiente  ]".$UrlFile."/Interface/Entrega/Id_Edu_Pedido/".$Id_Edu_Pedido."]ScreenRight]]]btn btn-primary m-w-120}";						
		        }else{					
		            $btn = " Proceder a Pagar ( ".$Total_Cantidad." Artículos )  ]".$UrlFile."/Interface/Entrega/Id_Edu_Pedido/".$Id_Edu_Pedido."]ScreenRight]]]btn btn-primary m-w-120}";								
				}
		        
				$Objectos_Accion = "<div id='Boton_Compras'>".DCButton($btn, 'botones1', 'sys_form_b'.$Count)."</div>";					
				$Boton_Pagar = $Objectos_Accion; 
				
				
				
				$Plugin = DCTablePluginA();
				
				$Layout = array(array("PanelA_Car","col-md-9",$Step_Interface),array("PanelB_Car","col-md-3",$Resumen . $Boton_Pagar));
				$Page = DCLayout($Layout);
				
				$Contenido = DCPage($DCPanelTitle ,$Page .  $Plugin ,"panel panel-default m-b-0","Pedido_Det");

				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;
				
        		
            case "Pedidos":
			
				$layout  = new Layout();
		        $User = $_SESSION['User'];					
		        $Id_Edu_Pedido = $Parm['Id_Edu_Pedido'];					
				$DCPanelTitle = DCPanelTitle("MIS COMPRAS","",$btn);	
				
				// if(!empty($Id_Edu_Pedido) && empty($User)){
					
					// $Settings = array();
					// $Settings['Url'] = "/sadministrator/edu_car_det/Interface/Pedidos_Carrito/Id_Edu_Pedido/".$Id_Edu_Pedido."";
					// $Settings['Screen'] = "ScreenRight";
					// $Settings['Type_Send'] = "";
					// DCRedirectJS($Settings);
                    // break;	
					
				// }
				
                ////xxxxxxxxxxxxxxxxxx
				$urlLinkB = "";
				$Pestanas = Biblioteca::Tabs_Pedidos_De(array(
				"".$urlLinkB."]Marca"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]"
				,""));	
				
				$Query = "
				    SELECT EP.Id_Edu_Pedido AS CodigoLink
					,CONCAT('<h4 class=m-y-0>',EP.Id_Edu_Pedido,'</h4>')  AS 'Código'
					, DATE_FORMAT(EP.Date_Time_Creation, '%W %M %e %Y') AS 'Creación'
					, EP.Total_Cantidad AS 'Ctd. Productos'					
					, CONCAT(  IF(EP.Id_Edu_Tipo_Moneda=1,'S/.','$'),'  ',EP.Total_Precio ) AS 'Precio Total'
					, EP.Estado AS 'Estado Pedido'	
					, EP.Estado_Pago AS 'Estado de Pago'	
					, CONCAT( '<b style=color:blue; >Ver detalle </b>') AS 'Acción'
			
					FROM edu_pedido EP
					WHERE 
					EP.Id_User_Creation = :Id_User_Creation
					AND ( EP.Estado = :Estado OR  EP.Estado = :EstadoB OR EP.Estado = :EstadoD )
					ORDER BY EP.Estado ASC
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Pedido';
				$Link = $UrlFile."/Interface/y";
				$Screen = 'ScreenRight';
				$where = ["Id_User_Creation"=>$User,"Estado"=>"Pendiente","EstadoB"=>"Seleccion","EstadoD"=>"En_Proceso"];
				$Content = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','HREF');
				 
				$Plugin = DCTablePluginA();
				
				$Layout = array(array("PanelA","col-md-12", $Content));
				$Page = DCLayout($Layout);
				
				$Contenido = DCPage($DCPanelTitle . $Pestanas , $Page .  $Plugin ,"panel panel-default m-b-0");

				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
						
                break;
				
            case "Pedidos_Carrito":
			
				$layout  = new Layout();
				$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
				// DCWrite("Hola mundo". $Id_Edu_Pedido);
		        $Session_ID = session_id();					
				$Query = "
					SELECT 
					EP.Id_Edu_Proforma  
					FROM edu_proforma EP
					WHERE 
					EP.Sesion_Id = :Sesion_Id 
					AND  ( EP.Estado = :Estado OR  EP.Estado = :EstadoB )
					AND  EP.Id_Edu_Cliente = :Id_Edu_Cliente
				";	
				$Where = ["Sesion_Id" => $Session_ID , "Estado"=>"Pendiente", "EstadoB"=>"En_Proceso", "Id_Edu_Cliente"=>0];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Proforma = $Row->Id_Edu_Proforma;						

				$Query = "
					SELECT 
					EP.Id_Edu_Pedido  
					FROM edu_pedido EP
					WHERE 
					EP.Id_Edu_Proforma = :Id_Edu_Proforma 
				";	
				$Where = ["Id_Edu_Proforma" => $Id_Edu_Proforma];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Pedido = $Row->Id_Edu_Pedido;					
				
				$DCPanelTitle = DCPanelTitle("CARRITO DE COMPRAS","",$btn);			
				
				$Query = "
				    SELECT EP.Id_Edu_Pedido AS CodigoLink
					,CONCAT('<h4 class=m-y-0>',EP.Id_Edu_Pedido,'</h4>')  AS 'Código'
					, DATE_FORMAT(EP.Date_Time_Creation, '%W %M %e %Y') AS 'Creación'
					, EP.Total_Cantidad AS 'Ctd. Productos'					
					, CONCAT(  IF(EP.Id_Edu_Tipo_Moneda=1,'S/.','$'),'  ',EP.Total_Precio ) AS 'Precio Total'
					, EP.Estado AS 'Estado Pedido'	
					, EP.Estado_Pago AS 'Estado de Pago'	
					, CONCAT( '<b style=color:blue; >Ver detalle </b>') AS 'Acción'
					FROM edu_pedido EP
					WHERE 
					EP.Id_Edu_Pedido = :Id_Edu_Pedido
					ORDER BY EP.Estado ASC
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Pedido';
				$Link = $UrlFile."/Interface/y";
				$Screen = 'ScreenRight';
				$where = ["Id_Edu_Pedido"=>$Id_Edu_Pedido];
				$Content = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','HREF');
				 
				$Plugin = DCTablePluginA();
				
				$Layout = array(array("PanelA","col-md-12", $Content));
				$Page = DCLayout($Layout);
				
				$Contenido = DCPage($DCPanelTitle . $Pestanas , $Page .  $Plugin ,"panel panel-default m-b-0");

				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
						
                break;				

            case "Pedidos_Pagos":
			
				$layout  = new Layout();
				
				$DCPanelTitle = DCPanelTitle("MIS COMPRAS","",$btn);				
                ////xxxxxxxxxxxxxxxxxx
				$urlLinkB = "";
				$Pestanas = Biblioteca::Tabs_Pedidos_De(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]Marca"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]"
				,""));	
				
				$Query = "
				    SELECT EP.Id_Edu_Pedido AS CodigoLink
					,CONCAT('<h4 class=m-y-0>',EP.Id_Edu_Pedido,'</h4>')  AS 'Código'
					, DATE_FORMAT(EP.Date_Time_Creation, '%W %M %e %Y') AS 'Creación'
					, EP.Total_Cantidad AS 'Ctd. Productos'					
					, CONCAT(  IF(EP.Id_Edu_Tipo_Moneda=1,'S/.','$'),'  ',EP.Total_Precio ) AS 'Precio Total'
					, EP.Estado AS 'Estado Pedido'	
					, EP.Estado_Pago AS 'Estado de Pago'	
					, CONCAT( '<b style=color:blue; >Ver detalle </b>') AS 'Acción'
			
					FROM edu_pedido EP
					WHERE 
					EP.Id_User_Creation = :Id_User_Creation
					AND  EP.Estado_Pago = :Estado_Pago 
					ORDER BY EP.Estado ASC
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Pedido';
				$Link = $UrlFile."/Interface/y";
				$Screen = 'ScreenRight';
				$where = ["Id_User_Creation"=>$User,"Estado_Pago"=>"Por_Confirmar"];
				$Content = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','HREF');
				 
				$Plugin = DCTablePluginA();
				
				$Layout = array(array("PanelA","col-md-12", $Content));
				$Page = DCLayout($Layout);
				
				$Contenido = DCPage($DCPanelTitle . $Pestanas , $Page .  $Plugin ,"panel panel-default m-b-0");

				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
						
                break;
				
				

            case "Pedidos_Cerrados":
			
				$layout  = new Layout();
				
				$DCPanelTitle = DCPanelTitle("MIS COMPRAS","",$btn);				
                ////xxxxxxxxxxxxxxxxxx
				$urlLinkB = "";
				$Pestanas = Biblioteca::Tabs_Pedidos_De(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]Marca"
				,"".$urlLinkB."]"
				,""));	
				
				$Query = "
				    SELECT EP.Id_Edu_Pedido AS CodigoLink
					,CONCAT('<h4 class=m-y-0>',EP.Id_Edu_Pedido,'</h4>')  AS 'Código'
					, DATE_FORMAT(EP.Date_Time_Creation, '%W %M %e %Y') AS 'Creación'
					, EP.Total_Cantidad AS 'Ctd. Productos'					
					, CONCAT(  IF(EP.Id_Edu_Tipo_Moneda=1,'S/.','$'),'  ',EP.Total_Precio ) AS 'Precio Total'
					, EP.Estado AS 'Estado Pedido'	
					, EP.Estado_Pago AS 'Estado de Pago'	
					, CONCAT( '<b style=color:blue; >Ver detalle </b>') AS 'Acción'
			
					FROM edu_pedido EP
					WHERE 
					EP.Id_User_Creation = :Id_User_Creation
					AND  EP.Estado_Pago = :Estado_Pago 
					ORDER BY EP.Estado ASC
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Pedido';
				$Link = $UrlFile."/Interface/y";
				$Screen = 'ScreenRight';
				$where = ["Id_User_Creation"=>$User,"Estado_Pago"=>"Cerrado"];
				$Content = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','HREF');
				 
				$Plugin = DCTablePluginA();
				
				$Layout = array(array("PanelA","col-md-12", $Content));
				$Page = DCLayout($Layout);
				
				$Contenido = DCPage($DCPanelTitle . $Pestanas , $Page .  $Plugin ,"panel panel-default m-b-0");

				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
						
                break;
				
				// NoHayPedido
				
            case "NoHayPedido":
			
				$layout  = new Layout();
				
				$DCPanelTitle = DCPanelTitle("Carrito de Compras","",$btn);				

				$Content = "<div style='text-align: center;'>
				<h2>EL CARRITO ESTÁ VACÍO</h2>
				<p style='font-size: 10em;color: #8BC34A;'><i class='zmdi zmdi-shopping-cart zmdi-hc-fw'></i></p>

				<p style=''> No haz seleccionado productos aún!!</p>
				</div>";
				$Form_Entrega = $Msg .  "<div style='text-align:center;padding:20px;'>".$Content ."</div>";
				
		
				$Contenido = DCPage($DCPanelTitle , $Form_Entrega  ,"panel panel-default m-b-0");

				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
						
                break;
				
            case "Entrega":
				
				$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
				
                if(empty($User)){
					

					$Settings = array();
					$Settings['Url'] = "/sadministrator/login/Id_Edu_Pedido/".$Id_Edu_Pedido;
					$Settings['Screen'] = "ScreenRight";
					$Settings['Type_Send'] = "";
					DCRedirectJS($Settings);	
					
				}
				$layout  = new Layout();
				
				$Row = Biblioteca::Pedido_Datos($Parm,$Id_Edu_Pedido);			
				$Total_Cantidad = $Row->Total_Cantidad;					
				$Total_Precio = $Row->Total_Precio;					
				$Estado_Pedido = $Row->Estado;	
				if($Estado_Pedido == "Cerrado"){
					$Subtitulo = "El pedido está cerrado, ya no es posible editarlo";
				}
				
				$DCPanelTitle = DCPanelTitle("<h4><b>Carrito de Compras</b></h4> ".$Subtitulo,"",$btn);				
				$Redirect = "/REDIRECT/articulo";
			
				$Query = "
					SELECT 
					EC.Id_Edu_Datos_Entrega  
					FROM edu_datos_entrega EC
					WHERE 
					EC.Id_User_Creation = :Id_User_Creation 
					AND EC.Predeterminado = :Predeterminado 
				";	
				$Where = ["Id_User_Creation" => $User , "Predeterminado"=>"SI" ];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Datos_Entrega = $Row->Id_Edu_Datos_Entrega;						
				
                if(!empty($Id_Edu_Datos_Entrega)){	
				
					$Reg = array(
						'Id_Edu_Datos_Entrega' => $Id_Edu_Datos_Entrega
					);
					$Where = array('Id_Edu_Pedido' => $Parm["Id_Edu_Pedido"]);
					$rg = ClassPDO::DCUpdate('edu_pedido', $Reg , $Where, $Conection);	
					
				 }
				
		
                $Grilla_Pedido = $this->Grilla_Pedido($Parm);
				
				// if(!empty($Id_Edu_Datos_Entrega)){
				    $Form_Entrega = $this->Direcciones_Entrega($Parm,$Estado_Pedido);
				// }else{
				    // $Form_Entrega = $this->Form_Entrega($Parm,$Estado_Pedido);
				// }
				$Form_Comprobante = $this->Form_Comprobante($Parm,$Id_Edu_Comprobante);				
				$Form_Pago = $this->Form_Pago($Parm);		
				
				$Query = "
					SELECT 
					    EP.Id_Edu_Datos_Entrega,
					    EP.Estado 
					FROM  edu_pedido EP
					WHERE 
					EP.Id_Edu_Pedido = :Id_Edu_Pedido
				";    
				$Where = ["Id_Edu_Pedido"=>$Parm["Id_Edu_Pedido"]];	
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Datos_Entrega = $Row->Id_Edu_Datos_Entrega;
				$Estado_Proforma = $Row->Estado;
				
				
				$Url_Btn = $UrlFile."/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"];
				if(!empty($User)){
					 
					$Btn1_Estado = "";
					$Btn1_Url = $Url_Btn."/Interface/y";
					
					$Btn2_Estado = "Active";
					$Btn2_Url = $Url_Btn."/Interface/Entrega";
					
					$Btn3_Estado = "";
					$Btn3_Url = $Url_Btn."/Interface/Comprobante";
					
					$Btn4_Estado = "";
					$Btn4_Url = $Url_Btn."/Interface/Pago";					

				}else{
					
					$Btn1_Estado = "";
					$Btn1_Url = $Url_Btn."/Interface/y";
					
					$Btn2_Estado = "Active";
					$Btn2_Url = "";
					
					$Btn3_Estado = "";
					$Btn3_Url = "";		

					$Btn4_Estado = "";
					$Btn4_Url = "";	
					
				}
				
				
				$Step_Interface = array(
				"Botones" => array( array("Pedido",$Btn1_Estado,$Btn1_Url)
				                  , array("Entrega",$Btn2_Estado,$Btn2_Url)
								  , array("Comprobante",$Btn3_Estado,$Btn3_Url)
								  , array("Pago",$Btn4_Estado,$Btn4_Url)
								  ) ,
				"Paneles" => array($Grilla_Pedido,$Form_Entrega,$Form_Comprobante,$Form_Pago)
				);

				$Step_Interface =  Step_Interface($Step_Interface);
				$Resumen =  $this->Resumen_Pedido($Parm);
				
				if($Estado_Pedido == "Cerrado"){
                     $btn = " Continuar con la vista siguiente  ]".$UrlFile."/Interface/Comprobante/Id_Edu_Pedido/".$Id_Edu_Pedido."]ScreenRight]]]btn btn-primary m-w-120}";						
		        }else{					
		             $btn = " Continuar con el paso siguiente <i class='zmdi zmdi-forward zmdi-hc-fw'></i> ]".$UrlFile."/Interface/Comprobante/Id_Edu_Pedido/".$Id_Edu_Pedido."]ScreenRight]]]btn btn-primary m-w-120}";					
                }				
				
				$Objectos_Accion = "<div id='Boton_Compras'>".DCButton($btn, 'botones1', 'sys_form_b'.$Count)."</div>";					
				$Boton_Pagar = $Objectos_Accion; 
				
				$Plugin = DCTablePluginA();
				
				$Layout = array(array("PanelA_Car","col-md-9",$Step_Interface),array("PanelB_Car","col-md-3",$Resumen . $Boton_Pagar));
				$Page = DCLayout($Layout);		
				
		
				$Contenido = DCPage($DCPanelTitle ,$Page .  $Plugin ,"panel panel-default m-b-0");

				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;
				

            case "Entrega_CreateB":
			 // DCWrite("HAHA");
			                break;
            case "Entrega_Create":
			
			    $Id_Edu_Pedido = $Parm["Id_Edu_Pedido"]; 
			    $Id_Edu_Datos_Entrega = $Parm["Id_Edu_Datos_Entrega"]; 
			    $Msg = $Parm["Msg"];
			    $Destino_Entrega = $Parm["Destino_Entrega"];
				

				// if(empty($Id_Edu_Datos_Entrega)){
					// if(!empty($Msg)){
						// DCWrite(Message("Debes seleccionar un tipo de entrega","C"));		
					// }
					
					// $Titulo = "Elegir tipo de Entrega";
                    // $Form_Entrega = $this->Form_Tipo_Entrega($Parm);
				// }else{

				
					// $Query = "
						// SELECT 
						// EC.Destino_Entrega 
						// FROM edu_datos_entrega EC
						// WHERE 
						// EC.Id_Edu_Datos_Entrega = :Id_Edu_Datos_Entrega 
					// ";	
					// $Where = ["Id_Edu_Datos_Entrega" => $Id_Edu_Datos_Entrega];
					// $Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					// $Destino_Entrega = $Row->Destino_Entrega;
					
					if($Destino_Entrega == "Entrega_Tiendab"){
						
						$Titulo = "ENTREGA EN TIENDA | Editar datos de Entrega";	
						
						$Msg =  MessageFijo("Confirma que deseas recoger en Tienda.","A");

						$btn = " Confirmar el recojo en tienda  ]".$UrlFile."/Process/ENTRY/Obj/Edu_Datos_Tipo_Entrega_Crud/Id_Edu_Pedido/".$Id_Edu_Pedido."/Destino_Entrega/".$Destino_Entrega."]ScreenRight]]]btn btn-primary m-w-120}";										
						$btn =  DCButton($btn, 'botones1', 'sys_form_b'.$Count);							

						
						$Datos_Entidad_Empresa = User::MainData($data);
						// DCVd($Datos_Entidad_Empresa);
						$Direccion_Oficina = $Datos_Entidad_Empresa->Direccion_Oficina;
						$Celular_Contacto = $Datos_Entidad_Empresa->Celular_Contacto;
						$Email_Soporte_Cliente = $Datos_Entidad_Empresa->Email_Soporte_Cliente;

						$Content = "<div style='text-align: center;'>
						<h2>DATOS DE LA OFICINA</h2>
						<p style='font-size: 10em;color: #8BC34A;'><i class='zmdi zmdi-local-store zmdi-hc-fw'></i></p>
		
						<p style=''> <b>PAÍS:</b> Perú </p>
						<p style=''> <b>DEPARTAMENTO:</b> Lima </p>
						<p style=''> <b>PROVINCIA:</b> Lima </p>
						<p style=''> <b>CALLE:</b> ".$Direccion_Oficina." </p>
						<p style=''> <b>Nro. Celular:</b> ".$Celular_Contacto." </p>
						<p style=''> <b>Email:</b> ".$Email_Soporte_Cliente." </p>
						</div>";
						$Form_Entrega = $Msg .  "<div style='text-align:center;padding:20px;'>".$Content . $btn."</div>";
						
					}else{
						$Titulo = "DELIVERY | Editar datos de Entrega";							
						$Form_Entrega = $this->Form_Entrega($Parm);							
					}

			    $Html = DCModalForm($Titulo, $Form_Entrega ,"");
				$Form = "<div id='Panel_Form_Entrega'>".$Html."</div>";
                DCWrite($Form);
                DCExit();
				
                break;
				
								
            case "Label_Car":
			
		        $Datos_Ventas = User::Datos_Venta($data);
			    DCWrite($Datos_Ventas->Total_Cantidad);		
				
                break;					
				
            case "Comprobante":
			
				$layout  = new Layout();
				$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
				$Redirect = "/REDIRECT/articulo";
				
				
				$Query = "
					SELECT 
					    EP.Id_Edu_Datos_Entrega,  EP.Id_Edu_Comprobante, EP.Id_Edu_Datos_Comprobante, EP.Estado
					FROM  edu_pedido EP
					WHERE 
					EP.Id_Edu_Pedido = :Id_Edu_Pedido
				";    
				$Where = ["Id_Edu_Pedido"=>$Parm["Id_Edu_Pedido"]];	
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Datos_Entrega = $Row->Id_Edu_Datos_Entrega;
				$Id_Edu_Comprobante = $Row->Id_Edu_Comprobante;
				$Id_Edu_Datos_Comprobante = $Row->Id_Edu_Datos_Comprobante;
				$Estado = $Row->Estado;				
				if($Estado == "Cerrado"){
					$Subtitulo = "El pedido está cerrado, ya no es posible editarlo";
				}
				
				
				$Query = "
					 SELECT 
						EDE.Id_Edu_Datos_Entrega
					 FROM edu_datos_entrega  EDE
					 WHERE
					 EDE.Id_Edu_Datos_Entrega = :Id_Edu_Datos_Entrega
				";	
				$Where = ["Id_Edu_Datos_Entrega" =>$Id_Edu_Datos_Entrega];
				$Rows = ClassPdo::DCRow($Query,$Where,$Conection);				
				$Id_Edu_Datos_Entrega_BD = $Rows->Id_Edu_Datos_Entrega;
				if(empty($Id_Edu_Datos_Entrega_BD)){
					
				    DCWrite(Message("Debes Crear y Vincular los datos de entrega para ir al siguiente paso.","A"));
					
					$Settings = array();
					$Settings['Url'] = "/sadministrator/edu_car_det/Interface/Entrega/Id_Edu_Pedido/".$Id_Edu_Pedido;
					$Settings['Screen'] = "ScreenRight";
					$Settings['Type_Send'] = "";
					DCRedirectJS($Settings);	
					
                    break;					
					
				}
				
				
				$DCPanelTitle = DCPanelTitle("<h4><b>Carrito de Compras</b></h4> ".$Subtitulo,"",$btn);	

				$Query = "
					SELECT 
					EC.Id_Edu_Datos_Comprobante 
					FROM edu_datos_comprobante EC
					WHERE 
					EC.Id_User_Creation = :Id_User_Creation 
					AND EC.Predeterminado = :Predeterminado 
				";	
				$Where = ["Id_User_Creation" => $User , "Predeterminado"=>"SI" ];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Datos_Comprobante = $Row->Id_Edu_Datos_Comprobante;	
				
				if(!empty($Id_Edu_Datos_Comprobante)){	
					$Reg = array(
						'Id_Edu_Datos_Comprobante' => $Id_Edu_Datos_Comprobante
					);
					$Where = array('Id_Edu_Pedido' => $Parm["Id_Edu_Pedido"]);
					$rg = ClassPDO::DCUpdate('edu_pedido', $Reg , $Where, $Conection);
				}		
			
			
                $Grilla_Pedido = $this->Grilla_Pedido($Parm);
				
				if(!empty($Id_Edu_Datos_Entrega)){
				    $Form_Entrega = $this->Direcciones_Entrega($Parm);
				}else{
				    $Form_Entrega = $this->Form_Entrega($Parm);
				}
				$Form_Comprobante = $this->Form_Comprobante($Parm,$Id_Edu_Comprobante,$Estado);				
				$Form_Pago = $this->Form_Pago($Parm);
				
				
				$Url_Btn = $UrlFile."/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"];
				
				if(!empty($User)){
					 
					$Btn1_Estado = "";
					$Btn1_Url = $Url_Btn."/Interface/y";
					
					$Btn2_Estado = "";
					$Btn2_Url = $Url_Btn."/Interface/Entrega";
					
					$Btn3_Estado = "Active";
					$Btn3_Url = $Url_Btn."/Interface/Comprobante";
					
					$Btn4_Estado = "";
					$Btn4_Url = $Url_Btn."/Interface/Pago";					

				}else{
					
					$Btn1_Estado = "";
					$Btn1_Url = $Url_Btn."/Interface/y";
					
					$Btn2_Estado = "";
					$Btn2_Url = "";
					
					$Btn3_Estado = "Active";
					$Btn3_Url = "";		

					$Btn4_Estado = "";
					$Btn4_Url = "";						
				}
				
				
				$Step_Interface = array(
				"Botones" => array( array("Pedido",$Btn1_Estado,$Btn1_Url)
				                  , array("Entrega",$Btn2_Estado,$Btn2_Url)
								  , array("Comprobante",$Btn3_Estado,$Btn3_Url)
								  , array("Pago",$Btn4_Estado,$Btn4_Url)
								  ) ,
				"Paneles" => array($Grilla_Pedido,$Form_Entrega,$Form_Comprobante,$Form_Pago)
				);

				$Step_Interface =  Step_Interface($Step_Interface);
				$Resumen =  $this->Resumen_Pedido($Parm);
				
				if($Estado == "Cerrado"){
                    $btn = " Continuar con la vista siguiente  ]".$UrlFile."/Interface/Pago/Id_Edu_Pedido/".$Id_Edu_Pedido."]ScreenRight]]]btn btn-primary m-w-120}";						
		        }else{					
		            $btn = " Continuar con el paso siguiente <i class='zmdi zmdi-forward zmdi-hc-fw'></i> ]".$UrlFile."/Interface/Pago/Id_Edu_Pedido/".$Id_Edu_Pedido."]ScreenRight]]]btn btn-primary m-w-120}";						
                }					
                
				$Objectos_Accion = "<div id='Boton_Compras'>".DCButton($btn, 'botones1', 'sys_form_b'.$Count)."</div>";					
				$Boton_Pagar = $Objectos_Accion; 			

				$Layout = array(array("PanelA_Car","col-md-9",$Step_Interface),array("PanelB_Car","col-md-3",$Resumen . $Boton_Pagar));
				$Page = DCLayout($Layout);				

				$Contenido = DCPage($DCPanelTitle ,$Page .  $Plugin ,"panel panel-default m-b-0");
				
				
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;

							
            case "Pago":
			
				$layout  = new Layout();
				$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
				$Redirect = "/REDIRECT/articulo";
				
				$Row = Biblioteca::Pedido_Datos($Parm,$Id_Edu_Pedido);			
				$Total_Cantidad = $Row->Total_Cantidad;					
				$Total_Precio = $Row->Total_Precio;					
				$Estado_Pedido = $Row->Estado;	
				$Id_Edu_Datos_Comprobante = $Row->Id_Edu_Datos_Comprobante;	
				if($Estado_Pedido == "Cerrado"){
					$Subtitulo = "El pedido está cerrado, ya no es posible editarlo";
				}
				
				$DCPanelTitle = DCPanelTitle("<h4><b>Carrito de Compras</b></h4> ".$Subtitulo,"",$btn);				
				$Redirect = "/REDIRECT/articulo";				
			
                $Grilla_Pedido = $this->Grilla_Pedido($Parm);
				
				$Query = "
				SELECT 
					EPD.Estado, EPD.Id_Solicitud_Pago
				FROM  solicitud_pago EPD
				WHERE 
				EPD.Id_Edu_Pedido = :Id_Edu_Pedido 
				";    
				$Where = ["Id_Edu_Pedido"=>$Parm["Id_Edu_Pedido"]];	
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Estado = $Row->Estado;
				$Id_Solicitud_Pago = $Row->Id_Solicitud_Pago;
				
				// DCVd($Id_Edu_Datos_Comprobante);
				$Query = "
					 SELECT 
						EDE.Id_Edu_Datos_Comprobante
					 FROM edu_datos_comprobante  EDE
					 WHERE
					 EDE.Id_Edu_Datos_Comprobante = :Id_Edu_Datos_Comprobante
				";	
				$Where = ["Id_Edu_Datos_Comprobante" =>$Id_Edu_Datos_Comprobante];
				$Rows = ClassPdo::DCRow($Query,$Where,$Conection);				
				$Id_Edu_Datos_Comprobante_BD = $Rows->Id_Edu_Datos_Comprobante;
				
				// DCVd($Id_Edu_Datos_Comprobante);
				if(empty($Id_Edu_Datos_Comprobante_BD)){
					
				    DCWrite(Message("Debes Crear y Vincular los datos del destinatario para ir al siguiente paso.","A"));
					
					$Settings = array();
					$Settings['Url'] = "/sadministrator/edu_car_det/Interface/Comprobante/Id_Edu_Pedido/".$Id_Edu_Pedido;
					$Settings['Screen'] = "ScreenRight";
					$Settings['Type_Send'] = "";
					DCRedirectJS($Settings);	
					
                    break;					
					
				}
				

				
				$Query = "
					SELECT 
					    EP.Id_Edu_Datos_Entrega,
					    EP.Id_Edu_Comprobante,
					    EP.Id_Edu_Datos_Comprobante,
					    EP.Total_Precio,
					    EP.Total_Cantidad,
					    EP.Estado
					FROM  edu_proforma EP
					WHERE 
					EP.Id_Edu_Pedido = :Id_Edu_Pedido
				";    
				$Where = ["Id_Edu_Pedido"=>$Parm["Id_Edu_Pedido"]];	
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Datos_Entrega = $Row->Id_Edu_Datos_Entrega;
				$Id_Edu_Comprobante = $Row->Id_Edu_Comprobante;
				$Id_Edu_Datos_Comprobante = $Row->Id_Edu_Datos_Comprobante;
				$Estado_Proforma = $Row->Estado;
				$Total_Precio =  $Row->Total_Precio.'00';
				$Total_Cantidad = $Row->Total_Cantidad;
				$Titulo = "Total de productos";
		
					
				if(!empty($Id_Edu_Datos_Entrega)){
					$Form_Entrega = $this->Direcciones_Entrega($Parm);
				}else{
					$Form_Entrega = $this->Form_Entrega($Parm);
				}
				
				$Form_Comprobante = $this->Form_Comprobante($Parm,$Id_Edu_Comprobante);	
				
				if($Estado == "Por_Confirmar"){
		
				    $Form_Pago = $this->Pago_Finalizado($Parm);		
				
				}else{
				    $Form_Pago = $this->Form_Pago($Parm);						
				}					
				
				$Url_Btn = $UrlFile."/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"];
				if(!empty($User)){
					 
					$Btn1_Estado = "";
					$Btn1_Url = $Url_Btn."/Interface/y";
					
					$Btn2_Estado = "";
					$Btn2_Url = $Url_Btn."/Interface/Entrega";
					
					$Btn3_Estado = "";
					$Btn3_Url = $Url_Btn."/Interface/Comprobante";
					
					$Btn4_Estado = "Active";
					$Btn4_Url = $Url_Btn."/Interface/Pago";					

				}else{
					
					$Btn1_Estado = "";
					$Btn1_Url = $Url_Btn."/Interface/y";
					
					$Btn2_Estado = "";
					$Btn2_Url = "";
					
					$Btn3_Estado = "";
					$Btn3_Url = "";		

					$Btn4_Estado = "Active";
					$Btn4_Url = "";	
					
				}
				
				
				$Step_Interface = array(
				"Botones" => array( array("Pedido",$Btn1_Estado,$Btn1_Url)
				                  , array("Entrega",$Btn2_Estado,$Btn2_Url)
								  , array("Comprobante",$Btn3_Estado,$Btn3_Url)
								  , array("Pago",$Btn4_Estado,$Btn4_Url)
								  ) ,
				"Paneles" => array($Grilla_Pedido,$Form_Entrega,$Form_Comprobante,$Form_Pago)
				);

				$Step_Interface =  Step_Interface($Step_Interface);
				//$Form_Entrega =  Form_Entrega($Step_Interface);
				$Resumen =  $this->Resumen_Pedido($Parm);
				

				$Layout = array(array("PanelA_Car","col-md-9", $Step_Interface),array("PanelB_Car","col-md-3",$Resumen . $Boton_Pagar));
				$Page = DCLayout($Layout);	
				
				if($Estado_Proforma == "Cerrado"){
				    $Contenido = $this->Panel_Estado($Parm);					
				}else{
				    $Contenido = DCPage($DCPanelTitle ,$Page .  $Plugin ,"panel panel-default m-b-0");
				}
				
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
				?>
				
<script>
    Culqi.publicKey = 'pk_test_R0EyGSsfLdKWMdY5';
    var producto = "";
    var precio = "";
    var descripcion = "";
   // Abre el formulario con las opciones de Culqi.settings
         producto = "Cantidad de Producto";
         descripcion = <?php echo  $Total_Cantidad;?>;
        precio =  <?php echo $Total_Precio;?>;
     Culqi.settings({
					    title: producto,
					    currency: 'PEN',
					    description: descripcion,
					    amount: precio
                    });
                Culqi.open();
                e.preventDefault();
                       // alart('hola');

                       
    function culqi() {

    if(Culqi.token) { // ¡Token creado exitosamente!
        // Get the token ID:
        var token = Culqi.token.id;
        var email = Culqi.token.email;
      //  alert('Se ha creado un token:'.token);
      var data = {producto:producto,precio:precio, token:token, email:email};
      var url = "http://comercio.local/culqi/proceso_pago.php";
      $.post(url,data,function(res){
        alert(res);
       // console.log(res);
      });

    }else{ // ¡Hubo algún problema!
        // Mostramos JSON de objeto error en consola
        console.log(Culqi.error);
        alert(Culqi.error.mensaje);
    }
};


</script>
				<?php
                break;
								
				
            case "Comprobante_Create":
			
			   
                $Form_Entrega = $this->Obj_Boleta($Parm,$Parm["Id_Edu_Comprobante"]);
				if($Parm["Id_Edu_Comprobante"] == 2 ){
				    $Tipo_Comprobante = " FACTURA ";
				}else{
				    $Tipo_Comprobante = " BOLETA ";					
				}
				
			    $Html = DCModalForm("CREAR DESTINATARIO DE LA ".$Tipo_Comprobante,$DCPanelTitle . $Form_Entrega ,"");
                DCWrite($Html);
                DCExit();
				
                break;	
				
            case "Comprobante_Edit":
			
				$btn = "Boleta]" .$UrlFile."/Process/ENTRY/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Obj/Boleta]ScreenRight]HXM]]btn btn-primary}";
				$btn .= "Factura]" .$UrlFile."/Process/ENTRY/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Obj/Factura]ScreenRight]HXM]]btn btn-primary}";
				$btn = DCButton($btn, 'botones1', 'Editar_Comprobante'.$Count);		
				$DCPanelTitle = DCPanelTitle("Elige el tipo de comprobante","",$btn);				

			    $Html = DCModalForm("Cambia el tipo de comprobante",$DCPanelTitle ,"");
                
				DCWrite($Html);
                DCExit();
				
                break;	

            case "Create":
			     
				 
	            $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Articulo_Crud/Id_Edu_Articulo/".$Id_Edu_Articulo;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Articulo/".$Id_Edu_Articulo;
				
				if(!empty($Id_Edu_Articulo)){
					
				    $Name_Interface = "Editar Programa";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Articulo_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Programa";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Articulo_Crud");							
				}
				
				$Combobox = array(
				     array("Id_Publicacion_Blog_Categoria"," SELECT Id_Publicacion_Blog_Categoria AS Id, Nombre AS Name FROM publicacion_blog_categoria ",[])
				     ,array("Id_Edu_Area_Conocimiento"," SELECT Id_Edu_Area_Conocimiento AS Id, Nombre AS Name FROM edu_area_conocimiento ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Articulo_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Articulo_Crud",$Class,$Id_Edu_Articulo,$PathImage,$Combobox,$Buttons,"Id_Edu_Articulo");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                break;
				
       			
            case "DeleteMassive":
		
		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Articulo/".$Id_Edu_Articulo."/Obj/edu_articulo]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;		

            case "Confirmar_Borrado":
		
		        $Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		        $Id_Edu_Datos_Entrega = $Parm["Id_Edu_Datos_Entrega"];
				
				$btn = "Confirmar ]" .$UrlFile ."/Process/DELETE/Obj/edu_datos_entrega/Id_Edu_Datos_Entrega/".$Id_Edu_Datos_Entrega."/Id_Edu_Pedido/".$Id_Edu_Pedido."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Cancelar ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Confirma si deseas eliminar el Destino",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;		
				
            case "Confirmar_Borrado_Comprobante":
		
		        $Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		        $Id_Edu_Datos_Comprobante = $Parm["Id_Edu_Datos_Comprobante"];
		        $Id_Edu_Comprobante = $Parm["Id_Edu_Comprobante"];
				
				$btn = "Confirmar ]" .$UrlFile ."/Process/DELETE/Obj/edu_datos_comprobante/Id_Edu_Datos_Comprobante/".$Id_Edu_Datos_Comprobante."/Id_Edu_Pedido/".$Id_Edu_Pedido."/Id_Edu_Comprobante/".$Id_Edu_Comprobante."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Cancelar ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Confirma si deseas eliminar el Destinatario",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;	

            case "Depositos_Cuenta":
		
		        $Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		        $Id_Edu_Datos_Comprobante = $Parm["Id_Edu_Datos_Comprobante"];
		        $Id_Edu_Comprobante = $Parm["Id_Edu_Comprobante"];
				
				
				$Query = "
				SELECT 
					EPD.Estado, EPD.Id_Solicitud_Pago
				 
				FROM  solicitud_pago EPD
				WHERE 
				EPD.Id_Edu_Pedido = :Id_Edu_Pedido 
				";    
				$Where = ["Id_Edu_Pedido"=>$Parm["Id_Edu_Pedido"]];	
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Estado = $Row->Estado;
				$Id_Solicitud_Pago = $Row->Id_Solicitud_Pago;
				
				if($Estado == "En_Proceso"){
					
					$Settings['Url'] = $UrlFile."/Interface/confirma_pago_deposito/Id_Edu_Pedido/".$Id_Edu_Pedido."/Id_Solicitud_Pago/".$Id_Solicitud_Pago;
					$Settings['Screen'] = "animatedModal5";
					$Settings['Type_Send'] = "HXM";
					$Settings['Type_Popup'] = "Static";
					DCRedirectJS($Settings);	
					break;
				}

				$Query = "
				    SELECT PB.Id_Edu_Cuenta_Deposito AS CodigoLink
					, PB.Numero
					, EB.Nombre AS 'Banco'
					, ETC.Nombre AS 'Tipo Cuenta'
					, ETM.Nombre AS 'Moneda'
					FROM edu_cuenta_deposito  PB
					INNER JOIN edu_tipo_cuenta ETC ON PB.Id_Edu_Tipo_Cuenta = ETC.Id_Edu_Tipo_Cuenta
					INNER JOIN edu_banco EB ON PB.Id_Edu_Banco = EB.Id_Edu_Banco
					INNER JOIN edu_tipo_moneda ETM ON PB.Id_Edu_Tipo_Moneda = ETM.Id_Edu_Tipo_Moneda
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Cuenta_Deposito';
				$Link = $UrlFile."/Interface/Create";
				$Screen = 'animatedModal5';
				$where = ["Entidad"=>$Entidad];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				
				
				$DCPanelTitle = DCPanelTitle("<h4>Para pagar con esta opción, realiza los siguientes pasos : </h4>","","");		
			   
				$Msg =  MessageFijo("
				                     <BR>1) Realiza el depósito a cualquiera de las cuentas bancarias que ves en la tabla.<BR>
				                         2) Adjunta el voucher de depósito y envialo para procesar tu pedido
				                     ","A"," PASOS: ","zmdi zmdi-long-arrow-down zmdi-hc-fw");
									 
									 
				// $DirecctionA = $UrlFile."/Interface/confirma_pago/Id_Edu_Pedido/".$Id_Edu_Pedido;
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Solicitud_Pago_Deposito_Crud/Id_Edu_Pedido/".$Id_Edu_Pedido;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Pedido/".$Id_Edu_Pedido;
				
				$Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i>Enviar Voucher";							

				
				$Combobox = array(
				     array("Id_Publicacion_Blog_Categoria"," SELECT Id_Publicacion_Blog_Categoria AS Id, Nombre AS Name FROM publicacion_blog_categoria ",[])
				     ,array("Id_Edu_Area_Conocimiento"," SELECT Id_Edu_Area_Conocimiento AS Id, Nombre AS Name FROM edu_area_conocimiento ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Panel_Solicitud_Pago_Deposito_Crud","Form","Solicitud_Pago_Deposito_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Solicitud_Pago_Deposito_Crud",$Class,$Id_Edu_Articulo,$PathImage,$Combobox,$Buttons,"Id_Edu_Articulo");
													 
				$DCPanelTitleB = DCPanelTitle("<h4> Adjunta y envianos el Voucher de Depósito </h4>","","");		
			   													 
			    $Form = "<div style='background-color:#fff;'>". $DCPanelTitle . $Msg . $Listado .$DCPanelTitleB . $Form1."</div>";								 
				$Html = DCModalForm("PAGO | Depósito en Cuenta de la Empresa", $Form ,"");
                DCWrite($Html);
				
                break;		
				
            case "Con_Tarjeta":
		
		        $Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		        $Id_Edu_Datos_Comprobante = $Parm["Id_Edu_Datos_Comprobante"];
		        $Id_Edu_Comprobante = $Parm["Id_Edu_Comprobante"];
				
				$Msg =  MessageFijo("La opción de pago con tarjeta a quedado desactiva por gestiones de mantenimiento. ","A","NOTA");
	
			    $Html = DCModalForm("PAGO CON TARJETA",$Msg ,"");
                DCWrite($Html);
				
                break;		
				
            case "confirma_pago_deposito":
		
		        $Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		        $Id_Solicitud_Pago = $Parm["Id_Solicitud_Pago"];
		        // $Id_Edu_Comprobante = $Parm["Id_Edu_Comprobante"];
				
				$DCPanelTitle = DCPanelTitle("<h4>Confirma si la imagen que subiste, es el voucher correcto </h4>","","");		
			   
				$Query = "
				SELECT 
				
					EPD.Id_Solicitud_Pago,
					EPD.Imagen_Voucher
				 
				FROM  solicitud_pago EPD
				WHERE 
				EPD.Id_Solicitud_Pago = :Id_Solicitud_Pago 
				";    
				$Where = ["Id_Solicitud_Pago"=>$Parm["Id_Solicitud_Pago"]];	
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Imagen_Voucher = $Row->Imagen_Voucher;					 
					 
				$Html_Imagen = "<div><img src='/sadministrator/simages/voucher/".$Imagen_Voucher."' width='300px'></div>";	 
					 
				// $DirecctionA = $UrlFile."/Interface/confirma_pago/Id_Edu_Pedido/".$Id_Edu_Pedido;
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Solicitud_Pago_Deposito_Crud/Estado/Confirmar/Id_Edu_Pedido/".$Id_Edu_Pedido."/Id_Solicitud_Pago/".$Id_Solicitud_Pago;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Pedido/".$Id_Edu_Pedido;
				
				$Name_Button = " Confirmar el envío del voucher";	
				$Combobox = array(
				     array("Id_Publicacion_Blog_Categoria"," SELECT Id_Publicacion_Blog_Categoria AS Id, Nombre AS Name FROM publicacion_blog_categoria ",[])
				     ,array("Id_Edu_Area_Conocimiento"," SELECT Id_Edu_Area_Conocimiento AS Id, Nombre AS Name FROM edu_area_conocimiento ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Solicitud_Pago_Deposito_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Solicitud_Pago_Deposito_Crud",$Class,$Id_Solicitud_Pago,$PathImage,$Combobox,$Buttons,"Id_Solicitud_Pago");
								

								
				$DCPanelTitleB = DCPanelTitle("<h4> Adjunta y envianos el Voucher de Depósito </h4>","","");		
			   													 
			    $Form = "<div style='background-color:#fff;'>". $DCPanelTitle . $Html_Imagen .$DCPanelTitleB . $Form1."</div>";								 
				$Html = DCModalForm("PAGO | Depósito en Cuenta de la Empresa", $Form ,"");
				
				
                DCWrite($Html);
				
                break;	
				
				
				// pago_finalizado
            case "pago_finalizado":
			
		
				$layout  = new Layout();
				$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
				$Redirect = "/REDIRECT/articulo";
				$DCPanelTitle = DCPanelTitle("Canasta de Compra","",$btn);		
				
				
                $Content = "<div style='text-align: center;'>
				            <h2>Hemos Iniciado con el proceso de verificación de tu pedido.</h2>
				            <p style='font-size: 10em;color: #8BC34A;'><i class='zmdi zmdi-directions-run zmdi-hc-fw'></i></p>
							</div>";
				
				$Layout = array(array("PanelA","col-md-12", $Content ));
				$Page = DCLayout($Layout);	
				$Contenido = DCPage($DCPanelTitle ,$Page ,"panel panel-default m-b-0");
			
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
			break;						
        }
			
	}
	
	
	public function Pago_Finalizado($Parm) {
		global $Conection,$DCTimeHour,$UrlFile,$Redirect;	
		$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		$Redirect = "/REDIRECT/articulo";
		$DCPanelTitle = DCPanelTitle("Canasta de Compra","",$btn);		
		
	    $Datos_Entidad_Empresa = User::MainData($data);
		// DCVd($Datos_Entidad_Empresa);
		$Direccion_Oficina = $Datos_Entidad_Empresa->Direccion_Oficina;
		$Celular_Contacto = $Datos_Entidad_Empresa->Celular_Contacto;
		$Email_Soporte_Cliente = $Datos_Entidad_Empresa->Email_Soporte_Cliente;
		
		$Content = "<div style='text-align: center;'>
					<h2>Hemos Iniciado con el proceso de verificación de tu pedido.</h2>
					<p style='font-size: 10em;color: #8BC34A;'><i class='zmdi zmdi-directions-run zmdi-hc-fw'></i></p>
					<p style=''> Estaremos en comunicación para ayudarte en cualquier duda que tengas!!</p>
					<h5 style=''> DATOS DE CONTACTO </h5>
					<p style=''> <b>OFICINAS:</b> ".$Direccion_Oficina." </p>
					<p style=''> <b>Nro. Celular:</b> ".$Celular_Contacto." </p>
					<p style=''> <b>Email:</b> ".$Email_Soporte_Cliente." </p>
					</div>";
		
        return  $Content;
				
	}
	
	public function Editar_Pedido($Parm,$Tipo_Tramsaccion) {
		global $Conection,$DCTimeHour,$UrlFile,$Redirect;
	
		
		if($Tipo_Tramsaccion !== "DELETE_ALL"){
			
			$Query = "
			SELECT 
			
				EPD.Cantidad,
				EPD.Precio
			 
			FROM  edu_pedido_detalle EPD
			WHERE 
			EPD.Id_Edu_Pedido_Detalle = :Id_Edu_Pedido_Detalle 
			";    
			$Where = ["Id_Edu_Pedido_Detalle"=>$Parm["Id_Edu_Pedido_Detalle"]];	
			$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
			$Cantidad_Producto = $Row->Cantidad;

				
				if($Tipo_Tramsaccion == "DELETE"){
								
					if( $Cantidad_Producto > 1 ){
						
							$Cantidad_Producto_A = $Cantidad_Producto - 1;	
				
					}else{
							$Cantidad_Producto_A = 1;							
					}
				    

					
				}else{
					$Cantidad_Producto_A = $Cantidad_Producto + 1;			
				}
	
				$Precio = $Row->Precio;
				
				$Reg = array(
					'Cantidad' => $Cantidad_Producto_A,
					'Total' => ( $Cantidad_Producto_A * $Precio )
				);
				$Where = array('Id_Edu_Pedido_Detalle' => $Parm["Id_Edu_Pedido_Detalle"]);
				$rg = ClassPDO::DCUpdate('edu_pedido_detalle', $Reg , $Where, $Conection);
				
				
	
		}else{
			
			
			$where = array('Id_Edu_Pedido_Detalle' =>$Parm["Id_Edu_Pedido_Detalle"]);
			$rg = ClassPDO::DCDelete('edu_pedido_detalle', $where, $Conection);
	        DCWrite(Message("Se eliminó el artículo de la canasta","C"));
			
		}	
			
		
		
		
		$Query = "
			 SELECT 
				 SUM(EPD.Precio) AS Precio
				 , SUM(EPD.Cantidad) AS ContReg
				 , SUM(EPD.Total) AS Total
			 FROM edu_pedido_detalle  EPD
			 WHERE
			 EPD.Id_Edu_Pedido = :Id_Edu_Pedido
		";	
		$Where = ["Id_Edu_Pedido" =>$Parm["Id_Edu_Pedido"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Precio = $Row->Precio;				
		$ContReg = $Row->ContReg;
		$Total = $Row->Total;

		$Reg = array(
			'Total_Precio' => $Total,
			'Total_Cantidad' => $ContReg
		);
		$Where = array('Id_Edu_Pedido' => $Parm["Id_Edu_Pedido"]);
		$rg = ClassPDO::DCUpdate('edu_pedido', $Reg , $Where, $Conection);
							
    }
	
	
	
	public function Eliminar_Direccion($Parm) {
		global $Conection,$DCTimeHour,$UrlFile,$Redirect;
        //////zzzzzzzzzzzzzzzzzzzzzzzz
	    $User = $_SESSION['User'];
		
		$Query = "
			 SELECT 
				COUNT(*) AS Tot_Reg
			 FROM edu_datos_entrega  EPD
			 WHERE
			 EPD.Id_User_Creation = :Id_User_Creation
		";	
		$Where = ["Id_User_Creation" =>$User];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Tot_Reg = $Row->Tot_Reg;
		
        if($Tot_Reg <= 1){
			
			DCWrite(Message("Debes mantener una Dirección de Entrega","C"));
		}else{
			
			$where = array('Id_Edu_Datos_Entrega' =>$Parm["Id_Edu_Datos_Entrega"]);
			$rg = ClassPDO::DCDelete('edu_datos_entrega', $where, $Conection);
			DCWrite(Message("Se eliminó el artículo de la canasta","C"));			
		}
		
		
		$Query = "
			 SELECT 
				COUNT(*) AS Tot_Reg, 
				EPD.Id_Edu_Datos_Entrega
			 FROM edu_datos_entrega  EPD
			 WHERE
			 EPD.Id_User_Creation = :Id_User_Creation
		";	
		$Where = ["Id_User_Creation" =>$User];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Tot_Reg = $Row->Tot_Reg;
		
		if($Tot_Reg == 1){
			$Parm["Id_Edu_Datos_Entrega"] = $Row->Id_Edu_Datos_Entrega;
            $this->Vincula_Proforma($Parm);			
		}

				
    }
	
	
	
	public function Eliminar_Datos_Comprobante($Parm) {
		global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
	    $User = $_SESSION['User'];
		
		$Query = "
			 SELECT 
				COUNT(*) AS Tot_Reg
			 FROM edu_datos_comprobante  EPD
			 WHERE
			 EPD.Id_User_Creation = :Id_User_Creation
		";	
		$Where = ["Id_User_Creation" =>$User];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Tot_Reg = $Row->Tot_Reg;
		
        if($Tot_Reg <= 1){
			
			DCWrite(Message("Debes mantener un destinatario para el comprobante","C"));
		
		}else{
			
			$where = array('Id_Edu_Datos_Comprobante' =>$Parm["Id_Edu_Datos_Comprobante"]);
			$rg = ClassPDO::DCDelete('edu_datos_comprobante', $where, $Conection);
			DCWrite(Message("Se eliminó el destinatario del comprobante","C"));			
		}		
	
		$Query = "
			 SELECT 
				COUNT(*) AS Tot_Reg, 
				EPD.Id_Edu_Datos_Comprobante
			 FROM edu_datos_comprobante  EPD
			 WHERE
			 EPD.Id_User_Creation = :Id_User_Creation
		";	
		$Where = ["Id_User_Creation" =>$User];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Tot_Reg = $Row->Tot_Reg;
		
		if($Tot_Reg == 1){
			$Parm["Id_Edu_Datos_Comprobante"] = $Row->Id_Edu_Datos_Comprobante;
            $this->Vincula_Proforma_Comprobante($Parm);			
			
		}	

							
    }
		
	
	
	
	public function Resumen_Pedido($Parm) {
        
		global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$Query = "
			 SELECT 
				 EPD.Total_Precio, EPD.Total_Cantidad
			 FROM edu_pedido  EPD
			 WHERE
			 EPD.Id_Edu_Pedido = :Id_Edu_Pedido
		";	
		$Where = ["Id_Edu_Pedido" =>$Parm["Id_Edu_Pedido"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$SubTotal = $Row->Total_Precio;				
		// $SubTotal = $Row->Total_Cantidad;
		$Envio = 0;
		$Descuento = 0;
		
							
        
		$Html = '
            <div class="panel panel-default">
              <div class="panel-heading">
               
                <h3 class="panel-title">Resumen del Pedido</h3>
                <div class="panel-subtitle"></div>
              </div>
             
              <table class="table">
                <tbody>
                  <tr>
                    <td>
                      <i class="zmdi zmdi-circle text-primary"></i>
                    </td>
                    <td>Sub Total</td>
                    <td>'.$SubTotal.'</td>
              
                  </tr>
				  
                  <tr>
                    <td>
                      <i class="zmdi zmdi-circle text-warning"></i>
                    </td>
                    <td>Envío</td>
                    <td>'.$Envio.'</td>
                  </tr>

                  <tr>
                    <td>
                      <i class="zmdi zmdi-circle text-warning"></i>
                    </td>
                    <td>Descuento</td>
                    <td>'.$Descuento.'</td>
                  </tr>
				  
                  <tr>
                    <td>
                      <i class="zmdi zmdi-circle text-danger"></i>
                    </td>
                    <td><b>Total</b></td>
                    <td>'.($SubTotal + $Envio + $Descuento).'</td>
                 
                  </tr>
                </tbody>
              </table>
            </div>			
			
			';	
		return $Html;
    }

	
	public function Direcciones_Entrega($Parm,$Estado){
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		$User = $_SESSION['User'];
		
		
		if($Estado !="Cerrado"){				
	
			$DCPanelTitle = DCPanelTitle("ENTREGA | Destinos de Entrega ","Crea y administra las direcciones de entrega ".$Tot_Vistas."",$btn);
	    }
		
		$Query = "
			 SELECT 
			    EDE.Nombre,
			    EDE.Id_Edu_Datos_Entrega,
			    EDE.Direccion,
			    EDE.Predeterminado,
			    EDE.Telefono,
			    EDE.Nro_Lote,
			    EDE.Departamento_Interior,
			    EDE.Apellidos
			 FROM edu_datos_entrega  EDE
			 WHERE
			 EDE.Id_User_Creation = :Id_User_Creation
		";	
		$Where = ["Id_User_Creation" =>$User];
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		$Card = "";
		
		$Html .= $DCPanelTitle;
		$Html .= '
	        <div class="row" style="padding: 20px;">
		';
		$Count = 0;
		foreach($Rows AS $Field){
			$Count += 1;
			
			$Style = 'style="background-color:#fff !important; min-height: 250px;" ';		
			$Style_text = 'style="color:#8c8a8a;" ';	
			$Style_Text_Predeterminado = 'style="color:#8c8a8a;padding: 10px 0px 10px 0px;" ';
			
		    if($Field->Predeterminado == "SI" ){

			    // $Style_text = ' ';	
			    $Predeterminado = ' El pedido será enviado a esta dirección ';	

			}else{
	
			    $Predeterminado = ' ';	
	            // $Style_text = 'style="color:#8c8a8a;" ';					
	            $Style_Text_Predeterminado = '';					
			}
			
			if($Estado !="Cerrado"){
				$btn = " Editar ]" .$UrlFile."/Interface/Entrega_Create/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Id_Edu_Datos_Entrega/".$Field->Id_Edu_Datos_Entrega."]animatedModal5]HXM]]btn btn-default m-w-120}";
				$btn .= " <i class='zmdi zmdi-delete'></i> ]" .$UrlFile."/Interface/Confirmar_Borrado/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Id_Edu_Datos_Entrega/".$Field->Id_Edu_Datos_Entrega."]animatedModal5]HXM]]btn btn-default m-w-120}";
				$BtnCard_B = DCButton($btn, 'botones1', 'Form-Card'.$Count); 				
			}
         
		   
			$Html .= '
				 
            <div class="col-md-4 col-sm-5"  >
				<div class="widget widget-tile-2 m-b-30" '.$Style.'>
				  <div class="wt-content p-a-20 p-b-50">
					<div class="wt-title" '.$Style_text.'>'.$Field->Nombre.' '.$Field->Apellidos.'
					  <span class="t-caret text-success">
						<i class="zmdi zmdi-pencil-up"></i>
					  </span>
					</div>
					<div class="wt-number" '.$Style_text.'>'.$Field->Telefono.'</div>
					<div class="wt-text" '.$Style_text.'>'.$Field->Direccion .' '. $Field->Nro_Lote .' ' . $Field->Departamento_Interior.'</div>
					<p class="wt-text" '.$Style_Text_Predeterminado.'>'.$Predeterminado.'</p>
					   '.$BtnCard_B .'		
				  </div>
			   
				</div>				 
            </div>	
			';			
        }
		
		if($Estado !="Cerrado"){		
			///XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
			$btn = "<i class='zmdi zmdi-local-shipping zmdi-hc-fw' style='font-size:3em;'></i><br><i style='font-size:1em;'>Entrega por Delivery</i>]" .$UrlFile."/Interface/Entrega_Create/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Destino_Entrega/Delivery]animatedModal5]HXM]]}";
			$btn .= "<i class='zmdi zmdi-local-store zmdi-hc-fw' style='font-size:3em;'></i><br><i style='font-size:1em;'>Entrega en Oficina</i>]" .$UrlFile."/Interface/Entrega_Create/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Destino_Entrega/Entrega_Tiendab]animatedModal5]HXM]]}";
			$btn = DCButton($btn, 'botones1', 'sys_form_crear'.$Count);			
		}
		$Html .= '
			 
			<div class="col-md-6"  >
				  <div class="wt-content p-a-20 p-b-50">
					'.$btn.'
				  </div>		
			</div>	
			
		';				

		
		
		
		
		$Html .= '
		    </div>
		';		
		return $Html; 
	}		
	
	
	public function Comprobantes_Pago($Parm,$Id_Edu_Comprobante,$Estado){
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$User = $_SESSION['User'];

		$Query = "
			 SELECT 
			    EDE.Razon_Social,
			    EDE.Ruc,
			    EDE.Direccion,
			    EDE.Predeterminado,
			    EDE.Id_Edu_Datos_Comprobante,
			    EDE.Id_Edu_Comprobante
			 FROM edu_datos_comprobante  EDE
			 WHERE
			 EDE.Id_User_Creation = :Id_User_Creation
		";	
		$Where = ["Id_User_Creation" =>$User];
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		$Card = "";
		
		$Html .= $DCPanelTitle;
		$Html .= '
		     <div class="row" style="padding: 20px;">
		';
		$Count = 0;
		foreach($Rows AS $Field){
			$Count += 1;

		    if($Field->Predeterminado == "SI" ){
			    $Style = 'style="background-color:#34a853" ';	
			    $Predeterminado = '<b style="color:#0d8aec;" > Estos datos serán tomados para el destinatario del comprobante </b> ';	
			}else{
				$Style = 'style="background-color:#939b96 !important" ';	
			    $Predeterminado = ' ';				
			}
			
		    if($Field->Id_Edu_Comprobante == 2 ){
			    $Tipo_Comprobante = ' DESTINATARIO DE LA FACTURA ';	
			}else{	
			    $Tipo_Comprobante = ' DESTINATARIO DE LA BOLETA ';				
			}
						
			

			if($Estado != "Cerrado"){
				$btn = " <i class='zmdi zmdi-delete'></i>  ]" .$UrlFile."/Interface/Confirmar_Borrado_Comprobante/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Id_Edu_Datos_Comprobante/".$Field->Id_Edu_Datos_Comprobante."/Id_Edu_Comprobante/".$Field->Id_Edu_Comprobante."]animatedModal5]HXM]]btn btn-default m-w-120}";
				$btn .= "Editar ]" .$UrlFile."/Interface/Comprobante_Create/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Id_Edu_Datos_Comprobante/".$Field->Id_Edu_Datos_Comprobante."/Id_Edu_Comprobante/".$Field->Id_Edu_Comprobante."]animatedModal5]HXM]]btn btn-default m-w-120}";
				$BtnCard = DCButton($btn, 'botones1', 'Form-Comp'.$Count);		
				
			}  
		   
			// 939b96
			$Html .= '
				 
            <div class="col-md-4 col-sm-5"  >
		
				<div class="panel panel-default">
				  <div class="panel-heading">
					<div class="panel-tools">
				
					</div>
					<h3 class="panel-title">'.$Field->Razon_Social.'</h3>
					<div class="panel-subtitle">'.$Field->Ruc.'</div>
				  </div>
				  <div class="panel-body">
					'.$Field->Direccion.' <br>
					'.$Tipo_Comprobante.' <br>
					'.$Predeterminado.' 
				 </div>
				  <div class="panel-footer text-right">
					  '.$BtnCard.'
				  </div>
				</div>
			
            </div>				 
				 
			';			
        }
		
		if($Estado != "Cerrado"){		
			$btn = "<i style='font-size:3em;' class='zmdi zmdi-city zmdi-hc-fw'></i> <br> <i style='font-size:1em;' >Crea Destinatario <BR>para la Factura</i> ]" .$UrlFile."/Interface/Comprobante_Create/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Id_Edu_Comprobante/2]animatedModal5]HXM]]}";
			$btn .= "<i style='font-size:3em;' class='zmdi zmdi-account-box zmdi-hc-fw'></i> <br> <i style='font-size:1em;' >Crea Destinatario <BR>para la Boleta</i> ]" .$UrlFile."/Interface/Comprobante_Create/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Id_Edu_Comprobante/1]animatedModal5]HXM]]}";
			$btn = DCButton($btn, 'botones1', 'panel_btn'.$Count);		
		}
		
		$Html .= '
		<div class="col-md-6"  >
				'.$btn.'
		</div>				 
		';			
		

		$Html .= '
		    </div>
		';		
		
	

		
		return $Html; 
	}		
	
	public function Grilla_Pedido($Parm,$Estado){
	    global $Conection,$DCTimeHour,$UrlFile,$UrlFile_edu_articulo_det;

		$Query = "
			 SELECT 
			    EPD.Id_Edu_Pedido_Detalle, 
			    EPD.Id_Edu_Pedido,
			    AR.Nombre,
			    EPD.Cantidad,
			    AR.Imagen,
			    EA.Id_Edu_Almacen,
			    EA.Id_Edu_Almacen,
			    EPD.Precio
			 FROM edu_pedido_detalle  EPD
			 INNER JOIN edu_almacen EA ON EPD.Id_Edu_Almacen = EA.Id_Edu_Almacen
			 INNER JOIN edu_articulo AR ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
			 WHERE
			 EPD.Id_Edu_Pedido = :Id_Edu_Pedido
		";	
		$Where = ["Id_Edu_Pedido" =>$Parm["Id_Edu_Pedido"]];
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		$Card = "";
		
		$Html .= '
                <div class="cart">
                  <table class="table table-hover">
                    <tbody>
		';
		$Count = 0;
		foreach($Rows AS $Field){
			
			$Parm["Id_Edu_Pedido_Detalle"] = $Field->Id_Edu_Pedido_Detalle;
			$Row_Detalle_Item = Biblioteca::Detalle_Item($Parm);
			$Color = $Row_Detalle_Item->Color;
			$Talla = $Row_Detalle_Item->Talla;
			$Id_Edu_Pedido = $Row_Detalle_Item->Id_Edu_Pedido;
			
			$Query = "
			SELECT 
			EFA.Nombre AS Foto
			FROM edu_foto_articulo EFA 
			WHERE 
			EFA.Id_Edu_Almacen = :Id_Edu_Almacen
			AND EFA.Principal = :Principal
			AND EFA.Id_Edu_Color_Articulo = :Id_Edu_Color_Articulo
			";    
			$Where = ["Id_Edu_Almacen" => $Field->Id_Edu_Almacen,"Principal"=>"SI","Id_Edu_Color_Articulo"=>$Row_Detalle_Item->Id_Edu_Color_Articulo];
			$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
			$Foto = $Row->Foto;			
		    
			$Count += 1;
			
			if($Estado != "Cerrado"){
			
					$CallUrlA = $UrlFile."/Process/DELETE/Obj/Proforma/Id_Edu_Almacen/".$Field->Id_Edu_Almacen."/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Id_Edu_Pedido_Detalle/".$Field->Id_Edu_Pedido_Detalle."|ScreenRight|HXM";
					$CallUrlB = $UrlFile."/Interface/Label_Car/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."|Label-Cart|HXM";
					$btn = "<i class='zmdi zmdi-minus'></i> ]".$CallUrlA."{".$CallUrlB."]]MULTICALL]]btn-grilla}";			
					$Objectos_Btn_Suma = DCButton($btn, 'botones1', 'sys_form_pedido_mas'.$Count);	
					
					$CallUrlA = $UrlFile."/Process/ENTRY/Obj/Proforma/Id_Edu_Almacen/".$Field->Id_Edu_Almacen."/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Id_Edu_Pedido_Detalle/".$Field->Id_Edu_Pedido_Detalle."|ScreenRight|HXM";
					$CallUrlB = $UrlFile."/Interface/Label_Car/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."|Label-Cart|HXM";
					$btn = "<i class='zmdi zmdi-plus'></i> ]".$CallUrlA."{".$CallUrlB."]]MULTICALL]]btn-grilla}";			
					$Objectos_Btn_Menos = DCButton($btn, 'botones1', 'sys_form_pedido_menos'.$Count);				
							
					
					$CallUrlA = $UrlFile."/Process/DELETE/Obj/Proforma_Item/Id_Edu_Almacen/".$Field->Id_Edu_Almacen."/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Id_Edu_Pedido_Detalle/".$Field->Id_Edu_Pedido_Detalle."|ScreenRight|HXM";
					$CallUrlB = $UrlFile."/Interface/Label_Car/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."|Label-Cart|HXM";
					$btn = "<i class='zmdi zmdi-delete'></i> ]".$CallUrlA."{".$CallUrlB."]]MULTICALL]]btn-grilla}";			
					$Objectos_Btn_Borrar = DCButton($btn, 'botones1', 'sys_form_pedido_borrar'.$Count);	
		
		    }
		
		
			$btn = "".$Field->Nombre."]".$UrlFile_edu_articulo_det."/Interface/begin/Key/".$Field->Id_Edu_Almacen."]ScreenRight]]]btn-link}";			
			$Objectos_Btn_Producto_Det = DCButton($btn, 'botones1', 'sys_form_pedido_producto_det'.$Count);									


		$Html .= '
	
			  <tr>
				<td>
				<div class="cart">
				    <a class="c-image" style="background-image: url(/sadministrator/simages/articulo/'.$Foto.');" ></a>
				</div>
				  
				</td>
				<td class="c-link" >
				
				  
				 '.$Objectos_Btn_Producto_Det.'
				  <p> Color: '.$Color.', Talla: '.$Talla.'</p>
				</td> 
				<td style="padding:0px 10px 0px 0px;">
				  '.$Field->Precio.'
				</td>

				<td>
				 <div style="float:left;width:100%;" class="Cart_Panel_Control">
				 
				 <div style="float:left" >'.$Objectos_Btn_Suma.'</div>	
				 <div style="float:left" ><span class="m-x-5">'.$Field->Cantidad.'</span></div>	
				 <div style="float:left" >'.$Objectos_Btn_Menos.'</div>	
				 
				 </div>				  
				</td>
				
				<td>
				    <div class="Cart_Panel_Control_D">				
				  '.$Objectos_Btn_Borrar.'
				    </div>						  
				</td>
			  </tr>
			';
			
        }	
		
		$Html .= '
                    </tbody>
                  </table>
    
                </div>
		';
		return $Html;
	}

	public function Form_Tipo_Entrega($Parm) {
       	global $Conection, $DCTimeHour,$NameTable,$UrlFile;
        
		$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		// $Id_Edu_Datos_Entrega = $Parm["Id_Edu_Datos_Entrega"];
		
		$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Datos_Tipo_Entrega_Crud/Id_Edu_Pedido/".$Id_Edu_Pedido."";
		$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Articulo/".$Id_Edu_Articulo;
		
		if(!empty($Id_Edu_Datos_Entrega)){
						    	
			$Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Editar Datos";
			// $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Datos_Entrega_Crud","btn btn-default m-w-120");					
		
		}else{				
			$Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Continuar ";	
			// $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Articulo_Crud");							
		}
		
		$Combobox = array(
			      array("Destino_Entrega","Entrega_Tiendab,Entrega en Tienda}Delivery,Delivery}",[])
		);
		
		$PathImage = array(
			 array("Imagen","/sadministrator/simages/articulo")
		);
		
		$Buttons = array(
			 array($Name_Button,$DirecctionA,"Panel_Form_Entrega","Form","Edu_Datos_Tipo_Entrega_Crud"),$ButtonAdicional
		);	
		$Form1 = BFormVertical("Edu_Datos_Tipo_Entrega_Crud",$Class,$Id_Edu_Datos_Entrega,$PathImage,$Combobox,$Buttons,"Id_Edu_Datos_Entrega");
				
        return $Form1;	
        
    }		
	
	public function Form_Entrega($Parm) {
       	global $Conection, $DCTimeHour,$NameTable,$UrlFile;
        
		$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		$Id_Edu_Datos_Entrega = $Parm["Id_Edu_Datos_Entrega"];
		$Destino_Entrega = $Parm["Destino_Entrega"];
		
		if(!empty($Destino_Entrega)){
			$Url_Destino_Entrega = "/Destino_Entrega/".$Destino_Entrega."";
		}		
		$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Datos_Entrega_Crud/Id_Edu_Pedido/".$Id_Edu_Pedido.$Url_Destino_Entrega."/Id_Edu_Datos_Entrega/".$Id_Edu_Datos_Entrega;
		$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Articulo/".$Id_Edu_Articulo;
		
		if(!empty($Id_Edu_Datos_Entrega)){
						    	
			$Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Guardar Datos";
			// $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Datos_Entrega_Crud","btn btn-default m-w-120");					
		
		}else{				
			$Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Guardar Datos";	
			// $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Articulo_Crud");							
		}
		
		$Combobox = array(
			 array("Id_Edu_Area_Conocimiento"," SELECT Id_Edu_Area_Conocimiento AS Id, Nombre AS Name FROM edu_area_conocimiento ",[])
			 ,array("Id_Edu_Tipo_Moneda"," SELECT Id_Edu_Tipo_Moneda AS Id, Nombre AS Name FROM edu_tipo_moneda ",[])
		);
		
		$PathImage = array(
			 array("Imagen","/sadministrator/simages/articulo")
		);
		
		$Buttons = array(
			 array($Name_Button,$DirecctionA,"Panel_Edu_Datos_Entrega_Crud","Form","Edu_Datos_Entrega_Crud"),$ButtonAdicional
		);	
		$Form1 = BFormVertical("Edu_Datos_Entrega_Crud",$Class,$Id_Edu_Datos_Entrega,$PathImage,$Combobox,$Buttons,"Id_Edu_Datos_Entrega");
				
        return $Form1;		
	}
	
	
	
	public function Form_Comprobante($Parm,$Id_Edu_Comprobante,$Estado) {
       	global $Conection, $DCTimeHour,$NameTable,$UrlFile;
        
		$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		$Id_Edu_Datos_Entrega = $Parm["Id_Edu_Datos_Entrega"];
		
		if($Id_Edu_Comprobante == 1 || $Id_Edu_Comprobante == 2){
			
			$Query = "
				SELECT 
				EC.Nombre 
				FROM edu_comprobante EC
				WHERE 
				EC.Id_Edu_Comprobante = :Id_Edu_Comprobante  
			";	
			$Where = ["Id_Edu_Comprobante" => $Id_Edu_Comprobante];
			$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
			$NombreComprobante = $Row->Nombre;	
			
			$Query = "
				SELECT 
				EP.Id_Edu_Datos_Comprobante  
				FROM edu_pedido EP
				WHERE 
				EP.Id_Edu_Pedido = :Id_Edu_Pedido  
			";	
			$Where = ["Id_Edu_Pedido" => $Id_Edu_Pedido];
			$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
			$Id_Edu_Datos_Comprobante = $Row->Id_Edu_Datos_Comprobante;			
					
			$DCPanelTitle = DCPanelTitle(" COMPROBANTE | Listado de Destinatarios "," Elige y crea el destinatario para el comprobante ",$btn);	
			$Form = $this->Comprobantes_Pago($Parm,$Id_Edu_Comprobante,$Estado);				

			
		}else{
			
			$btn = "Boleta]" .$UrlFile."/Process/ENTRY/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Obj/Boleta]ScreenRight]]]btn btn-primary}";
			$btn .= "Factura]" .$UrlFile."/Process/ENTRY/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Obj/Factura]ScreenRight]]]btn btn-primary}";
			$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);		
			$DCPanelTitle = DCPanelTitle("Elige el tipo de comprobante","",$btn);
			
		}
			
		
        return $DCPanelTitle.$Form;		
	}	
	
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Articulo = $Settings["Id_Edu_Articulo"];
		
		$where = array('Id_Edu_Articulo' =>$Id_Edu_Articulo);
		$rg = ClassPDO::DCDelete('edu_articulo', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}	
	
	
	public function Form_Pago($Parm){
       	global $Conection, $DCTimeHour,$NameTable,$UrlFile;
       
		$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		
		$DCPanelTitle = DCPanelTitle(" PAGO | Métodos de pago ","Elige el metodo de pago",$btn);

		$btn = " <i style='font-size:2em;' class='zmdi zmdi-balance zmdi-hc-fw'></i> <br> <i style='font-size:1em;' >Depósito en Cuenta</i> ]" .$UrlFile."/Interface/Depositos_Cuenta/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Obj/Boleta]animatedModal5]HXM]]}";
		$btn .= " <i style='font-size:2em;' class='zmdi zmdi-card zmdi-hc-fw'></i>  <br>  <i style='font-size:1em;' >Con Tarjeta</i> ]" .$UrlFile."/Interface/Con_Tarjeta/Id_Edu_Pedido/".$Parm["Id_Edu_Pedido"]."/Obj/Factura]animatedModal5]HXM]]}";
		$Botones = DCButton($btn, 'botones1', 'Botonera_Tipos_Pago'.$Count);		
		
		// $Botones = DCPanelTitle("Elige el tipo de comprobante","",$btn);
		
		// $Obj = "Solicitud_Pago_Crud";			
		// $Id_Obj = "Id_Solicitud_Pago";

		// $DirecctionA = $UrlFile."/Process/ENTRY/Obj/".$Obj."/Id_Edu_Pedido/".$Id_Edu_Pedido;
		// $DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Pedido/".$Id_Edu_Pedido;
		
		// if(!empty($Id_Edu_Datos_Comprobante)){
			// $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Editar Datos";
		// }else{				
			// $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Enviar Datos";	
		// }
		
		// $Combobox = array(
			 // array("Id_Edu_Area_Conocimiento"," SELECT Id_Edu_Area_Conocimiento AS Id, Nombre AS Name FROM edu_area_conocimiento ",[])
			 // ,array("Id_Edu_Tipo_Moneda"," SELECT Id_Edu_Tipo_Moneda AS Id, Nombre AS Name FROM edu_tipo_moneda ",[])
		// );
		
		// $PathImage = array(
			 // array("Imagen","/sadministrator/simages/articulo")
		// );
		
		// $Buttons = array(
			 // array($Name_Button,$DirecctionA,"ScreenRight","Form",$Obj),$ButtonAdicional
		// );	
		// $Form1 = BFormVertical($Obj,$Class,$Id_Edu_Datos_Comprobante,$PathImage,$Combobox,$Buttons,$Id_Obj);
		// $Name_Form = '<h3 class="m-t-0 m-b-30">Realizar Pago</h3>';	
		$Botones = '<div style="padding:30px 20px">'.$Botones.'</div>';	


		
        return $DCPanelTitle . $Botones;			
		
    }
	
	public function Obj_Boleta($Parm,$Id_Edu_Comprobante) {
       	global $Conection, $DCTimeHour,$NameTable,$UrlFile;
       
		$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		$Id_Edu_Datos_Comprobante = $Parm["Id_Edu_Datos_Comprobante"];

		if($Id_Edu_Comprobante == 1){
		    $Obj = "Edu_Datos_Comprobante_Crud";			
		}else{
		    $Obj = "Edu_Datos_Comprobante_Crud_Factura";			
		}

		
		$Id_Obj = "Id_Edu_Datos_Comprobante";

		$DirecctionA = $UrlFile."/Process/ENTRY/Obj/".$Obj."/Id_Edu_Pedido/".$Id_Edu_Pedido."/Id_Edu_Comprobante/".$Id_Edu_Comprobante."/".$Id_Obj."/".$Id_Edu_Datos_Comprobante;
		$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Articulo/".$Id_Edu_Articulo;
		
		if(!empty($Id_Edu_Datos_Comprobante)){
						    	
			$Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Editar Datos";
			// $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Datos_Entrega_Crud","btn btn-default m-w-120");					
		
		}else{				
			$Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Guardar Datos";	
			// $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Articulo_Crud");							
		}
		
		$Combobox = array(
			 array("Id_Edu_Area_Conocimiento"," SELECT Id_Edu_Area_Conocimiento AS Id, Nombre AS Name FROM edu_area_conocimiento ",[])
			 ,array("Id_Edu_Tipo_Moneda"," SELECT Id_Edu_Tipo_Moneda AS Id, Nombre AS Name FROM edu_tipo_moneda ",[])
		);
		
		$PathImage = array(
			 array("Imagen","/sadministrator/simages/articulo")
		);
		
		$Buttons = array(
			 array($Name_Button,$DirecctionA,"Panel_".$Obj,"Form",$Obj),$ButtonAdicional
		);	
		$Form1 = BFormVertical($Obj,$Class,$Id_Edu_Datos_Comprobante,$PathImage,$Combobox,$Buttons,$Id_Obj);
				
        return $Form1;	
		
    }


	
	public function Vincula_Proforma_Comprobante($Parm) {
       	global $Conection, $DCTimeHour,$NameTable;
		
        $User = $_SESSION['User'];
		$Id_Edu_Datos_Comprobante = $Parm["Id_Edu_Datos_Comprobante"];
		$Id_Edu_Comprobante = $Parm["Id_Edu_Comprobante"];
		
		// $Query = "
			// SELECT 
			// EC.Predeterminado  
			// FROM edu_datos_comprobante EC
			// WHERE 
			// EC.Id_User_Creation = :Id_User_Creation 
			// AND EC.Predeterminado = :Predeterminado 
			// AND EC.Id_Edu_Comprobante = :Id_Edu_Comprobante 
		// ";	
		// $Where = ["Id_User_Creation" => $User , "Predeterminado"=>"SI", "Id_Edu_Comprobante" => $Id_Edu_Comprobante];
		// $Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		// $Predeterminado = $Row->Predeterminado;	

					
		// if(!empty(DCPost("Predeterminado")) || empty($Predeterminado)){

			$Reg = array(
				'Predeterminado' => ''
			);
			$Where = array('Id_User_Creation' => $User);
			$rg = ClassPDO::DCUpdate('edu_datos_comprobante', $Reg , $Where, $Conection);		
			
			$Reg = array(
				'Predeterminado' => 'SI'
			);
			$Where = array('Id_Edu_Datos_Comprobante' => $Id_Edu_Datos_Comprobante);
			$rg = ClassPDO::DCUpdate('edu_datos_comprobante', $Reg , $Where, $Conection);
					  
        // }
		  // DCVd($Id_Edu_Datos_Comprobante);
		$Reg = array(
			'Id_Edu_Datos_Comprobante' => $Id_Edu_Datos_Comprobante
		);
		        // DCVd($Reg);
		$Where = array('Id_Edu_Pedido' => $Parm["Id_Edu_Pedido"]);
		
		$rg = ClassPDO::DCUpdate('edu_pedido', $Reg , $Where, $Conection);
		// DCWrite(Message("Process executed correctly","C"));		
		
    }
   	
	public function Procea_Pago($Parm) {
       	global $Conection, $DCTimeHour,$NameTable;
		
		$Id_Edu_Pedido = $Parm["Id_Edu_Pedido"];
		
		///Genera pedido
		// $Query = " INSERT INTO edu_pedido 
		                  // (Id_Edu_Cliente,Id_Edu_Datos_Comprobante,Id_Edu_Datos_Entrega,Total_Cantidad,Date_Time_Emision,Id_Edu_Tipo_Pago,Id_Edu_Tipo_Moneda,Id_Edu_Comprobante,Total_Precio,Estado,Sesion_Id,Entity,Date_Time_Creation,Date_Time_Update,Id_User_Creation,Id_User_Update)
		            // SELECT Id_Edu_Cliente,Id_Edu_Datos_Comprobante,Id_Edu_Datos_Entrega,Total_Cantidad,Date_Time_Emision,Id_Edu_Tipo_Pago,Id_Edu_Tipo_Moneda,Id_Edu_Comprobante,Total_Precio,Estado,Sesion_Id,Entity,Date_Time_Creation,Date_Time_Update,Id_User_Creation,Id_User_Update FROM edu_proforma WHERE Id_Edu_Proforma = ".$Parm["Id_Edu_Proforma"]."  ";
		
		// $Reg = ClassPDO::DCExecute($Query,$Conection);
     	// $Id_Edu_Pedido = $Conection->lastInsertId();		
		

		// /Genera detalle del pedido
		// $Query = " INSERT INTO edu_pedido_detalle (Id_Edu_Pedido,Id_Edu_Almacen,Cantidad,Precio,Total,Entity,Date_Time_Creation,Date_Time_Update,Id_User_Creation,Id_User_Update)
		            // SELECT '".$Id_Edu_Pedido."',Id_Edu_Almacen,Cantidad,Precio,Total,Entity,Date_Time_Creation,Date_Time_Update,Id_User_Creation,Id_User_Update FROM edu_proforma_detalle WHERE Id_Edu_Proforma = ".$Parm["Id_Edu_Proforma"]."  ";
		
		// $Reg = ClassPDO::DCExecute($Query,$Conection);
		
		
		
		///Genera voucher
		$Query = " INSERT INTO edu_voucher 
		                  (Id_Edu_Cliente,Id_Edu_Datos_Comprobante,Id_Edu_Datos_Entrega,Total_Cantidad,Date_Time_Emision,Id_Edu_Tipo_Pago,Id_Edu_Tipo_Moneda,Id_Edu_Comprobante,Total_Precio,Estado,Sesion_Id,Entity,Date_Time_Creation,Date_Time_Update,Id_User_Creation,Id_User_Update)
		            SELECT Id_Edu_Cliente,Id_Edu_Datos_Comprobante,Id_Edu_Datos_Entrega,Total_Cantidad,Date_Time_Emision,Id_Edu_Tipo_Pago,Id_Edu_Tipo_Moneda,Id_Edu_Comprobante,Total_Precio,Estado,Sesion_Id,Entity,Date_Time_Creation,Date_Time_Update,Id_User_Creation,Id_User_Update FROM edu_pedido WHERE Id_Edu_Pedido = ".$Parm["Id_Edu_Pedido"]."  ";
		
		$Reg = ClassPDO::DCExecute($Query,$Conection);
     	$Id_Edu_Voucher = $Conection->lastInsertId();		
		

		// /Genera detalle del voucher
		$Query = " INSERT INTO edu_voucher_detalle (Id_Edu_Voucher,Id_Edu_Almacen,Cantidad,Precio,Total,Entity,Date_Time_Creation,Date_Time_Update,Id_User_Creation,Id_User_Update)
		            SELECT '".$Id_Edu_Voucher."',Id_Edu_Almacen,Cantidad,Precio,Total,Entity,Date_Time_Creation,Date_Time_Update,Id_User_Creation,Id_User_Update FROM edu_pedido_detalle WHERE Id_Edu_Pedido = ".$Parm["Id_Edu_Pedido"]."  ";
		
		$Reg = ClassPDO::DCExecute($Query,$Conection);		
		
		
		///Genera voucher
		$Query = " INSERT INTO edu_registro_ventas 
		                  (Id_Edu_Cliente,Id_Edu_Datos_Comprobante,Id_Edu_Datos_Entrega,Total_Cantidad,Date_Time_Emision,Id_Edu_Tipo_Pago,Id_Edu_Tipo_Moneda,Id_Edu_Comprobante,Total_Precio,Estado,Sesion_Id,Entity,Date_Time_Creation,Date_Time_Update,Id_User_Creation,Id_User_Update)
		            SELECT Id_Edu_Cliente,Id_Edu_Datos_Comprobante,Id_Edu_Datos_Entrega,Total_Cantidad,Date_Time_Emision,Id_Edu_Tipo_Pago,Id_Edu_Tipo_Moneda,Id_Edu_Comprobante,Total_Precio,Estado,Sesion_Id,Entity,Date_Time_Creation,Date_Time_Update,Id_User_Creation,Id_User_Update FROM edu_pedido WHERE Id_Edu_Pedido = ".$Parm["Id_Edu_Pedido"]."  ";
		
		$Reg = ClassPDO::DCExecute($Query,$Conection);
     	$Id_Edu_Registro_Ventas = $Conection->lastInsertId();
		
		
		$Query = "
			SELECT 
			EC.Id_Edu_Proforma  
			FROM edu_pedido EC
			WHERE 
			EC.Id_Edu_Pedido = :Id_Edu_Pedido 
		";	
		$Where = ["Id_Edu_Pedido" => $Id_Edu_Pedido ];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Edu_Proforma = $Row->Id_Edu_Proforma;			

		
		$Reg = array(
			'Estado' => "Cerrado",
			'Date_Time_Emision' => $DCTimeHour
		);
		$Where = array('Id_Edu_Proforma' => $Id_Edu_Proforma );
		$rg = ClassPDO::DCUpdate('edu_proforma', $Reg , $Where, $Conection);		
		
		$Reg = array(
			'Estado' => "Cerrado",
			'Date_Time_Emision' => $DCTimeHour,
			'Id_Edu_Pedido' => $Parm["Id_Edu_Pedido"]
		);
		$Where = array('Id_Edu_Pedido' => $Id_Edu_Pedido);
		$rg = ClassPDO::DCUpdate('edu_pedido', $Reg , $Where, $Conection);		
				
		
		$Reg = array(
			'Estado' => "Cerrado",
			'Date_Time_Emision' => $DCTimeHour,
			'Id_Edu_Pedido' => $Id_Edu_Pedido
		);
		$Where = array('Id_Edu_Voucher' => $Id_Edu_Voucher);
		$rg = ClassPDO::DCUpdate('edu_voucher', $Reg , $Where, $Conection);		

		$Reg = array(
			'Estado' => "Cerrado",
			'Date_Time_Emision' => $DCTimeHour,
			'Id_Edu_Voucher' => $Id_Edu_Voucher
		);
		$Where = array('Id_Edu_Registro_Ventas' => $Id_Edu_Registro_Ventas);
		$rg = ClassPDO::DCUpdate('edu_registro_ventas', $Reg , $Where, $Conection);		
					
				
	    // DCWrite("procesa pago");
	}
	
	
	public function Vincula_Proforma($Parm) {
       	global $Conection, $DCTimeHour,$NameTable;
		
		$User = $_SESSION['User'];
		$Id_Edu_Datos_Entrega = $Parm["Id_Edu_Datos_Entrega"];
		
		
		$Reg = array(
			'Predeterminado' => ''
		);
		$Where = array('Id_User_Creation' => $User);
		$rg = ClassPDO::DCUpdate('edu_datos_entrega', $Reg , $Where, $Conection);		
		
		
		$Reg = array(
			'Predeterminado' => 'SI'
		);
		$Where = array('Id_Edu_Datos_Entrega' => $Id_Edu_Datos_Entrega);
		$rg = ClassPDO::DCUpdate('edu_datos_entrega', $Reg , $Where, $Conection);

	
		$Reg = array(
			'Id_Edu_Datos_Entrega' => $Id_Edu_Datos_Entrega
		);
		$Where = array('Id_Edu_Pedido' => $Parm["Id_Edu_Pedido"]);
		$rg = ClassPDO::DCUpdate('edu_pedido', $Reg , $Where, $Conection);
		

						
	}	
		

	public function Panel_Estado($Parm) {
       	global $Conection, $DCTimeHour,$NameTable;
		
        $DCPanelTitle = DCPanelTitle("Estado del Pedido","El pedido ya está cerrado",$btn);
		$Page = DCPage($DCPanelTitle ,$Page .  $Plugin ,"panel panel-default m-b-0");
		return 	$Page;	
    }		

	public function Search($Parm) {
       	global $Conection, $DCTimeHour,$NameTable;

            $Queryd = $Parm["Qr"];
			$User = $_SESSION['User'];
			
			if(empty($Queryd)){
			    $OperadorA = "<>";
				$Queryd = "8b8b8b8b8b8b8b8b8bb8b8b";
			}else{
				$OperadorA = "LIKE";
			}
							
			$Query = "
				SELECT 
				AR.Id_Edu_Articulo AS CodigoLink
				, EA.Id_Edu_Almacen
				, AR.Nombre
				, AR.Estado
				, AR.Imagen
				, AR.Date_Time_Creation
				, EAC.Nombre AS Categoria  
				FROM suscripcion SC
				INNER JOIN edu_almacen EA ON SC.Id_Edu_Almacen = EA.Id_Edu_Almacen
				INNER JOIN edu_articulo AR ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
				INNER JOIN edu_area_conocimiento EAC ON AR.Id_Edu_Area_Conocimiento = EAC.Id_Edu_Area_Conocimiento
				WHERE 
				SC.Id_User = :Id_User
				AND ( AR.Nombre ".$OperadorA." :Nombre )				
			";  
			
			$Where = [
			"Nombre" =>'%'.$Queryd.'%',
			"Id_User" => $User
			];
			
			$html ="";	
			$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
			
			$cont = 0;
			foreach ($Rows as $reg) {
				
				$con +=1;	
				
				$html .= "<div id='Item-Search-".$con."' class='item-search' onclick=selectionItem('Item-Search-".$con."'); >".$reg->Nombre."</div>";
			}			
			
		    DCWrite($html);			
	}	
	
	
	
}