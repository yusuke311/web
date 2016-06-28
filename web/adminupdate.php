<?php

require_once("./sqlclass.php");

session_start();

//データがない場合ユーザページに遷移する
if( !isset($_SESSION["userdata"]) )
{
	header("Location: adminiuser.php");
	exit;
}

//送られたjsonデータを変数に入れる
$userdata = json_decode($_SESSION["userdata"],true);
//jsonように値が変換されているため元の文に戻す
foreach( $userdata as &$var )
{
	$var = htmlspecialchars_decode( $var , ENT_QUOTES );
}

//update文
$SQL = "update userlist set	userID = :userID,password = :pass,name = :name,postal = :postalcode,pref = :pref,city = :city ,addr1 = :addr1,addr2 = :addr2,sex = :sex,tel = :tel,beef = :beef,vegetable = :vegetable,fish = :fish  , registtype = :registtype where userID = :beforeUserID";

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
	":beforeUserID"=>$_SESSION["userID_admin"],
	":registtype"=>$userdata["registtype"]
);

try
{
	$result = true;
	$mysql = new MyPDOClass();
	$mysql->ConnectSQL("MYSQL","localhost","webuser","user","website");
	$mysql->Transaction();	//トランザクション　始め
	$mysql->PrepareQuery($SQL);
	$mysql->Execute($Param);

	$mysql->Commit();		//トランザクション　終わり

	//セッションの更新と余計なデータを削除する
	$_SESSION["userID_admin"] = $userdata["userID"];
	unset($_SESSION["userdata"]);
}
catch( PDOException $e )
{
	$result = false;
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
<title>更新</title>
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
		}
		else
		{
			echo "エラーが発生しました。管理者に問い合わせてください";
		}
		?>
	</p>
	<a class="btn btn-default" href="adminuser.php" role="button">ユーザページに戻る</a>
</body>
</html>
