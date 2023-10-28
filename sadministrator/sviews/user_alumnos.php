<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class UserALumnos{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		$UrlFile = "/sadministrator/user_alumnos";
		$UrlFile_Edu_Tipo_Componente = "/sadministrator/edu_tipo_componente";
		$UrlFile_Edu_Tipo_Estructura = "/sadministrator/edu_tipo_estructura";
		$UrlFile_Edu_Area_Conocimiento = "/sadministrator/edu_area_conocimiento";
		$UrlFile_Edu_Sub_Linea = "/sadministrator/edu_sub_linea";
		$UrlFile_Edu_Productor = "/sadministrator/edu_productor";
		$UrlFile_Edu_Perfil_Educacion = "/sadministrator/edu-perfil-educacion";		
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];
        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
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
					case "Alumno_Suscripcion_Crud":
						
						$this->ObjectDelete($Parm);
									
						$Settings["interface"] = "";
						$Settings["REDIRECT"] = $Redirect;
						// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
						new UserALumnos($Settings);
						DCExit();
					
						
					break;
					case "visualizar_alumno":
						
							$Id_Warehouse = DCPost("ky");
							$estado="";
							$columnas='';

							for ($j = 0; $j < count($Id_Warehouse); $j++) {
								$Query = "SELECT SUS.Visibilidad
										FROM suscripcion SUS
										WHERE 
										SUS.Entity=:Entity AND SUS.Id_Suscripcion=:Id_Suscripcion
											";	
								$Where= ["Id_Suscripcion" =>$Id_Warehouse[$j],'Entity'=>$Entity];
								$Row = ClassPDO::DCRow($Query,$Where,$Conection);	
								$Visibilidad = $Row->Visibilidad;
									if ($Visibilidad=="Activo") {$Estado_CC="Inactivo";}
									else{$Estado_CC="Activo";}

								$data = array('Visibilidad'=>$Estado_CC);
								$Wheree = array("Entity"=>$Entity,"Id_Suscripcion"=>$Id_Warehouse[$j]);
								$Return = ClassPDO::DCUpdate("suscripcion",$data,$Wheree,$Conection,"");		
							}
							
							DCWrite(Message("Se realizo los cambios correctamente<br>El curso está ".$Estado_CC,"C"));	

							$Settings = array();
							$Settings['Url'] =$UrlFile;
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
				
				$Redirect = "/REDIRECT/articulo";

				$DCPanelTitle = DCPanelTitle("Alumnos","Administración de Usuarios",$btn);

                if($User == 7370 ){

					$Query = "
					SELECT 
						UM.Id_User_Miembro AS CodigoLink
						, UM.Nombre
						, UM.Email
						, PE.Nombre AS Perfil
						FROM user_miembro UM
						INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
						INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
						INNER JOIN perfil PE ON UM.Id_Perfil = PE.Id_Perfil
					WHERE 
					UM.Entity = :Entity 
					AND US.Id_User_Creation = :Id_User_Creation

					
					"; 		

					$Class = 'table table-hover';
					$LinkId = 'Id_User';
					$Link = $UrlFile."/Interface/List";
					$Screen = 'animatedModal5';

					$Where = ["Entity"=>$Entity,"Id_User_Creation"=>$User];	
					$Content = DCDataGrid('', $Query, $Where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','');
					$Plugin = DCTablePluginA();				
					$Contenido = DCPage($DCPanelTitle , $Content .  $Plugin ,"panel panel-default");
					
				}else{

					$Query = "
					SELECT 
						UM.Id_User_Miembro AS CodigoLink
						, UM.Nombre
						, US.Email
						, PE.Nombre AS Perfil
						FROM user_miembro UM
						INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
						INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
						INNER JOIN perfil PE ON UM.Id_Perfil = PE.Id_Perfil
					WHERE 
					UM.Entity = :Entity 
					
					"; 		

					$Class = 'table table-hover';
					$LinkId = 'Id_User';
					$Link = $UrlFile."/Interface/List";
					$Screen = 'animatedModal5';

					$Where = ["Entity"=>$Entity];	
					$Content = DCDataGrid('', $Query, $Where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','');
					$Plugin = DCTablePluginA();				
					$Contenido = DCPage($DCPanelTitle , $Content .  $Plugin ,"panel panel-default");
					
				}	
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
			break;
        
            case "List":
			
		        $Id_User = $Parm["Id_User"];
				
				$Name_Interface = "Detalle de alumno";	
				
				//	$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/interface/Create/Id_User/".$Id_User."]animatedModal5]HXMS]]btn btn-primary ladda-button}";
				$btn .= "Visualizacion]" .$UrlFile."/Interface/visualizar_alumno/Id_User/".$Id_User."]Msj-Accion]HXMS]]btn btn-default}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Resumen de datos del alumno en los cursos matriculados",$btn);
				
			
				
				$Query = "

					SELECT SUS.Id_Suscripcion AS CodigoLink, EAA.Nombre,SUS.Date_Time_Creation as Fecha_Matriculada, SUS.Visibilidad 
				     FROM suscripcion SUS 
				     INNER JOIN edu_almacen EA ON SUS.Id_Edu_Almacen = EA.Id_Edu_Almacen
             		inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
					 Where SUS.Id_User= :Id_User AND SUS.Entity=:Entity 
				";    
				// SELECT Id_Edu_Sub_Sector AS CodigoLink, Nombre AS Name FROM edu_sub_sector
				$Class = 'table table-hover';
				$LinkId = 'Id_Suscripcion';
				$Link = $UrlFile."/Process/ENTRY/Obj/Alumno_Suscripcion_Crud";
				$Screen = 'animatedModal5';
				$where = ["Id_User"=>$Id_User,"Entity"=>$Entity];
				// var_dump($where);
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '',"","", $Screen, 'Alumno_Suscripcion_Crud', 'checks', '','');

				$Query2 = "SELECT  EEDC.Id_Edu_Evaluacion_Desarrollo_Cab as Id,EAA.Nombre,EOE.Nombre as NombreExamen,EOE.Preguntas_Por_Mostrar as Total_Preguntas,EEDC.Pregunta_Actual as Preguntas_Resueltas,EEDC.Nota,EEDC.Estado,EEDC.Date_Time_Creation 
				    FROM suscripcion SUS 
				    INNER JOIN edu_almacen EA ON SUS.Id_Edu_Almacen = EA.Id_Edu_Almacen
             		inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
             		INNER JOIN edu_evaluacion_desarrollo_cab EEDC ON SUS.Id_Suscripcion=EEDC.Id_Suscripcion
       	      		INNER JOIN edu_objeto_evaluativo EOE ON EEDC.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
					 Where SUS.Id_User= :Id_User AND SUS.Entity=:Entity AND EOE.Tipo_Examen=4
				";
				$Where2 = ["Id_User"=>$Id_User,"Entity"=>$Entity];
				$info="";

				$Row = ClassPdo::DCRows($Query2,$Where2,$Conection);
				foreach ($Row as $field) {
						$Cod =$field->Id;$Nombre =$field->Nombre;  $NombreExamen=$field->NombreExamen;
						$Total_Preguntas = $field->Total_Preguntas;   $Preguntas_Resueltas = $field->Preguntas_Resueltas;
						$Nota = $field->Nota;$Estado = $field->Estado;$Date_Time_Creation = $field->Date_Time_Creation;
						if (!empty($Nota)) {
							$info.='<tr><td>'.$Cod.'</td>
										<td>'.$Nombre.'</td>
										<td>'.$Total_Preguntas.'</td><td>'.$Preguntas_Resueltas.'</td>
										<td>'.$Nota.'</td><td>'.$Estado.'</td><td>'.$Date_Time_Creation.'</td></tr>';
						}

						
				}
				if (empty($info)){
					$info='<td class="text-center" colspan="5">No ha realizado ningun examen</td>';
				}


				$Listado1='<h5 class="text-center" style="line-height: 1.538462;">Informacion de Examen Final</h5>
				<table id="table-1" class="table table-hover">
				<thead><tr><th>Cod_Examen</th><th>Curso</th><th>Total Preguntas</th><th>Pregunta Realizada</th><th>Nota</th><th>Estado</th><th>Fecha brindada</th></tr></thead>
				'.$info.'</tbody></table>
				<h5 class=" text-center" style="line-height: 1.538462;">Informacion de Curso</h5>
				';


			    $msj = "<div id='Msj-Accion'></div>";
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado1.$msj. $Listado,"");
                DCWrite($Html);
                DCExit();
            break;
            case "visualizar_alumno":
					$btn = "Confirmar ]" .$UrlFile."/Process/DELETE/Obj/visualizar_alumno]animatedModal5]FORM]Alumno_Suscripcion_Crud]btn btn-default dropdown-toggle]}";							
					$Button = DCButton($btn, 'botones14', '');					
					$Html = DCModalFormMsjInterno("Confirma que desea cambiar el estado",$Form,$Button,"bg-info");
					DCWrite($Html);
					DCExit();
	        break;
				
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Pro_Proceso_Detalle = $Settings["Id_Pro_Proceso_Detalle"];
			
		$where = array('Id_Pro_Proceso_Detalle' =>$Id_Pro_Proceso_Detalle);
		$rg = ClassPDO::DCDelete('edu_formato', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		
	
	
	
}