<?php

require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Tipo_Ubicacion{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-tipo-ubicacion";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Edu_Tipo_Ubicacion_Crud":
					
                            $Data = array();
							$Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];						
							$Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Tipo_Ubicacion"],"Id_Edu_Tipo_Ubicacion",$Data);  
							
							$Settings["interface"] = "List";
							// $Settings["REDIRECT"] = $Redirect;
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Edu_Tipo_Ubicacion($Settings);
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
					case "Edu_Tipo_Ubicacion_Crud":
						
						$this->ObjectDelete($Parm);
									
						$Settings["interface"] = "List";
						// $Settings["REDIRECT"] = $Redirect;
						// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
						new Edu_Tipo_Ubicacion($Settings);
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
		
				$Name_Interface = "Listado Tipos Ubicacion";	
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/interface/Create]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT PB.Id_Edu_Tipo_Ubicacion AS CodigoLink, PB.Nombre, PB.Date_Time_Creation FROM edu_tipo_ubicacion PB
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Tipo_Ubicacion';
				$Link = $UrlFile."/interface/Create";
				$Screen = 'animatedModal5';
				$where = ["Entidad"=>$Entidad];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "Create":
			
				$btn .= "Atrás]" .$UrlFile."/interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Tipo_Ubicacion = $Parm["Id_Edu_Tipo_Ubicacion"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Tipo_Ubicacion_Crud/Id_Edu_Tipo_Ubicacion/".$Id_Edu_Tipo_Ubicacion;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Tipo_Ubicacion/".$Id_Edu_Tipo_Ubicacion;
				
				if(!empty($Id_Edu_Tipo_Ubicacion)){
				    $Name_Interface = "Editar Componente";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Tipo_Ubicacion_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Componente";					
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
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Tipo_Ubicacion_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Tipo_Ubicacion_Crud",$Class,$Id_Edu_Tipo_Ubicacion,$PathImage,$Combobox,$Buttons,"Id_Edu_Tipo_Ubicacion");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "DeleteMassive":
		
		        $Id_Edu_Tipo_Ubicacion = $Parm["Id_Edu_Tipo_Ubicacion"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Tipo_Ubicacion/".$Id_Edu_Tipo_Ubicacion."/Obj/Edu_Tipo_Ubicacion_Crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/List]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;					
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Tipo_Ubicacion = $Settings["Id_Edu_Tipo_Ubicacion"];
			
		$where = array('Id_Edu_Tipo_Ubicacion' =>$Id_Edu_Tipo_Ubicacion);
		$rg = ClassPDO::DCDelete('edu_tipo_ubicacion', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		
	
	
	
}