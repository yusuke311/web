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
<a href="login.php?logout">ログアウト</a>
<div class="container">
<h1 class="page-header">ユーザリスト</h1>
<form method="POST" action="admin.php" >
	<div class="form-group">
		ユーザID<input type="TEXT" name="searchID" maxlength="64"/>
		氏名<input type="TEXT" name="searchname" maxlength="64"/>
			<input type="SUBMIT"  class="btn btn-primary" value="検索" /><br>
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
		echo "<tr>";
		echo "<td>".htmlspecialchars($user["userID"],ENT_QUOTES)."</td>";
		echo "<td>".htmlspecialchars($user["name"])."</td>";
		echo "<td>".$user["registtype"]."</td>";
		echo "<td>".$user["registdate"]."</td>";
		echo "<td>".$user["lastlogindate"]."</td>";
		echo "<td>";
		echo "<form method='POST'action='adminuser.php'>";
		echo "<button type='submit' class='btn btn-primary' name='userID' value='".htmlspecialchars($user["userID"],ENT_QUOTES)."'>編集</button>";
		echo "</td>";
		echo "</tr>";
	}
?>
</table>
</div>
</body>
</html>

