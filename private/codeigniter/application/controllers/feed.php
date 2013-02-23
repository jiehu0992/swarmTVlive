<?php
class Feed extends CI_Controller {  
    
    public function __construct()
    {
	parent::__construct();
    }
    
    function Feed()  
    {  
	parent::Controller();  
	$this->load->helper('xml');  
	$this->load->helper('text');  
	$this->load->model('updates_model', 'updates');  
    }
    
    function index()  
    {	
	 
	$this->load->helper('xml');  
	$this->load->helper('text');  
	$this->load->model('updates_model', 'updates');
	
	$data['feed_name'] = 'ucfmediacentre.co.uk/swarmtv';  
	$data['encoding'] = 'utf-8';  
	$data['feed_url'] = 'http://www.ucfmediacentre.co.uk/swarmtv/feed';  
	$data['page_description'] = 'Swarm TV Recent Changes';  
	$data['page_language'] = 'en-en';  
	$data['creator_email'] = 'ucfmediacentre.co.uk@gmail.com';  
	$data['updates'] = $this->updates->getUpdates(10);  
	header("Content-Type: application/rss+xml");  
	$this->load->view('rss', $data);  
    }  
}  
