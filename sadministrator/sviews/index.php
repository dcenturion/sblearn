<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/functions.php');
	require_once 'sbookstores/php/route.php';
	
	require_once 'sviews/home.php';
	require_once 'sviews/user_administrator.php';
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
	require_once 'sviews/edu_video.php';
	require_once 'sviews/edu_formato.php';
	
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
	$route->add('/user_administrator','UserAdministrator');
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
	$route->add('/edu_video','Edu_Video');
	$route->add('/edu_formato','Edu_Formato');

	// $route->add('/serviceall','ServiceAll');
	// $route->add('/contact','Contact');

	// $route->add('/evaluacion','evaluacion');
	// $route->add('/comentario','comentario');
	// $route->add('/finalf','finalf');
	// $route->add('/validacion','validacion');
	// $route->add('/tabladinamica','tabladinamica');
	$route->submit();


?>