<?php
include_once 'common/MysqlDB.class.php';
//连接数据库并返回数据库访问句柄
function connnectDB(){
    define ('HOSTNAME', 'localhost'); //数据库主机名
    define ('USERNAME', 'page'); //数据库用户名
    define ('PASSWORD', 'user'); //数据库用户登录密码
    define ('DATABASE_NAME', 'supermarket'); //需要查询的数据库
    $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD);
    $mysqli->select_db(DATABASE_NAME);
    $mysqli->query("set names 'utf8'");
    if ($mysqli->errno != 0){ 
        echo "Could not connect sql".$mysqli->error;
        die('Could not connect: '.$mysqli->error);
    }   
    return $mysqli;
}
//数据列表展示
function showProductList($mysqli,$shopCartLine){
    //设置选中商品
    $setCheckedSql = "UPDATE shopcarts SET isChecked = 1 WHERE goodsId = '".$shopCartLine['goodsId']."'";
    $mysqli->queryDb($setCheckedSql);
    //输出商品信息
    $goodsSql = "SELECT * FROM goodsdetail WHERE goodsId = '".$shopCartLine['goodsId']."'";
    $goodsResult = $mysqli->queryDb($goodsSql);
    $goodsDetail = $goodsResult->fetch_array(MYSQLI_ASSOC);
    if($goodsDetail){
        echo '<li id="'.$shopCartLine["goodsId"].'" data-role="'.$shopCartLine["goodsId"].'">
            <div class="goods-items">
            <div class="check-wrapper">
	    <span class="cart-checkbox checked" onclick="changeSelected('.$shopCartLine["goodsId"].')"></span>
            </div>
                        <div class="cart-goods-info">
                            <div class="cart-goods-info-img">
                                <a href="goodsDetail.htm" class="thumbnail">
                                    <img alt="图片加载失败" src="'.$goodsDetail["photo"].'" />
                                </a>
                            </div>
                            <div class="cart-goods-info-describe">
                                <div class="cart-goods-info-describe-name">
                    <a href="goodsDetail.htm"><span>'.$goodsDetail["goodsDesc"].'</span> </a>
                                </div>
                                <div class="cart-goods-info-describe-num">
                                    <div class="quantity-wrapper">
                        <input type="hidden" id="'.$shopCartLine["goodsId"].'-max-num" value="'.$goodsDetail["stock"].'" />
                                        <a class="quantity-decrease" href="javascript:subWareBybutton('.$shopCartLine["goodsId"].',otherParams)">
                                            -</a>
                                        <input type="text" class="quantity" readonly="true" size="4" onchange="modifyWare('.$shopCartLine["goodsId"].',otherParams)"
                                            value="'.$shopCartLine["goodsNum"].'" name="num" id="'.$shopCartLine["goodsId"].'" />
                                        <a class="quantity-increase" href="javascript:addWareBybutton('.$shopCartLine["goodsId"].',otherParams)">
                                            +</a>
                                    </div>
                                    <a class="shp-cart-icon-remove" href="javascript:deleteWare('.$shopCartLine["goodsId"].',otherParams)">
                                    </a>
                                </div>
                            </div>
                            <div class="cart-goods-info-price">
                                <span id="'.$shopCartLine["goodsId"].'-price">￥'.$goodsDetail["price"].'</span>
                            </div>
                        </div>
                    </div>
                </li>
        ';
    }
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
    $sqlC="SELECT * FROM shopcarts WHERE tUserId = '".$user."'";
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

//设置是否选中商品
function changeSelected($user,$id){
    $sqlObj = new MysqlDB("supermarket");
    $msg = "数据库连接失败";
    $num = 1;  //被选中
    $sql1 = "SELECT * FROM shopcarts WHERE (tUserId = '".$user."' OR oUserId = '".$user."')
                AND (goodsId = '".$id."');";
    if($sqlObj->connectDb()){
	$checked = $sqlObj->queryDb($sql1);
	$cartsDetail = $checked->fetch_array(MYSQLI_ASSOC);
	if($cartsDetail){
	    $num = ($cartsDetail['isChecked']+1)%2;
	    $sql2 = "UPDATE `shopcarts` SET isChecked = ".$num." WHERE (tUserId = '".$user."' OR oUserId = '".$user."')
		     AND (goodsId = '".$id."');";
	    $sqlObj->queryDb($sql2);
	    $msg = "操作成功";
	}
	else {
	    $msg = "购物车商品已被删除，请刷新页面";
	}
	$sqlObj->closeDb();
    }
    return $msg;
}

//修改商品数量
function correctGoodsNum($user,$id,$num){
    $sqlObj = new MysqlDB("supermarket");
    $msg = "数据库连接失败";
    $sql1 = "SELECT * FROM goodsdetail WHERE goodsId = '".$id."';";
    $sql2 = "UPDATE `shopcarts` SET goodsNum = ".$num." WHERE (tUserId = '".$user."' OR oUserId = '".$user."')
                AND (goodsId = '".$id."');";
    if($sqlObj->connectDb()){
	$checked = $sqlObj->queryDb($sql1);
	$goodsDetail = $checked->fetch_array(MYSQLI_ASSOC);
	if($goodsDetail){
	    if($num <= 0){
		$sql2 = "Delete from `shopcarts` WHERE (tUserId = '".$user."' OR oUserId = '".$user."')
                    AND (goodsId = '".$id."');";
		$sqlObj->queryDb($sql2);
		$msg = "删除商品成功";
	    }
	    elseif($num <= $goodsDetail['stock']){
		$sqlObj->queryDb($sql2);
		$msg = "操作成功";
	    }
	    elseif($num > $goodsDetail['stock']) {
		$msg = "库存不足";
	    }
	}
	else {
	    $msg = "商品不存在";
	}
	$sqlObj->closeDb();
    }
    return $msg;
}

/////点击“+”按钮
function inQuantity($user,$id,$num){
    $arr = array();
    $arr['opMsg'] = correctGoodsNum($user,$id,$num);
    $arr['price'] = totalPrice($user);
    return json_encode($arr);
}

/////点击“-”按钮
function deQuantity($user,$id,$num){
    $arr = array();
    $arr['opMsg'] = correctGoodsNum($user,$id,$num);
    $arr['price'] = totalPrice($user);
    return json_encode($arr);
}


/////点击“check”按钮
function changeCheck($user,$id){
    $arr = array();
   // $arr[opMsg] = "adb";
    $arr['opMsg'] = changeSelected($user,$id);
    $arr['price'] = totalPrice($user);
    return json_encode($arr);
}

?>




