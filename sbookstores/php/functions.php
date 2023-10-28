<?php
include('class/lib_pdo.php');
include('class/lib_imagen.php');
require_once('vendor/autoload.php');
date_default_timezone_set('America/Lima');
require_once('excel_classes_v2/PHPExcel.php');
require_once('excel_classes_v2/PHPExcel/IOFactory.php');
require 'vendor/autoload.php';


function DCEmailSes($NomCuenta, $email, $asunto, $body, $filenamePDF, $content, $From, $emailFrom)
{


    $configset = 'mailing';

    $ses = \Aws\Ses\SesClient::factory(array(
        'key' => AWS_ACCES_KEY_B,
        'secret' => AWS_SECRET_KEY_B,
        'region' => 'us-west-2',
    ));

    if (empty($content) || empty($filenamePDF)) {
        $emailadjunto = "--";
    } else {
        $emailadjunto = "\nContent-Type: application/octet-stream;\nContent-Transfer-Encoding: base64\nContent-Disposition: attachment; filename=\"$filenamePDF\"\n\n$content\n\n--NextPart--";
    }

    $data = "X-SES-CONFIGURATION-SET: $configset\nReply-to:$emailFrom\nFrom:$From<informes@esgep.com>\nTo:$NomCuenta <$email>\nSubject:$asunto\nMIME-Version: 1.0\nContent-type: Multipart/Mixed; boundary=\"NextPart\"\n\n--NextPart\nContent-Type: text/html;charset=UTF-8\n\n$body\n\n--NextPart$emailadjunto";



    $result = $ses->sendRawEmail(array(
        'RawMessage' => array('Data' => base64_encode("$data")),
        'Destinations' => array("$NomCuenta <$email>"),
        'Source' => 'informes@esgep.com',
    ));

    return $result;
}

function DCGet_client_ip() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
	   $ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}


function DCTipoDispositivo(){
	
	$tablet_browser = 0;
	$mobile_browser = 0;
	$body_class = 'desktop';

	if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
	$tablet_browser++;
	$body_class = "tablet";
	}

	if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
	$mobile_browser++;
	$body_class = "mobile";
	}

	if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
	$mobile_browser++;
	$body_class = "mobile";
	}

	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
	'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
	'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
	'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
	'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
	'newt','noki','palm','pana','pant','phil','play','port','prox',
	'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
	'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
	'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
	'wapr','webc','winw','winw','xda ','xda-');

	if (in_array($mobile_ua,$mobile_agents)) {
	$mobile_browser++;
	}

	if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
	$mobile_browser++;
	//Check for tablets on opera mini alternative headers
	$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
	if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
	  $tablet_browser++;
	}
	}
	if ($tablet_browser > 0) {
	// Si es tablet has lo que necesites
	    $dispositivo = "Tablet";
	}
	else if ($mobile_browser > 0) {
	// Si es dispositivo mobil has lo que necesites
		$dispositivo = "Movil";
	}
	else {
	// Si es ordenador de escritorio has lo que necesites
		$dispositivo = "Escritorio";
	}  
    
	return $dispositivo;
}


function DCProtect($vA){

    $var =trim($vA);
    return($var);
}

function DCGet($Field) {
    $Field = $_GET["" . $Field . ""];
    return $Field;
}

function DCPost($Field) {
    $Field = $_POST["" . $Field . ""];
    return $Field;
}


function DCWrite($valor) {
    echo $valor;
}

function DCExit($valor) {
    echo $valor;
    exit;
}

function DCTime() {
    return time();
}
function DCDate() {
    return date('Y-m-d');
}

function DCTimeHour() {
    return date('Y-m-d H:i:s');
}

function DCDuration_Date($fecha_indice,$duracion){
$separacion_Fechas=explode("-",$fecha_indice);
$Año_Indice=$separacion_Fechas[0];
$Mes_Indice=$separacion_Fechas[1];
$Dia_Indice=$separacion_Fechas[2];
$Modif_Mes=$Mes_Indice+$duracion;

	if ($Modif_Mes<=12) {
		$Fecha_Nueva=$Año_Indice."-0".$Modif_Mes."-".$Dia_Indice;
	}else{
		$Restante=$Modif_Mes-12;
		$fechaañorest=$Año_Indice+1;
		if ($Restante<10) {
			 $Restante="0".$Restante;
		}
		$Fecha_Nueva=$fechaañorest."-".$Restante."-".$Dia_Indice;
	}
return $Fecha_Nueva;

}


function DCValidateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function DCDateVerificacion($date,$format){
$Validacion=(DCValidateDate($date,$format)); # true
	if ($Validacion==false) {
			$devolver=date("Y-m-d",strtotime($date."+ 5 days")); 
	}else{
		$devolver=$date;
	}
	return $devolver;
}


function DCVd($expresion, $stop = false) {
	echo '<pre>';
	var_dump($expresion);
	echo '</pre>';
	if ($stop)
		exit;
}

function Message($String, $Type) {
    switch ($Type) {
        case 'E':
            $Html = '<script>
		        	alertify.error("'.$String.'"); 
			      </script>';
            break;
        case 'C':
            $Html = '<script>
		        	alertify.success("'.$String.'"); 
			      </script>';
            break;
        case 'A':
            $Html = '<script>
		        	alertify.log("'.$String.'"); 
			      </script>';
            break;
        case 'M':
            $Html = '<script>
		        	alertify.log("'.$String.'"); 
			      </script>';
            break;
    }
    DCWrite($Html);
}

function CleanForm($Form) {

	$Html = '
		<script>	
		$("#'.$Form.' :input").each(function(){
		$(this).val("");
		});
		</script>	
	';
  
    DCWrite($Html);
}



function TemplateEmailWhite($Email,$Nombre){
	
    $ColorCabeceraEmail = "#fff;";
    $ColorFondoEmail = "#ececef;";
	$Dominio = DCUrl();
	$ImagenLogo = "logo.png";
	
	$Cabecera = "
		<div style='background-color:".$ColorCabeceraEmail.";padding:50px 50px 20px 50px;'>
			<img src='".$Dominio."simages/".$ImagenLogo."' width='60%'>
			<br>
			<br>
			<br>
			<br>
			<h1 style='color:#000;font-size:1.7em;font-weight: lighter;'>ATENCIԎ AL CLIENTE</h1>
			<h2>".$NombreCuestionario."</h2>
			<p style='text-align:right;color:".$ColorMenuHorizontal.";font-weight:bold;'></p>
		</div>
	";

	$Cuerpo = "
		
		<div style='background-color:".$ColorCabeceraEmail.";padding:20px 50px 0px 50px;'>
			<p style='font-size:1.3em;'>Hola <b> ".$Nombre .",</b></p>

			<p style='font-size:1.3em;'>
			Es un placer atenderte y establecer comunicaci󮬍
			estamos procesando tu consulta a la brevedad, en unos momentos nos pondremos en contacto.
			<br>
			<br>
			Que sigas teniendo un gran d!!
			<br>
			<br>
			Saludos.
			</p>
			<br>						
		
			<br>
		</div>

	
	";

	$ImgControlVista = "<img src='".$dominio."system/_vistas/g_monitoreo_email.php?Tipo=leido&CodigoSuscripcion=$CodigoSuscripcion' width='100%'/>";

	$Footer = "
		<div style='background-color:".$ColorCabeceraEmail.";padding:20px 50px 70px 50px;'>
			<br>
			<p style='color:#979595;font-size:1.2em;'>Email de Soporte: informes@standardbusiness.us</p>
			<p style='color:#979595;font-weight: bold; font-size: 1.1em;'> ʓtandard Business | CONSULTORA, All Rights Reserved.</p>
			
		</div>
		
	";				
	$NombreReceptor = "Clienre";				
	$Asunto = "Atenci󮠡l Cliente ";	

	$data = array('Cabecera' => $Cabecera ,'Cuerpo'=> $Cuerpo
				   , 'ColorFondo' => $ColorFondoEmail, 'Footer' => $Footer
				   , 'NombreReceptor' => $NombreReceptor, 'Asunto' => $Asunto
				   );			
	TemplateEmailLayout($data,$Email,$cnPDO);

}


function TemplateEmailLayout($data,$emailDestino,$cnPDO){
	
	$NombreReceptor =$data["NombreReceptor"];
	$EmailReceptor =$emailDestino;
	$Asunto = $data["Asunto"];

	$Body = "
	<div style='padding:50px;background-color:".$data["ColorFondo"].";width:700px;' >
	
		<table style='width:100%;border-spacing: 0px;' cellpadding='0' border='0' cellspacing='0'>
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
	$EmailEmisor="informes@yachai.org";
	$NombreEmisor="Yachai";
	EmailSend($NombreReceptor, $EmailReceptor,$Asunto, $Body, $NombreEmisor,$EmailEmisor);
}

function EmailSend($NombreReceptor, $EmailReceptor, $Asunto, $Body, $NombreEmisor,$EmailEmisor){
	// echo "NombreReceptor:: ".$NombreReceptor;
	// echo "EmailReceptor:: ".$EmailReceptor;
	// echo "Asunto:: ".$Asunto;
	// echo "Body:: ".$Body;
	// echo "NombreEmisor:: ".$NombreEmisor;
	// echo "EmailEmisor:: ".$EmailEmisor;
	$from = new SendGrid\Email($NombreEmisor, $EmailEmisor);

	$to = new SendGrid\Email($NombreReceptor, $EmailReceptor);
	
	$content = new SendGrid\Content("text/html",$Body);
	$mail = new SendGrid\Mail($from,$Asunto, $to, $content);

	$apiKey = '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';
	$sg = new \SendGrid($apiKey);

	$response = $sg->client->mail()->send()->post($mail);
	// echo $response->statusCode();
	// print_r($response->headers());
	$response->headers();
	// echo $response->body();
	
	return $Parm;
}

function DCUrl($Url = '') {
	
    $Vc = 'http';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $Vc .= 's';
    }
    $Vc .= '://';
    $UrlS = $Vc . $_SERVER['HTTP_HOST'] . '/';
	
    return filter_var($UrlS . $Url, FILTER_VALIDATE_URL) ? $UrlS . $Url : $UrlS;
}

function getDomain() {

    return "//{$_SERVER["HTTP_HOST"]}";
}

function MenuLateralAdministrator(){

    $html = '
	<div class="site-left-sidebar" >
        <div class="sidebar-backdrop" style="border-right: 1px solid rgb(245, 245, 245);"></div>
        <div class="custom-scrollbar" style="background-color: rgb(245, 245, 245);">
          <ul class="sidebar-menu">';
		  

			
		$Perfil_User = $_SESSION['Perfil_User'];
		$Entity = $_SESSION['Entity'];
		// var_dump($_SESSION['Entity']);
		if(!empty($Perfil_User)){			
				$html .= '			  
						<li class="menu-title">Inicio</li>
						';	
						
				$html .= '			
						<li id="li_btn_home" >
						  <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/edu-articulo-curso" screen="ScreenRight"  id="btn_home" >
							<span class="menu-icon">
							  <i class="zmdi zmdi-book"></i>
							</span>
							<span class="menu-text">Biblioteca</span>
						  </a>
						</li>
						';

				// if($Entity == 3){
					
								// $html .= '			
										// <li id="li_btn_certificado" >
										  // <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/edu-gestion-certificado/interface/list_certificado" screen="ScreenRight"  id="btn_certificado" >
											// <span class="menu-icon">
											  // <i class="zmdi zmdi-graduation-cap"></i>
											// </span>
											// <span class="menu-text">Certificado</span>
										  // </a>
										// </li>
										
										// ';		


				// }
						
		}
			
    // $html .= '				
            // <li id="li_btn_tendencia" >
              // <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/edu-tendencia/ie/lesshere" screen="ScreenRight"  id="btn_tendencia" >
                // <span class="menu-icon">
                  // <i class="zmdi zmdi-trending-up zmdi-hc-fw"></i>
                // </span>
                // <span class="menu-text">Tendencia</span>
              // </a>
            // </li>	';
			
			
    // $html .= '				
            // <li class="menu-title" style="border-top: 1px solid #cac9c9;">TEMAS</li>	';
			
    // $html .= '				
            // <li id="li_btn_salud" >
              // <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/edu-store/theme/salud" screen="ScreenRight"  id="btn_salud" >
                // <span class="menu-icon">
                  // <i class="zmdi zmdi-plus-square zmdi-hc-fw"></i>
                // </span>
                // <span class="menu-text">Salud </span>
              // </a>
            // </li>	';		
			
    // $html .= '	
            // <li id="li_btn_belleza" >
              // <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/edu-store/theme/belleza" screen="ScreenRight"  id="btn_belleza" >
                // <span class="menu-icon">
                  // <i class="zmdi zmdi-face zmdi-hc-fw"></i>
                // </span>
                // <span class="menu-text">Belleza </span>
              // </a>
            // </li>	';	
			
    // $html .= '				
            // <li id="li_btn_hogar" >
              // <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/edu-store/theme/hogar" screen="ScreenRight"  id="btn_hogar" >
                // <span class="menu-icon">
                  // <i class="zmdi zmdi-home zmdi-hc-fw"></i>
                // </span>
                // <span class="menu-text">Hogar </span>
              // </a>
            // </li>	';	
			
    // $html .= '				
            // <li id="li_btn_liderazgo" >
              // <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/edu-store/theme/liderazgo" screen="ScreenRight"  id="btn_liderazgo" >
                // <span class="menu-icon">
                  // <i class="zmdi zmdi-accounts-add zmdi-hc-fw"></i>
                // </span>
                // <span class="menu-text">Liderazgo</span>
              // </a>
            // </li>	';	
			

			// $html .= '				
			// <li id="li_btn_liderazgo" >
			  // <a href="javascript:void(null);"  onclick="" direction="/sadministrator/edu-store/theme/liderazgo" screen="ScreenRight"  id="btn_liderazgo" >
				// <span class="menu-icon">
				  // <i class="zmdi zmdi-accounts-add zmdi-hc-fw"></i>
				// </span>
				// <span class="menu-text">Liderazgo</span>
			  // </a>
			// </li>	';	





			// $html .= '				
			// <li id="li_btn_finanzas" >
			  // <a href="javascript:void(null);"  onclick="" direction="/sadministrator/edu-store/theme/finanzas" screen="ScreenRight"  id="btn_finanzas" >
				// <span class="menu-icon">
				  // <i class="zmdi zmdi-chart zmdi-hc-fw"></i>
				// </span>
				// <span class="menu-text">Finanzas</span>
			  // </a>
			// </li>	';	
			// $html .= '				
			// <li id="li_btn_marketing" >
			  // <a href="javascript:void(null);"  onclick="" direction="/sadministrator/edu-store/theme/marketing" screen="ScreenRight"  id="btn_marketing" >
				// <span class="menu-icon">
				  // <i class="zmdi zmdi-card-giftcard zmdi-hc-fw"></i>
				// </span>
				// <span class="menu-text">Marketing</span>
			  // </a>
			// </li>';	
			// $html .= '				
			// <li id="li_btn_tecnologia" >
			  // <a href="javascript:void(null);"  onclick="" direction="/sadministrator/edu-store/theme/tecnologia" screen="ScreenRight"  id="btn_tecnologia" >
				// <span class="menu-icon">
				  // <i class="zmdi zmdi-lamp zmdi-hc-fw"></i>
				// </span>
				// <span class="menu-text">Tecnología</span>
			  // </a>
			// </li>';			


    // $html .= '				
            // <li id="li_btn_politica" >
              // <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/edu-store/theme/politica" screen="ScreenRight"  id="btn_politica" >
                // <span class="menu-icon">
                  // <i class="zmdi zmdi-balance zmdi-hc-fw"></i>
                // </span>
                // <span class="menu-text">Política</span>
              // </a>
            // </li>	';
    // $html .= '				
            // <li id="li_btn_entretenimiento" >
              // <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/edu-store/theme/entretenimiento" screen="ScreenRight"  id="btn_entretenimiento" >
                // <span class="menu-icon">
                  // <i class="zmdi zmdi-tv-play zmdi-hc-fw"></i>
                // </span>
                // <span class="menu-text">Entretenimiento</span>
              // </a>
            // </li>	
			
			// ';
		
        // var_dump($Perfil_User);	
		
		if($Perfil_User == 2 || $Perfil_User == 1){			
		
			$html .= '									
					<li class="menu-title">Configuraciones</li>
					<li id="li_btn_alumnos" >
					  <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/user_alumnos" screen="ScreenRight"  id="btn_alumnos" >
						<span class="menu-icon">
						  <i class="zmdi zmdi-accounts-alt"></i>
						</span>
						<span class="menu-text">Alumnos</span>
					  </a>
					</li>

					<li id="li_btn_usuarios" >
					  <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/user_administrator" screen="ScreenRight"  id="btn_usuarios" >
						<span class="menu-icon">
						  <i class="zmdi zmdi-accounts-alt"></i>
						</span>
						<span class="menu-text">Usuarios</span>
					  </a>
					</li>
					<li id="li_btn_camp_curso" >
					  <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/cursos_agrupados" screen="ScreenRight"  id="btn_camp_curso" >
						<span class="menu-icon">
						  <i class="zmdi zmdi-accounts-alt"></i>
						</span>
						<span class="menu-text">Gestion de<br>Campañas</span>
					  </a>
					</li>
					<li id="li_btn_programa" >
					  <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/curso-programa" screen="ScreenRight"  id="btn_programa" >
						<span class="menu-icon">
						  <i class="zmdi zmdi-accounts-alt"></i>
						</span>
						<span class="menu-text">Gestion de<br>Programas</span>
					  </a>
					</li>	
					
					<li id="li_btn_setting" >
					  <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/sys_setting_site" screen="ScreenRight"  id="btn_setting" >
						<span class="menu-icon">
						  <i class="zmdi zmdi-desktop-mac zmdi-hc-fw"></i>
						</span>
						<span class="menu-text">Sitio</span>
					  </a>
					</li>
					

				';
        }
		
		if( $Perfil_User == 1 ){			
		
			$html .= '									
					<li class="menu-title">Configuraciones</li>
					
					<li id="li_btn_componentes" >
					  <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/admin-empresa" screen="ScreenRight"  id="btn_empresas" >
						<span class="menu-icon">
						  <i class="zmdi zmdi-view-web"></i>
						</span>
						<span class="menu-text">Empresas</span>
					  </a>
					</li>
					
					<li id="li_btn_tools" >
					  <a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/admin_tools_site" screen="ScreenRight"  id="btn_tools" >
						<span class="menu-icon">
						  <i class="zmdi zmdi-settings"></i>
						</span>
						<span class="menu-text">Tools</span>
					  </a>
					</li>
						
					<li id="li_btn_sistema" >
					<a href="javascript:void(null);"  onclick="LoadPage(this);" direction="/sadministrator/procesos_sistema" screen="ScreenRight"  id="btn_sistema" >
					  <span class="menu-icon">
						<i class="zmdi zmdi-dns"></i>
					  </span>
					  <span class="menu-text">Procesos</span>
					</a>
				  </li>

				';
				
        }
		
		
		
    $html .= '				
          </ul>
        </div>
    </div>';
	return $html;
}

function DCMarca_Menu($Id_Btn){

    
	$Script ="

		<script>
		$('.sidebar-menu li').css({'background-color':'rgb(245, 245, 245)'} );   	
		$('.sidebar-menu li i').css({'color':'#333','font-weight':'100'} );   		
		$('#".$Id_Btn."').css({'background-color':'#ccc','font-weight':'800'} );
		$('#".$Id_Btn." i').css({'color':'rgb(43, 146, 233);'} );   

		</script>
	";
    return $Script;	
}

function DCPanelTitle($Titulo, $SubTitulo,$Botones, $Clase) {///Panel con subtitulo
    
	$Html = '
	        <div class="panel-heading" style="">
                <div class="panel-tools">
			    '.$Botones.'
                </div>
                <h1 class="panel-title">'.$Titulo.'</h1>
                <div class="panel-subtitle">'.$SubTitulo.'</div>
            </div>
	';
    return $Html;
	
}

function DCPanelTitleB($Titulo, $SubTitulo,$Botones, $Clase) {///Panel con subtitulo

    $Html = '
	        <div class="panel-heading" style="padding: 18px 17px; margin-right: 15px;">
                <div class="panel-tools">
			    '.$Botones.'
                </div>
                <h1 class="panel-title">'.$Titulo.'</h1>
                <div class="panel-subtitle">'.$SubTitulo.'</div>
            </div>
	';
    return $Html;

}

function DCPage($Header, $Contenido, $Clase) {///Panel con subtitulo
    if(empty($Clase)){
		$Clase = "panel panel-default m-b-0";
	}
	
    $Html = '
	
                <div class="'.$Clase.'"  style="border:none;padding:0px;" >
					'.$Header.'
					<div class="panel-body" style="background:#fafafa;" >
					'.$Contenido.'
					</div>
                </div>
	
	';
    return $Html;
}

function DCPageB($Header, $Contenido, $Clase) {///Panel con subtitulo
    if(empty($Clase)){
		$Clase = "panel panel-default m-b-0";
	}
	
    $Html = '
	
                <div class="'.$Clase.'"  style="border:none;padding:0px;" >
					'.$Header.'
					<div style="background:#fafafa;" id="cuerpo-panel" >
					'.$Contenido.'
					</div>
                </div>
	
	';
    return $Html;
}


function DCButton($menus, $clase, $formId) {
	
    $menu = explode("}", $menus);
	$Cont_SubMenu = 0;
    $v = '<div class="' . $clase . '">';
    for ($j = 0; $j < count($menu) - 1; $j++) {
        $mTemp = explode("]", $menu[$j]);
        $url = $mTemp[1];
        $pane = $mTemp[2];
        $panelCierra = $mTemp[3];
        $Class = $mTemp[5];
		
		if(empty($Class)){
			$Class = "btn btn-default";
		}

       if ($mTemp[1] == "SubMenu") {
		   
	        $Cont_SubMenu += 1;	
			
            $v = $v .'<div class="btn-group">';			
            $v = $v . "<button  type='button'   class='" . $Class . "' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true' id-style='Btn_H_".$j.$Cont_SubMenu."'>";
            $v = $v . $mTemp[0];
            $v = $v . "</button>";
			
            $v = $v . "<ul class='dropdown-menu' >";

            $sub_menu = explode("{",  $mTemp[2]);
            $m = "";
            for ($k = 0; $k < count($sub_menu) - 1; $k++) {

                $sub_menu_element = explode("[",  $sub_menu[$k]);
                if ($sub_menu_element[3] == "CHECK") {

                    $m = $m . "<li onclick=enviaFormNA_Val('" . $sub_menu_element[1] . "','" . $sub_menu_element[4]. "','" . $sub_menu_element[2]. "','true');  >";
                    $m = $m . "<a>";

                }elseif($sub_menu_element[3] == "HXM"){
 
					$m = $m . "<li>";
					$m = $m . "<a type_send='HXM' onclick=LoadPage(this); direction='".$sub_menu_element[1]."' screen='".$sub_menu_element[2]."'  data-style='expand-right' id='Sub_Btn_H_".$formId.$k.$Cont_SubMenu."' data-target='#animatedModal5' data-toggle='modal'> ";
                
				}elseif($sub_menu_element[3] == "HXMS"){
 
					$m = $m . "<li>";
					$m = $m . "<a type_send='HXM' onclick=LoadPage(this); direction='".$sub_menu_element[1]."' screen='".$sub_menu_element[2]."'  data-style='expand-right' id='Sub_Btn_H_".$formId.$k.$Cont_SubMenu."' > ";
										
                }elseif($sub_menu_element[3] == "FORM"){
 
					$m = $m . "<li>";
					$m = $m . "<a type_send='FORM' onclick=SaveForm(this); id='Sub_Btn_H_".$formId.$k.$Cont_SubMenu."' direction='".$sub_menu_element[1]."' screen='".$sub_menu_element[2]."' form='" . $sub_menu_element[4]. "' > ";

				} elseif ("HREF" == $sub_menu_element[3]) {
					
					$m = $m . "<li>";
					$m = $m . "<a  href='" . $sub_menu_element[1] . "' Target='".$sub_menu_element[2]."' class='" . $Class . "'  id='Sub_Btn_H_".$formId.$k.$Cont_SubMenu."' >";			 
				
				} elseif ("HREF_DONWLOAD" == $sub_menu_element[3]) {
					
					$m = $m . "<li>";
					$m = $m . "<a  href='" . $sub_menu_element[1] . "' Target='".$sub_menu_element[2]."' class='" . $Class . "'  id='Sub_Btn_H_".$formId.$k.$Cont_SubMenu."' download>";			 
				 				 
             
     			 } else{
                    
					$m = $m . "<li>";
                    $m = $m . "<a  onclick=LoadPage(this); direction='".$sub_menu_element[1]."' screen='".$sub_menu_element[2]."'   data-style='expand-right' id='Sub_Btn_H_".$formId.$k.$Cont_SubMenu."'> ";
            					
                }
                $m = $m .$sub_menu_element[0];
                $m = $m .' </a>';
                $m = $m .' </li>';
				
            }
            $v = $v . $m;

            $v = $v . "</ul>";
            $v = $v . "</div>"; 
			
        } else {
			

            if ($mTemp[3] == "CHECK") {
                $v = $v . "<button  type='button'   onclick=enviaFormNA_Val('" . $url . "','" . $formId. "','" . $pane. "','true'); class='" . $Class . "' >";
            
			} elseif ($mTemp[3] == "FORM") {

                $v = $v . "<button  type='button' type_send='FORM' onclick=SaveForm(this); id='Btn_H_".$j.$Cont_SubMenu."'  direction='".$url."' screen='".$pane."' form='" .$mTemp[4]. "'  class='" . $Class . "'  >";
			
			} elseif ($mTemp[3] == "FORM_JSA") {

                $v = $v . "<button  type='button' type_send='FORM' id_var_input='".$mTemp[6]."' onclick=SaveFormJSA(this); id='Btn_H_".$j.$Cont_SubMenu."'  direction='".$url."' screen='".$pane."' form='" .$mTemp[4]. "'  class='" . $Class . "'  >";
				
				
		    } elseif ("HREF_DONWLOAD" == $mTemp[3]) {

                $v = $v . "<a  href='" . $mTemp[1] . "'  class='" . $Class . "' download >";	
				
		    } elseif ("HREF" == $mTemp[3]) {

                $v = $v . "<a  href='" . $mTemp[1] . "' Target='".$mTemp[2]."' class='" . $Class . "'  >";
				
            }elseif("HXM"  == $mTemp[3]){

                $v = $v . "<button  type='button' type_send='HXM' onclick=LoadPage(this); direction='".$url."' screen='".$pane."'  class='" . $Class . "' data-style='expand-right' id='".$formId."_Btn_H_".$j . $Cont_SubMenu."' data-target='#animatedModal5' data-toggle='modal'> ";

            }elseif("HXMS"  == $mTemp[3]){

                $v = $v . "<button  type='button' type_send='HXM' onclick=LoadPage(this); direction='".$url."' screen='".$pane."'  class='" . $Class . "' data-style='expand-right' id='".$formId."_Btn_H_".$j . $Cont_SubMenu."' > ";
				
				
            }elseif("POPOVER"  == $mTemp[3]){

                $v = $v . "<button type='button'  class='" . $Class . "'  onclick='Popover_Content()' >";
				
				
            } else {
				
                $v = $v . "<button  type='button'  onclick=LoadPage(this); direction='".$url."' screen='".$pane."'  class='" . $Class . "' data-style='expand-right' id='".$formId."_Btn_H_".$j.$Cont_SubMenu."'> ";
            
			}

            $v =    $v . "
			           <span class='ladda-label'>".$mTemp[0]."</span>
                       <span class='ladda-spinner'></span>
			        ";
			
			if ("HREF" == $mTemp[3] || "HREF_DONWLOAD" == $mTemp[3] ) {
                $v = $v . "</a>";				
			}elseif("POPOVER" == $mTemp[3]){
				 $v = $v . "</button>";
				 $v.=DCPopover("Instrucciones","<p>En la siguiente imagen se mostrara el formato que tendra su excel, <br>definiendo como ultima columna el genero(Masculino o Femenina)","/sadministrator/simages/rs/formato_masivo.png");


			}else{
                $v = $v . "</button>";				
			}	
			
        }
    }
    $v = $v . "</div>";
    return $v;
}


function DCModalX($Contenido){
	
	$Html = '
	
	 <div class="modal-dialog">
		<div class="modal-content animated flipInX">
		
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">
				<i class="zmdi zmdi-close"></i>
			  </span>
			</button>
		  </div>
		  <div class="modal-body">
			<div class="text-center">
			   '.$Contenido.'
			</div>
		  </div>
		  <div class="modal-footer"></div>
		  
		  
		</div>
	</div>
	
	';
	return $Html;
	
}

function DCModalFormMsj($Titulo,$Body,$Buttons,$Class){
	
	if(empty($Class)){
	    $Class = "bg-success";
	    $ClassIcon = "zmdi zmdi-check zmdi-hc-5x";
	}
	$style="";
	
	if($Class == "bg-warning"){
	    $ClassIcon = "zmdi zmdi-alert-circle-o zmdi-hc-5x";		
	}

	if($Class == "bg-info"){
	    $ClassIcon = "zmdi zmdi-help zmdi-hc-5x";		
	}
	
	if($Class == "bg-info-b"){
		$Class = "bg-info";
	    $ClassIcon = "zmdi zmdi-info zmdi-hc-5x";		
	} 
	if ($Class="bg-default") {
		$Class="";
		$style="background-color: #f9f8faf7!important;border-color: #7d57c1!important;color: #0a0a0a!important;";
		$ClassIcon = "zmdi zmdi-help zmdi-hc-5x";
	}
		

	$Html = '
  
                  <div class="modal-dialog">
                    <div class="modal-content '.$Class.'" style='.$style.'>
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">
                            <i class="zmdi zmdi-close"></i>
                          </span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="text-center">
                          <div>
                            <i class="'.$ClassIcon.'"></i>
                          </div>
                          <h3>'.$Titulo.'</h3>
                          <p>'.$Body.'</p>
                          <div class="m-y-30" >
							'.$Buttons.'
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer"></div>
                    </div>
                  </div>
				     
	';
	return $Html;
}


function DCModalFormMsjInterno($Titulo,$Body,$Buttons,$Class){
	
	if(empty($Class)){
	    $Class = "bg-success";
	    $ClassIcon = "zmdi zmdi-check zmdi-hc-5x";
	}
	
	if($Class == "bg-warning"){
	    $ClassIcon = "zmdi zmdi-alert-circle-o zmdi-hc-5x";		
	}

	if($Class == "bg-info"){
	    $ClassIcon = "zmdi zmdi-help zmdi-hc-5x";		
	}
	
	if($Class == "bg-info-b"){
		$Class = "bg-info";
	    $ClassIcon = "zmdi zmdi-info zmdi-hc-5x";		
	}
		

	$Html = '
  
                  <div class="modal-dialog" style="height:140px;">
                    <div class="modal-content '.$Class.'">
                  
                      <div class="modal-body">
                        <div class="text-center">
                    
                          <h3>'.$Titulo.'</h3><div>'
                          .$Body.'</div>
                          <div class="m-y-30" >
							'.$Buttons.'
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer"></div>
                    </div>
                  </div>
				     
	';
	return $Html;
}


function DCModalFormListMsj($Titulo,$Body,$Buttons,$Class,$Titulo2,$Cuerpo2){
	
	if(empty($Class)){
	    $Class = "bg-success";
	    $ClassIcon = "zmdi zmdi-check zmdi-hc-5x";
	}
	
	if($Class == "bg-warning"){
	    $ClassIcon = "zmdi zmdi-alert-circle-o zmdi-hc-5x";		
	}

	if($Class == "bg-info"){
	    $ClassIcon = "zmdi zmdi-help zmdi-hc-5x";		
	}
	
	if($Class == "bg-info-b"){
		$Class = "bg-info";
	    $ClassIcon = "zmdi zmdi-info zmdi-hc-5x";		
	}
		

	$Html = '
  
                  <div class="modal-dialog">
                    <div class="modal-content ">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">
                            <i class="zmdi zmdi-close"></i>
                          </span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="text-center '.$Class.'">
                          <div>
                            <i class="'.$ClassIcon.'"></i>
                          </div>
                          <h3>'.$Titulo.'</h3>
                          <p>'.$Body.'</p>
                          <div class="m-y-30" >
							'.$Buttons.'
                          </div>
                        </div>
                        <div class="text-center">
                        	<h4 class="modal-title">'.$Titulo2.'</h4>
                        	<br>
                        	<div>'.$Cuerpo2.'</div>
                        </div>
                      </div>
                      <div class="modal-footer"></div>
                    </div>
                  </div>
				     
	';
	return $Html;
}
function DCModalForm($Titulo,$Body,$Class){
	
	if(empty($Class)){
	    $Class = "modal-dialog";
	}
	
	$Html = '
   
	    <div class="'.$Class.'" >
			<div class="modal-content" >
			
			  <div class="modal-header bg-primary">
			  
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">
					<i class="zmdi zmdi-close"></i>
				  </span>
				</button>
				
				<h4 class="modal-title">'.$Titulo.'</h4>
			  </div>
			  
			  '.$Body.'
			</div>
		</div>
	
	';
	return $Html;
}
function DCPopover($Titulo,$Contenido,$img){
	
	$Html = " <div class='card_popover' id='Popover_Muestra' style='display:none;'>
					  <div class='card-header'><h4 class='modal-title'>".$Titulo."</h4>
					    
					  </div>
					  <div class='card-body'>
					   	<div class='text-center'>
						   ".$Contenido."
						</div>
					   	<img src='".$img."'  alt=''>
					   	<br>
					   	<div class='text-center' style='margin-top:5px;' >
						   	<button type='button'aria-label='Close' class='btn btn-default' onclick='Popover_Cerrar()' id='Popover_btn_cerrar'  style='display:block;margin:auto; border-radius:50%;'>
							  <span aria-hidden='true'>
								<i class='zmdi zmdi-close'></i>
							  </span>
							</button>
						</div>
					  </div>
					</div>
	
	";
	return $Html;
	
}



function DCCloseModal(){
	
	$Script ="
		<script>
		$('.modal-backdrop').remove();
		$('.modal-dialog').remove();
		$('#animatedModal5').modal('hide');
		</script>
	";
	echo $Script;
}

function DCComboBoxSelect($Sql,$where,$Id,$IdTag,$Class,$Type_Data_Oup,$Options_Oup,$Id_Object_Ext){

	$reg = ClassPDO::DCRows($Sql,$where,"");
	
	$ComboBox = '
	<select id="'.$Id_Object_Ext."-".$IdTag.'"  name="'.$IdTag.'" class="'.$Class.'" >
	';
	if(empty($Id)){
		
		// if($Type_Data_Oup == "B"){
				$ComboBoxSearch .= '<option value="">Selecciona una opción</option>';			
		// }		
		
	}
	
	$ComboBoxNoSearch .="";
	foreach($reg as $Rows)
	{
		if($Rows->Id == $Id ){
			$ComboBoxSearch = '<option value="'.$Rows->Id.'">'.$Rows->Name.'</option>';				
		}else{
			$ComboBoxNoSearch .= '<option value="'.$Rows->Id.'">'.$Rows->Name.'</option>';			
		}
				  
	}
	
		
	$ComboBox .= $ComboBoxSearch . $ComboBoxNoSearch;
	if($Type_Data_Oup == "B"){
				$ComboBox .= '<option value="0">Todos</option>';			
	}	
	if($Type_Data_Oup == "IU"){
				$ComboBox .= '<option value="0">Otras opciones</option>';			
	}		
	$ComboBox .= '</select> ';
	return $ComboBox;
}


function Format_Sintax(array $Datos) {

    if (!empty($Datos)) {
        foreach ($Datos as $name => $value) {
            $Rtn[] = $name . '= :' . $name;
        }
    }
    return $Rtn;
}


function DCDataGrid($titulo,$sql,$where,$conexion,$clase,$ord,$url,$enlaceCod,$panel,$id_tabla,$checks,$paginador,$tipoUrl,$urlPaginacion) {
	
	// echo $tipoUrl;

    $totReg = ClassPDO::countrows($sql, $where ,$conexion);
    $nvistas = DCGet("nvistas");
    if ($paginador != '') {	
	
		if(!empty($nvistas)){
			$_SESSION['nvistas'] = $nvistas;
		}

		if(!empty($_SESSION['nvistas'])){
			$paginador = "0,".$_SESSION['nvistas'];
			$paginacionTot = $_SESSION['nvistas'];
		}else{
			$p = explode(',', $paginador);
			$paginacionTot = $p[1];
		}
     
        $result = pag($sql, $paginador);
        $sql = $result["sql"];
        $val_desde =$result["val_desde"];
        $val_hasta =$result["val_hasta"];
		
    }

    $cmp = array();
    $countcolumn = ClassPDO::countcolumn($sql,$where ,$conexion);
    $ccoulum = $countcolumn[0];
    $objQuery = $countcolumn[1];

    $v = "<div id='" . $clase . "' class='row'>";
    $v .= "<div class='col-sm-12' >";
	
	
    if ($titulo != "") {
        $v = $v . "<div style='width:100%;float:left;'><h1>" . $titulo . "<h1></div>";
    }

		$v = $v . "<div  style='width:100%;float:left;'>";
			$v = $v . "<div id='divTable' >";
			$v = $v . "</div>";

        if ($paginador != '') {	
		
			$v = $v . "<div style='font-size: 13px;padding: 6px 10px 6px 0px;color: #91929d;float:left;'> Nro. de registros por vista: ".$paginacionTot." ";
			$v = $v . "</div>";

			$v = $v . '<div style="font-size: 13px;padding: 6px 0px 6px 0px;color: #91929d;float:left;"> 
							<form name="paginador-'.$id_tabla.'" method="post" id="paginador-'.$id_tabla.'">
							<select name="nvistas" id="nvistas"  onchange=pideNVistasPaginador("'.$panel.'"); >
							<option value="Selecciona"   >Selecciona</option>
							<option value="5" id="c_5"  >5</option>
							<option value="10" id="c_10" >10</option>
							<option value="20" id="c_20"  >20</option>
							<option value="50" id="c_50" >50</option>
							<option value="Todos" id="c_Todos" >Todos</option>
							</select>
							</form>
						</div>
						';
		}				

		$v = $v . "</div>";
	

	
    if ($checks == 'checks' || $checks == 'form') {
        $v = $v . "<form name='" . $id_tabla . "' method='post' id='" . $id_tabla . "'>";
    }
								
    $v = $v . "<table id='table-1' class='".$clase."' >";
    $v = $v . "<thead>";
    $v = $v . "<tr>";
	
	if($paginador !== "" ){
		$v = $v . "<th >Nro.</th>";
	}

    for ($i = 0; $i < $ccoulum; ++$i) {

        $arr_campo = $objQuery->getColumnMeta($i);
        $campo = $arr_campo['name'];

        if ($campo != "CodigoLink" && $campo != 'CodigoLinkB' && $campo != 'CodigoLinkC') {
            if ($checks != 'SinTitulo') {
                if($checks == 'checks' && $campo == 'EstadoCheck'){
                    $v = $v . "<th > <input type='checkbox' name='checkAllSelected' value='all' onclick=\"checkAll('$id_tabla', this);\"></th>";
                }else{
                    $v = $v . "<th >" . $campo . "</th>";
                }
            }
        }
		
        $cmp[$i] = $campo;
    }



    if ($checks == 'checks') {
		
        $v = $v . " <th>";
		$v = $v . ' 
				<label class="custom-control custom-control-primary custom-checkbox active">
				  <input class="custom-control-input" type="checkbox" name="custom"  id="element-Check'.$id_tabla . '" >
				  <span class="custom-control-indicator" onclick=\'checkAllSelect("'.$id_tabla.'","element-Check'.$id_tabla . '");\' ></span>
				</label>						
		';
        $v = $v . "  </th>";
    }

    $v = $v . "</tr>";
    $v = $v . "</thead>";

    $v = $v . "</tbody>";

    $contA= 0;

    $countReg = ClassPDO::DCRows($sql,$where ,$conexion);
    $contB = 0;
    foreach ($countReg as $reg) {
        $contA += 1;
        for ($k = 0; $k < $ccoulum; ++$k) {
          

            $arr_campo = $objQuery->getColumnMeta($k);
            // DCVd("name:: ".$arr_campo['name']);
			
            $campo = $arr_campo['name'];
            $campoB = $cmp[$k];

            if ($campo == "CodigoLink") {
                $codAjax = $reg->$campoB;
            }
            if ($campo == "CodigoLinkB") {
                $codAjaxB = $reg->$campoB;
            }

            if ($campo == "CodigoLinkC") {
                $codAjaxC = $reg->$campoB;
            }

            $enlaceCodArray = explode(",", $enlaceCod);;

            $parm1 = $enlaceCodArray[0];
            $parm2 = $enlaceCodArray[1];
            $parm3 = $enlaceCodArray[2];

            if(!empty($parm1))
                $seg_url ="/" . $parm1 . "/" . $codAjax;

            if(!empty($parm2))
                $seg_urlB="/" . $parm2 . "/" . $codAjaxB;

            if(!empty($parm3))
                $seg_urlC="/" . $parm3 . "/" . $codAjaxC;

            $url2 = $url.$seg_url.$seg_urlB.$seg_urlC;
        }

		
        if(!empty($enlaceCod)){
			
            if ($checks == 'Buscar') {
                    $v = $v . "<tr  
					
					onclick=LoadPage(this); 
					role='row'
					direction='".$url2."'
					screen='".$panel."'
					id='".$codAjax."'
					type_send='HXM'
					data-toggle='modal'
					data-target='#animatedModal5'	
					
					>";
            } else {
						// DCVd($tipoUrl);
                if($tipoUrl == "HREF"){
					
					$AtributosTD = "
					
					onclick=LoadPage(this); 
					role='row'
					direction='".$url2."'
					screen='".$panel."'
					id='".$codAjax."'
					type_send=''
					
					";
					
                    $v = $v . "<tr>";
					
                }elseif($tipoUrl == "PS"){//Secuencia de popups
					
					$AtributosTD = "
					onclick=LoadPage(this); 
					direction='".$url2."'
					screen='".$panel."'
					id='".$codAjax."'
					data-style='expand-right'
					type_send='HXM'
					
					";
					
                    $v = $v . "<tr>";	
					
                }else{
					
					// DCVd("Hola");
					
					$AtributosTD = "
					onclick=LoadPage(this); 
					role='row'
					direction='".$url2."'
					screen='".$panel."'
					id='".$codAjax."'
					type_send='HXM'
					data-toggle='modal'
					data-target='#animatedModal5'	
					";
					
                    $v = $v . "<tr>";
                }
            }
        }

        $cont = 0;

        for ($i = 0; $i < $ccoulum; ++$i) {
            $cont += 1;
            $arr_campo = $objQuery->getColumnMeta($i);
            $campo = (string)$arr_campo['name'];
			
			if($paginador !== "" ){
				
				if($i == 0){
					$contB += 1;
					$nroReg = $val_desde + $contB;
					$v = $v . "<td >" . $nroReg  . "</td>";
				}
				
            }
            if ($campo != "CodigoLink" && $campo != 'CodigoLinkB' && $campo != 'CodigoLinkC' ) {

                // $v = $v . "<td ".$AtributosTD.">" . $reg->$cmp[$i]. "</td>";
				$strvar = (string)$cmp[$i];
                $v = $v . "<td ".$AtributosTD.">" . $reg->$strvar. "</td>";
                // $v = $v . "<td ".$AtributosTD.">" . $cmp[$i]. "</td>";

            }

            if ($checks == 'checks' && $ccoulum == $cont) {
				
                $v = $v . "<td >";

                $v = $v . '
							
						<label class="custom-control custom-control-primary custom-checkbox active">
						  <input class="custom-control-input" type="checkbox" name="ky[]" value="'.$codAjax.'" id="element-Check'.$contA.$id_tabla . '">
						  <span class="custom-control-indicator"></span>
						</label>	
						
						  ';
                $v = $v . "</td>";
            }
        }
        $v = $v . "</tr>";
    }


    $v = $v . "</tbody>";
    $v = $v . "</table>";
	
    if ($checks == "checks" || $checks == 'form') {
        $v = $v . "</form>";
    }
    $v = $v . "</div>";

    if ($paginador != '') {
        $v = $v . DCPaginator($sql,$paginador,$totReg,$panel,$urlPaginacion);
    }

    $v = $v . '</div>
                </form>			
               </div>
				';

    if ($totReg == 0) {
        $v = '<div class="MensajeTable" style="float:left;width:95%;">(!) No se encontó ningún registro...</div>';
    }

    return $v;
}


function DCPaginator($sql, $pag, $total, $panel, $url) {
	
    $p = explode(',', $pag);
    $v = "<div class='paginador'>";
    if (DCIniPag($pag)) {

        if (count($p) == 1) {
            $ini = $p[0];
            $fin = $p[0];
        } else {
            $ini = $p[0] - $p[1];
            $fin = $p[1];
        }
    }

    if (DCFinPag($pag, $total)) {
        if (count($p) == 1) {
            $ini = $p[0];
            $fin = $p[0];
        } else {
            $ini = $p[0] + $p[1];
            $fin = $p[1];
        }
    }

    $numBtn = 	round(($total / $p[1]) + 1);

    $v .= "<div class='texto'>";
    $v .= "</div>";
    $v .= "<div class='botonera'>";
    $v .= "<ul>";

    $rangoBtnVisualizados = $_SESSION['nvistas'];
    $rangoMovimiento = 5;
    $grupo = 0;
    if(get("grupoPaginator")==""){
        $grupo = 0;
        $regDesde = 0;
        $regHasta = 10;
    }else{
        $grupo = get("grupoPaginator");
        $grupoUrlNumNew = $grupo + 1;
        $grupoUrlOld = $grupo - 1;

        $regDesde = $rangoMovimiento * $grupo;
        $regHasta = ($rangoMovimiento * $grupo) + $rangoBtnVisualizados;
        $regHastaOld = $rangoMovimiento + $regDesde;
    }

    $grupoFinal =  round($numBtn / $rangoMovimiento) - 1;
    $pagin = get("pagin");
    if(empty($pagin)){ $pagin = 1; }

    $regHastaP = $pagin * $rangoBtnVisualizados;
    $regDesdeP = ($regHastaP - $rangoBtnVisualizados) + 1;
    if($total < $regHastaP ){
        $regHastaP = $total;
    }

    $v .= "<li>";
    $v = $v . "<span>Registros, del: ".$regDesdeP." al: ".$regHastaP.", de un total: ".$total."</span>";
    $v .= "</li>";

    $matrisBtn = "";
    for ($i = 1; $i <= $numBtn; $i++) {
        if($i == 1 && $grupo != 0 ){

            $grupoUrl = "&grupoPaginator=0";

            $v .= "<li>";
            $v = $v . "<a href='#'  onclick=enviaVistaPaginador('".$url."&pagin=".$i.$grupoUrl."','".$panel."',''); >".$i."</a>";
            $v .= "</li>";
            $v .= "<li>";
            $v = $v . "<span>....</span>";
            $v .= "</li>";

        }

        if($grupo == 0 && $regDesde >= 0  && $i <= $regHasta ){

            if($i == $regHasta ){
                $grupoUrl = "&grupoPaginator=1";
            }else{
                $grupoUrl = "&grupoPaginator=0";
            }

            if($i == $pagin){
                $v .= "<li>";
                $v = $v . "<a href='#'  class='activo' onclick=enviaVistaPaginador('".$url."&pagin=".$i.$grupoUrl."','".$panel."',''); >".$i."</a>";
                $v .= "</li>";
            }else{
                $v .= "<li>";
                $v = $v . "<a href='#' onclick=enviaVistaPaginador('".$url."&pagin=".$i.$grupoUrl."','".$panel."',''); >".$i."</a>";
                $v .= "</li>";
            }
        }


        if( $grupo != 0 && $i >=$regDesde && $i <= $regHasta ){

            if($i == $regHasta ){
                $grupoUrl = "&grupoPaginator=".$grupoUrlNumNew;

            }elseif( $i < $regHastaOld ){
                $grupoUrl = "&grupoPaginator=".$grupoUrlOld;
            }else{
                $grupoUrl = "&grupoPaginator=".$grupo;
            }

            if($i == $pagin ){
                $v .= "<li>";
                $v = $v . "<a href='#'  class='activo' onclick=enviaVistaPaginador('".$url."&pagin=".$i.$grupoUrl."','".$panel."',''); >".$i."</a>";
                $v .= "</li>";
            }else{
                $v .= "<li>";
                $v = $v . "<a href='#' onclick=enviaVistaPaginador('".$url."&pagin=".$i.$grupoUrl."','".$panel."',''); >".$i."</a>";
                $v .= "</li>";
            }
        }


        if($i == $numBtn && $grupo != $grupoFinal ){

            $grupoUrl = "&grupoPaginator=".$grupoFinal;

            $v .= "<li>";
            $v = $v . "<span>....</span>";
            $v .= "</li>";
            $v .= "<li>";
            $v = $v . "<a href='#'  onclick=enviaVistaPaginador('".$url."&pagin=".$i.$grupoUrl."','".$panel."',''); >".$numBtn."</a>";
            $v .= "</li>";

        }

    }
    $v .= "</ul>";
    $v .= "</div>";
    $v .= "</div>";
    $nvistas = get("nvistas");
    if($nvistas !== "Todos"){
        return $v;
    }else{
        return "";
    }
}


function DCPages($sql, $pag) {
    $p = explode(',', $pag);

    $getURL = DCGet("pagin");
    $nvistas = DCGet("nvistas");

    $index = $getURL - 1;
    if(empty($getURL)){
        $index = 0;
    }

    if($index == 0){
        $val_desde = $p[0];
        $val_hasta = $p[1];
    }else{
        $val_desde = ($p[1] * $index);
        $val_hasta = $p[1];
    }

    if($nvistas !== "Todos"){
        $sql = $sql . ' limit '.$val_desde.',' . $val_hasta;
    }

    $result = Array();
    $result["sql"] = $sql;
    $result["val_desde"] = $val_desde;
    $result["val_hasta"] = $val_hasta;
    return $result;
}

function DCIniPag($pag) {
    $p = explode(',', $pag);
    if (count($p) == 1) {
        return false;
    } else {
        if ($p[0] == 0) {
            return false;
        } else {
            return true;
        }
    }
}


function DCFinPag($pag, $total) {
    $p = explode(',', $pag);
    if (count($p) == 1) {
        if ($p[0] >= $total) {
            return false;
        } else {
            return true;
        }
    } else {
        if (($p[0] + $p[1]) >= $total) {
            return false;
        } else {
            return true;
        }
    }
}


function DCFormPluginA(){
	
	$Script =  '
	<script>
	$.getScript("/sadministrator/sbookstores/js/application.min.js");
	$.getScript("/sadministrator/sbookstores/js/forms-plugins.min.js");
	</script>
	';	

    return $Script;
}

function firebase(){

    $Script =  '
	<script>
	$.getScript("/sadministrator/sbookstores/js/jquery.3-1.not.slim.min.js");
	</script>
	';

    return $Script;
}

function MonitorChat(){
	
	$Script =  '
	<script>
	$.getScript("/sadministrator/sbookstores/js/monitor_chat.js");
	</script>
	';	

    return $Script;
}

function DCTablePluginA(){
	
	$Script =  '
	<script>
	$.getScript("/sadministrator/sbookstores/js/tables-datatables.min.js");
	</script>
	';	

    return $Script;
}

function DCSlidePlugin(){
	
	$Script =  '
	<script>
	
		$.getScript("/splugin/slide/js/miniSlider.js");
	
	    $(function() {
		$("#slider").miniSlider({autoPlay:true,delay:5000});
		$("#slider_b").miniSlider({autoPlay:false,delay:8000});
		});
		
	</script>
	';	

    return $Script;
}


function DCRedirect($Setinng) {
    header('Location:' . $Setinng . '');
}

function DCRedirectJSSP($Settings){
	

	$Script =  "
	<script>
	Redirect('" . json_encode($Settings) . "');
	</script>
	";	
    echo $Script;
}


function DCRedirectJSTime($Settings){
	

	$Script =  "
	<script>
	setTimeout(Redirect('" . json_encode($Settings) . "'), 3000);
	
	
	</script>
	";	
    echo $Script;
}


function DCRedirectJS($Settings){
	
	// $Script =  '
	// <script>
	// $.getScript("/sadministrator/sbookstores/js/tables-datatables.min.js");
	// </script>
	// ';		
	$Script .=  "
	<script>
	Redirect('" . json_encode($Settings) . "');
	</script>
	";	
    DCCloseModal();
    echo $Script;
}


function DCTabs($menus, $clase, $idMenu , $parm) {

    $menu = explode("}", $menus);

    $v = '<ul id="'.$idMenu.'"  class="'.$clase.'" >';
    $Contador = 0;
    for ($j = 0; $j < count($menu) - 1; $j++) {
        $Contador +=1;
        
		$mModificacion = 0;
		// DCVd($parm);
        foreach ($parm as $nombre => $valor) {
			
			
            $mModificacion = $nombre;
          

            if($mModificacion == $j){
                $mParm = explode("]", $valor);
                $mValue=$mParm[0];
                $Marca=$mParm[1];
            }
        }

        $mTemp = explode("]", $menu[$j]);
        $url = $mTemp[1];
        $pane = $mTemp[2];
        $Tipo_Link = $mTemp[4];
		
		if(empty($Tipo_Link)){
		    $type_send = "";
		}else{
		    $type_send = "HXM";			
		}
		
        // DCVd($Tipo_Link);

        if ($Marca == "Marca") {
			
            $v = $v . '<li class="active" >';	
			
				if($pane == "HREF") {
							
		
					$v = $v . '<a id="'.$idMenu."_SBT_".$Contador.'" href="'. $url.$mValue .'">';
					
					$v = $v . $mTemp[0];
					$v = $v . "</a>";					
					
				}else{
					
					$v = $v . '<a id="'.$idMenu."_SBT_".$Contador.'" direction="'. $url.$mValue .'" onclick="LoadPage(this);" 
					screen="'.$pane.'" type_send="'.$type_send.'">';
					
					$v = $v . $mTemp[0];
					$v = $v . "</a>";
				
				}
				
			
        }  elseif ($Marca == "HREF") {
			
		    $v = $v . '<li>';
            $v = $v . '<a id="'.$idMenu."_SBT_".$Contador.'" direction="'. $url.$mValue .'" onclick="LoadPage(this);" screen="'.$pane.'" type_send="HXM">';
            
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
			
        } else {
			
				if($pane == "HREF") {			
			
					$v = $v . '<li>';			
					$v = $v . '<a id="'.$idMenu."_SBT_".$Contador.'" href="'. $url.$mValue .'">';
					
					$v = $v . $mTemp[0];
					$v = $v . "</a>";
					
				}else{
					
					$v = $v . '<li>';			
					$v = $v . '<a id="'.$idMenu."_SBT_".$Contador.'" direction="'. $url.$mValue .'" onclick="LoadPage(this);" 
								screen="'.$pane.'" type_send="'.$type_send.'">';
					
					$v = $v . $mTemp[0];
					$v = $v . "</a>";					
				}	
			
        }

        $v = $v . "</li>";
    }

    $v = $v . "</ul>";
    return $v;
}



function BFormVertical($Id_Object_Ext,$Class,$Id,$PathImage,$Combobox,$Buttons,$Field_Key){
	
	$Data = file_get_contents("sbd_json/".$Id_Object_Ext.".json");
	$Result = json_decode($Data, true);
	
	// DCVd($Result);
	
	$Query = "SELECT OB.Id_Object, OB.Name, WR.Name AS Warehouse_Name
			  FROM object OB 
			  INNER JOIN warehouse WR ON OB.Id_Warehouse = WR.Id_Warehouse
			  WHERE OB.Name = :Name
			  ";	
	$Where = ["Name" => $Id_Object_Ext];
	$Row = ClassPdo::DCRow($Query,$Where,$Conection);
	
	$Query = "SELECT * FROM ".$Row->Warehouse_Name." WHERE ".$Field_Key." = :".$Field_Key." ";	
	$Where = [$Field_Key => $Id];
	$Row = ClassPdo::DCRow($Query,$Where,$Conection);	

    $Editor_Arr = Array();
    $Text_DataTime = Array();
	$Contador_Text_DataTime = 0;
	$Contador_TextArea = 0;
	$Contador_Select = 0;
	$Contador_Upload = 0;
	$Img = "";
	$Html .= ' <form method="post" name="'.$Id_Object_Ext.'" id="'.$Id_Object_Ext.'" action="javascript:void(null);"  enctype="multipart/form-data">';	
	
		$Html .= ' <div class="modal-body">';	
	    // DCVd("Hola");
		
		foreach ($Result as $Reg) {
			
			
				$WDName = (string) $Reg["WDName"];
				// DCVd($Reg["Id_Field_Type"]);
				
				
				if($Reg["Visibility"] == "SI"){
                     
					switch ($Reg["Id_Field_Type"]) {
							
						case 1:
						
							if( $Reg["Id_Field_Type_Client"] == 5 || $Reg["Id_Field_Type_Client"] == 7 ){
								
								
								// if($Row->$WDName == "0000-00-00"){
									// $Row->$WDName = "";
								// }else{
									// $Row->$WDName = $Row->$WDName;
								// }
								
								$Html .= '
									  <div class="form-group">
										<label for="form-control-1">'.$Reg["Alias"].'</label>
										<input type="text" id="'.$Id_Object_Ext.$Reg["WDName"].'" class="form-control" 
										placeholder="'.$Reg["Alias"].'" name="'.$Reg["WDName"].'" value="'.$Row->$WDName.'">
									  
									  </div>
								';			
								
								$Id_Input = Array();
								$Id_Input["Id"] = $Id_Object_Ext.$Reg["WDName"];
								$Id_Input["Format_Field"] = $Reg["Format_Field"];
								$Id_Input["Format_Type_Client"] = $Reg["Id_Field_Type_Client"];
								$Text_DataTime[$Contador_Text_DataTime] = $Id_Input;

								$Contador_Text_DataTime += 1;
								
							}else{
								
								$Html .= '
									  <div class="form-group">
										<label for="form-control-1">'.$Reg["Alias"].'</label>
										<input type="text" id="'.$Id_Object_Ext.$Reg["WDName"].'" class="form-control" 
										placeholder="'.$Reg["Alias"].'" name="'.$Reg["WDName"].'" value="'.$Row->$WDName.'">
									  </div>
								';	
							}
					
						
							break;
							
						case 2:
									
							$Combobox_Data = $Combobox[$Contador_Select];
                            $Type_Data_Oup = $Combobox_Data[3];
                            $Options_Oup = $Combobox_Data[4];
							$ComboBoxData_Type = DCComboBoxSelect($Combobox_Data[1],$Combobox_Data[2],$Row->$WDName,$Reg["WDName"],"custom-select",$Type_Data_Oup,$Options_Oup,"");
											
							$Html .= '
								  <div class="form-group">
									<label for="form-control-1">'.$Reg["Alias"].'</label>
									'.$ComboBoxData_Type.'
								  </div>
							';
							
                            $Contador_Select += 1;
							break;
							
						case 3:
						
							$Matris_Data = $Reg["Matriz_Data_Select"];
							
							
							// echo $Row->$WDName."   :: ". $Matris_Data."<br>";
							if( $Row->$WDName == $Matris_Data ){
								$Checked = ' checked="checked" ';
							}else{
								$Checked = '  ';
								
							}
								
							$Html .= '
								  <div class="form-group">
									<label for="form-control-1">'.$Reg["Alias"].'</label>
								    <div class="custom-controls-stacked">
										<label class="switch switch-primary">
										  <input type="checkbox" name="'.$Reg["WDName"].'" value="'.$Matris_Data.'" class="s-input" '.$Checked.' >
										  <span class="s-content">
											<span class="s-track"></span>
											<span class="s-handle"></span>
										  </span>
										</label>
									</div>
								  </div>
							';
							
         
							break;							
							
						case 4:
						
							
							$Matris_Data = explode(",", $Reg["Matriz_Data_Select"]);
							$cont = 0;
							$Radio = "";
							
						
							for ($i = 0; $i < count($Matris_Data) - 1; $i++) {
								
								if( $Row->$WDName == $Matris_Data[$i]){
								    $Checked = ' checked="checked" ';
							    }
								
                                $Radio .= '
									<label class="custom-control custom-control-primary custom-radio">
									<input class="custom-control-input" type="radio" name="'.$Reg["WDName"].'" value="'.$Matris_Data[$i].'" '.$Checked.'>
									<span class="custom-control-indicator"></span>
									<span class="custom-control-label">'.$Matris_Data[$i].'</span>
									</label>								
								';								
							}
							
							$Html .= '
								  <div class="form-group">
									<label for="form-control-1">'.$Reg["Alias"].'</label>
									<div class="custom-controls-stacked">
									
									'.$Radio.'
									</div>
								  </div>
							';
							
                            $Contador_Select += 1;
							break;							
							
						case 5: ///Upload
						
						    // DCWrite("Hoa mudno");
							
						    // DCVd($Id_Object_Ext);
							if(!empty($Reg["WDName"])){
										
									// DCVd($Reg["WDName"]);

                                    $Id_Field = "";
                                    $value = "";
                                    $Path_Image = "";
                                    $Extencions = "";
                                    $Size_File = "";
                                    $Img = "";
									
									$value = $Row->$WDName;
                                     
									$nombre_c = $Reg["WDName"];
									$Path_Image = $Reg["Path_Image"];
									$Extencions = $Reg["Extencions"];
									$Size_File = $Reg["Size_File"];
									
									$Id_Field = "Id_".$Id_Object_Ext.$Reg["WDName"];
									
									if(!empty($value)){
										
										$Img = "<div class='Upload_Panel_Edit' >
										        <img class='Upload_Img_Edit' src='".$Path_Image."/".$value."' width='40px'>
													<div id='Upload_Btm_Edita' class='Upload_Btn_Edit' panel='".$Id_Field."_div' onclick='Upload_Edita(this);' panel_this='".$Id_Field."_div_in_server' >
													Cambiar Archivo
													</div>
												</div> ";
												
										$Style = "display:block;";
										$StyleB = "display:none;";
									}else{
										$StyleB = "display:block;";										
									}
						
									$label = "
									    <p style='margin: 0 0 0px;'>".$Reg["Alias"] ."  | ".$value."</p>
									";
									
									$v = "<li style='list-style: none;margin: 10px 0px 10px 0px;background:#eef6f9; padding: 10px 16px;float: left;width: 100%;' id='".$Id_Field."_li' >";
										
										$v .= "<label>" . $label. "</label>";	

										$v .= "<div id='".$Id_Field."_div_in_server' class='Div_In_Server' style='".$Style."' >".$Img;
										$v .= "</div>";
										
										$v .= "<div id='".$Id_Field."_div' class='Div_Option_Upload' style='".$StyleB."'>";
										

										$v .= "<p class='label_p'> Peso Máximo: ".$Size_File." | Extenciones Permitidas: ".$Extencions." </p>";
										$v .= "<div id='msg_rs'></div>";
										$v .= "<div class='background_lp' id='".$Id_Field."background_lp' >
											   <div id='".$Id_Field."linea_pregress' class='linea_pregress'></div>
											   </div>";
											   
										
										$Dominio = DCUrl();
										// $Ruta_Imag = $PathImage[$Contador_Upload][1];

										$v .= '
												<input type="file" 
												name="'.$Reg["WDName"].'"  
												direction="'.$Ruta_Imag.'" 								
												id="'.$Id_Field.'" 
												Object="'.$Id_Object_Ext.'" 
												Id_Object_Detail="'.$Reg["Id_Object_Detail"].'" 
												value="image"
												onchange="upload_fr(this)"
												/>
																					
												';

										$v .= "</div>";
										
									$v .= "</li>";

									$Html .= '
									<div class="form-group">
									'.$v.'
									</div>
									';
							
							// $Contador_Upload += 1;
                            }
							
							break;	
							
						case 6: ///Textarea normal
						
					
							$Html .= '
								  <div class="form-group">
									<label for="form-control-1">'.$Reg["Alias"].'</label>
									<input type="password" id="'.$Id_Object_Ext.$Reg["WDName"].'" class="form-control" 
									placeholder="'.$Reg["Alias"].'" name="'.$Reg["WDName"].'" value="'.$Row->$WDName.'">
								  
								  </div>
							';	
							
							
							break;														
							
						case 7: ///Textarea normal
						
							
							$Html .= '
								  <div class="form-group">
									<label for="form-control-1">'.$Reg["Alias"].'</label>
									<textarea  type="text" id="'.$Id_Object_Ext.$Reg["WDName"].'" class="form-control" 
									placeholder="'.$Reg["Alias"].'" name="'.$Reg["WDName"].'" >'.$Row->$WDName.'
									</textarea>
								  </div>
							';
							
							break;	

						case 8: ///Text Area Enriquesido
						
						    $Editor_Arr[$Contador_TextArea] = $Id_Object_Ext.$Reg["WDName"];
							
							$Html .= '
								  <div class="form-group">
									<label for="form-control-1">'.$Reg["Alias"].'</label>
									<textarea type_ta="edit" type="text" id="'.$Id_Object_Ext.$Reg["WDName"].'" class="form-control" 
									placeholder="'.$Reg["Alias"].'" name="'.$Reg["WDName"].'" >'.$Row->$WDName.'
									</textarea>
								  </div>
							';
							
                            $Contador_TextArea += 1;
							break;								

// $Combobox							
							
					}	
				}					
		}
		$Html .= ' </div>';	
		
		$Html .= ' <div class="text-center" id="Mensaje_Id">';	
		$Html .= ' </div>';			
		
		$Html .= ' <div class="modal-footer text-center">';	
        $Contador = 0;		
		foreach ($Buttons as $Buttons_Field) {
			$Contador += 1;
			
			if(empty($Buttons_Field[5])){ $ClassButton = "btn btn-primary"; }else{ $ClassButton = $Buttons_Field[5]; }
			if(!empty($Buttons_Field[0])){
				$Html .= '
					<button 
					type="button" 
					onclick="SaveForm(this);" 
					direction="'.$Buttons_Field[1].'" 
					screen="'.$Buttons_Field[2].'" 
					class="'.$ClassButton.'" 
					id="'.$Id_Object_Ext.$Contador.'_Button_Form" 
					form="'.$Buttons_Field[4].'" 
					
					>'.$Buttons_Field[0].'</button>
				';
			}
			
		}
		$Html .= ' </div>';	
		
	$Html .= ' </form>';		
	
	
	$Contador_Ta = 0;
	$Contador_Ta_Js = " console.log('Bye'); ";

	foreach ($Editor_Arr as $Editor_Arr_Ini) {
			// DCVd($Editor_Arr_Ini);
		$Contador_Ta_Js .= " CKEDITOR.replace( '".$Editor_Arr_Ini."' ); ";
		$Contador_Ta += 1;		
		
		
	}	
	
	$Contador_Text_DataTime = 0;
	$Contador_Text_DataTime_Js =  " console.log('Bye'); ";

	foreach ($Text_DataTime as $Text_DataTime_Arr_Ini) {
		
		if($Text_DataTime[$Contador_Text_DataTime]["Format_Type_Client"] == 5){
			$Text_Date_Time = "
			      format: '".$Text_DataTime[$Contador_Text_DataTime]["Format_Field"]."',
				  time: false,
                  clearButton: true,
                  nowButton:true
			";
		}

		if($Text_DataTime[$Contador_Text_DataTime]["Format_Type_Client"] == 7){
			$Text_Date_Time = "
			      format: '".$Text_DataTime[$Contador_Text_DataTime]["Format_Field"]."',
				  date: false,
				  nowButton:true
			";
		}
				
		$Contador_Text_DataTime_Js .= " 
		        $('#".$Text_DataTime[$Contador_Text_DataTime]["Id"]."').bootstrapMaterialDatePicker({
					".$Text_Date_Time."
				});
							
			";
		$Contador_Text_DataTime += 1;		

	}	

	
	$Script .=  '
	<script>
	$.getScript("/splugin/cdatetime/js/moment-with-locales.min.js");
	$.getScript("/splugin/cdatetime/js/bootstrap-material-datetimepicker.js");

	$.getScript("/splugin/ckeditor/ckeditor.js");
	var initSample = ( function() {
		
        '.$Contador_Ta_Js.'
								
	} )();	
	
	$(document).ready(function()
	{
        '.$Contador_Text_DataTime_Js.'		
	});			
	
	</script>
	';	
	
	return $Html.$Script;
}



function DCSave($Id_Object_Ext,$Conection,$IdRow,$Field_Key,$Data_External){
	
	// $Field_Key = DCConvierte_Mayuscula($Field_Key);
	
	$Data = file_get_contents("sbd_json/".$Id_Object_Ext.".json");
	$Result = json_decode($Data, true);
	
	
	$Query = "SELECT OB.Id_Object, OB.Name, WR.Name AS Warehouse_Name
			  FROM object OB 
			  INNER JOIN warehouse WR ON OB.Id_Warehouse = WR.Id_Warehouse
			  WHERE OB.Name = :Name
			  ";	
	$Where = ["Name" => $Id_Object_Ext];
	$Row = ClassPdo::DCRow($Query,$Where,$Conection);
	$Warehouse_Name = $Row->Warehouse_Name;
	
		$Insert_Data = Array();
		$Update_Data = Array();
		$Parametros = Array();
		$Contador = 0;
		$Value_Field_Upload = "";
			// DCVd($Result);
		foreach ($Result as $Reg) {
		    $Contador += 1;			
		
			$Parametros["Id_Data_Type".$Contador] = $Reg["Id_Data_Type"];	
			// $Insert_Data[$Reg["WDName"].$Contador] = $Reg["Id_Data_Type"];	
			
      		if($Reg["Pk"] == "NO"){
                   

					if($Reg["Type_Process_BD"] == "UPDATE"){
					
					    // DCVd($Reg["WDName"]);
					    // DCVd(DCPost($Reg["WDName						
						if($Reg["Id_Field_Type"] == 5){ // Field type Upload 

							$Query = "
							
								SELECT HFL.Value_Field, HFL.Id_History_Field_Load
								FROM history_field_load HFL 
								INNER JOIN object OB ON HFL.Id_Object = OB.Id_Object
								INNER JOIN object_detail OD ON HFL.Id_Object_Detail = OD.Id_Object_Detail 
								INNER JOIN warehouse_detail WD  ON OD.Id_Warehouse_Detail = WD.Id_Warehouse_Detail
								WHERE 
								OB.Name = :Object_Name
								AND WD.Name = :Object_Detail_Name
								AND HFL.Id_User_Creation = :Id_User_Creation
								AND HFL.Entity = :Entity
									  
									  ";	
							$Where = ["Object_Name" => $Id_Object_Ext, "Object_Detail_Name" => $Reg["WDName"], "Id_User_Creation" => $_SESSION['User'], "Entity" => $_SESSION['Entity'] ];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);
							$Value_Field_Upload = $Row->Value_Field;
							$Id_History_Field_Load = $Row->Id_History_Field_Load;
                           
						   // DCVd($Where);
						   // DCVd($Value_Field_Upload);
                            if(!empty($Value_Field_Upload)){
								
						        $Update_Data[$Reg["WDName"]] = $Value_Field_Upload;
								
								$reg = array(
									'Value_Field' => "",
									'Id_User_Update' => $_SESSION['User'],
									'Date_Time_Update' => DCTimeHour()
								);
								$where = array('Id_History_Field_Load' =>$Id_History_Field_Load);
								$rg = ClassPDO::DCUpdate("history_field_load", $reg , $where, $Conection);								
											
														
								$Query = "
								SELECT TBL.".$Reg["WDName"]." 
								FROM ".$Warehouse_Name." TBL 
								WHERE 
								TBL.".$Field_Key." = :".$Field_Key."
								";	
								$Where = [$Field_Key => $IdRow ];
								//var_dump($Query);
								//var_dump($Where);
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);
								$Name_Field = (String)$Reg["WDName"];
								$Value_Field = $Row->$Name_Field;
                                
								$PathROOT = $_SERVER['DOCUMENT_ROOT'];
								// DCVd($PathROOT."/".$Reg["Path_Image"]."/".$Value_Field);	
								Elimina_Archivo($PathROOT.$Reg["Path_Image"]."/".$Value_Field);

							}else{
							
						        // $Update_Data[$Reg["WDName"]] = $_FILES[$Reg["WDName"]]["name"];	
								
							}							
						    
							
						}else{
							
						    $Update_Data[$Reg["WDName"]] = DCPost($Reg["WDName"]);							
						}
						
	
						if($Reg["WDName"] == "Date_Time_Update" ){
							$Update_Data[$Reg["WDName"]] = DCTimeHour();			
						}
						
		
						if($Reg["WDName"] == "Id_User_Update"){
							$Update_Data[$Reg["WDName"]] = $_SESSION['User'];			
						}								
										
						if( !empty($Data_External[$Reg["WDName"]])){
							$Update_Data[$Reg["WDName"]] = $Data_External[$Reg["WDName"]];		
						}	
						
					}
				
			


                    if(!empty($Value_Field_Upload)){ 
					    $Insert_Data[$Reg["WDName"]] = $Value_Field_Upload;						
					    $Value_Field_Upload = "";
					}else{
					    $Insert_Data[$Reg["WDName"]] = DCPost($Reg["WDName"]);							
					}

					
			

				
					if($Reg["WDName"] == "Date_Time_Update" ){
						$Insert_Data[$Reg["WDName"]] = DCTimeHour();			
					}
					
					if($Reg["WDName"] == "Date_Time_Creation"){
						$Insert_Data[$Reg["WDName"]] = DCTimeHour();			
					}
					
					if($Reg["WDName"] == "Id_User_Update"){
						$Insert_Data[$Reg["WDName"]] = $_SESSION['User'];			
					}				

					if($Reg["WDName"] == "Id_User_Creation"){
						$Insert_Data[$Reg["WDName"]] = $_SESSION['User'];			
					}					

					if($Reg["WDName"] == "Entity"){
						$Insert_Data[$Reg["WDName"]] = $_SESSION['Entity'];			
					}	

				
					if( !empty($Data_External[$Reg["WDName"]])){
						$Insert_Data[$Reg["WDName"]] = $Data_External[$Reg["WDName"]];		
					}	

			}else{
				$Name_Field_Key = $Reg["WDName"];
			}

		}	
		
		if(empty($IdRow)){
			
			$ResultB = ClassPDO::DCInsert($Warehouse_Name, $Insert_Data, $Conection,$Parametros);	
			
		    DCWrite(Message("Process Insert executed correctly","C"));
			
		}else{
			
			// DCVd($Update_Data);
			$where = array($Name_Field_Key =>$IdRow);
			$ResultB = ClassPDO::DCUpdate($Warehouse_Name, $Update_Data , $where, $Conection,$Parametros);
			
		    DCWrite(Message("Process Updated executed correctly","C"));
		}
		return $ResultB;
			
	
}

function ExcelExtract($NombreArchivo) {
    $obj = PHPExcel_IOFactory::load($NombreArchivo);
    $Json='';
	
	
	// DCVd($obj);
    foreach ($obj->getAllSheets() as $worksheet) {
        $worksheetTitle     = $worksheet->getTitle();
        $highestRow         = $worksheet->getHighestRow();
        $highestColumn      = $worksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
	
        $Json .= '[';
        for ($row = 2; $row <= $highestRow; ++ $row) {
            $Json .= '{';
            for ($col = 0; $col <= $highestColumnIndex; ++ $col) {

                $fil = $worksheet->getCellByColumnAndRow($col, 1);
                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
				$val = DCCleanText($val);
					 
				if($fil == "Email"){
					
					
					$val = trim($val);
							
				}

                $Json .= '"'.$fil.'":"'. $val.'",';
				
            }
            $Json = substr($Json,0,-1);
            $Json .= '},';
        }
        $Json = substr($Json,0,-1);
        $Json .= ']';
		
    }
	  
    return $Json;
}
function  DCCleanText($Text) {
    $temp = strtolower($Text);
    $b1 = array();
    $nueva_cadena = '';
 
    $ent = array('&aacute;', '&eacute;', '&iacute;', '&oacute;', '&oacute;', '&ntilde;');
    $entRep = array('á', 'é', 'í', 'ó', 'ú', 'ñ');

    $b = array('á', 'é', 'í', 'ó', 'ú', 'ä', 'ë', 'ï', 'ö', 'ü', 'à', 'è', 'ì', 'ò', 'ù', 'ñ',
        ',', ';', ':', '¡', '!', '¿', '?', '"',
        '�?', 'É', '�?', 'Ó', 'Ú', 'Ä', 'Ë', '�?', 'Ö', 'Ü', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ñ');
    $c = array('a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n',
        '', '', '', '', '', '', '', '',
        'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n');

    $temp = str_replace($ent, $entRep, $temp);
    $temp = str_replace($b, $c, $temp);
    $temp = str_replace($b1, $c, $temp);

    $new_cadena = explode(' ', $temp);

    foreach ($new_cadena as $cad) {
        $word = preg_replace("[^A-Za-z0-9]", "", $cad);
        if (strlen($word) > 0) {
            $nueva_cadena.=$word . '-';
        }
    }

    $nueva_cadena = substr($nueva_cadena, 0, strlen($nueva_cadena) - 1);
	
    return $nueva_cadena;
}
function VerificarEmail($direccion)
{
    if (filter_var($direccion, FILTER_VALIDATE_EMAIL)){
		return "V";
	}else{
        return "F";		
	}
}

function VerificarTexto($Cadena)
{
   // $Cadena = preg_match("/[^A-Za-zñÑ]/",$cadena);
   // if(ereg('[^A-Za-zñÑ]',$cadena))  
   if($Cadena)  
   {  
		return "V";	
   }else{
		return "F";	   
   }
}

function VerificarNumero($cadena)
{
   if(is_numeric($cadena))  
   {  
        return "V";	
   }else{
		return "F";	   
   }
}


function Elimina_Archivo($ruta) {
	
    if (file_exists($ruta)) {
        unlink($ruta);
        return true;
    } else {
        return false;
    }
	
}

function DCAcordeon($paneles){
    foreach ($paneles as $panel) {
		$Html .= '
				<div class="panel-group" id="accordionOne" role="tablist" aria-multiselectable="true">
				  <div class="panel panel-default">
					<div class="panel-heading" role="tab">
					  <h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionOne" href="#'.$panel[0].'" aria-expanded="false">
						  <i class="zmdi zmdi-chevron-down"></i> '.$panel[1].'
						</a>
					  </h4>
					</div>
					<div id="'.$panel[0].'" class="panel-collapse collapse" role="tabpanel">
					  <div class="panel-body">
						'.$panel[2].'
					  </div>
					</div>
				  </div>
				</div>	
		';
    }
	
    return $Html;
	
}

function DCLayout($paneles) {

    foreach ($paneles as $panel) {
		
        $s .= "<div id='" . $panel[0] . "' class='" . $panel[1] . "' >";
        $s .= $panel[2];
        $s .= "</div>";
		
    }
    return $s;
}

function GeraHash($qtd){ 
	//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code. 
	$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789'; 
	$QuantidadeCaracteres = strlen($Caracteres); 
	$QuantidadeCaracteres--; 

	$Hash=NULL; 
		for($x=1;$x<=$qtd;$x++){ 
			$Posicao = rand(0,$QuantidadeCaracteres); 
			$Hash .= substr($Caracteres,$Posicao,1); 
		} 

	return $Hash; 
} 

function Plugin_Facebook(){
	
	$Script =  '
	<script>
	$.getScript("/sadministrator/sbookstores/facebook/demo/js/facebookSDK.js");
	</script>
	';			
	return $Script;	
}


function Redes_Sociales($Url){
	
	$Brn_Compartir = '
			
			 
			 <a class="btn btn-facebook" target="_blank" 
			 href="https://www.facebook.com/sharer/sharer.php?u='.$Url.'">
			 <i class="zmdi zmdi-facebook-box zmdi-hc-2x"></i>
			 </a>
			 
			 <a class="btn btn-googleplus" target="_blank" 
			  href="https://plus.google.com/share?url='.$Url.'">
			 <i class="zmdi zmdi-google-plus zmdi-hc-2x"></i>
			 </a>	
			 
			 <a class="btn btn-twitter" target="_blank" 
			 href="https://twitter.com/?status='.$Url.'">
			 <i class="zmdi zmdi-twitter zmdi-hc-2x"></i>
			 </a>
			 
			 <a class="btn btn-linkedin" target="_blank" 
			 href="http://www.linkedin.com/shareArticle?url='.$Url.'">
			 <i class="zmdi zmdi-linkedin zmdi-hc-2x"></i>
			 </a>	
		
			';		
	return $Brn_Compartir;	
}

// function DCUrl_Amigable($string){
	// $slug = preg_replace('/[^A-Za-z0-9-]+/','-',$string);
	// $slug = strtolower($slug);
	// return $slug;
// }

function DCResizeImage($imagePath, $new_width, $new_height)
{
    $fileName = pathinfo($imagePath, PATHINFO_FILENAME);
    $fullPath = pathinfo($imagePath, PATHINFO_DIRNAME) . "/imagen-icono/" . $fileName . "-small.png";
	
	$resizeObj = new resize($imagePath);
 
    // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
    $resizeObj -> resizeImage($new_width, $new_height, 'crop');
 
    // *** 3) Save image
    $resizeObj -> saveImage($fullPath, 100);
	
    // if (file_exists($fullPath)) {
        // return $fullPath;
    // }
    // $image = openImage($imagePath);
    // if ($image == false) {
        // return null;
    // }
    // $width = imagesx($image);
    // $height = imagesy($image);
    // $imageResized = imagecreatetruecolor($width, $height);
	
    // if ($imageResized == false) {
        // return null;
    // }
    // $image = imagecreatetruecolor($width, $height);
    // $imageResized = imagescale($image, $new_width, $new_heigh);
    // touch($fullPath);
	// echo $fullPath;
    // $write = imagepng($imageResized, $fullPath);
	// DCVd($write);
    // if (!$write) {
        // imagedestroy($imageResized);
        // return null;
    // }
    // imagedestroy($imageResized);
    return $fileName."-small.png";
}


function  DCUrl_Name_File($Text) {
    $temp = strtolower($Text);
    $b1 = array();
    $nueva_cadena = '';

    $ent = array('&aacute;', '&eacute;', '&iacute;', '&oacute;', '&oacute;', '&ntilde;');
    $entRep = array('á', 'é', 'í', 'ó', 'ú', 'ñ');

    $b = array('á', 'é', 'í', 'ó', 'ú', 'ä', 'ë', 'ï', 'ö', 'ü', 'à', 'è', 'ì', 'ò', 'ù', 'ñ',
        ',', ';', ':', '¡', '!', '¿', '?', '"', '_',
        ' ?', 'É', ' ?', 'Ó', 'Ú', 'Ä', 'Ë', ' ?', 'Ö', 'Ü', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ñ');
		
    $c = array('a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n',
        '', '', '', '', '', '', '', '', '-',
        'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n');

    $temp = str_replace($ent, $entRep, $temp);
    $temp = str_replace($b, $c, $temp);
    $temp = str_replace($b1, $c, $temp);

    $new_cadena = explode(' ', $temp);

    foreach ($new_cadena as $cad) {
        $word = preg_replace("[^A-Za-z0-9]", "", $cad);
        if (strlen($word) > 0) {
            $nueva_cadena.=$word . '-';
        }
    }

    $nueva_cadena = substr($nueva_cadena, 0, strlen($nueva_cadena) - 1);

    return $nueva_cadena;
}



function  DCUrl_Amigable($Text) {
    $temp = strtolower($Text);
    $b1 = array();
    $nueva_cadena = '';

    $ent = array('&aacute;', '&eacute;', '&iacute;', '&oacute;', '&oacute;', '&ntilde;');
    $entRep = array('á', 'é', 'í', 'ó', 'ú', 'ñ');

    $b = array('á', 'é', 'í', 'ó', 'ú', 'ä', 'ë', 'ï', 'ö', 'ü', 'à', 'è', 'ì', 'ò', 'ù', 'ñ',
        ',', '.', ';', ':', '¡', '!', '¿', '?', '"', '_',
        ' ?', 'É', ' ?', 'Ó', 'Ú', 'Ä', 'Ë', ' ?', 'Ö', 'Ü', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ñ');
    $c = array('a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n',
        '', '', '', '', '', '', '', '', '', '-',
        'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n');

    $temp = str_replace($ent, $entRep, $temp);
    $temp = str_replace($b, $c, $temp);
    $temp = str_replace($b1, $c, $temp);

    $new_cadena = explode(' ', $temp);

    foreach ($new_cadena as $cad) {
        $word = preg_replace("[^A-Za-z0-9]", "", $cad);
        if (strlen($word) > 0) {
            $nueva_cadena.=$word . '-';
        }
    }

    $nueva_cadena = substr($nueva_cadena, 0, strlen($nueva_cadena) - 1);

    return $nueva_cadena;
}

function DCConvierte_Mayuscula($Parm){
	$new_cadena = explode('_', $Parm);
	$New_Cadena_Conver = "";
	$Cont = 0;
	foreach ($new_cadena as $cad) {
		$Cont += 1;
		$C_M = ucwords($cad);
		if($Cont == 1){
		   $Separador = "";
		}else{
			$Separador = "_";
		}
        $New_Cadena_Conver .= $Separador.$C_M;
		
    }		
	return $New_Cadena_Conver;
}



function DCMes_Texto($fecha) {
  $fecha = substr($fecha, 0, 10);
  $numeroDia = date('d', strtotime($fecha));
  $dia = date('l', strtotime($fecha));
  $mes = date('F', strtotime($fecha));
  $anio = date('Y', strtotime($fecha));
  $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
  $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
  
  return  $nombreMes;
}

function DCDia_Texto($fecha) {
  $fecha = substr($fecha, 0, 10);
  $numeroDia = date('d', strtotime($fecha));
  $dia = date('l', strtotime($fecha));
  $mes = date('F', strtotime($fecha));
  $anio = date('Y', strtotime($fecha));
  $dias_ES = array("Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom");
  $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
  $nombredia = str_replace($dias_EN, $dias_ES, $dia);
  
  return  $nombredia;
}

function DCMinHoras($min,$type){

	 $sec = $min * 60;
	 //dias es la division de n segs entre 86400 segundos que representa un dia
	 $dias=floor($sec/86400);
	 //mod_hora es el sobrante, en horas, de la division de días; 
	 $mod_hora=$sec%86400;
	 //hora es la division entre el sobrante de horas y 3600 segundos que representa una hora;
	 $horas=floor($mod_hora/3600); 
	 //mod_minuto es el sobrante, en minutos, de la division de horas; 
	 $mod_minuto=$mod_hora%3600;
	 //minuto es la division entre el sobrante y 60 segundos que representa un minuto;
	 $minutos=floor($mod_minuto/60);
	 if($horas<=0){
		 
	    $text = $minutos.' min';
		
	 }elseif($dias<=0){
		 
		 if($type=='round'){
			$text = $horas.' hrs';
		 }else{
			$text = $horas." hrs ".$minutos;
		 }
		 
	 }else{
		 //nos apoyamos de la variable type para especificar si se muestra solo los dias
		 if($type=='round'){
			$text = $dias.' dias';
		 }else{
			$text = $dias." dias ".$horas." hrs ".$minutos." min";
		 }
	 }
	 return $text; 
}

function DCEncriptar($texto) {
    $key='palabraclaveparalacodificacionydecodificacion';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
    $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $texto, MCRYPT_MODE_CBC, md5(md5($key))));
    return $encrypted;
}


function DCDesencriptar($texto) {
	// $texto = $texto."";
	// echo $texto;
	$texto = str_replace(" ", "+", $texto);
	// echo $texto." ...<br>"; 
	
	
    $key='palabraclaveparalacodificacionydecodificacion';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
    $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($texto), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    return $decrypted;
   
}


function DCModalFormB($Titulo,$Body,$Class){
	
	if(empty($Class)){
	    $Class = "modal-dialog";
	}
	
	$Html = '
   
	    <div class="'.$Class.'" >
			<div class="modal-content" >
			
			  <div class="modal-header bg-primary" style="background-color:#6b2be9!important;">
			  
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">
					<i class="zmdi zmdi-close"></i>
				  </span>
				</button>
				
				<h4 class="modal-title">'.$Titulo.'</h4>
			  </div>
			  
			  '.$Body.'
			</div>
		</div>
	
	';
	return $Html;
}


function Index_Paginador($paginaAnterior,$paginaActual,$paginas,$paginaSiguiente) {
	
	
	$Html ='	
	
	        <nav aria-label="Page navigation example">  
						<ul class="pagination"> ';

			if ($paginaActual <=1 ) {

			}else{
					$Html .='<li class="page-item"><a class="page-link" href="?pagina='. $paginaAnterior .'">Anterior</a></li>';
			}

			for ($i=1; $i <= $paginas; $i++) { 

				$active = ($paginaActual == $i) ? "active" : "";

				$Html .='<li class="page-item '. $active .'" ><a class="page-link" href="?pagina='. $i .'">'. $i .'</a></li>';
			
			}

			if ($paginaActual >= $paginas) {
				
			}else{
				  $disabled = ($paginaActual >= $paginas) ? "disabled" : "";

					$Html .='<li class="page-item '.$disabled .'"><a class="page-link" href="?pagina='. $paginaSiguiente .'">Siguiente</a></li>';	
	
			}
			
		$Html .='	</ul>
					</nav>	
				';	
				
	return $Html;
	
}


?>