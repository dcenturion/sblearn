<?php
require_once('../sbookstores/php/pdf/vendor/autoload.php');
use Spipu\Html2Pdf\Html2Pdf;

require_once('./sviews/layout.php');
require_once('./sviews/funtion-certificado.php');


$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Edu_Certificado{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile,$Redirect;
		
		$UrlFile = "/sadministrator/edu-certificado";
		$UrlFileDet = "/sadministrator/det_admin_object";
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
							
				
						    $Settings["interface"] = "configura_plantilla_predefinida";
						    $Settings["key"] = $Id_Edu_Almacen;
							new Edu_Certificado($Settings);
							DCExit();	
					
						break;	
						
					case "Edu_Certificado_Digital_Participante":
					        
							
				            $Id_Edu_Almacen = $Parm["Id_Edu_Almacen"];
				            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
                            $Data = array();
							
						    $Nombres = DCPost("Nombres");
						    $Id_Edu_Pais = DCPost("Id_Edu_Pais");
						    $Id_Edu_Tipo_Documento_Identidad = DCPost("Id_Edu_Tipo_Documento_Identidad");
						    $Numero_Identidad = DCPost("Numero_Identidad");
							
                            if(empty($Nombres)){
								
							    DCWrite(Message("Debe llenar el campo Nombres y Apellidos","C"));
								exit();
							}
							
                            if(strlen($Nombres) < 8){
								
							    DCWrite(Message("Debe insertar Nombres y Apellidos ","C"));
								exit();
							}

							
                            if(empty($Id_Edu_Pais)){
								
							    DCWrite(Message("Debe elegir un país ","C"));
								exit();
							}

							
                            if(empty($Id_Edu_Tipo_Documento_Identidad)){
								
							    DCWrite(Message("Debe elegir un tipo de documento ","C"));
								exit();
							}		

                            if(empty($Numero_Identidad)){
								
							    DCWrite(Message("Debe llenar el campo Número de Identidad ","C"));
								exit();
							}
							
                            if(strlen($Numero_Identidad) < 4){
								
							    DCWrite(Message("El Número de Identidad  no es correcto ","C"));
								exit();
							}
							
							$Numero_Identidad = is_int($Numero_Identidad) || ctype_digit($Numero_Identidad) ? true : false;
							
                            if(empty($Numero_Identidad)){
								
							    DCWrite(Message("El número de idendtidad debe ser numérico ".$Numero_Identidad."","C"));
								exit();
							}
														
								// exit();
							
							DCSave($Obj,$Conection,$Parm["Id_Edu_Certificado"],"Id_Edu_Certificado",$Data);
							
						
							$reg = array(
								'Estado_Edicion_Datos_Certificado' => "Revisado",
								'Estado_Emision_Certificado_Digital' => "Emitido",
								'Id_User_Update' => $User,
								'Date_Time_Update' => $DCTimeHour
							);
							$where = array('Id_Edu_Certificado' => $Id_Edu_Certificado);
							$rg = ClassPDO::DCUpdate('edu_certificado', $reg , $where, $Conection,"");

							$Query = "        
								
								SELECT EC.Estado_Academico
								, EC.Estado_Edicion_Datos_Certificado
								, EC.Id_Tipo_Certificado 
								, EC.Id_Edu_Certificado 
								
								FROM edu_certificado  EC
			
								WHERE  EC.Id_Edu_Certificado = :Id_Edu_Certificado
							
							";
							$Where = [ "Id_Edu_Certificado" => $Id_Edu_Certificado ];
							
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Estado_Academico = $Row->Estado_Academico;
							$Id_Tipo_Certificado = $Row->Id_Tipo_Certificado;

	
	
							
							$tipo_producto = $Parm["tipo-producto"];
							$interfaceraiz = $Parm["interfaceraiz"];
							
                            // DCWrite(Message(" tipo_producto  ".$tipo_producto." interfaceraiz  ".$interfaceraiz,"C"));
                            if($Id_Tipo_Certificado == 2){
								
								   // if(empty ($interfaceraiz )){	
								   
										$Settings = array();
										$Settings['Url'] = "/sadministrator/edu-certificado/interface/Create_Edit_Certificado_Envio/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto."/interfaceraiz/".$interfaceraiz;
										$Settings['Screen'] = "animatedModal5";
										$Settings['Type_Send'] = "HXM";
										DCRedirectJSSP($Settings);
										
								   // }else{
									   
										// $Settings = array();
										// $Settings['Url'] = "/sadministrator/edu-certificado/interface/Create_Edit_Certificado_Envio/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto;
										// $Settings['Screen'] = "animatedModal5";
										// $Settings['Type_Send'] = "HXM";
										// DCRedirectJSSP($Settings);	
									   
								   // }								
								
									
							}else{
								
								   if(empty ($interfaceraiz )){
								 
									
										$Settings = array();
										$Settings['Url'] = "/sadministrator/edu-articulo-det/interface/Certificado_Alumno/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/herramienta/certificado/tipo-producto/".$tipo_producto;
										$Settings['Screen'] = "PanelA";
										$Settings['Type_Send'] = "HXM";
										DCRedirectJSSP($Settings);
								     
								   }else{
									   
									   	$Settings = array();
										$Settings['Url'] = "/sadministrator/edu-gestion-certificado/interface/list_certificado/interfaceraiz/".$interfaceraiz;
										$Settings['Screen'] = "ScreenRight";
										$Settings['Type_Send'] = "HXM";
										DCRedirectJSSP($Settings);
										DCCloseModal();	
									   
								   }
								
								
							}							
		
							
							DCExit();	
					
						break;		

						
						
						
					case "Edu_Certificado_Datos_Envio":
					
		                    $Id_Edu_Almacen = $Parm["Id_Edu_Almacen"];
				            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				            $tipo_producto = $Parm["tipo-producto"];
				            $interfaceraiz = $Parm["interfaceraiz"];
				            // $Id_Edu_Tipo_Ubicacion = $Parm["Id_Edu_Tipo_Ubicacion"];
                            $Data = array();
							
						    $Nombres = DCPost("Nombres");
						    $Telefono_Contacto = DCPost("Telefono_Contacto");
						    $Id_Edu_Pais = DCPost("Id_Edu_Pais");
						    $Departamento = DCPost("Departamento");
						    $Provincia = DCPost("Provincia");
						    $Direccion = DCPost("Direccion");
						    $Referencia_Envio = DCPost("Referencia_Envio");
						    $Id_Edu_Tipo_Ubicacion = DCPost("Id_Edu_Tipo_Ubicacion");
							
							
							
						    $Id_Edu_Tipo_Documento_Identidad = DCPost("Id_Edu_Tipo_Documento_Identidad");
						    $Numero_Identidad = DCPost("Numero_Identidad");
							
                      											
												
                            if(empty($Telefono_Contacto)){
							    DCWrite(Message("Debe llenar el campo Telefono de Contacto","C"));
								exit();
							}
							
                            if(strlen($Telefono_Contacto) < 5){
							    DCWrite(Message("Debe insertar Telefono de Contacto ","C"));
								exit();
							}												
																					
							
                            if(empty($Id_Edu_Pais)){
							    DCWrite(Message("Debe elegir un país ","C"));
								exit();
							}	
							
                            if(empty($Departamento)){
							    DCWrite(Message("Debe llenar el campo Departamento","C"));
								exit();
							}
							
                  
                            if(empty($Provincia)){
							    DCWrite(Message("Debe llenar el campo Provincia","C"));
								exit();
							}
							
                      

                            if(empty($Direccion)){
							    DCWrite(Message("Debe llenar el campo Direccion","C"));
								exit();
							}
							
                            if(strlen($Direccion) < 8){
							    DCWrite(Message("Debe insertar Direccion ","C"));
								exit();
							}		
	


                            if(empty($Id_Edu_Tipo_Ubicacion)){
							    DCWrite(Message("Debe llenar el campo Tipo de Ubicación","C"));
								exit();
							}	


                            if(empty($Referencia_Envio)){
							    DCWrite(Message("Debe llenar el campo Referencia_Envio","C"));
								exit();
							}
	
							DCSave($Obj,$Conection,$Parm["Id_Edu_Certificado"],"Id_Edu_Certificado",$Data);
							
						
							$reg = array(
								'Estado_Emision_Certificado_Fisico' => "Emitido",
								'Estado_Edicion_Datos_Envio' => "Revisado",
								'Id_User_Update' => $User,
								'Date_Time_Update' => $DCTimeHour
								
							);
							$where = array('Id_Edu_Certificado' => $Id_Edu_Certificado);
							$rg = ClassPDO::DCUpdate('edu_certificado', $reg , $where, $Conection,"");	
							
							
				                    if(empty ($interfaceraiz )){
								 
										$Settings = array();
										$Settings['Url'] = "/sadministrator/edu-certificado/interface/List_Visor/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/tipo-producto/".$tipo_producto;
										$Settings['Screen'] = "ScreenRight";
										$Settings['Type_Send'] = "HXM";
										DCRedirectJSSP($Settings);
								     
								   }else{
									   
									   	$Settings = array();
										$Settings['Url'] = "/sadministrator/edu-gestion-certificado/interface/list_certificado/interfaceraiz/".$interfaceraiz;
										$Settings['Screen'] = "ScreenRight";
										$Settings['Type_Send'] = "HXM";
										DCRedirectJSSP($Settings);
										DCCloseModal();	
									   
								   }				


						
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
					case "Edu_Banner_Crud":
						
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
				$Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				$tipo_visualizacion = $Parm["tipo_visualizacion"];
				$tipo_descarga = $Parm["tipo_descarga"];
				$tipo_producto = $Parm["tipo-producto"];
				
				
				$Nombre_Participante = "NOMBRES COMPLETOS DEL PARTICIPANTE DEL CURSO ";
				$Estado_Academico = "Realizado";
                $Modalidad_Venta_Curso_1 = "participado y aprobado";
                $Horas_Lectivas = "30";
				
				// DCExit(" Los datos del participante están incompletos ERROR NRO:0002 ");
				// exit();
				
				if($tipo_producto=="programa"){
				    $Tipo_Prdoducto_desc = "Programa";				
				}else{ 
				    $Tipo_Prdoducto_desc = "Curso";
				}
				
				if(!empty($Id_Edu_Certificado)){
						
					$Query = "
						SELECT 
						
						EC.Nombres
						,  EC.Estado_Academico 
						,  EC.Pago_Total 
						,  EC.Modalidad_Venta_Curso 
						,  EC.Id_Edu_Pais 
						,  EC.Numero_Identidad 
						,  EC.Fecha_Inicio 
						,  EC.Fecha_Fin 
						,  EC.Horas_Lectivas 
						,  EC.Nombre_Curso 
						FROM edu_certificado EC  
						WHERE EC.Id_Edu_Certificado = :Id_Edu_Certificado

					";  
					$Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Estado_Academico = $Row->Estado_Academico;	
					$Nombre_Participante = mb_strtoupper($Row->Nombres, "UTF-8");	
					$Pago_Total = $Row->Pago_Total;	
					$Nombres = $Row->Nombres;			
					$Modalidad_Venta_Curso = $Row->Modalidad_Venta_Curso;
                    $Horas_Lectivas = $Row->Horas_Lectivas;			
         	
                    $Numero_Identidad = $Row->Numero_Identidad;			
                    $Id_Edu_Almacen = $Id_Edu_Almacen;		
                    $Id_Edu_Pais = $Row->Id_Edu_Pais;		
                    $Fecha_Inicio = $Row->Fecha_Inicio;		
                    $Fecha_Fin = $Row->Fecha_Fin;		
                    $Nombre_Curso_Alias = $Row->Nombre_Curso;		

					$Fecha_Inicio_Mes = DCMes_Texto($Fecha_Inicio);
					$Fecha_Inicio_Dia = date('d', strtotime($Fecha_Inicio));
					$Fecha_Inicio_Year = date('Y', strtotime($Fecha_Inicio));
			
					$Fecha_Fin_Mes = DCMes_Texto($Fecha_Fin);
					$Fecha_Fin_Dia = date('d', strtotime($Fecha_Fin));
					$Fecha_Fin_Year = date('Y', strtotime($Fecha_Fin));
					
					
					
					
					
					
					$Query = "
					SELECT EGC.Id_Suscripcion, EGC.Nota, EGC.Estado_Academico  FROM 
					edu_certificado EGC
					WHERE EGC.Id_Edu_Certificado = :Id_Edu_Certificado 
					";
					$Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
					$Rows = ClassPdo::DCRow($Query,$Where,$Conection);					
					$Id_Suscripcion_User = $Rows->Id_Suscripcion;
					
					
					$Query = "
						SELECT 
						PD.Id_Programa_Det AS CodigoLink
						,EAA.Nombre as Curso_Vinculado
						,PD.Id_Edu_Almacen
						,PD.Date_Time_Creation as Fecha_Creada
						FROM programa_det PD
						INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
						inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
						WHERE
						PD.Entity = :Entity AND  PD.Id_Programa_Cab=:Id_Programa_Cab
					";    
					$Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Edu_Almacen];
					$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
						
						$Td_Html_Valida = "<table border='1' width='100%'>";
						
						$Td_Html_Valida .= "<tr style='background-color:#000;color:#fff; width:100%;'>
						<th style=' width:80%;padding:5px;' >Cursos / Módulos </th>
						<th style=' width:20%;padding:5px;'>Nota</th></tr>";
						$Suma_Nt = 0;
						$Count_Curso = 0;
						foreach($Rows AS $Field){

							$Id_Edu_Almacen_pd = $Field->Id_Edu_Almacen;	
							
							
						    $Query = "
							SELECT SUS.Id_Suscripcion AS Id_Suscripcion_Curso FROM 
							suscripcion SUS
							WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_User =:Id_User
							";
							$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen_pd, "Id_User" => $Id_Suscripcion_User ];
							$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							$Id_Suscripcion_Curso = $Row->Id_Suscripcion_Curso;								
							
							$Query = "
							SELECT EGC.Id_Suscripcion, EGC.Nota, EGC.Estado_Academico  FROM 
							edu_certificado EGC
							WHERE EGC.Id_Edu_Almacen = :Id_Edu_Almacen AND EGC.Id_Suscripcion =:Id_Suscripcion
							";
							$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen_pd, "Id_Suscripcion" => $Id_Suscripcion_Curso];
							$Rows = ClassPdo::DCRow($Query,$Where,$Conection);
							
						    $Nota = $Rows->Nota;
						    $Suma_Nt += $Nota;
						    $Count_Curso += 1;
						
							$Curso_Vinculado = $Field->Curso_Vinculado;
							
							$Td_Html_Valida .= "<tr><td style=' width:80%;padding:5px;' >".$Id_Edu_Almacen_pd." - ".$Curso_Vinculado."</td>   <td style=' width:80%;padding:5px;' >".$Nota."</td>  </tr>";
						}									
					    $Td_Html_Valida .= "</table>";
									


					
			
			
					if($Modalidad_Venta_Curso == "En_Vivo"){
						
				        $Estado_Academico_text = "finalizado";
                        $Modalidad_Curso = "001";	
						//Estado_Academico_text
						
					}else{
				        $Estado_Academico_text = "aprobado";	
                        $Modalidad_Curso = "002";						
					}
					
                    $Codigo_Certificado = $Id_Edu_Almacen."_".$Id_Edu_Pais."_".$Numero_Identidad."_".$Modalidad_Curso;
					
					if($Estado_Academico == "aprobado"){
						
						$Tipo_Constancia = "CERTIFICADO";
						
				        $Modalidad_Venta_Curso_1 = "participado y aprobado";						
				        $Tipo_Text_Otorgamiento = "Otorgado a ";						
					}	

					if($Estado_Academico == "participado"){
						
						$Tipo_Constancia = " CONSTANCIA ";	
						$Tipo_Text_Otorgamiento = "Otorgada a ";
				        $Modalidad_Venta_Curso_1 = "participado en";	
                         $Estado_Academico_text = "finalizado";						
					}	
					
	
						// if($tipo_visualizacion !== "demo"){
					
						if(!empty($Estado_Academico)){
							
							if($Estado_Academico !== "Definir" ){
								
								if($Pago_Total == "Realizado" ){
									
								}	
								
							}else{
								
								DCExit(" Para emitir el certificado el Estado_Academico debe tener un valor de aprobado o participado ");

							}		
							
						}else{
								
								DCExit(" No hay código de certificado");
						}


						if(empty($Id_Edu_Pais)){
							
							    echo $Td_Html_Valida;
							
								DCExit("<br> <b style='font-family:arial;color:red;'>Falta el dato del país ERROR NRO:0001</b> ");							
						}	
						
						if(empty($Numero_Identidad)){
							     echo $Td_Html_Valida;
								DCExit("<br> <b style='font-family:arial;color:red;'>Falta el dato del nro. de identidad ERROR NRO:0002</b> ");							
						}													
						
						if(empty($Horas_Lectivas)){
							     echo $Td_Html_Valida;
								DCExit("<br> <b style='font-family:arial;color:red;'>Falta el dato del Horas_Lectivas ERROR NRO:0003</b> ");							
						}		

							
						// }	

				}
				
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen,$tipo_producto);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Curso = $Row_Producto->Nombre;	
				
				if(!empty($Nombre_Curso_Alias)){
					$Nombre_Curso = $Nombre_Curso_Alias;
				}
				
				$btn = "Subir Componentes ]" .$UrlFile."/interface/configura_plantilla_predefinida/key/".$Id_Edu_Almacen."]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn_3 = DCButton($btn, 'botones1', 'sys_form');	
				$DCPanelTitle_Msj = DCPanelTitle("Configura la plantilla predefinida","",$btn_3);	

				$Query = "

					SELECT 
					Certificado_Imagen
					, Certificado_Firma_1
					, Certificado_Firma_2 
					, Logo_Certificado_2 
					, Logo_Certificado_1 
					, Certificado_Sello_1 
					, Certificado_Nombre_Firma_1 
					, Certificado_Cargo_Firma_1 
					, Certificado_Nombre_Firma_2 
					, Certificado_Cargo_Firma_2 
					FROM entity ET
					WHERE 
					ET.Id_Entity = :Id_Entity 
						
				";	
				$Where = ["Id_Entity" => $Entity];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				$Certificado_Imagen = $Row->Certificado_Imagen;
				
			    if( $Entity == 7 ){
					
					$Color_Nombre_Particpante = "color:#1c879f;";
				}else{
					
					$Color_Nombre_Particpante = "color:#1cad1a;";					
				}
				
				
				$Certificado_Firma_1 = $Row->Certificado_Firma_1;
				$Certificado_Firma_2 = $Row->Certificado_Firma_2;
				
				if($tipo_descarga == "c_fisico" && $Modalidad_Venta_Curso == "En_Vivo"){
					
					$Firma_Hmtl_1 = "";
					$Firma_Hmtl_2 = "";
				}else{
					$Firma_Hmtl_1 = "<img src='../sadministrator/simages/".$Certificado_Firma_1."' style='width:200px;'>";
					$Firma_Hmtl_2 = "<img src='../sadministrator/simages/".$Certificado_Firma_2."' style='width:200px;'>";					
				}
				
				$Logo_Certificado_2 = $Row->Logo_Certificado_2;
				$Logo_Certificado_1 = $Row->Logo_Certificado_1;
				$Certificado_Sello_1 = $Row->Certificado_Sello_1;
				$Certificado_Nombre_Firma_1 = $Row->Certificado_Nombre_Firma_1;
				$Certificado_Cargo_Firma_1 = $Row->Certificado_Cargo_Firma_1;
				$Certificado_Nombre_Firma_2 = $Row->Certificado_Nombre_Firma_2;
				$Certificado_Cargo_Firma_2 = $Row->Certificado_Cargo_Firma_2;
				
				if($Modalidad_Venta_Curso == "En_Vivo"){				
				    $Fechas = $Fecha_Inicio_Dia." de ".$Fecha_Inicio_Mes." del ".$Fecha_Inicio_Year." al ".$Fecha_Fin_Dia." de ".$Fecha_Fin_Mes." del ".$Fecha_Fin_Year;
				}else{
	                $Fechas = $Fecha_Fin_Dia." de ".$Fecha_Fin_Mes." del ".$Fecha_Fin_Year;
									
				}
				
				
			    $Html ="
					
					<table width='100%'  bgcolor='#000'  border='0' cellpadding='0' cellspacing='0'
					style='background-image: url(../sadministrator/simages/".$Certificado_Imagen.");background-repeat:no-repeat;background-size:900px 700px;' >
					    
						  <tr>
							<td style='width:1300px;height:789px;vertical-align: top;'>
					
								<table>
									  <tr>
										<td style='width:500px;height:180px;'>
									
										</td>
										<td style='width:300px;padding:60px 0px 0px 0px;'>
										    <img src='../sadministrator/simages/".$Logo_Certificado_2."' style='width:280px;'>
										</td>
										<td style='width:300px;padding:60px 0px 0px 0px;'> 
										    <img src='../sadministrator/simages/".$Logo_Certificado_1."' style='width:250px;'>
										</td>										
									  </tr>
									  
								</table>							
								<table>
									  <tr>
										<td style='text-align: center;width:1300px;'>
									        <h1 style='font-size:50px;padding:0px;margin:0px;'> ".$Tipo_Constancia." </h1>
									        <h4 style='font-size:20px;padding:0px;margin:10px 0px 10px 0px;color:#4c4c4e;'>".$Tipo_Text_Otorgamiento."</h4>
											
										</td>										
									  </tr>
								</table>	
								
								<table>
									  <tr>
										<td style='text-align: center;width:250px;'>
									      
										</td>
										<td style='text-align: center;width:800px;'> 
											<h2 style='font-size:25px;".$Color_Nombre_Particpante." font-family: arial;padding:0px;margin:0px 0px 0px 0px;'>".$Nombre_Participante."</h2>
										    <hr style='color:#ccc;width:300px;padding:0px;margin:10px 0px 0px 0px;'>
										</td>										
									  </tr>
								</table>
								
								<table style='padding:0px;margin:0px 0px 0px 0px;'>
									  <tr>
										<td style='text-align: center;width:250px;'>
									      
										</td>
										<td style='text-align: center;width:800px;'> 
											<p style='font-size:18px;color:#4c4c4e;padding:0px;margin:0px 0px 0px 0px;'>En reconocimiento por haber ".$Modalidad_Venta_Curso_1." el ".$Tipo_Prdoducto_desc." de Especialización en 
											<span style='font-weight: bold;'>“".$Nombre_Curso."”</span>, 
											".$Estado_Academico_text." el ".$Fechas.", con una duración de ".$Horas_Lectivas." horas lectivas.
                                            </p>
										   
										</td>										
									  </tr>
								</table>
								
				               <table>
									  <tr>
										<td style='width:320px;height:200px;'>
									
										</td>
										<td style='width:250px;padding:40px 0px 0px 0px;vertical-align: bottom;text-align: center;'>
										    ".$Firma_Hmtl_1."
											
										    <hr style='color:#ccc;width:300px;'>
											<h4 style='font-size:18px;padding:0px;margin:0px;'>".$Certificado_Nombre_Firma_1."</h4>
											<p style='font-size:14px;padding:0px;margin:0px;'>".$Certificado_Cargo_Firma_1."</p>
										</td>
										<td style='width:150px;padding:40px 0px 0px 40px;'> 
										    <img src='../sadministrator/simages/".$Certificado_Sello_1."' style='width:150px;'>
										</td>	
										<td style='width:220px;padding:40px 0px 0px 0px;vertical-align: bottom;text-align: center;'> 
										      ".$Firma_Hmtl_2."
												<hr style='color:#ccc;width:300px;'>
												<h4 style='font-size:18px;padding:0px;margin:0px;'>".$Certificado_Nombre_Firma_2."</h4>
												<p style='font-size:14px;padding:0px;margin:0px;'>".$Certificado_Cargo_Firma_2."</p>
										</td>										
									  </tr>
									  
								</table>								

	                            <table>
									  <tr>
										<td style='width:800px;'>
									
										</td>

										<td style='width:240px;padding:40px 0px 0px 0px;vertical-align: bottom;text-align:right;'> 

												<p style='font-size:14px;padding:0px;margin:0px;'> COD-".$Codigo_Certificado." </p>
										</td>										
									  </tr>
									  
								</table>								
																
								
							</td>
							
						  </tr>
					
						  
					</table> ";
					
				if($tipo_producto == "programa"){
					
				
						// $Query = "
						// SELECT EGC.Id_Suscripcion, EGC.Nota, EGC.Estado_Academico  FROM 
						// edu_certificado EGC
						// WHERE EGC.Id_Edu_Certificado = :Id_Edu_Certificado 
						// ";
						// $Where = ["Id_Edu_Certificado" => $Id_Edu_Certificado];
						// $Rows = ClassPdo::DCRow($Query,$Where,$Conection);					
						// $Id_Suscripcion_User = $Rows->Id_Suscripcion;
						
					
					
						// $Query = "
							// SELECT 
							// PD.Id_Programa_Det AS CodigoLink
							// ,EAA.Nombre as Curso_Vinculado
							// ,PD.Id_Edu_Almacen
							// ,PD.Date_Time_Creation as Fecha_Creada
							// FROM programa_det PD
							// INNER JOIN edu_almacen EA ON PD.Id_Edu_Almacen = EA.Id_Edu_Almacen
		             		// inner join edu_articulo  EAA on EA.Id_Edu_Articulo = EAA.Id_Edu_Articulo
							// WHERE
							// PD.Entity = :Entity AND  PD.Id_Programa_Cab=:Id_Programa_Cab
						// ";    
						// $Where = ["Entity"=>$Entity,"Id_Programa_Cab"=>$Id_Edu_Almacen];
						// $Rows = ClassPdo::DCRows($Query,$Where,$Conection);
						// $Td_Html = "<table border='1' width='100%'>";
						
						// $Td_Html .= "<tr style='background-color:#000;color:#fff; width:100%;'>
						// <th style=' width:80%;padding:5px;' >Cursos / Módulos </th>
						// <th style=' width:20%;padding:5px;'>Nota</th></tr>";
						// $Suma_Nt = 0;
						// $Count_Curso = 0;
						// foreach($Rows AS $Field){

							// $Id_Edu_Almacen_pd = $Field->Id_Edu_Almacen;	
							
							
						    // $Query = "
							// SELECT SUS.Id_Suscripcion AS Id_Suscripcion_Curso FROM 
							// suscripcion SUS
							// WHERE SUS.Id_Edu_Almacen = :Id_Edu_Almacen AND SUS.Id_User =:Id_User
							// ";
							// $Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen_pd, "Id_User" => $Id_Suscripcion_User ];
							// $Row = ClassPdo::DCRow($Query,$Where,$Conection);	
							// $Id_Suscripcion_Curso = $Row->Id_Suscripcion_Curso;								
							
							// $Query = "
							// SELECT EGC.Id_Suscripcion, EGC.Nota, EGC.Estado_Academico  FROM 
							// edu_certificado EGC
							// WHERE EGC.Id_Edu_Almacen = :Id_Edu_Almacen AND EGC.Id_Suscripcion =:Id_Suscripcion
							// ";
							// $Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen_pd, "Id_Suscripcion" => $Id_Suscripcion_Curso];
							// $Rows = ClassPdo::DCRow($Query,$Where,$Conection);
							
						    // $Nota = $Rows->Nota;
						    // $Suma_Nt += $Nota;
						    // $Count_Curso += 1;
						
							// $Curso_Vinculado = $Field->Curso_Vinculado;
							
							// $Td_Html .= "<tr><td style=' width:80%;padding:5px;' >".$Curso_Vinculado."</td>   <td style=' width:80%;padding:5px;' >".$Nota."</td>  </tr>";
						// }									
					    // $Td_Html .= "</table>";
					    $Td_Html = $Td_Html_Valida;
				
				
				        $Promedio = $Suma_Nt / $Count_Curso;
						if($Promedio >= 14 ){
							$Estado = "Aprobado";
						}else{
							$Estado = "Participado";						
							
						}
				
					$Html .="	
		            <table width='100%' border='0' cellpadding='0' cellspacing='0'>
							
							<tr style='background-color:#000;color:#fff;'>
							<th  style='width:100%;padding:20px 20px;font-size:20px;'> 
							DETALLES DE LA CERTIFICACIÓN 
							</th>
							</tr>

							<tr>
							<td style='width:100%;padding:20px 20px;'> <br><br>
							". $Td_Html ."
							</td>
							</tr>
							
							<tr>
							<td style='width:100%;padding:20px 20px;'> <br><br>
							Nota Mínima de Aprobación: 14 <br>
							Nota Promedio Obtenida: ". round($Promedio, 0) ." <br>
							Estado: ". $Estado ."<br>
							Código de Certificado: COD-".$Codigo_Certificado." 
							</td>
							</tr>							
							
						</table>
					
					";
				}	
					
				ob_end_clean();
				// $html2pdf = new Html2Pdf('L', 'A4', 'en');
				// $html2pdf->setDefaultFont('Arial');
				// $html2pdf->writeHTML($Html);
				// $html2pdf->output('ticket.pdf');

				try
				{
					$html2pdf = new HTML2PDF('L', 'A4', 'en', true, 'UTF-8', array(0, 0, 0, 0));
					$html2pdf->setDefaultFont('Arial');
					$html2pdf->writeHTML($Html, false);
					$html2pdf->Output('COD-'.$Codigo_Certificado.'.pdf');
				}
				catch(HTML2PDF_exception $e) {
					echo $e;
					exit;
				}							
			

							
                DCWrite("");
                DCExit();
                break;

            case "List_Visor":
			
				$Id_Edu_Almacen = $Parm["key"];
				$Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
			    $tipo_producto = $Parm["tipo-producto"];
				DCCloseModal();
				
				$layout  = new Layout();
	
				
			    $btn = "Ir al curso ]/sadministrator/edu-articulo-det/interface/begin/request/on/key/".$Id_Edu_Almacen."/action/sugerencia/tipo-producto/".$tipo_producto."]]HREF]btn btn-primary ladda-button}";
			    $btn .= " Descargar el certificado ]/sadministrator/edu-certificado/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/interface/List/request/on/tipo-producto/".$tipo_producto."]animatedModal5]HREF_DONWLOAD]btn btn-primary ladda-button}";
				
				
				$btn = DCButton($btn, 'botones1', 'sys_form_990988');	
				$DCPanelTitle = DCPanelTitle("VISUALIZA Y DESCARGA TU CERTIFICADO ",$Nombre_Articulo,$btn);
					
			    $DCPanelTitle_Msj .= "<iframe width='100%' height='600px' src='/sadministrator/edu-certificado/key/".$Id_Edu_Almacen."/Id_Edu_Certificado/".$Id_Edu_Certificado."/interface/List/tipo-producto/".$tipo_producto."/request/on/'></iframe>";


				$Plugin = DCTablePluginA();
				
				$Contenido = DCPage($DCPanelTitle , $Pestanas ."<br>". $DCPanelTitle_Msj .  $Plugin ,"panel panel-default");
				
				if($Parm["request"] == "on"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;






            case "Confirma_Pl_Predefinida_C":
		
				$Id_Edu_Almacen = $Parm["key"];
				
				$btn = "Cancelar ]" .$UrlFile ."/interface/List/key/".$Id_Edu_Almacen."]ScreenRight]HXM]]btn btn-default dropdown-toggle}";	
                $btn .= "Si, Confirmo ]" .$UrlFile ."/interface/Confirma_Pl_Predefinida_A/key/".$Id_Edu_Almacen."]ScreenRight]HXM]]btn btn-info]}";				
								
				$Button = DCButton($btn, 'botones1', 'sys_form');					
				
			    $Html = DCModalFormMsj("Confirma el uso de la plantilla predefinida ? ",$Form,$Button,"bg-info");
							
                DCWrite($Html);
				
                break;	
				


            case "List_Excel_D_Envio":
		

		        $Id_Edu_Almacen = $Parm["key"];
		        // $Id_Edu_Articulo = $Parm["Id_Edu_Articulo"];
				// $Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        // $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
				$Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen);
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Curso = $Row_Producto->Nombre;
				
				require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/excel_classes_v2/PHPExcel.php');
	
				$objPHPExcel = new PHPExcel();			
				$objPHPExcel->getProperties()->setCreator("XELASC") // Nombre del autor
				->setLastModifiedBy("XELASC") //Ultimo usuario que lo modificó
				->setTitle("Participantes Registrados") // Titulo
				->setSubject("") //Asunto
				->setDescription("Reporte de Participantes Registrados") //Descripción
				->setKeywords("reporte de participantes registrados") //Etiquetas
				->setCategory("Reporte excel"); //Categorias		

				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1',"Source")
					->setCellValue('B1',"Target");
					
					
					
					
							
				$tableCabezera_Table ="<tr>"; 
				$tableCabezera_Table .="<th colspan='11' >".$Nombre_Curso." </th>"; 				
				$tableCabezera_Table .="</tr>"; 
				$tableCabezera_Table .="<tr>"; 
				$tableCabezera_Table .="<th>Alumno </th>"; 
				$tableCabezera_Table .="<th>Tipo Iden. </th>"; 
				$tableCabezera_Table .="<th>Nro. De Identidad </th>"; 
				$tableCabezera_Table .="<th>Nro. De Contacto </th>"; 
				$tableCabezera_Table .="<th>Email </th>"; 
				$tableCabezera_Table .="<th>Departamento </th>"; 
				$tableCabezera_Table .="<th>Provincia </th>"; 
				$tableCabezera_Table .="<th>Dirección </th>"; 
				$tableCabezera_Table .="<th>Tipo Ubicación </th>"; 
				$tableCabezera_Table .="<th>Datos. Certificado </th>"; 
				$tableCabezera_Table .="<th>Datoa Envio </th>"; 
				$tableCabezera_Table .="</tr>"; 
				


				
				$Query = "
				
					SELECT  
					EC.Nombres
					, ETI.Nombre AS T_Identidad
					, EC.Numero_Identidad
					, EC.Telefono_Contacto
					, UM.Email
					, EC.Departamento
					, EC.Provincia
					, EC.Direccion
					, EC.Estado_Edicion_Datos_Certificado
					, EC.Estado_Edicion_Datos_Envio
					, ETU.Nombre AS Tipo_Ubicacion
					FROM 
					edu_certificado EC 
					
					INNER JOIN suscripcion SC ON SC.Id_Suscripcion = EC.Id_Suscripcion
					INNER JOIN  user_miembro UM ON SC.Id_User = UM.Id_User_Miembro
					INNER JOIN  user US ON UM.Id_User_Creation = US.Id_User	
					INNER JOIN  edu_tipo_documento_identidad ETI ON ETI.Id_Edu_Tipo_Documento_Identidad = EC.Id_Edu_Tipo_Documento_Identidad	
					INNER JOIN  edu_tipo_ubicacion ETU ON ETU.Id_Edu_Tipo_Ubicacion = EC.Id_Edu_Tipo_Ubicacion	
					
					WHERE 
					EC.Id_Edu_Almacen = :Id_Edu_Almacen AND 
					EC.Modalidad_Venta_Curso = :Modalidad_Venta_Curso  

					
				";    

				$Where = ["Id_Edu_Almacen"=>$Id_Edu_Almacen, "Modalidad_Venta_Curso"=> "En_Vivo"];
		        $Registro_B = ClassPdo::DCRows($Query,$Where,$Conection);
			
				$ContGeneral = 0;
				$tableCuerpo = "";
				foreach ($Registro_B as $Reg) {
					
					$ContGeneral += 1;				
					
                                // $Id_Suscripcion_A = $Reg->Id_Suscripcion;
				
								$tableCuerpo .= "<tr>";			    
								$tableCuerpo .= "<td>".$Reg->Nombres."</td>";	
								$tableCuerpo .= "<td>".$Reg->T_Identidad."</td>";	
								$tableCuerpo .= "<td>".$Reg->Numero_Identidad."</td>";	
								$tableCuerpo .= "<td>".$Reg->Telefono_Contacto."</td>";	
								$tableCuerpo .= "<td>".$Reg->Email."</td>";	
								$tableCuerpo .= "<td>".$Reg->Departamento."</td>";	
								$tableCuerpo .= "<td>".$Reg->Provincia."</td>";	
								$tableCuerpo .= "<td>".$Reg->Direccion."</td>";	
								$tableCuerpo .= "<td>".$Reg->Tipo_Ubicacion."</td>";	
								$tableCuerpo .= "<td>".$Reg->Estado_Edicion_Datos_Certificado."</td>";	
								$tableCuerpo .= "<td>".$Reg->Estado_Edicion_Datos_Envio."</td>";	
                                    
							
								

								$tableCuerpo .= "</tr>";

				}	
				

				$table = "<table border='1' width='100%' >";		
				
		
								
				$table .= $tableCabezera_Table;			
				$table .= $tableCuerpo;			
				$table .= "</table>";	

				header ("Content-Type: application/vnd.ms-excel");
				header ("Content-Disposition: inline; filename=Envio_Certificados_".$Id_Edu_Almacen.".xls");
				
				echo $table;

				exit();	
				
			    break;		
				
				

            case "Confirma_Pl_Predefinida_A":
		
					$Id_Edu_Almacen = $Parm["key"];
					
					
					$data = array(
					'Tipo_Certificado' =>  "Predefinido",
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
					new Edu_Gestion_Certificado($Settings);
					DCExit();				
					
                break;	
								
				
				
            case "configura_plantilla_predefinida":
			
				$Id_Edu_Almacen = $Parm["key"];
				
	            $Id_Edu_Banner = $Parm["Id_Edu_Banner"];
				$DirecctionA = $UrlFile."/process/ENTRY/Obj/Entity_Certificado_Crud/Id_Entity/".$Entity."/key/".$Id_Edu_Almacen;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Banner/".$Id_Edu_Banner;
				

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

            case "Create_Edit_Certificado":
				
				$DCPanelTitle = DCPanelTitle("SOLO PODRÁ MODIFICAR UNA VEZ","<b>Los valores que inserte serán reflejados en el certificado</b>","");
				
	            $Id_Edu_Almacen = $Parm["Id_Edu_Almacen"];
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
	            $tipo_producto = $Parm["tipo-producto"];
	            $interfaceraiz = $Parm["interfaceraiz"];
				
				
				$DirecctionA = $UrlFile."/process/ENTRY/Obj/Edu_Certificado_Digital_Participante/Id_Edu_Certificado/".$Id_Edu_Certificado."/Id_Edu_Almacen/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."/interfaceraiz/".$interfaceraiz;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Banner/".$Id_Edu_Banner;
				$Name_Interface = "CONFIRMA TUS DATOS PARA LA EMISIÓN DEL CERTIFICADO";		
				
				if(!empty($Id_Edu_Certificado)){
			    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Confirmar Datos";
                    // $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Certificado_Digital_Participante","btn btn-default m-w-120");					
				}else{
	
				    $Name_Button = "<i class='zmdi zmdi-plus zmdi-hc-fw'></i> Crear";	
                    // $ButtonAdicional = array("Cancelar",$DirecctionA,"ScreenRight","Form","Publicacion_Blog_Crud");							
				}
				
				$Combobox = array(
				     array("Id_Edu_Pais"," SELECT Id_Edu_Pais AS Id, Nombre AS Name FROM edu_pais ORDER BY Nombre ASC ",[]),
					 array("Id_Edu_Tipo_Documento_Identidad"," SELECT Id_Edu_Tipo_Documento_Identidad AS Id, Nombre AS Name FROM edu_tipo_documento_identidad ",[])


				);
				
				$PathImage = array(
				     array("Imagen","/sadministrator/simages/avatars")
				);
				
				$Buttons = array(
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","Edu_Certificado_Digital_Participante"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Certificado_Digital_Participante",$Class,$Id_Edu_Certificado,$PathImage,$Combobox,$Buttons,"Id_Edu_Certificado");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;			

            case "Create_Edit_Certificado_Envio":
			
				$DCPanelTitle = DCPanelTitle("SOLO PODRÁ MODIFICAR UNA VEZ"," 
				<div style='background-color:red;    padding: 10px;color: #fff;' > <b>Si los datos son incorrectos, usted se hará responsable de pagar los costos adicionales por concepto de reenvío </b> </div>","");
				
	            $Id_Edu_Almacen = $Parm["key"];
	            $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
			    $tipo_producto = $Parm["tipo-producto"];
				$interfaceraiz = $Parm["interfaceraiz"];
				
				$DirecctionA = $UrlFile."/process/ENTRY/Obj/Edu_Certificado_Datos_Envio/Id_Edu_Certificado/".$Id_Edu_Certificado."/Id_Edu_Almacen/".$Id_Edu_Almacen."/tipo-producto/".$tipo_producto."/interfaceraiz/".$interfaceraiz;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Banner/".$Id_Edu_Banner;
				$Name_Interface = "CONFIRMA TUS DATOS PARA EL ENVÍO DEL CERTIFICADO";		
				
				if(!empty($Id_Edu_Certificado)){
			    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Confirmar Datos";
                    // $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Certificado_Digital_Participante","btn btn-default m-w-120");					
				}else{
	
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
				     array($Name_Button,$DirecctionA,"Mensaje_Id","Form","Edu_Certificado_Datos_Envio"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Certificado_Datos_Envio",$Class,$Id_Edu_Certificado,$PathImage,$Combobox,$Buttons,"Id_Edu_Certificado");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
				
                break;			



            case "DeleteMassive":
		
		        $Id_Edu_Banner = $Parm["Id_Edu_Banner"];
				
				$btn = "Continue ]" .$UrlFile ."/Process/DELETE/Id_Edu_Banner/".$Id_Edu_Banner."/Obj/Edu_Banner_Crud]animatedModal5]HXMS]]btn btn-default dropdown-toggle]}";				
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
				    SELECT PB.Id_Edu_Banner AS CodigoLink, PB.Frase, PB.Estado FROM edu_banner PB
					WHERE PB.Seccion_Pagina =:Seccion_Pagina
				";    
				$Class = 'table table-hover';
				$LinkId = 'Id_Edu_Banner';
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
				
 
	            $Id_Edu_Banner = $Parm["Id_Edu_Banner"];
				$DirecctionA = $UrlFile."/Process/ENTRY/Obj/Edu_Banner_Crud/Seccion_Pagina/Para_Empresa/Id_Edu_Banner/".$Id_Edu_Banner;
				$DirecctionDelete = $UrlFile."/Interface/DeleteMassive/Id_Edu_Banner/".$Id_Edu_Banner;
				
				if(!empty($Id_Edu_Banner)){
				    $Name_Interface = "Editar Banner ";				    	
				    $Name_Button = "<i class='zmdi zmdi-check zmdi-hc-fw'></i> Actualizar";
                    $ButtonAdicional = array("<i class='zmdi zmdi-delete zmdi-hc-fw'></i> Eliminar",$DirecctionDelete,"animatedModal5","Form","Edu_Banner_Crud","btn btn-default m-w-120");					
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
				     array($Name_Button,$DirecctionA,"animatedModal5","Form","Edu_Banner_Crud"),$ButtonAdicional
				);	
		        $Form1 = BFormVertical("Edu_Banner_Crud",$Class,$Id_Edu_Banner,$PathImage,$Combobox,$Buttons,"Id_Edu_Banner");
				
			    $Html = DCModalForm($Name_Interface,$DCPanelTitle . $Form1,"");
                DCWrite($Html);
                DCExit();
                break;	
				
			
        }
				
		
		
	}
	
	public function ObjectDelete($Settings) {
       	global $Conection, $DCTimeHour,$NameTable;

		$Id_Edu_Banner = $Settings["Id_Edu_Banner"];
			
		$where = array('Id_Edu_Banner' =>$Id_Edu_Banner);
		$rg = ClassPDO::DCDelete('edu_banner', $where, $Conection);
		
		DCWrite(Message("Process executed correctly","C"));
						
	}		
	
	
	
}