<?php
require_once(dirname(__FILE__).'/layout_B.php');
require_once(dirname(__FILE__).'/ft_biblioteca.php');
require_once(dirname(__FILE__).'/ft_settings_site.php');
require_once(dirname(__FILE__).'/user.php');

$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Monitor_Chat{

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
							DCExit();	
						
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
					
					
					        if($Obj == "Edu_Componente_Doc_Crud" ){
								
								$Id_Edu_Formato = 2;
								
					        }elseif($Obj == "Edu_Componente_DocA_Crud" ){
								
								$Id_Edu_Formato = 2;
																
					        }elseif($Obj == "Edu_Componente_Articulo_Crud" ){
								
								$Id_Edu_Formato = 6;
								
					        }elseif($Obj == "Edu_Componente_Embebido_Crud" ){
								
								$Id_Edu_Formato = 3;
														
							}else{
								$Id_Edu_Formato = 5;
							}
					
                            $Data = array();
							$Data['Id_Edu_Almacen'] = $Parm["key"]; 
							$Data['Id_Edu_Formato'] = $Id_Edu_Formato; // Servicios	
							// echo $Parm["Id_Edu_Componente_Jerar"];
							// $Data['Jerarquia_Id_Edu_Componente'] = 0;
						
                            if(!empty($Parm["Id_Edu_Componente_Jerar"])){
							    $Data['Jerarquia_Id_Edu_Componente'] = $Parm["Id_Edu_Componente_Jerar"]; // Servicios									
							}							
						    
					
							// echo $Data['Jerarquia_Id_Edu_Componente'];
							// echo "<br>";
							// echo "<br>";
							// echo "<br>";
							// echo "daniel";
							// echo $Parm["Id_Edu_Componente_Jerar"];
							// exit();
							
							
							
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
							
							if($Obj == "Edu_Componente_Carpeta_Crud" ){
								
							    $Settings["interface"] = "y";
								
							}elseif($Obj == "Edu_Componente_DocA_Crud" ){
								
							    $Settings["interface"] = "y";
								
							}else{
							    $Settings["interface"] = "PanelB";								
							}
							
							$Settings["key"] = $Parm["key"];
							$Settings["Id_Edu_Componente_S"] = $Parm["Id_Edu_Componente_S"];
							$Settings["Id_Edu_Componente"] = $Parm["Id_Edu_Componente"];
							new Edu_Articulo_Det($Settings);
							DCExit();	
						
						break;								
														
							
							
							
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "Edu_Componente_Crud":
						
						$this->ObjectDelete($Parm);
						
						DCCloseModal();		
					    $Settings["interface"] = "y";
					    $Settings["key"] = $Parm["key"];
					    new Edu_Articulo_Det($Settings);
						DCExit();							
						
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
						
						DCExit();							
						
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
						DCExit();		
						
                break;	

				
        }
		
		
		
        switch ($interface) {
            case "lectura":
			
			    $fecha_value = DCPost("fecha_value");
				echo "Entro al chat rrr ".$fecha_value;
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
					, EC.Nombre 
					, EC.Contenido_Embebido 
					, EC.Imagen 
					, EC.Id_Edu_Formato 
					, EC.Orden 
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

                if(empty($Id_Edu_Componente_S)){
					
					$Query = "
						SELECT 
						EC.Id_Edu_Componente 
						, EC.Nombre 
						, EC.Imagen 
						, EC.Contenido_Embebido 
						, EC.Id_Edu_Formato 
						, EC.Orden 
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
				$Dominio_url = $Dominio."/sadministrator/archivos/docs/".$Imagen;
				
				if($Id_Edu_Formato == 2){

					if($Id_Edu_Tipo_Privacidad == 3){

						$btn = "<i class='zmdi zmdi-download zmdi-hc-fw'></i> Descargar ]" .$Dominio_url."]_blank]HREF]]btn btn-primary m-w-120}";
						$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);												
					}			
					
					$ReproductorYT = '<iframe src="http://docs.google.com/viewer?url='.$Dominio_url.'&embedded=true" 
									  width="600" height="300" style="border: none;"></iframe>
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
					
					$DCPanelTitle = DCPanelTitle($NombreComponente," Visualizaciones ".$Tot_Vistas."",$btn,"");
					
				}
			
									                           
				$Imagen_Banner = "<div style='width:93%;padding:0px 0px 0px 15px;' >".$Promocion_Productos_Varios."</div>";
				$DCSlidePlugin = DCSlidePlugin();
				
				if($Id_Edu_Tipo_Privacidad == 3){
					
				    if(!empty($User)){				
					    $PanelA = "<div id='Screen_Content' ".$style.">".$ReproductorYT."</div>";
					}else{
					    $PanelA = "<div id='Screen_Content' style='font-size: 1.5em;padding: 100px 10px;text-align: center;' >Este contenido es privado</div>";						
					}
					
				}else{
					
					    $PanelA = "<div id='Screen_Content' ".$style.">".$ReproductorYT."</div>";					
				}	
				
                $PanelA .= "<div id='Screen_Content_Btn' style='position: absolute;width: 100%;' >".$DCPanelTitle." </div>".$Jscript;
      

					$Nombre_Articulo = $Nombre_Articulo;				
				

				
				$Perfil = Biblioteca::Valida_Perfil("");
		        if($Perfil == 1 || $Perfil == 2){					
					
					$listMn = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Contenido [".$UrlFile."/interface/Create_Conten/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
					$listMn .= "<i class='zmdi zmdi-folder-outline zmdi-hc-fw'></i> Sub Carpeta [".$UrlFile."/interface/Create_Conten_SubItem/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
					$listMn .= "<i class='zmdi zmdi-file zmdi-hc-fw'></i> Documento [".$UrlFile."/interface/Create_Conten_SubItemB_Dcoc/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";					
					$listMn .= "<i class='zmdi zmdi-edit zmdi-hc-fw'></i> Editar [".$UrlFile_Articulo.$Redirect."/interface/Create/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
					$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar Objeto [".$UrlFile."/interface/delete_objeto/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
					$listMn .= " Genear Url Amigable [".$UrlFile_Edu_Blog."/interface/Generar_Url_Amigable/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
					$listMn .= " Inscripción <br>de Participantes [".$UrlFile_Edu_Participantes."/interface/List/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";


					$btn = "<i class='zmdi zmdi-menu zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]]btn-simple}";
					$btnA = DCButton($btn, 'botones1', 'sys_form');

				}
				
				$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXM]]btn-simple-b}";
				$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				
                
				if(!empty($User)){
					
					$btn = "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat ]" .$UrlFile."/interface/Chat_Grupal/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXM]]btn-simple}";
					$btn_chat = DCButton($btn, 'botones1', 'sys_formB'.$Count);	
					
				}
					
                $PanelB = '
					<div class="messenger">
					  <div class="m-left-toolbar" >
						
						<ul style="text-align:left;">
						  <li style="padding-top:12px;">
							 '.$btn_componentes.'
						  </li>	
						   <li style="padding-top:12px;">
							 '.$btn_chat.'
						  </li>		
											
						</ul>
					  </div>				
				  </div>	';

				$PanelB .= $this->Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,"",$Id_Edu_Tipo_Privacidad);
				
				$Layout = array(array("PanelA","col-md-8",$PanelA),array("PanelB","col-md-4",$Imagen_Banner.$DCSlidePlugin.$PanelB));
				$Content = DCLayout($Layout);
				
				$DCPanelTitle = DCPanelTitle($Nombre_Articulo,"",$btnA,"");		
				
				$jsMontor = MonitorChat();
				
				$Contenido = DCPageB($DCPanelTitle, $Content . $jsMontor ,"panel panel-default");
				
				$Style = '
				    <style>
						.col-md-8 {
							padding: 0px;
						}
						.col-md-4 {
							padding: 0px;
						}
                        .botones1 a {
							margin: 0px; color: #fff;
						}						
				    </style>
				';
				
				// DCVd($Row_Producto);
				if($Parm["request"] == "on"){
					DCWrite($LayoutB->main($Contenido.$Style,$Row_Producto));
				}else{
					DCWrite($Contenido.$Style);			
				}
				
                break;
				
				
        		
            case "Elementos":
			
                DCCloseModal();		
				
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];	

				$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXM]]btn-simple-b}";
				$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				
				if(!empty($User)){
							$btn = "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat ]" .$UrlFile."/interface/Chat_Grupal/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXM]]btn-simple}";
							$btn_chat = DCButton($btn, 'botones1', 'sys_formB'.$Count);	
				}	
	
                $PanelB = '
					<div class="messenger">
					  <div class="m-left-toolbar" >
						
						<ul style="text-align:left;">
						  <li style="padding-top:12px;">
							 '.$btn_componentes.'
						  </li>	
						   <li style="padding-top:12px;">
							 '.$btn_chat.'
						  </li>		
											
						</ul>
					  </div>				
				  </div>	';				
			
				$PanelB .= $this->Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,"","");			
			    DCWrite($PanelB);	
                break;


        		
            case "Chat_Grupal":
			
                DCCloseModal();		
				
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];	

				$btn = "<i class='zmdi zmdi-format-list-numbered zmdi-hc-fw'></i> Elementos ]" .$UrlFile."/interface/Elementos/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXM]]btn-simple}";
				$btn_componentes = DCButton($btn, 'botones1', 'sys_formA'.$Count);				

				$btn = "<i class='zmdi zmdi-comments zmdi-hc-fw'></i> Chat ]" .$UrlFile."/interface/Chat_Grupal/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]PanelB]HXM]]btn-simple-b}";
				$btn_chat = DCButton($btn, 'botones1', 'sys_formB'.$Count);	
				
                $PanelB = '
					<div class="messenger">
					  <div class="m-left-toolbar" >
						
						<ul style="text-align:left;">
						  <li style="padding-top:12px;">
							 '.$btn_componentes.'
						  </li>	
						   <li style="padding-top:12px;">
							 '.$btn_chat.'
						  </li>		
											
						</ul>
					  </div>				
				  </div>	';				
			
			
			    //$Mensajes =  LeerMensajeChatDDB($Id_Edu_Almacen);
				
				$PanelB .= " 
				
				<div class='Contenedor-Chat'>
					<div class='Conversacion-Chat' id='ConversacionChat' > ". $Mensajes."
					</div>				
					<div class='Mensaje-Chat'>
					    <form method='post' name='Edu_Chat_Crud' id='Edu_Chat_Crud' action='javascript:void(null);' enctype='multipart/form-data'> 
						<textarea name='Edu_Chat_Crud_Mensaje' id='Edu_Chat_Crud_Mensaje'></textarea>";

				$PanelB .=' <button type="button"  id="boton_chat" onclick="SaveFormSP(this);" 
				            direction="'.$UrlFile.'/Process/ENTRY/Obj/Edu_Chat_Crud/key/'.$Id_Edu_Almacen.'/Id_Edu_Componente_S/'.$Id_Edu_Componente_S.'/Id_Edu_Componente/'.$Id_Edu_Componente_S.'" 
							screen="ConversacionChat" class="zmdi zmdi-navigation zmdi-hc-fw" 
							form="Edu_Chat_Crud" >
							
							</button>';						
				$PanelB .= " 
					    </form>				
					</div>					
				</div>
				
				
				";	


                

				
			    DCWrite($PanelB);	
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
					DCExit();					
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

                if(empty($Id_Edu_Componente_S)){
					
					$Query = "
						SELECT 
						EC.Id_Edu_Componente 
						, EC.Nombre 
						, EC.Imagen 
						, EC.Contenido_Embebido 
						, EC.Id_Edu_Formato 
						, EC.Orden 
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
				$Dominio_url = $Dominio."/sadministrator/archivos/docs/".$Imagen;
				
				if($Id_Edu_Formato == 2){
					
					if($Id_Edu_Tipo_Privacidad == 3){

						$btn = "<i class='zmdi zmdi-download zmdi-hc-fw'></i> Descargar ]" .$Dominio_url."]_blank]HREF]]btn btn-primary m-w-120}";
						$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);												
					}			
										
					
					$ReproductorYT = '<iframe src="http://docs.google.com/viewer?url='.$Dominio_url.'&embedded=true" 
									  width="600" height="300" style="border: none;"></iframe>
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
					$DCPanelTitle = DCPanelTitle($NombreComponente," Visualizaciones ".$Tot_Vistas."",$btn,"");			
				}
				
				if($Id_Edu_Tipo_Privacidad == 3){
					
				    if(!empty($User)){				
					    $PanelA = "<div id='Screen_Content' ".$style.">".$ReproductorYT."</div>";
					}else{
					    $PanelA = "<div id='Screen_Content' style='font-size: 1.5em;padding: 100px 10px;text-align: center;' >Este contenido es privado</div>";						
					}
					
				}else{
					
					    $PanelA = "<div id='Screen_Content' ".$style.">".$ReproductorYT."</div>";					
				}	
				
                $PanelA .= "<div id='Screen_Content_Btn' style='position: absolute;width: 100%;' >".$DCPanelTitle."  </div>".$Jscript;
      
				$jsMontor = MonitorChat();
				// $Layout = array(array("PanelA","col-md-12",$PanelA));
				// $Content = DCLayout($Layout);
				DCWrite($PanelA . $jsMontor);			
	
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
                DCExit();
                
				break;
				
				
            case "Create_Conten_SubItem":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
	            $key = $Parm["key"];
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Carpeta_Crud/key/".$key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."/Id_Edu_Componente_Jerar/".$Id_Edu_Componente_Jerar."/Id_Edu_Componente/".$Id_Edu_Componente;
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
				
				$Combobox = array(
				     array( "Id_Edu_Formato"," SELECT Id_Edu_Formato AS Id, Nombre AS Name FROM edu_formato ",[])
				     , array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Componente_Carpeta_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_Carpeta_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                
				break;
				
				
             case "Create_Conten_SubItem_Dcoc":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
				
	            $key = $Parm["key"];
				if(empty( $Id_Edu_Componente_Jerar)){
					
                    $DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Doc_Crud/key/".$key;
									
				}else{
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
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Componente_Doc_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_Doc_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                
				break;     
				
             case "Create_Conten_SubItemB_Dcoc":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
				
	            $key = $Parm["key"];
				if(empty( $Id_Edu_Componente_Jerar)){
					
                    $DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_DocA_Crud/key/".$key;
									
				}else{
				    $DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_DocA_Crud/key/".$key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."/Id_Edu_Componente_Jerar/".$Id_Edu_Componente_Jerar."/Id_Edu_Componente/".$Id_Edu_Componente;
				}
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Componente)){
					
				    $Name_Interface = "Editar Documento";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_DocA_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Documento";					
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
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Componente_DocA_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_DocA_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                
				break; 				
				
             case "Create_Conten_SubItem_Articulo":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
	            $key = $Parm["key"];
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Articulo_Crud/key/".$key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."/Id_Edu_Componente_Jerar/".$Id_Edu_Componente_Jerar."/Id_Edu_Componente/".$Id_Edu_Componente;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Componente)){
					
				    $Name_Interface = "Editar Articulo";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_Articulo_Crud","btn btn-default m-w-120");					
				
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
				     array($Name_Button,$DirecctionA,"PanelA","Form","Edu_Componente_Articulo_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_Articulo_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                
				break;     	
				
				
             case "Create_Conten_SubItem_Embebido":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Id_Edu_Componente_Jerar = $Parm["Id_Edu_Componente_Jerar"];
	            $key = $Parm["key"];
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Embebido_Crud/key/".$key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."/Id_Edu_Componente_Jerar/".$Id_Edu_Componente_Jerar."/Id_Edu_Componente/".$Id_Edu_Componente;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Componente)){
					
				    $Name_Interface = "Editar Vídeo Embebido";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_Embebido_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Vídeo Embebido";					
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
				     array($Name_Button,$DirecctionA,"PanelA","Form","Edu_Componente_Embebido_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_Embebido_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                
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
		        
				// echo "hola mundo";
				// exit();
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
///xxxxxxxxxxxxxxxxxxxxxxxx				
	            $PanelB = $this->Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,$Id_Edu_Componente,"");				
				
				echo $PanelB;
                break;	
				
            case "Cierrar_Panel_Detalle":
			      // echo "asss";
		          DCCloseModal();	
				
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
			$height =  "height:100%";
		    $Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen,"Jerarquia_Id_Edu_Componente"=>$Id_Edu_Componente];			
		}else{
			$height =  "height:400px";
	        $Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen,"Jerarquia_Id_Edu_Componente"=>0];	
        }

		
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		
		$btn = " Cerrar ]" .$UrlFile."/interface/Cierrar_Panel_Detalle/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."/Id_Edu_Componente/".$Id_Edu_Componente."]Panel_".$Id_Edu_Componente."]HXM]]SinEstilo}";
		$Btn_Ocultar = DCButton($btn, 'botones1', 'sys_form_btn_cerrar'.$Id_Edu_Componente);
		
		$PanelB = '
		<div class="cart" style="'.$height.'">		
		
		  <table class="table table-hover">
			<tbody>	';	
			
		if(!empty($Id_Edu_Componente)){			
			$PanelB .= '
				<tr>	
					<td colspan="2">
						<div style=" width: 100%; float: left;text-align: center;">'.$Btn_Ocultar.'</div>
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
				// $DivIntroduccion = "<div class='Introduccion' >Introducción</div>";
				$Num_Orden = $DivIntroduccion;					
					
			}else{
				$DivIntroduccion = "";			
				
			}
			

				if($Field->Id_Edu_Formato == 3){
				    $Icon = "<i class='zmdi zmdi-collection-video zmdi-hc-fw' ></i>";
				}elseif($Field->Id_Edu_Formato == 2){
				    $Icon = "<i class='zmdi zmdi-file zmdi-hc-fw'> </i>";
				}elseif($Field->Id_Edu_Formato == 5){
				    $Icon = "<i class='zmdi zmdi-folder-outline zmdi-hc-fw'> </i>";	
				}elseif($Field->Id_Edu_Formato == 6){
				    $Icon = "<i class='zmdi zmdi-format-indent-increase zmdi-hc-fw'> </i>";	

					
				}else{
				    $Icon = "<i class='zmdi zmdi-collection-video zmdi-hc-fw' ></i>";					
				}
			
				$PanelB .= '					
				 <tr>
					<td class="c-image" style="height:63px;" >
					'.$Icon.'
					</td>
					<td class="c-link" style="width: 80%;">
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
							
							
								// if($Field->Vista_Sin_Inscripcion == "SI"){	
								
									$btn = " ".$Field->Nombre." ]" .$UrlFile."/interface/PanelB/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."/Id_Edu_Componente/".$Field->Id_Edu_Componente."]PanelA]]]SinEstilo}";
									$btn = DCButton($btn, 'botones1', 'sys_form_btn_item'.$Field->Id_Edu_Componente);
									$PanelB .= $btn;	

								// }					
							
								// $btn = "".$Field->Nombre." ]" .$UrlFile."/interface/Matricula/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."/Id_Edu_Componente/".$Field->Id_Edu_Componente."]animatedModal5]HXM]]SinEstilo}";
								// $btn = DCButton($btn, 'botones1', 'sys_form_btn_item'.$Field->Id_Edu_Componente);
								// $PanelB .= $btn;
							
								
												
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
					if($Perfil == 1 || $Perfil == 2){
						
						
						if($Field->Id_Edu_Formato == 5){
							
							////hhhhhhhhh	
							
							$listMn = "<i class='zmdi zmdi-edit zmdi-hc-fw'></i> Editar [".$UrlFile.$Redirect."/interface/Create_Conten_SubItem/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_Jerar/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-folder zmdi-hc-fw'></i> Sub Carpeta [".$UrlFile."/interface/Create_Conten_SubItem/key/".$Id_Edu_Almacen."/Id_Edu_Componente_Jerar/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
							$listMn .= "<i class='zmdi zmdi-file zmdi-hc-fw'></i> Documento [".$UrlFile."/interface/Create_Conten_SubItem_Dcoc/key/".$Id_Edu_Almacen."/Id_Edu_Componente_Jerar/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
							// $listMn .= "<i class='zmdi zmdi-file zmdi-hc-fw'></i> Documento [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-movie-alt zmdi-hc-fw'></i> Video  [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[PanelA[HXMa[{";	
							$listMn .= "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Artículo [".$UrlFile.$Redirect."/interface/Create_Conten_SubItem_Articulo/key/".$Id_Edu_Almacen."/Id_Edu_Componente_Jerar/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[PanelA[HXMS[{";	
							$listMn .= "<i class='zmdi zmdi-collection-video zmdi-hc-fw'></i>Video Embeb[".$UrlFile.$Redirect."/interface/Create_Conten_SubItem_Embebido/Id_Edu_Componente_Jerar/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-collection-text zmdi-hc-fw'></i> Examen [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-folder-person zmdi-hc-fw'></i> Tarea [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Componente);
							
						}elseif($Field->Id_Edu_Formato == 6){
						
						
							$listMn = "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Edtar [".$UrlFile.$Redirect."/interface/Create_Conten_SubItem_Articulo/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_Jerar/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."[PanelA[HXMS[{";	
							// $listMn .= "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Componente);	

						}elseif($Field->Id_Edu_Formato == 3){
						
						
							$listMn = "<i class='zmdi zmdi-file-text zmdi-hc-fw'></i> Edtar [".$UrlFile.$Redirect."/interface/Create_Conten_SubItem_Embebido/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Id_Edu_Componente_Jerar/".$Field->Id_Edu_Componente."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."[animatedModal5[HXM[{";	
							// $listMn .= "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
							$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
							$btn = "<i class='zmdi zmdi-more-vert zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]}";
							$btnB = DCButton($btn, 'botones1', 'sys_form'.$Field->Id_Edu_Componente);	
							
							
						}else{
							
							
							
							$listMn = "<i class='zmdi zmdi-edit zmdi-hc-fw'></i> Editar [".$UrlFile.$Redirect."/interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
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
								<td colspan="2">
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
			$Id_Edu_Almacen = $Settings["key"];
			$OrdenP = DCPost("Orden");

			$Query = " 
			SELECT Id_Edu_Componente, Orden FROM edu_componente  
			WHERE Id_Edu_Almacen = :Id_Edu_Almacen 	
			"; 
			$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen];
			$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			$cont = 0;
							
			
			foreach ($Registro as $Reg) {		
				$CodigoItemBD = $Reg->Id_Edu_Componente;
				$OrdenBD = $Reg->Orden;
				
				if ($CodigoItemBD == $Codigo_Item) {
					
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
					$rg = ClassPDO::DCUpdate('edu_componente', $reg , $where, $Conection);
									
				} else {
					
					$ubicacionB = ($OrdenBD * 100 + 10);
					$reg = array(
						'Orden' => $ubicacionB
					);
					$where = array('Id_Edu_Componente' => $CodigoItemBD);
					$rg = ClassPDO::DCUpdate('edu_componente', $reg , $where, $Conection);	
				}			
			}	
			
			$Query = " 
			SELECT Id_Edu_Componente, Orden FROM edu_componente  WHERE 
			       Id_Edu_Almacen = :Id_Edu_Almacen ORDER BY Orden ASC 	
			"; 
			$Where = ["Id_Edu_Almacen" =>$Id_Edu_Almacen];
			$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
			$Cont = 0;
			foreach ($Registro as $Reg) {
				
				$Cont += 1;
				$reg = array(
					'Orden' => $Cont
				);
				
				$where = array('Id_Edu_Componente' => $Reg->Id_Edu_Componente);
				$rg = ClassPDO::DCUpdate('edu_componente', $reg , $where, $Conection);
				
			}	
			
	}
	
}