<?php
echo "EEEE";
require_once("./sqlclass.php");
require_once("./library.php");

session_start();
$highgraderegistry;	//本登録かどうかのフラグ
//本登録
if( $_SERVER["REQUEST_METHOD"] == "GET" )
{
	$highgraderegistry = true;
	$token = $_GET["ID"];

	//トークンからユーザIDを取得する
	$userID = GetUserIDToTokenMySQL("localhost","webuser","user","website","tokenlist",$token);

	//存在しないトークンの場合ログイン画面に遷移する
	if( $userID == NULL )
	{
		header("Location: login.php");
		exit;
	}
	
	try
	{
		$mysql = new MyPDOClass();
		$mysql->ConnectSQL("MYSQL","localhost","webuser","user","website");
		$result = true;
		$mysql->Transaction();	//トランザクション　始め

		//本登録処理SQL プリペアにするべき
		$updateSQL = "update userlist set registtype = 1 where userID = '".$userID."'";
		$deleteSQL = "delete from tokenlist where token = '".$token."'";
		//取得処理でないため　受け取ることはしない
		$mysql->QuickQuery($updateSQL);
		//トークン削除
		$mysql->QuickQuery($deleteSQL);
	}
	catch( PDOException $e )
	{
		$mysql->RollBack();
		echo "DBERROR -> ".$e->getMessage();
		$result = false;
	}
	$mysql->Commit();
}
//仮登録
else
{
	$highgraderegistry = false;
	//データがない場合ログインページに遷移する
	if( !isset($_SESSION["userdata"]) )
	{
		session_destroy();
		header("Location: login.php");
		exit;
	}

	//jsonデータを変数に入れる
	$userdata = json_decode($_SESSION["userdata"],true);

	//update文
	$SQL = "insert into  userlist values(:userID,:pass,:name,:postalcode,:pref,:city ,:addr1,:addr2,:sex,:tel,:beef,:vegetable,:fish ,0,now(),NULL)";
	//トークン追加文
	$TokenSQL = "insert into tokenlist values(:token,:userID)";

	foreach( $userdata as &$data )
	{
		if( $data == "" )
		{
			$data = NULL;
		}
	}

	//DB用パラメータ
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
		":fish"=>$userdata["fish"]
	);

	try
	{
		$mysql = new MyPDOClass();
		$mysql->ConnectSQL("MYSQL","localhost","webuser","user","website");
		$mysql->Transaction();	//トランザクション　始め
		$mysql->PrepareQuery($SQL);
		$mysql->Execute($Param);	//userlistテーブルに登録
		
		//DBに登録されているトークンとかぶらないトークンを生成する
		$token = GetOnlyOneTokenMySQL("localhost","webuser","user","website","tokenlist","token");
		//トークンの登録用SQLを渡す
		$mysql->PrepareQuery($TokenSQL);

		//トークンの登録
		if( !$mysql->Execute(array(":token"=>$token,":userID"=>$userdata["userID"])) )
		{
			echo "Execute エラー";
			$mysql->RollBack();
			exit;
		}
		
		$result = true;		//HTML出力用フラグ
		//本登録用メールを送る
		$Title = "ユーザ仮登録";
		$str = "リンクをクリックすると本登録完了になります。\nhttp://192.168.198.129/training/moc/userregistrydone.php?ID=".$token;
		$header = "From: y-sasajima@systemzeus.co.jp";
		mb_language('ja');
		mb_internal_encoding("UTF-8");	
		if( !mb_send_mail($userdata["userID"],$Title,$str,$header))
		{
			echo "first";
			$mysql->RollBack();
			$result = false;
			exit;
		}

		//正常終了した場合コミットする
		$mysql->Commit();		//トランザクション　終わり

		//セッションの更新と余計なデータを削除する
		unset($_SESSION["userdata"]);
		session_destroy();
	}
	catch( PDOException $e )
	{
		echo "ERROR INSERT".$e->getMessage();
		$mysql->RollBack();
	}

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
		if( $highgraderegistry  )
		{
			echo "本登録";
		}
		else
		{
			echo "仮登録";
		}
		if( $result )
		{
			echo "完了";
		}
		else
		{
			echo "失敗";
		}
		?>
	</h1>
	<p class="lead">
		<?php
		if( $result )
		{
			echo "ユーザ情報を登録しました。<br>";
			if( !$highgraderegistry )
			{
				echo "登録したメールアドレスにメールが届きます。<br>メールにあるリンクから本登録を行ってください。";
			}
		}
		else
		{
			echo "メールの送信に失敗しました。<br>再度登録してください<br>";
		}
		?>
	</p>
	<a class="btn btn-default" href="user.php" role="button">ログインページに行く</a>
</body>
</html>
