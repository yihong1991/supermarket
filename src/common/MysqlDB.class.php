<?php
	class MysqlDB{
		public $conn;
		private $dbName;
		public function __construct($name){
			$this->dbName = $name;
		}
		public function connectDb(){
			$this->conn = new mysqli('localhost', 'page', 'user',$this->dbName);
			if(!$this->conn){
				 echo "Can't connect to database".mysql_error();
				 return false;
			}			
			$this->conn->query("set names utf8");
			return true;
		}
		public function closeDb(){
			$this->conn->close();
		}
		public function queryDb($str){
			#echo $str;
			$ret = $this->conn->query($str);
			return $ret;
		}
	}
?>
