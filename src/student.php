<?php

	include "is_loggedin.php";

	isLoggedin();

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>

	<meta charset="UTF-8">

	<title>Aluno</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

	<style type="text/css">

		body
		{
			font: 14px sans-serif; 
			text-align: center;
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

		.primary
		{
			border-color: #337AB7;
			background-color: white;
			color: dodgerblue;
		}

		.primary:hover
		{
			background: #337AB7;
			color: white;
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

		.semi-danger
		{	
			border-color: #FF9800;
			background-color: white;
			color: orange;
		}

		.semi-danger:hover
		{
			background: #FF9800;
			color: white;
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

	</style>

	<script type="text/javascript">
		
		function uploadFile()
		{
			document.getElementById("upload_file").click();
		}

	</script>

</head>

<body>

	<div class="page-header">
		
		<h1>Olá, <b><?php echo htmlspecialchars($_SESSION["usertype"])." ". htmlspecialchars($_SESSION["name"]); ?></b>, bem vindo ao site!</h1>

	</div>

	<p>

		<div>
			<h3>Sua nota atual é: <?php echo htmlspecialchars($_SESSION["grade"]); ?></h3>
		</div>

		<div class="form-group">

			<form action="submit_task.php" method="post" enctype="multipart/form-data">

				<input type="file" id="upload_file" name="file_name" style="display: none;">
				<button type="button" name="button" class="btn info" value="upload" onclick="uploadFile();">Upload Trabalho</button>
				<button type="submit" name="submit" class="btn primary" value="submit" >Enviar Trabalho</button>
				

				<a href="change_data.php" class="btn success">Alterar Dados</a>	
				<a href="password_reset.php" class="btn semi-danger">Resetar Senha</a>
				<a href="logout.php" class="btn danger">Logout</a>

			</form>

		</div>
		
	</p>

</body>
</html>