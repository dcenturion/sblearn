<?php
require_once('./sviews/layout.php');
require_once('./sviews/ft_biblioteca.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Upload_Arc{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/upload_arc";
		$UrlFile_Edu_Tipo_Componente = "/sadministrator/edu_tipo_componente";
		$UrlFile_Edu_Tipo_Estructura = "/sadministrator/edu_tipo_estructura";
		$UrlFile_Edu_Area_Conocimiento = "/sadministrator/edu_area_conocimiento";
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["Process"];
		$Interface = $Parm["Interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":
			
		        switch ($Obj) {
					case "Edu_Articulo_Crud":
					
		                    $Data = array();
							$Data['Id_Edu_Tipo_Estructura'] = 1; //Cursos						
							$Data['Id_Edu_Tipo_Componente'] = 6; // Servicios						
				
						    DCCloseModal();									
							$Id_Edu_Articulo = DCSave($Obj,$Conection,$Parm["Id_Edu_Articulo"],"Id_Edu_Articulo",$Data); 
							
							// DCVd($Id_Edu_Articulo["lastInsertId"]);
							$data = array(
							'Id_Edu_Articulo' => $Id_Edu_Articulo["lastInsertId"],
							'Fecha_Hora_Ingreso_Almacen' => $DCTimeHour,
							'Entity' => $Entity,
							'Id_User_Creation' => $User,
							'Id_User_Update' => $User,
							);
							$Return = ClassPDO::DCInsert("edu_almacen", $data, $Conection);	
																	
												
							$Settings["Interface"] = "";
							// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
							new Articulo($Settings);
							DCExit();	
						
						break;							
							
				}		
				
                break;
            case "UPDATE":

                break;
            case "DELETE":
			
				switch ($Obj) {
					case "edu_articulo":
						
						$this->ObjectDelete($Parm);
						
						DCCloseModal();		
						$Settings["Interface"] = "";
					    new Articulo($Settings);
						DCExit();							
						
						break;	
						
				}	
				
                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":
						$this->Search($Parm);
						DCExit();	
                break;					
        }
		
		
		
        switch ($Interface) {
            case "":
			
				
				$Id_Object_Detail = $Parm["Id_Object_Detail"];
				$Ramdom = GeraHash(20);
				
				$Name_Field = DCUrl_Name_File($_FILES["archivo"]["name"]);
				$nombre_archivo = $Ramdom."_".$Name_Field;				
	
				$Query = "
				          SELECT OB.Id_Object, OB.Name, OD.Id_Object_Detail, WD.Name AS NameField
						  FROM object OB 
						  INNER JOIN object_detail OD ON OB.Id_Object = OD.Id_Object
						  INNER JOIN warehouse_detail WD ON OD.Id_Warehouse_Detail = WD.Id_Warehouse_Detail						  
						  WHERE OD.Id_Object_Detail = :Id_Object_Detail
						  ";	
				$Where = ["Id_Object_Detail" => $Id_Object_Detail];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);
				$Object_Name = $Row->Name;
				$Id_Object = $Row->Id_Object;
				$NameField = $Row->NameField;
	
				
				
				$Query = "SELECT HFL.Id_History_Field_Load
						  FROM history_field_load HFL 
						  WHERE HFL.Id_Object = :Id_Object  
						  AND HFL.Id_Object_Detail = :Id_Object_Detail
						  AND HFL.Id_User_Creation = :Id_User_Creation
						  ";	
				$Where = ["Id_Object_Detail" => $Id_Object_Detail, "Id_Object" => $Id_Object,"Id_User_Creation" =>$User];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);
				$Id_History_Field_Load = $Row->Id_History_Field_Load;
				
				if(empty($Id_History_Field_Load)){
					
					$data = array(
					'Id_Object' => $Id_Object,
					'Id_Object_Detail' => $Id_Object_Detail,
					'Value_Field' => $nombre_archivo,
					'Entity' => $Entity,
					'Id_User_Creation' => $User,
					'Id_User_Update' => $User,
					'Date_Time_Creation' => $DCTimeHour,
					'Date_Time_Update' => $DCTimeHour
					);
					$Result = ClassPDO::DCInsert("history_field_load", $data, $Conection);
				
				}else{
					
					$reg = array(
						'Value_Field' => $nombre_archivo,
						'Id_User_Update' => $User,
						'Date_Time_Update' => $DCTimeHour
					);
					$where = array('Id_History_Field_Load' =>$Id_History_Field_Load);
					$rg = ClassPDO::DCUpdate("history_field_load", $reg , $where, $Conection);
					
				}		
				
				
				$Data = file_get_contents("sbd_json/".$Object_Name.".json");
				$Result = json_decode($Data, true);
				foreach ($Result as $Reg) {
					
					if($Reg["WDName"] == $NameField){
						$Path_Image = $Reg["Path_Image"];
					}
					
				}	

				$BasePath   = $_SERVER['DOCUMENT_ROOT'];
		
				$return = Array("ok"=>TRUE);

				$upload_folder = $BasePath.$Path_Image;
				
				
				$tipo_archivo = $_FILES["archivo"]["type"];
				$tamano_archivo = $_FILES["archivo"]["size"];
				$tmp_archivo = $_FILES["archivo"]["tmp_name"];

				$Path_Image_Up = $upload_folder . "/" . $nombre_archivo;
				

				if (!move_uploaded_file($tmp_archivo, $Path_Image_Up)) {

					$return = Array("ok" => FALSE, "msg" => "Ocurrio un error al subir el archivo. No pudo guardarse.", "status" => "error");
					
				}
			
				$return = Array();
				$return["Path"] =$Path_Image;
				$return["Name_File"] =$nombre_archivo;
				$return["Bolean"] =$Bolean;
				$return["Mensaje"] =$data;
				echo json_encode($return);	
				
				DCExit();					
                break;
				

            case "upload_generic":
			
				
				$Id_Object_Detail = $Parm["Id_Object_Detail"];
				// $Ramdom = GeraHash(20);
				
				$Name_Field = DCUrl_Name_File($_FILES["archivo"]["name"]);
				$nombre_archivo = $Id_Object_Detail."_".$Name_Field;				
	
				
				$BasePath   = $_SERVER['DOCUMENT_ROOT'];
		
				$return = Array("ok"=>TRUE);

				$upload_folder = $BasePath."/sadministrator/simages/articulo";
				
				
				$tipo_archivo = $_FILES["archivo"]["type"];
				$tamano_archivo = $_FILES["archivo"]["size"];
				$tmp_archivo = $_FILES["archivo"]["tmp_name"];

				$Path_Image_Up = $upload_folder . "/" . $nombre_archivo;
				

				if (!move_uploaded_file($tmp_archivo, $Path_Image_Up)) {

					$return = Array("ok" => FALSE, "msg" => "Ocurrio un error al subir el archivo. No pudo guardarse.", "status" => "error");
					
				}
			
				$return = Array();
				$return["Path"] = "/sadministrator/simages/articulo";
				$return["Name_File"] =$nombre_archivo;
				$return["Bolean"] =$Bolean;
				$return["Mensaje"] =$data;
				echo json_encode($return);	
				
				DCExit();					
                break;
				
        		
            case "Create":
			     
				 
	            $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Articulo_Crud/Id_Edu_Articulo/".$Id_Edu_Articulo;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Articulo/".$Id_Edu_Articulo;
				
				if(!empty($Id_Edu_Articulo)){
					
				    $Name_Interface = "Editar Programa";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Articulo_Crud","btn btn-default m-w-120");					
				
				}else{
					
				    $Name_Interface = "Crear Programa";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Edu_Articulo_Crud");							
				}
				
				
				
				$Combobox = array(
				     array("Id_Publicacion_Blog_Categoria"," SELECT Id_Publicacion_Blog_Categoria AS Id, Nombre AS Name FROM publicacion_blog_categoria ",[])
				     ,array("Id_Edu_Area_Conocimiento"," SELECT Id_Edu_Area_Conocimiento AS Id, Nombre AS Name FROM edu_area_conocimiento ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/articulo")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"ScreenRight","Form","Edu_Articulo_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Articulo_Crud",$Class,$Id_Edu_Articulo,$PathImage,$Combobox,$Buttons,"Id_Edu_Articulo");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1 ,"");
                DCWrite($Html);
                DCExit();
                break;
				
       			
            case "DeleteMassive":
		
		        $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Articulo/".$Id_Edu_Articulo."/Obj/edu_articulo]ScreenRight]HXM]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/CLOSE/Id_Warehouse/".$Parm["Id_Warehouse"]."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;				
				
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Articulo = $Settings["Id_Edu_Articulo"];
		
		$where = array('Id_Edu_Articulo' =>$Id_Edu_Articulo);
		$rg = ClassPDO::DCDelete('edu_articulo', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}	
	
	
	public function Search($Parm) {
       	global $Conection, $DCTimeHour,$NameTable;

            $Queryd = $Parm["Qr"];
			$User = $_SESSION['User'];
			
			if(empty($Queryd)){
			    $OperadorA = "<>";
				$Queryd = "8b8b8b8b8b8b8b8b8bb8b8b";
			}else{
				$OperadorA = "LIKE";
			}
							
			$Query = "
				SELECT 
				AR.Id_Edu_Articulo AS CodigoLink
				, EA.Id_Edu_Almacen
				, AR.Nombre
				, AR.Estado
				, AR.Imagen
				, AR.Date_Time_Creation
				, EAC.Nombre AS Categoria  
				FROM edu_articulo AR
				INNER JOIN edu_almacen EA ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
				INNER JOIN edu_area_conocimiento EAC ON AR.Id_Edu_Area_Conocimiento = EAC.Id_Edu_Area_Conocimiento
				WHERE 
				AR.Estado = :Estado 
				AND ( AR.Nombre ".$OperadorA." :Nombre )
			";    
			$Id_Edu_Area_Conocimiento = $Parm["theme"];
			$Where = ["Estado"=>"Activo", "Nombre"=>'%'.$Queryd.'%'];
			
			$html ="";	
			$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
			
			$cont = 0;
			foreach ($Rows as $reg) {
				
				$con +=1;	
				
				$html .= "<div id='Item-Search-".$con."' class='item-search' onclick=selectionItem('Item-Search-".$con."'); >".$reg->Nombre."</div>";
			}			
			
		    DCWrite($html);			
	}		
}