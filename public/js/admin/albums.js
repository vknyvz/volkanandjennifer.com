var albums = null;

function showDeleteAlbumsDialog(id)
{			
	var albumsIdsString = '';
		
	if (id) {
		albumIds = id;
	}
	else {
		albumsIdsString = $('input:checked').map(function(i,n) {
	    	return $(n).val();
	    }).get();
	    
		albumIds = albumsIdsString;
	}
		
	if(albumsIdsString.length != 0 || id != 0) {
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
				        url: "/albums/delete/albumIds/" + albumIds, 
				        success: function(returnData) { 
				        	window.top.location = '/albums';	   
				    	}
				       }
				    )    
				}
			}
		});
	}
}