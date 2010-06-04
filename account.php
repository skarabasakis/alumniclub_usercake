<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Διαχείριση Λογαριασμού - <?php echo $websiteName; ?></title>
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
			
			<h1>Καλωσήρθατε, <?php echo $loggedInUser->display_username; ?></h1>
		
			<p>Είστε εγγεγραμμένος ως <strong><?php  $group = $loggedInUser->groupID(); echo $group['Group_Name']; ?></strong>. Η εγγραφή σας πραγρατοποιήθηκε στις <?php echo date("j/m/Y",$loggedInUser->signupTimeStamp()); ?>.</p>

			<p>Από αυτή τη σελίδα θα μπορείτε σύντομα να επεξεργάζεστε τα στοιχεία του λογαρισαμού σας.</p>
		</div>

	</div>
</div>
</body>
</html>

