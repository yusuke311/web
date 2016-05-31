<html>
<head>
</head>
<body>
<?php
require "./sqlclass.php";
	
	$date;
	$tablename;

	if( $_SERVER["REQUEST_METHOD"] == "POST" )
	{
		$date = $_POST["date"];
		$tablename = $_POST["tablename"];
	}
	else
	{
		echo "POSTされていません\n";
		exit();
	}
	$mysql = new MySQLClass();

	if(!$mysql->ConnectSQL("localhost","yusuke","MGS"))
	{
		echo "MySQLに接続できませんでした<br>";
		exit();
	}

	if( !$mysql->UseDataBase("training") )
	{
		echo "trainingがありませんでした。<br>";
		exit();
	}

	$str = "select * from ${tablename}";
	if( $date != ""  )
	{
		$str = $str." where 日付 = \"${date}\"";
	}
//	var_dump($str);echo "<br>";
	if( !$mysql->Query($str) )
	{
		echo "Queryエラー<br>";
	}	
	
	while( $record = $mysql->GetDatatoMap())
	{
		
		foreach( $record as $row )
		{
			printf("%s&#009;｜",$row);
		}
		echo "<br>";
	}
	$mysql->Close();
	
?>



</body>
</html>

