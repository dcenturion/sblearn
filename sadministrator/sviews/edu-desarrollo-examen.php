<?php
require_once('./sviews/layout.php');
require_once(dirname(__FILE__).'/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Desarrollo_Examen{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-desarrollo-examen";
		$UrlFile_Tarea = "/sadministrator/edu-evaluacion-tarea";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$UrlFile_Foro = "/sadministrator/edu-examen-foro";
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
					
                            $Data = array();
							$Data['Id_Edu_Articulo'] = $Parm["Id_Edu_Articulo"];			
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
								
							if(empty($Parm["Id_Edu_Objeto_Evaluativo"])){
								
								$data = array(
								'Id_Edu_Articulo' =>  $Parm["Id_Edu_Articulo"],
								'Id_Edu_Almacen' =>  $Parm["key"],
								'Nombre' =>  DCPost("Nombre"),
								'Id_Edu_Formato' =>  7,
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
							// var_dump($Settings);
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
						
											
					case "Edu_Crud_Pregunta":
					        
						
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
							
							if(!empty($Id_Edu_Respuesta)){
							    $this->Ordenar_Respuesta($Parm);
							}
							
							if($Id_Edu_Tipo_Pregunta == 1){
								$reg = array(
								'Respuesta_Correcta' => ''
								);
								$where = array('Id_Edu_Pregunta' => $Id_Edu_Pregunta);
								$rg = ClassPDO::DCUpdate('edu_respuesta', $reg , $where, $Conection,"");
                            }
							
                            $Data = array();
							$Data['Orden'] = $Tot_Items;			
							$Data['Id_Edu_Pregunta'] = $Parm["Id_Edu_Pregunta"];			
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Respuesta"],"Id_Edu_Respuesta",$Data);  
							
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
						, EC.Id_Edu_Objeto_Evaluativo_Detalle
						, OE.Nombre
						, OE.Descripcion
						, OE.Id_Edu_Tipo_Objeto_Evaluativo
						, OE.Hora_Inicio
						, OE.Hora_Fin
						, OE.Fecha_Inicio
						, OE.Tiempo_Duracion
						, OE.Fecha_Fin
						, OE.Estado
						, OE.Intentos_Desarrollo
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
				$Descripcion_Objeto_Evaluativo = $Row->Descripcion;
				$Id_Edu_Objeto_Evaluativo_Detalle = $Row->Id_Edu_Objeto_Evaluativo_Detalle;
				
				
		        $Tipo_Desarrollo = $Row->Tipo_Desarrollo;	
		        $Id_Edu_Tipo_Desarrollo = $Row->Id_Edu_Tipo_Desarrollo;
		        $Fecha_Inicio = $Row->Fecha_Inicio;
		        $Fecha_Fin = $Row->Fecha_Fin;
		        $Hora_Inicio = $Row->Hora_Inicio;
		        $Hora_Fin = $Row->Hora_Fin;
		        $Tiempo_Duracion = $Row->Tiempo_Duracion;
		        $Estado_Maestro = $Row->Estado;
		        $Intentos_Desarrollo = $Row->Intentos_Desarrollo;
				
				
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
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen,"Id_Edu_Componente" =>$Id_Edu_Componente_S, "Id_Edu_Objeto_Evaluativo_Detalle"=>$Id_Edu_Objeto_Evaluativo_Detalle,"Id_Suscripcion"=>$Id_Suscripcion];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo_Grupo_Detalle = $Row->Id_Edu_Objeto_Evaluativo_Grupo_Detalle;
				$Id_Edu_Objeto_Evaluativo_Grupo = $Row->Id_Edu_Objeto_Evaluativo_Grupo;
				
			
				$Query = "
				SELECT  
				DC.Id_Edu_Evaluacion_Desarrollo_Cab
				, DC.Pregunta_Actual
				, DC.Matriz_Preguntas
				, DC.Estado
				, DC.Nota
				, DC.Revision
				, DC.Incluir_En_Certificacion
				, DC.Cont_Cantidad_Intentos
				
				FROM edu_evaluacion_desarrollo_cab DC  
				WHERE 
				DC.Id_Suscripcion = :Id_Suscripcion AND DC.Id_Edu_Objeto_Evaluativo =:Id_Edu_Objeto_Evaluativo
				";	
				$Where = ["Id_Suscripcion" =>$Id_Suscripcion,"Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo ];
				// var_dump($Where);
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Row->Id_Edu_Evaluacion_Desarrollo_Cab;
				$Estado = $Row->Estado;
				$Nota = $Row->Nota;
				$Revision = $Row->Revision;
				$Incluir_En_Certificacion = $Row->Incluir_En_Certificacion;
				$Cont_Cantidad_Intentos = $Row->Cont_Cantidad_Intentos;
				
                if($Estado_Maestro !== "Activo"){
					
					$Form1 = "<div style='background-color:#fff;padding:20px;'> ".$Estado_Maestro." EL ACCESO A ESTA ACTIVIDAD NO ESTÁ ACTIVO </div>";
					DCWrite( $Form1);
					DCExit();					
				}
				
				
				if(empty($Estado)){
					$Estado = "Pendiente";
				}
				
		        if(!empty($Nota) || $Nota == 0 ){
					
				}else{
					$Nota = " -- ";
				}
		
				
                if($Id_Edu_Tipo_Objeto_Evaluativo != 1){
					$Tipo_Nota = "MANUAL";
				}else{
					$Tipo_Nota = "AUTOMÁTICA";					
				}				

				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	
				
				

                if( $Id_Edu_Tipo_Desarrollo == 1 ){
					$Tiempo = '<p> <b> Hora_Inicio:</b> '.$Hora_Inicio.' </p> ';
					$Tiempo .= '<p> <b> Hora_Fin:</b> '.$Hora_Fin.' </p> ';
				}				
                if( $Id_Edu_Tipo_Desarrollo == 2 ){
					$Tiempo = '<p> <b> Tiempo Duración: </b>  '.$Tiempo_Duracion.' min</p> ';
				}				
								
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/";	

				if($Intentos_Desarrollo == 0 || empty($Intentos_Desarrollo ) ){  $Intentos_Desarrollo = 1;  }

			
				
				if( $Estado !== "Finalizado" ){
					
					if($Id_Edu_Tipo_Objeto_Evaluativo == 3){
						$btn = " Desarrollar Tarea ]" .$UrlFile_Tarea ."/interface/List".$urlLinkB."]PanelA]HXM]]btn btn-info Btn_Evaluacion}";				
						$Button = DCButton($btn, 'botones1', 'sys_form_evaluacion');	
						
				    }elseif($Id_Edu_Tipo_Objeto_Evaluativo == 4){
						
						$btn = " Presiona aquí para participar del FORO ]" .$UrlFile_Foro ."/interface/List".$urlLinkB."/request/on/]]HREF]]btn btn-info Btn_Evaluacion}";				
						$Button = DCButton($btn, 'botones1', 'sys_form_evaluacion');	
											
					}else{
						if(empty($Incluir_En_Certificacion)){	
							if( $Cont_Cantidad_Intentos == 0){	
							
								$btn = " Presiona aquí para Iniciar la Evaluación ]" .$UrlFile ."/interface/Inicio_Desarrollo_Evaluacion".$urlLinkB."procces/reinicio/]PanelA]HXM]]btn btn-info Btn_Evaluacion}";				
								$Button = DCButton($btn, 'botones1', 'sys_form_evaluacion');						    

							}else{
							
								$btn = " Presiona aquí para Reiniciar la Evaluación ]" .$UrlFile ."/interface/Inicio_Desarrollo_Evaluacion".$urlLinkB."procces/reinicio/]PanelA]HXM]]btn btn-info Btn_Evaluacion}";				
								$Button = DCButton($btn, 'botones1', 'sys_form_evaluacion');	
							}	
						}		
					}
					
				}else{
					
					if($Id_Edu_Tipo_Objeto_Evaluativo == 3){
						$btn = " Ver Tarea Enviada ]" .$UrlFile_Tarea ."/interface/ver_tarea/".$urlLinkB."]PanelA]HXM]]btn btn-info}";				
						$Button = DCButton($btn, 'botones1', 'sys_form_evaluacion');	
						
					}elseif($Id_Edu_Tipo_Objeto_Evaluativo == 4){	
					
					}else{
						
 				        if($Cont_Cantidad_Intentos == 0 || empty($Cont_Cantidad_Intentos ) ){  $Cont_Cantidad_Intentos = 1;  }	
						 
						if($Intentos_Desarrollo == $Cont_Cantidad_Intentos ){	
						    
							
						}else{
							if(empty($Incluir_En_Certificacion)){	
								$btn = " Presiona aquí para Reiniciar la Evaluación ]" .$UrlFile ."/interface/Inicio_Desarrollo_Evaluacion".$urlLinkB."procces/reinicio/]PanelA]HXM]]btn btn-info Btn_Evaluacion}";				
								$Button = DCButton($btn, 'botones1', 'sys_form_evaluacion');
                            }								
					    }

						

					}					
					
				}
			
	
				$Date_System = DCDate();
				if($Fecha_Inicio > $Date_System ){
					$Button = "";
				}
				if($Fecha_Fin < $Date_System ){
					$Button = "";
				}		



                // DCWrite($Id_Edu_Tipo_Desarrollo);
                if( $Id_Edu_Tipo_Desarrollo == 1 ){
					
						$Time_System = DCTime();
						$Hora_Inicio = strtotime($Hora_Inicio);
						$Hora_Fin = strtotime($Hora_Fin);
						
						if($Hora_Inicio > $Time_System ){
							$Button = "";
						}				
						
						if($Hora_Fin < $Time_System ){
							$Button = "";
						}							
				}
				
				
			     // $Hora_Inicio = $Row->Hora_Inicio;
		        // $Hora_Fin = $Row->Hora_Fin;	
				
				if( empty($Intentos_Desarrollo)  ||  $Intentos_Desarrollo == 0){
					$Intentos_Desarrollo = 1;
				}
				
				if( $Revision == "Finalizado" && empty($Incluir_En_Certificacion) ){
					
							$btn = " Confirmo que he finalizado ]" .$UrlFile ."/interface/Cierra_Acta".$urlLinkB."procces/reinicio/]PanelA]HXM]]btn btn-danger Btn_Evaluacion}";				
							$Button_Final = DCButton($btn, 'botones1', 'sys_form_evaluacion_cerrar');					
							$Button_Final .= " <p style='color: red;text-align: center;padding: 5px 0px;font-weight: bold;'> Si no confirmas, la nota de la evaluación no se reflejará en el acta de notas.</p> ";					
				}
				
				
				$Form1 = '
					<div class="row" style="margin:0px;padding:0px 20px 20px 10px;">
	                    <div class="col-12 col-md-12" style="padding:5px 20px 20px 20px;">
						    <table class="table_2">
							<tr> <td colspan="2"> <span><h3>  DESARROLLA LA EVAUACIÓN  </h3></td></tr>
						    <tr> <td colspan="2"> <h4 style="color:#3887c9;"> '.$Nombre_Objeto_Evaluativo.' </h4></td></tr>
						    <tr> <td colspan="2"> <p> '.$Descripcion_Objeto_Evaluativo.' </p> </td></tr>
						
							<tr> <td><p> <b style="color:#2b92e9;font-size:1.2em;"> Nro. de Intentos Pendientes: </b></p></td><td><p> <b style="color:#2b92e9;font-size:1.2em;">  '.( $Intentos_Desarrollo - $Cont_Cantidad_Intentos ).'</b> </p></td></tr>
							 <tr> <td><p> <b style="color:#2b92e9;font-size:1.2em;"> Nota Obtenida:  </b></p></td><td><p style="color:#2b92e9;font-size:1.2em;"> '.$Nota.' </p></td></tr>														
							<tr> <td colspan="2"> <p> '.$Button.' </p> </td></tr>
							
							<tr> <td colspan="2"> <p> '.$Button_Final.' </p> </td></tr>
							
						    <tr> <td><p> <b> Estado de Revisión:  </b></p></td><td><p>  '.$Revision.' </p></td></tr>							
						    <tr> <td><p> <b> Estado de Desarrollo:  </b></p></td><td><p> '.$Estado.' </p></td></tr>
						    <tr> <td><p> <b> Tipo de Revisión:  </b></p></td><td><p> '.$Tipo_Nota.' </p></td></tr>
						   
						    <tr> <td><p> <b> Fecha_Inicio: </b> </p></td><td><p> '.$Fecha_Inicio.'  </p></td></tr>
						    <tr> <td><p> <b> Fecha_Fin: </b> </p></td><td><p>  '.$Fecha_Fin.'  </p></td></tr>
						    <tr> <td><p> <b> Tipo de Desarrollo:</b> </p></td><td><p> '.$Tipo_Desarrollo.' </p></td></tr>
						    
							<tr> <td colspan="2"><p> '.$Tiempo.' </p></td></tr>
							<tr> <td colspan="2"><p> '.$Id_Edu_Evaluacion_Desarrollo_Cab.' </p></td></tr>
						    <table>
						</span>
						</div>	
                        					
				    </div>
				
				';

				$Script = '<script>
						
						stop();
						
				';
				$Script .= "</script>";
					
				$Form1 = "<div style='background-color:#fff;'>".$DCPanelTitle . $Form1. "</div>";
				
		
				if($Parm["request"] == "on"){
					$LayoutB  = new LayoutB();
					
					DCWrite($LayoutB->main($Pestanas . $Form1 ,$Row_Producto));
					
				}else{
					DCWrite($Pestanas . $Form1 . $Script);		
				}
                break;				
                DCExit();
				
                break;	
				
            case "Inicio_Desarrollo_Evaluacion":
			    
				DCCloseModal();
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				$Estado_Pregunta = $Parm["Estado_Pregunta"];
				$Envia = $Parm["Envia"];
				$var_Url = $Parm["var_Url"];

				$Query = "
				SELECT 
				EOE.Nombre,
				EOE.Tiempo_Duracion,
				EOE.Hora_Inicio,
				EOE.Hora_Fin,
				EOE.Id_Edu_Tipo_Desarrollo,
				EOE.Intentos_Desarrollo
				
				FROM edu_objeto_evaluativo EOE 
				WHERE 
				EOE.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo ";	
				$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre_OE = $Row->Nombre;
				$Tiempo_Duracion_OE = $Row->Tiempo_Duracion;
				$Hora_Inicio = $Row->Hora_Inicio;
				$Hora_Fin = $Row->Hora_Fin;
				$Id_Edu_Tipo_Desarrollo = $Row->Id_Edu_Tipo_Desarrollo;
				$Intentos_Desarrollo = $Row->Intentos_Desarrollo;
				
				if($Id_Edu_Tipo_Desarrollo == 1){
					$Tiempo_Duracion_OE = (strtotime($Hora_Fin) - strtotime($Hora_Inicio))/60;
				}
				

				$Reg = $this->Preguntas_Evaluacion($Parm);
				
				
				$Matriz_Preguntas = json_decode($Reg->Matriz_Preguntas);
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Reg->Id_Edu_Evaluacion_Desarrollo_Cab;				

			
                // procces/reinicio				
				
				// var_dump($Matriz_Preguntas);
				$Pregunta_Actual = $Reg->Pregunta_Actual;	
				// var_dump($Pregunta_Actual);

				$Tiempo_Estado = $Reg->Tiempo_Estado;
				
				$Primera_Pregunta = $Matriz_Preguntas[0]->Id_Edu_Pregunta;

				$Contador_Reg = count($Matriz_Preguntas);
				$Ultima_Pregunta = $Matriz_Preguntas[$Contador_Reg -  1]->Id_Edu_Pregunta;				
			
				$Nombre_Pregunta =$Matriz_Preguntas[$Pregunta_Actual]->Nombre;
				$Id_Edu_Pregunta =$Matriz_Preguntas[$Pregunta_Actual]->Id_Edu_Pregunta;
				$Descripcion = $Matriz_Preguntas[$Pregunta_Actual]->Descripcion;

				$Id_Form = "Form_".$Id_Edu_Objeto_Evaluativo;
				
				$Parm["Id_Edu_Pregunta"] = $Id_Edu_Pregunta;
				$Parm["Id_Form"] = $Id_Form;
				$Parm["Id_Edu_Evaluacion_Desarrollo_Cab"] = $Id_Edu_Evaluacion_Desarrollo_Cab;
				


							
				$urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Pregunta/".$Id_Edu_Pregunta."/Id_Form/".$Id_Form."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Id_Edu_Evaluacion_Desarrollo_Cab;	
								
				$Pregunta_Actual_P = $Pregunta_Actual + 1 ;
				$Pregunta_Actual_A = $Pregunta_Actual - 1 ;
				
	
				$Reg_b = $this->Respuestas_Evaluacion($Parm);
				
				

                if( $Id_Edu_Pregunta == $Ultima_Pregunta ){
					
					$btn = " Pregunta Anterior ]" .$UrlFile ."/interface/Inicio_Desarrollo_Evaluacion_Atras".$urlLinkB."/Estado_Pregunta/".$Pregunta_Actual_A."/Envia/Atras]PanelA]FORM_JSA]".$Parm["Id_Form"]."]btn btn-default Btn_Evaluacion_2]timer}";				
					

					$btn .= " Finalizar Evaluación  ]" .$UrlFile ."/interface/Inicio_Desarrollo_Evaluacion_Next".$urlLinkB."/Estado_Pregunta/".$Pregunta_Actual_P."/Envia/Next]PanelA]FORM_JSA]".$Parm["Id_Form"]."]btn btn-info]timer}";									
					


					$Button = DCButton($btn, 'botones1', 'sys_form_examen');	
					
				}else{
					
					if( $Id_Edu_Pregunta == $Primera_Pregunta ){
						
						$btn = " Siguiente Pregunta ]" .$UrlFile ."/interface/Inicio_Desarrollo_Evaluacion_Next".$urlLinkB."/Estado_Pregunta/".$Pregunta_Actual_P."/Envia/Next]PanelA]FORM_JSA]".$Parm["Id_Form"]."]btn btn-default Btn_Evaluacion_2]timer}";				
						$Button = DCButton($btn, 'botones1', 'sys_form_examen');
						
					}else{
					
						$btn = " Pregunta Anterior ]" .$UrlFile ."/interface/Inicio_Desarrollo_Evaluacion_Atras".$urlLinkB."/Estado_Pregunta/".$Pregunta_Actual_A."/Envia/Atras]PanelA]FORM_JSA]".$Parm["Id_Form"]."]btn btn-default Btn_Evaluacion_2]timer}";				
						$btn .= " Siguiente Pregunta ]" .$UrlFile ."/interface/Inicio_Desarrollo_Evaluacion_Next".$urlLinkB."/Estado_Pregunta/".$Pregunta_Actual_P."/Envia/Next]PanelA]FORM_JSA]".$Parm["Id_Form"]."]btn btn-default Btn_Evaluacion_2]timer}";				
						$Button = DCButton($btn, 'botones1', 'sys_form_examen');
						
				    }
					
	            }

                if( $Id_Edu_Pregunta == $Primera_Pregunta){
					
					$Script = '<script>
					
						
							start('.$Tiempo_Duracion_OE.','.$Id_Edu_Evaluacion_Desarrollo_Cab.','.$Tiempo_Estado.','.$Id_Edu_Componente_S.','.$Id_Edu_Almacen.');
							
							
					';
					$Script .= "</script>";
				}
                 
				$Tiempo_Duracion_OE = DCMinHoras($Tiempo_Duracion_OE,"round");
				
				$Form1 = '
					<div class="row" style="margin:0px;padding:0px 20px 20px 10px;">
	                    <div class="col-12 col-md-12" style="padding:5px 20px 20px 20px;">
						    <h3 style="font-size:1em;">  '.$Nombre_OE.' <br> 
							<b style="color:#b1b0b3;">Duración de la Actividad: '.$Tiempo_Duracion_OE.'</b>
							</h3>
						    <h4 style="color:#3887c9;"> '.$Nombre_Pregunta.' </h4>
						    <p> '.$Descripcion.'  </p><br>
						    <form id="'.$Parm["Id_Form"].'" method="post"  name="'.$Parm["Id_Form"].'">
							'.$Reg_b.'
						    </form>
							'.$Button.'
						</div>	
                        					
				    </div>
				
				';


				$Form1 = "<div style='background-color:#fff;'>". $Form1 . $Script . "</div>";
                DCWrite($Pestanas . $Form1);
                DCExit();
				
                break;	
				
				
            case "Cierra_Acta":
			    
				DCCloseModal();
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];

				
				

				$Query = "
					    
						SELECT 
						EC.Id_Edu_Objeto_Evaluativo
						, EC.Id_Edu_Objeto_Evaluativo_Detalle
						, OE.Nombre
						, OE.Descripcion
						, OE.Id_Edu_Tipo_Objeto_Evaluativo
						, OE.Hora_Inicio
						, OE.Hora_Fin
						, OE.Fecha_Inicio
						, OE.Tiempo_Duracion
						, OE.Fecha_Fin
						, OE.Estado
						, OE.Intentos_Desarrollo
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
				$Descripcion_Objeto_Evaluativo = $Row->Descripcion;
				$Id_Edu_Objeto_Evaluativo_Detalle = $Row->Id_Edu_Objeto_Evaluativo_Detalle;
				
				
		        $Tipo_Desarrollo = $Row->Tipo_Desarrollo;	
		        $Id_Edu_Tipo_Desarrollo = $Row->Id_Edu_Tipo_Desarrollo;
		        $Fecha_Inicio = $Row->Fecha_Inicio;
		        $Fecha_Fin = $Row->Fecha_Fin;
		        $Hora_Inicio = $Row->Hora_Inicio;
		        $Hora_Fin = $Row->Hora_Fin;
		        $Tiempo_Duracion = $Row->Tiempo_Duracion;
		        $Estado_Maestro = $Row->Estado;
		        $Intentos_Desarrollo = $Row->Intentos_Desarrollo;
				
				
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
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen,"Id_Edu_Componente" =>$Id_Edu_Componente_S, "Id_Edu_Objeto_Evaluativo_Detalle"=>$Id_Edu_Objeto_Evaluativo_Detalle,"Id_Suscripcion"=>$Id_Suscripcion];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo_Grupo_Detalle = $Row->Id_Edu_Objeto_Evaluativo_Grupo_Detalle;
				$Id_Edu_Objeto_Evaluativo_Grupo = $Row->Id_Edu_Objeto_Evaluativo_Grupo;
				
				
				$Query = "
					SELECT 
					EOED.Id_Edu_Evaluacion_Desarrollo_Cab 
					FROM edu_evaluacion_desarrollo_cab EOED
					INNER JOIN suscripcion SUS ON EOED.Id_Suscripcion = SUS.Id_Suscripcion
					INNER JOIN  user_miembro UM ON SUS.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User			
				
									
					WHERE 
					EOED.Id_Suscripcion = :Id_Suscripcion
					AND EOED.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
					ORDER BY EOED.Id_Edu_Evaluacion_Desarrollo_Cab ASC  
				";	
				$Where = ["Id_Suscripcion" =>$Id_Suscripcion, "Id_Edu_Objeto_Evaluativo"=>$Id_Edu_Objeto_Evaluativo ];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Row->Id_Edu_Evaluacion_Desarrollo_Cab;	
				
			
				$Query = "
					SELECT 
					SUM( EOED.Nota ) AS SumNota
					FROM edu_evaluacion_desarrollo_cab EOED
					INNER JOIN suscripcion SUS ON EOED.Id_Suscripcion = SUS.Id_Suscripcion
					INNER JOIN  user_miembro UM ON SUS.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User			
				
									
					WHERE 
					EOED.Id_Suscripcion = :Id_Suscripcion
					ORDER BY EOED.Id_Edu_Evaluacion_Desarrollo_Cab ASC  
				";	
				$Where = ["Id_Suscripcion" =>$Id_Suscripcion ];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$SumNota = $Row->SumNota;				
				
				
				
				
			
				$Query = "
					SELECT 
					SUM( EOED.Nota ) AS SumNota
					FROM edu_evaluacion_desarrollo_cab EOED
					INNER JOIN suscripcion SUS ON EOED.Id_Suscripcion = SUS.Id_Suscripcion
					INNER JOIN  user_miembro UM ON SUS.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User			
				
									
					WHERE 
					EOED.Id_Suscripcion = :Id_Suscripcion
					ORDER BY EOED.Id_Edu_Evaluacion_Desarrollo_Cab ASC  
				";	
				$Where = ["Id_Suscripcion" =>$Id_Suscripcion ];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$SumNota = $Row->SumNota;		

				// $Revisio = "La evaluación fue revisada automáticamente ";					
				$reg = array(
					'Incluir_En_Certificacion' => 'SI'
				);	
				$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
				
				// var_dump($where);	
				$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection,"");	
	// var_dump($rg);


				$Query ="
				
						SELECT 
						EOED.Id_Suscripcion
						, UM.Nombre
						, SUS.Id_Modalidad_Venta_Curso
						, EOED.Nota
						, UM.Id_Edu_Pais
						FROM edu_evaluacion_desarrollo_cab EOED
						INNER JOIN suscripcion SUS ON EOED.Id_Suscripcion = SUS.Id_Suscripcion
						INNER JOIN  user_miembro UM ON SUS.Id_User = UM.Id_User_Miembro
						INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User								
						WHERE 
						EOED.Id_Edu_Evaluacion_Desarrollo_Cab= :Id_Edu_Evaluacion_Desarrollo_Cab
						ORDER BY EOED.Id_Edu_Evaluacion_Desarrollo_Cab ASC  
					
					"; 
				$Where = ["Id_Edu_Evaluacion_Desarrollo_Cab"=>$Id_Edu_Evaluacion_Desarrollo_Cab];
		
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);
				$Id_Suscripcion=$Row->Id_Suscripcion;	
				$Nombre = $Row->Nombre;	
				$Id_Modalidad_Venta_Curso = $Row->Id_Modalidad_Venta_Curso;	
				$Id_Edu_Pais = $Row->Id_Edu_Pais;	
				


				$Query ="
				
						SELECT 
						EA.Horas_Lectivas
                        , EA.Fecha_Publicacion, EA.Fecha_Fin_Curso
						FROM edu_articulo EA							
						WHERE 
						EA.Id_Edu_Articulo= :Id_Edu_Articulo
					
					"; 
				$Where = ["Id_Edu_Articulo"=>$Id_Edu_Articulo];
		
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);
				$Horas_Lectivas=$Row->Horas_Lectivas;	
				$Fecha_Inicio_C=$Row->Fecha_Publicacion;	
				$Fecha_Fin_C=$Row->Fecha_Fin_Curso;	
			
				
				

				$Query ="
				
						SELECT 
						 COUNT(*) AS Count_Obj_Evaluacion
						FROM edu_objeto_evaluativo_detalle EOED
						INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
						WHERE 
						EOED.Entity= :Entity
						AND EOED.Id_Edu_Articulo= :Id_Edu_Articulo
		
								
						ORDER BY EOE.Id_Edu_Objeto_Evaluativo ASC  
					
					"; 
				$Where = ["Entity"=>$Entity,"Id_Edu_Articulo"=>$Id_Edu_Articulo];

				$Row = ClassPdo::DCRow($Query,$Where,$Conection);
				$Count_Obj_Evaluacion=$Row->Count_Obj_Evaluacion;
				
				$Query ="
					  SELECT 
					  COUNT(*) AS Evaluaciones_Desarrolladas

					  FROM edu_evaluacion_desarrollo_cab EEDC
					  INNER JOIN edu_objeto_evaluativo EOE ON EEDC.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
					  INNER JOIN edu_objeto_evaluativo_detalle EOED ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
					  WHERE 
					  EOED.Entity = :Entity
					  AND EOED.Id_Edu_Articulo = :Id_Edu_Articulo
					  AND   EEDC.Id_Suscripcion =  :Id_Suscripcion 
					
					"; 
				$Where = ["Entity"=>$Entity,"Id_Edu_Articulo"=>$Id_Edu_Articulo,"Id_Suscripcion"=>$Id_Suscripcion];
		
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);
				$Evaluaciones_Desarrolladas=$Row->Evaluaciones_Desarrolladas;	
				

				$Query = "
					SELECT MAX( EEDC.Id_Edu_Objeto_Evaluativo ) AS REG_ULT
					FROM edu_evaluacion_desarrollo_cab EEDC
					INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion = SUS.Id_Suscripcion
					INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EEDC.Id_Edu_Objeto_Evaluativo
					WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_Suscripcion = :Id_Suscripcion AND EEDC.Estado = :Estado;
				";	
				$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Id_Suscripcion" => $Id_Suscripcion, "Estado" => "Finalizado"];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$REG_ULT = $Row->REG_ULT;	
				
				
				$Query = "
					SELECT 
					EEDC.Id_Edu_Objeto_Evaluativo 
					,  EEDC.Date_Time_Creation
					FROM edu_evaluacion_desarrollo_cab EEDC
					INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion = SUS.Id_Suscripcion
					INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EEDC.Id_Edu_Objeto_Evaluativo
					WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_Suscripcion = :Id_Suscripcion AND EEDC.Estado = :Estado 
					AND EEDC.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo ;
				";	
				$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen
				, "Id_Suscripcion" => $Id_Suscripcion
				, "Estado" => "Finalizado"
				,"Id_Edu_Objeto_Evaluativo"=>$REG_ULT];
				$Row_B = ClassPdo::DCRow($Query,$Where,$Conection);	



			    if($Id_Modalidad_Venta_Curso == 1 ){
					// $Modalidad_Venta_Curso = "En_Vivo";
					$Fecha_Inicio = $Fecha_Inicio_C;						
					$Fecha_Fin = $Fecha_Fin_C;					
				}else{
					$Fecha_Inicio = "";						
					$Fecha_Fin = $Row_B->Date_Time_Creation;
					
				}

	
							
				if( $Count_Obj_Evaluacion == $Evaluaciones_Desarrolladas ){
					

					if( $Entity == 3 || $Entity == 7 ){
						
							
						$Query ="
							  SELECT 
							  EGC.Id_Edu_Gestion_Certificado

							  FROM edu_gestion_certificado EGC
							  WHERE 
							  EGC.Entity = :Entity
							  AND EGC.Id_Edu_Almacen = :Id_Edu_Almacen
							  AND ( EGC.Tipo_Producto IS NULL OR EGC.Tipo_Producto = :Tipo_Producto )
							
							"; 
						$Where = ["Entity"=>$Entity,"Id_Edu_Almacen"=>$Id_Edu_Almacen,"Tipo_Producto"=>""];
				
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);
						$Id_Edu_Gestion_Certificado = $Row->Id_Edu_Gestion_Certificado;
						
	
							
							if(empty($Id_Edu_Gestion_Certificado)){

								$data = array(
								'Tipo_Producto' =>  "curso",
								'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
								'Tipo_Certificado' =>  "Predefinido",
								'Entity' => $Entity,	
								'Id_User_Update' => $User,
								'Id_User_Creation' => $User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
								$Return = ClassPDO::DCInsert("edu_gestion_certificado", $data, $Conection,"");

								$Id_Edu_Gestion_Certificado = $Return["lastInsertId"];
							
							}					
							
							
							if($Id_Modalidad_Venta_Curso == 1 ){
								$Modalidad_Venta_Curso = "En_Vivo";
							}else{
								$Modalidad_Venta_Curso = "Grabado";
								
							}
							
							$Nota =  $SumNota / $Count_Obj_Evaluacion;
							
							if($Nota < 14 ){
								$Estado_Academico = "participado";							
							}else{
								$Estado_Academico = "aprobado";							
							}

							
							if($Id_Modalidad_Venta_Curso == 1 && $Id_Edu_Pais  == 1 ){	// solo para peruanos que adquierene cursos en vivo
								$Id_Tipo_Certificado = 2; // fisico y digital
							}else{
								$Id_Tipo_Certificado = 1;	// digital						
							}

							
									
								$Query ="
									  SELECT 
									  EGC.Id_Edu_Certificado

									  FROM edu_certificado EGC
									  WHERE 
									  EGC.Id_Suscripcion = :Id_Suscripcion
									  AND EGC.Id_Edu_Almacen = :Id_Edu_Almacen
									
									"; 
								$Where = ["Id_Suscripcion"=>$Id_Suscripcion,"Id_Edu_Almacen"=>$Id_Edu_Almacen];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);
								$Id_Edu_Certificado = $Row->Id_Edu_Certificado;
								
								
								

								if(empty($Id_Edu_Certificado)){
										
									$data = array(
									'Nombres' =>  $Nombre,
									'Id_Edu_Gestion_Certificado' =>  $Id_Edu_Gestion_Certificado,
									'Modalidad_Venta_Curso' =>  $Modalidad_Venta_Curso,
									'Estado_Academico' =>  $Estado_Academico,
									'Nota' =>  $Nota,
									'Horas_Lectivas' =>  $Horas_Lectivas,
									'Estado_Edicion_Datos_Envio' =>  "Pendiente",
									'Estado_Emision_Certificado_Fisico' =>  "Pendiente",
									'Id_Tipo_Certificado' =>  $Id_Tipo_Certificado, //depende la venta
									'Estado_Edicion_Datos_Certificado' => "Pendiente",
									'Fecha_Fin' =>  $Fecha_Fin,
									'Fecha_Inicio' =>  $Fecha_Inicio,
									'Estado_Emision_Certificado_Digital' =>  "Pendiente",
									'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
									'Id_Suscripcion' =>  $Id_Suscripcion,
									'Entity' => $Entity,	
									'Id_User_Update' => $User,
									'Id_User_Creation' => $User,
									'Date_Time_Creation' => $DCTimeHour,
									'Date_Time_Update' => $DCTimeHour
									);
									$Return = ClassPDO::DCInsert("edu_certificado", $data, $Conection,"");
                                
								}else{
									
									$data = array(
									'Nombres' =>  $Nombre,
									'Id_Edu_Gestion_Certificado' =>  $Id_Edu_Gestion_Certificado,
									'Modalidad_Venta_Curso' =>  $Modalidad_Venta_Curso,
									'Estado_Academico' =>  $Estado_Academico,
									'Nota' =>  $Nota,
									'Horas_Lectivas' =>  $Horas_Lectivas,
									'Estado_Edicion_Datos_Envio' =>  "Pendiente",
									'Estado_Emision_Certificado_Fisico' =>  "Pendiente",
									'Id_Tipo_Certificado' =>  $Id_Tipo_Certificado, //depende la venta
									'Estado_Edicion_Datos_Certificado' => "Pendiente",
									'Fecha_Fin' =>  $Fecha_Fin,
									'Fecha_Inicio' =>  $Fecha_Inicio,
									'Estado_Emision_Certificado_Digital' =>  "Pendiente",
									'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
									'Id_Suscripcion' =>  $Id_Suscripcion,
									'Entity' => $Entity,	
									'Id_User_Update' => $User,
									'Id_User_Creation' => $User,
									'Date_Time_Creation' => $DCTimeHour,
									'Date_Time_Update' => $DCTimeHour
									);	
									$where = array('Id_Edu_Certificado' => $Id_Edu_Certificado);
									$rg = ClassPDO::DCUpdate('edu_certificado', $data , $where, $Conection,"");
									
								}
							
					}	
						
					
				}

				
				

			

				$urlLinkB_2 = "/sadministrator/edu-desarrollo-examen/interface/Crea_Objeto_Evaluativo/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S;	
				$Settings = array();
				$Settings['Url'] = $urlLinkB_2;
				$Settings['Screen'] = "PanelA";
				$Settings['Type_Send'] = "HXM";
				DCRedirectJSSP($Settings);		
                DCExit();
				
                break;					
				

            case "Inicio_Desarrollo_Evaluacion_Next":
			    
				DCCloseModal();
				
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				$Estado_Pregunta = $Parm["Estado_Pregunta"];
				$Id_Edu_Pregunta = $Parm["Id_Edu_Pregunta"];
				$var_Url = $Parm["var_Url"];
				$Id_Form = $Parm["Id_Form"];
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Form/".$Id_Form."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Id_Edu_Evaluacion_Desarrollo_Cab."/var_Url/".$var_Url;	
                         

						 
				$Query = "
				SELECT 
				OE.Id_Edu_Tipo_Pregunta
				FROM edu_pregunta OE 
				WHERE 
				OE.Id_Edu_Pregunta = :Id_Edu_Pregunta  ";	
				$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Tipo_Pregunta = $Row->Id_Edu_Tipo_Pregunta;
				

				$Query = "
				SELECT 
				EOE.Nombre,
				EOE.Tiempo_Duracion,
				EOE.Hora_Inicio,
				EOE.Hora_Fin,
				EOE.Id_Edu_Tipo_Desarrollo,
				EOE.Intentos_Desarrollo
				
				FROM edu_objeto_evaluativo EOE 
				WHERE 
				EOE.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo ";	
				$Where = ["Id_Edu_Objeto_Evaluativo" =>$Id_Edu_Objeto_Evaluativo];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Intentos_Desarrollo = $Row->Intentos_Desarrollo;
				// $Tiempo_Duracion_OE = $Row->Tiempo_Duracion;				

				if($Id_Edu_Tipo_Pregunta == 1){ 
						
						$Parm_Post = "".$Parm["Id_Form"]."_".$Parm["Id_Edu_Pregunta"]."";
						
						$Respuesta = DCPost($Parm_Post);
						
						if(empty($Respuesta)){
							
							$Estado_Pregunta = $Estado_Pregunta - 1;
							$Settings = array();
							$Settings['Url'] = $UrlFile ."/interface/Inicio_Desarrollo_Evaluacion".$urlLinkB."/Estado_Pregunta/".Estado_Pregunta;
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJSSP($Settings);	
							exit();					
						}
				        $Parm["Respuesta_Seleccionada"] = DCPost($Parm_Post);						

				}

				if($Id_Edu_Tipo_Pregunta == 2){ 
						
						$Parm_Post = "".$Parm["Id_Form"]."_".$Parm["Id_Edu_Pregunta"]."";
						$Respuesta = DCPost($Parm_Post);
						
						if(empty($Respuesta)){
							
							$Estado_Pregunta = $Estado_Pregunta - 1;
							$Settings = array();
							$Settings['Url'] = $UrlFile ."/interface/Inicio_Desarrollo_Evaluacion".$urlLinkB."/Estado_Pregunta/".Estado_Pregunta;
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJSSP($Settings);	
							exit();					
						}				
                  
				}
				
				
				
				if($Id_Edu_Tipo_Pregunta == 3){ 
					
						$Text_Area = "".$Parm["Id_Form"]."_".$Parm["Id_Edu_Pregunta"]."_Textarea";
					    $Text_Area = trim(DCPost($Text_Area));
						$File = $_FILES["".$Parm["Id_Form"]."_".$Parm["Id_Edu_Pregunta"]."_File"]["name"];
						$Cont_Validador = 0;
						if(!empty($Text_Area)){ $Cont_Validador += 1; }
						if(!empty($File)){ $Cont_Validador += 1; }
				
						if($Cont_Validador == 0){
							
							$Estado_Pregunta = $Estado_Pregunta - 1;
							$Settings = array();
							$Settings['Url'] = $UrlFile ."/interface/Inicio_Desarrollo_Evaluacion".$urlLinkB."/Estado_Pregunta/".Estado_Pregunta;
							$Settings['Screen'] = "PanelA";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJSSP($Settings);	
							exit();					
						}
				}
				
				$Parm["Pregunta_Actual"] = $Estado_Pregunta;
				
				$this->Guarda_Respuesta($Parm);
				// exit();
				
				$Query = "
				SELECT 
				EDC.Matriz_Preguntas
				, EDC.Id_Edu_Evaluacion_Desarrollo_Cab
				, EDC.Cont_Cantidad_Intentos
				FROM edu_evaluacion_desarrollo_cab EDC 
				WHERE 
				EDC.Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab ";	
				$Where = ["Id_Edu_Evaluacion_Desarrollo_Cab" =>$Id_Edu_Evaluacion_Desarrollo_Cab];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				
			
				$Matriz_Preguntas = $Row->Matriz_Preguntas;	
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Row->Id_Edu_Evaluacion_Desarrollo_Cab;	
				$Cont_Cantidad_Intentos = $Row->Cont_Cantidad_Intentos;	
				
				$Matriz_Preguntas = json_decode($Row->Matriz_Preguntas);
					
				$Primera_Pregunta = $Matriz_Preguntas[0]->Id_Edu_Pregunta;
				$Contador_Reg = count($Matriz_Preguntas);
				$Ultima_Pregunta = $Matriz_Preguntas[$Contador_Reg -  1]->Id_Edu_Pregunta;	

                				
			 
				if( empty($Intentos_Desarrollo) || $Intentos_Desarrollo == 0){ 
				    $Intentos_Desarrollo = 1;
				}
				
				if( empty($Cont_Cantidad_Intentos) || $Cont_Cantidad_Intentos == 0){ 
				    $Cont_Cantidad_Intentos = 1;
				}
				
				if($Ultima_Pregunta == $Id_Edu_Pregunta){ 
				
				       				////Cuenta intentos de desarrolo de examen
						
						$Query = "
							 SELECT EVOR.Cont_Cantidad_Intentos 
							 FROM edu_evaluacion_desarrollo_cab  EVOR
							 WHERE
							 EVOR.Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab
						";	
						$Where = ["Id_Edu_Evaluacion_Desarrollo_Cab" =>$Id_Edu_Evaluacion_Desarrollo_Cab];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Cont_Cantidad_Intentos = $Row->Cont_Cantidad_Intentos;	
						
						$Cont_Cantidad_Intentos_Sum = $Cont_Cantidad_Intentos + 1;				
						
						
						$reg = array(
						'Cont_Cantidad_Intentos' => $Cont_Cantidad_Intentos_Sum
						);
						$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
						$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection);	
						
						
									
						
						
					
						$Settings = array();
						$Settings['Url'] = $UrlFile ."/interface/Finalizar_Desarrollo_Evaluacion".$urlLinkB."/Estado_Pregunta/".Estado_Pregunta;
						$Settings['Screen'] = "PanelA";
						$Settings['Type_Send'] = "HXM";
						DCRedirectJSSP($Settings);
					

					
                }else{	
				
					$Settings = array();
					$Settings['Url'] = $UrlFile ."/interface/Inicio_Desarrollo_Evaluacion".$urlLinkB."/Estado_Pregunta/".Estado_Pregunta;
					$Settings['Screen'] = "PanelA";
					$Settings['Type_Send'] = "HXM";
					DCRedirectJSSP($Settings);
					
                }
				
                DCExit();
				
                break;	
			
            case "Inicio_Desarrollo_Evaluacion_Atras":
			    
				// DCCloseModal();
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				$Estado_Pregunta = $Parm["Estado_Pregunta"];
				$Id_Edu_Pregunta = $Parm["Id_Edu_Pregunta"];
				$var_Url = $Parm["var_Url"];
				$Id_Form = $Parm["Id_Form"];
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Form/".$Id_Form."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Id_Edu_Evaluacion_Desarrollo_Cab."/var_Url/".$var_Url;	
                         						
				$Query = "
				SELECT 
				OE.Id_Edu_Tipo_Pregunta
				FROM edu_pregunta OE 
				WHERE 
				OE.Id_Edu_Pregunta = :Id_Edu_Pregunta  ";	
				$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Tipo_Pregunta = $Row->Id_Edu_Tipo_Pregunta;

				if($Id_Edu_Tipo_Pregunta == 1){ 
						
						$Parm_Post = "".$Parm["Id_Form"]."_".$Parm["Id_Edu_Pregunta"]."";
						
						$Respuesta = DCPost($Parm_Post);
						
				        $Parm["Respuesta_Seleccionada"] = DCPost($Parm_Post);						

				}

				if($Id_Edu_Tipo_Pregunta == 2){ 
						
						$Parm_Post = "".$Parm["Id_Form"]."_".$Parm["Id_Edu_Pregunta"]."";
						$Respuesta = DCPost($Parm_Post);
								
                  
				}
				
				
				
				if($Id_Edu_Tipo_Pregunta == 3){ 
					
						$Text_Area = "".$Parm["Id_Form"]."_".$Parm["Id_Edu_Pregunta"]."_Textarea";
					    $Text_Area = trim(DCPost($Text_Area));
						$File = $_FILES["".$Parm["Id_Form"]."_".$Parm["Id_Edu_Pregunta"]."_File"]["name"];
						$Cont_Validador = 0;
						if(!empty($Text_Area)){ $Cont_Validador += 1; }
						if(!empty($File)){ $Cont_Validador += 1; }
				
					
				}
				
				$Parm["Pregunta_Actual"] = $Estado_Pregunta;
				
				$this->Guarda_Respuesta($Parm);
				// exit();
				
				$Query = "
				SELECT 
				EDC.Matriz_Preguntas, EDC.Id_Edu_Evaluacion_Desarrollo_Cab
				FROM edu_evaluacion_desarrollo_cab EDC 
				WHERE 
				EDC.Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab ";	
				$Where = ["Id_Edu_Evaluacion_Desarrollo_Cab" =>$Id_Edu_Evaluacion_Desarrollo_Cab];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);
				$Matriz_Preguntas = $Row->Matriz_Preguntas;	
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Row->Id_Edu_Evaluacion_Desarrollo_Cab;	
				
				
				$Matriz_Preguntas = json_decode($Row->Matriz_Preguntas);
					
				$Primera_Pregunta = $Matriz_Preguntas[0]->Id_Edu_Pregunta;
				$Contador_Reg = count($Matriz_Preguntas);
				$Ultima_Pregunta = $Matriz_Preguntas[$Contador_Reg -  1]->Id_Edu_Pregunta;	


				// var_dump($Matriz_Preguntas);
				// var_dump("------");
				// var_dump($Estado_Pregunta);
			 
				// if($Primera_Pregunta == $Id_Edu_Pregunta){ 
				
					$Settings = array();
					$Settings['Url'] = $UrlFile ."/interface/Inicio_Desarrollo_Evaluacion".$urlLinkB."/Estado_Pregunta/".Estado_Pregunta;
					$Settings['Screen'] = "PanelA";
					$Settings['Type_Send'] = "HXM";
					DCRedirectJSSP($Settings);
					
                // }else{	
					// $Settings = array();
					// $Settings['Url'] = $UrlFile ."/interface/Inicio_Desarrollo_Evaluacion_Atras".$urlLinkB."/Estado_Pregunta/".Estado_Pregunta;
					// $Settings['Screen'] = "PanelA";
					// $Settings['Type_Send'] = "HXM";
					// DCRedirectJSSP($Settings);
					
                // }
				
                DCExit();
				
                break;	
			
            case "Finalizar_Desarrollo_Evaluacion":
			
				DCCloseModal();
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
				$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				$Estado_Pregunta = $Parm["Estado_Pregunta"];
				$Id_Edu_Pregunta = $Parm["Id_Edu_Pregunta"];
				$Id_Form = $Parm["Id_Form"];
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];
				
				$Query = "
					    
						SELECT 
						EC.Id_Edu_Objeto_Evaluativo
						, OE.Intentos_Desarrollo
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
				$Intentos_Desarrollo = $Row->Intentos_Desarrollo;
				
				
				$Query = " 
				SELECT EWDD.Id_Edu_Respuesta
				, EWDD.Id_Edu_Evaluacion_Desarrollo_Det
				, EWDD.Id_Edu_Pregunta
				, EWDD.Nota 
				, EP.Id_Edu_Tipo_Pregunta 
				FROM edu_evaluacion_desarrollo_det EWDD
				INNER JOIN edu_pregunta EP ON EWDD.Id_Edu_Pregunta = EP.Id_Edu_Pregunta
				WHERE 
				EWDD.Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab  
				ORDER BY EP.Id_Edu_Tipo_Pregunta  ASC
				"; 
				$Where = ["Id_Edu_Evaluacion_Desarrollo_Cab" =>$Id_Edu_Evaluacion_Desarrollo_Cab];
				$Mat_EVD = ClassPdo::DCRows($Query,$Where,$Conection);
			    $Cont_Rpta = 0;
				$Suma_Nota = 0;
				$Suma_Cont_Misma_Pregunta = 0;
				$Suma_Nota_Errada = 0;
				$Suma_Rpta_Marcadas = 0;
				$Cont_Misma_Pregunta = "";
                $Suma_Nota = 0;				
				foreach ($Mat_EVD as $Reg_EVD) {
					
                    $Suma_Nota += $Reg_EVD->Nota;
						
				}	
				
								
				
				if($Id_Edu_Tipo_Objeto_Evaluativo != 1){
					$Revisio = "La evaluación a un debe ser evaluada por el docente";
					$reg = array(
						'Nota' => $Suma_Nota,
						'Estado' => "Finalizado",
						'Revision' => "Pendiente"
					);
					
			    }else{
					$Revisio = "La evaluación fue revisada automáticamente ";					
					$reg = array(
						'Nota' => $Suma_Nota,
						'Estado' => "Finalizado",
						'Revision' => "Finalizado",
					);	
					
				}
				// var_dump($reg);
				$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
				$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection,"");
				
				


				$Script = '<script>
				           stop();
				';
				$Script .= "</script>";

				$Form1 = "<div style='background-color:#fff;'>" . $Form1 . "</div>";
                DCWrite($Pestanas . $Form1 . $Script);
				
				$urlLinkB_2 = "/sadministrator/edu-desarrollo-examen/interface/Crea_Objeto_Evaluativo/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S;	
				$Settings = array();
				$Settings['Url'] = $urlLinkB_2;
				$Settings['Screen'] = "PanelA";
				$Settings['Type_Send'] = "HXM";
				DCRedirectJSSP($Settings);					
				
                DCExit();
				
				
                break;
				
				
				
				
            case "guarda_auto_evaluacion":

				$Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];
				$Tiempo_Estado = $Parm["Tiempo_Estado"];

				$reg = array(
					'Tiempo_Estado' => $Tiempo_Estado
				);
				$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
				$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection,"");
			
                // echo "Id_Edu_Evaluacion_Desarrollo_Cab:: ".$Id_Edu_Evaluacion_Desarrollo_Cab;
                // echo "guarda_auto_evaluacion";
				exit();
				
                break;	
							
	
				
            case "finaliza_evaluacion":

				$Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];

				$reg = array(
					'Estado' => 'Finalizado'
				);
				$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
				$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection,"");
			
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				
				$Settings = array();
				$Settings['Url'] = $UrlFile."/interface/Crea_Objeto_Evaluativo/key/".$Parm["key"]."/Id_Edu_Componente/".$Id_Edu_Componente_S."";
				
				$Settings['Screen'] = "PanelA";
				$Settings['Type_Send'] = "HXM";
				DCRedirectJSSP($Settings);
				

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
		$Envia = $Settings["Envia"];
	    // $Nota = $DCPost("");
		

		$reg = array(
			'Pregunta_Actual' => $Pregunta_Actual
		);
     
		$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
		$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection,"");	

		$Query = "
		SELECT 
		OE.Id_Edu_Tipo_Pregunta
		FROM edu_pregunta OE 
		WHERE 
		OE.Id_Edu_Pregunta = :Id_Edu_Pregunta  ";	
		$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Edu_Tipo_Pregunta = $Row->Id_Edu_Tipo_Pregunta;
		
		
		if($Envia !=="Atras" ){

				if($Id_Edu_Tipo_Pregunta == 1){    
		   
		   
						$Query = " 
						SELECT Id_Edu_Evaluacion_Desarrollo_Det, Id_Edu_Respuesta, Id_Edu_Pregunta 
						FROM edu_evaluacion_desarrollo_det
						WHERE 
						Id_Edu_Pregunta = :Id_Edu_Pregunta  AND 
						Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab  
						"; 
						$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta
						,"Id_Edu_Evaluacion_Desarrollo_Cab"=>$Id_Edu_Evaluacion_Desarrollo_Cab];
						
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Id_Edu_Evaluacion_Desarrollo_Det = $Row->Id_Edu_Evaluacion_Desarrollo_Det;
						

						$Query = "
						SELECT 
						OE.Nota, ER.Respuesta_Correcta
						FROM edu_respuesta ER
						INNER JOIN  edu_pregunta OE ON ER.Id_Edu_Pregunta = OE.Id_Edu_Pregunta
						WHERE 
						ER.Id_Edu_Respuesta = :Id_Edu_Respuesta  ";	
						$Where = ["Id_Edu_Respuesta" =>$Respuesta_Seleccionada];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Respuesta_Correcta_b2 = $Row->Respuesta_Correcta;
						$Nota_b2 = $Row->Nota;
						
						if($Respuesta_Correcta_b2 == "SI"){
							$Nota_b3 = $Nota_b2;
						}else{
							$Nota_b3 = 0;
						}
							
						if(empty($Id_Edu_Evaluacion_Desarrollo_Det)){
		
							
							
							$data = array(
							'Id_Edu_Respuesta' => $Respuesta_Seleccionada,
							'Id_Edu_Pregunta' => $Id_Edu_Pregunta,
							'Nota' => $Nota_b3,
							'Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab,
							'Entity' => $Entity,
							'Id_User_Creation' => $User,
							'Id_User_Update' => $User,
							'Date_Time_Creation' => $DCTimeHour,
							'Date_Time_Creation' => $DCTimeHour,
							'Date_Time_Update' => $DCTimeHour
							);	

							$Result_Cab = ClassPDO::DCInsert("edu_evaluacion_desarrollo_det", $data, $Conection);
							$Id_Edu_Evaluacion_Desarrollo_Cab = $Result_Cab["lastInsertId"];
																
						}else{

							$reg = array(
								'Id_Edu_Respuesta' => $Respuesta_Seleccionada,
								'Nota' => $Nota_b3
							);
							$where = array('Id_Edu_Evaluacion_Desarrollo_Det' => $Id_Edu_Evaluacion_Desarrollo_Det);
							$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_det', $reg , $where, $Conection,"");	


						}
				}
				
				if($Id_Edu_Tipo_Pregunta == 2){ 

						$where = array('Id_Edu_Pregunta' =>$Id_Edu_Pregunta,"Id_Edu_Evaluacion_Desarrollo_Cab"=>$Id_Edu_Evaluacion_Desarrollo_Cab);
						$rg = ClassPDO::DCDelete('edu_evaluacion_desarrollo_det', $where, $Conection);				
                        $Nota_Correcta = 0;
                        $Nota_In_Correcta = 0;
                        $Cantidad_Rpta_Correcta = 0;
						$Check = "".$Settings["Id_Form"]."_".$Settings["Id_Edu_Pregunta"]."";
						$Check = DCPost($Check);
						foreach($Check as $Respuesta_Seleccionada){



								$Query = "
								SELECT 
								OE.Nota, ER.Respuesta_Correcta
								FROM edu_respuesta ER
								INNER JOIN  edu_pregunta OE ON ER.Id_Edu_Pregunta = OE.Id_Edu_Pregunta
								WHERE 
								ER.Id_Edu_Respuesta = :Id_Edu_Respuesta  ";	
								$Where = ["Id_Edu_Respuesta" =>$Respuesta_Seleccionada];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$Respuesta_Correcta_b2 = $Row->Respuesta_Correcta;
								$Nota_b2 = $Row->Nota;
								if($Respuesta_Correcta_b2 == "SI"){
								    $Nota_Correcta += $Nota_b2;
								    $Cantidad_Rpta_Correcta += 1;
								}else{
									$Nota_In_Correcta += $Nota_b2;
									
								}	
								

								$Query = " 
								SELECT Id_Edu_Evaluacion_Desarrollo_Det, Id_Edu_Respuesta, Id_Edu_Pregunta 
								FROM edu_evaluacion_desarrollo_det
								WHERE 
								Id_Edu_Pregunta = :Id_Edu_Pregunta  AND 
								Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab  AND 
								Id_Edu_Respuesta = :Id_Edu_Respuesta  
								"; 
								$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta
								,"Id_Edu_Respuesta"=>$Respuesta_Seleccionada
								,"Id_Edu_Evaluacion_Desarrollo_Cab"=>$Id_Edu_Evaluacion_Desarrollo_Cab];

								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$Id_Edu_Evaluacion_Desarrollo_Det = $Row->Id_Edu_Evaluacion_Desarrollo_Det;

								if(empty($Id_Edu_Evaluacion_Desarrollo_Det)){
									
									
									// echo $Id_Edu_Evaluacion_Desarrollo_Cab."<br>";
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

									$Result_Cab = ClassPDO::DCInsert("edu_evaluacion_desarrollo_det", $data, $Conection);
									// $Id_Edu_Evaluacion_Desarrollo_Cab = $Result_Cab["lastInsertId"];

												
								}else{

									$reg = array(
										'Id_Edu_Respuesta' => $Respuesta_Seleccionada
									);
									$where = array('Id_Edu_Evaluacion_Desarrollo_Det' => $Id_Edu_Evaluacion_Desarrollo_Det);
									$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_det', $reg , $where, $Conection,"");	

								}

						}

							
						
						$Nota = $Nota_Correcta - $Nota_In_Correcta;
						if($Nota <= 0){
							$Nota_B = 0;
						}else{
							$Nota_B = ($Nota / $Cantidad_Rpta_Correcta)/ $Cantidad_Rpta_Correcta;
							$Nota_B =  round($Nota_B , 2);
						}
						
						
						foreach($Check as $Respuesta_Seleccionada){

								$Query = " 
								SELECT Id_Edu_Evaluacion_Desarrollo_Det, Id_Edu_Respuesta, Id_Edu_Pregunta 
								FROM edu_evaluacion_desarrollo_det
								WHERE 
								Id_Edu_Pregunta = :Id_Edu_Pregunta  AND 
								Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab  AND 
								Id_Edu_Respuesta = :Id_Edu_Respuesta  
								"; 
								$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta
								,"Id_Edu_Respuesta"=>$Respuesta_Seleccionada
								,"Id_Edu_Evaluacion_Desarrollo_Cab"=>$Id_Edu_Evaluacion_Desarrollo_Cab];

								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$Id_Edu_Evaluacion_Desarrollo_Det = $Row->Id_Edu_Evaluacion_Desarrollo_Det;
								
								$reg = array(
									'Id_Edu_Respuesta' => $Respuesta_Seleccionada,
									'Nota' => $Nota_B
									
								);
								$where = array('Id_Edu_Evaluacion_Desarrollo_Det' => $Id_Edu_Evaluacion_Desarrollo_Det);
								$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_det', $reg , $where, $Conection,"");	

							
                        }						
						
											
				}
				
						
				
				if($Id_Edu_Tipo_Pregunta == 3){ 		

						$Text_Area = "".$Settings["Id_Form"]."_".$Settings["Id_Edu_Pregunta"]."_Textarea";
						$Text_Area = DCPost($Text_Area);
						$File = $_FILES["".$Settings["Id_Form"]."_".$Settings["Id_Edu_Pregunta"]."_File"]["name"];
						$File = $Settings["Id_Form"]."_".$Settings["Id_Edu_Pregunta"]."_".$File;
						$Query = " 
						SELECT Id_Edu_Evaluacion_Desarrollo_Det, Id_Edu_Respuesta
						, Id_Edu_Pregunta 
						, Respuesta_Abierta 
						FROM edu_evaluacion_desarrollo_det
						WHERE 
						Id_Edu_Pregunta = :Id_Edu_Pregunta  AND 
						Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab   
						"; 
						$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta
						,"Id_Edu_Evaluacion_Desarrollo_Cab"=>$Id_Edu_Evaluacion_Desarrollo_Cab];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Id_Edu_Evaluacion_Desarrollo_Det = $Row->Id_Edu_Evaluacion_Desarrollo_Det;
						$Respuesta_Abierta = $Row->Respuesta_Abierta;


						
						if(empty($Id_Edu_Evaluacion_Desarrollo_Det)){
							
							$data = array(
							'Respuesta_Abierta' => $Text_Area,
							'Imagen' => $File,
							'Id_Edu_Pregunta' => $Id_Edu_Pregunta,
							'Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab,
							'Entity' => $Entity,
							'Id_User_Creation' => $User,
							'Id_User_Update' => $User,
							'Date_Time_Creation' => $DCTimeHour,
							'Date_Time_Creation' => $DCTimeHour,
							'Date_Time_Update' => $DCTimeHour
							);	

							$Result_Cab = ClassPDO::DCInsert("edu_evaluacion_desarrollo_det", $data, $Conection);
							$Id_Edu_Evaluacion_Desarrollo_Cab = $Result_Cab["lastInsertId"];
						
										
						}else{

							$reg = array(
							'Respuesta_Abierta' => $Text_Area,
							'Imagen' => $File
							);
							$where = array('Id_Edu_Evaluacion_Desarrollo_Det' => $Id_Edu_Evaluacion_Desarrollo_Det);
							$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_det', $reg , $where, $Conection,"");	

						}	



						
				}
				
		}
		
    }		
		
	
	public function Respuestas_Evaluacion($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];	
		$Id_Edu_Pregunta = $Settings["Id_Edu_Pregunta"];		
		$Id_Form = $Settings["Id_Form"];	
		$Id_Edu_Almacen = $Settings["key"];	
		$Id_Edu_Evaluacion_Desarrollo_Cab = $Settings["Id_Edu_Evaluacion_Desarrollo_Cab"];	
		
		
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
		OE.Id_Edu_Tipo_Pregunta
		FROM edu_pregunta OE 
		WHERE 
		OE.Id_Edu_Pregunta = :Id_Edu_Pregunta  ";	
		$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Edu_Tipo_Pregunta = $Row->Id_Edu_Tipo_Pregunta;
        
		
		if($Id_Edu_Tipo_Pregunta == 1){ 
				
			$Query = " 
			SELECT Id_Edu_Respuesta, Nombre, Id_Edu_Pregunta, Respuesta_Correcta
			FROM edu_respuesta
			WHERE 
			Id_Edu_Pregunta = :Id_Edu_Pregunta  ;
			"; 
			$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta];
			$Mat_Respuestas = ClassPdo::DCRows($Query,$Where,$Conection);
			$Html = "<table class='table_3'>";	
			
			foreach ($Mat_Respuestas as $Reg) {		

					$Nombre = $Reg->Nombre;
					$Id_Edu_Respuesta = $Reg->Id_Edu_Respuesta;
					$Id_Edu_Pregunta = $Reg->Id_Edu_Pregunta;
					$Respuesta_Correcta = $Reg->Respuesta_Correcta;
										
					$Query = "
					SELECT 
					EC.Id_Edu_Respuesta 
					FROM edu_evaluacion_desarrollo_det EC
					INNER JOIN edu_evaluacion_desarrollo_cab ECB ON EC.Id_Edu_Evaluacion_Desarrollo_Cab = ECB.Id_Edu_Evaluacion_Desarrollo_Cab
					WHERE 
					EC.Id_Edu_Pregunta = :Id_Edu_Pregunta AND 
					EC.Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab AND 
					ECB.Id_Suscripcion = :Id_Suscripcion AND 
					EC.Id_Edu_Respuesta = :Id_Edu_Respuesta 
					";	
					$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta
					, "Id_Edu_Respuesta" =>$Id_Edu_Respuesta
					, "Id_Suscripcion" =>$Id_Suscripcion
					, "Id_Edu_Evaluacion_Desarrollo_Cab"=>$Id_Edu_Evaluacion_Desarrollo_Cab ];

					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Id_Edu_Respuesta_Tran = $Row->Id_Edu_Respuesta;


					$Html .= '<tr>';
						$Html .= '<td>';
			
						if($Id_Edu_Respuesta_Tran == $Id_Edu_Respuesta ){

							$Html .= ' <input type="radio" class="checkbox-inline" id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_'.$Id_Edu_Respuesta.'" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'" value="'.$Id_Edu_Respuesta.'" checked >';		
							
						}else{
							
							$Html .= ' <input type="radio" class="checkbox-inline" id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_'.$Id_Edu_Respuesta.'" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'" value="'.$Id_Edu_Respuesta.'" >';		
						
						}	
						$Html .= '</td> <td> <p> '.$Nombre.' </p>';
						$Html .= '</td>';	
						
					$Html .= '</tr>';
			}
                    $Html .= '<tr>';
						$Html .= '<td colspan="2"><br></td>';					
					$Html .= '</tr>';			
			$Html .= "</table>";
        }

		if($Id_Edu_Tipo_Pregunta == 2){ 
				
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
					
					$Query = "
					SELECT 
					EC.Id_Edu_Respuesta 
					FROM edu_evaluacion_desarrollo_det EC
					INNER JOIN edu_evaluacion_desarrollo_cab ECB ON EC.Id_Edu_Evaluacion_Desarrollo_Cab = ECB.Id_Edu_Evaluacion_Desarrollo_Cab
					WHERE 
					EC.Id_Edu_Pregunta = :Id_Edu_Pregunta AND 
					EC.Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab AND 
					ECB.Id_Suscripcion = :Id_Suscripcion AND 
					EC.Id_Edu_Respuesta = :Id_Edu_Respuesta 
					";	
					$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta
					, "Id_Edu_Respuesta" =>$Id_Edu_Respuesta
					, "Id_Suscripcion" =>$Id_Suscripcion
					, "Id_Edu_Evaluacion_Desarrollo_Cab"=>$Id_Edu_Evaluacion_Desarrollo_Cab ];

					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Id_Edu_Respuesta_Tran = $Row->Id_Edu_Respuesta;					
					
					
					$Html .= '<div class="form-group">';
						$Html .= '<label class="control-label" >';
						if($Id_Edu_Respuesta_Tran == $Id_Edu_Respuesta ){

							// $Html .= ' <input type="radio" class="checkbox-inline" id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_'.$Id_Edu_Respuesta.'" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'" value="'.$Id_Edu_Respuesta.'" checked >';		
                            $Html .= ' <input type="checkbox" class="checkbox-inline" id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_'.$Id_Edu_Respuesta.'" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'[]" value="'.$Id_Edu_Respuesta.'" checked >';		
													
						}else{
                            $Html .= ' <input type="checkbox" class="checkbox-inline" id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_'.$Id_Edu_Respuesta.'" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'[]" value="'.$Id_Edu_Respuesta.'"  >';								
							// $Html .= ' <input type="radio" class="checkbox-inline" id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_'.$Id_Edu_Respuesta.'" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'" value="'.$Id_Edu_Respuesta.'" >';		
						
						}	
						$Html .= '   '.$Nombre.' ';
						$Html .= '</label>';	
						
					$Html .= '</div>';
			}	
			
        }
		
		if($Id_Edu_Tipo_Pregunta == 3){ 
				
						$Query = " 
						SELECT Id_Edu_Evaluacion_Desarrollo_Det, Id_Edu_Respuesta
						, Id_Edu_Pregunta 
						, Respuesta_Abierta 
						FROM edu_evaluacion_desarrollo_det
						WHERE 
						Id_Edu_Pregunta = :Id_Edu_Pregunta  AND 
						Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab   
						"; 
						$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta
						,"Id_Edu_Evaluacion_Desarrollo_Cab"=>$Id_Edu_Evaluacion_Desarrollo_Cab];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Id_Edu_Evaluacion_Desarrollo_Det = $Row->Id_Edu_Evaluacion_Desarrollo_Det;
						$Respuesta_Abierta = $Row->Respuesta_Abierta;
						
						
					$Html .= '<div class="form-group">';
						$Html .= '<label class="control-label" >';
						// $Html .= ' <input type="radio" class="checkbox-inline" id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_'.$Id_Edu_Respuesta.'" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'" value="'.$Id_Edu_Respuesta.'" >';		

						$Html .= '    '.$Nombre.' ';
						
						$Html .= '</label>';	
						$Html .= ' 
						            <textarea id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_Textarea" 
						            name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_Textarea" rows="4"  class="form-control">
									'.$Respuesta_Abierta.'
								    </textarea>
								   
                                    <li style="list-style: none;margin: 10px 0px 10px 0px;background:#eef6f9; padding: 10px 16px;float: left;width: 100%;" 
									id="'.$Id_Form.'Imagen_li">
									<label> <p style="margin: 0 0 0px;">Archivo  | </p></label>
									
									<div id="'.$Id_Form.'Imagen_div_in_server" class="Div_In_Server" style=""></div>
										<div id="'.$Id_Form.'Imagen_div" class="Div_Option_Upload" style="display:block;">
											
											<p class="label_p"> Peso Máximo: 100.00 | 
											Extenciones Permitidas:png,jpg,jpge,pdf,doc,docx</p>
											<div id="msg_rs"></div>
											<div class="background_lp" id="'.$Id_Form.'Imagenbackground_lp">
											    <div id="'.$Id_Form.'Imagenlinea_pregress" class="linea_pregress"></div>
											</div>
											<input type="file" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_File" 
											direction="" 
											id="'.$Id_Form.'Imagen" 
											object="'.$Id_Form.'" 
											cantidad="100" 
											extensions="png,jpg,jpge,pdf,doc,docx" 
											Id_Object_Detail="'.$Id_Form.'_'.$Id_Edu_Pregunta.'" 
											value="image" onchange="DCUpload(this);" >
																						
										</div>
									</li>								   
								  								   
								';	

								
						
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
		
		
		// var_dump("a");
		
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

		// var_dump("b");
		
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

		$procces = $Settings["procces"];
			// echo "Hola mundo c: ".$Pregunta_Actual;
		if(!empty($procces)){
			
			
			// echo "Hola mundo";
			
			$reg = array(
			'Nota' => 0,
			'Pregunta_Actual' => 0,
			'Tiempo_Estado' => 0,
			'Revision' => "",
			'Estado' => "Iniciado",
			'Matriz_Preguntas' => ""
			);
			$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
			$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection);	
			
			
			$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' =>$Id_Edu_Evaluacion_Desarrollo_Cab);
			$rg = ClassPDO::DCDelete('edu_evaluacion_desarrollo_det', $where, $Conection);
			
		}
				
		
		/////////tttttttttttttt
		
		// var_dump("c");
		// var_dump($Id_Edu_Evaluacion_Desarrollo_Cab);
				
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
			
			
			
			if(!empty($procces)){
				
		
						
						$reg = array(
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
						$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
						$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection,"");	
			
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