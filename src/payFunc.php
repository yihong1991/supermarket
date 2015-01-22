<?php
include_once 'common/MysqlDB.class.php';

//页面载入后获取用户收货地址的数据
function payOnload($user,$userId){
    $reg = array();
    $sqlObj = new MysqlDB('supermarket');
    if($sqlObj->connectDb()){
	//计算选中商品总价格
	$tmp = totalPrice($user,$sqlObj);
	$reg['price'] = $tmp['realPrice'];
	//获取收货地址数据
        $reg['msg'] = 'process';
	$sql="SELECT * FROM addresslist WHERE ".$userId." = '".$user."' ORDER BY addressId DESC" ;
        $result = $sqlObj->queryDb($sql);
        if($tUserRow = $result->fetch_array(MYSQLI_ASSOC)){
            $reg['address'] = $tUserRow['address'];
            $reg['phone'] = $tUserRow['phone'];
            $reg['name'] = $tUserRow['name'];
            $reg['msg'] = 'success';    
        }   
    }else{
        $reg['msg'] = 'failed';
    }
    $sqlObj->closeDb();
    return json_encode($reg);
}
//通过电话获取历史收货信息
function payGetByTel($tel){
    $reg = array();
    $reg['msg'] = $tel;
    $sqlObj = new MysqlDB('supermarket');
    if($sqlObj->connectDb()){
	$reg['msg'] = 'process';
	$sql="SELECT * FROM addresslist WHERE phone = '".$tel."' ORDER BY cTime DESC" ;
	$result = $sqlObj->queryDb($sql);
	$row_num = $result->num_rows;
	if(0 == $row_num){
	    $reg['msg'] = 'nothing found';
	    $sqlObj->closeDb();
	    return json_encode($reg);
	}
	if($tUserRow = $result->fetch_array(MYSQLI_ASSOC)){
            $reg['address'] = $tUserRow['address'];
            $reg['phone'] = $tUserRow['phone'];
            $reg['name'] = $tUserRow['name'];
            $reg['msg'] = 'success';    
        }   
    }else{
        $reg['msg'] = 'failed';
    }
    $sqlObj->closeDb();
    return json_encode($reg);
}
//计算购物车内商品总价
function totalPrice($user,$sqlObj = null){
    $reg = array();
    $flag = false;
    if(null == $sqlObj){
	$sqlObj = new MysqlDB('supermarket');
	$sqlObj->connectDb();
	$flag = true;
    }
    $sqlC="SELECT * FROM shopcarts WHERE tUserId = '".$user."' OR oUserId = '".$user."'";
    $resultC = $sqlObj->queryDb($sqlC);
    $totalOriPrice = 0;
    $totalBubPrice = 0;
    $totalRealPrice = 0;
    while ($cartsRow = $resultC->fetch_array(MYSQLI_ASSOC)) {
	if(0 == $cartsRow['isChecked'])
	    continue;
	$sqlG = "SELECT * FROM goodsdetail WHERE goodsId = '".$cartsRow['goodsId']."'";
	$resultG = $sqlObj->queryDb($sqlG);
	$goodsRow = $resultG->fetch_array(MYSQLI_ASSOC);
	$totalOriPrice += ($cartsRow['goodsNum'] * $goodsRow['price']);
    }
    if($flag){
	$sqlObj->closeDb();
    }
    $totalRealPrice = $totalOriPrice - $totalSubPrice;
    $reg['oriPrice'] = number_format($totalOriPrice,2);
    $reg['subPrice'] = number_format($totalSubPrice,2);
    $reg['realPrice'] = number_format($totalRealPrice,2);
    return $reg;
}
//点击下单后的动作
function confirmOrder($arr_rec){
    $reg['msg'] = 'order failed';
    $flag = true;
    $sqlObj = new MysqlDB('supermarket');
    $userId = $arr_rec['userIdType'];
    if($sqlObj->connectDb()){
	$reg['msg'] = 'process confirmOrder';
	$timestamp = time();
	$sql1 = "SELECT * FROM addresslist WHERE phone = '".$arr_rec['tel']."' AND address = '".$arr_rec['address']."' ORDER BY addressId ;";
	$result1 = $sqlObj->queryDb($sql1);
	if(0 == $result1->num_rows){
	    //添加收货地址表
	    $sql2 = "INSERT INTO addresslist (addressId, name, address, phone, ".$userId.") 
		    VALUES ('".$timestamp."', '".$arr_rec['realname']."', '".$arr_rec['address']."', '".$arr_rec['tel']."', '".$arr_rec['user']."');";
	}else{
	    $sql2 = "UPDATE addresslist set addressId = ".$timestamp.", name = '".$arr_rec['realname']."', ".$userId." = '".$arr_rec['user']."' 
		    WHERE phone = '".$arr_rec['tel']."' AND address = '".$arr_rec['address']."';";
	}
	if($sqlObj->queryDb($sql2)){
	    //添加用户订单表
	    $sql3 = "INSERT INTO userorder (".$userId.", orderId, orderTime, addressId, amount, sendTime)
		    VALUES ('".$arr_rec['user']."', '".$timestamp."', '".$timestamp."', '".$timestamp."', '".$arr_rec['price']."', '".$arr_rec['booktime']."')";
	    if($sqlObj->queryDb($sql3)){
		//添加订单详情表并删除购物车对应商品
		$tmp = orderAndCarts($arr_rec,$timestamp);
		if('success' != $tmp['msg']){
		    $reg['msg'] = $tmp['msg'];
		}else{
		    $reg['msg'] = 'success';
		}
	    }
	    else{
		$reg['msg'] = 'add userorder failed';
		$flag = false;
	    }	
	}else{
	    $reg['msg'] = 'update addresslist failed';
	}
    }else{
        $reg['msg'] = 'order failed';
    }
    $sqlObj->closeDb();
    return json_encode($reg);
}

//添加订单详情表并删除购物车对应商品
function orderAndCarts($arr_rec,$timestamp){
    $reg['msg'] = 'connection failed at orderdetail or shopcarts';
    $flag = true;
    $sqlObj = new MysqlDB('supermarket');
    $userId = $arr_rec['userIdType'];
    if($sqlObj->connectDb()){
	$sqlC="SELECT * FROM shopcarts WHERE ".$userId." = '".$arr_rec['user']."' 
		AND isChecked = 1 ;";
	$resultC = $sqlObj->queryDb($sqlC);
	while ($cartsRow = $resultC->fetch_array(MYSQLI_ASSOC)) {
	    $goodsId = $cartsRow['goodsId'];
	    $goodsNum = $cartsRow['goodsNum'];
	    $index = $cartsRow['index'];
	    $sql2 = "INSERT INTO orderdetail (orderId, goodsId, goodNum)
		VALUES ('".$timestamp."', '".$goodsId."','".$goodsNum."');";
	    if($sqlObj->queryDb($sql2)){
		$sql3 = "DELETE FROM `shopcarts` WHERE `index` = ".$index." ;";
		if($sqlObj->queryDb($sql3)){
		    ;
		}
		else{
		    $reg['msg'] = 'delete shopcarts failed'.$sql3;
		    $flag = false;
		}
	    }else{
		$reg['msg'] = 'add orderdetail failed';
		$flag = false;
	    }
	}
	
    }else{
        $reg['msg'] = 'connectDB failed at orderdetail or shopcarts';
        $flag = false;
    }
    $sqlObj->closeDb();
    if($flag){
	$reg['msg'] = 'success';
    }
    return $reg;
}


?>








