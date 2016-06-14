<html>
<head>
<title>ユーザ登録</title>
</head>
<body>
<?php
	require "./sqlclass.php";
	$userID;
	$pass;
	//$mysql = new MySQLClass();
	$mysql = new MyPDOClass();
	echo "REQUEST<BR>";
	//POSTされたデータを受け取る
	if( $_SERVER["REQUEST_METHOD"] == "POST" )
	{
		$userID = $_POST["userID"];
		$pass = $_POST["pass"];
		echo "POST<br>";
		//var_dump($userID);
		//var_dump($pass);
	}
	else
	{
		header("Location: index.html");
		exit;
	}

		echo "sql<br>";
	try
	{
		$mysql->ConnectSQL("MYSQL","localhost","yusuke","MGS","training");

	echo "training<br>";
		$mysql->PrepareQuery("select * from userlist where userID = :userID");	

		if( !$mysql->Execute(array(":userID"=>$userID)) )
		{
			echo "EXT false<br>";
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

		if( !$mysql->QuickQuery("insert into userlist values('${userID}','${pass}')") )
		{
			echo "\n<br>Queryエラー<br>";
		}
	}
	catch(PDOException $e)
	{
		echo "ERROR:".$e->getMessage();
	}
	
//	echo "Query<br>";
	//重複していないかチェックするためuserIDでwhereをかける

//	echo "重複なし";
	//重複していないかチェックするためuserIDでwhereをかける
	echo "ユーザを登録しました<br>\n";
	echo "<a href='index.html'>トップに行く</a>\n";

?>
</body>
</html>

