<?php

require_once("./sqlclass.php");
session_start();

if( $_SESSION["admin"] == false )
{
	header("Location: ./login.php");
	exit;
}

unset($_SESSION["userID_admin"]);

//検索情報を取得
if( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	$searchID = $_POST["searchID"];
	$searchname = $_POST["searchname"];
}

//ユーザリスト情報テーブル
$userlist = array();

$SQL = "select userID , name , registtype , registdate , lastlogindate from userlist";
$Param = array();
//検索ワードが入力されていたら条件を追加する
if( $searchID != NULL )
{
	$SQL .= " where userID LIKE :userID";
	$searchIDstr = "%".$searchID."%";
	$Param = array(":userID"=>$searchIDstr); 
}
try
{
	$mysql = new MyPDOClass();
	$mysql->ConnectSQL("MYSQL","localhost","webuser","user","website");
	$mysql->PrepareQuery($SQL);
	if( !$mysql->Execute($Param) )
	{
		echo "execute error";
		exit;
	}
	$count = 0;
	while( $result = $mysql->fetch_assoc() )
	{
		switch($result["registtype"])
		{
		case 0:
			$result["registtype"] = "仮登録";
			break;
		case 1:
			$result["registtype"] = "本登録";
			break;
		case 2:
			$result["registtype"] = "停止";
			break;
		}

		if( $result["lastlogindate"] == NULL )
		{
			$result["lastlogindate"] = "未ログイン";
		}

		$userlist[$count] = array("userID"=>$result["userID"],
								"name"=>$result["name"],
								"registtype"=>$result["registtype"],
								"registdate"=>$result["registdate"],
								"lastlogindate"=>$result["lastlogindate"]);	
		$count++;
	}
}
catch( PDOException $e  )
{
	echo "ERROR".$e->getMessage();
}

?>
<html>
<head>
<!-- BootstrapのCSS-->
<link href="css/bootstrap.min.css" rel="stylesheet">
<!-- BootstrapのJS読み込み -->
<script src="js/bootstrap.min.js"></script>
<style>
@media (min-width: 1200px) {
        .container {
          max-width: 1200px;
        }
}
</style>
<title>ユーザ一覧</title>
</head>
<body>
<a class="btn btn-link" href="login.php?logout" role="button">ログアウト</a>
<div class="container">
<h1 class="page-header">ユーザリスト</h1>
<form method="POST" action="admin.php" >
	<div class="form-group form-inline">
		ユーザID<input type="TEXT" name="searchID" class="form-control" maxlength="64"/>
		氏名<input type="TEXT" name="searchname" class="form-control" maxlength="64"/>
			<input type="SUBMIT"  class="btn btn-primary" class="form-control" value="検索" /><br>
	</div>
</form>
 
<table class="table" >
<tr>
<th>ログインID</th>
<th>氏名</th>
<th>登録状態</th>
<th>登録時刻</th>
<th>最終ログイン時刻</th>
<th></th>
</tr>
<?php
	foreach( $userlist as $user )
	{
?>
		<tr>
		<td><?php echo htmlspecialchars($user["userID"],ENT_QUOTES) ?></td>
		<td><?php echo htmlspecialchars($user["name"]) ?></td>
		<td><?php echo $user["registtype"] ?></td>
		<td><?php echo $user["registdate"] ?></td>
		<td><?php echo $user["lastlogindate"] ?></td>
		<td>
		<form method="POST"  action="adminuser.php">
		<button type="submit" class="btn btn-primary" name="userID" value="<?php echo htmlspecialchars($user["userID"],ENT_QUOTES) ?>">編集</button>
		</td>
		</tr>
<?php } ?>
</table>
</div>
</body>
</html>

