<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	include_once 'db.php';
	include_once 'createCount.php';
	class CartNum{
		private $goodsNum;
		private $priceTotal;
		private $userId;
		private $db;
		public function __construct(){
			$c = new Counter();
			$this->userId = $c->getUser();
			$this->db = new MysqlDB("supermarket");
			$this->priceTotal = 0;
		}

		private function getNum(){
			$sql = "select count(1) from shopcarts where tUserId = '".$this->userId."'";
			$ret = $this->db->queryDb($sql);
			if($ret){
				$row = mysql_fetch_array($ret);
				return $row[0];
			}
			return 0;
		}
		private function getPriceTotal(){
			$sql = "select a.goodsNum,b.price from shopcarts a inner join goodsdetail b on a.goodsId = b.goodsId and a.tUserId= '".$this->userId."'";
			$ret = $this->db->queryDb($sql);
			if($ret){
				while($row = mysql_fetch_row($ret)){
					$this->priceTotal += $row[0]*$row[1];
				}
				return $this->priceTotal;
			}
		}
		
		public function getTotal(){
			if($this->db->connectDb()){
				$this->goodsNum = $this->getNum();
				$this->priceTotal = $this->getPriceTotal();
				$arr=array(
				"amount"=>$this->goodsNum,
				"price"=>$this->priceTotal
				);
				echo json_encode($arr);
			}
		}	
	}
	$c = new CartNum();
	$c->getTotal();
	
?>