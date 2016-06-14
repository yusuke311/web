<?php
	require_once("./sqlclass.php");

	$adminID 	= "admin";
	$adminpass		= "suez";

	$userID;
	$pass;
	$failed = false;

	if($_SERVER["REQUEST_METHOD"] == "POST" )
	{
		$userID = $_POST["userID"];
		$pass	= $_POST["pass"];

		//管理者と一致していたら管理者ページへ飛ばす
		if( $userID == $adminID && $adminpass == $pass )
		{
			session_start();
			session_regenerate_id(true);
			$_SESSION["admin"] = $userID;	
			header("Location: admin.php");
			exit;
		}


		try
		{
			$mysql = new MyPDOClass();
			$mysql->ConnectSQL("MYSQL","localhost","webuser","user","website");
			$mysql->PrepareQuery("select * from userlist where userID = :userID and password = :password");
			if( !$mysql->Execute(array(":userID"=>$userID,":password"=>$pass)) )
			{
				echo "Executeエラー";
			}
		
			//戻り値が真 == userが見つかった時
			if( $mysql->fetch_num() )
			{
				session_start();
				session_regenerate_id(true);
				$_SESSION["userID"] = $userID;	
				header("Location: user.php");		
				exit;
			}

			$failed = true;	

		}
		catch(PDOException $e)
		{
			echo "エラー".$e->getMessage();
		}

	}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- BootstrapのCSS-->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- jQuery読み込み -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<div class="center-block">
		<div>
			<div class="row">
				<!-- <div class="col-xs-6 col-xs-offset-3 text-center" style="background-color:#FF4401;padding:30px 5px;"> -->
			<div class="col-xs-6 col-xs-offset-3 text-center" >
<h1 >ログイン</h1>
				<form method="POST" action="login.php">
				<?php if( $failed ) echo '<font color="red">ユーザ名またはパスワードが一致しません</font>';?>
					<p >ユーザID</p>
					<input type="TEXT" name="userID" style="margin-bottom:10px;"/><br>
					<p >パスワード</p>
					<input type="PASSWORD" name="pass"/><br>
					<button type="submit" style="margin-top:10px" class="btn-default">ログイン</button>
				</form>

			</div>
		</div>
				</div>
	</div>
</body>
</html>

