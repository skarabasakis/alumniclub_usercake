		<?php if(!isUserLoggedIn()) { ?>
			<ul>
				<li><a href="register.php">Εγγραφή Νέου Μέλους</a></li>
				<li><a href="resend-activation.php">Επαναποστολή μηνύματος ενεργοποίησης</a></li>
			</ul>
		<?php } else { ?>
	   		<ul>
				<li><a href="account.php">Το προφίλ μου</a></li>
				<li><a href="change-password.php">Αλλαγή Password</a></li>
				<li><a href="update-email-address.php">Αλλαγή E-mail</a></li>
			</ul>
	   <?php } ?>
			

			
