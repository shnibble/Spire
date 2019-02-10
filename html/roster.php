<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/ranks.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/classes.php";
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
							<h4>Roster</h4>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<div class="ajax-table-filter">
									<h5>Filter</h5>
									<select class="standard-select ajax-table-filter-select" data-filtertype="rank">
										<option value="0">RANK</option>
										<?php while ($r = mysqli_fetch_array($ranks)) { ?>
										<option value="<?php echo $r['id']; ?>"><?php echo $r['name']; ?></option>
										<?php } ?>
									</select>
									<select class="standard-select ajax-table-filter-select" data-filtertype="class">
										<option value="0">CLASS</option>
										<?php while ($c = mysqli_fetch_array($classes)) { ?>
										<option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
										<?php } ?>
									</select>
									<select class="standard-select ajax-table-filter-select" data-filtertype="role">
										<option value="0">ROLE</option>
										<option value="1">Caster</option>
										<option value="2">Cloth</option>
										<option value="3">DPS</option>
										<option value="4">Fighter</option>
										<option value="5">Healer</option>
										<option value="6">Leather</option>
										<option value="7">Mail</option>
										<option value="8">Melee</option>
										<option value="9">Plate</option>
										<option value="10">Range</option>
										<option value="11">Tank</option>
									</select>
								</div>
								<div class="ajax-table-results">
									<span><span class="results-count">0</span> results found</span>
								</div>
								<div class="ajax-table-pager">
									<input type="button" class="standard-button ajax-table-btn page-beginning" value="<<" disabled>
									<input type="button" class="standard-button ajax-table-btn page-back" value="<" disabled>
									<span>Page <span class="ajax-table-pager-page">1</span> of <span class="ajax-table-pager-pages"></span></span>
									<input type="button" class="standard-button ajax-table-btn page-forward" value=">" disabled>
									<input type="button" class="standard-button ajax-table-btn page-end" value=">>" disabled>
								</div>
								<table id="users-table" class="ajax-table" 	data-src="/src/ajax-tables/users.php" 
																			data-limit="50" 
																			data-page="1" 
																			data-pages="1" 
																			data-sort="username" 
																			data-order="ASC" 
																			data-filtertype="" 
																			data-filtervalue=""
																			data-validfilters="rank class role">
									<thead>
										<tr>
											<th rowspan="2" class="ajax-table-header" data-sort="username">Username<span></span></th>
											<th rowspan="2" class="ajax-table-header" data-sort="character">Main<span></span></th>
											<th rowspan="2" class="ajax-table-header" data-sort="rank">Rank<span></span></th>
											<th rowspan="2">Badges</th>
											<th rowspan="1" colspan="2">Loot</th>
											<th rowspan="1" colspan="2">Attendance</th>
											<th rowspan="2" class="ajax-table-header" data-sort="30dar">30DAR<span></span></th>
											<th rowspan="2"  class="ajax-table-header" data-sort="joined">Joined<span></span></th>
											<?php if ($user['security'] >= 2) { ?>
											<th rowspan="2" class="ajax-table-header" data-sort="lastlogin">Last Login<span></span></th>
											<th rowspan="2" class="ajax-table-header" data-sort="security">Security<span></span></th>
											<th rowspan="2" class="ajax-table-header" data-sort="timezone">Timezone<span></span></th>
											<?php } ?>
										</tr>
										<tr>
											<th class="ajax-table-header" data-sort="loot">All<span></span></th>
											<th class="ajax-table-header" data-sort="30d_loot">30 Days<span></span></th>
											<th class="ajax-table-header" data-sort="attendance">All<span></span></th>
											<th class="ajax-table-header" data-sort="30d_attendance">30 Days<span></span></th>
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
