<?php
	
	include "is_loggedin.php";

	isLoggedin();

	require_once "config.php";

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$_SESSION["grade"] = $_POST["change"];
		header("location: reset_users_password.php");
		exit;
	}

?>