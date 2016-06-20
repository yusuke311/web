<?php

require_once("./sqlclass.php");
session_start();

if( $_SESSION["admin"] == false )
{
	header("Location: ./login.php");
	exit;
}

//ユーザ情報テーブル
$userlist = array();

$SQL = "select userID , name , registtype , registdate , lastlogindate from userlist";

try
{
	$mysql = new MyPDOClass();
	$mysql->ConnectSQL("MYSQL","localhost","webuser","user","website");
	$mysql->PrepareQuery($SQL);
	if( !$mysql->Execute() )
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

		$userlist[count] = array("userID"=>$result["userID"],
								"name"=>$result["name"],
								"registtype"=>$result["registtype"],
								"registdate"=>$result["registdate"],
								"lastlogindate"=>$result["lastlogindate"]);	
		print_r($userlist[$count]);	
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
</head>
<body>

<table >
<tr>
<th>ログインID</th>
<th>氏名</th>
<th>登録状態</th>
<th>登録時刻</th>
<th>最終ログイン時刻</th>
</tr>
<?php
	foreach( $userlist as $user )
	{
		echo "<tr>";
		echo "<td>".$user["userID"]."</td>";
		echo "<td>".$user["name"]."</td>";
		echo "<td>".$user["registtype"]."</td>";
		echo "<td>".$user["registdate"]."</td>";
		echo "<td>".$user["lastlogindate"]."</td>";
		echo "</tr>";
	}
?>
</table>
</body>
</html>

