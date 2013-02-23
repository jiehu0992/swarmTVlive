<?php
class Updates_model extends CI_Model {

    public function __construct()
    {
	    $this->load->database();
    }
    
    public function get_updates($page = FALSE)
    {
	if ($page === FALSE)
	{
	    $this->db->limit(2);
	    $this->db->order_by("pubDate", "desc");
	    $query = $this->db->get('updates');
	    return $query->result_array();
	}
	
	$this->db->limit(2);
	$this->db->order_by("pubDate", "desc"); 
	$query = $this->db->get_where('updates', array('page' => $page));
	return $query->row_array();
    }
    
    function getUpdates($limit = NULL)  
    {  
        return $this->db->get('updates', $limit);  
    }
}
