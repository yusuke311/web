<html>
<head>
<title>ユーザ登録</title>
</head>
<body>
<?php
	require "./sqlclass.php";
	$userID;
	$pass;
	$mysql = new MyPDOClass();
//	echo "REQUEST<BR>";
	if( $_SERVER["REQUEST_METHOD"] == "POST" )
	{
		$userID = $_POST["userID"];
		$pass = $_POST["pass"];
//		echo "POST<br>";
		//var_dump($userID);
		//var_dump($pass);
	}
	else
	{
		header("Location: index.html");
		exit;
	}

	//		echo "sql<br>";

	try
	{
		//MySQL接続
	//	echo "MySQL接続<br>";
		$mysql->ConnectSQL("MYSQL","localhost","yusuke","MGS","training");

		//重複していないかチェックするためuserIDでwhereをかける
		//SQL構文を渡す
	//	echo "SQL構文を渡す<br>";
		$mysql->PrepareQuery("select * from userlist where userID = :ID");

		//SQL実行
//		echo "SQL実行<br>";
		if( !$mysql->Execute(array(":ID"=>$userID)))
		{
			echo "EXE false<br>";
		}

		$num =0;
		//レコードがあれば重複
		while( $result = $mysql->fetch_num() )
		{
			//var_dump($result);
			$num++;
		}
		if($num > 0)
		{
			echo "すでにユーザがいます。<br>\n";
			echo "<a href='index.html'>トップに行く</a>\n";
			exit;
		}
		
	//	echo "重複なし";
		//重複していないかチェックするためuserIDでwhereをかける
		if(0)
		{
			$mysql->QuickQuery("insert into userlist values('${userID}','${pass}')");
		}	
		else
		{
			$mysql->PrepareQuery("insert into userlist values(:userID,:pass)");
			echo "SQL実行";
			$mysql->Execute(array(":userID"=>$userID,":pass"=>$pass));
		}

	}
	catch(PDOException $e)
	{
		echo "throw ".$e->getMessage()."<br>\n";
	}
	echo "ユーザを登録しました<br>\n";
	echo "<a href='index.html'>トップに行く</a>\n";

?>
</body>
</html>

