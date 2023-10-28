if($('#Form_Call').length){

	$("#Form_Call").validate({

		rules: {
			username: { required: true},
			email: { required: true},
			phone: { required: true},
			message: { required: true},
		},
		messages: {
			username: "Debe llenar el campo.",
			email: "Debe llenar el campo.",
			phone: "Debe llenar el campo.",
			message: "Debe llenar el campo.",
		},
		submitHandler: function(form){
			
			ActiveCurtain();
			
			var Url = $("#Form_Call").attr("url");
	
			$.ajax({
			type:"POST",
			url:Url,
			data: $("#Form_Call").serialize(), 
			success: function(data){
				$("#Message").html(data);
				DisableCurtain();
			}
			});

		}
	})	

}
	
	
if($('#Form_Suscripcion').length){

	$("#Form_Suscripcion").validate({

		rules: {
			email: { required: true},
		},
		messages: {
			email: "Debe llenar el campo.",
		},
		submitHandler: function(form){
			
			ActiveCurtain();
			var Url = $("#Form_Suscripcion").attr("url");
			$.ajax({
			type:"POST",
			url:Url,
			data: $("#Form_Suscripcion").serialize(), 
			success: function(data){
				$("#Message").html(data);
				DisableCurtain();
			}
			});
		}
	})	
}	