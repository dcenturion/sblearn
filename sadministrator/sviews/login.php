<?php
//session_start();
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Login{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/login";
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
							
					case "Obj_Login":
					         
							$this->Iniciar_Sesion($Parm);
							// DCVd($Where);
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
			
			    $Form = $this->FormLocal($Parm,$UrlFile);
	
				$Html = DCModalForm("Iniciar Sesión",$DCPanelTitle . $Form ,"");
				DCWrite($Html);
		
		
                break;
				

            case "Recover_Password":
			
			    $Form = $this->Form_Recover_Psssword($Parm,$UrlFile);
	  
				$Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form ,"");
				DCWrite($Html);	    
				
                break;
	
	
            case "Recover_Password_Solicitud":
				
					$Email = DCPost("email");
					$Id_Entity = $_SESSION['Entity'];
					// DCWrite("haola ".$Entity);
					// exit();
					
				
					$Query=" SELECT Nombre, Email, Id_User
					FROM user WHERE Email = :Email  AND Entity = :Entidad ";
					$Where = ["Email" => $Email, "Entidad" => $Id_Entity ];
					$Row = ClassPDO::DCRow($Query,$Where,$Conection);					
					$Nombre = $Row->Nombre;	
					$CodigoUsuario = $Row->Id_User;	
					$Email = $Row->Email;	
				
					
					$Query=" SELECT 
					Id_Entity, Logo_Interno, Color_Cabecera_Email
					, Color_Cuerpo_Email, Color_Fondo_Email, Texto_Email_Inscripcion
					, Email_Soporte_Cliente
					, Telefono_Soporte_Cliente
					, Url
					, Color_Menu_Horizontal
					FROM entity WHERE Id_Entity = :Id_Entity  ";
					$rg = ClassPDO::DCRow($Query, ["Id_Entity" => $Id_Entity ] ,$Conection);
					$CodigoEntidad = $rg->Id_Entity;			
					$ColorCabeceraEmail = $rg->Color_Cabecera_Email;			
					$ColorCuerpoEmail = $rg->Color_Cuerpo_Email;			
					$ColorFondoEmail = "#ccc";			
					$ImagenLogo = $rg->Logo_Interno;			
					$TextoEmailInscripcion = $rg->Texto_Email_Inscripcion;					
					$EmailSoporteCliente = $rg->Email_Soporte_Cliente;			
					$NroTelefonoSoporteCliente = $rg->Telefono_Soporte_Cliente;	
					$SubDominio = $rg->Url;	
					$ColorMenuHorizontal = $rg->Color_Menu_Horizontal;	
					
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
							<h1 style='color:#000;font-size:1.5em;'>SOLICITUD</h1>
							<h2 style='font-size:1.3em;'> Recuperación de Contraseña</h2>
							
						</div>
					";
					
					$Cuerpo = "
						
						<div style='background-color:".$ColorCabeceraEmail.";padding:20px 50px 0px 50px;'>
							<p style='font-size:1.2em;'>Hola ".$Nombre ."  ". $Descripcion .",</p>					
							<p style='font-size:1.2em;'> Para recuperar tu contraseña, haz clic en el botón “RECUPERAR” </p>	

							  
							<br>
						</div>
			
						<div style='background-color:".$ColorCabeceraEmail.";padding:10px 30px 30px 30px;text-align: center;'>
							<a href='".$dominio."system/site.php?Usuario=".$CodigoUsuario."&sitio={$Entidad}&Accion=RecuperarContrasena'  style='text-decoration: none; background-color:#000; padding: 10px 20px; color: #fff;font-size:1.3em;'>RECUPERAR</a>
							<br>
						</div>		
					";
					
					$ImgControlVista = "<img src='".$dominio."system/_vistas/g_monitoreo_email.php?Tipo=leido&CodigoSuscripcion=$CodigoSuscripcion' width='100%'/>";
					
					$Footer = "
						<div style='background-color:".$ColorCabeceraEmail.";padding:20px 50px 30px 50px;'>
							<br>
							<p style='color:#979595;font-size:1.2em;'>Email de Soporte: ".$EmailSoporteCliente."</p>
							<p style='color:#979595;font-weight: bold; font-size: 1.1em;'> © LessHere 2017, All Rights Reserved.</p>
							
						</div>
						
					";				
					$NombreReceptor = "ADN";				
					$Asunto = "RECUPERAR CONTRASEÑA";	
					
					$data = array('Cabecera' => $Cabecera ,'Cuerpo'=> $Cuerpo
								   , 'ColorFondo' => $ColorFondoEmail, 'Footer' => $Footer
								   , 'NombreReceptor' => $NombreReceptor, 'Asunto' => $Asunto
								   );			
					// $this->emailInscripcion($data,$Email,$cnPDO);
					
				    // DCWrite($Cabecera.$Cuerpo);
				    // DCCloseModal();
                    DCWrite(Message("Process executed correctly","C"));
					
					DCExit();	
			
				
                break;	
				

            case "Edit":
			
                break;	
				
            case "DeleteMassive":
	
                break;				
				
			
        }
				
		
		
	}
	

    public function emailInscripcion($data,$emailDestino,$cnPDO){
	
			$NombreReceptor =$data["NombreReceptor"];
			$EmailReceptor =$emailDestino;
			$Asunto = $data["Asunto"];

			$Body = "
			<div style='padding:50px;background-color:".$data["ColorFondo"].";width:840px;' >
			
				<table style='width:100%;' >
					<tr style='background-color:'>
						<td>
						".$data["Cabecera"]."
						</td>
					</tr>
					<tr>
						<td>
						".$data["Cuerpo"]."					
						</td>
					</tr>
					<tr>
						<td>
						".$data["Footer"]."		
						</td>
					</tr>		
				</table>
				
			</div>
			
			";
			DCWrite($Body);
			$EmailEmisor="hola@adnendomarketing.com";
			$NombreEmisor="ADN - Endo Marketing 2017";
			// EnviarEmailV1($NombreReceptor, $EmailReceptor,$Asunto, $Body, $NombreEmisor,$EmailEmisor);
			
	}

	public function FormLocal($Settings,$UrlFile) {
		
	    global $Conection,$DCTimeHour;
		
        $Redirect = "";	
		$Id_Object = $Settings["Id_Object"];
		$Entity = $Settings["Entity"];
		$Id_Edu_Componente_S = $Settings["Id_Edu_Componente_S"];
		$Id_Edu_Almacen = $Settings["Key"];
		
        $IdForm = "Obj_Login";	
        $IdButton = "button_animatedModal5";	
	
        $Direcction = $UrlFile.$Redirect."/Process/ENTRY/Obj/".$IdForm."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."/Key/".$Id_Edu_Almacen;	
		
		//Type_Process_BD
        $Screen = "Msg_0";	
        $Class = "btn btn-info btn-block";	
		$NameButton = "Iniciar Sesión ";
		
		$Html = '
           <div class="authentication-content m-b-30">
            <h3 class="m-t-0 m-b-30 text-center">Ingresa a tu cuenta</h3>
			<form id="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >	
              <div class="form-group">
                <label for="form-control-1">Email</label>
                <input type="email" name="email" class="form-control" id="form-control-1" placeholder="Email" >
              </div>
              <div class="form-group">
                <label for="form-control-2">Contraseña</label>
                <input type="password" name="password" class="form-control" id="form-control-2" placeholder="Contraseña">
              </div>
              <div class="form-group">
				<label class="custom-control custom-control-info custom-checkbox active">
                  <input class="custom-control-input" type="checkbox" name="mode" checked="checked">
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-label">Mantenerme Conectado</span>
                </label>
		
	        
			<a href="javascript:void(null);" type_send="HXM" onclick="LoadPage(this);" 
			direction="/sadministrator/login/Interface/Recover_Password" 
			screen="animatedModal5" class="text-info pull-right" 
			id="form_Rp_1" data-target="#animatedModal5" 
			style="margin-top: 7px;margin-right:7px;">
              Recuperar Contraseña
            </a>			
			
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
	
	public function Iniciar_Sesion($Parm) {
        global $Conection;
				
		$Query = "
			SELECT 
			US.Usuario_Login
			, US.Password
			, ET.Id_Entity
			, US.Id_User
			, US.Id_Perfil
			FROM user US
			INNER JOIN entity ET ON ET.Id_Entity = US.Id_Entity
			WHERE 
			US.Usuario_Login = :Usuario_Login 
			AND US.Password = :Password 
			AND ET.Id_Entity = :Id_Entity
		"; 
		$Where = ["Usuario_Login"=>DCPost("email"),"Password"=>DCPost("password") ,"Id_Entity"=>$_SESSION['Entity']];
		$Reg = ClassPDO::DCRow($Query,$Where ,$Conection);								
		
		if(!empty($Reg->Usuario_Login)){
			session_start();
			
			$_SESSION['User'] = $Reg->Id_User;
			$_SESSION['Entity'] = $Reg->Id_Entity;
			$_SESSION['Perfil_User'] = $Reg->Id_Perfil;
			
			$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
			$Id_Edu_Almacen = $Parm["Key"];
			
			if(!empty($Id_Edu_Almacen)){
				
				Edu_Articulo_Det::Matricula($Parm);
				
				DCWrite('
				<script>
				window.location.href = "/sadministrator/edu_articulo_det/Interface/begin/Request/On/Key/'.$Id_Edu_Almacen.'/Id_Edu_Componente_S/'.$Id_Edu_Componente_S.'";
				</script>
				');		
				
			}else{
				
				DCWrite('
				<script>
				window.location.href = "/sadministrator/edu_tendencia/Request/On";
				</script>
				');
			
			}

		}
		
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