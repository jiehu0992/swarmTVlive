<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elements extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function add()
	{
		$this->load->model('Elements_model');
		$this->load->model('Links_model');
		$this->load->model('Pages_model');
		
		// check if there is a file to process
		if(sizeof($_FILES) > 0){
       		
       		// check if the file validates
			$this->Elements_model->validate_file() or exit($this->Elements_model->file_errors);
			
			// move the file depending on its mime type
			$this->Elements_model->move_file() or exit($this->Elements_model->file_errors);
		}		
		
		$this->Elements_model->validate_data() or exit($this->Elements_model->data_errors);
		
		$return_id = $this->Elements_model->add_element_to_database($this->Elements_model->data_errors) or exit();
		
		// process links for element
		// *** PROCESS THE LINKS IN THE DESCRIPTION & CONTENT***
		
		// get more page details
		$pages_id = $this->Elements_model->return_pages_id();
		$pages_title = $this->Pages_model->get_title($pages_id);
		
		// get the DESCRIPTION
		$description = $this->Elements_model->return_description();
		
		// piece the content back together with the link ids instead of the page titles
		$processed_description = $this->Links_model->process_links($description, $pages_title, $return_id);
			
		//update the CONTENTS
		$this->Elements_model->update_description($return_id, $processed_description);
		
		// get the content
		$contents = $this->Elements_model->return_contents();
		
		// piece the content back together with the link ids instead of the page titles
		$processed_contents = $this->Links_model->process_links($contents, $pages_title, $return_id);
			
		//update the description
		$this->Elements_model->update_contents($return_id, $processed_contents);
	}	
	
	public function update()
	{
		$this->load->model('Elements_model');
		echo $this->Elements_model->update_element();
	}
	
	public function delete($id)
	{
		$this->load->model('Elements_model');
		$this->Elements_model->delete($id);
	}
}

/* End of file Elements.php */
/* Location: ./application/controllers/Elements.php */
