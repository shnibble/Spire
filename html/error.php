<?php 
	switch($_GET['id']) {
		case 100:	$error_text = "Failed to connect to database, something with the website or your connection failed. Please try again and contact an Admin on Spire's Discord server if the issue persists."; break;
		case 101:	$error_text = "You tried to register a username with fewer than two characters. Try using a longer name."; break;
		case 102:	$error_text = "You tried to register a username that has already been taken. Try using a different name."; break;
		case 103:	$error_text = "The Discord ID you entered is not formatted properly. It must be 2-32 characters followed by the # symbol followed by four numbers like this: JohnDoe#1234."; break;
		case 104:	$error_text = "The passwords you submitted do not match. Try entering them again more carefully, they must be identical."; break;
		case 105:	$error_text = "An error occurred while trying to add your new account to the database. You either broke through our data validation and the server rejected your trashy data or something went wrong on our end. Contact an Admin in Spire's Discord server if this issue persists"; break;
		case 106:	$error_text = "An error occurred while trying to add your new account to the database. You either broke through our data validation and the server rejected your trashy data or something went wrong on our end. Contact an Admin in Spire's Discord server if this issue persists"; break;
		case 107:	$error_text = "Incorrect username and/or password, we won't tell you which one because... reasons. Try logging in again and actually using your username and/or password that you registered with, duh!"; break;
		case 108:	$error_text = "You tried to access a secure page without being properly logged in or maybe you tried to hijack a session and we caught you! Either way you will need to login again."; break;
		case 109:	$error_text = "Something failed when updating the database. This isn't right, you should let an Admin know what you did to break this poor function."; break;
		case 110:	$error_text = "Missing variable! Either the code is wrong or you tried to access a page directly that requires some sort of information. It is probably you because I write perfect code..."; break;
		case 111:	$error_text = "No signup record exists to add a note to! You need to first signup or call out for an event before you can add a personal note."; break;
		case 112:	$error_text = "You tried to register a character name that has already been taken. You either have an account already or someone stole your character's name and you need to talk to an Admin."; break;
		case 113:	$error_text = "You tried to access a page that requires a higher security level than your account. Contact an Admin if you require a higher security setting."; break;
		case 114:	$error_text = "You tried to access a page that requires a higher rank than your account. Contact an Admin if your rank is not correct."; break;
		case 115:	$error_text = "Invalid session token. Either your session has expired and you need to log in again, or you tried to hijack a session and failed, better luck next time :)"; break;
		case 116:	$error_text = "Invalid character name formatting. Character names must contain only letters and should match their in-game name exactly."; break;
		case 117:	$error_text = "You account has been deactivated. If you believe this was done in error please contact an Admin on Spire's Discord server for assistance."; break;
		case 118:	$error_text = "You cannot sign up for events in the past."; break;
		case 119:	$error_text = "Invalid event ID. The event you tried to access either never existed or was deleted."; break;
		case 120:	$error_text = "You cannot delete your main character. Try switching your main first and then deleting."; break;
		case 121:	$error_text = "Text entered exceeds the maximum length allowed."; break;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style-splash.css"></link>
		<script src="/src/js/jquery-1.11.3.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	</head>
	<body>
		<div id="header">
			<div id="header-emblem"></div>
		</div>
		<div id="content">
			<h1>Spire</h1>
			
			<div class="container">
				<h2>Oops! Something Broke...</h2>
				<p style="color: red;"><?php echo $error_text; ?></p>
				<br>
				<a href="/jail"><b>GO TO JAIL</b><br>Go directly to Jail. Do not pass GO, do not collect $200</a>
				<br>
				<br>
				<a href="/" style="font-size: 2px; text-decoration: none;">Alternatively, go back to the home page and start over WITHOUT going to jail.</a>
				<br>
				<br>
			</div>
		</div>
	</body>
</html>
