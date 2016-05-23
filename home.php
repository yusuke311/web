<html>
<body>

<?php
$username = "";
$password = "";
$login = false;

//サーバへのGETまたはPOSTリクエストがあった場合
if( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	$username = $_POST["username"];
	$password = $_POST["password"];

	if( strcmp($username,"sasajima") == 0 && strcmp($password,"yuusuke") == 0 )
	{
		$login = true;
	}
	else
	{
		header("location: ./index.html");
		exit;
	}
}

else
{
		header("location: ./index.html");
		exit;
}

if( $login == true )
{
	echo "ようこそ".$username."さん<br><br>";
	echo "<a href=\"./index.html\">ログイン画面に戻る</a><br>";
}

?>

</body>
</html>
