<div id="header">
	<div id="logo">&nbsp;
	</div>
	<div id="navi">
		<ul>
			<li><a href="register.php">εγγραφή</a></li>
		<?php 
		if(!isUserLoggedIn()) {
		?>
			<li><a href="login.php">σύνδεση</a></li>
		<?php 
		} else {
		?>
			<li><a href="logout.php">αποσύνδεση</a></li>
		<?php 
		} 
		?>
		</ul>
	</div>
</div>