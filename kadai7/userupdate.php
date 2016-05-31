<html>
<head>
<title>ユーザリスト</title>
</head>
<body>
<?php
	require "./sqlclass.php";
	define("UPDATE",1);
	define("DELETE",2);
	$mode;
	$mysql = new MySQLClass();
	$userID;
	$pass;

	if(!$mysql->ConnectSQL("localhost","yusuke","MGS"))
	{
		echo "MySQLに接続できませんでした<br>";
		exit();
	}
//	echo "training<br>";
	if( !$mysql->UseDataBase("training") )
	{
		echo "trainingがありませんでした。<br>";
		exit();
	}


	if( $_SERVER["REQUEST_METHOD"] == "POST" )
	{
	//	var_dump($_POST);
		$update = $_POST["update"];
		$delete = $_POST["delete"];
		$userID = $_POST["userID"];
		$pass	= $_POST["pass"];
		if( $update != NULL && $delete ==NULL )
		{
			$mode = UPDATE;
		}
		else if( $update == NULL && $delete != NULL  )
		{
			$mode = DELETE;
		}


		switch($mode)
		{
		case UPDATE:
			echo "更新しました。<br>";
			echo "i<a href='index.html' >トップに戻る</a>\n";
			if( !$mysql->Query("update userlist set pass = '${pass}' where userID = '${userID}'") )
			{
				echo "\n<br>Queryエラー<br>";
			}

			break;

		case DELETE:
			echo "更新しました。<br>";
			echo "<a href='index.html' >トップに戻る</a>\n";
			if( !$mysql->Query("delete from userlist where userID = '${userID}'") )
			{
				echo "\n<br>Queryエラー<br>";
			}

			break;
		}


	}
	else
	{
		header("Location: index.html");
		exit;
	}
?>
</body>
</html>

