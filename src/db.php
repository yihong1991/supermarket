<?php
	class MysqlDB{
		public $conn;
		private $dbName;
		public function __construct($name){
			$this->dbName = $name;
		}
		public function connectDb(){
			$this->conn = mysql_connect("localhost", "root", "7758521");
			if(!$this->conn){
				 echo "Can't connect to database".mysql_error();
				 return false;
			}			
			mysql_select_db($this->dbName, $this->conn);
			mysql_query("set names utf8");
			return true;
		}
		public function closeDb(){
			mysql_close($this->conn);
		}
		public function queryDb($str){
			$ret = mysql_query($str);
			return $ret;
		}
	}
?>