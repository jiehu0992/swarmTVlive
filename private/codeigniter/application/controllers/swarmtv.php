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
		$filter = $this->input->get('filter');
		
		// get filtered pages
		$pages = $this->Pages_model->get_filtered_pages($filter);
		
		
		for ($i = 0; $i < sizeof($pages); $i++)
		{
			$linked_pages = $this->Links_model->return_links_for_page($pages[$i]['title']);
			/*$filtered_linked_pages = array();
			for ($j = 0; $j < sizeof($linked_pages); $j++) {
			  if ($filter ==""){
				$filtered_linked_pages[] = $linked_pages[$j];
			  } else {
				if (strpos($linked_pages[$j]['pagesTitle'], $filter)>0){
				  $filtered_linked_pages[] = $linked_pages[$j];
				}
			  }
			}
			$pages[$i]['link_tree'] = $filtered_linked_pages;*/
			$pages[$i]['link_tree'] = $linked_pages;
		}
		
		
		$pages = json_encode($pages);
		
		$data['links'] = $pages;
		$data['filter'] = $filter;
		
		$this->load->view('swarm_home', $data);
	}
	
	public function stats()
	{
		//$this->load->view('welcome_message');
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
