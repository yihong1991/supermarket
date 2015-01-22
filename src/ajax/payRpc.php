<?php
include_once '../payFunc.php';

/////解析接收到的数据
$arr_rec = $_POST['uploadData'];
if($arr_rec){
    if($arr_rec['usertype'] == 'oUser'){
        $arr_rec['userIdType'] = 'oUserId';
    }else{
	$arr_rec['userIdType'] = 'tUserId';
    }
    $arr['msg'] = "order failed";
    $msg = json_encode($arr);
    if("confirm_order" == $arr_rec['func']){
	$msg = confirmOrder($arr_rec);
    }
    echo $msg;
}
//Get方式
$actG = $_GET['act'];
$userG = $_GET['user'];
$userTypeG = $_GET['usertype'];
$telG = $_GET['tel'];
//根据用户获取信息并计算选中商品总价格
if(!$telG && $actG == "onload_user" && $userG){
    if($userTypeG == "oUser"){
	$userIdType = 'oUserId';	
    }
    else{
	$userIdType = 'tUserId';
    }
	$msg = payOnload($userG,$userIdType);
    echo $msg;
}
//根据电话获取信息
if($telG && $actG == "get_by_tel"){
    $msg = 'test';
    $msg = payGetByTel($telG);
    echo $msg;
}//*/

?>   









