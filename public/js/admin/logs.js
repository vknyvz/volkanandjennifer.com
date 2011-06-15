var logs = null;

function showDeleteLogsDialog(type)
{			
	var logIdsString = '';
	
	logIdsString = $('input:checked').map(function(i,n) {
    	return $(n).val();
    }).get();
    
    logs = logIdsString;
		
	if(logIdsString.length != 0) {
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
				        url: '/logs/delete/type/' + type + '/logIds/' + logs, 
				        success: function(returnData) { 
				        	window.top.location = '/logs/' + type + '';	   
				    	}
				       }
				    )    
				}
			}
		});
	}
}

function showDetails(id, field)
{	
	$('#detailsDialog').dialog({
		resizable: false,
		width: 300,
		hide: 'explode',		
		buttons: {			
			'Close': function() {
				$(this).dialog('close');
		}}
	});
	
	$.ajax( {
        url: '/logs/show-detail/id/' + id + '/field/' + field, 
        success: function(returnData) { 
	        $('#detailsDialog').html(returnData);		           
    	}
       }
    );
}
