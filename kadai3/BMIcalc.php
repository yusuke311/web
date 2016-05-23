<?php

function BMICalc($height,$weight)
{
	return $weight / pow($height/100,2);
}
?>
