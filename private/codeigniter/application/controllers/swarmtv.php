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
		$this->load->model('Updates_model');
		$filter = $this->input->get('filter');
		
		// get pages that have something to do with the filter specified
		$pages = $this->Pages_model->get_filtered_pages($filter);

		if ($pages === false) {
			//if none are found, then retrieve all the pages
			$filter = "";
			$pages = array();
			$numberOfResults= 0;
		}
		
		$numberOfResults= count($pages);
		$data['searchResults'] = $numberOfResults." pages found";
		
		for ($i = 0; $i < sizeof($pages); $i++) {
			//create a list of links that come from these pages
			$linked_pages = $this->Links_model->return_links_for_page($pages[$i]['title']);
			
			$pages[$i]['link_tree'] = $linked_pages;
			
		}
		
		//add in Recent Changes
		/*array_push($pages, array('title'=>'Recent Changes'));
		//create articiial link tree for Recent Chanages
		$recentChangesPages = $this->Updates_model->get_links_from_RSS();
		$pages[sizeof($pages)-1]['link_tree'] = $recentChangesPages;
		//add in Stream Page
		array_push($pages, array('title'=>'Stream'));
		$streamPages = array(array("pagesTitle" => "home"));
		$pages[sizeof($pages)-1]['link_tree'] = $streamPages;*/
		//echo var_dump($pages);
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