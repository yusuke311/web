<html>
<body>
<?php

	$mail;

	if( $_SERVER["REQUEST_METHOD"] == "POST" )
	{
		$mail = $_POST["mail"];
	}

	if( mail($mail,"TEST","This is a test message!","From: centOS@example.co.jp"))
	{
		echo "メールの送信に成功しました。";
	}	
	else
	{
		echo "メールの送信に失敗しました。";
	}

?>
</body>
</html>
