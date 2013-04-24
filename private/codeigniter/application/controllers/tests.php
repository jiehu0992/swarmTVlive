<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tests extends CI_Controller {

	public function index()
	{
		$this->load->model('Links_model');
		$this->load->model('Pages_model');
		
		print_r ($this->Pages_model->get_title(36));
		echo ("boom");
	}
	
	public function stats()
	{
		//$this->load->view('welcome_message');
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */