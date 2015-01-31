<html><head>
<meta charset="utf-8">
<title>逛超市</title>
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link href="http://www.51qiguai.com/mobile/css/ratchet.min.css" rel="stylesheet">
<link href="css/css.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script src="js/jquery.js"></script>
</head>
<body>
<header class="bar bar-nav">
      <button class="btn btn-link btn-nav pull-left" onclick="location.href='carts.php'"> <span class="icon icon-left-nav"></span> </button>
    <h1 class="title">
    结账下单  </h1>
</header>
<nav class="bar bar-tab"> 
	<!--a class="tab-item" href="#" data-ignore="push" style="color: rgb(48, 113, 169);"> <span class="icon icon-list"></span> <span class="tab-label">买东西</span> </a><-->
	<a class="tab-item" href="./" data-ignore="push"> <span class="icon icon-pages"></span> <span class="tab-label">主页</span> </a>
    <a class="tab-item" href="./carts.php" data-ignore="push"> <span class="icon icon-star"></span> <span class="tab-label">购物车</span> </a>
    <!--a class="tab-item" href="#" data-ignore="push"> <span class="icon icon-person"></span> <span class="tab-label">个人中心</span> </a><-->
   </nav>
<input type="hidden" value="-1" id="payfull">
<input type="hidden" value="" id="uid">
<input type="hidden" value="1.0" id="total_price">
<div class="content">
<div class="content-padded">
<form class="input-group" onsubmit="return false">
  <div class="input-row">
    <label for="tel">手机号</label>
    <input type="text" name="tel" placeholder="正确输入手机号码" id="tel" value="">
  </div>

   <div class="input-row">
    <label for="address">收货地址</label>
    <input type="text" name="address" placeholder="" id="address" value="">
  </div>
  <div class="input-row">
    <label for="realname">收货人</label>
    <input type="text" name="realname" placeholder="" id="realname" value="" maxlength="4">
  </div>
  <div class="input-row">
    <label for="booktime">送货时间</label>
    <select name="booktime" id="booktime">
            <option value="立即送出">立即送出</option>
            <option value="16:00">16:00</option><option value="16:15">16:15</option><option value="16:30">16:30</option><option value="16:45">16:45</option><option value="17:00">17:00</option><option value="17:15">17:15</option><option value="17:30">17:30</option><option value="17:45">17:45</option><option value="18:00">18:00</option><option value="18:15">18:15</option><option value="18:30">18:30</option><option value="18:45">18:45</option><option value="19:00">19:00</option><option value="19:15">19:15</option><option value="19:30">19:30</option><option value="19:45">19:45</option><option value="20:00">20:00</option><option value="20:15">20:15</option><option value="20:30">20:30</option><option value="20:45">20:45</option><option value="21:00">21:00</option><option value="21:15">21:15</option><option value="21:30">21:30</option><option value="21:45">21:45</option><option value="22:00">22:00</option><option value="22:15">22:15</option><option value="22:30">22:30</option><option value="22:45">22:45</option>  	</select>
  </div>
  <textarea rows="2" placeholder="订单备注" name="other" id="other" class="payinfo-area"></textarea>
  <p></p>
  <!--
  <p class="beizhu">
	  <a href="javascript:;" class="btn">50元找零</a>
	  <a href="javascript:;" class="btn">100元找零</a>
	  <a href="javascript:;" class="btn">速度</a>
  </p>
  <div class="segmented-control">
  	<input type="radio" name="pay" value="0" class="pay" checked="">
    <a class="payway control-item active">货到付款</a>
   	<input type="radio" name="pay" value="1" class="pay">
    <a class="payway control-item">余额扣除</a>    
   	<input type="radio" name="pay" value="2" class="pay">
    <a class="control-item">支付宝支付</a>
  	<!-- 
   	<input type="radio" name="pay" value="3" class="pay"/>
    <a class="control-item">微信支付</a>
     -->
  <!--</div>-->
  <p>&nbsp;</p>
    
  <span class="login-tip"></span> 
  <a class="btn btn-primary btn-block checkNow" href="javascript:;">下单</a> 
  <span class="pay-tips">为保证订单及时送达 请确认您的联系信息正确</span>
  
  
</form>
</div>
<!-- END CONTENT -->
<div class="content-padded">
</div>
</div>
<div id="myModalexample" class="modal">
  <header class="bar bar-nav">
    <h1 class="title">下单成功啦</h1>
  </header>
  <div class="content">
    <p class="content-padded text-middle-show ">
    <img src="images/order-suc.png" alt="下单成功啦" class="fimg">
    购物订单提交啦！阿怪喵会<span class="havebooktime">尽快为您安排配送哒</span>！请保持手机 <span id="emobile"></span> 畅通。<br>  
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
<div class="loadings"><img src="images/loadings.gif">加载中...</div>
<div class="dangers"><!-- 危险提示 --></div>
<div class="cover"><!-- 遮罩层 --></div>
<div class="bottom_tip">
<div class="content-padded" id="searchbox">
	<form action="products.php" method="get">
   	 <input type="search" name="s" placeholder="找一找 o(╯□╰)o" class="pro-search">
    </form>
 </div>
</div>

<!-- Include the compiled Ratchet JS -->
<script src="js/function.js?id=3"></script>
<script src="js/ratchet.min.js"></script>

<div style="display:none">
<script src="http://s23.cnzz.com/stat.php?id=5828674&amp;web_id=5828674" language="JavaScript"></script>
</div>


</body></html>