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

	$postback = !empty($_POST);
	$v = NULL;
	$errors_global = array();
	$errors_specific = array();
	$warning_messages = array();
	$success = false;
	
	if($postback)
	{
		/*$errors = array();
		$email = trim($_POST["email"]);
		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]);
		$confirm_pass = trim($_POST["passwordc"]);*/
		
		// Sanitize inputs
		$s = new FormSanitizer();
		$s->sanitize($_POST);
		
		// Validate inputs
		$v = new Validator($_POST);
		foreach ($_POST as $key => $value) {
			$v->validate($value, $key);
		}
		
		if (!array_key_exists('group_id', $_POST))
			$v->set_specific_error('group_id',lang("REQUIRED_FIELD"));
		if (!array_key_exists('year', $_POST))
			$v->set_specific_error('year',lang("GENERIC_FORM_FIELD_ERROR"));
			
			
		if (!array_key_exists('signature_deed', $_POST))
			$v->set_specific_error('signature_deed', lang("ACCOUNT_SIGNATURE_MISSING", array("το καταστατικό του Συλλόγου Αποφοίτων")));
		if (!array_key_exists('signature_privacypolicy', $_POST))
			$v->set_specific_error('signature_privacypolicy', lang("ACCOUNT_SIGNATURE_MISSING", array("την πολιτική απορρήτου")));
		if (!array_key_exists('signature_termsofuse', $_POST))
			$v->set_specific_error('signature_termsofuse', lang("ACCOUNT_SIGNATURE_MISSING", array("τους όρους χρήσης της υπηρεσίας")));
		
		// Perform validation on the CAPTCHA before everything else
		$resp = recaptcha_check_answer ($recaptcha_privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
			$v->set_global_error(lang("CAPTCHA_ERROR"));
		}
			
		// If no validation errors present
		if(count($v->errors_global) + count($v->errors_specific) == 0)
		{	
			$success = true;
			
			//Construct a user object
			$user = new User($_POST['username'], $_POST['password'], $_POST['email']);
			
			//Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
			if($user->userCakeAddUser())
			{
				if ($user->mail_failure) {
					array_push($warning_messages, lang("ACTIVATION_MAIL_NOT_SENT", array($_POST['username'], $_POST['email'])));
				}
				
				// Retrieve the User_ID from the database
				$userdetails = fetchUserDetails($user->clean_username);
				
				if (!empty($userdetails)) {
					
					$contact = new Contact(true, $userdetails['User_ID'], $_POST);
					$personal = new Personal(true, $userdetails['User_ID'], $_POST);
					$status = new Status(true, $userdetails['User_ID'], $_POST);
					if ($_POST['group_id'] == 1 || $_POST['group_id'] == 2)
						$studies = new Studies_Undergr(true, $userdetails['User_ID'], $_POST);
					
					$inserted = array();
					$inserted['contact'] = $contact->insert();
					$inserted['personal'] = $personal->insert();
					$inserted['status'] = $status->insert();
					if ($_POST['group_id'] == 1 || $_POST['group_id'] == 2)
						$inserted['studies'] = $studies->insert();
						
					// TODO Failure handling here is non-existent
					// Need to better handle the case when database insertion partially fails
					$success = true;
					
					foreach ($inserted as $key => $value) {
						if ($value == false)
							array_push($warning_messages, lang("COULD_NOT_SAVE_IN_DATABASE", array($key)));
					}
				}
			}
		}
	}
	
	// Form utility functions
	function error_exists($key) {
		global $postback, $v;
		return ($postback && array_key_exists($key, $v->errors_specific));
	}
	
	function label($key, $label) {
		if (!empty($label)) {
			$class = (Validator::$check_required[$key] ? 'required' : 'optional');
			echo "<label for=\"$key\" class=\"$class field\">$label:</label>";
		}
		else 
		{
			echo "<label for=\"$key\" class=\"field\">&nbsp;</label>";
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Εγγραφή μέλους - <?php echo $websiteName; ?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="form.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include 'includes/header-nav.php'; ?>
<div id="wrapper">
	<div id="secondary-navi">
		<?php include 'includes/secondary-nav.php'; ?>
	</div>
	<div id="content" >
		<div id="main">
			
			<h1>Εγγραφή Νέου Μέλους</h1>

			<?php
			if($postback)
			{
				if(!$success)
				{
			?>
			<div id="errors">
				<p>Παρακαλούμε διορθώστε τα παρακάτω σφάλματα που εντοπίστηκαν στην υποβολή σας.</p>
				<ul>
					<?php 
						foreach ($v->errors_global as $error) {
							echo "<li>$error</li>";
						} 
					?>
				</ul>
			</div>	 
			<?php
		   		 } else {
				$message = lang("ACCOUNT_REGISTRATION_COMPLETE_TYPE1");
		
				if($emailActivation)
				{
			   		 $message .= "\n\n" . lang("ACCOUNT_REGISTRATION_COMPLETE_TYPE2");
				}
			?> 
			<div id="success">
			   <p><?php echo $message ?></p>
			</div>
			<?php 
				if (count($warning_messages) > 0) {
			?>
				<div id="warning">
					<p>Προσοχή!</p>
					<ul>
						<?php 
							foreach ($warning_messages as $warning) {
								echo "<li>$warning</li>";
							} 
						?>		
					</ul>
				</div>
			<? } } }?>

			<?php if (!$success) { ?>
			<div id="regbox">
				<form name="newUser" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
				
				<fieldset id="membertype">
					<legend>Τύπος Μέλους</legend>
					<p>Επιλέξτε τύπο μέλους:</p>
					<div>
						<ol>
							<li>
								<input type="radio" name="group_id" id="membertype-regular" value="1" <?php if ($postback && $_POST['group_id'] == 1) echo 'checked="checked"';?>/>
								<label class="boxradio" for="membertype-regular"><strong>Τακτικό Μέλος</strong> (Ετήσια Συνδρομή: &euro;5)</label>
							</li>
							<li>
								<input type="radio" name="group_id" id="membertype-junior" value="2"  <?php if ($postback && $_POST['group_id'] == 2) echo 'checked="checked"';?>/>
								<label class="boxradio" for="membertype-junior"><strong>Δόκιμο μέλος</strong> (Χωρίς ετήσια συνδρομή)</label>
							</li>
							<li>	
								<input type="radio" name="group_id" id="membertype-social" value="3" <?php if ($postback && $_POST['group_id'] == 3) echo 'checked="checked"';?>/>
								<label class="boxradio" for="membertype-social"><strong>Κοινωνικό Μέλος</strong> (Ετήσια Συνδρομή: &euro;5)</label>
							</li>
						</ol>
						<?php if (error_exists('group_id')) { ?><div class="field_error"><?php echo $v->errors_specific['group_id'];?></div><?php } ?>
						<input type="hidden" name="year" value="<?php
							// TODO We could use the registration date from the user table instead of a hidden field 
							echo (time() < $year_change_date ? 
								date("Y") : 
								(int)date("Y") + 1
							);
						?>"/>
					</div>
				</fieldset>
				
				<fieldset id="account">
					<legend>Στοιχεία Λογαριασμού</legend>
				
					<div>
						<?php label('username', "Username")?>
						<input type="text" name="username" value="<?php if ($postback) echo $_POST['username'];?>"/>
						<?php if (error_exists('username')) { ?><div class="field_error"><?php echo $v->errors_specific['username'];?></div><?php } ?>
					</div>
					
					<div>
						<?php label('password', "Password")?>
						<input type="password" name="password" value="<?php if ($postback) echo $_POST['password'];?>"/>
						<?php if (error_exists('password')) { ?><div class="field_error"><?php echo $v->errors_specific['password'];?></div><?php } ?>
					</div>
					
					<div>
						<?php label('password', "Password (επιβεβαίωση)")?>
						<input type="password" name="password_c" value="<?php if ($postback) echo $_POST['password_c'];?>"/>
						<?php if (error_exists('password_c')) { ?><div class="field_error"><?php echo $v->errors_specific['password_c'];?></div><?php } ?>
					</div>
					
					<div>
						<?php label('email', "Email")?>
						<input type="text" name="email" value="<?php if ($postback) echo $_POST['email'];?>"/>
						<?php if (error_exists('email')) { ?><div class="field_error"><?php echo $v->errors_specific['email'];?></div><?php } ?>
					</div>
				</fieldset>
				
				<fieldset id="personal">
					<legend>Προσωπικά Στοιχεία</legend>
				
					<div>
						<?php label('lastname', "Επώνυμο")?>
						<input type="text" name="lastname" value="<?php if ($postback) echo $_POST['lastname'];?>"/>
						<?php if (error_exists('lastname')) { ?><div class="field_error"><?php echo $v->errors_specific['lastname'];?></div><?php } ?>
					</div>
					
					<div>
						<?php label('firstname', "Όνομα")?>
						<input type="text" name="firstname" value="<?php if ($postback) echo $_POST['firstname'];?>"/>
						<?php if (error_exists('firstname')) { ?><div class="field_error"><?php echo $v->errors_specific['firstname'];?></div><?php } ?>
					</div>
					
					<div>
						<?php label('fathersname', "Όνομα Πατέρα")?>
						<input type="text" name="fathersname" value="<?php if ($postback) echo $_POST['fathersname'];?>"/>
						<?php if (error_exists('fathersname')) { ?><div class="field_error"><?php echo $v->errors_specific['fathersname'];?></div><?php } ?>
					</div>
										
					<div>
						<?php label('dobdate', "Ημερομηνία Γέννησης")?>
						<select name="dobdate">
							<option value="0">&nbsp;</option>
							<?php 
								for ($i = 1; $i <= 31; $i++) {
							?>
							<option value="<?php echo $i; ?>" <?php if ($postback && $_POST['dobdate'] == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
							<?php 
								}
							?>
						</select>
						<select name="dobmonth">
							<?php 
								$month = array( '', 'Ιανουαρίου', 'Φεβρουαρίου', 'Μαρτίου',
													'Απριλίου', 'Μαΐου', 'Ιουνίου',
													'Ιουλίου', 'Αυγούστου', 'Σεπτεμβρίου',
													'Οκτωβρίου', 'Νοεμβρίου', 'Δεκεμβρίου'); 
								
								foreach ($month as $i => $mname) {
							?>
							<option value="<?php echo $i; ?>" <?php if ($postback && $_POST['dobmonth'] == $i) echo 'selected="selected"';?>><?php echo $mname;?></option>
							<?php 
								}
							?>
						</select>
						<select name="dobyear">
							<option value="0">&nbsp;</option>
							<?php 
								$sixteen_years_ago = date("Y") - 16;
								for ($i = 1950; $i <= $sixteen_years_ago; $i++) {
							?>
							<option value="<?php echo $i; ?>" <?php if ($postback && $_POST['dobyear'] == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
							<?php 
								}
							?>
						</select>
						<?php if (error_exists('dobyear')) { ?><div class="field_error"><?php echo $v->errors_specific['dobyear'];?></div><?php } ?>
					</div>
										
				</fieldset>
				
				<fieldset id="studies">
					<legend>Σπουδές</legend>
					<p>Συμπληρώνεται μόνο από τα τακτικά και τα δόκιμα μέλη. Επιλέξτε το έτος εισαγωγής και αποφοίτησής σας από το προπτυχιακό πρόγραμμα σπουδών του τμήματος Πληροφορικής και Τηλεπικοινωνιών.</p>
					<div>
						<?php label('entryyear', "Έτος εισαγωγής")?>
						<select name="entryyear">
							<option value="0">Χωρίς επιλογή</option>
							<?php 
								$thisyear = date("Y");
								for ($i = 1986; $i <= $thisyear; $i++) {
							?>
							<option value="<?php echo $i; ?>" <?php if ($postback && $_POST['entryyear'] == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
							<?php 
								}
							?>
						</select>
						<?php if (error_exists('entryyear')) { ?><div class="field_error"><?php echo $v->errors_specific['entryyear'];?></div><?php } ?>
					</div>
					
					<div>
						<?php label('graduationyear', "Έτος αποφοίτησης")?>
						<select name="graduationyear">
							<option value="0">Χωρίς επιλογή</option>
							<?php 
								for ($i = 1986; $i <= $thisyear; $i++) {
							?>
							<option value="<?php echo $i; ?>" <?php if ($postback && $_POST['graduationyear'] == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
							<?php 
								}
							?>
						</select>
						<?php if (error_exists('graduationyear')) { ?><div class="field_error"><?php echo $v->errors_specific['graduationyear'];?></div><?php } ?>
					</div>
				</fieldset>
				
				<fieldset id="contact">
					<legend>Στοιχεία Επικοινωνίας</legend>
					
					<div>
						<?php label('address1', "Διεύθυνση")?>
						<input type="text" name="address1" value="<?php if ($postback) echo $_POST['address1'];?>"/><br />
						<?php label('address2', "")?>
						<input type="text" name="address2" value="<?php if ($postback) echo $_POST['address2'];?>"/>
						<?php if (error_exists('address1')) { ?><div class="field_error"><?php echo $v->errors_specific['address1'];?></div><?php } ?>
					</div>
					
					<div>
						<?php label('postcode', "Ταχυδρομικός Κώδικας")?>
						<input type="text" name="postcode" value="<?php if ($postback) echo $_POST['postcode'];?>" />
						<?php if (error_exists('postcode')) { ?><div class="field_error"><?php echo $v->errors_specific['postcode'];?></div><?php } ?>
					</div>
					
					<div>
						<?php label('city', "Πόλη/περιοχή")?>
						<input type="text" name="city" value="<?php if ($postback) echo $_POST['city'];?>" />
						<?php if (error_exists('city')) { ?><div class="field_error"><?php echo $v->errors_specific['city'];?></div><?php } ?>
					</div>
					
					<div>
						<?php label('country', "Χώρα")?>
						<select name="country">
							<?php 
								$sql = "SELECT Country_ID, name FROM ".$db_table_prefix."Countries;";
								$countries = $db->sql_query($sql);
								
								while ($country = $db->sql_fetchrow($countries)) {
							?>
							<option value="<?php echo $country['Country_ID']; ?>" <?php if ($postback && $_POST['country'] == $country['Country_ID']) echo 'selected="selected"';?>><?php echo $country['name'];?></option>
							<?php 
								}
							?>
						</select>
						<?php if (error_exists('country')) { ?><div class="field_error"><?php echo $v->errors_specific['country'];?></div><?php } ?>
					</div>
					
					<hr />
					
					<div>
						<?php label('phone_home', "Τηλέφωνο οικίας")?>
						<input type="text" name="phone_home" value="<?php if ($postback) echo $_POST['phone_home'];?>"/>
						<?php if (error_exists('phone_home')) { ?><div class="field_error"><?php echo $v->errors_specific['phone_home'];?></div><?php } ?>
					</div>
					
					<div>
						<?php label('phone_mobile', "Τηλέφωνο κινητό")?>
						<input type="text" name="phone_mobile" value="<?php if ($postback) echo $_POST['phone_mobile'];?>"/>
						<?php if (error_exists('phone_mobile')) { ?><div class="field_error"><?php echo $v->errors_specific['phone_mobile'];?></div><?php } ?>
					</div>
					
				</fieldset>
				
				<fieldset>
					<div id="signatures" style="float:left;width:60%;padding:15px 5px;">
						<div>
							<label>
								<input type="checkbox" name="signature_deed" id="signature_deed" <?php if ($postback && array_key_exists('signature_deed', $_POST)) echo 'checked="checked"';?>/>
								Έλαβα γνώση και αποδέχομαι <a href="docs/katastatiko.html" class="popup">το καταστατικό του Συλλόγου Αποφοίτων</a>
							</label>
							<?php if (error_exists('signature_deed')) { ?><div class="field_error"><?php echo $v->errors_specific['signature_deed'];?></div><?php } ?>
						</div>
						<div>
							<label for="signature_termsofuse">
								<input type="checkbox" name="signature_termsofuse" id="signature_termsofuse" <?php if ($postback && array_key_exists('signature_termsofuse', $_POST)) echo 'checked="checked"';?>/>
								Έλαβα γνώση και αποδέχομαι <a href="docs/termsofuse.html" class="popup">τους όρους χρήσης της υπηρεσίας</a>
							</label>
							<?php if (error_exists('signature_termsofuse')) { ?><div class="field_error"><?php echo $v->errors_specific['signature_termsofuse'];?></div><?php } ?>
					  	</div>
					  	<div>
							<label for="signature_privacypolicy">
								<input type="checkbox" name="signature_privacypolicy" id="signature_privacypolicy" <?php if ($postback && array_key_exists('signature_privacypolicy', $_POST)) echo 'checked="checked"';?>/>
								Έλαβα γνώση και αποδέχομαι <a href="docs/privacypolicy.html" class="popup">την πολιτική απορρήτου</a>
							</label>
							<?php if (error_exists('signature_privacypolicy')) { ?><div class="field_error"><?php echo $v->errors_specific['signature_privacypolicy'];?></div><?php } ?>
						</div>
					</div>
	
					
					<div id="captchabox" style="padding: 5px; width:320px; height:130px; float:right;">
						<?php echo recaptcha_get_html($recaptcha_publickey); ?>
					</div>
				</fieldset>
				
				<div style="text-align: center">
					<input class="button" type="submit" value="Εγγραφή μέλους"/>
				</div>
				
				</form>
			</div>
			<?php } ?>

			<div class="clear"></div>
	 	</div>
	</div>
</div>
<script type="text/javascript">
window.onload = function() {
	// check to see that the browser supports the getElementsByTagName method
	// if not, exit the loop 
	if (!document.getElementsByTagName) {
		return false; 
	} 
	// create an array of objects of each link in the document 
	var popuplinks = document.getElementsByTagName("a");
	// loop through each of these links (anchor tags) 	
	for (var i=0; i < popuplinks.length; i++) {	
		// if the link has a class of "popup"...	
		if (popuplinks[i].getAttribute("class") == "popup") {	
			// add an onclick event on the fly to pass the href attribute	
			// of the link to our second function, openPopUp 	
			popuplinks[i].onclick = function() {	
			openPopUp(this.getAttribute("href"));	
			return false; 	
			} 	
		}
	} 
} 

function openPopUp(linkURL) {
window.open(linkURL,'docs','toolbar=no,menubar=no,status=no,scrollbars=yes,width=600,height=600')
}
</script>
</body>
</html>
