<?php 
    Class Counter{
      public function __construct(){
          
      }
	  
      private function createUser(){
          include 'db.php';
          $uid = uniqid();
		  $time = date("Y-m-d h:i:sa");
		  //echo $time;
          $sql = "INSERT INTO `tmpconsumer` (tUserId,address,phone,name,cTime,reserve1) VALUES ('".$uid."', null, null, null, '".$time."', null);";
          $db = new MysqlDB("supermarket");
          if($db->connectDb()){
              $db->queryDb($sql);
              $db->closeDb();
              return $uid;
          }
          return null;
      }
      
      private function createTmpUserId(){
          $uid =  $this->createUser();
          //ÉèÖÃcookies
          setcookie("user",$uid);
		  return $uid;
      }
      
      //user
      private function getCookies(){
          if(isset($_COOKIE['user']))
              return $_COOKIE['user'];
          return null;
      }
      
      public function getUser(){
          $user = $this->getCookies();
          if($user == null)
              $user = $this->createTmpUserId();
          return $user;
      }
        
    };

?>