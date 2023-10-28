 <?php
require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();
$DCTime=DCDate();

class Edu_Participante{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-participante";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
         $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];

        switch ($Process) { 
            case "ENTRY":

				switch ($Obj) {
					case "User_Register_Crud":

						$Data = array();
						$Data['key'] = $Parm["key"];	
						$Data['Id_Perfil_Educacion'] =  DCPost("Id_Perfil_Educacion");	

						//$Parm = array();
						//$Parm["clave"] = DCPost("Password");	
									
						//Edu_Register::Register_User($Data);
						$this->Proceso_inscripcion($Parm);
						

						$Settings["interface"] = "List";
						$Settings["key"] = $Parm["key"];
						new Edu_Participante($Settings);
						DCExit();
						// }
						
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
					case "User_Register_Crud":
					
						$key = $Parm["key"];
						$Id_User = $Parm["Id_User"];
						$Id_Entity = $_SESSION['Entity'];
						$Name_surnames = DCPost("Nombre");	
						$Passwordb = DCPost("Password");	
						$Password=trim($Passwordb);
						$Telefono = DCPost("Telefono");	
						$Email = DCPost("Email");
						$Sexo=DCPost("Id_User_Sexo");
						$Id_Modalidad_Venta_Curso=DCPost("Id_Modalidad_Venta_Curso");
						$Id_Edu_Pais=DCPost("Id_Edu_Pais");
						$ruta="";
						if ($Sexo==1) {
							$ruta="4ad89d8dh345s_hombre.png";
						}else if ($Sexo==2) {
							$ruta="4ad89d345s_mujer.png";
						}

						//Verifica la entidad
						$Query="SELECT  US.Email,UM.Id_User_Miembro,US.Id_User
								FROM user_miembro  UM
				                INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
								WHERE  UM.Entity=:Entity AND US.Id_User=:Id_User";
						$Where = ["Entity"=>$Id_Entity,"Id_User"=>$Id_User];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Email_Bd = $Row->Email;
						if(!empty($Email_Bd)){
									$reg = array(
										'Id_Edu_Pais'=>$Id_Edu_Pais,
										'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
										'Nombre' => $Name_surnames,
										'Id_Perfil_Educacion' => DCPost("Id_Perfil_Educacion"),
										'Telefono' => $Telefono,
										'Password' => $Password,
										'Foto'=>$ruta,
										'Id_User_Sexo'=> $Sexo
									);
									$where = array('Id_User' => $Id_User);
									$rg = ClassPDO::DCUpdate('user', $reg , $where, $Conection,"");	

									$reg = array(
										'Bk_Password' => $Password,
										'Id_Edu_Pais'=>$Id_Edu_Pais,
										'Nombre' => $Name_surnames,
										'Telefono' => $Telefono,
										'Foto'=>$ruta
									);
									$where = array('Id_User_Creation' => $Id_User);
									$rg = ClassPDO::DCUpdate('user_miembro', $reg , $where, $Conection,"");	
									
									//Cambio de perfil de usuario
			                        $Id_Suscripcion = $Parm["Id_Suscripcion"];
									
									$reg = array(
									'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
									'Id_Perfil_Educacion' => DCPost("Id_Perfil_Educacion"),
									'Producto_Origen'=>"CURSO",
									'Fecha_Fin'=>DCPost("Fecha_Fin"),
									);
									$where = array('Id_Suscripcion' => $Id_Suscripcion);
						
									$rg = ClassPDO::DCUpdate("suscripcion", $reg , $where, $Conection);

									//Verifica si quiere realizar cambio de correo
									$Id_Entity = $_SESSION['Entity'];
									$Query="SELECT  US.Email
											FROM user_miembro  UM
							                INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
											WHERE  UM.Entity=:Entity AND US.Id_User=:Id_User";
									$Where = ["Entity"=>$Id_Entity,"Id_User"=>$Id_User];
									$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
									$Email_Bd = $Row->Email;
										if($Email_Bd!=$Email){
											$mensajeNew="No se permite actualizar el correo del usuario";
										}else{
											$mensajeNew="";
										}

									if($mensajeNew=="") {
										$Mensaje="Se actualizo la información";
									}else{
										$Mensaje=$mensajeNew;
									}

						}else{
							$Mensaje="No se encuentra este usuario en la escuela";
						}
						
						


						DCWrite(Message($Mensaje,"C"));

					
						$Settings["interface"] = "List";
						// $Settings["REDIRECT"] = $Redirect;
						$Settings["key"] = $Parm["key"];
						new Edu_Participante($Settings);
						DCExit();	
							 
						break;							
				}
				

                break;
				
            case "DELETE":
			
				switch ($Obj) {
					case "User_Register_Crud":
						
						$this->ObjectDelete($Parm);
									
						$Settings["interface"] = "List";
						$Settings["key"] = $Parm["key"];
						new Edu_Participante($Settings);
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
        
            case "List":
			
				$Name_Interface = "Listado de Participantes";	
			    
				$Id_Edu_Almacen = $Parm["key"];
				
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/interface/Create/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);

				
				// $Query = "
				
				    // SELECT UM.Nombre, US.Id_User AS CodigoLink , US.Usuario_Login,  SC.Estado, UM.Date_Time_Creation
					// FROM suscripcion SC
					// INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
					// INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
					// WHERE SC.Id_Edu_Almacen = :Id_Edu_Almacen

				// ";  

				
				$Query = "
				
				    SELECT UM.Nombre, PE.Nombre AS Perfil, SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login,  SC.Estado, UM.Date_Time_Creation
					FROM suscripcion SC
					INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
					LEFT JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
					WHERE SC.Id_Edu_Almacen = :Id_Edu_Almacen

				";  

				
				$Class = 'table table-hover';
				$LinkId = 'Id_Suscripcion';
				$Link = $UrlFile."/interface/Create_Edit/key/".$Id_Edu_Almacen."";
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;			

            
            case "Create":
			
				$Id_Edu_Almacen = $Parm["key"];	
				$Id_Suscripcion = $Parm["Id_Suscripcion"];	

				
				$btn .= "Atrás]" .$UrlFile."/interface/List/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Tipo_Privacidad = $Parm["Id_Edu_Tipo_Privacidad"];
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/User_Register_Crud/key/".$Id_Edu_Almacen;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/key/".$Id_Edu_Almacen;
				
				if(!empty($Id_Edu_Tipo_Privacidad)){
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


            case "Create_Edit":
			
				$Id_Edu_Almacen = $Parm["key"];			
				$Id_Suscripcion = $Parm["Id_Suscripcion"];	
				$Query = "
				
					SELECT UM.Nombre, US.Id_User  ,SC.Id_User as User_Miembro, US.Usuario_Login,  SC.Estado, UM.Date_Time_Creation
					FROM suscripcion SC
					INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
					WHERE SC.Id_Suscripcion = :Id_Suscripcion
				";  
				$Where = ["Id_Suscripcion"=>$Id_Suscripcion];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_User = $Row->Id_User;
				$Id_User2 = $Row->User_Miembro;						
				
				
				
				$btn .= "Atrás]" .$UrlFile."/interface/List/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Tipo_Privacidad = $Parm["Id_Edu_Tipo_Privacidad"];
				
				$DirecctionA = $UrlFile."/Process/CHANGE/Obj/User_Register_Crud/key/".$Id_Edu_Almacen."/Id_User/".$Id_User."/Id_Suscripcion/".$Id_Suscripcion;
				
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/key/".$Id_Edu_Almacen."/Id_User/".$Id_User2."/Id_Suscripcion/".$Id_Suscripcion;
				
				if(!empty($Id_User)){
				    $Name_Interface = "Editar Participante";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","User_Register_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Participante";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
  						
				}
				
				
				
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
		        $Form1 = BFormVertical("User_Register_Crud",$Class,$Id_User,$PathImage,$Combobox,$Buttons,"Id_User");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;	
				

            case "DeleteMassive":
		
		        $Id_User = $Parm["Id_User"];
		        $key = $Parm["key"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_User/".$Id_User."/Obj/User_Register_Crud/key/".$key."]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/List]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;					
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_User = $Settings["Id_User"];
		$key = $Settings["key"];
		$Id_Entity = $_SESSION['Entity'];
		// Verifica la entidad
		$Query="SELECT  US.Email,UM.Id_User_Miembro,US.Id_User
								FROM user_miembro  UM
				                INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
								WHERE  UM.Entity=:Entity AND UM.Id_User_Miembro=:Id_User";
		$Where = ["Entity"=>$Id_Entity,"Id_User"=>$Id_User];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Email_Bd = $Row->Email;
		if(!empty($Email_Bd)){

					// Verifica si es cliente antiguo 
					$Query="SELECT  count(*) as Conteo, UM.Id_User_Creation
								     FROM suscripcion SUS
								     INNER JOIN user_miembro UM on SUS.Id_User=UM.Id_User_Miembro
				                	 INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
			                     where SUS.Entity=:Entity AND SUS.Id_User=:user";
					$Where = ["Entity"=>$Id_Entity,"user"=>$Id_User];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Conteocorreo = $Row->Conteo;
					$Id_User_Creation = $Row->Id_User_Creation;

					$Mensaje="";
					if ($Conteocorreo==1 ) {
						//Eliminacion suscripcion

						$where = array('Id_Edu_Almacen'=>$key,'Id_User' =>$Id_User);
						$rg = ClassPDO::DCDelete('suscripcion', $where, $Conection);


						$where = array('Id_User' =>$Id_User_Creation);
						$rg = ClassPDO::DCDelete('user', $where, $Conection);
						
						$where = array('Id_User_Miembro' =>$Id_User);
						$rg = ClassPDO::DCDelete('user_miembro', $where, $Conection);
						$MensajeB="Usuario Eliminado";
					}else{
						//Eliminacion suscripcion

						$where = array('Id_Edu_Almacen'=>$key,'Id_User' =>$Id_User);
						$rg = ClassPDO::DCDelete('suscripcion', $where, $Conection);
						$MensajeB="El participante ya no pertenece al curso";
					}
					$Mensaje=$MensajeB;


		}else{
			$Mensaje="Este usuario no pertenece a esta escuela";
		}

		DCWrite(Message($Mensaje,"C"));
						
	}
	public function Proceso_inscripcion($Settings) {
       	global $Conection, $DCTimeHour,$NameTable,$DCTime;
       	$User = $_SESSION['User'];
		$Id_Entity = $_SESSION['Entity'];
		$Name_surnames = DCPost("Nombre");	
		$Correo = DCPost("Email");
		$PasswordO= DCPost("Password");	
		$Password=trim($PasswordO);
		$Telefono = DCPost("Telefono");	
		$Sexo=DCPost("Id_User_Sexo");
		$Fecha_Fin=DCPost("Fecha_Fin");
		$Id_Modalidad_Venta_Curso=DCPost("Id_Modalidad_Venta_Curso");
		$Id_Edu_Pais=DCPost("Id_Edu_Pais");
		$ruta="";$MensajeA="";
		if ($Sexo==1) {
				$ruta="4ad89d8dh345s_hombre.png";
		}else if ($Sexo==2) {
				$ruta="4ad89d345s_mujer.png";
		}	
		$Id_Edu_Almacen = $Settings["key"];	
		///Comprueba si existe en la entidad	

		$Query="SELECT  US.Email,UM.Id_User_Miembro,US.Id_User
				FROM user_miembro  UM
                INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
				WHERE  UM.Entity=:Entity AND UM.Email=:correo";
		$Where = ["Entity"=>$Id_Entity,"correo"=>$Correo];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Email_Bd = $Row->Email;
		$Id_User_MiembroP = $Row->Id_User_Miembro;
		$Id_UserP = $Row->Id_User;
			if(empty($Email_Bd)){
							$data = array(
								'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
								'Id_Edu_Pais' => $Id_Edu_Pais,
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
								'Entity' => $Id_Entity,
								'Id_Entity' => $Id_Entity,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user", $data, $Conection);
						    $Id_User = $Result["lastInsertId"];	
								
							$data = array(
								'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
								'Id_Edu_Pais' => $Id_Edu_Pais,
								'Bk_Password' => $Password,
								'Nombre' => $Name_surnames,
								'Email' => $Correo,
								'Celular' => $Telefono,
								'Telefono'=>$Telefono,
								'Foto'=> $ruta,
								'Id_Perfil' => 3,
								'Id_Perfil_Educacion' => DCPost("Id_Perfil_Educacion"),
								'Entity' => $Id_Entity,
								'Id_User_Creation' => $Id_User,
								'Id_User_Update' => $Id_User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user_miembro", $data, $Conection);	
						    $Id_User_Miembro_New = $Result["lastInsertId"];
							//Vinculo de matricula
							$data = array(
								'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
								'Producto_Origen'=>"CURSO",
								'Fecha_Inicio'=>$DCTime,
								'Fecha_Fin'=>$Fecha_Fin,
								'Id_Perfil_Educacion' => DCPost("Id_Perfil_Educacion"),
								'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
								'Estado' => "Matriculado",
								'Visibilidad'=>"Activo",
								'Id_User' => $Id_User_Miembro_New,
								'Entity' => $Id_Entity,
								'Id_User_Update' => $User,
								'Id_User_Creation' => $User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
							);
						$Return = ClassPDO::DCInsert("suscripcion", $data, $Conection);	
						$MensajeA="Se ha registrado a un nuevo usuario".$Correo;
			}else{

					$Query="SELECT  US.Usuario_Login,SUS.Id_User
					     FROM suscripcion SUS
					     INNER JOIN user_miembro UM on SUS.Id_User=UM.Id_User_Miembro
	                	 INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
						 WHERE SUS.Id_Edu_Almacen=:ID_Almacen AND SUS.Entity=:Entity AND US.Email=:correo AND SUS.Producto_Origen=:Producto_Origen";
					$Where = ["ID_Almacen"=>$Id_Edu_Almacen,"Entity"=>$Id_Entity,"correo"=>$Correo,"Producto_Origen"=>"CURSO"];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Email_Bd = $Row->Usuario_Login;
					$MensajeB="";
						if(empty($Email_Bd)){
							//Vinculo de matricula por curso
							$data = array(
								'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
								'Producto_Origen'=>"CURSO",
								'Fecha_Inicio'=>$DCTime,
								'Fecha_Fin'=>$Fecha_Fin,
								'Id_Perfil_Educacion' => DCPost("Id_Perfil_Educacion"),
								'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
								'Estado' => "Matriculado",
								'Visibilidad'=>"Activo",
								'Id_User' => $Id_User_MiembroP,
								'Entity' => $Id_Entity,
								'Id_User_Update' => $User,
								'Id_User_Creation' => $User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
							);
							$Return = ClassPDO::DCInsert("suscripcion", $data, $Conection);	
							$MensajeB="Usuario Matriculado ".$Correo."<br>";
							//Actualizacion de clave 
							$reg = array('Password' => $Password,'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,'Id_Edu_Pais' => $Id_Edu_Pais);
							$where = array('Id_User' => $Id_UserP);
							$rg = ClassPDO::DCUpdate('user', $reg , $where, $Conection,"");
							//Actualizacion de clave BK
							$reg = array('Bk_Password' => $Password,'Id_Edu_Pais' => $Id_Edu_Pais);
							$where = array('Id_User_Creation' => $Id_UserP);
							$rg = ClassPDO::DCUpdate('user_miembro', $reg , $where, $Conection,"");
							
							$MensajeB .="Clave Actualizada del usuario ".$Correo;

						}else{
								$MensajeB ="NO HAY DATOS";	
						}
					if ($MensajeB=="NO HAY DATOS") {
							$MensajeA="Ya esta matriculado a este curso";
					}else{
							$MensajeA=$MensajeB;
					}
					//var_dump($MensajeA);

			}
				DCWrite(Message($MensajeA,"C"));  
			
	}
		
	
	
	
}