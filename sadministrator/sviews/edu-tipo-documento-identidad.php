<?php

require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Tipo_Documento_Identidad{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-tipo-documento-identidad";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Edu_Tipo_Documento_Identidad_Crud":
					
                            $Data = array();
							$Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];						
							$Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Tipo_Documento_Identidad"],"Id_Edu_Tipo_Documento_Identidad",$Data);  
							
							$Settings["interface"] = "List";
							// $Settings["REDIRECT"] = $Redirect;
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Edu_Tipo_Documento_Identidad($Settings);
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
					case "Edu_Tipo_Documento_Identidad_Crud":
						
						$this->ObjectDelete($Parm);
									
						$Settings["interface"] = "List";
						// $Settings["REDIRECT"] = $Redirect;
						// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
						new Edu_Tipo_Documento_Identidad($Settings);
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
		
				$Name_Interface = "Listado de Tipos de Documentos";	
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/interface/Create]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT PB.Id_Edu_Tipo_Documento_Identidad AS CodigoLink, PB.Nombre, PB.Date_Time_Creation FROM edu_tipo_documento_identidad PB
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Tipo_Documento_Identidad';
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
				
 
	            $Id_Edu_Tipo_Documento_Identidad = $Parm["Id_Edu_Tipo_Documento_Identidad"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Tipo_Documento_Identidad_Crud/Id_Edu_Tipo_Documento_Identidad/".$Id_Edu_Tipo_Documento_Identidad;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_Edu_Tipo_Documento_Identidad/".$Id_Edu_Tipo_Documento_Identidad;
				
				if(!empty($Id_Edu_Tipo_Documento_Identidad)){
				    $Name_Interface = "Editar Componente";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Tipo_Documento_Identidad_Crud","btn btn-default m-w-120");					
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
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Tipo_Documento_Identidad_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Tipo_Documento_Identidad_Crud",$Class,$Id_Edu_Tipo_Documento_Identidad,$PathImage,$Combobox,$Buttons,"Id_Edu_Tipo_Documento_Identidad");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "DeleteMassive":
		
		        $Id_Edu_Tipo_Documento_Identidad = $Parm["Id_Edu_Tipo_Documento_Identidad"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Tipo_Documento_Identidad/".$Id_Edu_Tipo_Documento_Identidad."/Obj/Edu_Tipo_Documento_Identidad_Crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/List]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;					
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Tipo_Documento_Identidad = $Settings["Id_Edu_Tipo_Documento_Identidad"];
			
		$where = array('Id_Edu_Tipo_Documento_Identidad' =>$Id_Edu_Tipo_Documento_Identidad);
		$rg = ClassPDO::DCDelete('edu_tipo_documento_identidad', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		
	
	
	
}