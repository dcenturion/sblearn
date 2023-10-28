<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_settings_site.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Profile_User{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/profile_user";
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
					case "User_Miembro_Crud":
					case "User_Miembro_Foto_Crud":
					
		                    // $Data = array();
							// $Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];						
							// $Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_User_Miembro"],"Id_User_Miembro",$Data);  
							
							$Settings["Interface"] = "";
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Profile_User($Settings);
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
				// $btn .= "<i class='zmdi zmdi-edit'></i> Crear Warehouse ]" .$UrlFile_admin_warehouse."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				// $btn = DCButton($btn, 'botones1', 'sys_form');
				
				
				$Query = "
					SELECT 
					UM.Id_Perfil_Educacion,SC.Producto_Origen
					FROM user_miembro UM
					INNER JOIN suscripcion SC ON UM.Id_User_Miembro=SC.Id_User
					WHERE 
					UM.Id_User_Miembro=:Id_User 
				";	
				$Where = ["Id_User" => $User];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Id_Perfil_Educacion = $Row->Id_Perfil_Educacion;
				$Producto_Origen = $Row->Producto_Origen;				
                
				// var_dump($User);
				// var_dump($Entity);
				// var_dump($Id_Perfil_Educacion);
				$DCPanelTitle = DCPanelTitle("PERFIL","Datos del Usuario",$btn);
				

				// $Pestanas = SetttingsSite::Tabs(array(
				// "".$urlLinkB."]"
				// ,"".$urlLinkB."]"
				// ,"".$urlLinkB."]Marca","".$urlLinkB."]",""));					
				
				// $listMn .= "<i class='icon-chevron-right'></i> Save Details [".$UrlFile."/Process/ENTRY/Id_Object/".$Parm["Id_Object"]."/Id_Warehouse/".$RowWarehouse->Id_Warehouse."/Obj/Obj_Object_detail_grilla[ScreenRight[FORM[Obj_Object_detail_grilla{";	
				
				// $Id = 4;
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/User_Miembro_Crud/Id_User_Miembro/".$User;						
				$Buttons = array(
				     array("Actualizar",$DirecctionA,"ScreenRight","Form","User_Miembro_Crud")
				);	
		        $DatosPrincipales = BFormVertical("User_Miembro_Crud",$Class,$User,$PathImage,$Combobox,$Buttons,"Id_User_Miembro");
						
				$AcordeonA = array(
				                  array("Acordeon_PanelA","Datos Personales ",$DatosPrincipales)
								  );
				$Acordeones = DCAcordeon($AcordeonA);
				$PanelA =  $Acordeones;
				
				
				
				$DirecctionA = $UrlFile.$Redirect."/Process/ENTRY/Obj/User_Miembro_Foto_Crud/Id_User_Miembro/".$User;				
				$Buttons = array(
				     array("Actualizar",$DirecctionA,"ScreenRight","Form","User_Miembro_Foto_Crud")
				);	
				$PathImage = array(
				     array("Foto","/sadministrator/simages/avatars")
				);				
		        $LogoInterno = BFormVertical("User_Miembro_Foto_Crud",$Class,$User,$PathImage,$Combobox,$Buttons,"Id_User_Miembro");
												
				
				$AcordeonB = array(
				                  array("Acordeon_PanelC","Foto",$LogoInterno)
								  );
				$Acordeones = DCAcordeon($AcordeonB);
				$PanelB =  $Acordeones;				
				
				
				$Layout = array(array("PanelA","col-md-6",$PanelA),array("PanelB","col-md-6",$PanelB));
				$Content = DCLayout($Layout);
	
				$Contenido = DCPage($DCPanelTitle,$Pestanas ."<BR>".$Content .  $Plugin);
				
				if($Parm["Request"] == "On"){
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