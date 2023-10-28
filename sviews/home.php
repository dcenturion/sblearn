<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once './sviews/layout.php';
require_once('./sbookstores/php/conection.php');
require_once('./sbookstores/php/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/sadministrator/sbookstores/captcha/recaptcha_keys.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/sadministrator/sviews/login.php');
	

$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Home{
	private $Parm;

    public  function __construct($Parm=null)
	{
		
		$DCPost = DCPost("password");
		$Pass = DCEncriptar($DCPost);
		
		$Url_Uri = $_GET["uri"];
		
		$GoogleToken = DCPost("google-response-token");
		$Transaction = $Parm["Transaction"];
		$Form = $Parm["Form"];
		$login = $Parm["login"];
		$Url = $Parm["entidad"];
		$captcha = DCPost("g-recaptcha-response");

		$response=$captcha;
	    $secret="6Ldjy0cdAAAAAPswImRxVTS5d_byQU0OwRTW86dj";
	    $IP_LOCAL=$_SERVER["REMOTE_ADDR"];
	    $URL_VERIF="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$response."&remoteip=".$IP_LOCAL;
	    $respuesta=file_get_contents($URL_VERIF); 
	    $finalrespuesta=json_decode($respuesta,true);
		
	
		
		// if(trim($finalrespuesta ['success']) == true){
				
				global $Conection,$DCTimeHour;
				if(!empty($login)){
					
					$Query = "
						SELECT 
						US.Usuario_Login
						, US.Password
						, ET.Id_Entity
						, US.Id_User
						, UM.Id_Perfil
						, UM.Id_Perfil_Educacion
						, UM.Id_User_Miembro
						, ET.Url
						FROM user_miembro UM
						INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
						INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
						WHERE 
						US.Usuario_Login = :Usuario_Login 
						AND US.Password = :Password 
						AND ET.Url = :Url
					"; 
			
					$Where = ["Usuario_Login"=>trim(DCPost("email")),"Password"=> DCPost("password") ,"Url"=>$Url];
					$Reg = ClassPDO::DCRow($Query,$Where ,$Conection);
			
					if(!empty($Reg->Usuario_Login)){
						
						$User_MIEMBRO=$Reg->Id_User_Miembro;
						$Entidad_ID=$Reg->Id_Entity;

						
						$_SESSION['User'] = $User_MIEMBRO;
						$_SESSION['Entity'] = $Entidad_ID;
						$_SESSION['Perfil_User'] = $Reg->Id_Perfil;
						
						$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
						$Id_Edu_Almacen = $Parm["key"];
						$Id_Edu_Proforma = $Parm["Id_Edu_Proforma"];
						$Request_Det = $Parm["Request_Det"];

						$Clave_Token=$Reg->Password;
						$Tokenunico=uniqid($Clave_Token,false);
						$Token_Create=trim($Tokenunico,$Clave_Token);
						$Id_Perfil_Educacion=$Reg->Id_Perfil_Educacion;
						$Id_Perfil=$Reg->Id_Perfil;

						$INFO="";
						if (($Id_Perfil==3) OR ($Id_Perfil_Educacion==3)) {
							
							
								$data = array('Token' => $Token_Create);
								$where = array('Usuario_Login'=>DCPost("email"),'Entity'=>$Entidad_ID);
								ClassPDO::DCUpdate("user", $data , $where, $Conection);

								$INFO='<script>
								sessionStorage.setItem("Token_Client", "'.$Token_Create.'");
								</script>';
								
						}
						DCWrite($INFO);
						

						
						$User = $_SESSION['User'];
						$Entity = $_SESSION['Entity'];
						$Dispositivo = DCTipoDispositivo();
						$IP = DCGet_client_ip();
						
						$data = array(
						'Dispositivo' =>  $Dispositivo,
						'Ip' =>  $IP,
						'Entity' => $Entity,
						'Id_User_Update' => $User,
						'Id_User_Creation' => $User,
						'Dia_Hora_Ingreso' => $DCTimeHour,
						'Date_Time_Creation' => $DCTimeHour,
						'Date_Time_Update' => $DCTimeHour
						);
						$Return = ClassPDO::DCInsert("edu_ingreso_plataforma", $data, $Conection,"");
											

						DCWrite('
						
						
						
						<script>
						
						
						
						localStorage.setItem("Session_Entidad", "'.$Url.'");
						
		                var auth = firebase.auth();
						 console.log(auth);
						auth
						   .createUserWithEmailAndPassword("'.DCPost("email").'","'.DCPost("password").'")
						   .then(userCredential => {
								   console.log("hola console")
								   
							    }
						   )
						    .catch((error) => {
								firebase.auth().setPersistence(firebase.auth.Auth.Persistence.LOCAL)
								.then(() => {
								
									auth
										.signInWithEmailAndPassword("'.DCPost("email").'","'.DCPost("password").'")
										  .then((userCredential) => {
											
											const user = userCredential.user;
												   console.log("inicio de seion")
										})
								})
							});
							
							
							window.location.href = "/sadministrator/edu-articulo-curso/request/on/";		
						</script>
						
						
						');
									
								
					}else{
						
								DCWrite(Message("Los datos ingresados no son correctos","C"));
								
					}

				    exit();
					
					
				}else{
					
		
					
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
					$Url_Link = "inicio";
					$Where = ["Url"=>$Url_Link];				
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
					$datos["Url"] = $Url_Link;
				
					$layout  = new Layout();
					$BodyPage = $layout->render('./sviews/conten_page.phtml',$datos);

					echo $layout->main($BodyPage,$datos);					
					
					
				}
			
				
		 // }else{
			 
		 	// DCWrite(Message("Actualice la pagina y realice el Captcha","E"));
			
		 // }
		

	}
	


	public function Form_Call($Transaction) {
       	global $Conection, $DCTimeHour;

		$data = array(
		'Nombre' => DCPost("username"),
		'Email' => DCPost("email"),
		'Telefono' => DCPost("phone"),
		'Asunto' => DCPost("subject"),
		'Mensaje' => DCPost("message"),
		'Fecha_Hora_Creacion' => $DCTimeHour,
		'Fecha_Hora_Actualizacion' => $DCTimeHour
		);
		$rg = ClassPDO::DCInsert('contactado', $data, $Conection);	
		CleanForm("Form_Call");
		TemplateEmailWhite(DCPost("email"),DCPost("username"));
		DCWrite(Message("La consulta fue enviada","C"));
		
						
	}		

	

	public function Form_Suscripcion($Transaction) {
       	global $Conection, $DCTimeHour;

		$data = array(
		'Email' => DCPost("email"),
		);
		$rg = ClassPDO::DCInsert('contactado', $data, $Conection);
        // DCWrite(DCPost("email")." oooo");		
		CleanForm("Form_Suscripcion");
		TemplateEmailWhite(DCPost("email"),"");
		DCWrite(Message("La consulta fue enviada","C"));
						
	}		

}