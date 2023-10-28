<?php
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class Biblioteca{

	public function MainData($Parm) {
        
		global $Conection;
		
		$Query = "SELECT WH.Id_Warehouse, WH.Name  
		FROM warehouse WH 
		WHERE WH.Id_Warehouse = :Id_Warehouse
		";	
		$Where = ["Id_Warehouse" => $Parm["Id_Warehouse"]];
		$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		return $Row;
        
	}
	
	public function Tabs($arg){
		
		global $Conection;
		
		$menu = " Mis Cursos]/sadministrator/edu-articulo-curso]ScreenRight]Marca]}";
		
		$Perfil = Biblioteca::Valida_Perfil("");
		// DCVd($Perfil);
		if($Perfil != 3){
			$menu .= "Producción ]/sadministrator/articulo]ScreenRight]]}";			
			// $menu .= " Demo ]/sadministrator/articulo/Interface/Demo]ScreenRight]]}";			
		}else{
			$menu .= "Mis Programas]/sadministrator/edu-articulo-programa]ScreenRight]]}";	
		}
		// $menu .= "Historial ]/sadministrator/catalogo]ScreenRight]]}";
		
		$pestanas = DCTabs($menu, 'nav nav-tabs nav-tabs-custom m-b-15','Menu_One',$arg);
		return $pestanas;
	}
	public function Tabs_Programa($arg,$Url_Amigable){
		
		global $Conection;
		
		$Perfil = Biblioteca::Valida_Perfil("");
		// DCVd($Perfil);
		if($Perfil != 3){
			//$menu .= "Producción ]/sadministrator/articulo]ScreenRight]]}";		
		}else{
			$menu = "Mis Cursos]/sadministrator/edu-programa/Interface/begin/programa/".$Url_Amigable."]ScreenRight]]}";
			$menu .= "Detalles del Programa]/sadministrator/edu-programa/Interface/info/programa/".$Url_Amigable."]ScreenRight]]}";	
		}
		// $menu .= "Historial ]/sadministrator/catalogo]ScreenRight]]}";
		
		$pestanas = DCTabs($menu, 'nav nav-tabs nav-tabs-custom m-b-15','Menu_One',$arg);
		return $pestanas;
	}



	public function Valida_Perfil($arg){
		
		global $Conection;

		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Query = "
		SELECT 
				
			US.Usuario_Login
			, US.Password
			, UM.Nombre
			, ET.Id_Entity
			, US.Id_User
			, UM.Id_Perfil
			, UM.Foto
			, ET.Logo_Externo
			, ET.Logo_Interno
			FROM user_miembro UM
			INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
			INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
			
		WHERE 
		UM.Entity = :Entity 
		AND UM.Id_User_Miembro = :Id_User_Miembro 
		"; 		
		$Where = ["Entity"=>$Entity,"Id_User_Miembro"=>$User];				
		$Row = ClassPDO::DCRow($Query,$Where,$Conection);	
		return $Row->Id_Perfil;
	}
	
	
	public function JsComboBoxTipoEstructura(){
	
	    $script =" <script>
		
				$('#Edu_Articulo_Crud-Id_Edu_Tipo_Componente').on('change', '', function (e) {
						
                        Busqueda_Tipo_Estructura();
                 });
								 
				function Busqueda_Tipo_Estructura() {

					var Id_Edu_Tipo_Componente = $('#Edu_Articulo_Crud-Id_Edu_Tipo_Componente').val();
			
					
					var ruta = '/sadministrator/articulo/Interface/Datos_Tipo_Estructura/Id_Edu_Tipo_Componente/'+Id_Edu_Tipo_Componente+'';

					var parametros = {
						};
	                    $('#Edu_Articulo_Crud-Id_Edu_Tipo_Estructura').html('<option value=cargando>Cargando Datos..</option>');
						$.ajax({
							data: parametros,
							url: ruta,
							type: 'get',
							success: function(response) {
										console.log('response');
					console.log(response);
								$('#Edu_Articulo_Crud-Id_Edu_Tipo_Estructura').html(response);
								$('#Edu_Articulo_Crud-Id_Edu_Tipo_Estructura').fadeIn(1500);
							}
						});

				}
				
				
				
				$('#Edu_Articulo_Crud-Id_Edu_Area_Conocimiento').on('change', '', function (e) {
						
                        Busqueda_Sub_Linea();
                 });
								 
				function Busqueda_Sub_Linea() {

					var Id_Edu_Area_Conocimiento = $('#Edu_Articulo_Crud-Id_Edu_Area_Conocimiento').val();
			
					
					var ruta = '/sadministrator/articulo/Interface/Datos_Sub_Linea/Id_Edu_Area_Conocimiento/'+Id_Edu_Area_Conocimiento+'';

					var parametros = {
						};
	                    $('#Edu_Articulo_Crud-Id_Edu_Sub_Linea').html('<option value=cargando>Cargando Datos..</option>');
						$.ajax({
							data: parametros,
							url: ruta,
							type: 'get',
							success: function(response) {
										console.log('response');
					console.log(response);
								$('#Edu_Articulo_Crud-Id_Edu_Sub_Linea').html(response);
								$('#Edu_Articulo_Crud-Id_Edu_Sub_Linea').fadeIn(1500);
							}
						});

				}				
				

				
				</script> ";
	    return $script;
    }
	
	
	public function JsComboBox_Area_Conocimiento_Productor(){
	
	    $script =" <script>
		

				$('#Edu_Productor_Crud-Id_Edu_Area_Conocimiento').on('change', '', function (e) {
						
                        Busqueda_Sub_Linea_Productor();
                 });
								 
				function Busqueda_Sub_Linea_Productor() {

					var Id_Edu_Area_Conocimiento = $('#Edu_Productor_Crud-Id_Edu_Area_Conocimiento').val();
			
					
					var ruta = '/sadministrator/articulo/Interface/Datos_Sub_Linea/Id_Edu_Area_Conocimiento/'+Id_Edu_Area_Conocimiento+'';

					var parametros = {
						};
	                    $('#Edu_Productor_Crud-Id_Edu_Sub_Linea').html('<option value=cargando>Cargando Datos..</option>');
						$.ajax({
							data: parametros,
							url: ruta,
							type: 'get',
							success: function(response) {
										console.log('response');
					console.log(response);
								$('#Edu_Productor_Crud-Id_Edu_Sub_Linea').html(response);
								$('#Edu_Productor_Crud-Id_Edu_Sub_Linea').fadeIn(1500);
							}
						});

				}				
				

				
				</script> ";
	    return $script;		
	}

	
	public function JsComboBoxArea_Conocimiento(){
	
	    $script =" <script>
		

				$('#User_Register_Crud-Id_Edu_Area_Conocimiento').on('change', '', function (e) {
						
                        Busqueda_Sub_Linea();
                 });
								 
				function Busqueda_Sub_Linea() {

					var Id_Edu_Area_Conocimiento = $('#User_Register_Crud-Id_Edu_Area_Conocimiento').val();
			
					
					var ruta = '/sadministrator/articulo/Interface/Datos_Sub_Linea/Id_Edu_Area_Conocimiento/'+Id_Edu_Area_Conocimiento+'';

					var parametros = {
						};
	                    $('#User_Register_Crud-Id_Edu_Sub_Linea').html('<option value=cargando>Cargando Datos..</option>');
						$.ajax({
							data: parametros,
							url: ruta,
							type: 'get',
							success: function(response) {
										console.log('response');
					console.log(response);
								$('#User_Register_Crud-Id_Edu_Sub_Linea').html(response);
								$('#User_Register_Crud-Id_Edu_Sub_Linea').fadeIn(1500);
							}
						});

				}				
				

				
				</script> ";
	    return $script;
    }
	
	public function Articulos($Parm,$Tipo_Vista){
		global $Conection;
		
		$User = $_SESSION['User'];
		$Entity = $_SESSION['Entity'];
		
		$Queryd = $Parm["Qr"];
		$Queryd = explode(" ", $Queryd);;
		$Parm1 = $Queryd[0];
		$Parm2 = $Queryd[1];
		if(empty($Queryd)){
			$OperadorA = "<>";
			$Parm1 = "8b8b8b8b8b8b8b8b8bb8b8b";
			$Parm2 = "8b8b8b8b8b8b8b8b8bb8b8b";
		}else{
			$OperadorA = "LIKE";
		}
        
		$DCDate = DCDate(); 
		// if(!empty(post("Nombre"))){ $Nombre = DCPO("Nombre"); $Nombre_O ="LIKE"; }else{ $Nombre = "77777777777777777777";  $Nombre_O="<>";}
				
	    // echo $Tipo_Vista;
		if($Tipo_Vista == "En_Curso"){
			
			$Query = "
				SELECT 
				AR.Id_Edu_Articulo 
				, EA.Id_Edu_Almacen
				, AR.Nombre
				, AR.Url_Amigable
				, AR.Estado
				, AR.Imagen
				, AR.Imagen_Presentacion				
				, AR.Id_Edu_Tipo_Estructura
				, AR.Date_Time_Creation
				, EAC.Nombre AS Categoria  
				, AR.Fecha_Publicacion
				FROM suscripcion SC
				INNER JOIN edu_almacen EA ON SC.Id_Edu_Almacen = EA.Id_Edu_Almacen
				INNER JOIN edu_articulo AR ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
				INNER JOIN edu_area_conocimiento EAC ON AR.Id_Edu_Area_Conocimiento = EAC.Id_Edu_Area_Conocimiento
				WHERE 
				SC.Id_User = :Id_User 
				AND SC.Visibilidad=:Estado 
				AND SC.Producto_Origen=:Origen
				AND  AR.Id_Edu_Tipo_Estructura = :Id_Edu_Tipo_Estructura 
				AND  AR.Entity = :Entity
				AND  AR.Estado=:Estado
				AND ( AR.Nombre ".$OperadorA." :Nombre )	
				AND SC.Fecha_Fin >= :Fecha_Fin
				ORDER BY AR.Date_Time_Creation DESC
			
			";    
			$Where = [
			"Id_User"=>$User
			,"Estado"=>"Activo"
			, "Id_Edu_Tipo_Estructura" => 1
			, "Entity" => $Entity
			, "Origen"=>"CURSO"
			, "Nombre"=>'%'.$Parm1.'%'
			, "Fecha_Fin"=> $DCDate
			
			];
					// DCVd($OperadorA);
					// DCVd($Parm1);
			
			
		}elseif($Tipo_Vista == "Creacion"){

			$Query = "
				SELECT 
				AR.Id_Edu_Articulo 
				, EA.Id_Edu_Almacen
				, AR.Nombre
				, AR.Estado
				, AR.Url_Amigable
				, AR.Imagen
				, AR.Imagen_Presentacion				
				, AR.Id_Edu_Tipo_Estructura				
				, AR.Date_Time_Creation
				, AR.Fecha_Publicacion
				, EAC.Nombre AS Categoria  
				FROM edu_articulo AR
				INNER JOIN edu_almacen EA ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
				INNER JOIN edu_area_conocimiento EAC ON AR.Id_Edu_Area_Conocimiento = EAC.Id_Edu_Area_Conocimiento
				WHERE AR.Entity = :Entity
				ORDER BY AR.Date_Time_Creation DESC
				
		
			";    
			$Id_Edu_Area_Conocimiento = $Parm["theme"];
			$Where = ["Entity"=>$Entity];
			
		}elseif($Tipo_Vista == "Membresia"){

			$Query = "
				SELECT 
				AR.Id_Edu_Articulo 
				, EA.Id_Edu_Almacen
				, AR.Nombre
				, AR.Estado
				, AR.Url_Amigable
				, AR.Imagen
				, AR.Imagen_Presentacion				
				, AR.Id_Edu_Tipo_Estructura				
				, AR.Date_Time_Creation
				, AR.Fecha_Publicacion
				, EAC.Nombre AS Categoria  
				FROM edu_articulo AR
				INNER JOIN edu_almacen EA ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
				INNER JOIN edu_area_conocimiento EAC ON AR.Id_Edu_Area_Conocimiento = EAC.Id_Edu_Area_Conocimiento
				WHERE AR.Entity = :Entity AND AR.Estado=:Estado
				ORDER BY AR.Date_Time_Creation DESC
				
		
			";    
			$Id_Edu_Area_Conocimiento = $Parm["theme"];
			$Where = ["Entity"=>$Entity, "Estado"=>"Activo"];
			
		}elseif($Tipo_Vista == "Areas"){	
		
			
				$Query = "
				    SELECT 
					AR.Id_Edu_Articulo 
					, EA.Id_Edu_Almacen
					, AR.Nombre
					, AR.Url_Amigable
					, AR.Estado
					, AR.Imagen
				    , AR.Imagen_Presentacion					
				    , AR.Id_Edu_Tipo_Estructura					
					, AR.Date_Time_Creation
					, AR.Fecha_Publicacion
					, EAC.Nombre AS Categoria  
					FROM edu_articulo AR
					INNER JOIN edu_almacen EA ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
					INNER JOIN edu_area_conocimiento EAC ON AR.Id_Edu_Area_Conocimiento = EAC.Id_Edu_Area_Conocimiento
					WHERE 
					AR.Estado = :Estado 
					AND EAC.Alias_Id = :Alias_Id
					AND AR.Entity = :Entity
					AND ( AR.Nombre ".$OperadorA." :Nombre )
					ORDER BY AR.Fecha_Publicacion DESC
					
				";    
				$Id_Edu_Area_Conocimiento = $Parm["theme"];
		        $Where = ["Estado"=>"Activo","Entity"=>$Entity,"Alias_Id" => $Id_Edu_Area_Conocimiento, "Nombre"=>'%'.$Parm1.'%'];
			  			
				
		}else{
			
			// DCWrite("Hola musno");
			$Query = "
				SELECT 
				AR.Id_Edu_Articulo 
				, EA.Id_Edu_Almacen
				, AR.Nombre
				, AR.Url_Amigable
				, AR.Estado
				, AR.Imagen
				, AR.Imagen_Presentacion
				, AR.Fecha_Publicacion
				, AR.Id_Edu_Tipo_Estructura				
				, AR.Date_Time_Creation
				, EAC.Nombre AS Categoria  
				FROM edu_articulo AR
				INNER JOIN edu_almacen EA ON AR.Id_Edu_Articulo = EA.Id_Edu_Articulo
				INNER JOIN edu_area_conocimiento EAC ON AR.Id_Edu_Area_Conocimiento = EAC.Id_Edu_Area_Conocimiento
				WHERE 
				AR.Estado = :Estado 
				AR.Entity = :Entity 
				AND AR.Id_Edu_Tipo_Estructura = :Id_Edu_Tipo_Estructura 
				AND ( AR.Nombre ".$OperadorA." :Nombre )
				ORDER BY AR.Date_Time_Creation DESC
				
			";    

			$Where = ["Entity"=>$Entity,"Estado"=>"Activo","Id_Edu_Tipo_Estructura" => 1 , "Nombre"=>'%'.$Parm1.'%'];
			
		}
				
		$Rows = ClassPdo::DCRows($Query,$Where,$Conection);
		$Fields ="";
		$Count = 0;
		
		$Card = "";
		foreach($Rows AS $Field){
					
					$Count += 1;	
					if($Field->Estado == "Activo"){
						$Estado = "Activo";
					}else{
						$Estado = "Desactivo";						
					}
					
					$Query = "
						SELECT 
						SC.Id_Suscripcion
						, SC.Id_User 
						FROM suscripcion SC
						WHERE 
						SC.Id_Edu_Almacen = :Id_Edu_Almacen AND SC.Id_User = :Id_User 
					";	
					$Where = ["Id_Edu_Almacen" => $Field->Id_Edu_Almacen, "Id_User" =>$User];
					$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
					$Id_Suscripcion = $Row->Id_Suscripcion;	
					
					if($Tipo_Vista == "En_Curso" || $Tipo_Vista == "Creacion" || $Tipo_Vista == "Areas"  || $Tipo_Vista == "Tendencia"){
						$Id_Suscripcion = "";
					}
					
					if(empty($Id_Suscripcion)){

						if($Field->Id_Edu_Tipo_Estructura == 3){
                            
							// if(empty($Field->Url_Amigable)){
								$Url = "/sadministrator/edu-blog/interface/begin/request/on/key/".$Field->Id_Edu_Almacen."/action/sugerencia";
							// }else{
								// $Url = "/".$Field->Url_Amigable;
							// }
						
						}elseif($Field->Id_Edu_Tipo_Estructura == 6){

						    $Url = "/sadministrator/edu-imagen/interface/begin/request/on/key/".$Field->Id_Edu_Almacen."/action/sugerencia";
						
						}elseif($Field->Id_Edu_Tipo_Estructura == 8){

						    $Url = "/sadministrator/edu-video/interface/begin/request/on/key/".$Field->Id_Edu_Almacen."/action/sugerencia";
												
						}else{
						
                            // if(empty($Field->Url_Amigable)){
								$Url = "/sadministrator/edu-articulo-det/interface/begin/request/on/key/".$Field->Id_Edu_Almacen."/action/sugerencia";
							// }else{
								// $Url = "/".$Field->Url_Amigable;
							// }							
							
						}
						
						if(!empty($Field->Imagen_Presentacion)){
							$Imagen_Presentacion = $Field->Imagen;
							
						}else{
							
							$Domain = "http://{$_SERVER["HTTP_HOST"]}";
							// $Imagen_Presentacion = DCResizeImage('../sadministrator/simages/articulo/'.$Field->Imagen,300,180);
							$Imagen_Presentacion = $Field->Imagen;
															
							$reg = array(
							'Imagen_Presentacion' => $Field->Imagen
							);
							$where = array('Id_Edu_Articulo' =>$Field->Id_Edu_Articulo);
							$rg = ClassPDO::DCUpdate("edu_articulo", $reg , $where, $Conection);	
							
						
						}
					

						$Card .= '					
							<div class="col-md-3">
							  <div class="c-product" style="Position:relative;">
					
								<a class="cp-img" style="background-image: url('.$Domain.'/sadministrator/simages/articulo/'.$Imagen_Presentacion.')" 
								href="'.$Domain.$Url.'" >
								</a>
								  <div class="cp-content">
									  <div class="cp-title" >
										'.$Field->Nombre.'
										';
										
						$Card .= '										
									  </div>
								  </div>								
							  </div>
							</div>					
						';
					}			
		}
		return $Card;
		
	}

	
}

// class WarehouseDetail{

	// public function MainData($Parm) {
        
		// global $Conection;
		
		// $Query = "SELECT WHD.Id_Warehouse_Detail, WHD.Name, WHD.Id_Data_Type, WHD.Pk ,WHD.Length, WHD.Id_Warehouse
		// FROM warehouse_detail WHD 
		// WHERE WHD.Id_Warehouse_Detail = :Id_Warehouse_Detail
		// ";	
		// $Where = ["Id_Warehouse_Detail" => $Parm["Id_Warehouse_Detail"]];
		// $Row = ClassPdo::DCRow($Query,$Where,$Conection);	
		// return $Row;
        
	// }
	
// }



