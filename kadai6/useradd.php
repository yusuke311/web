<html>
<head>
<title>ユーザ登録</title>
</head>
<body>
<?php
	require "./sqlclass.php";
	$userID;
	$pass;
	$mysql = new MySQLClass();
//	echo "REQUEST<BR>";
	//POSTされたデータを受け取る
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
	if(!$mysql->ConnectSQL("localhost","yusuke","MGS"))
	{
		echo "MySQLに接続できませんでした<br>";
		exit;
	}
//	echo "training<br>";
	if( !$mysql->UseDataBase("training") )
	{
		echo "trainingがありませんでした。<br>";
		exit;
	}
//	echo "Query<br>";
	//重複していないかチェックするためuserIDでwhereをかける
	if( !$mysql->Query("select * from userlist where userID = '${userID}'") )
	{
		echo "\n<br>Queryエラー<br>";
	}
	
	$num =0;
	//レコードがあれば重複
	while( $result = $mysql->GetDatatoMap() )
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
	if( !$mysql->Query("insert into userlist values('${userID}','${pass}')") )
	{
		echo "\n<br>Queryエラー<br>";
	}
	
	echo "ユーザを登録しました<br>\n";
	echo "<a href='index.html'>トップに行く</a>\n";

?>
</body>
</html>

