<?php
	require "./sqlclass.php";
	session_start();
	$login = false;

	if(!isset($_SESSION["username"]))
	{
		header("Location: home.php");
		exit;
	}

	if( $_SERVER["REQUEST_METHOD"] != "POST")
	{
		header("Location: home.php");
		exit;
	}

	$userID = $_POST["userID"];
	$pass	= $_POST["pass"];
	$mysql = new MyPDOClass();
	
	try
	{
		$mysql->ConnectSQL("MYSQL","localhost","yusuke","MGS","training");
		$mysql->PrepareQuery("select * from userlist where userID = :user and pass = :pass");
		$mysql->Execute(array(":user"=>$userID,":pass"=>$pass));

		$num = 0;
		while( $mysql->fetch_num() )
		{
			$num++;
		}
		if( $num <= 0 )
		{
			header("Location: index.php");
			exit;
		}
		$_SESSION["userID"] = $userID;
		//header("Location: home.php");


	}
	catch(PDOException $e)
	{
		echo "throw ".$e->getMessage()."<br>";
	}

?>
<html>
<head>
<title>ユーザトップ</title>
</head>
<body>
</body>
<html>
