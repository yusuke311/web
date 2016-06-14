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
define("MYSQL",0);

class MyPDOClass
{
	private $pdo;
	private $stmt=NULL;

	function __construct()
	{
		$this->pdo = NULL;
	//	echo "PDOConstract<br>";
	}

	public function ConnectSQL($dbtype,$host,$user,$pass,$dbname)
	{
		switch($dbtype)
		{
		case "MYSQL":
		//	echo "ConnectMYSQL<br>";
			$datasource = "mysql:dbname=${dbname};host=${host};";
		//	echo $datasource;
			$options = array(
			    PDO::MYSQL_ATTR_READ_DEFAULT_FILE  => '/etc/my.cnf',
			); 
			$this->pdo = new PDO($datasource,$user,$pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::MYSQL_ATTR_READ_DEFAULT_FILE  => '/etc/my.cnf'));
//			$this->pdo->query('SET NAMES utf8');
		break;	
		}
	}

	public function PrepareQuery($SQL)
	{
		$this->stmt = $this->pdo->prepare($SQL);
	}	
	public function QuickQuery($SQL)
	{
		$this->stmt = NULL;
		return $this->pdo->query($SQL);
	}

	public function Execute($PrepareParam)
	{
		
		if( $this->stmt == NULL )
		{
			echo "stmt=NULL<br>";
			return false;
		}

		return $this->stmt->execute($PrepareParam);
	}

	public function fetch_num()
	{
		if( $this->stmt == NULL )
		{
			return false;
		}

		return $this->stmt->fetch(PDO::FETCH_NUM);
	}
	public function fetch_assoc()
	{
		if( $this->stmt == NULL )
		{
			return false;
		}

		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}


}
?>
