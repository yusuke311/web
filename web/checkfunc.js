function ParamCheck()
{
	var check = true;
	var userID = document.getElementById("userID");
	var pass = document.getElementById("pass");
	var pass_re = document.getElementById("pass_re");
	var name = document.getElementById("name");

	userID.innerHTML = "ユーザID<span class='text-danger'>※</span>";
	pass.innerHTML = "パスワード<span class='text-danger'>※</span>";
	pass_re.innerHTML = "パスワード(確認)<span class='text-danger'>※</span>";
	name.innerHTML = "氏名<span class='text-danger'>※</span>";

	if( document.userdata.userID.value == "" )
	{
		check = false;
		userID.innerHTML = "ユーザID<span class='text-danger'>※　入力されていません</span>";
	}
	//どちらも空白の場合
	if( document.userdata.pass.value == "" && document.userdata.pass_re.value == ""  )
	{
		check = false;
		pass.innerHTML = "パスワード<span class='text-danger'>※　入力されていません</span>";
		pass_re.innerHTML = "パスワード(確認)<span class='text-danger'>※　入力されていません</span>";
	}
	//どちらか空白の場合
	else if( document.userdata.pass.value == "" || document.userdata.pass_re.value == "" )
	{
		check = false;
		if( document.userdata.pass.value == "" )
		{
			pass.innerHTML = "パスワード<span class='text-danger'>※　入力されていません</span>";
		}
		else
		{
			pass_re.innerHTML = "パスワード(確認)<span class='text-danger'>※　入力されていません</span>";
		}
	}
	//一致していない場合
	else if( document.userdata.pass.value != document.userdata.pass_re.value )
	{
		check = false;
		pass.innerHTML = "パスワード<span class='text-danger'>※　一致していません</span>";
		pass_re.innerHTML = "パスワード(確認)<span class='text-danger'>※　一致していません</span>";
	}
	if( document.userdata.name.value == "" )
	{
		check = false;
		name.innerHTML = "氏名<span class='text-danger'>※　入力されていません</span>";
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
