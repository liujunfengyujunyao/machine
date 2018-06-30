<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="/Public/Admin/lib/html5shiv.js"></script>
<script type="text/javascript" src="/Public/Admin/lib/respond.min.js"></script>
<![endif]-->
<link href="/Public/Admin/static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/static/h-ui.admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/lib/Hui-iconfont/1.0.8/iconfont.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="/Public/Admin/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>注册</title>

</head>
<body>
<input type="hidden" id="TenantId" name="TenantId" value="" />
<!-- <div class="header"></div> -->
<div class="loginWraper">
  <div id="loginform" class="loginBox">
    <form class="form form-horizontal" action="/index.php/Admin/Login/register" method="post">
    <input type="hidden" name="pid" value="<?php echo ($super); ?>">
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
        <div class="formControls col-xs-8">
          <input id="email" name="email" type="email" placeholder="请输入邮箱" class="input-text size-L">
           
        </div>

      </div>
       <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
        <div class="formControls col-xs-8">
          <input id="nickname" name="nickname" type="text" placeholder="请输入昵称" class="input-text size-L">
           
        </div>

      </div>
      <!--   <div class="row cl">
            <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
            <div class="formControls col-xs-8">

                <input id="code" name="code" type="tel" placeholder="输入邮箱中的验证码" class="input-text size-L">
                <input class="btn btn-primary radius" type="button" name="" value="发送验证码" id="" onclick="sendemail(this);">
            </div>

        </div> -->
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
        <div class="formControls col-xs-8">
          <input id="password" name="password" type="password" placeholder="设置您的密码" class="input-text size-L">
        </div>

      </div>
        <div class="row cl">
            <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
            <div class="formControls col-xs-8">
                <input id="repassword" name="repassword" type="password" placeholder="再次确认密码" class="input-text size-L">
            </div>

        </div>
      <div class="row cl">
       <!--  <div class="formControls col-xs-8 col-xs-offset-3">
          <input class="input-text size-L" type="text" name="verify" placeholder="这里输入验证码">
          <img src="/index.php/Admin/Login/captcha" onclick="this.src = '/index.php/Admin/Login/captcha/_/' + Math.random()">  </div> -->
      </div>
      <div class="row cl">
        <!-- <div class="formControls col-xs-8 col-xs-offset-3">
          <label for="online">
            <input type="checkbox" name="online" id="online" value="">
            使我保持登录状态</label>
        </div> -->
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <input name="" type="submit" class="btn btn-success radius size-L" id="login-btn" value="&nbsp;注&nbsp;&nbsp;&nbsp;&nbsp;册&nbsp;">
          <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
          <a href="/index.php/Admin/Login/login">已有账号? 立即登陆</a>
        </div>
      </div>
    </form>
    <div class="msg"></div>
  </div>
</div>
<div class="footer">Copyright 你的公司名称 </div>
<script type="text/javascript" src="/Public/Admin/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/Admin/static/h-ui/js/H-ui.min.js"></script>
<script>
  var time=60;
  function sendemail(_this){
        if (time == 60) {
            $.ajax({
                'url' : '/index.php/Admin/Api/sendemail',
                'type' : 'post',

            })
        }
  }

</script>
</body>
</html>