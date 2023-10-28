<?php
require_once(dirname(__FILE__).'/layout_B.php');
require_once(dirname(__FILE__).'/ft_biblioteca.php');
require_once(dirname(__FILE__).'/ft_settings_site.php');
require_once(dirname(__FILE__).'/user.php');

$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Articulo_Det{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-articulo-det";
		$UrlFile_Articulo = "/sadministrator/articulo";
		$UrlFile_Edu_Tipo_Componente = "/sadministrator/edu_tipo_componente";
		$UrlFile_Edu_Tipo_Estructura = "/sadministrator/edu_tipo_estructura";
		$UrlFile_Edu_Area_Conocimiento = "/sadministrator/edu_area_conocimiento";
		$UrlFile_Edu_Blog = "/sadministrator/edu-blog";
		$UrlFile_Edu_Participantes = "/sadministrator/edu-participante";
		$UrlFile_Edu_Participantes_New="/sadministrator/edu-participantenew";
		$UrlFile_Edu_Participante_Masivo="/sadministrator/edu-participante-masivo";
		$UrlFile_Edu_Examen = "/sadministrator/edu-examen";
		$UrlFile_Edu_Reporte = "/sadministrator/edu-reportes";
		$UrlFile_Edu_Acta_Nota="/sadministrator/edu-acta-notas";
		// $UrlFile_Edu_Calificar = "/sadministrator/edu-calificar";
			
		$UrlFile_Edu_Gestion_Certificado = "/sadministrator/edu-gestion-certificado";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					
					case "Edu_Componente_Crud":
					
		                    $Data = array();
							$Data['Id_Edu_Almacen'] = $Parm["key"]; 
				
                            if(DCPost("Introduccion") == "SI" ){
								
								$reg = array(
								'Introduccion' => ''
								);
								$where = array('Id_Edu_Almacen' =>$Parm["key"]);
								$rg = ClassPDO::DCUpdate("edu_componente", $reg , $where, $Conection);	
								
							
							}
		
						    DCCloseModal();	

							$Query = "
							SELECT 
							Count(*)  AS Tot 
							FROM edu_componente EC
							WHERE 
							EC.Id_Edu_Almacen = :Id_Edu_Almacen
							";	
							$Where = ["Id_Edu_Almacen" =>$Parm["key"]];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Tot_Items = $Row->Tot + 1;
							
							$Data['Orden'] = $Tot_Items;
							$Id_Edu_Componente = DCSave($Obj,$Conection,$Parm["Id_Edu_Componente"],"Id_Edu_Componente",$Data);
							
							$this->OrdenarContenido($Parm);
							
							$Settings["interface"] = "y";
							$Settings["key"] = $Parm["key"];
							$Settings["Id_Edu_Componente_S"] = $Parm["Id_Edu_Componente_S"];
							new Edu_Articulo_Det($Settings);
							DCExit("");	
						
						break;		
						
					case "Edu_Articulo_Crud":
					
		                    $Data = array();
							// $Data['Id_Edu_Tipo_Estructura'] = 1; //Cursos						
							// $Data['Id_Edu_Tipo_Componente'] = 6; // Servicios						
				
						    DCCloseModal();									
							$Id_Edu_Articulo = DCSave($Obj,$Conection,$Parm["Id_Edu_Articulo"],"Id_Edu_Articulo",$Data); 	
							$Settings["interface"] = "y";
							$Settings["key"] = $Parm["key"];
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Edu_Articulo_Det($Settings);
							DCExit("");	
						
					break;
				//PROCESO DE VISTA MASIVA (opcion Analisis)
            		case "Visibilidad_Curso_Habilitado":
			            	$key = $Parm["key"];
				            $Id_Entity = $_SESSION['Entity'];
							
							$data = array(
											'Visibilidad' =>  "Activo",
							);
							$where = array('Id_Edu_Almacen' =>$key,'Entity'=>$Id_Entity);

							ClassPDO::DCUpdate("suscripcion", $data , $where, $Conection);
							DCWrite(Message("Se ha Habilitado la visualizacion a todos los participantes de este curso","C"));
							$Settings = array();
								$Settings['Url'] = $UrlFile."/interface/begin/key/".$Parm["key"]."/Action/Sugerencia";
								$Settings['Screen'] = "ScreenRight";
								$Settings['Type_Send'] = "";
								DCRedirectJS($Settings);
							DCExit();	
            		break;
            		case "Visibilidad_Curso_Inhabilitar":
			            	$key = $Parm["key"];
				            $Id_Entity = $_SESSION['Entity'];
							$data = array(
											'Visibilidad' =>  "Inactivo",
							);
							$where = array('Id_Edu_Almacen' =>$key,'Entity'=>$Id_Entity);
							ClassPDO::DCUpdate("suscripcion", $data , $where, $Conection);
							
							DCWrite(Message("Se ha desabilitado la visualizacion a todos los participantes de este curso","C"));
						    
			                $Settings = array();
								$Settings['Url'] = $UrlFile."/interface/begin/key/".$Parm["key"]."/Action/Sugerencia";
								$Settings['Screen'] = "ScreenRight";
								$Settings['Type_Send'] = "";
								DCRedirectJS($Settings);
							DCExit();	
					break;
				//PROCESO DUPLICADO -- CREACION
					case "edu_articulo_dup_create":
							$Id_Edu_Almacen=$Parm["key"];
							$Query="SELECT  EAA.Descripcion,EAA.Id_Edu_Tipo_Estructura,EAA.Imagen,EAA.Palabras_Claves,EAA.Link
										FROM edu_almacen  EA
						                INNER JOIN edu_articulo EAA ON EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
										WHERE  EA.Entity=:Entity AND EA.Id_Edu_Almacen=:Id_Edu_Almacen";
							$Where = ["Entity"=>$Entity,"Id_Edu_Almacen"=>$Id_Edu_Almacen];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Descripcion = $Row->Descripcion;
							$Id_Edu_Tipo_Estructura = $Row->Id_Edu_Tipo_Estructura;
							$Imagen = $Row->Imagen;
							$Palabras_Claves = $Row->Palabras_Claves;

							$Nombre_Curso = DCPost("Nombre");	
							$Estado = DCPost("Estado");
							$Fecha_Publicacion = DCPost("Fecha_Publicacion");
							$Id_Edu_Tipo_Privacidad=DCPost("Id_Edu_Tipo_Privacidad");

							$data = array(
								'Entity' => $Entity,
								'Id_User_Update' => $User,
								'Id_User_Creation' => $User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour,
								'Nombre'=>$Nombre_Curso,
								'Descripcion'=>$Descripcion,
								'Id_Edu_Tipo_Estructura'=>$Id_Edu_Tipo_Estructura,
								'Id_Edu_Tipo_Componente'=>6,
								'Imagen'=>$Imagen,
								'Id_Edu_Area_Conocimiento'=>4,
								'Estado'=>$Estado,
								'Id_Edu_Sub_Linea'=>3,
								'Palabras_Claves'=>$Palabras_Claves,
								'Fecha_Publicacion'=>$Fecha_Publicacion,
								'Imagen_Presentacion'=>$Imagen,
								'Id_Edu_Tipo_Privacidad'=>$Id_Edu_Tipo_Privacidad,
								'Link'=>"",
								'Url_Original'=>""
							);
							$Result = ClassPDO::DCInsert("edu_articulo", $data, $Conection,"");
							$Edu_Articulo = $Result["lastInsertId"];
							if (!empty($Edu_Articulo)) {
								$data = array(
								'Entity' => $Entity,
								'Id_User_Update' => $User,
								'Id_User_Creation' => $User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour,
								'Id_Edu_Articulo'=>$Edu_Articulo,
								'Fecha_Hora_Ingreso_Almacen'=>$DCTimeHour
								);
								$Result2 = ClassPDO::DCInsert("edu_almacen", $data, $Conection,"");
								$Edu_Almacen_Register = $Result2["lastInsertId"];
								$mje="Se ha duplicado el articulo exitosamente";
								//LLenar tabla de movimiento
								$data = array(
								'Entity' => $Entity,
								'Id_User_Update' => $User,
								'Id_User_Creation' => $User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour,
								'Id_Edu_Almacen_Origen'=>$Id_Edu_Almacen,
								'Id_Edu_Almacen_Duplicado'=>$Edu_Almacen_Register,
								'Estado'=>"Creacion",
								'Fecha_Creada'=>DCPost("Fecha_Publicacion"),
								);
								ClassPDO::DCInsert("edu_movimiento_articulo_duplicado", $data, $Conection,"");

							}else{
								$mje="No se ha creado el articulo";
							}
							

							DCWrite(Message($mje,"C"));
							$Settings = array();
								$Settings['Url'] = $UrlFile."/Process/ENTRY/Obj/edu_articulo_dup/key/".$Id_Edu_Almacen."/edu_almacen_new/".$Edu_Almacen_Register;
								$Settings['Screen'] = "ScreenRight";
								$Settings['Type_Send'] = "";
								DCRedirectJS($Settings);
							DCExit();
						
					
		                  
							//new Edu_Articulo_Det($Settings);
							//DCExit("");	
					break;
					//Proceso de duplicado solo contenido,no examen
					case "edu_articulo_dup":
				            	$key = $Parm["key"];
				            	$edu_almacen_new=$Parm["edu_almacen_new"];
				            	//Selecciona todo el contenido que no es examen y sea jerarquia 1 = documentos netos
				            	$Query = "
									SELECT  EC.Nombre,
											EC.Imagen,
											EC.Contenido_Embebido,
											EC.Introduccion,
											EC.Orden,
											EC.Vista_Sin_Inscripcion,
											EC.Id_Edu_Productor,
											EC.Id_User_Miembro_Gestor,
											EC.Id_Edu_Formato,
											EC.Jerarquia_Id_Edu_Componente,
											EC.Descarga,
											EC.Id_Edu_Articulo,
											EC.Id_Edu_Componente
									FROM edu_componente EC
									WHERE 
									EC.Id_Edu_Almacen = :Id_Edu_Almacen AND EC.Id_Edu_Articulo=:Id_Edu_Articulo AND EC.Jerarquia_Id_Edu_Componente=:Jerarquia_Id_Edu_Componente
									";	
									$Where = ["Id_Edu_Almacen" =>$key,"Id_Edu_Articulo" =>0,"Jerarquia_Id_Edu_Componente"=>1];
									$result = ClassPdo::DCRows($Query,$Where,$Conection);
									//Limpia las variables del else
									$Id_Edu_Formato=0;$Descarga="";$Id_User_Miembro_Gestor=0;$Id_Edu_Productor=0;
									$Vista_Sin_Inscripcion="";$Orden=0;$Introduccion="";$Contenido_Embebido="";
									$Imagen="";$Nombre="";$Id_Edu_Componente=0;
									//Limpia las variables del if
									$Nombrecarpeta="";$imgcarpeta="";$contembcarpeta="";$intrcarpeta="";$Ordencarpeta=0;
									$VSICARPETA="";$IDEPRODUCTOR=0;$IDUMG=0;$formatocarpeta=0;$Descargacarpeta="";$Id_Edu_Componente_NEW=0;

									$conteocarpeta=0;$conteo=0;

									foreach ($result as $res) {
										$Id_Edu_Formato=$res->Id_Edu_Formato;$Descarga=$res->Descarga;
										$Id_User_Miembro_Gestor=$res->Id_User_Miembro_Gestor;$Id_Edu_Productor=$res->Id_Edu_Productor;
										$Vista_Sin_Inscripcion=$res->Vista_Sin_Inscripcion;
										$Orden=$res->Orden;$Introduccion=$res->Introduccion;
										$Contenido_Embebido=$res->Contenido_Embebido;
										$Imagen=$res->Imagen;$Nombre=$res->Nombre;

										//VERIFICACION DE CARPETA E EJECUCION DE DUPLICAR CONTENIDO DE LA CARPETA
										if ($Id_Edu_Formato==5) {
											$Id_Edu_Componente=$res->Id_Edu_Componente;
												//Inserta las carpeta
												$Query = "
												SELECT  EC.Nombre,
														EC.Imagen,
														EC.Contenido_Embebido,
														EC.Introduccion,
														EC.Orden,
														EC.Vista_Sin_Inscripcion as VSICARPETA,
														EC.Id_Edu_Productor AS IDEPRODUCTOR,
														EC.Id_User_Miembro_Gestor AS IDUMG,
														EC.Id_Edu_Formato,
														EC.Descarga,
														EC.Id_Edu_Articulo
												FROM edu_componente EC
												WHERE 
												EC.Id_Edu_Almacen = :Id_Edu_Almacen AND EC.Id_Edu_Articulo=:Id_Edu_Articulo AND EC.Id_Edu_Componente=:Id_Edu_Componente
												AND EC.Id_Edu_Formato=:Id_Edu_Formato
												";	
												$Where = ["Id_Edu_Almacen" =>$key,"Id_Edu_Articulo"=>0,"Id_Edu_Componente"=>$Id_Edu_Componente,"Id_Edu_Formato"=>5];
												$res = ClassPdo::DCRow($Query,$Where,$Conection);
												$Nombrecarpeta=$res->Nombre;
												$imgcarpeta=$res->Imagen;$contembcarpeta=$res->Contenido_Embebido;
												$intrcarpeta=$res->Introduccion;$Ordencarpeta=$res->Orden;
												$VSICARPETA=$res->VSICARPETA;$IDEPRODUCTOR=$res->IDEPRODUCTOR;$IDUMG=$res->IDUMG;
												$formatocarpeta=$res->Id_Edu_Formato;$Descargacarpeta=$res->Descarga;
													
													$data = array(
														'Descarga' => $Descargacarpeta,'Id_Edu_Articulo'=>0,
														'Id_Edu_Formato' => $formatocarpeta,'Jerarquia_Id_Edu_Componente' =>1,
														'Vista_Sin_Inscripcion' => $vsicarpeta,'Id_Edu_Productor' => $IDEPRODUCTOR,'Id_User_Miembro_Gestor' => $IDUMG,
														'Introduccion' => $intrcarpeta,'Orden' => $Ordencarpeta,
														'Id_Edu_Almacen' =>  $edu_almacen_new,
														'Contenido_Embebido'=>$contembcarpeta,'Imagen' =>$imgcarpeta,
														'Nombre' => $Nombrecarpeta,
														'Entity' => $Entity,'Id_User_Update' => $User,'Id_User_Creation' => $User,
														'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour
													);
													$Return = ClassPDO::DCInsert("edu_componente", $data, $Conection);
													 $Id_Edu_Componente_NEW = $Return["lastInsertId"];



												$Query = "
												SELECT  EC.Nombre,
														EC.Imagen,
														EC.Contenido_Embebido,
														EC.Introduccion,
														EC.Orden,
														EC.Vista_Sin_Inscripcion as VSICARPETA,
														EC.Id_Edu_Productor AS IDEPRODUCTOR,
														EC.Id_User_Miembro_Gestor AS IDUMG,
														EC.Id_Edu_Formato,
														EC.Descarga,
														EC.Id_Edu_Articulo,
														EC.Id_Edu_Componente
												FROM edu_componente EC
												WHERE 
												EC.Id_Edu_Almacen = :Id_Edu_Almacen AND EC.Id_Edu_Articulo=:Id_Edu_Articulo AND EC.Jerarquia_Id_Edu_Componente =:Jerarquia_Id_Edu_Componente 
												";	
												$Where = ["Id_Edu_Almacen" =>$key,"Id_Edu_Articulo" =>0,"Jerarquia_Id_Edu_Componente"=>$Id_Edu_Componente];
												$result = ClassPdo::DCRows($Query,$Where,$Conection);
												foreach ($result as $res) {
													$Nombrecarpeta=$res->Nombre;
													$imgcarpeta=$res->Imagen;$contembcarpeta=$res->Contenido_Embebido;
													$intrcarpeta=$res->Introduccion;$Ordencarpeta=$res->Orden;
													$VSICARPETA=$res->VSICARPETA;$IDEPRODUCTOR=$res->IDEPRODUCTOR;$IDUMG=$res->IDUMG;
													$formatocarpeta=$res->Id_Edu_Formato;$Descargacarpeta=$res->Descarga;
													
													$data = array(
														'Descarga' => $Descargacarpeta,'Id_Edu_Articulo'=>0,
														'Id_Edu_Formato' => $formatocarpeta,'Jerarquia_Id_Edu_Componente' => $Id_Edu_Componente_NEW,
														'Vista_Sin_Inscripcion' => $vsicarpeta,'Id_Edu_Productor' => $IDEPRODUCTOR,'Id_User_Miembro_Gestor' => $IDUMG,
														'Introduccion' => $intrcarpeta,'Orden' => $Ordencarpeta,
														'Id_Edu_Almacen' =>  $edu_almacen_new,
														'Contenido_Embebido'=>$contembcarpeta,'Imagen' =>$imgcarpeta,
														'Nombre' => $Nombrecarpeta,
														'Entity' => $Entity,'Id_User_Update' => $User,'Id_User_Creation' => $User,
														'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour
													);
													$Return = ClassPDO::DCInsert("edu_componente", $data, $Conection);
													$conteocarpeta+=1;
												}
										}else if($Id_Edu_Formato<>5){
											$data = array(
															'Descarga' => $Descarga,'Id_Edu_Articulo'=>0,
															'Id_Edu_Formato' => $Id_Edu_Formato,'Jerarquia_Id_Edu_Componente' =>1,
															'Vista_Sin_Inscripcion' => $Vista_Sin_Inscripcion,'Id_Edu_Productor' => $Id_Edu_Productor,
															'Id_User_Miembro_Gestor' => $Id_User_Miembro_Gestor,
															'Introduccion' => $Introduccion,'Orden' => $Orden,
															'Id_Edu_Almacen' =>  $edu_almacen_new,
															'Contenido_Embebido'=>$Contenido_Embebido,'Imagen' =>$Imagen,
															'Nombre' => $Nombre,
															'Entity' => $Entity,'Id_User_Update' => $User,'Id_User_Creation' => $User,
															'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour
											);
											$Return = ClassPDO::DCInsert("edu_componente", $data, $Conection);
											$conteo+=1;
										}else{
											$conteo=0;
										}

									}

								//Actualiza estado de  movimiento de duplicado
								$data = array(
												'Estado' =>  "Contenido",
								);
								$where = array('Id_Edu_Almacen_Origen'=>$key,'Id_Edu_Almacen_Duplicado'=>$edu_almacen_new,'Entity'=>$Entity);

								ClassPDO::DCUpdate("edu_movimiento_articulo_duplicado", $data , $where, $Conection);

								DCWrite(Message("Se duplico ".$conteo." contenido y ".$conteocarpeta." carpetas del curso","C"));
							     
								$Settings = array();
									$Settings['Url'] = $UrlFile."/interface/edu_articulo_dup_confirmacion/key/".$key."/edu_almacen_new/".$edu_almacen_new;
									$Settings['Screen'] = "ScreenRight";
									$Settings['Type_Send'] = "";
									DCRedirectJS($Settings);
								DCExit();
					break;
					//Proceso de duplicado solo examen
					case "edu_articulo_examen_dup":
				            	$key = $Parm["key"];
				            	$edu_almacen_new=$Parm["edu_almacen_new"];
				            	$Query = "
									SELECT  EA.Id_Edu_Articulo
									FROM edu_almacen EA
									WHERE 
									EA.Id_Edu_Almacen = :Id_Edu_Almacen AND EA.Entity=:Entity
									";	
									$Where = ["Id_Edu_Almacen" =>$edu_almacen_new,"Entity"=>$Entity];
									$result = ClassPdo::DCRow($Query,$Where,$Conection);
									$Id_Edu_Articulo_new=$result->Id_Edu_Articulo;
								//Limpia las variables de Componente
									$Id_Edu_Formato=0;   $Descarga=""; $Id_User_Miembro_Gestor=0;  $Id_Edu_Productor=0;
									$Vista_Sin_Inscripcion="";  $Orden=0;   $Introduccion="";  $Contenido_Embebido="";
									$Imagen="";$Nombre="";  $Id_Edu_Componente=0;  $Id_Edu_Articulo=0;

								$conteoEvaluacion=0;
								$errorcomponente=0;
								//Fin limpieza Componente

								//Selecciona todo el contenido que es examen y sea jerarquia 1 = INFO CONTENIDO
				            	$Query2 = "SELECT EC.Id_Edu_Componente,  
				            				EC.Nombre,
											EC.Imagen,
											EC.Contenido_Embebido,
											EC.Introduccion,
											EC.Orden,
											EC.Vista_Sin_Inscripcion,
											EC.Id_Edu_Productor,
											EC.Id_User_Miembro_Gestor,
											EC.Id_Edu_Formato,
											EC.Jerarquia_Id_Edu_Componente,
											EC.Descarga,
											EC.Id_Edu_Articulo
										  FROM edu_componente EC
										  WHERE 
										  EC.Id_Edu_Almacen = :Id_Edu_Almacen AND 
										  EC.Jerarquia_Id_Edu_Componente=:Jerarquia_Id_Edu_Componente AND 
										  EC.Entity=:Entity AND EC.Id_Edu_Formato=:Id_Edu_Formato
									";	
								$Where2 = ["Id_Edu_Almacen" =>$key,"Jerarquia_Id_Edu_Componente"=>1,'Entity'=>$Entity,'Id_Edu_Formato'=>7];
								$result = ClassPdo::DCRows($Query2,$Where2,$Conection);
								foreach ($result as $res) {
									$Id_Edu_Formato=$res->Id_Edu_Formato;$Descarga=$res->Descarga;
									$Id_User_Miembro_Gestor=$res->Id_User_Miembro_Gestor;$Id_Edu_Productor=$res->Id_Edu_Productor;
									$Vista_Sin_Inscripcion=$res->Vista_Sin_Inscripcion;
									$Orden=$res->Orden;$Introduccion=$res->Introduccion;
									$Contenido_Embebido=$res->Contenido_Embebido;
									$Imagen=$res->Imagen;$Nombre=$res->Nombre;$Id_Edu_Articulo=$res->Id_Edu_Articulo;
									$Id_Edu_Componente=$res->Id_Edu_Componente;
									//Proceso de vinculacion de edu_evualitvio y componente
									if (!empty($Id_Edu_Componente)) {
											$data = array(
															'Descarga' => $Descarga,'Id_Edu_Articulo'=>$Id_Edu_Articulo_new,
															'Id_Edu_Formato' => $Id_Edu_Formato,'Jerarquia_Id_Edu_Componente' =>1,
															'Vista_Sin_Inscripcion' => $Vista_Sin_Inscripcion,'Id_Edu_Productor' => $Id_Edu_Productor,
															'Id_User_Miembro_Gestor' => $Id_User_Miembro_Gestor,
															'Introduccion' => $Introduccion,'Orden' => $Orden,
															'Id_Edu_Almacen' =>  $edu_almacen_new,
															'Contenido_Embebido'=>$Contenido_Embebido,'Imagen' =>$Imagen,
															'Nombre' => $Nombre,
															'Entity' => $Entity,'Id_User_Update' => $User,'Id_User_Creation' => $User,
															'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour
											);
											$Componente_Retorno=ClassPDO::DCInsert("edu_componente", $data, $Conection);
											$Id_Componente_Retorno = $Componente_Retorno["lastInsertId"];

											//Mostrar informacion de la tabla Edu_ objetivo evaluativo
											$Query3="
											SELECT EOED.Id_Edu_Objeto_Evaluativo,EOE.Nombre,EOE.Descripcion,
													EOE.Id_Edu_Tipo_Objeto_Evaluativo,
													EOE.Fecha_Inicio,EOE.Fecha_Fin,
													EOE.Hora_Inicio,EOE.Hora_Fin,
													EOE.Id_Edu_Tipo_Desarrollo,EOE.Estado,
													EOE.Tiempo_Duracion,EOE.Preguntas_Por_Mostrar
											FROM edu_objeto_evaluativo_detalle EOED
											INNER JOIN edu_objeto_evaluativo EOE ON  EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo 
											WHERE EOED.Id_Edu_Componente=:Id_Edu_Componente AND EOED.Entity=:Entity";	
											$Where3 = ["Id_Edu_Componente" =>$Id_Edu_Componente,'Entity'=>$Entity];
											$result = ClassPdo::DCRow($Query3,$Where3,$Conection);
											//DATA
											$Id_Edu_Objeto_Evaluativo=$result->Id_Edu_Objeto_Evaluativo;
											$NombreEOE=$result->Nombre;
											$DescripcionEOE=$result->Descripcion;
											$TipoOE=$result->Id_Edu_Tipo_Objeto_Evaluativo;
											$Fecha_InicioEOE=DCDate(); $AÑO=date('Y');
											$Fecha_FinEOE=$AÑO."-12-21";


											$Hora_InicioEOE=$result->Hora_Inicio;  $Hora_FinEOE=$result->Hora_Fin;
											$TipoDesarrolloEOE=$result->Id_Edu_Tipo_Desarrollo;
											$EstadoEOE=$result->Estado; $DuracionEOE=$result->Tiempo_Duracion;
											$Preguntas_P_M_EOE=$result->Preguntas_Por_Mostrar;
											//Verifica si el cambo de objeto evaluativo existe para duplicarlo
											if (!empty($Id_Edu_Objeto_Evaluativo)) {


												$data = array(
															'Entity' => $Entity,'Id_User_Creation' => $User,'Id_User_Update' => $User,
															'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour,
															'Nombre'=>$NombreEOE,'Descripcion'=>$DescripcionEOE,
															'Id_Edu_Tipo_Objeto_Evaluativo'=>$TipoOE,
															'Fecha_Inicio'=>$Fecha_InicioEOE,'Fecha_Fin'=>$Fecha_FinEOE,
															'Hora_Inicio'=>$Hora_InicioEOE,'Hora_Fin'=>$Hora_FinEOE,
															'Id_Edu_Tipo_Desarrollo'=>$TipoDesarrolloEOE,'Estado'=>$EstadoEOE,
															'Tiempo_Duracion'=>$DuracionEOE,'Preguntas_Por_Mostrar'=>$Preguntas_P_M_EOE
												);
												$Objeto_Evaluativo_Retorno=ClassPDO::DCInsert("edu_objeto_evaluativo", $data, $Conection);
												$Objeto_Evaluativo_Retorno = $Objeto_Evaluativo_Retorno["lastInsertId"];

												//Vinculacion Componente - Objetivo_Evaluativo (edu_objeto_evaluativo_detalle)
												if (!empty($Objeto_Evaluativo_Retorno)){
														$data2 = array(
																	'Entity' => $Entity,'Id_User_Creation' => $User,'Id_User_Update' => $User,
																	'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour,
																	'Id_Edu_Objeto_Evaluativo'=>$Objeto_Evaluativo_Retorno,
																	'Id_Edu_Articulo'=>$Id_Edu_Articulo_new,'Id_Edu_Componente'=>$Id_Componente_Retorno			
														);
														ClassPDO::DCInsert("edu_objeto_evaluativo_detalle", $data2, $Conection);
														//Proceso de creacion de preguntas
														$Querypreg="SELECT EP.Id_Edu_Pregunta,
																EP.Nombre,EP.Descripcion,EP.Id_Edu_Objeto_Evaluativo,
																EP.Nota,EP.Id_Edu_Tipo_Pregunta,EP.Orden,EP.Pregunta_Validada
																FROM edu_pregunta EP 
																WHERE EP.Id_Edu_Objeto_Evaluativo=:Id_Edu_Objeto_Evaluativo AND EP.Entity=:Entity";	
														$Wherepreg = ["Id_Edu_Objeto_Evaluativo"=>$Id_Edu_Objeto_Evaluativo,'Entity'=>$Entity];
														$resul_pregunta = ClassPdo::DCRows($Querypreg,$Wherepreg,$Conection);
														//Variables de limpieza
															$NombrePreg="";  $DescripcionPreg="";  $NotaPreg=0; $Tipo_Pregunta=0;  
															$OrdenPreg=0; $Pregunta_Valid_Preg="";$Id_Edu_Pregunta=0;
														//FIN
														$errorvinculo_preg_resp=0;
														foreach ($resul_pregunta as $res_preg) {
															$Id_Edu_Pregunta=$res_preg->Id_Edu_Pregunta;
															if (!empty($Id_Edu_Pregunta)) {
																//DATA
																	$NombrePreg=$res_preg->Nombre;  $DescripcionPreg=$res_preg->Descripcion;  
																	$NotaPreg=$res_preg->Nota;      $Tipo_Pregunta=$res_preg->Id_Edu_Tipo_Pregunta;  
																	$OrdenPreg=$res_preg->Orden;    $Pregunta_Valid_Preg=$res_preg->Pregunta_Validada;
																//FIN
																//Duplicado de pregunta
																$data3 = array(
																	'Entity' => $Entity,'Id_User_Creation' => $User,'Id_User_Update' => $User,
																	'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour,
																	'Nombre'=>$NombrePreg,'Descripcion'=>$DescripcionPreg,'Id_Edu_Objeto_Evaluativo'=>$Objeto_Evaluativo_Retorno,
																	'Nota'=>$NotaPreg,'Id_Edu_Tipo_Pregunta'=>$Tipo_Pregunta,'Orden'=>$OrdenPreg,'Pregunta_Validada'=>$Pregunta_Valid_Preg
																);
																$Pregunta_Retorno=ClassPDO::DCInsert("edu_pregunta", $data3, $Conection);
																$Id_Edu_Pregunta_Retorno = $Pregunta_Retorno["lastInsertId"];
																// Proceso de creacion de respuestas
																if (!empty($Id_Edu_Pregunta_Retorno)) {
																	$Queryresp="SELECT 
																				ER.Nombre as Respuesta,
																				ER.Descripcion as Descripcion_Resp,
																				ER.Respuesta_Correcta,ER.Orden
																				FROM edu_respuesta ER 
																				WHERE ER.Id_Edu_Pregunta=:Id_Edu_Pregunta AND ER.Entity=:Entity";	
																	$Whereresp = ["Id_Edu_Pregunta"=>$Id_Edu_Pregunta,'Entity'=>$Entity];
																	$resul_respuesta = ClassPdo::DCRows($Queryresp,$Whereresp,$Conection);
																	//Limpiar variables de respuesta
																		$Respuesta="";  $Descripcion_Resp="";
																		$Respuesta_Correc="";  $Orden_Res=0;
																	//Fin de Limpieza
																	foreach ($resul_respuesta as $res_resp) {
																		//DATA
																		$Respuesta=$res_resp->Respuesta;  $Descripcion_Resp=$res_resp->Descripcion_Resp;
																		$Respuesta_Correc=$res_resp->Respuesta_Correcta;  $Orden_Res=$res_resp->Orden;
																		//Registrar duplicado de respuesta
																		$data_resp = array(
																			'Entity' => $Entity,'Id_User_Creation' => $User,'Id_User_Update' => $User,
																			'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour,
																			'Nombre'=>$Respuesta,'Descripcion'=>$Descripcion_Resp,'Id_Edu_Pregunta'=>$Id_Edu_Pregunta_Retorno,
																			'Respuesta_Correcta'=>$Respuesta_Correc,'Orden'=>$Orden_Res
																		);
																		ClassPDO::DCInsert("edu_respuesta", $data_resp, $Conection);
																	}
																}
															}else{
																$errorvinculo_preg_resp+=1;
															}
														}
													$conteoEvaluacion+=1;
												}else{
													$errorvinculacionexamen+=1;
												}
											}
									}else{
										$errorcomponente+=1;
									}
								}

								//Actualiza estado de  movimiento de duplicado
								$data = array(
												'Estado' =>  "Contenido_Completo",
								);
								$where = array('Id_Edu_Almacen_Origen'=>$key,'Id_Edu_Almacen_Duplicado'=>$edu_almacen_new,'Entity'=>$Entity);

								ClassPDO::DCUpdate("edu_movimiento_articulo_duplicado", $data , $where, $Conection);

								if (($errorvinculacionexamen==0)or($errorcomponente==0)) {
									if ($errorvinculo_preg_resp==0) {
										$Mensaje="Se duplico exitosamente ".$conteoEvaluacion." Examen";
									}else{
										$Mensaje="No se ha creado las preguntas, ni las respuestas";
									}
								}else{
									$Mensaje="No se ha creado ".$errorvinculacionexamen. " examenes";
									DCWrite(Message("Se han duplicado ".$conteoEvaluacion." examenes","C"));
								}

								DCWrite(Message($Mensaje,"C"));

				                $Settings = array();
									$Settings['Url'] = $UrlFile."/interface/begin/key/".$edu_almacen_new."/Action/Sugerencia";
									$Settings['Screen'] = "ScreenRight";
									$Settings['Type_Send'] = "";
									DCRedirectJS($Settings);
								DCExit();	
					break;
					//Proceso de duplicado solo examen
					case "edu_articulo_examen_dup_dependiente":
				            	$key = $Parm["key"];
				            	$edu_almacen_new=DCPost("Id_Edu_Almacen");
				            	$Id_Edu_Componente_Form=DCPost("Id_Edu_Componente");
				            	$Query = "
									SELECT  EA.Id_Edu_Articulo
									FROM edu_almacen EA
									WHERE 
									EA.Id_Edu_Almacen = :Id_Edu_Almacen AND EA.Entity=:Entity
									";	
									$Where = ["Id_Edu_Almacen" =>$edu_almacen_new,"Entity"=>$Entity];
									$result = ClassPdo::DCRow($Query,$Where,$Conection);
									$Id_Edu_Articulo_new=$result->Id_Edu_Articulo;
								//Limpia las variables de Componente
									$Id_Edu_Formato=0;   $Descarga=""; $Id_User_Miembro_Gestor=0;  $Id_Edu_Productor=0;
									$Vista_Sin_Inscripcion="";  $Orden=0;   $Introduccion="";  $Contenido_Embebido="";
									$Imagen="";$Nombre="";  $Id_Edu_Componente=0;  $Id_Edu_Articulo=0;

								$conteoEvaluacion=0;
								$errorcomponente=0;
								//Fin limpieza Componente

								//Selecciona todo el contenido que es examen y sea jerarquia 1 = INFO CONTENIDO
				            	$Query2 = "SELECT EC.Id_Edu_Componente,  
				            				EC.Nombre,
											EC.Imagen,
											EC.Contenido_Embebido,
											EC.Introduccion,
											EC.Orden,
											EC.Vista_Sin_Inscripcion,
											EC.Id_Edu_Productor,
											EC.Id_User_Miembro_Gestor,
											EC.Id_Edu_Formato,
											EC.Jerarquia_Id_Edu_Componente,
											EC.Descarga,
											EC.Id_Edu_Articulo
										  FROM edu_componente EC
										  WHERE 
										  EC.Id_Edu_Componente = :Id_Edu_Componente AND EC.Entity=:Id_Edu_Componente";	
								$Where2 = ["Id_Edu_Componente" =>$Id_Edu_Componente_Form,'Entity'=>$Entity];
								$result = ClassPdo::DCRows($Query2,$Where2,$Conection);
								foreach ($result as $res) {
									$Id_Edu_Formato=$res->Id_Edu_Formato;$Descarga=$res->Descarga;
									$Id_User_Miembro_Gestor=$res->Id_User_Miembro_Gestor;$Id_Edu_Productor=$res->Id_Edu_Productor;
									$Vista_Sin_Inscripcion=$res->Vista_Sin_Inscripcion;
									$Orden=$res->Orden;$Introduccion=$res->Introduccion;
									$Contenido_Embebido=$res->Contenido_Embebido;
									$Imagen=$res->Imagen;$Nombre=$res->Nombre;$Id_Edu_Articulo=$res->Id_Edu_Articulo;
									$Id_Edu_Componente=$res->Id_Edu_Componente;
									//Proceso de vinculacion de edu_evualitvio y componente
									if (!empty($Id_Edu_Componente)) {
											$data = array(
															'Descarga' => $Descarga,'Id_Edu_Articulo'=>$Id_Edu_Articulo_new,
															'Id_Edu_Formato' => $Id_Edu_Formato,'Jerarquia_Id_Edu_Componente' =>1,
															'Vista_Sin_Inscripcion' => $Vista_Sin_Inscripcion,'Id_Edu_Productor' => $Id_Edu_Productor,
															'Id_User_Miembro_Gestor' => $Id_User_Miembro_Gestor,
															'Introduccion' => $Introduccion,'Orden' => $Orden,
															'Id_Edu_Almacen' =>  $edu_almacen_new,
															'Contenido_Embebido'=>$Contenido_Embebido,'Imagen' =>$Imagen,
															'Nombre' => $Nombre,
															'Entity' => $Entity,'Id_User_Update' => $User,'Id_User_Creation' => $User,
															'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour
											);
											$Componente_Retorno=ClassPDO::DCInsert("edu_componente", $data, $Conection);
											$Id_Componente_Retorno = $Componente_Retorno["lastInsertId"];

											//Mostrar informacion de la tabla Edu_ objetivo evaluativo
											$Query3="
											SELECT EOED.Id_Edu_Objeto_Evaluativo,EOE.Nombre,EOE.Descripcion,
													EOE.Id_Edu_Tipo_Objeto_Evaluativo,
													EOE.Fecha_Inicio,EOE.Fecha_Fin,
													EOE.Hora_Inicio,EOE.Hora_Fin,
													EOE.Id_Edu_Tipo_Desarrollo,EOE.Estado,
													EOE.Tiempo_Duracion,EOE.Preguntas_Por_Mostrar
											FROM edu_objeto_evaluativo_detalle EOED
											INNER JOIN edu_objeto_evaluativo EOE ON  EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo 
											WHERE EOED.Id_Edu_Componente=:Id_Edu_Componente AND EOED.Entity=:Entity";	
											$Where3 = ["Id_Edu_Componente" =>$Id_Edu_Componente,'Entity'=>$Entity];
											$result = ClassPdo::DCRow($Query3,$Where3,$Conection);
											//DATA
											$Id_Edu_Objeto_Evaluativo=$result->Id_Edu_Objeto_Evaluativo;
											$NombreEOE=$result->Nombre;
											$DescripcionEOE=$result->Descripcion;
											$TipoOE=$result->Id_Edu_Tipo_Objeto_Evaluativo;
											$Fecha_InicioEOE=DCDate(); $AÑO=date('Y');
											$Fecha_FinEOE=$AÑO."-12-21";


											$Hora_InicioEOE=$result->Hora_Inicio;  $Hora_FinEOE=$result->Hora_Fin;
											$TipoDesarrolloEOE=$result->Id_Edu_Tipo_Desarrollo;
											$EstadoEOE=$result->Estado; $DuracionEOE=$result->Tiempo_Duracion;
											$Preguntas_P_M_EOE=$result->Preguntas_Por_Mostrar;
											//Verifica si el cambo de objeto evaluativo existe para duplicarlo
											if (!empty($Id_Edu_Objeto_Evaluativo)) {


												$data = array(
															'Entity' => $Entity,'Id_User_Creation' => $User,'Id_User_Update' => $User,
															'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour,
															'Nombre'=>$NombreEOE,'Descripcion'=>$DescripcionEOE,
															'Id_Edu_Tipo_Objeto_Evaluativo'=>$TipoOE,
															'Fecha_Inicio'=>$Fecha_InicioEOE,'Fecha_Fin'=>$Fecha_FinEOE,
															'Hora_Inicio'=>$Hora_InicioEOE,'Hora_Fin'=>$Hora_FinEOE,
															'Id_Edu_Tipo_Desarrollo'=>$TipoDesarrolloEOE,'Estado'=>$EstadoEOE,
															'Tiempo_Duracion'=>$DuracionEOE,'Preguntas_Por_Mostrar'=>$Preguntas_P_M_EOE
												);
												$Objeto_Evaluativo_Retorno=ClassPDO::DCInsert("edu_objeto_evaluativo", $data, $Conection);
												$Objeto_Evaluativo_Retorno = $Objeto_Evaluativo_Retorno["lastInsertId"];

												//Vinculacion Componente - Objetivo_Evaluativo (edu_objeto_evaluativo_detalle)
												if (!empty($Objeto_Evaluativo_Retorno)){
														$data2 = array(
																	'Entity' => $Entity,'Id_User_Creation' => $User,'Id_User_Update' => $User,
																	'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour,
																	'Id_Edu_Objeto_Evaluativo'=>$Objeto_Evaluativo_Retorno,
																	'Id_Edu_Articulo'=>$Id_Edu_Articulo_new,'Id_Edu_Componente'=>$Id_Componente_Retorno			
														);
														ClassPDO::DCInsert("edu_objeto_evaluativo_detalle", $data2, $Conection);
														//Proceso de creacion de preguntas
														$Querypreg="SELECT EP.Id_Edu_Pregunta,
																EP.Nombre,EP.Descripcion,EP.Id_Edu_Objeto_Evaluativo,
																EP.Nota,EP.Id_Edu_Tipo_Pregunta,EP.Orden,EP.Pregunta_Validada
																FROM edu_pregunta EP 
																WHERE EP.Id_Edu_Objeto_Evaluativo=:Id_Edu_Objeto_Evaluativo AND EP.Entity=:Entity";	
														$Wherepreg = ["Id_Edu_Objeto_Evaluativo"=>$Id_Edu_Objeto_Evaluativo,'Entity'=>$Entity];
														$resul_pregunta = ClassPdo::DCRows($Querypreg,$Wherepreg,$Conection);
														//Variables de limpieza
															$NombrePreg="";  $DescripcionPreg="";  $NotaPreg=0; $Tipo_Pregunta=0;  
															$OrdenPreg=0; $Pregunta_Valid_Preg="";$Id_Edu_Pregunta=0;
														//FIN
														$errorvinculo_preg_resp=0;
														foreach ($resul_pregunta as $res_preg) {
															$Id_Edu_Pregunta=$res_preg->Id_Edu_Pregunta;
															if (!empty($Id_Edu_Pregunta)) {
																//DATA
																	$NombrePreg=$res_preg->Nombre;  $DescripcionPreg=$res_preg->Descripcion;  
																	$NotaPreg=$res_preg->Nota;      $Tipo_Pregunta=$res_preg->Id_Edu_Tipo_Pregunta;  
																	$OrdenPreg=$res_preg->Orden;    $Pregunta_Valid_Preg=$res_preg->Pregunta_Validada;
																//FIN
																//Duplicado de pregunta
																$data3 = array(
																	'Entity' => $Entity,'Id_User_Creation' => $User,'Id_User_Update' => $User,
																	'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour,
																	'Nombre'=>$NombrePreg,'Descripcion'=>$DescripcionPreg,'Id_Edu_Objeto_Evaluativo'=>$Objeto_Evaluativo_Retorno,
																	'Nota'=>$NotaPreg,'Id_Edu_Tipo_Pregunta'=>$Tipo_Pregunta,'Orden'=>$OrdenPreg,'Pregunta_Validada'=>$Pregunta_Valid_Preg
																);
																$Pregunta_Retorno=ClassPDO::DCInsert("edu_pregunta", $data3, $Conection);
																$Id_Edu_Pregunta_Retorno = $Pregunta_Retorno["lastInsertId"];
																// Proceso de creacion de respuestas
																if (!empty($Id_Edu_Pregunta_Retorno)) {
																	$Queryresp="SELECT 
																				ER.Nombre as Respuesta,
																				ER.Descripcion as Descripcion_Resp,
																				ER.Respuesta_Correcta,ER.Orden
																				FROM edu_respuesta ER 
																				WHERE ER.Id_Edu_Pregunta=:Id_Edu_Pregunta AND ER.Entity=:Entity";	
																	$Whereresp = ["Id_Edu_Pregunta"=>$Id_Edu_Pregunta,'Entity'=>$Entity];
																	$resul_respuesta = ClassPdo::DCRows($Queryresp,$Whereresp,$Conection);
																	//Limpiar variables de respuesta
																		$Respuesta="";  $Descripcion_Resp="";
																		$Respuesta_Correc="";  $Orden_Res=0;
																	//Fin de Limpieza
																	foreach ($resul_respuesta as $res_resp) {
																		//DATA
																		$Respuesta=$res_resp->Respuesta;  $Descripcion_Resp=$res_resp->Descripcion_Resp;
																		$Respuesta_Correc=$res_resp->Respuesta_Correcta;  $Orden_Res=$res_resp->Orden;
																		//Registrar duplicado de respuesta
																		$data_resp = array(
																			'Entity' => $Entity,'Id_User_Creation' => $User,'Id_User_Update' => $User,
																			'Date_Time_Creation' => $DCTimeHour,'Date_Time_Update' => $DCTimeHour,
																			'Nombre'=>$Respuesta,'Descripcion'=>$Descripcion_Resp,'Id_Edu_Pregunta'=>$Id_Edu_Pregunta_Retorno,
																			'Respuesta_Correcta'=>$Respuesta_Correc,'Orden'=>$Orden_Res
																		);
																		ClassPDO::DCInsert("edu_respuesta", $data_resp, $Conection);
																	}
																}
															}else{
																$errorvinculo_preg_resp+=1;
															}
														}
													$conteoEvaluacion+=1;
												}else{
													$errorvinculacionexamen+=1;
												}
											}
									}else{
										$errorcomponente+=1;
									}
								}

								//Actualiza estado de  movimiento de duplicado
								$data = array(
												'Estado' =>  "Contenido_Completo",
								);
								$where = array('Id_Edu_Almacen_Origen'=>$key,'Id_Edu_Almacen_Duplicado'=>$edu_almacen_new,'Entity'=>$Entity);

								ClassPDO::DCUpdate("edu_movimiento_articulo_duplicado", $data , $where, $Conection);

								if (($errorvinculacionexamen==0)or($errorcomponente==0)) {
									if ($errorvinculo_preg_resp==0) {
										$Mensaje="Se duplico exitosamente ".$conteoEvaluacion." Examen";
									}else{
										$Mensaje="No se ha creado las preguntas, ni las respuestas";
									}
								}else{
									$Mensaje="No se ha creado ".$errorvinculacionexamen. " examenes";
									DCWrite(Message("Se han duplicado ".$conteoEvaluacion." examenes","C"));
								}

								DCWrite(Message($Mensaje,"C"));
				            	DCWrite(Message("Componente:".$Id_Edu_Componente_Form." and almacen: ".$edu_almacen_new,"C"));

				                $Settings = array();
									$Settings['Url'] = $UrlFile."/interface/begin/key/".$edu_almacen_new."/Action/Sugerencia";
									$Settings['Screen'] = "ScreenRight";
									$Settings['Type_Send'] = "";
									DCRedirectJS($Settings);
								DCExit();	
					break;
				// PROCESO CONFIGURAR -- EVALUACION
					case "Configurar_Evaluar_Crud":
						            	$key = $Parm["key"];
							            $Id_Entity = $_SESSION['Entity'];

							            $Query = "SELECT EA.Id_Edu_Articulo FROM edu_almacen EA
							            		  WHERE EA.Id_Edu_Almacen=:Almacen AND EA.Entity=:Entity
												";	
										$Where = ["Almacen" =>$key,"Entity" =>$Id_Entity];
										$Row = ClassPdo::DCRow($Query,$Where,$Conection);
										$Articulo=$Row->Id_Edu_Articulo;
										if (!empty($Articulo)) {
											//Llenar info  almacen
											$data = array(
												'Tipo_Evaluar' => DCPost("Tipo_Evaluar"),
												'Nota_Minima' => DCPost("Nota_Minima"),
											);
											$where = array('Id_Edu_Almacen' =>$key,'Entity'=>$Id_Entity);
											ClassPDO::DCUpdate("edu_almacen", $data , $where, $Conection);
											//Llenar info  articulo
											$data = array(
												'Tipo_Evaluar' => DCPost("Tipo_Evaluar"),
												'Nota_Minima' => DCPost("Nota_Minima"),
											);
											$where = array('Id_Edu_Articulo' =>$Articulo,'Entity'=>$Id_Entity);
											ClassPDO::DCUpdate("edu_articulo", $data , $where, $Conection);
											$Mensaje_Evaluar="Se actualizo la informacion";
										}

										
										
										if (!empty($Mensaje_Evaluar)) {
											DCWrite(Message($Mensaje_Evaluar,"C"));
										}else{
											DCWrite(Message("Error","E"));
										}
										
										
										$Settings = array();
											$Settings['Url'] = $UrlFile."/interface/begin/key/".$Parm["key"]."/Action/Sugerencia";
											$Settings['Screen'] = "ScreenRight";
											$Settings['Type_Send'] = "";
											DCRedirectJS($Settings);
										DCExit();	
					break;
									
							
					case "Edu_Chat_Crud":
                            
							$Id_Edu_Almacen = $Parm["key"]; 					
							// $Id_Edu_Almacen = 3; 					
						    // DCCloseModal();	
							$Edu_Chat_Crud_Mensaje = DCPost("Edu_Chat_Crud_Mensaje");
							if(!empty($Edu_Chat_Crud_Mensaje)){
								$Script ="
									<script>
									$('#Edu_Chat_Crud_Mensaje').val('');
									</script>
								";
								echo $Script;		
								GuardaMensajeChatDDB($Id_Edu_Almacen,$DCTimeHour,$Entity,$Edu_Chat_Crud_Mensaje,$User);	
															
							}else{
								
								DCWrite(Message("Debes insertar un valor","C"));
								
								
							}
							
							//$Mensajes =  LeerMensajeChatDDB($Id_Edu_Almacen);


							
							echo $Mensajes;
					break;								
					
					case "Edu_Componente_Carpeta_Crud":
					case "Edu_Componente_Doc_Crud":
					case "Edu_Componente_DocA_Crud":
					case "Edu_Componente_Articulo_Crud":
					case "Edu_Componente_Embebido_Crud":
					case "Edu_Componente_Articulo_B_Crud":
					
					
					        if($Obj == "Edu_Componente_Doc_Crud" ){
								
								$Id_Edu_Formato = 2;
								
					        }elseif($Obj == "Edu_Componente_DocA_Crud" ){
								
								$Id_Edu_Formato = 2;
																
					        }elseif($Obj == "Edu_Componente_Articulo_Crud" ){
								
								$Id_Edu_Formato = 6;
								
																
					        }elseif($Obj == "Edu_Componente_Articulo_B_Crud" ){
								
								$Id_Edu_Formato = 6;
																
					        }elseif($Obj == "Edu_Componente_Embebido_Crud" ){
								
								$Id_Edu_Formato = 3;
														
							}else{
								$Id_Edu_Formato = 5;
							}
			                //brians
                            $Data = array();
							$Data['Id_Edu_Almacen'] = $Parm["key"]; 
							$Data['Id_Edu_Formato'] = $Id_Edu_Formato; // Servicios	
						
                            if(!empty($Parm["Id_Edu_Componente_Jerar"])){
								// var_dump($Parm["Id_Edu_Componente_Jerar"]);
							    $Data['Jerarquia_Id_Edu_Componente'] = $Parm["Id_Edu_Componente_Jerar"]; // Servicios
								
							// echo "hola ,ttt";								
							}else{
                                $Data['Jerarquia_Id_Edu_Componente'] = 1;
								// echo "hola ,mundo";
                            }
						    
					
                            if(DCPost("Introduccion") == "SI" ){
								
								$reg = array(
								'Introduccion' => ''
								);
								$where = array('Id_Edu_Almacen' =>$Parm["key"]);
								$rg = ClassPDO::DCUpdate("edu_componente", $reg , $where, $Conection,"");
								
							
							}
		
						    DCCloseModal();	

                            if(empty($Parm["Id_Edu_Componente_Jerar"])){	
							
								$Query = "
								SELECT 
								Count(*)  AS Tot 
								FROM edu_componente EC
								WHERE 
								EC.Id_Edu_Almacen = :Id_Edu_Almacen AND
								EC.Jerarquia_Id_Edu_Componente = :Jerarquia_Id_Edu_Componente
								";	
								$Where = ["Id_Edu_Almacen" =>$Parm["key"],"Jerarquia_Id_Edu_Componente" => 1];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$Tot_Items = $Row->Tot + 1;
								$Data['Orden'] = $Tot_Items;
								
							}else{
								
								$Query = "
								SELECT 
								Count(*)  AS Tot 
								FROM edu_componente EC
								WHERE 
								EC.Id_Edu_Almacen = :Id_Edu_Almacen AND
								EC.Jerarquia_Id_Edu_Componente <> :Jerarquia_Id_Edu_Componente
								";	
								$Where = ["Id_Edu_Almacen" =>$Parm["key"],"Jerarquia_Id_Edu_Componente" => 1];
			
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$Tot_Items = $Row->Tot + 1;
								$Data['Orden'] = $Tot_Items;								
							}
							
							$Id_Edu_Componente = DCSave($Obj,$Conection,$Parm["Id_Edu_Componente"],"Id_Edu_Componente",$Data);
							
							$this->OrdenarContenido($Parm);
							
							if($Obj == "Edu_Componente_Carpeta_Crud" ){
								
							    $Settings["interface"] = "Elementos_SI";
								
							}elseif($Obj == "Edu_Componente_DocA_Crud" ){
								
							    $Settings["interface"] = "Elementos_SI";							
								
							}elseif($Obj == "Edu_Componente_Articulo_Crud" ){
								
							    $Settings["interface"] = "Elementos_SI";
								
							}elseif($Obj == "Edu_Componente_Doc_Crud" ){
								
							    $Settings["interface"] = "Elementos_SI";								
								
								
							}elseif($Obj == "Edu_Componente_Embebido_Crud" ){
								
							    $Settings["interface"] = "Elementos_SI";								
								
							}elseif($Obj == "Create_Conten_SubItemB_Dcoc" ){
								
							    $Settings["interface"] = "PanelB";	
								
							}elseif($Obj == "Edu_Componente_Articulo_B_Crud" ){
								
							    $Settings["interface"] = "y";								
																
							}else{
							    $Settings["interface"] = "PanelB";								
							}
							
								$Settings["key"] = $Parm["key"];
								$Settings["Id_Edu_Componente_S"] = $Parm["Id_Edu_Componente_S"];
								$Settings["Id_Edu_Componente"] = $Parm["Id_Edu_Componente"];
								$Settings["Id_Edu_Componente_Jerar"] = $Parm["Id_Edu_Componente_Jerar"];
								//brians
								new Edu_Articulo_Det($Settings);
								DCExit("");	
					break;								
														
							
							
							
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "Edu_Componente_Crud":
					   
					   $Perfil_User = $_SESSION['Perfil_User'];
					   if($Perfil_User == 1 ){
						   
						   $this->ObjectDelete($Parm);
						   
					   }else{
						   
						    DCWrite(Message(" Para eliminar debe comunicarse con el administrador","E"));
							 
													   
					   }
			
 
						DCCloseModal();		
					    $Settings["interface"] = "y";
					    $Settings["key"] = $Parm["key"];
					    new Edu_Articulo_Det($Settings);
						DCExit("");							
						
						break;	
						
					case "Edu_Object":
						
						$this->ObjectDelete_Object($Parm);
						
						DCCloseModal();		
					    // $Settings["interface"] = "y";
					    // $Settings["key"] = $Parm["key"];
					    // new Edu_Articulo_Det($Settings);
						$Settings = array();
						$Settings['Url'] = "/sadministrator/articulo";
						$Settings['Screen'] = "ScreenRight";
						$Settings['Type_Send'] = "";
						DCRedirectJS($Settings);	
						
						DCExit("");							
						
						break;							
						
				}	
				
                break;
            case "VALIDATIONS":
                break;
            case "SEARCH":
                break;	
				
            case "MATRICULA":
						
						$this->Matricula($Parm);
						
						DCCloseModal();		
					    $Settings["interface"] = "y";
					    $Settings["key"] = $Parm["key"];
					    $Settings["Id_Edu_Componente_S"] = $Parm["Id_Edu_Componente_S"];
					    new Edu_Articulo_Det($Settings);
						DCExit("");		
						
                break;	

				
        }
		
		
		
        switch ($interface) {
            case "begin":
			 
				$action = $Parm["action"];
		        $_SESSION['action'] = $action;	
			
				$Settings["interface"] = "y";
				$Settings["key"] = $Parm["key"];
				$Settings["request"] = $Parm["request"];
				$Settings["Id_Edu_Componente_S"] = $Parm["Id_Edu_Componente_S"];

				new Edu_Articulo_Det($Settings);
				DCExit("");
				
				// $Settings = array();
				// $Settings['Url'] = "/sadministrator/edu_articulo_det/interface/y/key/".$Parm["key"];
				// $Settings['Screen'] = "ScreenRight";
				// $Settings['Type_Send'] = "";
				// DCRedirectJS($Settings);			
				
			break;
            case "y":
			
			    // DCVd($User);
				$LayoutB  = new LayoutB();
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
				
				if(empty($Entity)){
					
					$Query = "
					SELECT 
					ET.Id_Entity 
					FROM entity ET
					WHERE 
					ET.Url = :Url 
					";	
					$Where = ["Url" => $Parm["ie"]];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Id_Entity = $Row->Id_Entity;	
					
				    $_SESSION['Entity'] = $Id_Entity; 

				}				
				
				$Query = "
				    SELECT 
					EC.Id_Edu_Componente 
					, EC.Nombre 
					, EC.Contenido_Embebido 
					, EC.Imagen 
					, EC.Id_Edu_Formato 
					, EC.Orden 
					, EC.Descarga 
					FROM edu_componente EC
					WHERE EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" => $Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		        $NombreComponente = $Row->Nombre;				
		        $Contenido_Embebido = $Row->Contenido_Embebido;	
		        $Id_Edu_Componente_B = $Row->Id_Edu_Componente;	
		        $Orden = $Row->Orden;	
		        $Id_Edu_Formato = $Row->Id_Edu_Formato;	
		        $Imagen = $Row->Imagen;	
		        $Descarga = $Row->Descarga;	
			
                
                if(empty($Id_Edu_Componente_S)){
					
					$Query = "
						SELECT 
						EC.Id_Edu_Componente 
						, EC.Nombre 
						, EC.Imagen 
						, EC.Contenido_Embebido 
						, EC.Id_Edu_Formato 
						, EC.Orden 
						, EC.Descarga 
						FROM edu_componente EC
						WHERE 
						EC.Id_Edu_Almacen = :Id_Edu_Almacen AND EC.Introduccion = :Introduccion 
					";	
					$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Introduccion" => "SI" ];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);
					$NombreComponente = $Row->Nombre;	
		            $Contenido_Embebido = $Row->Contenido_Embebido;				
				    $Id_Edu_Componente_S = $Row->Id_Edu_Componente;	
		            $Id_Edu_Componente_B = $Row->Id_Edu_Componente;					
		            $Orden = $Row->Orden;			
		            $Id_Edu_Formato = $Row->Id_Edu_Formato;			
		            $Imagen = $Row->Imagen;			
		            $Descarga = $Row->Descarga;			
						
				}
				
				
				$Contenido_Embebido = trim($Contenido_Embebido);		
                $Parm["Id_Edu_Componente_S"]=$Id_Edu_Componente_S;
		     	$this->Cuenta_Vistas($Parm);
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;				
		        $Id_Edu_Area_Conocimiento = $Row_Producto->Id_Edu_Area_Conocimiento;				
		        $Id_Edu_Sub_Linea = $Row_Producto->Id_Edu_Sub_Linea;					
		        $Id_Edu_Tipo_Privacidad = $Row_Producto->Id_Edu_Tipo_Privacidad;					
				
				
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
                
				if($Id_Edu_Tipo_Privacidad == 1){
					
					if(empty($Id_Suscripcion) && !empty($_SESSION['action']) ){
						$btn = "<i class='zmdi zmdi-folder-star-alt zmdi-hc-lg'></i> Suscríbete ]" .$UrlFile."/interface/Matricula/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]animatedModal5]HXM]]btn btn-primary m-w-120}";
						$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);	
					}else{
						$btn = "<i class='zmdi zmdi-share zmdi-hc-lg'></i> Compartir ]" .$UrlFile."/interface/Compartir/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]animatedModal5]HXM]]btn btn-primary m-w-120}";
						$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);																		
					}
	            }
	

				$Query = "
				SELECT Count(*)  AS Tot FROM edu_vistas_objectos  EDV
				WHERE
				EDV.Id_Edu_Componente = :Id_Edu_Componente
				AND  EDV.Id_Edu_Almacen = :Id_Edu_Almacen
				";	
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen, "Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Tot_Vistas = $Row->Tot;
				
				$Query = "
						SELECT 
						Count(*)  AS Tot 
						FROM edu_componente EC
						WHERE 
						EC.Id_Edu_Almacen = :Id_Edu_Almacen
				";	
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Tot_Items = $Row->Tot;
				
				
				$Jscript = "
				           <script>
						   ResizeScreen('Screen_Content');
				           </script>
						   <style>
						    .botones1 {
								margin-top:0px;
							}
						    #Screen_Content_Btn .botones1 button {
								margin-right: -14px;
								margin-top: -5px;
								text-align: center;
							}
							
						   </style>
				           ";	
    						   
				$Dominio = DCUrl();
				$Dominio_url =  "https://yachai.org/sadministrator/archivos/docs/".$Imagen;
			
				if($Id_Edu_Formato == 2){

					if($Id_Edu_Tipo_Privacidad == 3){
                        
						if(!empty($User)){	
						
						    if($Descarga == "SI"){
								
								$btn = "<i class='zmdi zmdi-download zmdi-hc-fw'></i> Descargar Archivo.  ]" .$Dominio_url."]]HREF_DONWLOAD]]btn btn-primary m-w-120}";
								$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);
								
						    }
							
					    }	
					}			

					$ReproductorYT = "    
					<div style='position:relative;'>
							<div style='     background-color: #6b6464b8;
							top: 12px;
							height: 40px;
							position: absolute;
							right: 19px;
							width: 40px;'></div>

					";

					$ReproductorYT .= '
					                  
									  <iframe src="http://docs.google.com/viewer?url='.$Dominio_url.'&embedded=true" 
									  width="600" height="300" style="border: none;"></iframe>
					</div>				  
					';
								  
				}elseif($Id_Edu_Formato == 6){
					
					$ReproductorYT = $Contenido_Embebido;	
					$style = "style=margin-top:-19px;";
					
				}elseif($Id_Edu_Formato == 4){
					
					$ReproductorYT = '
					<video width="100%" height="100%" controls>
					<source src="'.$Contenido_Embebido.'" type="video/mp4">
					<source src="movie.ogg" type="video/ogg">
					Your browser does not support the video tag.
					</video>
					';		

				}else{
					
					$Contenido_Embebido = trim($Contenido_Embebido);
					$ReproductorYT =  	"
					
						<div id='ReproductorVideo'></div>
						<script>
							var vp = new ReproductorVideo({
								id      : 'ReproductorVideo',
								videoId : '".$Contenido_Embebido."'
							});
						</script>
					";
					
				}				
	   

				if( $Tot_Items != 0){
					$DCPanelTitle = DCPanelTitleB($NombreComponente," Visualizaciones  ".$Tot_Vistas."",$btn,"");
				}

				
				
				if($Id_Edu_Tipo_Privacidad == 3){
					
				    if(!empty($User)){	
					    $PanelA = "<div id='Screen_Content' ".$style.">".$ReproductorYT."</div>";
					}else{
						
						$btn_Iniciar_Sesion = " Iniciar Sesión ]/sadministrator/login/request/on/]_blank]HREF]]btn btn-primary m-w-120}";
						$btn_Iniciar_Sesion = DCButton($btn_Iniciar_Sesion, 'botones1', 'sys_form'.$Count);	
						
					    $PanelA = "<div id='Screen_Content' style='font-size: 1.5em;padding: 100px 10px;text-align: center;' >Tu sesión terminó!!,  debes iniciar Sesión 
						<br>
						<br>
						".$btn_Iniciar_Sesion."
						</div>";	
					}
					
				}else{
					    $PanelA = "<div id='Screen_Content' ".$style.">".$ReproductorYT."</div>";					
				}	
				
                $PanelA .= "<div id='Screen_Content_Btn' style='position: absolute;width: 100%;' >".$DCPanelTitle." </div>".$Jscript;
     
				$Nombre_Articulo = $Nombre_Articulo;	



				$User = $_SESSION['User'];
				
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
				
				

				$Query = "
					SELECT PE.Id_Perfil_Educacion
					FROM suscripcion SC
					INNER JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
					WHERE SC.Id_Suscripcion = :Id_Suscripcion
				";  
				
				$Where = ["Id_Suscripcion" =>$Id_Suscripcion];
			
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Perfil_Educacion = $Row->Id_Perfil_Educacion;

				$Perfil = Biblioteca::Valida_Perfil("");
				
				if($Id_Perfil_Educacion == 1 || $Id_Perfil_Educacion == 2  ){
					$Perfil = 777;
				}else{
					$Perfil = $Perfil;
				}
				
			    $btn_Menu = $this->MenuLocal($Id_Edu_Articulo,$Id_Edu_Almacen,$Id_Edu_Componente_S,$Perfil);
				
				$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]]]btn-simple-b}";
				$btn .= "<i class='zmdi zmdi-wrench'></i> Herramientas ]" .$UrlFile."/interface/Herramientas/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]]]btn-simple-d}";
				if(!empty($User)){				
				      $btn .= "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat <div id='alerta_chat'> </div>]" .$UrlFile."/interface/chat/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXMS]]btn-simple-d}";
				}
				$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				
                
                $PanelB = '
					<div class="messenger">
					  <div class="m-left-toolbar" style="padding: 0px 10px 0px 10px;">
						<div id="evaluador_indicador_chat" valor="si"></div>
						<ul style="text-align:left;">
						  <li style="padding-top:12px;">
							 '.$btn_componentes.'
						  </li>	
				
						</ul>
					  </div>				
				  </div>	';

				$PanelB .= $this->Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,"",$Id_Edu_Tipo_Privacidad);


				$Query = "        
					
					SELECT EC.Estado_Academico
					, EC.Estado_Edicion_Datos_Certificado
					, EC.Id_Tipo_Certificado 
					, EC.Id_Edu_Certificado 
					, EC.Pago_Total 
					, EC.Horas_Lectivas 
					
					FROM edu_certificado  EC
					INNER JOIN  suscripcion SCP ON SCP.Id_Suscripcion = EC.Id_Suscripcion
					INNER JOIN  user_miembro UM ON SCP.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
					WHERE EC.Id_Edu_Almacen = :Id_Edu_Almacen  AND UM.Id_User_Miembro = :Id_User_Miembro
				
				";
				$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen , "Id_User_Miembro" => $User ];
				
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Estado_Academico = $Row->Estado_Academico;
				$Id_Tipo_Certificado = $Row->Id_Tipo_Certificado;
				$Estado_Edicion_Datos_Certificado = $Row->Estado_Edicion_Datos_Certificado;
				$Id_Edu_Certificado = $Row->Id_Edu_Certificado;
				$Pago_Total = $Row->Pago_Total;
				$Horas_Lectivas = $Row->Horas_Lectivas;
				
	        	$UrlFile_Certificado = "/sadministrator/edu-certificado";	
		
				
				if(!empty($Horas_Lectivas)){
						
					if($Pago_Total == "Realizado" ){
						
						if($Estado_Academico == "aprobado" || $Estado_Academico == "participado" ){

							if($Estado_Edicion_Datos_Certificado == "Revisado" || $Estado_Edicion_Datos_Certificado == "Pendiente" ){
								
								$btn = "<i class='zmdi zmdi-info' style='padding-left:6px'></i>   Puedes Descargar aquí tu Certificado ]/sadministrator/edu-articulo-det/interface/Certificado_Alumno/key/".$Id_Edu_Almacen."/herramienta/certificado/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelA]HXMS]]btn-link-alert}";				
									
								$Button_Mensaje = DCButton($btn, 'botones1', 'sys_form_0998888');	
									$Mensaje_Alerta = '<div class="alert alert-success">
									  <strong> '.$Button_Mensaje.'
									</div>';
									
							}					
							
							
						}
					}
				}
				
				
				
				
				$Layout = array(array("PanelA","col-md-8",$PanelA),array("PanelB","col-md-4", $PanelB));
				$Content = DCLayout($Layout);
				
				$DCPanelTitle = DCPanelTitle($Nombre_Articulo," <span id=timer_text> </span> <input type=hidden id=timer> ",$btn_Menu,"");
				
				$Contenido = DCPageB($DCPanelTitle, $Content ,"panel panel-default");
				
				$Style = '
				    <style>
						.col-md-8 {
							padding: 0px;
						}
						.col-md-4 {
							padding: 0px;
						}
                        .botones1 a {
							margin-top: 0px; 
						}						
				    </style>
				';
				
				if($Parm["request"] == "on"){
					DCWrite($LayoutB->main($Mensaje_Alerta . $Contenido.$Style,$Row_Producto));
				}else{
					DCWrite($Mensaje_Alerta .$Contenido.$Style);			
				}
							
				DCExit();
				
                break;
				
				
        		
            case "chat":
				 
				// DCCloseModal();	
				$Id_Edu_Almacen = $Parm["Id_Edu_Almacen"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];	
				$Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];	
				$UrlFile_Chat = "/sadministrator/chat";			
				///////YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY
				                 // Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,$Id_Edu_Componente,$Id_Edu_Tipo_Privacidad) 
                // $PanelB = "<iframe src='".$UrlFile_Chat."/Id_Edu_Almacen/".$Id_Edu_Almacen."' width='100%' height='500' ></iframe>";
				
				
				$Query = "
				SELECT 
				UM.Nombre, UM.Email
				FROM user_miembro UM 
				WHERE 
				UM.Id_User_Miembro = :Id_User_Miembro   ";	
				$Where = ["Id_User_Miembro" =>$User ];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$NombreUser = $Row->Nombre;				
				$Email = $Row->Email;
				
				
				$PanelC = ' 
				
					<script>
						var ul_msj = document.getElementById("ul_msj");
						console.log("aaaaaaaaaaaaaaaaaaaaaaaaa");
							console.log(firebase.database().ref("ChatCurso/Chat-'.$Id_Edu_Almacen.'").limitToLast(30).orderByKey());
							
							
							firebase.database().ref("ChatCurso/Chat-'.$Id_Edu_Almacen.'").limitToLast(30).orderByKey().on("value", function(snapshot){		

								var html = "";
								$("#loadingChat").css({ "width":"100%"})
								
								snapshot.forEach(function(e){
									
							
									
									var element = e.val();
									var nombre = element.name;
									var msg = element.message;
									var fecha = element.fecha;
									var hora = element.hora;
									html += "<li style=\'background: #c2e3ff;list-style: none;padding: 5px 10px 5px 10px;margin: 10px 0px 10px 0px;width: 100%;\'><div>"+msg+"</div>";
								    html += "<div><span><b>"+nombre+"</b> </span><span style=\'font-size:0.85em;\'> | "+fecha+" "+hora+" </span> </div>";
			                        html += "</li>";
								});
								
								
								ul_msj.innerHTML = html;
								$("#chatPanel").animate({ scrollTop: $("#chatPanel").prop("scrollHeight")}, 30);
							});

					
					</script>
					
					';
						
				
				
				echo $PanelC;
				
						$PanelB = " 
						
						<div class='Contenedor-Chat'>

							<div class='Conversacion-Chat' id='chatPanel' style='overflow-x: hidden;' > 
								<div id='progressChat' class=\"progress\">
								  <div id='loadingChat' class=\"progress-bar progress-bar-striped 
								  progress-bar-animated\" role=\"progressbar\" aria-valuenow=\"25\" 
								  aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 10%;\">
								  </div>
								</div>
								
								<ul id='ul_msj' style='padding:0px;'>
								</ul>
							
							</div>			
							
							<div class='Mensaje-Chat'>
								
								<form id='contactForm' action='javascript:void(null);' enctype='multipart/form-data'> 
								<input type='hidden' id='programa' name='programa' value='Yachai_chat'>
								<input type='hidden' class='form-control' id='telefono'>
								<input type='hidden' class='form-control' id='email' value='{$Email}'>
								<input type='hidden' class='form-control' id='nombre' value='{$NombreUser}'>
								<input type='hidden' class='form-control' id='chat' value='{$Id_Edu_Almacen}'>
								
								<textarea name='Edu_Chat_Crud_Mensaje' id='mensaje' placeholder='Escribe campoo algo...'></textarea>
								";

						$PanelB .=' <button type="submit"  id="boton_chat"  onclick="boton_comentario(this);" 
									 class="zmdi zmdi-navigation zmdi-hc-fw boton_chat" 
									>
									
									</button>';						
						$PanelB .= " 
								</form>	
							</div>					
						</div>	
				

						";							
				
				$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]]]btn-simple-d}";
				$btn .= "<i class='zmdi zmdi-wrench'></i> Herramientas ]" .$UrlFile."/interface/Herramientas/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]]]btn-simple-d}";
				if(!empty($User)){				
				      $btn .= "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat <div id='alerta_chat'> </div>]" .$UrlFile."/interface/chat/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]]]btn-simple-b}";
				}
				$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				
                
                $PanelB2 = '
					<div class="messenger">
					  <div class="m-left-toolbar" style="padding: 0px 10px 0px 10px;">
						<div id="evaluador_indicador_chat" valor="si"></div>
						<ul style="text-align:left;">
						  <li style="padding-top:12px;">
							 '.$btn_componentes.'
						  </li>	
				
						</ul>
					  </div>				
				  </div>	';				
				
				DCWrite($PanelB2 . $PanelB);
				exit();	
				// echo $this->render(dirname(__FILE__).'/chat.phtml',$data);

                break;
        		
            case "Elementos_SI":
				
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];	
				$Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];	
			
				/////////YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY
				                 // Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,$Id_Edu_Componente,$Id_Edu_Tipo_Privacidad) 
                $PanelB = $this->Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,$Id_Edu_Componente_Jerar,"");			
			    DCWrite($PanelB);				
                break;

				
            case "Elementos_B":
			
                DCCloseModal();		
				
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];	

				$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]]]btn-simple-b}";
				$btn .= "<i class='zmdi zmdi-wrench'></i> Herramientas ]" .$UrlFile."/interface/Herramientas/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]]]btn-simple-d}";
				if(!empty($User)){				
				      $btn .= "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat <div id='alerta_chat'> </div>]" .$UrlFile."/interface/chat/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXMS]]btn-simple-d}";
				}
				
				$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				
                
                $PanelB2 = '
					<div class="messenger">
					  <div class="m-left-toolbar" style="padding: 0px 10px 0px 10px;">
						<div id="evaluador_indicador_chat" valor="si"></div>
						<ul style="text-align:left;">
						  <li style="padding-top:12px;">
							 '.$btn_componentes.'
						  </li>	
				
						</ul>
					  </div>				
				  </div>	';	
				
				$PanelB .= $this->Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,"","");	


				
			    DCWrite($PanelB2 . $PanelB);	
                break;

				
            case "Herramientas":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];	

				
			    $Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;				
		        $Id_Edu_Area_Conocimiento = $Row_Producto->Id_Edu_Area_Conocimiento;				
		        $Id_Edu_Sub_Linea = $Row_Producto->Id_Edu_Sub_Linea;					
		        $Id_Edu_Tipo_Privacidad = $Row_Producto->Id_Edu_Tipo_Privacidad;	


				$User = $_SESSION['User'];
				
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
				
				

				$Query = "
					SELECT PE.Id_Perfil_Educacion
					FROM suscripcion SC
					INNER JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
					WHERE SC.Id_Suscripcion = :Id_Suscripcion
				";  
				
				$Where = ["Id_Suscripcion" =>$Id_Suscripcion];
			
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Perfil_Educacion = $Row->Id_Perfil_Educacion;

				$Perfil = Biblioteca::Valida_Perfil("");
				
				if($Id_Perfil_Educacion == 1 || $Id_Perfil_Educacion == 2  ){
					$Perfil = 777;
				}else{
					$Perfil = $Perfil;
				}				
				
				 
							
				
				$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]]]btn-simple-d}";
				$btn .= "<i class='zmdi zmdi-wrench'></i> Herramientas ]" .$UrlFile."/interface/Herramientas/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]]]btn-simple-b}";
				if(!empty($User)){				
				      $btn .= "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat <div id='alerta_chat'> </div>]" .$UrlFile."/interface/chat/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXMS]]btn-simple-d}";
				}
				$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				
                
                $PanelB = '
					<div class="messenger">
					  <div class="m-left-toolbar" style="padding: 0px 10px 0px 10px;">
						<div id="evaluador_indicador_chat" valor="si"></div>
						<ul style="text-align:left;">
						  <li style="padding-top:12px;">
							 '.$btn_componentes.'
						  </li>	
				
						</ul>
					  </div>				
				  </div>	';

 
				  
				$PanelB .= $this->Herramientas_Control($UrlFile,$Id_Edu_Almacen,$Id_Edu_Componente_S);				
				
			    $PanelA = " ";
				
			
				$Layout = array(array("PanelA","col-md-8",$PanelA),array("PanelB","col-md-4", $PanelB));
				$Content = DCLayout($Layout);

				$btn_Menu = $this->MenuLocal($Id_Edu_Articulo,$Id_Edu_Almacen,$Id_Edu_Componente_S,$Perfil);
				
				$DCPanelTitle = DCPanelTitle($Nombre_Articulo," <span id=timer_text> </span> <input type=hidden id=timer> ",$btn_Menu,"");
				
				$Contenido = DCPageB($DCPanelTitle, $Content ,"panel panel-default");	
				
				$Style = '
				    <style>
						.col-md-8 {
							padding: 0px;
						}
						.col-md-4 {
							padding: 0px;
						}
                        .botones1 a {
							margin-top: 0px; 
						}						
				    </style>
				';
					
					
				if($Parm["request"] == "on"){
                   
	                $LayoutB  = new LayoutB();
					DCWrite($LayoutB->main($Contenido . $Style));
				}else{
					DCWrite($Contenido . $Style );			
				}

				
                break;						
				
				

            case "Certificado_Alumno":
		
				DCCloseModal();	
						
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
				
				
			    $Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;				
		        $Id_Edu_Area_Conocimiento = $Row_Producto->Id_Edu_Area_Conocimiento;				
		        $Id_Edu_Sub_Linea = $Row_Producto->Id_Edu_Sub_Linea;					
		        $Id_Edu_Tipo_Privacidad = $Row_Producto->Id_Edu_Tipo_Privacidad;	
		        $Usar_Acta_Nota = $Row_Producto->Usar_Acta_Nota;	



				$User = $_SESSION['User'];
				
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


				$Query = "
					SELECT PE.Id_Perfil_Educacion
					FROM suscripcion SC
					INNER JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
					WHERE SC.Id_Suscripcion = :Id_Suscripcion
				";  
				
				$Where = ["Id_Suscripcion" =>$Id_Suscripcion];
			
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Perfil_Educacion = $Row->Id_Perfil_Educacion;

				$Perfil = Biblioteca::Valida_Perfil("");
				
				if($Id_Perfil_Educacion == 1 || $Id_Perfil_Educacion == 2  ){
					$Perfil = 777;
				}else{
					$Perfil = $Perfil;
				}				
							
				
				 
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
				$Row1 = ClassPdo::DCRows($Query,$Where,$Conection);					
				
				
				
	
				$Query ="
					SELECT 
					EOE.Id_Edu_Objeto_Evaluativo
					,EOE.Nombre
					,EAE.Id_Edu_Aspecto_Evaluativo
					,EAE.Nombre as Aspecto 
					from edu_objeto_evaluativo_detalle EOED
					INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo=EOE.Id_Edu_Objeto_Evaluativo
					INNER JOIN edu_aspecto_evaluativo EAE ON EOE.Id_Edu_Aspecto_Evaluativo=EAE.Id_Edu_Aspecto_Evaluativo
					INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo=EA.Id_Edu_Articulo
					INNER JOIN edu_componente EC ON EOED.Id_Edu_Componente = EC.Id_Edu_Componente
					where 
					EOED.Entity=:Entity 
					AND EA.Id_Edu_Almacen=:Id_Edu_Almacen 
					AND EOE.Incluir_Acta=:Incluir_Acta   
					GROUP BY EAE.Id_Edu_Aspecto_Evaluativo
					ORDER BY EAE.Id_Edu_Aspecto_Evaluativo ASC
				"; 
				$Where = ["Entity"=>$Entity,"Id_Edu_Almacen"=>$Id_Edu_Almacen,"Incluir_Acta"=>"SI"];
				$Row2 = ClassPdo::DCRows($Query,$Where,$Conection);	
				$Fila = "<table class='table table-hover'>";	
				
				$SumaNota = 0;			
				$Count_Finalizacion = 0;			
				$Count_registros = 0;			
				
				foreach ($Row2 as $field1) {
					
					$Count_registros += 1;
					
					$Id_Edu_Aspecto_Evaluativo = $field1->Id_Edu_Aspecto_Evaluativo;

					
					$Fila .= "<tr style='color:#2b92e9;'><th>".$field1->Aspecto." </th><th> Finalizado </th> <th> Nota </th></tr>";

							$Query ="
							
								SELECT 
								EOE.Id_Edu_Objeto_Evaluativo
								,EOED.Id_Edu_Componente
								,EOE.Nombre AS Objeto_Evaluativo
								,EAE.Nombre as Aspecto 
								from edu_objeto_evaluativo_detalle EOED
								INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
								INNER JOIN edu_aspecto_evaluativo EAE ON EOE.Id_Edu_Aspecto_Evaluativo=EAE.Id_Edu_Aspecto_Evaluativo
								INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo=EA.Id_Edu_Articulo
								INNER JOIN edu_componente EC ON EOED.Id_Edu_Componente = EC.Id_Edu_Componente
								where 
								EOED.Entity=:Entity 
								AND EA.Id_Edu_Almacen =:Id_Edu_Almacen 
								AND EOE.Incluir_Acta =:Incluir_Acta   
								AND EAE.Id_Edu_Aspecto_Evaluativo =:Id_Edu_Aspecto_Evaluativo   
								GROUP BY EOE.Id_Edu_Objeto_Evaluativo
								ORDER BY EAE.Id_Edu_Aspecto_Evaluativo ASC
								
							"; 
							$Where = ["Entity"=>$Entity,"Id_Edu_Almacen"=>$Id_Edu_Almacen,"Incluir_Acta"=>"SI","Id_Edu_Aspecto_Evaluativo"=>$Id_Edu_Aspecto_Evaluativo];
							
							// var_dump($Where);	
							
							$Row3 = ClassPdo::DCRows($Query,$Where,$Conection);	
				
							foreach ($Row3 as $field2) {
								
								
									$Query = "
									SELECT 
									EOED.Nota 
									,EOED.Incluir_En_Certificacion 
									FROM edu_evaluacion_desarrollo_cab EOED	
									WHERE 
									EOED.Id_Suscripcion = :Id_Suscripcion
									AND EOED.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
									ORDER BY EOED.Id_Edu_Evaluacion_Desarrollo_Cab ASC  
									";	
									$Where = ["Id_Suscripcion" =>$Id_Suscripcion, "Id_Edu_Objeto_Evaluativo" => $field2->Id_Edu_Objeto_Evaluativo ];
									
									// var_dump($Where);	
									$Row1 = ClassPdo::DCRow($Query,$Where,$Conection);	
									
									
									if(empty($Row1)){
										$Nota = "Está pendiente el desarrollo de esta evaluación";
									}
									
									if(!empty($Row1)){
										$Nota = $Row1->Nota;
									}
								   
									
									if($Row1->Incluir_En_Certificacion == "SI"){
										$Count_Finalizacion += 1;
									}
									
									if(empty($Row1->Incluir_En_Certificacion)){
										$Incluir_En_Certificacion = "<p style='color:red;font-weight:bold;'> Ingresa aquí para confirmar que has finalizado la evaluación </p> ";
									}else{
										$Incluir_En_Certificacion = $Row1->Incluir_En_Certificacion;										
									}
																		
									// /sadministrator/edu-desarrollo-examen/interface/Crea_Objeto_Evaluativo/key/1211/Id_Edu_Componente_S/7166/Id_Edu_Componente/7166									

									
									$Atributo = ' type_send="HXM" onclick="LoadPage(this);" direction="/sadministrator/edu-desarrollo-examen/interface/Crea_Objeto_Evaluativo/key/'.$Id_Edu_Almacen.'/Id_Edu_Componente_S/'.$field2->Id_Edu_Componente.'/Id_Edu_Componente/'.$field2->Id_Edu_Componente.'" screen="PanelA" class="SinEstilo" data-style="expand-right" id="item_certificado_'.$field2->Id_Edu_Objeto_Evaluativo.'" ';
									
									$Fila .= "
									<tr ".$Atributo.">
									
									
									<td>".$field2->Objeto_Evaluativo."</td>
									
									<td>".$Incluir_En_Certificacion."</td>
									
									<td>".$Nota." </td>
									</tr>";
									
									$SumaNota += $Row1->Nota;	
									$CountNotas += 1;	
							}
				}
				
			
			
				if($Count_registros !== 0){
					
					$Fila .= "
							<tr style='background-color:#a9c5ef;'>
							<td> <b> Nota Final </b></td>
							<td colspan='2' style='text-align:center;' >".round(( $SumaNota / $CountNotas ),2)." </td>
							</tr>";	
							
				}else{
					
					
					 
					
					
					        $Fila .= "<tr style='color:#2b92e9;'><th>EVALUACIONES</th><th> Finalizado </th> <th> Nota </th></tr>";
						   
							$Query ="
							
								SELECT 
								EOE.Id_Edu_Objeto_Evaluativo
								,EOED.Id_Edu_Componente
								,EOE.Nombre AS Objeto_Evaluativo

								from edu_objeto_evaluativo_detalle EOED
								INNER JOIN edu_objeto_evaluativo EOE ON EOED.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
								INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo=EA.Id_Edu_Articulo
								INNER JOIN edu_componente EC ON EOED.Id_Edu_Componente = EC.Id_Edu_Componente
								where 
								EOED.Entity=:Entity 
								AND EA.Id_Edu_Almacen =:Id_Edu_Almacen 
							  
								GROUP BY EOE.Id_Edu_Objeto_Evaluativo
								ORDER BY EOE.Id_Edu_Objeto_Evaluativo ASC
								
							"; 
							$Where = ["Entity"=>$Entity,"Id_Edu_Almacen"=>$Id_Edu_Almacen];
							
						
							
							$Row3 = ClassPdo::DCRows($Query,$Where,$Conection);						
							foreach ($Row3 as $field2) {		

								
									$Query = "
									SELECT 
									EOED.Nota 
									,EOED.Incluir_En_Certificacion 
									FROM edu_evaluacion_desarrollo_cab EOED	
									WHERE 
									EOED.Id_Suscripcion = :Id_Suscripcion
									AND EOED.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo
									ORDER BY EOED.Id_Edu_Evaluacion_Desarrollo_Cab ASC  
									";	
									$Where = ["Id_Suscripcion" =>$Id_Suscripcion, "Id_Edu_Objeto_Evaluativo" => $field2->Id_Edu_Objeto_Evaluativo ];
									
									// var_dump($Where);	
									$Row1 = ClassPdo::DCRow($Query,$Where,$Conection);	
									
									if(empty($Row1)){
										$Nota = "Está pendiente el desarrollo de esta evaluación";
									}
									
									if(!empty($Row1)){
										$Nota = $Row1->Nota;
									}		


									if(empty($Row1->Incluir_En_Certificacion)){
										$Incluir_En_Certificacion = "<p style='color:red;font-weight:bold;'> Ingresa aquí para confirmar que has finalizado la evaluación </p> ";
									}else{
										$Incluir_En_Certificacion = $Row1->Incluir_En_Certificacion;										
									}		


									$Atributo = ' type_send="HXM" onclick="LoadPage(this);" direction="/sadministrator/edu-desarrollo-examen/interface/Crea_Objeto_Evaluativo/key/'.$Id_Edu_Almacen.'/Id_Edu_Componente_S/'.$field2->Id_Edu_Componente.'/Id_Edu_Componente/'.$field2->Id_Edu_Componente.'" screen="PanelA" class="SinEstilo" data-style="expand-right" id="item_certificado_'.$field2->Id_Edu_Objeto_Evaluativo.'" ';
										

                                    if($Row1->Incluir_En_Certificacion == "SI"){
										$Count_Finalizacion += 1;
									}										
					
					               	$SumaNota += $Row1->Nota;	
									$CountNotas += 1;		
					
									$Fila .= "
									<tr ".$Atributo.">
									
									
									<td>".$field2->Objeto_Evaluativo."</td>
									
									<td>".$Incluir_En_Certificacion."</td>
									
									<td>".$Nota." </td>
									</tr>";					
					
					        }
							
							$Fila .= "
									<tr style='background-color:#a9c5ef;'>
									<td> <b> Nota Final </b></td>
									<td colspan='2' style='text-align:center;' >".round(( $SumaNota / $CountNotas ),2)." </td>
									</tr>";	
							
					
					
					
					
					
				}			
				
				
				
				
				$Fila .= "</table>";




				$Query = "        
					
					SELECT EC.Estado_Academico
					, EC.Estado_Edicion_Datos_Certificado
					, EC.Id_Tipo_Certificado 
					, EC.Id_Edu_Certificado 
					, EC.Pago_Total 
					, EC.Horas_Lectivas 
					
					FROM edu_certificado  EC
					INNER JOIN  suscripcion SCP ON SCP.Id_Suscripcion = EC.Id_Suscripcion
					INNER JOIN  user_miembro UM ON SCP.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
					WHERE EC.Id_Edu_Almacen = :Id_Edu_Almacen  AND UM.Id_User_Miembro = :Id_User_Miembro
				
				";
				$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen , "Id_User_Miembro" => $User ];
				
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Estado_Academico = $Row->Estado_Academico;
				$Id_Tipo_Certificado = $Row->Id_Tipo_Certificado;
				$Estado_Edicion_Datos_Certificado = $Row->Estado_Edicion_Datos_Certificado;
				$Id_Edu_Certificado = $Row->Id_Edu_Certificado;
				$Pago_Total = $Row->Pago_Total;
				$Horas_Lectivas = $Row->Horas_Lectivas;
				
	        	$UrlFile_Certificado = "/sadministrator/edu-certificado";	
		
				
			    if($Usar_Acta_Nota == "SI"){
					
						if($Count_Finalizacion == $CountNotas ){
							
							if(!empty($Horas_Lectivas)){
									
								if($Pago_Total == "Realizado" ){
									
									if($Estado_Academico == "aprobado" || $Estado_Academico == "participado" ){
										
									
										if($Estado_Edicion_Datos_Certificado == "Pendiente" ){
											
											$btn = " Descarga aquí tu certificado ]" .$UrlFile_Certificado ."/interface/Create_Edit_Certificado/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/curso]animatedModal5]HXM]]}";				
												
											$Button_Mensaje = DCButton($btn, 'botones1', 'sys_form_0998888EEE');	
												$Mensaje_Alerta = '<div class="alert alert-success" style="padding:10px;">
												  <strong> COMUNICADO: </strong> Ya puedes descargar tu certificado digital.<br><br> '.$Button_Mensaje.'
												</div>';

										}
										
										
										if($Estado_Edicion_Datos_Certificado == "Revisado" ){
										
											$btn = " Descargar el certificado ]/sadministrator/edu-certificado/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/interface/List/request/on/tipo-producto/curso]animatedModal5]HREF_DONWLOAD]btn btn-primary ladda-button}";

											$btn = DCButton($btn, 'botones1', 'sys_form_990988');	
											$DCPanelTitle = DCPanelTitle("VISUALIZA Y DESCARGA TU CERTIFICADO ","",$btn);
												
											$Mensaje_Alerta .= $DCPanelTitle."<br><iframe width='100%' height='600px' src='/sadministrator/edu-certificado/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/interface/List/tipo-producto/curso/request/on/'></iframe>";
																
										}	
										

									}
									
								}else{
									
										$Mensaje_Alerta = '<div class="alert alert-success" style="padding:10px;">
												  <strong> COMUNICADO: </strong> Ya realizaste todas las evaluaciones, comúnicate con la coordinadora para que habilite la descarga del certificado '.$Button_Mensaje.'
												</div>';
								}
							}
							
						}else{
							
										$Mensaje_Alerta = '<div class="alert alert-success" style="padding:10px;">
												  <strong> COMUNICADO: </strong> Desbes confirmar la finalización de las evaluaciones, para iniciar la gestión del certificado.
												</div>';					
							
						}	
						
				}		
				
				
								
                $Fila .= $Mensaje_Alerta;				

				$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]]]btn-simple-d}";
				$btn .= "<i class='zmdi zmdi-wrench'></i> Herramientas ]" .$UrlFile."/interface/Herramientas/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]]]btn-simple-b}";
				if(!empty($User)){				
				      $btn .= "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat <div id='alerta_chat'> </div>]" .$UrlFile."/interface/chat/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXMS]]btn-simple-d}";
				}
				$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				
                
                $PanelB = '
					<div class="messenger">
					  <div class="m-left-toolbar" style="padding: 0px 10px 0px 10px;">
						<div id="evaluador_indicador_chat" valor="si"></div>
						<ul style="text-align:left;">
						
							<li style="padding-top:12px;">
								 '.$btn_componentes.'
							</li>	
				
						</ul>
					  </div>				
				  </div>	';

				
				$PanelB .= $this->Herramientas_Control($UrlFile,$Id_Edu_Almacen,$Id_Edu_Componente_S);
				
				
				$DCPanelTitleL = DCPanelTitle(" <i class='zmdi zmdi-graduation-cap' style='font-size: 2em;padding: 0px 10px 0px 0px;color: #2b92e9;'></i> <b>Certficado</b> " ," ","","");				
			    
				$PanelA = $DCPanelTitleL . "<div style='padding:0px 20px 0px 20px;'>".$Fila."</div>";
				
				$Layout = array(array("PanelA","col-md-8",$PanelA),array("PanelB","col-md-4",$PanelB));
				$Content = DCLayout($Layout);
				
			    $btn_Menu = $this->MenuLocal($Id_Edu_Articulo,$Id_Edu_Almacen,$Id_Edu_Componente_S,$Perfil);
				$DCPanelTitle = DCPanelTitle($Nombre_Articulo . $Id_Suscripcion ," <span id=timer_text> </span> <input type=hidden id=timer> ",$btn_Menu,"");
				
				$Contenido = DCPageB($DCPanelTitle, $Content ,"panel panel-default");
				
				$Style = '
				    <style>
						.col-md-8 {
							padding: 0px;
						}
						.col-md-4 {
							padding: 0px;
						}
                        .botones1 a {
							margin-top: 0px; 
						}
                        #PanelA{
							background-color:#d4d4d4;
						}						
				    </style>
				';				

				$LayoutB  = new LayoutB();				
				if($Parm["request"] == "on"){
					DCWrite($LayoutB->main( $Contenido . $Style));
				}else{
					DCWrite($PanelA . $Style);			
				}
				exit();
				
                break;	
		

            case "analisis":
		
				DCCloseModal();	
						
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
				
				
			    $Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;				
		        $Id_Edu_Area_Conocimiento = $Row_Producto->Id_Edu_Area_Conocimiento;				
		        $Id_Edu_Sub_Linea = $Row_Producto->Id_Edu_Sub_Linea;					
		        $Id_Edu_Tipo_Privacidad = $Row_Producto->Id_Edu_Tipo_Privacidad;	



				$User = $_SESSION['User'];
				
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


				$Query = "
					SELECT PE.Id_Perfil_Educacion
					FROM suscripcion SC
					INNER JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
					WHERE SC.Id_Suscripcion = :Id_Suscripcion
				";  
				
				$Where = ["Id_Suscripcion" =>$Id_Suscripcion];
			
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Perfil_Educacion = $Row->Id_Perfil_Educacion;

				$Perfil = Biblioteca::Valida_Perfil("");
				
				if($Id_Perfil_Educacion == 1 || $Id_Perfil_Educacion == 2  ){
					$Perfil = 777;
				}else{
					$Perfil = $Perfil;
				}		

				
							
				$Query = "
					SELECT EVIO.Id_Edu_Componente AS CodigoLink, EC.Nombre AS Componente
					, COUNT(EVIO.Id_Edu_Componente) AS 'Total <br> Interacciones' 
					FROM edu_vistas_objectos EVIO
					INNER JOIN edu_componente EC ON EC.Id_Edu_Componente = EVIO.Id_Edu_Componente
					WHERE 
					EVIO.Id_Edu_Almacen = :Id_Edu_Almacen  
					AND EVIO.Id_User = :Id_User
					GROUP BY EVIO.Id_Edu_Componente
					ORDER BY COUNT(EVIO.Id_Edu_Componente) DESC
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Componente';
				$Link = $UrlFile."/interface/Detalle_Analisis_Concurrencia/key/".$key;
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_User"=>$User];
				
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');						
				
				$DCPanelTitleL = DCPanelTitle(" <i class='zmdi zmdi-mouse' style='font-size: 2em;padding: 0px 10px 0px 0px;color: #2b92e9;'></i> <b>INTERACCIONES</b>" ," ","","");				

				$FilaA1 =  $DCPanelTitleL . $Listado;
                $Fila =  "<div style='padding:0px 20px 0px 20px;'>".$FilaA1."</div>";
				
				
						
				$Query = "
					SELECT  
					SUBSTRING(IP.Date_Time_Creation,1,10) AS CodigoLink,
					DATE_FORMAT(IP.Date_Time_Creation, '%M %d %Y') AS Fecha
					FROM 
					suscripcion SCP 
					INNER JOIN edu_ingreso_plataforma IP ON IP.Id_User_Creation = SCP.Id_User
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = SCP.Id_User
					WHERE 
					SCP.Id_Edu_Almacen = :Id_Edu_Almacen AND SCP.Id_User = :Id_User
					GROUP BY DATE_FORMAT(IP.Date_Time_Creation, '%M %d %Y')
					ORDER BY DATE_FORMAT(IP.Date_Time_Creation, '%M %d %Y')
					DESC
				";    
				$Class = 'table table-hover';
				$LinkId = 'Date_Time_Creation';
				$Link = $UrlFile."/interface/Ingreso_Aula_Det/key/".$Id_Edu_Almacen;
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_User" => $User];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');							
				
				$DCPanelTitleL2 = DCPanelTitle(" <i class='zmdi zmdi-mouse' style='font-size: 2em;padding: 0px 10px 0px 0px;color: #2b92e9;'></i> <b>CONCURRENCIA</b>" ," ","","");				
				
				
				$FilaA =  $DCPanelTitleL2 . $Listado;
                $Fila .=  "<div style='padding:0px 20px 0px 20px;'>".$FilaA."</div>";

				$Query = "        
					
					SELECT EC.Estado_Academico
					, EC.Estado_Edicion_Datos_Certificado
					, EC.Id_Tipo_Certificado 
					, EC.Id_Edu_Certificado 
					, EC.Pago_Total 
					, EC.Horas_Lectivas 
					
					FROM edu_certificado  EC
					INNER JOIN  suscripcion SCP ON SCP.Id_Suscripcion = EC.Id_Suscripcion
					INNER JOIN  user_miembro UM ON SCP.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
					WHERE EC.Id_Edu_Almacen = :Id_Edu_Almacen  AND UM.Id_User_Miembro = :Id_User_Miembro
				
				";
				$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen , "Id_User_Miembro" => $User ];
				
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Estado_Academico = $Row->Estado_Academico;
				$Id_Tipo_Certificado = $Row->Id_Tipo_Certificado;
				$Estado_Edicion_Datos_Certificado = $Row->Estado_Edicion_Datos_Certificado;
				$Id_Edu_Certificado = $Row->Id_Edu_Certificado;
				$Pago_Total = $Row->Pago_Total;
				$Horas_Lectivas = $Row->Horas_Lectivas;
				
	        	$UrlFile_Certificado = "/sadministrator/edu-certificado";	
		
		
				$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]]]btn-simple-d}";
				$btn .= "<i class='zmdi zmdi-wrench'></i> Herramientas ]" .$UrlFile."/interface/Herramientas/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]]]btn-simple-b}";
				if(!empty($User)){				
				      $btn .= "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat <div id='alerta_chat'> </div>]" .$UrlFile."/interface/chat/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXMS]]btn-simple-d}";
				}
				$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				
                
                $PanelB = '
					<div class="messenger">
					  <div class="m-left-toolbar" style="padding: 0px 10px 0px 10px;">
						<div id="evaluador_indicador_chat" valor="si"></div>
						<ul style="text-align:left;">
						
							<li style="padding-top:12px;">
								 '.$btn_componentes.'
							</li>	
				
						</ul>
					  </div>				
				  </div>	';

				
				$PanelB .= $this->Herramientas_Control($UrlFile,$Id_Edu_Almacen,$Id_Edu_Componente_S);
				
				
	            $PanelA =  $Fila;				
				
				$Layout = array(array("PanelA","col-md-8",$PanelA),array("PanelB","col-md-4",$PanelB));
				$Content = DCLayout($Layout);
				
			    $btn_Menu = $this->MenuLocal($Id_Edu_Articulo,$Id_Edu_Almacen,$Id_Edu_Componente_S,$Perfil);
				$DCPanelTitle = DCPanelTitle($Nombre_Articulo . $Id_Suscripcion ," <span id=timer_text> </span> <input type=hidden id=timer> ",$btn_Menu,"");
				
				$Contenido = DCPageB($DCPanelTitle, $Content ,"panel panel-default");
				
				$Style = '
				    <style>
						.col-md-8 {
							padding: 0px;
						}
						.col-md-4 {
							padding: 0px;
						}
                        .botones1 a {
							margin-top: 0px; 
						}
                        #PanelA{
							background-color:#d4d4d4;
						}						
				    </style>
				';				

				$LayoutB  = new LayoutB();				
				if($Parm["request"] == "on"){
					DCWrite($LayoutB->main( $Contenido . $Style));
				}else{
					DCWrite($PanelA . $Style);			
				}
				exit();
				
                break;	

				
            case "accesos_configuracion":
		
				DCCloseModal();	
				$DCDate = DCDate();		
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
				
				
			    $Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;				
		        $Id_Edu_Area_Conocimiento = $Row_Producto->Id_Edu_Area_Conocimiento;				
		        $Id_Edu_Sub_Linea = $Row_Producto->Id_Edu_Sub_Linea;					
		        $Id_Edu_Tipo_Privacidad = $Row_Producto->Id_Edu_Tipo_Privacidad;	



				$User = $_SESSION['User'];
				
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


				$Query = "
					SELECT 
					PE.Id_Perfil_Educacion
					, SC.Fecha_Inicio
					, SC.Fecha_Fin
					FROM suscripcion SC
					INNER JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
					WHERE SC.Id_Suscripcion = :Id_Suscripcion
				";  
				
				$Where = ["Id_Suscripcion" =>$Id_Suscripcion];
			
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Perfil_Educacion = $Row->Id_Perfil_Educacion;
				$Fecha_Inicio = $Row->Fecha_Inicio;
				$Fecha_Fin = $Row->Fecha_Fin;

				$Perfil = Biblioteca::Valida_Perfil("");
				
				if($Id_Perfil_Educacion == 1 || $Id_Perfil_Educacion == 2  ){
					$Perfil = 777;
				}else{
					$Perfil = $Perfil;
				}		

				
				$date1 = new DateTime($DCDate);
				$date2 = new DateTime($Fecha_Fin);
				$diff = $date1->diff($date2);
				// will output 2 days
				// echo $diff->days . ' days ';				
								
				
				
				$Table = "<table class='table table-hover'>";
				
				$Table .= "<tr>";
					$Table .= "<th>Fecha Inicio</th>";
					$Table .= "<th>Fecha Limite de acceso</th>";
				$Table .= "</tr>";
				
				$Table .= "<tr>";
					
					$Table .= "<td>".$Fecha_Inicio."</td>";
					$Table .= "<td>".$Fecha_Fin."</td>";
				
				$Table .= "</tr>";				
				
				$Table .= "</table>";
				
				
				$Table .= "<table class='table table-hover'>";
				
				$Table .= "<tr>";
					$Table .= "<th>Dias Restantes</th>";
				$Table .= "</tr>";
				
				$Table .= "<tr>";

					$Table .= "<td>".$diff->days."</td>";
				
				$Table .= "</tr>";				
				
				$Table .= "</table>";				
				

				$Listado = 	$Table;			
				
				$DCPanelTitleL = DCPanelTitle(" <i class='zmdi zmdi-timer' style='font-size: 2em;padding: 0px 10px 0px 0px;color: #2b92e9;'></i> <b>TIEMPOS DE ACCESOS</b>" ," ","","");				

				$FilaA1 =  $DCPanelTitleL . $Listado;
                $Fila =  "<div style='padding:0px 20px 0px 20px;'>".$FilaA1."</div>";
				

				$Query = "        
					
					SELECT EC.Estado_Academico
					, EC.Estado_Edicion_Datos_Certificado
					, EC.Id_Tipo_Certificado 
					, EC.Id_Edu_Certificado 
					, EC.Pago_Total 
					, EC.Horas_Lectivas 
					
					FROM edu_certificado  EC
					INNER JOIN  suscripcion SCP ON SCP.Id_Suscripcion = EC.Id_Suscripcion
					INNER JOIN  user_miembro UM ON SCP.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
					WHERE EC.Id_Edu_Almacen = :Id_Edu_Almacen  AND UM.Id_User_Miembro = :Id_User_Miembro
				
				";
				$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen , "Id_User_Miembro" => $User ];
				
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Estado_Academico = $Row->Estado_Academico;
				$Id_Tipo_Certificado = $Row->Id_Tipo_Certificado;
				$Estado_Edicion_Datos_Certificado = $Row->Estado_Edicion_Datos_Certificado;
				$Id_Edu_Certificado = $Row->Id_Edu_Certificado;
				$Pago_Total = $Row->Pago_Total;
				$Horas_Lectivas = $Row->Horas_Lectivas;
				
	        	$UrlFile_Certificado = "/sadministrator/edu-certificado";	
		
		
				$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]]]btn-simple-d}";
				$btn .= "<i class='zmdi zmdi-wrench'></i> Herramientas ]" .$UrlFile."/interface/Herramientas/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]]]btn-simple-b}";
				if(!empty($User)){				
				      $btn .= "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat <div id='alerta_chat'> </div>]" .$UrlFile."/interface/chat/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXMS]]btn-simple-d}";
				}
				$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				
                
                $PanelB = '
					<div class="messenger">
					  <div class="m-left-toolbar" style="padding: 0px 10px 0px 10px;">
						<div id="evaluador_indicador_chat" valor="si"></div>
						<ul style="text-align:left;">
						
							<li style="padding-top:12px;">
								 '.$btn_componentes.'
							</li>	
				
						</ul>
					  </div>				
				  </div>	';

				
				$PanelB .= $this->Herramientas_Control($UrlFile,$Id_Edu_Almacen,$Id_Edu_Componente_S);
				
				
	            $PanelA =  $Fila;				
				
				$Layout = array(array("PanelA","col-md-8",$PanelA),array("PanelB","col-md-4",$PanelB));
				$Content = DCLayout($Layout);
				
			    $btn_Menu = $this->MenuLocal($Id_Edu_Articulo,$Id_Edu_Almacen,$Id_Edu_Componente_S,$Perfil);
				$DCPanelTitle = DCPanelTitle($Nombre_Articulo . $Id_Suscripcion ," <span id=timer_text> </span> <input type=hidden id=timer> ",$btn_Menu,"");
				
				$Contenido = DCPageB($DCPanelTitle, $Content ,"panel panel-default");
				
				$Style = '
				    <style>
						.col-md-8 {
							padding: 0px;
						}
						.col-md-4 {
							padding: 0px;
						}
                        .botones1 a {
							margin-top: 0px; 
						}
                        #PanelA{
							background-color:#d4d4d4;
						}						
				    </style>
				';				

				$LayoutB  = new LayoutB();				
				if($Parm["request"] == "on"){
					DCWrite($LayoutB->main( $Contenido . $Style));
				}else{
					DCWrite($PanelA . $Style);			
				}
				exit();
				
                break;	
				
				
				
				
				
				
            case "Elementos":
		
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
				
				
			    $Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;				
		        $Id_Edu_Area_Conocimiento = $Row_Producto->Id_Edu_Area_Conocimiento;				
		        $Id_Edu_Sub_Linea = $Row_Producto->Id_Edu_Sub_Linea;					
		        $Id_Edu_Tipo_Privacidad = $Row_Producto->Id_Edu_Tipo_Privacidad;	


				$User = $_SESSION['User'];
				
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
				
				 
							
				
				$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]]]btn-simple-b}";
				$btn .= "<i class='zmdi zmdi-wrench'></i> Herramientas ]" .$UrlFile."/interface/Herramientas/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]]]btn-simple-d}";
				if(!empty($User)){				
				      $btn .= "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat <div id='alerta_chat'> </div>]" .$UrlFile."/interface/chat/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXMS]]btn-simple-d}";
				}
				$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				
                
                $PanelB = '
					<div class="messenger">
					  <div class="m-left-toolbar" style="padding: 0px 10px 0px 10px;">
						<div id="evaluador_indicador_chat" valor="si"></div>
						<ul style="text-align:left;">
						  <li style="padding-top:12px;">
							 '.$btn_componentes.'
						  </li>	
				
						</ul>
					  </div>				
				  </div>	';

				$PanelB .= $this->Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,"",$Id_Edu_Tipo_Privacidad);				
				
			    			
				if($Parm["request"] == "on"){
                   

					$Settings["interface"] = "begin";
					$Settings["request"] = $Parm["request"];
					$Settings["key"] = $Parm["key"];
					$Settings["Id_Edu_Componente_S"] = $Parm["Id_Edu_Componente_S"];
					new Edu_Articulo_Det($Settings);

					
				}else{
					DCWrite($PanelB);			
				}

				
                break;					
				
				
            case "PanelB":
			
			
			
			
				if($Parm["request"] == "on"){
                    
					// history.pushState(UrlRaiz+"/request/on", "", UrlRaiz+"/request/on");
					DCRedirect("/sadministrator/edu-articulo-det/interface/y/key/".$Parm["key"]."/Id_Edu_Componente_S/".$Parm["Id_Edu_Componente_S"]."/request/on");
					
					// $Settings = array("/sadministrator/edu_articulo_det/interface/y/key/".$Parm["key"]."/Id_Edu_Componente_S/".$Parm["Id_Edu_Componente_S"]);
					// $Settings['Url'] = "/sadministrator/edu_articulo_det/interface/y/key/".$Parm["key"]."/Id_Edu_Componente_S/".$Parm["Id_Edu_Componente_S"];
					// $Settings['Screen'] = "ScreenRight";
					// $Settings['Type_Send'] = "";
					// DCRedirectJS($Settings);						
					DCExit("");					
				}
				
				$layout  = new Layout();
				$Redirect = "/REDIRECT/edu-articulo-det";
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];

				
				if(empty($Entity)){
					
					$Query = "
					SELECT 
					ET.Id_Entity 
					FROM entity ET
					WHERE 
					ET.Url = :Url 
					";	
					$Where = ["Url" => $Parm["ie"]];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Id_Entity = $Row->Id_Entity;	
					
				    $_SESSION['Entity'] = $Id_Entity; 

				}				
										   
				$Query = "
				    SELECT 
					EC.Id_Edu_Componente 
					, EC.Imagen 
					, EC.Nombre 
					, EC.Contenido_Embebido 
					, EC.Id_Edu_Formato 
					, EC.Orden 
					, EC.Descarga 
					FROM edu_componente EC
					WHERE EC.Id_Edu_Componente = :Id_Edu_Componente
				";	
				$Where = ["Id_Edu_Componente" => $Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		        $NombreComponente = $Row->Nombre;				
		        $Contenido_Embebido = $Row->Contenido_Embebido;	
		        $Id_Edu_Componente_B = $Row->Id_Edu_Componente;	
		        $Orden = $Row->Orden;	
		        $Id_Edu_Formato = $Row->Id_Edu_Formato;	
		        $Imagen = $Row->Imagen;	
		        $Descarga = $Row->Descarga;	

                if(empty($Id_Edu_Componente_S)){
					
					$Query = "
						SELECT 
						EC.Id_Edu_Componente 
						, EC.Nombre 
						, EC.Imagen 
						, EC.Contenido_Embebido 
						, EC.Id_Edu_Formato 
						, EC.Orden 
						, EC.Descarga 
						FROM edu_componente EC
						WHERE 
						EC.Id_Edu_Almacen = :Id_Edu_Almacen AND EC.Introduccion = :Introduccion 
					";	
					$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Introduccion" => "SI" ];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$NombreComponente = $Row->Nombre;	
		            $Contenido_Embebido = $Row->Contenido_Embebido;				
				    $Id_Edu_Componente_S = $Row->Id_Edu_Componente;	
		            $Id_Edu_Componente_B = $Row->Id_Edu_Componente;					
		            $Orden = $Row->Orden;			
		            $Id_Edu_Formato = $Row->Id_Edu_Formato;			
		            $Imagen = $Row->Imagen;			
		            $Descarga = $Row->Descarga;			
						
				}
				
				
				
                $Parm["Id_Edu_Componente_S"]=$Id_Edu_Componente_S;
		     	$this->Cuenta_Vistas($Parm);
				
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
				
				
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;				
		        $Id_Edu_Area_Conocimiento = $Row_Producto->Id_Edu_Area_Conocimiento;				
		        $Id_Edu_Sub_Linea = $Row_Producto->Id_Edu_Sub_Linea;					
		        $Id_Edu_Tipo_Privacidad = $Row_Producto->Id_Edu_Tipo_Privacidad;					
								
				if($Id_Edu_Tipo_Privacidad == 1){
					if(empty($Id_Suscripcion) && !empty($_SESSION['action']) ){
				
						$btn = "<i class='zmdi zmdi-folder-star-alt zmdi-hc-lg'></i> Suscríbete ]" .$UrlFile."/interface/Matricula/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]animatedModal5]HXM]]btn btn-primary m-w-120}";
						$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);
								
					}else{
						
						$btn = "<i class='zmdi zmdi-share zmdi-hc-lg'></i> Compartir ]" .$UrlFile."/interface/Compartir/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]animatedModal5]HXM]]btn btn-primary m-w-120}";
						$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);												
						// $btn = "";												
					}
				}
					
				$Query = "
				SELECT Count(*)  AS Tot FROM edu_vistas_objectos  EDV
				WHERE
				EDV.Id_Edu_Componente = :Id_Edu_Componente
				AND  EDV.Id_Edu_Almacen = :Id_Edu_Almacen
				";	
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen, "Id_Edu_Componente" =>$Id_Edu_Componente_S];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Tot_Vistas = $Row->Tot;
				
				$Query = "
						SELECT 
						Count(*)  AS Tot 
						FROM edu_componente EC
						WHERE 
						EC.Id_Edu_Almacen = :Id_Edu_Almacen
				";	
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Tot_Items = $Row->Tot;
				
				$Jscript = "
				           <script>
						   ResizeScreen('Screen_Content');
				           </script>
						   <style>
						    .botones1 {
								margin-top:0px;
							}
						    #Screen_Content_Btn .botones1 button {
								margin-right: -14px;
								margin-top: -5px;
								text-align: center;
							}
							
						   </style>
				           ";	
    						   


				$Dominio = DCUrl();
				$Dominio_url = "https://yachai.org/sadministrator/archivos/docs/".$Imagen;
				

				
				if($Id_Edu_Formato == 2){
					
					if($Id_Edu_Tipo_Privacidad == 3){
						
                        if($Descarga == "SI"){
							
							$btn = "<i class='zmdi zmdi-download zmdi-hc-fw'></i> Descargar Archivo ]" .$Dominio_url."]]HREF_DONWLOAD]]btn btn-primary m-w-120}";
							$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);		
					    }						
					}			
										
					
					$ReproductorYT = "    
					<div style='position:relative;'>
							<div style='     background-color: #6b6464b8;
							top: 12px;
							height: 40px;
							position: absolute;
							right: 19px;
							width: 40px;'></div>

					";

					
					$ReproductorYT .= '
					                  
									  <iframe src="http://docs.google.com/viewer?url='.$Dominio_url.'&embedded=true" 
									  width="600" height="300" style="border: none;"   rel="noopener noreferrer" href="https://www.enable-javascript.com/"  ></iframe>
					</div>				  
					';  
									  
				}elseif($Id_Edu_Formato == 6){
					
					$ReproductorYT = $Contenido_Embebido;	
					$style = "style=margin-top:-19px;";
					
				}elseif($Id_Edu_Formato == 4){
					
			
					$ReproductorYT = '
					
					<video width="100%" height="100%" controls>
					<source src="'.$Contenido_Embebido.'" type="video/mp4">
					<source src="movie.ogg" type="video/ogg">
					Your browser does not support the video tag.
					</video>
					
					';		


				}else{
					
					$Contenido_Embebido = trim($Contenido_Embebido);
					$ReproductorYT =  	"
					
						<div id='ReproductorVideo'></div>
						<script>
							var vp = new ReproductorVideo({
								id      : 'ReproductorVideo',
								videoId : '".$Contenido_Embebido."'
							});
						</script>
					";
					
				}				


			
				$User = $_SESSION['User'];
				
				$Perfil_User = $_SESSION['Perfil_User'];		
				
				if($Perfil_User == 2 || $Perfil_User == 1){	
				
				    $Link_Descarga = "https://www.youtube.com/watch?v=".$Contenido_Embebido;
				}
				
                if( $Tot_Items != 0){
					$DCPanelTitle = DCPanelTitleB($NombreComponente," Visualizaciones  ".$Tot_Vistas." <br> ".$Link_Descarga,$btn,"");
				}
				
				if($Id_Edu_Tipo_Privacidad == 3){
					
				    if(!empty($User)){				
					    $PanelA = "<div id='Screen_Content' ".$style.">".$ReproductorYT."</div>";
					}else{
						$btn_Iniciar_Sesion = " Iniciar Sesión ]/sadministrator/login/request/on/]_blank]HREF]]btn btn-primary m-w-120}";
						$btn_Iniciar_Sesion = DCButton($btn_Iniciar_Sesion, 'botones1', 'sys_form'.$Count);	

						$PanelA = "<div id='Screen_Content' style='font-size: 1.5em;padding: 100px 10px;text-align: center;' >Tu sesión terminó!!,  debes iniciar Sesión 
						<br>
						<br>
						".$btn_Iniciar_Sesion."
						</div>";						
					}
					
				}else{
					
					    $PanelA = "<div id='Screen_Content' ".$style.">".$ReproductorYT."</div>";					
				}	
				
                $PanelA .= "<div id='Screen_Content_Btn' style='position: absolute;width: 100%;' >".$DCPanelTitle."  </div>".$Jscript;
      

				// $Layout = array(array("PanelA","col-md-12",$PanelA));
				// $Content = DCLayout($Layout);
				
				DCWrite($PanelA);			
	
                break;			
				
            case "Create_Conten":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $key = $Parm["key"];
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Crud/key/".$key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."/Id_Edu_Componente/".$Id_Edu_Componente;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Componente)){
					
				    $Name_Interface = "Editar Contenido";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Contenido";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Componente_Crud");							
				}
				
				$Combobox = array(
				     array( "Id_Edu_Formato"," SELECT Id_Edu_Formato AS Id, Nombre AS Name FROM edu_formato ",[])
				     , array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Componente_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit("");
                
				break;
				
				
            case "Create_Conten_SubItem":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
	            $key = $Parm["key"];
				
				if(!empty( $Id_Edu_Componente_Jerar)){
					$Id_Edu_Componente_Jerar_Url = "/Id_Edu_Componente_Jerar/".$Id_Edu_Componente_Jerar;
				}
				
				if(!empty( $Id_Edu_Componente)){
					$Id_Edu_Componente_Url = "/Id_Edu_Componente/".$Id_Edu_Componente;
				}								
				
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Carpeta_Crud/key/".$key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Id_Edu_Componente_Url . $Id_Edu_Componente_Jerar_Url;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Componente)){
					
				    $Name_Interface = "Editar Carpeta";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_Carpeta_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Carpeta";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Componente_Crud");							
				}
				
									   
				$Panel = "elementos_cart";	
				
				if(!empty( $Id_Edu_Componente_Jerar)){
					
					$Combobox = array(
						 array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen AND Jerarquia_Id_Edu_Componente <>:Jerarquia_Id_Edu_Componente ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key,"Jerarquia_Id_Edu_Componente"=>1])
					);
				
				}else{
					
					$Combobox = array(
						 array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen AND Jerarquia_Id_Edu_Componente =:Jerarquia_Id_Edu_Componente ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key,"Jerarquia_Id_Edu_Componente"=>1])
					);
									
				}									   
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,$Panel,"Form","Edu_Componente_Carpeta_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_Carpeta_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit("");
                
				break;
				
				
             case "Create_Conten_SubItem_Dcoc":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
				
	            $key = $Parm["key"];
				if(empty( $Id_Edu_Componente_Jerar)){
					
                    $DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Doc_Crud/key/".$key;
									
				}else{
					if(empty($Id_Edu_Componente_S)){
						///8888888888888888888888
						DCCloseModal();	
						DCWrite(Message("El curso debe tener un documento como introducción ","C"));
						DCExit();
					}
					
				    $DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Doc_Crud/key/".$key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."/Id_Edu_Componente_Jerar/".$Id_Edu_Componente_Jerar."/Id_Edu_Componente/".$Id_Edu_Componente;
				}
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Componente)){
					
				    $Name_Interface = "Editar Documento";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_Doc_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Documento";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Componente_Crud");							
				}
				
				$Combobox = array(
				     array( "Id_Edu_Formato"," SELECT Id_Edu_Formato AS Id, Nombre AS Name FROM edu_formato ",[])
				     , array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key])
				);
				
				
				if(!empty( $Id_Edu_Componente_Jerar)){
					
					$Panel = "Panel_".$Id_Edu_Componente_Jerar;	
					
				}else{
				    $Panel = "elementos_cart";						
									
				}				
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,$Panel,"Form","Edu_Componente_Doc_Crud"),$ButtonAdicional
				);	
				
		        $Form1 = BFormVertical("Edu_Componente_Doc_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit("");
                
				break;     
				
             case "Create_Conten_SubItemB_Dcoc":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
				
	            $key = $Parm["key"];
				if(!empty( $Id_Edu_Componente_Jerar)){
					$Id_Edu_Componente_Jerar_Url = "/Id_Edu_Componente_Jerar/".$Id_Edu_Componente_Jerar;
				}

				if(!empty( $Id_Edu_Componente)){
					$Id_Edu_Componente_Url = "/Id_Edu_Componente/".$Id_Edu_Componente;
				}
				
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_DocA_Crud/key/".$key.$Id_Edu_Componente_Url."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Id_Edu_Componente_Jerar_Url;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Componente)){
					
				    $Name_Interface = "Editar Documento";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_DocA_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Documento";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                }

				if(!empty( $Id_Edu_Componente_Jerar)){
					
					$Panel = "Panel_".$Id_Edu_Componente_Jerar;	
					$Combobox = array(
						 array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen AND Jerarquia_Id_Edu_Componente <>:Jerarquia_Id_Edu_Componente ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key,"Jerarquia_Id_Edu_Componente"=>1])
					);
				
				}else{
				    $Panel = "elementos_cart";						
					$Combobox = array(
						 array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen AND Jerarquia_Id_Edu_Componente =:Jerarquia_Id_Edu_Componente ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key,"Jerarquia_Id_Edu_Componente"=>1])
					);
									
				}
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,$Panel,"Form","Edu_Componente_DocA_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_DocA_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit("");
                
				break; 				
				
             case "Create_Conten_SubItem_Articulo":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
	            $key = $Parm["key"];
				
				if(!empty( $Id_Edu_Componente_Jerar)){
					$Id_Edu_Componente_Jerar_Url = "/Id_Edu_Componente_Jerar/".$Id_Edu_Componente_Jerar;
				}
				
				if(!empty( $Id_Edu_Componente)){
					$Id_Edu_Componente_Url = "/Id_Edu_Componente/".$Id_Edu_Componente;
				}
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Articulo_Crud/key/".$key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Id_Edu_Componente_Url.$Id_Edu_Componente_Jerar_Url;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;				
				
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Componente)){
					
				    $Name_Interface = "Editar Articulo EE";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_Articulo_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Articulo";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Componente_Crud");							
				}
				
				$Combobox = array(
				      array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen  
					                   AND   Jerarquia_Id_Edu_Componente = :Jerarquia_Id_Edu_Componente 
									   ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key,"Jerarquia_Id_Edu_Componente" => 0])
				);
				
				
					
						
				if(!empty( $Id_Edu_Componente_Jerar)){
					
					$Panel = "Panel_".$Id_Edu_Componente_Jerar;	
					$Combobox = array(
						 array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen AND Jerarquia_Id_Edu_Componente <>:Jerarquia_Id_Edu_Componente ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key,"Jerarquia_Id_Edu_Componente"=>1])
					);
				
				}else{
                    $Panel = "elementos_cart";	
					$Combobox = array(
						 array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen AND Jerarquia_Id_Edu_Componente =:Jerarquia_Id_Edu_Componente ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key,"Jerarquia_Id_Edu_Componente"=>1])
					);
									
				}				
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,$Panel,"Form","Edu_Componente_Articulo_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_Articulo_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit("");
                
				break;     	
				// Create_Conten_SubItem_Articulo_B
             case "Create_Conten_SubItem_Articulo_B":
			    // echo "Hola";
				// exit();
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
	            $key = $Parm["key"];
			
				if(!empty($Id_Edu_Componente_Jerar)){
					$Id_Edu_Componente_Jerar_Url = "/Id_Edu_Componente_Jerar/".$Id_Edu_Componente_Jerar;
				}
				
							
				if(!empty($Id_Edu_Componente)){
					$Id_Edu_Componente_Url = "/Id_Edu_Componente/".$Id_Edu_Componente;
				}
				
				
				
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Articulo_B_Crud/key/".$key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Id_Edu_Componente_Jerar_Url.$Id_Edu_Componente_Url;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Componente)){
					
				    $Name_Interface = "Editar Articulo";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_Articulo_B_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Articulo";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Componente_Crud");							
				}
				
				$Combobox = array(
				     array( "Id_Edu_Formato"," SELECT Id_Edu_Formato AS Id, Nombre AS Name FROM edu_formato ",[])
				     , array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Componente_Articulo_B_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_Articulo_B_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit("");
                
				break;   
				
             case "Create_Conten_SubItem_Embebido":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];

	            $key = $Parm["key"];
				// var_dump($Id_Edu_Componente_S);
                //    var_dump($key);
                    //brians
				if(!empty( $Id_Edu_Componente_Jerar)){
					$Id_Edu_Componente_Jerar_Url = "/Id_Edu_Componente_Jerar/".$Id_Edu_Componente_Jerar;
				}
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Embebido_Crud/key/".$key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Id_Edu_Componente_Jerar_Url."/Id_Edu_Componente/".$Id_Edu_Componente;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Componente)){
					
				    $Name_Interface = "Editar Vídeo Embebido ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_Embebido_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Vídeo Embebido";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";
				}
				
				if(!empty( $Id_Edu_Componente_Jerar)){
					
					$Panel = "Panel_".$Id_Edu_Componente_Jerar;	
		
					
					$Combobox = array(
						 array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen AND Jerarquia_Id_Edu_Componente <>:Jerarquia_Id_Edu_Componente ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key,"Jerarquia_Id_Edu_Componente"=>1])
					);
				
				}else{
					
				    $Panel = "elementos_cart";
					
					$Combobox = array(
						 array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen AND Jerarquia_Id_Edu_Componente =:Jerarquia_Id_Edu_Componente ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key,"Jerarquia_Id_Edu_Componente"=>1])
					);
									
				}
				
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,$Panel,"Form","Edu_Componente_Embebido_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_Embebido_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit("");
                
				break;     	
				
				
            case "Matricula":
			
				$User = $_SESSION['User'];

		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
                
                if(!empty($User)){
						
					$btn = " Aceptar ]" .$UrlFile ."/Process/MATRICULA/Obj/Edu_Componente_Crud/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
					$btn .= " Cancelar ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
					$Button = DCButton($btn, 'botones1', 'sys_form');					
					$Html = DCModalFormMsj("Para ver todas la lecciones del compendio debes suscribirte",$Form,$Button,"bg-info-b");
					
				}else{
                    
					$btn = " Ya soy Usuario ]/sadministrator/login/key/".$Id_Edu_Almacen."/request/on/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]]HREF]]btn btn-default dropdown-toggle]}";				
					$btn .= " Quiero Registrarme ]/sadministrator/edu-register/key/".$Id_Edu_Almacen."/request/on/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]]HREF]]btn btn-default dropdown-toggle]}";				
					$btn .= " Cancelar ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
					$Button = DCButton($btn, 'botones1', 'sys_form');					
					$Html = DCModalFormMsj("Para ver todas la lecciones del compendio debes registrarte",$Form,$Button,"bg-info-b");
					
				}
				
                DCWrite($Html);
				
                break;	
				
            case "Delete_Componente":
		
		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
				$Id_Edu_Almacen = $Parm["key"];
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Componente/".$Id_Edu_Componente."/Obj/Edu_Componente_Crud/key/".$Id_Edu_Almacen."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Deseas eliminar el contenido",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;	
				
            case "Compartir":
		
		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
				$Id_Edu_Almacen = $Parm["key"];
				// $btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Componente/".$Id_Edu_Componente."/Obj/Edu_Componente_Crud/key/".$Id_Edu_Almacen."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				// $btn .= "Close ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'Btn_Compartir');					
				
				
				$Form = '
				        
						<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.11";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, "script","facebook-jssdk"));</script>
						 
						 <a class="btn btn-facebook" target="_blank" 
						 href="https://www.facebook.com/sharer/sharer.php?u=http://yachai.org/sadministrator/edu-articulo-det/interface/begin/request/on/key/'.$Id_Edu_Almacen.'/action/sugerencia">
						 <i class="zmdi zmdi-facebook-box zmdi-hc-2x"></i>
						 </a>
						 
						 <a class="btn btn-googleplus" target="_blank" 
						  href="https://plus.google.com/share?url=http://yachai.org/sadministrator/edu-articulo-det/interface/begin/request/on/key/'.$Id_Edu_Almacen.'/action/sugerencia">
						 <i class="zmdi zmdi-google-plus zmdi-hc-2x"></i>
						 </a>	
						 
						 <a class="btn btn-twitter" target="_blank" 
						 href="https://twitter.com/?status=http://yachai.org/sadministrator/edu-articulo-det/interface/begin/request/on/key/'.$Id_Edu_Almacen.'/action/sugerencia">
						 <i class="zmdi zmdi-twitter zmdi-hc-2x"></i>
						 </a>
						 
						 <a class="btn btn-linkedin" target="_blank" 
						 href="http://www.linkedin.com/shareArticle?url=http://yachai.org/sadministrator/edu-articulo-det/interface/begin/request/on/key/'.$Id_Edu_Almacen.'/action/sugerencia">
						 <i class="zmdi zmdi-linkedin zmdi-hc-2x"></i>
						 </a>	
						 
						 
                        ';
				
			    $Html = DCModalFormMsj("¡ Comparte tu aprendizaje con tus amigos !","",$Form,"bg-info-b");
                DCWrite($Html);
				
                break;	

            case "delete_objeto":

		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
		        $key = $Parm["key"];
		        $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];

				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Articulo/".$Id_Edu_Articulo."/Obj/Edu_Object/key/".$key."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Deseas eliminar el Objeto",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;		
				
				
            case "Contenido_Componente":
		        
			    DCCloseModal();	
				
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];		
				$Id_Edu_Componente = $Parm["Id_Edu_Componente"];

                  ///xxxxxxxxxxxxxx
	            $PanelB = $this->Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,$Id_Edu_Componente,"");				
				
				echo $PanelB;
                break;	
				
            case "Cierrar_Panel_Detalle":
			      // echo "asss";
		          DCCloseModal();	
				  exit();
            break;	

            case "Analisis_Concurrencia":


	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
	            $key = $Parm["key"];
				
				$Query = "
					SELECT EVIO.Id_Edu_Componente AS CodigoLink, EC.Nombre AS Componente
					, COUNT(EVIO.Id_Edu_Componente) AS 'Total <br> Concurrencia' 
					FROM edu_vistas_objectos EVIO
					INNER JOIN edu_componente EC ON EC.Id_Edu_Componente = EVIO.Id_Edu_Componente
					WHERE 
					EVIO.Id_Edu_Almacen = :Id_Edu_Almacen  
					GROUP BY EVIO.Id_Edu_Componente
					ORDER BY COUNT(EVIO.Id_Edu_Componente) DESC
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Componente';
				$Link = $UrlFile."/interface/Detalle_Analisis_Concurrencia/key/".$key;
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Almacen"=>$key];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				
		
			    $Name_Interface = "Analisis de Concurrencia";					
			
			    $Html = DCModalForm($Name_Interface, $Listado ,"");
                DCWrite($Html);
                DCExit("");
                		
                break;					

            case "Visibilidad_Curso":

				$key = $Parm["key"];
	            $Id_Entity = $_SESSION['Entity'];
				$Name_Interface = "Visibilidad de Alumnos";
			    $btn = "Activar ]" .$UrlFile ."/Process/ENTRY/Obj/Visibilidad_Curso_Habilitado/key/".$key."]animatedModal5]HXM]]btn btn-info}";
			    $btn .= "Deshabilitar ]" .$UrlFile ."/Process/ENTRY/Obj/Visibilidad_Curso_Inhabilitar/key/".$key."]animatedModal5]HXM]]btn btn-info}";

			    $Button = DCButton($btn, 'botones1', 'sys_form_c');	
			
			    $Html = DCModalFormMsjInterno($Name_Interface,"Confirme el proceso que deseas realizar ",$Button,"bg-default");
                DCWrite($Html);
                DCExit("");
                		
            break;

            //Proceso de duplicado
           	case "edu_articulo_dup":

				$Id_Edu_Almacen = $Parm["key"];
	            $Id_Entity = $_SESSION['Entity'];
				$Name_Interface = "Duplicar Articulo"; 


			    $btn = "Crear curso]" .$UrlFile ."/interface/edu_articulo_dup_create/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]]btn btn-info}";
			    $btn .= "Cancelar]" .$UrlFile ."/sadministrator/edu-articulo-det/interface/begin/request/on/key/".$key."/action/sugerencia]animatedModal5]HXM]]btn btn-info}";

			    $Button = DCButton($btn, 'botones1', 'sys_form_c');	
			
			    $Html = DCModalFormMsjInterno($Name_Interface,"Confirme el proceso que deseas realizar ",$Button,"bg-default");
                DCWrite($Html);
                DCExit("");
           	break;
           	//Proceso de duplicar examenes
           	case "edu_articulo_dup_Examen":

				$Id_Edu_Almacen = $Parm["key"];
	            $Id_Entity = $_SESSION['Entity'];
				$Name_Interface = "Copiar Examen"; 

			    $DirecctionA = $UrlFile."/Process/ENTRY/Obj/edu_articulo_examen_dup_dependiente/key/".$Id_Edu_Almacen;
			    $btn = "Cancelar]" .$UrlFile ."/sadministrator/edu-articulo-det/interface/begin/request/on/key/".$key."/action/sugerencia]animatedModal5]HXM]]btn btn-info}";

				
				
				
				$Combobox = array(
					 array("Id_Edu_Componente","SELECT Id_Edu_Componente AS Id, Nombre AS Name FROM edu_componente where Id_Edu_Formato=7 AND Jerarquia_Id_Edu_Componente=1 AND Entity={$Id_Entity} AND Id_Edu_Almacen={$Id_Edu_Almacen}",[]),
					 array("Id_Edu_Almacen","SELECT EA.Id_Edu_Almacen AS Id, Nombre AS Name FROM edu_almacen EA INNER JOIN edu_articulo AR ON EA.Id_Edu_Articulo=AR.Id_Edu_Articulo where EA.Entity={$Id_Entity}",[])
					 
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array("Confirmar",$DirecctionA,"animatedModal5","Form","edu_articulo_crud_dup_evaluacion"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("edu_articulo_crud_dup_evaluacion",$Class,$Id_Edu_Almacen,$PathImage,$Combobox,$Buttons,"Id_Edu_Almacen");
				
			  



			    $Button = DCButton($btn, 'botones1', 'sys_form_c');	
			
			    $Html = DCModalFormMsjInterno($Name_Interface,"Confirme el proceso que deseas realizar<br>".$Form1,$Button,"bg-default");
                DCWrite($Html);
                DCExit("");
           	break;

           	case "edu_articulo_dup_create":
			
				$Id_Edu_Almacen = $Parm["key"];	

				
				$btn .= "Atrás]" .$UrlFile."/interface/List/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Tipo_Privacidad = $Parm["Id_Edu_Tipo_Privacidad"];
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/edu_articulo_dup_create/key/".$Id_Edu_Almacen;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/key/".$Id_Edu_Almacen;
				
				if(!empty($Id_Edu_Tipo_Privacidad)){
				    $Name_Interface = "Editar Participante hola";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","User_Register_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Curso";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				
				
				$Combobox = array(
					 array("Id_Edu_Tipo_Privacidad","SELECT Id_Edu_Tipo_Privacidad AS Id, Nombre AS Name FROM edu_tipo_privacidad",[])
					 
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","edu_articulo_crud_dup"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("edu_articulo_crud_dup",$Class,$Id_Edu_Almacen,$PathImage,$Combobox,$Buttons,"Id_Edu_Almacen");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
            break;		
			
			case "edu_articulo_dup_confirmacion":

				$Id_Edu_Almacen = $Parm["key"];
	            $edu_almacen_new =$Parm["edu_almacen_new"];
				$Name_Interface = "CONFIRMACION DE PROCESO"; 

			    $btn = "Continuar]" .$UrlFile ."/Process/ENTRY/Obj/edu_articulo_examen_dup/key/".$Id_Edu_Almacen."/edu_almacen_new/".$edu_almacen_new."]animatedModal5]HXMS]]btn btn-info}";
			    
			   // $btn .= "Cancelar]" .$UrlFile ."/sadministrator/edu-articulo-det/interface/begin/request/on/key/".$key."/action/sugerencia]animatedModal5]HXM]]btn btn-info}";

			    $Button = DCButton($btn, 'botones1', 'sys_form_c');	
			
			    $Html = DCModalFormMsjInterno($Name_Interface,"Confirmacion del duplicado de examen del curso",$Button,"bg-default");
                DCWrite($Html);
                DCExit();
            break;
            case "Configurar_Evaluar":
			
				$Id_Edu_Almacen = $Parm["key"];	
			
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Tipo_Privacidad = $Parm["Id_Edu_Tipo_Privacidad"];
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Configurar_Evaluar_Crud/key/".$Id_Edu_Almacen;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/key/".$Id_Edu_Almacen;
				
				if(!empty($Id_Edu_Almacen)){
				    $Name_Interface = "Actualizar Forma de Evaluar";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";				
				}else{
				    $Name_Interface = "Crear Curso";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				
				
				$Combobox = array(
					 array("Id_Edu_Tipo_Privacidad","SELECT Id_Edu_Tipo_Privacidad AS Id, Nombre AS Name FROM edu_tipo_privacidad",[])
					 
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Configurar_Evaluar"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Configurar_Evaluar",$Class,$Id_Edu_Almacen,$PathImage,$Combobox,$Buttons,"Id_Edu_Almacen");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
            break;	






            case "Detalle_Analisis_Concurrencia":


	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
	            $key = $Parm["key"];
				
				$Query = "
				   
					SELECT 
				    UM.Nombre AS 'Nombre Participante'
					, COUNT(EVIO.Id_Edu_Componente) AS 'Tot_Interacción'
					FROM edu_vistas_objectos EVIO
					INNER JOIN edu_componente EC ON EC.Id_Edu_Componente = EVIO.Id_Edu_Componente
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = EVIO.Id_User
					WHERE 
					EVIO.Id_Edu_Almacen = :Id_Edu_Almacen  AND 
					EVIO.Id_Edu_Componente = :Id_Edu_Componente  
					GROUP BY EVIO.Id_User
					ORDER BY EVIO.Date_Time_Creation DESC
				";    
				
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Formato';
				$Link = $UrlFile."/interface/Detalle_Analisis_Concurrencia/Id_Edu_Componente/".$Id_Edu_Componente."/key/".$key;
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Almacen"=>$key,"Id_Edu_Componente" => $Id_Edu_Componente];
				// var_dump($where);
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				
		
			    $Name_Interface = "Analisis de Concurrencia";	
	
				$btn = "<i class='zmdi zmdi-arrow-left zmdi-hc-fw'></i> Atrás]" .$UrlFile."/interface/Analisis_Concurrencia/key/".$key."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= " Descargar ]" .$UrlFile."/interface/Analisis_Concurrencia_Descargar/key/".$key."/Id_Edu_Componente/".$Id_Edu_Componente."]animatedModal5]HREF]]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');
				$DCPanelTitle = DCPanelTitle("","Total Participantes: ".$Tot,$btn);				
			
			    $Html = DCModalForm($Name_Interface, $DCPanelTitle.$Listado ,"");
                DCWrite($Html);
                DCExit("");
                		
            break;		


            case "Analisis_Concurrencia_Descargar":

				error_reporting(E_ALL);
				ini_set('display_errors', '1');

		        $key = $Parm["key"];
		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];

				
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
				    UM.Nombre AS Nombres
					, COUNT(EVIO.Id_Edu_Componente) AS 'Tot_Interaccion'
					FROM edu_vistas_objectos EVIO
					INNER JOIN edu_componente EC ON EC.Id_Edu_Componente = EVIO.Id_Edu_Componente
					INNER JOIN user_miembro UM ON UM.Id_User_Miembro = EVIO.Id_User
					WHERE 
					EVIO.Id_Edu_Almacen = :Id_Edu_Almacen AND  
					EVIO.Id_Edu_Componente = :Id_Edu_Componente  
					GROUP BY EVIO.Id_User
					ORDER BY EVIO.Date_Time_Creation DESC
				";    
				
				$Where = ["Id_Edu_Almacen" => $key, "Id_Edu_Componente" => $Id_Edu_Componente];
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
								$tableCuerpo .= "<td>".$Reg->Nombres."</td>";		
								$tableCuerpo .= "<td>".$Reg->Tot_Interaccion."</td>";		

								$tableCuerpo .= "</tr>";

				}	
				

				$table = "<table>";		
				
					$tableCabezera = "<tr>";
					$tableCabezera .= "<th>Nombres</th>";
					$tableCabezera .= "<th>Tot_Interaccion</th>";
					$tableCabezera .= "</tr>";
								
				$table .= $tableCabezera;			
				$table .= $tableCuerpo;			
				$table .= "</table>";	

				header ("Content-Type: application/vnd.ms-excel");
				header ("Content-Disposition: inline; filename=Concurrenci_Por_Tema.xls");
				
				echo $table;

				exit();				
				
                 				
                break;



				
			
        }
				
		
		
	}
	
	
	public function Cuenta_Vistas($Parm) {
       	global $Conection, $DCTimeHour,$NameTable;
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
				
		$data = array(
		'Id_Edu_Componente' =>  $Parm["Id_Edu_Componente_S"],
		'Id_Edu_Almacen' =>  $Parm["key"],
		'Id_User' => $User,
		'Entity' => $Entity,
		'Id_User_Update' => $User,
		'Id_User_Creation' => $User,
		'Date_Time_Creation' => $DCTimeHour,
		'Date_Time_Update' => $DCTimeHour
		);
		$Return = ClassPDO::DCInsert("edu_vistas_objectos", $data, $Conection,"");
		
		
		$Query = "
			 SELECT Count(*)  AS Tot FROM edu_vistas_objectos  EDV
			 WHERE
			 EDV.Id_Edu_Componente = :Id_Edu_Componente
			 AND  EDV.Id_Edu_Almacen = :Id_Edu_Almacen
		";	
		$Where = ["Id_Edu_Almacen" =>$Parm["key"], "Id_Edu_Componente" =>$Parm["Id_Edu_Componente_S"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Tot = $Row->Tot;	
	

		$Query = "
			 SELECT EVOR.Id_Edu_Vistas_Objectos_Resumen 
			 FROM edu_vistas_objectos_resumen  EVOR
			 WHERE
			 EVOR.Id_Edu_Componente = :Id_Edu_Componente
			 AND EVOR.Id_Edu_Almacen = :Id_Edu_Almacen
		";	
		$Where = ["Id_Edu_Almacen" =>$Parm["key"], "Id_Edu_Componente" =>$Parm["Id_Edu_Componente_S"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Edu_Vistas_Objectos_Resumen = $Row->Id_Edu_Vistas_Objectos_Resumen;	
			
		// DCVd($Row);
		if(!empty($Id_Edu_Vistas_Objectos_Resumen)){
			
			$reg = array(
				'Cantidad_Vistas_Componente' => $Tot
			);
			$where = array('Id_Edu_Almacen' => $Parm["key"], 'Id_Edu_Componente' => $Parm["Id_Edu_Componente_S"]);
			$rg = ClassPDO::DCUpdate('edu_vistas_objectos_resumen', $reg , $where, $Conection,"");
			
		}else{
			
			$data = array(
			'Cantidad_Vistas_Componente' =>  1,
			'Id_Edu_Componente' =>  $Parm["Id_Edu_Componente_S"],
			'Id_Edu_Almacen' =>  $Parm["key"],
			'Entity' => $Entity,
			'Id_User_Update' => $User,
			'Id_User_Creation' => $User,
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			$Return = ClassPDO::DCInsert("edu_vistas_objectos_resumen", $data, $Conection,"");
		}
		
		
				
		
		
	}
	
	public function ObjectDelete_Object($Parm) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
		$Id_Edu_Almacen = $Parm["key"];
		// $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];

		$where = array('Id_Edu_Articulo' =>$Id_Edu_Articulo);
		$rg = ClassPDO::DCDelete('edu_articulo', $where, $Conection);		
		
		$where = array('Id_Edu_Almacen' =>$Id_Edu_Almacen);
		$rg = ClassPDO::DCDelete('edu_almacen', $where, $Conection);
		
		$where = array('Id_Edu_Almacen' =>$Id_Edu_Almacen);
		$rg = ClassPDO::DCDelete('edu_componente', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));


		
	}
	
	
	public function Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,$Id_Edu_Componente,$Id_Edu_Tipo_Privacidad) {
       	global $Conection, $DCTimeHour,$NameTable;
		$UrlFile_Edu_Examen = "/sadministrator/edu-examen";
		$UrlFile_Edu_Desarrollo_Examen = "/sadministrator/edu-desarrollo-examen";
        $UrlFile_Edu_Calificar = "/sadministrator/edu-calificar";		
        $UrlFile_Edu_Foro = "/sadministrator/edu-examen-foro";	


			
		$User = $_SESSION['User'];
		
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
		
		

		$Query = "
			SELECT PE.Id_Perfil_Educacion
			FROM suscripcion SC
			INNER JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
			WHERE SC.Id_Suscripcion = :Id_Suscripcion
		";  
		
		$Where = ["Id_Suscripcion" =>$Id_Suscripcion];
	
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Perfil_Educacion = $Row->Id_Perfil_Educacion;		

		$Query = "
			SELECT 
			 EC.Id_Edu_Componente
			, EC.Nombre
			, EC.Imagen 
			, EC.Orden 
			, EC.Introduccion 
			, EC.Id_Edu_Formato 
			, EC.Vista_Sin_Inscripcion 
			FROM edu_componente EC
			WHERE
			EC.Id_Edu_Almacen = :Id_Edu_Almacen AND 
			EC.Jerarquia_Id_Edu_Componente = :Jerarquia_Id_Edu_Componente
			ORDER BY EC.Orden ASC
		";  		
	
		if(!empty($Id_Edu_Componente)){
			
			// echo " Url_Jerarquia ";
			// echo $Url_Jerarquia;
			//briansTest
			$Url_Jerarquia = "/Id_Edu_Componente_Jerar/".$Id_Edu_Componente;
			
			$height =  "height:100%";
		    $Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen,"Jerarquia_Id_Edu_Componente"=>$Id_Edu_Componente];	
		}else{
			
			$Url_Jerarquia = "";			
			$height =  "height:400px";
	        $Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen,"Jerarquia_Id_Edu_Componente"=>1];
        }
        //brians, aca modifico "Jerarquia_Id_Edu_Componente"=>1 por 1, ya que base de datos no guarda el cero,
        ////sino que el cero lo toma y lo guarda como nulo
		
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		
		$btn = " <i class='zmdi zmdi-close-circle zmdi-hc-fw'> </i> ]" .$UrlFile."/interface/Cierrar_Panel_Detalle/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."/Id_Edu_Componente/".$Id_Edu_Componente."]Panel_".$Id_Edu_Componente."]HXM]]SinEstilo}";
		$Btn_Ocultar = DCButton($btn, 'botones1', 'sys_form_btn_cerrar'.$Id_Edu_Componente);
		
		$PanelB = '
		<div class="cart"   id="elementos_cart" >		
		
		  <table class="table">
			<tbody>	';	
			
		if(!empty($Id_Edu_Componente)){			
			$PanelB .= '
				<tr>	
					<td colspan="2">
						<div style=" width: 100%; float: left;text-align: left;position:relative;" class="Cabezera_Si"> 
						
                            <i class="zmdi zmdi-play zmdi-hc-fw"></i>
							
							<div style=" width: 50%; float: left;text-align: left;"> Contenido de la Carpeta</div>
							<div style=" width: 50%; float: left;text-align: right;">'.$Btn_Ocultar.'</div>
						</div>
					</td>						
				</tr> 			
			
			';
		}

	
			
		$Introduccion = "";
		$DivIntroduccion = "";
		$Num_Orden = "";
		foreach($Rows AS $Field){
			$Count += 1;	
			$CountA += 1;	
			$Introduccion = $Field->Introduccion;
			
				if( $Introduccion == "SI"){
					$Num_Orden = $DivIntroduccion;			
				}else{
					$DivIntroduccion = "";	
				}
			

				if($Field->Id_Edu_Formato == 3){
				    $Icon = "<i class='zmdi zmdi-collection-video zmdi-hc-fw' ></i>";
				}elseif($Field->Id_Edu_Formato == 2){
				    $Icon = "<i class='zmdi zmdi-file zmdi-hc-fw' > </i>";
				}elseif($Field->Id_Edu_Formato == 5){
				    $Icon = "<i class='zmdi zmdi-folder-outline zmdi-hc-fw' > </i>";
				}elseif($Field->Id_Edu_Formato == 6){
				    $Icon = "<i class='zmdi zmdi-format-indent-increase zmdi-hc-fw'> </i>";	
				}elseif($Field->Id_Edu_Formato == 7){
				    $Icon = "<i class='zmdi zmdi-format-list-bulleted'> </i>";	
                }elseif($Field->Id_Edu_Formato == 8){
				    $Icon = "<i class='zmdi zmdi-cloud-upload'> </i>";	
                }elseif($Field->Id_Edu_Formato == 9){
				    $Icon = "<i class='zmdi zmdi-comments'> </i>";	

					
					
					
				}else{
				    $Icon = "<i class='zmdi zmdi-collection-video zmdi-hc-fw' ></i>";
				}
			
				$PanelB .= '					
				 <tr>
					<td class="c-image" style="height:63px;" >
					'.$Icon.'
					</td>
					<td class="c-link" style="width:90%;">
					  ';
						
						// DCWrite("Id_Suscripcion");
						// DCWrite($Id_Suscripcion);
						if(!empty($_SESSION['User'])){
							if(!empty($Id_Suscripcion)){
								
								if($Field->Vista_Sin_Inscripcion != "SI"){	
									$Field->Vista_Sin_Inscripcion = "SI";
								}									
							
							}
						}

						
						if($Field->Id_Edu_Formato == 5){
							
								$btn = " ".$Field->Nombre." ]" .$UrlFile."/interface/Contenido_Componente/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."/Id_Edu_Componente/".$Field->Id_Edu_Componente."]Panel_".$Field->Id_Edu_Componente."]HXM]]SinEstilo}";
								$btn = DCButton($btn, 'botones1', 'sys_form_btn_item'.$Field->Id_Edu_Componente);
								$PanelB .= $btn;							
							
						}elseif($Field->Id_Edu_Formato == 2){
						
								$btn = " ".$Field->Nombre." ]" .$UrlFile."/interface/PanelB/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."/Id_Edu_Componente/".$Field->Id_Edu_Componente."]PanelA]]]SinEstilo}";
								$btn = DCButton($btn, 'botones1', 'sys_form_btn_item'.$Field->Id_Edu_Componente);
								$PanelB .= $btn;	
								
						}elseif($Field->Id_Edu_Formato == 6){
						
								$btn = " ".$Field->Nombre." ]" .$UrlFile."/interface/PanelB/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."/Id_Edu_Componente/".$Field->Id_Edu_Componente."]PanelA]]]SinEstilo}";
								$btn = DCButton($btn, 'botones1', 'sys_form_btn_item'.$Field->Id_Edu_Componente);
								$PanelB .= $btn;	
								
						}elseif($Field->Id_Edu_Formato == 3){
							
							
								
									$btn = " ".$Field->Nombre." ]" .$UrlFile."/interface/PanelB/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."/Id_Edu_Componente/".$Field->Id_Edu_Componente."]PanelA]]]SinEstilo}";
									$btn = DCButton($btn, 'botones1', 'sys_form_btn_item'.$Field->Id_Edu_Componente);
									$PanelB .= $btn;	

						}elseif($Field->Id_Edu_Formato == 8 ||  $Field->Id_Edu_Formato == 7 ){
							
									$btn = " ".$Field->Nombre."]" .$UrlFile_Edu_Desarrollo_Examen."/interface/Crea_Objeto_Evaluativo/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."/Id_Edu_Componente/".$Field->Id_Edu_Componente."]PanelA]HXMS]]SinEstilo}";
									$btn = DCButton($btn, 'botones1', 'sys_form_btn_item'.$Field->Id_Edu_Componente);
									$PanelB .= $btn;

						}elseif($Field->Id_Edu_Formato == 9){
							
									$btn = " ".$Field->Nombre."]" .$UrlFile_Edu_Desarrollo_Examen."/interface/Crea_Objeto_Evaluativo/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."/Id_Edu_Componente/".$Field->Id_Edu_Componente."]PanelA]HXMS]]SinEstilo}";
									$btn = DCButton($btn, 'botones1', 'sys_form_btn_item'.$Field->Id_Edu_Componente);
									$PanelB .= $btn;
		
						}else{
							
							
							if($Field->Vista_Sin_Inscripcion == "SI"){	
							
								$btn = "".$Field->Nombre." ]" .$UrlFile."/interface/PanelB/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."/Id_Edu_Componente/".$Field->Id_Edu_Componente."]PanelA]]]SinEstilo}";
								$btn = DCButton($btn, 'botones1', 'sys_form_btn_item'.$Field->Id_Edu_Componente);
								$PanelB .= $btn;
								
							}else{						
							
								$btn = "".$Field->Nombre." ]" .$UrlFile."/interface/Matricula/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."/Id_Edu_Componente/".$Field->Id_Edu_Componente."]animatedModal5]HXM]]SinEstilo}";
								$btn = DCButton($btn, 'botones1', 'sys_form_btn_item'.$Field->Id_Edu_Componente);
								$PanelB .= $btn;
								
							}
	                    }	
					
				$PanelB .= '
					</td>
					
					<td class="c-link" width="10px">
						<div class="" >
						
						';
						
						
						
						
						
					$Perfil = Biblioteca::Valida_Perfil("");
					
					if($Id_Perfil_Educacion == 1 || $Id_Perfil_Educacion == 2  ){
						
						$Perfil = 777;
						
					}else{
						$Perfil = $Perfil;
					}					
					
					if($Perfil == 1 || $Perfil == 2 || $Perfil == 777){
						
						
						if($Field->Id_Edu_Formato == 5){
							
							////hhhhhhhhh	
							// echo "haha";
							// echo $Url_Jerarquia;
							$listMn = "<i class='zmdi zmdi-edit zmdi-hc-fw'></i> Editar [".$UrlFile.$Redirect."/interface/Create_Conten_SubItem/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Url_Jerarquia."[animatedModal5[HXM[{";	
							// $listMn .= "<i class='zmdi zmdi-folder zmdi-hc-fw'></i> Sub Carpeta [".$UrlFile."/interface/Create_Conten_SubItem/key/".$Id_Edu_Almacen."/Id_Edu_Componente_Jerar/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Url_Jerarquia."[animatedModal5[HXM[{";
							$listMn .= "<i class='zmdi zmdi-file zmdi-hc-fw'></i> Documento [".$UrlFile."/interface/Create_Conten_SubItem_Dcoc/key/".$Id_Edu_Almacen."/Id_Edu_Componente_Jerar/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Url_Jerarquia."[animatedModal5[HXM[{";
							// $listMn .= "<i class='zmdi zmdi-file zmdi-hc-fw'></i> Documento [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
							// $listMn .= "<i class='zmdi zmdi-movie-alt zmdi-hc-fw'></i> Video  [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Url_Jerarquia."[PanelA[HXMS[{";	
							$listMn .= "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Artículo [".$UrlFile.$Redirect."/interface/Create_Conten_SubItem_Articulo/key/".$Id_Edu_Almacen."/Id_Edu_Componente_Jerar/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Url_Jerarquia."[PanelA[HXMS[{";
							$listMn .= "<i class='zmdi zmdi-collection-video zmdi-hc-fw'></i>Video Embeb[".$UrlFile.$Redirect."/interface/Create_Conten_SubItem_Embebido/key/".$Id_Edu_Almacen."/Id_Edu_Componente_Jerar/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Url_Jerarquia."[animatedModal5[HXM[{";
							// $listMn .= "<i class='zmdi zmdi-collection-text zmdi-hc-fw'></i> Examen [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							// $listMn .= "<i class='zmdi zmdi-folder-person zmdi-hc-fw'></i> Tarea [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Componente);
							
						}elseif($Field->Id_Edu_Formato == 6){
						
						
							$listMn = "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Editar [".$UrlFile.$Redirect."/interface/Create_Conten_SubItem_Articulo/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[PanelA[HXMS[{";	
							// $listMn .= "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Componente);	

						}elseif($Field->Id_Edu_Formato == 2 ){
						
						
							$listMn = "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Editar [".$UrlFile.$Redirect."/interface/Create_Conten_SubItemB_Dcoc/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[animatedModal5[HXM[{";	
							// $listMn .= "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Componente);	
							
						}elseif($Field->Id_Edu_Formato == 3 ){
						
						
							$listMn = "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Editar [".$UrlFile.$Redirect."/interface/Create_Conten_SubItem_Embebido/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Componente);


														
						}elseif($Field->Id_Edu_Formato == 7 ){
						
									
							$listMn = "<i class='zmdi zmdi-settings'></i> Configurar [".$UrlFile_Edu_Examen.$Redirect."/interface/Crea_Objeto_Evaluativo/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[PanelA[HXMS[{";	
							$listMn .= "<i class='zmdi zmdi-spellcheck'></i> Calificar [".$UrlFile_Edu_Calificar.$Redirect."/interface/List/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[PanelB[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Editar [".$UrlFile.$Redirect."/interface/Create_Conten_SubItem_Embebido/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Componente);

						}elseif($Field->Id_Edu_Formato == 8 ){
						
									
							$listMn = "<i class='zmdi zmdi-settings'></i> Configurar [".$UrlFile_Edu_Examen.$Redirect."/interface/Crea_Objeto_Evaluativo/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[PanelA[HXMS[{";	
							$listMn .= "<i class='zmdi zmdi-spellcheck'></i> Calificar [".$UrlFile_Edu_Calificar.$Redirect."/interface/List/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[PanelB[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Editar [".$UrlFile.$Redirect."/interface/Create_Conten_SubItem_Embebido/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Componente);


						}elseif($Field->Id_Edu_Formato == 9 ){
						
									
							$listMn = "<i class='zmdi zmdi-settings'></i> Configurar [".$UrlFile_Edu_Foro."/interface/Crea_Objeto_Evaluativo/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[PanelA[HXMS[{";	
							$listMn .= "<i class='zmdi zmdi-spellcheck'></i> Calificar [".$UrlFile_Edu_Calificar.$Redirect."/interface/List/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[PanelB[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Editar [".$UrlFile.$Redirect."/interface/Create_Conten_SubItem_Embebido/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente.$Url_Jerarquia."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Componente);

													
							
						}else{
							
							
							
							$listMn = "<i class='zmdi zmdi-edit zmdi-hc-fw'></i> Editar [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S.$Url_Jerarquia."[animatedModal5[HXM[{";	
							// $listMn .= "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Componente);							
						}
						
					}else{
						
						$btnB = "";					
					}		
					
					$PanelB .= $btnB;
					
					
				$PanelB .='
						</div>
							
					</td>						
				  </tr>  
				';
				  
				  
				    if($Field->Id_Edu_Formato == 5){
						
						$PanelB .='
							<tr>	
								<td colspan="2"  class="td_contenedor">
									<div id="Panel_'.$Field->Id_Edu_Componente.'" class="panel-sub-item"> </div>
								</td>						
							</tr>  
						  ';
						  
				    }
				  
			  
		}
		$PanelB .= '
			</tbody>
		  </table>
		  
		</div>
		';				
		
        return $PanelB;		
	}	
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Componente = $Settings["Id_Edu_Componente"];
		
		$where = array('Id_Edu_Componente' =>$Id_Edu_Componente);
		$rg = ClassPDO::DCDelete('edu_componente', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}	
	
	public function Matricula($Settings){
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Componente_S = $Settings["Id_Edu_Componente_S"];
		$Id_Edu_Almacen = $Settings["key"];
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		$Email = DCPost("Email");
        if(!empty($Email)){
		    
			$Query = "
				SELECT 
				SC.Id_Suscripcion
				, SC.Id_User 
				FROM suscripcion SC
				INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
				INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User

				WHERE 
				SC.Id_Edu_Almacen = :Id_Edu_Almacen AND UM.Email = :Email 
			";	
			$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Email" =>$Email];
			
			
			$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
			$Id_Suscripcion = $Row->Id_Suscripcion;	
			
			$Row_User = User::MainDataUserRegistrado();
			$Email = $Row_User->Email;
			$Id_User_Miembro = $Row_User->Id_User_Miembro;

			if(empty($Id_Suscripcion)){		

					$data = array(
					'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
					'Estado' => "Matriculado",
					'Id_User' => $Id_User_Miembro,
					'Entity' => $Entity,
					'Id_User_Update' => $User,
					'Id_User_Creation' => $User,
					'Date_Time_Creation' => $DCTimeHour,
					'Date_Time_Update' => $DCTimeHour
					);
					$Return = ClassPDO::DCInsert("suscripcion", $data, $Conection);	
					
				
					DCWrite(Message("Matrícula Exitosa","C"));
					
			}else{
				
					DCWrite(Message("Ya estas matriculado en este objeto","C"));			
			}		
					
								
				
			
		}else{
			
			$Query = "
				SELECT 
				SC.Id_Suscripcion
				, SC.Id_User 
				FROM suscripcion SC
				INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
				INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User

				WHERE 
				SC.Id_Edu_Almacen = :Id_Edu_Almacen AND SC.Id_User = :Id_User 
			";	
			$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Id_User" =>$User];
			
			$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
			$Id_Suscripcion = $Row->Id_Suscripcion;	
			
			$Row_User = User::MainData();
			$Email = $Row_User->Email;

			if(empty($Id_Suscripcion)){		

					$data = array(
					'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
					'Estado' => "Matriculado",
					'Id_User' => $User,
					'Entity' => $Entity,
					'Id_User_Update' => $User,
					'Id_User_Creation' => $User,
					'Date_Time_Creation' => $DCTimeHour,
					'Date_Time_Update' => $DCTimeHour
					);
					$Return = ClassPDO::DCInsert("suscripcion", $data, $Conection);	
					
					
					
					// Edu_Articulo_Det::Email_Matricula($Id_Edu_Almacen,$Email);
					DCWrite(Message("Matrícula Exitosa","C"));
					
			}else{
				
					DCWrite(Message("Ya estas matriculado en este objeto","C"));			
			}							
			
			
		}
		
		
	}
	
	
	public function Email_Matricula($Id_Edu_Almacen,$Email){
	    global $Conection,$DCTimeHour;	
		
			$Id_Entity = $_SESSION['Entity'];
			
            ////zzzzzzzzzzzzzzzzzzzzzzzz
            $Row = SetttingsSite::Main_Data_EU($Conection,$Email);	

            // DCVd($Row);			
			
			$Nombre = $Row->Nombre;	
			$Id_User_Miembro = $Row->Id_User_Miembro;	
			$Email = $Row->Email;	
		
			$CodigoEntidad = $Row->Id_Entity;			
			$ColorCabeceraEmail = $Row->Color_Cabecera_Email;			
			$ColorCuerpoEmail = $Row->Color_Cuerpo_Email;			
			$ColorFondoEmail = "#ccc";			
			$ImagenLogo = $Row->Logo_Interno;			
			$TextoEmailInscripcion = $Row->Texto_Email_Inscripcion;					
			$EmailSoporteCliente = $Row->Email_Soporte_Cliente;			
			$NroTelefonoSoporteCliente = $Row->Telefono_Soporte_Cliente;	
			$SubDominio = $Row->Url;	
			$ColorMenuHorizontal = $Row->Color_Menu_Horizontal;	
			
			$dominio = DCUrl();
			$ColorCabeceraEmail = "#fff";

			$Row =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
			$Id_Edu_Articulo = $Row->Id_Edu_Articulo;
			$Nombre_Articulo = $Row->Nombre;

			$Cabecera = "
				<div style='background-color:".$ColorCabeceraEmail.";padding:50px 50px 20px 50px;text-align:center;'>
					<img src='".$dominio."sadministrator/simages/".$ImagenLogo."' >
					<br>
					<hr style='height: 1px;background-color:#ccc;border: 1px;'></hr>
					<br>
					<h1 style='color:#000;font-size:1.5em;'>BIENVENIDO AL COMPENDIO</h1>
					<h2 style='color: #2196F3;font-weight: lighter;padding: 0px; margin: 0px;'>".$Nombre_Articulo."</h2>
				</div>
			";
			
			$Cuerpo = "
				
				<div style='background-color:".$ColorCabeceraEmail.";padding:20px 50px 0px 50px;'>
					<p style='font-size:1.2em;'>Hola ".$Nombre .",</p>					
					<p style='font-size:1.2em;'>Estamos muy contentos de que haya decidido suscribirse al compedio de ".$Nombre_Articulo.". <br><br>
					En este compendio encontrarás lecciones que potenciarán tus capacidades como persona, 
					profesional y empresario(a), por ello te animamos a que accedas y visualices las lecciones que ya se encuentran disponibles.
					</p>	
                    <p style='font-size:1.2em;'>
					<br>¡ Que tengas un excelente día !<br>
                    <br>¡ El equipo de Yachai!
					</p>
                    <br>
					<table align='center' width='100%'>
						<tr>
							<td style='padding:30px 30px 20px 30px;'>
								<table align='center'>
									<tr>
										<td style='background-color:#000;padding: 10px;'>
											<a href='".$dominio."sadministrator/edu-articulo-det/interface/begin/request/on/key/".$Id_Edu_Almacen."/action/sugerencia' style='font-size:12pt;height: 30px;color: white;text-decoration: none;padding: 8px 10px;font-family: arial;' >
											Ingresar
											</a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
				
			";
			
			$ImgControlVista = "<img src='".$dominio."system/_vistas/g_monitoreo_email.php?Tipo=leido&CodigoSuscripcion=$CodigoSuscripcion' width='100%'/>";
			
			$Footer = "
				<div style='background-color:".$ColorCabeceraEmail.";padding:20px 50px 30px 50px;'>
					<br>
					<p style='color:#979595;font-size:1.2em;'>Email de Soporte: ".$EmailSoporteCliente."</p>
					<p style='color:#979595;font-weight: bold; font-size: 1.1em;'> © Yachai 2018, All Rights Reserved.</p>
					
				</div>
				
			";				
			$NombreReceptor = $Nombre;				
			$Asunto = "BIENVENIDO AL COMPENDIO";	
			
			$data = array('Cabecera' => $Cabecera ,'Cuerpo'=> $Cuerpo
						   , 'ColorFondo' => $ColorFondoEmail, 'Footer' => $Footer
						   , 'NombreReceptor' => $NombreReceptor, 'Asunto' => $Asunto
						   );			
			// $Email = "defs.centurion@gmail.com";	   
			TemplateEmailLayout($data,$Email,$cnPDO);
			
			// DCWrite($Cabecera.$Cuerpo);
			// DCCloseModal();
			DCWrite(Message("Process executed correctly","C"));
			
			return;
	}
	
	
	public function OrdenarContenido($Settings){
       	global $Conection, $DCTimeHour,$NameTable;
		
			$Codigo_Item = $Settings["Id_Edu_Componente"];
			$Id_Edu_Componente_Jerar = $Settings["Id_Edu_Componente_Jerar"];
			
				// echo " Query BBBB ".$Id_Edu_Componente_Jerar." <BR>";
				
			$Id_Edu_Almacen = $Settings["key"];
			
			$OrdenP = DCPost("Orden");

            if(empty($Settings["Id_Edu_Componente_Jerar"])){
				
				$Query = " 
				SELECT Id_Edu_Componente, Orden FROM edu_componente  
				WHERE 
				Id_Edu_Almacen = :Id_Edu_Almacen AND 
				Jerarquia_Id_Edu_Componente = :Jerarquia_Id_Edu_Componente  
				
				ORDER BY Orden ASC 	
				"; 
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen,"Jerarquia_Id_Edu_Componente"=>1];
				

					
			}else{
				
					
				$Query = " 
				
				SELECT Id_Edu_Componente, Orden FROM edu_componente  
				WHERE Id_Edu_Almacen = :Id_Edu_Almacen 	
				AND 
				Jerarquia_Id_Edu_Componente =:Jerarquia_Id_Edu_Componente  
				ORDER BY Orden ASC 	
				
				"; 
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen,"Jerarquia_Id_Edu_Componente"=>$Id_Edu_Componente_Jerar];				
			}
			
			
			$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			$cont = 0;
			$SesionN = 0;
			$ubicacionB = 0;
			$OrdenBD = "";
							
			// var_dump($Registro);
			
			foreach ($Registro as $Reg) {		
				$CodigoItemBD = $Reg->Id_Edu_Componente;
				
			
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
					$where = array('Id_Edu_Componente' => $CodigoItemBD);
					$rg = ClassPDO::DCUpdate('edu_componente', $reg , $where, $Conection,"");
					
									
				} else {
					
					$OrdenBD = $Reg->Orden;
					$ubicacionB = ($OrdenBD * 100 + 10);
					$reg = array(
						'Orden' => $ubicacionB
					);
					$where = array('Id_Edu_Componente' => $CodigoItemBD);
					$rg = ClassPDO::DCUpdate('edu_componente', $reg , $where, $Conection,"");
				}	
				
			}	

            if(empty($Settings["Id_Edu_Componente_Jerar"])){
				
				$Query = " 
			       SELECT Id_Edu_Componente, Orden FROM edu_componente  WHERE 
			       Id_Edu_Almacen = :Id_Edu_Almacen AND 
				   Jerarquia_Id_Edu_Componente = :Jerarquia_Id_Edu_Componente  
				   ORDER BY Orden ASC
				"; 
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen,"Jerarquia_Id_Edu_Componente"=>1];
				
			}else{
				
				$Query = " 
				
				SELECT Id_Edu_Componente, Orden FROM edu_componente  
				WHERE Id_Edu_Almacen = :Id_Edu_Almacen 	
				AND 
				Jerarquia_Id_Edu_Componente =:Jerarquia_Id_Edu_Componente  
				ORDER BY Orden ASC 	
				
				"; 
				$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen,"Jerarquia_Id_Edu_Componente"=>$Id_Edu_Componente_Jerar];	
				
			}
			
			$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			$Cont = 0;
			foreach ($Registro as $Reg) {
				
				$Cont += 1;
				$reg = array(
					'Orden' => $Cont
				);
				
				$where = array('Id_Edu_Componente' => $Reg->Id_Edu_Componente);
				$rg = ClassPDO::DCUpdate('edu_componente', $reg , $where, $Conection,"");
				
			}	
			
	}
	
			
	public function render($filename, $viewDataArray = '') {
		ob_start();
		if (is_array($viewDataArray)) {
			extract($viewDataArray, EXTR_OVERWRITE);
		}
		include_once $filename;
		$contenido = ob_get_contents();
		ob_get_clean();
		return $contenido;
	}
	
				
	public function MenuLocal($Id_Edu_Articulo,$Id_Edu_Almacen,$Id_Edu_Componente_S,$Perfil) {
		
		$Redirect = "/REDIRECT/edu-articulo-det";
				
		$UrlFile = "/sadministrator/edu-articulo-det";
		$UrlFile_Articulo = "/sadministrator/articulo";
		$UrlFile_Edu_Tipo_Componente = "/sadministrator/edu_tipo_componente";
		$UrlFile_Edu_Tipo_Estructura = "/sadministrator/edu_tipo_estructura";
		$UrlFile_Edu_Area_Conocimiento = "/sadministrator/edu_area_conocimiento";
		$UrlFile_Edu_Blog = "/sadministrator/edu-blog";
		$UrlFile_Edu_Participantes = "/sadministrator/edu-participante";
		$UrlFile_Edu_Participantes_New="/sadministrator/edu-participantenew";
		$UrlFile_Edu_Participante_Masivo="/sadministrator/edu-participante-masivo";
		$UrlFile_Edu_Examen = "/sadministrator/edu-examen";
		$UrlFile_Edu_Reporte = "/sadministrator/edu-reportes";
		$UrlFile_Edu_Acta_Nota="/sadministrator/edu-acta-notas";
		$UrlFile_Edu_Gestion_Certificado = "/sadministrator/edu-gestion-certificado";
		
		// echo $Perfil;
		
		if($Perfil == 1 || $Perfil == 2 || $Perfil == 777){

			$listMn = "<i class='zmdi zmdi-edit zmdi-hc-fw'></i> Configurar Curso [".$UrlFile_Articulo.$Redirect."/interface/Create/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
			$listMn .= "<i class='zmdi zmdi-chevron-right'></i> Otras Configuraciones  [/sadministrator/edu-configuracion-producto/interface/Configurar-Otros/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
			$listMn .= "<i class='zmdi zmdi-chevron-right'></i> Empresas  [/sadministrator/inhouse-empresa/interface/List/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
			$listMn .= "<i class='zmdi zmdi-link zmdi-hc-fw'></i> Generar Url del curso[".$UrlFile_Edu_Blog."/interface/Generar_Url_Amigable/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
			$listMn .= "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Configurar Acta de Notas [".$UrlFile."/interface/Configurar_Evaluar/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
			$listMn .= "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Configurar Aspectos de Evaluacion [/sadministrator/edu-acta-notas/interface/List_Aspecto/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
			$listMn .= " Estado de Curso [".$UrlFile."/interface/Visibilidad_Curso/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
			$listMn .= " Trasladar curso [".$UrlFile."/interface/edu_articulo_dup/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
			$listMn .= " Copiar Examen [".$UrlFile."/interface/edu_articulo_dup_confirmacion/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
			$btn = "<i class='zmdi zmdi-settings zmd-fw'></i> Configuracion ]SubMenu]{$listMn}]OPCIONES]]btn-simple-c}";
				
			
			$listMn = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Contenido [".$UrlFile."/interface/Create_Conten/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
			$listMn .= "<i class='zmdi zmdi-folder-outline zmdi-hc-fw'></i> Sub Carpeta [".$UrlFile."/interface/Create_Conten_SubItem/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
			$listMn .= "<i class='zmdi zmdi-file zmdi-hc-fw'></i> Documento [".$UrlFile."/interface/Create_Conten_SubItemB_Dcoc/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
			$listMn .= "<i class='zmdi zmdi-collection-video zmdi-hc-fw'></i> Vídeo Embebido Youtube[".$UrlFile."/interface/Create_Conten_SubItem_Embebido/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";					
			$listMn .= "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Artículo [".$UrlFile."/interface/Create_Conten_SubItem_Articulo_B/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[PanelA[HXMS[{";						
			$listMn .= "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Evaluación [".$UrlFile_Edu_Examen."/interface/Crea_Objeto_Evaluativo/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[PanelA[HXMS[{";
			$listMn .= "<i class='zmdi zmdi-account-circle zmdi-hc-fw'></i> Inscripción de Participante [".$UrlFile_Edu_Participantes."/interface/List/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
			$listMn .= "<i class='zmdi zmdi-accounts-add zmdi-hc-fw'></i> Inscripción Masiva de Participante [".$UrlFile_Edu_Participante_Masivo."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
			$listMn .= " <i class='zmdi zmdi-account-box-mail zmdi-hc-fw'></i> Gestión de Certificados [".$UrlFile_Edu_Gestion_Certificado."/interface/List/key/".$Id_Edu_Almacen."/tipo-producto/curso/request/on[_blank[HREF[{";
			$btn .= "<i class='zmdi zmdi-apps zmd-fw'></i> Opciones]SubMenu]{$listMn}]OPCIONES]]btn-simple-c}";


			$listMn = " Concurrencia [".$UrlFile."/interface/Analisis_Concurrencia/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
			$listMn .= " Asistencia por día [".$UrlFile_Edu_Reporte."/interface/Ingreso_Aula/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
			$listMn .= " Resultados <br> Evaluación [".$UrlFile_Edu_Reporte."/interface/Resultados_Evaluacion/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
			$listMn .= "Actas de Notas [".$UrlFile_Edu_Acta_Nota."/interface/Acta_Nota/key/".$Id_Edu_Almacen."[ScreenRight[PS[{";
			$listMn .= " Resumen Asistencia e Interacciones [".$UrlFile_Edu_Reporte."/interface/Resumen_Asistencia_Interacciones_View/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
			$btn .= "<i class='zmdi zmdi-chart zmdi-hc-fw'></i> Análisis ]SubMenu]{$listMn}]OPCIONES]]btn-simple-c}";
			$btnA = DCButton($btn, 'botones1', 'sys_form_principal');
		
		}
			
		return $btnA;
	
    }
	
	
	function Herramientas_Control($UrlFile,$Id_Edu_Almacen,$Id_Edu_Componente_S) {

		
		
				$Html ='
				
					<div class="card" style="width: 100%;">
					  <div class="card-header" style="padding:10px">
						Indicadores de Control
					  </div>
					  <ul class="list-group list-group-flush">
					  
					  ';
					  
				$Html .='						  
						<li class="list-group-item"> ';
						
							$btn = " <i class='zmdi zmdi-graduation-cap' style='font-size: 1.5em; color: #2b92e9;padding-right: 10px; padding-top: 4px;'></i> Certificado ]" .$UrlFile."/interface/Certificado_Alumno/key/".$Id_Edu_Almacen."/herramienta/certificado/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelA]]]SinEstilo}";
							$Html .= DCButton($btn, 'botones1', 'sys_form_btn_item_certificado');
				$Html .='						
						</li>';
						
				$Html .='						  
						<li class="list-group-item"> ';
						
							$btn = " <i class='zmdi zmdi-mouse' style='font-size: 1.5em; color: #2b92e9;padding-right: 10px; padding-top: 4px;'></i> Interacciones y Concurrencia ]" .$UrlFile."/interface/analisis/key/".$Id_Edu_Almacen."/herramienta/certificado/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelA]HXMS]]SinEstilo}";
							$Html .= DCButton($btn, 'botones1', 'sys_form_btn_item_analisis');
				$Html .='						
						</li>';		
						
				$Html .='						  
						<li class="list-group-item"> ';
						
							$btn = " <i class='zmdi zmdi-timer' style='font-size: 1.5em; color: #2b92e9;padding-right: 10px; padding-top: 4px;'></i> Limite de Acceso al Curso ]" .$UrlFile."/interface/accesos_configuracion/key/".$Id_Edu_Almacen."/herramienta/certificado/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelA]HXMS]]SinEstilo}";
							$Html .= DCButton($btn, 'botones1', 'sys_form_btn_item_accesos');
				$Html .='						
						</li>';								
						
				$Html .='						
					  </ul>
					</div>
				';
		        
				return $Html;
		
	}
	
	
}