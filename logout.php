<?php
	require_once("includes/init.php");
	$session = Session::get_instance();
	if($session->is_logged_in())
		$session->logout();
	header("location:index.php");
