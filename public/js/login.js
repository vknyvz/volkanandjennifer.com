$(document).ready(function() {
	var emailVal = $("#username").val();
	var passwordVal = $("#password").val();
	
	if(emailVal == '') {
		$("#username").parent().find('label').css('display', 'block');	
	}
	if(passwordVal == '') {
		$("#password").parent().find('label').css('display', 'block');	
	}
		
	$('form input').focus(function(){
		if($(this).attr('id') != 'remember')
			$(this).parent().find('label').fadeOut('fast');		
	});		
	
	$('form input').blur(function(){
	var currentInput = 	$(this);	
	if (currentInput.val() == ""){
	 $(this).parent().find('label').fadeIn('fast');
		 }
	});	
	
	$("#login_form #form_submit").click(function(){		
			   				 		
		$(".error").hide();
		
		$("form input").focus(function() {
			$(this).removeClass('error_input');
		});
		
		$("form input").keypress(function() {
			$(this).parent().find('span').fadeOut();	
		});
		
		var hasError = false;
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		var emailVal = $("#username").val();
		if(emailVal == '') {
			$("#username")
			.after('<span class="error">Please enter your e-mail</span>')
			.addClass('error_input')
			hasError = '2'; 
				
		}
		else if(!emailReg.test(emailVal)) {	
			$("#username")
			.after('<span class="error">Please provide a valid e-mail</span>')
			.addClass('error_input')
			hasError = '1';
		}
		
		var passwordVal = $("#password").val();		
		if(passwordVal == '') {
			$("#password")
			.after('<span class="error">Please enter your password</span>')
			.addClass('error_input')
			hasError = '3'; 			
		} 
		
		if(hasError) { return false; }
		
		if(hasError == false) {		
			var dataString = $('#login_form').serialize();
			       
			$.ajax({
			    type: "POST",
			    url: "/auth/login",
			    data: dataString,
			    dataType: 'json',
			    success: function(data){
		    	  if(data.success == 1) {
		    		  $('#loader').remove();
					  $('#login_form').children().fadeOut('fast');
					  $("#form_submit").fadeOut('fast', function () {
						 $('#login_form').append('<span id="loader"></span>'); 
					  });
					  window.top.location = data.redirectUrl;
		    	  }
		    	  else {		    		  
		    		  $('#login-errors').html(data.error);
		    	  }
				}
			});
					
			return false;  
		} 			
	});	
});	
		