<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	class DoCart{
		public function __construct(){
			include_once 'createCount.php';
			include_once 'db.php';
			$this->method = $_GET['act'];
			$this->data = $_POST['str'];	//post数据
			$this->arr = explode('@',$this->data,4);
			$this->id = $this->arr[0];	//goodsid
			$this->price = $this->arr[2]; //price
			$this->reserve = $this->arr[3]; //库存
			$c = new Counter();
			$this->userId = $c->getUser();//tmp User Id
			$this->db = new MysqlDB(constant('DBNAME'));
		}
		private $method;
		private $data;	//post数据
		private $arr;
		private $id;	//goodsid
		private $price; //price
		private $reserve; //库存
		private $userId;
		private $db;
		private function addCart(){
			if($this->db->connectDb(constant('DBNAME'))){
				$sql = "select count(1) from shopcarts where tUserId ='".$this->userId."' and goodsId = ".$this->id;
				$ret =$this->db->queryDb($sql);
				if($ret){
					$row = mysql_fetch_array($ret);
					$num = $row[0];
					if($num == 0){
						$sql = "insert into shopcarts (tUserId,goodsId,goodsNum) values('".$this->userId."',".$this->id.",1)";
					}else if($num == 1){
						$sql = "update shopcarts set goodsNum=goodsNum+1 where tUserId ='".$this->userId."' and goodsId = ".$this->id;
					}
					$this->db->queryDb($sql);
				}
				$this->db->closeDb();
			}
		}
		
		private function subCart(){
			if($this->db->connectDb(constant('DBNAME'))){
				$sql = "select goodsNum from shopcarts where tUserId ='".$this->userId."' and goodsId = ".$this->id;				
				$ret =$this->db->queryDb($sql);
				if($ret){
					$row = mysql_fetch_array($ret);
					$num = $row[0];
					if($num > 1){
						$num--;
						$sql = "update shopcarts set goodsNum = goodsNum-1  where tUserId ='".$this->userId."' and goodsId = ".$this->id;
					}else if($num == 1){
						$sql = "delete from shopcarts where tUserId ='".$this->userId."' and goodsId = ".$this->id;
					}
					echo $sql;
					$this->db->queryDb($sql);
				}
				$this->db->closeDb();
			}
		}
		
		public function run(){
			if($this->method == 'add'){
				$this->addCart();
			}else if($this->method = 'sub'){
				$this->subCart();
			}
		}
	}
	$cart = new DoCart();
	$cart->run();
?>