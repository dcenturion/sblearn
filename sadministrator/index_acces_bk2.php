<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/functions.php');
	
	require_once 'sbookstores/php/route.php';
	
	require_once 'sviews/home.php';
	require_once 'sviews/user_administrator.php';
	require_once 'sviews/user_alumnos.php';
	require_once 'sviews/cursos_agrupados.php';
	require_once 'sviews/curso-programa.php';
	require_once 'sviews/componente_page.php';
	require_once 'sviews/sys_setting_site.php';
	require_once 'sviews/admin_tools_site.php';
	require_once 'sviews/admin_warehouse.php';
	require_once 'sviews/det_admin_tools_site.php';
	require_once 'sviews/admin_object.php';
	require_once 'sviews/det_admin_object.php';
	require_once 'sviews/skeditor.php';
	require_once 'sviews/admin_home.php';
	require_once 'sviews/cliente.php';
	require_once 'sviews/pedido.php';
	require_once 'sviews/catalogo.php';
	require_once 'sviews/articulo.php';
	require_once 'sviews/login.php';
	require_once 'sviews/logout.php';
	require_once 'sviews/blog.php';
	require_once 'sviews/categoria_blog.php';
	require_once 'sviews/user_sys.php';
	require_once 'sviews/user_perfil.php';
	require_once 'sviews/settings_graf.php';
	require_once 'sviews/profile_user.php';
	require_once 'sviews/edu_tipo_componente.php';
	require_once 'sviews/edu_tipo_estructura.php';
	require_once 'sviews/edu-articulo-det.php';
	require_once 'sviews/edu_area_conocimiento.php';
	require_once 'sviews/edu_articulo_sugerencia.php';
	require_once 'sviews/edu-articulo-curso.php';
	require_once 'sviews/edu-articulo-programa.php';
	require_once 'sviews/edu-programa.php';
	require_once 'sviews/edu-store.php';
	require_once 'sviews/edu-tendencia.php';
	require_once 'sviews/edu-register.php';
	require_once 'sviews/upload_arc.php';
	require_once 'sviews/publi_setting.php';
	require_once 'sviews/publi_setting_det.php';
	require_once 'sviews/edu_publicidad_tipo_alcance.php';
	require_once 'sviews/edu_sub_linea.php';
	require_once 'sviews/edu-blog.php';
	require_once 'sviews/edu_productor.php';
	require_once 'sviews/edu-imagen.php';
	require_once 'sviews/edu-video.php';
	require_once 'sviews/edu_formato.php';
	require_once 'sviews/edu_tipo_privacidad.php';
	require_once 'sviews/edu-participante.php';
	require_once 'sviews/edu-participante-masivo.php';
	require_once 'sviews/admin-empresa.php';
	require_once 'sviews/edu-examen.php';
	require_once 'sviews/edu-desarrollo-examen.php';
	require_once 'sviews/edu-reportes.php';
	require_once 'sviews/chat.php';
	require_once 'sviews/edu_banner.php';
	require_once 'sviews/edu-calificar.php';
	require_once 'sviews/edu-examen-foro.php';
	require_once 'sviews/edu-perfil-educacion.php';	
	require_once 'sviews/edu-acta-notas.php';	
	require_once 'sviews/edu-gestion-certificado.php';	
	require_once 'sviews/edu-certificado.php';	
	require_once 'sviews/edu-estado-academico.php';	
	require_once 'sviews/edu-estado-edicion-certificado.php';	
	require_once 'sviews/edu-estado-emision.php';	
	require_once 'sviews/edu-tipo-documento-identidad.php';	
	require_once 'sviews/edu-pais.php';	
	
	require_once 'sviews/edu-visor-certificado.php';
	require_once 'sviews/edu-tipo-certificado.php';	
	require_once 'sviews/edu-tipo-ubicacion.php';		
	require_once 'sviews/api_user.php';		
	require_once 'sviews/edu-articulo-curso-test.php';		
	require_once 'sviews/edu-notificacion-actividades.php';	
	
	require_once 'sviews/edu-configuracion-producto.php';		
	require_once 'sviews/inhouse-empresa.php';		
	require_once 'sviews/certificado-public.php';		
	require_once 'sviews/edu-tipo-titulo.php';		
	// require_once 'sviews/procesos_sistema.php';		
	// require_once 'sviews/serviceall.php';
	// require_once 'sviews/contact.php';
	// require_once 'sviews/evaluacion.php';
	// require_once 'sviews/comentario.php';
	// require_once 'sviews/finalf.php';
	// require_once 'sviews/validacion.php';
	// require_once 'sviews/tabladinamica.php';
	


	error_reporting(E_ERROR);
	$route  = new Route();
	$route->add('/home','Home');
	$route->add('/user_alumnos','UserAlumnos');
	$route->add('/user_administrator','UserAdministrator');
	$route->add('/cursos_agrupados','CursosAgrupados');
	$route->add('/curso-programa','CursoPrograma');
	$route->add('/componente_page','ComponentePage');
	$route->add('/sys_setting_site','SysSettingSite');
	$route->add('/admin_tools_site','AdminToolsSite');
	$route->add('/admin_warehouse','AdminWarehouse');
	$route->add('/det_admin_tools_site','DetAdminToolsSite');
	$route->add('/admin_object','AdminObject');
	$route->add('/det_admin_object','DetAdminObject');
	$route->add('/skeditor','Skeditor');
	$route->add('/admin_home','AdminHome');
	$route->add('/cliente','Cliente');
	$route->add('/pedido','PedidoCab');
	$route->add('/catalogo','Catalogo');
	$route->add('/articulo','Articulo');
	$route->add('/login','Login');
	$route->add('/logout','Logout');
	$route->add('/blog','Blog');
	$route->add('/categoria_blog','Categoria_Blog');
	$route->add('/user_sys','User_Sys');
	$route->add('/user_perfil','User_Perfil');
	$route->add('/settings_graf','Settings_Graf');
	$route->add('/profile_user','Profile_User');
	$route->add('/edu_tipo_componente','Edu_Tipo_Componente');
	$route->add('/edu_tipo_estructura','Edu_Tipo_Estructura');
	$route->add('/edu_area_conocimiento','Edu_Area_Conocimiento');
	$route->add('/edu-articulo-det','Edu_Articulo_Det');
	$route->add('/edu_articulo_sugerencia','Edu_Articulo_Sugerencia');
	$route->add('/edu-articulo-curso','Edu_Articulo_Curso');
	$route->add('/edu-articulo-programa','Edu_Articulo_Programa');
	$route->add('/edu-programa','Edu_Programa');
	$route->add('/edu-store','Edu_Store');
	$route->add('/edu-tendencia','Edu_Tendencia');
	$route->add('/edu-register','Edu_Register');
	$route->add('/upload_arc','Upload_Arc');
	$route->add('/publi_setting','Publi_Setting');
	$route->add('/publi_setting_det','Publi_Setting_Det');
	$route->add('/edu_publicidad_tipo_alcance','Edu_Publicidad_Tipo_Alcance');
	$route->add('/edu_sub_linea','Edu_Sub_Linea');
	$route->add('/edu-blog','Edu_Blog');
	$route->add('/edu_productor','Edu_Productor');
	$route->add('/edu-imagen','Edu_Imagen');
	$route->add('/edu-video','Edu_Video');
	$route->add('/edu_formato','Edu_Formato');
	$route->add('/edu-tipo-privacidad','Edu_Tipo_Privacidad');
	$route->add('/edu-participante','Edu_Participante');
	$route->add('/edu-participante-masivo','Edu_Participante_Masivo');
	$route->add('/admin-empresa','AdminEmpresa');
	$route->add('/edu-examen','Edu_Examen');
	$route->add('/edu-desarrollo-examen','Edu_Desarrollo_Examen');
	$route->add('/edu-reportes','Edu_Reportes');
	$route->add('/chat','Chat');
	$route->add('/edu-banner','Edu_Banner');
	$route->add('/edu-calificar','Edu_Calificar');
	$route->add('/edu-examen-foro','Edu_Examen_Foro');
	$route->add('/edu-perfil-educacion','Edu_Perfil_Educacion');
	$route->add('/edu-acta-notas','Edu_Acta_Notas');	
	$route->add('/edu-gestion-certificado','Edu_Gestion_Certificado');	
	$route->add('/edu-certificado','Edu_Certificado');	
	$route->add('/edu-estado-academico','Edu_Estado_Academico');	
	$route->add('/edu-estado-edicion-certificado','Edu_Estado_Edicion_certificado');	
	$route->add('/edu-estado-emision','Edu_Estado_Emision');	
	$route->add('/edu-tipo-documento-identidad','Edu_Tipo_Documento_Identidad');	
	$route->add('/edu-pais','Edu_Pais');	
	$route->add('/edu-visor-certificado','Edu_Visor_Certificado');
	$route->add('/edu-tipo-certificado','Edu_Tipo_Certificado');
	
	$route->add('/edu-tipo-ubicacion','Edu_Tipo_Ubicacion');	
	$route->add('/api_user','Api_User');	
	$route->add('/edu-articulo-curso-test','Edu_Articulo_Curso_Test');	
	$route->add('/edu-notificacion-actividades','Edu_Notificacion_Actividades');
	
	$route->add('/edu-configuracion-producto','Edu_Configuracion_Producto');	
	$route->add('/inhouse-empresa','Inhouse_Empresa');	
	$route->add('/certificado-public','Certificado_Public');	
	$route->add('/edu-tipo-titulo','Edu_Tipo_Titulo');	
	// $route->add('/procesos_sistema','Procesos_Sistema');
	// $route->add('/serviceall','ServiceAll');
	// $route->add('/contact','Contact');

	// $route->add('/evaluacion','evaluacion');
	// $route->add('/comentario','comentario');
	// $route->add('/finalf','finalf');
	// $route->add('/validacion','validacion');
	// $route->add('/tabladinamica','tabladinamica');
	$route->submit();


?>