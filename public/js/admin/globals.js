$(document).ready(function() {
	
	$('.deleteSingle').bind('click', function() {

		var count;
		count = countCheckedCheckboxes('input');
										
		if(count > 0) {
			$('.deleteAll').addClass('enabledRed');
			$('.deleteAll').removeClass('disabled');
		}
		else {
			$('.deleteAll').addClass('disabled');
			$('.deleteAll').removeClass('enabledRed');
		}
	});		

	
	$("table tr:even").addClass("even");
	$("table tr:odd").addClass("odd"); //This is not required - you can avoid this if you have a table background
	$("table tr").hover(function(){
		$(this).addClass("hovcolor");
	}, function(){
		$(this).removeClass("hovcolor");
	});
	$("table tr").click(function(){
			//$("table.tablecolors tr").removeClass("highlightcolor"); // Remove this line if you dont want to de-highlight the previously highlighted row
			$(this).toggleClass("highlightcolor");
	});
	

});	

function searchUsers()
{
	$('#searchUsersDialog').dialog({
		resizable: false,
		width: 500,
		hide: 'explode',		
		buttons: {			
			'Search': function() {
				$('#usersSearch').submit();
			}
		}
	});
	
	$.ajax( {
        url: '/users/search/', 
        success: function(returnData) { 
	        $('#searchUsersDialog').html(returnData);		           
    	}
       }
    );
}

function countCheckedCheckboxes(name) 
{
	total = $('' + name + ':checked').map(function(i,n) {
    	return $(n).val();
    }).get();
	
	return total.length;
}

function enableAllButtons()
{		
}

function disableAllButtons()
{
	$('.unselectAll').addClass('disabled');		
}

function checkAllCheckbox()
{
	$('input:checkbox').attr('checked', true);
	
	$('.selectAll').removeClass('enabled');
	$('.selectAll').addClass('disabled');
	$('.unselectAll').removeClass('disabled');
	$('.unselectAll').addClass('enabled');
	$('.deleteAll').addClass('enabledRed');	
}

function uncheckAllCheckbox()
{
	$('input:checkbox').attr('checked', false);
	
	$('.selectAll').removeClass('disabled');
	$('.selectAll').addClass('enabled');
	$('.unselectAll').removeClass('enabled');
	$('.unselectAll').addClass('disabled');	 
	$('.deleteAll').removeClass('enabledRed');
}