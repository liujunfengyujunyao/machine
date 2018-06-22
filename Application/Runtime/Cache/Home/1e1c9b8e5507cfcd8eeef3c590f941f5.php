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
    $(function(){
        $('#button').click(function(){
            $.ajax({
                type: "post",
                contentType: "application/json;charset=utf-8",
                dataType: "json",
                // jsonp:"callback",
                // jsonpCallback:"success_jsonpCallback",
                data: JSON.stringify({id:1,timestamp:111}),
                // url: "http://www.12202.com.cn/diamond/index.php/Home/test/again",
                // url: "http://www.12202.com.cn/diamond/index.php/Home/Index/weixinpay_js1/userid/1",
                // url: "http://4445c91e.ngrok.io/Home/Rooms/get_banner_pictures",
                // url: "http://d0575f27.ngrok.io/Home/userlogin/c",
                // url: "http://192.168.1.164/Home/userlogin/c",
                // url: "http://www.12202.com.cn/diamond/index.php/Home/Weixinpay/test",
                // 
                // url:"http://liujunfeng.imwork.net:41413/Home/Userlogin/c",
                // url:"http://syjf.elvision.cn/Home/Test/test",
                url : "http://www.12202.com.cn/diamond/index.php/Home/Userlogin/c",
                success: function (res) {
                    console.log(res);
                }
            });


        });
    });
</script>
</html>