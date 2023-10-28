<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Articulo{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/articulo";
		$UrlFile_Edu_Tipo_Componente = "/sadministrator/edu_tipo_componente";
		$UrlFile_Edu_Tipo_Estructura = "/sadministrator/edu_tipo_estructura";
		$UrlFile_Edu_Area_Conocimiento = "/sadministrator/edu_area_conocimiento";
		$UrlFile_Edu_Sub_Linea = "/sadministrator/edu_sub_linea";
		$UrlFile_Edu_Formato = "/sadministrator/edu_formato";
		$UrlFile_Edu_Tipo_Privacidad = "/sadministrator/edu-tipo-privacidad";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];
		$Redirect = $Parm["REDIRECT"];

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					
					case "Edu_Articulo_Crud":
					
		                    $Data = array();
							$Data['Brian_Campo'] = "HOLA"; //Cursos						
							// $Data['Id_Edu_Tipo_Componente'] = 6; // Servicios						
				
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
																	
							if(!empty($Redirect)){
								
								$Settings = array();
								$Settings['Url'] = "/sadministrator/".$Redirect."/interface/begin/key/".$Parm["key"]."/Action/Sugerencia";
								$Settings['Screen'] = "ScreenRight";
								$Settings['Type_Send'] = "";
								DCRedirectJS($Settings);
								
							}else{
							
								$Settings["interface"] = "";
								new Articulo($Settings);
							
							}
							DCExit();	
						
						break;	

		                case "Edu_Articulo_Crud_B":
					
		                    $Data = array();
							$Data['Id_Edu_Tipo_Estructura'] = 1; //Cursos						
							$Data['Id_Edu_Tipo_Componente'] = 6; // Servicios						
							// $Data['Id_Edu_Tipo_Componente'] = 6; // Servicios						
							// $Data['Id_Edu_Tipo_Componente'] = 6; // Servicios						
							// $Data['Id_Edu_Tipo_Componente'] = 6; // Servicios						
				
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
																	
							if(!empty($Redirect)){
								
								$Settings = array();
								$Settings['Url'] = "/sadministrator/".$Redirect."/interface/begin/key/".$Parm["key"]."/Action/Sugerencia";
								$Settings['Screen'] = "ScreenRight";
								$Settings['Type_Send'] = "";
								DCRedirectJS($Settings);
								
							}else{
							
								$Settings["interface"] = "";
								new Articulo($Settings);
							
							}
							DCExit();	
						
						break;							
							
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "edu_articulo":
						
						// $this->ObjectDelete($Parm);
						
					    $Perfil_User = $_SESSION['Perfil_User'];
					   
					   
						   if($Entity == 1  ){
							   
							   if($Perfil_User == 1 ){
								   
								   $this->ObjectDelete($Parm);
							   }else{
									DCWrite(Message(" Para eliminar debe comunicarse con el administrador","E"));
							   }
							   
						   }else{
							     $this->ObjectDelete($Parm);
						   }
						
						DCCloseModal();		
						
						
		
						$Settings["interface"] = "";
					    new Articulo($Settings);
						DCExit();							
						
						break;	
						
				}	
				
                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":

                break;				
        }
		
		
		
        switch ($interface) {
            case "":
			
				$layout  = new Layout();
				
				$Redirect = "/REDIRECT/articulo";
				
				$listMn = "<i class='icon-chevron-right'></i> Tipo de Componente [".$UrlFile_Edu_Tipo_Componente.$Redirect."/interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Tipo de Estructura [".$UrlFile_Edu_Tipo_Estructura.$Redirect."/interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Area de Conocimiento [".$UrlFile_Edu_Area_Conocimiento.$Redirect."/interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Sub Línea [".$UrlFile_Edu_Sub_Linea.$Redirect."/interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Formatos de Archivo [".$UrlFile_Edu_Formato.$Redirect."/interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Tipo de Privacidad [".$UrlFile_Edu_Tipo_Privacidad.$Redirect."/interface/List[animatedModal5[HXM[{";
							
				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Compendio ]" .$UrlFile."/interface/Create]animatedModal5]HXM]]btn btn-primary}";
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle('<span class="menu-icon"><i class="zmdi zmdi-balance zmdi-hc-fw"></i></span>BIBLIOTECA',"",$btn);
					
				$urlLinkB = "";
				$Pestanas = Biblioteca::Tabs(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]Marca"
				,"".$urlLinkB."]","".$urlLinkB."]",""));		
				
				
				
				
				$Query = "SELECT count(AR.Id_Edu_Articulo) AS Total 
				FROM edu_articulo AR
				INNER JOIN edu_almacen EA ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
				WHERE AR.Entity = :Entity
				";	
				$Where = ["Entity"=>$Entity];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);
				
				$totalArticulos = $Row->Total;
				$totalArticulos_x_pagina=20;

				$paginas = ceil($totalArticulos/$totalArticulos_x_pagina);

				$paginaActual    = $_GET['pagina'];
				$paginaAnterior  = $paginaActual - 1;
				$paginaSiguiente = $paginaActual + 1;

				$desdePaginador = ($paginaActual-1)*$totalArticulos_x_pagina;	

				
				
				
				$Card = Biblioteca::Articulos($Parm,"Creacion",$desdePaginador  , $totalArticulos_x_pagina);
				
						
			    $Html = '
					  <div class="col-md-12">
						<div class="catalog-products">
						  <div class="row gutter-sm">
					        '.$Card.'	  
                          </div>	
						    <div>
								';
			    $Html .= Index_Paginador($paginaAnterior,$paginaActual,$paginas,$paginaSiguiente);
				
				$Html .='
				           </div>
				        </div>						  
                      </div>						  
				
				';	
				
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Html .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;
				

            case "Demo":
			
				$layout  = new Layout();
				
				$Redirect = "/REDIRECT/articulo";
				
				$listMn = "<i class='icon-chevron-right'></i> Tipo de Componente [".$UrlFile_Edu_Tipo_Componente.$Redirect."/interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Tipo de Estructura [".$UrlFile_Edu_Tipo_Estructura.$Redirect."/interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Area de Conocimiento [".$UrlFile_Edu_Area_Conocimiento.$Redirect."/interface/List[animatedModal5[HXM[{";
							
				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Compendio ]" .$UrlFile."/interface/Create]animatedModal5]HXM]]btn btn-primary}";
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle('<span class="menu-icon"><i class="zmdi zmdi-balance zmdi-hc-fw"></i></span>BIBLIOTECA',"",$btn);
					
				$urlLinkB = "";
				$Pestanas = Biblioteca::Tabs(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]","".$urlLinkB."]Marca",""));		
				
				
				
			    $Html = "<iframe width='100%' height='600px' scrolling='no' src='https://www.goconqr.com/es/p/983643-Manejo-del-estr-s-y-productividad-personal-mind_maps?frame=true' style='border: 1px solid #ccc' allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>
				";
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Html .  $Plugin ,"panel panel-default");
				
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;
        		
            case "Create":
			     
				 
	            $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
	            $REDIRECT = $Parm["REDIRECT"];
	            $key = $Parm["key"];
				if(!empty($REDIRECT)){
					
					$Url_REDIRECT = "/REDIRECT/".$REDIRECT."/key/".$key;
					
				}
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Articulo_Crud/Id_Edu_Articulo/".$Id_Edu_Articulo.$Url_REDIRECT;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Articulo/".$Id_Edu_Articulo;
				
				if(!empty($Id_Edu_Articulo)){
					
				    $Name_Interface = "Editar Compendio ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
					
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Articulo_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Compendio ";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear ";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Articulo_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Componente"," SELECT Id_Edu_Tipo_Componente AS Id, Nombre AS Name FROM edu_tipo_componente ",[])
				     ,array("Id_Edu_Tipo_Estructura"," SELECT Id_Edu_Tipo_Estructura AS Id, Nombre AS Name FROM edu_tipo_estructura ",[])
				     ,array("Id_Edu_Area_Conocimiento"," SELECT Id_Edu_Area_Conocimiento AS Id, Nombre AS Name FROM edu_area_conocimiento ",[])
				     ,array("Id_Edu_Sub_Linea"," SELECT Id_Edu_Sub_Linea AS Id, Nombre AS Name FROM edu_sub_linea ",[])
				     ,array("Id_Edu_Tipo_Privacidad"," SELECT Id_Edu_Tipo_Privacidad AS Id, Nombre AS Name FROM edu_tipo_privacidad ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Articulo_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Articulo_Crud",$Class,$Id_Edu_Articulo,$PathImage,$Combobox,$Buttons,"Id_Edu_Articulo");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
				
				$Js = Biblioteca::JsComboBoxTipoEstructura();
				
                DCWrite($Html.$Js);
                DCExit();
                break;
				
       			
            case "DeleteMassive":
		
		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Articulo/".$Id_Edu_Articulo."/Obj/edu_articulo]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;	

       			
            case "Datos_Tipo_Estructura":
		
		        $Id_Edu_Tipo_Componente = $Parm["Id_Edu_Tipo_Componente"];
				
				$Query = "SELECT 
						  ET.Id_Edu_Tipo_Estructura
						  , ET.Nombre 
						  FROM edu_tipo_estructura ET 
						  WHERE ET.Id_Edu_Tipo_Componente = :Id_Edu_Tipo_Componente
						  ";	
				$Where = ["Id_Edu_Tipo_Componente" => $Id_Edu_Tipo_Componente];
				
				$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
				$Html ="";
				$Count = 0;
				foreach($Rows AS $Field){
					$Count += 1;			
					
					$Html .= '<option value="'.$Field->Id_Edu_Tipo_Estructura.'">'.$Field->Nombre.'</option>'; 
					
				}			
                DCWrite($Html);
				DCExit();
				
                break;	
				
				
            case "Datos_Sub_Linea":
		
		        $Id_Edu_Area_Conocimiento = $Parm["Id_Edu_Area_Conocimiento"];
				
				$Query = "SELECT 
						  ES.Id_Edu_Sub_Linea
						  , ES.Nombre 
						  FROM  edu_sub_linea ES 
						  WHERE ES.Id_Edu_Area_Conocimiento = :Id_Edu_Area_Conocimiento
						  ";	
				$Where = ["Id_Edu_Area_Conocimiento" => $Id_Edu_Area_Conocimiento];
				
				$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
				$Html ="";
				$Count = 0;
				foreach($Rows AS $Field){
					$Count += 1;			
					
					$Html .= '<option value="'.$Field->Id_Edu_Sub_Linea.'">'.$Field->Nombre.'</option>'; 
					
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
}