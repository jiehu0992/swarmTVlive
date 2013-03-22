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

}