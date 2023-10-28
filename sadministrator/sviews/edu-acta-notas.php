<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();


class Edu_Acta_Notas{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-acta-notas";
		$UrlFile_Edu_Articulo_det = "/sadministrator/edu-articulo-det";
		$UrlFile_Edu_Tipo_Estructura = "/sadministrator/edu_tipo_estructura";
		$UrlFile_Edu_Area_Conocimiento = "/sadministrator/edu_area_conocimiento";
		$UrlFile_Edu_Sub_Linea = "/sadministrator/edu_sub_linea";
		$UrlFile_Edu_Productor = "/sadministrator/edu_productor";
		$UrlFile_Edu_Perfil_Educacion = "/sadministrator/edu-perfil-educacion";		
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];
        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					case "Aspecto_Evaluativo_Crud":
					
                            $Data = array();					
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Aspecto_Evaluativo"],"Id_Edu_Aspecto_Evaluativo",$Data);  
							$key=$Parm["key"];
							
							//$Settings["interface"] = "/edu-acta-notas/interface/key/".$key;
							//$Settings["REDIRECT"] = $Redirect;
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							//new Edu_Articulo_Det($Settings);
							
							
							 $Settings = array();
							 $Settings['Url'] = $UrlFile."/interface/List_Aspecto/key/".$key;
							 $Settings['Screen'] = "animatedModal5";
							 $Settings['Type_Send'] = "";
							 DCRedirectJSSP($Settings);
							 DCExit();	
						// }

					break;	
									
							
				}		
				
            break;
            case "CHANGE":
            	 switch ($Obj) {
            	 	case "Vinculacion_Objeto_Acta_Crud":
					
                            $Data = array();					
						    // DCCloseModal();									
							//DCSave($Obj,$Conection,$Parm["Id_Edu_Objeto_Evaluativo"],"Id_Edu_Objeto_Evaluativo",$Data);  
							$key=$Parm["key"];
							$Id_Edu_Objeto_Evaluativo=$Parm["Id_Edu_Objeto_Evaluativo"];

							$Id_Edu_Aspecto_Evaluativo=DCPost("Id_Edu_Aspecto_Evaluativo");
							$Incluir_Acta=DCPost("Incluir_Acta");

							if (!empty($Id_Edu_Objeto_Evaluativo)) {
								$reg = array(
									'Id_Edu_Aspecto_Evaluativo' => $Id_Edu_Aspecto_Evaluativo,
									'Incluir_Acta' => $Incluir_Acta
								);
								$where = array('Id_Edu_Objeto_Evaluativo' => $Id_Edu_Objeto_Evaluativo,"Entity"=>$Entity);
								$rg = ClassPDO::DCUpdate('edu_objeto_evaluativo', $reg , $where, $Conection,"");

								$Mensaje="Se actualizo la informacion";

							}

							DCWrite(Message($Mensaje,"C"));
							
							//$Settings["interface"] = "/edu-acta-notas/interface/key/".$key;
							//$Settings["REDIRECT"] = $Redirect;
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							//new Edu_Articulo_Det($Settings);
							
							
							 $Settings = array();
							 $Settings['Url'] = $UrlFile."/interface/Acta_Nota/key/".$key;
							 $Settings['Screen'] = "animatedModal5";
							 $Settings['Type_Send'] = "";
							 DCRedirectJS($Settings);
							 DCExit();	
						// }
					break;	
            	 	
            	 }

            break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "Vinculacion_Objeto_Acta_Crud":
						
						$this->ObjectDelete($Parm);
						
						// DCCloseModal();		
						$Settings["interface"] = "List_Aspecto";
						$Settings["key"] = $Parm["key"];
					    new Edu_Acta_Notas($Settings);
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
				
       		case "Acta_Nota":
				$key=$Parm["key"];

				$LayoutB  = new LayoutB();
				
				
				$listMn = "<i class='icon-chevron-right'></i> Generar Aspectos <br>de evaluacion [".$UrlFile."/interface/List_Aspecto/key/".$key."[animatedModal5[HXM[{";
				$listMn .= "<i class='zmdi zmdi-edit'></i> Relacionar Objeto y Aspecto [".$UrlFile."/interface/Listado_Objeto/key/".$key."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Tipo de Estructura [".$UrlFile_Edu_Tipo_Estructura.$Redirect."/Interface/List[animatedModal5[HXM[{";
				
												
				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-long-arrow-left zmd-fw'></i> Atras ]" .$UrlFile_Edu_Articulo_det."/interface/begin/request/on/key/".$key."/action/sugerencia]]]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');
				
						
				$Row_Producto =  SetttingsSite::Main_Data_Producto($key);
				$Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
				$Nombre_Articulo = $Row_Producto->Nombre;		

				$DCPanelTitle = DCPanelTitle("ACTA DE NOTAS",$Nombre_Articulo,$btn);

				$Query = "
				    SELECT EA.Tipo_Evaluar from edu_almacen EA
				    WHERE EA.Id_Edu_Almacen=:Id_Edu_Almacen AND EA.Entity=:Entity
				";
				$Where = ["Id_Edu_Almacen" =>$key, "Entity" =>$Entity];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);
				$Tipo_Evaluar=$Row->Tipo_Evaluar;

				if ($Tipo_Evaluar=="Porcentaje") {
					// $Content=$this->View_Porcentaje($Parm);
				}else{
					$Content=$this->View_Promediado($Parm);
				}




				
				 
				$Plugin = DCTablePluginA();				
				
				$Contenido = DCPage($DCPanelTitle , $Content .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($LayoutB->main($Contenido.$Style,$Row_Producto));
				}else{
					DCWrite($Contenido.$Style);			
				}			
				
				
            break;
        	case "List_Aspecto":
		
				$Name_Interface = "Listado de Aspectos";
				$key = $Parm["key"];
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/interface/Create_Aspecto/key/".$key."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT EAE.Id_Edu_Aspecto_Evaluativo AS CodigoLink
				
					, EAE.Agrupador 
					, EAE.Nombre
					, EAE.Valor_Ponderado 
					
					FROM edu_aspecto_evaluativo EAE
				    WHERE EAE.Entity=:Entidad AND EAE.Estado=:Estado
					ORDER BY EAE.Agrupador DESC
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Aspecto_Evaluativo';
				$Link = $UrlFile."/interface/Create_Aspecto/key/".$key;
				$Screen = 'animatedModal5';
				$where = ["Entidad"=>$Entity,"Estado"=>"Activo"];		

				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');						

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
            break;
            case "Listado_Objeto":
		
				$Name_Interface = "Vinculacion de Aspectos";
				$key = $Parm["key"];
				
				
				$btn = DCButton("", 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT 
					EOE.Id_Edu_Objeto_Evaluativo as CodigoLink
					,EOE.Nombre 
		
					FROM edu_objeto_evaluativo_detalle EOED
					INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
					INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo=EA.Id_Edu_Articulo
					where EOED.Entity=:Entidad AND EA.Id_Edu_Almacen=:Id_Edu_Almacen
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Objeto_Evaluativo';
				$Link = $UrlFile."/interface/Create_Vinculacion_Objeto_Acta/key/".$key;
				$Screen = 'animatedModal5';
				$where = ["Entidad"=>$Entity,"Id_Edu_Almacen"=>$key];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '',$Link, $LinkId, $Screen, 'warehouse', '', '','PS');				

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
            break;

            case "Create_Aspecto":
            	$key = $Parm["key"];
		
				$btn .= "Atrás]" .$UrlFile."/interface/List_Aspecto/key/".$key."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Aspecto_Evaluativo = $Parm["Id_Edu_Aspecto_Evaluativo"];
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Aspecto_Evaluativo_Crud/key/".$key."/Id_Edu_Aspecto_Evaluativo/".$Id_Edu_Aspecto_Evaluativo;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/key/".$key."/Id_Edu_Aspecto_Evaluativo/".$Id_Edu_Aspecto_Evaluativo;
				
				if(!empty($Id_Edu_Aspecto_Evaluativo)){
				    $Name_Interface = "Editar Componente";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Aspecto_Evaluativo_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Aspectos";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Publicacion_Blog_Categoria"," SELECT Id_Publicacion_Blog_Categoria AS Id, Nombre AS Name FROM publicacion_blog_categoria ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Aspecto_Evaluativo_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Aspecto_Evaluativo_Crud",$Class,$Id_Edu_Aspecto_Evaluativo,$PathImage,$Combobox,$Buttons,"Id_Edu_Aspecto_Evaluativo");
				// $Form1 = BFormVertical("Edu_Tipo_Componente_Crud",$Class,$Id_Edu_Tipo_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Tipo_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
            break;
            case "Create_Vinculacion_Objeto_Acta":
            	$Id_Edu_Objeto_Evaluativo = $Parm["Id_Edu_Objeto_Evaluativo"];
            	$key=$Parm["key"];
		
				$btn .= "Atrás]" .$UrlFile."/interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$DirecctionA = $UrlFile."/Process/CHANGE/Obj/Vinculacion_Objeto_Acta_Crud/Id_Edu_Objeto_Evaluativo/".$Id_Edu_Objeto_Evaluativo."/key/".$key;
				//$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Pro_Tipo_Evento/".$Id_Pro_Tipo_Evento;
				
				if(!empty($Id_Edu_Objeto_Evaluativo)){
				    $Name_Interface = "Editar Componente";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                   // $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Pro_Tipo_Evento_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Aspectos";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Edu_Aspecto_Evaluativo"," SELECT Id_Edu_Aspecto_Evaluativo AS Id, Nombre AS Name FROM edu_aspecto_evaluativo ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Vinculacion_Objeto_Acta_Crud"),$ButtonAdicional
				);	
				$Js = Biblioteca::JsComboBoxArea_Conocimiento();
		        $Form1 = BFormVertical("Vinculacion_Objeto_Acta_Crud",$Class,$Id_Edu_Objeto_Evaluativo,$PathImage,$Combobox,$Buttons,"Id_Edu_Objeto_Evaluativo","");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html. $Js);
                DCExit();
            break;
			
	
            case "Create":
			     
				 
	            $Id_User = $Parm["Id_User"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/User_Register_Crud/Id_User/".$Id_User;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_User/".$Id_User;
				
				if(!empty($Id_User)){
					
				    $Name_Interface = "Editar Datos del Usuario";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","User_Register_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Usuario";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","User_Register_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Perfil"," SELECT Id_Perfil AS Id, Nombre AS Name FROM perfil ",[])
						 
					);
				
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","User_Register_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("User_Register_Crud",$Class,$Id_User,$PathImage,$Combobox,$Buttons,"Id_User");
				$Js = Biblioteca::JsComboBoxArea_Conocimiento();				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
				
                DCWrite($Html . $Js);
                DCExit();
            break;
				
       			
            case "DeleteMassive":
		
            	$key = $Parm["key"];
	            $Id_Edu_Aspecto_Evaluativo = $Parm["Id_Edu_Aspecto_Evaluativo"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/key/".$key."/Id_Edu_Aspecto_Evaluativo/".$Id_Edu_Aspecto_Evaluativo."/Obj/Vinculacion_Objeto_Acta_Crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/List_Aspecto/key/".$Parm["key"]."]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;				
				
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
        
		$key = $Parm["key"];
		$Id_Edu_Aspecto_Evaluativo = $Settings["Id_Edu_Aspecto_Evaluativo"];
		
		$where = array('Id_Edu_Aspecto_Evaluativo' =>$Id_Edu_Aspecto_Evaluativo);
		$rg = ClassPDO::DCDelete('edu_aspecto_evaluativo', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}	

	public function  View_Porcentaje($Settings){
		global $Conection, $DCTimeHour,$NameTable;
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		$key = $Settings["key"];


				$info="";
				$cabecera_Evaluacion="";
				$countasexamenes=0;
				//Suscriptor
				$Query_Suscripcion = "SELECT SUS.Id_Suscripcion,UM.Nombre as Nombre_User from suscripcion SUS
									INNER JOIN user_miembro UM ON SUS.Id_User=UM.Id_User_Miembro
								where SUS.Entity=:Entity AND SUS.Id_Edu_Almacen=:Id_Edu_Almacen";   
				$Where_Suscripcion = ["Entity"=>$Entity,"Id_Edu_Almacen"=>$key];
				$Row_Suscripcion = ClassPdo::DCRows($Query_Suscripcion,$Where_Suscripcion,$Conection);
				foreach ($Row_Suscripcion as $RowSUS) {
							$Id_Suscripcion=$RowSUS->Id_Suscripcion;
							$Nombre_User=$RowSUS->Nombre_User;

							//Aspecto
							$QueryAspecto = "SELECT EAE.Id_Edu_Aspecto_Evaluativo from edu_aspecto_evaluativo EAE
											where EAE.Entity=:Entity";   
							$WhereAspecto = ["Entity"=>$Entity];
							$RowAspecto = ClassPdo::DCRows($QueryAspecto,$WhereAspecto,$Conection);
							$info.='<tr><td>'.$Nombre_User.'</td>';
							foreach ($RowAspecto as $RowA) {
								$Id_Edu_Aspecto_Evaluativo=$RowA->Id_Edu_Aspecto_Evaluativo;

									for ($i=0; $i <count($Id_Edu_Aspecto_Evaluativo); $i++) { 
											//NOTA
											$Query ="SELECT EOE.Id_Edu_Objeto_Evaluativo,EOE.Nombre,EAE.Nombre as Aspecto from edu_objeto_evaluativo_detalle EOED
													INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
			                        				INNER JOIN edu_aspecto_evaluativo EAE ON EOE.Id_Edu_Aspecto_Evaluativo=EAE.Id_Edu_Aspecto_Evaluativo
													INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo=EA.Id_Edu_Articulo
													where EOED.Entity=:Entity AND EA.Id_Edu_Almacen=:Id_Edu_Almacen 
													AND EOE.Id_Edu_Aspecto_Evaluativo=:Aspecto_ID AND EOE.Incluir_Acta=:Incluir_Acta   
													ORDER BY EAE.Id_Edu_Aspecto_Evaluativo ASC"; 
											$Where = ["Id_Edu_Almacen"=>$key,"Aspecto_ID"=>$Id_Edu_Aspecto_Evaluativo[$i],"Entity"=>$Entity,"Incluir_Acta"=>"SI"];
											$Row2 = ClassPdo::DCRows($Query,$Where,$Conection);
											
													foreach ($Row2 as $field) {
														$Nombre_Evaluativo=$field->Nombre;
														$Aspecto=$field->Aspecto;
														$Id_Edu_Objeto_Evaluativo=$field->Id_Edu_Objeto_Evaluativo;
														$countasexamenes+=1;
														

														$Query_evaluativo ="SELECT EEDC.Id_Suscripcion,UM.Nombre as Nombre_User, EEDC.Nota from edu_evaluacion_desarrollo_cab EEDC
																INNER JOIN edu_objeto_evaluativo EOE ON EEDC.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
																INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion=SUS.Id_Suscripcion
															    INNER JOIN user_miembro UM ON SUS.Id_User=UM.Id_User_Miembro
															    where EEDC.Entity=:Entity AND EOE.Id_Edu_Objeto_Evaluativo=:Id_Edu_Objeto_Evaluativo 
															    AND  EOE.Id_Edu_Aspecto_Evaluativo=:Aspecto_ID AND SUS.Id_Suscripcion=:Id_Suscripcion
															     ORDER BY EOE.Id_Edu_Aspecto_Evaluativo ASC"; 
														$Where_evaluativo = ["Id_Edu_Objeto_Evaluativo"=>$Id_Edu_Objeto_Evaluativo,"Aspecto_ID"=>$Id_Edu_Aspecto_Evaluativo[$i],"Entity"=>$Entity,"Id_Suscripcion"=>$Id_Suscripcion];
														$Rownota = ClassPdo::DCRows($Query_evaluativo,$Where_evaluativo,$Conection);
															foreach ($Rownota as $fieldnota) {
																$Nombre_User=$fieldnota->Nombre_User;
																$Nota=$fieldnota->Nota;
																$info.='<td>'.$Nota.'</td>';
															}


													}
									}

							}
				}



				//NOTA NOMBRES
				$Query3="SELECT EOE.Nombre,EAE.Valor_Ponderado AS Aspecto 
						from edu_objeto_evaluativo_detalle EOED
						INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
					    INNER JOIN edu_aspecto_evaluativo EAE ON EOE.Id_Edu_Aspecto_Evaluativo=EAE.Id_Edu_Aspecto_EvaluativO
						INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo=EA.Id_Edu_Articulo
	  					where EOED.Entity=:Entity 
						AND EA.Id_Edu_Almacen=:Id_Edu_Almacen 
						AND EOE.Incluir_Acta=:Incluir_Acta
			  				 ORDER BY EOE.Id_Edu_Aspecto_Evaluativo ASC"; 
											$Where3 = ["Id_Edu_Almacen"=>$key,"Entity"=>$Entity,"Incluir_Acta"=>"SI"];
											$Row3 = ClassPdo::DCRows($Query3,$Where3,$Conection);
											foreach ($Row3 as $field3) {
												$Nombre_Evaluativo=$field3->Nombre;
												$Aspecto=$field3->Aspecto;
												$cabecera_Evaluacion.="<th style='width:10%;TEXT-ALIGN:center;'>".$Nombre_Evaluativo."<br>(".$Aspecto." %)</th>";
											}

				$Content='
				<table id="table-1" class="table table-hover" style="TEXT-ALIGN: CENTER;TEXT-ALIGN-LAST: CENTER;">
					<thead>
						<tr>
							<th rowspan="2">Nombres</th><th colspan="'.$countasexamenes.'">Aspecto a Evaluar</th><th rowspan="2">NOTA <br>FINAL</th><th rowspan="2">ACCION</th>
						</tr>
						<tr>'.$cabecera_Evaluacion.'</tr>
					</thead>
					'.$info.'
					</tr></tbody>
				</table>';
				return $Content;
	}


	public function  View_Promediadob($Settings){
		global $Conection, $DCTimeHour,$NameTable;
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		$key = $Settings["key"];


				$info="";
				$cabecera_Evaluacion="";
				$countasexamenes=0;
				//Suscriptor
				
				$Query_Suscripcion = "
				SELECT SUS.Id_Suscripcion,UM.Nombre as Nombre_User from suscripcion SUS
				INNER JOIN user_miembro UM ON SUS.Id_User=UM.Id_User_Miembro
				where SUS.Entity=:Entity AND SUS.Id_Edu_Almacen=:Id_Edu_Almacen
				";   
				$Where_Suscripcion = ["Entity"=>$Entity,"Id_Edu_Almacen"=>$key];
				$Row_Suscripcion = ClassPdo::DCRows($Query_Suscripcion,$Where_Suscripcion,$Conection);
				foreach ($Row_Suscripcion as $RowSUS) {
							$Id_Suscripcion=$RowSUS->Id_Suscripcion;
							$Nombre_User=$RowSUS->Nombre_User;
							$info.='<tr><td>'.$Nombre_User.'</td>';

							//Aspecto
							$QueryAspecto = "
							                SELECT EAE.Id_Edu_Aspecto_Evaluativo 
							                FROM  edu_aspecto_evaluativo EAE
											WHERE EAE.Entity=:Entity";   
							$WhereAspecto = ["Entity"=>$Entity];
							$RowAspecto = ClassPdo::DCRows($QueryAspecto,$WhereAspecto,$Conection);
							
							foreach ($RowAspecto as $RowA) {
								
								
								$Id_Edu_Aspecto_Evaluativo=$RowA->Id_Edu_Aspecto_Evaluativo;

									for ($i=0; $i <count($Id_Edu_Aspecto_Evaluativo); $i++) { 
											//NOTA
											$Query ="SELECT EOE.Id_Edu_Objeto_Evaluativo,EOE.Nombre,EAE.Nombre as Aspecto from edu_objeto_evaluativo_detalle EOED
													INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
			                        				INNER JOIN edu_aspecto_evaluativo EAE ON EOE.Id_Edu_Aspecto_Evaluativo=EAE.Id_Edu_Aspecto_Evaluativo
													INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo=EA.Id_Edu_Articulo
													where EOED.Entity=:Entity AND EA.Id_Edu_Almacen=:Id_Edu_Almacen 
													AND EOE.Id_Edu_Aspecto_Evaluativo=:Aspecto_ID AND EOE.Incluir_Acta=:Incluir_Acta   
													ORDER BY EAE.Id_Edu_Aspecto_Evaluativo ASC"; 
											$Where = ["Id_Edu_Almacen"=>$key,"Aspecto_ID"=>$Id_Edu_Aspecto_Evaluativo[$i],"Entity"=>$Entity,"Incluir_Acta"=>"SI"];
											$Row2 = ClassPdo::DCRows($Query,$Where,$Conection);
											
													foreach ($Row2 as $field) {
														$Nombre_Evaluativo=$field->Nombre;
														$Aspecto=$field->Aspecto;
														$Id_Edu_Objeto_Evaluativo=$field->Id_Edu_Objeto_Evaluativo;
														$countasexamenes+=1;
														

														$Query_evaluativo ="SELECT EEDC.Id_Suscripcion,UM.Nombre as Nombre_User, EEDC.Nota from edu_evaluacion_desarrollo_cab EEDC
																INNER JOIN edu_objeto_evaluativo EOE ON EEDC.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
																INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion=SUS.Id_Suscripcion
															    INNER JOIN user_miembro UM ON SUS.Id_User=UM.Id_User_Miembro
															    where EEDC.Entity=:Entity AND EOE.Id_Edu_Objeto_Evaluativo=:Id_Edu_Objeto_Evaluativo 
															    AND  EOE.Id_Edu_Aspecto_Evaluativo=:Aspecto_ID AND SUS.Id_Suscripcion=:Id_Suscripcion
															    ORDER BY EOE.Id_Edu_Aspecto_Evaluativo ASC"; 
														$Where_evaluativo = ["Id_Edu_Objeto_Evaluativo"=>$Id_Edu_Objeto_Evaluativo,"Aspecto_ID"=>$Id_Edu_Aspecto_Evaluativo[$i],"Entity"=>$Entity,"Id_Suscripcion"=>$Id_Suscripcion];
														$Rownota = ClassPdo::DCRows($Query_evaluativo,$Where_evaluativo,$Conection);
															foreach ($Rownota as $fieldnota) {
																$Nombre_User=$fieldnota->Nombre_User;
																$Nota=$fieldnota->Nota;
																$info.='<td>'.$Nota.'</td>';
															}


													}
									}

							}
				}



				//NOTA NOMBRES
				$Query3="SELECT EOE.Nombre from edu_objeto_evaluativo_detalle EOED
						INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
						INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo=EA.Id_Edu_Articulo
	  					where EOED.Entity=:Entity AND EA.Id_Edu_Almacen=:Id_Edu_Almacen AND EOE.Incluir_Acta=:Incluir_Acta
			  		    ORDER BY EOE.Id_Edu_Aspecto_Evaluativo ASC"; 
				$Where3 = ["Id_Edu_Almacen"=>$key,"Entity"=>$Entity,"Incluir_Acta"=>"SI"];
				$Row3 = ClassPdo::DCRows($Query3,$Where3,$Conection);
				foreach ($Row3 as $field3) {
								$Nombre_Evaluativo=$field3->Nombre;
								$cabecera_Evaluacion.="<th style='width:10%;TEXT-ALIGN:center;'>".$Nombre_Evaluativo."</th>";
				}

				$Content='
				<table id="table-1" class="table table-hover" style="TEXT-ALIGN: CENTER;TEXT-ALIGN-LAST: CENTER;">
					<thead>
						<tr>
							<th rowspan="2">Nombres</th><th colspan="'.$countasexamenes.'">Aspecto a Evaluar</th><th rowspan="2">NOTA <br>FINAL</th><th rowspan="2">ACCION</th>
						</tr>
						<tr>'.$cabecera_Evaluacion.'</tr>
					</thead>
					'.$info.'
					</tr></tbody>
				</table>';
				return $Content;
	}
	
	
	public function  View_Promediado($Settings){
		global $Conection, $DCTimeHour,$NameTable;
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		$key = $Settings["key"];


		$Row_Producto =  SetttingsSite::Main_Data_Producto($key);
		$Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		$Nombre_Articulo = $Row_Producto->Nombre;		
				
		$info=""; $cabecera_Evaluacion=""; $countasexamenes=0;$countalum=0;$cabecera_EvaluacionB="";
		
		

		//evaluaciones
		$Query ="
		
			SELECT 
			EOE.Id_Edu_Objeto_Evaluativo
			,EOE.Nombre as ObjetoEvaluativo
			,EAE.Id_Edu_Aspecto_Evaluativo 
			,EAE.Nombre as Aspecto 
			FROM edu_objeto_evaluativo_detalle EOED
			INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
			INNER JOIN edu_aspecto_evaluativo EAE ON EOE.Id_Edu_Aspecto_Evaluativo=EAE.Id_Edu_Aspecto_Evaluativo
		
			WHERE 
			EOED.Entity=:Entity 
			AND EOED.Id_Edu_Articulo=:Id_Edu_Articulo 
			AND  EOE.Incluir_Acta=:Incluir_Acta   
			ORDER BY EAE.Id_Edu_Aspecto_Evaluativo, EOED.Id_Edu_Objeto_Evaluativo ASC  
			
			"; 
		$Where = ["Id_Edu_Articulo"=>$Id_Edu_Articulo,"Entity"=>$Entity,"Incluir_Acta"=>"SI"];
		$Row2 = ClassPdo::DCRows($Query,$Where,$Conection);
		$Id_Edu_Aspecto_Evaluativo_variable="";	$ContadorAspecto=0;	
		$cabecera_Evaluacion = '<td>CONCEPTOS DE EVALUACIÓN </td>';		
		$cabecera_EvaluacionB = '<td>NOMBRES</td>';	
        $Count_Concepto_Evaluarivo = 0;
        $Array_Concepto_Eval = array();		
        $Array_Objeto_Eval = array();		
		foreach ($Row2 as $field) {
			
					$Id_Edu_Objeto_Evaluativo=$field->Id_Edu_Objeto_Evaluativo;
					$Nombre_Evaluativo=$field->ObjetoEvaluativo;
					$Id_Edu_Aspecto_Evaluativo=$field->Id_Edu_Aspecto_Evaluativo;
					$Aspecto=$field->Aspecto;
							// $cabecera_Evaluacion .= '<td style="style="text-align: left;"">'.$Nombre_Evaluativo.'</td>';
					if( $Id_Edu_Aspecto_Evaluativo_variable == $Id_Edu_Aspecto_Evaluativo ) { 
					
					}else{
						

						$Query ="

						SELECT 
						count( * ) AS Cont_Aspectos
						FROM edu_objeto_evaluativo_detalle EOED
						INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
						INNER JOIN edu_aspecto_evaluativo EAE ON EOE.Id_Edu_Aspecto_Evaluativo=EAE.Id_Edu_Aspecto_Evaluativo

						WHERE 
						EOED.Entity=:Entity 
						AND EOED.Id_Edu_Articulo=:Id_Edu_Articulo 
						AND  EOE.Incluir_Acta=:Incluir_Acta   
						AND EAE.Id_Edu_Aspecto_Evaluativo =:Id_Edu_Aspecto_Evaluativo 
						ORDER BY EAE.Id_Edu_Aspecto_Evaluativo ASC  

						"; 
						$Where = ["Entity"=>$Entity,"Id_Edu_Articulo"=>$Id_Edu_Articulo,"Incluir_Acta"=>"SI","Id_Edu_Aspecto_Evaluativo"=> $Id_Edu_Aspecto_Evaluativo];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);
						$Cont_Aspectos = $Row->Cont_Aspectos + 1;
						
						$Array_Concepto_Eval[$Count_Concepto_Evaluarivo] = $Id_Edu_Aspecto_Evaluativo;
						
					    $Count_Concepto_Evaluarivo +=1;
						
						$cabecera_Evaluacion .= '<td  colspan="'.$Cont_Aspectos.'" style="text-align:center;text-transform: uppercase;" >'.$Aspecto.'</td>';
												
								$Query ="
								
									SELECT 
									EOE.Id_Edu_Objeto_Evaluativo
									,EOE.Nombre as ObjetoEvaluativo
									,EAE.Id_Edu_Aspecto_Evaluativo 
									,EAE.Nombre as Aspecto 
									FROM edu_objeto_evaluativo_detalle EOED
									INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
									INNER JOIN edu_aspecto_evaluativo EAE ON EOE.Id_Edu_Aspecto_Evaluativo=EAE.Id_Edu_Aspecto_Evaluativo
								
									WHERE 
									EOED.Entity=:Entity 
									AND EOED.Id_Edu_Articulo=:Id_Edu_Articulo 
									AND  EOE.Incluir_Acta=:Incluir_Acta 
						            AND EAE.Id_Edu_Aspecto_Evaluativo =:Id_Edu_Aspecto_Evaluativo								
									ORDER BY EOE.Id_Edu_Objeto_Evaluativo ASC  
									
									"; 
								$Where = ["Entity"=>$Entity,"Id_Edu_Articulo"=>$Id_Edu_Articulo,"Incluir_Acta"=>"SI","Id_Edu_Aspecto_Evaluativo"=> $Id_Edu_Aspecto_Evaluativo];
							
								$Row3 = ClassPdo::DCRows($Query,$Where,$Conection);
				                $Count_Objeto_Evaluativo = 0;
								foreach ($Row3 as $field) {		
								
									    $Count_Objeto_Evaluativo += 1 ;
						                $ObjetoEvaluativo=$field->ObjetoEvaluativo;
						                $Id_Edu_Objeto_Evaluativo=$field->Id_Edu_Objeto_Evaluativo;
									   
						                $Array_Objeto_Eval[$Count_Objeto_Evaluativo] = $Id_Edu_Objeto_Evaluativo;						
						
						               $cabecera_EvaluacionB .= '<td style="text-align:center;text-transform: lowercase;" >'.$ObjetoEvaluativo.'</td>';
						
								}
						               $cabecera_EvaluacionB .= '<td style="text-align:center;">Acu.</td>';						
						               // $cabecera_EvaluacionB .= '<td>Pro.</td>';						
					}
					
					$Id_Edu_Aspecto_Evaluativo_variable = $Id_Edu_Aspecto_Evaluativo;

		}
		$cabecera_Evaluacion .= '<td style="text-align:center;" rowspan="2">Tot.</td>';			



		$Query ="
		
			SELECT 
			EEDC.Nota,
			EEDC.Id_Suscripcion,
			EEDC.Id_Edu_Objeto_Evaluativo,
			EOE.Id_Edu_Aspecto_Evaluativo
			FROM edu_evaluacion_desarrollo_cab EEDC
			INNER JOIN edu_objeto_evaluativo EOE ON EEDC.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
			INNER JOIN edu_aspecto_evaluativo EAE ON EOE.Id_Edu_Aspecto_Evaluativo=EAE.Id_Edu_Aspecto_Evaluativo
			INNER JOIN edu_objeto_evaluativo_detalle EOED ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
			WHERE 
			EOED.Entity=:Entity 
			AND EOED.Id_Edu_Articulo=:Id_Edu_Articulo 
			AND  EOE.Incluir_Acta=:Incluir_Acta   
			ORDER BY EAE.Id_Edu_Aspecto_Evaluativo, EOED.Id_Edu_Objeto_Evaluativo ASC  
			
			"; 
		$Where = ["Entity"=>$Entity,"Id_Edu_Articulo"=>$Id_Edu_Articulo,"Incluir_Acta"=>"SI"];
		$Row2 = ClassPdo::DCRows($Query,$Where,$Conection);


		$Query ="
		
				SELECT 
				EOE.Id_Edu_Objeto_Evaluativo
				,EOE.Nombre as ObjetoEvaluativo
				,EAE.Id_Edu_Aspecto_Evaluativo 
				,EAE.Nombre as Aspecto 
				FROM edu_objeto_evaluativo_detalle EOED
				INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
				INNER JOIN edu_aspecto_evaluativo EAE ON EOE.Id_Edu_Aspecto_Evaluativo=EAE.Id_Edu_Aspecto_Evaluativo
				WHERE 
				EOED.Entity= :Entity
				AND EOED.Id_Edu_Articulo= :Id_Edu_Articulo
				AND EOE.Incluir_Acta= :Incluir_Acta
						
				ORDER BY EOE.Id_Edu_Objeto_Evaluativo ASC  
			
			"; 
		$Where = ["Entity"=>$Entity,"Id_Edu_Articulo"=>$Id_Edu_Articulo,"Incluir_Acta"=>"SI"];
		$Row3 = ClassPdo::DCRows($Query,$Where,$Conection);
		
	

		$Query_Suscripcion = "SELECT SUS.Id_Suscripcion,UM.Nombre as Nombre_User from suscripcion SUS
							INNER JOIN user_miembro UM ON SUS.Id_User=UM.Id_User_Miembro
							where SUS.Entity=:Entity 
							AND SUS.Id_Edu_Almacen=:Id_Edu_Almacen
							";   
		$Where_Suscripcion = ["Entity"=>$Entity,"Id_Edu_Almacen"=>$key];
		$Row_Suscripcion = ClassPdo::DCRows($Query_Suscripcion,$Where_Suscripcion,$Conection);
		$htmlcab='';
		$array_aspecto_evaluativo=array();

                    
		// $Count_Tot_Objetos_Eval = count($Array_Objeto_Eval);
		
		
		foreach ($Row_Suscripcion as $RowSUS) {
			
			$Id_Suscripcion=$RowSUS->Id_Suscripcion;
			$Nombre_User=$RowSUS->Nombre_User;
			
			$countalum+=1;
			
			$info.='<tr><td >'.$Nombre_User .'   </td>';	

			
			$Cont_Valores = 0;

			$Nota_Promedio = 0;
			$Count_Conceptos_Eval = 0;
			$Nota_Total = 0;
			$Matri_quiebres = "";
			
				foreach ($Array_Concepto_Eval as $Concepto_Evaluativo) {
					
					
					// echo $Concepto_Evaluativo."<br>";
					
					$Cont_Notas = 0;
					$Cont_Objetos_Evaluativos = 0;
					
					foreach ($Row2 as $itemB) {
                       
						    if( $itemB->Id_Suscripcion == $Id_Suscripcion && $itemB->Id_Edu_Aspecto_Evaluativo == $Concepto_Evaluativo ) {
								
								
								
							    $info.='<td style="text-align:center;" >'.$itemB->Nota.' </td>';
								
                                $Cont_Valores += 1;		
 								$Cont_Notas += $itemB->Nota; 
								
						
								// foreach ($Row3 as $itemC){	
					   
									// if( $itemC->Id_Edu_Aspecto_Evaluativo == $Concepto_Evaluativo ) {
										$Cont_Objetos_Evaluativos += 1;	
										// echo "hola<br>";
										
									// }   
								// }	
						    }
							
						
					}
					
					$Count_Tot_Objetos_Eval = 0;
					foreach ($Row3 as $itemC){	
		   
						if( $itemC->Id_Edu_Aspecto_Evaluativo == $Concepto_Evaluativo ) {
							$Count_Tot_Objetos_Eval += 1;	
							
						}   
					}	
								
					$Count_Celdas_Vacias = $Count_Tot_Objetos_Eval - $Cont_Objetos_Evaluativos;
					// $Matri_quiebres .= $Count_Tot_Objetos_Eval. " ...  " .$Cont_Objetos_Evaluativos." - ";
					

					
					for ($j = 0; $j < $Count_Celdas_Vacias; $j++) {	
						
								$info.='<td style="text-align:center;"> - </td>';
					}	

					$Nota_Promedio = round(( $Cont_Notas / $Count_Tot_Objetos_Eval),0);
					$Nota_Total += $Nota_Promedio;

						
				    $info.='<td style="text-align:center;" > '.$Nota_Promedio.' </td>';	
					$Count_Conceptos_Eval += 1;
								
				}
	
                // echo $Matri_quiebres."<br>";
				
				$info.='<td style="text-align:center;"> '.round(( $Nota_Total / $Count_Conceptos_Eval),0).' </td>';
				
				$info.='</tr>';
				
		}

		

				$Content='
				<table id="" class="table" >
					<thead>
			
						<tr style="    background: #cecccc;">'.$cabecera_Evaluacion.'</tr>
						<tr>'.$cabecera_EvaluacionB.'</tr>
					</thead>
					
					'.$info.'
					</tr></tbody>
				</table>';
				return $Content;
	
	
    }
}