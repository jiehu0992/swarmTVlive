<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Swarmtv extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');
		redirect('/swarmtv/map', 'location');	
	}

	public function map()
	{
		$this->load->model('Links_model');
		$this->load->model('Pages_model');
		
		// get all pages
		$pages = $this->Pages_model->get_all_pages();
		
		for ($i = 0; $i < sizeof($pages); $i++)
		{
			//print_r($pages[$i]);
			$linked_pages = $this->Links_model->return_links_for_page($pages[$i]['title']);
			$pages[$i]['link_tree'] = $linked_pages;
		}
		
		$pages = json_encode($pages);
		
		$data['links'] = $pages;
		
		$this->load->view('swarm_home', $data);
	}
	
	public function stats()
	{
		//$this->load->view('welcome_message');
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
