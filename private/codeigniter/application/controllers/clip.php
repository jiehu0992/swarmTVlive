<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clip extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function play($id, $width, $height)
	{		
		$this->load->helper('url');
		$this->load->model('Elements_model');
		
		$record = $this->Elements_model->get_clip_details($id);
		
		$data['id'] = $record->id;
		$data['description'] = $record->description;
		$timelineJSON = $record->timeline;
		$timeline = json_decode($timelineJSON);
		if ($timeline !== NULL){
			$data['in'] = $timeline->in;
			$data['out'] = $timeline->out;
			$data['duration'] = $timeline->duration;
		} else {
			$data['in'] = 0;
			$data['out'] = "100";
			$data['duration'] = "100";
		}
		$data['filename']=substr($record->filename, 0 , -4);
		$data['width']=$record->width;
		$data['height']=$record->height;
		
		$this->load->view('clipPlayer_view', $data);
		
	}
	
	public function setThumbnail($id, $currentPos)
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
                
		//create new image size
		$sizeRatio = $record->width/$record->height;
		$newWidthString = intval($sizeRatio*115);
		$sizeString = $newWidthString."x115";
		
		//create Terminal string for ffmpeg and execute it
		$makeFrameString = "/usr/local/bin/ffmpeg -i " . $videoDirectory . $filename . ".mp4";
		$makeFrameString = $makeFrameString . " -vframes 1 -an -s ". $sizeString ." -ss " . $currentPos . " ";
		$makeFrameString = $makeFrameString . $videopostersDirectory . $filename . ".jpg &";
		echo "makeFrameString = ".$makeFrameString;
		$execute = shell_exec($makeFrameString);
		
		echo $execute;
	}
	
	
	public function update($id, $altText, $inPoint, $outPoint, $duration)
	{		
		$this->load->helper('url');
		$this->load->model('Elements_model');
		
		$timeline = array(
			"in" => $inPoint,
			"out" => $outPoint,
			"duration" => $duration
		);
		
		$timelineJSON = json_encode($timeline);
		
		$data['description']=$altText;
		$data['timeline']=$timelineJSON;
		
		$this->db->where('id', $id);
		$this->db->update('elements', $data); 
		
	}
}

?>