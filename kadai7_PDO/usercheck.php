<html>
<head>
<title>確認</title>
</head>
<body>
<?php

	$userID;
	$pass;

	if( $_SERVER["REQUEST_METHOD"] == "POST" )
	{
		$userID = $_POST["userID"];
		$pass = $_POST["pass"];
		$re_pass = $POST["re_pass"];
	}
	else
	{
		header("Location: index.html");
		exit;
	}

	echo "<h1>確認</h1>";
	echo "ユーザ名:${userID}<br>";
	echo "パス:";
	for( $i = 0;$i<strlen($pass);$i++ )
	{
		echo "*";
	}	
	echo "<br>";
	echo "<form method='POST' action='useradd.php'> \n";
	echo "<input type='hidden'name='userID' value='${userID}'/><br>\n ";
	echo "<input type='hidden'name='pass' value='${pass}'/><br>\n ";
	echo "確認したら送信を押してください<br>\n";
	echo "<input type='submit' value='送信' />"; 
	echo "</form>";

?>
</body>
</html>

