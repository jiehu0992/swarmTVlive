<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Shortcodes class
 *
 * @package	SwarmTv
 * @subpackage	Libraries
 * @category	shortcodes
 * @author	Alcwyn Parker
 * @link	http://www.alcwynparker.co.uk
 */

class Shortcodes{

	private $shortcodes = null;
	
	private $accepted_keys = array('internal', 'external', 'youtube', 'vimeo');

	private $accepted_styling = array('b','i','u');

	/**
	 * Shortcodes Constructor
	 *
	 * Instantiate the array to store shortcodes in
	 * 
	 */
	function __construct()
	{
		$this->shortcodes = array();
	}
	// --------------------------------------------------------------------
	// PUBLIC METHODS
	// --------------------------------------------------------------------

	/**
	 * Break up the raw string into shortcodes and save them to list
	 *
	 * @access	public
	 * @return	null
	 */
	public function process_string($string)
	{
		$pattern = "/(?<=\[\[)[\w \!\?\&\+=@:\/\.\\\-]+(?=\]\])/"; // [[ ... ]] regex
		$short_code_info = null;
			
		// find all the links in $string
		// execute the regex on string and populate $links array storing the offset with the match
		preg_match_all ( $pattern, $string, $short_code_info, PREG_OFFSET_CAPTURE );
	
		// loop through the results of the regex and process shortcodes
		foreach($short_code_info[0] as $info)
		{
			$sc = new Shortcode($info[0], $info[1]);
			$this->add_short_code($sc);
		}
	}
	
	
	// --------------------------------------------------------------------

	/**
	 * returns the total number of short codes in the string
	 *
	 * @access	public
	 * @return	int
	 */
	public function length()
	{
		return (sizeof($this->shortcodes));
	}
	
	// --------------------------------------------------------------------

	/**
	 * returns an array of shortcodes by their type
	 *
	 * @access	public
	 * @return	array of shortcodes
	 */
	public function return_shortcodes_by_key($type)
	{
		$short_codes_by_type = array();
		
		foreach($this->shortcodes as $sc)
		{
			if ($sc->getKey() === 'internal')
			{
				array_push($short_codes_by_type, $sc);
			}
		}
		return ($short_codes_by_type);
	}
	
	// --------------------------------------------------------------------
	// PRIVATE METHODS
	// --------------------------------------------------------------------
	
	/**
	 * Fetch the current session data if it exists
	 *
	 * @access	private
	 * @return	null
	 */
	private function add_short_code($short_code)
	{
		array_push($this->shortcodes, $short_code);
	}
	
}


/**
 * Shortcode class
 *
 * @package	SwarmTv
 * @subpackage	Libraries
 * @category	shortcodes
 * @author	Alcwyn Parker
 * @link	http://www.alcwynparker.co.uk
 */

class Shortcode
{
	// property declaration
	private $raw = null;
	private $length = null;
	private $start = null;
	private $end = null;
	private $key = '';
	private $value = '';
	
	/**
	 * Shortcodes Constructor
	 *
	 * Initiates the starting parameters and kicks of the process of breaking
	 * the shortcode into its constituents. 
	 */
	function __construct($raw, $start )
	{
	    $this->raw = $raw;
	    $this->start = $start;
	    
	    $this->process_raw_shortcode();
	}
	
	
	// --------------------------------------------------------------------
	// PRIVATE METHODS
	// --------------------------------------------------------------------
	
	/**
	 * find out more info about the shortcode and break it apart
	 *
	 * @access	private
	 * @return	null
	 */
	private function process_raw_shortcode()
	{
	    // GENERAL DETAILS
	    $this->length 	= strlen($this->raw);
	    $this->end	= $this->start + $this->length;
	    
	    // KEY & VALUE
	    $key_val_pair 	= explode('::', $this->raw);
	    
	    // check to see if both key and value have been given
	    if (sizeof($key_val_pair) > 1)
	    {
		    $this->key = $key_val_pair[0];
		    $this->value = $key_val_pair[1];	
	    }else{
		    $http = strpos($this->raw, 'http');
		    
		    if($http === false){
			    $this->key = 'internal';
		    }else{
			    $this->key = 'external';
		    }
		    $this->value = $this->raw;
	    }
	} 
	
	// --------------------------------------------------------------------
	// GETTERS & SETTERS
	// --------------------------------------------------------------------
	
	public function getKey()
	{
		return $this->key;
	}
	
	public function getValue()
	{
		return $this->value;
	}
}