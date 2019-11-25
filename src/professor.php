<?php

	include "is_loggedin.php";

	isLoggedin();

	require_once "config.php";

	$ids = array();
	$names = array();
	$grades = array();
	$tasks = array();
	$current_id;
	$aux = 0;
	$error_msg = "";

	$sql = "SELECT id, name, grade, file_name FROM users WHERE usertype = :usertype ORDER BY id ASC";

	if($stmt = $pdo->prepare($sql))
	{
		$stmt->bindParam(":usertype", $param_usertype, PDO::PARAM_STR);

		$param_usertype = "Estudante";

		if($stmt->execute())
		{
			if($stmt->rowCount() > 0)
				while($row = $stmt->fetch())
				{
					$ids[$aux] = $row["id"];
					$names[$aux] = $row["name"];
					$grades[$aux] = $row["grade"];
					$tasks[$aux] = $row["file_name"];
					$aux++;
				}

			else
				$error_msg = "Não há alunos cadastrados.";
		}

		else
			$error_msg = "Algo deu errado. Por favor tente mais tarde.";

		unset($stmt);
		unset($pdo);

	}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>

	<meta charset="UTF-8">

	<title>Professor</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

	<style type="text/css">
		
		body
		{
			font: 20px sans-serif;
			text-align: center;
		}

		.wrapper
		{	
			width: 100%;
			padding: 30px;
			text-align: left;
		}

		th, td
		{
			padding: 5px;
		}

		input[type=number]::-webkit-inner-spin-button,
		input[type=number]::-webkit-outer-spin-button
		{
			-webkit-appearance: none;
		}

		input
		{
			width: 65px;
			text-align: center !important;
		}

		table
		{

		}

		.success
		{
			border-color: #4CAF50;
			background-color: white;
			color: green;
		}

		.success:hover
		{
			background-color: #4CAF50;
			color: white;
		}

		.info
		{
			border-color: #2196F3;
			background-color: white;
			color: dodgerblue;
		}

		.info:hover
		{
			background: #2196F3;
			color: white;
		}

		.gray
		{
			border-color: #E7E7E7;
			background-color: white;
			color: black;
		}

		.gray:hover
		{
			background: #E7E7E7;
		}

		.danger
		{
			border-color: #F44336;
			background-color: white;
			color: red;
		}

		.danger:hover
		{
			background: #F44336;
			color: white;
		}

	</style>

	<script type="text/javascript">
		
		function getInfo(current_id) 
		{
			document.getElementById(current_id).click();
		}

	</script>

</head>

<body>

	<div class="page-header">

		<h1>Olá, <b><?php echo htmlspecialchars($_SESSION["usertype"])." ". htmlspecialchars($_SESSION["name"]); ?></b>, bem vindo ao site!</h1>

	</div>

	<div class="wrapper">

		<h1>Lista de Alunos</h1>
		<br>

		<table style='width: 100%;'>
			<tr>
				
				<th>ID</th>
				<th>Nome</th>
				<th>Nota</th>
				<th>Trabalho</th>

			</tr>
		
			<?php

				$button_grade = "<form action='send_grade.php' method='post'><td style='display: inline-block;'><input type='number' min='0' max='100' value='' step='any' name='grade' class='btn info'>";
				
				for ($i = 0; $i < $aux; $i++)
				{
					$current_id = $ids[$i];
					echo "<tr><td style='width: 100px;'>".htmlspecialchars($ids[$i])."</td>";
					echo "<td style='width: 400px;'>".htmlspecialchars($names[$i])."</td>";
					echo "<td style='width: 125px;'>".htmlspecialchars($grades[$i])."</td>";
					echo "<td style='width: 450px;'>".htmlspecialchars($tasks[$i])."</td>";

					echo "<td><form action='info.php' method='post'><input type='submit' id='$current_id' name='info' class='btn info' value='$current_id' style='display: none;'></form></td>";

					echo "<td style='display: inline-block;'><button type='button' name='info' class='btn info' onclick='getInfo($current_id);'>Info</button></td>";

					echo "<td style='display: inline-block;'><form action='download.php' method='post'><button type='submit' name='download' class='btn gray' value='$current_id'>Download</button></form></td>";

					echo $button_grade;

					echo "<td style='display: inline-block;'><button type='submit' name='send_grade' class='btn success' value='$current_id'>Enviar Nota</button></td></form></tr>";							
				}
			?>

		</table>

	</div>

	<div class="form-group">

		<a href="logout.php" class="btn danger">Logout</a>

	</div>

</body>
</html>