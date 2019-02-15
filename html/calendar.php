<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user_characters.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/roles.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event_types.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/users.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/lockouts.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/lockout_events.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/lockout_occasions.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	// default view expanded articles
	$max_expanded = 1;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style.css"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="/src/js/timeout.js"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	</head>
	<body>
		<div id="wrapper">
			<?php require $_SERVER['DOCUMENT_ROOT'] . "/src/php/header.php"; ?>
			<div id="inner-wrapper">
				<?php require $_SERVER['DOCUMENT_ROOT'] . "/src/php/navigation.php"; ?>
				<div id="content">
				
					<div class="pre-article-container">
						<div class="left">
							<?php if ($user['security'] >= 1) { ?>
							<input type="submit" class="pre-article-button" id="add-event-btn" value="ADD EVENT"></input>
							<?php } ?>
						</div>
						<div class="right">
						</div>
					</div>
					
					<?php 
					$now = new DateTime();
					$article_count = 0;
					for ($i = 0; $i < count($lockout); $i++) {
						$article_count++;
					?>
					<article>
						<div class="header">
							<div class="occasions-container">
								<table>
									<tr class="<?php if ($lockout[$i]['begin'] < $now) echo "past"; ?>">
										<td><?php echo $lockout[$i]['begin']->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d D H:i'); ?></td>
										<td>Lockout Begins</td>
									</tr>
									<?php while ($lo = mysqli_fetch_array($lockout_occasions[$i])) { 
										$_lo_date = new DateTime($lo['date']);
									?>
									<tr class="<?php if ($_lo_date < $now) echo "past"; ?>">
										<td><?php echo $_lo_date->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d D H:i'); ?></td>
										<td><?php echo $lo['name']; ?></td>
									</tr>
									<?php } ?>
									<tr class="<?php if ($lockout[$i]['end'] < $now) echo "past"; ?>">
										<td><?php echo $lockout[$i]['end']->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d D H:i'); ?></td>
										<td>Lockout Ends</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="body">
						
							<?php while ($le = mysqli_fetch_array($lockout_events[$i])) { 
								$le_start = new DateTime($le['start']);
							?>
							<div class="event-container <?php if ($le_start < $now) echo "past"; ?> signup-<?php echo ($le['signupStatus'])? $le['signupStatus']:"0"; ?>">
								<a href="#" class="event-details-toggle">+</a>
								<span class="event-date"><?php echo $le_start->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d D G:i'); ?></span>
								<a href="/event?id=<?php echo $le['id']; ?>" class="event-title" title="<?php echo htmlspecialchars($le['title']); ?>"><?php echo htmlspecialchars($le['title']); ?></a>
								<div class="event-details">
									<table class="event-details-table">
										<tr>
											<td><b>Event Type:</b></td>
											<td><?php echo $le['typeName']; ?></td>
										</tr>
										<tr>
											<td><b>Raid Leader:</b></td>
											<td><?php echo $le['leader_name']; ?></td>
										</tr>
										<tr>
											<td><b>Master Looter:</b></td>
											<td><?php echo $le['looter_name']; ?></td>
										</tr>
										<tr>
											<td><b>Buff Instructions:</b></td>
											<td><?php echo htmlspecialchars($le['buff_instructions']); ?></td>
										</tr>
										<tr>
											<td><b>Meetup Instructions:</b></td>
											<td><?php echo htmlspecialchars($le['meetup_instructions']); ?></td>
										</tr>
										<tr>
											<td><b>Description:</b></td>
											<td><?php echo htmlspecialchars($le['description']); ?></td>
										</tr>
									</table>
								</div>
								<?php if ($le_start > $now) { ?>
								<div class="event-signups-container">
									<input type="button" class="event-btn signup" <?php if ($le['signupStatus'] == 1) echo "disabled"; ?> value="SIGN UP"></input>
									<input type="button" class="event-btn callout" <?php if ($le['signupStatus'] == 2) echo "disabled"; ?> value="CALL OUT"></input>
									<input type="button" class="event-btn note" <?php if (!$le['signupStatus']) echo "disabled"; ?> value="NOTE"></input>
									<div class="event-signup-action-container signup">
										<form method="POST" action="/src/php/signup.php">
											<input type="hidden" name="event_id" value="<?php echo $le['id'];?>"></input>
											<select class="signup-action-input signup-character" name="character_id" required>
												<?php 
												mysqli_data_seek($user_characters, 0); 
												while ($character = mysqli_fetch_array($user_characters)) { ?>
													<option value="<?php echo $character['id']; ?>" data-role="<?php echo $character['role']; ?>" <?php echo ($character['main'])?"selected":""; ?> ><?php echo $character['name']; ?></option>
												<?php } ?>
											</select>
											<select class="signup-action-input signup-role" name="character_role" required>
												<?php 
												mysqli_data_seek($roles, 0); 
												while ($role = mysqli_fetch_array($roles)) { 
													echo "<option value='" . $role['id'] . "'>" . $role['name'] . "</option>";
												} ?>
											</select>
											<input type="text" class="signup-action-input" name="note" placeholder="Note" value="<?php echo htmlspecialchars($le['note']); ?>"></input>
											<br>
											<button type="submit" class="signup-action-button submit submit-signup">SIGN UP</button>
											<button type="button" class="signup-action-button cancel">CANCEL</button>
											<br>
											<p style="color: #b30000;">Please use "CALL OUT" if you are tentative, otherwise your attendance may be recorded as a no-show.</p>
										</form>
									</div>
									<div class="event-signup-action-container callout">
										<form method="POST" action="/src/php/callout.php">
											<input type="hidden" name="event_id" value="<?php echo $le['id'];?>"></input>
											<input type="text" class="signup-action-input" name="note" placeholder="Note" value="<?php echo htmlspecialchars($le['note']); ?>"></input>
											<br>
											<button type="submit" class="signup-action-button submit submit-callout">CALL OUT</button>
											<button type="button" class="signup-action-button cancel">CANCEL</button>
										</form>
									</div>
									<div class="event-signup-action-container note">
										<form method="POST" action="/src/php/note.php">
											<input type="hidden" name="event_id" value="<?php echo $le['id'];?>"></input>
											<input type="text" class="signup-action-input" name="note" placeholder="Note" value="<?php echo htmlspecialchars($le['note']); ?>"></input>
											<br>
											<button type="submit" class="signup-action-button submit submit-note">UPDATE</button>
											<button type="button" class="signup-action-button cancel">CANCEL</button>
										</form>
									</div>
								</div>
								<?php } ?>
							</div>
							<?php } ?>
						</div>
					</article>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php if ($user['security'] >= 1) { ?>
		<div class="full-overlay">
			<div class="scrolling-table-container">
				<h2>Add Event</h2>
				<form method="POST" id="add-event-form" action="/src/php/add_event.php"></form>
				<table>
					<tr>
						<td><b>Title</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="event-title" form="add-event-form" name="event_title" required></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Date</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="event-date" form="add-event-form" name="event_date" required></input>
								<span>Enter using your local timezone: <b><?php echo $user['timezone_name']; ?></b>. Your local time can be changed from your Profile.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Type</b>:</td>
						<td>
							<div class="table-cell">
								<select form="add-event-form" class="standard-select" name="event_type" required>
									<?php 
									mysqli_data_seek($event_types, 0);
									while ($et = mysqli_fetch_array($event_types)) { ?>
									<option value="<?php echo $et['id']; ?>"><?php echo $et['name']; ?></option>
									<?php } ?>
								</select>
								<span>Be sure to make the appropriate selection. Attendance credits vary by event type.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Notify Late Signups</b>:</td>
						<td>
							<div class="table-cell">
								<input type="checkbox" class="standard-checkbox" form="add-event-form" name="event_notify"></input>
								<span>Signups added or changed within 24 hours of the event will be posted in Discord.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Log Attendance</b>:</td>
						<td>
							<div class="table-cell">
								<input type="checkbox" class="standard-checkbox" form="add-event-form" name="event_log"></input>
								<span>If checked then this event will be included in the admin attendance page and be able to be linked to an attendance log record.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Raid Leader</b>:</td>
						<td>
							<div class="table-cell">
								<select form="add-event-form" class="standard-select" name="event_leader">
									<option></option>
									<?php 
									mysqli_data_seek($users, 0);
									while ($u = mysqli_fetch_array($users)) { ?>
									<option value="<?php echo $u['id']; ?>"><?php echo $u['username']; ?></option>
									<?php } ?>
								</select>
								<span>Optional, leave blank if unknown.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Master Looter</b>:</td>
						<td>
							<div class="table-cell">
								<select form="add-event-form" class="standard-select" name="event_looter">
									<option></option>
									<?php 
									mysqli_data_seek($users, 0);
									while ($u = mysqli_fetch_array($users)) { ?>
									<option value="<?php echo $u['id']; ?>"><?php echo $u['username']; ?></option>
									<?php } ?>
								</select>
								<span>Optional, leave blank if unknown.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Buff Instructions</b>:</td>
						<td>
							<div class="table-cell">
								<textarea class="standard-textarea" form="add-event-form" name="event_buff" style="height: 50px;"></textarea>
								<span>Optional instructions for raid buff requirements or runs prior to entering the instance.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Meetup Instructions</b>:</td>
						<td>
							<div class="table-cell">
								<textarea class="standard-textarea" form="add-event-form" name="event_meetup" style="height: 50px;"></textarea>
								<span>Optional but <b>recommended</b> instructions for when and where to meetup for this event.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Description</b>:</td>
						<td>
							<div class="table-cell">
								<textarea class="standard-textarea" form="add-event-form" name="event_description" style="height: 200px;"></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" class="standard-button" form="add-event-form" value="SUBMIT"></input>
							<input type="button" class="standard-button" id="close-overlay-btn" value="CANCEL"></input>
						</td>
					</tr>
				</table>
		</div>
		<?php } ?>
		<script>		
			// datetime selector
			$('#event-date').flatpickr({
				enableTime: true,
				time_24hr: true,
				altInput: true,
				altFormat: 'Y-m-d H:i',
				dateFormat: 'Y-m-d H:i:S'
			});
			
			// toggle overlay
			$('#add-event-btn').click(function(){
				$('.full-overlay').slideDown(250);
			});
			$('#close-overlay-btn').click(function(){
				$('.full-overlay').slideUp(250);
			});
			
			// open history window
			$('#history-link').click(function() {
				window.open($(this).attr('href'),'title', 'width=800, height=700');
				return false;
			}); 
			
			// toggle event-details 
			$('.event-details-toggle').click(function(e){
				e.preventDefault();
				
				if ($(this).hasClass('active')) {
					$(this).text("+");
					$(this).removeClass('active');
					$(this).siblings('.event-details').slideUp(250);
				} else {
					$(this).text("-");
					$(this).addClass('active');
					$(this).siblings('.event-details').slideDown(250);
				}
			});
			
			// change role based on character's default role
			let defaultRole = $('.signup-character').children('option:selected').data('role');
			$('.signup-role').val(defaultRole);
			$('.signup-character').change(function(){
				let role = $(this).children('option:selected').data('role');
				console.log(role);
				$(this).siblings('.signup-role').val(role);
			});
			
			// toggle event-signup-action-containers
			$('.event-btn').click(function(){
				$(this).siblings('.event-signup-action-container').slideUp(250);
				if ($(this).hasClass('active')) {
					$(this).removeClass('active');
				} else {
					$(this).siblings('.event-btn').removeClass('active');
					$(this).addClass('active');
					
					if ($(this).hasClass('signup')) {
						$(this).siblings('.event-signup-action-container.signup').slideDown(250);
					}
					if ($(this).hasClass('callout')) {
						$(this).siblings('.event-signup-action-container.callout').slideDown(250);						
					}
					if ($(this).hasClass('note')) {
						$(this).siblings('.event-signup-action-container.note').slideDown(250);						
					}
				}
			});
			$('.signup-action-button.cancel').click(function(){
				$(this).parent().parent().toggle(250);
				$(this).parent().parent().siblings('.event-btn').removeClass('active');
			});
			
			// event signups 
			$('.signup-action-button.submit').click(function(e){
				e.preventDefault();
				let formData = $(this).parent('form').serialize();
				let par = $(this).parent().parent().parent();
				let newNote = $(this).siblings('input[name="note"]').val();
				
				if ($(this).hasClass('submit-signup')) {
					$.ajax({
						url: "/src/php/signup.php",
						type: "POST",
						data: formData,
						success: function(d) {
							if (d == 0) {
								par.children('.event-signup-action-container').children('form').children('input[name="note"]').val(newNote);
								par.children('.event-btn.signup').prop("disabled", true);
								par.children('.event-btn.callout').prop("disabled", false);
								par.children('.event-btn.note').prop("disabled", false);
								$('.event-btn.active').removeClass('active');
								$('.event-signup-action-container').slideUp(250);
							} else {
								alert("Oops! Something went wrong. Error code: " + d);
							}
						}
					});
				}
				if ($(this).hasClass('submit-callout')) {
					$.ajax({
						url: "/src/php/callout.php",
						type: "POST",
						data: formData,
						success: function(d) {
							if (d == 0) {
								par.children('.event-signup-action-container').children('form').children('input[name="note"]').val(newNote);
								par.children('.event-btn.signup').prop("disabled", false);
								par.children('.event-btn.callout').prop("disabled", true);
								par.children('.event-btn.note').prop("disabled", false);
								$('.event-btn.active').removeClass('active');
								$('.event-signup-action-container').slideUp(250);
							} else {
								alert("Oops! Something went wrong. Error code: " + d);
							}
						}
					});
				}
				if ($(this).hasClass('submit-note')) {
					$.ajax({
						url: "/src/php/note.php",
						type: "POST",
						data: formData,
						success: function(d) {
							if (d == 0) {
								par.children('.event-signup-action-container').children('form').children('input[name="note"]').val(newNote);
								$('.event-btn.active').removeClass('active');
								$('.event-signup-action-container').slideUp(250);
							} else {
								alert("Oops! Something went wrong. Error code: " + d);
							}
						}
					});
				}
			});
		</script>
	</body>
</html>
