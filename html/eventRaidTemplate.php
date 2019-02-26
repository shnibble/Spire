<?php
	if (!isset($_GET['id']) || $_GET['id'] == "") { 
		header("Location: /error.php?id=110");
		exit;
	}
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_1.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/raid_template.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/raid_template_event_signups.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	if (!$valid_id) { 
		header("Location: /error.php?id=110");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire Raid Template</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style-raidtemplate.css?v=6413"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="/src/js/timeout.js"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
		<script>
		$(init);
		
		function loadClassBays() {
			$('.character-bay.signed-up').each(function(){
				let _this = $(this);
				let _eventID = $(this).data('eventid');
				let _raidTemplateID = $(this).data('raidtemplateid');				
				_this.html('');
				
				$.ajax({
					url: '/src/ajax/raid_template_signed_up_characters.php', 
					type: 'POST', 
					data: {
						'event_id' : _eventID,
						'raid_template_id' : _raidTemplateID
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
				let _this = $(this);
				let _eventID = $(this).data('eventid');
				let _raidTemplateID = $(this).data('raidtemplateid');		
				_this.html('');
				
				$.ajax({
					url: '/src/ajax/raid_template_missing_non_social_characters.php', 
					type: 'POST', 
					data: {
						'event_id' : _eventID,
						'raid_template_id' : _raidTemplateID
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
				let _this = $(this);
				let _eventID = $(this).data('eventid');
				let _raidTemplateID = $(this).data('raidtemplateid');				
				_this.html('');
				
				$.ajax({
					url: '/src/ajax/raid_template_called_out_characters.php', 
					type: 'POST', 
					data: {
						'event_id' : _eventID,
						'raid_template_id' : _raidTemplateID
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
				let _this = $(this);
				let _eventID = $(this).data('eventid');
				let _raidTemplateID = $(this).data('raidtemplateid');		
				_this.html('');
				
				$.ajax({
					url: '/src/ajax/raid_template_missing_social_characters.php', 
					type: 'POST', 
					data: {
						'event_id' : _eventID,
						'raid_template_id' : _raidTemplateID
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
					let _templateID = $('#raid-template-id').val();
					let _id = $(ui.draggable).data('characterid');
					let _name = $(ui.draggable).data('name');
					let _class = $(ui.draggable).data('class');
					let _rank = $(ui.draggable).data('rank');
					let _className = $(ui.draggable).data('classname');
					let _slotName = $(this).attr('id');
					let _slot = $(this).data('slotid');
					
					$.ajax({
						url: '/src/php/update_raid_slot_character.php',
						type: 'POST',
						data: {
							'template_id' : _templateID,
							'character_id' : _id,
							'slot_id' : _slot
						},
						'success': function(e){
							if (e == "0") {
								$(ui.draggable).remove();
								_html = '<div id="player_' + _id + '" class="player class-' + _class + ' rank-' + _rank + '" data-characterid="' + _id + '" data-name="' + _name + '" data-class="' + _class + '" data-classname="' + _className + '" data-rank="' + _rank + '"><span class="player-name">' + _name + '</span><span class="player-class">' + _className + '</span></div>';
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
					let _id = $(ui.draggable).data('characterid');
					let _name = $(ui.draggable).data('name');
					let _class = $(ui.draggable).data('class');
					let _className = $(ui.draggable).data('classname');
					
					$.ajax({
						url: '/src/php/remove_raid_slot_character.php',
						type: 'POST',
						data: {
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
	</head>
	<body>
		<div id="header">
			<a href="/event?id=<?php echo $event['id']; ?>">Back to Event</a>
			<h1>Raid Template</h1>
			<table>
				<tr>
					<td><b>Raid Template ID:</b></td>
					<td><input type="number" id="raid-template-id" value="<?php echo $raid_template['id']; ?>" readonly></input></td>
				</tr>
				<tr>
					<td><b>Raid Template Name:</b></td>
					<td><input type="text" value="<?php echo $raid_template['template_name']; ?>" readonly></input></td>
				</tr>
				<tr>
					<td><b>Event ID:</b></td>
					<td><input type="text" id="event-id" value="<?php echo $event['id']; ?>" readonly></input></td>
				</tr>
				<tr>
					<td><b>Event:</b></td>
					<td><input type="text" value="<?php echo $event['title']; ?>" readonly></input></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="button" id="delete-raid-template-btn" value="DELETE TEMPLATE"></input></td>
				</tr>
			</table>
		</div>
		<div id="content">
			
			<div id="character-bay-dump">
				<span>DROP HERE TO RETURN PLAYER TO ROSTER</span>
			</div>
			<div class="raid-template">
				<?php
				$group = 1;
				
				while ($group <= 8) {
					$player = 1;
					
					echo "<div class='group'>";
					echo "	<h2>Group " . $group . "</h2>";
					
					while ($player <= 5) {
						$slot = mysqli_fetch_array($raid_template_slots);
						
						echo "<div class='slot'>";
						echo "	<span class='slot-description'>" . $slot['type_description'] . "</span>";
						echo "	<div id='g" . $group . "_p" . $player . "' class='slot-dropper' data-slotid='" . $slot['id'] . "'>";
						
						if ($slot['character_id'] != "") {
							echo "<div id='player_" . $slot['character_id'] . "' class='player class-" . $slot['character_class'] . " rank-" . $slot['rank'] . "' data-characterid='" . $slot['character_id'] . "' data-name='" . $slot['character_name'] . "' data-class='" . $slot['character_class'] . "' data-classname='" . $slot['character_class_name'] . "' data-rank='" . $slot['rank'] . "'>";
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
		$('#delete-raid-template-btn').click(function(){
				if (confirm("Are you sure you want to delete this raid event template? All work will be lost permanently.")) {
					
					let templateID = $('#raid-template-id').val();
					let eventID = $('#event-id').val();
					$.ajax({
						url: "/src/ajax/delete_event_raid_template.php",
						type: "POST",
						data: { 'raid_template_id' : templateID },
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
		</script>
	</body>
</html>
