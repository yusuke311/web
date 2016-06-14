<html>
<head>
<title>ユーザリスト</title>
</head>
<body>
<?php
	require "./sqlclass.php";

	session_start();
	if(!isset($_SESSION["userID"]))
	{
		header("Location: index.php");
	}
	if( $_SERVER["REQUEST_METHOD"] == "GET" )
	{
		if(isset($_GET["logout"]))
		{
			unset($_SESSION["userID"]);
			header("Location: index.php");
		}
	}
	
	echo "<h1>ユーザ情報の編集</h1>\n";

	$userID;
	$mysql = new MySQLClass();

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
		$userID = $_POST["userlist"];
//		var_dump($userID);
		if( !$mysql->Query("select * from userlist where userID = '${userID}'") )
		{
			echo "\n<br>Queryエラー<br>";
		}
		$num = 0;
		//ユーザが存在するか調べる	
		while( $result = $mysql->GetDatatoMap() )
		{
		//	var_dump($result);
			$num++;
		}
		if( $num > 0 )
		{
			echo "編集ユーザ".$userID."<br><br><br>";
		}
		else
		{
			echo "ユーザが存在しません";
			$userID = "";
		}

	}
	if( !$mysql->Query("select * from userlist ") )
	{
		echo "\n<br>Queryエラー<br>";
	}
	$num = 0;
?>

<?//ユーザ選択リストの作成?>

	<form method='POST' action='userlist.php' name='form1' >
		<select name='userlist'>
<?php
	while( $result = $mysql->GetDatatoMap() )
	{
		if( $userID == $result["userID"] )
		{
			echo "<option value='${result["userID"]}' selected>";
		}
		else
		{
			echo "<option value='${result["userID"]}'>";
		}
		echo $result["userID"];
		echo "</option>\n";
	}
?>
		</select>
<?//選択したユーザを表示する?>
		<input type='submit' value='選択'/><br>
	</form>
<?php
	if( $_SERVER["REQUEST_METHOD"] == "POST" )
	{
		echo "<form method='POST' action='userupdate.php' name='form2'>\n";
		echo "<input type='hidden' name='userID' value='${userID}'";
		echo "パスワード <br><input type='password' name='pass' /><br> \n";
		echo "<input type='submit' name='update' value='更新'><br><br>\n";
		echo "<input type='submit' name='delete' value='削除'><br>\n";
		echo "</form>\n";

	}
?>
	
</body>
</html>

