<?php

	include "is_loggedin.php";

	isLoggedin();

	require_once "config.php";

	$new_password = $confirm_password = "";
	$new_password_err = $confirm_password_err = "";

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(empty(trim($_POST["new_password"])))
			$new_password_err = "Por favor insira sua nova senha.";

		elseif(strlen(trim($_POST["new_password"])) < 8)
			$new_password_err = "A senha deve conter pelo menos 8 caracteres.";

		else
			$new_password = trim($_POST["new_password"]);

		if(empty(trim($_POST["confirm_password"])))
			$confirm_password_err = "Confirme a senha.";

		else
		{
			$confirm_password = trim($_POST["confirm_password"]);

			if(empty($new_password_err) && ($new_password != $confirm_password))
				$confirm_password_err = "As senhas não iguais.";
		}

		if(empty($new_password_err) && empty($confirm_password_err))
		{
			$sql = "UPDATE users SET password = :password WHERE id = :id";

			if($stmt = $pdo->prepare($sql))
			{
				$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
				$stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

				$param_id = $_SESSION["id"];
				$param_password = password_hash($new_password, PASSWORD_DEFAULT);
				
				if($stmt->execute())
				{
					session_destroy();
					header("location: login.php");
					exit();
				}

				else
					echo "Algo deu errado! Por favor tente novamente mais tarde.";
			}

			unset($stmt);

		}

		unset($pdo);
	}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>

	<meta charset="UTF-8">

	<title>Resetar a senha</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

	<style type="text/css">
		
		body
		{
			font: 14px sans-serif;
		}

		.wrapper
		{
			width: 350px;
			padding: 20px;
		}

	</style>

</head>

<body>

	<div class="wrapper">

		<h2>Resetar a senha</h2>
		<p>Preencha os campos abaixos para resetar a senha.</p>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

			<div class="form-group <?php echo (!empty($new_password_err)) ? 'Erro' : ''; ?>">

				<label>Nova senha</label>
				<input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
				<span class="help-block">
					<?php echo $new_password_err; ?>
				</span>	

			</div>	

			<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'Erro' : ''; ?>">

				<label>Confirme a senha</label>
				<input type="password" name="confirm_password" class="form-control">
				<span class="help-block">
					<?php echo $confirm_password_err; ?>
				</span>

			</div>

			<div class="form-group">

				<button type="submit" class="btn btn-primary" value="submit">Enviar</button>
				<a class="btn btn-link" href="users.php">Cancelar</a>	

			</div>

		</form>

	</div>

</body>
</html>