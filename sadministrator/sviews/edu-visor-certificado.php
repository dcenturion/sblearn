<?php
require_once('./sviews/layout.php');
require_once('./sviews/funtion-certificado.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Visor_Certificado{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-visor-certificado";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$UrlFile_edu_estado_academico = "/sadministrator/edu-estado-academico";
		$UrlFile_edu_estado_edicion_certificado = "/sadministrator/edu-estado-edicion-certificado";
		$UrlFile_edu_estado_emision = "/sadministrator/edu-estado-emision";
		$UrlFile_edu_tipo_documento_identidad = "/sadministrator/edu-tipo-documento-identidad";
		$UrlFile_edu_pais = "/sadministrator/edu-pais";
				
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Entity_Certificado_Crud":
					        
							
				            $Id_Edu_Almacen = $Parm["key"];
                            $Data = array();
							// $Data['Seccion_Pagina'] = $Seccion_Pagina;								
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Entity"],"Id_Entity",$Data);  
							
				
						    $Settings["interface"] = "List";
						    $Settings["key"] = $Id_Edu_Almacen;
							new Edu_Visor_Certificado($Settings);
							DCExit();	
					
						break;	
						
					case "Edu_Certificado_Estados_Crud":
					        
							
				            $Id_Edu_Almacen = $Parm["key"];
				            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
                            $Data = array();
							// $Data['Seccion_Pagina'] = $Seccion_Pagina;								
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Certificado"],"Id_Edu_Certificado",$Data);  
							
				
						    $Settings["interface"] = "Create_Edit_Edu_Certificado";
						    $Settings["Id_Edu_Certificado"] = $Id_Edu_Certificado;
						    $Settings["key"] = $Id_Edu_Almacen;
							new Edu_Visor_Certificado($Settings);
							DCExit();	
					
						break;	


					case "Edu_Certificado_DGeneral_Crud":
					        
							
				            $Id_Edu_Almacen = $Parm["key"];
				            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];							
                            $Data = array();
							// $Data['Seccion_Pagina'] = $Seccion_Pagina;								
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Certificado"],"Id_Edu_Certificado",$Data);  
							
				
						    $Settings["interface"] = "Create_Edit_Edu_Certificado_General";
						    $Settings["Id_Edu_Certificado"] = $Id_Edu_Certificado;
						    $Settings["key"] = $Id_Edu_Almacen;
							new Edu_Visor_Certificado($Settings);
							DCExit();	
					
						break;	



					case "Edu_Certificado_DEnvio_Crud":
					        
							
				            $Id_Edu_Almacen = $Parm["key"];
				            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];							
                            $Data = array();
							// $Data['Seccion_Pagina'] = $Seccion_Pagina;								
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Certificado"],"Id_Edu_Certificado",$Data);  
							
				
						    $Settings["interface"] = "Create_Edit_Edu_Certificado_Envio";
						    $Settings["Id_Edu_Certificado"] = $Id_Edu_Certificado;
						    $Settings["key"] = $Id_Edu_Almacen;
							new Edu_Visor_Certificado($Settings);
							DCExit();	
					
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
					case "Edu_Certificado_Estados_Crud":
						
						$this->ObjectDelete($Parm);
									
						$Settings["Interface"] = "List";
						$Settings["REDIRECT"] = $Redirect;
						// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
						new Edu_Banner($Settings);
						DCExit();
					
						
						break;	
						
				}	
				
                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":

                break;				
        }
		
		
		
        switch ($interface) {
        
            case "List":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				$tipo_producto = $Parm["tipo-producto"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	

				
				$layout  = new Layout();


				$DCPanelTitle = DCPanelTitle("VISOR DE CERTIFICADO ",$Nombre_Articulo,"");
					
				$urlLinkB = "/key/".$Id_Edu_Almacen."";
				
				$Pestanas = "";			
						
				$Query = "
				
						SELECT Tipo_Certificado FROM edu_gestion_certificado EGC
						WHERE 
						EGC.Id_Edu_Almacen = :Id_Edu_Almacen 
						
				";	
				$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Tipo_Certificado = $Row->Tipo_Certificado;
		

				$DCPanelTitle_Msj = "<iframe width='100%' height='600px' src='/sadministrator/edu-certificado/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/interface/List/tipo-producto/".$tipo_producto."/tipo_visualizacion/demo/request/on/'></iframe>";
				// $DCPanelTitle_Msj = "<iframe width='100%' height='600px' src='/sadministrator/sviews/edu-genera-certificado.php?key=".$Id_Edu_Almacen."&Id_Edu_Certificado=".$Id_Edu_Certificado."&interface=List&tipo-producto=".$tipo_producto."&tipo_visualizacion=demo&request=on'></iframe>";
	
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $DCPanelTitle_Msj .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;



            case "Confirma_Pl_Predefinida_C":
		
				$Id_Edu_Almacen = $Parm["key"];
				
				$btn = "Cancelar ]" .$UrlFile ."/interface/List/key/".$Id_Edu_Almacen."]ScreenRight]HXM]]btn btn-default dropdown-toggle}";	
                $btn .= "Si, Confirmo ]" .$UrlFile ."/interface/Confirma_Pl_Predefinida_A/key/".$Id_Edu_Almacen."]ScreenRight]HXM]]btn btn-info]}";				
								
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Confirma el uso de la plantilla predefinida ? ",$Form,$Button,"bg-info");
							
                DCWrite($Html);
				
                break;	
				

            case "Confirma_Pl_Predefinida_A":
		
					$Id_Edu_Almacen = $Parm["key"];
					
					
					$data = array(
					'Tipo_Certificado' =>  "Predefinido",
					'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
					'Entity' => $Entity,	
					'Id_User_Update' => $User,
					'Id_User_Creation' => $User,
					'Date_Time_Creation' => $DCTimeHour,
					'Date_Time_Update' => $DCTimeHour
					);
					$Return = ClassPDO::DCInsert("edu_gestion_certificado", $data, $Conection,"");
					
					DCWrite(Message("Proceso ejecutado correctamente","C"));
			        DCCloseModal();					
					
				    $Settings["interface"] = "List";	
				    $Settings["key"] = $Id_Edu_Almacen;	
					new Edu_Visor_Certificado($Settings);
					DCExit();				
					
                break;	
								
				
				
            case "configura_plantilla_predefinida":
			
				$Id_Edu_Almacen = $Parm["key"];
				
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				$DirecctionA = $UrlFile."/process/ENTRY/Obj/Entity_Certificado_Crud/Id_Entity/".$Entity."/key/".$Id_Edu_Almacen;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Certificado/".$Id_Edu_Certificado;
				

				$Name_Interface = "Agregar Componentes";				    	
				$Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Componente"," SELECT Id_Edu_Tipo_Componente AS Id, Nombre AS Name FROM edu_tipo_componente ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Entity_Certificado_Crud")
				);	
		        $Form1 = BFormVertical("Entity_Certificado_Crud",$Class,$Entity,$PathImage,$Combobox,$Buttons,"Id_Entity");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;	
			
			
            case "EnVivo_Digitales":

				$Id_Edu_Almacen = $Parm["key"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	

				
				$layout  = new Layout();
				$listMn = "<i class='icon-chevron-right'></i> Vincular Participantes [".$UrlFile."/interface/EnVivo_Digitales_Participantes/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Eliminar Participantes [".$enlaceArea."?interface=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				$listMn .= "<i class='icon-chevron-right'></i> Enviar Email Alerta [".$enlaceArea."?interface=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Estados Académicos [".$UrlFile_edu_estado_academico."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Estados Edicion [".$UrlFile_edu_estado_edicion_certificado."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Estados Emision [".$UrlFile_edu_estado_emision."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Tipo Documentos [".$UrlFile_edu_tipo_documento_identidad."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Pais [".$UrlFile_edu_pais."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Capturar Nota [".$UrlFile_edu_pais."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Capturar Fechas [".$UrlFile_edu_pais."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				
				// $listMn .= "<i class='icon-chevron-right'></i> Estados Envio  [".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Estados Emisión  [".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Configuración  ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				// $btn .= "<i class='zmdi zmdi-edit'></i> Crear Empresa ]" .$UrlFile."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');
				

				$DCPanelTitle = DCPanelTitle("GESTIÓN DE CERTIFICADOS",$Nombre_Articulo,$btn);
					
				$urlLinkB = "/key/".$Id_Edu_Almacen."";
				
				$Pestanas = Ft_Certificado::Tabs_Principal(array(
				"".$urlLinkB."/interface/List]"
				,"".$urlLinkB."/interface/EnVivo_Digitales]Marca"
				,"".$urlLinkB."/interface/EnVivo_Fisicos]"
				,"".$urlLinkB."/interface/Grabado_Digitales]"
				,"".$urlLinkB."/interface/Grabado_Fisicos]"));					
						
	
				$Query = "
				
				    SELECT EC.Nombres
					, EC.Id_Edu_Certificado AS CodigoLink 
					, EC.Pago_Total 
					, EC.Estado_Academico 
					, EC.Estado_Edicion_Datos_Certificado AS Datos_Certificado		
					, EC.Estado_Edicion_Datos_Envio AS Datos_Envio					
					, EC.Estado_Emision_Certificado_Digital AS Estado_C_Digital
					, EC.Estado_Emision_Certificado_Fisico AS Estado_C_Fisico				
	
					FROM edu_certificado EC
					WHERE 
					EC.Id_Edu_Almacen = :Id_Edu_Almacen
					AND EC.Modalidad_Venta_Curso = :Modalidad_Venta_Curso

				";  
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Certificado';
				$Link = $UrlFile."/interface/Create_Edit_Edu_Certificado/key/".$Id_Edu_Almacen."";
				$Screen = 'animatedModal5';
				
				$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Modalidad_Venta_Curso"=>"En_Vivo"];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','');	
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Listado .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
			
                break;	


            case "EnVivo_Digitales_Participantes":
			
				$Name_Interface = "Listado de Participantes";	
			    
				$Id_Edu_Almacen = $Parm["key"];
				
				
				$btn .= " Seleccionar ]" .$UrlFile."/interface/EnVivo_Digitales_Participantes_seleccina/key/".$Id_Edu_Almacen."]ScreenRight]FORM]warehouse]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);

				
				$Query = "
				
				    SELECT UM.Nombre, PE.Nombre AS Perfil, SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
					FROM suscripcion SC
					INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
					LEFT JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
					WHERE SC.Id_Edu_Almacen = :Id_Edu_Almacen

				";  
				$Class = 'table table-hover';
				$LinkId = 'Id_Suscripcion';
				$Link = $UrlFile."/interface/Create_Edit/key/".$Id_Edu_Almacen."";
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','PS');				

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
            break;	
			
			
            case "EnVivo_Digitales_Participantes_seleccina":
			
			    	$Id_Edu_Almacen = $Parm["key"];	
					
					$Query = "
					
							SELECT Tipo_Certificado, Id_Edu_Gestion_Certificado FROM edu_gestion_certificado EGC
							WHERE 
							EGC.Id_Edu_Almacen = :Id_Edu_Almacen 
							
					";	
					$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Id_Edu_Gestion_Certificado = $Row->Id_Edu_Gestion_Certificado;
				
					
					$Id_Warehouse = DCPost("ky");
					
					
					$columnas='';
					if(count($Id_Warehouse)== 0){
						DCWrite(Message("Seleccione un registro","C"));						
					}else{
						
						for ($j = 0; $j < count($Id_Warehouse); $j++) {
							
								$Query = "

								SELECT   EC.Id_Suscripcion 
								FROM edu_certificado EC
								WHERE Id_Suscripcion = :Id_Suscripcion

								";  
								$Where = ["Id_Suscripcion" => $Id_Warehouse[$j]];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$Id_Suscripcion_DB = $Row->Id_Suscripcion;
							
                                
								if(empty($Id_Suscripcion_DB)){
									
										$Query = "

										SELECT UM.Nombre,  SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
										FROM suscripcion SC
										INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
										INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
										WHERE SC.Id_Suscripcion = :Id_Suscripcion

										";  
										$Where = ["Id_Suscripcion" => $Id_Warehouse[$j]];
										$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
										$Nombre = $Row->Nombre;

																	
										$data = array(
										'Id_Suscripcion' =>  $Id_Warehouse[$j],
										'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
										'Id_Edu_Gestion_Certificado' =>  $Id_Edu_Gestion_Certificado,
										'Modalidad_Venta_Curso' =>  "En_Vivo",
										'Nombres' =>  $Nombre,
										'Estado_Edicion_Datos_Certificado' =>  "Pendiente",
										'Estado_Emision_Certificado_Fisico' =>  "Pendiente",
										'Estado_Emision_Certificado_Digital' =>  "Pendiente",
										'Estado_Edicion_Datos_Envio' =>  "Pendiente",
										'Estado_Academico' =>  "Definir",
										'Entity' => $Entity,	
										'Id_User_Update' => $User,
										'Id_User_Creation' => $User,
										'Date_Time_Creation' => $DCTimeHour,
										'Date_Time_Update' => $DCTimeHour
										);
										$Return = ClassPDO::DCInsert("edu_certificado", $data, $Conection,"");
								}		
								
							
						}
						DCWrite(Message("Proceso ejecutado correctamente","C"));
						DCCloseModal();							
					}
				
					
				    $Id_Edu_Almacen = $Parm["key"];							
				    $Settings["interface"] = "EnVivo_Digitales";	
				    $Settings["key"] = $Id_Edu_Almacen;	
					new Edu_Visor_Certificado($Settings);
					DCExit();				
            break;				
			
			
            case "Create_Edit_Edu_Certificado":
 
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
								
				$btn .= "Estados]" .$UrlFile."/interface/Create_Edit_Edu_Certificado/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				// $btn .= "D. General]" .$UrlFile."/Interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. General]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_General/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. Envio ]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_Envio/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "Certificado ]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_Envio/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');	
				
				
				$Query = "
				SELECT UM.Nombre,  SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
				FROM suscripcion SC
				INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
				INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
				INNER JOIN edu_certificado EC  ON EC.Id_Suscripcion = SC.Id_Suscripcion
				WHERE EC.Id_Edu_Certificado = :Id_Edu_Certificado

				";  
				$Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre = $Row->Nombre;				
								
				$DCPanelTitle = DCPanelTitle("",$Nombre,$btn);
				
				$DirecctionA = $UrlFile."/process/ENTRY/Obj/Edu_Certificado_Estados_Crud/Id_Edu_Certificado/".$Id_Edu_Certificado;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Certificado/".$Id_Edu_Certificado;
				
				if(!empty($Id_Edu_Certificado)){
				    $Name_Interface = "Administra datos del certificado ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Certificado_Estados_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Tipo de Estructura";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}

				$Combobox = array(
				     array("Estado_Edicion_Datos_Envio"," SELECT Alias AS Id, Nombre AS Name FROM edu_estado_edicion_certificado ",[]),
				     array("Estado_Edicion_Datos_Certificado"," SELECT Alias AS Id, Nombre AS Name FROM edu_estado_edicion_certificado ",[]),
				     array("Estado_Emision_Certificado_Digital"," SELECT Alias AS Id, Nombre AS Name FROM edu_estado_emision ",[]),					 
				     array("Estado_Emision_Certificado_Fisico"," SELECT Alias AS Id, Nombre AS Name FROM edu_estado_emision ",[]),
					 array("Estado_Academico"," SELECT Alias AS Id, Nombre AS Name FROM edu_estado_academico ",[])

				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Certificado_Estados_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Certificado_Estados_Crud",$Class,$Id_Edu_Certificado,$PathImage,$Combobox,$Buttons,"Id_Edu_Certificado");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "Create_Edit_Edu_Certificado_General":
 
 
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
								
				$btn .= "Estados]" .$UrlFile."/interface/Create_Edit_Edu_Certificado/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				// $btn .= "D. General]" .$UrlFile."/Interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. General]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_General/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. Envio ]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_Envio/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "Certificado ]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_Envio/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');	
				
				
				$Query = "
				SELECT UM.Nombre,  SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
				FROM suscripcion SC
				INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
				INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
				INNER JOIN edu_certificado EC  ON EC.Id_Suscripcion = SC.Id_Suscripcion
				WHERE EC.Id_Edu_Certificado = :Id_Edu_Certificado

				";  
				$Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre = $Row->Nombre;				
								
				$DCPanelTitle = DCPanelTitle("",$Nombre,$btn);
				
				$DirecctionA = $UrlFile."/process/ENTRY/Obj/Edu_Certificado_DGeneral_Crud/Id_Edu_Certificado/".$Id_Edu_Certificado;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Certificado/".$Id_Edu_Certificado;
				
				if(!empty($Id_Edu_Certificado)){
				    $Name_Interface = "Administra datos del certificado ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Certificado_DGeneral_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Tipo de Estructura";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}

				$Combobox = array(
				     array("Id_Edu_Tipo_Documento_Identidad"," SELECT Id_Edu_Tipo_Documento_Identidad AS Id, Nombre AS Name FROM edu_tipo_documento_identidad ",[]),

				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Certificado_DGeneral_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Certificado_DGeneral_Crud",$Class,$Id_Edu_Certificado,$PathImage,$Combobox,$Buttons,"Id_Edu_Certificado");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
				
                break;			
				
            case "Create_Edit_Edu_Certificado_Envio":
 
 
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
								
				$btn .= "Estados]" .$UrlFile."/interface/Create_Edit_Edu_Certificado/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				// $btn .= "D. General]" .$UrlFile."/Interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. General]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_General/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. Envio ]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_Envio/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "Certificado ]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_Envio/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');	
				
				
				$Query = "
				SELECT UM.Nombre,  SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
				FROM suscripcion SC
				INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
				INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
				INNER JOIN edu_certificado EC  ON EC.Id_Suscripcion = SC.Id_Suscripcion
				WHERE EC.Id_Edu_Certificado = :Id_Edu_Certificado

				";  
				$Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre = $Row->Nombre;				
								
				$DCPanelTitle = DCPanelTitle("",$Nombre,$btn);
				
				$DirecctionA = $UrlFile."/process/ENTRY/Obj/Edu_Certificado_DEnvio_Crud/Id_Edu_Certificado/".$Id_Edu_Certificado;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Certificado/".$Id_Edu_Certificado;
				
				if(!empty($Id_Edu_Certificado)){
				    $Name_Interface = "Administra datos del certificado ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Certificado_DEnvio_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Tipo de Estructura";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}

				$Combobox = array(
				     array("Id_Edu_Pais"," SELECT Id_Edu_Pais AS Id, Nombre AS Name FROM edu_pais ORDER BY Nombre ASC ",[]),

				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Certificado_DEnvio_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Certificado_DEnvio_Crud",$Class,$Id_Edu_Certificado,$PathImage,$Combobox,$Buttons,"Id_Edu_Certificado");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
				
                break;			
				
				
            case "DeleteMassive":
		
		        $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Certificado/".$Id_Edu_Certificado."/Obj/Edu_Certificado_Estados_Crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/List]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;	


            case "List_Banner_Capacitacion_Empresa":
			
				$Name_Interface = "Listado de Banners";	
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/Interface/Create_Para_Empresa]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT PB.Id_Edu_Certificado AS CodigoLink, PB.Frase, PB.Estado FROM edu_banner PB
					WHERE PB.Seccion_Pagina =:Seccion_Pagina
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Certificado';
				$Link = $UrlFile."/Interface/Create_Para_Empresa";
				$Screen = 'animatedModal5';
				$where = ["Seccion_Pagina"=>"Para_Empresa"];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;		
				

            case "Create_Para_Empresa":
			
				$btn .= "Atrás]" .$UrlFile."/Interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Certificado_Estados_Crud/Seccion_Pagina/Para_Empresa/Id_Edu_Certificado/".$Id_Edu_Certificado;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Certificado/".$Id_Edu_Certificado;
				
				if(!empty($Id_Edu_Certificado)){
				    $Name_Interface = "Editar Banner ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Certificado_Estados_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Banner ";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Componente"," SELECT Id_Edu_Tipo_Componente AS Id, Nombre AS Name FROM edu_tipo_componente ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Certificado_Estados_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Certificado_Estados_Crud",$Class,$Id_Edu_Certificado,$PathImage,$Combobox,$Buttons,"Id_Edu_Certificado");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;	
				
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Certificado = $Settings["Id_Edu_Certificado"];
			
		$where = array('Id_Edu_Certificado' =>$Id_Edu_Certificado);
		$rg = ClassPDO::DCDelete('edu_banner', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		
	
	
	
}