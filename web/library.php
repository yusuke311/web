<?php
require_once("./sqlclass.php");
header('Content-type: text/html; charset=utf-8');
//DB内にある値とかぶらないトークンを取得する
function GetOnlyOneTokenMySQL($host,$user,$pass,$dbname,$tablename,$column)
{
	$mysql = new MyPDOClass();
	$mysql->ConnectSQL("MYSQL",$host,$user,$pass,$dbname);

	try
	{
		$token;
		//重複しないトークンを生成するまでループ
		while( 1 )
		{
			//トークン生成
			$token = openssl_random_pseudo_bytes(16);
			$SQL = "select * from ".$tablename." where ".$column." = :token";
			$mysql->PrepareQuery($SQL);
			if( !$mysql->Execute(array(":token"=>$token)) )
			{
				echo "tokenERROR".$token;
			}

			if( !$mysql->fetch_num() )
			{
				break;
			}

		}
	}
	catch(PDOException $e)
	{
		echo "PDOException".$e->getMessage();
	}
	return bin2hex($token);
}

function GetUserIDToTokenMySQL($host,$user,$pass,$dbname,$tablename,$token)
{
	$mysql = new MyPDOClass();
	$mysql->ConnectSQL("MYSQL",$host,$user,$pass,$dbname);

	$SQL = "select userID from ".$tablename." where token = :token ";

	try
	{
		$mysql->PrepareQuery($SQL);
		if( !$mysql->Execute(array(":token"=>$token)) )
		{
			echo "tokenERROR".$token;
		}
		
		$result = $mysql->fetch_assoc();
		if( !$result )
		{
			return NULL;
		}

		return $result["userID"];

	}	
	catch(PDOException $e)
	{
		echo "ERROR->GetPairTOTOkenMySQL()".$e->getMessage();
	}
}

?>
