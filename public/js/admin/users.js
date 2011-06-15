var userIds = null;

function showDeleteUsersDialog(id)
{			
	var userIdsString = '';
	
	if (id) {
		userIds = id;
	}
	else {
		userIdsString = $('input:checked').map(function(i,n) {
        	return $(n).val();
	    }).get();
	    
	    userIds = userIdsString;
	}
		
	if(userIdsString.length != 0 || id != 0) {
		$('#deleteDialog').dialog({
			resizable: false,
			width: 390,
			hide: 'explode',
			buttons: {				
				'No': function() {
					$(this).dialog('close');
				},
				'Yes': function() {
					$.ajax( {
				        url: "/users/delete/userIds/" + userIds, 
				        success: function(returnData) { 
				        	window.top.location = '/users';	   
				    	}
				       }
				    )    
				}
			}
		});
	}
}

function sendEmailDialog(id, type)
{
	$('#sendEmailDialog').dialog({
		resizable: false,
		width: 480,
		title: (type == 'REGISTER') ? 'Send `Newly Registered` Email' : 'Send `Reset Password` Email',
		hide: 'explode',		
		buttons: {
			'Cancel': function() {
				$(this).dialog('close');
			},
			'Send': function() {	
				$.ajax( {
			        url: "/users/email/type/" + type + "/userId/" + id, 
			        success: function(returnData) { 
			        	//window.top.location = '/users';	   
			    	}
			       }
			    ) 
			}
		}
	});
	
	$('#sendEmailDialog').html('<ul class="system_messages">');
			
	if(type == 'REGISTER') {
		emailType = '`newly registered';
		explain = '<li class="blue"><span class="ico"></span><strong class="system_title">This email will state that an account was created by an admin on behalf of this user.</strong></li>';
	}
	else if(type == 'FORGOT_PASS'){
		emailType = '`reset password`';
		explain = '';
	}   
	
	$('#sendEmailDialog').html('Are you sure you want send a <b>' + emailType + '</b> email to this user? <ul class="system_messages">' + explain + '</ul>');
}



