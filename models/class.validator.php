<?php

class Validator {
	
	public $errors_global = array();
	public $errors_specific = array();

	public $validate_filters = array(
	
		// User
		'group_id'       => 'integer',
		'username'       => 'string_lowercase',
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
	
	public $check_optional = array(
	
		// User
		'group_id'       => true,
		'username'       => true,
		'password'       => true,
		'password_c'     => false,
		'email'          => true,
		'email_c'        => false,
	
		// Personal Information
		'lastname'       => true,
		'firstname'      => true,
		'fathersname'    => true,
		'dob-date'       => true,
	    'dob-month'      => true,
	    'dob-year'       => true,
	
		// Studies
		'entryyear'      => true,
		'graduationyear' => true,
	
		//Contact
		'address1'       => true,
		'address2'       => false,
		'postcode'       => true,
		'city'           => true,
		'country'        => true,
	
		'phone_home'     => false,
		'phone_mobile'   => false,
	
		'im_msn'         => false,
		'im_xmpp'        => false,
		'im_skype'       => false,
		'website1'       => false,
		'website2'       => false,
		'website3'       => false,
		'sn_facebook'    => false,
		'sn_twitter'     => false,
		'sn_linkedin'    => false,
		'sn_google'      => false,
	
		// CAPTCHA
		'recaptcha_challenge_field' => false,
		'recaptcha_response_field'  => false
	);
	
	// Insert an error message into the $errors_global array
	public function set_global_error($msg) {
		array_push($this->errors_global, $msg); 
	}
	
	// Insert an error message into the $errors_global array
	public function set_specific_error($field, $msg) {
		$this->errors_specific['$field'] = $msg; 
	}
	
	public function validate_username($value) {
		
		if (minMaxRange(4,16,$value)) {
			$this->set_specific_error('username',lang("ACCOUNT_USER_CHAR_LIMIT",array(5,25)));
		}
		else if (usernameExists($username)) {
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

		

	}
	
	public function validate_password($value) {
		if (minMaxRange(4,16,$value)) {
			$this->set_specific_error('password',lang("ACCOUNT_PASS_CHAR_LIMIT",array(4,16)));
		}
		
		// Remeber password for later confirmation
		$this->confirmation_fields['password'] = $value;
	}
	
	public function validate_password_c($value) {
		if (!($this->confirmation_fields['password'] === $value)) {
			$this->set_specific_error('password',lang("ACCOUNT_PASS_MISMATCH"));
		}
		
		// Remove password from confirmation array
		unset($this->confirmation_fields['password']);
	}
	
	public function validate_email($value) {
		if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array(
		  'regexp' => '/^[a-z0-9._%+-]+@(?:[a-z0-9-]+\.)+([a-z]{2}|com|net|org|edu|gov|mil|tel|biz|info|name|mobi|asia)$/'))) === false) {
			$this->set_specific_error('password',lang("ACCOUNT_INVALID_EMAIL"));
		}
		else if (emailExists($value)) {
			$this->set_specific_error('password',lang("ACCOUNT_EMAIL_TAKEN"));
		}
		
		// Remeber password for later confirmation
		$this->confirmation_fields['email'] = $value;
	}
	
	public function validate_email_c($value) {
		if (!($this->confirmation_fields['email'] === $value)) {
			$this->set_specific_error('email',lang("ACCOUNT_MAIL_MISMATCH"));
		}
		
		// Remove password from confirmation array
		unset($this->confirmation_fields['email']);
	}
	
	public function validate_phone_home($value) {
		if(filter_var($value, FILTER_VALIDATE_INT) === false) {
			$this->set_specific_error('phone_home',lang("ACCOUNT_PHONE_FORMAT_ERROR"));
		}
		// Extra validation rules for Greece
		else if ($this->confirmation_fields['country'] == 0) {
			if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array( 'regexp' => '/^2[1-9][0-9]{8}$/'))) === false) {
				$this->set_specific_error('phone_home',lang("ACCOUNT_PHONE_COUNTRY_ERROR"));
			}
		}
	}
	
	public function validate($value, $key) {
		
		if ( $this->check_optional[$key] ) {
			if (empty($value)) {
				$this->set_specific_error($key,lang("REQUIRED_FIELD"));
			}
		}
		$callback = '$this->validate_'.$key;
		if (function_exists($callback)) {
			$callback($value);
		}
	}
}

?>