<?php

class FormSanitizer {

	private $magic_quotes_on;
	private $do_not_sanitize = array('user_id', 'recaptcha_challenge_field', 'recaptcha_response_field');
	
	public $sanitize_filters = array(
	
		// User
		'group_id'       => 'integer',
		'username'       => 'string',
		'password'       => 'password',
		'password_c'     => 'password',
		'email'          => 'email',
		'email_c'        => 'email',
	
		// Personal Information
		'lastname'       => 'string',
		'firstname'      => 'string',
		'fathersname'    => 'string',
		'dob-date'       => 'integer',
	    'dob-month'      => 'integer',
	    'dob-year'       => 'integer',
	
		// Studies
		'entryyear'      => 'integer',
		'graduationyear' => 'integer',
	
		//Contact
		'address1'       => 'string',
		'address2'       => 'string',
		'postcode'       => 'string',
		'city'           => 'string',
		'country'        => 'integer',
	
		'phone_home'     => 'phone',
		'phone_mobile'   => 'phone',
	
		'im_msn'         => 'email',
		'im_xmpp'        => NULL,
		'im_skype'       => NULL,
		'website1'       => NULL,
		'website2'       => NULL,
		'website3'       => NULL,
		'sn_facebook'    => NULL,
		'sn_twitter'     => NULL,
		'sn_linkedin'    => NULL,
		'sn_google'      => NULL,
	
		// CAPTCHA
		'recaptcha_challenge_field' => NULL,
		'recaptcha_response_field'  => NULL
	);
	
	function __construct() {
		$this->magic_quotes_on = get_magic_quotes_gpc();
		
		// TODO: Let the user provide field-function pairings dynamically upon construction 
	}
	
	public function sanitize_integer($string) {
		$string = filter_var($string, FILTER_SANITIZE_NUMBER_INT);		// Removes everything except [0-9+-]
		return $string;
	}
	
	public function sanitize_string($string) {
		$string = trim($string);										// Removes whitespace from the beginning and end of string 
		$string = filter_var($string, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
																		// Removes most whitespace (except actual spaces) and low-ascii characters
		$string = magic_quotes_on ? stripslashes($string) : $string;	// Fixes backslash pollution because of magic_quotes
		
		return $string; // Must still be escaped upon database insertion, and html-escaped upon display
	}
	
	public function sanitize_string_lowercase($string) {
		$string = trim($string);										// Removes whitespace from the beginning and end of string 
		$string = filter_var($string, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
																		// Removes most whitespace (except actual spaces) and low-ascii characters
		$string = magic_quotes_on ? stripslashes($string) : $string;	// Fixes backslash pollution because of magic_quotes
		$string = strtolower($string);
		
		return $string; // Must still be escaped upon database insertion, and html-escaped upon display
	}
	
	public function sanitize_password($string) {
		$string = trim($string);										// Removes whitespace from the beginning and end of string 
		
		return $string;
	}
	
	public function sanitize_email($string) {
		$string = filter_var($string, FILTER_SANITIZE_EMAIL);			// Removes whitespace and forbidden characters
		$string = strtolower($string);									// Converts to lowercase
		$string = magic_quotes_on ? stripslashes($string) : $string;	// Fixes backslash pollution because of magic_quotes
		
		return $string; // Must still be escaped upon database insertion
	}
	
	public function sanitize_phone($string) {
		$string = str_replace("-", "", $string);						// Removes dashes from phone number 
		$string = filter_var($string, FILTER_SANITIZE_NUMBER_INT);		// Removes everything except numbers and '+' (international prefix)
		
		// TODO: Ugly hack -- Remove greek international call prefix
		$string = preg_replace("/^\+30[-]*/", "", $string);
		
		return $string;
	}

	public function sanitize($value, $key) {
		if ($this->sanitize_filters[$key] != NULL) {
			$callback = 'sanitize_'.$this->sanitize_filters[$key];
			return $this->$callback($value);
		}
		else {
			return $value;
		}
	}
}

?>