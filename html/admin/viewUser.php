<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/ranks.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/player.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	$user_joined = new DateTime($player['joined']);
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style.css"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	</head>
	<body>
		<div id="wrapper">
			<?php require $_SERVER['DOCUMENT_ROOT'] . "/src/php/header.php"; ?>
			<div id="inner-wrapper">
				<?php require $_SERVER['DOCUMENT_ROOT'] . "/src/php/navigation.php"; ?>
				<div id="content">
					<article>
						<div class="header">
							<h3><?php echo $player['username']; ?>'s Account</h3>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<table id="profile-account-table">
									<tr>
										<td><b>Username</b>:</td>
										<td>
											<div class="table-cell">
												<span><?php echo $player['username'];?></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>User ID</b>:</td>
										<td>
											<div class="table-cell">
												<span><?php echo $player['id'];?></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Joined</b>:</td>
										<td>
											<div class="table-cell">
												<span><?php echo $user_joined->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d'); ?></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Status</b>:</td>
										<td>
											<div class="table-cell">
												<div class="table-cell-notification" id="notification-status">
													<span class="notification-message"></span>
												</div>
												<form method="POST" action="/src/php/update_player_status.php">
													<input type="hidden" name="player_id" value="<?php echo $player['id']; ?>"></input>
													<select class="standard-select enable-form" name="status_id">
														<option value="0" <?php if ($player['active'] == 0) echo "selected"; ?>>Deactivated</option>
														<option value="1" <?php if ($player['active'] == 1) echo "selected"; ?>>Active</option>
													</select>
													<input type="submit" class="standard-button" id="change-status-btn" value="CHANGE STATUS" disabled></input>
												</form>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Rank</b>:</td>
										<td>
											<div class="table-cell">
												<div class="table-cell-notification" id="notification-rank">
													<span class="notification-message"></span>
												</div>
												<form method="POST" action="/src/php/update_player_rank.php">
													<input type="hidden" name="player_id" value="<?php echo $player['id']; ?>"></input>
													<select class="standard-select enable-form" name="rank_id">
													<?php while ($r = mysqli_fetch_array($ranks)) { ?> 
														<option value="<?php echo $r['id']; ?>" <?php if ($r['id'] == $player['rank']) { echo "selected"; } ?>><?php echo $r['name']; ?></option>
													<?php } ?>
													</select>
													<input type="submit" class="standard-button" id="change-rank-btn" value="CHANGE RANK" disabled></input>
												</form>
											</div>
										</td>
									</tr>
									<?php if ($user['security'] >= 3) { ?>
									<tr>
										<td><b>Security</b>:</td>
										<td>
											<div class="table-cell">
												<div class="table-cell-notification" id="notification-security">
													<span class="notification-message"></span>
												</div>
												<form method="POST" action="/src/php/update_player_security.php">
													<input type="hidden" name="player_id" value="<?php echo $player['id']; ?>"></input>
													<select class="standard-select enable-form" name="security_id">
													<?php while ($s = mysqli_fetch_array($security)) { ?> 
														<option value="<?php echo $s['id']; ?>" <?php if ($s['id'] == $player['security']) { echo "selected"; } ?>><?php echo $s['name'] . " - " . $s['description']; ?></option>
													<?php } ?>
													</select>
													<input type="submit" class="standard-button" id="change-security-btn" value="CHANGE SECURITY" disabled></input>
												</form>
											</div>
										</td>
									</tr>
									<?php }  else { ?>
									<tr>
										<td><b>Security</b>:</td>
										<td>
											<div class="table-cell">
												<h5 class="security-<?php echo $player['security']; ?>"><?php echo $player['securityName']; ?></h5>
												<span><?php echo $player['securityDescription']; ?></span>
											</div>
										</td>
									</tr>
									<?php } ?>
									
									<?php if ($user['security'] > $player['security']) { ?>
									<tr>
										<td><b>Password</b>:</td>
										<td>
											<div class="table-cell">
												<div class="table-cell-notification" id="notification-password">
													<span class="notification-message"></span>
												</div>
												<form method="POST" action="/src/php/update_player_password.php" onsubmit="return confirm('Are you sure you want to change this account password?');">
													<input type="hidden" name="player_id" value="<?php echo $player['id']; ?>"></input>
													<input type="password" class="standard-input" id="admin-pass" name="admin_pass" placeholder="Your Admin Password" required></input>
													<input type="password" class="standard-input" id="user-pass-1" name="new_pass_1" placeholder="User's New Password" required></input>
													<input type="password" class="standard-input" id="user-pass-2" name="new_pass_2" placeholder="Repeat User's New Password" required></input>
													<span id="user-pass-alert"></span>
													<input type="submit" id="change-pass-btn" class="standard-button" value="CHANGE PASSWORD" disabled></input>
												</form>
											</div>
										</td>
									</tr>
									<?php } ?>
								</table>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>
		<script>
			// password matching
			$('#admin-pass, #user-pass-1, #user-pass-2').on("input", function(){
				if ($('#admin-pass').val() == "") {
					$('#user-pass-alert').text("Please enter your admin password.");
					$('#change-pass-btn').prop("disabled", true);
				} else {
					if ($('#user-pass-1').val() == "" && $('#user-pass-2').val() == "") {
						$('#user-pass-alert').text("");
						$('#change-pass-btn').prop("disabled", true);
					} else if ($('#user-pass-1').val() !== $('#user-pass-2').val()) {
						$('#user-pass-alert').text("Passwords do not match.");
						$('#change-pass-btn').prop("disabled", true);
					} else {
						$('#user-pass-alert').text("");
						$('#change-pass-btn').prop("disabled", false);
					}
				}
			});
			
			
			// notifications
			function notify(type, msg) {
				let el = $('#notification-' + type);
				el.children('.notification-message').text(msg);
				el.fadeIn(125);
				setTimeout(function(){
					el.fadeOut(500);
				}, 2500);
			}
			
			// change status
			$('#change-status-btn').click(function(e){
				e.preventDefault();
				let formData = $(this).parent('form').serialize();
				$('#change-status-btn').prop('disabled', true);
				
				$.ajax({
					url: "/src/php/update_player_status.php",
					type: "POST",
					data: formData,
					success: function(d) {
						if (d == 0) {
							notify('status', "Status changed successfuly!");
						} else {
							notify('status', "Oops! Something went wrong, status not changed. Error code: " + d);
						}
					}
				});
			});
			
			// change rank
			$('#change-rank-btn').click(function(e){
				e.preventDefault();
				let formData = $(this).parent('form').serialize();
				$('#change-rank-btn').prop('disabled', true);
				
				$.ajax({
					url: "/src/php/update_player_rank.php",
					type: "POST",
					data: formData,
					success: function(d) {
						if (d == 0) {
							notify('rank', "Rank changed successfuly!");
						} else {
							notify('rank', "Oops! Something went wrong, rank not changed. Error code: " + d);
						}
					}
				});
			});
			
			// change security
			$('#change-security-btn').click(function(e){
				e.preventDefault();
				let formData = $(this).parent('form').serialize();
				$('#change-security-btn').prop('disabled', true);
				
				$.ajax({
					url: "/src/php/update_player_security.php",
					type: "POST",
					data: formData,
					success: function(d) {
						if (d == 0) {
							notify('security', "Security changed successfuly!");
						} else {
							notify('security', "Oops! Something went wrong, security not changed. Error code: " + d);
						}
					}
				});
			});
			
			
			// change password
			$('#change-pass-btn').click(function(e){
				e.preventDefault();
				let formData = $(this).parent('form').serialize();
				$('#admin-pass').val("");
				$('#user-pass-1').val("");
				$('#user-pass-2').val("");
				$('#change-pass-btn').prop('disabled', true);
				
				$.ajax({
					url: "/src/php/update_player_password.php",
					type: "POST",
					data: formData,
					success: function(d) {
						if (d == 0) {
							notify('password', "User's password changed successfuly!");
						} else if (d == 123) {
							notify('password', "Your admin password was not entered correctly. Please try again.");
							$('#change-pass-btn').prop("disabled", true);
						} else {
							notify('password', "Oops! Something went wrong, password not changed. Error code: " + d);
							$('#change-pass-btn').prop("disabled", true);
						}
					}
				});
			});
			
			// enable submit butons
			$('.enable-form').on('change', function(){
				console.log("changed");
				$(this).siblings('input[type=submit]').prop('disabled', false);
			});
		</script>
	</body>
</html>
