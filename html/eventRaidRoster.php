<?php
	if (!isset($_GET['id']) || $_GET['id'] == "") { 
		header("Location: /error.php?id=110");
		exit;
	}
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/check_login_token.php";
	if (!checkLoginToken($stmt)) { header("Location: /?url=" . $_SERVER['REQUEST_URI']); exit; }
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event_raid_roster.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/event_raid_roster_signups.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	if (!$valid_id) { 
		header("Location: /error.php?id=110");
		exit;
	}

	if (!$raid_roster['confirmed'] && ($user['security'] < 1 && $event['leader_id'] != $_SESSION['user_id'])) {
		header("Location: /error.php?id=113");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire Raid Roster</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style-raidtemplate.css?v=6413"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="/src/js/timeout.js"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

	<?php if ($user['security'] >= 1 || $event['leader_id'] == $_SESSION['user_id']) { ?>
		<script>
			function loadClassBays() {
				$('.character-bay.signed-up').each(function(){
					let _raidRosterID = $('#raid-roster-id').val();
					let _eventID = $('#event-id').val();
					let _this = $(this);		
					_this.html('');
					
					$.ajax({
						url: '/src/ajax/raid_roster_signed_up_characters.php', 
						type: 'POST', 
						data: {
							'event_id' : _eventID,
							'raid_roster_id' : _raidRosterID
						},
						'success': function(e) {
							_this.append(e);
							_this.children('.player').draggable({
								containment: '#content',
								helper: 'clone'
							});
							_this.append('<div class="player-placeholder"></div>');
						}
					});
				});
				
				$('.character-bay.missing').each(function(){
					let _raidRosterID = $('#raid-roster-id').val();
					let _eventID = $('#event-id').val();
					let _this = $(this);
					_this.html('');
					
					$.ajax({
						url: '/src/ajax/raid_roster_missing_non_social_characters.php', 
						type: 'POST', 
						data: {
							'event_id' : _eventID,
							'raid_roster_id' : _raidRosterID
						},
						'success': function(e) {
							_this.append(e);
							_this.children('.player').draggable({
								containment: '#content',
								helper: 'clone'
							});
							_this.append('<div class="player-placeholder"></div>');
						}
					});
				});
				
				$('.character-bay.called-out').each(function(){
					let _raidRosterID = $('#raid-roster-id').val();
					let _eventID = $('#event-id').val();
					let _this = $(this);		
					_this.html('');
					
					$.ajax({
						url: '/src/ajax/raid_roster_called_out_characters.php', 
						type: 'POST', 
						data: {
							'event_id' : _eventID,
							'raid_roster_id' : _raidRosterID
						},
						'success': function(e) {
							_this.append(e);
							_this.children('.player').draggable({
								containment: '#content',
								helper: 'clone'
							});
							_this.append('<div class="player-placeholder"></div>');
						}
					});
				});
				
				$('.character-bay.socials').each(function(){
					let _raidRosterID = $('#raid-roster-id').val();
					let _eventID = $('#event-id').val();
					let _this = $(this);
					_this.html('');
					
					$.ajax({
						url: '/src/ajax/raid_roster_missing_social_characters.php', 
						type: 'POST', 
						data: {
							'event_id' : _eventID,
							'raid_roster_id' : _raidRosterID
						},
						'success': function(e) {
							_this.append(e);
							_this.children('.player').draggable({
								containment: '#content',
								helper: 'clone'
							});
							_this.append('<div class="player-placeholder"></div>');
						}
					});
				});
			}

			function init() {
				loadClassBays();

				$('.player').draggable({
					containment: '#content',
					helper: 'clone'
				});
			
				$('.slot-dropper').droppable({
					drop: function (event, ui) {
						let _raidRosterID = $('#raid-roster-id').val();
						let _id = $(ui.draggable).data('characterid');
						let _name = $(ui.draggable).data('name');
						let _class = $(ui.draggable).data('class');
						let _rank = $(ui.draggable).data('rank');
						let _className = $(ui.draggable).data('classname');
						let _slotName = $(this).attr('id');
						let _slot = $(this).data('slotid');
						let _calledOut = $(ui.draggable).hasClass('called-out');
						console.log(_calledOut);
						
						$.ajax({
							url: '/src/ajax/update_raid_slot_character.php',
							type: 'POST',
							data: {
								'raid_roster_id' : _raidRosterID,
								'character_id' : _id,
								'slot_id' : _slot
							},
							'success': function(e){
								if (e == "0") {
									$(ui.draggable).remove();
									if (_calledOut) {
										_html = '<div id="player_' + _id + '" class="player class-' + _class + ' rank-' + _rank + ' called-out" data-characterid="' + _id + '" data-name="' + _name + '" data-class="' + _class + '" data-classname="' + _className + '" data-rank="' + _rank + '" title="Called Out"><span class="player-name">' + _name + '</span><span class="player-class">' + _className + '</span></div>';
									} else {
										_html = '<div id="player_' + _id + '" class="player class-' + _class + ' rank-' + _rank + '" data-characterid="' + _id + '" data-name="' + _name + '" data-class="' + _class + '" data-classname="' + _className + '" data-rank="' + _rank + '"><span class="player-name">' + _name + '</span><span class="player-class">' + _className + '</span></div>';
									}
									$('#' + _slotName).html('');
									$('#' + _slotName).append(_html);
									$('div#player_' + _id).draggable({
										containment: '#content',
										helper: 'clone'
									});
									loadClassBays();
								} else {
									console.log("AJAX error: " + e);
								}
							}
						});
					}
				});
				
				$('#character-bay-dump').droppable({
					drop: function (event, ui) {
						let _raidRosterID = $('#raid-roster-id').val();
						let _id = $(ui.draggable).data('characterid');
						let _name = $(ui.draggable).data('name');
						let _class = $(ui.draggable).data('class');
						let _className = $(ui.draggable).data('classname');
						
						$.ajax({
							url: '/src/ajax/remove_raid_slot_character.php',
							type: 'POST',
							data: {
								'raid_roster_id' : _raidRosterID,
								'character_id' : _id
							},
							'success': function(e){
								if (e == "0") {
									$(ui.draggable).remove();
									loadClassBays();
								} else {
									console.log("AJAX error: " + e);
								}
							}
						});
					}
				});
			}
		</script>
	<?php } else { ?>
		<script>
			function loadClassBays() {
				$('.character-bay.signed-up').each(function(){
					let _raidRosterID = $('#raid-roster-id').val();
					let _eventID = $('#event-id').val();
					let _this = $(this);		
					_this.html('');
					
					$.ajax({
						url: '/src/ajax/raid_roster_signed_up_characters.php', 
						type: 'POST', 
						data: {
							'event_id' : _eventID,
							'raid_roster_id' : _raidRosterID
						},
						'success': function(e) {
							_this.append(e);
							_this.append('<div class="player-placeholder"></div>');
						}
					});
				});
				
				$('.character-bay.missing').each(function(){
					let _raidRosterID = $('#raid-roster-id').val();
					let _eventID = $('#event-id').val();
					let _this = $(this);
					_this.html('');
					
					$.ajax({
						url: '/src/ajax/raid_roster_missing_non_social_characters.php', 
						type: 'POST', 
						data: {
							'event_id' : _eventID,
							'raid_roster_id' : _raidRosterID
						},
						'success': function(e) {
							_this.append(e);
							_this.append('<div class="player-placeholder"></div>');
						}
					});
				});
				
				$('.character-bay.called-out').each(function(){
					let _raidRosterID = $('#raid-roster-id').val();
					let _eventID = $('#event-id').val();
					let _this = $(this);		
					_this.html('');
					
					$.ajax({
						url: '/src/ajax/raid_roster_called_out_characters.php', 
						type: 'POST', 
						data: {
							'event_id' : _eventID,
							'raid_roster_id' : _raidRosterID
						},
						'success': function(e) {
							_this.append(e);
							_this.append('<div class="player-placeholder"></div>');
						}
					});
				});
				
				$('.character-bay.socials').each(function(){
					let _raidRosterID = $('#raid-roster-id').val();
					let _eventID = $('#event-id').val();
					let _this = $(this);
					_this.html('');
					
					$.ajax({
						url: '/src/ajax/raid_roster_missing_social_characters.php', 
						type: 'POST', 
						data: {
							'event_id' : _eventID,
							'raid_roster_id' : _raidRosterID
						},
						'success': function(e) {
							_this.append(e);
							_this.append('<div class="player-placeholder"></div>');
						}
					});
				});
			}

			function init() {
				loadClassBays();
			}
		</script>
	<?php } ?>
	</head>
	<body>
		<div id="header">
			<a href="/event?id=<?php echo $event['id']; ?>">Back to Event</a>
			<h1>Raid Roster</h1>
			<input type="hidden" id="event-id" value="<?php echo $event['id']; ?>" readonly></input>
			<input type="hidden" id="raid-roster-id" value="<?php echo $raid_roster['id']; ?>" readonly></input>
			<table>
				<tr>
					<td><b>Name:</b></td>
					<td><input type="text" id="raid-template-id" value="<?php echo $raid_roster['template_name'] . " (" . $raid_roster['id'] . ")"; ?>" readonly></input></td>
				</tr>
				<tr>
					<td><b>Event:</b></td>
					<td><input type="text" value="<?php echo $event['title'] . " (" . $event['id'] . ")"; ?>" readonly></input></td>
				</tr>
				<tr>
					<td><b>Confirmed:</b></td>
					<td><input type="text" value="<?php echo ($raid_roster['confirmed'])?"YES":"NO"; ?>" readonly></input></td>
					<?php if (($user['security'] >= 2 || $event['leader_id'] == $_SESSION['user_id']) && !$raid_roster['confirmed']) { ?>
						<td><input type="button" id="confirm-raid-roster-btn" value="CONFIRM"></input></td>
					<?php } ?>
				</tr>
			</table>
			
			<?php if ($user['security'] >= 2 || $event['leader_id'] == $_SESSION['user_id']) { ?>
				<br>
				<input type="button" id="delete-raid-roster-btn" value="DELETE TEMPLATE"></input>
			<?php } ?>
		</div>

		<div id="content">

			<?php if ($user['security'] >= 1 || $event['leader_id'] == $_SESSION['user_id']) { ?>
			<div id="character-bay-dump">
				<span>DROP HERE TO RETURN PLAYER TO ROSTER</span>
			</div>
			<?php } ?>

			<div class="raid-template">
				<?php
				$group = 1;
				
				while ($group <= 8) {
					$player = 1;
					
					echo "<div class='group'>";
					echo "	<h2>Group " . $group . "</h2>";
					
					while ($player <= 5) {
						$slot = mysqli_fetch_array($raid_roster_slots);
						
						echo "<div class='slot'>";
						echo "	<span class='slot-description'>" . $slot['type_description'] . "</span>";
						echo "	<div id='g" . $group . "_p" . $player . "' class='slot-dropper' data-slotid='" . $slot['id'] . "'>";
						
						if ($slot['character_id'] != "") {
							if ($slot['signup_status'] == 2) {
								echo "<div id='player_" . $slot['character_id'] . "' class='player class-" . $slot['character_class'] . " rank-" . $slot['rank'] . " called-out' data-characterid='" . $slot['character_id'] . "' data-name='" . $slot['character_name'] . "' data-class='" . $slot['character_class'] . "' data-classname='" . $slot['character_class_name'] . "' data-rank='" . $slot['rank'] . "' title='Called Out'>";
							} else {
								echo "<div id='player_" . $slot['character_id'] . "' class='player class-" . $slot['character_class'] . " rank-" . $slot['rank'] . "' data-characterid='" . $slot['character_id'] . "' data-name='" . $slot['character_name'] . "' data-class='" . $slot['character_class'] . "' data-classname='" . $slot['character_class_name'] . "' data-rank='" . $slot['rank'] . "'>";
							}
							echo "<span class='player-name'>" . $slot['character_name'] . "</span>";
							echo "<span class='player-class'>" . $slot['character_class_name'] . "</span>";
							echo "</div>";
						}
						
						echo "	</div>";
						echo "</div>";
						
						$player++;
					}
					echo "</div>";
					$group++;
				} ?>
			</div>
			<div class="character-bays">
				<div class="character-bay-container">
					<h2>Signed Up</h2>
					<div class="character-bay signed-up" data-eventid="<?php echo $event['id']; ?>" data-raidtemplateid="<?php echo $raid_template['id']; ?>"></div>
				</div>
			</div>
			<div class="character-bays">
				<div class="character-bay-container">
					<h2>Missing</h2>
					<div class="character-bay missing" data-eventid="<?php echo $event['id']; ?>" data-raidtemplateid="<?php echo $raid_template['id']; ?>"></div>
				</div>
			</div>
			<div class="character-bays">
				<div class="character-bay-container">
					<h2>Called Out</h2>
					<div class="character-bay called-out" data-eventid="<?php echo $event['id']; ?>" data-raidtemplateid="<?php echo $raid_template['id']; ?>"></div>
				</div>
				<div class="character-bay-container">
					<h2>Socials</h2>
					<div class="character-bay socials" data-eventid="<?php echo $event['id']; ?>" data-raidtemplateid="<?php echo $raid_template['id']; ?>"></div>
				</div>
			</div>
		</div>
		<script>
		init();
		$('#delete-raid-roster-btn').click(function(){
			if (confirm("Are you sure you want to delete this event raid roster? All work will be lost permanently.")) {
				
				let rosterID = $('#raid-roster-id').val();
				let eventID = $('#event-id').val();
				$.ajax({
					url: "/src/ajax/delete_event_raid_roster.php",
					type: "POST",
					data: { 'raid_roster_id' : rosterID, 'event_id' : eventID },
					success: function(d) {
						if (d == 0) {
							window.location = "/event.php?id=" + eventID;
						} else {
							alert("Oops! Something went wrong. Error code: " + d);
						}
					}
				});
			}
		});
		
		$('#confirm-raid-roster-btn').click(function(){
			if (confirm("Are you sure you want to confirm the event raid roster? This will make the roster visible to all users and cannot be undone.")) {
				
				let rosterID = $('#raid-roster-id').val();
				let eventID = $('#event-id').val();
				$.ajax({
					url: "/src/ajax/confirm_event_raid_roster.php",
					type: "POST",
					data: { 'raid_roster_id' : rosterID, 'event_id' : eventID },
					success: function(d) {
						if (d == 0) {
							window.location.reload();
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
