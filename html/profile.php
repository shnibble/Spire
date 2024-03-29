<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/check_login_token.php";
	if (!checkLoginToken($stmt)) { header("Location: /?url=" . $_SERVER['REQUEST_URI']); exit; }
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/badges.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user_characters.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/roles.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/classes.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user_scores.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/all_timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	$user_joined = new DateTime($user['joined']);
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style.css?v=51356zadf1"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="/src/js/timeout.js"></script>
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
							<h3>Account</h3>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<table id="profile-account-table">
									<tr>
										<td><b>Username</b>:</td>
										<td>
											<div class="table-cell">
												<span><?php echo $user['username']; ?></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Badges</b>:</td>
										<td>
											<div class="table-cell">
												<div class="user-badges-container">
													<?php if ($user['badges']) {
													$_user_badges = explode(",", $user['badges']);
													foreach ($_user_badges as $b) { ?>
													<div class="badge-row">
														<div class="user-badge large" style="background-image: url('/src/img/badge_<?php echo $b; ?>.png');"></div>
														<span class="badge-name"><?php echo htmlspecialchars($badges_array[$b]['name']); ?></span>
														<p class="badge-description"><?php echo htmlspecialchars($badges_array[$b]['description']); ?></p>
													</div>
													<?php }
													} ?>
												</div>
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
										<td><b>Rank</b>:</td>
										<td>
											<div class="table-cell">
												<span class="rank-<?php echo $user['rank']; ?>"><?php echo $user['rank_name']; ?></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Scores</b>:</td>
										<td>
											<div class="table-cell">
												<table class="user-scores-table">
													<tr>
														<th>Loot Cost</th>
														<th>Attendance</th>
														<th title="Attendance - Loot">Spread</th>
														<th title="Attendance at primary raids over the past 30 days.">30DAR</th>
													</tr>
													<tr>
														<td><span><?php echo "-" . $user_scores['loot_cost']; ?></span></td>
														<td><span><?php echo "+" . $user_scores['attendance_score']; ?></span></td>
														<td><span><?php echo ($user_scores['spread'] >= 0)?"+" . $user_scores['spread']:"" . $user_scores['spread']; ?></span></td>
														<td><span><?php echo round((float)$user_scores['30_day_attendance_rate'] * 100 ) . '%'; ?></span></td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Security</b>:</td>
										<td>
											<div class="table-cell">
												<h5 class="security-<?php echo $user['security']; ?>"><?php echo $user['security_name']; ?></h5>
												<span><?php echo $user['security_description']; ?></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Game Server Timezone</b>:</td>
										<td>
											<div class="table-cell">
												<span><?php echo $config['server_timezone']; ?></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Your Local Timezone</b>:</td>
										<td>
											<div class="table-cell">
												<div class="table-cell-notification" id="notification-timezone">
													<span class="notification-message"></span>
												</div>
												<form method="POST" id="change-timezone-form" action="/src/php/update_user_timezone.php">
													<select class="standard-select enable-form" name="timezone_id">
													<?php while ($tz = mysqli_fetch_array($timezones)) { ?> 
														<option value="<?php echo $tz['id']; ?>" <?php if ($tz['id'] == $user['timezone_id']) { echo "selected"; } ?>><?php echo $tz['name']; ?></option>
													<?php } ?>
													</select>
													<input type="submit" class="standard-button" id="change-timezone-btn" value="CHANGE TIMEZONE" disabled></input>
												</form>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Password</b>:</td>
										<td>
											<div class="table-cell">
												<div class="table-cell-notification" id="notification-password">
													<span class="notification-message"></span>
												</div>
												<form method="POST" action="/src/php/update_user_password.php">
													<input type="password" class="standard-input" id="user-pass-old" name="old_pass" placeholder="Current Password" required></input>
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
					<article>
						<div class="header">
							<h4>Characters</h4>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<input type="button" class="article-button" id="add-character-btn" value="ADD CHARACTER"></input>
								<?php while ($c = mysqli_fetch_array($user_characters)) { ?>
								<div class="profile-character <?php if($c['main']) { echo "main"; } ?>">
									<div class="profile-character-section">
										<h4 class="class-<?php echo $c['class']; ?>"><?php echo $c['name']; ?></h4>
										<span><?php echo $c['class_name']; ?></span>
										<span>(<?php echo $c['role_name']; ?>)</span>
									</div>
									<div class="profile-character-section">
										<?php if ($c['main']) { ?>
											<input type="hidden" name="character_id" value="<?php echo $c['id']; ?>"></input>
											<input type="hidden" name="character_name" value="<?php echo $c['name']; ?>"></input>
											<input type="hidden" name="character_class" value="<?php echo $c['class_name']; ?>"></input>
											<input type="hidden" name="character_role" value="<?php echo $c['role']; ?>"></input>
											<input type="hidden" name="character_main" value="<?php echo $c['main']; ?>"></input>
											<input type="button" class="profile-character-button edit-character-btn" value="EDIT"></input>
											<input type="button" class="profile-character-button" value="DELETE" disabled></input>
										<?php } else { ?>
										<form method="POST" action="/src/php/delete_character.php" onsubmit="return confirm('Are you sure you want to delete this character?');">
											<input type="hidden" name="character_id" value="<?php echo $c['id']; ?>"></input>
											<input type="hidden" name="character_name" value="<?php echo $c['name']; ?>"></input>
											<input type="hidden" name="character_class" value="<?php echo $c['class_name']; ?>"></input>
											<input type="hidden" name="character_role" value="<?php echo $c['role']; ?>"></input>
											<input type="hidden" name="character_main" value="<?php echo $c['main']; ?>"></input>
											<input type="button" class="profile-character-button edit-character-btn" value="EDIT"></input>
											<input type="submit" class="profile-character-button" value="DELETE"></input>
										</form>
										<?php } ?>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
					</article>
					<article>
						<div class="header">
							<h4>Loot</h4>
							<span>Total Cost: <?php echo $user_scores['loot_cost']; ?></span>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<div class="ajax-table-pager">
									<input type="button" class="standard-button ajax-table-btn page-beginning" value="<<" disabled>
									<input type="button" class="standard-button ajax-table-btn page-back" value="<" disabled>
									<span>Page <span class="ajax-table-pager-page">1</span> of <span class="ajax-table-pager-pages"></span></span>
									<input type="button" class="standard-button ajax-table-btn page-forward" value=">" disabled>
									<input type="button" class="standard-button ajax-table-btn page-end" value=">>" disabled>
								</div>
								<table class="profile-loot-table ajax-table" data-src="/src/ajax-tables/user_loot.php" data-limit="20" data-page="1" data-pages="1" data-sort="date" data-order="DESC" data-filtertype="" data-filtervalue="">
									<thead>
										<tr>
											<th class="ajax-table-header" data-sort="date">Date<span></span></th>
											<th class="ajax-table-header" data-sort="character">Character<span></span></th>
											<th class="ajax-table-header" data-sort="item">Item<span></span></th>
											<th class="ajax-table-header" data-sort="type">Type<span></span></th>
											<th class="ajax-table-header" data-sort="cost">Cost<span></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</article>
					<article>
						<div class="header">
							<h4>Attendance</h4>
							<span>Total: <?php echo $user_scores['attendance_score']; ?></span>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<div class="ajax-table-pager">
									<input type="button" class="standard-button ajax-table-btn page-beginning" value="<<" disabled>
									<input type="button" class="standard-button ajax-table-btn page-back" value="<" disabled>
									<span>Page <span class="ajax-table-pager-page">1</span> of <span class="ajax-table-pager-pages"></span></span>
									<input type="button" class="standard-button ajax-table-btn page-forward" value=">" disabled>
									<input type="button" class="standard-button ajax-table-btn page-end" value=">>" disabled>
								</div>
								<table class="profile-attendance-table ajax-table" data-src="/src/ajax-tables/user_attendance.php" data-limit="20" data-page="1" data-pages="1" data-sort="date" data-order="DESC" data-filtertype="" data-filtervalue="">
									<thead>
										<tr>
											<th class="ajax-table-header" data-sort="date">Date<span></span></th>
											<th class="ajax-table-header" data-sort="event">Event<span></span></th>
											<th class="ajax-table-header" data-sort="type">Type<span></span></th>
											<th class="ajax-table-header" data-sort="attendance">Attendance<span></span></th>
											<th class="ajax-table-header" data-sort="value">Value<span></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>
		<div class="full-overlay" id="add-character-overlay">
			<div class="scrolling-table-container">
				<h2>Add Character</h2>
				<form method="POST" id="add-character-form" action="/src/php/add_character.php"></form>
				<table>
					<tr>
						<td><b>Name</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="character-name" form="add-character-form" name="character_name" required></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Class</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="character-class" form="add-character-form" name="character_class">
									<?php mysqli_data_seek($classes, 0); 
									while ($cl = mysqli_fetch_array($classes)) { ?>
									<option value="<?php echo $cl['id']; ?>"><?php echo $cl['name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Default Role</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="character-role" form="add-character-form" name="character_role">
									<?php mysqli_data_seek($roles, 0); 
									while ($r = mysqli_fetch_array($roles)) { ?>
									<option value="<?php echo $r['id']; ?>"><?php echo $r['name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Main Character?</b>:</td>
						<td>
							<div class="table-cell">
								<input type="checkbox" class="standard-checkbox" id="character-main" form="add-character-form" name="character_main"></input>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" class="standard-button" form="add-character-form" value="SUBMIT"></input>
							<input type="button" class="standard-button" id="close-add-character-overlay-btn" value="CANCEL"></input>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="full-overlay" id="edit-character-overlay">
			<div class="scrolling-table-container">
				<h2>Edit Character</h2>
				<form method="POST" id="edit-character-form" action="/src/php/edit_character.php"></form>
				<input type="hidden" form="edit-character-form" id="edit-character-id" name="character_id"></input>
				<table>
					<tr>
						<td><b>Name</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" form="edit-character-form"  class="standard-input" id="edit-character-name"  name="character_name" readonly></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Class</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" form="edit-character-form"  class="standard-input" id="edit-character-class" name="character_class" readonly></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Default Role</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="edit-character-role" form="edit-character-form" name="character_role">
									<?php mysqli_data_seek($roles, 0); 
									while ($r = mysqli_fetch_array($roles)) { ?>
									<option value="<?php echo $r['id']; ?>"><?php echo $r['name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Main Character?</b>:</td>
						<td>
							<div class="table-cell">
								<input type="hidden" id="edit-character-main-hidden" form="edit-character-form" name="character_main"></input>
								<input type="checkbox" class="standard-checkbox" id="edit-character-main" form="edit-character-form" name="character_main"></input>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" class="standard-button" form="edit-character-form" value="SUBMIT"></input>
							<input type="button" class="standard-button" id="close-edit-character-overlay-btn" value="CANCEL"></input>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<script src="/src/js/ajaxTables.js"></script>
		<script>
			// toggle add-character-overlay
			$('#add-character-btn').click(function(){
				$('#add-character-overlay').slideDown(250);
			});
			$('#close-add-character-overlay-btn').click(function(){
				$('#add-character-overlay').slideUp(250);
			});
			
			// toggle edit-character-overlay
			$('.edit-character-btn').click(function(){
				
				// get character details
				let _id = $(this).siblings('input[name="character_id"]').val();
				let _name = $(this).siblings('input[name="character_name"]').val();
				let _class = $(this).siblings('input[name="character_class"]').val();
				let _role = $(this).siblings('input[name="character_role"]').val();
				let _main = $(this).siblings('input[name="character_main"]').val();
				
				// update form
				$('#edit-character-id').val(_id);
				$('#edit-character-name').val(_name);
				$('#edit-character-class').val(_class);
				$('#edit-character-role').val(_role);
				
				if (_main == 1) {
					$('#edit-character-main').prop("checked", true);
					$('#edit-character-main').prop("disabled", true);
					$('#edit-character-main-hidden').val(1);
				} else {
					$('#edit-character-main').prop("checked", false);
					$('#edit-character-main').prop("disabled", false);
					$('#edit-character-main-hidden').val(0);
				}
				
				$('#edit-character-overlay').slideDown(250);
				
			});
			$('#close-edit-character-overlay-btn').click(function(){
				$('#edit-character-overlay').slideUp(250);
			});
			
			// password matching
			$('#user-pass-old, #user-pass-1, #user-pass-2').on("input", function(){
				if ($('#user-pass-old').val() == "") {
					$('#user-pass-alert').text("Please enter your current password.");
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
			
			// change timezone
			$('#change-timezone-btn').click(function(e){
				e.preventDefault();
				let formData = $(this).parent('form').serialize();
				$('#change-timezone-btn').prop('disabled', true);
				
				$.ajax({
					url: "/src/php/update_user_timezone.php",
					type: "POST",
					data: formData,
					success: function(d) {
						if (d == 0) {
							notify('timezone', "Timezone changed successfuly!");
						} else {
							notify('timezone', "Oops! Something went wrong, timezone not changed. Error code: " + d);
						}
					}
				});
			});
			
			
			// change password
			$('#change-pass-btn').click(function(e){
				e.preventDefault();
				let formData = $(this).parent('form').serialize();
				$('#user-pass-old').val("");
				$('#user-pass-1').val("");
				$('#user-pass-2').val("");
				$('#change-pass-btn').prop('disabled', true);
				
				$.ajax({
					url: "/src/php/update_user_password.php",
					type: "POST",
					data: formData,
					success: function(d) {
						if (d == 0) {
							notify('password', "Password changed successfuly!");
						} else if (d == 123) {
							notify('password', "Your current password was not entered correctly. Please try again.");
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
