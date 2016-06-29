<html>
<head>
<title>ユーザ登録</title>
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
<!-- 入力値チェック -->
<script type="text/javascript" src="checkfunc.js"></script>

</head>
<body>
<a href="login.php?">ログインページに戻る</a>
	<div class="container">
		<h1 class="page-header">ユーザ登録</h1>	
		<div class="well">
		<form method="POST" name="userdata" action="userregistrycheck.php" onSubmit="return ParamCheck()">
			<div class="form-group">
			<div id="userID"> ユーザID<span class="text-danger">※</span></div><input type="EMAIL" class="form-control" name="userID" maxlength="64" /><br>
			</div>
			<div class="form-group">
			<div id="pass">パスワード<span class="text-danger">※</span></div><input type="PASSWORD" class="form-control" name="pass" maxlength="32"  style="ime-mode:disabled"/><br>
			</div>
			<div class="form-group">
				<div id="pass_re">
					パスワード(確認)<span class="text-danger">※</span>
				</div><input type="PASSWORD" class="form-control"  name="pass_re" value="<?php echo $data['password']; ?>" maxlength="32" style="ime-mode:disabled"/><br>
			</div>
			<div class="form-group">
			<div id="name">氏名<span class="text-danger">※</span></div><input type="TEXT" class="form-control" name="name"maxlength="128"/><br>
			</div>
			<div class="form-group">
					郵便番号<br><input type="TEXT" pattern="\d{7}" title="未入力か半角数字7文字で入力してください" class="form-control" name="postalcode" maxlength="8"/>
					<input type="button" class="btn btn-default"name="autoaddress" value="自動入力" onclick="Autopostal()"><br>
			</div>
			<div class="form-group">
					都道府県<br><input type="TEXT" class="form-control" name="pref"i maxlength="8"/><br>
			</div>
			<div class="form-group">
					市区町村<br><input type="TEXT" class="form-control" name="city" maxlength="16"/><br>
			</div>
			<div class="form-group">
					住所1<br><input type="TEXT" class="form-control" name="addr1" maxlength="128"/><br>
			</div>
			<div class="form-group">
					住所2<br><input type="TEXT" class="form-control" name="addr2" maxlength="128"/><br>
			</div>


			<div class="form-group">
					性別<br><input type="RADIO" class="radio-inline" name="sex" value="1">男&nbsp;
					<input type="RADIO" class="radio-inline" name="sex" value="2"/>女&nbsp;
					<input type="RADIO" class="radio-inline" name="sex" value="0" checked="checked" />未回答&nbsp;<br>
			</div>
			<div class="form-group">
					電話番号(半角数字のみ)<br><input type="TEXT"  class="form-control" name="tel" pattern="^([0-9]{10,})$" title="半角数字で入力してください" maxlength="11" />
			</div>
			<div class="form-group">
					好きなモノは何ですか?<br>
					肉<input  type="CHECKBOX" class="check-inline" name="like[]" value="beef"/>
					野菜<input  type="CHECKBOX" class="check-inline" name="like[]" value="vegetable"/>
					魚<input  type="CHECKBOX" class="check-inline" name="like[]" value="fish"/>
			</div>
			<div class="form-group">
					<input type="SUBMIT"  class="form-control btn-primary" value="登録"  /><br>
			</div>
			</form>
		</div>
	</div>
</body>
</html>

