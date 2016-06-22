<?php

require_once("./sqlclass.php");

session_start();

//データがない場合ユーザページに遷移する
if( !isset($_SESSION["userdata"]) )
{
	header("Location: user.php");
	exit;
}

//送られたjsonデータを変数に入れる
$userdata = json_decode($_SESSION["userdata"],true);

//update文
$SQL = "update userlist set	userID = :userID,password = :pass,name = :name,postal = :postalcode,pref = :pref,city = :city ,addr1 = :addr1,addr2 = :addr2,sex = :sex,tel = :tel,beef = :beef,vegetable = :vegetable,fish = :fish where userID = :beforeUserID";

//DB更新用のパラメータ
$Param = array(
	":userID"=>$userdata["userID"],
	":pass"=>$userdata["pass"],
	":name"=>$userdata["name"],
	":postalcode"=>$userdata["postalcode"],
	":pref"=>$userdata["pref"],
	":city"=>$userdata["city"],
	":addr1"=>$userdata["addr1"],
	":addr2"=>$userdata["addr2"],
	":sex"=>$userdata["sex"],
	":tel"=>$userdata["tel"],
	":beef"=>$userdata["beef"],
	":vegetable"=>$userdata["vegetable"],
	":fish"=>$userdata["fish"],
	":beforeUserID"=>$_SESSION["userID"]
);

try
{
	$mysql = new MyPDOClass();
	$mysql->ConnectSQL("MYSQL","localhost","webuser","user","website");
	$mysql->Transaction();	//トランザクション　始め
	$mysql->PrepareQuery($SQL);
	$mysql->Execute($Param);
	
	//ログイン時のユーザID(メアド)が更新したユーザIDと違う場合
	//変更したメアドにメールを送る
	$result = true;			//成功したかのフラグ
	$mailupdate = false;	//メールアドレスが更新されたかのフラグ
	if( $_SESSION["userID"] != $userdata["userID"] )
	{
		$Title = "ユーザ更新情報";
		$str = "メールアドレスを変更しました。";
		$header = "From: y-sasajima@systemzeus.co.jp";
		mb_language('ja');
		mb_internal_encoding("UTF-8");	
		if( !mb_send_mail($userdata["userID"],$Title,$str,$header))
		{
			$mysql->RollBack();
			$result = false;
			exit;
		}
		$mailupdate = true;
	}
	//正常終了した場合コミットする
	if( $result )
	{
		$mysql->Commit();		//トランザクション　終わり
	}

	//セッションの更新と余計なデータを削除する
	$_SESSION["userID"] = $userdata["userID"];
	unset($_SESSION["userdata"]);
}
catch( PDOException $e )
{
	echo "ERROR UPDATE".$e->getMessage();
	$mysql->RollBack();
}
?>
<html>
<head>
<!-- BootstrapのCSS-->
<link href="css/bootstrap.min.css" rel="stylesheet">
<!-- BootstrapのJS読み込み -->
<script src="js/bootstrap.min.js"></script>
<style>
@media (min-width: 300px) {
        .container {
          max-width: 300px;
        }
}
</style>
</head>
<body>
	<div class="container">
	<h1 class="page-header">
		<?php 
		if( $result )
		{
			echo "更新完了";
		}
		else
		{
			echo "更新失敗";
		}
		?>
	</h1>
	<p class="lead">
		<?php
		if( $result )
		{
			echo "ユーザ情報を更新しました。";
			if( $mailupdate  )
			{
				echo "変更したメールアドレスにメールが届きます。";
			}
		}
		else
		{
			echo "メールの送信に失敗しました。<br>ユーザデータは更新前に戻ります<br>";
		}
		?>
	</p>
	<a class="btn btn-default" href="user.php" role="button">ユーザページに戻る</a>
</body>
</html>
