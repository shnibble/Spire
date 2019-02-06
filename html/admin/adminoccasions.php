<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/occasion_types.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/future_occasions.php";
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
					
					<div class="pre-article-container">
						<div class="left">
							<input type="submit" class="pre-article-button" id="add-occasion-btn" value="ADD OCCASIONS"></input>
						</div>
						<div class="right">
						
						</div>
					</div>
					<article>
						<div class="header">
							<h4>Future Occasions</h4>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<table id="occasions-table">
									<thead>
										<tr>
											<th>ID</th>
											<th>Date</th>
											<th>Name</th>
										</tr>
									</thead>
									<tbody>
										<?php while ($fo = mysqli_fetch_array($future_occasions)) {
										$fo_date = new DateTime($fo['date']);
										?>
										<tr>
											<td><?php echo $fo['id']; ?>
											<td><?php echo $fo_date->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i'); ?>
											<td><?php echo $fo['name']; ?>
											<td>
												<form method="POST" action="/src/php/delete_occasion.php" onsubmit="return confirm('Are you sure you want to delete this occasion?');">
													<input type="hidden" name="occasion_id" value="<?php echo $fo['id']; ?>"></input>
													<input type="submit" class="standard-button" value="DELETE"></input>
												</form>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>
		<?php if ($user['security'] >= 2) { ?>
		<div class="full-overlay">
			<div class="scrolling-table-container">
				<h2>Add Occasions</h2>
				<form method="POST" id="add-occasions-form" action="/src/php/add_occasions.php">
					<table id="add-occasions-table">
						<tr>
							<td>
								<input type="text" class="standard-input occasion-date" name="occasion_date[]"></input>
							</td>
							<td class="select-td">
								<select class="standard-select occasion-type" name="occasion_type[]">
									<option value=""></option>
									<?php 
									mysqli_data_seek($occasion_types, 0);
									while ($ot = mysqli_fetch_array($occasion_types)) {
									echo "<option value='" . $ot['id'] . "'>" . $ot['name'] . "</option>";
									} ?>
								</select>
							</td>
						</tr>
					</table>
					<span>Enter dates using your local timezone: <b><?php echo $user['timezone_name']; ?></b>. Your local time can be changed from your Profile.</span>
					<input type="submit" class="standard-button"value="SUBMIT"></input>
					<input type="button" class="standard-button" id="close-overlay-btn" value="CANCEL"></input>
				</form>
			</div>
		</div>
		<?php } ?>		
		<script>
			
			// datetime selector
			$('.occasion-date').flatpickr({
				enableTime: true,
				time_24hr: true,
				altInput: true,
				altFormat: 'Y-m-d H:i',
				dateFormat: 'Y-m-d H:i:S'
			});
			
			// toggle overlay
			$('#add-occasion-btn').click(function(){
				$('.full-overlay').slideDown(250);
			});
			$('#close-overlay-btn').click(function(){
				$('.full-overlay').slideUp(250);
			});
			
			$('#add-occasions-table').on('change', 'tr:last .occasion-date, tr:last .occasion-type', function(){
				console.log("Trigger");
				
				let _this = $(this).val();
				let _other = $(this).parent().siblings().children('input, select').val();
				
				console.log(_this + " | " + _other);
				
				if (_this != "" && _other != "") {
					console.log("Both entered");
					
					let table = $('#add-occasions-table');
					let clonedSelect =$('#add-occasions-table tr:first').children('.select-td').html();
					let newHTML = '<tr><td><input type="text" class="standard-input occasion-date occasion-date-new" name="occasion_date[]"></input></td><td>' + clonedSelect + '</td></tr>';
					table.append(newHTML);
					$('.occasion-date-new').flatpickr({
						enableTime: true,
						time_24hr: true,
						altInput: true,
						altFormat: 'Y-m-d H:i',
						dateFormat: 'Y-m-d H:i:S'
					});
					$('.occasion-date-new').removeClass('occasion-date-new');
				}
			});
		</script>
	</body>
</html>
