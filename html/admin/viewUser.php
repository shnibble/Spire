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
												<form method="POST" action="/src/php/update_player_status.php" onsubmit="return confirm('Are you sure you want to change this account status?');">
													<input type="hidden" name="player_id" value="<?php echo $player['id']; ?>"></input>
													<select class="standard-select enable-form" name="status_id">
														<option value="0" <?php if ($player['active'] == 0) echo "selected"; ?>>Deactivated</option>
														<option value="1" <?php if ($player['active'] == 1) echo "selected"; ?>>Active</option>
													</select>
													<input type="submit" class="standard-button" value="CHANGE STATUS" disabled></input>
												</form>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Rank</b>:</td>
										<td>
											<div class="table-cell">
												<form method="POST" action="/src/php/update_player_rank.php" onsubmit="return confirm('Are you sure you want to change this account rank?');">
													<input type="hidden" name="player_id" value="<?php echo $player['id']; ?>"></input>
													<select class="standard-select enable-form" name="rank_id">
													<?php while ($r = mysqli_fetch_array($ranks)) { ?> 
														<option value="<?php echo $r['id']; ?>" <?php if ($r['id'] == $player['rank']) { echo "selected"; } ?>><?php echo $r['name']; ?></option>
													<?php } ?>
													</select>
													<input type="submit" class="standard-button" value="CHANGE RANK" disabled></input>
												</form>
											</div>
										</td>
									</tr>
									<?php if ($user['security'] >= 3) { ?>
									<tr>
										<td><b>Security</b>:</td>
										<td>
											<div class="table-cell">
												<form method="POST" action="/src/php/update_player_security.php" onsubmit="return confirm('Are you sure you want to change this account security?');">
													<input type="hidden" name="player_id" value="<?php echo $player['id']; ?>"></input>
													<select class="standard-select enable-form" name="security_id">
													<?php while ($s = mysqli_fetch_array($security)) { ?> 
														<option value="<?php echo $s['id']; ?>" <?php if ($s['id'] == $player['security']) { echo "selected"; } ?>><?php echo $s['name'] . " - " . $s['description']; ?></option>
													<?php } ?>
													</select>
													<input type="submit" class="standard-button" value="CHANGE SECURITY" disabled></input>
												</form>
											</div>
										</td>
									</tr>
									<?php }  else { ?>
									<tr>
										<td><b>Security</b>:</td>
										<td>
											<div class="table-cell">
												<h5 class="security-<?php echo $user['security']; ?>"><?php echo $user['securityName']; ?></h5>
												<span><?php echo $user['securityDescription']; ?></span>
											</div>
										</td>
									</tr>
									<?php } ?>
									<tr>
										<td><b>Password</b>:</td>
										<td>
											<div class="table-cell">
												<form method="POST" action="/src/php/update_player_password.php" onsubmit="return confirm('Are you sure you want to change this account password?');">
													<input type="hidden" name="player_id" value="<?php echo $player['id']; ?>"></input>
													<input type="password" class="standard-input" id="user-pass-1" name="new_pass_1" placeholder="New Password" required></input>
													<input type="password" class="standard-input" id="user-pass-2" name="new_pass_2" placeholder="Repeat New Password" required></input>
													<span id="user-pass-alert"></span>
													<input type="submit" id="change-pass-btn" class="standard-button" value="CHANGE PASSWORD" disabled></input>
												</form>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>
		<script>
			// password matching
			$('#user-pass-1, #user-pass-2').on("input", function(){
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
			});
			
			// enable submit butons
			$('.enable-form').on('change', function(){
				console.log("changed");
				$(this).siblings('input[type=submit]').prop('disabled', false);
			});
		</script>
	</body>
</html>
