
 // alert("ahahs");


  var count = 0;
  var listItems = 5;
  setInterval(function() {
	  
		var id_li =  $('#mensaje_chat_ul li:last').attr("fecha_value");	
		var id_almacen =  $('#mensaje_chat_ul li:last').attr("id_almacen");	
		var evaluador_indicador_chat =  $('#evaluador_indicador_chat').attr("valor");	
		
		var Parametros = {"fecha_value":id_li,"id_almacen":id_almacen};	  
		
		var protocol = window.location.protocol;
		var hostname = window.location.hostname;
		var pathname = window.location.pathname;

		UrlRaiz = ""+protocol+"//"+hostname+"/sadministrator/monitor-chat/id_almacen/"+id_almacen+"/interface/lectura"; 

		$.ajax({
			data: Parametros,
			url: UrlRaiz,
			type: 'post',
			async: true,
			success: function(response){
				
				
				var l_a_json = eval('(' + response + ')');
				var Html = l_a_json["Html"];
				var Nro_Msj = l_a_json["Nro_Msj"];

		        if(evaluador_indicador_chat == "si"){
					if(Nro_Msj > 0){
				        $("#alerta_chat").css("display", "block");							
					}					
				    $("#alerta_chat").html(Nro_Msj);						
				}else{
					

					var Cont_li =  $("#mensaje_chat_ul").children().length;
					
										
					for(var i in Html){
						Cont_li += 1;
					
						
						var html_screen = "<li id='chat_"+Html[i].Id_Sala_Conversacion+"' fecha_value='"+Html[i].Fecha_Hora_Creacion+"'  id_almacen='"+Html[i].Id_Sala+"' > <div>"+Html[i].Mensaje+"</div>  <div ><span><b>"+Html[i].Nombres+"</b> </span><span style='font-size:0.85em;'> | "+Html[i].Fecha_Hora_Creacion+"</span></div> </li>";
						
                        $("#mensaje_chat_ul").append( html_screen );						
					}
				
					
				    $("#alerta_chat").html('');	
					
					
				}
		   }
		});	
		
		   
        count += 1;
        if (count >= listItems){
            count = 0;
        }
    }, 60000);
	
	