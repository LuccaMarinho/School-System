<?php
	
	include "is_loggedin.php";

	isLoggedin();

	require_once "config.php";

	$sql = "UPDATE users SET file_name = :file_name, grade = :grade WHERE id = :id";

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if($stmt = $pdo->prepare($sql))
		{
			$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
			$stmt->bindParam(":file_name", $param_file_name, PDO::PARAM_STR);
			$stmt->bindParam(":grade", $param_grade, PDO::PARAM_STR);

			$param_id = $_POST["delete_task"];
			$param_file_name = "Não Enviado";
			$param_grade = "0";


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