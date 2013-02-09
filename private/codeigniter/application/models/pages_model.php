<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages_model extends CI_Model {

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url');
    }
    
    function get_all_pages()
    {
    	$query = $this->db->get('pages');
		if ($query->num_rows() > 0)
		{ 
   			return $query->result_array();
   		}else
   		{
   			return false;
   		}
    }
    
    function get_all_page_links()
    {
    	$query = $this->db->get('pages');

		$listview = '';
		foreach ($query->result() as $row)
		{
    		$listview = $listview . '<a href="' . site_url("pages/view/" . $row->title ) . '">' . $row->title . '</a><br />';
		}
		return $listview;
    }
    
   function get_page($page_name)
   {
   		$result = $this->db->get_where('pages', array('title' =>$page_name), 1);
   		
   		if ($result->num_rows() > 0)
		{ 
   			return $result->row();
   		}else
   		{
   			return false;
   		}
   }
   
   function get_titles()
   {
   		$this->db->select('title');
		$query = $this->db->get('pages');
		$result = $query->result_array();
		return json_encode($result);
   }
   
   function get_title($id)
   {
   		$this->db->where('id', $id);
   		$this->db->select('title');
   		$query = $this->db->get('pages');
		
		if ($query->num_rows() > 0)
		{
		   $row = $query->row(); 
			return $row->title;
		}else
		{
			return null;
		}
   }
   
   public function insert_page($page_title)
   {
   		//$row = array('pages'=>'title','$page_title');
   		$data = array(
   			'title' => $page_title
   			);

		$this->db->insert('pages', $data); 
   		
   		return $this->db->insert_id();
   }
   
   public function update()
   {
   		$id = $this->input->post('id');
   		$description = $this->input->post('description');
   		$keywords = $this->input->post('keywords');
   		$public = $this->input->post('public');
   		
	   	$data = array(
               'description' => $description,
               'keywords' => $keywords,
               'public' => $public
            );

		$this->db->where('id', $id);
		$this->db->update('pages', $data); 
		return $this->db->affected_rows();
   }
}