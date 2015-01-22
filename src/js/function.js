$(function(){
	 var myNav = $(".bar a"),i;
	  for(i=0;i<myNav.length;i++){
	    var links = myNav.eq(i).attr("href"),myURL = document.URL;
	     if(myURL.indexOf(links) != -1) {
	       myNav.eq(i).css("color","#3071a9")
	     }
	}
	var searchbox=$("#searchbox");
	$(".icon-search").click(function(event){/*显示搜索框*/
		event.stopPropagation();  //取消冒泡事件
		searchbox.show('fast');
		//点击弹出窗口时返回flase
		searchbox.click(function(){ return false;});
		$(document).click(function (event) { searchbox.hide('fast') }); 
	});
	//购物车的JS部分
	$("input.add").live("click",function(){//添加购物车
		var buy=$(this);
		var str=buy.attr("data-role");
		var strs=str.split("@");
		var reserves=Number(strs[3]);
		var limit=eval(strs[5]);
		
		var x=buy.offset().left;
		var y=buy.offset().top;
		
		
		var obj=buy.prev("#thisnum");
		var curReserves=Number(obj.text());	//购物车内商品数量
		/*
		if(curReserves && limit){
			AlertQa(buy,"商品限购");
			return false;
		}*/
		if(eval(reserves)>eval(curReserves) || eval(reserves)<=0){		
		}else{
			$('<u class="alert">库存不足啦！</u>').appendTo("body").css({"top":y,"left":x-150}).fadeOut(1800).remove(18000);
			return false;
		}
		
		$.ajax({ 
			type:"post",
			url: "docart.php?act=add", 
			beforeSend:function(){
				Numloading(buy,0);
			},
			data:{str:str}, 
			success: function(msg){
				obj.show(0).text(curReserves+1);
				CartNum();
	      	}
		});
	});
	$("input.plus").live("click",function(){//减少数量
		var t=$(this)
		var str=t.attr("data-role");
		var obj=t.next("#thisnum");
		var curReserves=Number(obj.text());	//购物车内商品数量
	
		$.ajax({ 
			type:"post",
			url: "docart.php?act=sub", 
			beforeSend:function(){
				Numloading(t,0);
			},
			data:{str:str}, 
			success: function(msg){
				CartNum();			
				if(eval(curReserves>1)){
					obj.text(curReserves-1);
				}else{
					obj.text(0).hide(0);
				}
	      	}
		});
	});
	
	$("input.upcart").live('click',function(){//增加数量
		var ad=$(this);
		var id=ad.attr("data-role");
		var x=ad.offset().left;
		var y=ad.offset().top;
		var obj=ad.prev("#thisnum");
		var curReserves=Number(obj.text());	//购物车内商品数量
		$.ajax({
			type : "POST",
			url : "docart.php?act=upcart",
			beforeSend:function(){
				Numloading(ad,0);
			},
			data : {id:id},
			dataType:"json",
			success : function(msg) {
				if(msg.act=="true"){
					CartNum();
				}else if(msg.act=="limit"){
					AlertQa(ad,"限购");
				}else{
					obj.text(curReserves);
					$('<u class="alert">库存不足啦！</u>').appendTo("body").css({"top":y-1,"left":x-150}).fadeOut(1800).remove(18000);
				}
			}
		});		
	});
	$(".empty").click(function(){//清空购物车
		$.get("ajax/docart.php?act=empty",function(data){
			CartNum();
		});
	});
	$("#tel").blur(function(){//通过手机号码检索其它信息
		var tel=$(this).val();
		$.ajax( {
			type : "POST",
			url : "../ajax/searchinfo.php",
			dataType: "json", 
			data:{tel:tel},
			success : function(msg) {
				$("#address").val(msg.address);
				$("#realname").val(msg.realname);
			}
		});	
	});
	if($("#other").length>0){
		$(".beizhu a.btn").click(function(){
			insertAtCaret($("#other").get(0), $(this).text() + " ");
		});
	}
	if($(".checkNow").length>0){
		$("input[type='radio']").each(function(){
			if($(this).is(':checked')){
				$(this).next().addClass("active");
			}
			$(".segmented-control a").click(function(){
				if($(this).prev().is(':checked')){
					return false;
				}
				$(".segmented-control a").removeClass("active");
				$(this).prev().attr("checked","checked");
				$(this).addClass("active");
			});
		});
		
		//使用新人优惠券
		if("booking"!=$("#orderact").val()){
			var total_price=$("#total_price").val();
			if(parseInt(total_price)>=10){
				document.querySelector('#pay-newman').addEventListener('toggle', function(){
					$("#pay-newman-input").toggle();
				})
			}
		}
		
		
		//预定选择自提还是送达,,针对预定订单
//		$("#delivery_way1").live('click',function(){
//			$("input[name=delivery_way]").val(1);
//			$("#songda-location").hide();
//			$("#ziti-location").show();
//			$("#ziti-location select").attr("name","address");
//			$("#ziti-location select").attr("id","address");
//			$("#songda-location input").attr("name","");
//			$("#songda-location input").attr("id","");
//		});
//		
//		$("#delivery_way2").live('click',function(){
//			$("input[name=delivery_way]").val(0);
//			$("#ziti-location").hide();
//			$("#songda-location").show();
//			$("#songda-location input").attr("name","address");
//			$("#songda-location input").attr("id","address");
//			$("#ziti-location select").attr("name","");
//			$("#ziti-location select").attr("id","");
//		});
		
		$(".checkNow").click(function(){
			var act=$("#orderact").val();		//预定或直接下定
			var r=$("#realname");
			var t=$("#tel");
			var o=$("#other");
			var b=$("#booktime");
			var a=$("#address");
			
			var realname=r.val();
			var tel=t.val();
			var other=o.val();
			var booktime = b.val();
			var address = a.val();
			
			var pay=$('.segmented-control input[type="radio"]:checked').val(); //付款方式
			var suburl="suborder.php";
			
			//var selfget = '';
			if(act=="booking"){
				suburl="bookorder.php";
				//selfget = $("input[name=delivery_way]").val();//取货方式
			}
			
			if(pay=="1"){//当选择在余额扣除时提交检测余额是否足够
				var payfull=$("#payfull").val()
				if(payfull<0){
					$(".login-tip").html('您还未登录或余额不足！<a href="reg.php">登录</a>');
					return false;
				}
			}
			if(booktime.length==0){//没有选择预定时间
				b.css("box-shadow","0 0 5px #f00 inset").focus();
				return false;
			}

			if(tel.length!=11 || !/^[0-9]*$/.test(tel)){//手机格式错误
				t.css("box-shadow","0 0 5px #f00 inset").focus();
				return false;
			}
			if(address.length==0){//地址信息必填
				a.css("box-shadow","0 0 5px #f00 inset").focus();
				return false;
			}
			if(realname.length==0){//地址信息必填
				r.css("box-shadow","0 0 5px #f00 inset").focus();
				return false;
			}
			
			var uid=$("#uid").val();
			var total_price=$("#total_price").val();
			
			if(parseInt(total_price)>=10){
				var newManCoupon = $("input[name=newManCoupon]").val();
				if($("#pay-newman").hasClass("active")){
					if(newManCoupon.length==0){
						$("input[name=newManCoupon]").css("box-shadow","0 0 5px #f00 inset").focus();
						return false;
					}
				}
			}
			
			$.ajax({
				type : "POST",
				url : "ajax/"+suburl,
				dataType: "json", 
				data:{"newManCoupon":newManCoupon,"booktime":booktime,"tel":tel,"address":address,"realname":realname,"other":other,"pay":pay},
				beforeSend:function(){
					Loadings();
				},
				success : function(msg) {
					shutloading();//关闭预加载
					if(msg.act=="true"){
						var id = msg.orderid;
						if(pay=="2"){//支付宝在线支付
							location.href="goalipay.php?orderid="+msg.orderid;
						}else{
							$("#emobile").text(tel);
							if(uid){
								$("#etext").html('您下单获得积分<font color=red> '+total_price+' </font>');
							}
							if(booktime!="立即送出"){
								$(".modal .havebooktime").html("在"+booktime+"准时为您送哒");
							}
							$(".modal .btn2 a").attr("href","order-view.php?id="+id);
							$(".modal").addClass("active");
						}
					}else{
						alert(msg.text);
					}
				}
			});	
		});
	}
	if($("#reg").length>0){
		var u=$("#username");
		var e=$("#email");
		var p=$("#password");
				
		u.blur(function(){
			var username=u.val();
			if(username.length>0){//当用户名不为空是执行
				$.post("inc/signup.php",{"act":"checkuser","username":username},function(data){
					if(data.status=="1"){//用户名已经存在
						RegTips(u,"用户名被占用",false);
					}else{
						RegTips(u,"正确",true);
					}
				},"json");
			}
		});
		e.blur(function(){
			var email=e.val();
			reg=/^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/gi;
			if(reg.test(email)){//当email不为空是执行
				$.post("inc/signup.php",{"act":"checkemail","email":email},function(data){
					if(data.status=="1"){//用户名已经存在
						RegTips(e,"Email被占用",false);
					}else{
						RegTips(e,"正确",true);
					}
				},"json");
			}else{
				RegTips(e,"格式错误",false);
			}
		});
		p.blur(function(){
			var password=p.val();
			if(password.length==0){
				RegTips(p,"不能为空",false);
			}else{
				RegTips(p,"正确",true);
			}
		});
		//提交注册
		$(".regnow").click(function(){
			var username=u.val();
			if(username.length>0){//当用户名不为空是执行
				$.post("inc/signup.php",{"act":"checkuser","username":username},function(data){
					if(data.status=="1"){//用户名已经存在
						RegTips(u,"用户名被占用",false);
						u.focus();
					}
				},"json");
			}else{
				RegTips(u,"用户名不能为空",false);
				u.focus();
				return false;
			}
			var email=e.val();
			reg=/^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/gi;
			if(reg.test(email)){//当email不为空是执行
				$.post("inc/signup.php",{"act":"checkemail","email":email},function(data){
					if(data.status=="1"){//已经存在
						RegTips(e,"Email被占用",false);
						e.focus();
					}
				},"json");
				if(status=="1"){
					return false;
				}
			}else{
				RegTips(e,"格式错误",false);
				e.focus();
				return false;
			}
			var password=p.val();
			if(password.length==0){
				RegTips(p,"不能为空",false);
				p.focus();
				return false;
			}
			$.ajax({
				type:"POST",
				url:"inc/reg.do.php?act=reg",
				data:{"username":username,"email":email,"password":password},
				dataType: "json", 
				beforeSend:function(){
					Loadings(); //加载中...
				},
				success:function(data){
					if(data.act=="username"){
						alert("用户名被占用");
						location.reload();
					}else if(data.act=="full"){
						alert("资料填写不完整");
						location.reload();
					}else if(data.act=="failed"){
						alert("插入数据失败");
						location.reload();
					}else{//注册成功
						$(".cover").hide();
						$(".loadings").hide();
						$(".modal").addClass("active");
					}
				}
			});
		});
		$(".regtips").live("click",function(){//点击提示时向右滑动退出
			$(this).animate({"right":-200});
		});
	}
	//修改在线支付为货到付款
	$(".editstatus").click(function(){
		var orderid=$(this).attr("data-role");
		$.post("ajax/editstatus.php",{"orderid":orderid},function(data){
			location.href="order-view.php?id="+orderid;
		});
	});
	//积分兑换
	$(".credits-convert").click(function(){
		var id=$(this).attr("id");
		$.post("ajax/credit_convert.php",{"id":id},function(data){
			location.reload();
		});
	});
	//居中弹框
	$(".shutoff").click(function(){
		$(".middlebox").hide();
		$(".cover").hide();
	});
	$(".aboutus").click(function(){
		$(".cover").show();
		$(".middlebox").css("height","175px").show();
	});
	//商品显示大图
	$(".showBig").live("click",function(){
		var title=$(this).attr("data-role");
		title=title.substring(0,12);
		var img=$(this).attr("src");
		var imgArr=img.split("/");
		var imgName=imgArr[4];		//图片名称
		$(".cover").show();
		$(".middlebox .htitle").text(title);
		$(".middlebox .contents").css({"text-align":"center","text-indent":"0"}).html('<img src="../products/uploads/max/'+imgName+'" width="150" height="150"/>');
		$(".middlebox").css("height","225px").show();
	});
	//预购商品
	if($(".yadd").length>0){
		var objQ=$("#bnums");
		$(".yadd").click(function(){
			var Q=eval(objQ.val());
			var c=Q+1;
			objQ.val(c);
		});
		$(".yplus").click(function(){
			var Q=eval(objQ.val());
			if(Q>1){
				var c=Q-1;
				objQ.val(c);
			}
		});
	}
	
	//客户催单
	$("#orderview-cuidan").live("click",function(){
		$(this).html("提交催单");
		$(this).removeClass("btn-negative");
		$(this).addClass("btn-primary");
		$(this).attr('id',"orderview-cuidan-submit");
		$("#orderview-cuidan-text").show();
		$("#orderview-cuidan-submit").click(function(){
			var obj = $(this);
			var id = obj.attr("orderid");
			var txt = $("#orderview-cuidan-text").val();
			$.post("ajax/orderdeal.php",{"id":id,"act":"cuidan","note":txt},function(data){
				$("#orderview-cuidan-text").hide();
				obj.html("您已催单");
				obj.removeAttr("orderid");
				obj.removeAttr("id");
				obj.removeClass("btn-primary");
				obj.addClass("btn-positive");
			});
		});
	});
	//客户取消订单
	$("#orderview-cancel").live("click",function(){
		if(!confirm('您确认要取消此订单吗\n取消后不可恢复')){
			return false;
		}
		var obj = $(this);
		var id = obj.attr("orderid");
		$.post("ajax/orderdeal.php",{"id":id,"act":"cancel"},function(data){
			obj.html("订单已被取消");
			obj.removeAttr("orderid");
			obj.removeAttr("id");
			obj.next().remove();
		});
	});
	
	
	//下单pay.php 选择配送地址
	$("#pay_location1").live("change",function(){
		$(this).attr("style","");
		var location1 = $(this).val();
		$("#pay_location2").hide();
		$("#address").hide();
		$("#address").val("");
		if(location1=="" || location1==null){
			//
		}else if(location1=="其它"){
			$(this).removeClass("buyLocation");
			$(this).addClass("buyLocation-24");
			$("#address").show();
		}else{
			$(this).removeClass("buyLocation-24");
			$(this).addClass("buyLocation");
			$("#pay_location2").show();
		}
	});
	
	$("#pay_location2").live("change",function(){
		$(this).attr("style","");
		var location1 = $("#pay_location1").val();
		var location2 = $("#pay_location2").val();
		if(location2!=""&&location2!=null){
			$("#address").val(location1+""+location2);
		}
	});
	
});
function loading(){
	$(".area").html('<span class="loading"><img src="images/loading.gif" />加载中...</span>');
}
function Numloading(t){
	$(t).parent().find("#thisnum").html('<img src="images/loading.gif" />');
}
function CartNum(){//商品数量
	$.ajax({
		type:"POST",
		url:"cartnum.php",
		dataType: "json", 
		success:function(data){
			//if(data.amount>0){
				$("#tnum").text(data.amount).show(0);
				$("#tprice").text(data.price).show(0);
				$(".J_order_count").show(0);
				/*
			}else{
				$(".J_order_count").hide(0);
				$(".paynext").hide(0);
				$("h1.title").text('您还没有选购商品 ');
				
				if($("#cartArea").length>0){
					$("#cartArea").html('<p class="content-padded"><img src="images/cart-empty.png" alt="购物车是空的" class="fimg"/></p>');
				}
			}*/
			if($("#cartArea").length>0){//在购物车页面时处理
				showCart();
			}
		}
	});
}
function ClearSpace(data){//清除Ajax返回值中的空格
	var newdata=data.replace(/^\s*|\s*$/g,"");
	return newdata;
}
function showCart(){
	$.get("ajax/cartview.php",function(data){
		$("#cartView").html(data)
	});
}
function AlertQa(e,text){
	var x=e.offset().left;
	var y=e.offset().top;
	$('<u class="Qa">'+text+'</u>').appendTo("body").css({"top":y,"left":x-100}).fadeOut(1800).remove(18000);
}
function setCaret(textObj) {
	if (textObj.createTextRange) {
		textObj.caretPos = document.selection.createRange().duplicate();
	}
}
function insertAtCaret(textObj, textFeildValue) {
	if (document.all && textObj.createTextRange && textObj.caretPos) {
		var caretPos = textObj.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == '' ? textFeildValue
				+ ''
				: textFeildValue;
	} else if (textObj.setSelectionRange) {
		var rangeStart = textObj.selectionStart;
		var rangeEnd = textObj.selectionEnd;
		var tempStr1 = textObj.value.substring(0, rangeStart);
		var tempStr2 = textObj.value.substring(rangeEnd);
		textObj.value = tempStr1 + textFeildValue + tempStr2;
		textObj.focus();
		var len = textFeildValue.length;
		textObj.setSelectionRange(rangeStart + len, rangeStart + len);
		textObj.blur();
	} else {
		textObj.value += textFeildValue;
	}
}
function CheckLogin(){
	var u=$("#username");
	var p=$("#password")
	var username=u.val();
	var password=p.val();
	
	if(username.length==0){
		u.css("box-shadow","0 0 5px #900 inset").focus();
		return false;
	}
	if(password.length==0){
		p.css("box-shadow","0 0 5px #900 inset").focus();
		return false;
	}	
}
function Loadings(){
	$(".cover").show();
	$(".loadings").show();	
	setTimeout(function(){//超时时间
		$(".cover").hide();
		$(".loadings").hide();	
	},10000);
}
function shutloading(){//关闭加载
	$(".cover").hide();
	$(".loadings").hide();	
}
function RegTips(t,word,act){
	var x=t.offset().left;
	var y=t.offset().top;
	var height=t.height();
	if(act==true){
		t.next().text(word).removeClass("errortip").addClass("succtips").css({"height":height,"line-height":height+"px"}).animate({"top":-7,"right":0},"fast");
	}else{
		t.next().text(word).removeClass("succtips").addClass("errortip").css({"height":height,"line-height":height+"px"}).animate({"top":-7,"right":0},"fast");
	}
}
function CheckNums(){//支付宝充值时检查金额
	var obj=$("#total_fee");
	var total_fee=obj.val();
	
	var decimalReg=/^\d{0,8}\.{0,1}(\d{1,2})?$/;
	if(total_fee==0 || !decimalReg.test(total_fee)){
		RegTips(obj,"充值金额格式错误");
		return false;
	}
}
