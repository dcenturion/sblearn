grecaptcha.ready(function() 	
{
grecaptcha.execute('6LeirKgZAAAAAKAdgLLRFiMgwrdnlny40M7hM0Oe', {action: 'homepage'})
.then(function(token) 
{
    console.log(token);
    $('#google-response-token').val(token);
});
});