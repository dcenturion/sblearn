<?php
require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Publicidad_Tipo_Alcance{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu_publicidad_tipo_alcance";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Edu_Publicidad_Tipo_Alcance_Crud":
					
                            $Data = array();
							$Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];						
							$Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Publicidad_Tipo_Alcance"],"Id_Edu_Publicidad_Tipo_Alcance",$Data);  
							
							$Settings["Interface"] = "List";
							$Settings["REDIRECT"] = $Redirect;
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Edu_Publicidad_Tipo_Alcance($Settings);
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
							$Settings['Url'] = "/sadministrator/det_admin_object/Interface/Details/Id_Object/".$Id_Object;
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
					case "Edu_Publicidad_Tipo_Alcance_Crud":
						
						$this->ObjectDelete($Parm);
									
						$Settings["Interface"] = "List";
						$Settings["REDIRECT"] = $Redirect;
						// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
						new Edu_Publicidad_Tipo_Alcance($Settings);
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
        
            case "List":
			
				$Name_Interface = "Listado de Tipo de Alcance";	
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/REDIRECT/".$Redirect."/Interface/Create]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT PB.Id_Edu_Publicidad_Tipo_Alcance AS CodigoLink, PB.Nombre, PB.Date_Time_Creation FROM edu_publicidad_tipo_alcance PB
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Publicidad_Tipo_Alcance';
				$Link = $UrlFile."/REDIRECT/".$Redirect."/Interface/Create";
				$Screen = 'animatedModal5';
				$where = ["Entidad"=>$Entidad];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "Create":
			
				$btn .= "Atrás]" .$UrlFile."/REDIRECT/".$Redirect."/Interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Publicidad_Tipo_Alcance = $Parm["Id_Edu_Publicidad_Tipo_Alcance"];
				$DirecctionA = $UrlFile."/REDIRECT/".$Redirect."/Process/ENTRY/Obj/Edu_Publicidad_Tipo_Alcance_Crud/Id_Edu_Publicidad_Tipo_Alcance/".$Id_Edu_Publicidad_Tipo_Alcance;
				$DirecctionDelete = $UrlFile."/REDIRECT/".$Redirect."/Interface/DeleteMassive/Id_Edu_Publicidad_Tipo_Alcance/".$Id_Edu_Publicidad_Tipo_Alcance;
				
				if(!empty($Id_Edu_Publicidad_Tipo_Alcance)){
				    $Name_Interface = "Editar Tipo de Alcance";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Publicidad_Tipo_Alcance_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Tipo de Alcance";					
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
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Publicidad_Tipo_Alcance_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Publicidad_Tipo_Alcance_Crud",$Class,$Id_Edu_Publicidad_Tipo_Alcance,$PathImage,$Combobox,$Buttons,"Id_Edu_Publicidad_Tipo_Alcance");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "DeleteMassive":
		
		        $Id_Edu_Publicidad_Tipo_Alcance = $Parm["Id_Edu_Publicidad_Tipo_Alcance"];
				
				$btn = "Continue ]" .$UrlFile ."/REDIRECT/".$Redirect."/Process/DELETE/Id_Edu_Publicidad_Tipo_Alcance/".$Id_Edu_Publicidad_Tipo_Alcance."/Obj/Edu_Publicidad_Tipo_Alcance_Crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/REDIRECT/".$Redirect."/Interface/List]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;					
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Publicidad_Tipo_Alcance = $Settings["Id_Edu_Publicidad_Tipo_Alcance"];
			
		$where = array('Id_Edu_Publicidad_Tipo_Alcance' =>$Id_Edu_Publicidad_Tipo_Alcance);
		$rg = ClassPDO::DCDelete('edu_publicidad_tipo_alcance', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		
	
	
	
}