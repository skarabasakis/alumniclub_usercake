<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the lost password page if he/she is already logged in
	if(isUserLoggedIn()) { header("Location: account.php"); die(); }
?>
<?php
	/* 
		Below process a new activation link for a user, as they first activation email may have never arrived.
	*/
	
$errors = array();
$success_message = "";

//Forms posted
//----------------------------------------------------------------------------------------------
if(!empty($_POST) && $emailActivation)
{
		$email = $_POST["email"];
		$username = $_POST["username"];
		
		//Perform some validation
		//Feel free to edit / change as required
		
		if(trim($email) == "")
		{
			$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
		}
		//Check to ensure email is in the correct format / in the db
		else if(!isValidEmail($email) || !emailExists($email))
		{
			$errors[] = lang("ACCOUNT_INVALID_EMAIL");
		}
		
		if(trim($username) == "")
		{
			$errors[] =  lang("ACCOUNT_SPECIFY_USERNAME");
		}
		else if(!usernameExists($username))
		{
			$errors[] = lang("ACCOUNT_INVALID_USERNAME");
		}
		
		
		if(count($errors) == 0)
		{
			//Check that the username / email are associated to the same account
			if(!emailUsernameLinked($email,$username))
			{
				$errors[] = lang("ACCOUNT_USER_OR_EMAIL_INVALID");
			}
			else
			{
				$userdetails = fetchUserDetails($username);
			
				//See if the user's account is activation
				if($userdetails["Active"]==1)
				{
					$errors[] = lang("ACCOUNT_ALREADY_ACTIVE");
				}
				else
				{
					// TODO: Potential division by zero
					$hours_diff = round((time()-$userdetails["LastActivationRequest"]) / (3600*$resend_activation_threshold),0);

					if($resend_activation_threshold!=0 && $hours_diff <= $resend_activation_threshold)
					{
						$errors[] = lang("ACCOUNT_LINK_ALREADY_SENT",array($resend_activation_threshold));
					}
					else
					{
						//For security create a new activation url;
						$new_activation_token = generateActivationToken();
						
						if(!updateLastActivationRequest($new_activation_token,$username,$email))
						{
							$errors[] = lang("SQL_ERROR");
						}
						else
						{
							$mail = new userCakeMail();
							
							$activation_url = $websiteUrl."activate-account.php?token=".$new_activation_token;
						
							//Setup our custom hooks
							$hooks = array(
								"searchStrs" => array("#ACTIVATION-URL","#USERNAME#"),
								"subjectStrs" => array($activation_url,$userdetails["Username"])
							);
							
							if(!$mail->newTemplateMsg("resend-activation.txt",$hooks))
							{
								$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
							}
							else
							{
								if(!$mail->sendMail($userdetails["Email"],"Ενεργοποιήστε το λογαριασμό σας"))
								{
									$errors[] = lang("MAIL_ERROR");
								}
								else
								{
									//Success, user details have been updated in the db now mail this information out.
									$success_message = lang("ACCOUNT_NEW_ACTIVATION_SENT");
								}
							}
						}
					}
				}
			}
		}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Επαναποστολή μηνύματος ενεργοποίησης - <?php echo $websiteName; ?></title>
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
 
 	 <h1>Επαναποστολή μηνύματος ενεργοποίησης</h1>
 
	<?php
	if(!empty($_POST) || !empty($_GET["confirm"]) || !empty($_GET["deny"]) && $emailActivation)
	{	 
	
			if(count($errors) > 0)
			{
		?>
			<div id="errors">
				<?php errorBlock($errors); ?>
			</div> 
		<?
			}
			else
			{
		?>
			<div id="success">
			
				<p><?php echo $success_message; ?></p>
			
			</div>
		<?
			}
		}
		?> 
	
	<div id="regbox">
	
	<?php 
	
	if(!$emailActivation)
	{ 
		echo lang("FEATURE_DISABLED");
	}
	else
	{
	?>
		<form name="resendActivation" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		
		
		<p>
			<label class="required field">Username:</label>
			<input type="text" name="username" value="<?php echo $_GET['username'] ?>"/>
		</p>	 
			
		 <p>
			<label class="required field">Email:</label>
			<input type="text" name="email" value="<?php echo $_GET['email'] ?>"/>
		 </p>	
	
		<p style="text-align: center;">
			<input type="submit" value="Αποστολή" class="submit" />
		 </p>
			
		</form>

	 <? } ?> 
	 </div>   
	 
	 		<div class="clear"></div>   
		</div>
	</div>   
</div>
</body>
</html>


