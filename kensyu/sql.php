<?php

	$mysqlID = mysql_connect("localhost","yusuke","MGS");

	mysql_set_charset('utf8');
	if( $mysqlID == false )
	{
		echo "MySQLへの接続が失敗しました\n";
	}
	echo "接続に成功\n";


	if( !mysql_select_db("training") )
	{
		echo "trainigがありません\n";
	}
	echo "trainingを選択\n";

	while(1)
	{
		echo "mysql>";
		$stdin = trim(fgets(STDIN));  
		if( $stdin == "quit" )
		{
			break;
		}

		$query = mysql_query($stdin);
		if(!$query)
		{
			echo mysql_error();
			continue;
		}

		while( $result = mysql_fetch_assoc($query) )
		{
			echo "|  ";
			foreach( $result as $num )
			{
				printf("%10s\t |",$num);
			}
			echo "\n";
		}	
	}

	if( $mysqlID != false )
	{
		mysql_close( $mysqlID );
	}

?>
