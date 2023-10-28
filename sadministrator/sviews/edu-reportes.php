<?php
require_once('./sviews/layout.php');
require_once(dirname(__FILE__).'/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Reportes{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-reportes";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					
					case "Edu_Evaluacion_Desarrollo_Cab_Crud":
					
                            $Data = array();		
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Evaluacion_Desarrollo_Cab"],"Id_Edu_Evaluacion_Desarrollo_Cab",$Data);  
							$Id_Edu_Objeto_Evaluativo = $Result["lastInsertId"];
							
							$Estado = DCPost("Estado");
							// var_dump("ddd");
							if(empty($Estado)){
								
							
								
								$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' =>$Parm["Id_Edu_Evaluacion_Desarrollo_Cab"]);
								$rg = ClassPDO::DCDelete('edu_evaluacion_desarrollo_det', $where, $Conection);

							
								$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' =>$Parm["Id_Edu_Evaluacion_Desarrollo_Cab"]);
								$rg = ClassPDO::DCDelete('edu_evaluacion_desarrollo_cab', $where, $Conection);
								
								// var_dump($rg);
								// echo "ingreso aquí".$Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];

							}
							
							
	                        $Settings = array();
							$Settings['Url'] = $UrlFile."/interface/Detalle_Resultados_Evaluacion/key/".$Parm["key"]."/Id_Suscripcion/".$Parm["Id_Suscripcion"];
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJS($Settings);	
							var_dump($Settings);
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
					
					case "Edu_Crud_Pregunta":
						
						$this->ObjectDeletePregunta($Parm);
									
						$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
						$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
						$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];


						$Settings = array();
						$Settings['Url'] = $UrlFile."/interface/Configura_Examen/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Articulo/".$Id_Edu_Articulo;
						$Settings['Screen'] = "PanelA";
						$Settings['Type_Send'] = "HXM";
						DCRedirectJSSP($Settings);	
						DCCloseModal();
						break;	
											
					case "Edu_Respuesta_Crud":
						
						$this->ObjectDeleteRespuesta($Parm);
									
						$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
						$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
						$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];


						$Settings = array();
						$Settings['Url'] = $UrlFile."/interface/Configura_Examen/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Articulo/".$Id_Edu_Articulo;
						$Settings['Screen'] = "PanelA";
						$Settings['Type_Send'] = "HXM";
						DCRedirectJSSP($Settings);	
						DCCloseModal();
						break;	
						
						
						
						
				}	
				
                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":

                break;				
        }
		
		
		
        switch ($interface) {

            case "Resultados_Evaluacion":
			
	            $Id_Edu_Almacen = $Parm["key"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        // $Nombre_Articulo = $Row_Producto->Nombre;
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo;	
						
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				$Name_Interface = "Reporte de Actividades Evaluativas";
				
				
				$Query = "
				SELECT 
				COUNT(*) AS Tot_Reg
				FROM suscripcion OE 
				WHERE 
				OE.Id_Edu_Almacen = :Id_Edu_Almacen  ";	
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Tot_Reg = $Row->Tot_Reg;
				
				$Query = "
				   
					SELECT 
				    S.Id_Suscripcion AS CodigoLink, UM.Nombre, UM.Email
					FROM edu_evaluacion_desarrollo_cab EDC
					INNER JOIN suscripcion S ON S.Id_Suscripcion = EDC.Id_Suscripcion
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = S.Id_User
					INNER JOIN edu_objeto_evaluativo_detalle EOE ON EDC.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
					WHERE 
					EOE.Id_Edu_Articulo = :Id_Edu_Articulo  
					GROUP BY S.Id_User
					ORDER BY EDC.Date_Time_Creation DESC
				";    
				
				$Class = 'table table-hover';
				$LinkId = 'Id_Suscripcion';
				$Link = $UrlFile."/interface/Detalle_Resultados_Evaluacion".$urlLinkB;
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Articulo"=>$Id_Edu_Articulo ];
				// var_dump($where);
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				
		
				$btn .= "  Descargar Excel ]" .$UrlFile."/interface/Descargar_Acta_Notas/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."]]HREF]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');
				$DCPanelTitle = DCPanelTitle("Datos Importantes","Total Participantes: ".$Tot_Reg,$btn);

                $Style = "
				    <style>
					.botones1 a {
						margin-top: 0px;
						color: #000;
					}
				    </style>
				
				";				
				
			    $Html = DCModalForm( $Name_Interface, $DCPanelTitle . $Listado .  $Style,"");
                DCWrite($Html);
                DCExit();
				
                break;	

            case "Detalle_Resultados_Evaluacion":
			
	            $Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Suscripcion = $Parm["Id_Suscripcion"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
	
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Suscripcion/".$Id_Suscripcion;	
						
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				$Name_Interface = "Reporte de Actividades Evaluativas";
				
				
				$Query = "
				SELECT 
				UM.Nombre
				FROM suscripcion OE 
				INNER JOIN user_miembro UM ON UM.Id_User_Miembro = OE.Id_User
				WHERE 
				OE.Id_Edu_Almacen = :Id_Edu_Almacen  AND OE.Id_Suscripcion =:Id_Suscripcion  ";	
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen ,"Id_Suscripcion" =>$Id_Suscripcion];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre = $Row->Nombre;
				
                
				$Query = "
					SELECT 
				    EOE.Id_Edu_Objeto_Evaluativo AS CodigoLink, EOEN.Nombre, EDC.Nota
					FROM edu_evaluacion_desarrollo_cab EDC
					INNER JOIN suscripcion S ON S.Id_Suscripcion = EDC.Id_Suscripcion
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = S.Id_User
					INNER JOIN edu_objeto_evaluativo_detalle EOE ON EDC.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
					INNER JOIN edu_objeto_evaluativo EOEN ON EOEN.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
					WHERE 
					EOE.Id_Edu_Articulo = :Id_Edu_Articulo AND  
					EDC.Id_Suscripcion = :Id_Suscripcion 
					ORDER BY EOE.Date_Time_Creation ASC
					
				";    
				

				
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Objeto_Evaluativo';
				$Link = $UrlFile."/interface/Detalle_Resultados_Evaluacion_Config".$urlLinkB;
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Articulo"=>$Id_Edu_Articulo, "Id_Suscripcion" => $Id_Suscripcion];
								var_dump($where);
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				
		
				$btn .= "  Atrás ]" .$UrlFile."/interface/Resultados_Evaluacion/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');
				$DCPanelTitle = DCPanelTitle("LISTADO",$Nombre,$btn);	
				
			    $Html = DCModalForm( $Name_Interface, $DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
				
                break;					
          

            case "Detalle_Resultados_Evaluacion_Config":
			
	            $Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Suscripcion = $Parm["Id_Suscripcion"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;	
						
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				$Name_Interface = "Reporte de Actividades Evaluativas";
				
				
				$Query = "
				SELECT 
				UM.Nombre
				FROM suscripcion OE 
				INNER JOIN user_miembro UM ON UM.Id_User_Miembro = OE.Id_User
				WHERE 
				OE.Id_Edu_Almacen = :Id_Edu_Almacen  AND OE.Id_Suscripcion =:Id_Suscripcion  ";	
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen , "Id_Suscripcion" =>$Id_Suscripcion];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre = $Row->Nombre;
				
				$Query = "
				
					SELECT 
					EOEN.Nombre , EOE.Id_Edu_Evaluacion_Desarrollo_Cab
					FROM  edu_evaluacion_desarrollo_cab EOE 
					INNER JOIN edu_objeto_evaluativo EOEN ON EOEN.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
					WHERE  
					EOEN.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo 
					AND EOE.Id_Suscripcion = :Id_Suscripcion
					
				";	
				$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo,"Id_Suscripcion"=>$Id_Suscripcion];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre_Objeto_Evaluativo = $Row->Nombre;
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Row->Id_Edu_Evaluacion_Desarrollo_Cab;
				
	            
				$urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Id_Edu_Evaluacion_Desarrollo_Cab."/Id_Suscripcion/".$Id_Suscripcion;								
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Evaluacion_Desarrollo_Cab_Crud".$urlLinkB;
				// $DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Evaluacion_Desarrollo_Cab)){		    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    // $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_Embebido_Crud","btn btn-default m-w-120");					
				}else{		
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";
				}
				
				$Combobox = array(
					 array( "Estado"," SELECT Alias_Estado AS Id, Nombre AS Name FROM edu_estado_objeto_evaluativo ",[])
				);

				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","Edu_Evaluacion_Desarrollo_Cab_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Evaluacion_Desarrollo_Cab_Crud",$Class,$Id_Edu_Evaluacion_Desarrollo_Cab,$PathImage,$Combobox,$Buttons,"Id_Edu_Evaluacion_Desarrollo_Cab");
					
					
                
				$btn .= "  Atrás ]" .$UrlFile."/interface/Detalle_Resultados_Evaluacion/key/".$Id_Edu_Almacen."/Id_Suscripcion/".$Id_Suscripcion."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');
				
				$DCPanelTitle = DCPanelTitle($Nombre_Objeto_Evaluativo,$Nombre ." -- ".$Id_Suscripcion,$btn);	
				
			    $Html = DCModalForm("Módulo de configuración ", $DCPanelTitle .$Form1 ,"");
                DCWrite($Html);
                DCExit();
				
                break;					
          
		
			
            case "Ingreso_Aula":
			
			    
			
	            $Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Suscripcion = $Parm["Id_Suscripcion"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;	
						
				$Query = "
					SELECT  
					SUBSTRING(IP.Date_Time_Creation,1,10) AS CodigoLink,
					DATE_FORMAT(IP.Date_Time_Creation, '%M %d %Y') AS Fecha
					FROM 
					suscripcion SCP 
					INNER JOIN edu_ingreso_plataforma IP ON IP.Id_User_Creation = SCP.Id_User
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = SCP.Id_User
					WHERE SCP.Id_Edu_Almacen = :Id_Edu_Almacen
					GROUP BY DATE_FORMAT(IP.Date_Time_Creation, '%M %d %Y')
					ORDER BY DATE_FORMAT(IP.Date_Time_Creation, '%M %d %Y')
					DESC
				";    
				$Class = 'table table-hover';
				$LinkId = 'Date_Time_Creation';
				$Link = $UrlFile."/interface/Ingreso_Aula_Det/key/".$Id_Edu_Almacen;
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				
		
				
				$DCPanelTitle = DCPanelTitle($Nombre_Objeto_Evaluativo,$Nombre,$btn);	
				
			    $Html = DCModalForm("Reporte de Asistencia por día ", $DCPanelTitle .$Listado ,"");
                DCWrite($Html);
                DCExit();
				
                break;					
          
		
			
            case "Ingreso_Aula_Det":
			
			    
	            $Id_Edu_Almacen = $Parm["key"];
				$Date_Time_Creation = $Parm["Date_Time_Creation"];
				$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
				// $Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;	
						
				$Query = "
					SELECT  
					IP.Date_Time_Creation AS CodigoLink,
					UM.Nombre
					FROM 
					suscripcion SCP 
					INNER JOIN edu_ingreso_plataforma IP ON IP.Id_User_Creation = SCP.Id_User
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = SCP.Id_User
					WHERE SCP.Id_Edu_Almacen = :Id_Edu_Almacen AND SUBSTRING(IP.Date_Time_Creation,1,10) = :Date_Time_Creation
					GROUP BY SCP.Id_User
					ORDER BY SUBSTRING(IP.Date_Time_Creation,1,10)
					DESC
				";    
				$Class = 'table table-hover';
				$LinkId = 'Date_Time_Creation';
				$Link = $UrlFile."/interface/Detalle_Analisis_Concurrencia/key/".$Id_Edu_Almacen;
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Date_Time_Creation"=>$Date_Time_Creation];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				
		
				$btn = "<i class='zmdi zmdi-arrow-left zmdi-hc-fw'></i> Atrás]" .$UrlFile."/interface/Ingreso_Aula/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= " Descargar ]" .$UrlFile."/interface/Ingreso_Aula_Det_Descargar/key/".$Id_Edu_Almacen."/Date_Time_Creation/".$Date_Time_Creation."]animatedModal5]HREF]]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');			
				$DCPanelTitle = DCPanelTitle("Día ".$Date_Time_Creation,$Nombre,$btn);	
				
			    $Html = DCModalForm("Reporte de Asistencia por día ", $DCPanelTitle .$Listado ,"");
                DCWrite($Html);
                DCExit();
				
                break;		
		
			
            case "Resumen_Asistencia_Interacciones_View":
			
			    
	            $Id_Edu_Almacen = $Parm["key"];
				$Date_Time_Creation = $Parm["Date_Time_Creation"];
				$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
				// $Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;	
						
		        $Query = "
					SELECT  

					UM.Nombre,
					SCP.Id_User
					
					FROM 
					suscripcion SCP 

					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = SCP.Id_User
					WHERE SCP.Id_Edu_Almacen = :Id_Edu_Almacen 
					GROUP BY SCP.Id_User
					ORDER BY UM.Nombre
					ASC
				";    

				$Where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];
		        $Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			
				$tableCuerpo = "";
				$DiferenciadorA = "";
				$DiferenciadorB = "";
				$ContGeneral = 0;
				$ContParticipantes = 0;
				$ContParticipantes_B = 0;
				foreach ($Registro as $Reg) {
					
					$ContGeneral += 1;				    

				

								$Query = "
								SELECT 
								COUNT(*) AS Cantidad_Acceso
								FROM edu_ingreso_plataforma IP 
								WHERE IP.Id_User_Creation =:Id_User_Creation  ";	
								$Where = ["Id_User_Creation" => $Reg->Id_User ];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$Cantidad_Acceso = $Row->Cantidad_Acceso;


								$Query = "
									SELECT 
									
									COUNT(*) AS Cantidad_Interacciones
									FROM edu_vistas_objectos EVIO
									INNER JOIN edu_componente EC ON EC.Id_Edu_Componente = EVIO.Id_Edu_Componente
									WHERE 
									EVIO.Id_User = :Id_User AND EVIO.Id_Edu_Almacen = :Id_Edu_Almacen 
								";    								
								$Where = ["Id_User" => $Reg->Id_User, "Id_Edu_Almacen"=>$Id_Edu_Almacen ];												
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$Cantidad_Interacciones = $Row->Cantidad_Interacciones;
				
								$tableCuerpo .= "<tr>";			    
								$tableCuerpo .= "<td>".$Reg->Nombre."</td>";		
								$tableCuerpo .= "<td>".$Cantidad_Acceso."</td>";		
								$tableCuerpo .= "<td>".$Cantidad_Interacciones."</td>";		

								$tableCuerpo .= "</tr>";

				}	
				

				$table = "<table class='table table-hover'>";		
				
					$tableCabezera = "<tr>";
					$tableCabezera .= "<th>Nombres</th>";
					$tableCabezera .= "<th> Ingreso a<br>Plataforma</th>";
					$tableCabezera .= "<th> Cantidad de <br>Interacciones</th>";
					$tableCabezera .= "</tr>";
								
				$table .= $tableCabezera;			
				$table .= $tableCuerpo;			
				$table .= "</table>";
				
		
				$btn = "<i class='zmdi zmdi-arrow-left zmdi-hc-fw'></i> Atrás]" .$UrlFile."/interface/Ingreso_Aula/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= " Descargar ]" .$UrlFile."/interface/Ingreso_Aula_Det_Descargar/key/".$Id_Edu_Almacen."/Date_Time_Creation/".$Date_Time_Creation."]animatedModal5]HREF]]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');			
				$DCPanelTitle = DCPanelTitle("Día ".$Date_Time_Creation,$Nombre,$btn);	
				
			    $Html = DCModalForm("Reporte de Asistencia por día ", $DCPanelTitle .$table ,"");
                DCWrite($Html);
                DCExit();
				
                break;		


            case "Ingreso_Aula_Det_Descargar":

				error_reporting(E_ALL);
				ini_set('display_errors', '1');

		        $Id_Edu_Almacen = $Parm["key"];
		        $Date_Time_Creation = $Parm["Date_Time_Creation"];

				
				require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/excel_classes_v2/PHPExcel.php');
	
				$objPHPExcel = new PHPExcel();			
				$objPHPExcel->getProperties()->setCreator("XELASC") // Nombre del autor
				->setLastModifiedBy("XELASC") //Ultimo usuario que lo modificó
				->setTitle("Participantes Registrados") // Titulo
				->setSubject("") //Asunto
				->setDescription("Reporte de Participantes Registrados") //Descripción
				->setKeywords("reporte de participantes registrados") //Etiquetas
				->setCategory("Reporte excel"); //Categorias		

				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1',"Source")
					->setCellValue('B1',"Target");
					
				$Query = "
					SELECT  
					IP.Date_Time_Creation ,
					UM.Nombre
					FROM 
					suscripcion SCP 
					INNER JOIN edu_ingreso_plataforma IP ON IP.Id_User_Creation = SCP.Id_User
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = SCP.Id_User
					WHERE SCP.Id_Edu_Almacen = :Id_Edu_Almacen AND SUBSTRING(IP.Date_Time_Creation,1,10) = :Date_Time_Creation
					GROUP BY SCP.Id_User
					ORDER BY SUBSTRING(IP.Date_Time_Creation,1,10)
					DESC
				";    

				$Where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Date_Time_Creation"=>$Date_Time_Creation];
		        $Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			
				$tableCuerpo = "";
				$DiferenciadorA = "";
				$DiferenciadorB = "";
				$ContGeneral = 0;
				$ContParticipantes = 0;
				$ContParticipantes_B = 0;
				foreach ($Registro as $Reg) {
					
					$ContGeneral += 1;				    

				
								$tableCuerpo .= "<tr>";			    
								$tableCuerpo .= "<td>".$Reg->Nombre."</td>";		
								$tableCuerpo .= "<td>".$Reg->Date_Time_Creation."</td>";		

								$tableCuerpo .= "</tr>";

				}	
				

				$table = "<table>";		
				
					$tableCabezera = "<tr>";
					$tableCabezera .= "<th>Nombres</th>";
					$tableCabezera .= "<th>Fecha_Asistencia</th>";
					$tableCabezera .= "</tr>";
								
				$table .= $tableCabezera;			
				$table .= $tableCuerpo;			
				$table .= "</table>";	

				header ("Content-Type: application/vnd.ms-excel");
				header ("Content-Disposition: inline; filename=Asistencia_Por_Dia.xls");
				
				echo $table;

				exit();							
			    break;	
				
				


            case "Resumen_Asistencia_Interacciones":

				// error_reporting(E_ALL);
				// ini_set('display_errors', '1');

		        $Id_Edu_Almacen = $Parm["key"];
		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				// $Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        // $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;

				
				require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/excel_classes_v2/PHPExcel.php');
	
				$objPHPExcel = new PHPExcel();			
				$objPHPExcel->getProperties()->setCreator("XELASC") // Nombre del autor
				->setLastModifiedBy("XELASC") //Ultimo usuario que lo modificó
				->setTitle("Participantes Registrados") // Titulo
				->setSubject("") //Asunto
				->setDescription("Reporte de Participantes Registrados") //Descripción
				->setKeywords("reporte de participantes registrados") //Etiquetas
				->setCategory("Reporte excel"); //Categorias		

				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1',"Source")
					->setCellValue('B1',"Target");
					
					
					
					
				$Query = "
					SELECT 
						EOE.Id_Edu_Objeto_Evaluativo , EOEN.Nombre, EDC.Nota
					FROM edu_evaluacion_desarrollo_cab EDC

					INNER JOIN edu_objeto_evaluativo_detalle EOE ON EDC.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
					INNER JOIN edu_objeto_evaluativo EOEN ON EOEN.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo

					WHERE 
					EOE.Id_Edu_Articulo = :Id_Edu_Articulo 
					GROUP BY EOE.Id_Edu_Objeto_Evaluativo
					ORDER BY EOE.Date_Time_Creation ASC
				";    

				$Where = ["Id_Edu_Articulo"=>$Id_Edu_Articulo];
		        $Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			
				$tableCabezera_Table = "";
				foreach ($Registro as $Reg) {					
					$tableCabezera_Table .="<th>".$Reg->Nombre."</th>"; 
				}


				
				$Query = "
					SELECT  
					UM.Nombre, SCP.Id_Suscripcion
					FROM 
					suscripcion SCP 
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = SCP.Id_User
					WHERE SCP.Id_Edu_Almacen = :Id_Edu_Almacen 
					GROUP BY SCP.Id_User
					DESC
				";    

				$Where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];
		        $Registro_B = ClassPdo::DCRows($Query,$Where,$Conection);
			
				$ContGeneral = 0;
				$tableCuerpo = "";
				foreach ($Registro_B as $Reg) {
					
					$ContGeneral += 1;				
					
                                $Id_Suscripcion_A = $Reg->Id_Suscripcion;
				
								$tableCuerpo .= "<tr>";			    
								$tableCuerpo .= "<td>".$Reg->Nombre."</td>";	
                                    
									$Nota_Imp = "";
				                    foreach ($Registro as $Reg_D) {	
									
									        $Id_Edu_Objeto_Evaluativo = $Reg_D->Id_Edu_Objeto_Evaluativo;
									        // $Id_Edu_Objeto_Evaluativo = $Reg_D->Nota;
											
											$Query = "
												SELECT 
													EOE.Id_Edu_Objeto_Evaluativo AS CodigoLink, EOEN.Nombre, EDC.Nota
												FROM edu_evaluacion_desarrollo_cab EDC

												INNER JOIN edu_objeto_evaluativo_detalle EOE ON EDC.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
												INNER JOIN edu_objeto_evaluativo EOEN ON EOEN.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo

												WHERE 
												EOE.Id_Edu_Articulo = :Id_Edu_Articulo AND  
												EDC.Id_Suscripcion = :Id_Suscripcion AND
												EOE.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
												GROUP BY EOE.Id_Edu_Objeto_Evaluativo
												ORDER BY EOE.Date_Time_Creation ASC
												
											";    
                                            
											$Where = ["Id_Edu_Articulo"=>$Id_Edu_Articulo,"Id_Suscripcion"=>$Id_Suscripcion_A,"Id_Edu_Objeto_Evaluativo"=>$Id_Edu_Objeto_Evaluativo];
											$Registroc = ClassPdo::DCRow($Query,$Where,$Conection);
										    $Nota_Imp = $Registroc->Nota;
											
			
											// if( $Nota_Imp == 0 ){
													// $tableCuerpo .= "<td>cero</td>";
											// }else{
												$tableCuerpo .= "<td>".$Nota_Imp."</td>"; 
											// }
											
											// if($Nota_Imp == ""){
												// $tableCuerpo .= "<td>F</td>";
											// }
											
											// if($Nota_Imp !== 0 && $Nota_Imp !== "" ){												
											        // $Nota_Imp = $Nota_Imp;
													// $tableCuerpo .= "<td>".$Nota_Imp."</td>"; 
											// }
												
											
											
											
									}		
									
									

								

								$tableCuerpo .= "</tr>";

				}	
				

				$table = "<table>";		
				
					$tableCabezera = "<tr>";
					$tableCabezera .="<th>Participante</th>"; 
					
					$tableCabezera .= $tableCabezera_Table;
					$tableCabezera .= "</tr>";
								
				$table .= $tableCabezera;			
				$table .= $tableCuerpo;			
				$table .= "</table>";	

				header ("Content-Type: application/vnd.ms-excel");
				header ("Content-Disposition: inline; filename=Acta_de_Notas.xls");
				
				echo $table;

				exit();							
			    break;					
				
								
				
				


            case "Descargar_Acta_Notas":

				// error_reporting(E_ALL);
				// ini_set('display_errors', '1');

		        $Id_Edu_Almacen = $Parm["key"];
		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				// $Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        // $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;

				
				require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/excel_classes_v2/PHPExcel.php');
	
				$objPHPExcel = new PHPExcel();			
				$objPHPExcel->getProperties()->setCreator("XELASC") // Nombre del autor
				->setLastModifiedBy("XELASC") //Ultimo usuario que lo modificó
				->setTitle("Participantes Registrados") // Titulo
				->setSubject("") //Asunto
				->setDescription("Reporte de Participantes Registrados") //Descripción
				->setKeywords("reporte de participantes registrados") //Etiquetas
				->setCategory("Reporte excel"); //Categorias		

				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1',"Source")
					->setCellValue('B1',"Target");
					
					
					
					
				$Query = "
					SELECT 
						EOE.Id_Edu_Objeto_Evaluativo , EOEN.Nombre, EDC.Nota
					FROM edu_evaluacion_desarrollo_cab EDC

					INNER JOIN edu_objeto_evaluativo_detalle EOE ON EDC.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
					INNER JOIN edu_objeto_evaluativo EOEN ON EOEN.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo

					WHERE 
					EOE.Id_Edu_Articulo = :Id_Edu_Articulo 
					GROUP BY EOE.Id_Edu_Objeto_Evaluativo
					ORDER BY EOE.Date_Time_Creation ASC
				";    

				$Where = ["Id_Edu_Articulo"=>$Id_Edu_Articulo];
		        $Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			
				$tableCabezera_Table = "";
				foreach ($Registro as $Reg) {					
					$tableCabezera_Table .="<th>".$Reg->Nombre."</th>"; 
				}


				
				$Query = "
					SELECT  
					UM.Nombre, SCP.Id_Suscripcion
					FROM 
					suscripcion SCP 
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = SCP.Id_User
					WHERE SCP.Id_Edu_Almacen = :Id_Edu_Almacen 
					GROUP BY SCP.Id_User
					DESC
				";    

				$Where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];
		        $Registro_B = ClassPdo::DCRows($Query,$Where,$Conection);
			
				$ContGeneral = 0;
				$tableCuerpo = "";
				foreach ($Registro_B as $Reg) {
					
					$ContGeneral += 1;				
					
                                $Id_Suscripcion_A = $Reg->Id_Suscripcion;
				
								$tableCuerpo .= "<tr>";			    
								$tableCuerpo .= "<td>".$Reg->Nombre."</td>";	
                                    
									$Nota_Imp = "";
				                    foreach ($Registro as $Reg_D) {	
									
									        $Id_Edu_Objeto_Evaluativo = $Reg_D->Id_Edu_Objeto_Evaluativo;
									        // $Id_Edu_Objeto_Evaluativo = $Reg_D->Nota;
											
											$Query = "
												SELECT 
													EOE.Id_Edu_Objeto_Evaluativo AS CodigoLink, EOEN.Nombre, EDC.Nota
												FROM edu_evaluacion_desarrollo_cab EDC

												INNER JOIN edu_objeto_evaluativo_detalle EOE ON EDC.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
												INNER JOIN edu_objeto_evaluativo EOEN ON EOEN.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo

												WHERE 
												EOE.Id_Edu_Articulo = :Id_Edu_Articulo AND  
												EDC.Id_Suscripcion = :Id_Suscripcion AND
												EOE.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
												GROUP BY EOE.Id_Edu_Objeto_Evaluativo
												ORDER BY EOE.Date_Time_Creation ASC
												
											";    
                                            
											$Where = ["Id_Edu_Articulo"=>$Id_Edu_Articulo,"Id_Suscripcion"=>$Id_Suscripcion_A,"Id_Edu_Objeto_Evaluativo"=>$Id_Edu_Objeto_Evaluativo];
											$Registroc = ClassPdo::DCRow($Query,$Where,$Conection);
										    $Nota_Imp = $Registroc->Nota;
											
			
											// if( $Nota_Imp == 0 ){
													// $tableCuerpo .= "<td>cero</td>";
											// }else{
												$tableCuerpo .= "<td>".$Nota_Imp."</td>"; 
											// }
											
											// if($Nota_Imp == ""){
												// $tableCuerpo .= "<td>F</td>";
											// }
											
											// if($Nota_Imp !== 0 && $Nota_Imp !== "" ){												
											        // $Nota_Imp = $Nota_Imp;
													// $tableCuerpo .= "<td>".$Nota_Imp."</td>"; 
											// }
												
											
											
											
									}		
									
									

								

								$tableCuerpo .= "</tr>";

				}	
				

				$table = "<table>";		
				
					$tableCabezera = "<tr>";
					$tableCabezera .="<th>Participante</th>"; 
					
					$tableCabezera .= $tableCabezera_Table;
					$tableCabezera .= "</tr>";
								
				$table .= $tableCabezera;			
				$table .= $tableCuerpo;			
				$table .= "</table>";	

				header ("Content-Type: application/vnd.ms-excel");
				header ("Content-Disposition: inline; filename=Acta_de_Notas.xls");
				
				echo $table;

				exit();							
			    break;					
				
				
        }
				
		
		
	}
	
	public function ObjectDeletePregunta($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Pregunta = $Settings["Id_Edu_Pregunta"];
		
		$where = array('Id_Edu_Pregunta' =>$Id_Edu_Pregunta);
		$rg = ClassPDO::DCDelete('edu_pregunta', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		


	
	public function Guarda_Respuesta($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];	
		$Id_Edu_Pregunta = $Settings["Id_Edu_Pregunta"];		
		$Pregunta_Actual = $Settings["Pregunta_Actual"];		
		$Id_Form = $Settings["Id_Form"];	
		$Respuesta_Seleccionada = $Settings["Respuesta_Seleccionada"];	
		$Id_Edu_Evaluacion_Desarrollo_Cab = $Settings["Id_Edu_Evaluacion_Desarrollo_Cab"];


		$reg = array(
			'Pregunta_Actual' => $Pregunta_Actual
		);
     
		$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
		$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection,"");	
		 // var_dump($where);	
		 
        // var_dump($Settings);
   
		$Query = " 
		SELECT Id_Edu_Evaluacion_Desarrollo_Det, Id_Edu_Respuesta, Id_Edu_Pregunta 
		FROM edu_evaluacion_desarrollo_det
		WHERE 
		Id_Edu_Pregunta = :Id_Edu_Pregunta  AND 
		Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab  AND 
		Id_Edu_Respuesta = :Id_Edu_Respuesta  
		"; 
		$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta,"Id_Edu_Respuesta"=>$Respuesta_Seleccionada,"Id_Edu_Evaluacion_Desarrollo_Cab"=>$Id_Edu_Evaluacion_Desarrollo_Cab];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Edu_Evaluacion_Desarrollo_Det = $Row->Id_Edu_Evaluacion_Desarrollo_Det;
		
        if(empty($Id_Edu_Evaluacion_Desarrollo_Det)){
			
			$data = array(
			'Id_Edu_Respuesta' => $Respuesta_Seleccionada,
			'Id_Edu_Pregunta' => $Id_Edu_Pregunta,
			'Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab,
			'Entity' => $Entity,
			'Id_User_Creation' => $User,
			'Id_User_Update' => $User,
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);	
			// var_dump($data);
			$Result_Cab = ClassPDO::DCInsert("edu_evaluacion_desarrollo_det", $data, $Conection);
			$Id_Edu_Evaluacion_Desarrollo_Cab = $Result_Cab["lastInsertId"];
			// var_dump($Id_Edu_Evaluacion_Desarrollo_Cab);
						
		}else{

			$reg = array(
				'Id_Edu_Respuesta' => $Respuesta_Seleccionada
			);
			$where = array('Id_Edu_Evaluacion_Desarrollo_Det' => $Id_Edu_Evaluacion_Desarrollo_Det);
			$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_det', $reg , $where, $Conection,"");	

		}
		
	
		


    }		
		
	
	public function Respuestas_Evaluacion($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];	
		$Id_Edu_Pregunta = $Settings["Id_Edu_Pregunta"];		
		$Id_Form = $Settings["Id_Form"];	

		
		
		$Query = " 
		SELECT Id_Edu_Respuesta, Nombre, Id_Edu_Pregunta FROM edu_respuesta
		WHERE 
		Id_Edu_Pregunta = :Id_Edu_Pregunta  ;
		"; 
		$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta];
		$Mat_Respuestas = ClassPdo::DCRows($Query,$Where,$Conection);
        $Html = "";		
		foreach ($Mat_Respuestas as $Reg) {		
				$Nombre = $Reg->Nombre;
				$Id_Edu_Respuesta = $Reg->Id_Edu_Respuesta;
				$Id_Edu_Pregunta = $Reg->Id_Edu_Pregunta;
				$Html .= '<div class="form-group">';
				    $Html .= '<label class="control-label" >';
				    $Html .= ' <input type="radio" class="checkbox-inline" id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_'.$Id_Edu_Respuesta.'" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'" value="'.$Id_Edu_Respuesta.'" >';		
					$Html .= '    '.$Nombre.' ';
				    $Html .= '</label>';	
					
				$Html .= '</div>';

        }		
		return $Html;
    }		
		
	
	public function Preguntas_Evaluacion($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];	
		
		$Id_Edu_Objeto_Evaluativo = $Settings["Id_Edu_Objeto_Evaluativo"];
		$Estado_Pregunta = $Settings["Estado_Pregunta"];
		
		$key = $Settings["key"];
		
		$Query = "
		SELECT 
		EC.Id_Edu_Objeto_Evaluativo
		, OE.Nombre
		, OE.Id_Edu_Tipo_Objeto_Evaluativo
		, OE.Hora_Inicio
		, OE.Hora_Fin
		, OE.Fecha_Inicio
		, OE.Tiempo_Duracion
		, OE.Fecha_Fin
		, OE.Preguntas_Por_Mostrar
		, OTD.Nombre AS Tipo_Desarrollo
		, OTD.Id_Edu_Tipo_Desarrollo
		FROM edu_objeto_evaluativo_detalle EC
		INNER JOIN edu_objeto_evaluativo OE ON EC.Id_Edu_Objeto_Evaluativo = OE.Id_Edu_Objeto_Evaluativo
		INNER JOIN edu_tipo_desarrollo OTD ON OE.Id_Edu_Tipo_Desarrollo = OTD.Id_Edu_Tipo_Desarrollo
		WHERE 
		OE.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
		";	
		$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Preguntas_Por_Mostrar = $Row->Preguntas_Por_Mostrar;	
		// $Nombre_Objeto_Evaluativo = $Row->Nombre;	

		$Query = " 
		SELECT Id_Edu_Pregunta, Nombre, Orden, Descripcion FROM edu_pregunta 
		WHERE 
		Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo 
		ORDER BY RAND() LIMIT ".$Preguntas_Por_Mostrar.";
		"; 
		$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo];
		$Max_Preguntas = ClassPdo::DCRows($Query,$Where,$Conection);
		$Matriz_Preguntas = json_encode($Max_Preguntas);
		
		
		// json_encode($array)
		$Query = "
		SELECT 
		OE.Id_Suscripcion
		, OE.Id_Edu_Almacen
		, OE.Id_User
		FROM suscripcion OE 
		WHERE 
		OE.Id_Edu_Almacen = :Id_Edu_Almacen AND OE.Id_User = :Id_User ";	
		$Where = ["Id_Edu_Almacen" =>$key,"Id_User" =>$User ];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Suscripcion = $Row->Id_Suscripcion;

		
		$Query = "
		SELECT  
		DC.Id_Edu_Evaluacion_Desarrollo_Cab
		, DC.Pregunta_Actual
		, DC.Matriz_Preguntas
		, DC.Estado
		FROM edu_evaluacion_desarrollo_cab DC  
		WHERE 
		DC.Id_Suscripcion = :Id_Suscripcion AND DC.Id_Edu_Objeto_Evaluativo =:Id_Edu_Objeto_Evaluativo
		";	
		$Where = ["Id_Suscripcion" =>$Id_Suscripcion,"Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo ];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Edu_Evaluacion_Desarrollo_Cab = $Row->Id_Edu_Evaluacion_Desarrollo_Cab;
		$Pregunta_Actual = $Row->Pregunta_Actual;
		
        if(empty($Id_Edu_Evaluacion_Desarrollo_Cab)){
			
			$data = array(
			'Pregunta_Actual' => 0,
			'Matriz_Preguntas' => $Matriz_Preguntas,
			'Id_Edu_Objeto_Evaluativo' => $Id_Edu_Objeto_Evaluativo,
			'Id_Suscripcion' => $Id_Suscripcion,
			'Estado' => 'Iniciado',
			'Entity' => $Entity,
			'Id_User_Creation' => $User,
			'Id_User_Update' => $User,
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
		     // var_dump($data);	
			
			$Result_Cab = ClassPDO::DCInsert("edu_evaluacion_desarrollo_cab", $data, $Conection);
			$Id_Edu_Evaluacion_Desarrollo_Cab = $Result_Cab["lastInsertId"];		
					
		}else{
			
			// if($Pregunta_Actual == 0){
				
				// if($Estado_Pregunta == ""){
						// $reg = array(
							// 'Matriz_Preguntas' => $Matriz_Preguntas
						// );
						// $where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
						// $rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection,"");	
						
				// }			
				
				// if($Estado_Pregunta != 0){
					
						// $reg = array(
							// 'Matriz_Preguntas' => $Matriz_Preguntas
						// );
						// $where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
						// $rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection,"");	
						
				// }
			// }
			
		}

		$Query = "
		SELECT  
		DC.Id_Edu_Evaluacion_Desarrollo_Cab
		, DC.Pregunta_Actual
		, DC.Matriz_Preguntas
		, DC.Estado
		FROM edu_evaluacion_desarrollo_cab DC  
		WHERE 
		 DC.Id_Edu_Evaluacion_Desarrollo_Cab =:Id_Edu_Evaluacion_Desarrollo_Cab
		";	
		$Where = ["Id_Edu_Evaluacion_Desarrollo_Cab" =>$Id_Edu_Evaluacion_Desarrollo_Cab ];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		
		return $Row;
						
	}		







    public function ControlInicioEvaluacion($Settings) {
      	global $Conection, $DCTimeHour,$NameTable;
		
		
			$Codigo_Item = $Settings["Id_Edu_Respuesta"];
			$Id_Edu_Pregunta = $Settings["Id_Edu_Pregunta"];
			
			$OrdenP = DCPost("Orden");
	
			$Query = " 
			SELECT Id_Edu_Respuesta, Orden FROM edu_respuesta 
			WHERE 
			Id_Edu_Pregunta = :Id_Edu_Pregunta 
			
			ORDER BY Orden ASC 	
			"; 
			$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta];

			$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			$cont = 0;
			$SesionN = 0;
			$ubicacionB = 0;
			$OrdenBD = "";
							
			foreach ($Registro as $Reg) {		
				$CodigoItemBD = $Reg->Id_Edu_Respuesta;
				
			
				if ($CodigoItemBD == $Codigo_Item) {
					$OrdenBD = $Reg->Orden;
					
					if ($OrdenP < $OrdenBD) {
						
						$SesionN = $OrdenP;
					} else {
						$SesionN = $OrdenP + 1;
					}
					
					
					$ubicacionB = ($SesionN * 100);
					$reg = array(
						'Orden' => $ubicacionB
					);
					$where = array('Id_Edu_Respuesta' => $CodigoItemBD);
					$rg = ClassPDO::DCUpdate('edu_respuesta', $reg , $where, $Conection,"");
					
									
				} else {
					
					$OrdenBD = $Reg->Orden;
					$ubicacionB = ($OrdenBD * 100 + 10);
					$reg = array(
						'Orden' => $ubicacionB
					);
					$where = array('Id_Edu_Respuesta' => $CodigoItemBD);
					$rg = ClassPDO::DCUpdate('edu_respuesta', $reg , $where, $Conection,"");
				}	
				
			}	


				
				$Query = " 
			       SELECT Id_Edu_Respuesta, Orden FROM edu_respuesta  WHERE 
			       Id_Edu_Pregunta = :Id_Edu_Pregunta  
				   ORDER BY Orden ASC
				"; 
				$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta];

			
			$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			$Cont = 0;
			foreach ($Registro as $Reg) {
				
				$Cont += 1;
				$reg = array(
					'Orden' => $Cont
				);
				
				$where = array('Id_Edu_Respuesta' => $Reg->Id_Edu_Respuesta);
				$rg = ClassPDO::DCUpdate('edu_respuesta', $reg , $where, $Conection,"");
				
			}		
					
	}		
		

}