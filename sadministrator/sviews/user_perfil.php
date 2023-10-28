<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class User_Perfil{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/user_perfil";
		$UrlFile_Cliente = "/sadministrator/cliente";
		$UrlFile_Pedido = "/sadministrator/pedido";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					case "Perfil_Crud":
					
		                    // $Data = array();
							// $Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];						
							// $Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Perfil"],"Id_Perfil",$Data);  
							
							$Settings["Interface"] = "";
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new User_Perfil($Settings);
							DCExit();	
						
						break;							
							
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "Perfil_Crud":
						
						$this->ObjectDelete($Parm);
						
						DCCloseModal();		
						$Settings["Interface"] = "";
					    new User_Perfil($Settings);
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
			
				$layout  = new Layout();
				// $listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[HXM[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Editar Áreas[".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				// $btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Perfil]" .$UrlFile."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("SITIO","Opciones del Sitio",$btn);
					
				$urlLinkB = "";
				$Pestanas = SetttingsSite::Tabs(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]Marca"
				,"".$urlLinkB."]","".$urlLinkB."]",""));		
				
				
				$Query = "
				    SELECT PE.Id_Perfil AS CodigoLink, PE.Nombre, PE.Date_Time_Creation FROM perfil PE
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Perfil';
				$Link = $UrlFile."/Interface/Create";
				$Screen = 'animatedModal5';
				$where = ["Entidad"=>$Entidad];
				$Table = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','');
				 				
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Table .  $Plugin ,"panel panel-default");
				
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;
				
        		
            case "Create":
			     
				 
	            $Id_Perfil = $Parm["Id_Perfil"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Perfil_Crud/Id_Perfil/".$Id_Perfil;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Perfil/".$Id_Perfil;
				
				if(!empty($Id_Perfil)){
				    $Name_Interface = "Editar Perfil";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Perfil_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Perfil";					
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
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Perfil_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Perfil_Crud",$Class,$Id_Perfil,$PathImage,$Combobox,$Buttons,"Id_Perfil");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                break;
				
       			
            case "DeleteMassive":
		
		        $Id_Perfil = $Parm["Id_Perfil"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Perfil/".$Id_Perfil."/Obj/Perfil_Crud]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;				
				
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Perfil = $Settings["Id_Perfil"];
			
		$where = array('Id_Perfil' =>$Id_Perfil);
		$rg = ClassPDO::DCDelete('perfil', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}	
}