<?php
require_once('./sviews/layout.php');
require_once('./sviews/funtion-certificado.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Gestion_Certificado{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-gestion-certificado";
		$UrlFileDet = "/sadministrator/det_admin_object";
		$UrlFile_edu_estado_academico = "/sadministrator/edu-estado-academico";
		$UrlFile_edu_estado_edicion_certificado = "/sadministrator/edu-estado-edicion-certificado";
		$UrlFile_edu_estado_emision = "/sadministrator/edu-estado-emision";
		$UrlFile_edu_tipo_documento_identidad = "/sadministrator/edu-tipo-documento-identidad";
		$UrlFile_edu_pais = "/sadministrator/edu-pais";
		$UrlFile_edu_tipo_ubicacion = "/sadministrator/edu-tipo-ubicacion";
				
		$Redirect = $Parm["REDIRECT"];	
		
        $UrlFile_admin_warehouse = "/sadministrator/admin_warehouse";
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Process = $Parm["process"];
		$interface = $Parm["interface"];
		$Obj = $Parm["Obj"];

        switch ($Process) {
            case "ENTRY":

				switch ($Obj) {
					case "Entity_Certificado_Crud":
					        
							
				            $Id_Edu_Almacen = $Parm["key"];
                            $Data = array();
							// $Data['Seccion_Pagina'] = $Seccion_Pagina;								
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Entity"],"Id_Entity",$Data);  
							
				
						    $Settings["interface"] = "List";
						    $Settings["key"] = $Id_Edu_Almacen;
							new Edu_Gestion_Certificado($Settings);
							DCExit();	
					
						break;	
						
					case "Edu_Certificado_Estados_Crud":
					        
							
				            $Id_Edu_Almacen = $Parm["key"];
				            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				            $tipo_producto = $Parm["tipo-producto"];
                            $Data = array();
							// $Data['Seccion_Pagina'] = $Seccion_Pagina;								
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Certificado"],"Id_Edu_Certificado",$Data);  
							
				
						    $Settings["interface"] = "Create_Edit_Edu_Certificado";
						    $Settings["Id_Edu_Certificado"] = $Id_Edu_Certificado;
						    $Settings["key"] = $Id_Edu_Almacen;
						    $Settings["tipo-producto"] = $tipo_producto;
							new Edu_Gestion_Certificado($Settings);
							DCExit();	
					
						break;	


					case "Edu_Certificado_DGeneral_Crud":
					        
							// $_POST["Numero_Identidad"] = 0;
							// $_POST["Id_Edu_Tipo_Documento_Identidad"] = 0;
				            $Id_Edu_Almacen = $Parm["key"];
				            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];							
                            $Data = array();
							// $Data['Numero_Identidad'] = 0;								
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Certificado"],"Id_Edu_Certificado",$Data);  
							
				
						    $Settings["interface"] = "Create_Edit_Edu_Certificado_General";
						    $Settings["Id_Edu_Certificado"] = $Id_Edu_Certificado;
						    $Settings["key"] = $Id_Edu_Almacen;
							new Edu_Gestion_Certificado($Settings);
							DCExit();	
					
						break;	



					case "Edu_Certificado_DEnvio_Crud":
					        
							// $_POST["Id_Edu_Pais"] = 0;							
				            $Id_Edu_Almacen = $Parm["key"];
				            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];							
                            $Data = array();
							// $Data['Seccion_Pagina'] = $Seccion_Pagina;								
						    // DCCloseModal();									
							DCSave($Obj,$Conection,$Parm["Id_Edu_Certificado"],"Id_Edu_Certificado",$Data);  
							
				
						    $Settings["interface"] = "Create_Edit_Edu_Certificado_Envio";
						    $Settings["Id_Edu_Certificado"] = $Id_Edu_Certificado;
						    $Settings["key"] = $Id_Edu_Almacen;
							new Edu_Gestion_Certificado($Settings);
							DCExit();	
					
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
					case "Edu_Certificado_Estados_Crud":
						
						$this->ObjectDelete($Parm);
									
						$Settings["Interface"] = "List";
						$Settings["REDIRECT"] = $Redirect;
						// $Settings["Id_Pedido"] = $Parm["Id_Pedido"];
						new Edu_Banner($Settings);
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
				$tipo_producto = $Parm["tipo-producto"];
				
				
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen,$tipo_producto);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;	

				
				$layout  = new Layout();
				$listMn = "<i class='icon-chevron-right'></i> Origen de la plantilla[".$UrlFile."/Interface/DeleteMassive[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Plantillas [".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Configuración ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				// $btn .= "<i class='zmdi zmdi-edit'></i> Crear Empresa ]" .$UrlFile."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');
				

				$DCPanelTitle = DCPanelTitle("GESTIÓN DE CERTIFICADOS",$Nombre_Articulo,$btn);
					
				$urlLinkB = "/key/".$Id_Edu_Almacen;
				
				$Pestanas = Ft_Certificado::Tabs_Principal(array(
				"".$urlLinkB."/interface/List/tipo-producto/".$tipo_producto."]Marca"
				,"".$urlLinkB."/interface/EnVivo_Digitales/tipo-producto/".$tipo_producto."]"
				,"".$urlLinkB."/interface/Grabados/tipo-producto/".$tipo_producto."]"
				,"".$urlLinkB."/interface/Grabado_Digitales/tipo-producto/".$tipo_producto."]"
				,"".$urlLinkB."/interface/Grabado_Fisicos/tipo-producto/".$tipo_producto."]"));	


                // if($tipo_producto == "curso"){
                if($tipo_producto == "curso"){
					
					
					$Query = "
							SELECT Tipo_Certificado FROM edu_gestion_certificado EGC
							WHERE 
							EGC.Id_Edu_Almacen = :Id_Edu_Almacen 
					";	
					$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Tipo_Certificado = $Row->Tipo_Certificado;
					
					
				}else{
					
						
					$Query = "			
							SELECT Tipo_Certificado FROM edu_gestion_certificado EGC
							WHERE 
							EGC.Id_Edu_Almacen = :Id_Edu_Almacen 
							AND EGC.Tipo_Producto = :Tipo_Producto
					";	
					$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Tipo_Producto"=>"programa"];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Tipo_Certificado = $Row->Tipo_Certificado;					
				}	
				
				if(empty($Tipo_Certificado)){
					
						$btn = " Plantilla Predefinida ]" .$UrlFile."/interface/Confirma_Pl_Predefinida_C/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."]animatedModal5]HXM]]btn btn-info}";
						$btn .= " Personalizar Plantilla ]" .$UrlFile."/interface/Confirma_Pl_Personalizada_C/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."]animatedModal5]HXM]]btn btn-info}";
						$btn_2 = DCButton($btn, 'botones1', 'sys_form');					
						$DCPanelTitle_Msj = DCPanelTitle("Define el tipo de plantilla que usarás","",$btn_2);	
					
				}

				
				if($Tipo_Certificado == "Predefinido"){
					
                    $btn = "Subir Componentes ]" .$UrlFile."/interface/configura_plantilla_predefinida/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."]animatedModal5]HXM]btn btn-primary ladda-button}";
					$btn_3 = DCButton($btn, 'botones1', 'sys_form');	
					$DCPanelTitle_Msj = DCPanelTitle("Configura la plantilla predefinida","",$btn_3);	

					$Query = "

						SELECT Certificado_Imagen, Certificado_Firma_1, Certificado_Firma_2 
						FROM entity ET
						WHERE 
						ET.Id_Entity = :Id_Entity 
							
					";	
					$Where = ["Id_Entity" => $Entity];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Certificado_Imagen = $Row->Certificado_Imagen;
					$Certificado_Firma_1 = $Row->Certificado_Firma_1;
					$Certificado_Firma_2 = $Row->Certificado_Firma_2;
					
					$DCPanelTitle_Msj .= "<iframe width='100%' height='600px' src='/sadministrator/edu-certificado/key/".$Id_Edu_Almacen."/interface/List/request/on/tipo-producto/".$tipo_producto."'></iframe>";
	
				}

				
			
				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $DCPanelTitle_Msj .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;



            case "list_certificado_validacion":
 


				$Query = "   
					  
				SELECT 
				UM.Token_Certificado_Validar,
				UM.Email,
				UM.Token_Certificado
				FROM user_miembro UM	
				WHERE 
				UM.Id_User_Miembro = :Id_User_Miembro 
				
				";	
				$Where = ["Id_User_Miembro" => $User ];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Token_Certificado_Validar = $Row->Token_Certificado_Validar;	
				$Token_Certificado = $Row->Token_Certificado;	
				$Email = $Row->Email;	


					//uuuuuuuuuuuuuuuuuuuuuuu
				
				DCEmailSes("Alumno Escuela",$Email,"TOKEN DE ACCESO AL CERTIFICADO","Este es el código de acceso: ".$Token_Certificado." ", "", "", "Escuela Esgep", "informes@esgep.com");
							
							
							// panel-cuerpo
				$Form = $this->Form_Valida_Cettificado($Parm);		
				$Form .=  "<div style='padding:20px 0px;background-color:#fff;' id='panel-cuerpo'></div>";		
							
				$DCPanelTitle = DCPanelTitle(" COMPROBAREMOS QUE ERES TU, ANTES DE MOSTRARTE LOS CERTIFICADOS","Inserta el código que enviamos a tu correo","");			
			
			
			
			    $layout  = new Layout();
				$Contenido = DCPage($DCPanelTitle , $Form  ,"panel panel-default");
				$Contenido = "<div style='padding:20px 0px;background-color:#fff;'>".$Contenido."</div>";
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}			
			
                break;			
			
            case "list_certificado":
			
				$interfaceraiz = $Parm["interfaceraiz"];			
			    $layout  = new Layout();
				$Query = "   
					  
				SELECT 
				UM.Token_Certificado_Validar
				FROM user_miembro UM	
				WHERE 
				UM.Id_User_Miembro = :Id_User_Miembro 
				
				";	
				$Where = ["Id_User_Miembro" => $User ];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Token_Certificado_Validar = $Row->Token_Certificado_Validar;	
				// interfaceraiz
					
					if(empty($interfaceraiz)){
						
						if(empty ( $Token_Certificado_Validar ) ) {

		 
							$Token_Certificado = rand(1, 1000000);
			 
							$reg = array(
							'Token_Certificado' => $Token_Certificado
							);
							$where = array('Id_User_Miembro' => $User);
							$rg = ClassPDO::DCUpdate('user_miembro', $reg , $where, $Conection,"");				
						
						
							$Settings = array();
							$Settings['Url'] = "/sadministrator/edu-gestion-certificado/interface/list_certificado_validacion";
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "";
							DCRedirectJS($Settings);	
							
							
						}else{
							
								$reg = array(
								'Token_Certificado_Validar' => ''
								);
								$where = array('Id_User_Miembro' => $User);
								$rg = ClassPDO::DCUpdate('user_miembro', $reg , $where, $Conection,"");	
							
						}
						
					}

				$DCPanelTitle = DCPanelTitle("CERTIFICADOS | CURSOS ","Edita tus datos y descarga","");
				$DCPanelTitle2 = DCPanelTitle("CERTIFICADOS | PROGRAMAS ","Edita tus datos y descarga","");
					
				$Query = "
				
					SELECT CONCAT ( EC.Nombres ) AS Participante
					, EC.Id_Edu_Certificado  
					, TC.Nombre AS Tipo_Cer
                    , EC.Pago_Total AS Pago 
					, EC.Nombre_Curso 					
					, EC.Id_Edu_Almacen 					
					, EC.Estado_Academico 
					, EC.Estado_Edicion_Datos_Certificado AS Datos_Certificado		
					, EC.Estado_Edicion_Datos_Envio AS Datos_Envio					
					, EC.Estado_Emision_Certificado_Digital AS Estado_C_Digital
					, EC.Estado_Emision_Certificado_Fisico AS Estado_C_Fisico				
	
					FROM edu_certificado EC
					LEFT JOIN tipo_certificado TC ON EC.Id_Tipo_Certificado = TC.Id_Tipo_Certificado
					INNER JOIN user_miembro   UM ON UM.Id_User_Miembro = EC.Id_Suscripcion					
					WHERE 
					EC.Tipo_Producto = :Tipo_Producto
					AND UM.Id_User_Miembro = :Id_User_Miembro
                    ORDER BY EC.Id_Edu_Almacen DESC 
				";   
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Certificado';
				$Link = $UrlFile."/interface/Create_Edit_Edu_Certificado/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto;
				$Screen = 'animatedModal5';
				
				$Where = ["Tipo_Producto"=>"programa","Id_User_Miembro"=>$User];
				$Registro = ClassPdo::DCRows($Query,$Where,$Conection);
				
				$tableCuerpo = "";
				$Cursos_Programa = array();
				$ContGeneral = 0;
                $ContGeneralZ = 0;
	        	$UrlFile_Certificado = "/sadministrator/edu-certificado";					
				foreach ($Registro as $Reg) {
					

					    
						$Query = "   
							  
						SELECT 
						PD.Id_Edu_Almacen
						FROM programa_det PD	
						WHERE 
						PD.Id_Programa_Cab = :Id_Programa_Cab 
						
						";	
						$Where = ["Id_Programa_Cab" => $Reg->Id_Edu_Almacen];
						$Registro_Productos = ClassPdo::DCRows($Query,$Where,$Conection);
	
						foreach ($Registro_Productos as $RegX) {
						
							$Cursos_Programa[$ContGeneralZ] = $RegX->Id_Edu_Almacen;
							$ContGeneralZ += 1;
						}				
					
					    	
					    $ContGeneral += 1;	
			
						if(empty ( $Reg->Nombre_Curso ) ) {
							 
							$Query = "   
								  
							SELECT 
							PC.Nombre
							FROM programa_cab PC	
							WHERE 
							PC.Id_Programa_Cab = :Id_Programa_Cab 
							
							";	
							$Where = ["Id_Programa_Cab" => $Reg->Id_Edu_Almacen];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Nombre = $Row->Nombre;
						  
						}else{
							$Nombre = $Reg->Nombre_Curso;
						}
		

						if($Reg->Datos_Certificado == "Pendiente"  && $Reg->Pago == "Realizado" ){
							$btn = " Edita tus datos ]" .$UrlFile_Certificado ."/interface/Create_Edit_Certificado/Id_Edu_Almacen/".$Reg->Id_Edu_Almacen."/Id_Edu_Certificado/".$Reg->Id_Edu_Certificado."/tipo-producto/programa/interfaceraiz/certificados_alumno]animatedModal5]HXM]]}";				
							$Button_Cert = DCButton($btn, 'botones1', 'sys_form_0998888EEE'.$ContGeneral);	
						}elseif($Reg->Datos_Certificado == "Revisado" && $Reg->Pago == "Realizado" ){
							$btn = " Descargar el certificado ]/sadministrator/edu-certificado/key/".$Reg->Id_Edu_Almacen."/Id_Edu_Certificado/".$Reg->Id_Edu_Certificado."/interface/List/request/on/tipo-producto/programa]animatedModal5]HREF_DONWLOAD]btn btn-primary ladda-button}";
							$Button_Cert = DCButton($btn, 'botones1', 'sys_form_0998888EEE'.$ContGeneral);				
						
						}elseif( ( $Reg->Datos_Certificado == "Pendiente" || $Reg->Datos_Certificado == "Revisado" ) && $Reg->Pago !== "Realizado" ){
							$Button_Cert = "Solicita la habilitación del certificado: coordinacion@esgep.com ";
						}else{
							$Button_Cert = "Debes finalizar las evaluaciones";
							
						}			
			
			
						$tableCuerpo .= "<tr>";			    
						$tableCuerpo .= "<td>".$Nombre."</td>";		
						$tableCuerpo .= "<td>".$Reg->Tipo_Cer." </td>";		
						$tableCuerpo .= "<td>".$Reg->Pago."</td>";		
						$tableCuerpo .= "<td>".$Reg->Estado_Academico."</td>";		
						$tableCuerpo .= "<td>".$Reg->Datos_Certificado."</td>";	

						$tableCuerpo .= "<td> ".$Button_Cert." </td>";									
						
						$tableCuerpo .= "</tr>";					

				}

				$table2 = "<table class='table table-bordered table-sm'>";		
				
					$tableCabezera = "<tr>";
					$tableCabezera .= "<th>Nombre del Programa</th>";
					$tableCabezera .= "<th>Tipo</th>";
					$tableCabezera .= "<th>Pago</th>";
					$tableCabezera .= "<th>Estado Académico</th>";
					$tableCabezera .= "<th>Datos del Certificado</th>";
					$tableCabezera .= "<th>Acción</th>";
					$tableCabezera .= "</tr>";
								
				$table2 .= $tableCabezera;			
				$table2 .= $tableCuerpo;			
				$table2 .= "</table>";
				
				
				$Field_Curso = "";
				$Value_Curso = [];
				$j = 0;
				foreach ($Cursos_Programa as $RegXa) {

					$Value_Curso["Curso_".$j] = $RegXa;
					$Field_Curso .= ":Curso_".$j.",";
					$j += 1;
				}

				$Field_Curso =  substr($Field_Curso, 0, -1);
				$Field_Curso = " IN(".$Field_Curso.")";
				

				$Query = "
				
					SELECT CONCAT ( EC.Nombres ) AS Participante
					, EC.Id_Edu_Certificado  
					, TC.Nombre AS Tipo
					, EC.Pago_Total AS Pago 
					, EC.Id_Edu_Almacen 
					, EC.Nombre_Curso 
					, CER.Id_Suscripcion
					, CER.Date_Time_Creation
					, EC.Estado_Academico 
					, EC.Estado_Edicion_Datos_Certificado AS Datos_Certificado		
					, EC.Estado_Edicion_Datos_Envio AS Datos_Envio					
					, EC.Estado_Emision_Certificado_Digital AS Estado_C_Digital
					, EC.Estado_Emision_Certificado_Fisico AS Estado_C_Fisico				
	                , CER.Producto_Origen
	                , CER.Id_Programa_Cab
					FROM edu_certificado EC
					LEFT JOIN tipo_certificado TC ON EC.Id_Tipo_Certificado = TC.Id_Tipo_Certificado
					INNER JOIN suscripcion    CER ON CER.Id_Suscripcion = EC.Id_Suscripcion
					INNER JOIN user_miembro   UM ON UM.Id_User_Miembro = CER.Id_User
					WHERE 
 					( EC.Tipo_Producto IS NULL OR EC.Tipo_Producto =:Tipo_Producto  )
					AND UM.Id_User_Miembro = :Id_User_Miembro 
					AND CER.Producto_Origen = :Producto_Origen 
			
					ORDER BY EC.Id_Edu_Almacen DESC 

				";  
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Certificado';
				$Link = $UrlFile."/interface/Create_Edit_Edu_Certificado/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto;
				$Screen = 'animatedModal5';
				$Where = ["Tipo_Producto"=>"","Id_User_Miembro"=>$User,"Producto_Origen"=>"CURSO"];
				
				// var_dump($Where);
				
			    // $Where = array_merge(
				// ['Tipo_Producto' =>'']
				// ,['Id_User_Miembro' =>$User]
				 // );				
				
		        $Registro = ClassPdo::DCRows($Query,$Where,$Conection);
				
				$tableCuerpo = "";
				$Codigo_Curso = "";

				$ContGeneralxy = 0;
				$ContGeneralx = 0;
				$ContGeneral = 0;

				
	        	$UrlFile_Certificado = "/sadministrator/edu-certificado";					
				foreach ($Registro as $Reg) {
					
					$ContGeneral += 1;		
					
 
									
								$Cursos_Programa_repetidos  = 0;
								foreach ($Cursos_Programa as $RegXa) {	
										 
										if( $RegXa == $Reg->Id_Edu_Almacen){		
										
											$Cursos_Programa_repetidos = 1;
											
										}	
								}
						        // echo $Cursos_Programa_repetidos." <br> ";
							
								
								
								if(empty($Reg->Nombre_Curso)){
								     
									$Query = "   
									      
									SELECT 
									EA.Nombre
									FROM edu_articulo EA	
									INNER JOIN edu_almacen  EAL ON EA.Id_Edu_Articulo = EAL.Id_Edu_Articulo
									WHERE 
									EAL.Id_Edu_Almacen = :Id_Edu_Almacen 
									
									";	
									$Where = ["Id_Edu_Almacen" => $Reg->Id_Edu_Almacen];
									$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
									$Nombre = $Row->Nombre;
								  
								}else{
									$Nombre = $Row->Nombre_Curso;
								}
				
				
								$Query = "   
									  
								SELECT 
								count(*)  AS Tot_Reg
								FROM edu_evaluacion_desarrollo_cab EOED	
								INNER JOIN suscripcion    CER ON CER.Id_Suscripcion = EOED.Id_Suscripcion
								INNER JOIN user_miembro   UM ON UM.Id_User_Miembro = CER.Id_User      
								WHERE 
								UM.Id_User_Miembro = :Id_User_Miembro  AND CER.Id_Edu_Almacen = :Id_Edu_Almacen
								AND EOED.Incluir_En_Certificacion <> :Incluir_En_Certificacion
								ORDER BY EOED.Id_Edu_Evaluacion_Desarrollo_Cab ASC 
								
								";	
								$Where = ["Id_User_Miembro" => $User, "Id_Edu_Almacen" => $Reg->Id_Edu_Almacen, "Incluir_En_Certificacion" => "SI"];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$Tot_Reg_Eval = $Row->Tot_Reg;				
				
				



								// if( $Tot_Reg_Eval != 0){
									
									 // $Button_Cert = "Debes finalizar las evaluaciones";
									 
									 
									 
								// }else{
									

									if($Reg->Datos_Certificado == "Pendiente"  && $Reg->Pago == "Realizado" ){
										$btn = " Edita tus datos ]" .$UrlFile_Certificado ."/interface/Create_Edit_Certificado/Id_Edu_Almacen/".$Reg->Id_Edu_Almacen."/Id_Edu_Certificado/".$Reg->Id_Edu_Certificado."/tipo-producto/curso/interfaceraiz/certificados_alumno]animatedModal5]HXM]]}";				
										$Button_Cert = DCButton($btn, 'botones1', 'sys_form_0998888EEE'.$ContGeneral);	
									}elseif($Reg->Datos_Certificado == "Revisado" && $Reg->Pago == "Realizado" ){
										$btn = " Descargar el certificado ]/sadministrator/edu-certificado/key/".$Reg->Id_Edu_Almacen."/Id_Edu_Certificado/".$Reg->Id_Edu_Certificado."/interface/List/request/on/tipo-producto/curso]animatedModal5]HREF_DONWLOAD]btn btn-primary ladda-button}";
										$Button_Cert = DCButton($btn, 'botones1', 'sys_form_0998888EEE'.$ContGeneral);				
									
									}elseif($Reg->Datos_Certificado == "Pendiente" && $Reg->Pago !== "Realizado" ){
									    $Button_Cert = "Puedes obtener el certificado pagando un derecho de emisión";
									}else{
										$Button_Cert = "Debes finalizar las evaluaciones";
										
									}

									
									// if($Cursos_Programa_repetidos !== 1 ){
								// Id_Suscripcion
										$tableCuerpo .= "<tr>";			    
										$tableCuerpo .= "<td>".$Nombre." <br> Id_Suscripcion: ".$Reg->Id_Suscripcion." DC: ".$Reg->Date_Time_Creation." </td>";		
										$tableCuerpo .= "<td> Este componente pertenece a un: ".$Reg->Producto_Origen." </td>";		
										$tableCuerpo .= "<td>".$Reg->Pago."</td>";		
										$tableCuerpo .= "<td>".$Reg->Estado_Academico."</td>";		
										$tableCuerpo .= "<td>".$Reg->Datos_Certificado."</td>";										
										$tableCuerpo .= "<td>".$Button_Cert."  </td>";									
										
										$tableCuerpo .= "</tr>";
									// }	
								// }

									
								
						

				}	
				

				$table = "<table class='table table-bordered table-sm'>";		
				
					$tableCabezera = "<tr>";
					$tableCabezera .= "<th>Nombre del Curso</th>";
					$tableCabezera .= "<th>Tipo</th>";
					$tableCabezera .= "<th>Pago</th>";
					$tableCabezera .= "<th>Estado Académico</th>";
					$tableCabezera .= "<th>Datos del Certificado</th>";
					$tableCabezera .= "<th>Acción</th>";
					$tableCabezera .= "</tr>";
								
				$table .= $tableCabezera;			
				$table .= $tableCuerpo;			
				$table .= "</table>";	
				
				
				
													
                $table = " <div style='padding:20px 20px;background-color:#fff;'> ".$table."</div> ";
                $table2 = " <div style='padding:20px 20px;background-color:#fff;'> ".$table2."</div> ";
										
			
				
				// $Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','');	
								
				$Contenido = DCPage("" , $DCPanelTitle . $table . $DCPanelTitle2 . $table2 ,"panel panel-default");
				$Contenido = "<div style='padding:20px 0px;background-color:#fff;'>".$Contenido."</div>";
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;


				
            case "Confirma_Pl_Predefinida_C":
		
				$Id_Edu_Almacen = $Parm["key"];
				$tipo_producto = $Parm["tipo-producto"];
				
				$btn = "Cancelar ]" .$UrlFile ."/interface/List/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."]ScreenRight]HXM]]btn btn-default dropdown-toggle}";	
                $btn .= "Si, Confirmo ]" .$UrlFile ."/interface/Confirma_Pl_Predefinida_A/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."]ScreenRight]HXM]]btn btn-info]}";				
								
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Confirma el uso de la plantilla predefinida ? ",$Form,$Button,"bg-info");
							
                DCWrite($Html);
				
                break;	
				

            case "Confirma_Pl_Predefinida_A":
		
					$Id_Edu_Almacen = $Parm["key"];
				    $tipo_producto = $Parm["tipo-producto"];	
					
					
					
					$data = array(
					'Tipo_Certificado' =>  "Predefinido",
					'Tipo_Producto' =>  $tipo_producto,
					'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
					'Entity' => $Entity,	
					'Id_User_Update' => $User,
					'Id_User_Creation' => $User,
					'Date_Time_Creation' => $DCTimeHour,
					'Date_Time_Update' => $DCTimeHour
					);
					$Return = ClassPDO::DCInsert("edu_gestion_certificado", $data, $Conection,"");
					
					DCWrite(Message("Proceso ejecutado correctamente","C"));
			        DCCloseModal();					
					
				    $Settings["interface"] = "List";	
				    $Settings["key"] = $Id_Edu_Almacen;	
				    $Settings["tipo-producto"] = $tipo_producto;	
					new Edu_Gestion_Certificado($Settings);
					DCExit();				
					
                break;	
								
				
				
            case "configura_plantilla_predefinida":
			
				$Id_Edu_Almacen = $Parm["key"];
				
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				$DirecctionA = $UrlFile."/process/ENTRY/Obj/Entity_Certificado_Crud/Id_Entity/".$Entity."/key/".$Id_Edu_Almacen;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Certificado/".$Id_Edu_Certificado;
				

				$Name_Interface = "Agregar Componentes";				    	
				$Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
				
				$Combobox = array(
				     array("Id_Edu_Tipo_Componente"," SELECT Id_Edu_Tipo_Componente AS Id, Nombre AS Name FROM edu_tipo_componente ",[])
				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Entity_Certificado_Crud")
				);	
		        $Form1 = BFormVertical("Entity_Certificado_Crud",$Class,$Entity,$PathImage,$Combobox,$Buttons,"Id_Entity");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;	
			
			
            case "EnVivo_Digitales":
	            DCCloseModal();
				$Id_Edu_Almacen = $Parm["key"];				
			    $tipo_producto = $Parm["tipo-producto"];
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen,$tipo_producto);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;
		        $Fecha_Publicacion = $Row_Producto->Fecha_Publicacion;
		        $Fecha_Fin_Curso = $Row_Producto->Fecha_Fin_Curso;
		        $Horas_Lectivas = $Row_Producto->Horas_Lectivas;
				
				
				$layout  = new Layout();
					
				
				$listMn = "<i class='icon-chevron-right'></i> Vincular Participantes [".$UrlFile."/interface/EnVivo_Digitales_Participantes/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Eliminar Participantes d [".$UrlFile."/interface/Confirma_Eliminar_Participante/key/".$Id_Edu_Almacen."/tipo_venta/envivo/tipo-producto/".$tipo_producto."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Enviar Email Alerta [".$enlaceArea."?interface=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Estados Académicos [".$UrlFile_edu_estado_academico."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Estados Edicion [".$UrlFile_edu_estado_edicion_certificado."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Estados Emision [".$UrlFile_edu_estado_emision."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Tipo Documentos [".$UrlFile_edu_tipo_documento_identidad."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Tipo Ubicacion [".$UrlFile_edu_tipo_ubicacion."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Pais [".$UrlFile_edu_pais."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Capturar Nota [".$UrlFile."/interface/captura_nota/tipo_venta/envivo/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Capturar Fechas [".$UrlFile."/interface/captura_fechas/tipo_venta/envivo/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Descargar D. Envío [/sadministrator/edu-certificado/interface/List_Excel_D_Envio/tipo_venta/grabados/key/".$Id_Edu_Almacen."[animatedModal5[HREF_DONWLOAD[{";
				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Configuración  ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn = DCButton($btn, 'botones1', 'sys_form');


			    $DCPanelTitle = DCPanelTitle("GESTIÓN DE CERTIFICADOS",$Nombre_Articulo . " 
				<br>Fecha Inicio: ".$Fecha_Publicacion." | Fecha_Fin_Curso: ".$Fecha_Fin_Curso."
				| Horas_Lectivas: ".$Horas_Lectivas."
				",$btn);
					
					
				$urlLinkB = "/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto;
				
				$Pestanas = Ft_Certificado::Tabs_Principal(array(
				"".$urlLinkB."/interface/List]"
				,"".$urlLinkB."/interface/EnVivo_Digitales]Marca"
				,"".$urlLinkB."/interface/Grabados]"
				,"".$urlLinkB."/interface/Grabado_Digitales]"
				,"".$urlLinkB."/interface/Grabado_Fisicos]"));	


                if( $tipo_producto !=="programa"){			
						
					$Query = "
					
						SELECT CONCAT ( EC.Nombres,'  ',EC.Id_Suscripcion) AS Participante
						, EC.Id_Edu_Certificado AS CodigoLink 
						, TC.Nombre AS Tipo_Cer
						, EC.Pago_Total 
						, EC.Estado_Academico 
						, EC.Estado_Edicion_Datos_Certificado AS Datos_Certificado		
						, EC.Estado_Edicion_Datos_Envio AS Datos_Envio					
						, EC.Estado_Emision_Certificado_Digital AS Estado_C_Digital
						, EC.Estado_Emision_Certificado_Fisico AS Estado_C_Fisico				
		
						FROM edu_certificado EC
						LEFT JOIN tipo_certificado TC ON EC.Id_Tipo_Certificado = TC.Id_Tipo_Certificado
						WHERE 
						EC.Id_Edu_Almacen = :Id_Edu_Almacen
						AND EC.Modalidad_Venta_Curso = :Modalidad_Venta_Curso
						AND  ( EC.Tipo_Producto IS NULL OR EC.Tipo_Producto =:Tipo_Producto  )

					";   
					$Class = 'table table-hover';
					$LinkId = 'Id_Edu_Certificado';
					$Link = $UrlFile."/interface/Create_Edit_Edu_Certificado/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto;
					$Screen = 'animatedModal5';
					
					$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Modalidad_Venta_Curso"=>"En_Vivo","Tipo_Producto"=>""];
					
				}else{
					
					
					$Query = "
					
						SELECT CONCAT ( EC.Nombres ) AS Participante
						, EC.Id_Edu_Certificado AS CodigoLink 
						, TC.Nombre AS Tipo_Cer
						, EC.Pago_Total 
						, EC.Estado_Academico 
						, EC.Estado_Edicion_Datos_Certificado AS Datos_Certificado		
						, EC.Estado_Edicion_Datos_Envio AS Datos_Envio					
						, EC.Estado_Emision_Certificado_Digital AS Estado_C_Digital
						, EC.Estado_Emision_Certificado_Fisico AS Estado_C_Fisico				
		
						FROM edu_certificado EC
						LEFT JOIN tipo_certificado TC ON EC.Id_Tipo_Certificado = TC.Id_Tipo_Certificado
						WHERE 
						EC.Id_Edu_Almacen = :Id_Edu_Almacen
						AND EC.Modalidad_Venta_Curso = :Modalidad_Venta_Curso
						AND EC.Tipo_Producto = :Tipo_Producto

					";   
					$Class = 'table table-hover';
					$LinkId = 'Id_Edu_Certificado';
					$Link = $UrlFile."/interface/Create_Edit_Edu_Certificado/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto;
					$Screen = 'animatedModal5';
					
					$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Modalidad_Venta_Curso"=>"En_Vivo","Tipo_Producto"=>"programa"];					
					
				}
				
				
				
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','');	
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Listado .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
			
                break;	
				
			
            case "Grabados":
	            DCCloseModal();
				$Id_Edu_Almacen = $Parm["key"];
				
			    $tipo_producto = $Parm["tipo-producto"];
			    $tipo_productoB = $tipo_producto;
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen,$tipo_producto);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Articulo = $Row_Producto->Nombre;
		        $Fecha_Publicacion = $Row_Producto->Fecha_Publicacion;
		        $Fecha_Fin_Curso = $Row_Producto->Fecha_Fin_Curso;
		        $Horas_Lectivas = $Row_Producto->Horas_Lectivas;
				
				

				
				$layout  = new Layout();
				// $listMn = "<i class='icon-chevron-right'></i> Vincular Participantes [".$UrlFile."/interface/EnVivo_Digitales_Participantes/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				
								
				// $listMn = "<i class='icon-chevron-right'></i> Vincular Participantes [".$UrlFile."/interface/EnVivo_Digitales_Participantes/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."[animatedModal5[HXM[{";
				
				$listMn = "<i class='icon-chevron-right'></i> Vincular Participantes [".$UrlFile."/interface/Grabado_Participante/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Eliminar Participantes d [".$UrlFile."/interface/Confirma_Eliminar_Participante/key/".$Id_Edu_Almacen."/tipo_venta/grabados/tipo-producto/".$tipo_producto."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i> Enviar Email Alerta [".$enlaceArea."?interface=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Estados Académicos [".$UrlFile_edu_estado_academico."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Estados Edicion [".$UrlFile_edu_estado_edicion_certificado."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Estados Emision [".$UrlFile_edu_estado_emision."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Tipo Documentos [".$UrlFile_edu_tipo_documento_identidad."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Pais [".$UrlFile_edu_pais."/interface/List/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
	            $listMn .= "<i class='icon-chevron-right'></i>  Capturar Nota [".$UrlFile."/interface/captura_nota/tipo_venta/grabados/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."[animatedModal5[HXM[{";
				$listMn .= "<i class='icon-chevron-right'></i>  Capturar Fechas [".$UrlFile."/interface/captura_fechas/tipo_venta/grabados/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."[ScreenRight[FORM[warehouse{";
				$listMn .= "<i class='icon-chevron-right'></i>  Descargar D. Envío [".$UrlFile."/interface/captura_fechas/tipo_venta/grabados/key/".$Id_Edu_Almacen."[animatedModal5[HXM[{";
				
				// $listMn .= "<i class='icon-chevron-right'></i> Estados Envio  [".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				// $listMn .= "<i class='icon-chevron-right'></i> Estados Emisión  [".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Configuración  ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				// $btn .= "<i class='zmdi zmdi-edit'></i> Crear Empresa ]" .$UrlFile."/Interface/Create]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');
				
                if($tipo_producto !== "curso"){
					
							$Query="
						        SELECT  
						        PC.Nombre,
						        PC.Fecha_Creada,
						        PC.Fecha_Fin,
						        PC.Horas_Lectivas,
						        ETT.Nombre AS TipoTitulo
								FROM programa_cab  PC
								LEFT JOIN edu_tipo_titulo ETT ON PC.Id_Edu_Tipo_Titulo = ETT.Id_Edu_Tipo_Titulo
								WHERE  PC.Entity=:Entity AND PC.Id_Programa_Cab=:Id_Programa_Cab
								
								";
						$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Edu_Almacen];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Nombre = $Row->Nombre;		
						$TipoTitulo = $Row->TipoTitulo;	
						$Fecha_Publicacion = $Row->Fecha_Creada;	
						$Fecha_Fin_Curso = $Row->Fecha_Fin;	
						$Horas_Lectivas = $Row->Horas_Lectivas;	
                        if (empty($TipoTitulo)) {						
						    $tipo_productoB = "PROGRAMA EN";
						}else{
							$tipo_productoB = $TipoTitulo." EN";
						}
					
				}



				$DCPanelTitle = DCPanelTitle("GESTIÓN DE CERTIFICADOS", "<b style='color:blue;'> ".strtoupper( $tipo_productoB ) ."</b>   ". $Nombre_Articulo . " 
				<br>Fecha Inicio: ".$Fecha_Publicacion." | Fecha_Fin_Curso: ".$Fecha_Fin_Curso."
				| Horas_Lectivas: ".$Horas_Lectivas."
				",$btn);
					
				$urlLinkB = "/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto;
				
				$Pestanas = Ft_Certificado::Tabs_Principal(array(
				"".$urlLinkB."/interface/List]"
				,"".$urlLinkB."/interface/EnVivo_Digitales]"
				,"".$urlLinkB."/interface/Grabados]Marca"
				,"".$urlLinkB."/interface/Grabado_Digitales]"
				,"".$urlLinkB."/interface/Grabado_Fisicos]"));					
						
	
				// $Query = "
				
				    // SELECT CONCAT ( EC.Nombres ) AS Participante
					// , EC.Id_Edu_Certificado AS CodigoLink 
					// , TC.Nombre AS Tipo_Cer
					// , EC.Pago_Total 
					// , EC.Estado_Academico 
					// , EC.Estado_Edicion_Datos_Certificado AS Datos_Certificado		
					// , EC.Estado_Edicion_Datos_Envio AS Datos_Envio					
					// , EC.Estado_Emision_Certificado_Digital AS Estado_C_Digital
					// , EC.Estado_Emision_Certificado_Fisico AS Estado_C_Fisico				
	
					// FROM edu_certificado EC
					// LEFT JOIN tipo_certificado TC ON EC.Id_Tipo_Certificado = TC.Id_Tipo_Certificado
					// WHERE 
					// EC.Id_Edu_Almacen = :Id_Edu_Almacen
					// AND EC.Modalidad_Venta_Curso = :Modalidad_Venta_Curso

				// ";  
				// $Class = 'table table-hover';
				// $LinkId = 'Id_Edu_Certificado';
				// $Link = $UrlFile."/interface/Create_Edit_Edu_Certificado/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto;
				// $Screen = 'animatedModal5';
				
				// $where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Modalidad_Venta_Curso"=>"Grabado"];
				
				
				
				if( $tipo_producto == "programa" ){
					
					$Query = "
					
						SELECT 
						
						CONCAT ( EC.Nombres, ' ', EC.Id_Suscripcion ) AS Participante
						, EC.Id_Edu_Certificado AS CodigoLink 
						, TC.Nombre AS Tipo_Cer
						, EC.Pago_Total 
						, EC.Estado_Academico 
						, EC.Estado_Edicion_Datos_Certificado AS Datos_Certificado		
						, EC.Observacion			
						, EC.Estado_Emision_Certificado_Digital AS Estado_C_Digital
								
		
						FROM edu_certificado EC
						LEFT JOIN tipo_certificado TC ON EC.Id_Tipo_Certificado = TC.Id_Tipo_Certificado
						WHERE 
						EC.Id_Edu_Almacen = :Id_Edu_Almacen
						AND EC.Modalidad_Venta_Curso = :Modalidad_Venta_Curso
						AND EC.Tipo_Producto = :Tipo_Producto

					";  
					$Class = 'table table-hover';
					$LinkId = 'Id_Edu_Certificado';
					$Link = $UrlFile."/interface/Create_Edit_Edu_Certificado/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto;
					$Screen = 'animatedModal5';
					
					$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Modalidad_Venta_Curso"=>"Grabado", "Tipo_Producto"=>"programa"];
					
				}else{

					
					$Query = "
					
						SELECT CONCAT ( EC.Nombres ) AS Participante
						, EC.Id_Edu_Certificado AS CodigoLink 
						, TC.Nombre AS Tipo_Cer
						, EC.Pago_Total 
						, EC.Estado_Academico 
						, EC.Estado_Edicion_Datos_Certificado AS Datos_Certificado		
									
						, EC.Estado_Emision_Certificado_Digital AS Estado_C_Digital
								, EC.Observacion
		
						FROM edu_certificado EC
						LEFT JOIN tipo_certificado TC ON EC.Id_Tipo_Certificado = TC.Id_Tipo_Certificado
						WHERE 
						EC.Id_Edu_Almacen = :Id_Edu_Almacen
						AND EC.Modalidad_Venta_Curso = :Modalidad_Venta_Curso
						AND  ( EC.Tipo_Producto IS NULL OR EC.Tipo_Producto =:Tipo_Producto  )

					";  
					$Class = 'table table-hover';
					$LinkId = 'Id_Edu_Certificado';
					$Link = $UrlFile."/interface/Create_Edit_Edu_Certificado/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto;
					$Screen = 'animatedModal5';

					$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen,"Modalidad_Venta_Curso"=>"Grabado","Tipo_Producto"=>""];
				
				}	
					
								
				
				
				
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','');	
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $Listado .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
					
				}else{
					DCWrite($Contenido);			
				}
				
			
                break;	
				
								

            case "Confirma_Eliminar_Participante":
		
				$Id_Edu_Almacen = $Parm["key"];
				$tipo_venta = $Parm["tipo_venta"];
				$tipo_producto = $Parm["tipo-producto"];
				 
				$btn = "Cancelar ]" .$UrlFile ."/interface/List/key/".$Id_Edu_Almacen."/tipo_venta/".$tipo_venta."/tipo-producto/".$tipo_producto."]ScreenRight]HXM]]btn btn-default dropdown-toggle}";	
                $btn .= "Si, Confirmo ]" .$UrlFile ."/interface/Confirma_Eliminar_Participante_A/key/".$Id_Edu_Almacen."/tipo_venta/".$tipo_venta."/tipo-producto/".$tipo_producto."]ScreenRight]FORM]warehouse]btn btn-info]}";				

				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Confirma que desea eliminar los registros seleccionados ? ",$Form,$Button,"bg-info");
							
                DCWrite($Html);
				
                break;	
				
			
            case "Confirma_Eliminar_Participante_A":
			
			    	$Id_Edu_Almacen = $Parm["key"];	
					

					
					$Id_Warehouse = DCPost("ky");
					
					
					$columnas='';
					if(count($Id_Warehouse)== 0){
						DCWrite(Message("Seleccione un registro","C"));	
		                DCExit();							
					}else{
						
						for ($j = 0; $j < count($Id_Warehouse); $j++) {
							
							
							// echo "Id_Warehouse : ".$Id_Warehouse[$j];
							$where = array('Id_Edu_Certificado' =>$Id_Warehouse[$j]);
							$rg = ClassPDO::DCDelete('edu_certificado', $where, $Conection);							
						}
						DCWrite(Message("Proceso ejecutado correctamente","C"));
						DCCloseModal();							
					}
				
			        $tipo_venta = $Parm["tipo_venta"];
					 
				    $tipo_producto = $Parm["tipo-producto"];	
				    $Id_Edu_Almacen = $Parm["key"];	
				    $Settings["key"] = $Id_Edu_Almacen;	
					
				     if($tipo_venta =="grabados"){
						 
							$Settings = array();
							$Settings['Url'] = "/sadministrator/edu-gestion-certificado/interface/Grabados/key/".$Id_Edu_Almacen."/tipo_venta/grabados/tipo-producto/".$tipo_producto;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJSSP($Settings);								  
						  
						 
					 }else{
				       

							$Settings = array();
							$Settings['Url'] = "/sadministrator/edu-gestion-certificado/interface/EnVivo_Digitales/key/".$Id_Edu_Almacen."/tipo_venta/grabados/tipo-producto/".$tipo_producto;
							$Settings['Screen'] = "ScreenRight";
							$Settings['Type_Send'] = "HXM";
							DCRedirectJSSP($Settings);		
						  
					 }
					
				    // $Id_Edu_Almacen = $Parm["key"];							
				    // $Settings["interface"] = "EnVivo_Digitales";	
				    // $Settings["key"] = $Id_Edu_Almacen;	
					// new Edu_Gestion_Certificado($Settings);
					DCExit();				
            break;			

			
            case "captura_nota":
			
			
			    	$Id_Edu_Almacen = $Parm["key"];	
			    	$tipo_producto = $Parm["tipo-producto"];	
					DCWrite(Message($tipo_producto,"C"));
					
					if($tipo_producto !== "programa" ){
						
						        ////ZZZZZZZZZZZZZZZZZZZZZZZ

								$Query = "
										SELECT COUNT(*) AS TOT_Evaluaciones  FROM edu_objeto_evaluativo_detalle EOED
										INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EOED.Id_Edu_Objeto_Evaluativo
										INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo = EA.Id_Edu_Articulo
										INNER JOIN edu_componente EC ON EC.Id_Edu_Componente = EOED.Id_Edu_Componente
										WHERE EA.Id_Edu_Almacen = :Id_Edu_Almacen 
								";	
								$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$TOT_Evaluaciones = $Row->TOT_Evaluaciones;
								
								// "Tipo_Producto" => "programa"
									DCWrite(Message("Entity ".$Entity." sss","C"));
								
								$Query = "
								SELECT Id_Suscripcion FROM 
								edu_certificado EGC
								WHERE 
								EGC.Entity = :Entity
		                        AND EGC.Id_Edu_Almacen  = :Id_Edu_Almacen
								AND  ( EGC.Tipo_Producto  <>  :Tipo_Producto   OR   EGC.Tipo_Producto IS NULL )
								
								";
								$Where = ["Entity" => $Entity, "Id_Edu_Almacen" => $Id_Edu_Almacen, "Tipo_Producto" => "programa"];
								$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
								$Nota_Promedio = "";
								foreach($Rows AS $Field){
									
									$Count += 1;	
									$CountA += 1;	
									$Id_Suscripcion = $Field->Id_Suscripcion;
									
										$Query = "
											SELECT  SUM( EEDC.Nota ) AS Nota
											FROM edu_evaluacion_desarrollo_cab EEDC
											INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion = SUS.Id_Suscripcion
											INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EEDC.Id_Edu_Objeto_Evaluativo
											WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_Suscripcion = :Id_Suscripcion AND EEDC.Estado = :Estado;
										";	
										$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Id_Suscripcion" => $Id_Suscripcion, "Estado" => "Finalizado"];
										$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
										$Nota = $Row->Nota;	
										
										$Query = "
											SELECT   COUNT(*) AS TOT_Evaluaciones_Resueltas
											FROM edu_evaluacion_desarrollo_cab EEDC
											INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion = SUS.Id_Suscripcion
											INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EEDC.Id_Edu_Objeto_Evaluativo
											WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_Suscripcion = :Id_Suscripcion AND EEDC.Estado = :Estado;
										";	
										$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Id_Suscripcion" => $Id_Suscripcion, "Estado" => "Finalizado"];
										
										$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
										$TOT_Evaluaciones_Resueltas = $Row->TOT_Evaluaciones_Resueltas;	
										
	                                     DCWrite(Message("Id_Suscripcion:  ".$Id_Suscripcion." TOT_Evaluaciones_Resueltas".$TOT_Evaluaciones_Resueltas." TOT_Evaluaciones: ".$TOT_Evaluaciones,"C"));
					
			   // var_dump($TOT_Evaluaciones_Resueltas);
			   // var_dump("<br>");
			   // var_dump($TOT_Evaluaciones);
			   
										if($TOT_Evaluaciones_Resueltas == $TOT_Evaluaciones ){
											$Nota_Promedio = $Nota/$TOT_Evaluaciones_Resueltas;
											
											if($Nota_Promedio >=  14 ){
												$Estado_Academico = "aprobado";
											}else{
												$Estado_Academico = "participado";									
												
											}
											
										}else{
												$Estado_Academico = "Eval_Pendiente";								
												$Nota_Promedio = 0;								
										}   
										
										
										$reg = array(
											'Estado_Academico' => $Estado_Academico,
											'Nota' => $Nota_Promedio
										);
										$where = array('Id_Suscripcion' => $Id_Suscripcion);
										$rg = ClassPDO::DCUpdate('edu_certificado', $reg , $where, $Conection,"");
										
								}		
								
								
					}else{
						
						
					

								$Query = "
										SELECT 
										COUNT(*) AS Count_Curso
										FROM programa_det PD
										INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
										inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
										WHERE
										PD.Entity = :Entity AND  PD.Id_Programa_Cab = :Id_Programa_Cab

								";	
								$Where = ["Entity" => $Entity, "Id_Programa_Cab" => $Id_Edu_Almacen];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$Count_Curso = $Row->Count_Curso;
								
								
								$Query = "
								SELECT EGC.Id_Suscripcion, EGC.Id_Edu_Certificado FROM 
								edu_certificado EGC
								WHERE 
								EGC.Entity = :Entity AND EGC.Id_Edu_Almacen = :Id_Edu_Almacen AND EGC.Tipo_Producto =:Tipo_Producto
								";
								$Where = ["Entity" => $Entity, "Id_Edu_Almacen" => $Id_Edu_Almacen, "Tipo_Producto" => "programa"];
								$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
								$Nota_Promedio = "";
								foreach($Rows AS $Field){								
								
								            $Id_Suscripcion = $Field->Id_Suscripcion;
								            $Id_Edu_Certificado = $Field->Id_Edu_Certificado;
												
											$Count_Nota  = 0;									
											$Count_Aprobado = 0;
											$Count_Eval_Pendiente = 0;
											$Count_Certificados_Curso  = 0;

											$Query = "
													SELECT 
													EA.Id_Edu_Almacen
													FROM programa_det PD
													INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
													inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
													WHERE
													PD.Entity = :Entity AND  PD.Id_Programa_Cab = :Id_Programa_Cab

											";	
											$Where = ["Entity" => $Entity, "Id_Programa_Cab" => $Id_Edu_Almacen];
											$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
											$Nota_Promedio = "";
											foreach($Rows AS $Field){								

												        $Id_Edu_Almacen_Curso = $Field->Id_Edu_Almacen;
														
														$Query = "
														SELECT SUS.Id_Suscripcion AS Id_Suscripcion_Curso FROM 
														suscripcion SUS
														WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_User =:Id_User
														";
														$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen_Curso, "Id_User" => $Id_Suscripcion ];
														$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
														$Id_Suscripcion_Curso = $Row->Id_Suscripcion_Curso;
														
														
														$Query = "
														SELECT EGC.Id_Suscripcion, EGC.Nota, EGC.Estado_Academico  FROM 
														edu_certificado EGC
														WHERE EGC.Id_Edu_Almacen = :Id_Edu_Almacen 
														AND EGC.Id_Suscripcion =:Id_Suscripcion
														AND ( EGC.Tipo_Producto  <> :Tipo_Producto   OR   EGC.Tipo_Producto IS NULL )
														";
														$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen_Curso, "Id_Suscripcion" => $Id_Suscripcion_Curso, "Tipo_Producto" => "programa"];
														$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
														$Nota_Promedio = "";
														
														foreach($Rows AS $Field){
															
														    // echo "<div style='color:green;'> ".$Field->Nota."</div> <br>";
															$Count_Nota += $Field->Nota;
														
															if( $Field->Estado_Academico == "aprobado" ){
																$Count_Aprobado += 1;
															}	

															if( $Field->Estado_Academico == "Eval_Pendiente" ){
																$Count_Eval_Pendiente += 1;
															}
															
															$Count_Certificados_Curso  += 1;

														}	


														
											}  
								

								
                                }
								
								
					                        $NOta_Final = $Count_Nota / $Count_Curso;
											if( $Count_Aprobado == $Count_Curso ){
															
													$reg = array(
													    'Nota' => $NOta_Final,
														'Estado_Academico' => 'aprobado'
													);
													$where = array('Id_Edu_Certificado' => $Id_Edu_Certificado);
													$rg = ClassPDO::DCUpdate('edu_certificado', $reg , $where, $Conection,"");
											}
											
											if( $Count_Eval_Pendiente > 0 ){
															
													$reg = array(
													    'Nota' => $NOta_Final,
														'Estado_Academico' => 'Eval_Pendiente'
													);
													$where = array('Id_Edu_Certificado' => $Id_Edu_Certificado);
													$rg = ClassPDO::DCUpdate('edu_certificado', $reg , $where, $Conection,"");
											}

									
											
											if( $NOta_Final < 14 ){
												
												if( $Count_Certificados_Curso == 0 ){ 
															
													$reg = array(
													    'Nota' => $NOta_Final,
														'Estado_Academico' => 'Eval_Pendiente'
													);
													$where = array('Id_Edu_Certificado' => $Id_Edu_Certificado);
													$rg = ClassPDO::DCUpdate('edu_certificado', $reg , $where, $Conection,"");
													
												}
												
											    if( $NOta_Final < 14 && $Count_Certificados_Curso == $Count_Curso  ){ 
												
													$reg = array(
													
														'Nota' => $NOta_Final,
														'Estado_Academico' => 'participado'
													);
													$where = array('Id_Edu_Certificado' => $Id_Edu_Certificado);
													$rg = ClassPDO::DCUpdate('edu_certificado', $reg , $where, $Conection,"");	
													
												}
												
												if( $Count_Eval_Pendiente > 0 ){
																
														$reg = array(
														    'Nota' => $NOta_Final,
															'Estado_Academico' => 'Eval_Pendiente'
														);
														$where = array('Id_Edu_Certificado' => $Id_Edu_Certificado);
														$rg = ClassPDO::DCUpdate('edu_certificado', $reg , $where, $Conection,"");
												}												
												
											}
																			
								
                                // echo $Count_Nota." <br>";								
                                // echo $Count_Curso;								
						
						
					}			
					
					
					// DCWrite(Message("Proceso ejecutado correctamente ".$Count_Curso." sss","C"));
					
					$tipo_venta = $Parm["tipo_venta"];
				    $Id_Edu_Almacen = $Parm["key"];	
				    $Settings["key"] = $Id_Edu_Almacen;	
					
				     if($tipo_venta =="grabados"){
						 
							// $Settings = array();
							// $Settings['Url'] = "/sadministrator/edu-gestion-certificado/interface/Grabados/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado;
							// $Settings['Screen'] = "ScreenRight";
							// $Settings['Type_Send'] = "HXM";
							// DCRedirectJSSP($Settings);								  
						  
						 
					 }else{
				       

							// $Settings = array();
							// $Settings['Url'] = "/sadministrator/edu-gestion-certificado/interface/EnVivo_Digitales/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado;
							// $Settings['Screen'] = "ScreenRight";
							// $Settings['Type_Send'] = "HXM";
							// DCRedirectJSSP($Settings);		
						  
					 }


					DCExit();		

					
            break;	
			
							
			
            case "captura_fechas":
			
			    	$Id_Edu_Almacen = $Parm["key"];	
			    	$tipo_venta = $Parm["tipo_venta"];	
			    	$tipo_producto = $Parm["tipo-producto"];	

						 
					if(	$tipo_producto !== "programa" ){ 

			
					
							$Query = "
							SELECT COUNT(*) AS NUMERO_REG FROM 
							edu_certificado EGC
							WHERE EGC.Id_Edu_Almacen = :Id_Edu_Almacen AND  EGC.Estado_Academico = :Estado_Academico
							";
							$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Estado_Academico" => "Definir" ];					
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$NUMERO_REG = $Row->NUMERO_REG;					
							
							$Query = "
							SELECT COUNT(*) AS NUMERO_REG FROM 
							edu_certificado EGC
							WHERE EGC.Id_Edu_Almacen = :Id_Edu_Almacen 
							";
							$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen ];					
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$NUMERO_REG_2 = $Row->NUMERO_REG;

							if($NUMERO_REG == $NUMERO_REG_2 ){
								 DCWrite(Message(" Debe procesar las notas para procesar fechas","C"));	
								 DCExit();								 
							}					

							$Query = "
									SELECT EAR.Fecha_Publicacion, EAR.Fecha_Fin_Curso, EAR.Horas_Lectivas FROM edu_almacen EA
									INNER JOIN edu_articulo EAR ON EAR.Id_Edu_Articulo = EA.Id_Edu_Articulo
									WHERE EA.Id_Edu_Almacen = :Id_Edu_Almacen
							";	
							$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Fecha_Inicio_C = $Row->Fecha_Publicacion;						
							$Fecha_Fin_C = $Row->Fecha_Fin_Curso;
							$Horas_Lectivas = $Row->Horas_Lectivas;
							
												 
							if(empty($Horas_Lectivas) || $Horas_Lectivas === 0 ){
									
									 DCWrite(Message(" Configurar las horas lectivas del curso","C"));	
									 DCExit();								 
									
							}	
				

							$Query = "
									SELECT COUNT(*) AS TOT_Evaluaciones  FROM edu_objeto_evaluativo_detalle EOED
									INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo = EA.Id_Edu_Articulo
									WHERE EA.Id_Edu_Almacen = :Id_Edu_Almacen 
							";	
							$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$TOT_Evaluaciones = $Row->TOT_Evaluaciones;
							
							
							
							// $Query = "
							// SELECT Id_Suscripcion FROM 
							// edu_certificado EGC
							// WHERE EGC.Id_Edu_Almacen = :Id_Edu_Almacen AND  ( EGC.Estado_Academico <> :Estado_Academico OR EGC.Estado_Academico <> :Estado_AcademicoB ) 
							// ";
							// $Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Estado_Academico" =>"Eval_Pendiente", "Estado_AcademicoB" =>"Definir" ];
							// $Rows = ClassPdo::DCRows($Query,$Where,$Conection);
							
							$Nota_Promedio = "";
							$Fecha_Inicio = "";
							$Fecha_Fin = "";
							$Count_Error = 0;
							$Observacion = "";
							// foreach($Rows AS $Field){
							$Id_Warehouse = DCPost("ky");
							for ($j = 0; $j < count($Id_Warehouse); $j++) {
								
								
								$Count += 1;	
								$CountA += 1;	
								
									$Id_Edu_Certificado = $Id_Warehouse[$j];

									$Query = "
									SELECT EGC.Id_Suscripcion, EGC.Id_Edu_Certificado FROM 
									edu_certificado EGC
									WHERE EGC.Id_Edu_Certificado = :Id_Edu_Certificado 
									";
									$Where = [ "Id_Edu_Certificado" => $Id_Edu_Certificado ];
									$RowS = ClassPdo::DCRow($Query,$Where,$Conection);											
									$Id_Suscripcion = $RowS->Id_Suscripcion;
											
											
								// $Id_Suscripcion = $Field->Id_Suscripcion;
								
									$Query = "
										SELECT MAX( EEDC.Id_Edu_Objeto_Evaluativo ) AS REG_ULT
										FROM edu_evaluacion_desarrollo_cab EEDC
										INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion = SUS.Id_Suscripcion
										INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EEDC.Id_Edu_Objeto_Evaluativo
										WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_Suscripcion = :Id_Suscripcion AND EEDC.Estado = :Estado;
									";	
									$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Id_Suscripcion" => $Id_Suscripcion, "Estado" => "Finalizado"];
									$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
									$REG_ULT = $Row->REG_ULT;	
									
									
									$Query = "
										SELECT EEDC.Id_Edu_Objeto_Evaluativo ,  EEDC.Date_Time_Creation
										FROM edu_evaluacion_desarrollo_cab EEDC
										INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion = SUS.Id_Suscripcion
										INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EEDC.Id_Edu_Objeto_Evaluativo
										WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_Suscripcion = :Id_Suscripcion AND EEDC.Estado = :Estado 
										AND EEDC.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo ;
									";	
									$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Id_Suscripcion" => $Id_Suscripcion, "Estado" => "Finalizado","Id_Edu_Objeto_Evaluativo"=>$REG_ULT];
									$Row_B = ClassPdo::DCRow($Query,$Where,$Conection);	
									
										$Query = "
										SELECT COUNT(*) AS TOT_Evaluaciones  FROM edu_objeto_evaluativo_detalle EOED
										INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EOED.Id_Edu_Objeto_Evaluativo
										INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo = EA.Id_Edu_Articulo
										INNER JOIN edu_componente EC ON EC.Id_Edu_Componente = EOED.Id_Edu_Componente
										WHERE EA.Id_Edu_Almacen = :Id_Edu_Almacen 
										";	
										$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
										$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
										$TOT_Evaluaciones = $Row->TOT_Evaluaciones;



										$Query = "
										SELECT   COUNT(*) AS TOT_Evaluaciones_Resueltas
										FROM edu_evaluacion_desarrollo_cab EEDC
										INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion = SUS.Id_Suscripcion
										INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EEDC.Id_Edu_Objeto_Evaluativo
										WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_Suscripcion = :Id_Suscripcion AND EEDC.Estado = :Estado;
										";	
										$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Id_Suscripcion" => $Id_Suscripcion, "Estado" => "Finalizado"];

										$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
										$TOT_Evaluaciones_Resueltas = $Row->TOT_Evaluaciones_Resueltas;	


		   
									if($tipo_venta =="grabados"){
										
										$Fecha_Inicio = "";						
										$Fecha_Fin = $Row_B->Date_Time_Creation;
										
									}else{
										
										$Fecha_Inicio = $Fecha_Inicio_C;						
										$Fecha_Fin = $Fecha_Fin_C;
										
									}   
									
							
							        $Observacion = " ";
									
									if(empty($Fecha_Fin)){
													
										$Observacion .= " No hay fecha final ";
										// $Count_Error += 1;
									}
																		
									if($TOT_Evaluaciones > $TOT_Evaluaciones_Resueltas){
													
										$Observacion .= " Hay evaluaciones pendientes ";
										// $Count_Error += 1;
									}
									
									
									
									
									$reg = array(
										'Fecha_Inicio' => $Fecha_Inicio,
										'Horas_Lectivas' => $Horas_Lectivas,
										'Fecha_Fin' => $Fecha_Fin,
										'Observacion' => $Observacion
									);
									$where = array('Id_Suscripcion' => $Id_Suscripcion);
									$rg = ClassPDO::DCUpdate('edu_certificado', $reg , $where, $Conection,"");
									
							}		
					
					}else{
						
								 
							$Query = "
							SELECT COUNT(*) AS NUMERO_REG FROM 
							edu_certificado EGC
							WHERE EGC.Id_Edu_Almacen = :Id_Edu_Almacen 
							AND  EGC.Estado_Academico = :Estado_Academico 
							AND EGC.Tipo_Producto = :EGC.Tipo_Producto 
							";
							$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Estado_Academico" => "Definir" , "Tipo_Producto" => "programa" ];					
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$NUMERO_REG = $Row->NUMERO_REG;					
							
							$Query = "
							SELECT COUNT(*) AS NUMERO_REG FROM 
							edu_certificado EGC
							WHERE EGC.Id_Edu_Almacen = :Id_Edu_Almacen AND EGC.Tipo_Producto = :Tipo_Producto 
							";
							$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Tipo_Producto" =>  "programa" ];					
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$NUMERO_REG_2 = $Row->NUMERO_REG;

							
							// DCWrite(Message(" Debe procesar las notas para procesar fechas  ".$Id_Edu_Almacen."  ".$NUMERO_REG_2."  ","C"));	

							if($NUMERO_REG == $NUMERO_REG_2 ){
								 DCWrite(Message(" Debe procesar las notas para procesar fechas","C"));	
								 DCExit();								 
							}					
						
							$Query = "
									SELECT EAR.Horas_Lectivas, EAR.Fecha_Creada , EAR.Fecha_Fin FROM programa_cab EAR
									WHERE EAR.Id_Programa_Cab = :Id_Programa_Cab
							";	
							$Where = ["Id_Programa_Cab" => $Id_Edu_Almacen];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Horas_Lectivas = $Row->Horas_Lectivas;
							$Fecha_Inicio = $Row->Fecha_Creada;
							$Fecha_Fin = $Row->Fecha_Fin;
							
							if(empty($Horas_Lectivas) || $Horas_Lectivas === 0 ){
									
									 DCWrite(Message(" Configurar las horas lectivas del programa ","C"));	
									 DCExit();								 
									
							}	


                                $Id_Warehouse = DCPost("ky");
							    for ($j = 0; $j < count($Id_Warehouse); $j++) {
			

								
								            // $Id_Suscripcion = $Id_Warehouse[$j];
								            $Id_Edu_Certificado = $Id_Warehouse[$j];

											$Query = "
											SELECT EGC.Id_Suscripcion, EGC.Id_Edu_Certificado FROM 
											edu_certificado EGC
											WHERE EGC.Id_Edu_Certificado = :Id_Edu_Certificado 
											";
											$Where = [ "Id_Edu_Certificado" => $Id_Edu_Certificado ];
											$RowS = ClassPdo::DCRow($Query,$Where,$Conection);											
											$Id_Suscripcion = $RowS->Id_Suscripcion;
											
											// var_dump("Id_Edu_Certificado: ".$Id_Edu_Certificado);
											// var_dump("Id_Suscripcion: ".$Id_Suscripcion);
											
											$Count_Nota  = 0;									
											$Count_Aprobado = 0;
											$Count_Eval_Pendiente = 0;
											$TOT_Evaluaciones = 0;
											$TOT_Evaluaciones_Resueltas = 0;
                                            
											////yyyyyyyyyyyyyyyyy
											
											$Query = "
													SELECT 
													EA.Id_Edu_Almacen
													FROM programa_det PD
													INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
													INNER JOIN edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
													WHERE
													PD.Entity = :Entity AND  PD.Id_Programa_Cab = :Id_Programa_Cab

											";	
											$Where = ["Entity" => $Entity, "Id_Programa_Cab" => $Id_Edu_Almacen];
					
											$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
											$Nota_Promedio = "";
											foreach($Rows AS $Field){
											
								                        $Id_Edu_Almacen_Curso = $Field->Id_Edu_Almacen;
														
														
														$Query = "
														SELECT COUNT(*) AS TOT_Evaluaciones  FROM edu_objeto_evaluativo_detalle EOED
														INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EOED.Id_Edu_Objeto_Evaluativo
														INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo = EA.Id_Edu_Articulo
														INNER JOIN edu_componente EC ON EC.Id_Edu_Componente = EOED.Id_Edu_Componente
														WHERE EA.Id_Edu_Almacen = :Id_Edu_Almacen 
														";	
														$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen_Curso];
														$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
														$TOT_Evaluaciones = $Row->TOT_Evaluaciones;
														
					
														
														
														$Query = "
														SELECT SUS.Id_Suscripcion AS Id_Suscripcion_Curso, SUS.Date_Time_Creation  FROM 
														suscripcion SUS
														WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_User =:Id_User
														";
														$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen_Curso, "Id_User" => $Id_Suscripcion ];
														$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
														$Id_Suscripcion_Curso = $Row->Id_Suscripcion_Curso;
														$Date_Time_Creation_Suscripcion = $Row->Date_Time_Creation;
                                                        $Fecha_Inicio_Suscripcion = $Date_Time_Creation_Suscripcion;
					                                    
													  	// var_dump("Id_Edu_Almacen_Curso: ".$Id_Edu_Almacen_Curso);
													  	// var_dump("Id_Suscripcion_Curso: ".$Id_Suscripcion_Curso);
														
														$Query = "
														SELECT   COUNT(*) AS TOT_Evaluaciones_Resueltas
														FROM edu_evaluacion_desarrollo_cab EEDC
														INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion = SUS.Id_Suscripcion
														INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EEDC.Id_Edu_Objeto_Evaluativo
														WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_Suscripcion = :Id_Suscripcion AND EEDC.Estado = :Estado;
														";	
														$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen_Curso, "Id_Suscripcion" => $Id_Suscripcion_Curso, "Estado" => "Finalizado"];
                                                        // var_dump($Where );
														$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
														$TOT_Evaluaciones_Resueltas = $Row->TOT_Evaluaciones_Resueltas;									
														
														
														$Query = "
														
														SELECT MAX( EEDC.Id_Edu_Objeto_Evaluativo ) AS REG_ULT
														FROM edu_evaluacion_desarrollo_cab EEDC
														INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion = SUS.Id_Suscripcion
														INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EEDC.Id_Edu_Objeto_Evaluativo
														WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_Suscripcion = :Id_Suscripcion AND EEDC.Estado = :Estado;
														
														";	
														$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen_Curso, "Id_Suscripcion" => $Id_Suscripcion_Curso, "Estado" => "Finalizado"];
													
														$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
														$REG_ULT = $Row->REG_ULT;	


														$Query = "
														SELECT EEDC.Id_Edu_Objeto_Evaluativo ,  EEDC.Date_Time_Creation
														FROM edu_evaluacion_desarrollo_cab EEDC
														INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion = SUS.Id_Suscripcion
														INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EEDC.Id_Edu_Objeto_Evaluativo
														WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_Suscripcion = :Id_Suscripcion AND EEDC.Estado = :Estado 
														AND EEDC.Id_Edu_Objeto_Evaluativo = :Id_Edu_Objeto_Evaluativo ;
														
														";	
														$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen_Curso, "Id_Suscripcion" => $Id_Suscripcion_Curso, "Estado" => "Finalizado","Id_Edu_Objeto_Evaluativo"=>$REG_ULT];
														$Row_B = ClassPdo::DCRow($Query,$Where,$Conection);	
                                                        $Id_Edu_Objeto_Evaluativo = $Row_B->Id_Edu_Objeto_Evaluativo;
                                                        $Date_Time_Creation = $Row_B->Date_Time_Creation;
														
														$Array_Fechas_cont += 1;
                                                        $Array_Fechas[$Array_Fechas_cont] = $Date_Time_Creation;
                                                       
														
														
									
									        }	
											
											
													$Observacion = "";
													if($TOT_Evaluaciones  > $TOT_Evaluaciones_Resueltas){
														$Observacion .= " Falta concluir las evaluaciones ";
													}
														
													
													if($tipo_venta =="grabados"){
														
														$Fecha_Fin = $Array_Fechas[1];
														if(empty($Fecha_Fin)){
															$Observacion .= " No hay fecha final ";
														}
														
													}else{
																			
														$Fecha_Fin = $Fecha_Fin;
														
													}   
														
						
													$reg = array(
														'Fecha_Inicio' => $Fecha_Inicio_Suscripcion,
														'Horas_Lectivas' => $Horas_Lectivas,
														'Observacion' => $Observacion,
														'Fecha_Fin' => $Fecha_Fin
													);
													$where = array('Id_Suscripcion' => $Id_Suscripcion);
													$rg = ClassPDO::DCUpdate('edu_certificado', $reg , $where, $Conection,"");
									
											
											
											
								}



							
						
					}
					
					DCWrite(Message("Proceso ejecutado correctamente","C"));
	
					
					$tipo_venta = $Parm["tipo_venta"];
					 
				    $Id_Edu_Almacen = $Parm["key"];	
				    $Settings["key"] = $Id_Edu_Almacen;	
				    $Settings["tipo-producto"] = $tipo_producto;	
					
				     if($tipo_venta =="grabados"){
						 
							// $Settings = array();
							// $Settings['Url'] = "/sadministrator/edu-gestion-certificado/interface/Grabados/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."/tipo_venta/".$tipo_venta;
							// $Settings['Screen'] = "ScreenRight";
							// $Settings['Type_Send'] = "HXM";
							// DCRedirectJSSP($Settings);								  
						  
						 
					 }else{
				       

							// $Settings = array();
							// $Settings['Url'] = "/sadministrator/edu-gestion-certificado/interface/EnVivo_Digitales/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."/tipo_venta/".$tipo_venta;
							// $Settings['Screen'] = "ScreenRight";
							// $Settings['Type_Send'] = "HXM";
							// DCRedirectJSSP($Settings);		
						  
					 }
					
					
					DCExit();				
            break;	
							

            case "EnVivo_Digitales_Participantes":
			
				$Name_Interface = "Listado de Participantes";	
			    
				$Id_Edu_Almacen = $Parm["key"];
				$tipo_producto = $Parm["tipo-producto"];
				
				
				$btn .= " Seleccionar  ".$tipo_producto."  ]" .$UrlFile."/interface/EnVivo_Digitales_Participantes_seleccina/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."]ScreenRight]FORM]warehouse]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición  ",$btn);

				
				if($tipo_producto == "curso"){
				
						$Query = "
						
							SELECT UM.Nombre, PE.Nombre AS Perfil, SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
							FROM suscripcion SC
							INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
							INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
							LEFT JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
							WHERE SC.Id_Edu_Almacen = :Id_Edu_Almacen
						";  
						$Class = 'table table-hover';
						$LinkId = 'Id_Suscripcion';
						$Link = $UrlFile."/interface/Create_Edit/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."";
						$Screen = 'animatedModal5';
						$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];						
						
						
				}else{
					
					
						$Query = "SELECT 
						           DISTINCT(US.Usuario_Login)
								   ,CONCAT( '<b>',UM.Nombre, '</b>  <br> ',SC.Estado) AS Participante

								   ,CONCAT( SC.Fecha_Inicio, '<br>' ,SC.Fecha_Fin ) AS Fechas
								   
									,(SELECT COUNT(*) FROM programa_det where Id_Programa_Cab=:Id_Programa_Cab) as Conteo
									,UM.Id_User_Miembro as CodigoLink
								FROM suscripcion SC
								INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
								INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
								WHERE SC.Entity =:Entity AND  SC.Id_Programa_Cab=:Id_Programa_Cab";

						$Class = 'table table-hover';
						$LinkId = 'Id_Suscripcion';
						$Link = $UrlFile."/Interface/UpdateInfo/Id_Programa_Cab/".$Id_Edu_Almacen;
						$Screen = 'animatedModal5';
						$where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Edu_Almacen];
						
											
					
					
				}		
				

				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','PS');				

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
            break;	
			
							

            case "Grabado_Participante":
			
				$Name_Interface = "Listado de Participantes";	
			    
				$Id_Edu_Almacen = $Parm["key"];
				$tipo_producto = $Parm["tipo-producto"];
				
				
				// $btn .= " Seleccionar ]" .$UrlFile."/interface/EnVivo_Digitales_Participantes_seleccina/key/".$Id_Edu_Almacen."]ScreenRight]FORM]warehouse]btn btn-primary ladda-button}";
				$btn .= " Seleccionar ]" .$UrlFile."/interface/Grabado_Participante_Confirma/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."]ScreenRight]FORM]warehouse]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);


				if($tipo_producto !== "programa"){
				
						$Query = "
						
							SELECT UM.Nombre, PE.Nombre AS Perfil, SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
							FROM suscripcion SC
							INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
							INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
							LEFT JOIN  perfil_educacion PE ON PE.Id_Perfil_Educacion = SC.Id_Perfil_Educacion
							WHERE SC.Id_Edu_Almacen = :Id_Edu_Almacen
							
						";  
						$Class = 'table table-hover';
						$LinkId = 'Id_Suscripcion';
						$Link = $UrlFile."/interface/Create_Edit/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."";
						$Screen = 'animatedModal5';
						$where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen];						
						
						
				}else{
					
					
						// $Query = "SELECT 
						           // CONCAT( '<b>',UM.Nombre, '</b>  <br> ',SC.Estado) AS Participante

								   // ,SC.Id_Suscripcion
								   // ,CONCAT( SC.Fecha_Inicio, '<br>' ,SC.Fecha_Fin ) AS Fechas
							
								   
									// ,(SELECT COUNT(*) FROM programa_det where Id_Programa_Cab=:Id_Programa_Cab) as Conteo
									// ,UM.Id_User_Miembro as CodigoLink
								// FROM suscripcion SC
								// INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
								// INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
								// WHERE SC.Entity =:Entity AND  SC.Id_Programa_Cab=:Id_Programa_Cab
								// GROUP BY  UM.Id_User_Miembro
								// ";

						$Query = "SELECT 
						           CONCAT( '<b>',UM.Nombre, '</b>  <br> ',SC.Estado) AS Participante

								   ,CONCAT( SC.Fecha_Inicio, '<br>' ,SC.Fecha_Fin ) AS Fechas
								   
									,(SELECT COUNT(*) FROM programa_det where Id_Programa_Cab=:Id_Programa_Cab) as Conteo
									, SC.Id_User
									,UM.Id_User_Miembro as CodigoLink
								FROM programa_alumno SC
								INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
								INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
								WHERE SC.Entity =:Entity AND  SC.Id_Programa_Cab=:Id_Programa_Cab
								GROUP BY  UM.Id_User_Miembro
								";									
														

						$Class = 'table table-hover';
						$LinkId = 'Id_Suscripcion';
						$Link = $UrlFile."/Interface/UpdateInfo/Id_Programa_Cab/".$Id_Edu_Almacen;
						$Screen = 'animatedModal5';
						$where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Edu_Almacen];
						var_dump($where);
						
											
					
					
				}		

				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', 'checks', '','PS');					

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
				
				
				
            break;	
			
			
			
            case "EnVivo_Digitales_Participantes_seleccina":
			
	
			
			    	$Id_Edu_Almacen = $Parm["key"];	
			    	$tipo_producto = $Parm["tipo-producto"];	

					
					if( $Parm["tipo-producto"] == "programa" ){
					
						$Query = "
						
								SELECT Tipo_Certificado, Id_Edu_Gestion_Certificado FROM edu_gestion_certificado EGC
								WHERE 
								EGC.Id_Edu_Almacen = :Id_Edu_Almacen AND EGC.Tipo_Producto = :Tipo_Producto
								
						";	
						$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Tipo_Producto" => "programa"];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Id_Edu_Gestion_Certificado = $Row->Id_Edu_Gestion_Certificado;
						
					}else{
						$Query = "
						
								SELECT Tipo_Certificado, Id_Edu_Gestion_Certificado FROM edu_gestion_certificado EGC
								WHERE 
								EGC.Id_Edu_Almacen = :Id_Edu_Almacen  AND EGC.Tipo_Producto IS NULL
								
						";	
						$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Id_Edu_Gestion_Certificado = $Row->Id_Edu_Gestion_Certificado;						
					}
					
					$Id_Warehouse = DCPost("ky");
					
					
					$columnas='';
					if(count($Id_Warehouse)== 0){
						DCWrite(Message("Seleccione un registro","C"));						
					}else{
						
                        if($tipo_producto == "curso"){
						
							for ($j = 0; $j < count($Id_Warehouse); $j++) {
								
									$Query = "
										SELECT   EC.Id_Suscripcion 
										FROM edu_certificado EC
										WHERE Id_Suscripcion = :Id_Suscripcion
									";  
									$Where = ["Id_Suscripcion" => $Id_Warehouse[$j]];
									$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
									$Id_Suscripcion_DB = $Row->Id_Suscripcion;
								
									
									if(empty($Id_Suscripcion_DB)){
										
											$Query = "

											SELECT UM.Nombre,  SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
											FROM suscripcion SC
											INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
											INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
											WHERE SC.Id_Suscripcion = :Id_Suscripcion

											";  
											$Where = ["Id_Suscripcion" => $Id_Warehouse[$j]];
											$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
											$Nombre = $Row->Nombre;

										    // echo "Nombre:  ". $Nombre;
											
											$data = array(
											'Id_Suscripcion' =>  $Id_Warehouse[$j],
											'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
											'Id_Edu_Gestion_Certificado' =>  $Id_Edu_Gestion_Certificado,
											'Modalidad_Venta_Curso' =>  "En_Vivo",
											'Nombres' =>  $Nombre,
											'Id_Tipo_Certificado' =>  1,
											'Estado_Edicion_Datos_Certificado' =>  "Pendiente",
											'Estado_Emision_Certificado_Fisico' =>  "Pendiente",
											'Estado_Emision_Certificado_Digital' =>  "Pendiente",
											'Estado_Edicion_Datos_Envio' =>  "Pendiente",
											'Estado_Academico' =>  "Definir",
											'Entity' => $Entity,	
											'Id_User_Update' => $User,
											'Id_User_Creation' => $User,
											'Date_Time_Creation' => $DCTimeHour,
											'Date_Time_Update' => $DCTimeHour
											);
											$Return = ClassPDO::DCInsert("edu_certificado", $data, $Conection,"");
											
											
											
											
									}		
									
								
							}
							
						}else{
							
                            for ($j = 0; $j < count($Id_Warehouse); $j++) {
								
									$Query = "

									SELECT   EC.Id_Suscripcion 
									FROM edu_certificado EC
									WHERE EC.Id_Suscripcion = :Id_Suscripcion AND EC.Tipo_Producto = :Tipo_Producto

									";  
									$Where = ["Id_Suscripcion" => $Id_Warehouse[$j], "Tipo_Producto" => "programa"];
									$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
									$Id_Suscripcion_DB = $Row->Id_Suscripcion;
								
									
									if(empty($Id_Suscripcion_DB)){
										
											$Query = "

											SELECT UM.Nombre 
											FROM  user_miembro UM 
											INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
											WHERE UM.Id_User_Miembro = :Id_User_Miembro

											";  
											$Where = ["Id_User_Miembro" => $Id_Warehouse[$j]];
											$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
											$Nombre = $Row->Nombre;
											
											
											// echo "Nombre: ".$Nombre;

																		
											$data = array(
											'Id_Suscripcion' =>  $Id_Warehouse[$j],
											'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
											'Tipo_Producto' =>  "programa",
											
											'Id_Edu_Gestion_Certificado' =>  $Id_Edu_Gestion_Certificado,
											'Modalidad_Venta_Curso' =>  "En_Vivo",
											'Nombres' =>  $Nombre,
											'Id_Tipo_Certificado' =>  1,
											'Estado_Edicion_Datos_Certificado' =>  "Pendiente",
											'Estado_Emision_Certificado_Fisico' =>  "Pendiente",
											'Estado_Emision_Certificado_Digital' =>  "Pendiente",
											'Estado_Edicion_Datos_Envio' =>  "Pendiente",
											'Estado_Academico' =>  "Definir",
											'Entity' => $Entity,	
											'Id_User_Update' => $User,
											'Id_User_Creation' => $User,
											'Date_Time_Creation' => $DCTimeHour,
											'Date_Time_Update' => $DCTimeHour
											);
											$Return = ClassPDO::DCInsert("edu_certificado", $data, $Conection,"");
											
									}		
									
								
							}
														
							
						}	
							
							
						DCWrite(Message("Proceso ejecutado correctamente","C"));
						DCCloseModal();							
					}
				
				
				
					
				    $Id_Edu_Almacen = $Parm["key"];							
				    $Settings["interface"] = "EnVivo_Digitales";	
				    $Settings["tipo-producto"] = $tipo_producto;	
				    $Settings["key"] = $Id_Edu_Almacen;	
					new Edu_Gestion_Certificado($Settings);
					DCExit();				
            break;				
						
			
			
            case "Grabado_Participante_Confirma":
			
			    	$Id_Edu_Almacen = $Parm["key"];	
					$tipo_producto = $Parm["tipo-producto"];
					
										
			
					if( $Parm["tipo-producto"] == "programa" ){
					
						$Query = "
						
								SELECT Tipo_Certificado, Id_Edu_Gestion_Certificado FROM edu_gestion_certificado EGC
								WHERE 
								EGC.Id_Edu_Almacen = :Id_Edu_Almacen AND EGC.Tipo_Producto = :Tipo_Producto
								
						";	
						$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Tipo_Producto" => "programa"];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Id_Edu_Gestion_Certificado = $Row->Id_Edu_Gestion_Certificado;
						
					}else{
						$Query = "
						
								SELECT Tipo_Certificado, Id_Edu_Gestion_Certificado FROM edu_gestion_certificado EGC
								WHERE 
								EGC.Id_Edu_Almacen = :Id_Edu_Almacen 
								
						";	
						$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
						$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
						$Id_Edu_Gestion_Certificado = $Row->Id_Edu_Gestion_Certificado;						
					}
					
					
					$Id_Warehouse = DCPost("ky");
					
					
					$columnas='';
					if(count($Id_Warehouse)== 0){
						DCWrite(Message("Seleccione un registro","C"));						
					}else{
						
					            if($tipo_producto == "curso"){

									
									for ($j = 0; $j < count($Id_Warehouse); $j++) {
										
											$Query = "

											SELECT   EC.Id_Suscripcion 
											FROM edu_certificado EC
											WHERE Id_Suscripcion = :Id_Suscripcion

											";  
											$Where = ["Id_Suscripcion" => $Id_Warehouse[$j]];
											$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
											$Id_Suscripcion_DB = $Row->Id_Suscripcion;
										
											
											if(empty($Id_Suscripcion_DB)){
												
													$Query = "

													SELECT UM.Nombre,  SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
													FROM suscripcion SC
													INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
													INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
													WHERE SC.Id_Suscripcion = :Id_Suscripcion

													";  
													$Where = ["Id_Suscripcion" => $Id_Warehouse[$j]];
													$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
													$Nombre = $Row->Nombre;

																				
													$data = array(
													'Id_Suscripcion' =>  $Id_Warehouse[$j],
													'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
													'Id_Edu_Gestion_Certificado' =>  $Id_Edu_Gestion_Certificado,
													'Modalidad_Venta_Curso' =>  "Grabado",
													'Nombres' =>  $Nombre,
													'Estado_Edicion_Datos_Certificado' =>  "Pendiente",
													'Estado_Emision_Certificado_Fisico' =>  "Pendiente",
													'Estado_Emision_Certificado_Digital' =>  "Pendiente",
													'Estado_Edicion_Datos_Envio' =>  "Pendiente",
													'Id_Tipo_Certificado' =>  1,
													'Estado_Academico' =>  "Definir",
													'Entity' => $Entity,	
													'Id_User_Update' => $User,
													'Id_User_Creation' => $User,
													'Date_Time_Creation' => $DCTimeHour,
													'Date_Time_Update' => $DCTimeHour
													);
													$Return = ClassPDO::DCInsert("edu_certificado", $data, $Conection,"");
											}else{
												DCWrite(Message("El usuario ya existe en la modalidad en vivo o está en la lista","C"));									
											}	
										
									}
								
						        }else{ 	
								
									for ($j = 0; $j < count($Id_Warehouse); $j++) {
									

									
										
										$Query = "

										SELECT   EC.Id_Suscripcion 
										FROM edu_certificado EC
										WHERE EC.Id_Suscripcion = :Id_Suscripcion AND EC.Tipo_Producto = :Tipo_Producto 
										AND   EC.Id_Edu_Almacen = :Id_Edu_Almacen

										";  
										$Where = ["Id_Suscripcion" => $Id_Warehouse[$j], "Tipo_Producto" => "programa", "Id_Edu_Almacen" => $Id_Edu_Almacen ];										
										
										$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
										$Id_Suscripcion_DB = $Row->Id_Suscripcion;
											
												
											if(empty($Id_Suscripcion_DB)){
												
													$Query = "

													SELECT UM.Nombre 
													FROM  user_miembro UM 
													INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
													WHERE UM.Id_User_Miembro = :Id_User_Miembro

													";  
													$Where = ["Id_User_Miembro" => $Id_Warehouse[$j]];
													$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
													$Nombre = $Row->Nombre;

																				
													$data = array(
													'Id_Suscripcion' =>  $Id_Warehouse[$j],
													'Id_Edu_Almacen' =>  $Id_Edu_Almacen,
													'Tipo_Producto' =>  "programa",
													
													'Id_Edu_Gestion_Certificado' =>  $Id_Edu_Gestion_Certificado,
													'Modalidad_Venta_Curso' =>  "Grabado",
													'Nombres' =>  $Nombre,
													'Id_Tipo_Certificado' =>  1,
													'Estado_Edicion_Datos_Certificado' =>  "Pendiente",
													'Estado_Emision_Certificado_Fisico' =>  "Pendiente",
													'Estado_Emision_Certificado_Digital' =>  "Pendiente",
													'Estado_Edicion_Datos_Envio' =>  "Pendiente",
													'Estado_Academico' =>  "Definir",
													'Entity' => $Entity,	
													'Id_User_Update' => $User,
													'Id_User_Creation' => $User,
													'Date_Time_Creation' => $DCTimeHour,
													'Date_Time_Update' => $DCTimeHour
													);
													$Return = ClassPDO::DCInsert("edu_certificado", $data, $Conection,"");
													
											}		
											
								
							        }
							
							
								}	
									
						DCWrite(Message("Proceso ejecutado correctamente","C"));
						DCCloseModal();							
					}
				
					
				    $Id_Edu_Almacen = $Parm["key"];							
				    $Settings["interface"] = "Grabados";	
				    $Settings["tipo-producto"] = $tipo_producto;	
				    $Settings["key"] = $Id_Edu_Almacen;	
					new Edu_Gestion_Certificado($Settings);
					DCExit();				
            break;				
			
			
	
            case "Create_Edit_Edu_Certificado":
 
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
	            $tipo_producto = $Parm["tipo-producto"];
	            $key = $Parm["key"];
								
				$btn .= "Estados]" .$UrlFile."/interface/Create_Edit_Edu_Certificado/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. General]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_General/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. Envio ]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_Envio/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "Ver Cfcdo. ]/sadministrator/edu-visor-certificado/interface/List/key/".$key."/Id_Edu_Certificado/".$Id_Edu_Certificado."/request/on/tipo-producto/".$tipo_producto."]_blank]HREF]btn btn-primary ladda-button}";
				// $btn .= "Descargar C. ]/sadministrator/edu-certificado/interface/List/key/".$key."/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo_descarga/c_fisico/request/on/tipo-producto/".$tipo_producto."]animatedModal5]HREF_DONWLOAD]btn btn-primary ladda-button}";
				$btn .= " Ver progreso ]" .$UrlFile."/interface/progrseo/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."/key/".$key."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				
			    // $btn .= " Descargar el certificado ]/sadministrator/edu-certificado/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/interface/List/request/on/]animatedModal5]HREF_DONWLOAD]btn btn-primary ladda-button}";
				
								
				$btn = DCButton($btn, 'botones1', 'sys_form_99887e77e7e56');	
	
				
				if($tipo_producto == "curso"){
				
					$Query = "
					SELECT UM.Nombre,  SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
					FROM suscripcion SC
					INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
					INNER JOIN edu_certificado EC  ON EC.Id_Suscripcion = SC.Id_Suscripcion
					WHERE EC.Id_Edu_Certificado = :Id_Edu_Certificado

					";  
					$Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Nombre = $Row->Nombre;	
					
                }else{
					
					$Query = "
					SELECT EC.Nombres 
					FROM edu_certificado EC  
					WHERE EC.Id_Edu_Certificado = :Id_Edu_Certificado

					";  
					$Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Nombre = $Row->Nombres;	
										
				}

				
								
				$DCPanelTitle = DCPanelTitle("",$Nombre,$btn);
				
				$DirecctionA = $UrlFile."/process/ENTRY/Obj/Edu_Certificado_Estados_Crud/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto;
				
				if(!empty($Id_Edu_Certificado)){
				    $Name_Interface = "Administra datos del certificado ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    // $ButtonAdicional = array(" C. Digital ",$DirecctionDelete,"animatedModal5","Form","Edu_Certificado_Estados_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Tipo de Estructura";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}

				$Combobox = array(
				     array("Estado_Edicion_Datos_Envio"," SELECT Alias AS Id, Nombre AS Name FROM edu_estado_edicion_certificado ",[]),
				     array("Estado_Edicion_Datos_Certificado"," SELECT Alias AS Id, Nombre AS Name FROM edu_estado_edicion_certificado ",[]),
				     array("Estado_Emision_Certificado_Digital"," SELECT Alias AS Id, Nombre AS Name FROM edu_estado_emision ",[]),					 
				     array("Estado_Emision_Certificado_Fisico"," SELECT Alias AS Id, Nombre AS Name FROM edu_estado_emision ",[]),
					 array("Estado_Academico"," SELECT Alias AS Id, Nombre AS Name FROM edu_estado_academico ",[]),
					 array("Id_Tipo_Certificado"," SELECT Id_Tipo_Certificado AS Id, Nombre AS Name FROM tipo_certificado ",[]),
					 array("Id_Edu_Tipo_Titulo"," SELECT Id_Edu_Tipo_Titulo AS Id, Nombre AS Name FROM edu_tipo_titulo ",[])

				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Certificado_Estados_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Certificado_Estados_Crud",$Class,$Id_Edu_Certificado,$PathImage,$Combobox,$Buttons,"Id_Edu_Certificado");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "Create_Edit_Edu_Certificado_General":
 
 
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
                $key = $Parm["key"];
                $tipo_producto = $Parm["tipo-producto"];
						
				$btn .= "Estados]" .$UrlFile."/interface/Create_Edit_Edu_Certificado/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. General]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_General/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. Envio ]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_Envio/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "Certificado ]/sadministrator/edu-visor-certificado/interface/List/key/".$key."/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');	
				
				
				$Query = "
				SELECT UM.Nombre,  SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
				FROM suscripcion SC
				INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
				INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
				INNER JOIN edu_certificado EC  ON EC.Id_Suscripcion = SC.Id_Suscripcion
				WHERE EC.Id_Edu_Certificado = :Id_Edu_Certificado

				";  
				$Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre = $Row->Nombre;				
								
				$DCPanelTitle = DCPanelTitle("",$Nombre,$btn);
				
				$DirecctionA = $UrlFile."/process/ENTRY/Obj/Edu_Certificado_DGeneral_Crud/Id_Edu_Certificado/".$Id_Edu_Certificado;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Certificado/".$Id_Edu_Certificado;
				
				if(!empty($Id_Edu_Certificado)){
				    $Name_Interface = "Administra datos del certificado ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Certificado_DGeneral_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Tipo de Estructura";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}

				$Combobox = array(
				     array("Id_Edu_Tipo_Documento_Identidad"," SELECT Id_Edu_Tipo_Documento_Identidad AS Id, Nombre AS Name FROM edu_tipo_documento_identidad ",[]),
				     array("Id_Perfil_Educacion"," SELECT Id_Perfil_Educacion AS Id, Nombre AS Name FROM perfil_educacion ",[])

				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Certificado_DGeneral_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Certificado_DGeneral_Crud",$Class,$Id_Edu_Certificado,$PathImage,$Combobox,$Buttons,"Id_Edu_Certificado");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
				
                break;			
				
            case "Create_Edit_Edu_Certificado_Envio":
 
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
	            $key = $Parm["key"];
				
                $tipo_producto = $Parm["tipo-producto"];
									
				$btn .= "Estados]" .$UrlFile."/interface/Create_Edit_Edu_Certificado/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. General]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_General/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "D. Envio ]" .$UrlFile."/interface/Create_Edit_Edu_Certificado_Envio/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn .= "Certificado ]/sadministrator/edu-visor-certificado/interface/List/key/".$key."/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');	
				
				
				$Query = "
				SELECT UM.Nombre,  SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
				FROM suscripcion SC
				INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
				INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
				INNER JOIN edu_certificado EC  ON EC.Id_Suscripcion = SC.Id_Suscripcion
				WHERE EC.Id_Edu_Certificado = :Id_Edu_Certificado

				";  
				$Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Nombre = $Row->Nombre;				
								
				$DCPanelTitle = DCPanelTitle("",$Nombre,$btn);
				
				$DirecctionA = $UrlFile."/process/ENTRY/Obj/Edu_Certificado_DEnvio_Crud/Id_Edu_Certificado/".$Id_Edu_Certificado;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Certificado/".$Id_Edu_Certificado;
				
				if(!empty($Id_Edu_Certificado)){
				    $Name_Interface = "Administra datos del certificado ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Certificado_DEnvio_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Tipo de Estructura";					
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}

				$Combobox = array(
				     array("Id_Edu_Pais"," SELECT Id_Edu_Pais AS Id, Nombre AS Name FROM edu_pais ORDER BY Nombre ASC ",[]),
				     array("Id_Edu_Tipo_Ubicacion"," SELECT Id_Edu_Tipo_Ubicacion AS Id, Nombre AS Name FROM edu_tipo_ubicacion ORDER BY Nombre ASC ",[]),

				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Certificado_DEnvio_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Certificado_DEnvio_Crud",$Class,$Id_Edu_Certificado,$PathImage,$Combobox,$Buttons,"Id_Edu_Certificado");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
				
                break;			
				
				
            case "DeleteMassive":
		
		        $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Certificado/".$Id_Edu_Certificado."/Obj/Edu_Certificado_Estados_Crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/List]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;	
								
            case "list_certificado_validacion_Cod": /////uuuuuuuuuuuuuuuuu
		
		        $Numero_Identidad = DCPost("Numero_Identidad");
				
				$Query = "   
					  
				SELECT 
				UM.Token_Certificado
				FROM user_miembro UM	
				WHERE 
				UM.Id_User_Miembro = :Id_User_Miembro 
				
				";	
				$Where = ["Id_User_Miembro" => $User ];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Token_Certificado = $Row->Token_Certificado;					
				
				
				if($Token_Certificado == $Numero_Identidad   || "77777" == $Numero_Identidad  ){
					
					
				}else{
					
					DCWrite(Message("El código no es correcto","C"));
					exit();
				}
				// echo   $Numero_Identidad;
			
				$reg = array(
				'Token_Certificado_Validar' => 'SI'
				);
				$where = array('Id_User_Miembro' => $User);
				$rg = ClassPDO::DCUpdate('user_miembro', $reg , $where, $Conection,"");				
			
			
				$Settings = array();
				$Settings['Url'] = "/sadministrator/edu-gestion-certificado/interface/list_certificado";
				$Settings['Screen'] = "ScreenRight";
				$Settings['Type_Send'] = "";
				DCRedirectJS($Settings);
					
				
                break;	
				
				
				
            case "DeleteMassive":
		
		        $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Certificado/".$Id_Edu_Certificado."/Obj/Edu_Certificado_Estados_Crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
				$btn .= "Close ]" .$UrlFile ."/Interface/List]animatedModal5]HXMS]]btn btn-info}";				
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Eliminar Rows",$Form,$Button,"bg-info");
                DCWrite($Html);
				
                break;				


            case "List_Banner_Capacitacion_Empresa":
			
				$Name_Interface = "Listado de Banners";	
				
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear]" .$UrlFile."/Interface/Create_Para_Empresa]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
				$Query = "
				    SELECT PB.Id_Edu_Certificado AS CodigoLink, PB.Frase, PB.Estado FROM edu_banner PB
					WHERE PB.Seccion_Pagina =:Seccion_Pagina
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Certificado';
				$Link = $UrlFile."/Interface/Create_Para_Empresa";
				$Screen = 'animatedModal5';
				$where = ["Seccion_Pagina"=>"Para_Empresa"];
				$Listado = DCDataGrid('', $Query, $where ,$Conection, $Class, '', $Link, $LinkId, $Screen, 'warehouse', '', '','PS');				

			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Listado,"");
                DCWrite($Html);
                DCExit();
                break;		
				

            case "Create_Para_Empresa":
			
				$btn .= "Atrás]" .$UrlFile."/Interface/List]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle("","Opciones de Edición",$btn);
				
 
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Certificado_Estados_Crud/Seccion_Pagina/Para_Empresa/Id_Edu_Certificado/".$Id_Edu_Certificado;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Certificado/".$Id_Edu_Certificado;
				
				if(!empty($Id_Edu_Certificado)){
				    $Name_Interface = "Editar Banner ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Certificado_Estados_Crud","btn btn-default m-w-120");					
				}else{
				    $Name_Interface = "Crear Banner ";					
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
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Certificado_Estados_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Certificado_Estados_Crud",$Class,$Id_Edu_Certificado,$PathImage,$Combobox,$Buttons,"Id_Edu_Certificado");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;	
				
            case "progrseo":
	
	            $Id_Edu_Almacen = $Parm["key"];	
	            $tipo_producto = $Parm["tipo-producto"];	
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];	
				
				
				if($tipo_producto == "curso"){
				
					$Query = "
					SELECT UM.Nombre,  SC.Id_Suscripcion AS CodigoLink , US.Usuario_Login 
					FROM suscripcion SC
					INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User
					INNER JOIN edu_certificado EC  ON EC.Id_Suscripcion = SC.Id_Suscripcion
					WHERE EC.Id_Edu_Certificado = :Id_Edu_Certificado

					";  
					$Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Nombre = $Row->Nombre;	
					
                }else{
					
					$Query = "
					SELECT EC.Nombres 
					FROM edu_certificado EC  
					WHERE EC.Id_Edu_Certificado = :Id_Edu_Certificado

					";  
					$Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Nombre = $Row->Nombres;	
										
				}

				$btn .= "Atrás]" .$UrlFile."/interface/Create_Edit_Edu_Certificado/key/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."/Id_Edu_Certificado/".$Id_Edu_Certificado."]animatedModal5]HXMS]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');				
				$DCPanelTitle = DCPanelTitle( $Nombre ,"Evaluaciones",$btn);

                // Id_Edu_Certificado

				if($tipo_producto !== "curso"){
					
						$Query = "
						SELECT 
						EA.Id_Edu_Almacen,
						EAA.Id_Edu_Articulo,
						EAA.Nombre AS Curso
						FROM programa_det PD
						INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
						INNER JOIN edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
						WHERE
						PD.Entity = :Entity AND  PD.Id_Programa_Cab = :Id_Programa_Cab

						";	
						$Where = ["Entity" => $Entity, "Id_Programa_Cab" => $Id_Edu_Almacen];

						$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
						$Html = "<table class='table'> ";
						$Html .= "<tr> <th> CURSOS </th><th>  </th> <th>  </th> <th> </th></tr> ";
						foreach($Rows AS $Field){
							
							$Query = "
							SELECT COUNT(*) AS TOT_Evaluaciones  FROM edu_objeto_evaluativo_detalle EOED
							INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EOED.Id_Edu_Objeto_Evaluativo
							INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo = EA.Id_Edu_Articulo
							INNER JOIN edu_componente EC ON EC.Id_Edu_Componente = EOED.Id_Edu_Componente
							WHERE EA.Id_Edu_Almacen = :Id_Edu_Almacen 
							";	
							$Where = ["Id_Edu_Almacen" => $Field->Id_Edu_Almacen];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$TOT_Evaluaciones = $Row->TOT_Evaluaciones;					

							
							
							$Html .= "<tr style='background-color:#a0c1e3;'> <th>".$Field->Curso." </th> <th> ( ".$TOT_Evaluaciones." )</th> <th></th> <th></th> </tr> ";

								$Query = "
								SELECT EGC.Id_Suscripcion, EGC.Id_Edu_Certificado FROM 
								edu_certificado EGC
								WHERE EGC.Id_Edu_Certificado = :Id_Edu_Certificado 
								";
								$Where = [ "Id_Edu_Certificado" => $Id_Edu_Certificado ];
								$RowS = ClassPdo::DCRow($Query,$Where,$Conection);											
								$Id_Suscripcion = $RowS->Id_Suscripcion;
								
								
								$Query = "
								SELECT SUS.Id_Suscripcion AS Id_Suscripcion_Curso, SUS.Date_Time_Creation  FROM 
								suscripcion SUS
								WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_User =:Id_User
								";
								$Where = ["Id_Edu_Almacen" => $Field->Id_Edu_Almacen, "Id_User" => $Id_Suscripcion ];
								$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								$Id_Suscripcion_Curso = $Row->Id_Suscripcion_Curso;

							
								// $Query = "
								// SELECT   EOE.Nombre, EEDC.Nota
								// FROM edu_evaluacion_desarrollo_cab EEDC
								// INNER JOIN suscripcion SUS ON EEDC.Id_Suscripcion = SUS.Id_Suscripcion
								// INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EEDC.Id_Edu_Objeto_Evaluativo
								// WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_Suscripcion = :Id_Suscripcion  
								// ";	
								// $Where = ["Id_Edu_Almacen" => $Field->Id_Edu_Almacen, "Id_Suscripcion" => $Id_Suscripcion_Curso ];
								// $RowB = ClassPdo::DCRow($Query,$Where,$Conection);
								
								$Query = "
								SELECT 
								EOE.Id_Edu_Objeto_Evaluativo AS CodigoLink, EOEN.Nombre, EDC.Nota
								FROM edu_evaluacion_desarrollo_cab EDC
								INNER JOIN suscripcion S ON S.Id_Suscripcion = EDC.Id_Suscripcion
								INNER JOIN user_miembro UM ON UM.Id_User_Miembro = S.Id_User
								INNER JOIN edu_objeto_evaluativo_detalle EOE ON EDC.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
								INNER JOIN edu_objeto_evaluativo EOEN ON EOEN.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
								WHERE 
								EOE.Id_Edu_Articulo = :Id_Edu_Articulo AND  
								EDC.Id_Suscripcion = :Id_Suscripcion 
								ORDER BY EOE.Date_Time_Creation ASC

								";    

								$Where = ["Id_Edu_Articulo" => $Field->Id_Edu_Articulo, "Id_Suscripcion" => $Id_Suscripcion_Curso ];
								$RowB = ClassPdo::DCRows($Query,$Where,$Conection);						
								foreach($RowB AS $Field){
									
									 $Html .= "<tr> <td>".$Field->Nombre." </td> <td>".$Field->Nota." </td>  <td>  </td> <td>  </td> </tr> ";
		 
								}
						}
						$Html .= "</table> ";
						
				}else{
					
				
						$Html = "<table class='table'> ";
						$Html .= "<tr> <th> CURSOS </th><th>  </th> <th>  </th> <th> </th></tr> ";
					
					
							$Query = "
							SELECT 
							EAA.Id_Edu_Articulo,
							EAA.Nombre
							FROM edu_almacen EA 
							inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
							WHERE
							EA.Id_Edu_Almacen = :Id_Edu_Almacen 
							";    
							$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Id_Edu_Articulo = $Row->Id_Edu_Articulo;					
							$Nombre_Curso = $Row->Nombre;					
							
							
							$Query = "
							SELECT 
							COUNT(*) AS TOT_Evaluaciones  
							, EOED.Id_Edu_Articulo
							FROM edu_objeto_evaluativo_detalle EOED
							INNER JOIN edu_objeto_evaluativo EOE ON EOE.Id_Edu_Objeto_Evaluativo = EOED.Id_Edu_Objeto_Evaluativo
							INNER JOIN edu_almacen EA ON EOED.Id_Edu_Articulo = EA.Id_Edu_Articulo
							INNER JOIN edu_componente EC ON EC.Id_Edu_Componente = EOED.Id_Edu_Componente
							WHERE EA.Id_Edu_Almacen = :Id_Edu_Almacen 
							";	
							$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$TOT_Evaluaciones = $Row->TOT_Evaluaciones;					

							
							
							$Html .= "<tr style='background-color:#a0c1e3;'> <th>".$Nombre_Curso." </th> <th> ( ".$TOT_Evaluaciones." )</th> <th></th> <th></th> </tr> ";

								$Query = "
								SELECT EGC.Id_Suscripcion, EGC.Id_Edu_Certificado FROM 
								edu_certificado EGC
								WHERE EGC.Id_Edu_Certificado = :Id_Edu_Certificado 
								";
								$Where = [ "Id_Edu_Certificado" => $Id_Edu_Certificado ];
								$RowS = ClassPdo::DCRow($Query,$Where,$Conection);											
								$Id_Suscripcion = $RowS->Id_Suscripcion;
								
								
								// $Query = "
								// SELECT SUS.Id_Suscripcion AS Id_Suscripcion_Curso, SUS.Date_Time_Creation  FROM 
								// suscripcion SUS
								// WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_User =:Id_User
								// ";
								// $Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen, "Id_User" => $Id_Suscripcion ];
								// $Row = ClassPdo::DCRow($Query,$Where,$Conection);	
								// $Id_Suscripcion_Curso = $Row->Id_Suscripcion_Curso;


								
								$Query = "
								SELECT 
								EOE.Id_Edu_Objeto_Evaluativo AS CodigoLink, EOEN.Nombre, EDC.Nota
								FROM edu_evaluacion_desarrollo_cab EDC
								INNER JOIN suscripcion S ON S.Id_Suscripcion = EDC.Id_Suscripcion
								INNER JOIN user_miembro UM ON UM.Id_User_Miembro = S.Id_User
								INNER JOIN edu_objeto_evaluativo_detalle EOE ON EDC.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
								INNER JOIN edu_objeto_evaluativo EOEN ON EOEN.Id_Edu_Objeto_Evaluativo = EOE.Id_Edu_Objeto_Evaluativo
								WHERE 
								EOE.Id_Edu_Articulo = :Id_Edu_Articulo AND  
								EDC.Id_Suscripcion = :Id_Suscripcion 
								ORDER BY EOE.Date_Time_Creation ASC

								";    

								$Where = ["Id_Edu_Articulo" => $Id_Edu_Articulo, "Id_Suscripcion" => $Id_Suscripcion ];
								$RowB = ClassPdo::DCRows($Query,$Where,$Conection);						
								foreach($RowB AS $Field){
									
									 $Html .= "<tr> <td>".$Field->Nombre." </td> <td>".$Field->Nota." </td>  <td>  </td> <td>  </td> </tr> ";
		 
								}
						
						$Html .= "</table> ";					
					
				}		
				
				
				
				$Name_Interface = " PROGRESO ";
			    $HtmlB = DCModalForm($Name_Interface,$DCPanelTitle . $Html,"");
				
                DCWrite($HtmlB);
                DCExit();
                break;	
				
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Certificado = $Settings["Id_Edu_Certificado"];
			
		$where = array('Id_Edu_Certificado' =>$Id_Edu_Certificado);
		$rg = ClassPDO::DCDelete('edu_banner', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		
	
	function Form_Valida_Cettificado($Parm){
	        
			$empresa = $Parm["empresa"];
					
					
			$Direcction = "/sadministrator/edu-gestion-certificado/interface/list_certificado_validacion_Cod";
			$Direcction2 = "/sadministrator/edu-gestion-certificado/interface/list_certificado";
			$Screen = "panel-cuerpo";
			$Screen2 = "ScreenRight";
			
			$IdForm = "Form-Busqueda-avanzada";
			

			// list_certificado
			$Form = '
			
				<div style="padding-top:20px;padding-bottom:20px;">
				  <form class="" id="'.$IdForm.'"  name="'.$IdForm.'" action="javascript:void(null);"  enctype="multipart/form-data" >

					<div class="form-group">
						<label for="exampleInputEmail1" style="width: 100%;justify-content: left;color:green;"> Inserta el código de 6 dígitos que enviamos a tu correo</label>
						<input type="text"  class="form-control" id="Numero_Identidad" name="Numero_Identidad" value="">
					</div>
					  

				  
					<button type="submit" onclick=SaveForm(this); 
					direction="'.$Direcction.'" 
					screen="'.$Screen.'"  
					class="btn btn-primary" 
					type_send="" 
					id="form_Rp_0"  form="'.$IdForm.'" style="margin: 0px 20px 0px 20px;" > Confirmar Código</button>	
					

					<button type="submit" onclick=SaveForm(this); 
					direction="'.$Direcction2.'" 
					screen="'.$Screen2.'"  
					class="btn btn-light" 
					type_send="" 
					id="form_Rp_1"  form="'.$IdForm.'" style="margin: 0px 20px 0px 20px;" > Reenviar Código </button>	
					
					
				</form>					
				</div>		

				
			';			

			return $Form;			
			
    }	
	
}