<?php
	
	require_once "config.php";

	include "validate_cpf.php";

	include "validate_date.php";

	$name = $cpf = $birthdate = $address = $phone = $usertype = $password = $confirm_password = "";

	$name_err = $cpf_err = $birthdate_err = $address_err = $phone_err = $usertype_err = $password_err = $confirm_password_err = "";

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(empty(trim($_POST["name"])))
			$name_err = "Por favor insira o seu nome.";

		else
			$name = ($_POST["name"]);

		if(empty(trim($_POST["cpf"])) || !(validateCpf($_POST["cpf"])))
			$cpf_err = "CPF inválido.";

		else
		{
			$sql = "SELECT id FROM users WHERE cpf = :cpf";

			if($stmt = $pdo->prepare($sql))
			{
				$stmt->bindParam(":cpf", $param_cpf, PDO::PARAM_STR);

				$param_cpf = ($_POST["cpf"]);

				if($stmt->execute())
					if($stmt->rowCount() == 1)
						$cpf_err = "CPF já cadastrado.";
					else
						$cpf = trim($_POST["cpf"]);

				else
					echo "Algo deu errado, tente novamente mais tarde.";
			}

			unset($stmt);
			
		}

		if(empty(trim($_POST["birthdate"])) || !(validateDate($_POST["birthdate"])))
			$birthdate_err = "Insira uma data válida.";

		else
			$birthdate = trim(($_POST["birthdate"]));	

		if(empty(trim($_POST["address"])))
			$address_err = "Insira um endereço.";

		else
			$address = (trim($_POST["address"]));

		if(empty(trim($_POST["phone"])) || (strlen($_POST["phone"]) < 10))
			$phone_err = "Insira um número de telefone válido.";

		else
			$phone = trim($_POST["phone"]);

		if(empty(trim($_POST["usertype"])))
			$usertype_err = "Escolha o tipo de usuário.";

		else
			$usertype = trim($_POST["usertype"]);

		if(empty(trim($_POST["password"])))
			$password_err = "Por favor insira uma senha.";

		elseif(strlen(trim($_POST["password"])) < 8)
			$password_err = "A senha tem de conter pelo menos 8 caracteres.";

		else
			$password = trim($_POST["password"]);
		
		if(empty(trim($_POST["confirm_password"])))
			$confirm_password_err = "Por favor confirme a senha.";

		else
		{
			$confirm_password = trim($_POST["confirm_password"]);

			if(empty($password_err) && ($password != $confirm_password))
				$confirm_password_err = "As senhas não são iguais.";
		}

		if(empty($name_err) && empty($password_err) && 
			empty($confirm_password_err) && empty($cpf_err) && empty($birthdate_err) && (empty($address_err)) && (empty($phone_err)) && (empty($usertype_err)))
		{
			$sql = "INSERT INTO users (name, cpf, birthdate, address, phone, usertype, password, file_name, grade) VALUES 
			(:name, :cpf, :birthdate, :address, :phone, :usertype, :password, :file_name, :grade)";

			if($stmt = $pdo->prepare($sql))
			{
				$stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
				$stmt->bindParam(":cpf", $param_cpf, PDO::PARAM_STR);
				$stmt->bindParam(":birthdate", $param_birthdate, PDO::PARAM_STR);
				$stmt->bindParam(":address", $param_address, PDO::PARAM_STR);
				$stmt->bindParam(":phone", $param_phone, PDO::PARAM_STR);
				$stmt->bindParam(":usertype", $param_usertype, PDO::PARAM_STR);
				$stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
				$stmt->bindParam(":file_name", $param_file_name, PDO::PARAM_STR);
				$stmt->bindParam(":grade", $param_grade, PDO::PARAM_STR);

				$param_name = $name;
				$param_cpf = $cpf;
				$param_birthdate = $birthdate;
				$param_address = $address;
				$param_phone = $phone;
				$param_usertype = $usertype;
				$param_password = password_hash($password, PASSWORD_DEFAULT);

				if($_POST["usertype"] == "Estudante")
				{
					$param_file_name = "Não Enviado";
					$param_grade = "0";
				}

				else
				{
					$param_file_name = "";
					$param_grade = "";
				}

				if($stmt->execute())
					header("location: login.php");

				else
					echo "Algo deu errado, por favor tente mais tarde.";
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

	<title>Cadastro</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

	<style type="text/css">

		body
		{
			font: 14px sans-serif;
		}

		.wrapper
		{
			width: 400px; 
			padding: 20px;
		}

	</style>

</head>

<body>
	
	<div class="wrapper">

		<h2>Cadastro</h2>
		<p>Preencha os campos abaixos para criar uma conta.</p>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

			<div class="form-group <?php echo (!empty($name_err)) ? 'Erro' : ''; ?>">

				<label>Nome Completo</label>
				<input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
				<span class="help-block">
					<?php echo $name_err; ?>
				</span>

			</div>

			<div class="form-group <?php echo (!empty($cpf_err)) ? 'Erro' : ''; ?>">

				<label>CPF</label>
				<input type="text" name="cpf" class="form-control" value="<?php echo $cpf; ?>">
				<span class="help-block">
					<?php echo $cpf_err; ?>
				</span>

			</div>

			<div class="form-group <?php echo (!empty($birthdate)) ? 'Erro' : ''; ?>">

				<label>Data de Nascimento</label>
				<input type="text" name="birthdate" class="form-control" value="<?php echo $birthdate; ?>">
				<span class="help-block">
					<?php echo $birthdate_err; ?>
				</span>

			<div class="form-group <?php echo (!empty($address_err)) ? 'Erro' : ''; ?>">

				<label>Endereço</label>
				<input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
				<span class="help-block">
					<?php echo $address_err; ?>
				</span>

			</div>

			<div class="form-group <?php echo (!empty($phone_err)) ? 'Erro' : ''; ?>">

				<label>Telefone</label>
				<input type="tel" name="phone" class="form-control" value="<?php echo $phone; ?>">
				<span class="help-block">
					<?php echo $phone_err; ?>
				</span>

			</div>

			<div class="form-group <?php echo (!empty($usertype_err)) ? 'Erro' : ''; ?>">

				<label>Tipo de Usuário</label>
				<br><input type="radio" name="usertype" value="Administrador">Administrador<br>
				<input type="radio" name="usertype" value="Professor">Professor<br>
				<input type="radio" name="usertype" value="Estudante">Estudante<br>
				<input type="radio" name="usertype" value="" style="display: none;" checked>
				<span clas="help-block">
					<?php echo "<br>".$usertype_err; ?>
				</span>

			</div>

			<div class="form-group <?php echo (!empty($password_err)) ? 'Erro' : ''; ?>">

				<label>Senha</label>
				<input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
				<span class="help-block">
					<?php echo $password_err; ?>
				</span>

			</div>

			<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'Erro' : ''; ?>">

				<label>Confirme a senha</label>
				<input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
				<span class="help-block">
					<?php echo $confirm_password_err; ?>			
				</span>

			</div>

			<div class="form-group">

				<button type="submit" class="btn btn-primary" value="submit">Enviar</button>
				<button type="reset" class="btn btn-default" value="reset">Resetar</button>
				
			</div>

			<p>Já possui uma conta? <a href="login.php"> Logue aqui</a>.</p>

		</form>

	</div>

</body>
</html>

