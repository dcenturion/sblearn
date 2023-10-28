<?php
require_once('./sviews/layout.php');
require_once(dirname(__FILE__).'/ft_settings_site.php');

$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Examen{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-examen";
		$UrlFile_Tarea = "/sadministrator/edu-evaluacion-tarea";
		$UrlFile_Foro = "/sadministrator/edu-examen-foro";
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
					
					case "Edu_Crud_Objeto_Evaluativo_Examen_NA":
					
					        $Id_Edu_Tipo_Objeto_Evaluativo =  DCPost("Id_Edu_Tipo_Objeto_Evaluativo");
							
							$Query = "
							SELECT 
							EC.Usar_Acta_Nota
							FROM edu_articulo EC
							WHERE 
							EC.Id_Edu_Articulo = :Id_Edu_Articulo 
							";	
							$Where = ["Id_Edu_Articulo" =>$Parm["Id_Edu_Articulo"]];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Usar_Acta_Nota = $Row->Usar_Acta_Nota;		

                            if(	$Usar_Acta_Nota == "SI"){						
							     
								  $Id_Edu_Aspecto_Evaluativo = DCPost("Id_Edu_Aspecto_Evaluativo");
								  if(empty($Id_Edu_Aspecto_Evaluativo)){
									  	
											Message("Mensaje!!: El campo Id_Edu_Aspecto_Evaluativo debe estar lleno ","E");
											exit();		
								  }
								 
								 
							}							
							
						
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
							

						
							if($Id_Edu_Tipo_Objeto_Evaluativo == 3 ){
								
								$Settings = array();
								$Settings['Url'] = $UrlFile_Tarea."/interface/Configura_Objeto_Evaluativo/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."";
								$Settings['Screen'] = "PanelA";
								$Settings['Type_Send'] = "HXM";
								DCRedirectJS($Settings);
						
							}elseif($Id_Edu_Tipo_Objeto_Evaluativo == 4 ){
								
								$Settings = array();
								$Settings['Url'] = $UrlFile_Foro."/interface/Configura_Objeto_Evaluativo/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."";
								$Settings['Screen'] = "PanelA";
								$Settings['Type_Send'] = "HXM";
								DCRedirectJS($Settings);
								
																
							}else{
						
								
								$Settings = array();
								$Settings['Url'] = $UrlFile."/interface/Configura_Objeto_Evaluativo/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."";
								$Settings['Screen'] = "PanelA";
								$Settings['Type_Send'] = "HXM";
								DCRedirectJS($Settings);
								
														
								
							}							
							
							DCExit();	
							
							
							
						break;	
						
											
					
					case "Edu_Crud_Objeto_Evaluativo_Examen_NA_Configura":
					case "Edu_Crud_Objeto_Evaluativo_Examen_NA_Configura_B":
					
                            $Data = array();
							$Data['Id_Edu_Articulo'] = $Parm["Id_Edu_Articulo"];			
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Objeto_Evaluativo"],"Id_Edu_Objeto_Evaluativo",$Data);  
							
							$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
							$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
							$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
							
	                        $Settings = array();
							$Settings['Url'] = $UrlFile."/interface/Configura_Examen/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Articulo/".$Id_Edu_Articulo;
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJS($Settings);	
							
							DCExit();	
						break;	
						
						case "Edu_Crud_Objeto_Evaluativo_Examen_NA_Estado":
					
                            $Data = array();
							$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
							$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
							$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
							
							$Query = "
							
								SELECT 
								EP.Pregunta_Validada, EP.Nombre, EP.Id_Edu_Pregunta, EP.Id_Edu_Tipo_Pregunta
								FROM edu_pregunta EP 
								WHERE 
								EP.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo 
							
							";	
							// var_dump($Query);
							$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo];
							$Mat_EVD = ClassPdo::DCRows($Query,$Where,$Conection);
							$Cont_Rpta = 0;
		
							foreach ($Mat_EVD as $Reg_EVD) {
								
								$Pregunta_Validada = $Reg_EVD->Pregunta_Validada;
								$Id_Edu_Pregunta = $Reg_EVD->Id_Edu_Pregunta;
								$Nombre = $Reg_EVD->Id_Edu_Pregunta;
								$Id_Edu_Tipo_Pregunta = $Reg_EVD->Id_Edu_Tipo_Pregunta;
								
								$Query = "
								SELECT 
								Count(*) AS Count_Reg
								FROM edu_respuesta EDC 
								WHERE 
								EDC.Id_Edu_Pregunta = :Id_Edu_Pregunta ";	
								$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);
								$Count_Reg = $Row->Count_Reg;	

								$Query = "
								SELECT 
								Count(*) AS Count_Reg
								FROM edu_respuesta EDC 
								WHERE 
								EDC.Id_Edu_Pregunta = :Id_Edu_Pregunta AND EDC.Respuesta_Correcta = :Respuesta_Correcta ";	
								$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta,"Respuesta_Correcta"=>"SI"];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);
								$Count_Reg_Rpta_Correcta = $Row->Count_Reg;

							
								    if($Id_Edu_Tipo_Pregunta == 1){
										
										
										
										if($Count_Reg <= 1){
											
											Message("ERROR!!: La ".$Nombre.", debe tener un mínimo de 2 respuestas ","E");
											exit();									
										}
										
										// if($Pregunta_Validada !== "SI"){
											
											// Message("ERROR!!: ".$Nombre.", debe tener una respuesta correcta ","C");
										
											// exit();									
										// }										
							        }
							
								
								    if($Id_Edu_Tipo_Pregunta == 2){
										
										if($Count_Reg <= 2){
											
											Message("ERROR!!: La ".$Nombre.", debe tener un mínimo de 3 respuestas ","E");
											exit();									
										}
										
										if($Count_Reg_Rpta_Correcta <= 1){
											
											Message("ERROR!!: ".$Nombre.", debe tener, mínimo 2 respuestas correctas ","E");
											exit();									
										}										
							        }									

									
									
									
							}
							
				
												
							
							$Data['Id_Edu_Articulo'] = $Parm["Id_Edu_Articulo"];			
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Objeto_Evaluativo"],"Id_Edu_Objeto_Evaluativo",$Data);  
							
							
	                        $Settings = array();
							$Settings['Url'] = $UrlFile."/interface/Publicacion_Examen/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Articulo/".$Id_Edu_Articulo;
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJS($Settings);	
							
							DCExit();	
						break;	
						
						
						
						
											
					case "Edu_Crud_Pregunta":
					        
							
							
							
							if(empty(DCPost("Nombre"))){
								Message("Debes insertar el Nombre de la pregunta","C");
								exit();
							}
							if(empty(DCPost("Nota"))){
								Message("Debes insertar la Nota de la pregunta","C");
								exit();
							}
							if(empty(DCPost("Id_Edu_Tipo_Pregunta"))){
								Message("Debes insertar el tipo de pregunta","C");
								exit();
							}
						
						
							$Query = "
							SELECT 
							Count(*)  AS Tot 
							FROM edu_pregunta EC
							WHERE 
							EC.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo 
							";	
							$Where = ["Id_Edu_Objeto_Evaluativo" =>$Parm["Id_Edu_Objeto_Evaluativo"]];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Tot_Items = $Row->Tot + 1;
													
							
                            $Data = array();
							$Data['Orden'] = $Tot_Items;			
							$Data['Id_Edu_Objeto_Evaluativo'] = $Parm["Id_Edu_Objeto_Evaluativo"];			
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Pregunta"],"Id_Edu_Pregunta",$Data);  
							
							$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
							$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
							$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
							
							$Id_Edu_Pregunta = $Parm["Id_Edu_Pregunta"];
							if(!empty($Id_Edu_Pregunta)){
							    $this->Ordenar_Preguntas($Parm);
			
							}

	                        $Settings = array();
							$Settings['Url'] = $UrlFile."/interface/Configura_Examen/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Articulo/".$Id_Edu_Articulo;
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJSSP($Settings);	
							DCCloseModal();
							DCExit();	
							
						break;	
						
												
											
					case "Edu_Respuesta_Crud":
					
					
						    if(empty(DCPost("Nombre"))){
								Message("Debes insertar el Nombre de la respuesta","C");
								exit();
							}
							
					        
						
							$Query = "
							SELECT 
							Count(*)  AS Tot 
							FROM edu_respuesta EC
							WHERE 
							EC.Id_Edu_Pregunta = :Id_Edu_Pregunta 
							";	
							$Where = ["Id_Edu_Pregunta" =>$Parm["Id_Edu_Pregunta"]];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Tot_Items = $Row->Tot + 1;

							$Query = "
							SELECT 
							EC.Id_Edu_Tipo_Pregunta 
							FROM edu_pregunta EC
							WHERE 
							EC.Id_Edu_Pregunta = :Id_Edu_Pregunta 
							";	
							$Where = ["Id_Edu_Pregunta" =>$Parm["Id_Edu_Pregunta"]];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Id_Edu_Tipo_Pregunta = $Row->Id_Edu_Tipo_Pregunta;													

							$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
							$Id_Edu_Componente = $Parm["Id_Edu_Componente"];
							$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
							$Id_Edu_Pregunta = $Parm["Id_Edu_Pregunta"];
							$Id_Edu_Respuesta = $Parm["Id_Edu_Respuesta"];
							
							
							if($Id_Edu_Tipo_Pregunta == 1){
								
								$reg = array(
								'Respuesta_Correcta' => ''
								);
								$where = array('Id_Edu_Pregunta' => $Id_Edu_Pregunta);
								$rg = ClassPDO::DCUpdate('edu_respuesta', $reg , $where, $Conection,"");
								
								// Pregunta_Validada
                                
								if(!empty(DCPost("Respuesta_Correcta"))){
										$reg = array(
										'Pregunta_Validada' => 'SI'
										);
										$where = array('Id_Edu_Pregunta' => $Id_Edu_Pregunta);
										$rg = ClassPDO::DCUpdate('edu_pregunta', $reg , $where, $Conection,"");
                                }
								
                            }
							
							
                            $Data = array();
							$Data['Orden'] = $Tot_Items;			
							$Data['Id_Edu_Pregunta'] = $Parm["Id_Edu_Pregunta"];			
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Respuesta"],"Id_Edu_Respuesta",$Data);  

							if(!empty($Id_Edu_Respuesta)){
							    $this->Ordenar_Respuesta($Parm);
								
							}
							
	                        $Settings = array();
							$Settings['Url'] = $UrlFile."/interface/Configura_Examen/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Articulo/".$Id_Edu_Articulo;
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJSSP($Settings);	
							DCCloseModal();
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
                // echo $Id_Edu_Componente_S;	
                // var_dump($Row);				
                // exit();				
				
				if( $Id_Edu_Tipo_Objeto_Evaluativo == 3){
					
					$UrlFile_Edu_Evaluacion_Tarea = "/sadministrator/edu-evaluacion-tarea";
					
					$Settings = array();
					$Settings['Url'] = $UrlFile_Edu_Evaluacion_Tarea."/interface/Crea_Objeto_Evaluativo/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;
					$Settings['Screen'] = "PanelA";
					$Settings['Type_Send'] = "HXM";
					DCRedirectJSSP($Settings);	
					exit();
					
				}


				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;	
				
				if(!empty($Id_Edu_Objeto_Evaluativo)){
					
					$Pestanas = SetttingsSite::Tabs_Objeto_Evaluativo(array(
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
				     ,array("Tipo_Examen"," SELECT Id_Edu_Evaluativo_Tipo_Examen AS Id, Nombre AS Name FROM edu_evaluativo_tipo_examen ",[])
				     ,array("Id_Edu_Aspecto_Evaluativo"," SELECT Id_Edu_Aspecto_Evaluativo AS Id, Nombre AS Name FROM edu_aspecto_evaluativo WHERE Entity = :Entity ",["Entity"=>$Entity])
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
					
					$Pestanas = SetttingsSite::Tabs_Objeto_Evaluativo(array(
					"".$urlLinkB."]"
					,"".$urlLinkB."]Marca"
					,"".$urlLinkB."]","".$urlLinkB."]",""));	
					
				}					
				
				if($Id_Edu_Tipo_Desarrollo == 1){
				    
					$Id_Form = "Edu_Crud_Objeto_Evaluativo_Examen_NA_Configura";
				
				}
				if($Id_Edu_Tipo_Desarrollo == 2){
				    
					$Id_Form = "Edu_Crud_Objeto_Evaluativo_Examen_NA_Configura_B";
				
				}
			
				$DCPanelTitle = DCPanelTitle("OBJETO EVALUATIVO","Examen con Nota Automática",$btn);
				
 
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
				
            case "Publicacion_Examen":
			
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
					
					$Pestanas = SetttingsSite::Tabs_Objeto_Evaluativo(array(
					"".$urlLinkB."]"
					,"".$urlLinkB."]"
					,"".$urlLinkB."]"
					,"".$urlLinkB."]Marca",""));	
					
				}					
				
				
				$Id_Form = "Edu_Crud_Objeto_Evaluativo_Examen_NA_Estado";
				
				
			
				$DCPanelTitle = DCPanelTitle("OBJETO EVALUATIVO","Examen con Nota Automática",$btn);
				
 
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
			
			
            case "Configura_Examen":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;	
				
				if(!empty($Id_Edu_Objeto_Evaluativo)){
					
					$Pestanas = SetttingsSite::Tabs_Objeto_Evaluativo(array(
					"".$urlLinkB."]"
					,"".$urlLinkB."]"
					,"".$urlLinkB."]Marca","".$urlLinkB."]",""));	
					
				}	
				
				$Query = "
				    SELECT PB.Id_Edu_Pregunta, PB.Nombre
					, PB.Nota FROM edu_pregunta PB
					WHERE PB.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
					ORDER BY PB.Orden ASC
				  
				";    
				$Where = ["Id_Edu_Objeto_Evaluativo"=>$Id_Edu_Objeto_Evaluativo];
				$Rows = ClassPdo::DCRows($Query,$Where,$Conection);

				$PanelB = '
				<div class="cart"   id="elementos_cart" >		
				
				    <table class="table">
                    <tr>	
                        <th width="80%">Pregunta</th>					
                        <th>Nota</th>					
                        <th>Acción</th>					
                    </tr>					
					';	
					
						foreach($Rows AS $Field){
							
							$listMn = " Crear Respuestas [".$UrlFile."/interface/Create_Respuesta".$urlLinkB."/Id_Edu_Pregunta/".$Field->Id_Edu_Pregunta."[animatedModal5[HXM[{";	
							$listMn .= " Editar [".$UrlFile."/interface/Create".$urlLinkB."/Id_Edu_Pregunta/".$Field->Id_Edu_Pregunta."[animatedModal5[HXM[{";	
							$listMn .= " Eliminar [".$UrlFile."/interface/r_elimina_pregunta".$urlLinkB."/Id_Edu_Pregunta/".$Field->Id_Edu_Pregunta."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Pregunta);		

							$PanelB .= '
								<tr>	
									<td width="80%">'.$Field->Nombre.'</td>					
									<td>'.$Field->Nota.'</td>					
									<td>'.$btnB.'</td>					
								</tr>					
								';	
								
							$Query = "
								SELECT PB.Id_Edu_Respuesta
								 , PB.Nombre
								 , PB.Respuesta_Correcta
								 FROM edu_respuesta PB
								WHERE PB.Id_Edu_Pregunta = :Id_Edu_Pregunta
								ORDER BY PB.Orden ASC
							  
							";    
							$Where = ["Id_Edu_Pregunta"=>$Field->Id_Edu_Pregunta];
							$Rows_B = ClassPdo::DCRows($Query,$Where,$Conection);
						    foreach($Rows_B AS $Field_B){
								
								$listMn = " Editar [".$UrlFile."/interface/Create_Respuesta".$urlLinkB."/Id_Edu_Pregunta/".$Field->Id_Edu_Pregunta."/Id_Edu_Respuesta/".$Field_B->Id_Edu_Respuesta."[animatedModal5[HXM[{";	
								$listMn .= " Eliminar [".$UrlFile."/interface/r_elimina_respuesta".$urlLinkB."/Id_Edu_Pregunta/".$Field->Id_Edu_Pregunta."/Id_Edu_Respuesta/".$Field_B->Id_Edu_Respuesta."[animatedModal5[HXM[{";	
								$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
								$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Pregunta."-".$Field_B->Id_Edu_Respuesta);	
							
								if($Field_B->Respuesta_Correcta == "SI" ){
									$Icon = '<i class="zmdi zmdi-check-circle"></i>';
								}else{
									$Icon = '<i class="zmdi zmdi-circle-o"></i>';									
								}
								
							    $PanelB .= '
								<tr bgcolor="#ebf5fd" style="    border-style: solid;border-width: 0px 0px 0px 5px;border-color: #5d7aa5;">	
									<td width="80%">'.$Field_B->Nombre.'</td>					
									<td> '.$Icon.' </td>					
									<td>'.$btnB.'</td>					
								</tr>					
								';				
								
							}	
								
						}
				
				$PanelB .= '
				    </table>
				</div>
						';	
			
				$btn = "<i class='zmdi zmdi-edit'></i> Crear Preguntas ]" .$UrlFile."/interface/Create".$urlLinkB."]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');
				
				$DCPanelTitle = DCPanelTitle("OBJETO EVALUATIVO","Examen con Nota Automática",$btn);
				
				
				$Style = "
					<style>
					#Examen .cart{
						height: 100%;
					}
					</style>
					
				";
				$Listado = "<div style='background-color:#fff;padding-bottom:100px;' id='Examen'>".$DCPanelTitle . $PanelB . $Style."</div>";
				
                DCWrite($Pestanas.$Listado);
                DCExit();							
				
                break;	
							
	

            case "Create":

				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;	
						
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn); 
	            $Id_Edu_Pregunta = $Parm["Id_Edu_Pregunta"];
				
	
				$Query = "
						SELECT 
						EC.Id_Edu_Tipo_Desarrollo, 
						EC.Id_Edu_Tipo_Objeto_Evaluativo
						FROM edu_objeto_evaluativo EC
						WHERE 
						EC.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
				";	
				$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Tipo_Objeto_Evaluativo = $Row->Id_Edu_Tipo_Objeto_Evaluativo;					
				
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Crud_Pregunta".$urlLinkB."/Id_Edu_Pregunta/".$Id_Edu_Pregunta;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive".$urlLinkB."/Id_Edu_Pregunta/".$Id_Edu_Pregunta;
				
				if(!empty($Id_Edu_Pregunta)){
				    $Name_Interface = "Editar Pregunta";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"Mensaje_Id","Form","Edu_Crud_Pregunta","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Pregunta";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				
				$Query = "
				    SELECT PB.Orden AS Id, PB.Orden AS Name
					, PB.Nota FROM edu_pregunta PB
					WHERE PB.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
					ORDER BY PB.Orden ASC
				";    
				$Where = ["Id_Edu_Objeto_Evaluativo"=>$Id_Edu_Objeto_Evaluativo];
				 

				if( $Id_Edu_Tipo_Objeto_Evaluativo  ==  1){
					$Query_A = "
					SELECT Id_Edu_Tipo_Pregunta AS Id, Nombre AS Name FROM edu_tipo_pregunta 
					WHERE Id_Edu_Tipo_Pregunta IN(?,?)
					";   					
			     	$Where_A = array_merge([1,2]);						
				}else{
				
					$Query_A = "
					SELECT Id_Edu_Tipo_Pregunta AS Id, Nombre AS Name FROM edu_tipo_pregunta 
					WHERE Id_Edu_Tipo_Pregunta IN(?,?,?)
					";   					
					$Where_A = array_merge([1,2,3]);						
				}
				
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Pregunta",$Query_A,$Where_A)
				     ,array("Orden",$Query,$Where)
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","Edu_Crud_Pregunta"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Crud_Pregunta",$Class,$Id_Edu_Pregunta,$PathImage,$Combobox,$Buttons,"Id_Edu_Pregunta");
				
			    $Html = DCModalForm($Name_Interface ,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;			



            case "r_elimina_pregunta":
		
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Pregunta = $Parm["Id_Edu_Pregunta"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;	
										
				$btn = "Aceptar ]" .$UrlFile ."/Process/DELETE/Obj/Edu_Crud_Pregunta".$urlLinkB."/Id_Edu_Pregunta/".$Id_Edu_Pregunta."]PanelA]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Cancelar ]" .$UrlFile ."/interface/Configura_Examen".$urlLinkB."]PanelA]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Pregunta",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;	
				

            case "r_elimina_respuesta":
		
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Pregunta = $Parm["Id_Edu_Pregunta"];
				$Id_Edu_Respuesta = $Parm["Id_Edu_Respuesta"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo;	
										
				$btn = "Aceptar ]" .$UrlFile ."/Process/DELETE/Obj/Edu_Respuesta_Crud".$urlLinkB."/Id_Edu_Pregunta/".$Id_Edu_Pregunta."/Id_Edu_Respuesta/".$Id_Edu_Respuesta."]PanelA]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Cancelar ]" .$UrlFile ."/interface/Configura_Examen".$urlLinkB."]PanelA]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Pregunta",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;	
				
				
				

            case "Create_Respuesta":

				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;
				
	            $Id_Edu_Pregunta = $Parm["Id_Edu_Pregunta"];
	            $Id_Edu_Respuesta = $Parm["Id_Edu_Respuesta"];
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Pregunta/".$Id_Edu_Pregunta;	
						
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Respuesta_Crud".$urlLinkB."/Id_Edu_Respuesta/".$Id_Edu_Respuesta;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive".$urlLinkB."/Id_Edu_Respuesta/".$Id_Edu_Respuesta;
				
				if(!empty($Id_Edu_Respuesta)){
				    $Name_Interface = "Editar Respuesta";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"Mensaje_Id","Form","Edu_Respuesta_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Respuesta";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				
				$Query = "
				    SELECT PB.Orden AS Id, PB.Orden AS Name
					 FROM edu_respuesta PB
					WHERE PB.Id_Edu_Pregunta = :Id_Edu_Pregunta
					ORDER BY PB.Orden ASC
				";    
				$Where = ["Id_Edu_Pregunta"=>$Id_Edu_Pregunta];
				
				$Combobox = array(
				     array("Orden",$Query,$Where)
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","Edu_Respuesta_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Respuesta_Crud",$Class,$Id_Edu_Respuesta,$PathImage,$Combobox,$Buttons,"Id_Edu_Respuesta");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
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


	
	public function ObjectDeleteRespuesta($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Respuesta = $Settings["Id_Edu_Respuesta"];
		
		$where = array('Id_Edu_Respuesta' =>$Id_Edu_Respuesta);
		$rg = ClassPDO::DCDelete('edu_respuesta', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		







    public function Ordenar_Respuesta($Settings) {
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
		
	

    public function Ordenar_Preguntas($Settings) {
      	global $Conection, $DCTimeHour,$NameTable;
		
			$Codigo_Item = $Settings["Id_Edu_Pregunta"];
			$Id_Edu_Objeto_Evaluativo = $Settings["Id_Edu_Objeto_Evaluativo"];
			
			$OrdenP = DCPost("Orden");
	
			$Query = " 
			SELECT Id_Edu_Pregunta, Orden FROM edu_pregunta 
			WHERE 
			Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo 
			
			ORDER BY Orden ASC 	
			"; 
			$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo];

			$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			$cont = 0;
			$SesionN = 0;
			$ubicacionB = 0;
			$OrdenBD = "";
							
			// var_dump($Registro);
			
			foreach ($Registro as $Reg) {		
				$CodigoItemBD = $Reg->Id_Edu_Pregunta;
				
			
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
					$where = array('Id_Edu_Pregunta' => $CodigoItemBD);
					$rg = ClassPDO::DCUpdate('edu_pregunta', $reg , $where, $Conection,"");
					
									
				} else {
					
					$OrdenBD = $Reg->Orden;
					$ubicacionB = ($OrdenBD * 100 + 10);
					$reg = array(
						'Orden' => $ubicacionB
					);
					$where = array('Id_Edu_Pregunta' => $CodigoItemBD);
					$rg = ClassPDO::DCUpdate('edu_pregunta', $reg , $where, $Conection,"");
				}	
				
			}	


				
				$Query = " 
			       SELECT Id_Edu_Pregunta, Orden FROM edu_pregunta  WHERE 
			       Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo  
				   ORDER BY Orden ASC
				"; 
				$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo];

			
			$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			$Cont = 0;
			foreach ($Registro as $Reg) {
				
				$Cont += 1;
				$reg = array(
					'Orden' => $Cont
				);
				
				$where = array('Id_Edu_Pregunta' => $Reg->Id_Edu_Pregunta);
				$rg = ClassPDO::DCUpdate('edu_pregunta', $reg , $where, $Conection,"");
				
			}	
					
	}		
		
	
}