<?php

	include "is_loggedin.php";

	isLoggedin();

	require_once "config.php";

	include "validate_cpf.php";

	include "validate_date.php";

 	$id = $name = $cpf = $birthdate = $address = $phone = "";

	$new_name = $new_cpf = $new_birthdate = $new_address = $new_phone = $new_usertype = "";

	$new_cpf_err = $new_birthdate_err = $new_phone_err = "";	

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$sql = "SELECT * FROM users WHERE id = :id";

		if($stmt = $pdo->prepare($sql))
		{
			$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

			$param_id = $_SESSION["grade"];

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
					}
				}
			}

			unset($stmt);
		}

		if(empty(trim($_POST["new_name"])))
			$new_name = $name;

		else
			$new_name = ($_POST["new_name"]);
			
		if(!empty(trim($_POST["new_cpf"])) && !(validateCpf($_POST["new_cpf"])))
			$new_cpf_err = "CPF inválido.";

		elseif(!empty(trim($_POST["new_cpf"])) && (validateCpf($_POST["new_cpf"])))
		{
			$sql = "SELECT id FROM users WHERE cpf = :cpf";

			if($stmt = $pdo->prepare($sql))
			{
				$stmt->bindParam(":cpf", $param_cpf, PDO::PARAM_STR);

				$param_cpf = ($_POST["new_cpf"]);

				if($stmt->execute())
				{
					if($stmt->rowCount() == 1)
						$new_cpf_err = "CPF já cadastrado.";

					else
						$new_cpf = ($_POST["new_cpf"]);
				}

				else
					echo "Algo deu errado, tente novamente mais tarde.";			
			}

			unset($stmt);
			
		}

		else
			$new_cpf = $cpf;

		if(!empty(trim($_POST["new_birthdate"])) && validateDate($_POST["new_birthdate"]))
			$new_birthdate = ($_POST["new_birthdate"]);

		elseif(!empty(trim($_POST["new_birthdate"])) && !validateDate($_POST["new_birthdate"]))
			$new_birthdate_err = "Insira uma data válida.";

		else
			$new_birthdate = $birthdate;			

		if(!empty(trim($_POST["new_address"])))
			$new_address = ($_POST["new_address"]);

		else
			$new_address = $address;

		if((!empty(trim($_POST["new_phone"]))) && (strlen($_POST["new_phone"]) >= 10))
			$new_phone = ($_POST["new_phone"]);

		elseif((!empty(trim($_POST["new_phone"]))) && (strlen($_POST["new_phone"]) < 10))
			$new_phone_err = "Insira um telefone válido.";

		else
			$new_phone = $phone;

		if(empty($new_birthdate_err) && empty($new_phone_err) && empty($new_cpf_err))
		{
			$sql = "UPDATE users SET name = :name, cpf = :cpf, birthdate = :birthdate, address = :address, phone = :phone WHERE id = :id";

			if($stmt = $pdo->prepare($sql))
			{
				$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
				$stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
				$stmt->bindParam(":cpf", $param_cpf, PDO::PARAM_STR);
				$stmt->bindParam(":birthdate", $param_birthdate, PDO::PARAM_STR);
				$stmt->bindParam(":address", $param_address, PDO::PARAM_STR);
				$stmt->bindParam(":phone", $param_phone, PDO::PARAM_STR);

				$param_id = $id;
				$param_name = $new_name;
				$param_cpf = $new_cpf;
				$param_birthdate = $new_birthdate;
				$param_address = $new_address;
				$param_phone = $new_phone;

				if($stmt->execute())
				{	
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

	<meta charset="UTF-8">

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
		
		<h2>Alterar Dados</h2>
		<p>Preencha os desejados campos para alterar os dados cadastrais.</p>
		<br>

	</div>

	<div class="wrapper">

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			
			<div class="form-group">

				<label>Nome Completo</label>
				<input type="text" name="new_name" class="form-control" value="<?php echo $new_name; ?>">

			</div>

			<div class="form-group <?php echo (!empty($cpf_err)) ? 'Erro' : ''; ?>">

				<label>CPF</label>
				<input type="text" name="new_cpf" class="form-control" value="<?php echo $new_cpf; ?>">
				<span class="help-block">
					<?php echo $new_cpf_err; ?>
				</span>

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
