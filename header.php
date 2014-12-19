<?php
	$user = Session::get_instance()->logged_in_user();
?>
<div class="w-container">
	<a class="w-nav-brand" href="#"></a>
	<nav class="w-nav-menu" role="navigation">
		<a class="w-nav-link nav" href="profile.php">Home</a>
		<?php
			if($user->admin == Employee::MASTER_ADMIN):
		?>
		<a class="w-nav-link nav" href="manage.php">Manage site</a>
		<a class="w-nav-link nav" href="users.php">Manage users</a>
		<?php endif;?>
		<?php
			if($user->admin == Employee::MASTER_ADMIN || $user->admin > 0):
		?>
		<a class="w-nav-link nav" href="complaints.php">View complaints</a>
		<?php endif;?>
		<a class="w-nav-link nav" href="user-profile.php">Profile</a>
		<a class="w-nav-link nav" href="logout.php">Logout</a>
	</nav>
	<div class="w-nav-button">
		<div class="w-icon-nav-menu"></div>
	</div>
</div>
