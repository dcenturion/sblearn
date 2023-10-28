<?php
error_reporting(0);
require_once($_SERVER['DOCUMENT_ROOT'].'sbookstores/php/conection.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'sbookstores/php/class/lib_pdo.php');
require_once($_SERVER['DOCUMENT_ROOT'].'sbookstores/php/functions.php');
date_default_timezone_set('America/Lima');

$Conection=Conection();
session_start();
$User = $_SESSION['User'];
$Entity = $_SESSION['Entity'];
if(!empty($_GET['data'])){
    $TokenSesion=$_GET['data'];  
    
}else{
     $TokenSesion="";
}

$Query = "SELECT US.Token,ET.Url from user_miembro UM 
		INNER JOIN user US ON UM.Id_User_Creation=US.Id_User
		INNER JOIN entity ET ON ET.Id_Entity = UM.Entity
		WHERE UM.Id_User_Miembro=:Id_User_Miembro AND UM.Entity=:Entity AND UM.Id_Perfil=:Id_Perfil";
$Where = ["Id_User_Miembro"=>$User,"Entity"=>$Entity,'Id_Perfil'=>3];
$Row = ClassPdo::DCRow($Query,$Where,$Conection);


//echo 'Holaa mundo';
$Mensaje="";
if (!empty($Row->Token)) {
	$TokenBD=$Row->Token;
	$Url=$Row->Url;	

	if ($TokenBD<>$TokenSesion) {
		
		$Mensaje='<div class="modal " style="display: block;background: #7172737d;"> 
					  <div class="modal-dialog modal-dialog-centered modal-sm" >
					    <div class="modal-content text-center">
					      <div class="modal-body">
					      	 <h3 class="modal-title">¡ALERTA!</h3>
					      	<img src="http://esgep.local/simages/user_unique.png" style="width: 150px;" alt="">
					        <h4>Ha ingresado desde otro dispositivo. <br>La sesion se cerrará en 3 segundos.<br
					        Acceda Nuevamente</h4>
					        
					      </div>
					    </div>
					  </div>
					</div>'.session_destroy().'
			<script>
			 sessionStorage.clear();
            </script>
            <meta http-equiv="refresh" content="3; url=http://yachai.org/'.$Url.'">';
	}
	echo $Mensaje;
}




?>