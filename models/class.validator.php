<?php

class Validator {
	
	private $post;
	public $errors_global;
	public $errors_specific;

	public static $check_required = array(
	
		// User
		'group_id'		=> true,
		'username'		=> true,
		'password'		=> true,
		'password_c'	=> false,
		'email'			=> true,
		'email_c'		=> false,
		'year'			=> true,
	
		// Personal Information
		'lastname'		=> true,
		'firstname'		=> true,
		'fathersname'	=> true,
		'dobdate'		=> true,
		'dobmonth'		=> true,
		'dobyear'		=> true,
	
		// Studies
		'entryyear'		=> true,
		'graduationyear'=> true,
	
		//Contact
		'address1'		=> true,
		'address2'		=> false,
		'postcode'		=> true,
		'city'			=> true,
		'country'		=> true,
	
		'phone_home'	=> false,
		'phone_mobile'	=> false,
	
		'im_msn'		=> false,
		'im_xmpp'		=> false,
		'im_skype'		=> false,
		'website1'		=> false,
		'website2'		=> false,
		'website3'		=> false,
		'sn_facebook'	=> false,
		'sn_twitter'	=> false,
		'sn_linkedin'	=> false,
		'sn_google'		=> false,
	
		// CAPTCHA
		'recaptcha_challenge_field' => false,
		'recaptcha_response_field'  => false,
	
		// Signatures
		'signature_deed' 			=> false,
		'signature_privacypolicy' 	=> false,
		'signature_termsofuse'		=> false
	);
	
	function __construct(&$post = NULL) {
		$this->post = &$post;
		$this->errors_global = array();
		$this->errors_specific = array();
	}
	
	// Insert an error message into the $errors_global array
	public function set_global_error($msg) {
		array_push($this->errors_global, $msg);
	}
	
	// Insert an error message into the $errors_specific array
	public function set_specific_error($field, $msg) {
		$this->errors_specific[$field] = $msg; 
	}
	
	public function validate_group_id ($value) {
		if ($value < 1 || $value > 3) {
			$this->set_specific_error('group_id',lang("GENERIC_FORM_FIELD_ERROR"));
		}
	}
	
	public function validate_entryyear ($value) {
		if ($this->post['group_id'] == 1 || $this->post['group_id'] == 2) {
			if ($value == 0)
				$this->set_specific_error('entryyear', lang("PLEASE_SELECT_ENTRY_YEAR"));
			else if ($value < $this->post['dobyear'] + 16 )
				$this->set_global_error(lang("DOB_OR_ENTRYYEAR_IS_WRONG"));
		}
	}
	
	public function validate_graduationyear ($value) {
		if ($this->post['group_id'] == 1)
		{
			if ($value == 0)
				$this->set_specific_error('graduationyear', lang("PLEASE_SELECT_GRADUATION_YEAR"));
			else if ($value < $this->post['entryyear'] + 3)
				$this->set_specific_error('graduationyear', lang("WHAT_ARE_YOU,_A_GENIUS?"));
		}
		else if ($this->post['group_id'] == 2)
		{
			
		}
	}
	
	public function validate_username($value) {
		if (minMaxRange(4,16,$value)) {
			$this->set_specific_error('username',lang("ACCOUNT_USER_CHAR_LIMIT",array(4,16)));
		}
		else if (usernameExists($value)) {
			$this->set_specific_error('username',lang("ACCOUNT_USERNAME_IN_USE",array($value)));
		}
	}
	
	public function validate_website($value) {
		$regex = "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
		$regex .= "(\:[0-9]{2,5})?"; // Port
		$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor
		
		$regex = "/^$regex$/";

		// TODO Check against regex 

	}
	
	public function validate_password($value) {
		if (minMaxRange(4,16,$value)) {
			$this->set_specific_error('password',lang("ACCOUNT_PASS_CHAR_LIMIT",array(4,16)));
		}
	}
	
	public function validate_password_c($value) {
		if (!($this->post['password'] === $value)) {
			$this->set_specific_error('password_c',lang("ACCOUNT_PASS_MISMATCH"));
		}
	}
	
	public function validate_email($value) {
		if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array(
		  'regexp' => '/^[a-z0-9._%+-]+@(?:[a-z0-9-]+\.)+([a-z]{2}|com|net|org|edu|gov|mil|tel|biz|info|name|mobi|asia)$/'))) === false) {
			$this->set_specific_error('password',lang("ACCOUNT_INVALID_EMAIL"));
		}
		else if (emailExists($value)) {
			$this->set_specific_error('email',lang("ACCOUNT_EMAIL_IN_USE", array($value)));
		}
	}
	
	public function validate_email_c($value) {
		if (!($this->post['email'] === $value)) {
			$this->set_specific_error('email_c',lang("ACCOUNT_MAIL_MISMATCH"));
		}
	}
	
	public function validate_postcode($value) {
		if ($this->post['country'] == 1) {
			if (strlen($value) != 5) {
				$this->set_specific_error('postcode', lang(ACCOUNT_POSTCODE_ERROR));
			}
		}
	}
	
	public function validate_phone_home($value) {
		if (!empty($value)) {
			if ($this->post['country'] == 1) {
				if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array( 'regexp' => '/^2[1-9][0-9]{8}$/'))) == false) {
					$this->set_specific_error('phone_home',lang("ACCOUNT_PHONE_COUNTRY_ERROR"));
				}
			}
		}
	}
	
	public function validate_phone_mobile($value) {
		if (!empty($value)) {
			if ($this->post['country'] == 1) {
				if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array( 'regexp' => '/^69[3-9][0-9]{7}$/'))) == false) {
					$this->set_specific_error('phone_mobile',lang("ACCOUNT_PHONE_COUNTRY_ERROR"));
				}
			}
		}
		else 
		{
			if (empty($this->post['phone_mobile']) && empty($this->post['phone_home']))
				$this->set_specific_error('phone_mobile', lang("AT_LEAST_ONE_PHONE_NUMBER"));
		}
	}
	
	// TODO Validators for web profiles and instant messengers
	// Google id looks like this: http://www.google.com/profiles/106237828597073414736
	// Google talk id looks like this: chris.pirillo@gmail.com
	
	public function validate_dobyear($value) {
		if ($value < 1950 || $value > (int)date("Y") - 16 || !checkdate((int)$this->post['dobmonth'], (int)$this->post['dobdate'], (int)$this->post['dobyear'])) 
			$this->set_specific_error('dobyear', lang("ACCOUNT_INVALID_DATE_OF_BIRTH"));
	}
	
	public function validate($value, $key) {
		
		if ( Validator::$check_required[$key] ) {
			if (empty($value)) {
				$this->set_specific_error($key,lang("REQUIRED_FIELD"));
			}
		}
		$callback = 'validate_'.$key;
		if (method_exists($this, $callback)) {
			$this->$callback($value);
		}
		
		
	}
}

?>