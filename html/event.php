<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/check_login_token.php";
	if (!checkLoginToken($stmt)) { header("Location: /?url=" . $_SERVER['REQUEST_URI']); exit; }
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user_event_signup.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user_characters.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/roles.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event_signup_details.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event_signups.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event_callouts.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event_attendance.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event_types.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/raid_roster_templates.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event_raid_rosters_confirmed.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event_raid_rosters_unconfirmed.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/users.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	if (!$valid_event) {
		header("Location: /error.php?id=119");
		exit;
	}
	
	$event_start = New DateTime($event['start']);
	$late_signup = New DateTime($event['start']);
	$tosub = new DateInterval('PT24H');
	$late_signup->sub($tosub);
	
	$now = new DateTime();
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
					<?php if ($user['security'] >= 1) { ?>
					<form method="POST" id="delete-event-form" action="/src/php/delete_event.php" onsubmit="return confirm('Are you sure you want to delete this event?');"></form>
					<input type="hidden" form="delete-event-form" name="event_id" value="<?php echo $event['id']; ?>"></input>
					<input type="submit" class="pre-article-button" id="edit-event-btn" value="EDIT EVENT"></input>
					<?php if ($user['security'] >= 2) { ?>
					<input type="submit" class="pre-article-button" id="delete-event-btn" form="delete-event-form" value="DELETE EVENT"></input>
					<?php } ?>
					<?php } ?>
					<article>
						<div class="header">
							<h3><?php echo $event['title']; ?></h3>
						</div>
						<div class="body">
							<table id="event-table">
								<tr>
									<td><b>Date</b>:</td>
									<td>
										<div class="table-cell">
											<span><?php echo $event_start->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i'); ?></span>
										</div>
									</td>
								</tr>
								<tr>
									<td><b>Type</b>:</td>
									<td>
										<div class="table-cell">
											<span><?php echo $event['typeName']; ?></span>
										</div>
									</td>
								</tr>
								<tr>
									<td><b>Notify Late Signups</b>:</td>
									<td>
										<div class="table-cell">
											<span><?php echo ($event['notify_late_signups'])?"Yes":"No"; ?></span>
										</div>
									</td>
								</tr>
								<tr>
									<td><b>Log Attendance</b>:</td>
									<td>
										<div class="table-cell">
											<span><?php echo ($event['log_attendance'])?"Yes":"No"; ?></span>
										</div>
									</td>
								</tr>
								<tr>
									<td><b>Raid Leader</b>:</td>
									<td>
										<div class="table-cell">
											<span><?php echo $event['leaderName']; ?></span>
										</div>
									</td>
								</tr>
								<tr>
									<td><b>Master Looter</b>:</td>
									<td>
										<div class="table-cell">
											<span><?php echo $event['looterName']; ?></span>
										</div>
									</td>
								</tr>
								<tr>
									<td><b>Buff Instructions</b>:</td>
									<td>
										<div class="table-cell">
											<span><?php echo htmlspecialchars($event['buff_instructions']); ?></span>
										</div>
									</td>
								</tr>
								<tr>
									<td><b>Meetup Instructions</b>:</td>
									<td>
										<div class="table-cell">
											<span><?php echo htmlspecialchars($event['meetup_instructions']); ?></span>
										</div>
									</td>
								</tr>
								<tr>
									<td><b>Description</b>:</td>
									<td>
										<div class="table-cell">
											<span><?php echo htmlspecialchars($event['description']); ?></span>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</article>
					<article>
						<div class="header">
							<h3>Raid Rosters</h3>
						</div>
						<div class="body">
							<div class="raid-roster-link-container">
								<h4>Confirmed Rosters</h4>
								<?php if (mysqli_num_rows($event_raid_rosters_confirmed) == 0) { ?>
									<p><i>None</i></p>
								<?php } else { 
									while ($errc = mysqli_fetch_array($event_raid_rosters_confirmed)) { ?>
										<a href="/eventRaidRoster.php?id=<?php echo $errc['id']; ?>" class="raid-roster-link"><?php echo $errc['template_name']; ?></a>
									<?php } ?>
								<?php } ?>
							</div>
							<?php if ($user['security'] >= 1 || $event['leader_id'] == $_SESSION['user_id']) { ?>
							<div id="unconfirmed-raid-rosters" class="raid-roster-link-container">
								<h4>Unconfirmed Rosters</h4>
								<p><i>Only Moderators and event Raid Leader can view</i></p> 
								<?php while ($erru = mysqli_fetch_array($event_raid_rosters_unconfirmed)) { ?>
									<a href="/eventRaidRoster.php?id=<?php echo $erru['id']; ?>" class="raid-roster-link"><?php echo $erru['template_name']; ?></a>
								<?php } ?>
							</div>
							<?php } ?>

							<?php if ($user['security'] >= 2 || $event['leader_id'] == $_SESSION['user_id']) { ?>
							<div class="raid-roster-link-container">
								<h4>Add New Roster Template</h4>
								<p><i>Only Admins and event Raid Leader can view</i></p> 
								<select class="standard-select" id="add-template-type" style="display: inline-block; max-width: 200px;">
									<?php while ($rrt = mysqli_fetch_array($raid_roster_templates)) { ?>
									<option value="<?php echo $rrt['id']; ?>"><?php echo $rrt['name']; ?></option>
									<?php } ?>
								</select>
								<input type="button" id="add-template-btn" class="standard-button" data-eventid="<?php echo $_GET['id']; ?>" value="ADD" style="display: inline-block; width: 80px;"></input>
							</div>
							<?php } ?>

						</div>
					</article>
					<article>
						<div class="header">
							<h3>Sign Ups</h3>
						</div>
						<div class="body">
							<div class="signup-detail-container">
								<div class="signup-detail">
									Total: <b><?php echo $signup_details['totalSignedUp']; ?></b>
								</div>
								<div class="signup-detail">
									DPS: <b><?php echo $signup_details['totalDPS']; ?></b>
								</div>
								<div class="signup-detail">
									Healers: <b><?php echo $signup_details['totalHealers']; ?></b>
								</div>
								<div class="signup-detail">
									Tanks: <b><?php echo $signup_details['totalTanks']; ?></b> 
								</div>
								<div class="signup-detail class-1">
									Druids: <b><?php echo $signup_details['totalDruids']; ?></b>
								</div>	
								<div class="signup-detail class-2">
									Hunters: <b><?php echo $signup_details['totalHunters']; ?></b>
								</div>
								<div class="signup-detail class-3">
									Mages: <b><?php echo $signup_details['totalMages']; ?></b>
								</div>
								<div class="signup-detail class-5">
									Priests: <b><?php echo $signup_details['totalPriests']; ?></b>
								</div>
								<div class="signup-detail class-6">
									Rogues: <b><?php echo $signup_details['totalRogues']; ?></b>
								</div>
								<div class="signup-detail class-7">
									Shamans: <b><?php echo $signup_details['totalShamans']; ?></b>
								</div>
								<div class="signup-detail class-8">
									Warlocks: <b><?php echo $signup_details['totalWarlocks']; ?></b>
								</div>
								<div class="signup-detail class-9">
									Warriors: <b><?php echo $signup_details['totalWarriors']; ?></b>
								</div>
							</div>
							<?php if ($event_start > $now) { ?>
							<div class="event-signups-container">
								<input type="button" class="event-btn signup" <?php if ($user_event_signup['type'] == 1) echo "disabled"; ?> value="SIGN UP"></input>
								<input type="button" class="event-btn callout" <?php if ($user_event_signup['type'] == 2) echo "disabled"; ?> value="CALL OUT"></input>
								<input type="button" class="event-btn note" <?php if (!$user_event_signup['type']) echo "disabled"; ?> value="NOTE"></input>
								<div class="event-signup-action-container signup">
									<form method="POST" action="/src/php/signup.php">
										<input type="hidden" name="event_id" value="<?php echo $event['id'];?>"></input>
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
										<input type="text" class="signup-action-input" name="note" placeholder="Note" value="<?php echo htmlspecialchars($user_event_signup['note']); ?>"></input>
										<br>
										<button type="submit" class="signup-action-button submit submit-signup">SIGN UP</button>
										<button type="button" class="signup-action-button cancel">CANCEL</button>
										<br>
										<p style="color: #b30000;">Please use "CALL OUT" if you are tentative, otherwise your attendance may be recorded as a no-show.</p>
									</form>
								</div>
								<div class="event-signup-action-container callout">
									<form method="POST" action="/src/php/callout.php">
										<input type="hidden" name="event_id" value="<?php echo $event['id'];?>"></input>
										<input type="text" class="signup-action-input" name="note" placeholder="Note" value="<?php echo htmlspecialchars($user_event_signup['note']); ?>"></input>
										<br>
										<button type="submit" class="signup-action-button submit submit-callout">CALL OUT</button>
										<button type="button" class="signup-action-button cancel">CANCEL</button>
									</form>
								</div>
								<div class="event-signup-action-container note">
									<form method="POST" action="/src/php/note.php">
										<input type="hidden" name="event_id" value="<?php echo $event['id'];?>"></input>
										<input type="text" class="signup-action-input" name="note" placeholder="Note" value="<?php echo htmlspecialchars($user_event_signup['note']); ?>"></input>
										<br>
										<button type="submit" class="signup-action-button submit submit-note">UPDATE</button>
										<button type="button" class="signup-action-button cancel">CANCEL</button>
									</form>
								</div>
							</div>
							<?php } ?>
							<div class="scrolling-table-container">
								<table class="event-signup-table">
									<thead>
										<tr>
											<th>Time</th>
											<th>User</th>
											<th>Rank</th>
											<th>Character</th>
											<th>Role</th>
											<th title="Number of times this player has been benched">Bench Count</th>
											<th>Note</th>
										</tr>
									</thead>
									<tbody>
										<?php while ($s = mysqli_fetch_array($signups)) { 
											$signup_timestamp = New DateTime($s['timestamp']);
										?>
										<tr>
											<td <?php if ($signup_timestamp > $late_signup) { echo 'class="late" title="Late"'; } ?>><?php echo $signup_timestamp->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d D H:i'); ?></td>
											<td><a href="/viewUser?id=<?php echo $s['user_id']; ?>" <?php if ($s['user_id'] == $_SESSION['user_id']) echo 'style="color: #ffcd55"'; ?>><?php echo $s['username']; ?></a></td>
											<td class="rank-<?php echo $s['rank']; ?>"><?php echo $s['rankName']; ?></td>
											<td class="class-<?php echo $s['class']; ?>"><?php echo $s['characterName']; ?></td>
											<td><?php echo $s['roleName']; ?></td>
											<td><?php echo $s['benchedCount']; ?></td>
											<td><?php echo $s['note']; ?></td>
										</tr>
										<?php  } ?>
									</tbody>
								</table>
							</div>
						</div>
					</article>
					<article>
						<div class="header">
							<h3>Call Outs</h3>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<table class="event-signup-table">
									<thead>
										<tr>
											<th>Time</th>
											<th>User</th>
											<th>Rank</th>
											<th>Note</th>
										</tr>
									</thead>
									<tbody>
										<?php while ($c = mysqli_fetch_array($callouts)) { 
											$callout_timestamp = New DateTime($c['timestamp']);
										?>
										<tr>
											<td <?php if ($callout_timestamp > $late_signup) { echo 'class="late" title="Late"'; } ?>><?php echo $callout_timestamp->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d D H:i');; ?></td>
											<td><a href="/viewUser?id=<?php echo $c['user_id']; ?>" <?php if ($c['user_id'] == $_SESSION['user_id']) echo 'style="color: #ffcd55"'; ?>><?php echo $c['username']; ?></a></td>
											<td class="rank-<?php echo $c['rank']; ?>"><?php echo $c['rankName']; ?></td>
											<td><?php echo $c['note']; ?></td>
										</tr>
										<?php  } ?>
									</tbody>
								</table>
							</div>
						</div>
					</article>
					<article>
						<div class="header">
							<h3>Attendance</h3>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<table class="attendance-table">
									<thead>
										<tr>
											<th>User</th>
											<th>Rank</th>
											<th>Attendance</th>
											<th>Value</th>
										</tr>
									</thead>
									<tbody>
										<?php while ($a = mysqli_fetch_array($attendance)) { ?>
										<tr>
											<td><a href="/viewUser?id=<?php echo $a['user_id']; ?>" <?php if ($a['user_id'] == $_SESSION['user_id']) echo 'style="color: #ffcd55"'; ?>><?php echo $a['username']; ?></a></td>
											<td class="rank-<?php echo $a['rank']; ?>"><?php echo $a['rankName']; ?></td>
											<td><?php echo $a['typeName']; ?></td>
											<td><?php if ($a['value'] > 0) echo "+"; else if ($a['value'] < 0) echo "-";  echo $a['value']; ?></td>
										</tr>
										<?php  } ?>
									</tbody>
								</table>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>
		<?php if ($user['security'] >= 1) { ?>
		<div class="full-overlay">
			<div class="scrolling-table-container">
				<h2>Edit Event</h2>
				<form method="POST" id="edit-event-form" action="/src/php/edit_event.php"></form>
				<input type="hidden" form="edit-event-form" name="event_id" value="<?php echo $event['id']; ?>"></input>
				<table>
					<tr>
						<td><b>Title</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="event-title" form="edit-event-form" name="event_title" value="<?php echo $event['title']; ?>" required></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Date</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="event-date" form="edit-event-form" name="event_date" value="<?php echo $event_start->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i'); ?>" required></input>
								<span>Enter using your local timezone: <b><?php echo $user['timezone_name']; ?></b>. Your local time can be changed from your Profile.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Type</b>:</td>
						<td>
							<div class="table-cell">
								<select form="edit-event-form" class="standard-select" name="event_type" required>
									<?php 
									mysqli_data_seek($event_types, 0);
									while ($et = mysqli_fetch_array($event_types)) { ?>
									<option value="<?php echo $et['id']; ?>" <?php if ($et['id'] == $event['type']) echo "selected"; ?>><?php echo $et['name']; ?></option>
									<?php } ?>
								</select>
								<span>Be sure to make the appropriate selection. Attendance credits may vary by event type.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Notify Late Signups</b>:</td>
						<td>
							<div class="table-cell">
								<input type="checkbox" class="standard-checkbox" form="edit-event-form" name="event_notify" <?php if ($event['notify_late_signups']) echo "checked"; ?>></input>
								<span>Signups added or changed within 24 hours of the event will be posted in Discord.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Log Attendance</b>:</td>
						<td>
							<div class="table-cell">
								<input type="checkbox" class="standard-checkbox" form="edit-event-form" name="event_log" <?php if ($event['log_attendance']) echo "checked"; ?>></input>
								<span>If checked then this event will be included in the admin attendance page and be able to be linked to an attendance log record.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Raid Leader</b>:</td>
						<td>
							<div class="table-cell">
								<select form="edit-event-form" class="standard-select" name="event_leader">
									<option></option>
									<?php 
									mysqli_data_seek($users, 0);
									while ($u = mysqli_fetch_array($users)) { ?>
									<option value="<?php echo $u['id']; ?>" <?php if ($u['id'] == $event['leader_id']) echo "selected"; ?>><?php echo $u['username']; ?></option>
									<?php } ?>
								</select>
								<span>Optional, leave blank is unknown.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Master Looter</b>:</td>
						<td>
							<div class="table-cell">
								<select form="edit-event-form" class="standard-select" name="event_looter">
									<option></option>
									<?php 
									mysqli_data_seek($users, 0);
									while ($u = mysqli_fetch_array($users)) { ?>
									<option value="<?php echo $u['id']; ?>" <?php if ($u['id'] == $event['looter_id']) echo "selected"; ?>><?php echo $u['username']; ?></option>
									<?php } ?>
								</select>
								<span>Optional, leave blank is unknown.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Meetup Instructions</b>:</td>
						<td>
							<div class="table-cell">
								<textarea class="standard-textarea" form="edit-event-form" name="event_meetup" style="height: 50px;" maxlength="200"><?php echo htmlspecialchars($event['meetup_instructions']); ?></textarea>
								<span>Optional but <b>recommended</b> instructions for when and where to meetup for this event.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Buff Instructions</b>:</td>
						<td>
							<div class="table-cell">
								<textarea class="standard-textarea" form="edit-event-form" name="event_buff" style="height: 50px;" maxlength="200"><?php echo htmlspecialchars($event['buff_instructions']); ?></textarea>
								<span>Optional instructions for raid buff requirements or runs prior to entering the instance.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Description</b>:</td>
						<td>
							<div class="table-cell">
								<textarea class="standard-textarea" form="edit-event-form" name="event_description" style="height: 200px;"><?php echo htmlspecialchars($event['description']); ?></textarea>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<input type="submit" class="standard-button" form="edit-event-form" value="UPDATE"></input>
			<input type="button" class="standard-button" id="close-overlay-btn" value="CANCEL"></input>
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
			$('#edit-event-btn').click(function(){
				$('.full-overlay').slideDown(250);
			});
			$('#close-overlay-btn').click(function(){
				$('.full-overlay').slideUp(250);
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
								window.location="";
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
								window.location="";
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
								window.location="";
							} else {
								alert("Oops! Something went wrong. Error code: " + d);
							}
						}
					});
				}
			});
			
			// add raid roster template
			$('#add-template-btn').click(function(){
				let template_id = $('#add-template-type').val();
				let event_id = $(this).data('eventid');
				if (confirm('Are you sure you want to add a new raid roster to this event?')) {
					$.ajax({
						url: "/src/ajax/add_event_raid_roster.php",
						type: "POST",
						data: { 'raid_roster_template_id' : template_id, 'event_id' : event_id }, 
						success: function(d) {
							if (d == 0) {
								alert("Oops! Something went wrong. Roster template was not added.");
							} else {
								$('#unconfirmed-raid-rosters').append(d);
							}
						}
					});
					
				}
			});
		</script>
	</body>
</html>
