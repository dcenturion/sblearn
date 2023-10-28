<?php
// session_start();
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Register{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-register";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$UrlFile_Admin_Home = "/sadministrator/admin_home";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];
		
        switch ($Process) {
            case "ENTRY":
			  
				switch ($Obj) {
							
					case "Obj_Register":
							
							$Name_surnames = DCPost("name_surnames"); 
							$Email = DCPost("email"); 
							$Password = DCPost("password"); 
							$PasswordB = DCPost("passwordB"); 
							
							if(empty($Name_surnames)){
                                DCWrite(Message("Debe insertar sus nombres y apellidos","C")); DCExit();
							}
 							if(empty($Email)){
                                DCWrite(Message("Debe insertar su email","C")); DCExit();
							}
 							if(empty($Password)){
                                DCWrite(Message("Debe insertar su contraseña","C")); DCExit();
							}							
 							if(empty($PasswordB)){
                                DCWrite(Message("Debe repetir su contraseña","C")); DCExit();
							}							
														
						    // var_dump($Parm);
							
							$Id_User = $this->Register_User($Parm);
							
							// $Parm["Id_User"] = $Id_User;
							// $Id_User = $this->Email_Register($Parm);		
							
							Login::Iniciar_Sesion($Parm);
							
							
							// DCWrite($Id_User);
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
							$Settings['Url'] = "/sadministrator/det_admin_object/Interface/Details/Id_Object/".$Id_Object;
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
					case "Warehouse":
						
						$this->ObjectDeleteBlock($Parm);
						
						DCCloseModal();
									
						$Settings["Interface"] = "";
					    new DetAdminToolsSite($Settings);
						
						
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
			    $Form = $this->FormLocal($Parm,$UrlFile);
	
				$Form = DCModalForm("Regístrate",$DCPanelTitle . $Form ,"");

				if($Parm["request"] == "on"){
					DCWrite($layout->main($Form,$datos));
				}else{
					DCWrite($Form);			
				}
						
				exit();
		
		
                break;
				

            case "Recover_Password":
			
			    $Form = $this->Form_Recover_Psssword($Parm,$UrlFile);
	  
				$Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form ,"");
				DCWrite($Html);	    
				
                break;
	
	
            case "Recover_Password_Solicitud":
				
                break;	
				

            case "Edit":
			
                break;	
				
            case "DeleteMassive":
	
                break;				
				
			
        }
				
		
		
	}
	
	public function Email_Register(){
	    global $Conection,$DCTimeHour;	
		
			$Email = DCPost("email");
			$Id_Entity = $_SESSION['Entity'];
			

            $Row = SetttingsSite::Main_Data_EU($Conection,$Email);			
			
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
			$Usuario_Login = $Row->Usuario_Login;	
			$Password = $Row->Password;	
			
			$dominio = DCUrl();
			
			// if( $ColorCabeceraEmail == "Ninguno"){  
				$ColorCabeceraEmail = "#fff";
			// }
	
			$Cabecera = "
				<div style='background-color:".$ColorCabeceraEmail.";padding:50px 50px 20px 50px;text-align:center;'>
					<img src='".$dominio."sadministrator/simages/".$ImagenLogo."' >
					<br>
					<hr style='height: 1px;background-color:#ccc;border: 1px;'></hr>
					<br>
					<h1 style='color:#000;font-size:1.5em;'>Registro exitoso!!</h1>
					
				</div>
			";
			
			$Cuerpo = "
				
				<div style='background-color:".$ColorCabeceraEmail.";padding:20px 50px 0px 50px;'>
					<p style='font-size:1.2em;'>Hola ".$Nombre .",</p>					
					<p style='font-size:1.2em;'>Estamos muy contentos de que haya decidido utilizar <b>Yachai</b>.<br><br>
					Seguimos trabajando en multiples productos y servicios, con el fin de contribuir en tu éxito como persona, profesional y empresario.</p>	
                    <p style='font-size:1.2em;'>
					<br>
					<b>DATOS DE ACCESO</b>
					<br>USUARIO: ".$Usuario_Login."
                    <br>CONTRASEÑA: ".$Password."
					</p>
                    <p style='font-size:1.2em;'>
					<br>¡ Que tengas un gran día !<br>
                    <br>¡ El equipo de Yachai  !
					</p>
					<br>
					<table align='center' width='100%'>
						<tr>
							<td style='padding:30px 30px 20px 30px;'>
								<table align='center'>
									<tr>
										<td style='background-color:#000;padding: 10px;'>
											<a href='".$dominio."' style='font-size:12pt;height: 30px;color: white;text-decoration: none;padding: 8px 10px;font-family: arial;' >
											INGRESAR
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
			$Asunto = "REGISTRO EXITOSO";	
			
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

    public function Register_User($Parm){
	    global $Conection,$DCTimeHour;
		
		$Id_Entity = $_SESSION['Entity'];
		   
		$Name_surnames = DCPost("name_surnames");
		if(empty($Name_surnames)){
		    $Name_surnames = DCPost("Nombre");			
		}
		
		$Email = DCPost("email");
		if(empty($Email)){
		    $Email = DCPost("Email");	
		}		
		
		
		$Password = DCPost("password");
		if(empty($Password)){
		    $Password = DCPost("Password");	
		}		
				
		$PasswordB = DCPost("passwordB");
		if(empty($PasswordB)){
		    $PasswordB = DCPost("Password");	
		}			
	
		$Query = "
		
			SELECT 
			US.Usuario_Login
			, US.Password
			, ET.Id_Entity
			, US.Id_User
			, UM.Id_Perfil
			, UM.Id_User_Miembro
			FROM user_miembro UM
			INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
			INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
			WHERE 
			US.Email = :Email 
			AND ET.Id_Entity = :Id_Entity

		"; 
		$Where = ["Email"=>$Email,"Id_Entity"=>$_SESSION['Entity']];
		$Reg = ClassPDO::DCRow($Query,$Where ,$Conection);	
		
		if(empty($Reg->Usuario_Login)){
			
			$data = array(
			'Nombre' => $Name_surnames,
			'Email' => $Email,
			'Usuario_Login' => $Email,
			'Password' => $Password,
			'Estado' => "Comprobando",
			'Id_Perfil' => 3,
			'Entity' => $Id_Entity,
			'Id_Entity' => $Id_Entity,
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			$Result = ClassPDO::DCInsert("user", $data, $Conection);
	     	$Id_User = $Result["lastInsertId"];	
			
			
			$data = array(
			'Nombre' => $Name_surnames,
			'Email' => $Email,
			'Id_Perfil' => 3,
			'Entity' => $Id_Entity,
			'Id_User_Creation' => $Id_User,
			'Id_User_Update' => $Id_User,
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			$Result = ClassPDO::DCInsert("user_miembro", $data, $Conection);	
	     	$Id_User_Miembro = $Result["lastInsertId"];
			
			return $Id_User_Miembro; 
		}else{
			return "";			
			DCWrite(Message("El email ya existe","C"));
			// DCExit();
			
		}
         
			
	}

	
	public function FormLocal($Settings,$UrlFile) {
		
	    global $Conection,$DCTimeHour;
		
        $Redirect = "";	
		$Id_Object = $Settings["Id_Object"];
        $Entity = $_SESSION['Entity'];
		$Key = $Settings["key"];
		$Id_Edu_Componente_S = $Settings["Id_Edu_Componente_S"];
		
		if(empty($Key)){
			$Url = "";
		}else{
			$Url = "/key/".$Key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S;
		}
		
        $IdForm = "Obj_Register";	
        $IdButton = "button_animatedModal5";	
	
        $Direcction = $UrlFile.$Redirect."/Process/ENTRY/Obj/".$IdForm."".$Url;	
		
		// Type_Process_BD
        $Screen = "Msg_0";	
        $Class = "btn btn-info btn-block";	
		$NameButton = "Regístrate";
		
		$Query = "
			SELECT 
			ET.Id_Entity, ET.Nombre 
			FROM entity ET
			WHERE ET.Id_Entity = :Id_Entity			
		";	
		$Where = ["Id_Entity" => $Entity];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Nombre = $Row->Nombre;
				
		
		$Html = '
           <div class="authentication-content m-b-30">
            <h3 class="m-t-0 m-b-30 text-center">Forma parte de '.$Nombre.' </h3>
			<form id="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >	
              <div class="form-group">
                <label for="form-control-1">Nombres y Apellidos</label>
                <input type="text" name="name_surnames" class="form-control" id="form-control-1" placeholder="Nombres y Apellidos" >
              </div>
              <div class="form-group">
                <label for="form-control-1">Email</label>
                <input type="email" name="email" class="form-control" id="form-control-1" placeholder="Email" >
              </div>			  
              <div class="form-group">
                <label for="form-control-2">Contraseña</label>
                <input type="password" name="password" class="form-control" id="form-control-2" placeholder="Contraseña">
              </div>
              <div class="form-group">
                <label for="form-control-2">Repetir Contraseña</label>
                <input type="password" name="passwordB" class="form-control" id="form-control-2" placeholder="Repetir Contraseña">
              </div>			  
              <div class="form-group">
						
			
			  </div>
              <button type="submit" onclick=SaveForm(this); direction="'.$Direcction.'" screen="'.$Screen.'"  class="'.$Class.'"  id="'.$IdButton.'" form="'.$IdForm.'"  >'.$NameButton.'</button>
            </form>
          </div>
		  
		  <div id="Msg_0">
          </div>
	    ';
		
		
		return $Html;
	}
	

	public function Form_Recover_Psssword($Settings,$UrlFile) {
		
	    global $Conection,$DCTimeHour;
		
        $Redirect = "";	
		$Id_Object = $Settings["Id_Object"];
		$Entity = $Settings["Entity"];
		
        $IdForm = "Obj_Login";	
        $IdButton = "button_animatedModal5";	
	
        $Direcction = $UrlFile.$Redirect."/Interface/Recover_Password_Solicitud/Obj/".$IdForm."/Entity/".$Entity;	
		
		// Type_Process_BD
        $Screen = "Msg_0";	
        $Class = "btn btn-info btn-block";	
		$NameButton = "Recuperar Contraseña";
		
		$Html = '
           <div class="authentication-content m-b-30">
            <h3 class="m-t-0 m-b-30 text-center">Recuperar Contraseña</h3>
			<form id="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >	
              <div class="form-group">
                <label for="form-control-1">Enviar solicitud al Email</label>
                <input type="email" name="email" class="form-control" id="form-control-1" placeholder="Email" >
              </div>
            
            
              <button type="submit" onclick=SaveForm(this); direction="'.$Direcction.'" screen="'.$Screen.'"  class="'.$Class.'"  id="'.$IdButton.'" form="'.$IdForm.'"  >'.$NameButton.'</button>
            </form>
          </div>
		  
		  <div id="Msg_0">
		  
          </div>
		  
		  
	    ';
		
		
		return $Html;
	}	
	
	public function ObjectDeleteBlock($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Warehouse = DCPost("ky");
		$columnas='';
		for ($j = 0; $j < count($Id_Warehouse); $j++) {
			
			$Query = " SELECT Name AS Warehouse FROM warehouse WHERE Id_Warehouse = :Id_Warehouse ";
			$Where = ["Id_Warehouse"=>$Id_Warehouse[$j]];
			$Reg = ClassPDO::DCRow($Query,$Where ,$Conection);			
			// DCWrite("Warehouse:: ".$Reg->Warehouse."<br>");
			ClassPDO::DCDrop($Reg->Warehouse,$Conection);		

			$where = array('Id_Warehouse' =>$Id_Warehouse[$j]);
			$rg = ClassPDO::DCDelete('warehouse', $where, $Conection);
			
		}

		DCWrite(Message("Process executed correctly","C"));
						
	}	
	
	
	public function ObjectEntry($Settings) {
       	global $Conection, $DCTimeHour;
        
		$Object = DCPost("Name");
		$Id_Type_Form = DCPost("Id_Type_Form");

		$data = array(
		'Name' => $Object,
		'Id_Type_Form' => $Id_Type_Form,
		'Id_Warehouse' => $Settings["Id_Warehouse"],
		'State' => "A",
		'Date_Time_Creation' => $DCTimeHour,
		'Date_Time_Update' => $DCTimeHour
		);
		$Result = ClassPDO::DCInsert("object", $data, $Conection);
		$Return = $Result["lastInsertId"];	



        $Query = "SELECT WD.Id_Warehouse_Detail, WD.Name, WD.Id_Data_Type, WD.Length, WD.Pk  
		          FROM warehouse_detail WD 
				  WHERE WD.Id_Warehouse = :Id_Warehouse
				  ";	
		$Where = ["Id_Warehouse" => $Settings["Id_Warehouse"]];
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		foreach($Rows AS $Field){
		    $Count += 1;
			
			$Visibility = "SI";	
			
			if( $Field->Name =="Date_Time_Update" ||  $Field->Name =="Date_Time_Creation"  ||  $Field->Name =="Id_User_Update"   ||  $Field->Name =="Entity"  ||  $Field->Name =="Id_User_Creation" ){
				$Visibility = "NO";
			}
			
			if($Field->Pk == "SI"){
				$Visibility = "NO";			
			}
			
			if($Field->Name =="Date_Time_Creation"  ||  $Field->Name =="Entity"  ||  $Field->Name =="Id_User_Creation" ){
				$Type_Process_BD = "INSERT";
			}else{
				$Type_Process_BD = "UPDATE";				
			}
			
			$data = array(
			'Id_Warehouse_Detail' => $Field->Id_Warehouse_Detail,
			'Alias' => $Field->Name,
			'Id_Object' => $Return,
			'Id_Data_Type' => $Field->Id_Data_Type,
			'Id_Field_Type' => 1,
			'Pk' => $Field->Pk,
			'Visibility' => $Visibility,
			'State' => "SI",
			'OrderP' => $Count,
			'Type_Process_BD' => $Type_Process_BD,
			'Read_Only' => "SI",
			'Date_Time_Creation' => $DCTimeHour,
			'Date_Time_Update' => $DCTimeHour
			);
			$ResultB = ClassPDO::DCInsert("object_detail", $data, $Conection);	
		
        }			
	
		DCWrite(Message("Process executed correctly","C"));	
				
		return $Return;
						
	}	

	public function ObjectChange($Settings) {
       	global $Conection, $DCTimeHour;

		$reg = array(
		'Name' => DCPost("Name"),
		'Id_Type_Form' => DCPost("Id_Type_Form")
		);
		$where = array('Id_Object' =>$Settings["Id_Object"]);
		$rg = ClassPDO::DCUpdate("object", $reg , $where, $Conection);

		DCWrite(Message("Process executed correctly ".$Settings["Id_Object"]."","C"));
		return $Settings["Id_Object"];						
	}			

	

	
}