<?php 
	
	include "is_loggedin.php";

	isLoggedin();
	
	require_once "config.php";

	$status_msg = "";
	$target_dir = "uploads/";
	$file_name = basename($_FILES["file_name"]["name"]);
	$target_file_path = $target_dir . $file_name;
	$file_type = pathinfo($target_file_path, PATHINFO_EXTENSION);

	if(isset($_POST["submit"]) && (!empty($_FILES["file_name"]["name"])))
	{
		$allowed_types = array('zip', 'rar', 'pdf');

		if(in_array($file_type, $allowed_types))
		{
			if(move_uploaded_file($_FILES["file_name"]["tmp_name"], $target_file_path))
			{
				$sql = "UPDATE users SET file_name = :file_name, grade = :grade WHERE id = :id";

				if($stmt = $pdo->prepare($sql))
				{
					$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
					$stmt->bindParam(":file_name", $param_file_name, PDO::PARAM_STR);
					$stmt->bindParam(":grade", $param_grade, PDO::PARAM_STR);

					$param_id = ($_SESSION["id"]);
					$param_file_name = $file_name;
					$param_grade = 0.0;

					if($stmt->execute())
					{ 
						$_SESSION["grade"] = $param_grade;
						$status_msg = "O arquivo " . $file_name . " foi enviado
					 com sucesso.";
					}

					else
						$status_msg = "Falha ao enviar o arquivo, tente mais tarde novamente.";
				}

				else
					$status_msg = "Houve um erro ao enviar o seu arquivo.";

					unset($stmt);
			}
			else
				$status_msg = "Houve um erro ao enviar o seu arquivo.";
				unset($pdo);
		}

		else
			$status_msg = "Apenas arquivos no formato ZIP, RAR e PDF são permitidos.";
		
	}

	else
		$status_msg = "Por favor selecione um arquivo para enviar.";

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>

	<meta charset="UTF-8">

	<title>Envio do Trabalho</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

	<style type="text/css">
		
		body
		{
			font: 14px sans-serif;
			text-align: center;
		}

	</style>

</head>

<body>

	<div class="page-header">

		<h1><b><?php echo htmlspecialchars($status_msg); ?></b></h1>

	</div>

	<div class="form-group">

		<a href="student.php" class="btn btn-primary">Voltar</a>

	</div>	

</body>
</html>