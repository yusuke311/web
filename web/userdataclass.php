<?php
require_once("./sqlclass.php");
class UserData
{
	private $userID;		//ユーザID
	private $password;		//パスワード
	private $name;			//氏名
	private $postalcode;	//郵便番号
	private $prefecture;	//都道府県
	private $city;			//市区町村
	private $addr1;			//住所１
	private $addr2;			//住所２(ビル名等)
	private $sex;			//性別
	private $question;		//連想配列

	private $data;			//すべての連想配列データ

	private $mysql;

	public function __construct()
	{
		$this->mysql = new MyPDOClass();
		$this->data = array();
	}

	public function SetMySQLData($hostname,$username,$pass,$dbname)
	{
		try
		{
			$this->mysql->ConnectSQL("MYSQL",$hostname,$username,$pass,$dbname);
		}
		catch(PDOException $e)
		{
			echo "UserDataClass MYSQL<br>";
			echo "Error=".$e->getMessage();
		}
	}

	public function GetUserDataToMySQL($userID,$pass = NULL)
	{
		try
		{
			//共通機能部分のSQL
			$sql = "select * from userlist where userID = :userID";
			$param = array(":userID"=>$userID);

			//パスワード認証をつける場合追加する
			if( $pass != NULL )
			{
				$sql .= " and password = :password";
				$param += array(":pass"=>$pass);
			}

			$this->mysql->PrepareQuery($sql);

			if( !$this->mysql->Execute($param) )
			{
				echo "Execute 失敗";	
			}
			$count = 0;
			//データを連想配列で取得する
			while( $result = $this->mysql->fetch_assoc() )
			{
				$this->data = $result;
				$count++;
			}	
			//複数あったり、存在しなかった場合 falseを返す
			if( $count != 1 )
			{
				return false;
			}

			return true;
		}	
		catch(PDOException $e)
		{

		}
	}

	public function GetUserData($Sanitizing = false)
	{
		if( $Sanitizing )
		{
			$this->Sanitizing();
		}
		else
		{
			$this->UnSanitizing();
		}
		return $this->data; 
	}

	//サニタイジング
	private function Sanitizing()
	{
		foreach( $this->data as &$var )
		{
			$var = htmlspecialchars($var);
		}
	}

	private function UnSanitizing()
	{
		foreach( $this->data as &$var )
		{
			$var = htmlspecialchars($var);
		}
	}

}


?>
