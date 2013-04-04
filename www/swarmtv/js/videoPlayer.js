$(document).ready(function() {

	// Set up link thumbnails
	$('a.videoLink').each(function(){
		
		var thumbnailFilePath = 'moviePosters/' + $(this).attr('videofile') + '.jpg';
		var videoCaption = $(this).attr('videocaption');
		
		$(this).css('background-image','url('+thumbnailFilePath+')');
		$(this).html('<div class="caption">'+videoCaption+'</div><img class="play" src="images/play_icon.png" />');
		fancyWidth=eval($(this).attr('videowidth'))+26;
		//alert(fancyWidth);
		fancyHeight=eval($(this).attr('videoheight'))+82;
		//alert(fancyHeight);
		$(this).attr('href','http://www.swarmtv.org/clipPlayer.php?id=' + $(this).attr('id') + "&width="+fancyWidth+"&height="+fancyHeight);
		$(this).addClass('iframe');
	});
	
	$('a.sequenceLink').addClass('iframe');

	$('a.sequenceLink').each(function(){  
        var dWidth  = parseInt($(this).attr('href').match(/width=[0-9]+/i)[0].replace('width=',''));  
        var dHeight     =  parseInt($(this).attr('href').match(/height=[0-9]+/i)[0].replace('height=',''));  
		$(this).fancybox({  
			'width':dWidth,  
			'height':dHeight, 
			'padding':0,
			'autoScale'         : false,  
			'transitionIn'		: 'fade',
			'transitionOut'		: 'fade',
			'overlayColor'		: '#000',
			'overlayOpacity'	: '.6',
			'type'          : 'iframe',  
			'onClosed'	:	function() {
			  window.location.href=window.location.href;
			  restoreZIndex();
			}  
		});  
	});  

	$('a.videoLink').each(function(){  
        var dWidth  = parseInt($(this).attr('href').match(/width=[0-9]+/i)[0].replace('width=',''));  
        var dHeight     =  parseInt($(this).attr('href').match(/height=[0-9]+/i)[0].replace('height=',''));  
		$(this).fancybox({  
			'width':dWidth,  
			'height':dHeight, 
			'padding':0,
			'autoScale'         : false,  
			'transitionIn'		: 'fade',
			'transitionOut'		: 'fade',
			'overlayColor'		: '#000',
			'overlayOpacity'	: '.6',
			'type'          : 'iframe',  
			'onClosed'	:	function() {
			  window.location.href=window.location.href;
			  restoreZIndex();
			}
		});  
	});

});

