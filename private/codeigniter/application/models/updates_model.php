<?php
class Updates_model extends CI_Model {  
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('xml');
    }
    
    // get all postings  
    function getUpdates($limit = 10)  
    {  
        $this->db->order_by('pubDate', 'desc');
		return $this->db->get('updates', $limit);  
    }
	
	
	//return all the links from the current RSS feed
	function get_links_from_RSS()
	{

		$this->db->distinct();
		$this->db->select('page AS pagesTitle');
		$this->db->order_by("pubDate", "desc");
		$this->db->limit(50);
		$query = $this->db->get('updates');
		$result = $query->result_array();
		return $result;
	}

}