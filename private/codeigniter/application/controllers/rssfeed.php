<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rssfeed extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
		}

		function get_ars() 
		{
				// Load RSS Parser
				$this->load->library('rssparser');
				
				// Get 6 items from arstechnica
				$rss = $this->rssparser->set_feed_url('http://ucfmediacentre.co.uk/swarmtv/index.php/feed/')->set_cache_life(30)->getFeed(1);
				
				print_r($rss);
				
				/*foreach ($rss as $item)
				{
						echo "title = ".$item['title']."<br />";
						echo "description = ".$item['description']."<br />";
						echo "content:encoded = ".$item['content:encoded']."<br />";
				}*/
		}
}

/* End of file pages.php */
/* Location: ./application/controllers/pages.php */
