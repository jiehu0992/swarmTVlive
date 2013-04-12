<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SetThumbnail extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function currentPos($id, $currentPos)
	{		
		$this->load->helper('url');
		$this->load->model('Elements_model');
		
		$record = $this->Elements_model->get_clip_details($id);
		
		$filename = $record->filename;
		$filename = substr($filename, 0, -4);
		
		//Jem's local URLs
		//$videoDirectory = "/Users/media/Sites/swarmTVlive/www/swarmtv/assets/video/";
		//$videopostersDirectory = "/Users/media/Sites/swarmTVlive/www/swarmtv/assets/videoposters/";
		
		//public server's URLs
		$videoDirectory = "/var/www/swarmtv/assets/video/";
		$videopostersDirectory = "/var/www/swarmtv/assets/videoposters/";
		
		//create Terminal string for ffmpeg and execute it
		$makeFrameString = "/usr/local/bin/ffmpeg -i " . $videoDirectory . $filename . ".mp4";
		$makeFrameString = $makeFrameString . " -vframes 1 -an -s 200x115 -ss " . $currentPos . " ";
		$makeFrameString = $makeFrameString . $videopostersDirectory . $filename . ".jpg";
		echo "makeFrameString = ".$makeFrameString;
		$execute = shell_exec($makeFrameString);
		
	}
}

?>
