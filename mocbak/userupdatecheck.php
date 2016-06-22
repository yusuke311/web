<?php
require_once("./sqlclass.php");
session_start();

//POSTされていない場合ユーザページに遷移する
if($_SERVER["REQUEST_METHOD"] != "POST" )
{
	header("Location: user.php");
	exit;
}

try
{
	//ユーザIDが変更されていたら
	//ユーザIDの重複チェックを行う
	if( $_SESSION["userID"] != $_POST["userID"] )
	{
		$mysql = new MyPDOClass();
		$mysql->ConnectSQL("MYSQL","localhost","webuser","user","website");
		$mysql->PrepareQuery("select * from userlist where userID = :userID");
		$num = $mysql->GetDataCountToPrerare(array(":userID"=>$_POST["userID"]));

		//重複していたらメッセージを出して終了
		if( $num >= 1 )
		{
			echo "入力されたユーザ名がすでに存在します<br>";
			echo "<a href='./user.php'>ユーザページに戻る</a>";
			exit;
		}
	}
}
catch(PDOException $e)
{
	echo "ERROR 重複チェック".$e->getMessage();
}

//最初に０で初期化して、送られたチェックボックスの名前を調べる
//未選択時バグあり
$beef = 0;
$vegetable = 0;
$fish = 0;
$likestr= "";
foreach( $_POST["like"] as $var)
{
	if( $var == "beef" )
	{
		$beef = 1;
		$likestr .= "肉　"; 	
	}
	else if( $var == "vegetable" )
	{
		$vegetable = 1;
		$likestr .= "野菜　";
	}
	else
	{
		$fish = 1;
		$likestr .= "魚　";
	}
}
//データを更新完了画面に送るためにjson形式にする
$_SESSION["userdata"]  = json_encode( 
	array(
		"userID"=>$_POST["userID"],
		"pass"=>$_POST["pass"],
		"name"=>$_POST["name"],
		"postalcode"=>$_POST["postalcode"],
		"pref"=>$_POST["pref"],
		"city"=>$_POST["city"],
		"addr1"=>$_POST["addr1"],
		"addr2"=>$_POST["addr2"],
		"sex"=>$_POST["sex"],
		"tel"=>$_POST["tel"],
		"beef"=>$beef,
		"vegetable"=>$vegetable,
		"fish"=>$fish
	) );

//HTML出力用文章生成
$sexstr;
switch($_POST["sex"])
{
case 0:
	$sexstr = "未回答";
	break;
case 1:
	$sexstr = "男";
	break;
case 2:
	$sexstr = "女";
	break;
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
<script type="text/javascript">
function submitcancel()
{
	location.href = "./user.php";
}
</script>

</head>
<body>
	<div class="container">
		<h1 class="page-header">更新情報の確認</h1>
		<table class="table">
			<tr><td>ユーザID</td><td><?php echo $_POST["userID"];?></td>
			<tr><td>パスワード</td><td><?php echo $_POST["pass"];?></td>
			<tr><td>氏名</td><td><?php echo $_POST["name"];?></td>
			<tr><td>郵便番号</td><td><?php echo $_POST["postalcode"];?></td>
			<tr><td>都道府県</td><td><?php echo $_POST["pref"];?></td>
			<tr><td>市区町村</td><td><?php echo $_POST["city"];?></td>
			<tr><td>住所１</td><td><?php echo $_POST["addr1"];?></td>
			<tr><td>住所２</td><td><?php echo $_POST["addr2"];?></td>
			<tr><td>性別</td><td><?php echo $sexstr;?></td>
			<tr><td>好きなもの</td><td><?php echo $likestr;?></td>
		</table>
		<form method="POST" action="userupdate.php">
			<button class="btn btn-primary" type="SUBMIT">更新</button>
			<button class="btn" type="BUTTON" onclick="submitcancel()">キャンセル</button>
		</form>
	</div>
</body>

