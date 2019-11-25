<?php

	include "is_loggedin.php";

	isLoggedin();

	require_once "config.php";
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$sql = "UPDATE users SET grade = :grade WHERE id = :id";

		if($stmt = $pdo->prepare($sql))
		{
			$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
			$stmt->bindParam(":grade", $param_grade, PDO::PARAM_STR);

			$param_id = $_POST["send_grade"];
			$param_grade = $_POST["grade"];

			if($stmt->execute())
			{
				header("location: users.php");
				exit;
			}
		}

		unset($stmt);
		unset($pdo);
	}

?>