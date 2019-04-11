<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/check_login_token.php";
	if (checkLoginToken($stmt)) { header("Location: /home"); exit; }
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style-splash.css">
		<link rel="icon" href="/favicon.ico?v=2" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	</head>
	<body>
		<div id="header">
			<div id="header-emblem"></div>
		</div>
		<div id="content">
			<h1>Spire</h1>
			
			<div class="container">
				<form method="POST" id="login-form" action ="/src/php/login.php"></form>
				<input type="hidden" form="login-form" name="return-url" value="<?php if (isset($_GET['url'])) echo $_GET['url']; ?>">
				<div class="row">
					<input type="text" id="login-username" form="login-form" name="login-username" placeholder="Username">
				</div>
				<div class="row">
					<input type="password" id="login-password" form="login-form" name="login-password" placeholder="Password">
					<div class="tooltip">Forgot your username or password? Contact an Admin in Spire's Discord server for assistance.</div>
				</div>
				<div class="row">
					<input type="submit" class="button" id="login-submit" form="login-form" value="LOGIN">
				</div>
			</div>
			
			<div class="container">
				<h2>Register</h2>
				<form method="POST" id="register-form" action="/src/php/register_new_user.php"></form>
				<h3>Account</h3>
				<div class="row">
					<input type="text" id="register-username" form="register-form" name="register-username" placeholder="Username">
					<div class="tooltip">What you login with and what others see your name displayed as throughout the site. This does not need to be your character's name but should be something everyone will recognize.</div>
				</div>
				<div class="row">
					<input type="password" id="register-password-1" form="register-form" name="register-password-1" placeholder="Password">
					<input type="password" id="register-password-2" form="register-form" name="register-password-2" placeholder="Repeat Password">
				</div>
				<h3>Character</h3>
				<div class="row">
					<input type="text" id="register-character" form="register-form" name="register-character" placeholder="Character Name">
					<div class="tooltip">Your main character's name, <b>spelled exactly as it is in-game</b>. This is important to spell properly to match against attendance and loot records.</div>
				</div>
				<div class="row">
					<select type="select" id="register-class" form="register-form" name="register-class" placeholder="Character Class">
						<option value=""></option>
						<option value="1">Druid</option>
						<option value="2">Hunter</option>
						<option value="3">Mage</option>
						<option value="4">Paladin</option>
						<option value="5">Priest</option>
						<option value="6">Rogue</option>
						<option value="7">Shaman</option>
						<option value="8">Warlock</option>
						<option value="9">Warrior</option>
					</select>
					<div class="tooltip">Your main character's class.</div>
				</div>
				<div class="row">
					<select type="select" id="register-role" form="register-form" name="register-role" placeholder="Character Role">
						<option value=""></option>
						<option value="1">DPS</option>
						<option value="2">Healer</option>
						<option value="3">Tank</option>
					</select>
					<div class="tooltip">Your main character's primary role (you can change this later and when signing up case-by-case).</div>
				</div>
				<div class="row">
					<input type="submit" class="button" id="register-submit" form="register-form" value="REGISTER">
				</div>
			</div>
		</div>
		
		<script>
			// login form
			$('#login-submit').click(function(e){
				e.preventDefault();
				
				let loginUsername	= $('#login-username').val();
				let loginPassword 	= $('#login-password').val();
				
				// check if all required fields are entered
				if (!loginUsername || !loginPassword) {
					alert("Please fill out all required fields.");
					if (!loginUsername) {
						$('#login-username').focus();
					} else if (!loginPassword) {
						$('#login-password').focus();
					}
				} else {
					$('#login-form').submit();
				}
			});
			
			
			// register form
			$('#register-submit').click(function(e){
				e.preventDefault();
				
				let registerUsername	= $('#register-username').val();
				let registerPassword1 	= $('#register-password-1').val();
				let registerPassword2 	= $('#register-password-2').val();
				let registerCharacter 	= $('#register-character').val();
				let registerClass 		= $('#register-class').val();
				let registerRole 		= $('#register-role').val();
				const discordKeyRegExp = /(.){2,32}#(\d{4})/;
				const characterKeyRegExp = /^[a-zA-Z]+$/;
				
				// check if all required fields are entered
				if (!registerUsername || !registerPassword1 || !registerPassword2 || !registerCharacter || !registerClass || !registerRole) {
					alert("Please fill out all required fields.");
					if (!registerUsername) {
						$('#register-username').focus();
					} else if (!registerPassword1) {
						$('#register-password-1').focus();
					} else if (!registerPassword2) {
						$('#register-password-2').focus();
					} else if (!registerCharacter) {
						$('#register-character').focus();
					} else if (!registerClass) {
						$('#register-class').focus();
					} else if (!registerRole) {
						$('#register-role').focus();
					}
				
				// check username format
				} else if (registerUsername.length < 2) {
					alert("Your username must be at least two characters long.");
					$('#register-username').focus();
				
				// check if passwords match
				} else if (registerPassword1 !== registerPassword2) {
					alert("Passwords do not match.");
					$('#register-password-1').val(null);
					$('#register-password-2').val(null);
					$('#register-password-1').focus();
				
				// check character name format
				} else if (!characterKeyRegExp.test(registerCharacter)) {
					alert("Your Character name must contain only letters.");
					$('#register-character').focus();
				
				// submit form
				} else {
					$('#register-form').submit();
				}
			});
		</script>
	</body>
</html>
