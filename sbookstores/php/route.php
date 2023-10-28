<?php
    session_start();

    class Route{
 
    private $_uri = array();
    private $_uri_b = array();
    private $_method = array();
    private $_method_b = array();
	  
	    public function add($uri,$method=null){
		     $this->_uri[] = '/'.trim($uri,'/');
			 
			 if($method != null ){
			     $this->_method[] = $method;
			 }
		
		}
		
	    public function add_subdirectorio($uri_b,$method_b=null){
		     $this->_uri_b[] = '/'.trim($uri_b,'/');
			 
			 if($method_b != null ){
			     $this->_method_b[] = $method_b;
			 }
		
		}		
		
		
	    public function submit(){
			  
            $uriParm = isset($_GET['uri']) ?  '/'.$_GET['uri'] : '/';
            $secciones_Valida_Session = explode('/', $uriParm);

			$Query = "
				SELECT 
				AR.Url_Original
				FROM edu_articulo AR 
				WHERE 
				AR.Url_Amigable =:Url_Amigable
			";	
			$Where = ["Url_Amigable" =>$secciones_Valida_Session[1]];
			$Row = ClassPdo::DCRow($Query,$Where,$Conection);	
			$Url_Original = $Row->Url_Original;
			
			$Url_Original = strtolower($Url_Original);
			$Url_Original = str_replace("_","-",$Url_Original);
			
			// echo "".$Url_Original;
					
			if(!empty($Url_Original)){
		        $uriParm = $Url_Original;				
			}			
			
            // $uriParm = "/sadministrator/edu_blog/Interface/begin/Request/On/Key/144/Action/Sugerencia";
		    // echo "prueba rr rrr";
		    // exit();
			
			$contSeccion = 0;
		    foreach($this->_uri as $key => $value){
			
				$secciones = explode('/', $uriParm);

				if(count($secciones) == 2 || count($secciones) == 3 ){
				     
						if($secciones[1]==""){
						    $uriParm = "/home";
						}
						
						if(preg_match("#^$value$#",$uriParm)){
						    $contSeccion += 1;
						}
						
				}elseif( count($secciones) > 3 ){	
								// var_dump(count($secciones));
				          $contSeccion += 1;
				}else{
					 if(preg_match("#^$value#",$uriParm)){
                            $contSeccion += 1;
					 }					
				}
            }
			
			
			if($contSeccion == 0){
				header('HTTP/1.0 404 Not Found');
				include('error_404.php');
				exit;		    
			}else{
			
			}
	
		    foreach($this->_uri as $key => $value){

			    $secciones = explode('/', $uriParm);
				
				$value_Url = "/".$secciones[1]."/";

				if(strstr($value_Url,$value."/")){
					
					 if(is_string($this->_method[$key])){
						 	
						$userMethod = $this->_method[$key];
						// DCVd($uriParm);
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
						  // DCVd($stringParametros);
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
						    // echo "<br>::".$userMethod." :: <br>";
						}else{
						    // echo "<br>::".$userMethod." :: <br>";
						    new $userMethod($getParm);
						}						
						
						
					 }else{
					    if(preg_match("#^$value$#",$uriParm)){
						    call_user_func($this->_method[$key]);
						}
					 }
				}
			}
		
		    foreach($this->_uri_b as $key => $value){

			    $secciones = explode('/', $uriParm);
				
				$value_Url = "/".$secciones[2]."/";

				if(strstr($value_Url,$value."/")){
					// DCVd($secciones[2]);
					 if(is_string($this->_method_b[$key])){
						 	
						$userMethod = $this->_method_b[$key];
						
						$parametrosGet = str_replace("/sadministrator".$value."/","", $uriParm);
	  
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
						  // DCVd($stringParametros);
						$getParm = array();
			            $cadena_parametrosGetB = explode('<{defsei-cmd2}>', $stringParametros);		
						for ($k = 0; $k < count($cadena_parametrosGetB) - 1 ; $k ++) {
			                $cadena_parametrosGetB2 = explode('<{defsei-cmd1}>', $cadena_parametrosGetB[$k]);		
							$values = DCProtect($cadena_parametrosGetB2[1]);
							$valuesB = $cadena_parametrosGetB2[1];
                            $getParm[$cadena_parametrosGetB2[0]] = $valuesB;
						}

						// DCVd($getParm);
	                    if(count($secciones) > 2){
		                	new $userMethod($getParm);	
						    // echo "<br>::".$userMethod." :: <br>";
						}else{
						    // echo "<br>::".$userMethod." :: <br>";
						    new $userMethod($getParm);
						}						
						
						
					 }else{
					    if(preg_match("#^$value$#",$uriParm)){
						    call_user_func($this->_method_b[$key]);
						}
					 }
				}
			}
				
				   
		} 
		
		
		
		
		
		
 
    }