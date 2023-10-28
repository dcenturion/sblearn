<?php
require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Perfil_Educacion{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-perfil-educacion";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "perfil_educacion_crud":
					
                            $Data = array();
							$Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];						
							$Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Perfil_Educacion"],"Id_Perfil_Educacion",$Data);  
							
							$Settings["interface"] = "List";
							$Settings["REDIRECT"] = $Redirect;
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Edu_Perfil_Educacion($Settings);
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
					case "perfil_educacion_crud":
						
						$this->ObjectDelete($Parm);
									
						$Settings["interface"] = "List";
						// $Settings["REDIRECT"] = $Redirect;
						// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
						new Edu_Perfil_Educacion($Settings);
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
			
				$Name_Interface = "Perfiles Educativos";	
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/interface/Create]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT PB.Id_Perfil_Educacion AS CodigoLink, PB.Nombre, PB.Date_Time_Creation FROM perfil_educacion PB
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Perfil_Educacion';
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
				
	            $Id_Perfil_Educacion = $Parm["Id_Perfil_Educacion"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/perfil_educacion_crud/Id_Perfil_Educacion/".$Id_Perfil_Educacion;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Perfil_Educacion/".$Id_Perfil_Educacion;
				
				if(!empty($Id_Perfil_Educacion)){
				    $Name_Interface = "Editar Area de Conocimiento";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","perfil_educacion_crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Area de Conocimiento";					
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
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","perfil_educacion_crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("perfil_educacion_crud",$Class,$Id_Perfil_Educacion,$PathImage,$Combobox,$Buttons,"Id_Perfil_Educacion");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "DeleteMassive":
		
		        $Id_Perfil_Educacion = $Parm["Id_Perfil_Educacion"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Perfil_Educacion/".$Id_Perfil_Educacion."/Obj/perfil_educacion_crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/List]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;					
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Perfil_Educacion = $Settings["Id_Perfil_Educacion"];
			
		$where = array('Id_Perfil_Educacion' =>$Id_Perfil_Educacion);
		$rg = ClassPDO::DCDelete('perfil_educacion', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		
	
	
	
}