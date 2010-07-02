<?php
	/*
		UserCake
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is already logged in
	if(isUserLoggedIn()) { header("Location: account.php"); die(); }
?>
<?php
	/* 
		Below is a very simple example of how to process a login request.
		Some simple validation (ideally more is needed).
	*/

//Forms posted
if(!empty($_POST))
{
		$errors = array();
		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]);
	
		//Perform some validation
		//Feel free to edit / change as required
		if($username == "")
		{
			$errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
		}
		if($password == "")
		{
			$errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
		}
		
		//End data validation
		if(count($errors) == 0)
		{
			//A security note here, never tell the user which credential was incorrect
			if(!usernameExists($username))
			{
				$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
			}
			else
			{
				$userdetails = fetchUserDetails($username);
			
				//See if the user's account is activation
				if($userdetails["Active"]==0)
				{
					$errors[] = lang("ACCOUNT_INACTIVE");
				}
				else
				{
					//Hash the password and use the salt from the database to compare the password.
					$entered_pass = generateHash($password,$userdetails["Password"]);

					if($entered_pass != $userdetails["Password"])
					{
						//Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
						$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
					}
					else
					{
						//Passwords match! we're good to go'
						
						//Construct a new logged in user object
						//Transfer some db data to the session object
						$loggedInUser = new loggedInUser();
						$loggedInUser->email = $userdetails["Email"];
						$loggedInUser->user_id = $userdetails["User_ID"];
						$loggedInUser->hash_pw = $userdetails["Password"];
						$loggedInUser->display_username = $userdetails["Username"];
						$loggedInUser->clean_username = $userdetails["Username_Clean"];
						
						//Update last sign in
						$loggedInUser->updateLastSignIn();
		
						$_SESSION["userCakeUser"] = $loggedInUser;
						
						//Redirect to user account page
						header("Location: account.php");
						die();
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
<title>Login - <?php echo $websiteName; ?></title>
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
		
		<h1>Σύνδεση</h1>
		
		<?php
		if(!empty($_POST))
		{
		?>
		<?php
		if(count($errors) > 0)
		{
		?>
		<div id="errors">
		<?php errorBlock($errors); ?>
		</div>	 
		<?php
		} }
		?> 
		
			<div id="regbox">
				<form name="newUser" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
				<p>
					<label class="field">Username:</label>
					<input type="text" name="username" />
				</p>
				
				<p>
					 <label class="field">Password:</label>
					 <input type="password" name="password" />
				</p>
				
				<p style="text-align: center;">
					<input type="submit" value="Σύνδεση" class="submit" />
				</p>

				</form>
				
			</div>
		</div>
		
			<div class="clear"><a href="forgot-password.php">Ξεχάσατε το password σας;</a></div>
		</div>
</div>
</body>
</html>


