<?php

class MySQLClass
{
	private	$mysqlID;
	private $query;

	public  function ConnectSQL($URL,$user,$pass)
	{
		$this->mysqlID = mysql_connect($URL,$user,$pass);

		mysql_set_charset('utf8');
		if( $this->mysqlID == false )
		{
			return false;
		}
		return true;
	}

	public function UseDataBase($name)
	{
		if( !mysql_select_db($name) )
		{
			return false;
		}
		return true;
	}

	public function Query($SQL)
	{
		$this->query = mysql_query($SQL);
		if(!$this->query)
		{
			echo mysql_error();
		}
		return $this->query;
	}

	public function GetDatatoMap()
	{
		return mysql_fetch_assoc($this->query);
	}

	public function Close()
	{
		if( $mysqlID != false )
		{
			mysql_close( $mysqlID );
		}
	}


}
?>
