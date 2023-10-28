<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Publi_Setting{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/publi_setting";
		$UrlFile_publi_setting_det = "/sadministrator/publi_setting_det";
		$UrlFile_Publicidad_Tipo = "/sadministrator/edu_publicidad_tipo_alcance";
		$UrlFile_Pedido = "/sadministrator/pedido";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];
		$REDIRECT = $Parm["REDIRECT"];

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					case "Edu_Publicidad_Crud":
					
		                    $Data = array();
		
						    DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Publicidad"],"Id_Edu_Publicidad",$Data);

                      					
                            
 							if($REDIRECT == "publi_setting_det"){
								
									$Settings = array();
									$Settings['Url'] = "/sadministrator/publi_setting_det/Id_Edu_Publicidad/".$Parm["Id_Edu_Publicidad"];
									$Settings['Screen'] = "ScreenRight";
									$Settings['Type_Send'] = "";
									DCRedirectJS($Settings);
							
								
							}else{
							
									$Settings["Interface"] = "";
									new Publi_Setting($Settings);
									
							}

										
							DCExit();	
						
						break;							
							
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "Edu_Publicidad_Crud":
						
						$this->ObjectDelete($Parm);
						
						DCCloseModal();		
						$Settings["Interface"] = "";
					    new Publi_Setting($Settings);
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
							
		        $Redirect = "/REDIRECT/publi_setting";										
				$layout  = new Layout();
				$listMn = "<i class='icon-chevron-right'></i> Tipo de Alcance [".$UrlFile_Publicidad_Tipo.$Redirect."/Interface/List[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Editar Áreas[".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Promoción ]" .$UrlFile."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("SITIO","Administración del sitio",$btn);

				$Pestanas = SetttingsSite::Tabs(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]","".$urlLinkB."]Marca",""));					
			

				$Query = "
				    SELECT EP.Id_Edu_Publicidad AS CodigoLink
					, EP.Nombre
					, EP.Estado
					, ETA.Nombre AS 'Tipo Alcance'
					, EP.Date_Time_Creation 
					FROM edu_publicidad EP
					INNER JOIN edu_publicidad_tipo_alcance ETA ON ETA.Id_Edu_Publicidad_Tipo_Alcance = EP.Id_Edu_Publicidad_Tipo_Alcance
					
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Publicidad';
				$Link = $UrlFile_publi_setting_det."";
				$Screen = 'ScreenRight';
				$where = ["Entidad"=>$Entidad];
				$Table = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','HREF');
				 				
				$Plugin = DCTablePluginA();				
				
				$Contenido = DCPage($DCPanelTitle,$Pestanas ."<BR>".$Table.$Plugin);
				
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				exit();
				
                break;
				
        		
            case "Create":
			     
				 
	            $Id_Edu_Publicidad = $Parm["Id_Edu_Publicidad"];
	            $REDIRECT = $Parm["REDIRECT"];
				if(!empty($REDIRECT)){
					$Url_Redirect = "/REDIRECT/".$REDIRECT;
				}
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Publicidad_Crud/Id_Edu_Publicidad/".$Id_Edu_Publicidad.$Url_Redirect;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Publicidad/".$Id_Edu_Publicidad;
				
				if(!empty($Id_Edu_Publicidad)){
				    $Name_Interface = "Editar Promoción";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Publicidad_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Promoción";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				$Combobox = array(
				     array("Id_Edu_Publicidad_Tipo_Alcance"," SELECT Id_Edu_Publicidad_Tipo_Alcance AS Id, Nombre AS Name FROM edu_publicidad_tipo_alcance ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/publicidad")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Publicidad_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Publicidad_Crud",$Class,$Id_Edu_Publicidad,$PathImage,$Combobox,$Buttons,"Id_Edu_Publicidad");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                break;
				
       			
            case "DeleteMassive":
		
		        $Id_Edu_Publicidad = $Parm["Id_Edu_Publicidad"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Publicidad/".$Id_Edu_Publicidad."/Obj/Edu_Publicidad_Crud]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;				
				
            case "Delete":
		
		        $Id_Edu_Publicidad = $Parm["Id_Edu_Publicidad"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Publicidad/".$Id_Edu_Publicidad."/Obj/Edu_Publicidad_Crud]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Promoción",$Form,$Button,"bg-info");
                DCWrite($Html);
                DCExit();				
                break;				
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Publicidad = $Settings["Id_Edu_Publicidad"];
			
		$where = array('Id_Edu_Publicidad' =>$Id_Edu_Publicidad);
		$rg = ClassPDO::DCDelete('edu_publicidad', $where, $Conection);
		
		$where = array('Id_Edu_Publicidad' =>$Id_Edu_Publicidad);
		$rg = ClassPDO::DCDelete('edu_publicidad_det', $where, $Conection);		
		
		DCWrite(Message("Process executed correctly","C"));
						
	}	
}