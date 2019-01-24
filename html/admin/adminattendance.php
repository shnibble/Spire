<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/unlogged_events.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event_types.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/attendance_types.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_event.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user_event_signups.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style.css"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
					<article>
						<div class="header">
							<h4>Log Attendance</h4>
						</div>
						<div class="body">
							<form method="POST" id="log-attendance-form" action="/src/php/log_attendance.php" onsubmit="return confirm('Are you sure you want to log this attendance?');"></form>
							<div class="scrolling-table-container">
								<table id="admin-attendance-table">
									<tr>
										<td><b>Link to Event</b>:</td>
										<td>
											<div class="table-cell">
												<form method="POST" action="">
													<select class="standard-select" name="event" onchange="this.form.submit()">
														<option>**NONE**</option>
														<?php while ($ue = mysqli_fetch_array($uEvents)) { 
														$ue_date = new DateTime($ue['start']);
														
														?> 
														<option value="<?php echo $ue['id']; ?>" <?php if(isset($_POST['event']) && $_POST['event'] == $ue['id']) echo "selected"; ?>><?php echo $ue_date->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d') . " " . substr($ue['title'], 0, 50) . " (" . $ue['id'] . ")"; ?></option>
														 <?php } ?>
													</select>
												</form>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Title</b>:</td>
										<td>
											<div class="table-cell">
												<input type="hidden" form="log-attendance-form" name="event_id" <?php if ($valid_event) { echo 'value="' . $vEvent['id'] . '" readonly'; } ?>></input>
												<input type="text" class="standard-input" id="event-title" form="log-attendance-form" name="event_title" <?php if ($valid_event) { echo 'value="' . $vEvent['title'] . '" readonly'; } ?> required></input>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Type</b>:</td>
										<td>
											<div class="table-cell">
												<select form="log-attendance-form" class="standard-select" id="event-type" name="event_type">
													<?php 
													mysqli_data_seek($event_types, 0);
													while ($et = mysqli_fetch_array($event_types)) { ?>
													<option value="<?php echo $et['id']; ?>" <?php if ($valid_event && $vEvent['type'] == $et['id']) { echo "selected"; } else if ($valid_event) { echo "disabled"; } ?>><?php echo $et['name']; ?></option>
													<?php } ?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Date</b>:</td>
										<td>
											<div class="table-cell">
												<input type="text" class="standard-input" id="event-date" form="log-attendance-form" name="event_date"
												<?php if ($valid_event) { 
													$ve_date = new DateTime($vEvent['start']);
													echo 'value="' . $ve_date->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i') . '" readonly'; 
												} ?> required></input>
												<span><i>Entered in your local time (<?php echo $user['timezone_name']; ?>).</i></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Bulk Update</b>:</td>
										<td>
											<div class="table-cell">
												<textarea id="bulk-update-values" class="standard-textarea" style="height: 200px;" style="height: 200px;"></textarea>
												<select id="bulk-update-type" class="standard-select">
													<?php 
													mysqli_data_seek($attendance_types, 0);
													while ($at = mysqli_fetch_array($attendance_types)) { ?>
													<option value="<?php echo $at['id']; ?>"><?php echo $at['name'] . " (" . $at['code'] . ")"; ?></option>
													<?php } ?>
												</select>
												<p>Enter space/comma/newline separated character names to mass update the below records. Note that this will not submit the final attendance log, just <i>set</i> the attendance type for users listed below.</p>
												<input type="submit" id="bulk-update-btn" class="standard-button" value="UPDATE RECORDS"></input>
											</div>
										</td>
									</tr>
								</table>
								<br>
								<div id="missing-users"></div>
								<input type="submit" form="log-attendance-form" id="log-attendance-btn" class="standard-button" value="LOG ATTENDANCE"></input>
								<table id="attendance-log-table">
									<thead>
										<tr>
											<th>User</th>
											<th>Rank</th>
											<th>Character(s)</th>
											<th>Sign Up</th>
											<th>Attendance
												<div>
													<?php
													mysqli_data_seek($attendance_types, 0);
													while ($at = mysqli_fetch_array($attendance_types)) { ?>
														<input type="button" class="table-header-button" data-id="<?php echo $at['id']; ?>" value="<?php echo $at['code']; ?>" title="<?php echo $at['name']; ?>"></input>
													<?php } ?>
												</div>
											</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php 
									$i = 0;
									while ($ues = mysqli_fetch_array($user_event_signups)) { 
										if (isset($_POST['event'])) {
											switch ($ues['signupStatus']) {
												case 1:
													$signupStatus = "Signed Up";
													break;
												case 2:
													$signupStatus = "Called Out";
													break;
												default:
													$signupStatus = "Missed";
													break;
											}
										} else {
											$signupStatus = "None";
										}
										
										$chars = explode(",", $ues['characters']);
									?>
										<tr class="user-row">
											<td><?php echo $ues['username']; ?></td>
											<td><?php echo $ues['rankName']; ?></td>
											<td class="characters-td"><?php foreach ($chars as $c) {
												echo "<p class='character-name' value='" . $c . "'>" . $c . "</p>";
											} ?></td>
											<td title="<?php echo $ues['signupNote']; ?>"><?php echo $signupStatus; ?></td>
											<td class="radios-td">
												<?php
												mysqli_data_seek($attendance_types, 0);
												while ($at = mysqli_fetch_array($attendance_types)) { ?>
													<input type="hidden" name="user_id[<?php echo $i; ?>]" value="<?php echo $ues['id']; ?>" form="log-attendance-form"></input>
													<input type="radio" name="attnd[<?php echo $i; ?>]" id="attnd-radio-<?php echo $ues['id'] . "-" . $at['id']; ?>"  form="log-attendance-form" class="standard-radio hidden usr-attnd" value="<?php echo $at['id']; ?>" <?php if ($ues['signupStatus'] == 2 && $at['id'] == 2) { echo "checked"; } ?>></input>
													<label for ="attnd-radio-<?php echo $ues['id'] . "-" . $at['id']; ?>" class="standard-radio-label" title="<?php echo $at['name']; ?>"><?php echo $at['code']; ?></label>
												<?php } ?>
											</td>
											<td>
												<input type="button" class="clear-attendance-btn" value="CLEAR"></input>
											</td>
										</tr>
									<?php 
									$i++;
									} ?>
									</tbody>
								</table>
							</div>
						</div>
					</article>						
				</div>
			</div>
		</div>
		<script>
			$('#bulk-update-btn').click(function(){
				let type = $('#bulk-update-type').val();
				let values = $('#bulk-update-values').val().toLowerCase().split(/[\s,;\t\n]+/);
				let rows = $('.user-row');
				
				// iterate through each account row
				rows.each(function(){
					let found = false;
					
					// iterate through user character names
					let userCharacters = $(this).children('.characters-td').children('.character-name');
					userCharacters.each(function(){
						let characterName = $(this).attr('value').toLowerCase();
						
						// check if character name found in bulk update list
						let index = jQuery.inArray(characterName, values);
						if (index !== -1) {
							values.splice(index, 1);
							found = true;
						}
					});
					
					// update radio button to match bulk update type
					if (found) {
						$(this).children('.radios-td').children('input[value=' + type + ']').prop('checked', true);
					}
				});
				
				// remove blank-space values
				values = values.filter(function(v){return v!==''});
				
				// if leftover values, print to page
				if (values.length > 0) {
					$('#missing-users').addClass('active');
					$('#missing-users').text(values.join(", "));
				} else {
					$('#missing-users').removeClass('active');
					$('#missing-users').text("");
				}
				
			});
			
			// datetime selector
			$('#event-date').flatpickr({
				enableTime: true,
				time_24hr: true,
				altInput: true,
				altFormat: 'Y-m-d H:i',
				dateFormat: 'Y-m-d H:i:S'
			});
			
			// clear attendance button
			$('.clear-attendance-btn').click(function(){
				$(this).parent().siblings('.radios-td').children('input').prop('checked', false);
			});
			
			// set all attendance buttons
			$('.table-header-button').click(function(){
				let x = $(this).data('id');
				$('.usr-attnd[value=' + x + ']').prop('checked', true)
			});
			
			// validate attendance log date
			$('#log-attendance-btn').click(function(e){
				console.log("#event-date = " + $('#event-date').val());
				if ($('#event-date').val() <= 0 || $('#event-date') == null) {
					e.preventDefault();
					alert("Please enter a valid date for this attendance log.");
				}
			});
		</script>
	</body>
</html>
