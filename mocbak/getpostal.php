
<?php
	require_once("./sqlclass.php");
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$code = $_POST["postalcode"];
		
		//echo json_encode(array("status"=>true,"pref"=>"茨城県","city"=>"那珂市","addr"=>"後台"));
		//return;
		if( strlen($code) != 7 || !ctype_digit($code)  )
		{
			echo json_encode(array("status"=>false,"errormsg"=>"パラメータエラー"));
			return;
		}

		try
		{
			$mysql = new MyPDOClass();
			$mysql->ConnectSQL("MYSQL","localhost","webuser","user","website");
			$mysql->PrepareQuery("select * from postalcode where code = :code");
			if( !$mysql->Execute(array(":code"=>$code)) )
			{
				echo json_encode(array("status"=>false,"errormsg"=>"ExecuteError"));
			}

			
			$pref = array();
			$city = array();
			$addr = array();
			$count = 0;
			$result;
			while($result = $mysql->fetch_assoc())
			{
				//var_dump($result);
				$pref[$count] = $result["pref"];
				$city[$count] = $result["city"];
				$addr[$count] = $result["addr"];	
				$count++;
			}
			if( $count == 0 )
			{
				echo json_encode(array("status"=>false,"errormsg"=>"NoMatch"));
			}
//echo "COunt";
			$num = 0;
			$nowpref = $pref[0]; 
			$nowcity = $city[0];
			for( $i = 1; $i < $count; $i++ )
			{
				//違い
				if( $nowcity != $city[$i] )
				{
					$nowcity="";	
				}
			}
			$nowaddr = $addr[0];
			for( $i = 1; $i < $count; $i++ )
			{
				//違い
				if( $nowaddr != $addr[$i] )
				{
					$nowaddr="";	
				}
			}
			//echo $nowpref."<br>";
			//echo $nowcity."<br>";
			//echo $nowaddr."<br>";

			echo json_encode(array("status"=>true,"pref"=>$nowpref,"city"=>$nowcity,"addr"=>$nowaddr),JSON_UNESCAPED_UNICODE);

		}
		catch( PDOException $e )
		{
			echo json_encode(array("status"=>false,"errormsg"=>$e->getMessage()));

		}

		

	}
?>	
