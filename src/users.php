<?php

	include "is_loggedin.php";

	isLoggedin();

	if($_SESSION["usertype"] === "Estudante")
 		header("location: student.php");

	elseif($_SESSION["usertype"] === "Professor")
 		header("location: professor.php");

 	elseif($_SESSION["usertype"] === "Administrador")
 		header("location: admin.php");

 	else
 	{
 		echo "Algo deu errado! Tente novamente mais tarde.";
 		header("location: login.php");
	}

?>