<?php
	if (!isset($_GET['offset']) || $_GET['offset'] < 0) {
		$_GET['offset'] = 0;
	}
	
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_1.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/all_announcements.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style-history.css"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	</head>
	<body>
		<input type="button" class="button close-button" value="CLOSE"></input>
		<h1>Announcement History</h1>
		<div class="pager">
			<a href="?offset=<?php echo $_GET['offset'] - 10; ?>">Previous Page</a>
			<a href="?offset=<?php echo $_GET['offset'] + 10; ?>">Next Page</a>
		</div>
		<table class="announcements">
			<tr>
				<th>Date</th>
				<th>By</th>
				<th>Title</th>
				<th>Content</th>
			</tr>
			<?php while ($row = mysqli_fetch_array($all_announcements)) { 
				
				$date = new DateTime($row['timestamp'], $SERVER_TIMEZONE);
				$date->setTimeZone($LOCAL_TIMEZONE);
					
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
			<tr>
				<td><?php echo $date->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i:s'); ?></td>
				<td><a href="/viewUser.php?id=<?php echo $row['user_id']; ?>" class="user-link"><?php echo $row['username']; ?></a></td>
				<td><?php echo nl2br($row['title']); ?></td>
				<td><?php echo nl2br($content); ?></td>
				<?php if ($user['security'] >= 2) {?>
				<td>
					<input type="submit" class="button delete-announcement-btn" data-id="<?php echo $row['id']; ?>" value="DELETE"></input>
				</td>
				<?php } ?>
			</tr>
			<?php } ?>
		</table>
		<script>
			// close window
			$('.close-button').click(function(){
				window.close();
			});
			
			// delete announcement
			$('.delete-announcement-btn').click(function(){
				if (confirm("Are you sure you want to delete this announcement?")) {
					
					let announcementID = $(this).data('id');
					let par = $(this).parent().parent();
					let rowData = { 'announcement_id': announcementID };
					$.ajax({
						url: "/src/php/delete_announcement.php",
						type: "POST",
						data: rowData,
						success: function(d) {
							if (d == 0) {
								par.remove();
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
