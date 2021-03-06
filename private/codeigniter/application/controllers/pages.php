<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	// initial testing to display page name
	public function index($page_name)
	{
	 	echo $page_name;
	}
	
	
	// set up the page in HTML
	public function view($group, $page_title = NULL)
	{
		$this->load->helper('url');
		
		//test to see if page requested is recent changes, if so go there
		if (strtoupper(urldecode($page_title)) === "RECENT CHANGES" | strtoupper ($page_title) === "RECENTCHANGES") {
			redirect('/'.$group.'/recentChanges', 'location');
		}
		
		if ($page_title === NULL){
				$page_title = $group;
				$group = "main";
				redirect('/pages/view/'.$group.'/'.$page_title, 'location');
		}
		
		// get the page information from the db.php
		$this->load->model('Pages_model');
		$page_details= $this->Pages_model->get_page($group, URLdecode($page_title));
		$data['page_info'] = $page_details;
		
		if($page_details) 
		{
			// get the page elements
			$this->load->model('Elements_model');
			$this->load->model('Links_model');
			
			$page_elements = $this->Elements_model->get_all_elements($page_details->id);
			$data['page_elements'] = $page_elements;
			
			// load view with data
			$this->load->view('header', $data);
			$this->load->view('pages_view/page_view');
			$this->load->view('pages_view/page_element_form');
			$this->load->view('pages_view/page_info_form');
			$this->load->view('pages_view/page_view_scripts');
			$this->load->view('footer');
		}else
		{
			//Page was not found, so create a new one
			$page_id=$this->Pages_model->insert_page($group, $page_title);
			redirect('/pages/view/'.$group.'/'.$page_title, 'location');
			
		}
	}
	
	// updates the page_info and returns "1" if successful
	public function update()
	{
		$this->load->model('Pages_model');
		return $this->Pages_model->update();
		//redirect('/pages/view/'.$group.'/'.$page_title, 'location');
	}
	
	// displays success on uploading an image and the image name
	public function upload_image()
	{
		echo '{"success":true, "name": "' . $_GET['name'] . '"}';
	}
	
}


/* End of file pages.php */
/* Location: ./application/controllers/pages.php */
