<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/

	//General Settings
	//--------------------------------------------------------------------------
	
	//Database Information
	$dbtype = "mysql"; 
	$db_host = "localhost";
	$db_user = "skarab_usercake";
	$db_pass = "skarab_usercake";
	$db_name = "skarab_usercake";
	$db_port = "";
	$db_table_prefix = "usercake_";

	$langauge = "en";
	
	//Generic website variables
	$websiteName = "DIT Alumni Club Membership Management";
	$websiteUrl = "http://skarab.co.cc/usercake/"; //including trailing slash

	//Do you wish UserCake to send out emails for confirmation of registration?
	//We recommend this be set to true to prevent spam bots.
	//False = instant activation
	//If this variable is falses the resend-activation file not work.
	$emailActivation = true;

	//In hours, how long before UserCake will allow a user to request another account activation email
	//Set to 0 to remove threshold
	$resend_activation_threshold = 0;
	
	//Tagged onto our outgoing emails
	$emailAddress = "alumniclub@di.uoa.gr";
	
	//Date format used on email's
	$emailDate = date("d-m-Y"); /*date("l \\t\h\e jS");*/
	
	//Directory where txt files are stored for the email templates.
	$mail_templates_dir = "models/mail-templates/";
	
	$default_hooks = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
	$default_replace = array($websiteName,$websiteUrl,$emailDate);
	
	//Display explicit error messages?
	$debug_mode = false;
	
	//---------------------------------------------------------------------------
?>