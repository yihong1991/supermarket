<!DOCTYPE html>
<!-- saved from url=(0043)http://www.51qiguai.com/mobile/products.php -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>逛超市</title>
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link href="http://www.51qiguai.com/mobile/css/ratchet.min.css" rel="stylesheet">
<link href="http://www.51qiguai.com/mobile/css/css.css" rel="stylesheet">
<link href="http://www.51qiguai.com/mobile/css/style.css" rel="stylesheet">
<script src="http://www.51qiguai.com/mobile/js/jquery.js"></script><style type="text/css"></style>
</head>
<body>
<header class="bar bar-nav">
      <button class="btn btn-link btn-nav pull-left" onclick="location.href=&#39;area.php?act=change&#39;"> <span class="icon icon-left-nav"></span> </button>
  <span class="media-object pull-right icon icon-search"  style="display:none"></span>    <h1 class="title">
    广州大学城  </h1>
</header>
<nav class="bar bar-tab"> 
	<a class="tab-item" href="#" data-ignore="push" style="color: rgb(48, 113, 169);"> <span class="icon icon-list"></span> <span class="tab-label">买东西</span> </a>
    <a class="tab-item" href="#" data-ignore="push"> <span class="icon icon-star"></span> <span class="tab-label">购物车</span> </a>
    <a class="tab-item" href="#" data-ignore="push"> <span class="icon icon-pages"></span> <span class="tab-label">公告栏</span> </a>
    <a class="tab-item" href="#" data-ignore="push"> <span class="icon icon-person"></span> <span class="tab-label">个人中心</span> </a>
   </nav>
<script type="application/javascript" src="./js/jquery.infinitescroll.js"></script>
<script type="text/javascript">
$(function(){
  	  $('#waterfall').infinitescroll({
        navSelector: "#navigation", 
        nextSelector: "#navigation a", 
        itemSelector: ".wfc", 
        debug: true, 
        animate: false, 
        extraScrollPx: 25,	 	//滚动条距离底部多少像素的时候开始加载，默认150
        bufferPx: 40, 			//载入信息的显示时间，时间越大，载入信息显示时间越短
        errorCallback: function() {
           	$(".loading").text('没有了');
        }, 
        localMode: true, 		//是否允许载入具有相同函数的页面，默认为false
        dataType: 'html',		//可以是json
        loading: {
            msgText: "更多商品加载中",
            finishedMsg: '没有新数据了...',
            img: './img/loading.gif',
            selector: ''
        }
    }, function(newElems) {
        var $newElems = $(newElems);
    });

});
</script>  
<div class="content">
  <!-- scroll Begin -->
  <div class="list_scroller">
    <ul class="table-view list_main" id="waterfall">
<?php
	error_reporting(0);
	include 'db.php';
	class goodsDetail{
		public $goodsId;
		public $goodsName;
		public $goodsDesc;
		public $price;
		public $photo;
		public $stock;
	};
	class products{
		private $pageNum; //当前页数
		private $db;
		private $goodsArray = array();
		public function __construct(){
			$this->db = new MysqlDB("supermarket");
		}
		private function init(){
			$pageNum = $_GET['page'];
			$ret = $this->db->connectDb();
			if($ret)
				return true;
			return false;
		}
		private function echoList($goodsInfo){
			if(!$goodsInfo) return;
			echo '<li class="table-view-cell product-list wfc"> 
			<span class="product-show">
			<img class="media-object pull-left showBig" data-role="'.$goodsInfo->goodsName.'" src="'.$goodsInfo->photo.'">
			<div class="media-body">
			<div class="name">
          	    <u class="label hui">惠</u>';
          	    echo '<span><a href="#" data-ignore="push">  '.$goodsInfo->goodsDesc.'</a></span>
          </div>
          <div class="price_wrap clearfix">
            <div class="price"><span>￥<b>'.$goodsInfo->price.'</b></span></div>
          </div>
          <!-- 按钮 -->
          <div class="carts">
                                            <input type="button" value="-" class="plus btn white" data-role="'.$goodsInfo->goodsId.'@'.$goodsInfo->goodsName.'@'.$goodsInfo->price.'@'.$goodsInfo->stock.'@97@1@64">
            <span id="thisnum"></span>
            <input type="button" class="btn white add" value="+" data-role="'.$goodsInfo->goodsId.'@'.$goodsInfo->goodsName.'@'.$goodsInfo->price.'@'.$goodsInfo->stock.'@97@1@64">
                                           </div>
        </div>
        <!-- end 按钮 -->
        </span> </li>';
			
		}
		public function getGoodsDetail($num){
			$sql = "select * from goodsdetail limit ".$num;
			$ret = $this->db->queryDb($sql);
			$i = 0;
			while($row = mysql_fetch_array($ret)){				
				$this->goodsArray[$i]->goodsId = $row[0];
				$this->goodsArray[$i]->goodsName = $row[1];
				$this->goodsArray[$i]->goodsDesc = $row[2];
				$this->goodsArray[$i]->price = $row[3];
				$this->goodsArray[$i]->photo = $row[4];
				$this->goodsArray[$i]->stock = $row[6];
				$this->echoList($this->goodsArray[$i]);
			}
		}
		public function main(){
			$ret = $this->init();
			if($ret){
				$this->getGoodsDetail(10);
			}
		}
	}
	$p = new products();
	$p->main();
	
        
?>
</ul>
        <div class="loading text-middle-show" style="border:none;"><img src="./img/loading.gif"> 更多商品加载中哟...</div>   
	<div id="navigation" style="display:none;"><a href="http://localhost/html/main.php?page=1"></a></div>
  	  </div>
  <!-- END LIST -->
  <!--END SCROLLER  -->
</div>
<!-- TIPS -->
<div class="bottom_tip">
  <div class="tip_wrap">
    <!-- 有产品加入购物车时显示 -->
    <p class="J_order_count"> <span style="vertical-align:middle;"><span id="tnum"></span>个商品，共计￥<span id="tprice"></span> &nbsp;&nbsp;</span> <a class="btn btn-negative btn-outlined" href="#" data-ignore="push" style="vertical-align:middle;">选好了</a> </p>
  </div>
</div>
<!-- END TIPS -->
<!-- END MEMU SCROLLER -->
<div class="memu_scroller">
  <div class="memu_block">
    <ul id="memu_list">
     <li>
     	<div class=" current"><a href="http://localhost/html/main.php?page=1" class="memuname" data-ignore="push">全部商品</a></div>
     </li>
      </li>
            <li class="memu_space"></li>
    </ul>
  </div>
  <!-- END MEMU -->
</div>
<!-- END CONTENT -->
<div class="loadings"><img src="./img/loadings.gif">加载中...</div>
<div class="dangers"><!-- 危险提示 --></div>
<div class="cover"><!-- 遮罩层 --></div>
<div class="bottom_tip">
<div class="content-padded" id="searchbox">
	<form action="./img/main.htm" method="get">
   	 <input type="search" name="s" placeholder="找一找 o(╯□╰)o" class="pro-search">
    </form>
    </form>
 </div>
</div>
 <div class="middlebox">
 	<div class="toptitle">
  		<span class="pull-right shutoff icon icon-close"></span>
 		<span class="pull-left htitle">逛超市</span>
 	</div>
 	<div class="clearfix"></div>
 	<hr class="hr">
 	<div class="contents">
	逛超市
 	</div>
 </div>
<!-- Include the compiled Ratchet JS -->
<script src="./js/function.js"></script>
<script src="./js/ratchet.min.js"></script>


</body></html>