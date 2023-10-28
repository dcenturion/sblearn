<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();


class UserAdministrator{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/user_administrator";
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
					case "User_Register_Crud":
					
		                    $Data = array();
													
				
						    DCCloseModal();		

                            $Email = DCPost("Email");
                            $Password = DCPost("Password");
                            $Nombres = DCPost("Nombre");
							
							if(empty($Email)){
							    DCWrite(Message("Llenar el campo Email","C"));
								exit();
							}
							
							if(empty($Password)){
							    DCWrite(Message("Llenar el campo Password","C"));
								exit();
							}				
							
							if(empty($Nombres)){
							    DCWrite(Message("Llenar el campo Nombres","C"));
								exit();
							}							
							
							$this->Proceso_inscripcion($Parm);									
												
							$Settings["Interface"] = "";
							new UserAdministrator($Settings);
							DCExit();	
						
					break;							
							
				}		
			break;
			case "CHANGE":
				switch ($Obj) {
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
										'Nombre' => $Name_surnames,
										'Telefono' => $Telefono,
										'Foto'=>$ruta
									);
									$where = array('Id_User_Creation' => $Id_User);
									$rg = ClassPDO::DCUpdate('user_miembro', $reg , $where, $Conection,"");	
									

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
						DCCloseModal();	
						DCWrite(Message($Mensaje,"C"));

					
						$Settings["Interface"] = "";
						new UserAdministrator($Settings);
						DCExit();
					break;							
				}
			break;






            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "User_Register_Crud":
						
						$this->ObjectDelete($Parm);
						
						DCCloseModal();		
						$Settings["Interface"] = "";
					    new UserAdministrator($Settings);
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
				
				$listMn = "<i class='icon-chevron-right'></i> Tipo de Componente [".$UrlFile_Edu_Tipo_Componente.$Redirect."/Interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Tipo de Estructura [".$UrlFile_Edu_Tipo_Estructura.$Redirect."/Interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Area de Conocimiento [".$UrlFile_Edu_Area_Conocimiento.$Redirect."/Interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Sub Línea [".$UrlFile_Edu_Sub_Linea.$Redirect."/Interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Productores [".$UrlFile_Edu_Productor.$Redirect."/Interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Perfiles Educativos [".$UrlFile_Edu_Perfil_Educacion."/interface/List[animatedModal5[HXM[{";
												
				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear ]" .$UrlFile."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("USUARIOS","Administración de Usuarios",$btn);

                if($User == 7370 ){
					
					$Query = "
					SELECT 
						US.Id_User AS CodigoLink
						, UM.Nombre
						, UM.Email
						, PE.Nombre AS Perfil
						FROM user_miembro UM
						INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
						INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
						LEFT JOIN perfil PE ON UM.Id_Perfil = PE.Id_Perfil
					WHERE 
					UM.Entity = :Entity 
					AND US.Id_User_Creation = :Id_User_Creation 
					
					"; 		
					
					$Class = 'table table-hover';
					$LinkId = 'Id_User';
					$Link = $UrlFile."/Interface/Create";
					$Screen = 'animatedModal5';

					$Where = ["Entity"=>$Entity, "Id_User_Creation"=>$User];	
					$Content = DCDataGrid('', $Query, $Where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','');
					 				
				
				}else{

					$Query = "
					SELECT 
						US.Id_User AS CodigoLink
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
					$Link = $UrlFile."/Interface/Create";
					$Screen = 'animatedModal5';

					$Where = ["Entity"=>$Entity];	
					$Content = DCDataGrid('', $Query, $Where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','');
					 
				}
				 
				 
				$Plugin = DCTablePluginA();				
				
				$Contenido = DCPage($DCPanelTitle , $Content .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;
				
        		
            case "Create":
			     
				 
	            $Id_User = $Parm["Id_User"];
	            $Id_Entity = $_SESSION['Entity'];		
				$Id_Suscripcion = $Parm["Id_Suscripcion"];	
				$Query = "
				
					SELECT 
				    US.Id_User, UM.Id_User_Miembro as User_Miembro
					FROM user_miembro UM
					INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
					WHERE 
					UM.Entity = :Id_Entity and UM.Id_User_Creation=:Id_User
				";  
				$Where = ["Id_User"=>$Id_User,"Id_Entity"=>$Id_Entity];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_User2 = $Row->User_Miembro;		
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_User/".$Id_User2;
				
				if(!empty($Id_User)){
					
				    $Name_Interface = "Editar Datos del Usuario";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","User_Register_Crud","btn btn-default m-w-120");	
                    $DirecctionA = $UrlFile."/Process/CHANGE/Obj/User_Register_Crud/Id_User/".$Id_User;				
				
				}else{
					
				    $Name_Interface = "Crear Usuario";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
				    $DirecctionA = $UrlFile."/Process/ENTRY/Obj/User_Register_Crud";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","User_Register_Crud");	

				}
				
				
				
				$Combobox = array(
					 array("Id_User_Sexo","SELECT Id_User_Sexo AS Id, Nombre AS Name FROM user_genero",[]),
				     // array("Id_Publicacion_Blog_Categoria"," SELECT Id_Publicacion_Blog_Categoria AS Id, Nombre AS Name FROM publicacion_blog_categoria ",[]),
	                 array("Id_Perfil"," SELECT Id_Perfil_Educacion AS Id, Nombre AS Name FROM perfil_educacion ",[]),
	                 array("Id_Edu_Pais"," SELECT Id_Edu_Pais AS Id, Nombre AS Name FROM edu_pais ",[]),
	                 array("Id_Modalidad_Venta_Curso"," SELECT Id_Modalidad_Venta_Curso AS Id, Nombre AS Name FROM Modalidad_Venta_Curso ",[]),
					 
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
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
		
		        $Id_User = $Parm["Id_User"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Obj/User_Register_Crud/Id_User/".$Id_User."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";							
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;				
				
        }
				
		
		
	}
	

	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_User = $Settings["Id_User"];
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
						$MensajeB="El participante no se puede eliminar";
						
					}else{

						$where = array('Id_User' =>$Id_User_Creation);
						$rg = ClassPDO::DCDelete('user', $where, $Conection);
						
						$where = array('Id_User_Miembro' =>$Id_User);
						$rg = ClassPDO::DCDelete('user_miembro', $where, $Conection);
						$MensajeB="Usuario Eliminado";
					}
					$Mensaje=$MensajeB;


		}else{
			$Mensaje="Este usuario no pertenece a esta escuela";
		}

		DCWrite(Message($Mensaje,"C"));
						
	}





	public function Proceso_inscripcion($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;
       	$User = $_SESSION['User'];
		$Id_Entity = $_SESSION['Entity'];
		$Name_surnames = DCPost("Nombre");	
		$Correo = DCPost("Email");
		$PasswordO= DCPost("Password");	
		$Password=trim($PasswordO);
		$Telefono = DCPost("Telefono");	
		$Sexo=DCPost("Id_User_Sexo");
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
								'Id_User_Creation' => $User,
								'Id_User_Update' => $User,								
								'Id_Entity' => $Id_Entity,
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
								'Entity' => $Id_Entity,
								'Id_User_Creation' => $Id_User,
								'Id_User_Update' => $Id_User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user_miembro", $data, $Conection);	
						    $Id_User_Miembro_New = $Result["lastInsertId"];
						$MensajeA="Se ha registrado a un nuevo usuario ".$Correo;
			}else{

					$Query="SELECT  US.Usuario_Login,SUS.Id_User
					     FROM suscripcion SUS
					     INNER JOIN user_miembro UM on SUS.Id_User=UM.Id_User_Miembro
	                	 INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
						 WHERE SUS.Id_Edu_Almacen=:ID_Almacen AND SUS.Entity=:Entity AND US.Email=:correo";
					$Where = ["ID_Almacen"=>$Id_Edu_Almacen,"Entity"=>$Id_Entity,"correo"=>$Correo];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Email_Bd = $Row->Usuario_Login;
					$MensajeB="";
						if(empty($Email_Bd)){
							
	
							//Actualizacion de clave 
							$reg = array('Password' => $Password);
							$where = array('Id_User' => $Id_UserP);
							$rg = ClassPDO::DCUpdate('user', $reg , $where, $Conection,"");	
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