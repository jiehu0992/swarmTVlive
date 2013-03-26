<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shortcodes_model extends CI_Model {

	function __construct()
	{
	    // Call the Model constructor
	    parent::__construct();
	    
	    $this->load->database();
	    $this->load->helper('url');
	}
    
	
	function process_text($string)
	{
		$pattern = "/(?<=\[\[)[\w :]+(?=\]\])/"; // [[ ... ]] regex
		$short_code_info = null;
			
		// find all the links in $string
		// execute the regex on string and populate $links array storing the offset with the match
		preg_match_all ( $pattern, $string, $short_code_info, PREG_OFFSET_CAPTURE );
	
		$short_codes = new Short_codes();
		
		// loop through the results of the regex and process shortcodes
		foreach($short_code_info[0] as $info)
		{
			// GENERAL DETAILS
			$length = strlen($info[0]);
			$start 	= $info[1];
			$end 	= $info[1] + $length;
			
			// KEY & VALUE
			$key_val_string = substr($string, $start, $length);
			$key_val_pair 	= explode('::',$key_val_string);
			
			
			
		}
	}
}

class Short_codes {
	
	public function add_short_code($short_code)
	{
		
	}
}


class Short_code
{
    // property declaration
    private $length = null;
    private $start = null;
    private $end = null;
    private $key = null;
    private $value = null;
    
    
    
}