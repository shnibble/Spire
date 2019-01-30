<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_2.php";
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
		<script src="/src/js/stupidtable.min.js"></script>
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
							<h4>Roster</h4>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<div class="ajax-table-pager">
									<input type="button" class="standard-button ajax-table-btn page-back" value="<" disabled>
									<span>Page <span class="ajax-table-pager-page">1</span> of <span class="ajax-table-pager-pages"></span></span>
									<input type="button" class="standard-button ajax-table-btn page-forward" value=">" disabled>
								</div>
								<table id="users-table" class="ajax-table" data-src="/src/ajax-tables/users.php" data-limit="20" data-page="1" data-pages="1" data-sort="username" data-order="ASC">
									<thead>
										<tr>
											<th class="ajax-table-header" data-sort="username">Username</th>
											<th class="ajax-table-header" data-sort="character">Main</th>
											<th class="ajax-table-header" data-sort="rank">Rank</th>
											<th>Badges</th>
											<th class="ajax-table-header" data-sort="loot">Loot</th>
											<th class="ajax-table-header" data-sort="attendance">Attnd</th>
											<th class="ajax-table-header" data-sort="30dar">30DAR</th>
											<th class="ajax-table-header" data-sort="joined">Joined</th>
											<?php if ($user['security'] >= 2) { ?>
											
											<th class="ajax-table-header" data-sort="lastlogin">Last Login</th>
											<th class="ajax-table-header" data-sort="security">Security</th>
											<th class="ajax-table-header" data-sort="timezone">Timezone</th>
											
											<?php } ?>
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
		<script src="/src/js/ajaxTables.js"></script>
	</body>
</html>
