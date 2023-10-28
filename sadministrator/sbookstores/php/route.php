<?php
    session_start();
    require_once 'sviews/error.php';
 					
		
    class Route{
 
    private $_uri = array();
    private $_method = array();
	  
	    public function add($uri,$method=null){
		     $this->_uri[] = '/'.trim($uri,'/');
			 
			 if($method != null ){
			     $this->_method[] = $method;
			 }
		
		}
		
	    public function submit(){
	
            $uriParm = isset($_GET['uri']) ?  '/'.$_GET['uri'] : '/';
			$uriParm_Ori = isset($_GET['uri']) ?  '/'.$_GET['uri'] : '/';

            // var_dump($uriParm);
            // var_dump($uriParm_Ori);
			
			// exit();
			$secciones_Valida_SessionOri = explode('/', $uriParm);
			
			if($secciones_Valida_SessionOri[1] == "edu_imagen" ){
				$uriParm = str_replace("_","-",$uriParm_Ori);
				$uriParm = strtolower($uriParm);
			}
			
			if($secciones_Valida_SessionOri[1] == "edu-imagen" ){
				$uriParm = strtolower($uriParm);
			}		
			$Host = $_SERVER["HTTP_HOST"];
			$Url= $_SERVER["REQUEST_URI"];
			$Host_Protocol = explode('www', $Host);
	  
            $secciones_Valida_Session = $secciones_Valida_SessionOri[1];
			

			if(empty($_SESSION['Entity'])){

					$_SESSION['Entity'] = 1; 
				 
			}
		
			if(empty($_SESSION['User'])){
				
				if($secciones_Valida_Session == "profile_user" 
				|| $secciones_Valida_Session == "edu-articulo-curso" 
				|| $secciones_Valida_Session == "edu_articulo_sugerencia" 
				|| $secciones_Valida_Session == "recuperar-password" 
				|| $secciones_Valida_Session == "api_user"
				|| $secciones_Valida_Session == "certificado-public"
				){
					
					
					// echo "<script>
						
						// var strb = localStorage.getItem('Session_Entidad');
					
						// if( strb == undefined  ){
						
							// window.location.href = '/sadministrator/login/request/on/';	
							
						// }else{
							
							// window.location.href = '/'+strb;						
										
						// }
					// </script>";
					
					// exit();
					
				}else{
								
					$html = "<script>
						
						var strb = localStorage.getItem('Session_Entidad');
					   
						if( strb == undefined || strb == ''){ 
						
							if( '".$secciones_Valida_SessionOri[5]."' == 'yes'  ){ 
							
							}else{
								
							    window.location.href = '/sadministrator/login/request/on/ini/yes';	
							   
							} 
							
						}else{
							
							window.location.href = '/'+strb;	
						}
						
					</script>";
					echo $html;
					
				}
				
				
			}

			$contSeccion = 0;
	       
	        // echo  $uriParm;
		    foreach($this->_uri as $key => $value){
		
				$secciones = explode('/', $uriParm);
				// var_dump(count($secciones));
				
			
				if(count($secciones) == 2 || count($secciones) == 3 ){
				     
						if($secciones[1]==""){
						     $uriParm = "/home";
						}
						
						if(preg_match("#^$value$#",$uriParm)){
						$contSeccion += 1;
						}	
					 
				}else{
					// var_dump($value);
					// var_dump($uriParm);
					 if(preg_match("#^$value#",$uriParm)){
                         $contSeccion += 1;
					 }					
				}
            }
			
			 
			if($contSeccion == 0){
				new Error_Srt();
				return;			    
			}else{
			
			}
		
			$value_Url = "";
		    foreach($this->_uri as $key => $value){

			    $secciones = explode('/', $uriParm);
				$Num_Secciones = count($secciones);

				$value_Url = "/".$secciones[1]."/";


				if(strstr($value_Url,$value."/")){
				
		         
					 if(is_string($this->_method[$key])){
						 	
						$userMethod = $this->_method[$key];
						// echo "<br>".$userMethod;
						
						$parametrosGet = str_replace($value."/","", $uriParm);
			            $cadena_parametrosGet = explode('/', $parametrosGet);
						$contParametros = 0;
						$stringParametros = "";
						$stringParametroSegmento = "";

					
						for ($j = 0; $j < count($cadena_parametrosGet) +1 ; $j++) {
							$contParametros += 1;		
							$residuo = $contParametros % 2;
							if( $residuo == 0 ){
							     $delimitador = "<{defsei-cmd2}>"; 
						         $stringParametros .= $cadena_parametrosGet[$j].$delimitador  ;
							}else{
							     $stringParametros .= $cadena_parametrosGet[$j]."<{defsei-cmd1}>" ;
							}
						}
						
						$getParm = array();
			            $cadena_parametrosGetB = explode('<{defsei-cmd2}>', $stringParametros);		
						for ($k = 0; $k < count($cadena_parametrosGetB) - 1 ; $k ++) {
			                $cadena_parametrosGetB2 = explode('<{defsei-cmd1}>', $cadena_parametrosGetB[$k]);
							
							$values = DCProtect($cadena_parametrosGetB2[1]);
							$valuesB = $cadena_parametrosGetB2[1];
                            $getParm[$cadena_parametrosGetB2[0]] = $valuesB;
							 
						}
	
	                    if(count($secciones) > 2){
		                	new $userMethod($getParm);	
						}else{
						    new $userMethod($getParm);
						}						
						
						
					 }else{
					    if(preg_match("#^$value$#",$uriParm)){
						    call_user_func($this->_method[$key]);
						}
					 }
				}	
			
			
			}
		
		} 
 
    }