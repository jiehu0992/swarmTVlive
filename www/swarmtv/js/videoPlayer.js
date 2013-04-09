$(document).ready(function() {
  
	// Set up link thumbnails
	$('a.videoLink').each(function(){
		
		var thumbnailFilePath = '../../../assets/videoposters/' + $(this).attr('videofile') + '.jpg';
		var videoCaption = $(this).attr('videocaption');
		$(this).css('background-image', 'url('+thumbnailFilePath+')');
		$(this).html('<div class="caption">'+videoCaption+'</div><img class="play" src="../../../img/play_icon.png" />');
		fancyWidth=eval($(this).attr('videowidth'))+26;
		fancyHeight=eval($(this).attr('videoheight'))+82;
		
		//$(this).attr('href','../../../clipPlayer.php?id=' + $(this).parent().attr('id') + "&width="+fancyWidth+"&height="+fancyHeight);
		$(this).attr('href','../../../index.php/clipPlayer/playClip/457/640/480');
		$(this).attr('target','fancybox-frame');
		
		$(this).addClass('iframe');
	});
	
	$('a.sequenceLink').addClass('iframe');

	$('a.sequenceLink').each(function(){
		var parameters = $(this).attr('href').split("/");
		//codeigniter parameters for width and height
        var dWidth  = parseInt(parameters[7]);
		var dHeight     =  parseInt(parameters[8]);  
        //var dWidth  = parseInt($(this).attr('href').match(/width=[0-9]+/i)[0].replace('width=',''));  
        //var dHeight     =  parseInt($(this).attr('href').match(/height=[0-9]+/i)[0].replace('height=',''));  
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
		var parameters = $(this).attr('href').split("/");
		//codeigniter parameters for width and height
        var dWidth  = parseInt(parameters[7]);
		var dHeight     =  parseInt(parameters[8]);  
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
			  //restoreZIndex();
			}
		});  
	});

});

