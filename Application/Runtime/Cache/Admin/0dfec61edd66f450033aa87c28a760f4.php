<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
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
<title>后台登录</title>

</head>
<body>
<input type="hidden" id="TenantId" name="TenantId" value="" />
<!-- <div class="header"></div> -->
<div class="loginWraper">
  <div id="loginform" class="loginBox">
    <form class="form form-horizontal" action="/index.php/Admin/Login/login" method="post">
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
        <div class="formControls col-xs-8">
          <input id="username" name="username" type="text" placeholder="账户" class="input-text size-L">
        </div>
      </div>
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
        <div class="formControls col-xs-8">
          <input id="password" name="password" type="password" placeholder="密码" class="input-text size-L">
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <input class="input-text size-L" type="text" name="verify" placeholder="这里输入验证码">
          <img src="/index.php/Admin/Login/captcha" onclick="this.src = '/index.php/Admin/Login/captcha/_/' + Math.random()">  </div>
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
          <input name="" type="submit" class="btn btn-success radius size-L" id="login-btn" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
          <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
          <a href="/index.php/Admin/Login/register">立即注册</a>
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
    $(function(){
      $('.#login-btn').on('click',function(){
        if($('#username').val() == ''){
          $('.msg').html('登录名不能为空');
          return;
        }
        if($('#password').val() == ''){
          $('.msg').html('密码不能为空');
          return;
        }
        if ($('#verify').val() =='') {
          $('.msg').html('验证码不能为空');
          return;
        }
        var data = {
          "username":$('#username').val(),
          "password":$('#password').val(),
          "verify":$('#verify').val(),
        };
        $.ajax({
          "type":"post",
          "url":"/index.php/Admin/Login/ajaxlogin",
          "data":data,
          "dataType":"json",
          "success":function(response){
            console.log(response);
            if (response.code != 10000) {
              //登陆失败 code !=10000都标识失败,直接提示错误信息
              alert(response.msg);
            }else{
              //登陆成功,跳转到后台页面
              location.href ="/index.php/Admin/Index/index";
            }
          }
        });
      });
    })

</script>
</body>
</html>