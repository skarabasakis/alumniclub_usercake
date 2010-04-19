
		<div id="build">
			<a href="http://usercake.com"><span>UserCake</span></a>
		</div>

		<?php if(!isUserLoggedIn()) { ?>
			<ul>
                <li><a href="index.php">Αρχική Σελίδα</a></li>
                <li><a href="login.php">Σύνδεση</a></li>
                <li><a href="register.php">Εγγραφή Μέλους</a></li>
                <li><a href="forgot-password.php">Forgot Password</a></li>
                <li><a href="resend-activation.php">Resend Activation Email</a></li>
            </ul>
		<?php } else { ?>
       		<ul>
            	<li><a href="account.php">Το προφίλ μου</a></li>
                <li><a href="account.php">Ενημέρωση στοιχείων</a>
	            	<ul>
	            		<li><a href="update-email-address.php">Αλλαγή E-mail</a></li>
	            	</ul>
	            </li>
				<li><a href="change-password.php">Αλλαγή Password</a></li>
            	<li><a href="logout.php">Αποσύνδεση</a></li>
			</ul>
       <?php } ?>
            

            
