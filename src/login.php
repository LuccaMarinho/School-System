<?php
	
 	session_start();

 	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
 	{
 		header("location: users.php");
 		exit;
 	}

 	require_once "config.php";

 	$name = $cpf = $password = "";
 	$cpf_err = $password_err = "";

 	if($_SERVER["REQUEST_METHOD"] == "POST")
 	{
 		if(empty(trim($_POST["cpf"])))
 			$cpf_err = "Por favor insira um CPF.";

 		else
 			$cpf = trim($_POST["cpf"]);

 		if(empty(trim($_POST["password"])))
 			$password_err = "Por favor insira uma senha.";

 		else
 			$password = trim($_POST["password"]);

 		if(empty($cpf_err) && empty($password_err))
 		{
 			$sql = "SELECT id, name, cpf, birthdate, address, phone, usertype, password, grade FROM users WHERE cpf = :cpf";

 			if($stmt = $pdo->prepare($sql))
 			{
 				$stmt->bindParam(":cpf", $param_cpf, PDO::PARAM_STR);

 				$param_cpf = trim($_POST["cpf"]);

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
 							$hashed_password = $row["password"];
 							$grade = $row["grade"];

 							if(password_verify($password, $hashed_password))
 							{
 								session_start();

 								$_SESSION["loggedin"] = true;
 								$_SESSION["id"] = $id;
 								$_SESSION["name"] = $name;
 								$_SESSION["cpf"] = $cpf;
 								$_SESSION["birthdate"] = $birthdate;
 								$_SESSION["address"] = $address;
 								$_SESSION["phone"] = $phone;
 								$_SESSION["usertype"] = $usertype;
 								$_SESSION["grade"] = $grade;

 								header("location: users.php");
 							}

 							else
 								$password_err = "Senha inválida.";
 						}

 					else
 						$cpf_err = "Usuário não encontrado.";
 					}

 				else
 					$cpf_err = "Usuário não encontrado.";

 				}
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

	<title>Login</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

	<style type="text/css">
		
		body
		{
			font: 14px sans-serif;
		}

		.wrapper
		{
			width:350px; 
			padding: 20px;
		}

	</style>

</head>

<body>
	
	<div class="wrapper">

		<h2>Login</h2>
		<p>Preencha os campos abaixos para fazer  login.</p>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			
			<div class="form-group <?php echo (!empty($cpf_err)) ? 'Erro' : '';	?>">

				<label>CPF</label>
				<input type="text" name="cpf" class="form-control" value="<?php echo $cpf; ?>">
				<span class="help-block">
					<?php echo $cpf_err; ?>	
				</span>

			</div>

			<div class="form-group <?php echo (!empty($password_err)) ? 'Erro' : ''; ?>">

				<label>Senha</label>
				<input type="password" name="password" class="form-control">
				<span class="help-block">
					<?php echo $password_err; ?>
				</span>

			</div>

			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Login">	
			</div>

			<p>Não tem uma conta? <a href="register.php">Criar conta</a>.</p>

		</form>

	</div>

</body>
</html>	