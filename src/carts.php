<?php
    include_once 'cartsFunc.php';
    include_once 'common/MysqlDB.class.php';
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>我的购物车</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style2.css" rel="stylesheet" />
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/function2.js"></script>
    <style type="text/css">
        .cart-checkbox{
            background-image: url(img/cart/shp-cart-icon-sprites.png);
        }
        .quantity-decrease, .quantity-increase{
             background-image: url(img/cart/shp-cart-icon-sprites.png);
        }
<?php
    $mysqli = new MysqlDB('supermarket');
    $mysqli->connectDb();
    $user = "testTmpId_1";
    $sql="SELECT * FROM shopcarts WHERE tUserId = '".$user."'";
    $result = $mysqli->queryDb($sql);
    //显示结果    
    $row_num = $result->num_rows;
    if(0 == $row_num){
    echo '
    #emptyCart {
        display:block !important;
    }
        #notEmptyCart {
        display:none !important;
    }';
    }
?>
    </style>
</head>
<body id="body" data-role="<?php echo $user;?>">
<!--导航栏设置-->
    <div class="container">
        <div class="row" id="cart-nav">
            <div class="col-xs-3 col-md-3">
                <!--<a href="javascript:pageBack();">返回</a>-->
                <a class="cart-navbar" href="javascript:history.go(-1);">返回</a>
            </div>
            <div class="col-xs-9 col-md-9" style="text-align: center;">
                <a class="cart-navbar" href="javascript:history.go(0);">购物车</a>
            </div>
        </div>
        <!--购物车为空时显示-->
    <div id="emptyCart">
            <div class="row">
                    <div class="empty-warning-text" style="text-align:center;">
                        <h2><br />购物车空空如也<br/>快去购物吧</h2></div>
                    <div class="empty-btn-wrapper" style="text-align:center;">
                        <h2><a href="index.html" class="go-shopping-href"><br/>去逛逛</a></h2>
                    </div>
            </div>
        </div>
        <!--购物车非空时显示-->
        <div id="notEmptyCart">
        <!--商品列表-->
        <ul class="shp-cart-list">
<?php
    while ($shopCartLine = $result->fetch_array(MYSQLI_ASSOC)) {
        showProductList($mysqli,$shopCartLine);
    }
    $priceArr = totalPrice($user,$mysqli);
?>
        </ul>
            <!--结算-->
            <hr />
            <div class="row" id="payment-total-bar">
                    <div class="bottom-price">
                        <span class="sale-off">商品总额:￥<span class="bottom-bar-price" id="cart_oriPrice"><?php echo $priceArr['oriPrice'];?></span>
                            优惠:￥<span class="bottom-bar-price" id="cart_subPrice"><?php echo $priceArr['subPrice'];?></span></span><br/>
                        <strong class="shp-cart-total">总计:￥<span class="" id="cart_realPrice"><?php echo $priceArr['realPrice'];?></span></strong>
                    </div>
                    <div class="bottom-pay">
                        <button type="submit" id="btn-submit" onClick="javascript:window.location.href='pay.php'"> 结算 </button>
                    </div>
            </div>
    </div>

<?php
    //释放结果//*/
    mysql_free_result($result);
    //关闭连接
    $mysqli->closeDb();
?>
    </div>
</body>
</html>









