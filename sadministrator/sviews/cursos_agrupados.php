<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
// echo 'samesite_test:' . $_COOKIE['samesite_test'];
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class CursosAgrupados{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/cursos_agrupados";
		$UrlFile_Edu_Participante = "/sadministrator/edu-participante";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];

		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {	

					case "programa_cab_crud":
					         
	
		                    $Data = array();					
											
							
							$Id_Programa_Cab = DCSave($Obj,$Conection,$Parm["Id_Programa_Cab"],"Id_Programa_Cab",$Data);
							$Nombre=DCPost("Nombre");
							$data2 = array(
										'Tipo_Proceso' =>  "Agrupados","Estado"=>"Creacion"
							);
							$where = array('Nombre' =>$Nombre);
							ClassPDO::DCUpdate("programa_cab", $data2 , $where, $Conection);	

							$Settings = array();
							$Settings['Url'] = $UrlFile;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "HXMS";
							DCRedirectJS($Settings);
							DCExit();	
					break;
					//Logica de Vinculacion
					case "vinculacion_curso_create":
					
				        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];			
						$Id_Warehouse = DCPost("ky");
						$columnas='';
						for ($j = 0; $j < count($Id_Warehouse); $j++) {
						
							// DCWrite("Warehouse:: ".$Id_Warehouse[$j]."<br>");
						
							$data = array(
							'Id_Programa_Cab' =>  $Id_Programa_Cab,
							'Id_Edu_Almacen' =>  $Id_Warehouse[$j],
							'Entity' => $Entity,
							'Id_User_Update' => $User,
							'Id_User_Creation' => $User,
							'Date_Time_Creation' => $DCTimeHour,
							'Date_Time_Update' => $DCTimeHour
							);
							$Return = ClassPDO::DCInsert("programa_det", $data, $Conection,"");	
						}
						//Cambio de estado
							$data = array(
										'Estado' =>  "Cursos Vinculados",
							);
							$where = array('Id_Programa_Cab' =>$Parm["Id_Programa_Cab"]);
							ClassPDO::DCUpdate("programa_cab", $data , $where, $Conection);	
						// DCCloseModal();	

						DCWrite(Message("Se ha vinculado el curso correctamente a la campaña","C"));									
						$Settings = array();
						$Settings['Url'] =$UrlFile;
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);
						
						//$Settings["Interface"] = "List";
						//$Settings["Id_Programa_Cab"] = $Id_Programa_Cab;
						//new CursosAgrupados($Settings);
						DCExit();		
					break;
					//Logica Matricula
					case "User_Register_Crud":

						$Data = array();
						$Data['Id_Programa_Cab'] = $Parm["Id_Programa_Cab"];	
						$Data['Id_Perfil_Educacion'] =  DCPost("Id_Perfil_Educacion");	

						//$Parm = array();
						//$Parm["clave"] = DCPost("Password");	
									
						//Edu_Register::Register_User($Data);
						$this->Proceso_inscripcion($Parm);
						//Cambio de estado
							$data = array(
										'Estado' =>  "Proceso Completado",
							);
							$where = array('Id_Programa_Cab' =>$Parm["Id_Programa_Cab"]);
							ClassPDO::DCUpdate("programa_cab", $data , $where, $Conection);	
						$Settings = array();
						$Settings['Url'] =$UrlFile;
						$Settings['Screen'] = "ScreenRight";
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
					case "programa_cab_crud":
						
						//$this->ObjectDelete($Parm);
						
						DCCloseModal();		
						$Settings["Interface"] = "";
					    new CursosAgrupados($Settings);
						DCExit();							
						
					break;
					//PROCESO DE VINCULACION DE CURSO			
					case "vinculacion_curso_eliminar":
						
					        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];			
							$Id_Warehouse = DCPost("ky");
							$columnas='';
							for ($j = 0; $j < count($Id_Warehouse); $j++) {
								

								$where = array('Id_Programa_Det' =>$Id_Warehouse[$j],'Id_Programa_Cab'=>$Id_Programa_Cab,'Entity'=>$Entity);
								$rg = ClassPDO::DCDelete('programa_det', $where, $Conection);				
											
								
							}
							
							DCWrite(Message("Se elimino el curso correctamente de la campaña","C"));									
							$Settings = array();
							$Settings['Url'] =$UrlFile;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "";
							DCRedirectJS($Settings);
							DCExit();		
					break;
					case "vinculacion_alumno_eliminar":
						
					        $this->ObjectDelete($Parm);
							
							DCWrite(Message("Se desabilito los cursos a los usuarios","C"));									
							$Settings = array();
							$Settings['Url'] =$UrlFile;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "";
							DCRedirectJS($Settings);
							DCExit();		
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
			
				if(empty($Parm["tipo_servicio"])){
					
				}else{
					$_SESSION['tipo_servicio'] =  $Parm["tipo_servicio"];
				}								
				
					
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Listado]".$UrlFile."/Interface/Create_Componente]ScreenRight]HXM_SP]]btn btn-primary}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form_Sers');

				$DCPanelTitle = DCPanelTitle("Listados de Campañas","Administración",$btn);

				
				$Query = "
				
				    SELECT 
					PC.Id_Programa_Cab AS CodigoLink
					, PC.Nombre	
					, PC.Fecha_Creada
					, PC.Estado	
					FROM programa_cab PC
					WHERE
					PC.Entity = :Entity AND PC.Tipo_Proceso!=:Tipo_Proceso
					ORDER BY PC.Date_Time_Creation ASC
			
				";    


				$Class = 'table table-hover';
				$LinkId = 'Id_Programa_Cab';
				$Link = $UrlFile."/Interface/tipo_proceso_realizar";
				$Screen = 'animatedModal5';
				$where = [ "Entity" => $Entity,"Tipo_Proceso"=>"Programa"];
				$Table = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','');
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Table .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
         break;
					
				
            case "Create_Componente":
			
				$layout  = new Layout();	
	            $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
				
				
				//$DirecctionA = $UrlFile."/Process/ENTRY/Obj/programa_cab_crud/Id_Programa_Cab/".$Id_Programa_Cab;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Programa_Cab/".$Id_Programa_Cab;
				
				if(!empty($Id_Programa_Cab)){
					
				    $Name_Interface = "Editar";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
				    $DirecctionA = $UrlFile."/Process/ENTRY/Obj/programa_cab_crud/Id_Programa_Cab/".$Id_Programa_Cab;
					
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form_P","programa_cab_crud","btn btn-default m-w-120");				
				
				}else{
					
				    $Name_Interface = "Crear Campaña";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear ";
				    $DirecctionA = $UrlFile."/Process/ENTRY/Obj/programa_cab_crud";
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Pro_Evento_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Programa_Cab"," SELECT Id_Pro_Tipo_Evento AS Id, Nombre AS Name FROM pro_tipo_evento ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","programa_cab_crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("programa_cab_crud",$Class,$Id_Programa_Cab,$PathImage,$Combobox,$Buttons,"Id_Programa_Cab");
				
				
				//$Js = Biblioteca::JsComboBoxTipoEstructura_Arb($Id_Pro_Evento);
				
				$Form1  = "<div style='background-color:#fff;'>".$Form1."</div>";
	         					
				
				$btn = " Volver al listado ]" .$UrlFile."]ScreenRight]HXM_SP]]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'programa_cab');
				$DCPanelTitle = DCPanelTitle($Name_Interface,"PRODUCCIÓN | FASE DE ESTUDIO ",$btn);
					
	
				
		        $Contenido = $DCPanelTitle.$Form1.$Js;				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
			break;

            //Case para vinculacion
            case "tipo_proceso_realizar":

			        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];					
					$btn = "Alumnos ]" .$UrlFile."/Interface/List_alumno/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HMS]programa_det]btn btn-default dropdown-toggle]}";				
					$btn .= "Cursos ]" .$UrlFile ."/Interface/List_curso/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]programa_det]btn btn-default dropdown-toggle]}";				
					$Button = DCButton($btn, 'botones1', 'sys_form_proceso');					
					
				    $Html = DCModalFormMsjInterno("Proceso de Vinculacion","¿Que vas a realizar?",$Button,"bg-info");
	                DCWrite($Html);
					DCExit();
	        break;
	        	//Listado de vinculacion
	        	case "List_alumno":
				
			        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
			        


					$Name_Interface = "Alumnos Vinculados";	

					$listMn = "<i class='icon-chevron-right'></i> Crear Usuarios [".$UrlFile."/Interface/Create_vinculacion_Alumno/Id_Programa_Cab/".$Id_Programa_Cab."[animatedModal5[HXMS[{";
					$listMn .= "<i class='icon-chevron-right'></i> Eiminar [".$UrlFile."/Interface/Eliminar_vinculacion_alumnos/Id_Programa_Cab/".$Id_Programa_Cab."[Msj-Accion[HXMS[{";
					$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
					
					//$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/Interface/Create_vinculacion_Alumno/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-primary ladda-button}";
					//$btn .= " Borrar ]" .$UrlFile."/Interface/Eliminar_vinculacion/Id_Programa_Cab/".$Id_Programa_Cab."]Msj-Accion]HXMS]]btn}";
					$btn .= " Volver ]" .$UrlFile."/Interface/tipo_proceso_realizar/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-default}";
					$btn = DCButton($btn, 'botones1', 'programa_cab');				
					$DCPanelTitle = DCPanelTitle("","Estos son los alumnos vinculados ",$btn,"");
					
				
					
					/*$Query = "
						SELECT
						PA.Id_Programa_Cab AS CodigoLink , 
						US.Nombre,
						US.Usuario_Login,
						PA.Conteo as Cursos_Matriculados
						,PA.Id_User,(SELECT COUNT())
					FROM programa_alumno PA
					INNER JOIN  user US ON PA.Id_User = US.Id_User
					WHERE PA.Entity = :Entity AND  PA.Id_Programa_Cab=:Id_Programa_Cab


					"; */
					$Query = "SELECT DISTINCT(US.Usuario_Login),UM.Nombre,
						 SC.Estado,(SELECT COUNT(*) FROM programa_det where Id_Programa_Cab=:Id_Programa_Cab) as Conteo,UM.Id_User_Miembro as CodigoLink
							FROM suscripcion SC
							INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
							INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
							WHERE SC.Entity =:Entity AND  SC.Id_Programa_Cab=:Id_Programa_Cab";
					$Class = 'table table-hover';
					$LinkId = 'Id_User_Miembro';
					$Link = $UrlFile."";
					$Screen = 'animatedModal5';
					$where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
					// var_dump($where);
					$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '',"","", $Screen, 'programa_det', 'checks', '','PS');				
				    $msj = "<div id='Msj-Accion'></div>";
				    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $msj . $Listado,"");
	                DCWrite($Html);
	                DCExit();
	            break;
	            case "List_curso":
				
			        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
					
					$Name_Interface = "Listado de Cursos en Campaña";

					$listMn = "<i class='icon-chevron-right'></i> Agregar Cursos [".$UrlFile."/Interface/Create_vinculacion/Id_Programa_Cab/".$Id_Programa_Cab."[animatedModal5[HXMS[{";
					$listMn .= "<i class='icon-chevron-right'></i> Eiminar [".$UrlFile."/Interface/Eliminar_vinculacion/Id_Programa_Cab/".$Id_Programa_Cab."[Msj-Accion[HXMS[{";
					$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";	
					
					//$btn .= "<i class='zmdi zmdi-edit'></i> Agregar Cursos]" .$UrlFile."/Interface/Create_vinculacion/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-primary ladda-button}";
					$btn .= " Volver ]" .$UrlFile."/Interface/tipo_proceso_realizar/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-default}";
					$btn = DCButton($btn, 'botones1', 'programa_cab');				
					$DCPanelTitle = DCPanelTitle("","Estos son los cursos seleccionados ",$btn,"");
					
				
					
					$Query = "
						SELECT 
						PD.Id_Programa_Det AS CodigoLink
						,EAA.Nombre as Curso_Vinculado
						, PD.Date_Time_Creation as Fecha_Creada
						FROM programa_det PD
						INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
	             		inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
						WHERE
						PD.Entity = :Entity AND  PD.Id_Programa_Cab=:Id_Programa_Cab
					";    
					$Class = 'table table-hover';
					$LinkId = 'Id_Programa_Det';
					$Link = $UrlFile."";
					$Screen = 'animatedModal5';
					$where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
					// var_dump($where);
					$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '',"","", $Screen, 'programa_det', 'checks', '','PS');				
				    $msj = "<div id='Msj-Accion'></div>";
				    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $msj . $Listado,"");
	                DCWrite($Html);
	                DCExit();
	            break;
	            // MUESTRA LOS CURSOS DISPONIBLES PARA AGREGAR
	            case "Create_vinculacion":
			        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
			        $Entity = $_SESSION['Entity'];
					
					$btn = "Atrás]" .$UrlFile."/Interface/List_curso/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]btn btn-primary ladda-button}";
					$btn .= " Añadir ]" .$UrlFile."/Interface/vinculacion_curso/Id_Programa_Cab/".$Id_Programa_Cab."]Msj-Accion]HXMS]]btn btn-primary ladda-button}";
					$btn = DCButton($btn, 'botones1', 'sys_form');				
					$DCPanelTitle = DCPanelTitle("","Selecciona y Agrega a tu lista ",$btn);
					
	 
					$Query = "
					    SELECT 
					EA.Id_Edu_Almacen AS CodigoLink
					, AR.Nombre  
					FROM edu_articulo AR
					INNER JOIN edu_almacen EA ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
					WHERE AR.Entity = :Entity AND AR.Estado=:Estado
					ORDER BY AR.Date_Time_Creation DESC

					";    
					$Class = 'table table-hover';
					$LinkId = 'Id_Edu_Almacen';
					$Link = $UrlFile."/Interface/Create";
					$Screen = 'animatedModal5';
					$where = ["Entity"=>$Entity,"Estado"=>"Activo"];
					$Form1 = DCDataGrid('', $Query, $where ,$Conection, $Class, '',"","", $Screen, 'programa_det', 'checks', '','PS');				

				    $msj = "<div id='Msj-Accion'></div>";
					$msj .= "<style>
					.bg-primary{    background-color: #000!important;
					border-color: #000!important;
					color: #fff!important;	
					} </style>";
				    $Html = DCModalForm("Agregar Cursos",$DCPanelTitle . $msj .$Form1,"");
	                DCWrite($Html . $Js);
	                DCExit();
	            break;
	            //Form matricula
	            case "Create_vinculacion_Alumno":
			
					$Id_Edu_Almacen = $Parm["key"];	
					$Id_Programa_Cab = $Parm["Id_Programa_Cab"];	

					
					$btn .= "Atrás]" .$UrlFile."/Interface/List_alumno/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]btn btn-primary ladda-button}";
					$btn = DCButton($btn, 'botones1', 'sys_form');				
					$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
					
					$DirecctionA = $UrlFile."/Process/ENTRY/Obj/User_Register_Crud/Id_Programa_Cab/".$Id_Programa_Cab;
					$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Programa_Cab/".$Id_Programa_Cab;
					
					if(!empty($Id_Programa_Cab)){
					    $Name_Interface = "Editar Participante hola";				    	
					    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
	                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","User_Register_Crud","btn btn-default m-w-120");					
					}else{
					    $Name_Interface = "Crear Participante";					
					    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
	                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
					}
					
					
					
					$Combobox = array(
						 array("Id_User_Sexo","SELECT Id_User_Sexo AS Id, Nombre AS Name FROM user_genero",[]),
					     array("Id_Publicacion_Blog_Categoria"," SELECT Id_Publicacion_Blog_Categoria AS Id, Nombre AS Name FROM publicacion_blog_categoria ",[]),
		                 array("Id_Perfil"," SELECT Id_Perfil_Educacion AS Id, Nombre AS Name FROM perfil_educacion ",[])
						 
					);
					
					$PathImage = array(
					     array("Imagen","/sadministrator/simages/avatars")
					);
					
					$Buttons = array(
					     array($Name_Button,$DirecctionA,"animatedModal5","Form","User_Register_Crud"),$ButtonAdicional
					);	
			        $Form1 = BFormVertical("User_Register_Crud",$Class,$Id_Edu_Tipo_Privacidad,$PathImage,$Combobox,$Buttons,"Id_Edu_Tipo_Privacidad");
					
				    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
	                DCWrite($Html);
	                DCExit();
                break;

	            //CONFIRMACIONES DE EJECUCION DE PROCESOS
	            case "vinculacion_curso":

			        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];					
					$btn = "Confirmar ]" .$UrlFile."/Process/ENTRY/Obj/vinculacion_curso_create/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]FORM]programa_det]btn btn-default dropdown-toggle]}";				
					$btn .= "Cancelar ]" .$UrlFile ."/Interface/List_curso/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-info}";				
					$Button = DCButton($btn, 'botones1', 'sys_form_confirma');					
					
				    $Html = DCModalFormMsjInterno("Confirma que deseas agregar","programa_det",$Button,"bg-info");
	                DCWrite($Html);
					DCExit();
	            break;
	            case "Eliminar_vinculacion":
				
			        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];

			        $Query="
				        SELECT 	PC.Estado	
						FROM programa_cab PC
						WHERE PC.Entity = :Entity AND PC.Id_Programa_Cab = :Id_Programa_Cab";
					$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Estado = $Row->Estado;
					if($Estado=="Proceso Completado"){
								
						$btn .= "Volver ]" .$UrlFile ."/Interface/List_curso/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-info}";				
						$Button = DCButton($btn, 'botones1', 'Eliminar_vinculacion');					
						
					    $Html = DCModalFormMsjInterno("No puede eliminar, el proceso esta completo",$Form,$Button,"bg-info");

					}else{
						$btn = "Confirmar ]" .$UrlFile."/Process/DELETE/Obj/vinculacion_curso_eliminar/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]FORM]programa_det]btn btn-default dropdown-toggle]}";				
						$btn .= "Cancelar ]" .$UrlFile ."/Interface/List_curso/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-info}";				
						$Button = DCButton($btn, 'botones1', 'Eliminar_vinculacion');					
						
					    $Html = DCModalFormMsjInterno("Confirma que deseas eliminar",$Form,$Button,"bg-info");

					}
			        				
					
	                DCWrite($Html);
					DCExit();
	            break;
	            case "Eliminar_vinculacion_alumnos":
				
			        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];

			       /* $Query="
				        SELECT 	PC.Estado	
						FROM programa_cab PC
						WHERE PC.Entity = :Entity AND PC.Id_Programa_Cab = :Id_Programa_Cab";
					$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Estado = $Row->Estado;
					if($Estado=="Proceso Completado"){
								
						$btn .= "Volver ]" .$UrlFile ."/Interface/List_curso/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-info}";				
						$Button = DCButton($btn, 'botones1', 'Eliminar_vinculacion');					
						
					    $Html = DCModalFormMsjInterno("No puede eliminar, el proceso esta completo",$Form,$Button,"bg-info");

					}else{*/
						$btn = "Confirmar ]" .$UrlFile."/Process/DELETE/Obj/vinculacion_alumno_eliminar/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]FORM]programa_det]btn btn-default dropdown-toggle]}";				
						$btn .= "Cancelar ]" .$UrlFile ."/Interface/List_alumno/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-info}";				
						$Button = DCButton($btn, 'botones1', 'Eliminar_vinculacion');					
						
					    $Html = DCModalFormMsjInterno("Confirma que deseas eliminar",$Form,$Button,"bg-info");

					//}
			        				
					
	                DCWrite($Html);
					DCExit();
	            break;	
			
				
			
        }
				
		
		
	}
	
    public function OrdenarComponente($Settings){
       	global $Conection, $DCTimeHour,$NameTable;
		
			$Codigo_Item = $Settings["Id_Edu_Rrss_Det"];	
			$Id_Edu_Capacitacion = $Settings["Id_Edu_Capacitacion"];
			
			$OrdenP = DCPost("Orden");


			$Query = " 
			SELECT Id_Edu_Rrss_Det, Orden, Nombre FROM edu_rrss_det  
			WHERE 
			Id_Edu_Capacitacion = :Id_Edu_Capacitacion 
			ORDER BY Orden ASC 	
			"; 
			$Where = ["Id_Edu_Capacitacion" =>$Id_Edu_Capacitacion];

			$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			$cont = 0;
			$SesionN = 0;
			$ubicacionB = 0;
			$OrdenBD = "";
			foreach ($Registro as $Reg) {		
				$CodigoItemBD = $Reg->Id_Edu_Rrss_Det;
				$Nombre = $Reg->Nombre;
				
			
				if ($CodigoItemBD == $Codigo_Item) {
					
					$OrdenBD = $Reg->Orden;
					///   1       2
					if ($OrdenP < $OrdenBD) {
						
						$SesionN = $OrdenP;
					} else {
						$SesionN = $OrdenP + 1;
					}
					
					
					$ubicacionB = ($SesionN * 100);
					// echo $ubicacionB." :: ".$Nombre." <br>";
					$reg = array(
						'Orden' => $ubicacionB
					);
					$where = array('Id_Edu_Rrss_Det' => $CodigoItemBD);
					$rg = ClassPDO::DCUpdate('edu_rrss_det', $reg , $where, $Conection,"");
					
									
				} else {
					
					$OrdenBD = $Reg->Orden;
					$ubicacionB = ($OrdenBD * 100 + 10);
					// echo $ubicacionB." :: ".$Nombre." <br>";
					$reg = array(
						'Orden' => $ubicacionB
					);
					$where = array('Id_Edu_Rrss_Det' => $CodigoItemBD);
					$rg = ClassPDO::DCUpdate('edu_rrss_det', $reg , $where, $Conection,"");
				}	
				
			}	


			$Query = " 
			SELECT Id_Edu_Rrss_Det, Orden FROM edu_rrss_det  
			WHERE 
			Id_Edu_Capacitacion = :Id_Edu_Capacitacion 
			ORDER BY Orden ASC 	
			"; 
			$Where = ["Id_Edu_Capacitacion" =>$Id_Edu_Capacitacion];

			$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			$Cont = 0;
			foreach ($Registro as $Reg) {
				
				$Cont += 1;
				$reg = array(
					'Orden' => $Cont
				);
				
				$where = array('Id_Edu_Rrss_Det' => $Reg->Id_Edu_Rrss_Det);
				$rg = ClassPDO::DCUpdate('edu_rrss_det', $reg , $where, $Conection,"");
				
			}			
	}	

	public function Proceso_inscripcion($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
       	$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		$Name_surnames = DCPost("Nombre");	
		$Correo = DCPost("Email");
		$PasswordO= DCPost("Password");	
		$Password=trim($PasswordO);
		$Telefono = DCPost("Telefono");	
		$Sexo=DCPost("Id_User_Sexo");
		$ruta="";
		if ($Sexo==1) {
				$ruta="4ad89d8dh345s_hombre.png";
		}else if ($Sexo==2) {
				$ruta="4ad89d345s_mujer.png";
		}	
		///Comprueba si existe en la entidad if(Si esta vacion)

		$Query="SELECT  US.Email,UM.Id_User_Miembro,US.Id_User
					FROM user_miembro  UM
	                INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
					WHERE  UM.Entity=:Entity AND UM.Email=:correo";
		$Where = ["Entity"=>$Entity,"correo"=>$Correo];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Email_Bd = $Row->Email;
		$Id_User_MiembroP = $Row->Id_User_Miembro;
		$Id_UserP = $Row->Id_User;
		$Registro_Nuevo=0;
			if(empty($Email_Bd)){
							$data = array(
								'Nombre' => $Name_surnames,
								'Email' => $Correo,
								'Usuario_Login' => $Correo,
								'Password' => $Password,
								'Id_User_Sexo'=> $Sexo,
								'Foto'=> $ruta,
								'Telefono'=> $Telefono,
								'Estado' => "Comprobando",
								'Id_Perfil' => 3,
								'Id_Perfil_Educacion' => DCPost("Id_Perfil_Educacion"),
								'Entity' => $Entity,
								'Id_Entity' => $Entity,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user", $data, $Conection);
						    $Id_User = $Result["lastInsertId"];	
								
							$data = array(
								'Nombre' => $Name_surnames,
								'Email' => $Correo,
								'Celular' => $Telefono,
								'Telefono'=>$Telefono,
								'Foto'=> $ruta,
								'Id_Perfil' => 3,
								'Id_Perfil_Educacion' => DCPost("Id_Perfil_Educacion"),
								'Entity' => $Entity,
								'Id_User_Creation' => $Id_User,
								'Id_User_Update' => $Id_User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user_miembro", $data, $Conection);	
						    $Registro_Nuevo=1;
			}

		//Consulta  para traer el edu almacen de campaña
		$Id_Programa_Cab = $Settings["Id_Programa_Cab"];
		$Query="SELECT PD.Id_Programa_Cab
				,PD.Id_Edu_Almacen
				FROM programa_det PD
				INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
	            inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
				WHERE
						PD.Entity = :Entity AND  PD.Id_Programa_Cab=:Id_Programa_Cab";
		$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
		$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
		$Conteoregister=0;
		$Conteoactualizado=0;
		$error=0;
		//obtener el id de usuario sin verificar el edu almacen
			$Query="SELECT UM.Id_User_Miembro
					    FROM user_miembro UM 
						WHERE  UM.Entity=:Entity AND UM.Email=:Email";
						$Where = ["Entity"=>$Entity,"Email"=>$Correo];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
			$Id_User_MiembroNuevo = $Row->Id_User_Miembro;

		foreach($Registro as $Reg) {
			$Edu_Almacen=$Reg->Id_Edu_Almacen;

			//Comprueba si existe en la entidad if(Si esta vacion)
			$Query="SELECT  UM.Email,UM.Id_User_Miembro,UM.Id_User_Creation as Id_User_User
					     FROM suscripcion SUS
					     INNER JOIN user_miembro UM on SUS.Id_User=UM.Id_User_Miembro
	                	 INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
						 WHERE SUS.Id_Edu_Almacen=:Edu_Almacen AND SUS.Entity=:Entity AND UM.Email=:Email";
				$Where = ["Entity"=>$Entity,"Edu_Almacen"=>$Edu_Almacen,"Email"=>$Correo];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Email_Bd = $Row->Email;
				$Id_User_MiembroP = $Row->Id_User_Miembro;
				$Id_UserP = $Row->Id_User_User;
					if(empty($Email_Bd)){
						//Vinculo de matricula
							$data = array(
								'Id_Perfil_Educacion' => DCPost("Id_Perfil_Educacion"),
								'Id_Edu_Almacen' =>  $Edu_Almacen,
								'Id_Programa_Cab'=>$Id_Programa_Cab,
								'Estado' => "Matriculado",
								'Id_User' => $Id_User_MiembroNuevo,
								'Entity' => $Entity,
								'Visibilidad'=>"Activo",
								'Id_User_Update' => $User,
								'Id_User_Creation' => $User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
							);
						$Return = ClassPDO::DCInsert("suscripcion", $data, $Conection);
						$Conteoregister +=1;


					}else if(!empty($Email_Bd)) {
						
					
								//Activando cursos anteriores
								$data = array(
									'Visibilidad'=>"Activo","Id_Programa_Cab"=>$Id_Programa_Cab
								);
								$Wheree = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_User"=>$Id_User_MiembroP);
								$Return = ClassPDO::DCUpdate("suscripcion",$data,$Wheree,$Conection,"");
								//Actualizacion de clave 
								$reg = array('Password' => $Password);
								$where = array('Id_User' => $Id_UserP,'Entity'=>$Entity);
								$rg = ClassPDO::DCUpdate('user', $reg , $where, $Conection,"");	
								$Conteoregister +=1;
								$Conteoactualizado +=1;
							
					}else{
						$error=1;
					}
			
			
		}

		if ($error==0) {
				if($Registro_Nuevo==1){
					$MensajeA="Se ha registrado a un nuevo usuario";
				}else{
					if ($Conteoactualizado==0) {
						$MensajeA="Matricula exitosa en ".$Conteoregister." cursos";
						$MensajeB="Se actualizo el estado de ".$Conteoactualizado." cursos";
					}
					$MensajeA="Matricula exitosa en ".$Conteoregister." cursos";
					$MensajeB="Se actualizo el estado de ".$Conteoactualizado."cursos";
				}
		}else{
			$MensajeA="No se realizo la vinculacion";
			$MensajeB="";
		}

		$MensajeMatricula=$MensajeA."<br>";
		$MensajeMatricula.=$MensajeB;
		DCWrite(Message($MensajeMatricula,"C"));  
	}

	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
		$Id_Programa_Cab = $Settings["Id_Programa_Cab"];
		$Entity = $_SESSION['Entity'];
		$Id_Warehouse = DCPost("ky");
		$Conteoeliminar=0;
						for ($j = 0; $j < count($Id_Warehouse); $j++) {
								// Verifica la entidad
								$Query="SELECT 
										PD.Id_Edu_Almacen
										FROM programa_det PD
										WHERE
										PD.Entity = :Entity AND  PD.Id_Programa_Cab=:Id_Programa_Cab";
								$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
								$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
								foreach($Registro as $Reg) {
									$Edu_Almacen=$Reg->Id_Edu_Almacen;
									$Conteoeliminar += 1;
									//Desabilitando
									$data = array(
										'Visibilidad'=>"Inactivo"
									);
									$Wheree = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_User"=>$Id_Warehouse[$j]);
									$Return = ClassPDO::DCUpdate("suscripcion",$data,$Wheree,$Conection,"");
								}
						}	

		

		DCWrite(Message($Conteoeliminar. " cursos ","C"));
						
	}
}