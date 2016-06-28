<?php
require_once("./sqlclass.php");
session_start();

//POSTされていない場合ユーザページに遷移する
if($_SERVER["REQUEST_METHOD"] != "POST" )
{
	header("Location: user.php");
	exit;
}
//重複フラグ
$IDisUse = false;
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
			$IDisUse = true;
		}
	}
}
catch(PDOException $e)
{
	header("Location: user.php");
	exit;
	//echo "ERROR 重複チェック".$e->getMessage();
}

//最初に０で初期化して、送られたチェックボックスの名前を調べる
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
		"userID"=>htmlspecialchars($_POST["userID"],ENT_QUOTES),
		"pass"=>htmlspecialchars($_POST["pass"],ENT_QUOTES),
		"name"=>htmlspecialchars($_POST["name"],ENT_QUOTES),
		"postalcode"=>htmlspecialchars($_POST["postalcode"],ENT_QUOTES),
		"pref"=>htmlspecialchars($_POST["pref"],ENT_QUOTES),
		"city"=>htmlspecialchars($_POST["city"],ENT_QUOTES),
		"addr1"=>htmlspecialchars($_POST["addr1"],ENT_QUOTES),
		"addr2"=>htmlspecialchars($_POST["addr2"],ENT_QUOTES),
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
@media (min-width: 500px) {
        .container {
          max-width: 500px;
        }
}
</style>
<script type="text/javascript">
function submitcancel()
{
	location.href = "./user.php";
}
</script>
<title>更新確認</title>
</head>
<body>
	<div class="container">
		<?php if( $IDisUse == false ){ ?>
		<h1 class="page-header">更新情報の確認</h1>
		<table class="table">
			<tr><td>ユーザID</td><td><?php echo htmlspecialchars($_POST["userID"]);?></td>
			<tr><td>パスワード</td><td><?php echo htmlspecialchars($_POST["pass"]);?></td>
			<tr><td>氏名</td><td><?php echo htmlspecialchars($_POST["name"]);?></td>
			<tr><td>郵便番号</td><td><?php echo $_POST["postalcode"];?></td>
			<tr><td>都道府県</td><td><?php echo htmlspecialchars($_POST["pref"]);?></td>
			<tr><td>市区町村</td><td><?php echo htmlspecialchars($_POST["city"]);?></td>
			<tr><td>住所１</td><td><?php echo htmlspecialchars($_POST["addr1"]);?></td>
			<tr><td>住所２</td><td><?php echo htmlspecialchars($_POST["addr2"]);?></td>
			<tr><td>性別</td><td><?php echo $sexstr;?></td>
			<tr><td>好きなもの</td><td><?php echo $likestr;?></td>
		</table>
		<form method="POST" action="userupdate.php">
			<button class="btn btn-primary" type="SUBMIT">更新</button>
			<button class="btn" type="BUTTON" onclick="submitcancel()">キャンセル</button>
		</form>
		<?php } else { ?>
		<h1 class="page-header">ユーザの重複</h1>
		ユーザIDが重複しています<br>
		<a href='./user.php'>ユーザページに戻る</a>
		<?php } ?>
	</div>
</body>

