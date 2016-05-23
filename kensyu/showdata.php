<?php
function SEXstring($num)
{
	switch($num)
	{
	case 0:
		return  "男";
		break;
	case 1:
		return "女";
		break;
	default:
		return "未回答";
		break;
	}

}
$name;
$pass;
$sex;
$prefect;
$address;
$tel;
$email;
$favorite;
$comment;

if( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	$name = $_POST["name"];
	$pass = $_POST["pass"];
	$sex = $_POST["sex"];
	$prefect = $_POST["pref"];
	$address = $_POST["address"];
	$tel = $_POST["tel"];
	$email = $_POST["email"];
	$favorite = $_POST["favorite"];
	$comment = $_POST["comment"];

	//var_dump($_POST);
	echo "<br>";

	echo "名前=${name}<br>";
	echo "パスワード=${pass}<br>";
	echo "性別=".SEXstring($sex)."<br>";
	echo "都道府県=${prefect}<br>";
	echo "住所=${address}<br>";
	echo "電話番号=${tel}<br>";
	echo "e-mail=${email}<br>";
	echo "興味=${favorite}<br>";
	echo "コメント=${comment}<br>";

}
else
{
	echo "POSTされていません";
}

?>
