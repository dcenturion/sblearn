$(document).ready(function() {

	var firebaseConfig = {
	apiKey: "AIzaSyCGaJ8DXPN3SK3iAeeC3RXrT6i6mXm8Zjk",
	authDomain: "chat-yachai.firebaseapp.com",
	databaseURL: "https://chat-yachai.firebaseio.com",
	projectId: "chat-yachai",
	storageBucket: "chat-yachai.appspot.com",
	messagingSenderId: "684972069719",
	appId: "1:684972069719:web:d33ccddda0ce0eb0b9e412",
	measurementId: "G-C77VG93SBJ"
	};
	firebase.initializeApp(firebaseConfig);

		var text_msj = document.getElementById("mensaje");
		var ul_msj = document.getElementById("ul_msj");
		var Btn_Enviar = document.getElementById("boton_chat");
		var Id_Chat = document.getElementById("chat").value;
		
		Btn_Enviar.addEventListener("click",function(){
			
			var text_msj_a = text_msj.value;
			var s = new Date;
			var m = s.getFullYear() + "-" + (s.getMonth() + 1) + "-" + s.getDate();
			var	r = s.getHours() + ":" + s.getMinutes()  + ":" + s.getSeconds();
			var t = document.getElementById("nombre");
			var	n = document.getElementById("email");
		    
			firebase.database().ref("Chat-"+Id_Chat).push({
				name: t.value,
				email: n.value,
				message:text_msj_a,
				fecha: m,
                hora: r
				
			});
			$('#mensaje').val('');
			// text_msj_a.val('');
			
		})
		
            firebase.database().ref("Chat-"+Id_Chat).limitToLast(30).orderByKey().on('value', function(snapshot){		
		// firebase.database().ref("chat").on("value",function(snapshot){
			var html = "";
			$('#loadingChat').css({ 'width':'100%'})
			snapshot.forEach(function(e){
				var element = e.val();
				var nombre = element.name;
				var msg = element.message;
				var fecha = element.fecha;
				var hora = element.hora;
				html += "<li style='background: #c2e3ff;list-style: none;padding: 5px 10px 5px 10px;margin: 10px 0px 10px 0px;width: 100%;'><div>"+msg+"</div>";
			    html += "<div><span><b>"+nombre+"</b> </span><span style='font-size:0.85em;'> | "+fecha+" "+hora+" </span> </div>";
			    html += "</li>";
			});
			ul_msj.innerHTML = html;
			$("#chatPanel").animate({ scrollTop: $("#chatPanel").prop("scrollHeight")}, 30);
		});
		
})			
	