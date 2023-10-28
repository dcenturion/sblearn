<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
require_once('./sviews/ft_settings_site.php');
require_once('./sviews/user.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();


class Edu_Video{
    
	
	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu_imagen";
		$UrlFile_Articulo = "/sadministrator/articulo";
		$UrlFile_Edu_Tipo_Componente = "/sadministrator/edu_tipo_componente";
		$UrlFile_Edu_Tipo_Estructura = "/sadministrator/edu_tipo_estructura";
		$UrlFile_Edu_Area_Conocimiento = "/sadministrator/edu_area_conocimiento";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];
		
		// echo "hola";
		// exit();
		// var_dump($Parm);

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					case "Edu_Componente_Blog_Crud":
					
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
							new Edu_Blog($Settings);
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
							new Edu_Blog($Settings);
							DCExit();	
						
						break;								
							
							
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "Edu_Componente_Blog_Crud":
						
						$this->ObjectDelete($Parm);
						
						DCCloseModal();		
					    $Settings["interface"] = "y";
					    $Settings["key"] = $Parm["key"];
					    new Edu_Blog($Settings);
						DCExit();							
						
						break;	
						
					case "Edu_Object":
						
						$this->ObjectDelete_Object($Parm);
						
						DCCloseModal();		
					    // $Settings["interface"] = "y";
					    // $Settings["key"] = $Parm["key"];
					    // new Edu_Blog($Settings);
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
					    new Edu_Blog($Settings);
						DCExit();		
						
                break;	

				
        }
		
		
		
        switch ($interface) {
            case "begin":
			
				$Action = $Parm["action"];
		        $_SESSION['Action'] = $Action;	
				// echo "hola ";
				
				$Settings["interface"] = "y";
				$Settings["key"] = $Parm["key"];
				$Settings["request"] = $Parm["request"];
				$Settings["Id_Edu_Componente_S"] = $Parm["Id_Edu_Componente_S"];
				new Edu_Video($Settings);	
				DCExit();
				// $Settings = array();
				// $Settings['Url'] = "/sadministrator/edu_articulo_det/interface/y/key/".$Parm["key"];
				// $Settings['Screen'] = "ScreenRight";
				// $Settings['Type_Send'] = "";
				// DCRedirectJS($Settings);			
				
			break;
            case "y":
			
			    // DCVd($User);
				$layout  = new Layout();
				$Redirect = "/REDIRECT/edu_blog";
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
					$Where = ["Url" => $Parm["Ie"]];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Id_Entity = $Row->Id_Entity;	
					
				    $_SESSION['Entity'] = $Id_Entity; 

				}				
				
				$Query = "
				    SELECT 
					EC.Id_Edu_Componente 
					, EC.Nombre 
					, EC.Contenido_Embebido 
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

                if(empty($Id_Edu_Componente_S)){
					
					$Query = "
						SELECT 
						EC.Id_Edu_Componente 
						, EC.Nombre 
						, EC.Contenido_Embebido 
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

				if(empty($Id_Suscripcion) && !empty($_SESSION['Action']) ){
			
					$btn = "<i class='zmdi zmdi-folder-star-alt zmdi-hc-lg'></i> Suscríbete ]" .$UrlFile."/interface/Matricula/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]animatedModal5]HXM]]btn btn-primary m-w-120}";
					$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);
							
				}else{
					$btn = "<i class='zmdi zmdi-share zmdi-hc-lg'></i> Compartir ]" .$UrlFile."/interface/Compartir/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]animatedModal5]HXM]]btn btn-primary m-w-120}";
					$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);												
					$btn = "";												
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
    						   
                if( $Tot_Items != 0){
				    
					$DCPanelTitle = DCPanelTitle($NombreComponente," Visualizaciones ".$Tot_Vistas."",$btn);
									
				}

                $Contenido_Embebido =trim($Contenido_Embebido);

				if(empty($Contenido_Embebido)){
					
					if(empty($Id_Suscripcion)){
						

						$Query = "
							SELECT COUNT(*) AS Tot_Reg FROM edu_componente EC
							WHERE EC.Contenido_Embebido <> :Contenido_Embebido AND Id_Edu_Almacen = :Id_Edu_Almacen;
						";					
						$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Contenido_Embebido" => "" ];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Tot_Reg_Comp = $Row->Tot_Reg;
						// DCWrite("Tot_Reg_Comp");
						// DCWrite($Tot_Reg_Comp);
						
						if($Tot_Reg_Comp == 0 ){
							
							$ReproductorYT =  	"
								<a 
								type_send='HXM'
								data-toggle='modal'
								id='sys_form_Btn_H_0_img'
								screen='animatedModal5'
								onclick='LoadPage(this);'
								data-target='#animatedModal5'
								class='Screen_Content_Img_Vacio' 
								direction='/sadministrator/edu_articulo_det/interface/Matricula/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."' 
								style='background-image: url(/sadministrator/simages/Mensaje-No-Disponobilidad.png)' 
								
								></a>						
							";	
							
						}else{
							
							$ReproductorYT =  	"
								<a 
								class='Screen_Content_Img_Vacio' 
								style='background-image: url(/sadministrator/simages/Mensaje-No-Disponobilidad-Registrado.png)' 
								></a>
							";			
							
						}						
						
					}else{
						

						$Query = "
							SELECT COUNT(*) AS Tot_Reg FROM edu_componente EC
							WHERE EC.Contenido_Embebido <> :Contenido_Embebido AND Id_Edu_Almacen = :Id_Edu_Almacen;
						";					
						$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Contenido_Embebido" => "" ];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Tot_Reg_Comp = $Row->Tot_Reg;
						
						if($Tot_Reg_Comp == 0 ){
							
							$ReproductorYT =  	"
								<a 
								class='Screen_Content_Img_Vacio' 
								style='background-image: url(/sadministrator/simages/Mensaje-Pronta-Disponobilidad.png)' 
								></a>
							";
							
						}else{
							
							$ReproductorYT =  	"
								<a 
								class='Screen_Content_Img_Vacio' 
								style='background-image: url(/sadministrator/simages/Mensaje-No-Disponobilidad-Registrado.png)' 
								></a>
							";							
						}

						
					}
					
				}else{

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
				
				$Row_DP =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_DP->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_DP->Nombre;				
		        $Id_Edu_Area_Conocimiento = $Row_DP->Id_Edu_Area_Conocimiento;		
		        $Fundacion = $Row_DP->Fundacion;		
		        $Site_Facebook_Fundacion = $Row_DP->Site_Facebook_Fundacion;		
				
				// var_dump($Row_DP);


									                           
				$Query = "
					SELECT EP.Imagen, EP.Url_Redireccion, EPD.Id_Edu_Area_Conocimiento FROM edu_publicidad EP
					INNER JOIN edu_publicidad_det  EPD ON  EP.Id_Edu_Publicidad = EPD.Id_Edu_Publicidad    
					WHERE  
					EPD.Id_Edu_Area_Conocimiento = :Id_Edu_Area_Conocimiento AND EP.Estado = :Estado
				";  
				$Where = ["Id_Edu_Area_Conocimiento"=>$Id_Edu_Area_Conocimiento, "Estado"=>"Activo"];
				$Datos_Publicidad = ClassPDO::DCRow($Query,$Where ,$Conection);	
                $Imagen	 = $Datos_Publicidad->Imagen;	
                $Url_Redireccion	 = $Datos_Publicidad->Url_Redireccion;	
				

				
				$LenghtNA = strlen($Nombre_Articulo);
				if($LenghtNA > 100){
                    $Nombre_Articulo = substr($Nombre_Articulo, 0,100);					
                    $Nombre_Articulo = $Nombre_Articulo." ..";	
				}else{		
					$Nombre_Articulo = $Nombre_Articulo;				
				}

				
				$Perfil = Biblioteca::Valida_Perfil();					
		        if($Perfil == 1 || $Perfil == 2){					
					
					$listMn = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Contenido [".$UrlFile."/interface/create_conten/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
					$listMn .= "<i class='zmdi zmdi-edit zmdi-hc-fw'></i> Editar [".$UrlFile_Articulo.$Redirect."/interface/Create/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
					$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar Objeto [".$UrlFile.$Redirect."/interface/Delete_Objeto/Id_Edu_Articulo/".$Id_Edu_Articulo."/key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";

					$btn = "<i class='zmdi zmdi-menu zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
					$btnA = DCButton($btn, 'botones1', 'sys_form');

				}
				
                $PanelB = '
					<div class="col-md-12">
					  <div style="position:relative;padding:10px 0px 20px 0px;">
						<div>
						<h4>'.$Nombre_Articulo.' </h4>
						</div>
						<ul style="position:absolute;right:0px;top:0px;" >
						  <li style="padding-top:12px;list-style: none;">
							 '.$btnA.'
						  </li>						
						</ul>
					  </div>				
				  </div>	';
                
			
				$PanelB .= $this->Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,$Id_Edu_Area_Conocimiento);
					
				$Layout = array(array("PanelA","",$PanelB));
				$Content = DCLayout($Layout);
				
				
				$Contenido = DCPage("" , $Content . $Imagen_Banner ,"panel panel-default");

			
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$Row_DP));
				}else{
					DCWrite($Contenido.$Style);			
				}
				
                break;
				
        		
            case "create_conten":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $key = $Parm["key"];
				
				// DCWrite("Hola mundo");
				// DCExit();
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Blog_Crud/key/".$key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."/Id_Edu_Componente/".$Id_Edu_Componente;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
				if(!empty($Id_Edu_Componente)){
					
				    $Name_Interface = "Editar Contenido";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Componente_Blog_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Contenido";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Componente_Blog_Crud");							
				}
				
				$Combobox = array(

				     array("Id_Edu_Productor"," SELECT Id_Edu_Productor AS Id, Nombre AS Name FROM edu_productor ",[])
				     ,array("Id_User_Miembro_Gestor"," SELECT Id_User_Miembro AS Id, Nombre AS Name FROM user_miembro ",[])
				     ,array(
					 "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente 
					 WHERE Id_Edu_Almacen = :Id_Edu_Almacen ORDER BY Orden ASC",["Id_Edu_Almacen"=>$key]					 
					 )
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Componente_Blog_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Componente_Blog_Crud",$Class,$Id_Edu_Componente,$PathImage,$Combobox,$Buttons,"Id_Edu_Componente");
				
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
						
					$btn = " Aceptar ]" .$UrlFile ."/Process/MATRICULA/Obj/Edu_Componente_Blog_Crud/key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
					$btn .= " Cancelar ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
					$Button = DCButton($btn, 'botones1', 'sys_form');					
					$Html = DCModalFormMsj("Para ver todas la lecciones del compendio debes suscribirte",$Form,$Button,"bg-info-b");
					
				}else{
                    
					$btn = " Ya soy Usuario ]/sadministrator/login/key/".$Id_Edu_Almacen."/Request/On/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]]HREF]]btn btn-default dropdown-toggle]}";				
					$btn .= " Quiero Registrarme ]/sadministrator/edu_register/key/".$Id_Edu_Almacen."/Request/On/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]]HREF]]btn btn-default dropdown-toggle]}";				
					$btn .= " Cancelar ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
					$Button = DCButton($btn, 'botones1', 'sys_form');					
					$Html = DCModalFormMsj("Para ver todas la lecciones del compendio debes registrarte",$Form,$Button,"bg-info-b");
					
				}
				
                DCWrite($Html);
				
                break;	
				
            case "Delete_Componente":
		
		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
				$Id_Edu_Almacen = $Parm["key"];
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Componente/".$Id_Edu_Componente."/Obj/Edu_Componente_Blog_Crud/key/".$Id_Edu_Almacen."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Deseas eliminar el contenido",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;	
				
            case "Compartir":
		
		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
				$Id_Edu_Almacen = $Parm["key"];
				// $btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Componente/".$Id_Edu_Componente."/Obj/Edu_Componente_Blog_Crud/key/".$Id_Edu_Almacen."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
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
						 href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fibm.com%2F&amp;src=sdkpreparse">
						 <i class="zmdi zmdi-facebook-box zmdi-hc-2x"></i>
						 </a>
						 
						 <a class="btn btn-googleplus" target="_blank" 
						  href="https://plus.google.com/share?url=http://developando.com">
						 <i class="zmdi zmdi-google-plus zmdi-hc-2x"></i>
						 </a>	
						 
						 <a class="btn btn-twitter" target="_blank" 
						 href="https://twitter.com/?status=Me gusta esta página http://norfipc.com/redes-sociales/codigos-direcciones-para-compartir-contenido-redes-sociales.php">
						 <i class="zmdi zmdi-twitter zmdi-hc-2x"></i>
						 </a>
						 
						 <a class="btn btn-linkedin" target="_blank" 
						 href="http://www.linkedin.com/shareArticle?url=http://norfipc.com/redes-sociales/codigos-direcciones-para-compartir-contenido-redes-sociales.php">
						 <i class="zmdi zmdi-linkedin zmdi-hc-2x"></i>
						 </a>	
						 
						 
                        ';
				
			    $Html = DCModalFormMsj("¡ Comparte tu aprendizaje con tus amigos !","",$Form,"bg-info-b");
                DCWrite($Html);
				
                break;	

            case "Delete_Objeto":
		
		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
		        $key = $Parm["key"];
		        $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];

				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Articulo/".$Id_Edu_Articulo."/Obj/Edu_Object/key/".$key."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Deseas eliminar el Objeto",$Form,$Button,"bg-info");
                DCWrite($Html);
				
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
		$Return = ClassPDO::DCInsert("edu_vistas_objectos", $data, $Conection);	
		
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
			$rg = ClassPDO::DCUpdate('edu_vistas_objectos_resumen', $reg , $where, $Conection);				
			
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
			$Return = ClassPDO::DCInsert("edu_vistas_objectos_resumen", $data, $Conection);				
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
	
	
	public function Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion,$Id_Edu_Area_Conocimiento) {
       	global $Conection, $DCTimeHour,$NameTable;
		  // DCWrite("hola");
		$Query = "
			SELECT 
			 EC.Id_Edu_Componente
			, EP.Nombre AS Productor
			, EP.Url_Pagina_Web
			, EM.Nombre AS Gestor
			, EM.Url_Facebook
			FROM edu_componente EC
			INNER JOIN edu_productor EP ON EC.Id_Edu_Productor = EP.Id_Edu_Productor
			INNER JOIN user_miembro EM ON EM.Id_User_Miembro = EC.Id_User_Miembro_Gestor
			WHERE
			EC.Id_Edu_Componente = :Id_Edu_Componente
			ORDER BY EC.Orden ASC
		";    
		$Where = ["Id_Edu_Componente" => $Id_Edu_Componente_S];		
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);
		$Productor = $Row->Productor;
		$Gestor = $Row->Gestor;
		$Url_Pagina_Web = $Row->Url_Pagina_Web;
		$Url_Facebook = $Row->Url_Facebook;
		
		$Row_DP =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		$Id_Edu_Articulo = $Row_DP->Id_Edu_Articulo;
		$Nombre_Articulo = $Row_DP->Nombre;				
		$Id_Edu_Area_Conocimiento = $Row_DP->Id_Edu_Area_Conocimiento;		
		$Fundacion = $Row_DP->Fundacion;		
		$Site_Facebook_Fundacion = $Row_DP->Site_Facebook_Fundacion;
		$Imagen_Articulo = $Row_DP->Imagen;
		$Contenido_Embebido = $Row_DP->Link;

		$Query = "
			SELECT EP.Imagen, EP.Url_Redireccion, EPD.Id_Edu_Area_Conocimiento FROM edu_publicidad EP
			INNER JOIN edu_publicidad_det  EPD ON  EP.Id_Edu_Publicidad = EPD.Id_Edu_Publicidad    
			WHERE  
			EPD.Id_Edu_Area_Conocimiento = :Id_Edu_Area_Conocimiento AND EP.Estado = :Estado
		";  
		$Where = ["Id_Edu_Area_Conocimiento"=>$Id_Edu_Area_Conocimiento, "Estado"=>"Activo"];
		$Datos_Publicidad = ClassPDO::DCRow($Query,$Where ,$Conection);	
		$Imagen	 = $Datos_Publicidad->Imagen;	
		$Url_Redireccion	 = $Datos_Publicidad->Url_Redireccion;	
		
		$Imagen_Banner = "
			<div class='col-md-12'>
				<div id='Screen_Banner'  style='bottom:0px;position:relative;' >
						<a href='".$Url_Redireccion."' target='_blank'
						class='Screen_Banner_Img_Vacio' 
						style='background-image: url(/sadministrator/simages/promocion/".$Imagen.")' 
						></a>
				</div>
			</div>
		";	
		
		
		$Url = "http://yachai.org/sadministrator/edu_articulo_det/interface/begin/Request/On/key/".$Id_Edu_Almacen."/Action/Sugerencia";
		
		$Redes_Sociales = Redes_Sociales($Url);	
		
		$Paneles_Inferiores = '
			<div class="profile">
			
					<div class="col-md-12" >
						<div class="p-info m-b-20">
							<div class="m-y-0"> '.$Redes_Sociales.' </div>
						
						</div>	
					</div>	
		    </div>
		  
		';		
		
		
		
		$Query = "
			SELECT 
			 EAR.Id_Edu_Articulo
			, EAR.Nombre
			, EAR.Imagen 
			, EA.Id_Edu_Almacen 

			FROM edu_articulo EAR
			INNER JOIN edu_almacen EA ON EAR.Id_Edu_Articulo = EA.Id_Edu_Articulo   
			WHERE EAR.Id_Edu_Tipo_Estructura = :Id_Edu_Tipo_Estructura 
			AND  EAR.Id_Edu_Area_Conocimiento = :Id_Edu_Area_Conocimiento
			AND  EA.Id_Edu_Almacen <> :Id_Edu_Almacen
		";    
		$Where = ["Id_Edu_Tipo_Estructura" => 6 
		         , "Id_Edu_Area_Conocimiento" => $Id_Edu_Area_Conocimiento
		         , "Id_Edu_Almacen" => $Id_Edu_Almacen
				 ];		
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		
		$Articulos_Relacionados = '
		<div class="" style="padding: 0px 0px 0px 15px;width: 95%;">	
			<div class="" style="width:100%;padding:20px 40px 20px 0px;">
			<h4>CONTENIDO RELACIONADO</h4>
			</div>		
		';

		foreach($Rows AS $Field){
			
			$Articulos_Relacionados .= '
                <a href="/sadministrator/edu_blog/interface/begin/Request/On/key/'.$Field->Id_Edu_Almacen.'/Action/Sugerencia">				
				   <div class="widget-infoblock wi-small m-b-30" style="background-image: url(/sadministrator/simages/articulo/'.$Field->Imagen.');height: 123px;">
					  <div class="wi-bg"></div>
					  <div class="wi-content-bottom p-a-30">
						<div class="wi-text">'.$Field->Nombre.'</div>
					  </div>
					</div>	
                </a>	
				
			';
		
        }	
		$Articulos_Relacionados .= '
		</div>			
		';

		if(empty($Imagen_Articulo)){
			$Url_Img = '/sadministrator/simages/Iconos-Contents.png';	
			$Num_Orden = "<h1 style='margin:18px 0px 0px 10px;color: #fff;'>".$Field->Orden."</h1>";					
		}else{
			$Url_Img = '/sadministrator/simages/articulo/'.$Imagen_Articulo.'';	
			$Num_Orden =  "";					
		}
	
		$ReproductorYT =  	"

			<div id='ReproductorVideo'></div>
			<script>
				var vp = new ReproductorVideo({
					id      : 'ReproductorVideo',
					videoId : '".$Contenido_Embebido."'
				});
			</script>
		";
		$ReproductorYT = "<div id='Screen_Content' style='height:300px;'>".$ReproductorYT."</div>";	
			
			
		$PanelB = '					
		<div class="panel_blog" style="">	
		
		<div class="col-md-12">	
			<div class="col-md-8" >	
				'.$ReproductorYT.'
				<div>  '.$Imagen_Banner.'</div>
			</div>
			<div  class="col-md-4">'.$Paneles_Inferiores . $Articulos_Relacionados.'</div>						
		</div>		
	
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
	
	public function Matricula($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Componente_S = $Settings["Id_Edu_Componente_S"];
		$Id_Edu_Almacen = $Settings["key"];
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		

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
		
        $Row_User = User::MainData();
        $Email = $Row_User->Email;
		// DCWrite("Email");
		// DCWrite($Email);
		// DCExit();
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
				
				
				
				Edu_Blog::Email_Matricula($Id_Edu_Almacen,$Email);
				DCWrite(Message("Matrícula Exitosa","C"));
				
		}else{
			
				DCWrite(Message("Ya estas matriculado en este objeto","C"));			
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
											<a href='".$dominio."sadministrator/edu_articulo_det/interface/begin/Request/On/key/".$Id_Edu_Almacen."/Action/Sugerencia' style='font-size:12pt;height: 30px;color: white;text-decoration: none;padding: 8px 10px;font-family: arial;' >
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