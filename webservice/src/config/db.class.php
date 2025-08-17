<?php
	

	class db{
		private $dbhost = 'localhost';
		private $dbuser = 'root';
		private $dbpassword = '';
		private $dbname = 'wbms-cbl';


		public function connect(){
			$connectionstr = "mysql:host=$this->dbhost;dbname=$this->dbname";

			try{
				$dbConnection = new PDO($connectionstr,$this->dbuser,$this->dbpassword);
				$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $dbConnection;
			}
			catch(PDOException $e){
				echo $e->getMessage();
				exit();
			}
		}
	}

?>