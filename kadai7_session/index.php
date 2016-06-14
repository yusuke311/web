<?php
	$userID;
	$pass;
	$failed = false;
	require_once("./sqlclass.php");
	//ログインデータが送られた場合
	if( $_SERVER["REQUEST_METHOD"] == "POST" )
	{
		$userID = $_POST["userID"];
		$pass = $_POST["pass"];

		try
		{
			$mysql = new MyPDOClass();
			$mysql->ConnectSQL("MYSQL","localhost","yusuke","MGS","training");
			$mysql->PrepareQuery("select * from userlist where userID = :ID and pass = :pass");
			if( !$mysql->Execute(array(":ID"=>$userID,":pass"=>$pass)) )
			{
				echo "QueryError";
				exit;
			}

			//ユーザ認証成功
			if( $mysql->fetch_num() )
			{
				session_start(); 
				session_regenerate_id(true);
				$_SESSION["userID"] = $userID;
				header("Location: userlist.php");
				exit;
			}
			$failed = true;

		}
		catch( PDOException $e )
		{
			echo "Error:".$e->getMessage();
		}
		$mysql = null;

	}

?>
<html>
<head>
<title>ログイン</title>
</head>
<body>
<?php
	if($failed)
	{
		echo "<font color='red'>";
		echo "ユーザ名またはパスワードが一致しません";
		echo "</font>";
	}
?>
<form method="POST" action="index.php">
	ユーザID<br><input type="TEXT" name="userID" maxlength="64"><br>
	パスワード<br><input type="PASSWORD" name="pass" /><br>
	<input type="SUBMIT" value="ログイン"/>
</form>


</body>
</html>
