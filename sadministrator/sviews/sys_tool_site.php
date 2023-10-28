<?php
require_once('./sviews/layout.php');
$DCTimeHour = DCTimeHour();
$Conection = Conection();
$UrlFile = "/sadministrator/admin_tool_site";

class AdminToolsSite{

	private $Parm;
    public  function __construct($Parm=null)
	{
	    global $Conection,$DCTimeHour,$UrlFile;
		
		$Transaction = $Parm["Transaction"];
		$Interface = $Parm["Interface"];
		$Form = $Parm["Form"];

        switch ($Transaction) {
            case "INSERT":

				switch ($Form) {
					case "Form_Call":
						// $this->Form_Call($Transaction);
						break;	
					case "Form_Suscripcion":
						// $this->Form_Suscripcion($Transaction);
						break;							
				}			
				
				DCExit();
                break;
            case "UPDATE":

                break;
            case "DELETE":

                break;
            case "VALIDATIONS":

                break;
            case "SEARCH":

                break;				
        }
		
		
		
        switch ($Interface) {
            case "":
			
				$layout  = new Layout();
				$listMn = "<i class='icon-chevron-right'></i> Editar Estados [".$enlaceEstado."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";
				$listMn .= "<i class='icon-chevron-right'></i> Editar Áreas [".$enlaceArea."?Main=Principal&CodRegistro={$CodRegistro}&CodComponente=".$reg["Codigo"]."[panelOculto[[{";

				$btn = "<i class='zmdi zmdi-chevron-down zmdi-hc-fw'></i> Opciones ]SubMenu]{$listMn}]OPCIONES]btn btn-default dropdown-toggle}";
				$btn .= "<i class='zmdi zmdi-edit'></i> Crear Usuario ]" .$UrlFile."/Interface/CreateUser]animatedModal5]HXM]btn btn-primary ladda-button}";
				$btn = DCButton($btn, 'botones1', 'sys_form');

				$DCPanelTitle = DCPanelTitle("INTERFACES","Adminitrator of interfaces and objects",$btn);
				
				$Contenido = DCPage($DCPanelTitle,"Hola");
				
				if($Parm["Request"] == "On"){
					DCWrite($layout->main($Contenido,$datos));
				}else{
					DCWrite($Contenido);			
				}
				
                break;
				
            case "CreateUser":
			
			    $Form = $this->FormUser();
				
			    $Html = DCModalForm("Crear Usuario",$Form);
                DCWrite($Html);
                DCExit();
                break;
			
        }
				
		
		
	}
	

	public function FormUser() {		
		
		$Html = '		  
		    <div class="modal-body">
				<form>
				
				  <div class="form-group">
					<label for="form-control-1" class="control-label">Username</label>
					<div class="input-group">
					  <span class="input-group-addon">@</span>
					  <input type="text" class="form-control" id="form-control-1" placeholder="Username">
					</div>
				  </div>
				  
				  <div class="form-group">
					<label for="form-control-2" class="control-label">Email</label>
					<input type="email" class="form-control" id="form-control-2" placeholder="Email">
				  </div>
				  <div class="form-group">
					<label for="form-control-3" class="control-label">Choose counrty</label>
					<select id="form-control-3" class="custom-select">
					  <option value="" selected="selected">Choose counrty</option>
					  <option value="1">Denmark</option>
					  <option value="2">Iceland</option>
					  <option value="3">Republic of Macedonia</option>
					  <option value="4">Saint Kitts and Nevis</option>
					  <option value="5">Vanuatu</option>
					  <option value="6">Yemen</option>
					  <option value="7">Zimbabwe</option>
					</select>
				  </div>
				  <div class="form-group">
					<label for="form-control-4" class="control-label">About You</label>
					<textarea id="form-control-4" class="form-control" rows="3"></textarea>
					<div class="help-block with-errors">Write some details about yourself.</div>
				  </div>
				  <div class="form-group">
					<label for="form-control-5" class="control-label">Password</label>
					<div class="row">
					  <div class="col-sm-6">
						<input type="password" class="form-control" id="form-control-5" placeholder="Password">
						<div class="help-block with-errors m-b-0">Minimum of 6 characters</div>
					  </div>
					  <div class="col-sm-6">
						<input type="password" class="form-control" id="form-control-6" placeholder="Confirm">
						<div class="help-block with-errors m-b-0"></div>
					  </div>
					</div>
				  </div>
				</form>
			  </div>
			  
			  <div class="modal-footer text-center">
				<button type="button" data-dismiss="modal" class="btn btn-primary">Continue</button>
			  </div>   
	    ';
		
		return $Html;
	}

}