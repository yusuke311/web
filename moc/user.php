<?php
require_once("./sqlclass.php");
require_once("./userdataclass.php");

session_start();
if(!isset($_SESSION["userID"]))
{
	header("Location: ./login.php");
	exit;
}

$userID = $_SESSION["userID"];
$userdata = new UserData();

//DBへ接続
$userdata->SetMySQLData("localhost","webuser","user","website");
if( !$userdata->GetUserDataToMySQL($userID) )
{
	echo "存在しない不正なユーザです";
}
//ユーザデータを取得する
$data = $userdata->GetUserData();
$_SESSION["userpage"] = true;
?>
<html>
<head>
<title>ユーザーページ</title>
<style>
.centerleft
{
	margin-left:auto;
	margin-right:auto;
	width:200px;
}
input
{
	margin-top:10px;
	margin-bottom:20px;
	margin-left:2px;
	border-top-width: 2px;
	border-left-width: 1px;
	margin-right: 2px;
	border-right-width: 2px;
	margin-left: 2px;
	padding:2px;
}
@media (min-width: 300px) {
        .container {
          max-width: 300px;
        }
}
</style>
<!-- BootstrapのCSS-->
<link href="css/bootstrap.min.css" rel="stylesheet">
<!-- BootstrapのJS読み込み -->
<script src="js/bootstrap.min.js"></script>
<!-- jQuery読み込み 郵便番号取得に必要 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
	function ParamCheck()
	{
		var check = true;
		if( document.userdata.userID.value == "" )
		{
			check = false;
			var userID = document.getElementById("userID");
			userID.innerHTML = "ユーザID※　<font color='red'>入力されていません</font>";
		}
		if( document.userdata.pass.value == "" )
		{
			check = false;
			var pass = document.getElementById("pass");
			pass.innerHTML = "パスワード※　<font color='red'>入力されていません</font>";
		}
		if( document.userdata.name.value == "" )
		{
			check = false;
			var name = document.getElementById("name");
			name.innerHTML = "氏名※　<font color='red'>入力されていません</font>";
		}

		if( !check )
		{
			alert("必要項目が入力されていません");
		}	

		return check;
	}		
	function Autopostal()
	{
		$.post("getpostal.php",
			{ "postalcode": document.userdata.postalcode.value },
			function(data)
			{
				if(!data.status)
				{
					return false;
				}

				document.userdata.pref.value =  data.pref;
				document.userdata.city.value = data.city;
				document.userdata.addr1.value = data.addr;
			},
			"json"
		);
	}
</script>

</head>
<body>
<a href="login.php?logout">ログアウト</a>
	<div class="container">
		<h1 class="page-header">ユーザページ</h1>	
		<form method="POST" name="userdata" action="userupdatecheck.php" onSubmit="return ParamCheck()">
			<div class="form-group">
			<div id="userID"> ユーザID</div><input type="TEXT" class="form-control" name="userID" value="<?php echo $data['userID']; ?>"/><br>
			</div>
			<div class="form-group">
			<div id="pass">パスワード</div><input type="PASSWORD" class="form-control" name="pass" value="<?php echo $data['password']; ?>"/><br>
			</div>
			<div class="form-group">
			<div id="name">氏名</div><br><input type="TEXT" class="form-control" name="name" value="<?php echo $data['name'];?>" /><br>
			</div>
			<div class="form-group">
					郵便番号<br><input type="TEXT" pattern="\d{7}" class="form-control" name="postalcode"value="<?php echo $data['postal'];?>" />
					<input type="button" class="btn btn-default"name="autoaddress" value="自動入力" onclick="Autopostal()"><br>
			</div>
			<div class="form-group">
					都道府県<br><input type="TEXT" class="form-control" name="pref" value="<?php echo $data['pref'];?>"/><br>
			</div>
			<div class="form-group">
					市区町村<br><input type="TEXT" class="form-control" name="city" value="<?php echo $data['city'];?>"/><br>
			</div>
			<div class="form-group">
					住所1<br><input type="TEXT" class="form-control" name="addr1" value="<?php echo $data['addr1'];?>"/><br>
			</div>
			<div class="form-group">
					住所2<br><input type="TEXT" class="form-control" name="addr2" value="<?php echo $data['addr2'];?>"/><br>
			</div>


			<div class="form-group">
					性別<br><input type="RADIO" class="radio-inline" name="sex" value="1"<?php if($data['sex'] == 1 )echo "checked='checked'";?>>男&nbsp;
					<input type="RADIO" class="radio-inline" name="sex" value="2"<?php if($data['sex'] == 2 )echo "checked='checked'";?>/>女&nbsp;
					<input type="RADIO" class="radio-inline" name="sex" value="0" <?php if($data['sex'] == 0 )echo "checked='checked'";?>/>未回答&nbsp;<br>
			</div>
			<div class="form-group">
					電話番号<br><input type="TEXT"  class="form-control" name="tel" pattern="^([a-zA-Z0-9]{10,})$" maxlength="11" value="<?php echo $data['tel'];?>" />
			</div>
			<div class="form-group">
					好きなモノは何ですか?<br>
					肉<input  type="CHECKBOX" class="check-inline" name="like[]" value="beef" <?php if($data['beef'] ==1 )echo "checked='checked'";?>/>
					野菜<input  type="CHECKBOX" class="check-inline" name="like[]" value="vegetable"<?php if($data['vegetable'] ==1 )echo "checked='checked'";?>/>
					魚<input  type="CHECKBOX" class="check-inline" name="like[]" value="fish" <?php if($data['fish'] ==1 )echo "checked='checked'";?>/>
			</div>
			<div class="form-group">
					<input type="SUBMIT"  class="form-control" value="更新"  /><br>
			</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>

