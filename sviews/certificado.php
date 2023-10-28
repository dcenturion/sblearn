<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once './sviews/layout.php';
require_once('./sbookstores/php/conection.php');
require_once('./sbookstores/php/functions.php');
require_once('./sviews/ft_front.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class certificado{

   private $Parm;

    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour;
		$UrlFile = "/sviews/certificado";		
		
		$Transaction = $Parm["Transaction"];
		$Interface = $Parm["interface"];
		$Form = $Parm["Form"];


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

                break;
            case "DELETE":

                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":

                break;				
        }		

		
        switch ($Interface) {
        
            case "List":	
			
                    $empresa = $Parm["empresa"];
					
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
				
					// $BodyPage = $layout->render('./sviews/certificado.phtml',$datos);

					// echo $layout->main($BodyPage,$datos);
					$urlLinkB = "/empresa/".$empresa;
					
					$Pestanas = Ft_Front::Tabs_Principal(array(
					"".$urlLinkB."/interface/List]Marca"
					,"".$urlLinkB."/interface/Busqueda_Avanzanda]"));	
					
					
					$DCPanelTitle = DCPanelTitle("CERTIFICADOS","En este sitio podrás ubicar los certificados emitidos por nuestra entidad",$btn);
					
					$Form = $this->Form_Simple($Parm);
				
					
					$Panel_Cuerpo =  " <div style='padding:10px 20px;'> ".$Pestanas ."</div>";					
					$Panel_Cuerpo .=  " <div style='padding:10px 20px;' id='panel-form' > ".$Form ."</div>";					
					$Panel_Cuerpo .=  " <div style='padding:10px 20px;' id='panel-cuerpo'> </div>";					
					
					$Contenido = DCPage( $DCPanelTitle , $Panel_Cuerpo  ,"panel panel-default");
					
					
					$Contenido =  " <div id='ScreenRight' style='background-color:#fff;'> ".$Contenido ."</div>";
					
					$Style = "
					<style>
					.panel {
					padding: 3% !important;
					}
					</style>
					";
					
					if($Parm["request"] == "on"){
						DCWrite($layout->main($Contenido . $Style,$datos));
					}else{
						DCWrite($Contenido . $Style);			
					}	

                break;	


            case "ListDet":	
			
                    $empresa = $Parm["empresa"];
                    $key = $_GET["key"];
					
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
				
					// $BodyPage = $layout->render('./sviews/certificado.phtml',$datos);

					// echo $layout->main($BodyPage,$datos);
					$urlLinkB = "/empresa/".$empresa;
					
					// $Pestanas = Ft_Front::Tabs_Principal(array(
					// "".$urlLinkB."/interface/List]Marca"
					// ,"".$urlLinkB."/interface/Busqueda_Avanzanda]"));	
					
					
					$DCPanelTitle = DCPanelTitle("CERTIFICADOS","En este sitio podrás ubicar los certificados emitidos por nuestra entidad",$btn);
					
					$key =  DCDesencriptar($key);
					
					$Query = "
					SELECT EGC.Id_Suscripcion
					, EGC.Nota
					, EGC.Estado_Academico  
					, EGC.Id_Edu_Almacen  
					, EGC.Id_Edu_Pais  
					, EGC.Id_Edu_Certificado  
					, EGC.Tipo_Producto  
					, EGC.Entity  
					FROM 
					edu_certificado EGC
					WHERE EGC.Codigo_Sistema = :Codigo_Sistema 
					";
					$Where = ["Codigo_Sistema" => $key];
					$Rows = ClassPdo::DCRow($Query,$Where,$Conection);					
					$Id_Suscripcion_User = $Rows->Id_Suscripcion;		
					$Id_Edu_Almacen = $Rows->Id_Edu_Almacen;		
					$Id_Edu_Certificado = $Rows->Id_Edu_Certificado;		
					$Id_Edu_Almacen = $Rows->Id_Edu_Almacen;		
					$Tipo_Producto = $Rows->Tipo_Producto;
					$Entity = $Rows->Entity;
					
					if(empty($Tipo_Producto	)){
						$Tipo_Producto = "curso";
					}
					
					
					// echo $Tipo_Producto;
					
					if( ! empty( $Id_Edu_Certificado 	)){
						
						$_SESSION['Entity'] = $Entity;

						$Form = "<iframe width='100%' height='600px' src='/sadministrator/edu-certificado/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/interface/List/tipo-producto/".$Tipo_Producto."/tipo_visualizacion/demo/request/on/'></iframe>";
		
						
					}else{
						
						$Form =  "El linnk no es correcto";	
					}
							
					
				
					$Panel_Cuerpo =  " <div style='padding:10px 20px;' id='panel-form' > ".$Form ."</div>";					
					$Panel_Cuerpo .=  " <div style='padding:10px 20px;' id='panel-cuerpo'> </div>";					
					
					$Contenido = DCPage( $DCPanelTitle , $Panel_Cuerpo  ,"panel panel-default");
					
					
					$Contenido =  " <div id='ScreenRight' style='background-color:#fff;'> ".$Contenido ."</div>";
					
					$Style = "
					<style>
					.panel {
					padding: 3% !important;
					}
					</style>
					";
					
					if($Parm["request"] == "on"){
						DCWrite($layout->main($Contenido . $Style,$datos));
					}else{
						DCWrite($Contenido . $Style);			
					}	

                break;	
				
        
            case "buscar_general":	
			
					$GoogleToken = DCPost("google-response-token");

	
					$captcha = DCPost("g-recaptcha-response");

					$response=$captcha;
					$secret="6Ldjy0cdAAAAAPswImRxVTS5d_byQU0OwRTW86dj";
					$IP_LOCAL=$_SERVER["REMOTE_ADDR"];
					$URL_VERIF="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$response."&remoteip=".$IP_LOCAL;
					$respuesta=file_get_contents($URL_VERIF); 
					$finalrespuesta=json_decode($respuesta,true);



					if(trim($finalrespuesta ['success']) == true)
					{
						
				
						$Codigo = DCPost("Codigo");

						if(empty( $Codigo)){
							
							echo "Debes llenar el campo 'Código' ";
							exit();
							
						}	
							
					}else{
						
						echo "Debes confirmar que no eres un robot";
						exit();
					}
						
			
					$Codigo_Array = explode("_", $Codigo);
					echo count($Codigo_Array);
					
					if(count($Codigo_Array) == 5 ){
						
						$Query = "
						SELECT 
						Codigo,
						Nombres,
						Estado_Academico,
						Estado_Edicion_Datos_Certificado,
						Numero_Identidad,
						Codigo_V2,
						Pago_Total
						FROM view_edu_certificado
						WHERE Codigo_V2 = :Codigo_V2  
						LIMIT 0,2
						";
						$Where = ["Codigo_V2"=>$Codigo];
						
					}else{
						
						$Query = "
						SELECT 
						Codigo,
						Nombres,
						Estado_Academico,
						Estado_Edicion_Datos_Certificado,
						Numero_Identidad,
						Codigo_V2,
						Pago_Total
						FROM view_edu_certificado
						WHERE Codigo = :Codigo
						LIMIT 0,2
						";
						$Where = ["Codigo"=>$Codigo];						
						
					}
					

					
					
					$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
					$Html = '<table class="table">
							  <thead>
								<tr>
								  <th scope="col">Código</th>
								  <th scope="col">Nombres</th>
								  <th scope="col">Estado Académico</th>
								  <th scope="col">Número Identidad</th>
								</tr>
							  </thead>
							  <tbody>
							  ';
				    $Count = 0;
					foreach($Rows AS $Field){
						
						$Count += 1;	
						$CountA += 1;	
						$Codigo = $Field->Codigo;	
                        // $Html .= $Codigo ."<br>";
			            $Html .= ' <tr>';	

						if(count($Codigo_Array) == 5 ){	
						
							$Html .= ' <td scope="col">'.$Field->Codigo_V2.'</td>';
						
						}else{
							$Html .= ' <td scope="col">'.$Field->Codigo.'</td>';							
						}
							
			            $Html .= ' <td scope="col">'.$Field->Nombres.'</td>';						
			            $Html .= ' <td scope="col">'.$Field->Estado_Academico.'</td>';						
			            $Html .= ' <td scope="col">'.$Field->Numero_Identidad.'</td>';						
			            $Html .= ' </tr>';						
					}   
			        $Html .= '  
					            </tbody>		
							</table>';
					
					$empresa = $Parm["empresa"];
				    if ( $Count == 0 ){
					  echo "No hay resultados en la búsqueda, es posible que tu certificado aún no se haya publicado en nuestro sistema, 
					    <b> contáctate al correo de coordinación para realizar el trámite de publicación de tu certificado en el sistema actual,
												  <i>coordinacion@esgep.com</i> </b>";
					}else{
						 echo "Resultados de la búsqueda";
					}
				  
				  
				    echo $Html;
				  
					$Settings = array();
					$Settings['Url'] = "/certificado/empresa/".$empresa."/interface/form_busqueda/";
					$Settings['Screen'] = "panel-form";
					$Settings['Type_Send'] = "HXM";
					DCRedirectJS($Settings);				  
				  
				  
				  exit();

                break;							
				
				
            case "form_busqueda":		

					// $layout  = new Layout();
					
					$Form = $this->Form_Simple($Parm);	
					
					echo $Form;

                break;	

				
            case "Busqueda_Avanzanda":		

					$layout  = new Layout();
					
					$empresa = $Parm["empresa"];
					
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
				
					// $BodyPage = $layout->render('./sviews/certificado.phtml',$datos);

					// echo $layout->main($BodyPage,$datos);
					$urlLinkB = "/empresa/".$empresa;
					
					$Pestanas = Ft_Front::Tabs_Principal(array(
					"".$urlLinkB."/interface/List]"
					,"".$urlLinkB."/interface/Busqueda_Avanzanda]Marca"));
									
					$Form = $this->Form_Busqueda_Avanzanda($Parm);			
					$Panel_Cuerpo =  " <div style='padding:10px 20px;'> ".$Pestanas ."</div>";					
					$Panel_Cuerpo .=  " <div style='padding:10px 20px;' id='panel-form' > ".$Form ."</div>";					
					$Panel_Cuerpo .=  " <div style='padding:10px 20px;' id='panel-cuerpo'> </div>";											
					
					$DCPanelTitle = DCPanelTitle("CERTIFICADOS","En este site podrás ubicar los certificados emitidos por nuestra entidad",$btn);
					
					$Contenido = DCPage( $DCPanelTitle , $Panel_Cuerpo ,"panel panel-default");
					$Contenido =  " <div id='ScreenRight'> ".$Contenido ."</div>";
					
					
					
					
					$Style = "
					<style>
					.panel {
					padding: 3% !important;
					}
					</style>
					";
					
					if($Parm["request"] == "on"){
						DCWrite($layout->main($Contenido . $Style,$datos));
					}else{
						DCWrite($Contenido . $Style);			
					}	
					
		
                break;			


           case "buscar_avanzada_process":	
			
					$GoogleToken = DCPost("google-response-token");

	
					$captcha = DCPost("g-recaptcha-response");

					$response=$captcha;
					$secret="6Ldjy0cdAAAAAPswImRxVTS5d_byQU0OwRTW86dj";
					$IP_LOCAL=$_SERVER["REMOTE_ADDR"];
					$URL_VERIF="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$response."&remoteip=".$IP_LOCAL;
					$respuesta=file_get_contents($URL_VERIF); 
					$finalrespuesta=json_decode($respuesta,true);



					if(trim($finalrespuesta ['success']) == true)
					{
						
				
						$Id_Edu_Tipo_Documento_Identidad = DCPost("Id_Edu_Tipo_Documento_Identidad");

						if(empty( $Id_Edu_Tipo_Documento_Identidad)){
							
							echo "Debes llenar el campo 'Tipo de Documento' ";
							exit();
							
						}	
						
						$Numero_Identidad = DCPost("Numero_Identidad");						
						if(empty( $Numero_Identidad)){
							
							echo "Debes llenar el campo 'Numero de Identidad' ";
							exit();
							
						}							
						
						$Id_Edu_Pais = DCPost("Id_Edu_Pais");

						if(empty( $Id_Edu_Pais)){
							
							echo "Debes llenar el campo 'Pais' ";
							exit();
							
						}							
						
					}else{
						
						echo "Debes confirmar que no eres un robot";
						exit();
					}
						
			
                    $Id_Edu_Tipo_Documento_Identidad = DCPost("Id_Edu_Tipo_Documento_Identidad");
                    $Numero_Identidad = DCPost("Numero_Identidad");
                    $Id_Edu_Pais = DCPost("Id_Edu_Pais");
					
					$Query = "
					SELECT 
					Codigo,
					Codigo_V2,
					Nombres,
					Estado_Academico,
					Date_Time_Creation,
					Estado_Edicion_Datos_Certificado,
					Numero_Identidad,
					Pago_Total
					FROM view_edu_certificado
					WHERE 
					Id_Edu_Tipo_Documento_Identidad = :Id_Edu_Tipo_Documento_Identidad AND
					Numero_Identidad = :Numero_Identidad AND
					Id_Edu_Pais = :Id_Edu_Pais
					LIMIT 0,2
					";
					$Where = ["Id_Edu_Tipo_Documento_Identidad"=>$Id_Edu_Tipo_Documento_Identidad, "Numero_Identidad"=>$Numero_Identidad , "Id_Edu_Pais"=>$Id_Edu_Pais];
					
					// var_dump($_POST);
					$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
					$Html = '<table class="table">
							  <thead>
								<tr>
								  <th scope="col">Código</th>
								  <th scope="col">Nombres</th>
								  <th scope="col">Estado Académico</th>
								  <th scope="col">Número Identidad</th>
								</tr>
							  </thead>
							  <tbody>
							  ';
				    $Count = 0;
					foreach($Rows AS $Field){
						
						$Count += 1;	
						$CountA += 1;	
						$Codigo = $Field->Codigo;	
                        // $Html .= $Codigo ."<br>";
			            $Html .= ' <tr>';				
							
							if( $Field->Date_Time_Creation <= "2023-10-12"){
								
								$Html .= ' <td scope="col">'.$Field->Codigo.'</td>';
								
							}else{
								
								$Html .= ' <td scope="col">'.$Field->Codigo_V2.'</td>';
							}
							
						

							
			            $Html .= ' <td scope="col">'.$Field->Nombres.'</td>';						
			            $Html .= ' <td scope="col">'.$Field->Estado_Academico.'</td>';						
			            $Html .= ' <td scope="col">'.$Field->Numero_Identidad.'</td>';						
			            $Html .= ' </tr>';						
					}   
			        $Html .= '  
					            </tbody>		
							</table>';
					
					$empresa = $Parm["empresa"];
				    if ( $Count == 0 ){
					  echo "No hay resultados en la búsqueda, es posible que tu certificado aún no se haya publicado en nuestro sistema, 
					    <b> contáctate al correo de coordinación para realizar el trámite de publicación de tu certificado en el sistema actual,
												  <i>coordinacion@esgep.com</i> </b>";
					}else{
						 echo "Resultados de la búsqueda";
					}
				  
				  
				  echo $Html;
				  
					$Settings = array();
					$Settings['Url'] = "/certificado/empresa/".$empresa."/interface/form_busqueda_avanzada/";
					$Settings['Screen'] = "panel-form";
					$Settings['Type_Send'] = "HXM";
					DCRedirectJS($Settings);				  
				  
				  
				  exit();

                break;					
        				
            case "form_busqueda_avanzada":		

					// $layout  = new Layout();
					
					$Form = $this->Form_Busqueda_Avanzanda($Parm);	
					
					echo $Form;

                break;	

				
        }		
		

				
        // break;		
		
	}
	
	
	
	function Form_Simple($Parm){
	        
			$empresa = $Parm["empresa"];
					
					
			$Direcction = "/certificado/empresa/".$empresa."/interface/buscar_general";
			$Screen = "panel-cuerpo";
			$IdForm = "Form-Busqueda";
			
			$Form = '
			
				<div style="padding-top:20px;padding-bottom:20px;">
				
				<form class="form-inline" id="'.$IdForm.'"  name="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >

				  <div class="form-group mb-2">
					<label for="exampleInputEmail1" style="    width: 100%;justify-content: left;"> Código de certificado</label>
					<input type="text"  class="form-control" id="Codigo" name="Codigo" value="">
				  </div>
					<div class="g-recaptcha" id="g-recaptcha"></div>						  
					
											
					<button type="submit" onclick=SaveForm(this); 
					direction="'.$Direcction.'" 
					screen="'.$Screen.'"  
					class="btn btn-primary mb-2" 
					type_send="" 
					id="form_Rp_0"  form="'.$IdForm.'" style="margin: 0px 0px 0px 20px;" > Buscar </button>								
											
							
				</form>		

				
				</div>	

				<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
				<script type="text/javascript">
					var onloadCallback = function() {
						grecaptcha.render("g-recaptcha", {
						"sitekey" : "6Ldjy0cdAAAAAL3VZjTUgeeUfPMZyHVbZrW9JR0V",
						"callback" : function(token){ console.log("token: ", token)},
						});
					};
				</script>						
			';	
			return $Form;
    }
	
		
	
	function Form_Busqueda_Avanzanda($Parm){
	        
			$empresa = $Parm["empresa"];
					
					
			$Direcction = "/certificado/empresa/".$empresa."/interface/buscar_avanzada_process";
			$Screen = "panel-cuerpo";
			$IdForm = "Form-Busqueda-avanzada";
			

			$Query = "
				SELECT 
					Id_Edu_Tipo_Documento_Identidad,
					Nombre
				FROM edu_tipo_documento_identidad
			";
			$Where = [];
			$Rows = ClassPdo::DCRows($Query,$Where,$Conection);

			$Count = 0;
			$Select = "";
			foreach($Rows AS $Field){
				$Select .= "<option value='".$Field->Id_Edu_Tipo_Documento_Identidad."'>".$Field->Nombre."</option>";
			}

			
			
			$Query = "
				SELECT 
					Id_Edu_Pais,
					Nombre
				FROM edu_pais
			";
			$Where = [];
			$Rows = ClassPdo::DCRows($Query,$Where,$Conection);

			$Count = 0;
			$Select2 = "";
			foreach($Rows AS $Field){
				$Select2 .= "<option value='".$Field->Id_Edu_Pais."'>".$Field->Nombre."</option>";
			}	

			
			$Form = '
			
				<div style="padding-top:20px;padding-bottom:20px;">
				  <form class="form-inline" id="'.$IdForm.'"  name="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >

				  <div class="form-group mb-2">
					<label for="exampleInputEmail1" style="    width: 100%;justify-content: left;">Tipo de documento</label>
						
						<select name="Id_Edu_Tipo_Documento_Identidad" id="Id_Edu_Tipo_Documento_Identidad" class="form-control">
						 '.$Select.'
						</select>
						
				  </div>
				  <div class="form-group mb-2">
					<label for="exampleInputEmail1" style="    width: 100%;justify-content: left;">Número de documento </label>
					<input type="text"  class="form-control" id="Numero_Identidad" name="Numero_Identidad" value="">
				  </div>
				  
				  <div class="form-group mb-2">
					<label for="exampleInputEmail1" style="    width: 100%;justify-content: left;">Nacionalidad </label>
						<select name="Id_Edu_Pais" id="Id_Edu_Pais"  class="form-control">
						   '.$Select2.'
						</select>
				  </div>
				  <div class="g-recaptcha" id="g-recaptcha"></div>
				  
					<button type="submit" onclick=SaveForm(this); 
					direction="'.$Direcction.'" 
					screen="'.$Screen.'"  
					class="btn btn-primary mb-2" 
					type_send="" 
					id="form_Rp_0"  form="'.$IdForm.'" style="margin: 0px 20px 0px 20px;" > Buscar </button>		
					
				</form>					
				</div>		

				<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
				<script type="text/javascript">
					var onloadCallback = function() {
						grecaptcha.render("g-recaptcha", {
						"sitekey" : "6Ldjy0cdAAAAAL3VZjTUgeeUfPMZyHVbZrW9JR0V",
						"callback" : function(token){ console.log("token: ", token)},
						});
					};
				</script>	
				
			';			

			return $Form;			
			
    }
	
	

}