<?php
require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Banner{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-banner";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Edu_Banner_Crud":
					        
							
							$Seccion_Pagina = $Parm["Seccion_Pagina"];
                            $Data = array();
							$Data['Seccion_Pagina'] = $Seccion_Pagina;								
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Banner"],"Id_Edu_Banner",$Data);  
							
							if($Seccion_Pagina=="Inicio"){
							    $Settings["Interface"] = "List";								
							}
							if($Seccion_Pagina=="Para_Empresa"){
							    $Settings["Interface"] = "List_Banner_Capacitacion_Empresa";								
							}

							new Edu_Banner($Settings);
							DCExit();	
					
						break;	
					case "Form_Suscripcion":
						// $this->Form_Suscripcion($Transaction);
						break;							
				}			
				
                break;
            case "CHANGE":

				switch ($Obj) {
					case "Obj_Object":
						
						$Id_Object = $this->ObjectChange($Parm);
						
						if($Redirect == "det_admin_object"){
							$Settings = array();
							$Settings['Url'] = "/sadministrator/det_admin_object/interface/Details/Id_Object/".$Id_Object;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "";
							DCRedirectJS($Settings);
						}
						
					
						break;	
					case "Form_Suscripcion":
						// $this->Form_Suscripcion($Transaction);
						break;							
				}
				

                break;
				
            case "DELETE":
			
				switch ($Obj) {
					case "Edu_Banner_Crud":
						
						$this->ObjectDelete($Parm);
									
						$Settings["Interface"] = "List";
						$Settings["REDIRECT"] = $Redirect;
						// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
						new Edu_Banner($Settings);
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
        
            case "List":
			
				$Name_Interface = "Listado de Banners";	
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/Interface/Create]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT PB.Id_Edu_Banner AS CodigoLink, PB.Frase, PB.Estado FROM edu_banner PB
					WHERE PB.Entity =:Entity
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Banner';
				$Link = $UrlFile."/Interface/Create";
				$Screen = 'animatedModal5';
				$where = ["Entity"=>$Entity];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;		
				

            case "Create":
			
				$btn .= "Atrás]" .$UrlFile."/Interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Banner = $Parm["Id_Edu_Banner"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Banner_Crud/Seccion_Pagina/Inicio/Id_Edu_Banner/".$Id_Edu_Banner;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Banner/".$Id_Edu_Banner;
				
				if(!empty($Id_Edu_Banner)){
				    $Name_Interface = "Editar Tipo de Estructura";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Banner_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Tipo de Estructura";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Componente"," SELECT Id_Edu_Tipo_Componente AS Id, Nombre AS Name FROM edu_tipo_componente ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Banner_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Banner_Crud",$Class,$Id_Edu_Banner,$PathImage,$Combobox,$Buttons,"Id_Edu_Banner");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "DeleteMassive":
		
		        $Id_Edu_Banner = $Parm["Id_Edu_Banner"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Banner/".$Id_Edu_Banner."/Obj/Edu_Banner_Crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/List]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;	


            case "List_Banner_Capacitacion_Empresa":
			
				$Name_Interface = "Listado de Banners";	
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/Interface/Create_Para_Empresa]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT PB.Id_Edu_Banner AS CodigoLink, PB.Frase, PB.Estado FROM edu_banner PB
					WHERE PB.Seccion_Pagina =:Seccion_Pagina
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Banner';
				$Link = $UrlFile."/Interface/Create_Para_Empresa";
				$Screen = 'animatedModal5';
				$where = ["Seccion_Pagina"=>"Para_Empresa"];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;		
				

            case "Create_Para_Empresa":
			
				$btn .= "Atrás]" .$UrlFile."/Interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Banner = $Parm["Id_Edu_Banner"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Banner_Crud/Seccion_Pagina/Para_Empresa/Id_Edu_Banner/".$Id_Edu_Banner;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Banner/".$Id_Edu_Banner;
				
				if(!empty($Id_Edu_Banner)){
				    $Name_Interface = "Editar Banner ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Banner_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Banner ";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Componente"," SELECT Id_Edu_Tipo_Componente AS Id, Nombre AS Name FROM edu_tipo_componente ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Banner_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Banner_Crud",$Class,$Id_Edu_Banner,$PathImage,$Combobox,$Buttons,"Id_Edu_Banner");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;	
				
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Banner = $Settings["Id_Edu_Banner"];
			
		$where = array('Id_Edu_Banner' =>$Id_Edu_Banner);
		$rg = ClassPDO::DCDelete('edu_banner', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		
	
	
	
}