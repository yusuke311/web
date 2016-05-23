<html>
<body>
<?php

require "./BMIcalc.php";
	$height = NULL;
	$weight = NULL;
	$BMIClass = new BMICalc();
	$BMI = 0;
	if( $_SERVER["REQUEST_METHOD"] == "POST" )
	{
		$height = $_POST["height"];
		$weight = $_POST["weight"];
		if( !is_numeric($height) )
		{
			$height = NULL;
		}
		if( !is_numeric($weight) )
		{
			$weight = NULL;
		}
		$BMIClass->SetHeight($height);
		$BMIClass->SetWeight($weight);
		$BMIClass->Calc();
		$BMI = $BMIClass->GetBMI();
	}

echo "<form name=\"BMI\" action=\"index.php\" method=\"POST\">";
echo "身長(cm)<br>";
echo "<input type=\"text\" name=\"height\" value=\"${height}\"><br>";
echo "体重(kg)<br>";
echo "<input type=\"text\" name=\"weight\" value=\"${weight}\"><br>";
echo "<input type=\"submit\" value=\"送信\">";
echo "</form>";


if( $BMI > 0.0 )
{
	echo "あなたのBMIは",number_format($BMI,1),"です。";
}
?>

</body>
</html>

