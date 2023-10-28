<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
// echo 'samesite_test:' . $_COOKIE['samesite_test'];
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class CursoPrograma{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/curso-programa";
		$UrlFile_Edu_Participante = "/sadministrator/edu-participante";
		$Url_Curso_Edit="/sadministrator/edu-articulo-det";
		
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
		                    $Nombre_Programa=DCPost("Nombre");		
		                    $Url_Amigable=DCUrl_Amigable($Nombre_Programa);
											
							
							$Id_Programa = DCSave($Obj,$Conection,$Parm["Id_Programa_Cab"],"Id_Programa_Cab",$Data);
							$reg = array(
							'Tipo_Proceso' =>"Programa",'Estado'=>"Creado","Url"=>$Url_Amigable
							);
							$where = array('Nombre' =>$Nombre_Programa);
							 ClassPDO::DCUpdate("programa_cab", $reg , $where, $Conection);

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
						$columnas=0;$columerror=0;
						for ($j = 0; $j < count($Id_Warehouse); $j++) {
						
							// DCWrite("Warehouse:: ".$Id_Warehouse[$j]."<br>");

							$Id_Edu_Almacen=$Id_Warehouse[$j];
							$Query="SELECT 
										PD.Id_Edu_Almacen
										FROM programa_det PD
										WHERE
										PD.Entity = :Entity AND  PD.Id_Edu_Almacen=:Id_Edu_Almacen and PD.Id_Programa_Cab=:Id_Programa_Cab";
								$Where = ["Entity"=>$Entity,"Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_Programa_Cab"=>$Id_Programa_Cab];
							$Registro = ClassPdo::DCRow($Query,$Where,$Conection);
							$Id_Edu_Almacen=$Registro->Id_Edu_Almacen;
							if(empty($Id_Edu_Almacen)){
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
								$columnas+=1;
								
							}else{
								
								$columerror+=1;
							}
						}
						//Cambio de estado
						$data = array('Estado' =>  "Cursos Vinculados");
						$where = array('Id_Programa_Cab' =>$Parm["Id_Programa_Cab"]);
						ClassPDO::DCUpdate("programa_cab", $data , $where, $Conection);	


						if ($columerror==0) {
							$Mensaje="Se ha vinculado ".$columnas." cursos correctamente al programa";
						}else{
							$Mensaje="Existen ".$columerror." cursos vinculados al programa <br><br>";
							$Mensaje.=$columnas." cursos vinculados correctamente";
						}

						DCWrite(Message($Mensaje,"C"));									
						$Settings = array();
						$Settings['Url'] =$UrlFile."/Interface/List_programa_det/Id_Programa_Cab/".$Id_Programa_Cab;
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);
						//DCCloseModal();	
						
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

						$this->Proceso_inscripcion($Parm);
						//Cambio de estado
							$data = array(
										'Estado' =>  "Alumnos vinculados",
							);
						$where = array('Id_Programa_Cab' =>$Parm["Id_Programa_Cab"]);
						ClassPDO::DCUpdate("programa_cab", $data , $where, $Conection);	
						$Settings = array();
						$Settings['Url'] =$UrlFile."/Interface/List_programa_det/Id_Programa_Cab/".$Parm["Id_Programa_Cab"];
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);
						DCExit();
						// }
					break;
					//Logica Matricula Masiva
					case "Crud_Edu_Inscripcion_Masivo":
							$Id_Programa_Cab = $Parm["Id_Programa_Cab"];
							// DCCloseModal();	
						    $Data = array();
							$Data['Id_Edu_Almacen'] = $Id_Programa_Cab; 
							$Data['Producto_Origen'] = "Programa"; //Id_Programa_Cab					
							$Id_Edu_Inscripcion_Masivo=DCSave($Obj,$Conection,$Parm["Id_Edu_Inscripcion_Masivo"],"Id_Edu_Inscripcion_Masivo",$Data);
							$Id_Retorno=$Id_Edu_Inscripcion_Masivo["lastInsertId"];

							$reg = array(
							'Id_Edu_Almacen' => $Id_Programa_Cab,'Producto_Origen'=>"Programa"
							);
							$where = array('Id_Edu_Inscripcion_Masivo' =>$Id_Retorno);
							$rg = ClassPDO::DCUpdate("edu_inscripcion_masivo", $reg , $where, $Conection);
							
							$Settings = array();
							$Settings['Url'] =$UrlFile."/Interface/proc_matricula_masiva/Id_Edu_Inscripcion_Masivo/".$Id_Retorno."/Id_Programa_Cab/".$Id_Programa_Cab;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "HXMS";
							DCRedirectJS($Settings);
							DCExit();	
					break;
					//Logica Matricula Masiva B
					case "Matricula_Masiva":
							$this->Proceso_inscripciones_masivas($Parm);

							$Settings = array();
							$Settings['Url'] =$UrlFile."/Interface/List_programa_det/Id_Programa_Cab/".$Id_Programa_Cab;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "HXMS";
							DCRedirectJS($Settings);
							DCExit();
					break;	

						}		
				
                break;
            case "UPDATE":

            break;
            case "DELETE":
			
				switch ($Obj) {

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
					case "Eliminar_alumno_por_curso":
						
					        $Id_Programa_Det = $Parm["Id_Programa_Det"];
					        $Query = "
										SELECT PD.Id_Edu_Almacen
										FROM programa_det PD
										WHERE 
										PD.Id_Programa_Det = :Id_Programa_Det AND PD.Entity=:Entity
										";	
							$Wherek = ["Id_Programa_Det" =>$Id_Programa_Det,'Entity'=>$Entity];
							$Rowk = ClassPDO::DCRow($Query,$Wherek,$Conection);	
							$Edu_Almacen = $Rowk->Id_Edu_Almacen;
							$Id_Warehouse = DCPost("ky");
							$estado="";
							$columnas='';

							for ($j = 0; $j < count($Id_Warehouse); $j++) {
								$Query = "SELECT SUS.Visibilidad
										FROM suscripcion SUS
										WHERE 
										SUS.Id_User = :Id_User AND SUS.Entity=:Entity AND SUS.Id_Edu_Almacen=:Id_Edu_Almacen
										";	
							$Where= ["Id_User" =>$Id_Warehouse[$j],'Entity'=>$Entity,'Id_Edu_Almacen'=>$Edu_Almacen];
							$Row = ClassPDO::DCRow($Query,$Where,$Conection);	
							$Visibilidad = $Row->Visibilidad;
								if ($Visibilidad=="Activo") {$Estado_CC="Inactivo";}
								else{$Estado_CC="Activo";}

							$data = array('Visibilidad'=>$Estado_CC);
							$Wheree = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_User"=>$Id_Warehouse[$j]);
							$Return = ClassPDO::DCUpdate("suscripcion",$data,$Wheree,$Conection,"");		
							}
							
							DCWrite(Message("Se realizo los cambios correctamente","C"));	

							$Settings = array();
							$Settings['Url'] =$UrlFile."/Interface/List_programa_det_curso/Id_Programa_Det/".$Id_Programa_Det;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "HXMS";
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
			
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear programa]".$UrlFile."/Interface/Create_Componente]ScreenRight]HXM_SP]]btn btn-primary}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form_Sers');

				$DCPanelTitle = DCPanelTitle("Gestión de Programas","Administración",$btn);

				
				$Query = "
				
				    SELECT 
					PC.Id_Programa_Cab AS CodigoLink
					, PC.Nombre	
					, PC.Fecha_Creada
					, PC.Estado	
					FROM programa_cab PC
					WHERE
					PC.Entity = :Entity AND PC.Tipo_Proceso=:Tipo_Proceso
					ORDER BY PC.Date_Time_Creation ASC
			
				";    


				$Class = 'table table-hover';
				$LinkId = 'Id_Programa_Cab';
				$Link =$UrlFile ."/Interface/List_programa_det";
				$Screen = 'ScreenRight';
				$where = [ "Entity" => $Entity,"Tipo_Proceso"=>"Programa"];
				$Table = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Table .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
			break;
					
		//Creacion de programa	
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
	         					
				
				$btn = " Volver ]" .$UrlFile."]ScreenRight]HXM_SP]]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'programa_cab');
				$DCPanelTitle = DCPanelTitle($Name_Interface,"PRODUCCIÓN | FASE DE ESTUDIO ",$btn);
					
	
				
		        $Contenido = $DCPanelTitle.$Form1.$Js;				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
			break;
			case "Create_Componente_edit":
			
					
	            $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
				
				
				//$DirecctionA = $UrlFile."/Process/ENTRY/Obj/programa_cab_crud/Id_Programa_Cab/".$Id_Programa_Cab;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Programa_Cab/".$Id_Programa_Cab;
				
				if(!empty($Id_Programa_Cab)){
					
				    $Name_Interface = "Editar";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
				    $DirecctionA = $UrlFile."/Process/ENTRY/Obj/programa_cab_crud/Id_Programa_Cab/".$Id_Programa_Cab;
					
                   // $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form_P","programa_cab_crud","btn btn-default m-w-120");				
				
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
	         					
				
				$btn = " Volver ]" .$UrlFile."]ScreenRight]HXM_SP]]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
	
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();	
			break;
	    //1er Page
	        	case "List_programa_det":
				
			        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];

			       	$btn .= "<i class='zmdi zmdi-edit'></i> Editar Programa]".$UrlFile."/Interface/Create_Componente_edit/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXM]]btn btn-default}";


					$listMn = "<i class='zmdi zmdi-edit'></i> Agregar Cursos [".$UrlFile."/Interface/Create_vinculacion_curso/Id_Programa_Cab/".$Id_Programa_Cab."[animatedModal5[HXM[{";
					$listMn .= "<i class='icon-chevron-right'></i> Eliminar Curso[".$UrlFile."/Interface/Eliminar_vinculacion/Id_Programa_Cab/".$Id_Programa_Cab."[animatedModal5[HXM[{";
					$btn .= "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i>Opcion Curso]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";


					$Query="SELECT PD.Id_Programa_Det
										FROM programa_det PD
										WHERE
										PD.Entity = :Entity and PD.Id_Programa_Cab=:Id_Programa_Cab";
					$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
					$Registro = ClassPdo::DCRow($Query,$Where,$Conection);
					$Id_Programa_Det=$Registro->Id_Programa_Det;
					if (!empty($Id_Programa_Det)) {
								$listMn = "<i class='icon-chevron-right'></i> Lista Participantes [".$UrlFile."/Interface/List_programa_alumno/Id_Programa_Cab/".$Id_Programa_Cab."/Tipo_proceso/Global[animatedModal5[HXM[{";
								$listMn .= "<i class='icon-chevron-right'></i> Importar Archivo[".$UrlFile."/Interface/Create_importacion_masiva/Id_Programa_Cab/".$Id_Programa_Cab."[animatedModal5[HXM[{";

								$btn .= "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Participantes ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
					}
					$btn .= " Volver ]" .$UrlFile."]ScreenRight]]]btn btn-default}";
					$btn = DCButton($btn, 'botones1', 'programa_cab');

					$Query="SELECT  PC.Nombre
								FROM programa_cab  PC
								WHERE  PC.Entity=:Entity AND PC.Id_Programa_Cab=:Id_Programa_Cab";
					$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Nombre = $Row->Nombre;		

					$DCPanelTitle = DCPanelTitle("Programa : ".$Nombre."","Estos son los cursos seleccionados ",$btn);
					
				
					
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
					$Link = $UrlFile."/Interface/List_programa_det_curso";
					$Screen = 'ScreenRight';
					$where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
					// var_dump($where);
					$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '',$Link,$LinkId, $Screen, 'programa_det', 'checks', '','PS');				
				    $msj = "<div id='Msj-Accion'></div>";
				    $Contenido = DCPage($DCPanelTitle,$Pestanas ."<br>" . $msj . $Listado,"");


				    if($Parm["request"] == "on"){
						DCWrite($layout->main($Contenido,$datos));
					}else{
						DCWrite($Contenido);			
					}

	              //  DCWrite($Html);
	                DCExit();
				break;
			//1er Page A : Listado participantes 
				case "List_programa_alumno":
					$Id_Programa_Cab = $Parm["Id_Programa_Cab"];
	            	
					$listMn = "<i class='icon-chevron-right'></i> Crear Usuarios [".$UrlFile."/Interface/Create_vinculacion_Alumno/Id_Programa_Cab/".$Id_Programa_Cab."[animatedModal5[HXMS[{";	
	           		$listMn .= "<i class='icon-chevron-right'></i> Eiminar [".$UrlFile."/Interface/Eliminar_vinculacion_alumnos/Id_Programa_Cab/".$Id_Programa_Cab."[Msj-Accion[HXMS[{";
					$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
					
					//$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/Interface/Create_vinculacion_Alumno/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-primary ladda-button}";
					//$btn .= " Borrar ]" .$UrlFile."/Interface/Eliminar_vinculacion/Id_Programa_Cab/".$Id_Programa_Cab."]Msj-Accion]HXMS]]btn}";
					
					$btn = DCButton($btn, 'botones1', 'List_programa_alumno');				
					$DCPanelTitle = DCPanelTitle("","Estos son los alumnos vinculados ",$btn,"");
					
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
				    $Html = DCModalForm("Alumnos en Campaña",$DCPanelTitle . $msj . $Listado,"");
	                DCWrite($Html);
	                DCExit();
	            break;
	        //1er Page A1: Proceso de crear alumno 
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
            //1er Page A2: Proceso de Eliminar alumno
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
            //1er Page B1 : Importacion masiva de alumnos - Fase excel
	            case "Create_importacion_masiva":
	            	$Id_Programa_Cab = $Parm["Id_Programa_Cab"];

				
					$btn .= "Atrás]" .$UrlFile."/interface/List/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]btn btn-primary ladda-button}";
					$btn = DCButton($btn, 'botones1', 'sys_form');				
					$DCPanelTitle = DCPanelTitle("","Opciones de Masivo",$btn);
					
	 
		           
					$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Crud_Edu_Inscripcion_Masivo/Id_Programa_Cab/".$Id_Programa_Cab;
					
					
					 $Name_Interface = "Crear Componente";					
					 $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
	                  					
					
					$Combobox = array(
					     array("Id_Edu_Almacen"," SELECT EA.Id_Edu_Almacen AS Id, EA.Id_Edu_Articulo AS Nombre FROM edu_almacen EA ",[])
					);
					
					$PathImage = array(
					     array("Imagen","/sadministrator/archivos/doc_inscritos")
					);
					
					$Buttons = array(
					     array($Name_Button,$DirecctionA,"animatedModal5","Form","Crud_Edu_Inscripcion_Masivo"),$ButtonAdicional
					);	
			        $Form1 = BFormVertical("Crud_Edu_Inscripcion_Masivo",$Class,$Id_Edu_Inscripcion_Masivo,$PathImage,$Combobox,$Buttons,"Id_Edu_Inscripcion_Masivo");
					
				    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
	                DCWrite($Html);
	                DCExit();
	            break;
	        //1er Page B2 : Confirmacion de proceso de matricula masiva
	            case "proc_matricula_masiva":
		
			        $Id_Edu_Inscripcion_Masivo = $Parm["Id_Edu_Inscripcion_Masivo"];
			        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
					
					$btn = "Matricular ]" .$UrlFile ."/Process/ENTRY/Obj/Matricula_Masiva/Id_Edu_Inscripcion_Masivo/".$Id_Edu_Inscripcion_Masivo."/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXM]]btn btn-default dropdown-toggle]}";				
					$Button = DCButton($btn, 'botones1', 'sys_form');

				    $Html = DCModalFormMsj("Deseas continuar con la matricula",$Form,$Button,"bg-info");
	                DCWrite($Html);
				break;
	        //1er Page C : Prceso create curso
	            case "Create_vinculacion_curso":
			        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
			        $Entity = $_SESSION['Entity'];
					
					$btn = "Atrás]" .$UrlFile."/Interface/List_curso/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]btn btn-primary ladda-button}";
					$btn .= " Añadir ]" .$UrlFile."/Interface/vinculacion_curso/Id_Programa_Cab/".$Id_Programa_Cab."]Msj-Accion]HXMS]]btn btn-primary ladda-button}";
					$btn = DCButton($btn, 'botones1', 'sys_form');				
					$DCPanelTitle = DCPanelTitle("","Selecciona y Agrega a tu lista ",$btn);
					
	 
					$Query = "
					    SELECT 
					EA.Id_Edu_Almacen AS CodigoLink
					, AR.Nombre,EA.Fecha_Hora_Ingreso_Almacen
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

	    //2do Page
	        	case "List_programa_det_curso":
				
			        $Id_Programa_Det = $Parm["Id_Programa_Det"];
			        $Query="SELECT PC.Nombre as Nombre_Programa,
						EAA.Nombre,EA.Id_Edu_Almacen,PD.Id_Programa_Cab
						FROM programa_det PD
						INNER JOIN programa_cab PC on PD.Id_Programa_Cab=PC.Id_Programa_Cab
						INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
	             		inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
						WHERE  PD.Entity=:Entity AND PD.Id_Programa_Det=:Id_Programa_Det";
					$Where = ["Entity"=>$Entity,"Id_Programa_Det"=>$Id_Programa_Det];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Nombre = $Row->Nombre; $Nombre_Programa = $Row->Nombre_Programa;
					$Id_Edu_Almacen=$Row->Id_Edu_Almacen;$Id_Programa_Cab=$Row->Id_Programa_Cab;
					

					$listMn = "<i class='icon-chevron-right'></i> Visibilidad del curso[".$UrlFile."/Interface/Eliminar_alumno_por_curso/Id_Programa_Det/".$Id_Programa_Det."[Msj-Accion[HXMS[{";

					$btn = "<i class='zmdi zmdi-edit'></i> Editar Curso]".$Url_Curso_Edit."/interface/begin/request/on/key/".$Id_Edu_Almacen."/action/sugerencia]animatedModal5]HREF]]btn btn-default}";
					$btn .= "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones de Participantes ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
					$btn .= " Volver ]" .$UrlFile."/Interface/List_programa_det/Id_Programa_Cab/".$Id_Programa_Cab."]ScreenRight]]]btn btn-default}";
					$btn = DCButton($btn, 'botones1', 'programa_cab');				

					$DCPanelTitle = DCPanelTitle($Nombre_Programa." | ".$Nombre."","Estos son los alumnos del curso",$btn);
					
				
					
					$Query = "
						SELECT UM.Id_User_Miembro as CodigoLink,UM.Nombre,UM.Email,S.Visibilidad as Estado_Curso
						from	suscripcion S
	   			    	INNER JOIN user_miembro UM ON S.Id_User=UM.Id_User_Miembro
	            		INNER JOIN programa_det PD ON S.Id_Edu_Almacen=PD.Id_Edu_Almacen
						WHERE
						S.Entity = :Entity AND  PD.Id_Programa_Det=:Id_Programa_Det AND S.Producto_Origen=:Producto_Origen
					";    
					$Class = 'table table-hover';
					$LinkId = 'Id_User_Miembro';
					$Link = $UrlFile."/Interface/Resumen_alumno/Id_Edu_Almacen/".$Id_Edu_Almacen;
					$Screen = 'animatedModal5';
					$where = ["Entity"=>$Entity,"Id_Programa_Det"=>$Id_Programa_Det,"Producto_Origen"=>"Programa"];
					// var_dump($where);
					$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class,"",$Link,$LinkId, $Screen, 'List_programa_det_curso', 'checks', '','HXMS');				
				    $msj = "<div id='Msj-Accion'></div>";
				    $Contenido = DCPage($DCPanelTitle,$Pestanas ."<br>" . $msj . $Listado,"");


				    if($Parm["request"] == "on"){
						DCWrite($layout->main($Contenido,$datos));
					}else{
						DCWrite($Contenido);			
					}

	              //  DCWrite($Html);
	                DCExit();
				break;
				//2do Page: Visibilidad del curso
				case "Eliminar_alumno_por_curso":
				
			        $Id_Programa_Det = $Parm["Id_Programa_Det"];
					$btn = "Confirmar ]" .$UrlFile."/Process/DELETE/Obj/Eliminar_alumno_por_curso/Id_Programa_Det/".$Id_Programa_Det."]animatedModal5]FORM]List_programa_det_curso]btn btn-default dropdown-toggle]}";							
					$Button = DCButton($btn, 'botones14', 'List_programa_det_curso');					
					$Html = DCModalFormMsjInterno("Confirma que deseas deshabilitar",$Form,$Button,"bg-info");
					DCWrite($Html);
					DCExit();
	            break;
	            //2do Page: Resumen de alumno
	            case "Resumen_alumno":
					$Id_User_Miembro = $Parm["Id_User_Miembro"];
					$Id_Edu_Almacen = $Parm["Id_Edu_Almacen"];
					//Informacion del alumno
			       $Query="SELECT  US.Email,US.Password
							FROM user_miembro  UM
			                INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
							WHERE  UM.Entity=:Entity AND UM.Id_User_Miembro=:Id_User_Miembro";
					$Where = ["Entity"=>$Entity,"Id_User_Miembro"=>$Id_User_Miembro];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);
					$Email=$Row->Email;$Password=$Row->Password;


					
					$btn = DCButton($btn, 'botones1', 'resumen_alumno');				
					$DCPanelTitle = DCPanelTitle(""," ",$btn,"");
					
					$Query = " SELECT EOE.Nombre,EOE.Preguntas_Por_Mostrar as Total_Preguntas,EEDC.Pregunta_Actual as Preguntas_Resueltas,EEDC.Nota,EEDC.Estado
							FROM programa_alumno PA
							INNER JOIN  suscripcion SUS ON PA.Id_User= SUS.Id_User
							INNER JOIN edu_evaluacion_desarrollo_cab EEDC ON SUS.Id_Suscripcion=EEDC.Id_Suscripcion
       	      				INNER JOIN edu_objeto_evaluativo EOE ON EEDC.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
							WHERE PA.Entity =:Entity AND  PA.Id_User=:Id_User AND SUS.Id_Edu_Almacen=:Id_Edu_Almacen";

					$Class = 'table table-hover';
					$LinkId = 'Id_User_Miembro';
					$Link = $UrlFile."";
					$Screen = 'animatedModal5';
					$where = ["Entity"=>$Entity,"Id_User"=>$Id_User_Miembro,"Id_Edu_Almacen"=>$Id_Edu_Almacen];
					// var_dump($where);
					$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '',"","", $Screen, 'programa_det_RESUMEN', '', '','PS');				
				    $msj = "<div id='Msj-Informacion'><h4 class='modal-title text-center'>Información del alumno</h4><br>
				    			<div style='display: flex;justify-content: space-around;'>
				    			<span>    Correo: ".$Email."</span><span>Clave: ".$Password."</span></div>
				    		</div><br>";
				    $Html = DCModalForm("Resumen del Alumno",$DCPanelTitle . $msj . $Listado,"");
	                DCWrite($Html);
	                DCExit();
	            break;
			
				
			
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

		//Consulta  para traer el edu almacen de campaña - GLobal
			//obtener el id de usuario sin verificar el edu almacen
			$Query="SELECT UM.Id_User_Miembro
					    FROM user_miembro UM 
						WHERE  UM.Entity=:Entity AND UM.Email=:Email";
						$Where = ["Entity"=>$Entity,"Email"=>$Correo];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
			$Id_User_MiembroNuevo = $Row->Id_User_Miembro;
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
										'Producto_Origen'=>"Programa",
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
											'Visibilidad'=>"Activo","Id_Programa_Cab"=>$Id_Programa_Cab,'Producto_Origen'=>"Programa"
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
				//Insertar en  tabla temporal de 
				$data = array(
							'Conteo' => $Conteoregister,
							'Id_User' => $Id_User_MiembroNuevo,
							'Id_Programa_Cab' => $Id_Programa_Cab,
							'Entity' => $Entity,
							'Id_User_Update' => $User,
							'Id_User_Creation' => $User,
							'Date_Time_Creation' => $DCTimeHour,
							'Date_Time_Update' => $DCTimeHour);
				$Return = ClassPDO::DCInsert("programa_alumno", $data, $Conection);







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

	public function Proceso_inscripciones_masivas($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
       	$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
 
		$Id_Edu_Inscripcion_Masivo = $Settings["Id_Edu_Inscripcion_Masivo"];
		$Id_Programa_Cab = $Settings["Id_Programa_Cab"];	///Primer Nivel	

		$Query = "
				SELECT 
				EIM.Archivo_Inscripcion, EIM.Id_Edu_Almacen 
				FROM edu_inscripcion_masivo EIM
				WHERE 
				EIM.Id_Edu_Inscripcion_Masivo = :Id_Edu_Inscripcion_Masivo AND EIM.Entity=:entity
				AND EIM.Id_Edu_Almacen=:Id_Programa_Cab AND EIM.Producto_Origen=:Producto_Origen
				";	
		$Where = ["Id_Edu_Inscripcion_Masivo" =>$Id_Edu_Inscripcion_Masivo,"entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab,"Producto_Origen"=>"Programa"];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$DocExcelInscritos = $Row->Archivo_Inscripcion;
		//$NombreArchivo=$_SERVER['DOCUMENT_ROOT']."/sadministrator/archivos/doc_inscritos/".$DocExcelInscritos;
		$NombreArchivo=$_SERVER['DOCUMENT_ROOT']."/sadministrator/archivos/doc_inscritos/".$DocExcelInscritos;
		var_dump($NombreArchivo);
		$Excel = ExcelExtract($NombreArchivo);
		$Array_Json = json_decode($Excel);
		//var_dump($Array_Json);
		$count_new_masivo=0;//Conteo de alumno new
		$count_new_masivo_b=0;//Conteo de curso new
		$count_update_masivo=0;//Conteo de alumno update
		$count_update_masivo_b=0;//Conteo de curso update
		$error=0;
		     
		foreach($Array_Json as $key ){
			
			//Valores de excel
			$Nombre_Value = $key->Nombres;
			$Email_C = VerificarEmail($key->Email);
			$Email_Value=$key->Email;
			$Passwordb = $key->Clave;
			$Password=trim($Passwordb);
			$Celular_value = $key->Telefono;
			$Sexo= $key->Sexo;
			//$Sexo=trim($Sexob);

			$ruta="";
			if ($Sexo==1) {
				$ruta="4ad89d8dh345s_hombre.png";
			}else if ($Sexo==2) {
				$ruta="4ad89d345s_mujer.png";
			}	

			if($Email_C == "V" ){
				$Query="SELECT 
								US.Usuario_Login
								,US.Email as Correo_User
								,US.Nombre
								, US.Password
								, ET.Id_Entity
								, US.Id_User
								, UM.Id_Perfil
								, UM.Id_User_Miembro
								FROM user_miembro UM
								INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
								INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
								WHERE 
								US.Email = :Email_excel  AND ET.Id_Entity = :Id_Entity";
				$Where = ["Email_excel"=>$Email_Value,"Id_Entity"=>$Entity];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Email_Bd = $Row->Correo_User;
				$Id_User_Miembro=$Row->Id_User_Miembro;
				$ID_UACTUALIZACION=$Row->Id_User;
				if(empty($Email_Bd)){
							$data = array(
								'Nombre' => $Nombre_Value,
								'Email' => $Email_Value,
								'Usuario_Login' => $Email_Value,
								'Password' => $Password,
								'Telefono'=>$Celular_value,
								'Id_User_Sexo'=>$Sexo,
								'Foto'=>$ruta,
								'Estado' => "Comprobando",
								'Id_Perfil' => 3,
								'Id_Perfil_Educacion' => 3,
								'Entity' => $Entity,
								'Id_Entity' => $Entity,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user", $data, $Conection);
						    $Id_User = $Result["lastInsertId"];	
								
							$data = array(
								'Nombre' => $Nombre_Value,
								'Email' => $Email_Value,
								'Celular' => $Celular_value,
								'Telefono'=>$Celular_value,
								'Foto'=>$ruta,
								'Id_Perfil' => 3,
								'Id_Perfil_Educacion' => 3,
								'Entity' => $Entity,
								'Id_User_Creation' => $Id_User,
								'Id_User_Update' => $Id_User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user_miembro", $data, $Conection);	
						    $Id_User_Miembro_New = $Result["lastInsertId"];
							$count_new_masivo+=1;
				}else{
						$count_update_masivo+=1;
				}
				//Consulta  para traer el edu almacen de campaña 
				//obtener el id de usuario sin verificar el edu almacen
					$Query="SELECT UM.Id_User_Miembro,US.Id_User as USERNEW
							    FROM user_miembro UM 
							    INNER JOIN user US ON UM.Id_User_Creation=US.Id_User
								WHERE  UM.Entity=:Entity AND UM.Email=:Email";
					$Where = ["Entity"=>$Entity,"Email"=>$Email_Value];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Id_User_MiembroNuevo = $Row->Id_User_Miembro;
					$Id_User_NUEVO = $Row->USERNEW;
					$Query="SELECT PD.Id_Programa_Cab
								,PD.Id_Edu_Almacen
								FROM programa_det PD
								INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
					            inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
								WHERE
										PD.Entity = :Entity AND  PD.Id_Programa_Cab=:Id_Programa_Cab";
						$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
						$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
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
												'Producto_Origen'=>"Programa",
												'Id_Perfil_Educacion' =>3,
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
										//Actualizacion de clave 
										$reg = array('Password' => $Password);
										$where = array('Id_User' => $Id_User_NUEVO,'Entity'=>$Entity);
										$rg = ClassPDO::DCUpdate('user', $reg , $where, $Conection,"");	
										$count_new_masivo_b +=1;

									}else if(!empty($Email_Bd)) {
											//Activando cursos anteriores
												$data = array(
													'Visibilidad'=>"Activo","Id_Programa_Cab"=>$Id_Programa_Cab,'Producto_Origen'=>"Programa"
												);
												$Wheree = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_User"=>$Id_User_MiembroP);
												$Return = ClassPDO::DCUpdate("suscripcion",$data,$Wheree,$Conection,"");
												//Actualizacion de clave 
												$reg = array('Password' => $Password);
												$where = array('Id_User' => $Id_UserP,'Entity'=>$Entity);
												$rg = ClassPDO::DCUpdate('user', $reg , $where, $Conection,"");	
												$count_update_masivo_b+=1;
												
											
									}else{
										$error+=1;
									}
						}
						//Insertar en  tabla temporal de 
						$data = array(
									'Conteo' => $Conteoregister,
									'Id_User' => $Id_User_MiembroNuevo,
									'Id_Programa_Cab' => $Id_Programa_Cab,
									'Entity' => $Entity,
									'Id_User_Update' => $User,
									'Id_User_Creation' => $User,
									'Date_Time_Creation' => $DCTimeHour,
									'Date_Time_Update' => $DCTimeHour);
						$Return = ClassPDO::DCInsert("programa_alumno", $data, $Conection);
			}		
		}
		if($count_update_masivo==0){
			$Mensaje="Se han inscrito ".$count_new_masivo."nuevos participantes<br> a ".$count_new_masivo_b." cursos";
		}else{
			if($count_update_masivo_b==0){$conteocursos=$count_new_masivo_b;}
			else{$conteocursos=$count_new_masivo_b+$count_update_masivo_b;}

			$Mensaje="Se han actualizado la informacion de".$count_update_masivo." participantes y se actualizaron ".$conteocursos."cursos";
			DCWrite(Message("Se han registrado a ".$count_new_masivo." nuevos participantes en ".$conteocursos." cursos","C"));  
		}
		//Mensaje error
		if($error==0){
			DCWrite(Message($Mensaje,"C"));  
		}else{
			DCWrite(Message($Mensaje,"C"));  
			DCWrite(Message("No se han inscritos ".$error." participantes","E"));  
		}

		
		
		
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