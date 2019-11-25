<?php

	include "is_loggedin.php";

	isLoggedin();

	require_once "config.php";

	include "validate_date.php";

	$new_name = $new_birthdate = $new_address = $new_phone = "";

	$new_birthdate_err = $new_phone_err = "";

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(empty(trim($_POST["new_name"])))
			$new_name = ($_SESSION["name"]);

		else
			$new_name = ($_POST["new_name"]);

		if(empty(trim($new_birthdate)))
			$new_birthdate = ($_SESSION["birthdate"]);

		elseif(!validateDate($_POST["new_birthdate"]))
			$new_birthdate_err = "Insira uma data válida.";

		else
			$new_birthdate = ($_POST["new_birthdate"]);

		if(empty(trim($_POST["new_address"])))
			$new_address = $_SESSION["address"];

		else
			$new_address = ($_POST["new_address"]);

		if(empty(trim($_POST["new_phone"])))
			$new_phone = ($_SESSION["phone"]);

		elseif((!empty(trim($_POST["new_phone"]))) && (strlen($_POST["new_phone"]) < 10))
			$new_phone_err = "Insira um telefone válido.";

		else
			$new_phone = ($_POST["new_phone"]);

		if(empty($new_birthdate_err) && empty($new_phone_err))
		{
			$sql = "UPDATE users SET name = :name, birthdate = :birthdate, address = :address, phone = :phone WHERE id = :id";

			if($stmt = $pdo->prepare($sql))
			{
				$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
				$stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
				$stmt->bindParam(":birthdate", $param_birthdate, PDO::PARAM_STR);
				$stmt->bindParam(":address", $param_address, PDO::PARAM_STR);
				$stmt->bindParam(":phone", $param_phone, PDO::PARAM_STR);

				$param_id = ($_SESSION["id"]);
				$param_name = $new_name;
				$param_birthdate = $new_birthdate;
				$param_address = $new_address;
				$param_phone = $new_phone;

				if($stmt->execute())
				{	
					$_SESSION["name"] = $new_name;
					header("location: users.php");
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

	<meta charset="pt-br">

	<title>Alterar Dados</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

	<style type="text/css">
		
		body
		{
			font: 14px sans-serif;
		}

		.title
		{
			width: 450px; 
			padding: 20px;
		}

		.wrapper
		{
			width: 350px; 
			padding: 20px; 
			margin-top: -30px;
		}

	</style>

</head>

<body>

	<div class="title">

		<h2>Alterar os dados cadastrais</h2>
		<p>Preencha os desejados campos para alterar os dados cadastrais.</p>

	</div>

	<div class="wrapper">

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			
			<div class="form-group">

				<label>Nome Completo</label>
				<input type="text" name="new_name" class="form-control" value="<?php echo $new_name; ?>">

			</div>

			<div class="form-group <?php echo (!empty($new_birthdate_err)) ? 'Erro' : ''; ?>">

				<label>Data de Nascimento</label>

				<input type="text" name="new_birthdate" class="form-control" value="<?php echo $new_birthdate; ?>">
				<span class="help-block">
					<?php echo $new_birthdate_err; ?>
				</span>

			</div>

			<div class="form-group">

				<label>Endereço</label>
				<input type="text" name="new_address" class="form-control" value="<?php echo $new_address; ?>">

			</div>	

			<div class="form-group <?php echo (!empty($new_phone_err)) ? 'Erro' : ''; ?>">

				<label>Telefone</label>
				<input type="tel" name="new_phone" class="form-control" value="<?php echo $new_phone; ?>">
				<span class="help-block">
					<?php echo $new_phone_err; ?>
				</span>

			</div>	

			<div class="form-group">

				<button type="submit" class="btn btn-primary" value="submit">Enviar</button>
				<button type="reset" class="btn btn-default" value="reset">Resetar</button>
				<button type="button" class="btn btn-link" value="go_back"><a href="users.php">Voltar</a></button>

			</div>

		</form>

	</div>

</body>
</html>