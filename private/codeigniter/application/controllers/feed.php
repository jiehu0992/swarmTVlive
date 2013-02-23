<?php
class Feed extends CI_Controller {

    public function __construct()
    {
	parent::__construct();
	$this->load->helper('xml');  
        $this->load->helper('text'); 
	$this->load->model('updates_model', 'updates');
    }

    public function index(){  
        $data['feed_name'] = 'ucfmediacentre.co.uk/swarmtv'; // your website  
        $data['encoding'] = 'utf-8'; // the encoding  
	$data['feed_url'] = 'ucfmediacentre.co.uk/swarmtv/feed'; // the url to your feed  
	$data['page_description'] = 'A website that encourages collaborative thinking'; // some description  
	$data['page_language'] = 'en-en'; // the language  
	$data['creator_email'] = 'ucfmediacentre@gmail.com'; // your email  
	$data['updates'] = $this->updates->getUpdates(3);  
	header("Content-Type: application/rss+xml"); // important!   
	$this->load->view('rss', $data);   
    }
}


