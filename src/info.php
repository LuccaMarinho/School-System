<?php

	include "is_loggedin.php";

	isLoggedin();

	require_once "config.php";

	$id = $name = $cpf = $birthdate = $address = $phone = $usertype = "";

	$sql = "SELECT * FROM users WHERE id = :id";
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if($stmt = $pdo->prepare($sql))
		{
			$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

			$param_id = $_POST["info"];

			if($stmt->execute())
			{
				if($stmt->rowCount() == 1)
				{
					if($row = $stmt->fetch())
					{
						$id = $row["id"];
						$name = $row["name"];
						$cpf = $row["cpf"];
						$birthdate = $row["birthdate"];
						$address = $row["address"];
						$phone = $row["phone"];
						$usertype = $row["usertype"];
						$task = $row["file_name"];
						$grade = $row["grade"];
					}
				}
			}

			unset($stmt);
			unset($pdo);
		}
	}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

	<meta charset="UTF-8">

	<title>Informações</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

	<style type="text/css">
		
		body
		{
			font: 20px sans-serif;
			text-align: left;
			margin-left: 10px;
		}

		.wrapper
		{
			width: 100%;
			padding: 20px;
			margin-bottom: -15px;
			text-align: left;
		}

		.form-group
		{
			padding: 15px;
			margin-top: 10px;
		}

		.info
		{
			border-color: #5BC0DE;
			background-color: white;
			color: deepskyblue;
		}

		.info:hover
		{
			background: #5BC0DE;
			color: white;
		}

	</style>

</head>

<body>

	<div class="wrapper">
		
		<h2>Informações</h2>
		<br>

	</div>

	<div class="wrapper">

		<label>ID</label>
		<?php echo "<br>".$id; ?>

	</div>

	<div class="wrapper">
		
		<label>Nome Completo</label>
		<?php echo "<br>".$name; ?>

	</div>

	<div class="wrapper">

		<label>CPF</label>
		<?php echo "<br>".$cpf; ?>

	</div>

	<div class="wrapper">

		<label>Data de Nascimento</label>
		<?php echo "<br>".$birthdate; ?>

	</div>

	<div class="wrapper">

		<label>Endereço</label>
		<?php echo "<br>".$address; ?>

	</div>

	<div class="wrapper">

		<label>Telefone</label>
		<?php echo "<br>".$phone; ?>

	</div>

	<div class="wrapper">

		<label>Usuário</label>
		<?php echo "<br>".$usertype; ?>

	</div>

	<div class="wrapper">

		<label>Trabalho</label>
		<?php echo "<br>".$task; ?>

	</div>

	<div class="wrapper">

		<label>Nota</label>
		<?php echo "<br>".$grade; ?>

	</div>

	<div class="form-group">

		<form action="users.php">

			<button type="submit" class="btn info" value="go_back">Voltar</button>

		</form>

	</div>

</body>
</html>

