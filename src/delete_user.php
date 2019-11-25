<?php
	
	include "is_loggedin.php";

	isLoggedin();

	require_once "config.php";

	$sql = "DELETE FROM users WHERE id = :id";

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if($stmt = $pdo->prepare($sql))
		{
			$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

			$param_id = $_POST["delete_user"];

			if($stmt->execute())
			{
				header("location: users.php");
				exit();
			}

			unset($stmt);
			unset($pdo);
		}
	}

?>
