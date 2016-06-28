<?php
	require_once("./sqlclass.php");
	require_once("./library.php");
	session_start();

	//管理者ID
	$adminID 	= "admin";
	//管理者パス
	$adminpass	= "suez";

	$userID;
	$pass;
	//ユーザログインに失敗したかのフラグ
	$failed = false;

	//ログアウト処理を行う
	if( $_SERVER["REQUEST_METHOD"] == "GET" )
	{
		if( isset($_GET["logout"]) )
		{
			$_SESSION = array();
			if (ini_get("session.use_cookies")) {
			    $params = session_get_cookie_params();
			    setcookie(session_name(), '', time() - 42000,
				        $params["path"], $params["domain"],
					    $params["secure"], $params["httponly"]
			    );
			}
			session_destroy();
			header("Location: ./login.php");
			exit;
		}
	}

	//ユーザ認証を行う
	if($_SERVER["REQUEST_METHOD"] == "POST" )
	{
		$userID = $_POST["userID"];
		$pass	= $_POST["pass"];

		//管理者と一致していたら管理者ページへ遷移する
		if( $userID == $adminID && $adminpass == $pass )
		{
			session_start();
			session_regenerate_id(true);
			$_SESSION["admin"] = true;	
			unset($_SESSION["userID"]);
			header("Location: admin.php");
			exit;
		}

		//ユーザと一致しているか調べる
		try
		{
			$mysql = new MyPDOClass();
			$mysql->ConnectSQL("MYSQL","localhost","webuser","user","website");
			$mysql->PrepareQuery("select * from userlist where userID = :userID and password = :password and registtype = 1");
			if( !$mysql->Execute(array(":userID"=>$userID,":password"=>$pass)) )
			{
				//	echo "Executeエラー";
				exit;
			}
		
			//userが存在した場合
			//ログイン時刻を更新しユーザページへ遷移する
			if( $mysql->fetch_num() )
			{
				$mysql->PrepareQuery("update userlist set lastlogindate = now() where userID = :userID");
				if( !$mysql->Execute(array(":userID"=>$userID)))
				{
					echo "ERROR";
				}
				session_start();
				session_regenerate_id(true);
				$_SESSION["userID"] = $userID;	
				unset($_SESSION["admin"]);	
				header("Location: user.php");		
				exit;
			}
			$failed = true;	
		}
		catch(PDOException $e)
		{
		//	echo "エラー".$e->getMessage();
			exit;
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
    <!-- BootstrapのJS読み込み -->
	<script src="js/bootstrap.min.js"></script>
<style>
@media (min-width: 400px) {
        .container {
          max-width: 400px;
        }
}
</style>
<title>ログイン</title>
</head>
<body>
	<div class="center-block">
		<div class="row">
			<div class="col-xs-6 col-xs-offset-3 text-center" >
				<div class="row">
					<div class="col-xs-3 col-xs-offset-6 text-right">
						<a class="btn btn-link" href="userregistry.php" rele="button">新規登録</a>	
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<h1 class="page-header">ログイン</h1>
		<div class="well">
		<form method="POST" action="login.php">
			<?php if( $failed ) echo '<font color="red">ユーザ名またはパスワードが間違っています。</font>';?>
			<div class="form-group">
				ユーザID<input type="TEXT" name="userID" class="form-control" maxlength="64" /><br>
			</div>
			<div class="form-group">
				パスワード<input type="PASSWORD" class="form-control" maxlength="32" pattern="^[0-9A-Za-z]+$"  name="pass"/><br>
			</div>
			<button type="submit" class="btn btn-primary form-control">ログイン</button>
		</form>
</div>
	</div>
</body>
</html>

