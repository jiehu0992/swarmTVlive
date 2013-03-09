
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fineuploader.jquery-3.0/jquery.fineuploader-3.0.min.js"></script>
<script type="text/javascript">

(function($){

	// Save the base url as a a javascript variable
	var base_url = "<?php echo base_url(); ?>";
	var initDiagonal;
	var initFontSize;
	
	$(document).ready(function(){
		
		// as soon as the page is ready initiate all elements on the page
		initElements();
		
		// open the page info view
		$('#page_title_wrapper').click(function(e){
            $("#page_info_form_trigger").trigger('click');
			
			//$('input[name="x"]').val(e.pageX);
			//$('input[name="y"]').val(e.pageY);
			$('textarea').focus();
			clearSelection();
		});
        
        
		$('#page_title_wrapper').dblclick(function(e){
			$("a#add_element_form_trigger").trigger('click');
			
			$('input[name="x"]').val(e.pageX);
			$('input[name="y"]').val(e.pageY);
			$('textarea').focus();
			clearSelection();
		});
		
		// Work around for linking with special chars
		$('.text-content a').click(function(e){
			
			var location = $(this).attr('href');
			
			if(location.indexOf('http://') > 0)
			{
				e.preventDefault();
				window.location.href = base_url + "index.php/pages/view/" +location;
			}
		});
		
		// Ajax submit for updating page info 
		$('#page_info_submit').click(function(e){
			// Stop the page from navigating away from this page
			e.preventDefault();		
			
			// get the values from the form
			var idVal = $('input[name="id"]').val();
			var descriptionVal = $('textarea[name="description"]').val();
			var keywordsVal = $('textarea[name="keywords"]').val();
			var publicVal = $('select[name="public"]').val();
			
			// Post the values to the pages controller  
			$.post(base_url + "index.php/pages/update", { id: idVal , description: descriptionVal, keywords: keywordsVal, public:publicVal },
				function(data) {
				// User feed back
				alert("Data Loaded: " + data);
			});
		});
		
		// init fancy boxes
		$("a#add_element_form_trigger").fancybox({
			'overlayOpacity':0,
			'autoDimensions':true,
			'showCloseButton':false,
		});
        
		$("a#page_info_form_trigger").fancybox({
			'overlayOpacity':0,
			'autoDimensions':true,
			'showCloseButton':false,
		});
		
		// trigger the fancy box on double click
		$('#background').dblclick(function(e){
			$("a#add_element_form_trigger").trigger('click');
			
			$('input[name="x"]').val(e.pageX);
			$('input[name="y"]').val(e.pageY);
			$('textarea').focus();
			clearSelection();
		});
		
		// double click elements
		$('.element').dblclick(function(){
			
			$(this).find('.delete_button').fadeIn();
			
			// Only allow inline editing for text elements
			if( $(this).hasClass('text') )
			{
				// make content editable and disable drag
				$(this).find('.text-content').attr('contenteditable','true');
				$(this).draggable({ disabled: true });
				
				// listen for when the user shifts focus out of the box
				$(this).bind('focusout', function(updateTextElementContent)
				{
					$(this).find('.delete_button').fadeOut();
					// remove the event
					$(this).unbind('focusout', updateTextElementContent);
				
					// undo changes to element for editing
					
					// get the content
					var content_container = $(this).find('.text-content');
					
					// activate the drag and deactivate the content editable
					$(content_container).removeAttr('contenteditable');
					$(this).draggable({ disabled: false });	
					
					// get the id of the container
					var link_id = $(this).attr('id');
					
					// make a replica of content
					var cloned_content = $(content_container).clone();
					
					// get the  a list of all links
					var links = $(cloned_content).find('a'); 
					
					// replace links with their shortcodes
					$.each( links, function( key, value ) {
						var page_title = $(this).html();
						$(this).replaceWith('[[' + page_title + ']]');
					});
					
					// get the new content
					var new_contents = $(content_container).html();
					// send to database
					updateElement(link_id, 'text-content', new_contents);
					// update the element with the links
					$(content_container).html(processShortCodes(new_contents));
					
				});
			}
		});
		
		// Ajax for adding element
		$('#submit_element').click(function(e){
			e.preventDefault();
			
			// get all the form values
			var element_file = $('#element_file').get(0).files[0];
			var element_description = $('#element_text').val();
			var pages_id = $('input[name="pages_id"]').val();
			var x = $('input[name="x"]').val();
			var y = $('input[name="y"]').val();
			 
			// AJAX to server
			var uri = base_url + "index.php/elements/add";
			var xhr = new XMLHttpRequest();
			var fd = new FormData();
	
			xhr.open("POST", uri, true);
			
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					// Handle response.
					//alert(xhr.responseText); // handle response.
					location.reload();
				}
			};
			
			// check to see if a file has been selected 
			// if there is a file the text box will become the description
			// if there is no file the textbox is the content
			
			if (typeof element_file !== "undefined")
			{
				fd.append('file', element_file);
				fd.append('description', element_description);
			}else
			{
				fd.append('contents', element_description);
			}
			
			fd.append('pages_id', pages_id);
			fd.append('x', x);
			fd.append('y', y);
			
			// Initiate a multipart/form-data upload
			xhr.send(fd);
			
		});
		
		$('.delete_button').click(function(e){
		
			e.preventDefault();
			
			var element_id = $(this).attr('href');
		
			$.ajax({
				url		: base_url + 'index.php/elements/delete/' + element_id,
				type	: 'GET',
				success	: function(data, status)
				{
					location.reload();
				}
			});
		});
		
		// update preview is file is selected
		$('#element_file').change(function(){
			
			// check to see if a file has been selected
			var element_file = $('#element_file').get(0).files[0];
			if (typeof element_file !== "undefined") 
			{	
				$('#element_file_info').empty();
				
				var imageType = /image.*/;
		 
				if (element_file.type.match(imageType)) {
					
					// create thumbnail
					var img = document.createElement("img");
					img.classList.add("thumbnail");
					img.file = element_file;
					img.width = 100;
					$('#element_file_info').append(img);
					
					// load in image data 
					var reader = new FileReader();
					reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
					reader.readAsDataURL(element_file);
				}	
				
				var info = '<br />Name: ' + element_file.name + "<br /> Size: " + element_file.size + " bytes";
				$('#element_file_info').append(info); 	
			}
		});
		
		
		
	});
	
	// CREATE ELEMENTS ON THE PAGE
	// output the elements as a json array
	var page_elements_json = <?php echo json_encode($page_elements); ?>;
	var page_elements = new Array();
	
	function initElements()
	{
		// Loop through all of the elements in the json array
		for (var i = 0; i < page_elements_json.length; i++)
		{
			// create the style object
			var style = { 
			    'background-color'	:   page_elements_json[i].backgroundColor,
			    'color'		:   page_elements_json[i].color,
			    'font-size'		:   page_elements_json[i].fontSize + 'px',				
			    'font-family'	:   page_elements_json[i].fontFamily,
			    'height'		:   page_elements_json[i].height+'px',
			    'opacity'		:   page_elements_json[i].opacity,
			    'text-align'	:   page_elements_json[i].textAlign,
			    'width'		:   page_elements_json[i].width+'px',
			    'left'		:   page_elements_json[i].x+'px',
			    'top'		:   page_elements_json[i].y+'px',
			    'z-index'  		:   page_elements_json[i].z,
			    'position'		:   'absolute'
			}
			
			if (page_elements_json[i].type === 'text') style.height = 'auto';
	
			// create the div to contain the elements content
			var elm = $('<div>');
			
			// add the id
			$(elm).attr('id', page_elements_json[i].id);
			
			// add some sneaky data to the container
			$(elm).data('page_id', page_elements_json[i].pages_id);
			$(elm).data('license', page_elements_json[i].license);
			
			//add the style to the element and the generic class 
			$(elm).css(style);
			$(elm).addClass('element');
			$(elm).addClass(page_elements_json[i].type);
			
			// customise the element depending on its content type
			switch (page_elements_json[i].type)
			{
				case 'text':
					initText(elm, i);
					break;
				case 'image':
					initImage(elm, i);
					break;
				case 'audio':
					initAudio(elm, i);
					break;
				case 'video':
					initVideo(elm, i);
					break;
			}
			
			// MAKE DRAGGABLE
			$(elm).draggable({
				stop: function(event, ui) {
					updateElement(ui.helper[0].id , 'position');
				}
			});
			
			// *** GLOBAL VARIABLES CAUSING HAVOC WITH THIS FUNCTION
			// if the file type is not audio then add resize 
			if (page_elements_json[i].type !== 'audio')
			{
				$(elm).resizable({
					create: function(event, ui) {
						
					},
					start: function(e, ui) {
						initDiagonal = getContentDiagonal(this);
						initFontSize = parseInt($(ui.element).css("font-size"));
					},
					resize: function(e, ui) {
						var newDiagonal = getContentDiagonal(this);
						var ratio = newDiagonal / initDiagonal;
						$(this).css({"font-size" : initFontSize*ratio});
					},
					stop: function(event, ui) {
						updateElement(ui.helper[0].id, 'size');
						if ($(this).hasClass('text')) $(this).css({'height':'auto'});
					}
				});
			}		
			
			// Add delete button
			var delete_button = $('<a href="' + page_elements_json[i].id + '">');
			$(delete_button).addClass("delete_button");
			$(elm).append(delete_button);
			
			// add new element to the array
			page_elements.push(elm);
		}
		
		// add all the elements in the array to the page.
		$('body').append(page_elements);
	}
	
	function getContentDiagonal(element) {
		
		var contentWidth = $(element).width()-10;
		var contentHeight = $(element).height()-10;
		return Math.sqrt((contentWidth * contentWidth) + (contentHeight * contentHeight));
	}
	
	
	// ----------------------------------------------- TEXT
	function initText(elm, index)
	{
		// display the content not the description
		$(elm).append('<div class="text-content">' + page_elements_json[index].contents + '</div>');
	}
	
	// ----------------------------------------------- IMAGE
	function initImage(elm, index)
	{
		$(elm).html('<img width="100%" height="100%" src="' + base_url + 'assets/image/' + page_elements_json[index].filename + '" />');
	}
	
	// ----------------------------------------------- AUDIO
	function initAudio(elm, index)
	{
		var filename_NoExt = page_elements_json[index].filename.split('.');
		var audio_html = '<audio controls preload="none" style="width:320px">';
		audio_html = audio_html + '<source src="' + base_url + 'assets/audio/' + filename_NoExt[0] + '.mp3" type="audio/mpeg">';
		audio_html = audio_html + '<source src="' + base_url + 'assets/audio/' + filename_NoExt[0] + '.oga" type="audio/ogg">';
		audio_html = audio_html + '</audio>';	
		audio_html = audio_html + '<p><strong>Download Audio: </strong><a href="' + base_url + 'assets/audio/' + filename_NoExt[0] + '.mp3">MP3</a></p>';
		
		var audio_element = $(audio_html);
		$(elm).append(audio_element);
	}
	// ----------------------------------------------- VIDEO
	function initVideo(elm, index)
	{
		var filename_NoExt = page_elements_json[index].filename.split('.');
		var video_html = '<video width="100%" height="100%" controls preload="auto">';
		video_html = video_html + '<source src="' + base_url + 'assets/video/' + filename_NoExt[0] + '.mp4" type="video/mp4">';
		video_html = video_html + '<source src="' + base_url + 'assets/video/' + filename_NoExt[0] + '.webm" type="video/webm">';
		video_html = video_html + '<source src="' + base_url + 'assets/video/' + filename_NoExt[0] + '.ogv" type="video/ogg">';
		video_html = video_html + '<object width="100%" height="100%" type="application/x-shockwave-flash" data="http://fpdownload.adobe.com/strobe/FlashMediaPlayback.swf"><param name="movie" value="http://fpdownload.adobe.com/strobe/FlashMediaPlayback.swf"></param><param name="flashvars" value="src=http://www.ucfmediacentre.co.uk/swarmtv/assets/video/' + filename_NoExt[0] + '.mp4"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><p>Your browser does not support HTML5 video.</p></object>';
		video_html = video_html + '</video>';
		video_html = video_html + '<p><strong>Download Video: </strong><a href="' + base_url + 'assets/video/' + filename_NoExt[0] + '.mp4">MP4</a></p>';
		var video_element = $(video_html);
		
		$(elm).append(video_element);
	}
	
	// Update only the changes that have been made
	function updateElement(elementId, change, alt){
	
		// create an object with only the id 
		var changes = {'id':elementId};
		
		// add the specific changes to the object
		switch(change)
		{
			case 'size':
				// update width and height
				changes.width = parseInt($('#' + elementId).css('width'), 10);
				changes.height = parseInt($('#' + elementId).css('height'), 10);
				// only update font size if the element type is text (found some problems with positions otherwise)
				if ($('#' + elementId).hasClass('text')) changes.fontSize = $('#' + elementId).css('font-size');
				break;
			case 'position':
				// change the x and y for left and top ( tut tut  for mixing up terminology from data base to css )
				changes.x = parseInt($('#' + elementId).css('left'), 10);
				changes.y = parseInt(	$('#' + elementId).css('top'), 10);
				break;
			case 'text-content':
				changes.contents = alt;
				break; 
		}
		
		// Ajax the values to the pages controller 
		//alert('elementData=' + JSON.stringify(page_elements[$pageElementsArray])); 
		$.ajax({
			url		: base_url + 'index.php/elements/update',
			data	: changes,
			type	: 'POST',
			success	: function(data, status)
			{
				//DO NOTHING
			}
		});
	}
	
	// 
	function htmlDecode(input){
	  var e = document.createElement('div');
	  e.innerHTML = input;
	  return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
	}
	
	// create the display content with links
	function processShortCodes(string){

		while(string.indexOf('[[') > 0)
		{
			// get the beginning and end of the shortcode declaration
			var openCodeIndex = string.indexOf('[[');
			var closeCodeIndex = string.indexOf(']]') + 2;
			
			// get the shortcode content
			var link = string.substr(openCodeIndex + 2, (closeCodeIndex) - (openCodeIndex+4));
			//var linkURI = encodeURIComponent(string.substr(openCodeIndex + 2, (closeCodeIndex) - (openCodeIndex+4)));
			var linkURI = string.substr(openCodeIndex + 2, (closeCodeIndex) - (openCodeIndex+4));
			
			var leftOfShortCode = string.slice(0,openCodeIndex);	
			var rightOfShortCode = string.slice(closeCodeIndex);
		
			var linkHTML = '<a href="' + linkURI + '">' + linkURI + '</a>';
			
			string = leftOfShortCode + linkHTML + rightOfShortCode;
		
		}
		
		return string;
	}
	
	function clearSelection() {
    	if(document.selection && document.selection.empty) {
        	document.selection.empty();
    	} else if(window.getSelection) {
        	var sel = window.getSelection();
        	sel.removeAllRanges();
    	}
	}
})($);
</script>
