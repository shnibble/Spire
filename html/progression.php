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
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_1.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/raid_types.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/raid_progression.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
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
					<?php while ($raid = mysqli_fetch_array($raid_types)) { ?>
					<div class="raid">
						<h2><?php echo $raid['name']; ?> <span class="raid-percent-complete"><?php echo round((float)$raid['percent_complete'] * 100 ) . '%'; ?></span></h2>
						<?php 
						mysqli_data_seek($raid_progression, 0);
						while ($boss = mysqli_fetch_array($raid_progression)) {
							if ($boss['raid_type_id'] == $raid['id']) { ?>
								<div class="boss <?php if ($boss['completed']) echo "completed"; ?>">
									<div class="boss-gauge"></div>
									<div class="boss-gauge-end"></div>
									<span class="boss-name"><?php echo $boss['boss_name']; ?>
										<br>
										<span class="boss-date"><?php echo $boss['completed']; ?></span>
									</span>
									
								</div>
							<?php }
						} ?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<script>			
			
		</script>
	</body>
</html>
