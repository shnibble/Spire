<a id="nav-open">
	<div></div><div></div><div></div>
</a>
<div id="navigation">
	<div id="nav-header">
		<h1>Spire Guild</h1>
	</div>
	<a id="nav-close">
		<div></div><div></div>
	</a>
	<a class="item <?php echo($pageName=='home')?'active':''; ?>" href="/home">
		<div class="nav-icon home">
			<div></div><div></div><div></div><div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">WHAT'S NEW?</span>
			<span class="nav-description">News and Announcements</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<a class="item <?php echo($pageName=='charter')?'active':''; ?>" href="/charter">
		<div class="nav-icon charter">
			<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">CHARTER</span>
			<span class="nav-description">The guild's charter</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<a class="item <?php echo($pageName=='codeOfConduct')?'active':''; ?>" href="/codeOfConduct">
		<div class="nav-icon coc">
			<div></div><div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">CODE OF CONDUCT</span>
			<span class="nav-description">The guild's Code of Conduct</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	
	<?php 
	// guild member pages
	if ($user['rank'] >= 2) { ?>
	<a class="item <?php echo($pageName=='calendar')?'active':''; ?>" href="/calendar">
		<div class="nav-icon calendar">
			<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">EVENTS CALENDAR</span>
			<span class="nav-description">Sign up for raids and other events</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<a class="item <?php echo($pageName=='roster')?'active':''; ?>" href="/roster">
		<div class="nav-icon roster">
			<div></div><div></div><div></div><div></div><div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">ROSTER</span>
			<span class="nav-description">Search and browse our team</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<a class="item <?php echo($pageName=='loot')?'active':''; ?>" href="/loot">
		<div class="nav-icon loot">
			<div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">LOOT LOG</span>
			<span class="nav-description">Log of all looted items</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<a class="item <?php echo($pageName=='items')?'active':''; ?>" href="/items">
		<div class="nav-icon items">
			<div></div><div></div><div></div><div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">ITEMS</span>
			<span class="nav-description">Items database and loot priority</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<a class="item <?php echo($pageName=='buffs')?'active':''; ?>" href="/buffs">
		<div class="nav-icon buffs">
			<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">WORLD BUFFS</span>
			<span class="nav-description">World buff information</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<a class="item <?php echo($pageName=='feed')?'active':''; ?>" href="/feed">
		<div class="nav-icon feed">
			<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">ACTIVITY FEED</span>
			<span class="nav-description">What is happening?</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<?php } else { ?>
	<p class="nav-guest-msg">Hello Guest! You need to be promoted to a member rank to view more content. Please contact an Admin in Spire's Discord to be promoted.</p>
	<p class="nav-guest-msg">In the meantime, feel free to setup your account profile and alt characters.</p>
	<?php } ?>
	
	<?php 
	// admin pages
	if ($user['security'] >= 2) { ?>
	<div class="category">
		<span>--ADMIN</span>
	</div>
	<a class="item <?php echo($pageName=='adminattendance')?'active':''; ?>" href="/admin/adminattendance">
		<div class="nav-icon adminattendance">
			<div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">ATTENDANCE</span>
			<span class="nav-description">Log event attendance</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<a class="item <?php echo($pageName=='adminoccasions')?'active':''; ?>" href="/admin/adminoccasions">
		<div class="nav-icon adminoccasions">
			<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">OCCASIONS</span>
			<span class="nav-description">Set server events and raid resets</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<a class="item <?php echo($pageName=='adminusers')?'active':''; ?>" href="/admin/adminusers">
		<div class="nav-icon adminusers">
			<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">USERS</span>
			<span class="nav-description">Manage users</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<?php } ?>
	
	<div class="category">
		<span>--PERSONAL</span>
	</div>
	<a class="item <?php echo($pageName=='profile')?'active':''; ?>" href="/profile">
		<div class="nav-icon profile">
			<div></div><div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">YOUR PROFILE</span>
			<span class="nav-description">Update your account and information</span>
		</div>
		<div class="nav-carrot"></div>
	</a>
	<a class="item" href="/logout">
		<div class="nav-icon logout">
			<div></div><div></div>
		</div>
		<div class="nav-text">
			<span class="nav-title">LOGOUT</span>
			<span class="nav-description">Logout from your account</span>
		</div>
	</a>
	<div class="category">
		<span>--IMPORTANT LINKS</span>
	</div>
	<a class="link" href="<?php echo $config['guild_discord_link']; ?>" target="_BLANK">
		<span class="nav-title">OUR DISCORD</span>
	</a>
	<a class="link" href="<?php echo $config['server_discord_link']; ?>" target="_BLANK">
		<span class="nav-title">LIGHTSHOPE DISCORD</span>
	</a>
	<a class="link" href="<?php echo $config['server_website_link']; ?>" target="_BLANK">
		<span class="nav-title">LIGHTSHOPE WEBSITE</span>
	</a>
</div>
<script>
	$('#nav-open').click(function(){
		$('#navigation').addClass('active');
	});
	$('#nav-close').click(function(){
		$('#navigation').removeClass('active');
	});
</script>
