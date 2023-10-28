<?php
require_once('./sviews/layout.php');
require_once(dirname(__FILE__).'/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Inhouse_Empresa{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/inhouse-empresa";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Inhouse_Empresa_Crud":
					
                            $Data = array();
							$Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];						
							$Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Inhouse_Empresa"],"Id_Inhouse_Empresa",$Data);  
							
							$Settings["interface"] = "List";
							// $Settings["REDIRECT"] = $Redirect;
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Inhouse_Empresa($Settings);
							DCExit();	
							
						// if($Redirect == "articulo"){
							// $Settings = array();
							// $Settings['Url'] = "/sadministrator/articulo";
							// $Settings['Screen'] = "ScreenRight";
							// $Settings['Type_Send'] = "";
							// DCRedirectJS($Settings);
						// }
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
					case "Inhouse_Empresa_Crud":
						
						$this->ObjectDelete($Parm);
									
						$Settings["interface"] = "List";
						// $Settings["REDIRECT"] = $Redirect;
						// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
						new Inhouse_Empresa($Settings);
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
		
				$Name_Interface = "Listado de Empresas";	
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/interface/Create]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT PB.Id_Inhouse_Empresa AS CodigoLink, PB.Razon_Social,  PB.Date_Time_Creation 
					FROM inhouse_empresa PB
					WHERE PB.Entity = :Entity
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Inhouse_Empresa';
				$Link = $UrlFile."/interface/Create";
				$Screen = 'animatedModal5';
				$where = ["Entity"=>$Entity];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "Create":
			
				$btn .= "Atrás]" .$UrlFile."/interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Inhouse_Empresa = $Parm["Id_Inhouse_Empresa"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Inhouse_Empresa_Crud/Id_Inhouse_Empresa/".$Id_Inhouse_Empresa;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Inhouse_Empresa/".$Id_Inhouse_Empresa;
				
				if(!empty($Id_Inhouse_Empresa)){
				    $Name_Interface = "Editar Empresas";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Inhouse_Empresa_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Empresas";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Publicacion_Blog_Categoria"," SELECT Id_Publicacion_Blog_Categoria AS Id, Nombre AS Name FROM publicacion_blog_categoria ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Inhouse_Empresa_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Inhouse_Empresa_Crud",$Class,$Id_Inhouse_Empresa,$PathImage,$Combobox,$Buttons,"Id_Inhouse_Empresa");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "DeleteMassive":
		
		        $Id_Inhouse_Empresa = $Parm["Id_Inhouse_Empresa"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Inhouse_Empresa/".$Id_Inhouse_Empresa."/Obj/Inhouse_Empresa_Crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/List]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;					
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Inhouse_Empresa = $Settings["Id_Inhouse_Empresa"];
			
		$where = array('Id_Inhouse_Empresa' =>$Id_Inhouse_Empresa);
		$rg = ClassPDO::DCDelete('inhouse_empresa', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		
	
	
	
}