<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class SysSettingSite{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/sys_setting_site";
		$UrlFile_Cliente = "/sadministrator/cliente";
		$UrlFile_Pedido = "/sadministrator/pedido";
		$UrlFile_Edu_Banner = "/sadministrator/edu-banner";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					case "User_Crud":
					
		                    $Data = array();		
				            $Data['Id_Entity'] = $_SESSION['Entity'];											
						    DCCloseModal();									
							
							DCSave($Obj,$Conection,$Parm["Id_User"],"Id_User",$Data); 
							
							
							$Settings["interface"] = "";
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new SysSettingSite($Settings);
							DCExit();	
						
						break;		


						case "Entity_Crud_Nosotros":
					
		                    $Data = array();
							// $Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];		
				            // $Data['Id_Entity'] = $_SESSION['Entity'];															
							DCSave($Obj,$Conection,$_SESSION["Entity"],"Id_Entity",$Data);  
							
							$Settings["interface"] = "abaout";
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new SysSettingSite($Settings);
							DCExit();	
						
						break;		


						
							
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "User_Crud":
						
						$this->ObjectDelete($Parm);
						
						DCCloseModal();		
						$Settings["interface"] = "";
					    new SysSettingSite($Settings);
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
			    
				
				DCRedirect("/sadministrator/settings_graf/request/on/");
				DCExit();
				
				$layout  = new Layout();
				// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/interface/DeleteMassive[animatedModal5[HXM[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Editar Áreas[".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				// $btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Usuario]" .$UrlFile."/interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("SITIO","Opciones del Sitio",$btn);
					
				$urlLinkB = "";
				$Pestanas = SetttingsSite::Tabs(array(
				"".$urlLinkB."]Marca"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]","".$urlLinkB."]",""));		
				
				
				$Query = "
				    SELECT US.Id_User AS CodigoLink, US.Nombre, PE.Nombre AS Perfil, US.Date_Time_Creation 
					FROM user US
					INNER JOIN perfil PE ON US.Id_Perfil = PE.Id_Perfil
					
					
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_User';
				$Link = $UrlFile."/interface/Create";
				$Screen = 'animatedModal5';
				$where = ["Entidad"=>$Entidad];
				$Table = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','');
				 				
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Table .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;
				
        		
            case "Create":
			     
				 
	            $Id_User = $Parm["Id_User"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/User_Crud/Id_User/".$Id_User;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_User/".$Id_User;
				
				if(!empty($Id_User)){
				    $Name_Interface = "Editar Usuario";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","User_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Usuario";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				$Combobox = array(
				     array("Id_Perfil"," SELECT Id_Perfil AS Id, Nombre AS Name FROM perfil ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","User_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("User_Crud",$Class,$Id_User,$PathImage,$Combobox,$Buttons,"Id_User");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                break;
				
       			
            case "DeleteMassive":
		
		        $Id_User = $Parm["Id_User"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_User/".$Id_User."/Obj/User_Crud]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;				

            case "abaout":
			
				$layout  = new Layout();
				$listMn = "<i class='icon-chevron-right'></i> Configurar Banner[".$UrlFile_Edu_Banner."/Interface/List[animatedModal5[HXM[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Editar Áreas[".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Usuario]" .$UrlFile."/interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("SITIO","Opciones del Sitio",$btn);
					
				$urlLinkB = "";
				$Pestanas = SetttingsSite::Tabs(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]Marca","".$urlLinkB."]",""));		
				
				
	            // $Id_User = $Parm["Id_User"];
				
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Entity_Crud_Nosotros/Id_User/".$Id_User;
				$DirecctionDelete = $UrlFile."/interface/DeleteMassive/Id_User/".$Id_User;
				
				if(!empty($Entity)){
				    $Name_Interface = "Editar Usuario";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","User_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Usuario";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				$Combobox = array(
				     array("Id_Perfil"," SELECT Id_Perfil AS Id, Nombre AS Name FROM perfil ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Entity_Crud_Nosotros"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Entity_Crud_Nosotros",$Class,$Entity,$PathImage,$Combobox,$Buttons,"Id_Entity");
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Form1 ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;
				
				
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_User = $Settings["Id_User"];
			
		$where = array('Id_User' =>$Id_User);
		$rg = ClassPDO::DCDelete('user', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}	
}