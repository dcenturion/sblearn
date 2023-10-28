<?php
require_once('./sviews/layout.php');
require_once(dirname(__FILE__).'/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Configuracion_Producto{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-configuracion-producto";
		$UrlFile_Edu_Articulo_Det = "/sadministrator/edu-articulo-det";
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
					
					case "Edu_Articulo_Crud_Configuracion":
					
                            $Data = array();		
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Articulo"],"Id_Edu_Articulo",$Data);  

							
	                        // $Settings = array();
							// $Settings['Url'] = $UrlFile."/interface/obtener_nota/key/".$Parm["key"]."/Id_Edu_Componente/".$Parm["Id_Edu_Componente"]."/Id_Edu_Evaluacion_Desarrollo_Det/".$Parm["Id_Edu_Evaluacion_Desarrollo_Det"]."";
							// $Settings['Screen'] = "Nota".$Parm["Id_Edu_Evaluacion_Desarrollo_Det"];
							// $Settings['Type_Send'] = "HXM";
							// DCRedirectJSSP($Settings);	
							DCCloseModal();
							DCExit();	
							
						break;	
						
						
											
					case "Edu_Evaluacion_Desarrollo_Cab_Crud_B":
					
                            $Data = array();		
							$Result = DCSave($Obj,$Conection,$Parm["Id_Edu_Evaluacion_Desarrollo_Cab"],"Id_Edu_Evaluacion_Desarrollo_Cab",$Data);  

							
	                        $Settings = array();
							$Settings['Url'] = $UrlFile."/interface/obtener_nota_cab/key/".$Parm["key"]."/Id_Edu_Componente/".$Parm["Id_Edu_Componente"]."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Parm["Id_Edu_Evaluacion_Desarrollo_Cab"]."";
							$Settings['Screen'] = "Nota".$Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];
							$Settings['Type_Send'] = "HXM";
							DCRedirectJSSP($Settings);	
							DCCloseModal();
							DCExit();	
							
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
		
			
            case "Configurar-Otros":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				$Id_Edu_Evaluacion_Desarrollo_Det = $Parm["Id_Edu_Evaluacion_Desarrollo_Det"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;
				
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Evaluacion_Desarrollo_Det/".$Id_Edu_Evaluacion_Desarrollo_Det;	
						
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Articulo_Crud_Configuracion".$urlLinkB;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive".$urlLinkB;
				
				$Name_Interface = "Otras Configuraciones";	
				
				if(!empty($Id_Edu_Articulo)){
			    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                }else{
					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                }
				
				
				$Query = "
				    SELECT PB.Id_Inhouse_Empresa AS Id, PB.Razon_Social AS Name
					 FROM inhouse_empresa PB
					WHERE PB.Entity = :Entity
					ORDER BY PB.Razon_Social ASC
				";    
				$Where = ["Entity"=>$Entity];
				
				$Combobox = array(
				     array("Id_Inhouse_Empresa",$Query,$Where)
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","Edu_Articulo_Crud_Configuracion"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Articulo_Crud_Configuracion",$Class,$Id_Edu_Articulo,$PathImage,$Combobox,$Buttons,"Id_Edu_Articulo");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();			
			
                break;	


            case "Aplicar_Nota_Foro":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
				$Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;
				
				
                $urlLinkB = "/key/".$Id_Edu_Almacen."/Id_Edu_Articulo/".$Id_Edu_Articulo."/Id_Edu_Componente/".$Id_Edu_Componente_S."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Id_Edu_Evaluacion_Desarrollo_Cab;	
						
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Evaluacion_Desarrollo_Cab_Crud_B".$urlLinkB;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive".$urlLinkB;
				
				if(!empty($Id_Edu_Evaluacion_Desarrollo_Cab)){
				    $Name_Interface = "Aplicar Nota";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                }else{
				    $Name_Interface = "Crear Respuesta";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
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
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","Edu_Evaluacion_Desarrollo_Cab_Crud_B"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Evaluacion_Desarrollo_Cab_Crud_B",$Class,$Id_Edu_Evaluacion_Desarrollo_Cab,$PathImage,$Combobox,$Buttons,"Id_Edu_Evaluacion_Desarrollo_Cab");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();			
			
                break;	


            case "Finalizar-R":

		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
		        $key = $Parm["key"];
		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
		        $Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
		        $Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];

				
				$btn = "Continue ]" .$UrlFile ."/interface/Finalizar/Id_Edu_Componente/".$Id_Edu_Componente."/key/".$key."/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/Id_Edu_Evaluacion_Desarrollo_Cab/".$Id_Edu_Evaluacion_Desarrollo_Cab."/Id_Edu_Articulo/".$Id_Edu_Articulo."]PanelA]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Deseas Finalizar la Revisión ? ",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;		
					

            case "Finalizar":

		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
		        $key = $Parm["key"];
		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
		        $Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
		        $Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];

				$Query = "
				SELECT 
				EEDD.Nota,
				EEDD.Id_Edu_Evaluacion_Desarrollo_Cab
				FROM edu_evaluacion_desarrollo_cab EEDD
				WHERE 
				EEDD.Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab  ";	
				$Where = ["Id_Edu_Evaluacion_Desarrollo_Cab" =>$Id_Edu_Evaluacion_Desarrollo_Cab];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Tot_Nota = $Row->Nota;

				$Query = "
				SELECT 
				EOE.Id_Edu_Objeto_Evaluativo ,
				EODC.Id_Edu_Tipo_Objeto_Evaluativo ,
				EODC.Nombre
				FROM  edu_objeto_evaluativo_detalle EOE
				INNER JOIN edu_objeto_evaluativo EODC ON EOE.Id_Edu_Objeto_Evaluativo = EODC.Id_Edu_Objeto_Evaluativo
				WHERE  EOE.Id_Edu_Articulo = :Id_Edu_Articulo  
				AND  EOE.Id_Edu_Componente = :Id_Edu_Componente  
				";	
				$Where = ["Id_Edu_Articulo" =>$Id_Edu_Articulo, "Id_Edu_Componente" =>$Id_Edu_Componente];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Edu_Objeto_Evaluativo = $Row->Id_Edu_Objeto_Evaluativo;
				$Id_Edu_Tipo_Objeto_Evaluativo = $Row->Id_Edu_Tipo_Objeto_Evaluativo;
				$Nombre = $Row->Nombre;
				
                if($Id_Edu_Tipo_Objeto_Evaluativo == 3 || $Id_Edu_Tipo_Objeto_Evaluativo == 5){

					$Query = "
					SELECT 
					SUM(EEDD.Nota) AS Tot_Nota
					FROM edu_evaluacion_desarrollo_det EEDD
					INNER JOIN edu_pregunta EP ON EEDD.Id_Edu_Pregunta = EP.Id_Edu_Pregunta
					WHERE 
					EEDD.Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab 
					AND EP.Id_Edu_Tipo_Pregunta = :Id_Edu_Tipo_Pregunta ";	
					$Where = ["Id_Edu_Evaluacion_Desarrollo_Cab" =>$Id_Edu_Evaluacion_Desarrollo_Cab,"Id_Edu_Tipo_Pregunta"=>3];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				
					$Tot_Nota = $Tot_Nota + $Row->Tot_Nota;
					
					
				}


				$reg = array(
				'Date_Time_Update' => $DCTimeHour,
				'Revision' => "Finalizado",
				'Nota' => $Tot_Nota
				);
				
			
                if($Id_Edu_Tipo_Objeto_Evaluativo == 4 ){				
					// echo "<br>".$Tot_Nota;
					
					$reg = array(
					'Date_Time_Update' => $DCTimeHour,
					'Revision' => "Finalizado",
					'Estado' => "Finalizado"
					);
				
				}
				
				$where = array('Id_Edu_Evaluacion_Desarrollo_Cab' => $Id_Edu_Evaluacion_Desarrollo_Cab);
				$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_cab', $reg , $where, $Conection,"");
				
				$Settings["key"] = $Parm["key"];
				$Settings["Id_Edu_Articulo"] = $Parm["Id_Edu_Articulo"];
				$Settings["Id_Edu_Componente"] = $Parm["Id_Edu_Componente"];
				$Settings["Id_Edu_Evaluacion_Desarrollo_Cab"] = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];
				$Settings["interface"] = "Revision_Finalizada";
				new Edu_Calificar($Settings);
				DCExit("");	

							

                break;								

            case "Revision_Finalizada":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente"];
		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
		        $key = $Parm["key"];
		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
		        $Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
		        $Id_Edu_Evaluacion_Desarrollo_Cab = $Parm["Id_Edu_Evaluacion_Desarrollo_Cab"];
				


				$Query = "
				SELECT 
				EEDC.Estado, EEDC.Nota
				FROM edu_evaluacion_desarrollo_cab EEDC
				WHERE 
				EEDC.Id_Edu_Evaluacion_Desarrollo_Cab = :Id_Edu_Evaluacion_Desarrollo_Cab  ";	
				$Where = ["Id_Edu_Evaluacion_Desarrollo_Cab" =>$Id_Edu_Evaluacion_Desarrollo_Cab];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nota = $Row->Nota;				
				$Estado = $Row->Estado;				
				

				$Form1 = '
					<div class="row" style="margin:0px;padding:0px 20px 20px 10px;">
	                    <div class="col-12 col-md-12" style="padding:5px 20px 20px 20px;">
						  
						    <span><h3>  ESTADO DE REVISIÓN </h3>
						    <p> <b> Estado de Revisión:  </b>'.$Estado.' </p>
						    <p> <b> Nota:  </b>'.$Nota.' </p>
							
					    </div>		
					</div>		
				
				';
				echo $Form1;
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
					$Id_Edu_Evaluacion_Desarrollo_Cab = $Result_Cab["lastInsertId"];
				
								
				}else{

					$reg = array(
						'Id_Edu_Respuesta' => $Respuesta_Seleccionada
					);
					$where = array('Id_Edu_Evaluacion_Desarrollo_Det' => $Id_Edu_Evaluacion_Desarrollo_Det);
					$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_det', $reg , $where, $Conection,"");	

				}
        }
		
		if($Id_Edu_Tipo_Pregunta == 2){ 		

				$Check = "".$Settings["Id_Form"]."_".$Settings["Id_Edu_Pregunta"]."";
				$Check = DCPost($Check);
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

							$Result_Cab = ClassPDO::DCInsert("edu_evaluacion_desarrollo_det", $data, $Conection);
							$Id_Edu_Evaluacion_Desarrollo_Cab = $Result_Cab["lastInsertId"];

										
						}else{

							$reg = array(
								'Id_Edu_Respuesta' => $Respuesta_Seleccionada
							);
							$where = array('Id_Edu_Evaluacion_Desarrollo_Det' => $Id_Edu_Evaluacion_Desarrollo_Det);
							$rg = ClassPDO::DCUpdate('edu_evaluacion_desarrollo_det', $reg , $where, $Conection,"");	

						}

				}
				 
				
									
		}
		
				
		
		if($Id_Edu_Tipo_Pregunta == 3){ 		

				$Text_Area = "".$Settings["Id_Form"]."_".$Settings["Id_Edu_Pregunta"]."_Textarea";
				$Text_Area = DCPost($Text_Area);
				$File = $_FILES["".$Settings["Id_Form"]."_".$Settings["Id_Edu_Pregunta"]."_File"]["name"];
				
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
	
	
	
		
	
	public function Comentario_Revisar($Id_Edu_Objeto_Evaluativo_Detalle,$Id_User_Miembro_BK,$Id_Comentario_Herencia) {
       	global $Conection, $DCTimeHour,$NameTable;
		
		
		if( $Id_Comentario_Herencia == "Principal"){
			
			// var_dump("aaaja");

			$Query = "
			   
				SELECT 
				UM.Nombre AS Nombres, UM.Foto, CM.Id_Comentario, CM.Id_User_Miembro, CM.Date_Time_Update, CM.Comentario
				FROM
				comentario CM
				INNER JOIN user_miembro UM ON UM.Id_User_Miembro = CM.Id_User_Miembro 
				WHERE 
				CM.Id_Edu_Objeto_Evaluativo_Detalle = :Id_Edu_Objeto_Evaluativo_Detalle AND 
				CM.Id_User_Miembro = :Id_User_Miembro AND 
				CM.Jerarquia = :Jerarquia
				ORDER BY CM.Date_Time_Update DESC 
				
			";    
			$Where = ["Id_Edu_Objeto_Evaluativo_Detalle" => $Id_Edu_Objeto_Evaluativo_Detalle,"Id_User_Miembro"=>$Id_User_Miembro_BK, "Jerarquia"=>1];
			
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
			$Where = ["Id_Comentario" => $Id_Comentario_Herencia];
						
			
		}
		
		$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
		
		
		

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
			
			if(empty($Foto)){
				
				$Img_Foto = "<div><i class='zmdi zmdi-account-circle' style='font-size: 4.5em;color: #0873b1'></i></div>";			
			
			}else{
				
				$Img_Foto = "<div class='Comentario'><img src='/sadministrator/simages/avatars/".$Reg->Foto."' width='50' height='50' class='img-circle'></div>";			
			}
			
				$Dia_Num = substr($Reg->Date_Time_Update, 8, 2);
				$Year = substr($Reg->Date_Time_Update, 0, 4);
				$Minutos_Descripcion = substr($Reg->Date_Time_Update, 10, 6);

				$ContGeneral += 1;	
				
				if( $Id_Comentario_Herencia !== "Principal"){
						
					$Settings["Id_Comentario_Herencia"] = $Id_Comentario;
					$Settings["Id_User_Miembro"] = $Id_User_Miembro_BK;
					$SubComentarios = Edu_Examen_Foro::List_Sub_Comentario($Settings);
					
				}
				
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
	
	
	public function Respuestas_Evaluacion($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];	
		$Id_Edu_Pregunta = $Settings["Id_Edu_Pregunta"];		
		$Id_Form = $Settings["Id_Form"];	

		$Query = "
		SELECT 
		OE.Id_Edu_Tipo_Pregunta
		FROM edu_pregunta OE 
		WHERE 
		OE.Id_Edu_Pregunta = :Id_Edu_Pregunta  ";	
		$Where = ["Id_Edu_Pregunta" =>$Id_Edu_Pregunta];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Edu_Tipo_Pregunta = $Row->Id_Edu_Tipo_Pregunta;
        
		// echo $Id_Edu_Tipo_Pregunta;
		if($Id_Edu_Tipo_Pregunta == 1){ 
				
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
					$Html .= '<div class="form-group">';
						$Html .= '<label class="control-label" >';
						$Html .= ' <input type="checkbox" class="checkbox-inline" id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_'.$Id_Edu_Respuesta.'" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'[]" value="'.$Id_Edu_Respuesta.'" >';		
						$Html .= '    '.$Nombre.' ';
						$Html .= '</label>';	
						
					$Html .= '</div>';
			}	
			
        }
		
		if($Id_Edu_Tipo_Pregunta == 3){ 
				

					$Html .= '<div class="form-group">';
						$Html .= '<label class="control-label" >';
						// $Html .= ' <input type="radio" class="checkbox-inline" id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_'.$Id_Edu_Respuesta.'" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'" value="'.$Id_Edu_Respuesta.'" >';		

						$Html .= '    '.$Nombre.' ';
						
						$Html .= '</label>';	
						$Html .= ' 
						            <textarea id="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_Textarea" 
						            name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_Textarea" rows="4"  class="form-control">
								    </textarea>
								   
                                    <li style="list-style: none;margin: 10px 0px 10px 0px;background:#eef6f9; padding: 10px 16px;float: left;width: 100%;" 
									id="'.$Id_Form.'Imagen_li">
									<label> <p style="margin: 0 0 0px;">Archivo  | </p></label>
									
									<div id="'.$Id_Form.'Imagen_div_in_server" class="Div_In_Server" style=""></div>
										<div id="'.$Id_Form.'Imagen_div" class="Div_Option_Upload" style="display:block;">
											
											<p class="label_p"> Peso Máximo: 100.00 | 
											Extenciones Permitidas: png,jpg,jpge</p>
											<div id="msg_rs"></div>
											<div class="background_lp" id="'.$Id_Form.'Imagenbackground_lp">
											    <div id="'.$Id_Form.'Imagenlinea_pregress" class="linea_pregress"></div>
											</div>
											<input type="file" name="'.$Id_Form.'_'.$Id_Edu_Pregunta.'_File" 
											direction="" 
											id="'.$Id_Form.'Imagen" 
											object="'.$Id_Form.'" 
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
			
			if($Pregunta_Actual == 0){
				
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