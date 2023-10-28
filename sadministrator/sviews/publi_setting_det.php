<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Publi_Setting_Det{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/publi_setting_det";
		$UrlFile_Publicidad = "/sadministrator/publi_setting";
		$UrlFile_Publicidad_Tipo = "/sadministrator/edu_publicidad_tipo_alcance";
		$UrlFile_Pedido = "/sadministrator/pedido";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					case "Edu_Publicidad_Det_Crud":
					
		                    $Data = array();
							$Data['Id_Edu_Publicidad'] = $Parm["Id_Edu_Publicidad"];						
							// $Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Publicidad_Det"],"Id_Edu_Publicidad_Det",$Data);  
							
							$Settings["Interface"] = "";
							$Settings["Id_Edu_Publicidad"] = $Parm["Id_Edu_Publicidad"];
							new Publi_Setting_Det($Settings);
							DCExit();	
						
						break;							
							
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "Edu_Publicidad_Det_Crud":
						
						$this->ObjectDeleteBlock($Parm);
						
						DCCloseModal();		
						$Settings["Interface"] = "";
						$Settings["Id_Edu_Publicidad"] = $Parm["Id_Edu_Publicidad"];						
					    new Publi_Setting_Det($Settings);
						DCExit();							
						
						break;	
						
				}	
				
                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":

                break;				
        }
		
		
		
        switch ($Interface) {
            case "":

				$Query = "
				    SELECT EP.Id_Edu_Publicidad AS CodigoLink
					, EP.Nombre, ETA.Nombre AS 'Tipo Alcance'
					, EP.Date_Time_Creation 
					, EP.Imagen 
					FROM edu_publicidad EP
					INNER JOIN edu_publicidad_tipo_alcance ETA ON ETA.Id_Edu_Publicidad_Tipo_Alcance = EP.Id_Edu_Publicidad_Tipo_Alcance
					WHERE EP.Id_Edu_Publicidad = :Id_Edu_Publicidad
				";  
				$Where = ["Id_Edu_Publicidad"=>$Parm["Id_Edu_Publicidad"]];
				$Datos_Publicidad = ClassPDO::DCRow($Query,$Where ,$Conection);
		
		        $Redirect = "/REDIRECT/publi_setting_det";										
				$layout  = new Layout();
				$listMn = "<i class='icon-chevron-right'></i> Editar Promoción[".$UrlFile_Publicidad.$Redirect."/Interface/Create/Id_Edu_Publicidad/".$Parm["Id_Edu_Publicidad"]."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Eliminar Promoción[".$UrlFile_Publicidad.$Redirect."/Interface/Delete/Id_Edu_Publicidad/".$Parm["Id_Edu_Publicidad"]."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Eliminar Alcance[".$UrlFile."/Interface/DeleteMassive/Id_Edu_Publicidad/".$Parm["Id_Edu_Publicidad"]."[animatedModal5[HXM[{";
				
				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Alcance ]" .$UrlFile."/Interface/Create/Id_Edu_Publicidad/".$Parm["Id_Edu_Publicidad"]."]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("PROMOCIÓN | ".$Datos_Publicidad->Nombre,"Alcance",$btn);
				
				$Query = "
				    SELECT 
					EPD.Id_Edu_Publicidad_Det AS CodigoLink
					, AC.Nombre AS 'Área de conocimiento'
					, EPD.Cantidad_Minima AS 'Cantidad Mínima'
					, EPD.Porcentaje_Descuento AS 'Porcentaje Descuento'					
					, EPD.Date_Time_Creation 
					, ET.Nombre AS Tipo_Estructura 
					FROM edu_publicidad_det EPD
					INNER JOIN edu_area_conocimiento AC ON AC.Id_Edu_Area_Conocimiento = EPD.Id_Edu_Area_Conocimiento
					INNER JOIN edu_tipo_estructura ET ON ET.Id_Edu_Tipo_Estructura = EPD.Id_Edu_Tipo_Estructura
					WHERE EPD.Id_Edu_Publicidad = :Id_Edu_Publicidad
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Publicidad_Det';
				$Link = $UrlFile."/Interface/Create/Id_Edu_Publicidad/".$Parm["Id_Edu_Publicidad"];
				$Screen = 'animatedModal5';
				$Where = ["Id_Edu_Publicidad"=>$Parm["Id_Edu_Publicidad"]];
				$Table = DCDataGrid('', $Query, $Where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','');
				 				
				$Plugin = DCTablePluginA();				
				
				
				$Imagen_Banner = "
				        <div id='Screen_Banner' style='position:unset;bottom:0px;'>
				                <a 
								class='Screen_Banner_Img_Vacio' 
								style='background-image: url(/sadministrator/simages/promocion/".$Datos_Publicidad->Imagen.")' 
								></a>
						</div>
				";
				
				$Contenido = DCPage($DCPanelTitle.$Imagen_Banner,$Table.$Plugin);
				
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				exit();
				
                break;
				
        		
            case "Create":
			     
				 
	            $Id_Edu_Publicidad_Det = $Parm["Id_Edu_Publicidad_Det"];
	            $Id_Edu_Publicidad = $Parm["Id_Edu_Publicidad"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Publicidad_Det_Crud/Id_Edu_Publicidad/".$Id_Edu_Publicidad."/Id_Edu_Publicidad_Det/".$Id_Edu_Publicidad_Det;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Publicidad_Det/".$Id_Edu_Publicidad_Det;
				
				if(!empty($Id_Edu_Publicidad_Det)){
				    $Name_Interface = "Editar Promoción";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Publicidad_Det_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Promoción";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				$Select_Almacen = " 
				         SELECT EAL.Id_Edu_Almacen AS Id, EAR.Nombre AS Name 
				         FROM edu_articulo EAR
						 INNER JOIN edu_almacen  EAL ON  EAL.Id_Edu_Articulo = EAR.Id_Edu_Articulo
						 ";
				
				$Combobox = array(
				    array("Id_Edu_Area_Conocimiento"," SELECT Id_Edu_Area_Conocimiento AS Id, Nombre AS Name FROM edu_area_conocimiento ",[])
				    ,array("Id_Edu_Almacen",$Select_Almacen,[])
				    ,array("Id_Edu_Tipo_Estructura"," SELECT Id_Edu_Tipo_Estructura AS Id, Nombre AS Name FROM edu_tipo_estructura ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Publicidad_Det_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Publicidad_Det_Crud",$Class,$Id_Edu_Publicidad_Det,$PathImage,$Combobox,$Buttons,"Id_Edu_Publicidad_Det");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                break;
				
       			
            case "DeleteMassive":
		
		        $Id_Edu_Publicidad = $Parm["Id_Edu_Publicidad"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Obj/Edu_Publicidad_Det_Crud/Id_Edu_Publicidad/".$Id_Edu_Publicidad."]ScreenRight]FORM]warehouse]btn btn-default dropdown-toggle]}";					
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;				
				
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Publicidad_Det = $Settings["Id_Edu_Publicidad_Det"];
			
		$where = array('Id_Edu_Publicidad_Det' =>$Id_Edu_Publicidad_Det);
		$rg = ClassPDO::DCDelete('perfil', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}	
	
	public function ObjectDeleteBlock($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Publicidad_Det = DCPost("ky");
		$columnas='';
		for ($j = 0; $j < count($Id_Edu_Publicidad_Det); $j++) {
					

			$where = array('Id_Edu_Publicidad_Det' =>$Id_Edu_Publicidad_Det[$j]);
			$rg = ClassPDO::DCDelete('edu_publicidad_det', $where, $Conection);
			
		}

		DCWrite(Message("Process executed correctly","C"));
						
	}		
}