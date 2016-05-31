<?php
	session_start();
if( isset($_SESSION["username"])  )
{
	header("Location: home.php");
}

		
?>
<html>
<head>
<title>トップ</title>
</head>
<body>
<form method="POST" action"login.php" name="LoginForm">
	ユーザ名<br><input type="text" maxlength="64" /><br><br>
	パスワード<br><input type="PASSWORD" /><br><br>
	<input type="SUBMIT" value="ログイン"/>
</form>
<br><br>
<a href="usercreate.html" >ユーザ登録</a>
</body>
</html>

