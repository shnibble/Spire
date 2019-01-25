<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
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
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	</head>
	<body>
		<div id="wrapper">
			<?php require $_SERVER['DOCUMENT_ROOT'] . "/src/php/header.php"; ?>
			<div id="inner-wrapper">
				<?php require $_SERVER['DOCUMENT_ROOT'] . "/src/php/navigation.php"; ?>
				<div id="content">
					<h2 class="coc-title">SPIRE CODE OF CONDUCT</h2>
					<div class="coc-row">
						<span class="coc-section">1.</span>
						<span class="coc-text">
							<b>Do not violate the <a href="https://lightshope.org/terms-of-use" target="_BLANK">Terms of Use</a></b> for our server or do anything to potentially get yourself banned; it is entirely avoidable by following a few simple rules that they have clearly defined. We need you! Getting banned can severely hinder the guild’s raid progression due to your absence.
						</span>
					</div>
					<div class="coc-row">
						<span class="coc-section">2.</span>
						<span class="coc-text">
							<b>We want to build and uphold a good name in the vanilla/classic community.</b> Refrain from any action or behavior that may damage the guild’s reputation. Everything you do can improve or damage our reputation and seeing as the population on private servers and the vanilla/classic community is mostly fluid, reputations can last longer than the lifespan of your character and even into the servers to be released by Blizzard. Memers and elitists will not be welcomed as members of the guild.
						</span>
					</div>
					<div class="coc-row">
						<span class="coc-section">3.</span>
						<span class="coc-text">
							<b>Do not initiate or engage in hateful speech</b> in any public or private channels used for the guild and the servers on which we play. While banter and rude jokes are part of what we enjoy, keep it tasteful. Misogyny, racism, homophobia, xenophobia and other forms of hate speech are not tasteful. Keep in mind that this game community includes people from all around the world; what may be a benign euphemism to one may be incredibly offensive to another.
						</span>
					</div>
					<div class="coc-row">
						<span class="coc-section">4.</span>
						<span class="coc-text">
							<b>Do not be personally mean or cruel to another player of either faction</b>, including excessive griefing or engaging in harassing behavior in public and private channels used for the guild and the servers on which we play. Ganking can be fun and trolling other players is just a part of the game, but don't take it so far as to damage the guild’s reputation with your actions. Relentlessly harassing a lower level player could very well result in the guild being listed by hit squads and raid wipers from the opposite faction which can unnecessarily hinder the guild’s leveling members and raid progression.
						</span>
					</div>
					<div class="coc-row">
						<span class="coc-section">5.</span>
						<span class="coc-text">
							<b>Administrators will enforce this Code of Conduct</b> by means of a warning sent privately to offending members or recruits. Should a member or recruit break the Code of Conduct multiple times they may be removed from the guild at the discretion of the guild’s Administrators. If you are unsure about a particular behavior or action or have any questions regarding this code of conduct, please reach out to a guild Administrator for clarification.
						</span>
					</div>
					<div class="coc-row">
						<span class="coc-section">6.</span>
						<span class="coc-text">
							<b>This Code of Conduct does not exist to create a community that is moderated and censored</b>. On the contrary, it exists to prevent the need for moderation and censorship by establishing some basic rules everyone can abide by for the comfort and enjoyment of all our members. We are here to play the game and engage in a community focused on that, not to debate political beliefs or make inflammatory statements.
						</span>
					</div>
				</div>
			</div>
		</div>
		<script>
			
		</script>
	</body>
</html>
