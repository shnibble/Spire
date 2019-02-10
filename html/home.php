<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/badges.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_1.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/announcements.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style.css?v=531535"></link>
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
							<h3>Information</h3>
						</div>
						<div class="body">
							<a class="information-link" href="/charter">Charter</a>
							<a class="information-link" href="/codeOfConduct">Code of Conduct</a>
							<a class="information-link" href="/progression">Raid Progression</a>
						</div>
					</article>
					<div class="pre-article-container">
						<div class="left">
							<?php if ($user['security'] >= 2) { ?>
							<input type="submit" class="pre-article-button" id="add-announcement-btn" value="ADD ANNOUNCEMENT"></input>
							<?php } ?>
						</div>
						<div class="right">
							<a href="/announcementHistory.php" class="pre-article-link" id="history-link">Announcement History</a>
						</div>
					</div>
					<article>
						<div class="header">
							<h3>Announcements</h3>
						</div>
						<div class="body">
							<?php 
							$today = new DateTime();
							$today->setTimeZone($LOCAL_TIMEZONE);
							$yesterday = new DateTime();
							$yesterday->modify('-1 days');
							$yesterday->setTimeZone($LOCAL_TIMEZONE);
							$lastWeek = new DateTime();
							$lastWeek->modify('-6 days');
							$lastWeek->setTimeZone($LOCAL_TIMEZONE);
							$article_count = 0;
							
							while ($row = mysqli_fetch_array($announcements)) { 
								$article_count++;
								
								// process posted date
								$date = new DateTime($row['timestamp'], $SERVER_TIMEZONE);
								$date->setTimeZone($LOCAL_TIMEZONE);
								
									// today
									if($today->format('d-m-Y') == $date->format('d-m-Y')) {
										$date_formatted = "Today at " . $date->format('g:i A');
									}					
									// yesterday
									else if($yesterday->format('d-m-Y') == $date->format('d-m-Y')) {
										$date_formatted = "Yesterday at " . $date->format('g:i A');
									}					
									// within 6 days
									else if($lastWeek < $date) {
										$date_formatted = $date->format('l') . " at " . $date->format('g:i A');
									}
									// older than 7 days
									else {
										$date_formatted = $date->format('m/d/Y') . " at " . $date->format('g:i A');
									}
								
								// format content using Discord markdown
								$content = $row['content'];
								// replace angle braces
								$content = preg_replace('#\<{1}(.*?)\>{1}#', '&lt$1&gt', $content);
								// *** = strong
								$content = preg_replace('#\*{3}(.*?)\*{3}#', '<b><i>$1</i></b>', $content);
								// ** = bold
								$content = preg_replace('#\*{2}(.*?)\*{2}#', '<b>$1</b>', $content);
								// * = italic
								$content = preg_replace('#\*{1}(.*?)\*{1}#', '<i>$1</i>', $content);
								// __ = underline
								$content = preg_replace('#\_{2}(.*?)\_{2}#', '<u>$1</u>', $content);
								// ` = code
								$content = preg_replace('#\`{1}(.*?)\`{1}#', '<code>$1</code>', $content);
								
							?>
							<div class="announcement">
								<div class="announcement-header">
									<span class="announcement-name rank-<?php echo $user['rank']; ?>"><?php echo $row['username']; ?></span>
									<div class="user-badges-container">
										<?php if ($user['badges']) {
										$_user_badges = explode(",", $user['badges']);
										foreach ($_user_badges as $b) { ?>
										<div class="user-badge" style="background-image: url('/src/img/badge_<?php echo $b; ?>.png');" title="<?php echo htmlspecialchars($badges_array[$b]['name']) . "\n" . htmlspecialchars($badges_array[$b]['description']); ?>"></div>
										<?php }
										} ?>
									</div>
									<span class="announcement-date"><?php echo $date_formatted; ?></span>
								</div>
								<div class="content">
									<h3><?php echo htmlspecialchars($row['title']);?></h3>
									<?php echo nl2br($content); ?>
								</div>
								<?php if ($user['security'] >= 2) {?>
								<input type="button" class="article-footer-button delete-announcement-btn" data-id="<?php echo $row['id']; ?>" value="DELETE"></input>
								<?php } ?>
							</div>
							<?php } ?>
						</div>
					</article>
				</div>
			</div>
		</div>
		<?php if ($user['security'] >= 2) { ?>
		<div class="full-overlay">
			<div class="scrolling-table-container">
				<h2>Add Announcement</h2>
				<form method="POST" id="add-announcement-form" action="/src/php/add_announcement.php"></form>
				<table>
					<tr>
						<td><b>Title</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="announcement-title" form="add-announcement-form" name="announcement_title" maxlength="100" required></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Content</b>:</td>
						<td>
							<div class="table-cell">
								<textarea type="text" class="standard-textarea" id="announcement-date" form="add-announcement-form" name="announcement_content" style="height: 400px;" maxlength="890" required></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Bridge to Discord?</b>:</td>
						<td>
							<div class="table-cell">
								<input type="checkbox" class="standard-checkbox" id="announcement-bridge" form="add-announcement-form" name="bridge"></input>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" class="standard-button" form="add-announcement-form" value="SUBMIT"></input>
							<input type="button" class="standard-button" id="close-overlay-btn" value="CANCEL"></input>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<?php } ?>
		<script>			
			// toggle add-announcement overlay
			$('#add-announcement-btn').click(function(){
				$('.full-overlay').slideDown(250);
			});
			$('#close-overlay-btn').click(function(){
				$('.full-overlay').slideUp(250);
			});
			
			// open announcement history window
			$('#history-link').click(function() {
				window.open($(this).attr('href'),'title', 'width=940, height=700');
				return false;
			});  
			
			// delete announcement
			$('.delete-announcement-btn').click(function(){
				if (confirm("Are you sure you want to delete this announcement?")) {
					
					let announcementID = $(this).data('id');
					let par = $(this).parent();
					let rowData = { 'announcement_id': announcementID };
					$.ajax({
						url: "/src/php/delete_announcement.php",
						type: "POST",
						data: rowData,
						success: function(d) {
							if (d == 0) {
								par.slideUp(250);
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
