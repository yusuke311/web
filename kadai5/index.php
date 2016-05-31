<html>
<head>
<title>都道府県</title>
</head>
<body>

<form method="POST" action="">
	
<?php
require "./sqlclass.php";

	$mysql = new MySQLClass();
	$mysql->ConnectSQL("localhost","yusuke","MGS");
	
	if( !$mysql->UseDataBase("training") )
	{
		echo "trainingがありませんでした。<br>";
		exit();
	}

	if( !$mysql->Query("select pref from 都道府県") )
	{
		echo "Queryエラー<br>";
		exit();
	}

	echo "<select name='pref'>";
	$num = 0;
	while( $record = $mysql->GetDatatoMap() )
	{
		echo "<option value='${num}'>".$record["pref"]."</option>";
		$num++;
	}
	echo "</select>";

	$mysql->Close();

?>
</form>
</body>
</html>
