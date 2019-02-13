<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/buff_loot_available.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/buff_loot_unavailable.php";
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
							<h4>World Buffs Queue</h4>
						</div>
						<div class="body">
							<table class="buffs-table">
								<thead>
									<tr>
										<th>Received</th>
										<th>Character</th>
										<th>Item</th>
										<?php if ($user['security'] >= 1) { ?>
										<th></th>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<?php while ($bl = mysqli_fetch_array($buff_loot_available)) { 
										$bl_timestamp = new DateTime($bl['timestamp']);
										?>
										<tr>
											<td><?php echo $bl_timestamp->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i:s'); ?></td>
											<td><?php echo $bl['character_name']; ?></td>
											<td><a href="https://classicdb.ch/?item=<?php echo $bl['item_id']; ?>" target="_BLANK" class="quality-<?php echo $bl['quality']; ?>"><?php echo $bl['item_name']; ?></a></td>
										<?php if ($user['security'] >= 1) { ?>
										<td>
											<form method="POST" action="/src/php/turn_in_buff_item.php" onsubmit="return confirm('Are you sure you want to turn in this buff item?');">
												<input type="hidden" name="loot_id" value="<?php echo $bl['id']; ?>"></input>
												<input type="submit" class="standard-button" value="TURN IN"></input>
											</form>
										</td>
										<?php } ?>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</article>
					<article>
						<div class="header">
							<h4>Turned In Buffs</h4>
						</div>
						<div class="body">
							<table class="buffs-table">
								<thead>
									<tr>
										<th>Received</th>
										<th>Character</th>
										<th>Item</th>
										<th>Turned In</th>
									</tr>
								</thead>
								<tbody>
									<?php while ($bl = mysqli_fetch_array($buff_loot_unavailable)) { 
										$bl_timestamp = new DateTime($bl['timestamp']);
										$bl_turned_in = new DateTime($bl['turned_in']);
									?>
									<tr>
										<td><?php echo $bl_timestamp->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i:s'); ?></td>
										<td><?php echo $bl['character_name']; ?></td>
										<td><a href="https://classicdb.ch/?item=<?php echo $bl['item_id']; ?>" target="_BLANK" class="quality-<?php echo $bl['quality']; ?>"><?php echo $bl['item_name']; ?></a></td>
										<td><?php echo $bl_turned_in->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i:s'); ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</article>
				</div>
			</div>
		</div>
	</body>
</html>
