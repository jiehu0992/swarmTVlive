<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages_model extends CI_Model {

    //makes sure all functions load the database and url helper
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url');
    }
    
    // returns the whole of the `pages` as an array
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
    
    // returns all the page names in the site as a list of hyperlinks
	function get_all_page_links()
    {
    	$query = $this->db->get('pages');

		$listview = '';
		foreach ($query->result() as $row)
		{
    		$listview = $listview . '<a href="' . base_url("pages/view/" . $row->title ) . '">' . $row->title . '</a><br />';
		}
		return $listview;
    }
    
   // gets all the details of a specified page
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
   
   // gets all the pages in an array that have something to do with a specified string
   function get_filtered_pages($string)
   {
		if ($string != "") {
			//build up SQL statement that finds any page title that has something to do with the filtered string, including links!
			$sql = "SELECT DISTINCT pages.title ";
			$sql = $sql . "FROM pages ";
			$sql = $sql . "LEFT JOIN links ";
			$sql = $sql . "ON pages.title=links.pageTitle ";
			$sql = $sql . "LEFT JOIN elements ";
			$sql = $sql . "ON pages.id=elements.pages_id ";
			$sql = $sql . "WHERE (CONVERT(elements.description USING utf8) LIKE '%" . $string ."%' ";
			$sql = $sql . "OR CONVERT(elements.contents USING utf8) LIKE '%" . $string ."%' ";
			$sql = $sql . "OR CONVERT(elements.keywords USING utf8) LIKE '%" . $string ."%' ";
			$sql = $sql . "OR CONVERT(links.linkTitle USING utf8) LIKE '%" . $string ."%' ";
			$sql = $sql . "OR CONVERT(pages.description USING utf8) LIKE '%" . $string ."%' ";
			$sql = $sql . "OR CONVERT(pages.keywords USING utf8) LIKE '%" . $string ."%' ";
			$sql = $sql . "OR CONVERT(pages.title USING utf8) LIKE '%" . $string ."%')";
			
		} else {
			$sql = "SELECT pages.title FROM pages";
		}
		
   		$result = $this->db->query($sql);
   		
   		if ($result->num_rows() > 0)
		{ 
   			return $result->result_array();
   		}else
   		{
   			return false;
   		}
   }
   
   // returns a json array of all details to do with a specified page
   function get_titles()
   {
   		$this->db->select('title');
		$query = $this->db->get('pages');
		$result = $query->result_array();
		return json_encode($result);
   }
   
   // gets the page title of a page with a specified id
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
   
   // creates a page with a specified title
   public function insert_page($page_title)
   {
   		//$row = array('pages'=>'title','$page_title');
   		$data = array(
   			'title' => URLdecode($page_title)
   			);

		$this->db->insert('pages', $data); 
   		
   		return $this->db->insert_id();
   }
   
   // updates page_info in the `pages` table & returns "1" if successful
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