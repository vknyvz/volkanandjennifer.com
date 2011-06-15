function editTemplate(id, mode)
{
	$('#editTemplate').dialog({
		resizable: false,
		width: 470,
		hide: 'explode',		
		buttons: {			
			'Cancel': function() {
				$(this).dialog('close');
			},
			'Save': function() {
				$('#adminTemplateEditForm').submit();
			}			
		}
	});
	
	$.ajax( {
        url: '/settings/edit-template/mode/' + mode + '/templateId/' + id, 
        success: function(returnData) { 
	        $('#editTemplate').html(returnData);		           
    	}
       }
    );
}

function previewTemplate(id)
{
	$('#previewTemplateDialog').dialog({
		resizable: false,
		width: 610,
		hide: 'explode'
	});
	
	$.ajax( {
        url: '/settings/preview-template/templateId/' + id, 
        success: function(returnData) { 
	        $('#previewTemplateDialog').html(returnData);		           
    	}
       }
    );
}

function submitadminTemplateEditForm(data)
{
	if (!data.success)  {
		var errors = '';

		jQuery.each(data.fail, function() {
			errors += this.message + '<br />';
		});
		
		$('#adminTemplateEditFormError').html('<ul class="system_messages"><li class="red"><span class="ico"></span><strong class="system_title">' + errors + '</strong></li></ul>');		
	} else {
		$('#adminTemplateEditFormError').html('');
		window.top.location = '/settings/templates/mode/' + data.mode + '';		
	}
}
