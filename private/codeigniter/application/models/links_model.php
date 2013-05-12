<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Links_model extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url');
		$this->load->library('Shortcodes');
    }
	
	// saves the new links to the database
	function add_links($link_info, $page_title, $element_id)
	{
		$this->load->library('Shortcodes');
		// adds a new key with array to link info
		$link_info['replace'] = array();
		// loop through each link
		for ($i = 0; $i < sizeof($link_info['links']); $i++)
		{
			// compile data
			$data = array(
  				'linkTitle' =>  $link_info['links'][$i][0],
   				'elementsId' => $element_id,
   				'pageTitle' => $page_title
			);
			// add to the database
			if($this->db->insert('links', $data))
			{
				// assign the new id to the link
				array_push($link_info['replace'], $this->db->insert_id());
			}
		}
		// return info array updated with each link id
		return ($link_info);
	}
	
	// recreates the content with the link ids instead of the page titles 
	function replace_titles_with_insert_ids($link_info)
	{
		// put the first part of the content in
		$content = $link_info['parts'][0];
		
		// loop through the links adding the link id then the next part
		for ($i = 0; $i < sizeof($link_info['links']); $i++)
		{
			$content = $content . $link_info['replace'][$i] . $link_info['parts'][$i+1];
		}
	}
	
	// swaps the link Titles for link ids from the `links` table
	function process_codes($string, $forWhat, $pages_title, $elements_id)
	{
		
		$this->load->library('Shortcodes');
		// creates an object with all the details about any shortcodes in the specified string
		$linksObj = $this->shortcodes->process_string($string);
		
		// compiles the common data string
		$data = array(
			'elementsId' => $elements_id,
			'pageTitle' => $pages_title
		);
		
		$i=0;
		foreach($linksObj as $link)
        {
				switch ($forWhat){
						case "forDb":
								switch ($link->getKey()) {
										case "internal":
												
												$data["linkTitle"] = $link->getValue();
												
												// adds the link details to the database if the shortcode is a link
												if($this->db->insert('links', $data))
												{
														// replaces the link title with the replacement code
														$this->shortcodes->replaceShortCode($i, "[[".$this->db->insert_id()."]]");
												}
												
												break;
										case "external":
												
												$data["linkTitle"] = $link->getValue();
												
												$data["linkTitle"] = $link->getValue();
												// adds the link details to the database if the shortcode is a link
												if($this->db->insert('links', $data))
												{
														// replaces the link title with the replacement code
														$this->shortcodes->replaceShortCode($i, "[[".$this->db->insert_id()."]]");
												}
												
												break;
								}
								break;
						case "forWeb":
								switch ($link->getKey()) {
										case "internal":
												
												// gets the linkTitle from the stored link id
												$linkDetails = $this->get_link_by_id($link->getValue());
												
												$linkTitle = $linkDetails->linkTitle;
												//$linkTitle = $link->getValue();
												// replaces the link id with replacement code
												$this->shortcodes->replaceShortCode($i, '<a href="' . $linkTitle . '">' . $linkTitle . '</a>');
												break;
										case "external":
												
												// gets the linkTitle from the stored link id
												$linkDetails = $this->get_link_by_id($link->getValue());
												
												$linkTitle = $linkDetails->linkTitle;
												//$linkTitle = $link->getValue();
												// replaces the link id with replacement code
												$this->shortcodes->replaceShortCode($i, '<a href="http://' . $linkTitle . '">' . $linkTitle . '</a>');
												break;
								}
								break;
						case "forEditing":
								switch ($link->getKey()) {
										case "internal":
												// gets the linkTitle from the stored link id
												$linkDetails = $this->get_link_by_id($link->getValue());
												$linkTitle = $linkDetails->linkTitle;
												// replaces the link id with replacement code
												$this->shortcodes->replaceShortCode($i, '[[' . $linkTitle . ']]');
												break;
										case "external":
												// gets the linkTitle from the stored link id
												$linkDetails = $this->get_link_by_id($link->getValue());
												$linkTitle = $linkDetails->linkTitle;
												// replaces the link id with replacement code
												$this->shortcodes->replaceShortCode($i, '[[http://' . $linkTitle . ']]');
												break;
								}
								break;
				}
				$i++;
        }
		
		return ($this->shortcodes->getAdaptedString());
	}
	
	// gets all the details from the `links` table with a specific id
	function get_link_by_id($id)
	{
		$query = $this->db->get_where('links', array('id' => $id));
		if ($query->num_rows() > 0)
		{
   			$row = $query->row(); 
   			return $row;
		}else
		{
			return false;
		}	
	}
	
	// outputs all of the links as json
	function get_links()
	{
		$query = $this->db->get('links');
		$results = $query->result_array();
		$results = json_encode($results);
		return $results;
	}
	
	// gets only unique page titles from links
	function get_unique_page_titles()
	{
		$query = $this->db->query('SELECT DISTINCT linkTitle FROM links');
		$results = $query->result_array();
		$results = json_encode($results);
		return $results;
	}
	
	// return all the links for specific page
	function return_links_for_page($page_title)
	{
		$this->db->select('linkTitle');
		$this->db->where('UPPER(pageTitle)', strtoupper($page_title));
		$query = $this->db->get('links');
		$result = $query->result_array();
		return $result;
	}
	
	// deletes the element with specific id from the `links` table
	function delete_links_by_element_id($elements_id)
	{
		$this->db->delete('links', array('elementsId' => $elements_id)); 
		
	}
}
