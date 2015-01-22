<?php
    include_once 'payFunc.php';
    include_once 'common/MysqlDB.class.php';
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>订单结算页</title>
    <!-- css -->
    <link href="css/ratchet.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" />
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/function.js"></script>
    <style type="text/css">
<?php
    $mysqli = new MysqlDB('supermarket');
    if($mysqli->connectDb()){
        $user = "testTmpId_1";
        $sql="SELECT * FROM shopcarts WHERE (tUserId = '".$user."' OR oUserId = '".$user."') 
		AND isChecked = '1'" ;
        $result = $mysqli->queryDb($sql);
        //显示结果    
        $row_num = $result->num_rows;
        if(0 == $row_num){//没有商品选中时
        echo '
	#nothingSelected {
        display:block !important;
	}
        #somethingSelected {
        display:none !important;
	}';
        }
    }
?>
    </style>
    <script type="text/javascript">
        $(window).load(function() {
            var user = $("body").attr("data-role");
            $.get("ajax/payRpc.php?act=onload_user&user="+user+"&usertype=",payOnloadFunc);
        });
    </script>
</head>
<body id="body" data-role="<?php echo $user;?>">
<input type="hidden" id="total_price" value="0"/>
<!--导航栏设置-->
    <div class="container">
        <div id="cart-nav">
	    <div class="nav-back" style="height:50px;">
                <!--<a href="javascript:pageBack();">返回</a>-->
                <a class="cart-navbar" href="carts.php">返回</a>
		<div class="nav-title" style="display:inline;position:relative;left:35%;">
		    <a class="cart-navbar" href="javascript:history.go(0);">结账下单</a>
		</div>
	    </div>
	</div>
    </div>
    <!--无商品选中时显示-->
    <div id="nothingSelected">
        <div class="row">
	    <div class="empty-warning-text" style="text-align:center;">
		<h2><br />未选中任何商品<br/><a href="carts.php" class="go-shopping-href"><br/>返回购物车</a></h2></div>
            <div class="empty-btn-wrapper" style="text-align:center;">
		<h2><a href="index.html" class="go-shopping-href"><br/>去逛逛</a></h2>
	    </div>
	</div>
    </div>
    <!--有商品选中时显示-->
    <div id="somethingSelected">
	<div class="content-padded">
	    <form class="input-group" onsubmit="return false">
		<div class="input-row">
		    <label for="tel">手机号</label>
		    <input type="text" name="tel" placeholder="请正确输入手机号码" id="tel" value=""/>
		    <span id="moileMsg"></span>
		</div>
	    
	        <div class="input-row">
		    <label for="address">收货地址</label>
		    <input type="text" name="address" placeholder="" id="address" value=""/>
		</div>
		<div class="input-row">
		    <label for="realname">收货人</label>
		    <input type="text" name="realname" placeholder="" id="realname" value="" maxlength="4"/>
		</div>
	        <div class="input-row">
		    <label for="booktime">送货时间</label>
		    <select name="booktime" id="booktime">
		            <option value="立即送出">立即送出</option>
		            <option value="12:15">12:15</option><option value="12:30">12:30</option><option value="12:45">12:45</option><option value="13:00">13:00</option><option value="13:15">13:15</option><option value="13:30">13:30</option><option value="13:45">13:45</option><option value="14:00">14:00</option><option value="14:15">14:15</option><option value="14:30">14:30</option><option value="14:45">14:45</option><option value="15:00">15:00</option><option value="15:15">15:15</option><option value="15:30">15:30</option><option value="15:45">15:45</option><option value="16:00">16:00</option><option value="16:15">16:15</option><option value="16:30">16:30</option><option value="16:45">16:45</option><option value="17:00">17:00</option><option value="17:15">17:15</option><option value="17:30">17:30</option><option value="17:45">17:45</option><option value="18:00">18:00</option><option value="18:15">18:15</option><option value="18:30">18:30</option><option value="18:45">18:45</option><option value="19:00">19:00</option><option value="19:15">19:15</option><option value="19:30">19:30</option><option value="19:45">19:45</option><option value="20:00">20:00</option><option value="20:15">20:15</option><option value="20:30">20:30</option><option value="20:45">20:45</option><option value="21:00">21:00</option><option value="21:15">21:15</option><option value="21:30">21:30</option><option value="21:45">21:45</option><option value="22:00">22:00</option><option value="22:15">22:15</option><option value="22:30">22:30</option><option value="22:45">22:45</option>    </select>
		</div>
		<textarea rows="2" placeholder="订单备注" name="other" id="other" class="payinfo-area"></textarea>
		<p></p>
		<div class="segmented-control">
		    <input type="radio" name="pay" value="0" class="pay" checked/>
		    <a class="payway control-item active">支付宝付款</a>
		</div>
		    
		<span class="login-tip"></span> 
		<a class="btn btn-primary btn-block checkNow" href="javascript:;">下  单</a> 
		<span class="pay-tips">为保证订单及时送达 请确认您的联系信息正确</span>
	    </form>
	</div>       

    </div>
    
    <!-- END CONTENT -->
    <div class="content-padded">
    </div>
    <div id="myModalexample" class="modal">
	<header class="bar bar-nav">
	    <h1 class="title">下单成功啦</h1>
	</header>
	<div class="content">
	    <p class="content-padded text-middle-show ">
	    <img src="images/order-suc.png" alt="下单成功啦" class="fimg"/>
		购物订单提交啦！
	    <span class="havebooktime">尽快为您安排配送哒</span>！请保持手机 <span id="emobile"></span> 畅通。
	    </br>  
	    <span id="etext"> 注册后可以使用支付宝在线支付或充值支付功能，购物更方便呦</span>！
	    
	    </p>
	    <div class="content-padded text-middle-show">
		<div class="btn1">
	            <a class="btn btn-positive btn-block" href="reg.php" data-ignore="push">去注册</a>
	        </div>
		<div class="btn2">
		    <a class="btn btn-primary btn-block" href="user.php" data-ignore="push">查看订单</a>
		</div>
	    </div>
	</div>
    </div>
    
</body>
</html>
<?php 
    $mysqli->closeDb();
?>













