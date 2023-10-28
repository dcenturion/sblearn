<?php
$DCTimeHour = DCTimeHour();
$Conection = Conection();

class SetttingsSite{

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
		

		$Perfil_User = $_SESSION['Perfil_User'];
		if($Perfil_User == 1){
		    $menu = "Usuarios]/sadministrator/sys_setting_site]ScreenRight]Marca}";			
		    $menu .= "Perfiles]/sadministrator/user_perfil]ScreenRight}";			
		}

		$menu .= "Entidad Gráfica]/sadministrator/settings_graf]ScreenRight}";
		$menu .= " Módulos Públicos ]/sadministrator/sys_setting_site/interface/abaout]ScreenRight}";
		// $menu .= " Banner ]/sadministrator/sys_setting_site/interface/abaout]ScreenRight}";
		// $menu .= " Promoción ]/sadministrator/publi_setting]ScreenRight}";
		
		$pestanas = DCTabs($menu, 'nav nav-tabs nav-tabs-custom m-b-15','Menu_One',$arg);
		return $pestanas;
	}

	
	public function Tabs_Objeto_Evaluativo($arg){
		
		$menu = " Inicio ]/sadministrator/edu-examen/interface/Crea_Objeto_Evaluativo]PanelA]Marca]HXM}";
		$menu .= " Configuración ]/sadministrator/edu-examen/interface/Configura_Objeto_Evaluativo]PanelA]]HXM}";
		$menu .= " Preguntas / Respuestas ]/sadministrator/edu-examen/interface/Configura_Examen]PanelA]]HXM}";
		$menu .= " Publicación ]/sadministrator/edu-examen/interface/Publicacion_Examen]PanelA]]HXM}";
		
		$pestanas = DCTabs($menu, 'nav nav-tabs nav-tabs-custom m-b-15','Menu_One',$arg);
		
		return $pestanas;
	}
	
	public function Tabs_Objeto_Evaluativo_Tarea($arg){
		
		$menu = " Inicio ]/sadministrator/edu-evaluacion-tarea/interface/Crea_Objeto_Evaluativo]PanelA]Marca]HXM}";
		$menu .= " Configuración ]/sadministrator/edu-evaluacion-tarea/interface/Configura_Objeto_Evaluativo]PanelA]]HXM}";
		// $menu .= " Preguntas / Respuestas ]/sadministrator/edu-evaluacion-tarea/interface/Configura_Examen]PanelA]]HXM}";
		// $menu .= " Promoción ]/sadministrator/publi_setting]ScreenRight}";
		
		$pestanas = DCTabs($menu, 'nav nav-tabs nav-tabs-custom m-b-15','Menu_One',$arg);
		
		return $pestanas;
	}
	
	public function Tabs_Menu_Comentario($arg){
		
		$menu = " Comentarios que realizó el participante  ]/sadministrator/edu-calificar/interface/Revisa_Recurso]PanelA]Marca]HXM}";
		$menu .= " Sub Comentarios que realizó el participante ]/sadministrator/edu-calificar/interface/Sub_Comentaio_Revision]PanelA]]HXM}";
		// $menu .= " Preguntas / Respuestas ]/sadministrator/edu-evaluacion-tarea/interface/Configura_Examen]PanelA]]HXM}";
		// $menu .= " Promoción ]/sadministrator/publi_setting]ScreenRight}";
		
		$pestanas = DCTabs($menu, 'nav nav-tabs nav-tabs-custom m-b-15','Menu_One',$arg);
		
		return $pestanas;
	}
	
		
	public function Tabs_Objeto_Evaluativo_Foro($arg){
		
		$menu = " Inicio ]/sadministrator/edu-examen-foro/interface/Crea_Objeto_Evaluativo]PanelA]Marca]HXM}";
		$menu .= " Configuración ]/sadministrator/edu-examen-foro/interface/Configura_Objeto_Evaluativo]PanelA]]HXM}";
		$menu .= " Grupos ]/sadministrator/edu-examen-foro/interface/Configura_Grupo]PanelA]]HXM}";
		$menu .= " Activar ]/sadministrator/edu-examen-foro/interface/Configura_Objeto_Evaluativo_Estado]PanelA]]HXM}";
		// $menu .= " Promoción ]/sadministrator/publi_setting]ScreenRight}";
		
		$pestanas = DCTabs($menu, 'nav nav-tabs nav-tabs-custom m-b-15','Menu_One',$arg);
		
		return $pestanas;
	}
	
	public function Main_Data_Producto($Id_Edu_Almacen,$tipo_producto) {
		global $Conection;
		
		
		if( $tipo_producto !=="programa" ) {
			
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
			$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
			
			
		}else{	
				$Query = "
				
				    SELECT 
					PC.Id_Programa_Cab 
					, PC.Nombre	
					, PC.Fecha_Creada
					, PC.Estado	
					FROM programa_cab PC
					WHERE
					PC.Id_Programa_Cab = :Id_Programa_Cab AND PC.Tipo_Proceso=:Tipo_Proceso
					ORDER BY PC.Date_Time_Creation ASC
			
				";    
				$Where = ["Id_Programa_Cab" => $Id_Edu_Almacen,"Tipo_Proceso"=>"Programa"];
				$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
				
		}	
			
		
		return $Row;
				
	}
	
	public function Main_Data_EU($Conection,$Email) {
		global $Conection;
		
		// var_dump($Parm);
				
		$Id_Entity = $_SESSION['Entity'];
		
		$Query = "
			SELECT 
			US.Usuario_Login
			, US.Password
			, ET.Id_Entity
			, US.Id_User
			, UM.Id_Perfil
			, UM.Id_User_Miembro
			, UM.Nombre
			, UM.Email
			, ET.Logo_Interno, ET.Color_Cabecera_Email
			, ET.Color_Cuerpo_Email, ET.Color_Fondo_Email, ET.Texto_Email_Inscripcion
			, ET.Email_Soporte_Cliente
			, ET.Telefono_Soporte_Cliente
			, ET.Url
			, ET.Color_Menu_Horizontal				
			FROM user_miembro UM
			INNER JOIN user US ON UM.Id_User_Creation = US.Id_User
			INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
			WHERE 
			US.Email = :Email 
			AND ET.Id_Entity = :Id_Entity
		"; 
		$Where = ["Email"=>$Email,"Id_Entity"=>$Id_Entity];
		// var_dump($Where);
		$Row = ClassPDO::DCRow($Query,$Where ,$Conection);	
		
		return $Row;
        
	}	
	
	
	
}





