$(document).ready(function () {
        Temporizador_Comp();   

        
    });


function Temporizador_Comp(){
    myVar = setInterval(alertFunc,50000);
}


function alertFunc(){
    let data = sessionStorage.getItem('Token_Client');
    var protocol = window.location.protocol;
    var hostname = window.location.hostname;
    
    var pathname = window.location.pathname;
    UrlRaiz = ""+protocol+"//"+hostname+"";
    

    $.get(UrlRaiz+"/sadministrator/sbookstores/js/comp_session_unica.php?data="+data, function(request){
    
          if (request != "") {
                  
                  // console.log("HOLA todo bien");
                  // alert(request);
                  $("#Tocken_Proccess").append(request);
                   
                } else {
                     // console.log("Algo fallo");
                  // alert(request);
                   
                    $("#Tocken_Proccess").append(request);

                   
               }
        })

}