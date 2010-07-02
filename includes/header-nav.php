<div id="header">
	<div id="logo"><img src="members-logo.png" alt="Περιοχή Μελών" />
	</div>
	<div id="navi">
		<ul>
		<?php 
		if(!isUserLoggedIn()) {
		?>
			<li><a href="register.php">εγγραφή</a></li>
			<li><a href="login.php">σύνδεση</a></li>
		<?php 
		} else {
		?>
			<li><a href="account.php">προφίλ</a></li>
			<li><a href="logout.php">αποσύνδεση</a></li>
		<?php 
		} 
		?>
		</ul>
	</div>
</div>