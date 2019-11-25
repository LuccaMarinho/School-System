<?php

	function isLoggedin()
	{
		session_start();

		if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
		{
			header("location:login.php");
			exit;
		}
	}

?>