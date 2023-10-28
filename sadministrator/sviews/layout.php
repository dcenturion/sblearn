<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/functions.php');
require_once(dirname(__FILE__).'/user.php');

class Layout{

	public function main($Contenido,$Datos_Page) {
		

		$Entity = $_SESSION['Entity'];
		$Data_Entity["Entity"] = $Entity;

		$MenuLateralAdministrator = MenuLateralAdministrator();	
		
		$DatosUser = User::MainData("");
		$DatosEntity = User::MainDataEntity_Id_Session($Data_Entity);
	
		$data["DatosUser"] = $DatosUser;	
		$data["Datos_Page"] = $Datos_Page;	
		$data["DatosEntity"] = $DatosEntity;
		
				// var_dump($DatosEntity);
		$PanelRight = '
						<div id="animatedModal5" class="modal" tabindex="-1" role="dialog">
						</div>					
							
							   <div class="site-content" id="ScreenRight" style="background:#fafafa;padding:0px;height: 100%;">  
							   '.$Contenido.'  
							   </div>
							   
					   
					   ';
					   
					   
					   
		$BodyPage = '
					
			<div class="site-main">		
			'.$MenuLateralAdministrator. $PanelRight .' 
			
	
			
			</div>
						
			
		';
		
		$this->view = $BodyPage;
		return $this->render(dirname(__FILE__).'/home.phtml',$data);
		
		
		
		
	}
    
	public function mainB($Contenido,$data) {
		
		
		$DatosUser = User::MainDataEntity($data);
		// DCVd($DatosUser);
		
		$data["DatosUser"] = $DatosUser;	
		$BodyPage = '

				<div class="row">
					<div class="col-sm-4 col-sm-offset-4">
							'.$Contenido.'		
					</div>			
				</div>	
				
		';
		$this->view = $BodyPage;
		// $data["Login"]="Login";
		// var_dump($DatosUser);
		return $this->render('./sviews/homeB.phtml',$data);

	}
	
	public function loginAdmin($conten) {
	
		$this->_pageTitle = "FlexZinn";
		$this->view = $conten;
		return $this->render('./sviews/homec.phtml');

	}

	public function dashboard($conten) {
	
		$this->_pageTitle = "FlexZinn";
		$this->view = $conten;
		return $this->render('./sviews/homed.phtml');

	}	
	
	
		
	public function render($filename, $viewDataArray = '') {
		ob_start();
		if (is_array($viewDataArray)) {
			extract($viewDataArray, EXTR_OVERWRITE);
		}
		include_once $filename;
		$contenido = ob_get_contents();
		ob_get_clean();
		return $contenido;
	}
	
	public function _pageTitle($name) {

		return $name;
	}
	// public function segmento($filename,$viewDataArray) {

		// return $this->render($filename, $viewDataArray = '');
	// }	
	
	public function view($arg) {

		return $arg;
	}	
	public function formContactos($arg) {

		return $arg;
	}	
	
}