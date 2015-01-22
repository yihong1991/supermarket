<?php
include_once '../cartsFunc.php';

/*
var uploadData = {
    "func":"quantity-increase",
    "user":"testTmpId_1",
    "id":id,
    "num":curReserves
};*/
/////解析接收到的数据
$json_string = $_POST['uploadData'];
$func = $json_string['func'];
$user = $json_string['user'];
$id = $json_string['id'];
$num = $json_string['num'];

switch ($func) {
   case "quantity-increase":
	$msg = inQuantity($user,$id,$num);
	break;
   case "quantity-decrease":
	$msg = deQuantity($user,$id,$num);
	break;
   case "checkbox_click":
	$msg = changeCheck($user,$id);
	break;
   default:
	echo "请刷新页面";
}

echo $msg;


   



