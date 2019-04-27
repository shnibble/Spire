<?php
	$error = false;

	// connect to database
	include_once $_SERVER["DOCUMENT_ROOT"] . '/src/config.php';
	$conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
	if (mysqli_connect_errno()) {
		// ERROR: connection failed
		$error_id = 100;
		header("Location: /error.php?id=" . $error_id);
		exit;
		
	} else {
		// prepare statement
		$stmt = $conn->stmt_init();
		
		// get POST variables
		$username 	= $_POST['login-username'];
		$password 	= $_POST['login-password'];
		
		// get user from database
		if (!$error) {
			$stmt->prepare("SELECT `id`, `active`, `username`, `password` FROM `users` WHERE `username` = ?");
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$result = $stmt->get_result();
			if (mysqli_num_rows($result) == 0) {
				// ERROR: username not found
				$error = true;
				$error_id = 107;
			} else {
				$user = mysqli_fetch_array($result);
			}
		}
		
		// verify password
		if (!$error) {
			if (!password_verify($password, $user['password'])) {
				// ERROR: incorrect password
				$error = true;
				$error_id = 107;	// same ID as no username for security
			}
		}
		
		// check if active
		if (!$error) {
			if (!$user['active']) {
				// ERROR: inactive user account
				$error = true;
				$error_id = 117;
			}
		}
		
		// generate and store session token and log lastLogin
		if (!$error) {
			// added base64 encoding because there's less chance of having transport/storage issues than raw binary text
			$token = base64_encode(random_bytes(32));
			$stmt->prepare("UPDATE `users` SET `token` = ?, `last_login` = NOW() WHERE `id` = ?");
			$stmt->bind_param("si", $token, $user['id']);
			if (!($stmt->execute())) {
				// ERROR: execute failed
				$error_id = 109;
				header("Location: /error.php?id=" . $error_id);
				exit;
			}
		}
		
		// log event
		if(!$error) {
			$logDescription = "logged in.";
			$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 3)");
			$stmt->bind_param("is", $user['id'], $logDescription);
			$stmt->execute();
		}
		
		$stmt->close();
		$conn->close();
		
		if (!$error) {
			session_start();
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['token'] = $token;

			$lifetime = 604800; // set token cookie to expire in 1 week
			setcookie('login_token', $token, time()+$lifetime, "/");
			
			// return to original url if provided else go to home page
			if (isset($_POST['return-url']) && $_POST['return-url'] != "") {
				header("Location: " . $_POST['return-url']);
				exit;
			} else {
				header("Location: /home");
				exit;
			}

		} else {

			header("Location: /error.php?id=" . $error_id);
			exit;

		}
	}
?>
