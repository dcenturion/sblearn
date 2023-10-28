<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Settings_Graf{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/settings_graf";
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
					case "Entity_Crud":
					case "Entity_Crud_Logo_Externo":
					case "Entity_Crud_Logo_Interno":
					case "Entity_Crud_Colores":
					case "Entity_Crud_Format_Email":
					case "Entity_Crud_Datos_Soporte":
					
		                    // $Data = array();
							// $Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];						
							// $Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Entity"],"Id_Entity",$Data);  
							
							$Settings["Interface"] = "";
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Settings_Graf($Settings);
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
				$listMn = "<i class='icon-chevron-right'></i> Delete Rows[".$UrlFile."/Interface/DeleteMassive[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Editar Áreas[".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Warehouse ]" .$UrlFile_admin_warehouse."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("SITIO","Administración del sitio",$btn);

				$Pestanas = SetttingsSite::Tabs(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]"
				,"".$urlLinkB."]Marca","".$urlLinkB."]",""));					
				
				// $listMn .= "<i class='icon-chevron-right'></i> Save Details [".$UrlFile."/Process/ENTRY/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."/Obj/Obj_Object_detail_grilla[ScreenRight[FORM[Obj_Object_detail_grilla{";	
				
				// $Id = 4;
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Entity_Crud/Id_Entity/".$Entity;						
				$Buttons = array(
				     array("Actualizar",$DirecctionA,"ScreenRight","Form","Entity_Crud")
				);	
		        $DatosPrincipales = BFormVertical("Entity_Crud",$Class,$Entity,$PathImage,$Combobox,$Buttons,"Id_Entity");
				
				
				
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Entity_Crud_Logo_Externo/Id_Entity/".$Entity;				
				$Buttons = array(
				     array("Actualizar",$DirecctionA,"ScreenRight","Form","Entity_Crud_Logo_Externo")
				);	
				$PathImage = array(
				     array("Logo_Externo","/sadministrator/simages")
				);				
		        $LogoExterno = BFormVertical("Entity_Crud_Logo_Externo",$Class,$Entity,$PathImage,$Combobox,$Buttons,"Id_Entity");
								
				$AcordeonA = array(
				                  array("Acordeon_PanelA","Datos Principales ",$DatosPrincipales)
								  ,array("Acordeon_PanelB","Logo Externo",$LogoExterno)
								  );
				$Acordeones = DCAcordeon($AcordeonA);
				$PanelA =  $Acordeones;
				
				
				
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Entity_Crud_Logo_Interno/Id_Entity/".$Entity;				
				$Buttons = array(
				     array("Actualizar",$DirecctionA,"ScreenRight","Form","Entity_Crud_Logo_Interno")
				);	
				$PathImage = array(
				     array("Logo_Interno","/sadministrator/simages")
				);				
		        $LogoInterno = BFormVertical("Entity_Crud_Logo_Interno",$Class,$Entity,$PathImage,$Combobox,$Buttons,"Id_Entity");
										

										
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Entity_Crud_Colores/Id_Entity/".$Entity;				
				$Buttons = array(
				     array("Actualizar",$DirecctionA,"ScreenRight","Form","Entity_Crud_Colores")
				);	
				$PathImage = array(
				     array("Logo_Externo","/sadministrator/simages")
				);				
		        $Form_Colores = BFormVertical("Entity_Crud_Colores",$Class,$Entity,$PathImage,$Combobox,$Buttons,"Id_Entity");

				$AcordeonB = array(
				                  array("Acordeon_PanelC","Logo Interno",$LogoInterno)
								  ,array("Acordeon_PanelD","Colores",$Form_Colores)
								  );
				$Acordeones = DCAcordeon($AcordeonB);
				$PanelB =  $Acordeones;	

				
				
				
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Entity_Crud_Format_Email/Id_Entity/".$Entity;				
				$Buttons = array(
				     array("Actualizar",$DirecctionA,"ScreenRight","Form","Entity_Crud_Format_Email")
				);	
				$PathImage = array(
				     array("Logo_Externo","/sadministrator/simages")
				);				
		        $Datos_Email = BFormVertical("Entity_Crud_Format_Email",$Class,$Entity,$PathImage,$Combobox,$Buttons,"Id_Entity");
												
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Entity_Crud_Datos_Soporte/Id_Entity/".$Entity;				
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/Entity_Crud_Datos_Soporte/Id_Entity/".$Entity;				
				$Buttons = array(
				     array("Actualizar",$DirecctionA,"ScreenRight","Form","Entity_Crud_Datos_Soporte")
				);	
				$PathImage = array(
				     array("Logo_Externo","/sadministrator/simages")
				);				
		        $Datos_Soporte = BFormVertical("Entity_Crud_Datos_Soporte",$Class,$Entity,$PathImage,$Combobox,$Buttons,"Id_Entity");

				$AcordeonC = array(
				                  array("Acordeon_PanelE","Emails de Salida",$Datos_Email)
								  ,array("Acordeon_PanelF","Datos de Soporte",$Datos_Soporte)
								  );
				$Acordeones = DCAcordeon($AcordeonC);
				$PanelC =  $Acordeones;	

				
				
				
				$Layout = array(array("PanelA","col-md-6",$PanelA),array("PanelB","col-md-6",$PanelB),array("PanelC","col-md-6",$PanelC));
				$Content = DCLayout($Layout);
	
				$Contenido = DCPage($DCPanelTitle,$Pestanas ."<BR>".$Content .  $Plugin);
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				exit();
				
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