<?php
require_once(dirname(__FILE__).'/layout_B.php');
require_once(dirname(__FILE__).'/ft_biblioteca.php');
require_once(dirname(__FILE__).'/ft_settings_site.php');
require_once(dirname(__FILE__).'/user.php');

$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Articulo_Det{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu_articulo_det";
		$UrlFile_Articulo = "/sadministrator/articulo";
		$UrlFile_Edu_Tipo_Componente = "/sadministrator/edu_tipo_componente";
		$UrlFile_Edu_Tipo_Estructura = "/sadministrator/edu_tipo_estructura";
		$UrlFile_Edu_Area_Conocimiento = "/sadministrator/edu_area_conocimiento";
		$UrlFile_Edu_Blog = "/sadministrator/edu_blog";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					case "Edu_Componente_Crud":
					
		                    $Data = array();
							$Data['Id_Edu_Almacen'] = $Parm["Key"]; 
				
                            if(DCPost("Introduccion") == "SI" ){
								
								$reg = array(
								'Introduccion' => ''
								);
								$where = array('Id_Edu_Almacen' =>$Parm["Key"]);
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
							$Where = ["Id_Edu_Almacen" =>$Parm["Key"]];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Tot_Items = $Row->Tot + 1;
							
							$Data['Orden'] = $Tot_Items;
							$Id_Edu_Componente = DCSave($Obj,$Conection,$Parm["Id_Edu_Componente"],"Id_Edu_Componente",$Data);
							
							$this->OrdenarContenido($Parm);
							
							$Settings["Interface"] = "y";
							$Settings["Key"] = $Parm["Key"];
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
							$Settings["Interface"] = "y";
							$Settings["Key"] = $Parm["Key"];
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
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
					    $Settings["Interface"] = "y";
					    $Settings["Key"] = $Parm["Key"];
					    new Edu_Articulo_Det($Settings);
						DCExit();							
						
						break;	
						
					case "Edu_Object":
						
						$this->ObjectDelete_Object($Parm);
						
						DCCloseModal();		
					    // $Settings["Interface"] = "y";
					    // $Settings["Key"] = $Parm["Key"];
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
					    $Settings["Interface"] = "y";
					    $Settings["Key"] = $Parm["Key"];
					    $Settings["Id_Edu_Componente_S"] = $Parm["Id_Edu_Componente_S"];
					    new Edu_Articulo_Det($Settings);
						DCExit();		
						
                break;	

				
        }
		
		
		
        switch ($Interface) {
            case "begin":
			
				$Action = $Parm["Action"];
		        $_SESSION['Action'] = $Action;	
				
				$Settings["Interface"] = "y";
				$Settings["Key"] = $Parm["Key"];
				$Settings["Request"] = $Parm["Request"];
				$Settings["Id_Edu_Componente_S"] = $Parm["Id_Edu_Componente_S"];
				new Edu_Articulo_Det($Settings);	
				DCExit();
				// $Settings = array();
				// $Settings['Url'] = "/sadministrator/edu_articulo_det/Interface/y/Key/".$Parm["Key"];
				// $Settings['Screen'] = "ScreenRight";
				// $Settings['Type_Send'] = "";
				// DCRedirectJS($Settings);			
				
			break;
            case "y":
			
			    // DCVd($User);
				$LayoutB  = new LayoutB();
				$Redirect = "/REDIRECT/edu_articulo_det";
				$Id_Edu_Almacen = $Parm["Key"];
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

                if(empty($Id_Edu_Componente_S)){
					
					$Query = "
						SELECT 
						EC.Id_Edu_Componente 
						, EC.Nombre 
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
			
					$btn = "<i class='zmdi zmdi-folder-star-alt zmdi-hc-lg'></i> Suscríbete ]" .$UrlFile."/Interface/Matricula/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]animatedModal5]HXM]]btn btn-primary m-w-120}";
					$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);
							
				}else{
					$btn = "<i class='zmdi zmdi-share zmdi-hc-lg'></i> Compartir ]" .$UrlFile."/Interface/Compartir/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]animatedModal5]HXM]]btn btn-primary m-w-120}";
					$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);												
					// $btn = "";												
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


				// echo '
						// <video width="320" height="240" controls>
						// <source src="https://1fhjlk8.oloadcdn.net/dl/l/HtKuUWD2dAX2bgbl/kKhPzygA7Pw/3ll0b0lat.d3.w4ll.str33_-_www.locopelis.com.mp4" type="video/mp4">
						// <source src="movie.ogg" type="video/ogg">
						// Your browser does not support the video tag.
						// </video>
				
				
				// ';
				if(empty($Contenido_Embebido)){
					
					if(empty($Id_Suscripcion)){
						

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
								type_send='HXM'
								data-toggle='modal'
								id='sys_form_Btn_H_0_img'
								screen='animatedModal5'
								onclick='LoadPage(this);'
								data-target='#animatedModal5'
								class='Screen_Content_Img_Vacio' 
								direction='/sadministrator/edu_articulo_det/Interface/Matricula/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."' 
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
                    
	
					$Contenido_Embebido   = rtrim($Contenido_Embebido);
					
									// DCVd($Id_Edu_Formato);
									
				    if($Id_Edu_Formato == 2){
						$ReproductorYT = $Contenido_Embebido;
						
					}elseif($Id_Edu_Formato == 4){
						
						$ReproductorYT = '
						
						<video width="100%" height="100%" controls>
						<source src="'.$Contenido_Embebido.'" type="video/mp4">
						<source src="movie.ogg" type="video/ogg">
						Your browser does not support the video tag.
						</video>
						
						';											
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
					
                }
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;				
		        $Id_Edu_Area_Conocimiento = $Row_Producto->Id_Edu_Area_Conocimiento;				
		        $Id_Edu_Sub_Linea = $Row_Producto->Id_Edu_Sub_Linea;				
									                           
				$Query = "
					SELECT EP.Imagen, EP.Url_Redireccion, EPD.Id_Edu_Area_Conocimiento FROM edu_publicidad EP
					INNER JOIN edu_publicidad_det  EPD ON  EP.Id_Edu_Publicidad = EPD.Id_Edu_Publicidad    
					WHERE  
					EPD.Id_Edu_Area_Conocimiento = :Id_Edu_Area_Conocimiento AND EP.Estado = :Estado
				";  
				$Where = ["Id_Edu_Area_Conocimiento"=>$Id_Edu_Area_Conocimiento, "Estado"=>"Activo"];
//				$Datos_Publicidad = ClassPDO::DCRows($Query,$Where ,$Conection);
				
//				$Promocion_Productos_Varios ='
//					<div  class="slider_content" style="margin-left:0%;" >
//						<div id="slider" class="slider" >
//							  ';
//
//				foreach($Datos_Publicidad AS $Field){
//						$Promocion_Productos_Varios .='
//
//										<a href="'.$Field->Url_Redireccion.'" target="_blank" style="background-image: url(/sadministrator/simages/promocion/'.$Field->Imagen.')">
//
//										</a>
//
//										';
//				}
//				$Promocion_Productos_Varios .='
//						</div>
//					</div>
//				';
				
				
				
				$Imagen_Banner = "<div style='width:93%;padding:0px 0px 0px 15px;' >".$Promocion_Productos_Varios."</div>";
				$DCSlidePlugin = DCSlidePlugin();
				
				
                $PanelA = "<div id='Screen_Content'>".$ReproductorYT."</div>";
				
                $PanelA .= "<div id='Screen_Content_Btn' style='position: absolute;width: 100%;' >".$DCPanelTitle."</div>".$Jscript;
      

				
				$LenghtNA = strlen($Nombre_Articulo);
				if($LenghtNA > 38){
                    $Nombre_Articulo = substr($Nombre_Articulo, 0,38);					
                    $Nombre_Articulo = $Nombre_Articulo." ..";	
				}else{		
					$Nombre_Articulo = $Nombre_Articulo;				
				}

				
				$Perfil = Biblioteca::Valida_Perfil();					
		        if($Perfil == 1 || $Perfil == 2){					
					
					$listMn = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Contenido [".$UrlFile."/Interface/Create_Conten/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
					$listMn .= "<i class='zmdi zmdi-edit zmdi-hc-fw'></i> Editar [".$UrlFile_Articulo.$Redirect."/Interface/Create/Id_Edu_Articulo/".$Id_Edu_Articulo."/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
					$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar Objeto D[".$UrlFile.$Redirect."/Interface/delete_objeto/Id_Edu_Articulo/".$Id_Edu_Articulo."/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";
					$listMn .= " Genear Url Amigable [".$UrlFile_Edu_Blog."/Interface/Generar_Url_Amigable/Id_Edu_Articulo/".$Id_Edu_Articulo."/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";


					$btn = "<i class='zmdi zmdi-menu zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
					$btnA = DCButton($btn, 'botones1', 'sys_form');

				}
				
                $PanelB = '
					<div class="messenger">
					  <div class="m-left-toolbar">
						<div class="mlt-contact">
						'.$Nombre_Articulo.'
						</div>
						<ul>
						  <li style="padding-top:12px;">
							 '.$btnA.'
						  </li>						
						</ul>
					  </div>				
				  </div>	';

				$PanelB .= $this->Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion);
				
				$Layout = array(array("PanelA","col-md-8",$PanelA),array("PanelB","col-md-4",$Imagen_Banner.$DCSlidePlugin.$PanelB));
				$Content = DCLayout($Layout);
				
				$Contenido = DCPage("" , $Content ,"panel panel-default");
				$Style = '
				    <style>
						.col-md-8 {
							padding: 0px;
						}
						.col-md-4 {
							padding: 0px;
						}						
				    </style>
				';
				
				// DCVd($Row_Producto);
				if($Parm["Request"] == "On"){
					DCWrite($LayoutB->main($Contenido.$Style,$Row_Producto));
				}else{
					DCWrite($Contenido.$Style);			
				}
				
                break;
				
				
        		
            case "PanelB":
			
				if($Parm["Request"] == "On"){
                    
					// history.pushState(UrlRaiz+"/Request/On", "", UrlRaiz+"/Request/On");
					DCRedirect("/sadministrator/edu_articulo_det/Interface/y/Key/".$Parm["Key"]."/Id_Edu_Componente_S/".$Parm["Id_Edu_Componente_S"]."/Request/On");
					
					// $Settings = array("/sadministrator/edu_articulo_det/Interface/y/Key/".$Parm["Key"]."/Id_Edu_Componente_S/".$Parm["Id_Edu_Componente_S"]);
					// $Settings['Url'] = "/sadministrator/edu_articulo_det/Interface/y/Key/".$Parm["Key"]."/Id_Edu_Componente_S/".$Parm["Id_Edu_Componente_S"];
					// $Settings['Screen'] = "ScreenRight";
					// $Settings['Type_Send'] = "";
					// DCRedirectJS($Settings);						
					DCExit();					
				}
				
				$layout  = new Layout();
				$Redirect = "/REDIRECT/edu_articulo_det";
				$Id_Edu_Almacen = $Parm["Key"];
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

                if(empty($Id_Edu_Componente_S)){
					
					$Query = "
						SELECT 
						EC.Id_Edu_Componente 
						, EC.Nombre 
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
			
					$btn = "<i class='zmdi zmdi-folder-star-alt zmdi-hc-lg'></i> Suscríbete ]" .$UrlFile."/Interface/Matricula/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]animatedModal5]HXM]]btn btn-primary m-w-120}";
					$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);
							
				}else{
					$btn = "<i class='zmdi zmdi-share zmdi-hc-lg'></i> Compartir ]" .$UrlFile."/Interface/Compartir/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]animatedModal5]HXM]]btn btn-primary m-w-120}";
					$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);												
					// $btn = "";												
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

                // $Contenido_Embebido =trim($Contenido_Embebido);

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
								direction='/sadministrator/edu_articulo_det/Interface/Matricula/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."' 
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
                    
					// DCVd($Contenido_Embebido);
				    if($Id_Edu_Formato == 2){
						
						$ReproductorYT = $Contenido_Embebido;
											
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
					
                }
				
                $PanelA = "<div id='Screen_Content'>".$ReproductorYT."</div>";
                $PanelA .= "<div id='Screen_Content_Btn' style='position: absolute;width: 100%;' >".$DCPanelTitle."</div>".$Jscript;
      

				$Layout = array(array("PanelA","col-md-12",$PanelA));
				$Content = DCLayout($Layout);
				DCWrite($Content);			
	
                break;			
            case "Create_Conten":
			      
	            $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
	            $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
	            $Key = $Parm["Key"];
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Componente_Crud/Key/".$Key."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."/Id_Edu_Componente/".$Id_Edu_Componente;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Componente/".$Id_Edu_Componente;
				
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
				     , array( "Orden"," SELECT Orden AS Id, Orden AS Name FROM edu_componente  WHERE Id_Edu_Almacen = :Id_Edu_Almacen ORDER BY Orden ASC",["Id_Edu_Almacen"=>$Key])
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
				
      
				
            case "Matricula":
			
				$User = $_SESSION['User'];

		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
				$Id_Edu_Almacen = $Parm["Key"];
				$Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];
                
                if(!empty($User)){
						
					$btn = " Aceptar ]" .$UrlFile ."/Process/MATRICULA/Obj/Edu_Componente_Crud/Key/".$Id_Edu_Almacen."/Id_Edu_Componente/".$Id_Edu_Componente."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
					$btn .= " Cancelar ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
					$Button = DCButton($btn, 'botones1', 'sys_form');					
					$Html = DCModalFormMsj("Para ver todas la lecciones del compendio debes suscribirte",$Form,$Button,"bg-info-b");
					
				}else{
                    
					$btn = " Ya soy Usuario ]/sadministrator/login/Key/".$Id_Edu_Almacen."/Request/On/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]]HREF]]btn btn-default dropdown-toggle]}";				
					$btn .= " Quiero Registrarme ]/sadministrator/edu_register/Key/".$Id_Edu_Almacen."/Request/On/Id_Edu_Componente_S/".$Id_Edu_Componente_S."]]HREF]]btn btn-default dropdown-toggle]}";				
					$btn .= " Cancelar ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
					$Button = DCButton($btn, 'botones1', 'sys_form');					
					$Html = DCModalFormMsj("Para ver todas la lecciones del compendio debes registrarte",$Form,$Button,"bg-info-b");
					
				}
				
                DCWrite($Html);
				
                break;	
				
            case "Delete_Componente":
		
		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
				$Id_Edu_Almacen = $Parm["Key"];
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Componente/".$Id_Edu_Componente."/Obj/Edu_Componente_Crud/Key/".$Id_Edu_Almacen."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Deseas eliminar el contenido",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;	
				
            case "Compartir":
		
		        $Id_Edu_Componente = $Parm["Id_Edu_Componente"];
				$Id_Edu_Almacen = $Parm["Key"];
				// $btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Componente/".$Id_Edu_Componente."/Obj/Edu_Componente_Crud/Key/".$Id_Edu_Almacen."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				// $btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
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
						 href="https://www.facebook.com/sharer/sharer.php?u=http://yachai.org/sadministrator/edu_articulo_det/Interface/begin/Request/On/Key/'.$Id_Edu_Almacen.'/Action/Sugerencia">
						 <i class="zmdi zmdi-facebook-box zmdi-hc-2x"></i>
						 </a>
						 
						 <a class="btn btn-googleplus" target="_blank" 
						  href="https://plus.google.com/share?url=http://yachai.org/sadministrator/edu_articulo_det/Interface/begin/Request/On/Key/'.$Id_Edu_Almacen.'/Action/Sugerencia">
						 <i class="zmdi zmdi-google-plus zmdi-hc-2x"></i>
						 </a>	
						 
						 <a class="btn btn-twitter" target="_blank" 
						 href="https://twitter.com/?status=http://yachai.org/sadministrator/edu_articulo_det/Interface/begin/Request/On/Key/'.$Id_Edu_Almacen.'/Action/Sugerencia">
						 <i class="zmdi zmdi-twitter zmdi-hc-2x"></i>
						 </a>
						 
						 <a class="btn btn-linkedin" target="_blank" 
						 href="http://www.linkedin.com/shareArticle?url=http://yachai.org/sadministrator/edu_articulo_det/Interface/begin/Request/On/Key/'.$Id_Edu_Almacen.'/Action/Sugerencia">
						 <i class="zmdi zmdi-linkedin zmdi-hc-2x"></i>
						 </a>	
						 
						 
                        ';
				
			    $Html = DCModalFormMsj("¡ Comparte tu aprendizaje con tus amigos !","",$Form,"bg-info-b");
                DCWrite($Html);
				
                break;	

            case "delete_objeto":
		
		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
		        $Key = $Parm["Key"];
		        $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];

				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Articulo/".$Id_Edu_Articulo."/Obj/Edu_Object/Key/".$Key."]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
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
		'Id_Edu_Almacen' =>  $Parm["Key"],
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
		$Where = ["Id_Edu_Almacen" =>$Parm["Key"], "Id_Edu_Componente" =>$Parm["Id_Edu_Componente_S"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Tot = $Row->Tot;	
	

		$Query = "
			 SELECT EVOR.Id_Edu_Vistas_Objectos_Resumen 
			 FROM edu_vistas_objectos_resumen  EVOR
			 WHERE
			 EVOR.Id_Edu_Componente = :Id_Edu_Componente
			 AND EVOR.Id_Edu_Almacen = :Id_Edu_Almacen
		";	
		$Where = ["Id_Edu_Almacen" =>$Parm["Key"], "Id_Edu_Componente" =>$Parm["Id_Edu_Componente_S"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Id_Edu_Vistas_Objectos_Resumen = $Row->Id_Edu_Vistas_Objectos_Resumen;	
			
		// DCVd($Row);
		if(!empty($Id_Edu_Vistas_Objectos_Resumen)){
			
			$reg = array(
				'Cantidad_Vistas_Componente' => $Tot
			);
			$where = array('Id_Edu_Almacen' => $Parm["Key"], 'Id_Edu_Componente' => $Parm["Id_Edu_Componente_S"]);
			$rg = ClassPDO::DCUpdate('edu_vistas_objectos_resumen', $reg , $where, $Conection,"");
			
		}else{
			
			$data = array(
			'Cantidad_Vistas_Componente' =>  1,
			'Id_Edu_Componente' =>  $Parm["Id_Edu_Componente_S"],
			'Id_Edu_Almacen' =>  $Parm["Key"],
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
		$Id_Edu_Almacen = $Parm["Key"];
		// $Id_Edu_Componente_S = $Parm["Id_Edu_Componente_S"];

		$where = array('Id_Edu_Articulo' =>$Id_Edu_Articulo);
		$rg = ClassPDO::DCDelete('edu_articulo', $where, $Conection);		
		
		$where = array('Id_Edu_Almacen' =>$Id_Edu_Almacen);
		$rg = ClassPDO::DCDelete('edu_almacen', $where, $Conection);
		
		$where = array('Id_Edu_Almacen' =>$Id_Edu_Almacen);
		$rg = ClassPDO::DCDelete('edu_componente', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));


		
	}
	
	
	public function Contenidos_Item($Id_Edu_Almacen,$UrlFile,$Id_Edu_Componente_S,$Id_Suscripcion) {
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
			EC.Id_Edu_Almacen = :Id_Edu_Almacen
			ORDER BY EC.Orden ASC
		";    
		$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];		
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		
		$PanelB = '
		
		<div class="cart" style="">			
		  <table class="table table-hover">
			<tbody>				
		
		';
		$Introduccion = "";
		$DivIntroduccion = "";
		$Num_Orden = "";
		foreach($Rows AS $Field){
			$Count += 1;	
			$Introduccion = $Field->Introduccion;
			
			if( $Introduccion == "SI"){
				// $DivIntroduccion = "<div class='Introduccion' >Introducción</div>";
				$Num_Orden = $DivIntroduccion;					
					
			}else{
				$DivIntroduccion = "";			
				
			}
			
                // Iconos-Contents

				if($Field->Id_Edu_Formato == 3){
				    $Icon = "<i class='zmdi zmdi-collection-video zmdi-hc-fw' ></i>";
				}elseif($Field->Id_Edu_Formato == 2){
				    $Icon = "<i class='zmdi zmdi-collection-text zmdi-hc-fw'> </i>";
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
						
						if($Field->Vista_Sin_Inscripcion == "SI"){	
						
							$btn = "".$Field->Nombre."]" .$UrlFile."/Interface/PanelB/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."]PanelA]]]SinEstilo}";
							$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);
							$PanelB .= $btn;
							
						}else{						
						
							$btn = "".$Field->Nombre."]" .$UrlFile."/Interface/Matricula/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Field->Id_Edu_Componente."]animatedModal5]HXM]]SinEstilo}";
							$btn = DCButton($btn, 'botones1', 'sys_form'.$Count);
							$PanelB .= $btn;
							
						}
						
					
				$PanelB .= '
					</td>
					
					<td class="c-link">
						<div class="btn-group">
						';
						
					$Perfil = Biblioteca::Valida_Perfil();					
					if($Perfil == 1 || $Perfil == 2){
						
						$listMn = "<i class='zmdi zmdi-edit zmdi-hc-fw'></i> Editar [".$UrlFile.$Redirect."/Interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
						// $listMn .= "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear [".$UrlFile.$Redirect."/Interface/Create_Conten/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Key/".$Id_Edu_Almacen."/Id_Edu_Componente_S/".$Id_Edu_Componente_S."[animatedModal5[HXM[{";	
						$listMn .= "<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar [".$UrlFile.$Redirect."/Interface/Delete_Componente/Id_Edu_Componente/".$Field->Id_Edu_Componente."/Key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";	
						$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
						$btnB = DCButton($btn, 'botones1', 'sys_form'.$Count);
						
						
					}else{
						
						$btnB = "";					
					}		
					
					$PanelB .= $btnB;
					
				
				$PanelB .='
						</div>
							
					</td>						
				  </tr>  ';
			  
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
	
	public function Matricula($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Componente_S = $Settings["Id_Edu_Componente_S"];
		$Id_Edu_Almacen = $Settings["Key"];
		
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
				$Return = ClassPDO::DCInsert("suscripcion", $data, $Conection,"");
				
				
				
				Edu_Articulo_Det::Email_Matricula($Id_Edu_Almacen,$Email);
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
											<a href='".$dominio."sadministrator/edu_articulo_det/Interface/begin/Request/On/Key/".$Id_Edu_Almacen."/Action/Sugerencia' style='font-size:12pt;height: 30px;color: white;text-decoration: none;padding: 8px 10px;font-family: arial;' >
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
			$Id_Edu_Almacen = $Settings["Key"];
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