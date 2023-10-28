 <?php

require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();
$DCTime=DCDate();

class Edu_Participante_Masivo{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;

		
		$UrlFile = "/sadministrator/edu-participante-masivo";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		
		$Process = $Parm["Process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Crud_Edu_Inscripcion_Masivo":
							 $Id_Edu_Almacen = $Parm["Id_Edu_Almacen"];

						    // DCCloseModal();	
						    $Data = array();
							$Data['Id_Edu_Almacen'] = $Id_Edu_Almacen; //Edu Almacen							
							$Id_Edu_Inscripcion_Masivo=DCSave($Obj,$Conection,$Parm["Id_Edu_Inscripcion_Masivo"],"Id_Edu_Inscripcion_Masivo",$Data);
							$Id_Retorno=$Id_Edu_Inscripcion_Masivo["lastInsertId"];

							$reg = array(
							'Id_Edu_Almacen' => $Id_Edu_Almacen
							);
							$where = array('Id_Edu_Inscripcion_Masivo' =>$Id_Retorno);
							$rg = ClassPDO::DCUpdate("edu_inscripcion_masivo", $reg , $where, $Conection);

							
							$Settings["interface"] = "List";
						//	$Settings["REDIRECT"] = $Redirect;
							$Settings["key"] =$Id_Edu_Almacen;
							$Settings["Id_Edu_Inscripcion_Masivo"]=$Id_Retorno;
							
							new Edu_Participante_Masivo($Settings);
							DCExit();	
					break;	
					case "Matricula_Masiva":
							$this->Proceso_inscripciones_masivas($Parm);

							$Settings["interface"] = "Confirmar_Carga";
							$Settings["REDIRECT"] = $Redirect;
							$Settings["key"] = $Parm["Id_Edu_Almacen"];
							new Edu_Participante_Masivo($Settings);
							DCExit();
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
					case "Crud_Edu_Inscripcion_Masivo":
						
						$this->ObjectDelete($Parm);
									
						$Settings["interface"] = "List";
						$Settings["REDIRECT"] = $Parm[""];
						// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
						new Edu_Participante_Masivo($Settings);
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
		
				$Name_Interface = "Inscripciones masivas";
				$Id_Edu_Almacen = $Parm["key"];
				
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Visualizar Formato]]]POPOVER]btn btn-primary ladda-button}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/interface/Create/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT EIM.Id_Edu_Inscripcion_Masivo AS CodigoLink, EIM.Nombre_Archivo FROM edu_inscripcion_masivo EIM 
				    where EIM.Id_Edu_Almacen=:Id_Edu_Almacen
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Inscripcion_Masivo';
				$Link = $UrlFile."/interface/Confirmar_Carga/Id_Edu_Almacen/".$Id_Edu_Almacen."/Id_Edu_Inscripcion_Masivo/".$LinkId;
				$Screen = 'animatedModal5';
				$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "Create":
            	$Id_Edu_Inscripcion_Masivo = $Parm["Id_Edu_Inscripcion_Masivo"];
            	$Id_Edu_Almacen = $Parm["key"];

			
				$btn .= "Atrás]" .$UrlFile."/interface/List/key/".$Id_Edu_Almacen."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Masivo",$btn);
				
 
	           
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Crud_Edu_Inscripcion_Masivo/Id_Edu_Almacen/".$Id_Edu_Almacen;
				
				
				 $Name_Interface = "Crear Componente";					
				 $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                  					
				
				$Combobox = array(
				     array("Id_Edu_Almacen"," SELECT EA.Id_Edu_Almacen AS Id, EA.Id_Edu_Articulo AS Nombre FROM edu_almacen EA ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/archivos/doc_inscritos")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Crud_Edu_Inscripcion_Masivo"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Crud_Edu_Inscripcion_Masivo",$Class,$Id_Edu_Inscripcion_Masivo,$PathImage,$Combobox,$Buttons,"Id_Edu_Inscripcion_Masivo");

				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
            break;

            case "Confirmar_Carga":
		
		        $Id_Edu_Inscripcion_Masivo = $Parm["Id_Edu_Inscripcion_Masivo"];
		        $Id_Edu_Almacen = $Parm["Id_Edu_Almacen"];
		        $Id_Entity = $_SESSION['Entity'];
				
				$btn = "Matricular ]" .$UrlFile ."/Process/ENTRY/Obj/Matricula_Masiva/Id_Edu_Inscripcion_Masivo/".$Id_Edu_Inscripcion_Masivo."/Id_Edu_Almacen/".$Id_Edu_Almacen."]animatedModal5]HXM]]btn btn-default dropdown-toggle]}";	
				$btn .= "Cancelar ]" .$UrlFile ."/Interface/List/key/".$Id_Edu_Almacen."]animatedModal5]HXM]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');

				$Query = "
				SELECT 
				EIM.Archivo_Inscripcion 
				FROM edu_inscripcion_masivo EIM
				WHERE 
				EIM.Id_Edu_Inscripcion_Masivo = :Id_Edu_Inscripcion_Masivo
				";	
				$Where = ["Id_Edu_Inscripcion_Masivo" =>$Id_Edu_Inscripcion_Masivo];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$DocExcelInscritos = $Row->Archivo_Inscripcion;


				$NombreArchivo=$_SERVER['DOCUMENT_ROOT']."/sadministrator/archivos/doc_inscritos/".$DocExcelInscritos;	
				var_dump($NombreArchivo);		
				$Excel = ExcelExtract($NombreArchivo);
				$Array_Json = json_decode($Excel);
				$htmlList="<table id='table-1' class='table table-hover' >
				<thead><tr><th>Nombre del usuario</th><th>Email</th><th>Clave</th></tr></thead>
				<tbody>";
				$infoList="";
		        foreach($Array_Json as $key ){		
		        	
		        	$Nombre = VerificarTexto($key->Nombre);
						$Nombre_Value = $key->Nombre;
						$Email = VerificarEmail($key->Email);
						$Email_Value= $key->Email;
						$Password = $key->Clave;
						$Celular_value = $key->Celular;
						


					if($Email == "V" ){
							$Query = "
							    SELECT 
								US.Usuario_Login
								,US.Email as Correo_User
								,US.Nombre
								, US.Password
								, ET.Id_Entity
								, US.Id_User
								, UM.Id_Perfil
								, UM.Id_User_Miembro
								FROM user_miembro UM
								INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
								INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
								WHERE 
								US.Email = :Email_excel  AND ET.Id_Entity = :Id_Entity
							"; 
							$Where = ["Email_excel"=>$Email_Value,"Id_Entity"=>$Id_Entity];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Email_Bd = $Row->Correo_User;
							$Nombre = $Row->Nombre;
							$Password = $Row->Password;
							if(!empty($Email_Bd)){
								$infoList.="<tr><td>".$Nombre."</td><td>".$Email_Bd."</td><td>".$Password."</td></tr>";
							}else{
								$infoList.="";
							}

					}
				}
				if ($infoList=="") {
					 $Listado="<div><span>Todos los usuarios seran registrados</span></div>";
				}else{
					$Listado =$htmlList."".$infoList."</tbody></table>";
				}
			    $Html = DCModalFormListMsj("Deseas Matricular",$Form,$Button,"bg-info","Listado de usuarios registrados en la bd",$Listado);
                DCWrite($Html);
				
            break;	
		}
        	
				
		
		
	}
	
	public function Proceso_inscripciones_masivas($Settings) {
       	global $Conection, $DCTimeHour,$NameTable,$DCTime;
 
		$Id_Edu_Inscripcion_Masivo = $Settings["Id_Edu_Inscripcion_Masivo"];
		$Id_Edu_Almacen = $Settings["Id_Edu_Almacen"];	///Primer Nivel	

		$Query = "
				SELECT 
				EIM.Archivo_Inscripcion, EIM.Id_Edu_Almacen 
				FROM edu_inscripcion_masivo EIM
				WHERE 
				EIM.Id_Edu_Inscripcion_Masivo = :Id_Edu_Inscripcion_Masivo
				";	
		$Where = ["Id_Edu_Inscripcion_Masivo" =>$Id_Edu_Inscripcion_Masivo];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		$DocExcelInscritos = $Row->Archivo_Inscripcion;
		$NombreArchivo=$_SERVER['DOCUMENT_ROOT']."/sadministrator/archivos/doc_inscritos/".$DocExcelInscritos;
		var_dump($NombreArchivo);		
		$Excel = ExcelExtract($NombreArchivo);
		$Array_Json = json_decode($Excel);
		$Mensaje = ""; 
		$User=0;
		$Id_Entity=0;
		$count_MensajeA=0;
		$count_MensajeB=0;
		$Mensaje_error=0;
		$Mensaje_errorI=0;
		     
		foreach($Array_Json as $key ){
			$User = $_SESSION['User'];
			$Id_Entity = $_SESSION['Entity'];
			$Id_Edu_Almacen = $Settings["Id_Edu_Almacen"];
			
			//Valores de excel
			$Nombre_Value = $key->Nombres;
            $Nombre_A = VerificarTexto($key->Nombres);
            $Con_Espaciado = str_replace("-", " ",$Nombre_Value);
			$Nombre_Completo_Mayus=strtoupper($Con_Espaciado);

			
			$Email_C = VerificarEmail($key->Email);
			$Email_Valueb= $key->Email;
			$Email_Value=$Email_Valueb;
			$Passwordb = $key->Clave;
			$Passwordespacio=trim($Passwordb);
			$Password=strtoupper($Passwordespacio);

			$Celular_value = $key->Telefono;
			$Sexo= $key->Genero;
			$Sexoespacio=trim($Sexo);
			$Sexo=strtoupper($Sexoespacio);

			$ruta="";
			if ($Sexo=="MASCULINO") {
				$ruta="4ad89d8dh345s_hombre.png";
				$Genero=1;
			}else if ($Sexo=="FEMENINA") {
				$ruta="4ad89d345s_mujer.png";$Genero=2;
			}	

			if($Email_C == "V" ){
				//Verifica si es un usuario dentro  de la entidad o si existe
				$Query="SELECT  US.Email as Correo_User
								, US.Id_User
								, UM.Id_User_Miembro
								FROM user_miembro UM
								INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
								WHERE 
								UM.Email = :Email_excel  AND UM.Entity = :Id_Entity";
				$Where = ["Email_excel"=>$Email_Value,"Id_Entity"=>$Id_Entity];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Email_Bd = $Row->Correo_User;
				$Id_User_Miembro=$Row->Id_User_Miembro;
				$ID_UACTUALIZACION=$Row->Id_User;
				if(empty($Email_Bd)){
							$data = array(
								'Nombre' => $Nombre_Completo_Mayus,
								'Email' => $Email_Value,
								'Usuario_Login' => $Email_Value,
								'Password' => $Password,
								'Telefono'=>$Celular_value,
								'Id_User_Sexo'=>$Genero,
								'Foto'=>$ruta,
								'Estado' => "Comprobando",
								'Id_Perfil' => 3,
								'Id_Perfil_Educacion' => 3,
								'Entity' => $Id_Entity,
								'Id_Entity' => $Id_Entity,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user", $data, $Conection);
						    $Id_User = $Result["lastInsertId"];	
								
							$data = array(
								'Nombre' => $Nombre_Completo_Mayus,
								'Email' => $Email_Value,
								'Bk_Password'=>$Password,
								'Celular' => $Celular_value,
								'Telefono'=>$Celular_value,
								'Foto'=>$ruta,
								'Id_Perfil' => 3,
								'Id_Perfil_Educacion' => 3,
								'Entity' => $Id_Entity,
								'Id_User_Creation' => $Id_User,
								'Id_User_Update' => $Id_User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
								);
							$Result = ClassPDO::DCInsert("user_miembro", $data, $Conection);	
						    $Id_User_Miembro_New = $Result["lastInsertId"];

						    //Vinculo de matricula
							$data = array(
								'Producto_Origen'=>"CURSO",
								'Visibilidad'=>"Activo",
								'Id_Perfil_Educacion' => 3,
								'Id_Modalidad_Venta_Curso'=>1,
								'Fecha_Inicio'=>$DCTime,
								'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
								'Estado' => "Matriculado",
								'Id_User' => $Id_User_Miembro_New,
								'Entity' => $Id_Entity,
								'Id_User_Update' => $User,
								'Id_User_Creation' => $User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
							);
							ClassPDO::DCInsert("suscripcion", $data, $Conection);
							$count_MensajeA+=1;
				}else{
					//Verifica si el alumno existe en el curso 
					$Query = "
							    SELECT  SUS.Id_Edu_Almacen,US.Email as Correo_User
								FROM suscripcion SUS
				                inner join user_miembro UM on SUS.Id_User=UM.Id_User_Miembro
				                INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
								WHERE 
								US.Email =:Email_excel AND SUS.Entity =:Id_Entity and SUS.Id_Edu_Almacen=:Id_Edu_Almacen AND SUS.Producto_Origen=:TIPO_ORIGEN"; 
					$Where = ["Email_excel"=>$Email_Value,"Id_Entity"=>$Id_Entity,"Id_Edu_Almacen"=>$Id_Edu_Almacen,"TIPO_ORIGEN"=>"CURSO"];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Email_Bd2 = $Row->Correo_User;
					if (empty($Email_Bd2)) {
						//Vinculo de matricula
						$data = array(
								'Visibilidad'=>"Activo",
								'Fecha_Inicio'=>$DCTime,
								'Id_Modalidad_Venta_Curso'=>1,
								'Producto_Origen'=>"CURSO",
								'Id_Perfil_Educacion' => 3,
								'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
								'Estado' => "Matriculado",
								'Id_User' => $Id_User_Miembro,
								'Entity' => $Id_Entity,
								'Id_User_Update' => $User,
								'Id_User_Creation' => $User,
								'Date_Time_Creation' => $DCTimeHour,
								'Date_Time_Update' => $DCTimeHour
							);
						$Return = ClassPDO::DCInsert("suscripcion", $data, $Conection);	
						//Actualiza  la clave y numero del usuario
						$reg_update_us = array('Password' => $Password,'Telefono'=>$Celular_value,'Nombre' => $Nombre_Completo_Mayus);
						$where_update_us = array('Id_User' => $ID_UACTUALIZACION);
						ClassPDO::DCUpdate('user', $reg_update_us , $where_update_us, $Conection,"");
						// actualizacion en user miembro
						$reg_update_UM = array('Bk_Password'=>$Password,'Telefono'=>$Celular_value,'Celular'=>$Celular_value,'Nombre' => $Nombre_Completo_Mayus);
						$where_update_UM = array('Id_User_Miembro' => $Id_User_Miembro);
						ClassPDO::DCUpdate('user_miembro', $reg_update_UM , $where_update_UM, $Conection,"");	

						$count_MensajeB+=1;
					}else{
						$Mensaje_error+=1;
					}
					$Mensaje_errorI+=1;
				}
			}		
		}
		if (($Mensaje_errorI==0)AND ($Mensaje_error==0)) {
			$MensajeE="No hay ningun problema en el registro";
			$estado="C";
		}else{
			$MensajeE="No se ha podido registrar ".$Mensaje_error." ni crear a ".$Mensaje_errorI." nuevos usuarios";
			$estado="E";
		}

		
		DCWrite(Message("Se han registrado ".$count_MensajeA." nuevos usuarios y ".$count_MensajeB." usuarios al curso","C")); 
		DCWrite(Message($MensajeE,"C"));  
	}


	
	
	
}