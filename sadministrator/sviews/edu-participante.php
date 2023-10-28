 <?php
require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();
$DCTime=DCDate();

class Edu_Participante{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-participante";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
         $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];

        switch ($Process) { 
            case "ENTRY":

				switch ($Obj) {
					case "User_Register_Crud":
					
						$Id_Suscripcion = $Parm["Id_Suscripcion"];
						
						$data=$this->Proceso_inscripcion_Datos_G($Parm);
						$Id_Link=$data['Link_Id'];
						$Mensaje_Register=$data['Mensaje'];
						$Validacion_Register=$data['Validacion'];

						
						if ($Validacion_Register=="error") {
							DCWrite(Message("NO SE PERMITE HACER CAMBIOS DE EMAIL","E"));
						}

						$Settings=array();

						if ($Id_Link==1) {
							 DCWrite(Message("Usuario Matriculado en el curso","E"));
							 $Settings["interface"]="List";
						}else{
							DCWrite(Message($Mensaje_Register,"C"));
							$Settings["interface"]="Create_D_Extra";
							
							if (!empty($Id_Suscripcion)) {
						 	 	$Settings["Id_Suscripcion"] =$Id_Link;
							}else{
								$Settings["Id_Register"]=$Id_Link;
							}

						}
						$Settings["key"] = $Parm["key"];
						new Edu_Participante($Settings);
						DCExit();
						// }
						
					break;
					case "User_Matricula_Crud":
						$Id_Suscripcion = $Parm["Id_Suscripcion"];
						
						$data=$this->Proceso_Matricula_G($Parm);
						$Mensaje_Matricula=$data['Mensaje'];
						$Validacion_Matricula=$data['Validacion'];

						
						if ($Validacion_Matricula==1) {
							DCWrite(Message("Error en el proceso","E"));
						}else{
							DCWrite(Message($Mensaje_Matricula,"C"));
						}
						$Settings=array();
						$Settings["interface"]="List";
						$Settings["key"] = $Parm["key"];
						new Edu_Participante($Settings);
						DCExit();
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
					
				}
				

                break;
				
            case "DELETE":
			
				switch ($Obj) {
					case "Delete_Crud":
					
					   $Perfil_User = $_SESSION['Perfil_User'];
					   if($Perfil_User == 1 ){
							   
							$Id_Suscripcion = $Parm["Id_Suscripcion"];
							
							$data=$this->ObjectDelete($Parm);
							$Mensaje_Delete=$data['Mensaje'];
							$Validacion_Delete=$data['Validacion'];

							
							if ($Validacion_Delete==2) {
								DCWrite(Message($Mensaje_Delete,"E"));
							}else if ($Validacion_Delete==1) {
								DCWrite(Message("Error en la Eliminacion","E"));
							}else{
								DCWrite(Message($Mensaje_Delete,"C"));
							}					
						   
					   }else{
						   
						  	DCWrite(Message(" Para eliminar debe comunicarse con el administrador","E"));
							 
													   
					   }					
					
					
					

				
						 
						$Settings=array();
						$Settings["interface"]="List";
						$Settings["key"] = $Parm["key"];
						new Edu_Participante($Settings);
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
				$Id_Edu_Almacen = $Parm["key"];

				

				$btn = " GRABADOS ]" .$UrlFile."/interface/Create_D_General/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]nav nav-tabs}";
				$btn .= " PROGRAMA ]" .$UrlFile."/interface/Create_D_General/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn_Grabado = DCButton($btn, 'botones1', 'sys_form');
				$Name_Interface = "<div class='text-center' style='display:flex;justify-content: space-around;'><div>Listado de Participantes En Vivo</div>".$btn_Grabado."</div>";	
			    
			    
                if($User == 7370 ){

						$Query="SELECT count(*) as Conteo_User_Alum 
								FROM suscripcion SC
								WHERE SC.Id_Edu_Almacen = :Id_Edu_Almacen 
								AND SC.Id_Perfil_Educacion=:Id_Perfil_Educacion 
								AND SC.Entity=:Entity
								AND SC.Id_User_Creation=:Id_User_Creation
								"; 
						$Where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_Perfil_Educacion"=>"3","Entity"=>$Entity, "Id_User_Creation"=>$User]; 
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Conteo_User_Alum = $Row->Conteo_User_Alum;
						
						

						$Query_User="SELECT count(*) as Conteo_User FROM suscripcion SC
								WHERE SC.Id_Edu_Almacen = :Id_Edu_Almacen AND SC.Id_Perfil_Educacion<>:Id_Perfil_Educacion 
								AND SC.Entity=:Entity
								AND SC.Id_User_Creation = :Id_User_Creation
								"; 
						$Where_User = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_Perfil_Educacion"=>"3","Entity"=>$Entity, "Id_User_Creation"=>$User]; 
						$Row_Conteo_User = ClassPdo::DCRow($Query_User,$Where_User,$Conection);	
						$Conteo_User = $Row_Conteo_User->Conteo_User; 

						$btn = "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/interface/Create_D_General/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-info ladda-button}";
						$btn_Crear = DCButton($btn, 'botones1', 'sys_form');
						$DCPanelTitle = DCPanelTitle("","Existen ".$Conteo_User_Alum." alumnos / ".$Conteo_User." usuarios que no son alumnos",$btn_Crear);


						$Query = "
						
							SELECT UM.Nombre, PE.Nombre AS Perfil, SC.Id_Suscripcion AS CodigoLink 
							,UM.Email as Usuario_Acceso
							, SC.Date_Time_Creation as F_Mt
							, SC.Fecha_Fin as F_Fin
							
							FROM suscripcion SC
							INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
							INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
							LEFT JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
							WHERE 
							SC.Id_Edu_Almacen = :Id_Edu_Almacen
		
							AND SC.Id_User_Creation = :Id_User_Creation

					
					";					
				
					$Class = 'table table-hover';
					$LinkId = 'Id_Suscripcion';
					$Link = $UrlFile."/interface/Create_D_General/key/".$Id_Edu_Almacen."";
					$Screen = 'animatedModal5';
					$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen, "Id_User_Creation"=>$User];
					$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				
						
						
					
					
				}else{
					
		

						$Query="SELECT count(*) as Conteo_User_Alum 
								FROM suscripcion SC
								WHERE SC.Id_Edu_Almacen = :Id_Edu_Almacen AND SC.Id_Perfil_Educacion=:Id_Perfil_Educacion AND SC.Entity=:Entity"; 
						$Where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_Perfil_Educacion"=>"3","Entity"=>$Entity]; 
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Conteo_User_Alum = $Row->Conteo_User_Alum;
						
						

						$Query_User="SELECT count(*) as Conteo_User FROM suscripcion SC
								WHERE SC.Id_Edu_Almacen = :Id_Edu_Almacen AND SC.Id_Perfil_Educacion<>:Id_Perfil_Educacion AND SC.Entity=:Entity"; 
						$Where_User = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Id_Perfil_Educacion"=>"3","Entity"=>$Entity]; 
						$Row_Conteo_User = ClassPdo::DCRow($Query_User,$Where_User,$Conection);	
						$Conteo_User = $Row_Conteo_User->Conteo_User; 

						$btn = "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/interface/Create_D_General/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-info ladda-button}";
						$btn_Crear = DCButton($btn, 'botones1', 'sys_form');
						$DCPanelTitle = DCPanelTitle("","Existen ".$Conteo_User_Alum." alumnos / ".$Conteo_User." usuarios que no son alumnos",$btn_Crear);


						$Query = "
						
							SELECT UM.Nombre, PE.Nombre AS Perfil, SC.Id_Suscripcion AS CodigoLink 
							,UM.Email as Usuario_Acceso
							, SC.Date_Time_Creation as F_Mt
							, SC.Fecha_Fin as F_Fin
							
							FROM suscripcion SC
							INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
							INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
							LEFT JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
							WHERE 
							SC.Id_Edu_Almacen = :Id_Edu_Almacen

					";  

				
					$Class = 'table table-hover';
					$LinkId = 'Id_Suscripcion';
					$Link = $UrlFile."/interface/Create_D_General/key/".$Id_Edu_Almacen."";
					$Screen = 'animatedModal5';
					$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,];
					$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				
						
					
					
                }

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
            break;			

            case "Create_D_General":
			
				$Id_Edu_Almacen = $Parm["key"];	
				$Id_Suscripcion = $Parm["Id_Suscripcion"];	
				

				
				$btn .= "D. Personales]" .$UrlFile."/interface/Create_D_General/key/".$Id_Edu_Almacen."/Id_Suscripcion/".$Id_Suscripcion."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				if(!empty($Id_Suscripcion)){
					$btn .= "D. Extra]" .$UrlFile."/interface/Create_D_Extra/key/".$Id_Edu_Almacen."/Id_Suscripcion/".$Id_Suscripcion."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				}
				$btn .= "Atrás]" .$UrlFile."/interface/List/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				
				
				
				if(!empty($Id_Suscripcion)){
				    $Name_Interface = "Actualizar Información";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";	
                    // Traer los id siempre y cuando exista id suscripcion
                    $Query = "
							SELECT UM.Nombre, US.Id_User  ,SC.Id_User as User_Miembro, US.Usuario_Login
							FROM suscripcion SC
							INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
							INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
							WHERE SC.Id_Suscripcion = :Id_Suscripcion
					";  
					$Where = ["Id_Suscripcion"=>$Id_Suscripcion];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);
					$Id_User_Miembro = $Row->User_Miembro;
					$Id_User= $Row->Id_User;	
					//botones
					$Ruta ="User_Register_Crud/key/".$Id_Edu_Almacen."/Id_Suscripcion/".$Id_Suscripcion;
					$DirecctionDelete = $UrlFile."/interface/DeleteMassive/key/".$Id_Edu_Almacen."/Id_User/".$Id_User_Miembro."/Id_Suscripcion/".$Id_Suscripcion;
					$ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Form_Suscripcion_D_General_Crud","btn btn-default m-w-120");

				}else{
				    $Name_Interface = "Crear Participante";
				  	$Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
				  	$Ruta="User_Register_Crud/key/".$Id_Edu_Almacen;							
				}

				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/".$Ruta;
				
				$Combobox = array(
					 array("Id_User_Sexo","SELECT Id_User_Sexo AS Id, Nombre AS Name FROM user_genero",[]),
				     array("Id_Edu_Pais"," SELECT Id_Edu_Pais AS Id, Nombre AS Name FROM edu_pais ",[]),
				     array("Id_Perfil"," SELECT Id_Perfil_Educacion AS Id, Nombre AS Name FROM perfil_educacion ",[])
					 
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Form_Suscripcion_D_General_Crud"),$ButtonAdicional
				);	
		        //$Form1 = BFormVertical("User_Register_Crud",$Class,$Id_User,$PathImage,$Combobox,$Buttons,"Id_User");
		        $Form1 = BFormVertical("Form_Suscripcion_D_General_Crud",$Class,$Id_User,$PathImage,$Combobox,$Buttons,"Id_User");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
            break;		
			case "Create_D_Extra":
			
				$Id_Edu_Almacen = $Parm["key"];			
				$Id_Suscripcion = $Parm["Id_Suscripcion"];	
				$Id_Register= $Parm["Id_Register"];			
				
				if(!empty($Id_Suscripcion)){
					$btn .= "D. Personales]" .$UrlFile."/interface/Create_D_General/key/".$Id_Edu_Almacen."/Id_Suscripcion/".$Id_Suscripcion."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				}
				if(!empty($Id_Register)){
					$btn .= "D. Extra]" .$UrlFile."/interface/Create_D_Extra/key/".$Id_Edu_Almacen."/Id_Register/".$Id_Register."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				}else{
					$btn .= "D. Extra]" .$UrlFile."/interface/Create_D_Extra/key/".$Id_Edu_Almacen."/Id_Suscripcion/".$Id_Suscripcion."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				}
				$btn .= "Atrás]" .$UrlFile."/interface/List/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
	
				
				
				
				if(!empty($Id_Suscripcion)){
					$Name_Interface = "Actualizar Matricula";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
				    $Ruta ="User_Matricula_Crud/key/".$Id_Edu_Almacen."/Id_Suscripcion/".$Id_Suscripcion;				
				}else{
				    $Name_Interface = "Generar Matricula";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Generar";
				  	$Ruta ="User_Matricula_Crud/key/".$Id_Edu_Almacen."/Id_Register/".$Id_Register;
  						
				}//2414 = DCDesencriptarB($texto) DCEncriptarB($texto) 
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/".$Ruta;
				
				
				$Combobox = array(
				     array("Id_Perfil"," SELECT Id_Perfil_Educacion AS Id, Nombre AS Name FROM perfil_educacion ",[]),
				     array("Id_Modalidad_Venta_Curso"," SELECT Id_Modalidad_Venta_Curso AS Id, Nombre AS Name FROM Modalidad_Venta_Curso ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Form_Suscripcion_D_Extra_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Form_Suscripcion_D_Extra_Crud",$Class,$Id_Suscripcion,$PathImage,$Combobox,$Buttons,"Id_Suscripcion","");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
            break;	

            case "DeleteMassive":
		
		        $Id_User = $Parm["Id_User"];
		        $Id_Suscripcion = $Parm["Id_Suscripcion"];
		        $key = $Parm["key"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Obj/Delete_Crud/key/".$key."/Id_User/".$Id_User."/Id_Suscripcion/".$Id_Suscripcion."]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Cerrar ]" .$UrlFile ."/interface/List/key/".$key."]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
            break;
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Suscripcion = $Settings["Id_Suscripcion"];
		$Id_User = $Settings["Id_User"];
		$key = $Settings["key"];
		$Id_Entity = $_SESSION['Entity'];


		$error=0;$Mensaje="";

		if (!empty($Id_Suscripcion)) {

				// Verifica si es cliente antiguo 
				$Query="SELECT  count(*) as Verif_Suscripcion,UM.Id_User_Creation
						FROM suscripcion SUS
						INNER JOIN user_miembro UM on SUS.Id_User=UM.Id_User_Miembro
		                where SUS.Entity=:Entity AND SUS.Id_User=:Id_User AND SUS.Producto_Origen=:Producto_Origen";
				$Where = ["Entity"=>$Id_Entity,"Id_User"=>$Id_User,"Producto_Origen"=>"CURSO"];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Verif_Suscripcion = $Row->Verif_Suscripcion;
				$Id_User_Creation = $Row->Id_User_Creation;
				if ($Verif_Suscripcion==1) {
					//Eliminacion suscripcion
					$where = array('Id_Edu_Almacen'=>$key,'Id_Suscripcion' =>$Id_Suscripcion);
					$rg = ClassPDO::DCDelete('suscripcion', $where, $Conection);
					
					$where = array('Id_User' =>$Id_User_Creation,"Entity"=>$Id_Entity);
					$rg = ClassPDO::DCDelete('user', $where, $Conection);
					
					$where = array('Id_User_Miembro' =>$Id_User,"Entity"=>$Id_Entity);
					$rg = ClassPDO::DCDelete('user_miembro', $where, $Conection);
					$Mensaje="Usuario Nuevo eliminado";
					
				}else if($Verif_Suscripcion>1) {
					//Eliminacion suscripcion
					$where = array('Id_Edu_Almacen'=>$key,'Id_Suscripcion' =>$Id_Suscripcion,"Producto_Origen"=>"CURSO");
					$rg = ClassPDO::DCDelete('suscripcion', $where, $Conection);
					$Mensaje="Suscripcion Eliminada";
				}else{
					$error=1;
				}

		}else{
			$error=2;
			$Mensaje="Error no capta el codigo de matricula";
		}

		$Return=array(); 
		$Return['Mensaje'] = $Mensaje;
		$Return['Validacion'] = $error;			
		return $Return; 
						
	}

	public function Proceso_inscripcion_Datos_G($Settings) {
       	global $Conection, $DCTimeHour,$NameTable,$DCTime;
       	$User = $_SESSION['User'];
		$Id_Entity = $_SESSION['Entity'];
		$Id_Edu_Almacen = $Settings["key"];
		$Id_Suscripcion = $Settings["Id_Suscripcion"];


		//Datos formulario
		$Name_surnames = DCPost("Nombre");	
		$Correo_Previo = DCPost("Email");
		$PasswordO= DCPost("Password");	
		$Password=trim($PasswordO);
		$Telefono = DCPost("Telefono");	
		$Sexo=DCPost("Id_User_Sexo");
		$Id_Edu_Pais=DCPost("Id_Edu_Pais");

		$link_Id=0;$MensajeA="";$MensajeC="";$Validacion="";
		if ($Sexo==1) {$ruta="4ad89d8dh345s_hombre.png";}
		else if($Sexo==2){$ruta="4ad89d345s_mujer.png";}
		//Verificacion en update del correo
		if (!empty($Id_Suscripcion)) {
			$Query="SELECT  UM.Email
				FROM suscripcion  SUS INNER JOIN user_miembro UM ON SUS.Id_User=UM.Id_User_Miembro
				WHERE  SUS.Entity=:Entity AND SUS.Id_Suscripcion=:Id_Suscripcion";
			$Where = ["Entity"=>$Id_Entity,"Id_Suscripcion"=>$Id_Suscripcion]; 
			$Row_SUS = ClassPdo::DCRow($Query,$Where,$Conection);	
			$Correo = $Row_SUS->Email;
		}else{$Correo = $Correo_Previo;}


		///Comprueba si existe en la entidad	

		$Query="SELECT  UM.Email,UM.Id_User_Miembro,UM.Id_User_Creation as Id_User_User
				FROM user_miembro  UM
				WHERE  UM.Entity=:Entity AND UM.Email=:correo";
		$Where = ["Entity"=>$Id_Entity,"correo"=>$Correo]; 
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$Email_Bd = $Row->Email;
		$Id_User_User = $Row->Id_User_User;
		$Id_User_Miembro=$Row->Id_User_Miembro;

		if(empty($Email_Bd)){

							$data = array(
								'Id_Edu_Pais' => $Id_Edu_Pais,
								'Nombre' => $Name_surnames,
								'Email' => $Correo,
								'Usuario_Login' => $Correo,
								'Password' => $Password,
								'Id_User_Sexo'=> $Sexo,
								'Foto'=> $ruta,
								'Telefono'=> $Telefono,
								'Estado' => "Comprobando",
								'Id_Perfil' => 3,
								'Id_Perfil_Educacion' => 0,
								'Entity' => $Id_Entity,
								'Id_Entity' => $Id_Entity,
								'Id_User_Creation' => $User,
								'Id_User_Update' => $User,								
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user", $data, $Conection);
						    $Id_User_New = $Result["lastInsertId"];	
								
							$data = array(
								'Id_Edu_Pais' => $Id_Edu_Pais,
								'Bk_Password' => $Password,
								'Nombre' => $Name_surnames,
								'Email' => $Correo,
								'Celular' => $Telefono,
								'Telefono'=>$Telefono,
								'Foto'=> $ruta,
								'Id_Perfil' => 3,
								'Id_Perfil_Educacion' => 0,
								'Entity' => $Id_Entity,
								'Id_User_Creation' => $Id_User_New,
								'Id_User_Update' => $Id_User_New,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user_miembro", $data, $Conection);	
						    $Id_User_Miembro_New = $Result["lastInsertId"];

							$MensajeA="Se ha registrado a un nuevo usuario <br>".$Correo;
							$link_Id=$Id_User_Miembro_New;
		}else{
				
				//Actualizacion de informacion
				$reg = array(
						'Id_Edu_Pais' => $Id_Edu_Pais,
						'Nombre' => $Name_surnames,
						'Password' => $Password,
						'Id_User_Sexo'=> $Sexo,
						'Foto'=> $ruta,
						'Telefono'=> $Telefono,
						'Id_Perfil' => 3,
						'Id_Perfil_Educacion' => 0,
						'Date_Time_Update' => $DCTimeHour);
				$where = array('Id_User' => $Id_User_User,'Entity'=>$Id_Entity);
				$rg = ClassPDO::DCUpdate('user', $reg , $where, $Conection,"");
				//Actualizacion de clave BK
				$reg = array(
						'Id_Edu_Pais' => $Id_Edu_Pais,
						'Bk_Password' => $Password,
						'Nombre' => $Name_surnames,
						'Celular' => $Telefono,
						'Telefono'=>$Telefono,
						'Foto'=> $ruta,
						'Id_Perfil_Educacion' => 0,
						'Date_Time_Update' => $DCTimeHour);
				$where = array('Id_User_Miembro' => $Id_User_Miembro,'Entity'=>$Id_Entity);
				$rg = ClassPDO::DCUpdate('user_miembro', $reg , $where, $Conection,"");
				
				//TIPO DE EJECUCION
				if (!empty($Id_Suscripcion)) {
					$MensajeC="Informacion Actualizada";
					$link_Id=$Id_Suscripcion;
				}else{
					$Query="SELECT SUS.Id_User
					     	FROM suscripcion SUS
						 	WHERE SUS.Id_Edu_Almacen=:Id_Almacen AND
						 	SUS.Entity=:Entity AND SUS.Id_User=:Id_User_SUS AND SUS.Producto_Origen=:Producto_Origen";
					$Where = ["Id_Almacen"=>$Id_Edu_Almacen,"Entity"=>$Id_Entity,"Id_User_SUS"=>$Id_User_Miembro,"Producto_Origen"=>"CURSO"];
					$Row_Suscripcion = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Id_User_SuscripcionSUS = $Row_Suscripcion->Id_User;
					if (!empty($Id_User_SuscripcionSUS)) {
						$link_Id=1;
					}else{
						$link_Id=$Id_User_Miembro;
						$MensajeA="Datos Actualizados del usuario registrado <br> ".$Correo;
					}
				}

				if ($Correo_Previo!=$Email_Bd) {
					$Validacion="error";
				}


		}

		if(!empty($Id_Suscripcion)){
			$Mensaje=$MensajeC;
		}else{
			if($link_Id!=1){$Mensaje=$MensajeA;}
		}

		$Return=array(); 
		$Return['Mensaje'] = $Mensaje;
		$Return['Link_Id'] = $link_Id;
		$Return['Validacion'] = $Validacion;			
		return $Return; 
			
	}

	public function Proceso_Matricula_G($Settings) {
       	global $Conection, $DCTimeHour,$NameTable,$DCTime;
       	$User = $_SESSION['User'];
		$Id_Entity = $_SESSION['Entity'];

		$Id_Edu_Almacen = $Settings["key"];
		$Id_Suscripcion = $Settings["Id_Suscripcion"];
		$Id_Register = $Settings["Id_Register"];


		//Datos formulario
		$Id_Perfil_Educacion = DCPost("Id_Perfil_Educacion");	
		$Fecha_Inicio = DCPost("Fecha_Inicio");
		$Fecha_Fin= DCPost("Fecha_Fin");
		$Id_Modalidad_Venta_Curso = DCPost("Id_Modalidad_Venta_Curso");	
		$Visibilidad=DCPost("Visibilidad");
		
		if ($Visibilidad=="Activo") {
			$Estado=$Visibilidad;
		}else{
			$Estado="Inactivo";
		}


		$error=0;
		if (!empty($Id_Register)) {
			///Comprueba si existe la matricula	
			$Query="SELECT  SUS.Id_Suscripcion as VALID_SUS
					FROM suscripcion  SUS
					WHERE  SUS.Entity=:Entity AND SUS.Id_User=:Id_User_SUS_REGISTRE AND SUS.Producto_Origen=:Producto_Origen AND SUS.Id_Edu_Almacen=:Almacen";
			$Where = ["Entity"=>$Id_Entity,"Id_User_SUS_REGISTRE"=>$Id_Register,"Producto_Origen"=>"CURSO","Almacen"=>$Id_Edu_Almacen]; 
			$Row_SUS_REGISTER = ClassPdo::DCRow($Query,$Where,$Conection);	
			$VALID_SUS = $Row_SUS_REGISTER->VALID_SUS;
			if (empty($VALID_SUS)) {
				
					//Vinculo de matricula
					$data = array(
						'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
						'Producto_Origen'=>"CURSO",
						'Fecha_Inicio'=>$Fecha_Inicio,
						'Fecha_Fin'=>$Fecha_Fin,
						'Id_Perfil_Educacion' => $Id_Perfil_Educacion,
						'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
						'Estado' => "Matriculado",
						'Visibilidad'=>$Estado,
						'Id_User' => $Id_Register,
						'Entity' => $Id_Entity,
						'Id_User_Update' => $User,
						'Id_User_Creation' => $User,
						'Date_Time_Creation' => $DCTimeHour,
						'Date_Time_Update' => $DCTimeHour
						);
					$Return = ClassPDO::DCInsert("suscripcion", $data, $Conection);
					$Mensaje="Se ha generado la matricula en el curso";
					
				
			}
		}else if(!empty($Id_Suscripcion)){
			///Actualiza la informacion de la matricula
			$reg = array(
				'Visibilidad'=>$Estado,
				'Id_Modalidad_Venta_Curso'=>$Id_Modalidad_Venta_Curso,
				'Fecha_Inicio'=>$Fecha_Inicio,
				'Fecha_Fin'=>$Fecha_Fin,
				'Id_Perfil_Educacion' => $Id_Perfil_Educacion
			);
			$where = array('Id_Suscripcion' => $Id_Suscripcion,'Entity'=>$Id_Entity);
			$rg = ClassPDO::DCUpdate('suscripcion', $reg , $where, $Conection,"");
			$Mensaje="Se actualizo la informacion de matricula";
		}else{
			$error=1;
		}

		$Return=array(); 
		$Return['Mensaje'] = $Mensaje;
		$Return['Validacion'] = $error;			
		return $Return; 
			
	}
		
	
	
	
}