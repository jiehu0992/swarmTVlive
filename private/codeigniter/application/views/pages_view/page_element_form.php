<a class="hidden" id="add_element_form_trigger" href="#add_element_form"> HACK FOR FANCYBOX IMPLEMENTATION </a>

<!-- omni-element box: will detect exactly what the user wants to do instead of forcing them to make so many choices -->
<div class="hidden">
	<div id="add_element_form_wrapper">
		<form id="add_element_form" >
			<h2> New Element </h2>	
			<p id="element_file_info"> </p>
			<label for="element_file">Choose a file:</label>
			<input type="file" name="element_file" id="element_file" /><br />
			
			<label for="element_description">Add some text:</label><br />
			<textarea id="element_description" > </textarea> <br />
			
			<input type="submit" id="submit_element" value="Submit" /> 
			
			<!-- hidden values -->
			<input type="hidden" name="pages_id" value="<?php echo $page_info->id; ?>"/>
			<input type="hidden" name="x" value="400"/>
			<input type="hidden" name="y" value="400"/>
		</form>
	</div>
</div>
