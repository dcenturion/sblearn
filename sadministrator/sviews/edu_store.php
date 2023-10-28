<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Store{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu_store";
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
			
				$layout  = new Layout();
				
				$Redirect = "/REDIRECT/articulo";

				$Query = "
				SELECT 
				AC.Alias_Id
				, AC.Nombre 
				, AC.Icon_Class 
				FROM edu_area_conocimiento AC
				WHERE 
				AC.Alias_Id = :Alias_Id 
				";	
				$Where = ["Alias_Id" => $Parm["theme"]];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Icon_Class = $Row->Icon_Class;	
				$Nombre_Area_Conocimiento = $Row->Nombre;	

				$DCPanelTitle = DCPanelTitle("<span><i class='".$Icon_Class."'></i></span>".$Nombre_Area_Conocimiento,"",$btn);
				
				$Card = Biblioteca::Articulos($Parm,"Areas");
					
	            $theme = $Parm["theme"];
	            $Search = $Parm["search"];
				
				$DirecctionA = $UrlFile."/theme/".$theme."/search/y/Request/On";
				// $DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Articulo/".$Id_Edu_Articulo;
								
				$Combobox = array(
				     array("Id_Edu_Sub_Linea"," SELECT Id_Edu_Sub_Linea AS Id, Nombre AS Name FROM edu_sub_linea ",[])
				     ,array("Id_Edu_Tipo_Categoria"," SELECT Id_Edu_Tipo_Categoria AS Id, Nombre AS Name FROM edu_tipo_categoria ",[])
				     ,array("Id_Edu_Diseno"," SELECT Id_Edu_Diseno AS Id, Nombre AS Name FROM edu_diseno ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array("Buscar",$DirecctionA,"Reporte_A1","Form","Edu_Articulo_Busqueda","","HXMB","data-dismiss")
				);
				
				$JsComboBox = Biblioteca::JsCombo_Box_Filtro();				
		        $Form_Busqueda = BFormVertical("Edu_Articulo_Busqueda",$Class,$Id_Edu_Articulo,$PathImage,$Combobox,$Buttons,"Id_Edu_Articulo");
			    
				$Filtro = DCModalForm("Filtros de Búsqueda",$Form_Busqueda,$Class,"animated bounceInRight");
					
				
			    $Html_Filtro = '
				      <div id="animatedModal3" class="modal" tabindex="-1" role="dialog">
					  '.$Filtro.$JsComboBox.'	
                      </div> ';
					  
					  
			    $Html = '					  
					  <div class="col-sm-12">
						<div class="catalog-products">
						
						  <div class="row gutter-sm"  id="Reporte_A1">
					        '.$Card.'	  
                          </div>	
						  
						  <button class="Btn_Filter" data-target="#animatedModal3" data-toggle="modal">
					       	  Filtros
                          </button>
						  
						  <a class="Btn_Wsp" href="https://api.whatsapp.com/send?phone=51935812741&text=Buen%20dia!,%20Deseo%20mas%20información" target="_blank">
						  <i class="zmdi zmdi-whatsapp zmdi-hc-fw"></i>
                          </a>						  
						  
                        </div>						  
                      </div>						  
				
				';	
				
				$Plugin = DCTablePluginA();
				$Plugin .= DCMarca_Menu("li_Btn_".$Parm["theme"]);               
			    $Contenido = DCPage($DCPanelTitle . $Html_Filtro , $Html .  $Plugin ,"panel panel-default");
			    $Contenido .= Biblioteca::Footer();
				

				if($Parm["request"] == "on"){
					
					$Id_Edu_Sub_Linea = DCPost("Id_Edu_Sub_Linea");
				    // var_dump($Id_Edu_Sub_Linea);					
					if(!empty($Id_Edu_Sub_Linea)){
						
						$Contenido = $Html;
						DCWrite($Contenido);
						
					}else{
						
					    DCWrite($layout->main($Contenido,$datos));						
					}
					

				}else{
					DCWrite($Contenido);			
				}
				
                break;
				
        		
            case "Busqueda":
			
					$Card = Biblioteca::Articulos($Parm,"Areas");
			    	DCWrite($Card);
					
					
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

				
            case "Datos_Modelo":
		
		        $Id_Edu_Tipo_Categoria = $Parm["Id_Edu_Tipo_Categoria"];
				
				$Query = "SELECT 
						  ET.Id_Edu_Modelo
						  , ET.Nombre 
						  FROM edu_modelo ET 
						  WHERE ET.Id_Edu_Tipo_Categoria = :Id_Edu_Tipo_Categoria
						  ";	
				$Where = ["Id_Edu_Tipo_Categoria" => $Id_Edu_Tipo_Categoria];
				
				$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
				$Html ="";
				$Count = 0;
				$Html .= '<option value="seleccione">Seleccione</option>';
				foreach($Rows AS $Field){
					$Count += 1;			
					
					$Html .= '<option value="'.$Field->Id_Edu_Modelo.'">'.$Field->Nombre.'</option>'; 
					
				}			
                DCWrite($Html);
				DCExit();
				
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
				FROM edu_articulo AR
				INNER JOIN edu_almacen EA ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
				INNER JOIN edu_area_conocimiento EAC ON AR.Id_Edu_Area_Conocimiento = EAC.Id_Edu_Area_Conocimiento
				WHERE 
				AR.Estado = :Estado 
				AND ( AR.Nombre ".$OperadorA." :Nombre )
				LIMIT 0,10
			";    
			$Id_Edu_Area_Conocimiento = $Parm["theme"];
			
			$Where = ["Estado"=>"Activo", "Nombre"=>'%'.$Queryd.'%'];
			
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