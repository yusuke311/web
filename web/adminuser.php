<?php
require_once("./sqlclass.php");
require_once("./userdataclass.php");

session_start();

//管理者でないまたは
//ユーザがセットされずかつPOSTされていない場合
if($_SESSION["admin"] == false || ( isset($_SESSION["userID_admin"]) == false && $_SERVER["REQUEST_METHOD"] != "POST" ) )
{
	header("Location: ./admin.php");
	exit;
}

//編集されるユーザが格納されていない場合格納する
if( isset($_SESSION["userID_admin"]) == false )

	//送られたユーザIDをセッションに記憶する
	//$_SESSION["userID"]はユーザページで使用しているため重複を避けるために別の名前を使用する
	$_SESSION["userID_admin"] = htmlspecialchars_decode($_POST["userID"],ENT_QUOTES);
}
$userdata = new UserData();

//DBへ接続
$userdata->SetMySQLData("localhost","webuser","user","website");
if( !$userdata->GetUserDataToMySQL($_SESSION["userID_admin"]) )
{
	header("Location: ./admin.php");
	exit;
}
//ユーザデータを取得する
$data = $userdata->GetUserData();
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
@media (min-width: 500px) {
        .container {
          max-width: 500px;
        }
}
</style>
<!-- BootstrapのCSS-->
<link href="css/bootstrap.min.css" rel="stylesheet">
<!-- BootstrapのJS読み込み -->
<script src="js/bootstrap.min.js"></script>
<!-- jQuery読み込み 郵便番号取得に必要 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="checkfunc.js"></script>

</head>
<body>
<a class="btn btn-link" href="admin.php" role="button">ユーザ一覧に戻る</a>
	<div class="container">
		<h1 class="page-header">ユーザページ(管理者)</h1>	
		<div class="well">
		<form method="POST" name="userdata" action="adminupdatecheck.php" onSubmit="return ParamCheck()">
			<div class="form-group">
				<div id="userID">
					ユーザID<span class="text-danger">※</span>
				</div>
				<input type="EMAIL" class="form-control" name="userID" value="<?php echo $data['userID']; ?>" maxlength="64"/><br>
			</div>
			<div class="form-group">
				<div id="pass">
					パスワード<span class="text-danger">※</span>
				</div>
				<input type="PASSWORD" class="form-control" name="pass" value="<?php echo $data['password']; ?>" maxlength="32" style="ime-mode:disabled"/><br>
			</div>
			<div class="form-group">
				<div id="pass_re">
					パスワード(確認)<span class="text-danger">※</span>
				</div>
				<input type="PASSWORD" class="form-control" name="pass_re" value="<?php echo $data['password']; ?>" maxlength="32" style="ime-mode:disabled"/><br>
			</div>
			<div class="form-group">
				<div id="name">
					氏名<span class="text-danger">※</span>
				</div>
				<input type="TEXT" class="form-control" name="name" value="<?php echo $data['name'];?>" maxlength="128"/><br>
			</div>
			<div class="form-group">
					郵便番号<br><input type="TEXT" pattern="\d{7}" class="form-control" name="postalcode"value="<?php echo $data['postal'];?>"maxlength="8" />
					<input type="button" class="btn btn-default"name="autoaddress" value="自動入力" onclick="Autopostal()"><br>
			</div>
			<div class="form-group">
					都道府県<br><input type="TEXT" class="form-control" name="pref" value="<?php echo $data['pref'];?>" maxlength="8"/><br>
			</div>
			<div class="form-group">
					市区町村<br><input type="TEXT" class="form-control" name="city" value="<?php echo $data['city'];?>" maxlength="16"/><br>
			</div>
			<div class="form-group">
					住所1<br><input type="TEXT" class="form-control" name="addr1" value="<?php echo $data['addr1'];?>" maxlength="128"/><br>
			</div>
			<div class="form-group">
					住所2<br><input type="TEXT" class="form-control" name="addr2" value="<?php echo $data['addr2'];?>" maxlength="128"/><br>
			</div>
			<div class="form-group">
					性別<br><input type="RADIO" class="radio-inline" name="sex" value="1"<?php if($data['sex'] == 1 )echo "checked='checked'";?>>男&nbsp;
					<input type="RADIO" class="radio-inline" name="sex" value="2"<?php if($data['sex'] == 2 )echo "checked='checked'";?>/>女&nbsp;
					<input type="RADIO" class="radio-inline" name="sex" value="0" <?php if($data['sex'] == 0 )echo "checked='checked'";?>/>未回答&nbsp;<br>
			</div>
			<div class="form-group">
					電話番号(半角数字のみ)<br><input type="TEXT"  class="form-control" name="tel" pattern="^([0-9]{10,})$" maxlength="11" value="<?php echo $data['tel'];?>" />
			</div>
			<div class="form-group">
					好きなモノは何ですか?<br>
					肉<input  type="CHECKBOX" class="check-inline" name="like[]" value="beef" <?php if($data['beef'] ==1 )echo "checked='checked'";?>/>
					野菜<input  type="CHECKBOX" class="check-inline" name="like[]" value="vegetable"<?php if($data['vegetable'] ==1 )echo "checked='checked'";?>/>
					魚<input  type="CHECKBOX" class="check-inline" name="like[]" value="fish" <?php if($data['fish'] ==1 )echo "checked='checked'";?>/>
			</div>
			<div class="form-group">
					登録状態<br>
					<input type="RADIO" class="radio-inline" name="registtype" value="0" <?php if($data['registtype'] == 0) echo "checked='checked'";?>/>仮登録
					<input type="RADIO" class="radio-inline" name="registtype" value="1" <?php if($data['registtype'] == 1) echo "checked='checked'";?>/>本登録
					<input type="RADIO" class="radio-inline" name="registtype" value="2" <?php if($data['registtype'] == 2) echo "checked='checked'";?>/>停止
			</div>
			<div class="form-group">
				<input type="SUBMIT"  class="btn btn-primary btn-block" value="更新"  /><br>
			</div>
			</form>
		</div>
	</div>
</body>
</html>


