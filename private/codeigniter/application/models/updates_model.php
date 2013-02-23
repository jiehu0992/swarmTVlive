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
    function getUpdates($limit = NULL)  
    {  
        return $this->db->get('updates', $limit);  
    }  

}