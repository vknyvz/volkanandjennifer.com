$(document).ready(function() {                  	

	// CUFON REPLACEMENT
	Cufon.replace('#navbar li a, h1.name, #main h2', { fontFamily: 'PT Sans Narrow' }); 
	
	Cufon.replace('#navbar li a span, .post h3 a, a.read_more, #sidebar h3, #main h3, #main h4, #pagination a',  { fontFamily: 'PT Sans' }); 
	
	Cufon.replace('#navbar li a.loginCufon', { fontSize: '12px' });
	
	Cufon.replace('a.button', {fontFamily: 'PT Sans',textShadow: '1px 1px #434343'});

	//DROPDOWN SCRIPT
	$('#navbar ul').css({display: "none"}); 
	$('#navbar li').hover(function(){
		$(this).find('ul:first').css({visibility: "visible", display: "none"}).fadeIn('fast');
		},function(){
		$(this).find('ul:first').css({visibility: "hidden"});
		});	
	
		// IE navbar last child fix		
		$('#navbar li ul li:last-child').css('border-bottom', 'none');	

	
	//SHOW THE LOADER AND HIDE BACKGROUND IMAGE UNTIL IT IS LOADED
	$('#bg img').css('display', 'none');
	$('body').append('<span id="body_loader"></span>');	
	$('#body_loader').fadeIn();


	//100% MAIN DIV HEIGHT HACK
	var winHeight = $(window).height();
	var mainHeight = $('#main').height();

	if(winHeight > mainHeight){
	$('#main').addClass('fullheight');	
	}
	else
	{$('#main').addClass('expandheight');}

		//100% main div height hack - apply on window resize
		$(window).resize(function() {	
			if(winHeight > mainHeight){
				$('#main').addClass('fullheight');
			}
			else
			{$('#main').addClass('expandheight');}	
		});
		
	

	//RANDOM IMAGE ON PAGE LOAD 	
	$.randomImage = {
		defaults: {			
			path: '/_dB/_random/', 
			myImages: ['IMG_0002.jpg','IMG_0003.jpg','IMG_0004.jpg','IMG_0005.jpg','IMG_0006.jpg', 
			           'IMG_0008.jpg','IMG_0009.jpg','IMG_0010.jpg','IMG_0011.jpg','IMG_0012.jpg', 'IMG_0013.jpg',
			           'IMG_0014.jpg','IMG_0016.jpg','IMG_0017.jpg','IMG_0018.jpg','IMG_0019.jpg','IMG_0020.jpg',
			           'IMG_0021.jpg','IMG_0022.jpg','IMG_0023.jpg','IMG_0024.jpg','IMG_0025.jpg','IMG_0026.jpg',
			           'IMG_0027.jpg','IMG_0028.jpg','IMG_0029.jpg','IMG_0030.jpg','IMG_0032.jpg','IMG_0033.jpg',
			           'IMG_0035.jpg','IMG_0037.jpg','IMG_0038.jpg','IMG_0040.jpg','IMG_0041.jpg'] 
		}			
	}
	
	$.fn.extend({
			randomImage:function(config) {	
				var config = $.extend({}, $.randomImage.defaults, config); 
				 return this.each(function() {		
						var imageNames = config.myImages;	
						//get size of array, randomize a number from this
						// use this number as the array index
						var imageNamesSize = imageNames.length;
						var lotteryNumber = Math.floor(Math.random()*imageNamesSize);
						var winnerImage = imageNames[lotteryNumber];
						var fullPath = config.path + winnerImage;
						//put this image into DOM at class of randomImage
						// alt tag will be image filename.
						$(this).attr( {
										src: fullPath,
										alt: winnerImage
									});	
				});	
			}
	});
	
		
	//run the random script
	$('.random').randomImage();
	
	//random image script ends here

    //GALLERY IMAGES HOVER SCRIPT
		
		//add span that will be shown on hover on gallery item
		$(".gallery li a.image").append('<span class="image_hover"></span>'); //add span to images
		$(".gallery  li a.video").append('<span class="video_hover"></span>'); //add span to videos

		$('.gallery  li a span').css('opacity', '0').css('display', 'block') //span opacity = 0 
		
		// show / hide span on hover
		$(".gallery li a").hover(
			 function () {
				 $(this).find('.image_hover, .video_hover').stop().fadeTo('slow', .7); }, 
			function () {
		  		  $('.image_hover, .video_hover').stop().fadeOut('slow', 0);
		});
			
	
             
    //IF BODY HAS CLASS VIDEO_BACKGROUND, MAKE HTML HEIGHT 100% AND ABILITY TO HIDE AND SHOW NAVIGATION ON HOME PAGE
	if($('body').hasClass('video_background'))
	{
		$('html').css('height', '100%');		
		
		
	$('#hide_menu a').css('display', 'block');	
		                  	         
   //SHOWING MENU TOOLTIP ------ delete if you don't want it!                    
    $('#hide_menu a').hover( function(){
         $('.menu_tooltip').stop().fadeTo('fast', 1);            	
     },
     function () {
     	$('.menu_tooltip').stop().fadeTo('fast', 0);  
	});
             
	
	 
		            
    //HIDING MENU         
    
    //click on hide menu button (arrow points up)                            
    $('#hide_menu a.menu_visible').live('click',function(){	
		
	    	//add class hidden
	        $('#hide_menu a.menu_visible')
		        .removeClass('menu_visible')
		        .addClass('menu_hidden')
		        .attr('title', 'Show the navigation');
		   
		    // hide the tooltip     
	        $('.menu_tooltip').css('opacity', '0');
	          
	        //move navigation up, after replace the text                  
		    $('#menu_wrap').animate({top: "-=290px"}, "normal", function() {      	
		                $('.menu_tooltip p').text('Show the navigation');	//tooltip text in when menu is 'hidden' 
	        });
	                       	
            return false;
            
     	});
	          
     	       	
	//SHOWING MENU	
		//click on show menu button (arrow points down)                      	
		 $('#hide_menu a.menu_hidden').live('click', function(){	
		    
		     //add class visible	
		     $('#hide_menu a.menu_hidden')
			     .removeClass('menu_hidden')
			     .addClass('menu_visible');
		
			// hide the tooltip      
		      $('.menu_tooltip').css('opacity', '0');    
		        $('#menu_wrap').animate({ top: "+=290px"}, 'normal');  
		            $('.menu_tooltip p').text('Hide the navigation'); //tooltip text in when menu is 'visible' 
		            
		            return false;    
		 });

	};  

	//CONTACT PAGE MAP - CHANE OPACITY ON HOVER
	$('img.map').css('opacity', '.5');
	
	$('img.map').hover(function(){
		$(this).fadeTo('fast', 1);	
	},
	function(){
		$(this).fadeTo('fast', .5);	
	});

}); //document.ready function ends here

//WAIT UNTIL CONTENT IS LOADED
$(window).load(function() {
	 
	//hide loader
	$('#body_loader').hide().remove();
	
	//append grid if not on the home page
	if (!$('body').hasClass('body_home')) {	
	$('.grid_overlay, #bg').fadeTo('fast', .8).append('<div class="grid"></div>');
	}
	
	//fade in the image
	$('#bg img').fadeIn('normal');
	   	
});

function countPicture(id)
{
	$.ajax({
		url: "/dashboard/index/picture-count/token/" + id, 
	    success: function(returnData) {	    	
	    }
	}) 
}