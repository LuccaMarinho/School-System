<?php

function validateCpf($cpf)
	{
		$cpf = preg_replace('/[^0-9]/is', '', $cpf);

		if(strlen($cpf) != 11)
			return false;

		if(preg_match('/(\d)\1{10}/', $cpf))
			return false;

		for ($i = 9; $i < 11; $i++)
		{
			for($j = 0, $k = 0; $k < $i; $k++)
			{
				$j += $cpf[$k] * (($i + 1) - $k);
			}

			$j = (((10 * $j) % 11) % 10);

			if($cpf[$k] != $j)
				return false;
		}

		return true;
	}

?>
