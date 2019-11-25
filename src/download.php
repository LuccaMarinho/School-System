<?php

	include "is_loggedin.php";

	isLoggedin();

	require_once "config.php";

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$sql = "SELECT file_name FROM users WHERE id = :id";

		if($stmt = $pdo->prepare($sql))
		{
			$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

			$param_id = $_POST["download"];

			if($stmt->execute())
			{
				if($stmt->rowCount() == 1)
				{
					if($row = $stmt->fetch())
					{
						$file_path = "uploads/" . $row["file_name"];
					}
		
        			header('Content-Disposition: attachment; filename=' . basename($file_path));
					readfile($file_path);
				}
				
				header("location: users.php");
				exit;
			}
		}

		unset($stmt);
		unset($pdo);
	}

?>