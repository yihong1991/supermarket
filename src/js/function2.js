/*function changeSelected(goodsId) {
    alert("changeSelected run!!");
}//*/
//结算页面载入完成后执行动作或手机号输入完成时执行
function payOnloadFunc(data){
    //alert(data);
    var jsonObj = JSON.parse(data);
    if(jsonObj.msg == "success"){
	$("#tel").val(jsonObj.phone);
	$("#address").val(jsonObj.address);
        $("#realname").val(jsonObj.name);
    }
    if(jsonObj.price > 0){
	$("#total_price").val(jsonObj.price);
	//alert(jsonObj.price);
    }
}
$(function () {
    $("span.cart-checkbox").click(function(){
    var t = $(this);
        var user = $("body").attr("data-role");
        var liIterm = t.parents("li");
        var id = liIterm.attr("data-role");
        var uploadData = {
                "func":"checkbox_click",
                "user":user,
                "id":id,
                "num":""
            };
    $.ajax({
            url:"ajax/cartsRpc.php?act=checkbox_click",
            type:"post",
            data: {uploadData:uploadData},
            success: function (data) {
                var jsonObj = JSON.parse(data);
                var msg = jsonObj.opMsg;
                var oriPrice = jsonObj.price.oriPrice;
                var subPrice = jsonObj.price.subPrice;
                var realPrice = jsonObj.price.realPrice;
                $("#cart_oriPrice").text(oriPrice);
                $("#cart_subPrice").text(subPrice);
                $("#cart_realPrice").text(realPrice);
                t.toggleClass('checked');  //设置是否选中//*/
    //	alert(msg);
        },
            error: function () {
                alert("修改数量失败"+msg);
            }
        }); 
    });
    $(".quantity-wrapper a").mousedown(function(){
        $(this).css("backgroundColor","#ddd");
    });
    $(".quantity-wrapper a").mouseup(function(){
        $(this).css("backgroundColor","white");
    });
    $("input.quantity").blur(function(){    //为何离开焦点后文本框会死掉
        var t=$(this);
        //t.attr('readonly',true);
        var b=Number(t.val());
        t.attr("value",b+0);
        //alert(t.attr("value"));
    });
    $("a.quantity-decrease").click(function(){
        var t = $(this);
        var user = $("body").attr("data-role");
        var liIterm = t.parents("li");
        var id = liIterm.attr("data-role");
        var numTxt = t.next(".quantity");
        var curReserves = Number(numTxt.attr("value"))-1; //购物车内此商品数量
        var uploadData = {
            "func":"quantity-decrease",
            "user":user,
            "id":id,
            "num":curReserves
        };
        if(0 < curReserves){
            $.ajax({
                url:"ajax/cartsRpc.php?act=de_quantity",
                type:"post",
                data: {uploadData:uploadData},
                success: function (data) {
                    numTxt.attr("value",curReserves);
                    var jsonObj = JSON.parse(data);
                    var msg = jsonObj.opMsg;
                    var oriPrice = jsonObj.price.oriPrice;
                    var subPrice = jsonObj.price.subPrice;
                    var realPrice = jsonObj.price.realPrice;
                    $("#cart_oriPrice").text(oriPrice);
                    $("#cart_subPrice").text(subPrice);
                    $("#cart_realPrice").text(realPrice);
                },
                error: function () {
                    alert("修改数量失败"+curReserves+msg);
                }
            });
        }else if(0 == curReserves){                          //商品数量为0时
            $.ajax({
                url:"ajax/cartsRpc.php?act=del_quantity",
                type:"post",
                data: { uploadData: uploadData},
                success: function (data) {
                    liIterm.css("display","none");
                    var jsonObj = JSON.parse(data);
                    var msg = jsonObj.opMsg;
                    var oriPrice = jsonObj.price.oriPrice;
                    var subPrice = jsonObj.price.subPrice;
                    var realPrice = jsonObj.price.realPrice;
                    $("#cart_oriPrice").text(oriPrice);
                    $("#cart_subPrice").text(subPrice);
                    $("#cart_realPrice").text(realPrice);
                },
                error: function () {
                    alert("修改数量失败"+curReserves+msg);
                }
            });
        }else{
            alert("数量错误！"+curReserves);
        }
    });
    $("a.quantity-increase").click(function(){
        var t = $(this);
        var user = $("body").attr("data-role");
        var liIterm = t.parents("li");
        var id = liIterm.attr("data-role");
        var numTxt = t.prev(".quantity");
        var curReserves = Number(numTxt.attr("value"))+1; //购物车内此商品数量
        var uploadData = {
            "func":"quantity-increase",
            "user":user,
            "id":id,
            "num":curReserves
        };
        if(0 < curReserves){
            $.ajax({
                url:"ajax/cartsRpc.php?act=in_quantity",
                type:"post",
                data: { uploadData: uploadData},
                success: function (data) {
                    numTxt.attr("value",curReserves);
                    var jsonObj = JSON.parse(data);
                    var msg = jsonObj.opMsg;
                    var oriPrice = jsonObj.price.oriPrice;
                    var subPrice = jsonObj.price.subPrice;
                    var realPrice = jsonObj.price.realPrice;
                    $("#cart_oriPrice").text(oriPrice);
                    $("#cart_subPrice").text(subPrice);
                    $("#cart_realPrice").text(realPrice);
                },
                error: function () {
                    alert("修改数量失败"+curReserves+msg);
                }
            });
        }else{
            alert("数量错误！"+curReserves);
        }
    });
    
    /////订单结算页相关/////
    $("#tel").blur(function () {//通过手机号码检索其它信息
        var tel = $(this);
        if(tel.val() == ""){
            //$("#moileMsg").html("<font color='red'>手机号码不能为空！</font>");
            //tel.focus();
	    alert("手机号码不能为空！");
            return false;
        }
        if(!tel.val().match(/^1[3|5|7|8][0-9]\d{8}$/)){
            //$("#moileMsg").html("<font color='red'>手机号码格式不正确！请检查！</font>");
            //tel.focus();
	    alert("手机号码格式不正确！请检查！");
            return false;
        }
        $.get("ajax/payRpc.php?act=get_by_tel&tel="+tel.val()+"&usertype=",payOnloadFunc);
    });
    
    if ($(".checkNow").length > 0) {
	//设置为只能选择一种付款方式。
        $("input[type='radio']").each(function () {
            if ($(this).is(':checked')) {
                $(this).next().addClass("active");
            }
            $(".segmented-control a").click(function () {
                if ($(this).prev().is(':checked')) {
                    return false;
                }
                $(".segmented-control a").removeClass("active");
                $(this).prev().attr("checked", "checked");
                $(this).addClass("active");
            });
        });
	//点击下单
        $(".checkNow").click(function () {
            var r = $("#realname");
            var t = $("#tel");
            var o = $("#other");    //订单备注
            var b = $("#booktime"); //送货时间
            var a = $("#address");  //送货地址
	    
	    var realname = r.val();
            var tel = t.val();
            var other = o.val();
            var booktime = b.val();
            var address = a.val();
	    
	    var pay = $('.segmented-control input[type="radio"]:checked').val(); //付款方式
            var suburl = "payRpc.php";
    
            var selfget = '';

	    if (address.length == 0) {//地址信息必填
                alert("收货地址不能为空");
                return false;
            }
            if (realname.length == 0) {//收货人信息必填
		alert("收货人不能为空");
		//r.css("box-shadow", "0 0 5px #f00 inset").focus();
                return false;
            }
            
            var uid = $("body").attr("data-role");
            var total_price = $("#total_price").val();
	    alert(total_price);
	    var uploadData = {
		    "func":"confirm_order",
		    "user": uid,
		    "price": total_price,
		    "booktime": booktime, 
		    "tel": tel, 
		    "address": address, 
		    "realname": realname, 
		    "other": other, 
		    "pay": pay,
                };

	    $.ajax({
                type: "POST",
                url: "ajax/" + suburl,
		data: { uploadData: uploadData},
                /*beforeSend: function () {
                    Loadings();	//加载中
                },//*/
                success: function (data) {
                    //shutloading(); //关闭预加载
                    if (data.msg == "success") {
                        var id = data.orderid;
                        if (pay == "0") {//支付宝在线支付
                            location.href = "goalipay.php?orderid=" + data.orderid;
                        } 
                    } else if (data.msg == "no") {
    
                    } else {
                        alert(data);
                    }//*/
                }
            });//$.ajax({
	});//$(".checkNow").click(function () {
	//alert("test");
    };//if ($(".checkNow").length > 0) {       
    
    
    
});









