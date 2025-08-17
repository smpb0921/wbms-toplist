<?php
class database{
	private $host;
	private $username;
	private $password;
	private $dbname;

	private static $instance;

	private $connection;
	private $resultset;
	private $numrows;
	private $insertID;

	public function __construct(){

	}

	static function getInstance(){
		if(!self::$instance){
			self::$instance=new self();
		}
		return self::$instance;
	}

	public function connect($h,$u,$p,$db){
		$this->host = $h;
		$this->username = $u;
		$this->password = $p;
		$this->dbname = $db;

		$this->connection = mysql_connect($this->host,$this->username,$this->password);
		$database = mysql_select_db($this->dbname);
		if(!$this->connection){
			return "Can't connect to database";
		}
		else if(!$database){
			return "Invalid database name";
		}
		else{
			return true;
		}

	}

	public function doQuery($query){

		$this->resultset=mysql_query($query);
		$this->insertID = mysql_insert_id();////////////
		@$this->numrows = mysql_num_rows($this->resultset);
		if(!$this->resultset){
			echo mysql_errno()." - ".mysql_error();
		}
		else{
			return $this->resultset;
		}

	}

	public function loadObjectlist(){
		$obj = "No Results";
		if($this->resultset){
			$obj=mysql_fetch_object($this->resultset);
		}
		return $obj;
	}

	public function getInsertId(){
		return $this->insertID;
	}
}

?>