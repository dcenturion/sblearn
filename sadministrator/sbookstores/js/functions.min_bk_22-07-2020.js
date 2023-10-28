
function empty(str)
{
    if (typeof str == 'undefined' || !str || str.length === 0 || str === "" || !/[^\s]/.test(str) || /^\s*$/.test(str) || str.replace(/\s/g,"") === "")
    {
        return true;
    }
    else
    {
        return false;
    }
}


function Redirect(Settings){

    var JsnData = JSON.parse(Settings);
    Url = JsnData.Url;
    Screen = JsnData.Screen;
    Type_Send = JsnData.Type_Send;
	
	var Parametros = {
	};	  
	
	var protocol = window.location.protocol;
	var hostname = window.location.hostname;
	var pathname = window.location.pathname;
	UrlRaiz = ""+protocol+"//"+hostname+Url+"";
	
	// console.log("load page new "+UrlRaiz);		
	console.log(" Type_Send:: "+Type_Send);		
	// console.log(UrlRaiz);	
	
	if(Type_Send !== "HXM"){
		history.pushState(UrlRaiz+"/request/on/", "", UrlRaiz+"/request/on/");	
	}

	ActiveCurtain_B();
	$.ajax({
		data: Parametros,
		url: UrlRaiz,
		type: 'get',
		async: true,
		success: function(response){
			// console.log("response:: "+response);
			$("#"+Screen+"").html(response);	
            DisableCurtain_B();			
	   }
	});	
}

function LoadPage(elem){

	var id = $(elem).attr("id");
	// alert(id);
	var Url = $("#"+id+"").attr("direction");
	var Panel = $("#"+id+"").attr("screen");
	var Type_Send = $("#"+id+"").attr("type_send");

	var Parametros = {
	};	  
	
	$("li[id*='li_']").removeClass();
    $("#li_"+id+"").addClass("with-sub active");	

	var protocol = window.location.protocol;
	var hostname = window.location.hostname;
	var pathname = window.location.pathname;
	UrlRaiz = ""+protocol+"//"+hostname+Url+"";
	console.log("load page");		
	console.log(UrlRaiz);		
	if(Type_Send !== "HXM"){
		history.pushState(UrlRaiz+"/request/on/", "", UrlRaiz+"/request/on/");	
	}

	ActiveCurtain_B();
	$.ajax({
		data: Parametros,
		url: UrlRaiz,
		type: 'get',
		async: true,
		success: function(response){
			// console.log("response:: "+response);
			$("#"+Panel+"").html(response);	
            DisableCurtain_B();			
	   }
	});	
	
	
}



function SaveFormSP(elem){
	var id = $(elem).attr("id");

	var Url = $("#"+id+"").attr("direction");	
	var Panel = $("#"+id+"").attr("screen");	
	var formDom = $("#"+id+"").attr("form");	
	var protocol = window.location.protocol;
	var hostname = window.location.hostname;
	UrlRaiz = ""+protocol+"//"+hostname+Url+"";
	
	console.log(UrlRaiz);
	// ActiveCurtain_B();

    var form = document.getElementById(formDom);	
    var form_elem = form.elements;
    for (var i = 0; i < form_elem.length; i++) {
        var elem = form_elem[i], responseValue, success = true;
  
            switch (elem.type) {			
                case "textarea":
					var type_ta = $("#"+elem.id+"").attr("type_ta");
			        if(type_ta == "edit"){
						var contenido = CKEDITOR.instances[elem.id].getData();	
						document.getElementById(elem.id).value = contenido;	
					
					}
                break;				
            }
    }

	$.ajax({
	type:"POST",
	url:UrlRaiz,
	data: new FormData(form), 
	cache: false,
	processData: false,
    contentType: false,
	success: function(data){
				// console.log("data:: "+data);
		$('#'+Panel+'').html(data);
		// DisableCurtain_B();	
	}
	});
	
    $(".Conversacion-Chat").animate({ scrollTop: $("#mensaje_chat_ul").innerHeight() }, 1000);
			
}

function SaveForm(elem){
	var id = $(elem).attr("id");

	var Url = $("#"+id+"").attr("direction");	
	var Panel = $("#"+id+"").attr("screen");	
	var formDom = $("#"+id+"").attr("form");	
	var protocol = window.location.protocol;
	var hostname = window.location.hostname;
	UrlRaiz = ""+protocol+"//"+hostname+Url+"";
	
	console.log(UrlRaiz);
	ActiveCurtain_B();

    var form = document.getElementById(formDom);	
    var form_elem = form.elements;
    for (var i = 0; i < form_elem.length; i++) {
        var elem = form_elem[i], responseValue, success = true;
  
            switch (elem.type) {			
                case "textarea":
					var type_ta = $("#"+elem.id+"").attr("type_ta");
			        if(type_ta == "edit"){
						var contenido = CKEDITOR.instances[elem.id].getData();	
						document.getElementById(elem.id).value = contenido;	
					
					}
                break;				
            }
    }

	$.ajax({
	type:"POST",
	url:UrlRaiz,
	data: new FormData(form), 
	cache: false,
	processData: false,
    contentType: false,
	success: function(data){
				// console.log("data:: "+data);
		$('#'+Panel+'').html(data);
		DisableCurtain_B();	
	}
	});

			
}


function SaveFormJSA(elem){
	var id = $(elem).attr("id");

	var Url = $("#"+id+"").attr("direction");	
	var Panel = $("#"+id+"").attr("screen");	
	var formDom = $("#"+id+"").attr("form");
	var id_var_input = $("#"+id+"").attr("id_var_input");
  
	var var_Url = document.getElementById(""+id_var_input+"").innerHTML;
	// alert(var_Url);
	var protocol = window.location.protocol;
	var hostname = window.location.hostname;
	UrlRaiz = ""+protocol+"//"+hostname+Url+"/var_Url/"+var_Url;
	
	console.log(UrlRaiz);
	ActiveCurtain_B();

    var form = document.getElementById(formDom);	
    var form_elem = form.elements;
    for (var i = 0; i < form_elem.length; i++) {
        var elem = form_elem[i], responseValue, success = true;
  
            switch (elem.type) {			
                case "textarea":
					var type_ta = $("#"+elem.id+"").attr("type_ta");
			        if(type_ta == "edit"){
						var contenido = CKEDITOR.instances[elem.id].getData();	
						document.getElementById(elem.id).value = contenido;	
					
					}
                break;				
            }
    }

	$.ajax({
	type:"POST",
	url:UrlRaiz,
	data: new FormData(form), 
	cache: false,
	processData: false,
    contentType: false,
	success: function(data){
				// console.log("data:: "+data);
		$('#'+Panel+'').html(data);
		DisableCurtain_B();	
	}
	});

			
}



function ActiveCurtain_B(){

	var ventana_anchoA = $(window).width();
	var ventana_altoA = $(window).height()+70;
	
	var hostname = window.location.hostname;
	var protocol = window.location.protocol ;

	$("body").append("<div class='Cortina'><div class='Cortina_Tenue' style='width:"+ventana_anchoA+"px;height:"+ventana_altoA+"px;top:-"+ventana_altoA+"px;'><img src='"+protocol+"//"+hostname+"/sadministrator/simages/loadingC.gif' width=150px style='position:fixed;top:238px;'></div></div>");

}

function DisableCurtain_B(){
	
	$(".Cortina").remove();
}	  	


function checkAllSelect(formId,IdCheckSelectIni) {

    var Formulario = document.getElementById(formId);
    var IdCheckSelect = document.getElementById(IdCheckSelectIni).checked;
	var cont = 0;

	if(IdCheckSelect == false){
		
		for (var i in Formulario.elements) {
			
			if (Formulario.elements[i].type === 'checkbox') {
				
				var IdCheck = Formulario.elements[i].id;
				if(IdCheck !== "element-Checkwarehouse"){
						Formulario.elements[i].checked = true;
				}
			}
		}	
		
	}else{
		
		for (var i in Formulario.elements) {
			
			if (Formulario.elements[i].type === 'checkbox') {

				var IdCheck = Formulario.elements[i].id;
				if(IdCheck !== "element-Checkwarehouse"){
						Formulario.elements[i].checked = false;
				}
			}
		}	
		
	}
}

function ResizeScreen(id){

	var ventana_anchoA = $("#ScreenRight").width();
	var ventana_altoA = $(window).height();
	// ventana_altoA = ventana_altoA
	// ventana_alto = ventana_altoA - 350;
	// ventana_ancho = ventana_anchoA;
		// alert(ventana_altoA);	
    var altoEnPorcentaje = ventana_altoA * 25 / 100;
	ventana_altoA = ventana_altoA - altoEnPorcentaje;
	$("#"+id+" iframe").attr({"width":"99%","height":""+ventana_altoA+"px"});
 

}


function selectionItem(sCon){
    var valorInput = document.getElementById(sCon).innerHTML;

	var pathnameb  = window.location;

    var parmUrl = pathnameb.toString().split('/Qr');
		// alert(parmUrl[0]);
	history.pushState(null, 'path', parmUrl[0]+"/Qr/"+valorInput);
	location.replace(window.location.href);	
	// history.pushState(null, 'path', parmUrl[0]);	
}

function buscadorAccion(sDiv){
    var valorInput = document.getElementById("searchInput").value;
					
	var pathnameb  = window.location;
		// var pathname = window.location.pathname;
    var parmUrl = pathnameb.toString().split('&');
	history.pushState(null, 'path', parmUrl[0]+"/Qr/"+valorInput);
	location.replace(window.location.href);	
}

function Search(){
   
	var panel ="screenSearch";
	
    if (document.getElementById(panel)) {
       	
		$("#screenSearch").attr("style","display:block;");
		$("#screen_result_search").attr("style","display:block;");
		
		var pathname = window.location.pathname;
	        
		url = pathname+"/Process/SEARCH";
	
        var numKey = 0;
		var valorInput = document.getElementById("searchInput").value;
		var valorInputB = valorInput.length;
		var Parametros = {
		};	  
		
		if(valorInputB > 3){
			
			url = url+"/Qr/"+valorInput;
			    // console.log('buscadorGeneral :: '+url);
				$.ajax({
					data: Parametros,
					url: url,
					type: 'get',
					async: true,
					success: function(response){
						// console.log("response:: "+response);
						$("#"+panel+"").html(response);			
				   }
				});	
	
		
		}
		
    } else {
        console.log('No existe Elemento con Id: ' + panel);
    }
}


function hideSSearch(sDiv){
	$("#screenSearch").attr("style","display:none;");
	$("#screen_result_search").attr("style","display:none;");
}



function Reloj_Evaluacion() {

	
}


let count = 0;
let counter;
let Eval_Cab;
let min_duracion;
let Tiempo_Estado_V;
let Id_Edu_Componente_V;
let Id_Edu_Almacen_V;

function start(Tiempo_Duracion_OE,Id_Edu_Evaluacion_Desarrollo_Cab,Tiempo_Estado,Id_Edu_Componente_S,Id_Edu_Almacen){
	
	min_duracion = Tiempo_Duracion_OE;
	Eval_Cab = Id_Edu_Evaluacion_Desarrollo_Cab;
	Tiempo_Estado_V = Tiempo_Estado;
	Id_Edu_Componente_V = Id_Edu_Componente_S;
	Id_Edu_Almacen_V = Id_Edu_Almacen;
    // alert(min_duracion);
    // alert(Eval_Cab);
    count = Tiempo_Estado;
	
	if (counter) clearInterval(counter);
	counter = setInterval(timer, 1000);
}

function timer(){
	
	// count++;
	
	if(Tiempo_Estado_V == 0 ){
	    count  += 1;		
	}else{
		
	    count  += 1;		
	}
	// alert(d);	
	
	
	var min = count;
	d = Number(min);

	var residuo =  d % 60;
	var h = Math.floor(d / 3600);
	var m = Math.floor(d % 3600 / 60);
	var s = Math.floor(d % 3600 % 60);

	var hDisplay = h > 0 ? h + (h == 1 ? " hora, " : " horas, ") : "";
	var mDisplay = m > 0 ? m + (m == 1 ? " minuto, " : " minutos, ") : "";
	var sDisplay = s > 0 ? s + (s == 1 ? " segundo" : " segundos") : "";

	var protocol = window.location.protocol;
	var hostname = window.location.hostname;
	var pathname = window.location.pathname;
	var Url = "";	
	var UrlRaiz = "";	
	var UrlB = "";	
	var UrlRaizB = "";	
	
    if(residuo == 0){

		Url = "/sadministrator/edu-desarrollo-examen/interface/guarda_auto_evaluacion/Tiempo_Estado/"+min+"/Id_Edu_Evaluacion_Desarrollo_Cab/"+Eval_Cab;
		UrlRaiz = ""+protocol+"//"+hostname+Url+"";

		$.ajax({
			data: "",
			url: UrlRaiz,
			type: 'get',
			async: true,
			success: function(response){
				console.log("response:: "+response);	
		   }
		});	
		
    }
   
    if( min_duracion == m ){
		
		Url = "/sadministrator/edu-desarrollo-examen/interface/finaliza_evaluacion/Id_Edu_Componente/"+Id_Edu_Componente_V+"/Id_Edu_Evaluacion_Desarrollo_Cab/"+Eval_Cab+"/key/"+Id_Edu_Almacen_V;
		UrlRaiz = ""+protocol+"//"+hostname+Url+"";

		$.ajax({
			data: "",
			url: UrlRaiz,
			type: 'get',
			async: true,
			success: function(response){
				console.log("response:: "+response);
			    $("#PanelA").html(response);	
				
		   }
		});			
		
    }

	document.getElementById("timer").value = min;
	document.getElementById("timer_text").innerHTML = hDisplay + mDisplay + sDisplay;
 
  
}


function stop(){
	if (counter) clearInterval(counter);
	count = 0;
	document.getElementById("timer").value = "";
	document.getElementById("timer_text").innerHTML = "";

}