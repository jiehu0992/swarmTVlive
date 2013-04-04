<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Player extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->helper('url');
		$this->load->model('Player_model');
		
		$video_id = $this->input->post('id')
		$element_details= $this->Player_model->get_element($video_id);
		$data['element_details'] = $element_details;
		
		/*$altText = $element_details['description'];
		$timeline = $element_details['timeline'];
		$in = $timeline->in;
		$out = $timeline->out;
		$filename=$element_details['filename'];
		$height=$element_details['height'];
		$width=$element_details['width'];*/
		
		// load view with data
		$this->load->view('player', $data);
		
	}
	
	
}

/* End of file pages.php */
/* Location: ./application/controllers/pages.php */