<?php

	function validateDate($date)
	{
		$day = $date[0] . $date[1];
		$month = $date[3] . $date[4];
		$year = $date[6] . $date[7] . $date[8] . $date[9];

		return checkdate($month, $day, $year);
	}

?>