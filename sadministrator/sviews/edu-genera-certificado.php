<?php
    // session_start();
error_reporting(0);
require_once('../../sbookstores/php/qr/vendor/autoload.php');
require_once('../../sbookstores/php/qr/Utilities/qr_factory.php');
use Utilities\qr_factory;

require_once('../../sbookstores/php/functions.php');

require_once('../../sbookstores/php/pdf/vendor/autoload.php');
use Spipu\Html2Pdf\Html2Pdf;

$qr_code = new qr_factory;
$imgQRA = $qr_code->generate("https://pagolink.niubiz.com.pe/pagoseguro/STANDARDBUSINESSUNIVERSITY/2239316","primer_qr","230");
// echo 
// exit();

				// $Id_Edu_Almacen = $Parm["key"];
				// $Id_Edu_Certificado = $Parm["Id_Edu_Certificado"];
				// $tipo_visualizacion = $Parm["tipo_visualizacion"];
				// $tipo_descarga = $Parm["tipo_descarga"];
				// $tipo_producto = $Parm["tipo-producto"];
		$Entity = $_SESSION['Entity'];		

// echo $Entity;		
				$Id_Edu_Almacen = $_GET["key"];
				$Id_Edu_Certificado = $_GET["Id_Edu_Certificado"];
				$tipo_visualizacion = $_GET["tipo_visualizacion"];
				$tipo_descarga = $_GET["tipo_descarga"];
				$tipo_producto = $_GET["tipo-producto"];				
				
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
						,  EC.Id_Perfil_Educacion 
                        ,  EC.Id_Edu_Certificado
						,  EC.Considerar_Fecha_Inicio 
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
                    $Id_Edu_Certificado = $Row->Id_Edu_Certificado;			
         	
                    $Numero_Identidad = $Row->Numero_Identidad;			
                    $Id_Edu_Almacen = $Id_Edu_Almacen;		
                    $Id_Edu_Pais = $Row->Id_Edu_Pais;		
                    $Fecha_Inicio = $Row->Fecha_Inicio;		
                    $Fecha_Fin = $Row->Fecha_Fin;		
                    $Nombre_Curso_Alias = $Row->Nombre_Curso;		
                    $Id_Perfil_Educacion = $Row->Id_Perfil_Educacion;		
                    $Considerar_Fecha_Inicio = $Row->Considerar_Fecha_Inicio;		
					
					


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
						
						
							if($Considerar_Fecha_Inicio == "Activo"){
								
								$Estado_Academico_text = "realizado desde ";
								
							}else{
								
							
								if($Fecha_Inicio == "0000-00-00"){
									
									$Estado_Academico_text = "finalizado";		
									
								}else{
									
									$Estado_Academico_text = "realizado desde";
									
								}	
								
							}
						
						
							// $Estado_Academico_text = "finalizado";
							$Modalidad_Curso = "001";	
						//Estado_Academico_text
						
					}else{
						
						if($Considerar_Fecha_Inicio == "Activo"){
							
							$Estado_Academico_text = "realizado desde ";
							
						}else{
							$Estado_Academico_text = "aprobado";
						}
						
				        // $Estado_Academico_text = "aprobado";
						
                        $Modalidad_Curso = "002";
						
					}
					
                    $Codigo_Certificado = $Id_Edu_Almacen."_".$Id_Edu_Pais."_".$Numero_Identidad."_".$Modalidad_Curso;
					
					if($Estado_Academico == "aprobado"){
						
						
						if( $Id_Perfil_Educacion == 1){
							
							$Tipo_Constancia = "CERTIFICADO DE DOCENCIA ";
							$Modalidad_Venta_Curso_1 = "participado como docente en ";						
							$Tipo_Text_Otorgamiento = "Otorgado a ";								
							
						}else{
							
							$Tipo_Constancia = "CERTIFICADO";
							$Modalidad_Venta_Curso_1 = "participado y aprobado";						
							$Tipo_Text_Otorgamiento = "Otorgado a ";	
                        }
						
					}	

					if($Estado_Academico == "participado"){
						
						$Tipo_Constancia = " CONSTANCIA ";	
						$Tipo_Text_Otorgamiento = "Otorgada a ";
				        $Modalidad_Venta_Curso_1 = "participado en";	
                        // $Estado_Academico_text = "finalizado";						
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
				
				// $Row_Producto =  SetttingsSite::Main_Data_Producto($Id_Edu_Almacen,$tipo_producto);
				
				
		$Query = "
				SELECT 
				AR.Id_Edu_Articulo 
				, EA.Id_Edu_Almacen
				, AR.Nombre
				, AR.Usar_Acta_Nota
				, AR.Descripcion
				, AR.Link
				, AR.Palabras_Claves
				, AR.Imagen
				, AR.Id_Edu_Area_Conocimiento
				, AR.Inhouse
				, AR.Id_Edu_Sub_Linea
				, AR.Date_Time_Creation
				, AR.Fecha_Publicacion
				, AR.Id_Edu_Tipo_Privacidad
				, AR.Horas_Lectivas
				, AR.Fecha_Fin_Curso
				, EAC.Nombre AS Categoria  
				, EAC.Fundacion  
				, EAC.Site_Facebook_Fundacion  
				FROM edu_articulo AR
				INNER JOIN edu_almacen EA ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
				INNER JOIN edu_area_conocimiento EAC ON AR.Id_Edu_Area_Conocimiento = EAC.Id_Edu_Area_Conocimiento
				WHERE EA.Id_Edu_Almacen = :Id_Edu_Almacen
			";	
			$Where = ["Id_Edu_Almacen" => $Id_Edu_Almacen];
			$Row_Producto = ClassPdo::DCRow($Query,$Where,$Conection);				
				
		        $Id_Edu_Articulo = $Row_Producto->Id_Edu_Articulo;
		        $Nombre_Curso = $Row_Producto->Nombre;	
		        $Inhouse = $Row_Producto->Inhouse;	
				
				if(!empty($Nombre_Curso_Alias)){
					$Nombre_Curso = $Nombre_Curso_Alias;
				}
				
				// $btn = "Subir Componentes ]" .$UrlFile."/interface/configura_plantilla_predefinida/key/".$Id_Edu_Almacen."]animatedModal5]HXM]btn btn-primary ladda-button}";
				// $btn_3 = DCButton($btn, 'botones1', 'sys_form');	
				// $DCPanelTitle_Msj = DCPanelTitle("Configura la plantilla predefinida","",$btn_3);	

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

				}elseif( $Entity == 9 ){
					$Color_Nombre_Particpante = "color:#cd0b4b;";					
				}else{
					
					$Color_Nombre_Particpante = "color:#1cad1a;";					
				}
				
				
				$Certificado_Firma_1 = $Row->Certificado_Firma_1;
				$Certificado_Firma_2 = $Row->Certificado_Firma_2;
				
				if($tipo_descarga == "c_fisico" && $Modalidad_Venta_Curso == "En_Vivo"){
					
					$Firma_Hmtl_1 = "";
					$Firma_Hmtl_2 = "";
				}else{
					$Firma_Hmtl_1 = "<img src='http://yachai.local/sadministrator/simages/".$Certificado_Firma_1."' style='width:200px;'>";
					$Firma_Hmtl_2 = "<img src='http://yachai.local/sadministrator/simages/".$Certificado_Firma_2."' style='width:200px;'>";					
				}
				
				$Logo_Certificado_2 = $Row->Logo_Certificado_2;
				$Logo_Certificado_1 = $Row->Logo_Certificado_1;
				$Certificado_Sello_1 = $Row->Certificado_Sello_1;
				$Certificado_Nombre_Firma_1 = $Row->Certificado_Nombre_Firma_1;
				$Certificado_Cargo_Firma_1 = $Row->Certificado_Cargo_Firma_1;
				$Certificado_Nombre_Firma_2 = $Row->Certificado_Nombre_Firma_2;
				$Certificado_Cargo_Firma_2 = $Row->Certificado_Cargo_Firma_2;
				
				if($Modalidad_Venta_Curso == "En_Vivo"){	

							if($Considerar_Fecha_Inicio == "Activo"){
								
							    $Fechas = $Fecha_Inicio_Dia." de ".$Fecha_Inicio_Mes." del ".$Fecha_Inicio_Year." al ".$Fecha_Fin_Dia." de ".$Fecha_Fin_Mes." del ".$Fecha_Fin_Year;

							}else{
								
							
								if($Fecha_Inicio == "0000-00-00"){
									
									$Fechas = $Fecha_Fin_Dia." de ".$Fecha_Fin_Mes." del ".$Fecha_Fin_Year;		
									
								}else{
									
									
							         $Fechas = $Fecha_Inicio_Dia." de ".$Fecha_Inicio_Mes." del ".$Fecha_Inicio_Year." al ".$Fecha_Fin_Dia." de ".$Fecha_Fin_Mes." del ".$Fecha_Fin_Year;

									
								}	
								
							}				
				
				
				}else{
					
					if($Considerar_Fecha_Inicio == "Activo"){
						
						$Fechas = $Fecha_Inicio_Dia." de ".$Fecha_Inicio_Mes." del ".$Fecha_Inicio_Year." al ".$Fecha_Fin_Dia." de ".$Fecha_Fin_Mes." del ".$Fecha_Fin_Year;

					}else{	
					
						$Fechas = $Fecha_Fin_Dia." de ".$Fecha_Fin_Mes." del ".$Fecha_Fin_Year;
						
					}					
				}
				
				
			    $Html ="
					
					<table width='100%'  bgcolor='#000'  border='0' cellpadding='0' cellspacing='0'
					style='background-image: url(http://yachai.local/sadministrator/simages/".$Certificado_Imagen.");background-repeat:no-repeat;background-size:900px 700px;' >
					    
						  <tr>
							<td style='width:1300px;height:789px;vertical-align: top;'>
					
								<table>
									  <tr>
										<td style='width:500px;height:180px;'>
									
										</td>
										<td style='width:300px;padding:60px 0px 0px 0px;'>
										    <img src='http://yachai.local/sadministrator/simages/".$Logo_Certificado_2."' style='width:280px;'>
										</td>
										<td style='width:300px;padding:60px 0px 0px 0px;'> 
										    <img src='http://yachai.local/sadministrator/simages/".$Logo_Certificado_1."' style='width:250px;'>
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
										    <img src='http://yachai.local/sadministrator/simages/".$Certificado_Sello_1."' style='width:150px;'>
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
												<p style='font-size:14px;padding:0px;margin:0px;'>  ".$imgQRA." </p>
										</td>										
									  </tr>
									  
								</table>								
																
								
							</td>
							
						  </tr>
					
						  
					</table> ";
					
				if($tipo_producto == "programa"){

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
						
							</td>
							</tr>							
							
						</table>
					
					";
				}	
				// echo  $Html;	
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
			

							
            


exit();		


	
	
?>