<?php
require_once('./sviews/layout.php');
require_once(dirname(__FILE__).'/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Examen_Foro{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-examen-foro";
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


					case "Edu_Objeto_Evaluativo_Grupo_Crud":
	                        
							$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
							$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
							
                            $Data = array();
							$Data['Id_Edu_Componente'] = $Parm["Id_Edu_Componente"];			
							$Data['Id_Edu_Almacen'] = $Parm["key"];			
							$Data['Id_Edu_Objeto_Evaluativo_Detalle'] = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];			
							// $Data['Id_Edu_Tipo_Objeto_Evaluativo'] = 1;			
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Objeto_Evaluativo_Grupo"],"Id_Edu_Objeto_Evaluativo_Grupo",$Data);  
							$Id_Edu_Objeto_Evaluativo = $Result["lastInsertId"];


							$Settings = array();
							$Settings['Url'] = $UrlFile."/interface/Configura_Grupo/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."";
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJSSP($Settings);
							DCCloseModal();
												
												
						break;						
						
					case "Edu_Crud_Objeto_Evaluativo_Examen_NA":
					
					        $Id_Edu_Tipo_Objeto_Evaluativo =  DCPost("Id_Edu_Tipo_Objeto_Evaluativo");
						
                            $Data = array();
							$Data['Id_Edu_Articulo'] = $Parm["Id_Edu_Articulo"];			
							// $Data['Id_Edu_Tipo_Objeto_Evaluativo'] = 1;			
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Objeto_Evaluativo"],"Id_Edu_Objeto_Evaluativo",$Data);  
							$Id_Edu_Objeto_Evaluativo = $Result["lastInsertId"];
							
							
							$Query = "
							SELECT 
							Count(*)  AS Tot 
							FROM edu_componente EC
							WHERE 
							EC.Id_Edu_Articulo = :Id_Edu_Articulo AND
							EC.Jerarquia_Id_Edu_Componente = :Jerarquia_Id_Edu_Componente
							";	
							$Where = ["Id_Edu_Articulo" =>$Parm["Id_Edu_Articulo"],"Jerarquia_Id_Edu_Componente" => 1];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Tot_Items = $Row->Tot + 1;
							
							if($Id_Edu_Tipo_Objeto_Evaluativo == 2 || $Id_Edu_Tipo_Objeto_Evaluativo == 1 || $Id_Edu_Tipo_Objeto_Evaluativo == 5){
								$Id_Edu_Formato = 7;
							}
							
							if($Id_Edu_Tipo_Objeto_Evaluativo == 3){//Tarea adjunta
								$Id_Edu_Formato = 8;
							}							
							if($Id_Edu_Tipo_Objeto_Evaluativo == 4){ //Foro
								$Id_Edu_Formato = 9;
							}							
														
								
							if(empty($Parm["Id_Edu_Objeto_Evaluativo"])){
								
								$data = array(
								'Id_Edu_Articulo' =>  $Parm["Id_Edu_Articulo"],
								'Id_Edu_Almacen' =>  $Parm["key"],
								'Nombre' =>  DCPost("Nombre"),
								'Id_Edu_Formato' => $Id_Edu_Formato,
								'Jerarquia_Id_Edu_Componente' =>  1,
								'Orden' =>  $Tot_Items,
								'Entity' => $Entity,
								'Id_User_Update' => $User,
								'Id_User_Creation' => $User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
								$Return_B = ClassPDO::DCInsert("edu_componente", $data, $Conection,"");							
								$Id_Edu_Componente = $Return_B["lastInsertId"];
								
								
								$data = array(
								'Id_Edu_Articulo' =>  $Parm["Id_Edu_Articulo"],
								'Id_Edu_Componente' =>  $Id_Edu_Componente,
								'Id_Edu_Objeto_Evaluativo' =>  $Id_Edu_Objeto_Evaluativo,
								'Entity' => $Entity,
								'Id_User_Update' => $User,
								'Id_User_Creation' => $User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
								$Return = ClassPDO::DCInsert("edu_objeto_evaluativo_detalle", $data, $Conection,"");	
								
				            }else{

								$Query = "
								SELECT 
								EC.Id_Edu_Componente
								FROM edu_objeto_evaluativo_detalle EC
								WHERE 
								EC.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo 
								";	
								$Where = ["Id_Edu_Objeto_Evaluativo" =>$Parm["Id_Edu_Objeto_Evaluativo"]];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);
                                $Id_Edu_Componente = $Row->Id_Edu_Componente;
								$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
								
								$reg = array(
								'Nombre' => DCPost("Nombre")
								);
								$where = array('Id_Edu_Componente' => $Id_Edu_Componente);
								$rg = ClassPDO::DCUpdate('edu_componente', $reg , $where, $Conection,"");
							}
							

							
							$Settings = array();
							$Settings['Url'] = $UrlFile."/interface/Configura_Objeto_Evaluativo/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."";
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJS($Settings);
							
													
							DCExit();	
							
						break;	
											
					case "comentario_crud":
					
					
                            $Data = array();
							$Data['Id_Edu_Objeto_Evaluativo_Detalle'] = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];			
							$Data['Id_User_Miembro'] = $User;			
							$Data['Jerarquia'] = 1;	
                            $Id_Comentario_Herencia = $Parm["Id_Comentario_Herencia"];
                            $Id_Comentario = $Parm["Id_Comentario"];
							
                            $SubComentario = $Parm["SubComentario"];
                            $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
							
							if(!empty($Id_Comentario_Herencia)){
								
							    $Data['Id_Comentario_Herencia'] = $Id_Comentario_Herencia;	
                                $Data['Jerarquia'] = 2;									
							}
							
							$Result = DCSave($Obj,$Conection,$Parm["Id_Comentario"],"Id_Comentario",$Data);  
							// $Id_Edu_Objeto_Evaluativo = $Result["lastInsertId"];
					
							$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
							$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
							$Id_Comentario_Principal = $Parm["Id_Comentario_Principal"];
							$Id_Edu_Objeto_Evaluativo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];
							$Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];
							
							
	                        $Settings = array();
							
							
							if(!empty($Id_Comentario_Principal)){

										$Settings['Url'] = $UrlFile."/interface/List_Comentario/key/".$Parm["key"]."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Comentario/".$Id_Comentario."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Id_Edu_Evaluacion_Desarrollo_Cab."/Id_Edu_Objeto_Evaluativo_Detalle/".$Id_Edu_Objeto_Evaluativo_Detalle;								
										// $Settings['Url'] = $UrlFile."/interface/List_Comentario/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Comentario/".$Id_Comentario;								
										$Settings['Screen'] = "Comentario_".$Id_Comentario;
										$Settings['Type_Send'] = "HXM";							
							}else{
								
							
									if(!empty($Id_Comentario_Herencia)){
										
			
										$Settings['Url'] = $UrlFile."/interface/ListB/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Comentario_Herencia/".$Id_Comentario_Herencia."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Comentario/".$Id_Comentario;							
										$Settings['Screen'] = "Comentario_Det_".$Id_Comentario_Herencia;
										$Settings['Type_Send'] = "HXM";
										
									}else{
										
										
										$Settings['Url'] = $UrlFile."/interface/List/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."";							
										$Settings['Screen'] = "PanelA";
										$Settings['Type_Send'] = "HXM";
									
									}	
							}		
									
							DCRedirectJSSP($Settings);	
							DCExit();	
                          
							
						break;	
						
											
					
					case "comentario_crud_Configura":
					case "Edu_Objeto_Evaluativo_Tarea_Crud":
					
                            $Data = array();
							$Data['Id_Edu_Articulo'] = $Parm["Id_Edu_Articulo"];			
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Objeto_Evaluativo"],"Id_Edu_Objeto_Evaluativo",$Data);  
							
							$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
							$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
							$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
							
	                        $Settings = array();
							$Settings['Url'] = $UrlFile."/interface/Configura_Objeto_Evaluativo/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Articulo/".$Id_Edu_Articulo;
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJS($Settings);	
							
							DCExit();	
						break;			
						
						
					case "Edu_Objeto_Evaluativo_Foro_Activa":
					
                            $Data = array();
							$Data['Id_Edu_Articulo'] = $Parm["Id_Edu_Articulo"];			
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Objeto_Evaluativo"],"Id_Edu_Objeto_Evaluativo",$Data);  
							
							$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
							$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
							$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
							
	                        $Settings = array();
							$Settings['Url'] = $UrlFile."/interface/Configura_Objeto_Evaluativo_Estado/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Articulo/".$Id_Edu_Articulo;
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJS($Settings);	
							
							DCExit();	
						break;							
						
						
						case "Edu_Evaluacion_Desarrollo_Det_Tarea":
					
                            $Data = array();
							$Data['Id_Edu_Evaluacion_Desarrollo_Cab'] = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];			
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Evaluacion_Desarrollo_Det"],"Id_Edu_Evaluacion_Desarrollo_Det",$Data);  
							
							$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
							$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
							$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
							$Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];
							
	                        $Settings = array();
							$Settings['Url'] = $UrlFile."/interface/Finalizar_Desarrollo_Evaluacion/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Id_Edu_Evaluacion_Desarrollo_Cab;
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJS($Settings);	
							
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

            case "List":
			
				DCCloseModal();
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];


				$Query = "
				SELECT 
				OE.Id_Suscripcion
				, OE.Id_Edu_Almacen
				, OE.Id_User
				FROM suscripcion OE 
				WHERE 
				OE.Id_Edu_Almacen = :Id_Edu_Almacen AND OE.Id_User = :Id_User ";	
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen,"Id_User" =>$User ];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Suscripcion = $Row->Id_Suscripcion;

 
				$Query = "
						SELECT 
						EC.Id_Edu_Tipo_Desarrollo, 
						EC.Descripcion, 
						EED.Id_Edu_Objeto_Evaluativo_Detalle, 
						EC.Nombre 
						FROM edu_objeto_evaluativo EC
						INNER JOIN edu_objeto_evaluativo_detalle EED ON EC.Id_Edu_Objeto_Evaluativo = EED.Id_Edu_Objeto_Evaluativo
						WHERE 
						EC.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
				";	
				$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre_Objeto_Evaluativo = $Row->Nombre;	
				$Id_Edu_Objeto_Evaluativo_Detalle = $Row->Id_Edu_Objeto_Evaluativo_Detalle;	
				$Descripcion_Objeto_Evaluativo = $Row->Descripcion;	

 
				$Query = "
						SELECT 
						EEDC.Id_Edu_Objeto_Evaluativo,
						EEDC.Id_Edu_Evaluacion_Desarrollo_Det,
						EEDC.Id_Suscripcion
						FROM edu_evaluacion_desarrollo_det EEDD
						INNER JOIN edu_evaluacion_desarrollo_cab EEDC 
						ON EEDD.Id_Edu_Evaluacion_Desarrollo_Cab = EEDC.Id_Edu_Evaluacion_Desarrollo_Cab
						WHERE 
						EEDC.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo AND
						EEDC.Id_Suscripcion = :Id_Suscripcion
						
				";	
				$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo, "Id_Suscripcion"=>$Id_Suscripcion];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Evaluacion_Desarrollo_Det = $Row->Id_Edu_Evaluacion_Desarrollo_Det;	
			
			

				$Query = "
				SELECT 
				OED.Id_Edu_Objeto_Evaluativo_Grupo_Detalle,
				OE.Nombre,
				OED.Id_Edu_Objeto_Evaluativo_Grupo
				FROM edu_objeto_evaluativo_grupo OE
                INNER JOIN edu_objeto_evaluativo_grupo_detalle OED ON OE.Id_Edu_Objeto_Evaluativo_Grupo = OED.Id_Edu_Objeto_Evaluativo_Grupo				
				WHERE 
				OE.Id_Edu_Almacen = :Id_Edu_Almacen 
				AND OED.Id_Suscripcion = :Id_Suscripcion 
				AND OE.Id_Edu_Componente = :Id_Edu_Componente 
				AND OE.Id_Edu_Objeto_Evaluativo_Detalle = :Id_Edu_Objeto_Evaluativo_Detalle 

				";	
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen,"Id_Edu_Componente" =>$Id_Edu_Componente_S, "Id_Edu_Objeto_Evaluativo_Detalle"=>$Id_Edu_Objeto_Evaluativo_Detalle,"Id_Suscripcion"=>$Id_Suscripcion];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo_Grupo_Detalle = $Row->Id_Edu_Objeto_Evaluativo_Grupo_Detalle;
				$Id_Edu_Objeto_Evaluativo_Grupo = $Row->Id_Edu_Objeto_Evaluativo_Grupo;
				$Nombre_Grupo = $Row->Nombre;
				
				
				$Reg = $this->Inicia_Evaluacion($Parm);	
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Reg->Id_Edu_Evaluacion_Desarrollo_Cab;
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;				
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Id_Edu_Evaluacion_Desarrollo_Cab."/Id_Edu_Objeto_Evaluativo_Detalle/".$Id_Edu_Objeto_Evaluativo_Detalle."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Grupo/".$Id_Edu_Objeto_Evaluativo_Grupo;	

			
				$DCPanelTitle = DCPanelTitle($Nombre_Objeto_Evaluativo." ",$Descripcion_Objeto_Evaluativo,$btn);
				
				$btn = " INGRESA AQUÍ TU COMENTARIO  ]" .$UrlFile."/interface/Comenta".$urlLinkB."]animatedModal5]HXM]]Boton_Comentario}";
				$DCPanelTitle_B = DCButton($btn, 'botones1', 'sys_form_Comentario');	


                $btn = " <i class='zmdi zmdi zmdi-accounts'></i> ]" .$UrlFile."/interface/List_Participantes_Rp".$urlLinkB."]animatedModal5]HXM]]Boton_Comentario_Actualizar}";		
                $btn .= " <i class='zmdi zmdi-refresh-alt'></i> ]" .$UrlFile."/interface/List".$urlLinkB."]PanelA]HXM]]Boton_Comentario_Actualizar}";		
				$btn = DCButton($btn, 'botones1', 'sys_form_Actualizar');                
				
				$DCPanelTitle_B2 = DCPanelTitleB("Comentarios", $Nombre_Grupo ,$btn,"");
				
				$Form1 = "";
				$Form1_B = "<div style='background-color:#fff;'> ".$DCPanelTitle."</div>";
				$Form1_B .= "<div style='background-color:#fff;'> ".$DCPanelTitle_B. $DCPanelTitle_B2."  </div>";
				
				$Parm["Id_Edu_Objeto_Evaluativo_Detalle"] = $Id_Edu_Objeto_Evaluativo_Detalle;
				$Parm["Id_Edu_Evaluacion_Desarrollo_Cab"] = $Id_Edu_Evaluacion_Desarrollo_Cab;
				$Parm["Id_Edu_Articulo"] = $Id_Edu_Articulo;
				
				$Form1_B .= $this->List_Comentario($Parm);
				
				
				if($Parm["request"] == "on"){
					
					$LayoutB  = new LayoutB();
		            $UrlFile = "/sadministrator/edu-articulo-det";					
					
					$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
					$Nombre_Articulo = $Row_Producto->Nombre;	

					$Perfil = Biblioteca::Valida_Perfil("");
					if($Perfil == 1 || $Perfil == 2){		
					
						$UrlFile_Edu_Blog = "/sadministrator/edu-blog";
						$UrlFile_Edu_Participantes = "/sadministrator/edu-participante";
						$UrlFile_Edu_Examen = "/sadministrator/edu-examen";
						$UrlFile_Edu_Reporte = "/sadministrator/edu-reportes";
		
						
						$listMn = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Contenido [".$UrlFile."/interface/Create_Conten/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
						$listMn .= "<i class='zmdi zmdi-folder-outline zmdi-hc-fw'></i> Sub Carpeta [".$UrlFile."/interface/Create_Conten_SubItem/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
						$listMn .= "<i class='zmdi zmdi-file zmdi-hc-fw'></i> Documento [".$UrlFile."/interface/Create_Conten_SubItemB_Dcoc/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
						$listMn .= "<i class='zmdi zmdi-collection-video zmdi-hc-fw'></i> Vídeo Embebido Youtube[".$UrlFile."/interface/Create_Conten_SubItem_Embebido/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";					
						$listMn .= "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Artículo [".$UrlFile."/interface/Create_Conten_SubItem_Articulo_B/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[PanelA[HXMS[{";						
						$listMn .= "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Evaluación [".$UrlFile_Edu_Examen."/interface/Crea_Objeto_Evaluativo/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[PanelA[HXMS[{";						
						$listMn .= "<i class='zmdi zmdi-edit zmdi-hc-fw'></i> Editar [".$UrlFile_Articulo.$Redirect."/interface/Create/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
						
						// $listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar Objeto [".$UrlFile."/interface/delete_objeto/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
						$listMn .= " Genear Url Amigable [".$UrlFile_Edu_Blog."/interface/Generar_Url_Amigable/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
						$listMn .= " Inscripción de Participantes [".$UrlFile_Edu_Participantes."/interface/List/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
						$btn = "<i class='zmdi zmdi-menu zmdi-hc-fw'></i> Opciones]SubMenu]{$listMn}]OPCIONES]]btn-simple-c}";

						$listMn = " Concurrencia [".$UrlFile."/interface/Analisis_Concurrencia/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
						$listMn .= " Asistencia por día [".$UrlFile_Edu_Reporte."/interface/Ingreso_Aula/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
						$listMn .= " Resultados <br> Evaluación [".$UrlFile_Edu_Reporte."/interface/Resultados_Evaluacion/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
						$btn .= "<i class='zmdi zmdi-chart zmdi-hc-fw'></i> Análisis ]SubMenu]{$listMn}]OPCIONES]]btn-simple-c}";
						$btnA = DCButton($btn, 'botones1', 'sys_form_principal');

					}
					
					$Query = "
						SELECT 
						SC.Id_Suscripcion
						, SC.Id_User 
						FROM suscripcion SC
						WHERE 
						SC.Id_Edu_Almacen = :Id_Edu_Almacen AND SC.Id_User = :Id_User 
					";	
					$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Id_User" =>$User];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Id_Suscripcion = $Row->Id_Suscripcion;	
				
				    $DCPanelTitle = DCPanelTitle($Nombre_Articulo," <span id=timer_text> </span> <input type=hidden id=timer> ",$btnA,"");
									
					// $btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXM]]btn-simple-b}";
					// $btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				
							
					// if(!empty($User)){
						// $btn = "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat <div id='alerta_chat'> </div>]" .$UrlFile."/interface/chat/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]elementos_cart]HXM]]btn-simple}";
						// $btn_chat = DCButton($btn, 'botones1', 'sys_formB'.$Count);
					// }
					
					$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]]]btn-simple-b}";
					$btn .= "<i class='zmdi zmdi-wrench'></i> Herramientas ]" .$UrlFile."/interface/Herramientas/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]]]btn-simple-d}";
					if(!empty($User)){				
						  $btn .= "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat <div id='alerta_chat'> </div>]" .$UrlFile."/interface/chat/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXM]]btn-simple-d}";
					}
					$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);						
					
					
					$PanelB = '
						<div class="messenger">
						  <div class="m-left-toolbar" >
							<div id="evaluador_indicador_chat" valor="si"></div>
							<ul style="text-align:left;">
							  <li style="padding-top:12px;">
								 '.$btn_componentes.'
							  </li>	
							   <li style="padding-top:12px;position:relative;">
								 '.$btn_chat.'
							  </li>			
							</ul>
						  </div>				
					  </div>	';

					$PanelB .= Edu_Articulo_Det::Contenidos_Item($Id_Edu_Almacen,$UrlFile_Edu_Articulo_Det,$Id_Edu_Componente_S,$Id_Suscripcion,"",$Id_Edu_Tipo_Privacidad);
					$Layout = array(array("PanelA","col-md-8",$Form1_B),array("PanelB","col-md-4",$Chat . $Imagen_Banner.$DCSlidePlugin.$PanelB));
					$Content = DCLayout($Layout);	
				    $Contenido = DCPageB($DCPanelTitle, $Content . $Chat . $jsMontor ,"panel panel-default");
					
					$Style = '
						<style>
							.col-md-8 {
								padding: 0px;
							}
							.col-md-4 {
								padding: 0px;
							}
							.botones1{
							    font-size: 13px;
							}
							.botones1 a {
								margin-top: 0px; color: #fff;
							}		
							image i {
								font-size: 2em;
							}							
						</style>
					';					
						
					$Script = '<script>
					
						
							start_monitor_foro('.$Tiempo_Duracion_OE.','.$Id_Edu_Evaluacion_Desarrollo_Cab.','.$Tiempo_Estado.','.$Id_Edu_Componente_S.','.$Id_Edu_Almacen.');
							
							
					';
					$Script .= "</script>";
					
					DCWrite($LayoutB->main($Contenido . $Style,""));
					
				}else{
					DCWrite($Form1_B);		
				}				
			
                break;
				
            case "Crea_Objeto_Evaluativo":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];

				$Query = "
						SELECT 
						EC.Id_Edu_Objeto_Evaluativo 
						FROM edu_objeto_evaluativo_detalle EC
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo = $Row->Id_Edu_Objeto_Evaluativo;	
				
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
						, OE.Estado
						, OTD.Nombre AS Tipo_Desarrollo
						, OTD.Id_Edu_Tipo_Desarrollo
						FROM edu_objeto_evaluativo_detalle EC
						INNER JOIN edu_objeto_evaluativo OE ON EC.Id_Edu_Objeto_Evaluativo = OE.Id_Edu_Objeto_Evaluativo
						INNER JOIN edu_tipo_desarrollo OTD ON OE.Id_Edu_Tipo_Desarrollo = OTD.Id_Edu_Tipo_Desarrollo		
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
						
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Tipo_Objeto_Evaluativo = $Row->Id_Edu_Tipo_Objeto_Evaluativo;
      
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;	
				
				if(!empty($Id_Edu_Objeto_Evaluativo)){
					
					$Pestanas = SetttingsSite::Tabs_Objeto_Evaluativo_Foro(array(
					 "".$urlLinkB."]Marca"
					,"".$urlLinkB."]"
					,"".$urlLinkB."]","".$urlLinkB."]",""));	
					
				}				
					
				
				$DCPanelTitle = DCPanelTitle("OBJETO EVALUATIVO","Examen con Nota Automática",$btn);
				
 
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Crud_Objeto_Evaluativo_Examen_NA/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Area_Conocimiento/".$Id_Edu_Area_Conocimiento;
				
				if(!empty($Id_Edu_Objeto_Evaluativo)){
				    $Name_Interface = "Editar Area de Conocimiento";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    // $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"Mensaje_Id","Form","Edu_Crud_Objeto_Evaluativo_Examen_NA","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Area de Conocimiento";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Objeto_Evaluativo"," SELECT Id_Edu_Tipo_Objeto_Evaluativo AS Id, Nombre AS Name FROM edu_tipo_objeto_evaluativo ",[])
				     ,array("Id_Edu_Tipo_Desarrollo"," SELECT Id_Edu_Tipo_Desarrollo AS Id, Nombre AS Name FROM edu_tipo_desarrollo ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","Edu_Crud_Objeto_Evaluativo_Examen_NA"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Crud_Objeto_Evaluativo_Examen_NA",$Class,$Id_Edu_Objeto_Evaluativo,$PathImage,$Combobox,$Buttons,"Id_Edu_Objeto_Evaluativo");
				
				$Form1 = "<div style='background-color:#fff;'>".$DCPanelTitle . $Form1."</div>";
                DCWrite($Pestanas . $Form1);
                DCExit();
                break;	


				
            case "Configura_Grupo":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];

				$Query = "
						SELECT 
						EC.Id_Edu_Objeto_Evaluativo 
						FROM edu_objeto_evaluativo_detalle EC
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo = $Row->Id_Edu_Objeto_Evaluativo;	
				
				$Query = "
					    
						SELECT 
						EC.Id_Edu_Objeto_Evaluativo
						, EC.Id_Edu_Objeto_Evaluativo_Detalle
						, OE.Nombre
						, OE.Id_Edu_Tipo_Objeto_Evaluativo
						, OE.Hora_Inicio
						, OE.Hora_Fin
						, OE.Fecha_Inicio
						, OE.Tiempo_Duracion
						, OE.Fecha_Fin
						, OE.Estado
						, OTD.Nombre AS Tipo_Desarrollo
						, OTD.Id_Edu_Tipo_Desarrollo
						FROM edu_objeto_evaluativo_detalle EC
						INNER JOIN edu_objeto_evaluativo OE ON EC.Id_Edu_Objeto_Evaluativo = OE.Id_Edu_Objeto_Evaluativo
						INNER JOIN edu_tipo_desarrollo OTD ON OE.Id_Edu_Tipo_Desarrollo = OTD.Id_Edu_Tipo_Desarrollo		
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
						
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Tipo_Objeto_Evaluativo = $Row->Id_Edu_Tipo_Objeto_Evaluativo;
				$Id_Edu_Objeto_Evaluativo_Detalle = $Row->Id_Edu_Objeto_Evaluativo_Detalle;
      
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;	
				
				if(!empty($Id_Edu_Objeto_Evaluativo)){
					
					$Pestanas = SetttingsSite::Tabs_Objeto_Evaluativo_Foro(array(
					 "".$urlLinkB."]"
					,"".$urlLinkB."]"
					,"".$urlLinkB."]Marca","".$urlLinkB."]",""));	
					
				}		
				
				
				$btn = " Crear Grupo ]" .$UrlFile ."/interface/Crear_Grupo".$urlLinkB."]animatedModal5]HXM]]btn btn-default dropdown-toggle]}";				
				$Button = DCButton($btn, 'botones1', 'sys_comentario_'.$Id_Comentario);	
				
				$Query = "
				
				    SELECT Count(*) AS Tot_Reg
					FROM suscripcion SC
					INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
					WHERE SC.Id_Edu_Almacen = :Id_Edu_Almacen 
					
				";    
				$Where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];				
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Tot_Reg = $Row->Tot_Reg;					
				
				$Query = "
				
				    SELECT COUNT(*) AS Tot_Reg_B
					FROM suscripcion SC
					INNER JOIN user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
					INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
					LEFT JOIN edu_objeto_evaluativo_grupo_detalle EOEGD ON ( EOEGD.Id_Suscripcion = SC.Id_Suscripcion AND EOEGD.Id_Edu_Objeto_Evaluativo = '".$Id_Edu_Objeto_Evaluativo."' )
					WHERE SC.Id_Edu_Almacen = :Id_Edu_Almacen 
					AND EOEGD.Id_Suscripcion is null
					
				";    
				$Where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Tot_Reg_B = $Row->Tot_Reg_B;
				
				$DCPanelTitle = DCPanelTitle("GESTIÓN DE GRUPOS",$Tot_Reg." participantes ",$Button);
				
				$Query = "
				    SELECT PB.Id_Edu_Objeto_Evaluativo_Grupo, PB.Nombre
					FROM edu_objeto_evaluativo_grupo PB
					WHERE PB.Id_Edu_Objeto_Evaluativo_Detalle = :Id_Edu_Objeto_Evaluativo_Detalle
					ORDER BY PB.Orden DESC
				  
				";    
				$Where = ["Id_Edu_Objeto_Evaluativo_Detalle"=>$Id_Edu_Objeto_Evaluativo_Detalle];
				$Rows = ClassPdo::DCRows($Query,$Where,$Conection);

                if($Tot_Reg_B !== 0){
				    $PanelB = '<div style="padding: 10px 20px 10px 20px;background: #a9e8a9;"> Hay participantes sin grupo ('.$Tot_Reg_B.')</div>';
				}
				$PanelB .= '
				
				<div class="cart"   id="elementos_cart" >		
				
				    <table class="table">
                    <tr>	
                        <th width="80%">Grupo</th>					
                  				
                        <th>Acción</th>					
                    </tr>					
					';	
					
						foreach($Rows AS $Field){
							
							$listMn = "  Añadir Participantes [".$UrlFile."/interface/List_Participantes_Curso".$urlLinkB."/Id_Edu_Objeto_Evaluativo_Grupo/".$Field->Id_Edu_Objeto_Evaluativo_Grupo."[animatedModal5[HXM[{";	
							$listMn .= " Ver Participantes [".$UrlFile."/interface/List_Participantes".$urlLinkB."/Id_Edu_Objeto_Evaluativo_Grupo/".$Field->Id_Edu_Objeto_Evaluativo_Grupo."[animatedModal5[HXM[{";	
							$listMn .= " Editar Grupo [".$UrlFile."/interface/Crear_Grupo".$urlLinkB."/Id_Edu_Objeto_Evaluativo_Grupo/".$Field->Id_Edu_Objeto_Evaluativo_Grupo."[animatedModal5[HXM[{";	
							$listMn .= " Eliminar Grupo [".$UrlFile."/interface/Confirmar_Delete".$urlLinkB."/Id_Edu_Objeto_Evaluativo_Grupo/".$Field->Id_Edu_Objeto_Evaluativo_Grupo."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Objeto_Evaluativo_Grupo);		


							$Query = "
							
					            SELECT  COUNT(*) AS CONT   FROM edu_objeto_evaluativo_grupo_detalle 
								WHERE    
								Id_Edu_Objeto_Evaluativo_Grupo = :Id_Edu_Objeto_Evaluativo_Grupo AND
								Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo 

								;
									
							";	
							$Where = [
							"Id_Edu_Objeto_Evaluativo_Grupo" =>$Field->Id_Edu_Objeto_Evaluativo_Grupo,
							"Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo
							];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$CONT = $Row->CONT;	
							

							$PanelB .= '
								<tr>	
									<td width="80%">'.$Field->Nombre.' (Nro. Participantes: '.$CONT.')</td>					
											
									<td>'.$btnB.'</td>					
								</tr>					
								';	
												
						}
				
				$PanelB .= '
				    </table>
				</div>
						';					
				
		
				$Form1 = "<div style='background-color:#fff;'>".$DCPanelTitle . $PanelB."</div>";
                DCWrite($Pestanas . $Form1);
                DCExit();
                break;	


            case "List_Participantes":

				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Objeto_Evaluativo_Grupo = $Parm["Id_Edu_Objeto_Evaluativo_Grupo"];

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Grupo/".$Id_Edu_Objeto_Evaluativo_Grupo;	
				
				$Query = "
				
						SELECT 
						EG.Id_Edu_Objeto_Evaluativo_Grupo
						, EG.Nombre
						FROM edu_objeto_evaluativo_grupo EG
						
						WHERE 
						EG.Id_Edu_Objeto_Evaluativo_Grupo = :Id_Edu_Objeto_Evaluativo_Grupo
						
				";	
				$Where = ["Id_Edu_Objeto_Evaluativo_Grupo" =>$Id_Edu_Objeto_Evaluativo_Grupo];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre_Grupo = $Row->Nombre;				
				
				
				$Name_Interface = "PARTICIPANTE DEL GRUPO";	
				
				$btn .= "Agregar Participantes]" .$UrlFile."/interface/List_Participantes_Curso".$urlLinkB."]animatedModal5]HXMS]btn btn-primary ladda-button}";
                $btn .= " Eliminar ]" .$UrlFile."/interface/Confirmar_Delete_Masive".$urlLinkB."]Msj_Alert]HXMS]]btn btn-success}";
								
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle($Nombre_Grupo,"",$btn);
				
				$Query = "
				    SELECT PB.Id_Edu_Objeto_Evaluativo_Grupo_Detalle AS CodigoLink
					, UM.Nombre
					, PB.Date_Time_Creation 
					FROM edu_objeto_evaluativo_grupo_detalle PB
					INNER JOIN suscripcion SUS ON PB.Id_Suscripcion = SUS.Id_Suscripcion
					INNER JOIN  user_miembro UM ON SUS.Id_User = UM.Id_User_Miembro		
					WHERE 
					PB.Id_Edu_Objeto_Evaluativo_Grupo = :Id_Edu_Objeto_Evaluativo_Grupo AND
					PB.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo 
				";    
			
				$Class = 'table table-hover';
				$LinkId = '';
				$Link = $UrlFile."/interface/Create";
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Objeto_Evaluativo_Grupo"=>$Id_Edu_Objeto_Evaluativo_Grupo, "Id_Edu_Objeto_Evaluativo" => $Id_Edu_Objeto_Evaluativo ];
					var_dump($where);
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','PS');				
                
				$Div_Msj = "<div id='Msj_Alert' style='width:100%;'></div>";
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Div_Msj . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;


            case "List_Participantes_Rp":

				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Objeto_Evaluativo_Grupo = $Parm["Id_Edu_Objeto_Evaluativo_Grupo"];

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Grupo/".$Id_Edu_Objeto_Evaluativo_Grupo;	
				
				$Query = "
				
						SELECT 
						EG.Id_Edu_Objeto_Evaluativo_Grupo
						, EG.Nombre
						FROM edu_objeto_evaluativo_grupo EG
						
						WHERE 
						EG.Id_Edu_Objeto_Evaluativo_Grupo = :Id_Edu_Objeto_Evaluativo_Grupo
						
				";	
				$Where = ["Id_Edu_Objeto_Evaluativo_Grupo" =>$Id_Edu_Objeto_Evaluativo_Grupo];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre_Grupo = $Row->Nombre;				
				
				
				$Name_Interface = "PARTICIPANTE DEL GRUPO";	
				$btn = "";
				// $btn .= "Agregar Participantes]" .$UrlFile."/interface/List_Participantes_Curso".$urlLinkB."]animatedModal5]HXMS]btn btn-primary ladda-button}";
                // $btn .= " Eliminar ]" .$UrlFile."/interface/Confirmar_Delete_Masive".$urlLinkB."]Msj_Alert]HXMS]]btn btn-success}";
								
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle($Nombre_Grupo,"",$btn);
				
				$Query = "
				    SELECT PB.Id_Edu_Objeto_Evaluativo_Grupo_Detalle AS CodigoLink
					, UM.Nombre
					, PB.Date_Time_Creation 
					FROM edu_objeto_evaluativo_grupo_detalle PB
					INNER JOIN suscripcion SUS ON PB.Id_Suscripcion = SUS.Id_Suscripcion
					INNER JOIN  user_miembro UM ON SUS.Id_User = UM.Id_User_Miembro		
					WHERE PB.Id_Edu_Objeto_Evaluativo_Grupo = :Id_Edu_Objeto_Evaluativo_Grupo
				";    
				$Class = 'table table-hover';
				$LinkId = '';
				$Link = $UrlFile."/interface/Create";
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Objeto_Evaluativo_Grupo"=>$Id_Edu_Objeto_Evaluativo_Grupo];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				
                
				$Div_Msj = "<div id='Msj_Alert' style='width:100%;'></div>";
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Div_Msj . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;



            case "List_Participantes_Curso":

				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Objeto_Evaluativo_Grupo = $Parm["Id_Edu_Objeto_Evaluativo_Grupo"];

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Grupo/".$Id_Edu_Objeto_Evaluativo_Grupo;	
				
				
				$Name_Interface = "PARTICIPANTES DEL CURSO";	
				
				$btn = " Atrás ]" .$UrlFile."/interface/List_Participantes".$urlLinkB."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= " Agregar ]" .$UrlFile."/interface/Confirmar_Insert".$urlLinkB."]Msj_Alert]HXMS]]btn btn-success}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("Selecciona y añade participantes al grupo","",$btn);
				
				$Query = "
				
				    SELECT UM.Nombre, SC.Id_Suscripcion AS CodigoLink, US.Usuario_Login,  SC.Estado, UM.Date_Time_Creation
					FROM suscripcion SC
					INNER JOIN user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
					INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
					LEFT JOIN edu_objeto_evaluativo_grupo_detalle EOEGD ON ( EOEGD.Id_Suscripcion = SC.Id_Suscripcion AND EOEGD.Id_Edu_Objeto_Evaluativo = '".$Id_Edu_Objeto_Evaluativo."' )
					WHERE 
					SC.Id_Edu_Almacen = :Id_Edu_Almacen 
					AND EOEGD.Id_Suscripcion is null
					
				";    
				$Class = 'table table-hover';
				$LinkId = '';
				$Link = $UrlFile."/interface/Create_Edit/key/".$Id_Edu_Almacen."";
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','PS');				

                $Div_Msj = "<div id='Msj_Alert' style='width:100%;'></div>";
			    $Html = DCModalFormB($Name_Interface,$DCPanelTitle . $Div_Msj . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;

            case "Confirmar_Insert":
			
			// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[FORM[warehouse{";
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Objeto_Evaluativo_Grupo = $Parm["Id_Edu_Objeto_Evaluativo_Grupo"];

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Grupo/".$Id_Edu_Objeto_Evaluativo_Grupo;	
								
				$btn = "Confirmar ]" .$UrlFile ."/interface/Insert_Participantes".$urlLinkB."]animatedModal5]FORM]warehouse]btn btn-default dropdown-toggle]}";				
				$btn .= "Cancelar ]" .$UrlFile ."/interface/List_Participantes_Curso".$urlLinkB."]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form_Confirmar');					
				
			    $Html = DCModalFormMsj("Agregar más participantes",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;						
				

            case "Insert_Participantes":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Objeto_Evaluativo_Grupo = $Parm["Id_Edu_Objeto_Evaluativo_Grupo"];

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Grupo/".$Id_Edu_Objeto_Evaluativo_Grupo;	
				
	
				$Id_Object = DCPost("ky");
				$columnas='';
				// var_dump($Id_Object);
				for ($j = 0; $j < count($Id_Object); $j++) {
					
					$data = array(
					'Id_User_Creation' => $User,
					'Id_User_Update' => $User,
					'Entity' => $Entity,
					'Id_Edu_Objeto_Evaluativo_Grupo' => $Id_Edu_Objeto_Evaluativo_Grupo,
					'Id_Edu_Objeto_Evaluativo' => $Id_Edu_Objeto_Evaluativo,
					'Id_Suscripcion' => $Id_Object[$j],
					'Date_Time_Creation' => $DCTimeHour,
					'Date_Time_Update' => $DCTimeHour
					);
					$ResultB = ClassPDO::DCInsert("edu_objeto_evaluativo_grupo_detalle", $data, $Conection);	
			
					
				}

				$Settings["key"] = $Id_Edu_Almacen;
				$Settings["Id_Edu_Componente"] = $Id_Edu_Componente_S;
				$Settings["Id_Edu_Objeto_Evaluativo"] = $Id_Edu_Objeto_Evaluativo;
				$Settings["Id_Edu_Objeto_Evaluativo_Grupo"] = $Id_Edu_Objeto_Evaluativo_Grupo;
				$Settings["interface"] = "List_Participantes";
				new Edu_Examen_Foro($Settings);


                DCExit();
				
                break;



            case "Confirmar_Delete_Masive":
			
			// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[FORM[warehouse{";
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Objeto_Evaluativo_Grupo = $Parm["Id_Edu_Objeto_Evaluativo_Grupo"];
				$Id_Edu_Objeto_Evaluativo_Grupo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Grupo_Detalle"];

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Grupo/".$Id_Edu_Objeto_Evaluativo_Grupo."/Id_Edu_Objeto_Evaluativo_Grupo_Detalle/".$Id_Edu_Objeto_Evaluativo_Grupo_Detalle;	
								
				$btn = "Confirmar ]" .$UrlFile ."/interface/Confirmar_Delete_Masive_R".$urlLinkB."]animatedModal5]FORM]warehouse]btn btn-default dropdown-toggle]}";				
				$btn .= "Cancelar ]" .$UrlFile ."/interface/List_Participantes".$urlLinkB."]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form_Confirmar');					
				
			    $Html = DCModalFormMsj("Agregar más participantes",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;					

            case "Confirmar_Delete_Masive_R":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Objeto_Evaluativo_Grupo = $Parm["Id_Edu_Objeto_Evaluativo_Grupo"];
				$Id_Edu_Objeto_Evaluativo_Grupo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Grupo_Detalle"];

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Grupo/".$Id_Edu_Objeto_Evaluativo_Grupo;	
				
	
				$Id_Object = DCPost("ky");
				$columnas='';
				for ($j = 0; $j < count($Id_Object); $j++) {

					$where = array('Id_Edu_Objeto_Evaluativo_Grupo_Detalle' =>$Id_Object[$j]);
					$rg = ClassPDO::DCDelete('edu_objeto_evaluativo_grupo_detalle', $where, $Conection);		

				}

				$Settings["key"] = $Id_Edu_Almacen;
				$Settings["Id_Edu_Componente"] = $Id_Edu_Componente_S;
				$Settings["Id_Edu_Objeto_Evaluativo"] = $Id_Edu_Objeto_Evaluativo;
				$Settings["Id_Edu_Objeto_Evaluativo_Grupo"] = $Id_Edu_Objeto_Evaluativo_Grupo;
				$Settings["interface"] = "List_Participantes";
				new Edu_Examen_Foro($Settings);


                DCExit();
				
                break;
				

            case "Confirmar_Delete":
			
			// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[FORM[warehouse{";
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Objeto_Evaluativo_Grupo = $Parm["Id_Edu_Objeto_Evaluativo_Grupo"];

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Grupo/".$Id_Edu_Objeto_Evaluativo_Grupo;	
								
				$btn = "Confirmar ]" .$UrlFile ."/interface/Confirmar_Delete_R".$urlLinkB."]PanelA]HXM]participantes]btn btn-default dropdown-toggle]}";				
				$btn .= "Cancelar ]" .$UrlFile ."/interface/List_Participantes_Curso".$urlLinkB."]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form_Confirmar');					
				
			    $Html = DCModalFormMsj("Está seguro que desea borrar el registro",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;						

            case "Confirmar_Delete_R":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Objeto_Evaluativo_Grupo = $Parm["Id_Edu_Objeto_Evaluativo_Grupo"];

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Grupo/".$Id_Edu_Objeto_Evaluativo_Grupo;	
		
		
		
				$where = array('Id_Edu_Objeto_Evaluativo_Grupo' =>$Id_Edu_Objeto_Evaluativo_Grupo);
				$rg = ClassPDO::DCDelete('edu_objeto_evaluativo_grupo_detalle', $where, $Conection);		
		
				$where = array('Id_Edu_Objeto_Evaluativo_Grupo' =>$Id_Edu_Objeto_Evaluativo_Grupo);
				$rg = ClassPDO::DCDelete('edu_objeto_evaluativo_grupo', $where, $Conection);
				
				
				
				DCCloseModal();
					
				DCWrite(Message("Process executed correctly","C"));
				
				
				$Settings["key"] = $Id_Edu_Almacen;
				$Settings["Id_Edu_Componente"] = $Id_Edu_Componente_S;
				$Settings["Id_Edu_Objeto_Evaluativo"] = $Id_Edu_Objeto_Evaluativo;
				$Settings["Id_Edu_Objeto_Evaluativo_Grupo"] = $Id_Edu_Objeto_Evaluativo_Grupo;
				$Settings["interface"] = "Configura_Grupo";
				new Edu_Examen_Foro($Settings);


                DCExit();
				
                break;						


            case "Crear_Grupo":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Comentario = $Parm["Id_Comentario"];
				// $Id_Edu_Objeto_Evaluativo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Objeto_Evaluativo_Grupo = $Parm["Id_Edu_Objeto_Evaluativo_Grupo"];

				$Query = "
						SELECT 
						EC.Id_Edu_Objeto_Evaluativo 
						FROM edu_objeto_evaluativo_detalle EC
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo = $Row->Id_Edu_Objeto_Evaluativo;	
				
				$Query = "
					    
						SELECT 
						EC.Id_Edu_Objeto_Evaluativo
						, EC.Id_Edu_Objeto_Evaluativo_Detalle
						, OE.Nombre
						, OE.Id_Edu_Tipo_Objeto_Evaluativo
						, OE.Hora_Inicio
						, OE.Hora_Fin
						, OE.Fecha_Inicio
						, OE.Tiempo_Duracion
						, OE.Fecha_Fin
						, OE.Estado
						, OTD.Nombre AS Tipo_Desarrollo
						, OTD.Id_Edu_Tipo_Desarrollo
						FROM edu_objeto_evaluativo_detalle EC
						INNER JOIN edu_objeto_evaluativo OE ON EC.Id_Edu_Objeto_Evaluativo = OE.Id_Edu_Objeto_Evaluativo
						INNER JOIN edu_tipo_desarrollo OTD ON OE.Id_Edu_Tipo_Desarrollo = OTD.Id_Edu_Tipo_Desarrollo		
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo = $Row->Id_Edu_Objeto_Evaluativo;	
				$Nombre_Objeto_Evaluativo = $Row->Nombre;	
				$Id_Edu_Tipo_Objeto_Evaluativo = $Row->Id_Edu_Tipo_Objeto_Evaluativo;				
				$Id_Edu_Objeto_Evaluativo_Detalle = $Row->Id_Edu_Objeto_Evaluativo_Detalle;				
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Detalle/".$Id_Edu_Objeto_Evaluativo_Detalle;	
                
				if(!empty($Id_Edu_Objeto_Evaluativo_Grupo)){
				    $Url_Id_Comentario_Herencia = "/Id_Edu_Objeto_Evaluativo_Grupo/".$Id_Edu_Objeto_Evaluativo_Grupo;
				}
	
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Objeto_Evaluativo_Grupo_Crud".$urlLinkB."".$Url_Id_Comentario_Herencia;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Area_Conocimiento/".$Id_Edu_Area_Conocimiento;
				
				if(!empty($Id_Edu_Objeto_Evaluativo_Grupo)){
				    $Name_Interface = "Editar Grupo ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar ";
                    // $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"Mensaje_Id","Form","Edu_Objeto_Evaluativo_Grupo_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Grupo";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear ";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Objeto_Evaluativo"," SELECT Id_Edu_Tipo_Objeto_Evaluativo AS Id, Nombre AS Name FROM edu_tipo_objeto_evaluativo ",[])
				     ,array("Id_Edu_Tipo_Desarrollo"," SELECT Id_Edu_Tipo_Desarrollo AS Id, Nombre AS Name FROM edu_tipo_desarrollo ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","Edu_Objeto_Evaluativo_Grupo_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Objeto_Evaluativo_Grupo_Crud",$Class,$Id_Edu_Objeto_Evaluativo_Grupo,$PathImage,$Combobox,$Buttons,"Id_Edu_Objeto_Evaluativo_Grupo");
				
				$Form1 = "<div style='background-color:#fff;padding:10px 30px;'>" . $Form1."</div>";
			
				
			    $Html = DCModalForm($Name_Interface, $Form1,"");
                DCWrite($Html);				 
					
                break;
				
				

            case "ListB":
			
				    DCCloseModal();	
					$SubComentarios = $this->List_Sub_Comentario($Parm);
					
					DCWrite($SubComentarios);	
					
                break;
				
				
				
							

            case "List_Comentario":
			
				    DCCloseModal();	
					 // var_dump($Parm);
					$Comentarios = $this->List_Comentario($Parm);
					
					DCWrite($Comentarios);	
					
                break;
							
			
            case "Comenta":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Comentario = $Parm["Id_Comentario"];
				$Id_Edu_Objeto_Evaluativo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Comentario_Herencia = $Parm["Id_Comentario_Herencia"];

				$Query = "
						SELECT 
						EC.Id_Edu_Objeto_Evaluativo 
						FROM edu_objeto_evaluativo_detalle EC
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo = $Row->Id_Edu_Objeto_Evaluativo;	
				
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
						, OE.Estado
						, OTD.Nombre AS Tipo_Desarrollo
						, OTD.Id_Edu_Tipo_Desarrollo
						FROM edu_objeto_evaluativo_detalle EC
						INNER JOIN edu_objeto_evaluativo OE ON EC.Id_Edu_Objeto_Evaluativo = OE.Id_Edu_Objeto_Evaluativo
						INNER JOIN edu_tipo_desarrollo OTD ON OE.Id_Edu_Tipo_Desarrollo = OTD.Id_Edu_Tipo_Desarrollo		
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo = $Row->Id_Edu_Objeto_Evaluativo;	
				$Nombre_Objeto_Evaluativo = $Row->Nombre;	
				$Id_Edu_Tipo_Objeto_Evaluativo = $Row->Id_Edu_Tipo_Objeto_Evaluativo;				
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Detalle/".$Id_Edu_Objeto_Evaluativo_Detalle;	
                
				if(!empty($Id_Comentario_Herencia)){
				    $Url_Id_Comentario_Herencia = "/Id_Comentario_Herencia/".$Id_Comentario_Herencia;
				}
				if(!empty($Id_Comentario)){
				    $Url_Id_Comentario = "/Id_Comentario/".$Id_Comentario;
				}
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/comentario_crud".$urlLinkB."".$Url_Id_Comentario_Herencia."".$Url_Id_Comentario;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Area_Conocimiento/".$Id_Edu_Area_Conocimiento;
				
				if(!empty($Id_Comentario)){
				    $Name_Interface = "Edita tu comentario";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualiza tu comentario";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"Mensaje_Id","Form","comentario_crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Ingresa tu comentario";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Guarda tu comentario ";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Objeto_Evaluativo"," SELECT Id_Edu_Tipo_Objeto_Evaluativo AS Id, Nombre AS Name FROM edu_tipo_objeto_evaluativo ",[])
				     ,array("Id_Edu_Tipo_Desarrollo"," SELECT Id_Edu_Tipo_Desarrollo AS Id, Nombre AS Name FROM edu_tipo_desarrollo ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","comentario_crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("comentario_crud",$Class,$Id_Comentario,$PathImage,$Combobox,$Buttons,"Id_Comentario");
				
				$Form1 = "<div style='background-color:#fff;padding:10px 30px;'>" . $Form1."</div>";
			
				
			    $Html = DCModalForm($Name_Interface, $Form1,"");
                DCWrite($Html);
				
                break;	
				
											
			
            case "Comenta_Edit":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Comentario = $Parm["Id_Comentario"];
				$Id_Edu_Objeto_Evaluativo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Comentario_Herencia = $Parm["Id_Comentario_Herencia"];

				$Query = "
						SELECT 
						EC.Id_Edu_Objeto_Evaluativo 
						FROM edu_objeto_evaluativo_detalle EC
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo = $Row->Id_Edu_Objeto_Evaluativo;	
				
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
						, OE.Estado
						, OTD.Nombre AS Tipo_Desarrollo
						, OTD.Id_Edu_Tipo_Desarrollo
						FROM edu_objeto_evaluativo_detalle EC
						INNER JOIN edu_objeto_evaluativo OE ON EC.Id_Edu_Objeto_Evaluativo = OE.Id_Edu_Objeto_Evaluativo
						INNER JOIN edu_tipo_desarrollo OTD ON OE.Id_Edu_Tipo_Desarrollo = OTD.Id_Edu_Tipo_Desarrollo		
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo = $Row->Id_Edu_Objeto_Evaluativo;	
				$Nombre_Objeto_Evaluativo = $Row->Nombre;	
				$Id_Edu_Tipo_Objeto_Evaluativo = $Row->Id_Edu_Tipo_Objeto_Evaluativo;				
				
				

				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Comentario_Herencia/".$Id_Comentario_Herencia;	

				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/comentario_crud".$urlLinkB."/Id_Comentario/".$Id_Comentario;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Area_Conocimiento/".$Id_Edu_Area_Conocimiento;
				
				if(!empty($Id_Comentario)){
				    $Name_Interface = "Edita tu comentario";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualiza tu comentario";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"Mensaje_Id","Form","comentario_crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Ingresa tu comentario";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Guarda tu comentario ";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Objeto_Evaluativo"," SELECT Id_Edu_Tipo_Objeto_Evaluativo AS Id, Nombre AS Name FROM edu_tipo_objeto_evaluativo ",[])
				     ,array("Id_Edu_Tipo_Desarrollo"," SELECT Id_Edu_Tipo_Desarrollo AS Id, Nombre AS Name FROM edu_tipo_desarrollo ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","comentario_crud")
				);	
		        $Form1 = BFormVertical("comentario_crud",$Class,$Id_Comentario,$PathImage,$Combobox,$Buttons,"Id_Comentario");
				
				$Form1 = "<div style='background-color:#fff;padding:10px 30px;'>" . $Form1."</div>";
			
				
			    $Html = DCModalForm($Name_Interface, $Form1,"");
                DCWrite($Html);
				
                break;	
				
														
			
            case "Comenta_Edit_cab":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Comentario = $Parm["Id_Comentario"];
				$Id_Edu_Objeto_Evaluativo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Comentario_Herencia = $Parm["Id_Comentario_Herencia"];
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];

				$Query = "
						SELECT 
						EC.Id_Edu_Objeto_Evaluativo 
						FROM edu_objeto_evaluativo_detalle EC
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo = $Row->Id_Edu_Objeto_Evaluativo;	
				
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
						, OE.Estado
						, OTD.Nombre AS Tipo_Desarrollo
						, OTD.Id_Edu_Tipo_Desarrollo
						FROM edu_objeto_evaluativo_detalle EC
						INNER JOIN edu_objeto_evaluativo OE ON EC.Id_Edu_Objeto_Evaluativo = OE.Id_Edu_Objeto_Evaluativo
						INNER JOIN edu_tipo_desarrollo OTD ON OE.Id_Edu_Tipo_Desarrollo = OTD.Id_Edu_Tipo_Desarrollo		
						WHERE 
						EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo = $Row->Id_Edu_Objeto_Evaluativo;	
				$Nombre_Objeto_Evaluativo = $Row->Nombre;	
				$Id_Edu_Tipo_Objeto_Evaluativo = $Row->Id_Edu_Tipo_Objeto_Evaluativo;				
				
				

				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Objeto_Evaluativo_Detalle/".$Id_Edu_Objeto_Evaluativo_Detalle."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Id_Edu_Evaluacion_Desarrollo_Cab."/Id_Comentario_Principal/".$Id_Comentario;	

				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/comentario_crud".$urlLinkB."/Id_Comentario/".$Id_Comentario;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Area_Conocimiento/".$Id_Edu_Area_Conocimiento;
				
				if(!empty($Id_Comentario)){
				    $Name_Interface = "Edita tu comentario";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualiza tu comentario";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"Mensaje_Id","Form","comentario_crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Ingresa tu comentario";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Guarda tu comentario ";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Objeto_Evaluativo"," SELECT Id_Edu_Tipo_Objeto_Evaluativo AS Id, Nombre AS Name FROM edu_tipo_objeto_evaluativo ",[])
				     ,array("Id_Edu_Tipo_Desarrollo"," SELECT Id_Edu_Tipo_Desarrollo AS Id, Nombre AS Name FROM edu_tipo_desarrollo ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","comentario_crud")
				);	
		        $Form1 = BFormVertical("comentario_crud",$Class,$Id_Comentario,$PathImage,$Combobox,$Buttons,"Id_Comentario");
				
				$Form1 = "<div style='background-color:#fff;padding:10px 30px;'>" . $Form1."</div>";
			
				
			    $Html = DCModalForm($Name_Interface, $Form1,"");
                DCWrite($Html);
				
                break;	
				
				
				
				
				
            case "Configura_Objeto_Evaluativo":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];

	
				$Query = "
						SELECT 
						EC.Id_Edu_Tipo_Desarrollo 
						FROM edu_objeto_evaluativo EC
						WHERE 
						EC.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
				";	
				$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Tipo_Desarrollo = $Row->Id_Edu_Tipo_Desarrollo;				

				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;	
				
				if(!empty($Id_Edu_Objeto_Evaluativo)){
					
					$Pestanas = SetttingsSite::Tabs_Objeto_Evaluativo_Foro(array(
					"".$urlLinkB."]"
					,"".$urlLinkB."]Marca"
					,"".$urlLinkB."]","".$urlLinkB."]",""));	
					
				}					
				
				
				// if($Id_Edu_Tipo_Desarrollo == 3 ){
				    
				$Id_Form = "Edu_Objeto_Evaluativo_Tarea_Crud";
				
				// }
			
				$DCPanelTitle = DCPanelTitle("OBJETO EVALUATIVO","Foro",$btn);
				
 
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/".$Id_Form."/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;
				$DirecctionDelete = $UrlFile."/REDIRECT/".$Redirect."/interface/DeleteMassive/Id_Edu_Area_Conocimiento/".$Id_Edu_Area_Conocimiento;
				
				if(!empty($Id_Edu_Objeto_Evaluativo)){
				    $Name_Interface = "Editar Area de Conocimiento";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    // $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"Mensaje_Id","Form",$Id_Form,"btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Area de Conocimiento";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                }
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Desarrollo"," SELECT Id_Edu_Tipo_Desarrollo AS Id, Nombre AS Name FROM edu_tipo_desarrollo ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form",$Id_Form),$ButtonAdicional
				);	
		        $Form1 = BFormVertical($Id_Form,$Class,$Id_Edu_Objeto_Evaluativo,$PathImage,$Combobox,$Buttons,"Id_Edu_Objeto_Evaluativo");
				
				$Form1 = "<div style='background-color:#fff;'>".$DCPanelTitle . $Form1."</div>";
				
                DCWrite($Pestanas.$Form1);
                DCExit();
				
                break;	
								
				
				
            case "Configura_Objeto_Evaluativo_Estado":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];

	
				$Query = "
						SELECT 
						EC.Id_Edu_Tipo_Desarrollo 
						FROM edu_objeto_evaluativo EC
						WHERE 
						EC.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
				";	
				$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Tipo_Desarrollo = $Row->Id_Edu_Tipo_Desarrollo;				

				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;	
				
				if(!empty($Id_Edu_Objeto_Evaluativo)){
					
					$Pestanas = SetttingsSite::Tabs_Objeto_Evaluativo_Foro(array(
					"".$urlLinkB."]"
					,"".$urlLinkB."]"
					,"".$urlLinkB."]","".$urlLinkB."]Marca",""));	
					
				}					
				
				
				// if($Id_Edu_Tipo_Desarrollo == 3 ){
				    
				$Id_Form = "Edu_Objeto_Evaluativo_Foro_Activa";
				
				// }
			
				$DCPanelTitle = DCPanelTitle("OBJETO EVALUATIVO","Foro",$btn);
				
 
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/".$Id_Form."/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;
				$DirecctionDelete = $UrlFile."/REDIRECT/".$Redirect."/interface/DeleteMassive/Id_Edu_Area_Conocimiento/".$Id_Edu_Area_Conocimiento;
				
				if(!empty($Id_Edu_Objeto_Evaluativo)){
				    $Name_Interface = "Editar Area de Conocimiento";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    // $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"Mensaje_Id","Form",$Id_Form,"btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Area de Conocimiento";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                }
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Desarrollo"," SELECT Id_Edu_Tipo_Desarrollo AS Id, Nombre AS Name FROM edu_tipo_desarrollo ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form",$Id_Form),$ButtonAdicional
				);	
		        $Form1 = BFormVertical($Id_Form,$Class,$Id_Edu_Objeto_Evaluativo,$PathImage,$Combobox,$Buttons,"Id_Edu_Objeto_Evaluativo");
				
				$Form1 = "<div style='background-color:#fff;'>".$DCPanelTitle . $Form1."</div>";
				
                DCWrite($Pestanas.$Form1);
                DCExit();
				
                break;	
				
				
				
	
            case "Delete_Comentario":
		
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Comentario = $Parm["Id_Comentario"];
				$Id_Edu_Objeto_Evaluativo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Comentario_Herencia = $Parm["Id_Comentario_Herencia"];

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Comentario_Herencia/".$Id_Comentario_Herencia."/Id_Comentario/".$Id_Comentario;	
				
				$btn = "Confirmar ]" .$UrlFile ."/interface/DComentarioR".$urlLinkB."]Comentario_Det_".$Id_Comentario_Herencia."]HXM]]btn btn-default dropdown-toggle]}";				
				// $btn .= "Cancelar ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_comentario_'.$Id_Comentario);					
				
			    $Html = DCModalFormMsj("Deseas eliminar el contenido",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;		
	

	
            case "DComentarioR":
		
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Comentario = $Parm["Id_Comentario"];
				$Id_Edu_Objeto_Evaluativo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Comentario_Herencia = $Parm["Id_Comentario_Herencia"];
                

				// $Id_Edu_Pregunta = $Settings["Id_Edu_Pregunta"];
			
				
				$where = array('Id_Comentario' =>$Id_Comentario);
				// var_dump($where);
				$rg = ClassPDO::DCDelete('comentario', $where, $Conection);
				
				DCCloseModal();	
				$SubComentarios = $this->List_Sub_Comentario($Parm);

				DCWrite($SubComentarios);	

                // DCWrite($Html);
				exit();
                break;			

				
            case "Delete_Comentario_P":
		
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Comentario = $Parm["Id_Comentario"];
				$Id_Edu_Objeto_Evaluativo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Comentario_Herencia = $Parm["Id_Comentario_Herencia"];

                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Comentario/".$Id_Comentario;	
				
				$btn = "Confirmar ]" .$UrlFile ."/interface/DComentarioR2".$urlLinkB."]Comentario_".$Id_Comentario."]HXM]]btn btn-default dropdown-toggle]}";				
				// $btn .= "Cancelar ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_comentario_'.$Id_Comentario);					
				
			    $Html = DCModalFormMsj("Deseas eliminar el contenido",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;	
				

            case "DComentarioR2":
		
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Comentario = $Parm["Id_Comentario"];
				$Id_Edu_Objeto_Evaluativo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Comentario_Herencia = $Parm["Id_Comentario_Herencia"];
                

				// $Id_Edu_Pregunta = $Settings["Id_Edu_Pregunta"];
			
				
				$where = array('Id_Comentario' =>$Id_Comentario);
				$rg = ClassPDO::DCDelete('comentario', $where, $Conection);
				
				$where = array('Id_Comentario_Herencia' =>$Id_Comentario);
				$rg = ClassPDO::DCDelete('comentario', $where, $Conection);				
				
				DCCloseModal();	
				
                $SubComentarios = "<div style='padding:10px 20px;'>Comentario Eliminado</div>";
				DCWrite($SubComentarios);	

                // DCWrite($Html);
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

	
	public function List_Sub_Comentario($Parm) {
       	global $Conection, $DCTimeHour,$NameTable,$UrlFile;
		$User = $_SESSION['User'];
		$Id_Comentario = $Parm["Id_Comentario"];
		$Id_Comentario_Herencia = $Parm["Id_Comentario_Herencia"];
		$Id_Edu_Almacen = $Parm["key"];
		$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
		$Id_Edu_Objeto_Evaluativo_Detalle = $Parm["Id_Edu_Objeto_Evaluativo_Detalle"];
		$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
		$Id_Edu_Articulo = $Parm['Id_Edu_Articulo'];
		$Id_User_Miembro = $Parm['Id_User_Miembro'];
	
		
        $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Comentario_Herencia/".$Id_Comentario_Herencia;	

        if(empty($Id_User_Miembro)){
			
			$Query = "

			SELECT 
			UM.Nombre AS Nombres,
			UM.Foto,
			CM.Id_Comentario,
			CM.Date_Time_Update,
			CM.Id_User_Miembro,
			CM.Comentario
			FROM
			comentario CM
			INNER JOIN user_miembro UM ON UM.Id_User_Miembro = CM.Id_User_Miembro 
			WHERE 
			CM.Id_Comentario_Herencia = :Id_Comentario_Herencia
			ORDER BY CM.Date_Time_Update DESC 

			";    

			$Where = ["Id_Comentario_Herencia" => $Id_Comentario_Herencia];
		}else{
			
			$Query = "

			SELECT 
			UM.Nombre AS Nombres,
			UM.Foto,
			CM.Id_Comentario,
			CM.Date_Time_Update,
			CM.Id_User_Miembro,
			CM.Comentario
			FROM
			comentario CM
			INNER JOIN user_miembro UM ON UM.Id_User_Miembro = CM.Id_User_Miembro 
			WHERE 
			CM.Id_Comentario_Herencia = :Id_Comentario_Herencia AND
			CM.Id_User_Miembro = :Id_User_Miembro
			ORDER BY CM.Date_Time_Update DESC 

			";    

			$Where = ["Id_Comentario_Herencia" => $Id_Comentario_Herencia,"Id_User_Miembro"=>$Id_User_Miembro];			
		}	
			
			
		$Registro = ClassPdo::DCRows($Query,$Where,$Conection);

		$tableCuerpo = '

		<div style="background-color:#fff">

		';
		$DiferenciadorA = "";
		$DiferenciadorB = "";
		$DCPanelTitle_B_2 = "";
		$ContGeneral = 0;
		$ContParticipantes = 0;
		$ContParticipantes_B = 0;

		foreach ($Registro as $Reg) {

		$Foto = $Reg->Foto;
		$Id_Comentario = $Reg->Id_Comentario;
		$Id_User_Miembro = $Reg->Id_User_Miembro;

		if(empty($Foto)){
			
			$Img_Foto = "<div><i class='zmdi zmdi-account-circle' style='font-size: 4.5em;color: #ccd3d8'></i></div>";			

		}else{
			
			$Img_Foto = "<div class='Comentario'><img src='/sadministrator/simages/avatars/".$Reg->Foto."' width='50' height='50' class='img-circle'></div>";			
		}

			$Dia_Num = substr($Reg->Date_Time_Update, 8, 2);
			$Year = substr($Reg->Date_Time_Update, 0, 4);
			$Minutos_Descripcion = substr($Reg->Date_Time_Update, 10, 6);			

			$ContGeneral += 1;	
			
			// echo $Id_User_Miembro." :: ".$User;
			
			if($Id_User_Miembro == $User){

				$btn = " Editar ]" .$UrlFile."/interface/Comenta_Edit/Id_Comentario/".$Id_Comentario."".$urlLinkB."]animatedModal5]HXM]]boton_link_href}";
				$btn .= " Eliminar ]" .$UrlFile."/interface/Delete_Comentario/Id_Comentario/".$Id_Comentario."".$urlLinkB."]animatedModal5]HXM]]boton_link_href}";
				$DCPanelTitle_B_2 = DCButton($btn, 'botones1', 'sys_form_comen_sub_'.$ContGeneral);				
				
			}else{
				$DCPanelTitle_B_2 =  "";
			}
			
			$tableCuerpo .= '

			<div class="panel panel-default" style="border: none; margin-bottom: 6px;">
				
				<div class="panel-body" style="padding: 2px 10px;padding: 2px 10px;background: #fff;">
					<div class="row">
						<div class="col-md-1">
							'.$Img_Foto.'
						</div>
						<div class="col-md-11 panel-sub-comentario">
							<div class="col-md-11 panel-sub-comentario-det">
							  <b>'.$Reg->Nombres.'</b>
							  <i> | '.DCDia_Texto($Reg->Date_Time_Update) .' '.$Dia_Num. ' - '. DCMes_Texto($Reg->Date_Time_Update).' del '. $Year.' | '.$Minutos_Descripcion.'  </i>
							  <p>'.$Reg->Comentario.'</p>
							  <p>'.$DCPanelTitle_B_2.'</p>
							  
							</div>
						</div>
					</div>					
					
				</div>
				
			</div>

			';

		}

		$tableCuerpo .= '

		</div>
				

		';
        return $tableCuerpo;		
	}	
	
	
	public function List_Comentario($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];	
		$Id_Edu_Objeto_Evaluativo_Detalle = $Settings['Id_Edu_Objeto_Evaluativo_Detalle'];	
		$Id_Edu_Almacen = $Settings['key'];	
		$Id_Edu_Articulo = $Settings['Id_Edu_Articulo'];	
		$Id_Edu_Componente = $Settings['Id_Edu_Componente'];	
		$Id_Comentario = $Settings['Id_Comentario'];	
		$Id_Edu_Objeto_Evaluativo = $Settings['Id_Edu_Objeto_Evaluativo'];	
		$Id_Edu_Evaluacion_Desarrollo_Cab = $Settings['Id_Edu_Evaluacion_Desarrollo_Cab'];	
		$Id_Edu_Objeto_Evaluativo_Detalle = $Settings['Id_Edu_Objeto_Evaluativo_Detalle'];	
		$Id_Edu_Objeto_Evaluativo = $Settings['Id_Edu_Objeto_Evaluativo'];	
		

		$Query = "
		SELECT 
		OE.Id_Suscripcion
		, OE.Id_Edu_Almacen
		, OE.Id_User
		FROM suscripcion OE 
		WHERE 
		OE.Id_Edu_Almacen = :Id_Edu_Almacen AND OE.Id_User = :Id_User ";	
		$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen,"Id_User" =>$User ];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Suscripcion = $Row->Id_Suscripcion;
				

		$Query = "
		SELECT 
		OE.Nombre,
		OED.Id_Edu_Objeto_Evaluativo_Grupo_Detalle,
		OED.Id_Edu_Objeto_Evaluativo_Grupo
		FROM edu_objeto_evaluativo_grupo OE
		INNER JOIN edu_objeto_evaluativo_grupo_detalle OED ON OE.Id_Edu_Objeto_Evaluativo_Grupo = OED.Id_Edu_Objeto_Evaluativo_Grupo				
		WHERE 
		OE.Id_Edu_Almacen = :Id_Edu_Almacen 
		AND OED.Id_Suscripcion = :Id_Suscripcion 
		AND OE.Id_Edu_Componente = :Id_Edu_Componente 
		AND OE.Id_Edu_Objeto_Evaluativo_Detalle = :Id_Edu_Objeto_Evaluativo_Detalle 

		";	
		$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen,"Id_Edu_Componente" =>$Id_Edu_Componente, "Id_Edu_Objeto_Evaluativo_Detalle"=>$Id_Edu_Objeto_Evaluativo_Detalle,"Id_Suscripcion"=>$Id_Suscripcion];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Edu_Objeto_Evaluativo_Grupo_Detalle = $Row->Id_Edu_Objeto_Evaluativo_Grupo_Detalle;
		$Id_Edu_Objeto_Evaluativo_Grupo = $Row->Id_Edu_Objeto_Evaluativo_Grupo;
		$Nombre = $Row->Nombre;
				
		

				
        $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Id_Edu_Evaluacion_Desarrollo_Cab."/Id_Edu_Objeto_Evaluativo_Detalle/".$Id_Edu_Objeto_Evaluativo_Detalle."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;			
		$UrlFile = "/sadministrator/edu-examen-foro";
        // var_dump($Where);
        if(!empty($Id_Comentario)){	
		
		    if(!empty($Id_Edu_Objeto_Evaluativo_Grupo)){
				$Query = "
				   
					SELECT 
					UM.Nombre AS Nombres, UM.Foto, CM.Id_Comentario, CM.Id_User_Miembro, CM.Date_Time_Update, CM.Comentario
					FROM
					comentario CM
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = CM.Id_User_Miembro 
					INNER JOIN suscripcion SUS ON ( SUS.Id_User = CM.Id_User_Miembro AND  SUS.Id_Edu_Almacen = ".$Id_Edu_Almacen."  ) 
					INNER JOIN edu_objeto_evaluativo_grupo_detalle EOGD ON  ( EOGD.Id_Suscripcion = SUS.Id_Suscripcion AND  EOGD.Id_Edu_Objeto_Evaluativo_Grupo = ".$Id_Edu_Objeto_Evaluativo_Grupo." ) 
					WHERE 
					CM.Id_Comentario = :Id_Comentario 
					ORDER BY CM.Date_Time_Update DESC 
					
				";    
				$Where = ["Id_Comentario" => $Id_Comentario];
				$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
				
			}else{
				
				$Query = "
				   
					SELECT 
					UM.Nombre AS Nombres, UM.Foto, CM.Id_Comentario, CM.Id_User_Miembro, CM.Date_Time_Update, CM.Comentario
					FROM
					comentario CM
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = CM.Id_User_Miembro 
					WHERE 
					CM.Id_Comentario = :Id_Comentario 
					ORDER BY CM.Date_Time_Update DESC 
					
				";    
				$Where = ["Id_Comentario" => $Id_Comentario];
				$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
				
			}
			
		}else{

		    if(!empty($Id_Edu_Objeto_Evaluativo_Grupo)){
				
				$Query = "
				   
					SELECT 
					UM.Nombre AS Nombres,
					UM.Foto,
					CM.Id_Comentario,
					CM.Id_User_Miembro,
					CM.Date_Time_Update,
					CM.Comentario
					FROM
					comentario CM
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = CM.Id_User_Miembro 
					INNER JOIN suscripcion SUS ON ( SUS.Id_User = CM.Id_User_Miembro AND  SUS.Id_Edu_Almacen = ".$Id_Edu_Almacen."  ) 
					INNER JOIN edu_objeto_evaluativo_grupo_detalle EOGD ON  ( EOGD.Id_Suscripcion = SUS.Id_Suscripcion AND  EOGD.Id_Edu_Objeto_Evaluativo_Grupo = ".$Id_Edu_Objeto_Evaluativo_Grupo." ) 
					WHERE 
					CM.Id_Edu_Objeto_Evaluativo_Detalle = :Id_Edu_Objeto_Evaluativo_Detalle AND
					CM.Jerarquia = :Jerarquia 
					ORDER BY CM.Date_Time_Update DESC 
					
				";   
				
            }else{	
			
				$Query = "
				   
					SELECT 
					UM.Nombre AS Nombres,
					UM.Foto,
					CM.Id_Comentario,
					CM.Id_User_Miembro,
					CM.Date_Time_Update,
					CM.Comentario
					FROM
					comentario CM
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = CM.Id_User_Miembro 
					WHERE 
					CM.Id_Edu_Objeto_Evaluativo_Detalle = :Id_Edu_Objeto_Evaluativo_Detalle AND
					CM.Jerarquia = :Jerarquia 
					ORDER BY CM.Date_Time_Update DESC 
					
				";    
				
			}
			$Where = ["Id_Edu_Objeto_Evaluativo_Detalle" => $Id_Edu_Objeto_Evaluativo_Detalle,"Jerarquia"=>1];
			$Registro = ClassPdo::DCRows($Query,$Where,$Conection);			
			
		}	
		
	
		$tableCuerpo = '
		
		    <div style="background-color:#fff">
		
		';
		$DiferenciadorA = "";
		$DiferenciadorB = "";
		$ContGeneral = 0;
		$ContParticipantes = 0;
		$ContParticipantes_B = 0;

		foreach ($Registro as $Reg) {
			
			$Foto = $Reg->Foto;
			$Id_Comentario = $Reg->Id_Comentario;
			$Id_User_Miembro = $Reg->Id_User_Miembro;
			
			// echo $Foto."<br>";
			
			if(empty($Foto)){
				
			    $Img_Foto = "<div><i class='zmdi zmdi-account-circle' style='font-size: 4.5em;color: #0873b1'></i></div>";			
			
			}else{
				
			    $Img_Foto = "<div class='Comentario'><img src='/sadministrator/simages/avatars/".$Reg->Foto."' width='50' height='50' class='img-circle'></div>";			
			}
			
				$Dia_Num = substr($Reg->Date_Time_Update, 8, 2);
				$Year = substr($Reg->Date_Time_Update, 0, 4);
				$Minutos_Descripcion = substr($Reg->Date_Time_Update, 10, 6);
				
				$btn = " Responde al comentario]" .$UrlFile."/interface/Comenta/Id_Comentario_Herencia/".$Id_Comentario."".$urlLinkB."]animatedModal5]HXM]]boton_link}";
				$DCPanelTitle_B = DCButton($btn, 'botones1', 'sys_form_comen_'.$ContGeneral);	

				
				if($Id_User_Miembro == $User){

					$btn = " Editar ]" .$UrlFile."/interface/Comenta_Edit_cab/Id_Comentario/".$Id_Comentario."".$urlLinkB."]animatedModal5]HXM]]boton_link_href_gris}";
					$btn .= " Eliminar ]" .$UrlFile."/interface/Delete_Comentario_P/Id_Comentario/".$Id_Comentario."".$urlLinkB."]animatedModal5]HXM]]boton_link_href_gris}";
					$DCPanelTitle_B_2 = DCButton($btn, 'botones1', 'sys_form_comen_edit'.$ContGeneral);				
					
				}else{
					$DCPanelTitle_B_2 =  "";
				}

				
		
				$ContGeneral += 1;	
			    $Settings["Id_Comentario_Herencia"] = $Id_Comentario;
		        $SubComentarios = $this->List_Sub_Comentario($Settings);
				
				$tableCuerpo .= '

					<div id="Comentario_'.$Id_Comentario.'"  > 
						<div class="panel panel-default panel-comentario"  > 
							
							<div class="panel-body">
								<div class="row">
									<div class="col-md-1">
										'.$Img_Foto.'
									</div>
									<div class="col-md-11">
									  <b>'.$Reg->Nombres.'</b>
									  <i> | '.DCDia_Texto($Reg->Date_Time_Update) .' '.$Dia_Num. ' - '. DCMes_Texto($Reg->Date_Time_Update).' del '. $Year.' | '.$Minutos_Descripcion.'  </i>
									  <p>'.$Reg->Comentario.'</p>
									   <div>'.$DCPanelTitle_B_2.'</div>
									  <div>'.$DCPanelTitle_B.'</div>
									 
									</div>
								</div>
								<div id="Comentario_Det_'.$Id_Comentario.'">
								
								'.$SubComentarios.'
								
								
								</div>	
							</div>
							
						</div>
					</div>

				';

		}
	
		$tableCuerpo .= '

			</div>
					
		
		';
		return 	$tableCuerpo;	
		
	}	
	
	public function Inicia_Evaluacion($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];	
		
		
		
		
		$Id_Edu_Objeto_Evaluativo = $Settings["Id_Edu_Objeto_Evaluativo"];
		$key = $Settings["key"];

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

        if(empty($Id_Edu_Evaluacion_Desarrollo_Cab)){
			
			$data = array(
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
			
			$Result_Cab = ClassPDO::DCInsert("edu_evaluacion_desarrollo_cab", $data, $Conection);
			$Id_Edu_Evaluacion_Desarrollo_Cab = $Result_Cab["lastInsertId"];		
					
		}else{
			
			if($Pregunta_Actual == 0){
			
			}
			
		}

		$Query = "
		SELECT  
		DC.Id_Edu_Evaluacion_Desarrollo_Cab
		, DC.Pregunta_Actual
		, DC.Matriz_Preguntas
		, DC.Estado
		, DC.Tiempo_Estado
		FROM edu_evaluacion_desarrollo_cab DC  
		WHERE 
		 DC.Id_Edu_Evaluacion_Desarrollo_Cab =:Id_Edu_Evaluacion_Desarrollo_Cab
		";	
		$Where = ["Id_Edu_Evaluacion_Desarrollo_Cab" =>$Id_Edu_Evaluacion_Desarrollo_Cab ];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		
		return $Row;
						
	}		





		



}