<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Programa{


	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-articulo-curso";
		$UrlFile_Edu_Tipo_Componente = "/sadministrator/edu_tipo_componente";
		$UrlFile_Edu_Tipo_Estructura = "/sadministrator/edu_tipo_estructura";
		$UrlFile_Edu_Area_Conocimiento = "/sadministrator/edu_area_conocimiento";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					case "Edu_Articulo_Crud":
					
		                    $Data = array();
							$Data['Id_Edu_Tipo_Estructura'] = 1; //Cursos						
							$Data['Id_Edu_Tipo_Componente'] = 6; // Servicios						
				
						    DCCloseModal();									
							$Id_Edu_Articulo = DCSave($Obj,$Conection,$Parm["Id_Edu_Articulo"],"Id_Edu_Articulo",$Data); 
							
							// DCVd($Id_Edu_Articulo["lastInsertId"]);
							$data = array(
							'Id_Edu_Articulo' => $Id_Edu_Articulo["lastInsertId"],
							'Fecha_Hora_Ingreso_Almacen' => $DCTimeHour,
							'Entity' => $Entity,
							'Id_User_Creation' => $User,
							'Id_User_Update' => $User,
							);
							$Return = ClassPDO::DCInsert("edu_almacen", $data, $Conection);	
																	
												
							$Settings["Interface"] = "";
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Articulo($Settings);
							DCExit();	
						
						break;							
							
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "edu_articulo":
						
						$this->ObjectDelete($Parm);
						
						DCCloseModal();		
						$Settings["Interface"] = "";
					    new Articulo($Settings);
						DCExit();							
						
						break;	
						
				}	
				
                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":

						$this->Search($Parm);
						DCExit();						
                break;				
        }
		
		
		
        switch ($Interface) {
            case "begin":
            	$Url_Amigable = $Parm["programa"];
            	$Query = "
					SELECT  PC.Id_Programa_Cab,PC.Nombre,PC.Descripcion
					FROM programa_alumno PA
					INNER JOIN programa_cab PC ON PA.Id_Programa_Cab=PC.Id_Programa_Cab
					WHERE 
					PA.Id_User=:Id_User AND PC.Url=:Url
				";	
				$Where = ["Id_User" => $User,"Url"=>$Url_Amigable];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre = $Row->Nombre;$Descripcion = $Row->Descripcion;
				$Id_Programa_Cab=$Row->Id_Programa_Cab;
			
			
			    $DCDate = DCDate();
				
				$layout  = new Layout();
				$Redirect = "/REDIRECT/articulo";
				$DCPanelTitle = DCPanelTitle('<span class="menu-icon"><i class="zmdi zmdi-balance zmdi-hc-fw"></i></span>'.$Nombre,"",$btn,"");
					
				$urlLinkB = "";
				$Pestanas = Biblioteca::Tabs_Programa(array(
				"".$urlLinkB."]Marca"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]","".$urlLinkB."]",""),$Url_Amigable);	
				$User = $_SESSION['User'];
				//var_dump($User);
				$Query = "
						SELECT AR.Id_Edu_Articulo
								,PD.Id_Edu_Almacen
								,AR.Nombre
								, AR.Url_Amigable
								, AR.Estado
								, AR.Imagen
								, AR.Imagen_Presentacion				
								, AR.Id_Edu_Tipo_Estructura
								, AR.Date_Time_Creation
								, EAC.Nombre AS Categoria  
								, AR.Fecha_Publicacion
						FROM  suscripcion SUS 
						INNER JOIN programa_det PD ON SUS.Id_Edu_Almacen=PD.Id_Edu_Almacen
   			    		INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
						INNER JOIN edu_articulo AR ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
						INNER JOIN edu_area_conocimiento EAC ON AR.Id_Edu_Area_Conocimiento = EAC.Id_Edu_Area_Conocimiento
						WHERE 
						SUS.Id_User = :Id_User 
						AND SUS.Visibilidad=:Estado 
						AND  SUS.Entity =:Entity AND PD.Id_Programa_Cab=:Id_Programa_Cab
						AND  SUS.Fecha_Fin  >= :Fecha_Fin 
						
						ORDER BY AR.Date_Time_Creation DESC
						";    
					$Where = ["Id_User"=>$User,"Entity"=>$Entity,"Estado"=>"Activo","Id_Programa_Cab"=>$Id_Programa_Cab,"Fecha_Fin"=>$DCDate];
					$Row = ClassPdo::DCRows($Query,$Where,$Conection);
					$Card = '';
					$Hola="";
					foreach($Row AS $Field){
						$almacen=$Field->Id_Edu_Almacen;
						
						$Card .= '<div class="col-md-3">
									<div class="c-product" style="Position:relative;">
										<a class="cp-img" style="background-image: url(/sadministrator/simages/articulo/'.$Field->Imagen.')" href="/sadministrator/edu-articulo-det/interface/begin/request/on/key/'.$Field->Id_Edu_Almacen.'/action/sugerencia">
										</a>
									  <div class="cp-content">
										  <div class="cp-title">
											'.$Field->Nombre.'
																					
										  </div>
									  </div>								
								  </div>
								  </div> 
								';

									



					}
				
			    $Html = '
					  <div class="col-md-12">
						<div class="catalog-products">
						  <div class="row gutter-sm">
					        '.$Card.'</div> 
                          </div>	
                        </div>						  
                      </div>						  
				
				';	
				
				$Plugin = DCTablePluginA();
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Html .  $Plugin ,"panel panel-default");
				$Contenido .= '';
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
            break;
            case "info":
            	$Url_Amigable = $Parm["programa"];
            	$Query = "
					SELECT  PC.Id_Programa_Cab,PC.Nombre,PC.Descripcion
					FROM programa_alumno PA
					INNER JOIN programa_cab PC ON PA.Id_Programa_Cab=PC.Id_Programa_Cab
					WHERE 
					PA.Id_User=:Id_User AND PC.Url=:Url
				";	
				$Where = ["Id_User" => $User,"Url"=>$Url_Amigable];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre = $Row->Nombre;$Descripcion = $Row->Descripcion;
				$Id_Programa_Cab=$Row->Id_Programa_Cab;
			
				$layout  = new Layout();
				$Redirect = "/REDIRECT/articulo";
				$DCPanelTitle = DCPanelTitle('<span class="menu-icon"><i class="zmdi zmdi-balance zmdi-hc-fw"></i></span>'.$Nombre,"",$btn,"");
					
				$urlLinkB = "";
				$Pestanas = Biblioteca::Tabs_Programa(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]Marca"
				,"".$urlLinkB."]","".$urlLinkB."]",""),$Url_Amigable);	
					$Card = '
					<div class="col-md-8">
					<h1 class="panel-title">DESCRIPCIÓN DE PROGRAMA</h1>
								'.$Descripcion.'
							</div>';
					
				
				
			    $Html = '
					  <div class="col-md-12">
						<div class="catalog-products">
						  <div class="row gutter-sm">
					        '.$Card.'	   
                          </div>	
                        </div>						  
                      </div>						  
				
				';	
				
				$Plugin = DCTablePluginA();
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Html .  $Plugin ,"panel panel-default");
				$Contenido .= '';
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
            break;
            
				
        		
            case "Create":
			     
				 
	            $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Articulo_Crud/Id_Edu_Articulo/".$Id_Edu_Articulo;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Articulo/".$Id_Edu_Articulo;
				
				if(!empty($Id_Edu_Articulo)){
					
				    $Name_Interface = "Editar Programa";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Articulo_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Programa";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Articulo_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Publicacion_Blog_Categoria"," SELECT Id_Publicacion_Blog_Categoria AS Id, Nombre AS Name FROM publicacion_blog_categoria ",[])
				     ,array("Id_Edu_Area_Conocimiento"," SELECT Id_Edu_Area_Conocimiento AS Id, Nombre AS Name FROM edu_area_conocimiento ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Articulo_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Articulo_Crud",$Class,$Id_Edu_Articulo,$PathImage,$Combobox,$Buttons,"Id_Edu_Articulo");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                break;
				
       			
            case "DeleteMassive":
		
		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Articulo/".$Id_Edu_Articulo."/Obj/edu_articulo]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;				
				
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Articulo = $Settings["Id_Edu_Articulo"];
		
		$where = array('Id_Edu_Articulo' =>$Id_Edu_Articulo);
		$rg = ClassPDO::DCDelete('edu_articulo', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}	

	public function Search($Parm) {
       	global $Conection, $DCTimeHour,$NameTable;

            $Queryd = $Parm["Qr"];
			$User = $_SESSION['User'];
			
			if(empty($Queryd)){
			    $OperadorA = "<>";
				$Queryd = "8b8b8b8b8b8b8b8b8bb8b8b";
			}else{
				$OperadorA = "LIKE";
			}
							
			$Query = "
				SELECT 
				AR.Id_Edu_Articulo AS CodigoLink
				, EA.Id_Edu_Almacen
				, AR.Nombre
				, AR.Estado
				, AR.Imagen
				, AR.Date_Time_Creation
				, EAC.Nombre AS Categoria  
				FROM suscripcion SC
				INNER JOIN edu_almacen EA ON SC.Id_Edu_Almacen = EA.Id_Edu_Almacen
				INNER JOIN edu_articulo AR ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
				INNER JOIN edu_area_conocimiento EAC ON AR.Id_Edu_Area_Conocimiento = EAC.Id_Edu_Area_Conocimiento
				WHERE 
				SC.Id_User = :Id_User
				AND ( AR.Nombre ".$OperadorA." :Nombre )				
			";  
			
			$Where = [
			"Nombre" =>'%'.$Queryd.'%',
			"Id_User" => $User
			];
			
			$html ="";	
			$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
			
			$cont = 0;
			foreach ($Rows as $reg) {
				
				$con +=1;	
				
				$html .= "<div id='Item-Search-".$con."' class='item-search' onclick=selectionItem('Item-Search-".$con."'); >".$reg->Nombre."</div>";
			}			
			
		    DCWrite($html);			
	}	
	
	
}