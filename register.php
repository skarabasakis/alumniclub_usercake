<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	require_once("libs/recaptchalib.php");
	
	//Prevent the user visiting the logged in page if he/she is already logged in
	if(isUserLoggedIn()) { header("Location: account.php"); die(); }
?>

<?php
	/* 
		Below is a very simple example of how to process a new user.
		 Some simple validation (ideally more is needed).
		
		The first goal is to check for empty / null data, to reduce workload here
		we let the user class perform it's own internal checks, just in case they are missed.
	*/

//Forms posted
	if(!empty($_POST))
	{
		/*$errors = array();
		$email = trim($_POST["email"]);
		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]);
		$confirm_pass = trim($_POST["passwordc"]);*/
		
		// Sanitize inputs
		$s = new FormSanitizer();
		array_walk($_POST, '$s->sanitize');
		
		// Validate inputs
		$v = new Validator();
		array_walk($_POST, '$v->validate');
	
		//Perform some validation
		//Feel free to edit / change as required
		
		// Perform validation on the CAPTCHA before everything else
		$resp = recaptcha_check_answer ($recaptcha_privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
			$errors[] = lang("CAPTCHA_ERROR");
		}
		else {
		
			//End data validation
			if(count($v->errors_global) + $count($v->errors_specific) == 0)
			{	
				//Construct a user object
				$user = new User($username,$password,$email);
				
				//Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
				if(!$user->userCakeAddUser())
				{
					if($user->mail_failure) $errors[] = lang("MAIL_ERROR");
					if($user->sql_failure)  $errors[] = lang("SQL_ERROR");
				}
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Αρχική Σελίδα - <?php echo $websiteName; ?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="form.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header">
	<div id="logo">&nbsp;
	</div>
	<div id="navi">
		<ul>
			<li><a href="login.php">σύνδεση</a></li>
			<li><a href="register.php">εγγραφή</a></li>
		</ul>
	</div>
</div>
<div id="wrapper">
	<div id="functions">
	</div>
	<div id="content">
	
		<!--
		<div id="left-nav">
		<?php /*include("layout_inc/left-nav.php");*/ ?>
		<div class="clear"></div>
		</div>
		-->
        
        <div id="main">
			
            <h1>Εγγραφή Νέου Μέλους</h1>

			<?php
            if(!empty($_POST))
            {
				if(count($errors) > 0)
				{
            ?>
            <div id="errors">
            <?php errorBlock($errors); ?>
            </div>     
            <?php
           		 } else {
          
            	$message = lang("ACCOUNT_REGISTRATION_COMPLETE_TYPE1");
        
            	if($emailActivation)
				{
               		 $message = lang("ACCOUNT_REGISTRATION_COMPLETE_TYPE2");
				}
	        ?> 
	        <div id="success">
	        
	           <p><?php echo $message ?></p>
	           
	        </div>
	        <? } }?>

            <div id="regbox">
                <form name="newUser" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                
                <fieldset id="membertype">
                	<legend>Τύπος Μέλους</legend>
                	
                	<div>
	                	<label for="membertype-regular">
	                		<input type="radio" name="group_id" id="membertype-regular" value="1" checked="checked" />
	                		Τακτικό Μέλος - Ετήσια Συνδρομή: &euro;5
	                	</label>
                	</div>

					<div>
	                	<label for="membertype-juniot">
							<input type="radio" name="group_id" id="membertype-junior" value="2" disabled="disabled" />
							Δόκιμο μέλος - Χωρίς ετήσια συνδρομή
						</label>
					</div>
                	
                	<div>
	                	<label for="membertype-social">
	                	    <input type="radio" name="group_id" id="membertype-social" value="3" disabled="disabled" />
	                		Κοινωνικό Μέλος - Ετήσια Συνδρομή: &euro;5
	                	</label>
                	</div>
                </fieldset>
                
                <fieldset id="account">
                    <legend>Στοιχεία Λογαριασμού</legend>
                
	                <div>
	                    <label>Username:</label>
	                    <input type="text" name="username" />
	                </div>
	                
	                <div>
	                    <label>Password (x2):</label>
	                    <input type="password" name="password" />
	                    <input type="password" name="password_c" />
	                </div>
	                
	                <div>
	                    <label>Email:</label>
	                    <input type="text" name="email" />
	                </div>
                </fieldset>
                
                <fieldset id="personal">
                    <legend>Προσωπικά Στοιχεία</legend>
                
	                <div>
	                    <label>Επώνυμο:</label>
	                    <input type="text" name="lastname" />
	                </div>
	                
	                <div>
	                    <label>Όνομα:</label>
	                    <input type="text" name="firstname" />
	                </div>
	                
	                <div>
	                    <label>Όνομα Πατέρα:</label>
	                    <input type="text" name="fathersname" />
	                </div>
	                	                
	                <div>
	                    <label>Ημερομηνία Γέννησης:</label>
	                    <select name="dob-date">
	                    	<option value="0">&nbsp;</option>
	                    	<?php 
	                    		for ($i = 1; $i <= 31; $i++) {
	                    	?>
	                    	<option value="<?php echo $i; ?>"><?php echo $i;?></option>
	                    	<?php 
	                    		}
	                    	?>
	                    </select>
	                    <select name="dob-month">
	                    	<?php 
		                    	$month = array( '', 'Ιανουαρίου', 'Φεβρουαρίου', 'Μαρτίου',
		                    	                    'Απριλίου', 'Μαΐου', 'Ιουνίου',
		                    	                    'Ιουλίου', 'Αυγούστου', 'Σεπτεμβρίου',
		                    	                    'Οκτωβρίου', 'Νοεμβρίου', 'Δεκεμβρίου'); 
		                    	
		                    	foreach ($month as $i => $mname) {
	                    	?>
	                    	<option value="<?php echo $i; ?>"><?php echo $mname;?></option>
	                    	<?php 
	                    		}
	                    	?>
	                    </select>
	                    <select name="dob-year">
	                    	<option value="0">&nbsp;</option>
	                    	<?php 
	                    		$sixteen_years_ago = date("Y") - 16;
	                    		for ($i = 1950; $i <= $sixteen_years_ago; $i++) {
	                    	?>
	                    	<option value="<?php echo $i; ?>"><?php echo $i;?></option>
	                    	<?php 
	                    		}
	                    	?>
	                    </select>
	                </div>
	                	                
                </fieldset>
                
                <fieldset id="studies">
                	<legend>Σπουδές</legend>
                	
                	<div>
                		<label>Έτος εισαγωγής:</label>
						<select name="entryyear">
	                    	<option value="0">&nbsp;</option>
	                    	<?php 
	                    		$thisyear = date("Y");
	                    		for ($i = 1986; $i <= $thisyear; $i++) {
	                    	?>
	                    	<option value="<?php echo $i; ?>"><?php echo $i;?></option>
	                    	<?php 
	                    		}
	                    	?>
	                    </select>
	                </div>
	                
	                <div>
	                    <label>Έτος αποφοίτησης:</label>
						<select name="graduationyear">
	                    	<option value="0">&nbsp;</option>
	                    	<?php 
	                    		for ($i = 1986; $i <= $thisyear; $i++) {
	                    	?>
	                    	<option value="<?php echo $i; ?>"><?php echo $i;?></option>
	                    	<?php 
	                    		}
	                    	?>
	                    </select>
	                </div>
                </fieldset>
                
                <fieldset id="contact">
                	<legend>Στοιχεία Επικοινωνίας</legend>
                	
	                <div>
	                    <label>Διεύθυνση:</label>
	                    <input type="text" name="address1" value="&amp;"/><br />
	                    <label>&nbsp;</label>
	                    <input type="text" name="address2" />
	                </div>
	                
	                <div>
	                    <label>Ταχυδρομικός κώδικας:</label>
	                    <input type="text" name="postcode" />
	                </div>
	                
	                <div>
	                    <label>Πόλη/Περιοχή:</label>
	                    <input type="text" name="city" />
	                </div>
	                
	                <div>
	                    <label>Χώρα:</label>
	                    <select name="country">
	                    	<?php 
	                    		$sql = "SELECT Country_ID, name FROM ".$db_table_prefix."Countries;";
	                    		$countries = $db->sql_query($sql);
	                    		
	                    		while ($country = $db->sql_fetchrow($countries)) {
	                    	?>
	                    	<option value="<?php echo $country['Country_ID']; ?>"><?php echo $country['name'];?></option>
	                    	<?php 
	                    		}
	                    	?>
	                    </select>
	                </div>
	                
	                <hr />
	                
	                <div>
	                    <label>Τηλέφωνο οικίας:</label>
	                    <input type="text" name="phone_home" />
	                </div>
	                
	                <div>
	                    <label>Τηλέφωνο κινητό:</label>
	                    <input type="text" name="phone_mobile" />
	                </div>
	                
                </fieldset>
                
                <div>
                	<label>
                		<input type="checkbox" name="signature-deed" id="signature-deed" value="1" />
                		Έλαβα γνώση και αποδέχομαι το καταστατικό του Συλλόγου Αποφοίτων
                	</label>
                </div>
                <div>
                	<label for="signature-termsofuse">
                		<input type="checkbox" name="signature-termsofuse" id="signature-termsofuse" value="1" />
                		Έλαβα γνώση και αποδέχομαι τους όρους χρήσης της υπηρεσίας
                	</label>
              	</div>
              	<div>
                	<label for="signature-privacypolicy">
                		<input type="checkbox" name="signature-privacypolicy" id="signature-privacypolicy" value="1" />
                		Έλαβα γνώση και αποδέχομαι την πολιτική απορρήτου
                	</label>
                </div>

                
                <div style="text-align: center">
                    <?php echo recaptcha_get_html($recaptcha_publickey); ?>
                </div>
                
                <div style="text-align: center">
                    <label>&nbsp;</label>
                    <input type="submit" value="Register"/>
                </div>
                
                </form>
            </div>

			<div class="clear"></div>
	 	</div>
	</div>
</div>
</body>
</html>
