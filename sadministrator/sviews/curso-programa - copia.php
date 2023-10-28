<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
// echo 'samesite_test:' . $_COOKIE['samesite_test'];
$DCTimeHour = DCTimeHour();
$DCTime=DCDate();
$Conection = Conection();

class CursoPrograma{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect,$DCTime;
		
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
							$Almacen=$Registro->Id_Edu_Almacen;
							if(empty($Almacen)){
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
						$Query="SELECT PC.Estado
								FROM programa_cab PC
								WHERE
										PD.Entity = :Entity AND PD.Id_Programa_Cab=:Id_Programa_Cab";
						$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
						$Registro = ClassPdo::DCRow($Query,$Where,$Conection);
						$Estado_Cab=$Registro->Estado;
						if ($Estado_Cab=="Creado") {
							$data = array('Estado' =>  "En Proceso");
							$where = array('Id_Programa_Cab' =>$Parm["Id_Programa_Cab"]);
							ClassPDO::DCUpdate("programa_cab", $data , $where, $Conection);	
						}
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
						//Actualizacion de estado de curso, dependiendo de la matricula

						$this->Proceso_inscripcion($Parm);
						//Cambio de estado
						$Query="SELECT PC.Estado
								FROM programa_cab PC
								WHERE
										PC.Entity = :Entity AND PC.Id_Programa_Cab=:Id_Programa_Cab";
						$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
						$Registro = ClassPdo::DCRow($Query,$Where,$Conection);
						$Estado_Cab=$Registro->Estado;
						if ($Estado_Cab=="En Proceso") {
							$data = array(
										'Estado' =>  "Vinculacion Completa"
							);
							$where = array('Id_Programa_Cab' =>$Parm["Id_Programa_Cab"]);
							$retorno=ClassPDO::DCUpdate("programa_cab", $data , $where, $Conection);
							$Id_Registro = $Result["lastInsertId"];		
							//Actualizar estado de curso
							if (!empty($Id_Registro)) {
								$data = array(
										'Estado' =>  "Generado"
								);
								$where = array('Id_Programa_Cab' =>$Parm["Id_Programa_Cab"]);
								ClassPDO::DCUpdate("programa_det", $data , $where, $Conection);
							}

						}


						$Settings = array();
						$Settings['Url'] =$UrlFile."/Interface/List_programa_det/Id_Programa_Cab/".$Parm["Id_Programa_Cab"];
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);
						DCExit();
						// }
					break;
					// Update 
					case "UpdateInfo_Crud":

						$Data = array();
						$Data['Id_Programa_Cab'] = $Parm["Id_Programa_Cab"];	
						$Id_Programa_Cab=$Parm["Id_Programa_Cab"];
						$Fecha_InicioDC=  DCPost("Fecha_Inicio");	
						$Fecha_FinDC =  DCPost("Fecha_Fin");
						$error=0;$Conteo=0;
						// Verifica la entidad
						$Query="SELECT 
										PA.Id_User,
										PA.Fecha_Fin,
										PA.Fecha_Inicio
										FROM programa_alumno PA
										WHERE
										PA.Entity = :Entity AND PA.Id_Programa_Cab=:Id_Programa_Cab";
								$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
								$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
								foreach($Registro as $Reg) {
									$Id_User_Foreach=$Reg->Id_User;

									if (!empty($Id_User_Foreach)) {

										$data = array(
										'Fecha_Inicio'=>$Fecha_InicioDC,
										'Fecha_Fin'=>$Fecha_FinDC
										);
										$Wheree = array("Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab);
										$Return = ClassPDO::DCUpdate("programa_alumno",$data,$Wheree,$Conection,"");


										$data2 = array(
										'Fecha_Inicio'=>$Fecha_InicioDC,
										'Fecha_Fin'=>$Fecha_FinDC
										);
										$Wheree2 = array("Entity"=>$Entity,"Producto_Origen"=>"Programa","Id_Programa_Cab"=>$Id_Programa_Cab);
										$Return = ClassPDO::DCUpdate("suscripcion",$data2,$Wheree2,$Conection,"");
										$Conteo+= 1;

									}else{
										$error+=1;
									}
								}
							
						

						if ($error==0) {
							if ($Conteo!=0) {
								$Mensaje="Se ha actualizado los datos de ".$Conteo." suscriptores. ";
							}else{$Mensaje="No detecta a los usuarios";}
							
						}else{
							$Mensaje="Se ha actualizado los datos de ".$Conteo." usuarios. ";
							DCWrite(Message("Hay ".$error." errores en la actualizacion","C"));
						}
						DCWrite(Message($Mensaje,"C"));
						$Settings = array();
						$Settings['Url'] =$UrlFile."/Interface/List_programa_det/Id_Programa_Cab/".$Parm["Id_Programa_Cab"];
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);
						DCExit();
						// }
					break;
					// Update tiempo por cada usuario
					case "UpdateInfo_Crud_User":

						$Data = array();
						$Data['Id_Programa_Cab'] = $Parm["Id_Programa_Cab"];	
						$Id_Programa_Cab =$Parm["Id_Programa_Cab"];
						$Id_User=$Parm["Id_User"];
						
						
						$Query_A = "        
							SELECT Fecha_Fin, Fecha_Inicio, Estado FROM programa_alumno
							WHERE 
							Id_Programa_Cab = :Id_Programa_Cab AND Id_User =:Id_User
							GROUP BY Id_Programa_Cab
						";

						$Where = ["Id_Programa_Cab"=>$Id_Programa_Cab,"Id_User"=>$Id_User];
						$Registro_B = ClassPdo::DCRow($Query,$Where,$Conection);						
					    $Fecha_Fin = $Registro_B->Fecha_Fin;
					    $Fecha_Inicio = $Registro_B->Fecha_Inicio;
					    $Estado = $Registro_B->Estado;
						
						$Fecha_InicioDC =  DCPost("Fecha_Inicio");
                        
						// if(empty($Fecha_InicioDC)){
							// $Fecha_InicioDC =  $Fecha_Inicio;
						// }
						
						$Fecha_FinDC =  DCPost("Fecha_Fin");
                        
						// if(empty($Fecha_FinDC)){
							// $Fecha_FinDC =  $Fecha_Fin;
						// }						
						
						$Estado_POST = DCPost("Estado");
						// if(empty($Estado_POST)){
							// $Estado_POST = $Estado;
						// }						
												
						
						if ($Estado_POST == "Activo") {
							
							$Estado_SUS="Activo";
							$Estado_PROG="Matriculado";
							
						}else{
							
							$Estado_SUS="Inactivo";
							$Estado_PROG="Inactivo";
						}
						
						$error=0;$Conteo=0;
						// Verifica la entidad
						$Query="SELECT 
										PA.Id_User,
										PA.Id_Edu_Almacen,
										PA.Fecha_Fin,
										PA.Fecha_Inicio
										FROM programa_alumno PA
										WHERE
										PA.Entity = :Entity AND PA.Id_Programa_Cab=:Id_Programa_Cab AND PA.Id_User=:Id_User ";
								$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab,"Id_User"=>$Id_User];
								$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
								foreach($Registro as $Reg) {
									$Id_User_Foreach=$Reg->Id_User;
									$Id_Edu_Almacen=$Reg->Id_Edu_Almacen;

									if (!empty($Id_User_Foreach)) {
										
										

										$data = array(
											'Fecha_Inicio'=>$Fecha_InicioDC,
											'Fecha_Fin'=>$Fecha_FinDC,
											'Estado'=>$Estado_PROG
										);
										$Wheree = array("Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab,"Id_User"=>$Id_User_Foreach);
										$Return = ClassPDO::DCUpdate("programa_alumno",$data,$Wheree,$Conection,"");


										$data_update_sus = array(
										'Fecha_Inicio'=>$Fecha_InicioDC,
										'Fecha_Fin'=>$Fecha_FinDC,
										'Visibilidad'=>$Estado_SUS
										);
										$Where_update_sus = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_User"=>$Id_User_Foreach);
										$Return = ClassPDO::DCUpdate("suscripcion",$data_update_sus,$Where_update_sus,$Conection,"");
										
										
										
										$Conteo+= 1;

									}else{
										$error+=1;
									}
								}
							
						

						if ($error==0) {
							if ($Conteo!=0) {
								$Mensaje="Se ha actualizado los datos de ".$Conteo." suscriptores. ";
							}else{$Mensaje="No detecta a los usuarios";}
							
						}else{
							$Mensaje="Se ha actualizado los datos de ".$Conteo." usuarios. ";
							DCWrite(Message("Hay ".$error." errores en la actualizacion","C"));
						}
						DCWrite(Message($Mensaje,"C"));
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
							//Cambio de estado
							$Query="SELECT PC.Estado
									FROM programa_cab PC
									WHERE
											PD.Entity = :Entity AND PD.Id_Programa_Cab=:Id_Programa_Cab";
							$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
							$Registro = ClassPdo::DCRow($Query,$Where,$Conection);
							$Estado_Cab=$Registro->Estado;
							if ($Estado_Cab=="En Proceso") {
								$data = array(
											'Estado' =>  "Vinculacion Completa"
								);
								$where = array('Id_Programa_Cab' =>$Parm["Id_Programa_Cab"]);
								$retorno=ClassPDO::DCUpdate("programa_cab", $data , $where, $Conection);
								$Id_Registro = $Result["lastInsertId"];		
								//Actualizar estado de curso
								if (!empty($Id_Registro)) {
									$data = array(
											'Estado' =>  "Generado"
									);
									$where = array('Id_Programa_Cab' =>$Parm["Id_Programa_Cab"]);
									ClassPDO::DCUpdate("programa_det", $data , $where, $Conection);
								}

							}

							$Settings = array();
							$Settings['Url'] =$UrlFile."/Interface/List_programa_det/Id_Programa_Cab/".$Parm["Id_Programa_Cab"];
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "HXMS";
							DCRedirectJS($Settings);
							DCExit();
					break;	

						}		
				
                break;
            case "UPDATE":
            	switch ($Obj) {	
					//Logica Matricula
					case "UpdateMatricula":

						$Id_Programa_Cab = $Parm["Id_Programa_Cab"];
						//Seleccionar Cursos agregados despues del proceso completo. programa_Det
						$Query="SELECT 	
						 			PD.Estado,PD.Id_Edu_Almacen	
								FROM programa_det PD
								WHERE PD.Entity = :Entity AND PD.Id_Programa_Cab =:Id_Programa_Cab AND PD.Estado=:Estado_Curso";
						$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab,"Estado_Curso"=>"SIN GENERAR"];
						$Row = ClassPdo::DCRows($Query,$Where,$Conection);

						//Variables
						$conteoerrormatricula=0;$conteoerroruser=0;$conteoerroralmacen=0;
						$conteomatricula=0;$conteomatriculaupdate=0;

						foreach ($Row as $Result_foreach) {
							$Id_Edu_Almacen=$Result_foreach->Id_Edu_Almacen;
							if (!empty($Id_Edu_Almacen)){
								//Selecciona todos los usuarios
								$Query2="SELECT PA.Id_User,PA.Fecha_Fin,PA.Fecha_Inicio
										FROM programa_alumno PA
										WHERE PA.Entity = :Entity AND PA.Id_Programa_Cab =:Id_Programa_Cab";
								$Where2 = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
								$Row2 = ClassPdo::DCRows($Query2,$Where2,$Conection);
								foreach ($Row2 as $foreachupdate) {
									$Id_User=$foreachupdate->Id_User;
									$Fecha_Fin=$foreachupdate->Fecha_Fin;
									$Fecha_Inicio=$foreachupdate->Fecha_Inicio;
									if (!empty($Id_User)) {
										//Verificar si esta matriculado
										$QueryVerifi="SELECT PA.Id_User as VerificacionUser,PA.Estado,PA.Fecha_Inicio,PA.Fecha_Fin
												FROM programa_alumno PA
												WHERE PA.Entity = :Entity AND PA.Id_Programa_Cab =:Id_Programa_Cab 
												AND PA.Id_Edu_Almacen=:Id_Edu_Almacen AND PA.Id_User=:Id_User";
										$WhereVerifi = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab,"Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_User"=>$Id_User];
										$Row_Verificacion= ClassPdo::DCRow($QueryVerifi,$WhereVerifi,$Conection);
										$VerificacionUser=$Row_Verificacion->VerificacionUser;
										$Fecha_InicioVerificado=$Row_Verificacion->Fecha_Inicio;
										$Fecha_FinVerificado=$Row_Verificacion->Fecha_Fin;
										if (empty($VerificacionUser)) {
											//Vinculo de matricula
												$data = array(
													'Fecha_Fin'=> $Fecha_Fin,
													'Fecha_Inicio'=>$Fecha_Inicio,
													'Producto_Origen'=>"Programa",
													'Id_Perfil_Educacion' =>3,
													'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
													'Id_Programa_Cab'=>$Id_Programa_Cab,
													'Estado' => "Matriculado",
													'Id_User' => $Id_User,
													'Entity' => $Entity,
													'Visibilidad'=>"Activo",
													'Id_User_Update' => $User,
													'Id_User_Creation' => $User,
													'Date_Time_Creation' => $DCTimeHour,
													'Date_Time_Update' => $DCTimeHour
												);
											$Return = ClassPDO::DCInsert("suscripcion", $data, $Conection);
											$Conteoregister +=1;
											//LLenado de program alum
											$data = array(
														'Estado'=>"Matriculado",
														'Fecha_Fin'=> $Fecha_Fin,
														'Fecha_Inicio'=>$Fecha_Inicio,
														'Id_Edu_Almacen' => $Id_Edu_Almacen,
														'Id_User' => $Id_User,
														'Id_Programa_Cab' => $Id_Programa_Cab,
														'Entity' => $Entity,
														'Id_User_Update' => $User,
														'Id_User_Creation' => $User,
														'Date_Time_Creation' => $DCTimeHour,
														'Date_Time_Update' => $DCTimeHour);
											$Return = ClassPDO::DCInsert("programa_alumno", $data, $Conection);
											$conteomatricula+=1;
											
										}else if(!empty($VerificacionUser)){
												//Activando cursos anteriores
												$data = array(
													'Visibilidad'=>"Activo",
													"Date_Time_Update"=>$DCTimeHour,
													"Fecha_Inicio"=>$Fecha_InicioVerificado,
													"Fecha_Fin"=>$Fecha_FinVerificado
												);
												$Wheree = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_User"=>$VerificacionUser);
												$Return = ClassPDO::DCUpdate("suscripcion",$data,$Wheree,$Conection,"");
												//Program_Alum
												$data2 = array(
													'Estado'=>"Matriculado",
													"Date_Time_Update"=>$DCTimeHour,
													"Fecha_Inicio"=>$Fecha_InicioVerificado,
													"Fecha_Fin"=>$Fecha_FinVerificado
												);
												$Where2 = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_User"=>$VerificacionUser,"Id_Programa_Cab"=>$Id_Programa_Cab);
												$Return = ClassPDO::DCUpdate("programa_alumno",$data2,$Where2,$Conection,"");
												$conteomatriculaupdate+=1;

												

										}else{
											$conteoerrormatricula+=1;
										}

									}
									$conteoerroruser+=1;
									
								}

								if($conteoerrormatricula==0){
										//update de Estado de curso 
										$datacurso = array('Estado'=>"Generado");
										$Wherecurso = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_Programa_Cab"=>$Id_Programa_Cab);
										$Return = ClassPDO::DCUpdate("programa_det",$datacurso,$Wherecurso,$Conection,"");
								}
							}
							$conteoerroralmacen+=1;

						}

						if (($conteoerrormatricula==0)) {
								if ($conteomatriculaupdate==0) {
									$Mensaje="Se matriculo correctamente <br> ".$conteomatricula." matriculas realizadas.";

								}else{
									DCWrite(Message("Se actualizo el estado de ".$conteomatriculaupdate." matriculas","C"));	
									$Mensaje="Se matriculo correctamente <br> ".$conteomatricula." matriculas realizadas.";
								}
								

						}else {
							$Mensaje="Error en actualizar las matriculas <br>".$conteoerrormatricula." matriculas no realizada.";

						}

						DCWrite(Message($Mensaje,"C"));





						

						$Settings = array();
						$Settings['Url'] =$UrlFile."/Interface/List_programa_det/Id_Programa_Cab/".$Parm["Id_Programa_Cab"];
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);
						DCExit();
						// }
					break;



				}	
	    	break;
            case "DELETE":
			
				switch ($Obj) {
					//PROCESO PROGRAMA
					case "Delete_Programa":
					
					    DCWrite(Message(" Para eliminar debe contactarse con el administrador","C"));
						
						$Id_Programa_Cab = $Parm["Id_Programa_Cab"];
						$Conteo_Usuarios=0;$Conteo_Cursos=0;

						
						if (!empty($Id_Programa_Cab)) {
								$Query_Conteo="SELECT count(*) as CONTEO_CURSO_PROG FROM programa_det PD
												WHERE PD.Id_Programa_Cab = :Id_Programa_Cab AND PD.Entity=:Entity";	
								$Where_Conteo= ["Id_Programa_Cab" =>$Id_Programa_Cab,'Entity'=>$Entity];
								$Row_Conteo_Curso = ClassPDO::DCRow($Query_Conteo,$Where_Conteo,$Conection);	
								$Conteo_Cursos = $Row_Conteo_Curso->CONTEO_CURSO_PROG;

								if ($Conteo_Cursos!=0) {
										$Query_Conteo_B="SELECT  count(DISTINCT  PA.Id_User) as CONTEO_ALUMNO_PROG FROM programa_alumno PA
														WHERE PA.Id_Programa_Cab = :Id_Programa_Cab AND PA.Entity=:Entity";	
										$Where_Conteo_B= ["Id_Programa_Cab" =>$Id_Programa_Cab,'Entity'=>$Entity];
										$Row_Conteo_User = ClassPDO::DCRow($Query_Conteo_B,$Where_Conteo_B,$Conection);	
										$Conteo_User= $Row_Conteo_User->CONTEO_ALUMNO_PROG;
										if ($Conteo_User!=0) {
											//Programa_Alumno
											
											// $where_prog = array('Id_Programa_Cab'=>$Id_Programa_Cab,'Entity'=>$Entity);
											// ClassPDO::DCDelete('programa_alumno', $where_prog, $Conection);
											//Suscripcion
											// $where_sus = array('Id_Programa_Cab'=>$Id_Programa_Cab,'Producto_Origen'=>'Programa','Entity'=>$Entity);
											// ClassPDO::DCDelete('suscripcion', $where_sus, $Conection);
											
											$Conteo_Usuarios+=1;
										}
										//Programa detalle (Curso del programa)
										// $where_prog_det = array('Id_Programa_Cab'=>$Id_Programa_Cab,'Entity'=>$Entity);
										// ClassPDO::DCDelete('programa_det', $where_prog_det, $Conection);

										$Conteo_Cursos+=1;
								}
								//Programa
								// $where_prog_det = array('Id_Programa_Cab'=>$Id_Programa_Cab,'Entity'=>$Entity);
								// ClassPDO::DCDelete('programa_cab', $where_prog_det, $Conection);

								if (($Conteo_Cursos==0)&&($Conteo_Usuarios==0)) {
									$Mensaje="Se elimino el programa";
								}else{
									if ($Conteo_Usuarios!=0){
										$dato_User=" y existen ".$Conteo_User." usuarios";
									}
									$Mensaje="Se elimino el programa con sus suscripciones";
									// DCWrite(Message("Existen ".$Conteo_Cursos." cursos".$dato_User." dentro del programa","C"));
								}
								// DCWrite(Message($Mensaje,"C"));
						}else{
							// DCWrite(Message("Error en el proceso","E"));
						}
						$Settings = array();
						$Settings['Url'] = $UrlFile;
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);
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
					case "Eliminar_alumno_por_curso":
						
					        $Id_Programa_Det = $Parm["Id_Programa_Det"];

					        $Query = "SELECT PD.Id_Edu_Almacen,PD.Id_Programa_Cab
										FROM programa_det PD
										WHERE 
										PD.Id_Programa_Det = :Id_Programa_Det AND PD.Entity=:Entity";	
							$Wherek = ["Id_Programa_Det" =>$Id_Programa_Det,'Entity'=>$Entity];
							$Rowk = ClassPDO::DCRow($Query,$Wherek,$Conection);	
							$Edu_Almacen = $Rowk->Id_Edu_Almacen;
							$Id_Programa_Cab=$Rowk->Id_Programa_Cab;
							$Id_Warehouse = DCPost("ky");
							$Estado_Update_Sus="";$Estado_Update_Prog="";$Mensaje="";$Estado="";
							if (!empty($Id_Programa_Cab)){
								for ($j = 0; $j < count($Id_Warehouse); $j++) {
										$Query="SELECT PA.Estado
												FROM programa_alumno PA
												WHERE 
												PA.Id_User =:Id_User AND PA.Entity=:Entity AND 
												PA.Id_Edu_Almacen=:Id_Edu_Almacen AND PA.Id_Programa_Cab=:Programa_Cab
												";	
										$Where= ["Id_User" =>$Id_Warehouse[$j],'Entity'=>$Entity,'Id_Edu_Almacen'=>$Edu_Almacen,"Programa_Cab"=>$Id_Programa_Cab];
										$Row = ClassPDO::DCRow($Query,$Where,$Conection);	
										$Estado = $Row->Estado;
										if ($Estado=="Matriculado"){
											$Estado_Update_Sus="Inactivo";$Estado_Update_Prog="Inactivo";
										}else if($Estado=="Inactivo"){$Estado_Update_Sus="Activo";$Estado_Update_Prog="Matriculado";}

										//Realizar cambios en SUS
										$data_User_Sus = array('Visibilidad'=>$Estado_Update_Sus);
										$Where_User_Sus = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_User"=>$Id_Warehouse[$j],"Producto_Origen"=>"Programa","Id_Programa_Cab"=>$Id_Programa_Cab);
										$Return = ClassPDO::DCUpdate("suscripcion",$data_User_Sus,$Where_User_Sus,$Conection,"");
										//Realizar cambios en PROGRAM
										$data_User_Prog = array('Estado'=>$Estado_Update_Prog);
										$Where_User_Prog = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_User"=>$Id_Warehouse[$j],"Id_Programa_Cab"=>$Id_Programa_Cab);
										$Return = ClassPDO::DCUpdate("programa_alumno",$data_User_Prog,$Where_User_Prog,$Conection,"");		
								}
								$Mensaje="Se realizo los cambios correctamente";
							}else{
								$Mensaje="Error en el proceso";
							}

							DCWrite(Message($Mensaje,"C"));
							

							$Settings = array();
							$Settings['Url'] = $UrlFile."/Interface/List_programa_det_curso/Id_Programa_Det/".$Parm["Id_Programa_Det"];
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "HXMS";
							DCRedirectJS($Settings);
							DCExit();			
					break;
					// PROCESO 1ER PAGE ELIMINACION DE USUARIO DEFINITIVO
					case "Delet_User_Definit":
						
					        $this->Delet_User_Definit($Parm);
								 // DCWrite(Message(" Para eliminar debe comunicarse con el administrador","E"));
					        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
															
							$Settings = array();
							$Settings['Url'] =$UrlFile;
							$Settings['Id_Programa_Cab'] =$Id_Programa_Cab;
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
			
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear programa]".$UrlFile."/Interface/Create_Programa]ScreenRight]HXM_SP]]btn btn-primary}";
				
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
				$Table = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','HREF');
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Table .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
			break;
						
			//Page Programa
	            case "Create_Programa":
				
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
				case "Edit_Programa":
				
						
		            $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
					
					
					//$DirecctionA = $UrlFile."/Process/ENTRY/Obj/programa_cab_crud/Id_Programa_Cab/".$Id_Programa_Cab;
					$DirecctionDelete = $UrlFile."/Interface/Eliminar_Programa/Id_Programa_Cab/".$Id_Programa_Cab;
					
					if(!empty($Id_Programa_Cab)){
						
					    $Name_Interface = "Editar";				    	
					    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
					    $DirecctionA = $UrlFile."/Process/ENTRY/Obj/programa_cab_crud/Id_Programa_Cab/".$Id_Programa_Cab;
						
	                   $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","programa_cab_crud","programa_cab_crud","btn btn-default m-w-120");				
					
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
					
					
					
					$Form1  = "<div style='background-color:#fff;'>".$Form1."</div>";
		         					
					
					$btn = " Volver ]" .$UrlFile."]ScreenRight]HXM_SP]]btn btn-primary ladda-button}";
					$btn = DCButton($btn, 'botones1', 'sys_form');				
					$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
		
				    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
	                DCWrite($Html);
	                DCExit();	
				break;
				case "Eliminar_Programa":
				
				
						 $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
							$btn = "Confirmar ]" .$UrlFile."/Process/DELETE/Obj/Delete_Programa/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]programa_cab_crud]btn btn-default dropdown-toggle]}";				
							$btn .= "Cancelar ]" .$UrlFile ."/Interface/List_alumno/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-info}";				
							$Button = DCButton($btn, 'botones1', 'Eliminar_vinculacion');					
							
						    $Html = DCModalFormMsjInterno("¿Deseas Continuar?","Se va eliminar el programa y sus inscritos dentro de ello.",$Button,"bg-info");
		                DCWrite($Html);
						DCExit();
						
		        break;
		    //Fin
		    //1er Page
		        	case "List_programa_det":
					
					    $layout  = new Layout();	

				        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
				        $btn .= "<i class='zmdi zmdi-edit'></i> Editar Programa]".$UrlFile."/Interface/Edit_Programa/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXM]]btn btn-default}";
						
						$listMn = "<i class='zmdi zmdi-edit'></i> Agregar Cursos [".$UrlFile."/Interface/Create_vinculacion_curso/Id_Programa_Cab/".$Id_Programa_Cab."[animatedModal5[HXM[{";
						$listMn .= "<i class='icon-chevron-right'></i> Eliminar Curso[".$UrlFile."/Interface/Eliminar_vinculacion/Id_Programa_Cab/".$Id_Programa_Cab."[animatedModal5[HXM[{";
						$listMn .= "<i class='icon-chevron-right'></i> Certificados [/sadministrator/edu-gestion-certificado/interface/List/key/".$Id_Programa_Cab."/tipo-producto/programa/request/on[_blank[HREF[{";
						$btn .= "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i>Opciones]SubMenu]{$listMn}]OPCIONES]]btn-simple-c}";
				

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
								
									$QueryUpdate="SELECT count(*) as Estado_Curso
												  FROM programa_det PD WHERE
						        				PD.Entity =:Entity  AND PD.Estado=:Estado_Det AND PD.Id_Programa_Cab=:Id_Programa_Cab";
									$WhereUpdate = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab,"Estado_Det"=>"SIN GENERAR"];
									$Reg = ClassPdo::DCRow($QueryUpdate,$WhereUpdate,$Conection);
									$Estado_Curso=$Reg->Estado_Curso;
									if(!empty($Estado_Curso)){
										$listMn.= "<i class='icon-chevron-right'></i> Actualizar Matriculas [".$UrlFile."/Process/UPDATE/Obj/UpdateMatricula/Id_Programa_Cab/".$Id_Programa_Cab."[animatedModal5[HXM[{";
									}

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
						$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '',$Link,$LinkId, $Screen, 'programa_det', 'checks', '','HREF');				
					    $msj = "<div id='Msj-Accion'></div>";
					    
					    $Contenido = DCPage($DCPanelTitle,$Pestanas ."<br>".$msj . $Listado,"");


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
						$listMn .= "<i class='icon-chevron-right'></i> Actualizar Tiempo [".$UrlFile."/Interface/UpdateInfo/Id_Programa_Cab/".$Id_Programa_Cab."[animatedModal5[HXMS[{";
		           		$listMn .= "<i class='icon-chevron-right'></i> Bloquear accesos [".$UrlFile."/Interface/Eliminar_vinculacion_alumnos/Id_Programa_Cab/".$Id_Programa_Cab."[Msj-Accion[HXMS[{";
		           		$listMn .= "<i class='icon-chevron-right'></i> Eiminar Usuario [".$UrlFile."/Interface/Delet_User_Definit/Id_Programa_Cab/".$Id_Programa_Cab."[Msj-Accion[HXMS[{";
						$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
						
						//$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/Interface/Create_vinculacion_Alumno/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-primary ladda-button}";
						//$btn .= " Borrar ]" .$UrlFile."/Interface/Eliminar_vinculacion/Id_Programa_Cab/".$Id_Programa_Cab."]Msj-Accion]HXMS]]btn}";
						
						$btn = DCButton($btn, 'botones1', 'List_programa_alumno');				
						$DCPanelTitle = DCPanelTitle("","Estos son los alumnos vinculados ",$btn,"");
						

						
						// $Query = "SELECT 
						           // CONCAT( '<b>',UM.Nombre, '</b>  <br> ',SC.Estado) AS Participante

								   // ,CONCAT( SC.Fecha_Inicio, '<br>' ,SC.Fecha_Fin ) AS Fechas
								   
									// ,(SELECT COUNT(*) FROM programa_det where Id_Programa_Cab=:Id_Programa_Cab) as Conteo
									// , SC.Id_Suscripcion
									// ,UM.Id_User_Miembro as CodigoLink
								// FROM suscripcion SC
								// INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
								// INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
								// WHERE SC.Entity =:Entity AND  SC.Id_Programa_Cab=:Id_Programa_Cab
								// GROUP BY  UM.Id_User_Miembro
								// ";
								
						$Query = "SELECT 
						           CONCAT( '<b>',UM.Nombre, '</b>  <br> ',SC.Estado) AS Participante

								   ,CONCAT( SC.Fecha_Inicio, '<br>' ,SC.Fecha_Fin ) AS Fechas
								   
									,(SELECT COUNT(*) FROM programa_det where Id_Programa_Cab=:Id_Programa_Cab) as Conteo
									, SC.Id_User
									,UM.Id_User_Miembro as CodigoLink
								FROM programa_alumno SC
								INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
								INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
								WHERE SC.Entity =:Entity AND  SC.Id_Programa_Cab=:Id_Programa_Cab
								GROUP BY  UM.Id_User_Miembro
								";								
								

						$Class = 'table table-hover';
						$LinkId = 'Id_User_Miembro';
						$Link = $UrlFile."/Interface/UpdateInfo/Id_Programa_Cab/".$Id_Programa_Cab;
						$Screen = 'animatedModal5';
						$where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
						
						
						// var_dump($where);
						$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '',$Link, $LinkId, $Screen, 'programa_det', 'checks', '','PS');				
					    $msj = "<div id='Msj-Accion'></div>";
					    $Html = DCModalForm("Alumnos en Campaña",$DCPanelTitle . $msj . $Listado,"");
		                DCWrite($Html);
		                DCExit();
		            break;
		        //1er Page A1: Proceso de crear alumno 
		            case "Create_vinculacion_Alumno":
				
						$Id_Edu_Almacen = $Parm["key"];	
						$Id_Programa_Cab = $Parm["Id_Programa_Cab"];	

						
						$btn .= "Atrás]" .$UrlFile."/Interface/List_programa_alumno/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]btn btn-primary ladda-button}";
						$btn = DCButton($btn, 'botones1', 'sys_form');				
						$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
						
						$DirecctionA = $UrlFile."/Process/ENTRY/Obj/User_Register_Crud/Id_Programa_Cab/".$Id_Programa_Cab;
						$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Programa_Cab/".$Id_Programa_Cab;
						
						// if(!empty($Id_Programa_Cab)){
						    // $Name_Interface = "Editar Participante ";				    	
						    // $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";				
						// }else{
							
						    $Name_Interface = "Crear Participante";					
						    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
		                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
						// }
						
						
						
						$Combobox = array(
							array("Id_User_Sexo","SELECT Id_User_Sexo AS Id, Nombre AS Name FROM user_genero",[]),
			                array("Id_Perfil"," SELECT Id_Perfil_Educacion AS Id, Nombre AS Name FROM perfil_educacion ",[]),
					    	array("Id_Edu_Pais"," SELECT Id_Edu_Pais AS Id, Nombre AS Name FROM edu_pais ",[]),
					     	array("Id_Modalidad_Venta_Curso"," SELECT Id_Modalidad_Venta_Curso AS Id, Nombre AS Name FROM Modalidad_Venta_Curso ",[])
							 
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
	            //1er Page A1: Proceso Update Time
		            case "UpdateInfo":
				
						$Id_Programa_Cab = $Parm["Id_Programa_Cab"];
						$Id_User_Miembro = $Parm["Id_User_Miembro"];


						$btn = DCButton($btn, 'botones1', 'sys_form');				
						$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
						$Name_Interface = "Editar Tiempo";
						$Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";	
						
						if(!empty($Id_User_Miembro)){
						     $DirecctionA= $UrlFile."/Process/ENTRY/Obj/UpdateInfo_Crud_User/Id_Programa_Cab/".$Id_Programa_Cab."/Id_User/".$Id_User_Miembro;
						    			
						}else{
						   	$DirecctionA= $UrlFile."/Process/ENTRY/Obj/UpdateInfo_Crud/Id_Programa_Cab/".$Id_Programa_Cab;					
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
						     array($Name_Button,$DirecctionA,"animatedModal5","Form","Programa_Alumno_Time_Crud"),$ButtonAdicional
						);	
				        $Form1 = BFormVertical("Programa_Alumno_Time_Crud",$Class,$Id_Edu_Tipo_Privacidad,$PathImage,$Combobox,$Buttons,"Id_Edu_Tipo_Privacidad");
						
					    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
		                DCWrite($Html);
		                DCExit();
	                break;
	            //1er Page A2: Proceso de Eliminar alumno
	                case "Eliminar_vinculacion_alumnos":
					
				        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
							$btn = "Confirmar ]" .$UrlFile."/Process/DELETE/Obj/vinculacion_alumno_eliminar/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]FORM]programa_det]btn btn-default dropdown-toggle]}";				
							$btn .= "Cancelar ]" .$UrlFile ."/Interface/List_alumno/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-info}";				
							$Button = DCButton($btn, 'botones1', 'Eliminar_vinculacion');					
							
						    $Html = DCModalFormMsjInterno("Confirma que deseas eliminar",$Form,$Button,"bg-info");

						//}
				        				
						
		                DCWrite($Html);
						DCExit();
		            break;
		        //1er Page A2: Proceso de Eliminar alumno Definitivo
	                case "Delet_User_Definit":
					
				        $Id_Programa_Cab = $Parm["Id_Programa_Cab"];
							$btn = "Confirmar ]" .$UrlFile."/Process/DELETE/Obj/Delet_User_Definit/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]FORM]programa_det]btn btn-default dropdown-toggle]}";				
							$btn .= "Cancelar ]" .$UrlFile ."/Interface/List_alumno/Id_Programa_Cab/".$Id_Programa_Cab."]animatedModal5]HXMS]]btn btn-info}";				
							$Button = DCButton($btn, 'botones1', 'Eliminar_vinculacion');					
							
						    $Html = DCModalFormMsjInterno("¿Confirma que deseas eliminar al usuario?",$Form,$Button,"bg-info");

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
						, AR.Nombre,EA.Fecha_Hora_Ingreso_Almacen as Fecha_Creada, EA.Id_Edu_Almacen as Almacen
						FROM edu_almacen EA
						INNER JOIN edu_articulo AR ON EA.Id_Edu_Articulo = AR.Id_Edu_Articulo
						WHERE EA.Entity = :Entity AND AR.Estado=:Estado AND AR.Id_Edu_Tipo_Estructura = :Id_Edu_Tipo_Estructura 
						ORDER BY EA.Date_Time_Creation DESC

						";    
						$Class = 'table table-hover';
						$LinkId = 'Id_Edu_Almacen';
						$Link = $UrlFile."/Interface/Create";
						$Screen = 'animatedModal5';
						$where = ["Entity"=>$Entity,"Estado"=>"Activo","Id_Edu_Tipo_Estructura"=>1];
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
						$Id_Edu_Almacen=$Row->Id_Edu_Almacen;
						$Id_Programa_Cab=$Row->Id_Programa_Cab;
						

						

						$btn = "<i class='zmdi zmdi-edit'></i> Editar Curso]".$Url_Curso_Edit."/interface/begin/request/on/key/".$Id_Edu_Almacen."/action/sugerencia]animatedModal5]HREF]]btn btn-default}";

						$listMn = "<i class='icon-chevron-right'></i>Gestion de Estado[".$UrlFile."/Interface/Eliminar_alumno_por_curso/Id_Programa_Det/".$Id_Programa_Det."[Msj-Accion[HXMS[{";
						$btn .= "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones de Participantes ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
						$btn .= " Volver ]" .$UrlFile."/Interface/List_programa_det/Id_Programa_Cab/".$Id_Programa_Cab."]ScreenRight]]]btn btn-default}";
						$btn = DCButton($btn, 'botones1', 'programa_cab');				

						$DCPanelTitle = DCPanelTitle($Nombre_Programa." | ".$Nombre."","Estos son los alumnos del curso",$btn);

						//$Query="SELECT PA.Id_Programa_Alumno as CodigoLink,UM.Nombre,UM.Email,PA.Estado as Estado_Curso from programa_alumno PA
						//		INNER JOIN user_miembro UM ON PA.Id_User=UM.Id_User_Miembro
						//		WHERE PA.Id_Edu_Almacen=:Id_Edu_Almacen AND PA.Entity AND PA.Id_Programa_Cab=:Id_Programa_Cab";
						
					
						
						$Query = "
							SELECT UM.Id_User_Miembro as CodigoLink,UM.Nombre,UM.Email,S.Visibilidad as Estado_Curso
							from	suscripcion S
		   			    	INNER JOIN user_miembro UM ON S.Id_User=UM.Id_User_Miembro
		            		INNER JOIN programa_det PD ON S.Id_Edu_Almacen=PD.Id_Edu_Almacen
							WHERE
							S.Entity = :Entity AND  PD.Id_Programa_Det=:Id_Programa_Det AND S.Id_Programa_Cab=:Id_Programa_Cab AND S.Producto_Origen=:Producto_Origen
						";    
						$Class = 'table table-hover';
						$LinkId = 'Id_User_Miembro';
						$Link = $UrlFile."/Interface/Resumen_alumno/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Programa_Det/".$Id_Programa_Det;
						$Screen = 'animatedModal5';
						$where = ["Entity"=>$Entity,"Id_Programa_Det"=>$Id_Programa_Det,"Producto_Origen"=>"Programa","Id_Programa_Cab"=>$Id_Programa_Cab];
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


						$btn = "<i class='zmdi zmdi-edit'></i> Editar Acceso]".$UrlFile."/Interface/Edit_Matricula/request/on/key/".$Id_Edu_Almacen."/action/sugerencia]animatedModal5]HREF]]btn btn-default}";
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
		            // 2do Page: Editar Acceso dentro de curso
		            case "Edit_Matricula":
						
							$Id_Edu_Almacen = $Parm["key"];			
							$Id_Suscripcion = $Parm["Id_Suscripcion"];				
							
							
							$btn .= "D. Personales]" .$UrlFile."/interface/Create_Edit_General/key/".$Id_Edu_Almacen."/Id_Suscripcion/".$Id_Suscripcion."]animatedModal5]HXMS]btn btn-primary ladda-button}";
							$btn .= "D. Extra]" .$UrlFile."/interface/List/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
							$btn .= "Atrás]" .$UrlFile."/interface/List/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
							$btn = DCButton($btn, 'botones1', 'sys_form');				
							$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
							
							
							$DirecctionA = $UrlFile."/Process/CHANGE/Obj/User_Register_Crud/key/".$Id_Edu_Almacen."/Id_User/".$Id_User."/Id_Suscripcion/".$Id_Suscripcion;
							
							$DirecctionDelete = $UrlFile."/interface/DeleteMassive/key/".$Id_Edu_Almacen."/Id_User/".$Id_User2."/Id_Suscripcion/".$Id_Suscripcion;
							
							if(!empty($Id_Suscripcion)){
								$Name_Interface = "Editar Participante";				    	
							    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
			                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","User_Register_Crud","btn btn-default m-w-120");					
							}else{
								$Id_User_Register = $Parm["Id_User_Register"];
							    $Name_Interface = "Generar Matricula";					
							    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Generar";	
			  						
							}//2414 = DCDesencriptarB($texto) DCEncriptarB($texto) 
							
							
							$Combobox = array(
							     array("Id_Perfil"," SELECT Id_Perfil_Educacion AS Id, Nombre AS Name FROM perfil_educacion ",[]),
							     array("Id_Modalidad_Venta_Curso"," SELECT Id_Modalidad_Venta_Curso AS Id, Nombre AS Name FROM Modalidad_Venta_Curso ",[])
							);
							
							$PathImage = array(
							     array("Imagen","/sadministrator/simages/avatars")
							);
							
							$Buttons = array(
							     array($Name_Button,$DirecctionA,"animatedModal5","Form","Form_Suscripcion_D_Extra_Crud"),$ButtonAdicional
							);	
					        $Form1 = BFormVertical("Form_Suscripcion_D_Extra_Crud",$Class,$Id_Suscripcion,$PathImage,$Combobox,$Buttons,"Id_Suscripcion","");
							
						    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
			                DCWrite($Html);
			                DCExit();
	            	break;

		}
				
		
		
	}
	public function Proceso_inscripcion($Settings) {
       	global $Conection, $DCTimeHour,$DCTime,$NameTable;
       	$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		$Name_surnames = DCPost("Nombre");	
		$Correo =   DCPost("Email");
		$PasswordO= DCPost("Password");	
		$Password=trim($PasswordO);
		$Telefono = DCPost("Telefono");	
		$Sexo=DCPost("Id_User_Sexo");
		$Pais=DCPost("Id_Edu_Pais");
		$Id_Modalidad_Venta_Curso=DCPost("Id_Modalidad_Venta_Curso");
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
								'Id_Edu_Pais'=>$Pais,
								'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
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
								'Id_Edu_Pais'=>$Pais,
								'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
								'Entity' => $Entity,
								'Id_User_Creation' => $Id_User,
								'Id_User_Update' => $Id_User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user_miembro", $data, $Conection);
						    $Registro_Nuevo=1;
			}
			
			
			

		//obtener el id de usuario registrado mediante el correo POST
		$Query="SELECT UM.Id_User_Miembro
					    FROM user_miembro UM 
						WHERE  UM.Entity=:Entity AND UM.Email=:Email";
						$Where = ["Entity"=>$Entity,"Email"=>$Correo];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_User_MiembroNuevo = $Row->Id_User_Miembro;
		
		$data2 = array(
			'Version_Desarrollo_Certificado'=>"2"
		);
		$Where2 = array("Id_User_Miembro"=>$Id_User_MiembroNuevo);
		$Return = ClassPDO::DCUpdate("user_miembro",$data2,$Where2,$Conection,"");
		
		
			
		$Id_Programa_Cab = $Settings["Id_Programa_Cab"];
		//Me trae todo los cursos del programa
		$Query="SELECT PD.Id_Programa_Cab,PD.Id_Edu_Almacen 
		        
				FROM programa_det PD
				INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
			    inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
				WHERE PD.Entity = :Entity AND  PD.Id_Programa_Cab=:Id_Programa_Cab
				";
		$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
		$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
		$Conteoregister=0; $Conteoactualizado=0; $error=0;

		foreach($Registro as $Reg) {
				$Edu_Almacen=$Reg->Id_Edu_Almacen;

					//Comprueba si existe en el almacen programa if(Si esta vacio)
					$Query="
				
				                 SELECT  UM.Email,UM.Id_User_Miembro,UM.Id_User_Creation as Id_User_User
							     FROM suscripcion SUS
							     INNER JOIN user_miembro UM on SUS.Id_User=UM.Id_User_Miembro
			                	 INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
								 WHERE SUS.Id_Edu_Almacen=:Edu_Almacen 
								 AND SUS.Entity=:Entity 
								 AND UM.Email=:Email 

								 ";
								 
						// $Where = ["Entity"=>$Entity,"Edu_Almacen"=>$Edu_Almacen,"Email"=>$Correo,"Origen"=>"Programa","Programa_Cab"=>$Id_Programa_Cab];
						$Where = ["Entity"=>$Entity,"Edu_Almacen"=>$Edu_Almacen,"Email"=>$Correo ];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Email_Bd = $Row->Email;
						$Id_User_MiembroP = $Row->Id_User_Miembro;
						
						$Id_UserP = $Row->Id_User_User;
							if(empty($Email_Bd)){
								
								//Vinculo de matricula
									$data = array(
										'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
										'Fecha_Fin'=> DCPost("Fecha_Fin"),
										'Fecha_Inicio'=>$DCTime,
										'Producto_Origen'=>"Programa",
										'Id_Perfil_Educacion' => DCPost("Id_Perfil_Educacion"),
										'Id_Edu_Almacen' =>  $Edu_Almacen,
										// 'Id_Programa_Cab'=>$Id_Programa_Cab,
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
								//LLenado de program alum

								$data = array(
											'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
											'Estado'=>"Matriculado",
											'Fecha_Fin'=> DCPost("Fecha_Fin"),
											'Fecha_Inicio'=>$DCTime,
											'Id_Edu_Almacen' => $Edu_Almacen,
											'Id_User' => $Id_User_MiembroNuevo,
											'Id_Programa_Cab' => $Id_Programa_Cab,
											'Entity' => $Entity,
											'Id_User_Update' => $User,
											'Id_User_Creation' => $User,
											'Date_Time_Creation' => $DCTimeHour,
											'Date_Time_Update' => $DCTimeHour);
								$Return = ClassPDO::DCInsert("programa_alumno", $data, $Conection);


							}elseif(!empty($Email_Bd)) {
										
										
										//Activando cursos anteriores
										$data = array(
											'Visibilidad'=>"Activo",
											"Date_Time_Update"=>$DCTimeHour,
											"Fecha_Inicio"=>$DCTime,
											"Fecha_Fin"=>DCPost("Fecha_Fin")
										);
										$Wheree = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_User"=>$Id_User_MiembroP);
										$Return = ClassPDO::DCUpdate("suscripcion",$data,$Wheree,$Conection,"");
										
										
										$Query="
										SELECT  SUS.Id_Programa_Alumno
										FROM programa_alumno SUS
										WHERE 
										SUS.Entity=:Entity 
										AND SUS.Id_User =:Id_User 
										AND SUS.Id_Programa_Cab=:Id_Programa_Cab 
										AND SUS.Id_Edu_Almacen=:Id_Edu_Almacen 
										";
                                        $Where = ["Entity"=>$Entity,"Id_User"=>$Id_User_MiembroP,"Id_Programa_Cab"=>$Id_Programa_Cab ,"Id_Edu_Almacen"=>$Edu_Almacen];
										$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
										$Id_Programa_Alumno = $Row->Id_Programa_Alumno;

										if(empty($Id_Programa_Alumno)){
											
												$data = array(
															'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
															'Estado'=>"Matriculado",
															'Fecha_Fin'=> DCPost("Fecha_Fin"),
															'Fecha_Inicio'=>$DCTime,
																'Id_Programa_Cab' => $Id_Programa_Cab,
															'Id_Edu_Almacen' => $Edu_Almacen,
															'Id_User' => $Id_User_MiembroNuevo,
															'Entity' => $Entity,
															'Id_User_Update' => $User,
															'Id_User_Creation' => $User,
															'Date_Time_Creation' => $DCTimeHour,
															'Date_Time_Update' => $DCTimeHour);
												$Return = ClassPDO::DCInsert("programa_alumno", $data, $Conection);
											
										}else{
											//Program_Alum
											$data2 = array(
												'Estado'=>"Matriculado",
												"Date_Time_Update"=>$DCTimeHour,
												"Fecha_Inicio"=>$DCTime,
												"Fecha_Fin"=>DCPost("Fecha_Fin")
											);
											$Where2 = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_User"=>$Id_User_MiembroP,"Id_Programa_Cab"=>$Id_Programa_Cab);
											$Return = ClassPDO::DCUpdate("programa_alumno",$data2,$Where2,$Conection,"");
                                       
									   }

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

	public function Proceso_inscripciones_masivas($Settings) {
       	global $Conection, $DCTimeHour,$NameTable,$DCTime;
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
			$Email_Valueb= $key->Email;
			$Email_Value=$Email_Valueb;
			$Passwordb = $key->Clave;
			$Passwordespacio=trim($Passwordb);
			$Password=strtoupper($Passwordespacio);

			$Celular_value = $key->Telefono;
			$Sexo= $key->Genero;

			$Sexoespacio=trim($Sexo);
			$Sexo=strtoupper($Sexoespacio);

			$ruta="";
			if ($Sexo=="MASCULINO") {
				$ruta="4ad89d8dh345s_hombre.png";
				$Genero=1;
			}else if ($Sexo=="FEMENINA") {
				$ruta="4ad89d345s_mujer.png";$Genero=2;
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
								'Id_User_Sexo'=>$Genero,
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
												"Fecha_Inicio"=>$DCTime,
												"Fecha_Fin"=>DCPost("Fecha_Fin"),
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
										//LLenado de program alum
										

										$data = array(
													'Estado'=>"Matriculado",
													"Fecha_Inicio"=>$DCTime,
													"Fecha_Fin"=>DCPost("Fecha_Fin"),
													'Id_Edu_Almacen' => $Edu_Almacen,
													'Id_User' => $Id_User_MiembroNuevo,
													'Id_Programa_Cab' => $Id_Programa_Cab,
													'Entity' => $Entity,
													'Id_User_Update' => $User,
													'Id_User_Creation' => $User,
													'Date_Time_Creation' => $DCTimeHour,
													'Date_Time_Update' => $DCTimeHour);
										$Return = ClassPDO::DCInsert("programa_alumno", $data, $Conection);
										//Actualizacion de clave 
										$reg = array('Password' => $Password);
										$where = array('Id_User' => $Id_User_NUEVO,'Entity'=>$Entity);
										$rg = ClassPDO::DCUpdate('user', $reg , $where, $Conection,"");	
										$count_new_masivo_b +=1;

									}else if(!empty($Email_Bd)) {
											//Activando cursos anteriores
												$data = array(
													'Visibilidad'=>"Activo",
													"Id_Programa_Cab"=>$Id_Programa_Cab,
													'Producto_Origen'=>"Programa",
													"Date_Time_Update"=>$DCTimeHour,
													"Fecha_Inicio"=>$DCTime,
													"Fecha_Fin"=>DCPost("Fecha_Fin")
												);
												$Wheree = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_User"=>$Id_User_MiembroP);
												$Return = ClassPDO::DCUpdate("suscripcion",$data,$Wheree,$Conection,"");


												$Fecha_Fin=DCDuration_Date($DCTime,$Duracion_Programa);
												$Fecha_Fin_Verificado=DCDateVerificacion($Fecha_Fin,"Y-m-d");
												//Program_Alum
												$data2 = array(
													'Estado'=>"Matriculado",
													"Date_Time_Update"=>$DCTimeHour,
													"Fecha_Inicio"=>$DCTime,
													"Fecha_Fin"=>DCPost("Fecha_Fin")
												);
												$Where2 = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_User"=>$Id_User_MiembroP,"Id_Programa_Cab"=>$Id_Programa_Cab);
												$Return = ClassPDO::DCUpdate("programa_alumno",$data2,$Where2,$Conection,"");
												//Actualizacion de clave 
												$reg = array('Password' => $Password);
												$where = array('Id_User' => $Id_UserP,'Entity'=>$Entity);
												$rg = ClassPDO::DCUpdate('user', $reg , $where, $Conection,"");	
												$count_update_masivo_b+=1;
												
											
									}else{
										$error+=1;
									}
						}
						
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
       	global $Conection, $DCTimeHour,$NameTable,$DCTime;
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
									$Wheree = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_Programa_Cab"=>$Id_Programa_Cab,"Id_User"=>$Id_Warehouse[$j]);
									$Return = ClassPDO::DCUpdate("suscripcion",$data,$Wheree,$Conection,"");
									//Desabilitar program alum

									$data = array(
										'Estado'=>"Inactivo"
									);
									$Wheree = array("Entity"=>$Entity,"Id_Edu_Almacen"=>$Edu_Almacen,"Id_Programa_Cab"=>$Id_Programa_Cab,"Id_User"=>$Id_Warehouse[$j]);
									$Return = ClassPDO::DCUpdate("programa_alumno",$data,$Wheree,$Conection,"");
								}
						}	

		

		DCWrite(Message($Conteoeliminar. " cursos ","C"));
	}
	public function Delet_User_Definit($Settings) {
       	global $Conection, $DCTimeHour,$NameTable,$DCTime;
		$Id_Programa_Cab = $Settings["Id_Programa_Cab"];
		$Entity = $_SESSION['Entity'];
		$Id_Warehouse = DCPost("ky");

		$Conteo_Eliminacion_Unique=0;$Eliminacion=0;
				for ($j = 0; $j < count($Id_Warehouse); $j++) {
					var_dump($Id_Warehouse);
							//Verifica si tiene mas de un programa
							$Query_program="SELECT count(*) as COUNT_REGISTRO_PROGRAMA
									FROM programa_alumno PA
									WHERE PA.Id_User=:Id_User AND PA.Entity=:Entity AND PA.Id_Programa_Cab<>:Id_Programa_Cab";
							$Where_program = ["Id_User"=>$Id_Warehouse[$j],"Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Programa_Cab];
							$Registro_program = ClassPdo::DCRows($Query_program,$Where_program,$Conection);
							
							$Programa_Verif=$Registro_program->COUNT_REGISTRO_PROGRAMA;
							if ($Programa_Verif==0){
											// Verifica si el usuario pertenece a cursos independientes
											$Query_curso="SELECT  COUNT(*) AS COUNT_REGISTRO_CURSO FROM suscripcion SC
													WHERE SC.Id_User=:Id_User AND SC.Entity=:Entity AND SC.Producto_Origen=:Producto_Origen";
											$Where_curso= ["Id_User"=>$Id_Warehouse[$j],"Entity"=>$Entity,"Producto_Origen"=>"CURSO"];
											$Reg_curso = ClassPdo::DCRow($Query_curso,$Where_curso,$Conection);
											
                                            // DCWrite(Message(" Id_User ".$Id_Warehouse[$j]."  Id_Programa_Cab".$Id_Programa_Cab." Entity".$Entity,"C"));

											$verif_cursos=$Reg_curso->COUNT_REGISTRO_CURSO;
												// Eliminacion de suscripcion en programa
													$where_delete_program = array("Id_User"=>$Id_Warehouse[$j],'Id_Programa_Cab'=>$Id_Programa_Cab,"Entity"=>$Entity);
													$rg = ClassPDO::DCDelete('programa_alumno', $where_delete_program, $Conection);
												// Eliminacion de suscripcion en cursos
													$where_delet_suscripcion = array("Id_User"=>$Id_Warehouse[$j],'Id_Programa_Cab'=>$Id_Programa_Cab,"Entity"=>$Entity,"Producto_Origen"=>"Programa");
													$rg = ClassPDO::DCDelete('suscripcion', $where_delet_suscripcion, $Conection);
												//Si el registro vota que no tiene cursos independientes, se elimina en global
												if ($verif_cursos==0){
														// Me trae el id user 
														$Query_user="SELECT UM.Id_User_Creation as Id_User_User
																FROM user_miembro UM WHERE UM.Id_User_Miembro=:Id_User_Miembro and UM.Entity=:Entity";
														$Where_user = ["Id_User_Miembro"=>$Id_Warehouse[$j],"Entity"=>$Entity];
														$Registro_user = ClassPdo::DCRow($Query_user,$Where_user,$Conection);
														$Id_User_User=$Registro_user->Id_User_User;
														// Eliminar usuario 
														$where_delet_user = array('Id_User'=>$Id_User_User,"Entity"=>$Entity);
														$rg = ClassPDO::DCDelete('user',$where_delet_user, $Conection);
														// Eliminar usuario miembro
														$where_delete_user_miem = array('Id_User_Miembro'=>$Id_Warehouse[$j],"Entity"=>$Entity);
														$rg = ClassPDO::DCDelete('user_miembro',$where_delete_user_miem, $Conection);
														$Eliminacion+=1;
												}
												$Conteo_Eliminacion_Unique+=1;
											
											
							}else{
												// Eliminacion de suscripcion en programa
												$where_delete_program = array("Id_User"=>$Id_Warehouse[$j],
																				  'Id_Programa_Cab'=>$Id_Programa_Cab,
																				  "Entity"=>$Entity);
												ClassPDO::DCDelete('programa_alumno', $where_delete_program, $Conection);
												// Eliminacion de suscripcion en cursos
												$where_delet_suscripcion = array("Id_User"=>$Id_Warehouse[$j]
																					,'Id_Programa_Cab'=>$Id_Programa_Cab
																					,"Entity"=>$Entity
																					,"Producto_Origen"=>"Programa");
												ClassPDO::DCDelete('suscripcion', $where_delet_suscripcion, $Conection);
												$Conteo_Eliminacion_Unique+=1;
							}
									
				}
				if ($Eliminacion==0) {
					DCWrite(Message("Se eliminaron  <br>".$Conteo_Eliminacion_Unique." suscriptores","C"));
				}else{
					DCWrite(Message("Se eliminaron <br>".$Eliminacion." usuarios unicos del programa","C"));
					DCWrite(Message("Se eliminaron  <br> ".$Conteo_Eliminacion_Unique."suscriptores","C"));
				}
		
		
	}





}