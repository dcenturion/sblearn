<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_blog.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Categoria_Blog{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/categoria_blog";
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
					case "publicacion_blog_categoria_crud":
					
		                    // $Data = array();
							// $Data['Id_Pedido_Cab'] = $Parm["Id_Pedido"];						
							// $Data['Total'] = DCPost("Cantidad") * DCPost("Precio");						
						    DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Publicacion_Blog_Categoria"],"Id_Publicacion_Blog_Categoria",$Data);  
							
							$Settings["Interface"] = "";
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Categoria_Blog($Settings);
							DCExit();	
						
						break;							
							
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "publicacion_blog_categoria_crud":
						
						$this->ObjectDelete($Parm);
						
						DCCloseModal();		
						$Settings["Interface"] = "";
					    new Categoria_Blog($Settings);
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
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Categoría ]" .$UrlFile."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("BLOG","Administrador de Publicaciones ",$btn);
					
				$urlLinkB = "";
				$Pestanas = BlogObject::Tabs(array(
				"".$urlLinkB."]"
				,"".$urlLinkB."]Marca"
				,"".$urlLinkB."]","".$urlLinkB."]",""));		
				
				
				$Query = "
				    SELECT PBC.Id_Publicacion_Blog_Categoria AS CodigoLink, PBC.Nombre, PBC.Date_Time_Creation FROM publicacion_blog_categoria PBC
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Publicacion_Blog_Categoria';
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
			     
				 
	            $Id_Publicacion_Blog_Categoria = $Parm["Id_Publicacion_Blog_Categoria"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/publicacion_blog_categoria_crud/Id_Publicacion_Blog_Categoria/".$Id_Publicacion_Blog_Categoria;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Publicacion_Blog_Categoria/".$Id_Publicacion_Blog_Categoria;
				
				if(!empty($Id_Publicacion_Blog_Categoria)){
				    $Name_Interface = "Editar Categoría";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Publicacion_Blog_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Categoría";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
			
				$Combobox = array(
				     array("Id_Publicacion_Blog_Categoria"," SELECT Id_Publicacion_Blog_Categoria AS Id, Nombre AS Name FROM publicacion_blog_categoria ",[])
				);
				
				$PathImage = array(
				     array("Nameb","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","publicacion_blog_categoria_crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("publicacion_blog_categoria_crud",$Class,$Id_Publicacion_Blog_Categoria,$PathImage,$Combobox,$Buttons,"Id_Publicacion_Blog_Categoria");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                break;
				
       			
            case "DeleteMassive":
		
		        $Id_Publicacion_Blog_Categoria = $Parm["Id_Publicacion_Blog_Categoria"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Publicacion_Blog_Categoria/".$Id_Publicacion_Blog_Categoria."/Obj/publicacion_blog_categoria_crud]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;				
				
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Publicacion_Blog_Categoria = $Settings["Id_Publicacion_Blog_Categoria"];
			
		$where = array('Id_Publicacion_Blog_Categoria' =>$Id_Publicacion_Blog_Categoria);
		$rg = ClassPDO::DCDelete('Id_Publicacion_Blog_Categoria', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}	
}