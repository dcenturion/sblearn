<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once './sviews/layout.php';
require_once('./sbookstores/php/conection.php');
require_once('./sbookstores/php/functions.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class RecuperarPasword{

   private $Parm;

    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour;

        $UrlFile = "/recuperar-password";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$UrlFile_Admin_Home = "/sadministrator/admin_home";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";

        $Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];
		$empresa = $Parm["empresa"];

        switch ($Transaction) {
            case "INSERT":

				switch ($Form) {
					case "Form_Call":
                        $this->Form_Call($Transaction);
						break;	
					case "Form_Suscripcion":
						$this->Form_Suscripcion($Transaction);
						break;							
				}			
				
				DCExit();
            break;
            case "UPDATE":

             reak;
            case "DELETE":

            break;
            case "VALIDATIONS":

            break;
            case "SEARCH":

            break;				
        }
        
        switch ($Process) {
            case "ENTRY":
			  
				switch ($Obj) {
							

					case "Obj_Password_Reset_Process":

                            $DCPost = $_POST;
							$Password = DCPost("password"); 
                            $Password_B = DCPost("password_b"); 
							if(empty($Password)){
                                DCWrite(Message("Debe insertar la primera contraseña","C")); DCExit();
							}					
							if(empty($Password_B)){
                                DCWrite(Message("Debe insertar la segunda contraseña","C")); DCExit();
							}		
                            $this->Pass_Reset($Parm,$UrlFile);
				
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

        $layout  = new Layout();
        switch ($Interface) {

            case "Recover_Password_Solicitud":
                   
	            //var_dump($empresa);
				$Email = DCPost("email");
					
				if ( false !== strpos($Email, "@") && false !== strpos($Email, ".") ) {				
					
					//$Id_Entity = $_SESSION['Entity'];
					//var_dump($Id_Entity);
					//exit();
				
					$Query="                  
                    SELECT 
                    US.Usuario_Login
                    , ET.Id_Entity
                    , US.Id_User
                    , US.Nombre
                    , UM.Id_Perfil
                    , UM.Id_User_Miembro
                    , ET.Url
                    FROM user_miembro UM
                    INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
                    INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
                    WHERE 
                    US.Usuario_Login = :Usuario_Login 
                    AND ET.Url = :Url
                    ";

                    $Where = ["Usuario_Login" => $Email, "Url"=>$empresa ];
                    $Row = ClassPDO::DCRow($Query,$Where,$Conection);	
					$Nombre = $Row->Nombre;	
					$CodigoUsuario = $Row->Id_User;	
                    		$Email = $Row->Usuario_Login;
				$Id_Entity = $Row->Id_Entity;			
					$Query=" SELECT 
					Id_Entity, Logo_Interno, Color_Cabecera_Email
					, Color_Cuerpo_Email, Color_Fondo_Email, Texto_Email_Inscripcion
					, Email_Soporte_Cliente
					, Telefono_Soporte_Cliente
					, Url
					, Color_Menu_Horizontal
					FROM entity WHERE Id_Entity = :Id_Entity";
					
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
					$Id_Hash = GeraHash(20);					
					$Fecha_Limite = DCDate();;					
					$Hora = Time() + (60 *60 * 2);   
					$Hora_Limite = date('H:i:s',$Hora); // + 12 hor

					// edu_solicitud_cambio_password 
					$data = array(
						'Estado_Entrega' => "Pendiente",
						'Fecha_Limite' => $Fecha_Limite,
						'Hora_Limite' => $Hora_Limite,
						'Hash' => $Id_Hash,
						'Entity' => $Id_Entity,
						'Id_User_Creation' => $CodigoUsuario,
						'Id_User_Update' => $CodigoUsuario,
						'Date_Time_Creation' => $DCTimeHour,
						'Date_Time_Update' => $DCTimeHour
					);
					$ResultB = ClassPDO::DCInsert("edu_solicitud_cambio_password", $data, $Conection);	
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
							<a href='".$dominio."recuperar-password/Interface/Recover_Password_reset/empresa/".$empresa."/Id/".$Id_Hash."/Request/On'  style='text-decoration: none; background-color:#000; padding: 10px 20px; color: #fff;font-size:1.3em;'>RECUPERAR</a>
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
                    $this->emailInscripcion($data,$Email,$cnPDO);
                    
                    DCWrite(Message("La Solicitud Fue Enviada a Su Email","C"));
				
					
			    }else{
					
			        DCWrite(Message("El valor no corresponde a una cuenta de correo","C"));
					
				}

            break;

            case "Recover_Password_reset":
                $empresa = $Parm["empresa"];
                $Hash = $Parm["Id"];
                $layout  = new Layout();
                $Query = "
                    SELECT 
                    ET.Id_Entity
                    , ET.Logo_Externo
                    , ET.Logo_Interno
                    , ET.Color_Menu_Horizontal
                    , ET.Color_Fondo_Boton
                    , ET.Color_Texto_Boton
                    , ET.Color_Texto_Menu
                    , ET.Color_Texto
                    , ET.Url
                    , ET.Nosotros
                    , ET.Estado_Nosotros
                    FROM entity ET 

                    WHERE 
                    ET.Url = :Url 
                    "; 		
                    $Where = ["Url"=>$empresa];				
                    $Row = ClassPDO::DCRow($Query,$Where,$Conection);	

                    $datos = array();
                    $datos["Img_Logo"] = $Row->Logo_Externo;
                    $datos["Url"] = $Row->Url;
                    $datos["Color"] = $Row->Color_Menu_Horizontal;
                    $datos["Color_Texto"] = $Row->Color_Texto;
                    $datos["Color_Fondo_Boton"] = $Row->Color_Fondo_Boton;
                    $datos["Color_Texto_Boton"] = $Row->Color_Texto_Boton;
                    $datos["Color_Texto_Menu"] = $Row->Color_Texto_Menu;
                    $datos["Nosotros"] = $Row->Nosotros;
                    $datos["Estado_Nosotros"] = $Row->Estado_Nosotros;
                    $datos["Id_Entity"] = $Row->Id_Entity;
                    $datos["Url"] = $Row->Url;
                    $datos["Id_Hash"] = $Hash;

                    $Query = " SELECT Hash AS Hash_Bd
                    , Id_User_Creation
                    , Estado_Entrega
                      FROM edu_solicitud_cambio_password 
                    WHERE Hash = :Hash
                    ";
                    $Where = ["Hash"=>$datos["Id_Hash"]];
                    $Reg_B = ClassPDO::DCRow($Query,$Where ,$Conection);

                    $BodyPage = $layout->render('./sviews/recuperar_password.phtml',$datos);
                    echo $layout->main($BodyPage,$datos);

                    if($Reg_B->Hash_Bd == $Hash && $Reg_B->Estado_Entrega == "Pendiente"){

                        //$BodyPage = $layout->render('./sviews/recuperar_password.phtml',$datos);
                        //echo $layout->main($BodyPage,$datos);

                    }else{
                        
                        $Settings  = array();
                        $Settings['Url'] = $UrlFile."/Interface/Cambio_de_contrasena_Finalizado/empresa/".$empresa."/Hash/".$Reg_B->Hash_Bd;
                        $Settings['Screen'] = "panel_formulario";
                        $Settings['Type_Send'] = "HXM";
                        DCRedirectJSSP($Settings);	
                        DCExit();
                    }
			
            break;
            
            case "Cambio_de_contrasena_Finalizado":
                
                $empresa = $Parm["empresa"];
		//var_dump("$empresa");
                $Html = ' 
                <h3 class="m-t-0 m-b-30 text-center">La Contraseña Se Cambio Con Exito</h3>
                <br>
                <a class="nav-link text-center" href="/'.$empresa.'" style="background-color: #17a2b8 !important; color:#ffff;" >Volver a Esgep</a>
                ';
                echo $Html;
                exit();
            break;    
        }

    }

    public function emailInscripcion($data,$emailDestino,$cnPDO){
	
        $NombreReceptor =$data["NombreReceptor"];
        $EmailReceptor =$emailDestino;
        $Asunto = $data["Asunto"];

        $Body = "
        <div style='padding:50px;background-color:".$data["ColorFondo"].";width:700px;' >
        
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
        // DCWrite($Body);
        $EmailEmisor="informes@yachai.com";
        $NombreEmisor="Yachai";
        // EnviarEmailV1($NombreReceptor, $EmailReceptor,$Asunto, $Body, $NombreEmisor,$EmailEmisor);
        // DCVd("hIA");	
        $Retunr = DCEmailSes("Estimado Usuario",$EmailReceptor,$Asunto, $Body , $filenamePDF, $content, $NombreEmisor,"mail@mailmkglobal.com");
            // DCVd($Retunr);								
        
}

    public function Pass_Reset($Settings,$UrlFile) {
        global $Conection,$DCTimeHour;
        $Id_Entity = $_SESSION['Entity'];
                    
        if(!empty(DCPost("password")) || !empty(DCPost("password_b"))){
            if(DCPost("password") == DCPost("password_b")){
            
                $Query = " SELECT Hash AS Hash_Bd, Id_User_Creation FROM edu_solicitud_cambio_password 
                    WHERE Hash = :Hash
                    ";
                $Where = ["Hash"=>$Settings["Id"]];
                $Reg_B = ClassPDO::DCRow($Query,$Where ,$Conection);
                $DCPost = DCPost("password");
                $Pass = DCEncriptar($DCPost);
                $Id_Edu_Solicitud = $Reg_B->Id_edu_solicitud_cambio_password;



                if(!empty($Reg_B->Hash_Bd)){

                    $Reg1 = array(
                        'Estado_Entrega' => "Finalizado"
                        );
                        $where = array('Hash'=>$Settings["Id"]);
                        $Rg1 = ClassPDO::DCUpdate("edu_solicitud_cambio_password", $Reg1 , $where, $Conection);

                        $reg = array(
                        'Password' => $DCPost
                        );
                        $where = array('Id_User' =>$Reg_B->Id_User_Creation);
                        $rg = ClassPDO::DCUpdate("user", $reg , $where, $Conection);

                        DCWrite(Message("Su contraseña fué actualizada, ya puede iniciar sesión ","C"));
                        $Settings  = array();
                        $Settings['Url'] = $UrlFile."/Interface/Cambio_de_contrasena_Finalizado/Hash/".$Reg_B->Hash_Bd;
                        $Settings['Screen'] = "panel_formulario";
                        $Settings['Type_Send'] = "HXM";
                        DCRedirectJSSP($Settings);	
                        DCExit();
                        					
                }
                
            }else{
            
                    DCWrite(Message("Los valores no son iguales","C"));	
            }	
            
        }else{
            
                    DCWrite(Message("Debe llenar los campos","C"));
        }

    }

}