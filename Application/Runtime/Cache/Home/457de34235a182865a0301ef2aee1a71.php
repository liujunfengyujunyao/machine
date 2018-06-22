<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>测试页面</title>
    <!-- <script src="__STATIC__/js/jquery.min.js"></script> -->
    <script type="text/javascript" src="/Public/Admin/lib/jquery/1.9.1/jquery.min.js"></script> 
</head>
<body>
<input type='button' id="button">

</body>
<script type="text/javascript">
 function onBridgeReady(){
    var data=<?php echo ($data); ?>;
    WeixinJSBridge.invoke(
        'getBrandWCPayRequest', data, 
        function(res){
            if(res.err_msg == "get_brand_wcpay_request:ok" ) {
            window.location.href="http://www.12202.com.cn/vue/index.html#/recharge?amount="+data.product_id+"&msgtype=success"; 
            // window.location.href='http://192.168.1.171/?code=061l1eNd25UAVC0S6WLd23RhNd2l1eNb&state=#/recharge';
                // window.history.back();
                            // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
            }else{
                window.location.href="http://www.12202.com.cn/vue/index.html#/recharge?msgtype=error"; 
                  // console.log("error");  
                // window.history.back();
                // alert(res.err_code+res.err_desc+res.err_msg); // 显示错误信息
            }
        }
    );
}
 if (typeof WeixinJSBridge == "undefined"){
     if( document.addEventListener ){
         document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
     }else if (document.attachEvent){
         document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
         document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
     }
 }else{
      onBridgeReady();
 }
    $('#button').click(function(){
            $.ajax({
                type: "GET",
                contentType: "application/json;charset=utf-8",
                dataType: "json",
                data: JSON.stringify({out_trade_no:1528691070}),
                url: "http://www.12202.com.cn/diamond/index.php/Home/Weixinpay/pay",
                success: function (res) {
                    console.log(res);
                }
            });
        });

</script>
</html>