<?php
	/*
		UserCake Langauge File.
		Language: English.
		Author: Adam Davis
		http://adamdavis.co.uk
	*/
	
	/*
		%m1% - Dymamic markers which are replaced at run time by the relevant index.
	*/

	$lang = array();
	
	//Account
	$lang = array_merge($lang,array(
		"ACCOUNT_SPECIFY_USERNAME" 				=> "Παρακαλούμε συμπληρώστε ένα όνομα χρήστη (username)",
		"ACCOUNT_SPECIFY_PASSWORD" 				=> "Παρακαλούμε συμπληρώστε έναν κωδικό πρόσβασης (password)",
		"ACCOUNT_SPECIFY_EMAIL"					=> "Παρακαλούμε συμπληρώστε μια διεύθυνση email",
		"ACCOUNT_INVALID_EMAIL"					=> "Η διεύθυνση email δεν είναι έγκυρη",
		"ACCOUNT_INVALID_USERNAME"				=> "Το username δεν είναι έγκυρο",
		"ACCOUNT_USER_OR_EMAIL_INVALID"			=> "Η διεύθυνση email ή το username δεν είναι έγκυρο",
		"ACCOUNT_USER_OR_PASS_INVALID"			=> "Το username ή το password δεν είναι έγκυρο",
		"ACCOUNT_ALREADY_ACTIVE"				=> "Ο λογαριασμός σας έχει ήδη ενεργοποιηθεί",
		"ACCOUNT_INACTIVE"						=> "Ο λογαριασμός σας δεν έχει ενεργοποιηθεί. Παρακαλούμε ελέγξτε την ηλεκτρονική αλληλογραφία σας (συμπεριλαμβανομένου του φακέλου spam) για οδηγίες ενεργοποίησης",
		"ACCOUNT_USER_CHAR_LIMIT"				=> "Το username σας δεν πρέπει να είναι μικρότερο από %m1% ή μεγαλύτερο από %m2% χαρακτήρες",
		"ACCOUNT_PASS_CHAR_LIMIT"				=> "Το password σας δεν πρέπει να είναι μικρότερο από %m1% ή μεγαλύτερο από %m2% χαρακτήρες",
		"ACCOUNT_PASS_MISMATCH"					=> "Τα passwords δεν ταυτίζονται",
		"ACCOUNT_USERNAME_IN_USE"				=> "Το username %m1% χρησιμοποιείται ήδη",
		"ACCOUNT_EMAIL_IN_USE"					=> "Η διεύθυνση email %m1% χρησιμοποιείται ήδη",
		"ACCOUNT_LINK_ALREADY_SENT"				=> "Ένα μήνυμα ενεργοποίησης εστάλη ήδη σε αυτή τη διεύθυνση πριν από λιγότερο από %m1% ώρες",
		"ACCOUNT_NEW_ACTIVATION_SENT"			=> "Ένα μήνυμα ενεργοποίησης εστάλη στη διεύθυνση ηλεκτρονικής αλληλογραφίας σας",
		"ACCOUNT_NOW_ACTIVE"					=> "Ο λογαριασμός σας ενεργοποιήθηκε επιτυχώς",
		"ACCOUNT_SPECIFY_NEW_PASSWORD"			=> "Παρακαλούμε εισάγετε το νέο password σας",	
		"ACCOUNT_NEW_PASSWORD_LENGTH"			=> "Το νέο password σας δεν πρέπει να είναι μικρότερο από %m1% ή μεγαλύτερο από %m2% χαρακτήρες",	
		"ACCOUNT_PASSWORD_INVALID"				=> "Το τρέχον password δεν συμφωνεί με αυτό που έχουμε στη βάση δεδομένων μας",	
		"ACCOUNT_DETAILS_UPDATED"				=> "Τα στοιχεία του λογαριασμού σας ενημερώθηκαν",
		"ACCOUNT_INVALID_DATE_OF_BIRTH"			=> "Η ημερομηνία γέννησης που δηλώσατε δεν είναι έγκυρη",
		"ACCOUNT_SIGNATURE_MISSING"				=> "Παρακαλούμε σημειώστε ότι αποδέχεστε %m1%",
		"ACTIVATION_MESSAGE"					=> "Για να ολοκληρωθεί η εγγραφή σας και να μπορέσετε να συνδεθείτε στο λογαριασμό σας είναι απαραίτητο να ενεργοποιήσετε το λογαριασμό σας. Για να πραγματοποιηθεί η ενεργοποίηση ακολουθήστε τον παρακάτω σύνδεσμο. \n\n%m1%activate-account.php?token=%m2%",							
		"ACCOUNT_REGISTRATION_COMPLETE_TYPE1"	=> "Η αίτηση εγγραφής σας υποβλήθηκε επιτυχώς. Ευχαριστούμε για την εγγραφή σας στο Σύλλογο Αποφοίτων.",
		"ACCOUNT_REGISTRATION_COMPLETE_TYPE2"	=> "Πριν μπορέσετε να συνδεθείτε είναι απαραίτητο να ενεργοποιήσετε το λογαριασμό σας. Σε λίγα λεπτά θα λάβετε στο email σας ένα μήνυμα με οδηγίες ενεργοποίησης",
	));
	
	//Forgot Password
	$lang = array_merge($lang,array(
		"FORGOTPASS_INVALID_TOKEN"				=> "Μη έγκυρος σύνδεσμος ενεργοποίησης",
		"FORGOTPASS_NEW_PASS_EMAIL"				=> "Ένα νέο password εστάλη σας έχει αποσταλεί με email",
		"FORGOTPASS_REQUEST_CANNED"				=> "Το αίτημα υπενθύμισης κωδικού ακυρώθηκε",
		"FORGOTPASS_REQUEST_EXISTS"				=> "Έχετε ήδη υποβάλει ένα αίτημα υπενθύμισης κωδικού",
		"FORGOTPASS_REQUEST_SUCCESS"			=> "Σας έχουμε στείλει email με οδηγίες για να ανακτήσετε την πρόσβαση στο λογαριασμό σας",
	));
	
	//Miscellaneous
	$lang = array_merge($lang,array(
		"CONFIRM"								=> "Επιβεβαίωση",
		"DENY"									=> "Άρνηση",
		"SUCCESS"								=> "Επιτυχία",
		"ERROR"									=> "Σφάλμα",
		"NOTHING_TO_UPDATE"						=> "Καμία τιμή προς ενημέρωση",
		"SQL_ERROR"								=> "Σφάλμα SQL",
		"MAIL_ERROR"							=> "Σφάλμα συστήματος κατά την αποστολή email. Επικοινωνήστε με το διαχειριστή.",
		"MAIL_TEMPLATE_BUILD_ERROR"				=> "Error building email template",
		"MAIL_TEMPLATE_DIRECTORY_ERROR"			=> "Unable to open mail-templates directory. Perhaps try setting the mail directory to %m1%",
		"MAIL_TEMPLATE_FILE_EMPTY"				=> "Template file is empty... nothing to send",
		"FEATURE_DISABLED"						=> "Αυτή η λειτουργία έχει απενεργοποιηθεί",
	));
	
	// Special Strings for Alumniclub
	$lang = array_merge($lang,array(
		"CAPTCHA_ERROR"							=> "Η απάντηση που δώσατε στο CAPTCHA δεν ήταν σωστή",
		"ACCOUNT_MAIL_MISMATCH"					=> "Οι διευθύνσεις email δεν ταυτίζονται",
		"ACCOUNT_PHONE_FORMAT_ERROR"			=> "Ο τηλεφωνικός αριθμός δεν είναι έγκυρος",
		"ACCOUNT_PHONE_COUNTRY_ERROR"			=> "Ο τηλεφωνικός αριθμός δεν είναι έγκυρος για τη χώρα που δηλώσατε. Αν πιστεύετε ότι αυτό το μήνυμα λάθους είναι εσφαλμένο, παρακαλούμε επικοινωνήστε με τον webmaster στη διεύθυνση alumniclub@di.uoa.gr",
		"AT_LEAST_ONE_PHONE_NUMBER"				=> "Σας παρακαλούμε να συμπληρώσετε τουλάχιστον ένα τηλέφωνο επικοινωνίας",
		"REQUIRED_FIELD"						=> "Το πεδίο αυτό είναι υποχρεωτικό",
		"GENERIC_FORM_FIELD_ERROR"				=> "Η τιμή που συμπληρώσατε δεν είναι έγκυρη",
		"ERRORS_FOUND"							=> "Βρέθηκαν σφάλματα",
		"PLEASE_SELECT_ENTRY_YEAR"				=> "Παρακαλώ επιλέξτε το έτος εισαγωγής σας στο τμήμα Πληροφορικής και Τηλεπικοινωνιών",
		"PLEASE_SELECT_GRADUATION_YEAR"			=> "Παρακαλώ επιλέξτε το έτος αποφοίτησής σας από το τμήμα Πληροφορικής και Τηλεπικοινωνιών",
		"WHAT_ARE_YOU,_A_GENIUS?"				=> "Το έτος αποφοίτησης πρέπει να έχει διαφορά τουλάχιστον 4 χρόνια από το έτος εγγραφής",
	
		"COULD_NOT_SAVE_IN_DATABASE"			=> "Διαπιστώθηκε πρόβλημα κατά την αποθήκευση των στοιχείων που δώσατε στο τμήμα <strong>%m1%</strong> της φόρμας. Ίσως χρειαστεί να επικοινωνήσουμε μαζί σας για να επιβεβαιώσουμε τα στοιχεία σας.",
		"ACTIVATION_MAIL_NOT_SENT"				=> "Δεν μπορέσαμε να στείλουμε το email ενεργοποίησης του λογαρισμού σας. <a href=\"resend-activation.php?username=%m1%&email=%m2%\">[Επαναποστολή email ενεργοποίησης]</a>"
	
	));
?>