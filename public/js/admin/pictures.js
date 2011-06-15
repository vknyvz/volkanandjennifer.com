var albums = null;

function showDeletePicturesDialog(id)
{			
	var pictureIdsString = '';
		
	if (id) {
		pictureIds = id;
	}
	else {
		pictureIdsString = $('input:checked').map(function(i,n) {
	    	return $(n).val();
	    }).get();
	    
		pictureIds = pictureIdsString;
	}
		
	if(pictureIdsString.length != 0 || id != 0) {
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
				        url: "/pictures/delete/pictureIds/" + pictureIds, 
				        success: function(returnData) { 
				        	window.top.location = '/pictures';	   
				    	}
				       }
				    )    
				}
			}
		});
	}
}