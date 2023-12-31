<?php
require_once './_vistas/layout.php';

class cambiarClave{

    private $_parm;
    public  function __construct($_parm=null)
    {
		$pedido = $_parm["pedido"];
		$empresa = $_parm["entidad"];
		$clienteGet = $_parm["cliente"];

        $layout  = new Layout();
		
		$reg = atributosEntidadAPP($cnOwlPDO,$empresa);
		
		$sessionId = sessionId();
		$datosCliente = datosClienteAPP($cnOwlPDO,$sessionId);
		$cod_cliente = $datosCliente->Codigo;			
		$usuario_entidad = $_SESSION['usuario_entidad']['string'];
		
		if(!empty($usuario_entidad)){
			$datosUser = datosUserAPP($cnOwlPDO,$usuario_entidad);	
		    $cod_cliente = $datosUser->Clientes;			
		}
		
		$dataB = totalItemPedidosAPP($cnOwlPDO,$cod_cliente);
		$categorias = categoriasAPP($cnPDO,$empresa);
		
		$propiedadesEntidad = array();
        $propiedadesEntidad['principal'] = $reg;		
        $propiedadesEntidad['totalItem'] = $dataB;		
        $propiedadesEntidad['datosUser'] = $datosUser;	
        $propiedadesEntidad['empresa'] = $empresa;		
        $propiedadesEntidad['categorias'] = $categorias;		
        $propiedadesEntidad['sub_categorias'] = $categorias;	
		
		$datos = array();
        $datos['propiedadesEntidad'] = $propiedadesEntidad;
        $datos['idProducto']=$idProducto;
		
		echo $layout->main($this->viewHome($datosUser,$empresa,$clienteGet,$cnOwlPDO),$datos);
    }



    public function viewHome($datosUser,$empresa,$clienteGet) {
   
        $layout  = new Layout();
		$datos = array();	
        $datos['datosUser'] = $datosUser;			
        $datos['empresa'] = $empresa;			
        $datos['clienteGet'] = $clienteGet;			
        $datos['CodigoUsuario'] = $datosUser->Codigo;			
        return $layout->render('./_vistas/cambiar-clave.phtml',$datos);
		
    }



    public function formContacto($arg) {



        return $arg;

    }



}