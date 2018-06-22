<?php if (!defined('THINK_PATH')) exit();?> <!--_meta 作为公共模版分离出去-->
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="favicon.ico" >
<LINK rel="Shortcut Icon" href="favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="/Public/Admin/lib/html5.js"></script>
<script type="text/javascript" src="/Public/Admin/lib/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/Public/Admin/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/Public/Admin/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/Public/Admin/lib/Hui-iconfont/1.0.8/iconfont.css" />

<link rel="stylesheet" type="text/css" href="/Public/Admin/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/Public/Admin/static/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script><![endif]-->
<!--/meta 作为公共模版分离出去-->

<title>商品列表 - 商品管理 </title>
<meta name="keywords" content="H-ui.admin v3.0,H-ui网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载">
<meta name="description" content="H-ui.admin v3.0，是一款由国人开发的轻量级扁平化网站后台模板，完全免费开源的网站后台管理系统模版，适合中小型CMS后台系统。">

<link rel="stylesheet" href="/Public/Admin/zyupload/control/css/zyUpload.css">
</head>
<body>
<!--_header 作为公共模版分离出去-->
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="/index.php/Admin/Index/index">管理平台</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="/index.php/Admin/Goods/Index/index">管理平台</a> <span class="logo navbar-slogan f-l mr-10 hidden-xs"></span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
		<?php if( $_SESSION['manager_info']['role_id']== 3 || $_SESSION['manager_info']['role_id']== 5): ?><nav class="nav navbar-nav">
							<ul class="cl">
					<li class="dropDown dropDown_hover"><a href="javascript:;" class="dropDown_A"><i class="Hui-iconfont">&#xe600;</i> 群管理 <i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="/index.php/Admin/Group/index" onclick=""><i class="Hui-iconfont">&#xe616;</i> 全部 </a></li>
						<?php if(is_array($_SESSION['group'])): $i = 0; $__LIST__ = $_SESSION['group'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="/index.php/Admin/Group/detail/id/<?php echo ($v["id"]); ?>" onclick=""><i class="Hui-iconfont">&#xe616;</i> <?php echo ($v["group_name"]); ?> </a></li><?php endforeach; endif; else: echo "" ;endif; ?>
							
						</ul>
					</li>
				</ul>
				
			</nav><?php endif; ?>
			<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
				<ul class="cl">
					<li></li>
					<li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A"><?php echo ($_SESSION["manager_info"]["nickname"]); ?> <i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<!-- <li><a href="javascript:;" onClick="myselfinfo()">个人信息</a></li> -->
							<li><a href="/index.php/Admin/Login/login">切换账户</a></li>
							<li><a href="/index.php/Admin/Login/logout">退出</a></li>
							<li><a href="/index.php/Admin/Manager/repass/id/<?php echo ($_SESSION['manager_info']['id']); ?>">修改密码</a></li>
						</ul>
					</li>
<!-- 					<li id="Hui-msg"> <a href="#" title="消息"><span class="badge badge-danger">1</span><i class="Hui-iconfont" style="font-size:18px">&#xe68a;</i></a> </li>
 -->					<li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
							<li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
							<li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
							<li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
							<li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
							<li><a href="javascript:;" data-val="orange" title="橙色">橙色</a></li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<!--/_header 作为公共模版分离出去-->

<!--_menu 作为公共模版分离出去-->
<aside class="Hui-aside">

	<div class="menu_dropdown bk_2">
	<?php if(is_array($_SESSION['top'])): $k = 0; $__LIST__ = $_SESSION['top'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol_top): $mod = ($k % 2 );++$k;?><dl id="menu-article<?php echo ($k); ?>">
			<dt><i class="Hui-iconfont">&#xe62d;</i><?php echo ($vol_top["auth_name"]); ?><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
			<?php if(is_array($_SESSION['second'])): $k = 0; $__LIST__ = $_SESSION['second'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol_second): $mod = ($k % 2 );++$k; if( $vol_second["pid"] == $vol_top["id"] ): ?><ul id="menu-article<?php echo ($k); ?>">
					
					<li><a href="/index.php/Admin/<?php echo ($vol_second["auth_c"]); ?>/<?php echo ($vol_second["auth_a"]); ?>"><?php echo ($vol_second["auth_name"]); ?></a></li>
				
			</ul><?php endif; endforeach; endif; else: echo "" ;endif; ?>
			</dd>
		</dl><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<!--/_menu 作为公共模版分离出去-->

<section class="Hui-article-box">
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i><a href="/index.php/Admin/Index/index">首页</a>
		<span class="c-gray en">&gt;</span>
		商品管理
		<span class="c-gray en">&gt;</span>
		编辑商品
		<a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
	</nav>
	<div class="Hui-article">
		<form class="form form-horizontal" id="form-article-add" action="/index.php/Admin/Goods/edit" method="post" enctype="multipart/form-data">
		<!-- 隐藏域为商品id -->
		
		<input type="hidden" name="goods_id" value="<?php echo ($goods["id"]); ?>">
		<div class="row cl" align="center" >
		<a href="/index.php/Admin/Goods/index" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe67f;</i>&emsp;返回列表页&emsp;</a>
				&emsp;&emsp;<span class="btn btn-default radius"><i class="Hui-iconfont">&#xe6df;</i>&emsp;这里是商品编辑</span>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>商品名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo ($goods["name"]); ?>" id="" name="name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>商品类型：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo ($goods["type"]); ?>" id="" name="type">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>机台类型：</label>
			<div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
		
				<select name="type_id" class="select">
					<option value="">==选择类型==</option>
					<?php if(is_array($type)): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["type_id"]); ?>" <?php if( $v['type_id'] == $goods['type_id'] ): ?>selected="selected"<?php endif; ?> ><?php echo ($v["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
				</span> </div>
		</div>
		<div class="row cl from-group">
		
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>单次价格：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text form-control" value="<?php echo ($goods["price"]); ?>"  id="price" name="price">
			</div>
		
		</div>
		<div class="row cl from-group">
		
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>单次时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text form-control" value="<?php echo ($goods["time_limit"]); ?>"  id="time_limit" name="time_limit">
			</div>
		
		</div>
		<div class="row cl from-group">
		
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>赔率：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text form-control" value="<?php echo ($goods["equipment_odds"]); ?>"  id="equipment_odds" name="equipment_odds">
			</div>
		
		</div>
		  <div class="tab-pane fade in" id="pics">
                <div class="well">
                    <?php if(is_array($goodspics)): $i = 0; $__LIST__ = $goodspics;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($i % 2 );++$i;?><div>
                        <img src="<?php echo ($vol["pics_mid"]); ?>">[<a href="javascript:void(0);" pics_id="<?php echo ($vol["id"]); ?>" class="pics_sub">删除</a>]
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="well">
                        <div>[<a href="javascript:void(0);" class="add">+</a>]商品图片：<input type="file" name="goods_pics[]" value="" class="input-xlarge"></div>
                </div>
            </div>
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">存放机台：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<dl class="permission-list">
					<?php if(is_array($equipment)): $i = 0; $__LIST__ = $equipment;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($i % 2 );++$i;?><dt>
						<label>
							<input type="checkbox" value="<?php echo ($vol["id"]); ?>" name="id[]"  <?php if(in_array(($vol["id"]), is_array($keyiyong)?$keyiyong:explode(',',$keyiyong))): ?><!--  checked="checked" --><?php endif; ?> ><?php echo ($vol["name"]); ?>
							</label>
					</dt><?php endforeach; endif; else: echo "" ;endif; ?>
				</dl>
				
			</div>
		</div>
		   <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">现存放于：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<dl class="permission-list">
					<?php if(is_array($equipment_setauth)): $i = 0; $__LIST__ = $equipment_setauth;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><dt>
						<label>
							 <input type="checkbox" value="<?php echo ($v["id"]); ?>" name="equipment_id[]" checked="checked" ><?php echo ($v["name"]); ?>
							</label>
					</dt><?php endforeach; endif; else: echo "" ;endif; ?>
				</dl>
				
			</div>
		</div>    
		<div class="well">
		<div class="row cl">  
			<label class="form-label col-xs-4 col-sm-2">商品简介：</label>
			<div class="formControls col-xs-8 col-sm-9"> 
				<textarea id="" name='equipment_introduce' style="width:800px;height:400px;"><?php echo ($goods["brief"]); ?></textarea>
			</div>
		</div>
	
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button class="btn btn-primary radius" type="submit" id="addgood"><i class="Hui-iconfont">&#xe632;</i> 点击修改</button>
				
			</div>
		</div>
	</form>
	</div>
</section>


<!--_footer 作为公共模版分离出去-->

<script type="text/javascript" src="/Public/Admin/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/Admin/lib/layer/2.4/layer.js"></script>
 
<script type="text/javascript" src="/Public/Admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript" src="/Public/Admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="/Public/Admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
<script type="text/javascript" src="/Public/Admin/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/Public/Admin/static/h-ui.admin/js/H-ui.admin.page.js"></script> 
<script type="text/javascript" src="/Public/Admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>


<!--/_footer /作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<!-- <script type="text/javascript" src="/Public/Admin/lib/My97DatePicker/4.8/WdatePicker.js"></script> 
<script type="text/javascript" src="/Public/Admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script> 
<script type="text/javascript" src="/Public/Admin/lib/jquery.validation/1.14.0/validate-methods.js"></script> 
<script type="text/javascript" src="/Public/Admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>   
<script type="text/javascript" src="/Public/Admin/lib/webuploader/0.1.5/webuploader.min.js"></script>  -->

<!-- <script type="text/javascript" src="/Public/Admin/lib/jquery/1.9.1/jquery.js"></script> -->


<!-- <script type="text/javascript" src="/Public/Admin/lib/laypage/1.2/laypage.js"></script> -->
<!-- 引入zyupload上传组件 -->
<!-- <script type="text/javascript" src="/Public/Admin/zyupload/core/zyFile.js"></script> 

<script type="text/javascript" src="/Public/Admin/zyupload/control/js/zyUpload.js"></script>  -->
<!-- 引入zyupload上传组件 -->
<script type="text/javascript">
$(function(){

	//实例化编辑器
      // var ue=UE.getEditor('editor');
     $('.add').click(function(){
            var add_div = '<div>[<a href="javascript:void(0);" class="sub">-</a>]商品图片：<input type="file" name="equipment_pics[]" value="" class="input-xlarge"></div>';
            $(this).parent().after(add_div);
        });
        // $('.sub').live('click',function(){
        //     $(this).parent().remove();
        // });
      //ajax删除相册图片
      //给前侧图片后面的 - 好绑定onclick事件,发送ajax请求
      $('.pics_sub').click(function(){
      	
      	//ajax的毁掉函数中this关键字的指向会发生变化,这里先保存到另一个变量
      	var _this = this;
      	//发送ajax请求
      	$.ajax({
      		'url': '/index.php/Admin/Goods/ajaxdel',
      		'type':'post',
      		'data':'id=' + $(this).attr('pics_id'),
      		'dataType':'json',
      		'success':function(response){
      			
      			//判断response中的code值
      			if (response.code !=10000) {
      				alert(response.msg);
      				return;
      			}else{
      				//从页面移除当前图片的显示
      				$(_this).parent().remove();
      			}
      		}
      	});
      });


   
   

});

</script>
<!--/请在上方写此页面业务相关的脚本-->

</body>
</html>