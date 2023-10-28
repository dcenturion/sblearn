
function ActiveCurtain(){

	var ventana_anchoA = $(window).width();
	var ventana_altoA = $("body").height();
	
	var pathname = window.location.hostname;
	var protocol = window.location.protocol ;

	$("body").append("<div class='Cortina'><div class='Cortina_Tenue' style='width:"+ventana_anchoA+"px;height:"+ventana_altoA+"px;top:-"+ventana_altoA+"px;'><img src='"+protocol+"//"+pathname+"/simages/loadingB.gif' width=150px style='position:fixed;top:238px;'></div></div>");

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
				console.log("data:: "+data);
		$('#'+Panel+'').html(data);
		// DisableCurtain_B();	
	}
	});

			
}

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

function DisableCurtain(){
	
	$(".Cortina").remove();
}

function Redirect(Settings){
    var JsnData = JSON.parse(Settings);
    Url = JsnData.Url;
    Screen = JsnData.Screen;
    Type_Send = JsnData.Type_Send;
		// ActiveCurtain();
	var Parametros = {
	};	  
	
	var protocol = window.location.protocol;
	var hostname = window.location.hostname;
	
	var pathname = window.location.pathname;
	UrlRaiz = ""+protocol+"//"+hostname+Url+"";
	
	// console.log("load page new "+UrlRaiz);		
	console.log(" Screen:: "+Screen);	
    $("#"+Screen+"").html('<div>cargando....</div>');	
	// console.log(UrlRaiz);	
	
	if(Type_Send !== "HXM"){
		history.pushState(UrlRaiz+"/request/on/", "", UrlRaiz+"/request/on/");	
	}
	//ActiveCurtain_B();

	$.ajax({
		data: Parametros,
		url: UrlRaiz,
		type: 'get',
		async: true,
		success: function(response){
			// console.log("response:: "+response);
			$("#"+Screen+"").html(response);	
            // DisableCurtain();			
	   }
	});	
}	  	

