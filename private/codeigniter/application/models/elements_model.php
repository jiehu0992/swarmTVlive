<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elements_model extends CI_Model {

	var $file_errors = null;
	var $data_errors = null;
	var $file = false;				// is a file present on the server?
	var $valid_file = false;
	
	var $current_mime_type_index = -1;
	var $excepted_mime_types = array 	(
										array('image/jpeg;'	, 'image'),
										array('image/png;'	, 'image'),
										array('image/gif;'	, 'image'),
										array('image/jpg;'	, 'image'),
										array('image/gif;'	, 'image'),
										array('audio/mpeg;'	, 'audio'),
										array('video/mp4;' , 'video')
										);
										
	var $data = array();
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url');
        
        $config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100';
		$config['max_width'] = '1024';
		$config['max_height'] = '768';

		$this->load->library('upload', $config);
    }
    
    // get all of the elements for a page
    function get_all_elements($page_id)
    {
    	$query = $this->db->get_where('elements', array('pages_id' => $page_id));
    	
    	// loop through each element and update the description
    	$elements = $query->result_array();
    	
    	for ($i = 0; $i < sizeof($elements); $i++)
    	{
    		$description = $elements[$i]['description'];

			// break up the parts of the description
			$break_apart_description = $this->Links_model->parse_string_for_links($description);
		
			// piece the content back together with the html links embedded
			$processed_description = $this->Links_model->insert_links($break_apart_description);
			
			//update the description
			$elements[$i]['description'] = $processed_description;
			
			$contents = $elements[$i]['contents'];

			// break up the parts of the description
			$break_apart_contents = $this->Links_model->parse_string_for_links($contents);
		
			// piece the content back together with the html links embedded
			$processed_contents = $this->Links_model->insert_links($break_apart_contents);
			
			//update the description
			$elements[$i]['contents'] = $processed_contents;
    	}
    	return $elements;
    }
    
    function get_element_by_id($id)
    {
    	$this->db->where('id', $id);
    	$query = $this->db->get('elements');
    	return $query->row();
    }

	// validate the file using magic-bytes
	function validate_file()
	{
		$file = $_FILES['file'];
		
		// Check there was no errors with the upload
		if ($file['error'] > 0) 
		{
			$this->file_errors = "There was an error uploading the file!";
			return false;
			exit;
		}
	
		// interesting article on magicbytes here: 
		// http://designshack.net/articles/php-articles/smart-file-type-detection-using-php/
		// Get the file mime type
		$file_info = new finfo(FILEINFO_MIME);  
		$mime_type_string = $file_info->buffer(file_get_contents($file['tmp_name']));
		$mime_type_parts = explode(' ', $mime_type_string);
		
		$file_mime_type = $mime_type_parts[0]; 
		
		// check mime type against a list of excepted mime types
		foreach($this->excepted_mime_types as  $index => $type) 
        { 
            if (in_array($file_mime_type, $type))
            {
            	$this->current_mime_type_index = $index;
            	break;
            }
        } 
		
		// send error if the file does not validate
		if ($this->current_mime_type_index < 0) 
		{
			$this->file_errors = "The file type is not allowed! :" . $file_mime_type;
			return false;
			exit;
		}
		return true;			// the file validates
	}
	
	function move_file()
	{	 
		// Consider creating a folder every new month so that elements are easier to find? 
		// construct the location from the data
		$folder_from_mime_type = $this->excepted_mime_types[$this->current_mime_type_index][1];  // image / audio / movie folder
		$uploads_dir = base_url() . 'assets/' . $folder_from_mime_type . '/';
		
		$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		$unique_name = $folder_from_mime_type . '-' . uniqid();
		
		$full_name = $unique_name . '.' . $extension;
		
		$this->data['filename'] = $full_name;
		$this->data['type'] = $folder_from_mime_type;
		
		$success = move_uploaded_file($_FILES['file']['tmp_name'], 'assets/' . $folder_from_mime_type . '/' . $full_name);	
		
		if ($success){
			$file = true;
		}else
		{
			$this->file_errors = "An error occurred when moving the file on the server!";
			return false;
			exit;
		} 
		return true;
	}
	
	/*
	backgroundColor, color, content, filename, fontFamily, fontSize, height, width, timeline, 
	opacity, attribution, description, keywords, license, pages_id, textAlign, type, x, y, z
	*/
	function validate_data()
	{
		// Check the basic data - then filter the rest later
		// filter main text
		$post_data = $this->input->post(NULL, TRUE); // return all post data filtered XSS - SCRIPT SAFE
		if (array_key_exists('description', $post_data))
		{
			$description = $post_data['description'];
			$description = htmlspecialchars($description, ENT_QUOTES);
			
			$this->data['description'] = $description;
		}
		
		if (array_key_exists('contents', $post_data))
		{
			$contents = $post_data['contents'];
			$contents = htmlspecialchars($contents, ENT_QUOTES);
			
			$this->data['contents'] = $contents;
		}
		
		// check pages_id
		if (array_key_exists('pages_id', $post_data))
		{	
			$pages_id = $post_data['pages_id'];
			$this->data['pages_id'] = $pages_id;
		}else
		{
			// should probably check to see if a page exist with this id as well?
			$this->data_errors = "There was no page assigned to the element!";
			return false;
			exit;
		}
		
		if (array_key_exists('x', $post_data) && array_key_exists('y', $post_data))
		{
			$x = $post_data['x'];
			$y = $post_data['y'];
			
			echo $x . ' ' . $y;
			// check x and y are integers
			if (filter_var($x, FILTER_VALIDATE_INT) && filter_var($x, FILTER_VALIDATE_INT))
			{
				$this->data['x'] = $x;
				$this->data['y'] = $y;
			}else
			{
				$this->data_errors = "Position values are incorrect!";
				return false;
				exit;
			}
		}
		
		return true;
	}
	
	function add_element_to_database()
	{
		if (!$this->db->insert('elements', $this->data))
		{
			// should probably check to see if a page exist with this id as well?
			$this->data_errors = "There was an error adding element to the database";
			//delete file if there was one?
			// *** IMPORTANT *** 
			if ($file) remove_orthan_file();
			return false;
			exit;
		} 
   		return $this->db->insert_id();
	}
	
	function update_description($id, $description)
	{
		$data = array( 'description' => $description);

		$this->db->where('id', $id);
		$this->db->update('elements', $data); 
	}
	
	function update_contents($id, $contents)
	{
		$data = array( 'contents' => $contents);

		$this->db->where('id', $id);
		$this->db->update('elements', $data); 
	}
	
	// clean up your mess mr parker... no file left behind
	private function remove_orthan_file()
	{
		unlink('assets/' . $this->type . '/' . $filename);	
	}
	
	// 
	public function update_element()
    {
    	// get the post data
    	$post_data = $this->input->post();
    	print_r($post_data);
   		$id = $this->input->post('id');

		if ($this->input->post('contents'))
		{
			$this->load->model('Links_model');
			$this->load->model('Pages_model');
			
			$pages_title = $this->Pages_model->get_title($id);
			
			$this->Links_model->delete_links_by_element_id($id);
			
			$contents = $this->Links_model->process_links($post_data['contents'], $pages_title, $id);
		
			$post_data['contents'] = $contents;
		}
		
		$this->db->where('id', $id);
		
		$this->db->update('elements', $post_data); 
		return $this->db->affected_rows();
   	}
   
	public function return_description()
	{
		if (isset($this->data['description']))
		{
			return $this->data['description'];
		}else
		{
			return false;
		}
	}
	
	public function return_contents()
	{
		if (isset($this->data['contents']))
		{
			return $this->data['contents'];
		}else
		{
			return false;
		}
	}
	
	public function return_pages_id()
	{
		return $this->data['pages_id'];
	}
	
	public function delete($id)
	{
		$element = $this->get_element_by_id($id);
		// delete all links for this element
		$this->load->model('Links_model');
		$this->Links_model->delete_links_by_element_id($id);
		
		// create a new element in the deleted_elements **** make sure to add the old ID field
		unlink($element->id);
		$this->db->insert('deleted_elements', $element);
		
		// delete element
		$this->db->delete('elements', array('id' => $id)); 
		
		
	}
}