<?php
	$error = false;

	// connect to database
	include_once $_SERVER["DOCUMENT_ROOT"] . '/src/config.php';
	$conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
	if (mysqli_connect_errno()) {
		// ERROR: connection failed
		$error_id = 100;
		header("Location: /error.php?id=" . $error_id);
		
	} else {
		// prepare statement
		$stmt = $conn->stmt_init();
		
		// get POST variables
		$username 	= $_POST['register-username'];
		$password1 	= $_POST['register-password-1'];
		$password2 	= $_POST['register-password-2'];
		$character 	= $_POST['register-character'];
		$class 		= $_POST['register-class'];
		$role 		= $_POST['register-role'];
	
	
		// check if username is at least 2 characters
		if (strlen($username) <	2) {
			// ERROR: username not long enough
			$error = true;
			$error_id = 101;
		}
		
		// check if username is unique
		if (!$error) {
			$stmt->prepare("SELECT `id` FROM `users` WHERE `username` = ?");
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$result = $stmt->get_result();
			if (mysqli_num_rows($result) > 0) {
				// ERROR: not a unique username
				$error = true;
				$error_id = 102;
			}
		}
		
		// check passwords match
		if (!$error) {
			if ($password1 != $password2) {
				// ERROR: passwords do not match
				$error = true;
				$error_id = 104;
			} else {
				// hash password
				$password_hashed = password_hash($password1, PASSWORD_DEFAULT);				
			}
		}
		
		// check if character name contains only characters
		if (!$error) {
			if (!preg_match('/^[a-zA-Z]+$/', $character)) {
				// ERROR: invalid name format
				$error = true;
				$error_id = 116;
			}
		}
		
		// check if character name is unique
		if (!$error) {
			$stmt->prepare("SELECT `id` FROM `characters` WHERE `name` = ?");
			$stmt->bind_param("s", $character);
			$stmt->execute();
			$result = $stmt->get_result();
			if (mysqli_num_rows($result) > 0) {
				// ERROR: not a unique character name
				$error = true;
				$error_id = 112;
			}
		}
		
		// insert into database
		if (!$error) {
			$stmt->prepare("INSERT INTO `users` (`username`, `password`) VALUES (?, ?)");
			if (!$stmt->bind_param("ss", $username, $password_hashed)) {
				// ERROR: failed to bind parameters
				$error = true;
				$error_id = 105;
			
			} else if (!$stmt->execute()) {
				// ERROR: failed to insert into database
				$error = true;
				$error_id = 106;
			} else {
				// get insert ID
				$last_id = $conn->insert_id;
			}
		}
		
		// insert main character into database
		if (!$error) {
			$stmt->prepare("INSERT INTO `characters` (`user_id`, `name`, `class`, `role`, `main`) VALUES (?, ?, ?, ?, TRUE)");
			if (!$stmt->bind_param("isii", $last_id, $character, $class, $role)) {
				// ERROR: failed to bind parameters
				$error = true;
				$error_id = 105;
			} else if (!$stmt->execute()) {
				// ERROR: failed to insert into database
				$error = true;
				$error_id = 106;
			}
		}
		
		// log event
		if(!$error) {			
			$logDescription = "created an account.";
			$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 2)");
			$stmt->bind_param("is", $last_id, $logDescription);
			$stmt->execute();
		}
		
		// post new registration notification to discord admins
		// admin role id if wanting to use in notification: <@&448608682079682560>
		if (!$error) {
			/*$curl = curl_init("https://discordapp.com/api/webhooks/485112226362032151/67C1Odj7zmQnpHtINyaUhiIqcRkSwpDnF9-K8sSn0Ia9T0zO8shW-odN1hA2YzrFSGyP");
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array("content" => "**$username** has registered a new account.")));
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_exec($curl);*/
			
		} else {
			header("Location: /error.php?id=" . $error_id);
		}
		
		$stmt->close();
		$conn->close();
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
			<h1>Account Registration Successful!</h1>
			<div class="container">
				<br>
				<p>Return <a href="/"><b>HOME</b></a> and login with your credentials.</p>
				<br>
				<p>Note that one of the Admins will need to verify your account in order to utilize most of the site. Don't worry, they have already been notified and should get to it quickly.</p>
				<br>
			</div>
		</div>
	</body>
</html>
