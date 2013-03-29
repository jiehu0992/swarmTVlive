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
	
	public function shortcodes()
	{
		
		$this->load->library('Shortcodes');
		$this->shortcodes->process_string("jhghj jklklj [[here::wewe]] kjhjkh kjhkjhjkhjh [[http://www.-\!ghj&=@+ghjg]] kljljkjh [[jk&hjkh ]]kjhkj[[kjhjk::jkhkj]] hjkghjg");
		
		
		$keys = array('internal', 'external');
		
		$links = $this->shortcodes->return_shortcodes_by_key($keys);
		//echo $internal_links[0]['shortcode']->getValue();
		
		foreach($links as $link)
		{
			$this->shortcodes->replaceShortCodeWithHTML($link['index'], '<a href="' . $link['shortcode']->getValue() . '"> ' . $link['shortcode']->getValue() . '</a>');
		}
		
		echo $this->shortcodes->getAdaptedString();
		
		//$this->load->view('test_page');
	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */