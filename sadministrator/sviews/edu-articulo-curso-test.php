<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
	
$DCTimeHour = DCTimeHour();
$Conection = Conection();
		// echo "archivo final";
		// exit();
class Edu_Articulo_Curso_Test{

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
            case "":
			// echo "prueba";
			// exit();
			
				$layout  = new Layout();
				$Redirect = "/REDIRECT/articulo";
				$DCPanelTitle = DCPanelTitle('<span class="menu-icon"><i class="zmdi zmdi-balance zmdi-hc-fw"></i></span>BIBLIOTECA',"",$btn,"");
					
				$urlLinkB = "";
				$Pestanas = Biblioteca::Tabs(array(
				"".$urlLinkB."]Marca"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]","".$urlLinkB."]",""));	
				$User = $_SESSION['User'];
				$DCDate = DCDate(); 
				
				// var_dump($DCDate);
				
				$Query = "
					SELECT 
					UM.Id_Perfil_Educacion,SC.Producto_Origen
					FROM user_miembro UM
					INNER JOIN suscripcion SC ON UM.Id_User_Miembro=SC.Id_User
					WHERE 
					UM.Id_User_Miembro=:Id_User AND SC.Producto_Origen =:Producto_Origen
				";	
				$Where = ["Id_User" => $User, "Producto_Origen" => "CURSO"];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Perfil_Educacion = $Row->Id_Perfil_Educacion;
				$Producto_Origen = $Row->Producto_Origen;


				if($Id_Perfil_Educacion == 4){
					$Card = Biblioteca::Articulos($Parm,"Membresia");
				}else{
					// echo $Producto_Origen;
					if($Producto_Origen!="Programa"){
						$Card = Biblioteca::Articulos($Parm,"En_Curso");
					}



					
					//$Card = Biblioteca::Articulos($Parm,"Programacion");
				}
				
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