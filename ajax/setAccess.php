<?php
	require_once("../includes/init.php");
	if(isset($_POST['id']) && isset($_POST['access']))
	{
		$user = Employee::find_by_id($_POST['id']);
		$user->admin = $_POST['access'];
		$user->save();
	}
