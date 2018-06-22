<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<!--_meta 作为公共模版分离出去-->
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
<!--_header 作为公共模版分离出去-->
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="/index.php/Admin/Index/index">管理平台</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="/index.php/Admin/Operating/Index/index">管理平台</a> <span class="logo navbar-slogan f-l mr-10 hidden-xs"></span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
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
<link href="/Public/Admin/lib/table/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<link href="/Public/Admin/lib/table/css/bootstrapValidator.min.css" rel="stylesheet" type="text/css" />
<title>机台列表 - 机台管理 </title>
</head>
<body>


<section class="Hui-article-box">
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i><a href="/index.php/Admin/Index/index">首页</a>
		<span class="c-gray en">&gt;</span>
		经营数据
		<span class="c-gray en">&gt;</span>
		经营策略
		<a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
	</nav>
	<div class="Hui-article">
		<form class="form form-horizontal" id="form-article-add" action="/index.php/Admin/Operating/equipment_edit" method="post" onsubmit="return submit_sure()" enctype="multipart/form-data">
		<!-- 隐藏域为机台id -->
		
		<input type="hidden" name="id" id="id" value="<?php echo ($equipment["id"]); ?>">
		<div class="row cl from-group" align="center" >
		<a href="/index.php/Admin/Operating/operating" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe67f;</i>&emsp;返回列表页&emsp;</a>
				&emsp;&emsp;<span class="btn btn-default radius"><i class="Hui-iconfont">&#xe6df;</i>&emsp;经营策略更改</span>
		</div>
		<div class="row cl from-group">
	
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>机台名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text form-control" value="<?php echo ($equipment["name"]); ?>" id="" name="" disabled="disabled">
			</div>
		
		</div>
		<!--上传商品照片-->
		<!--   <div class="tab-pane fade in" id="pics">
                <div class="well">
                        <div>[<a href="javascript:void(0);" class="add">图片+</a>]：<input type="file" name="goods_pics[]" value="" class="input-xlarge"></div>
                </div>
            </div>	 -->
		<div class="row cl from-group">
		
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>单次价格：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text form-control" value="<?php echo ($equipment["price"]); ?>"  id="price" name="price">
			</div>
		
		</div>

		<div class="row cl from-group">
		
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>游戏时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text form-control" value="<?php echo ($equipment["time_limit"]); ?>"  id="time_limit" name="time_limit">
			</div>
		
		</div>
		<?php if( $equipment["type_id"] == 1 ): ?><!--娃娃机-->
		<div class="row cl from-group">
		
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>抓取率：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text form-control" value="<?php echo ($equipment["odds"]); ?>"  id="odds1" name="odds1" style="display: inline;width:50%;"><span style="color:blue;">中奖概率，运行多少次可以抓取一个，0为永不中奖</span>
			</div>
		
		</div><?php endif; ?>
		<?php if( $equipment["type_id"] == 2 ): ?><!--彩票机-->
		<div class="row cl from-group">
		
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>出票数：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text form-control" value="<?php echo ($equipment["odds"]); ?>"  id="odds2" name="odds2" style="display:inline;width:50%;"><span style="color:blue;">单次游戏出票数，0-255张之间，0为不出</span>
			</div>
		
		</div><?php endif; ?>
		<?php if( $equipment["type_id"] == 3 ): ?><!--推币机-->	
		<div class="row cl from-group">
		
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>返还率：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text form-control" value="<?php echo ($equipment["odds"]); ?>"  id="odds3" name="odds3" style="display:inline;width:50%;"><span style="color:blue;">返还比率，50%-100%之间</span>
			</div>
		
		</div><?php endif; ?>





		<div class="row cl from-group">
		
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>机台类型：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text form-control" value="<?php echo ($equipment["type_name"]); ?>"  id="" name="" disabled="disabled">
			</div>

		</div>

		
	
		<div class="row cl from-group">
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
<!-- <script type="text/javascript" src="/Public/Admin/lib/table/js/jquery.min.js"></script> -->
<script type="text/javascript" src="/Public/Admin/lib/table/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/Admin/lib/table/js/bootstrapValidator.min.js"></script>



<script type="text/javascript" src="/Public/Admin/lib/My97DatePicker/4.8/WdatePicker.js"></script> 
<script type="text/javascript" src="/Public/Admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script> 
<script type="text/javascript" src="/Public/Admin/lib/jquery.validation/1.14.0/validate-methods.js"></script> 
<script type="text/javascript" src="/Public/Admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>   
<script type="text/javascript" src="/Public/Admin/lib/webuploader/0.1.5/webuploader.min.js"></script> 
<script type="text/javascript" src="/Public/Admin/lib/ueditor/1.4.3/ueditor.config.js"></script> 
<script type="text/javascript" src="/Public/Admin/lib/ueditor/1.4.3/ueditor.all.min.js"> </script> 
<script type="text/javascript" src="/Public/Admin/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>


<!-- 引入zyupload上传组件 -->
<script type="text/javascript" src="/Public/Admin/zyupload/core/zyFile.js"></script> 
<script type="text/javascript" src="/Public/Admin/zyupload/control/css/zyUpload.css"></script> 
<script type="text/javascript" src="/Public/Admin/zyupload/control/js/zyUpload.js"></script> 
<!-- 引入zyupload上传组件 -->
<script type="text/javascript">
$(function(){
	
	//实例化编辑器
      var ue=UE.getEditor('editor');
      var icon = {
        valid: 'glyphicon glyphicon-ok',
        validating: 'glyphicon glyphicon-refresh'
    };
      //异步删除图片
     

      $("#form-article-add").bootstrapValidator({
        feedbackIcons: icon,
        live: 'disabled',
        fields: {
            equipment_price: {
                validators: {
                    notEmpty: { message: '请输入价格' },
                   // stringLength: { min: 1, max: 3, message: '长度请保持在1至3位' } 
                  	between: { min: 0, max: 100, message:'单次价格应在0 - 100元之间'} 
                }
            },
            time_limit: {
                validators: {
                    notEmpty: { message: '请输入单次时长' },
                    // stringLength: { min: 1, max: 3, message: '长度请保持在1至3位' },
                    between: { min: 10, max: 120, message:'单次时长应在10秒 - 120秒之间'}
                }
            },
             equipment_odds1: {
                validators: {
                    notEmpty: { message: '请输入赔率' },
                    between: { min: 0, max: 255, message: '平均抓取局数应为0-255之间' }
                }
            },
              equipment_odds2: {
                validators: {
                    notEmpty: { message: '请输入出票数量' },
                    between: { min: 0, max: 255, message: '彩票出票应为0-255之间' }
                }
            },
           equipment_odds3: {
           		validators: {
           			notEmpty: { message: '请输入返还率' },
           			between: { min :50, max: 100, message: '赔率百分比应为50%-100%之间'}
           		}
           }
           
        }
    });

      $('.add').click(function(){
      	var add_div = '<div>[<a href="javascript:void(0);" class="sub">图片-</a>]：<input type="file" name="equipment_pics[]" value="" class="input-xlarge"></div>';
      	$(this).parent().after(add_div);

      });
      //添加未来事件
      $('.sub').click(function(){
      	$(this).parent().remove();
      });

      // 添加修改提示
      // $('#addgood').click(function(){
      // 	var equipment_price = $("input[name='equipment_price']").val();
      // 	var time_limit = $("input[name='time_limit']").val();
      // 	var equipment_odds1 = $("input[name='equipment_odds1']").val();
      // 	var id = $("input[name='id']").val();
      // 	if(confirm("此次修改将同时修改<?php echo ($equipment_name); ?>")){
      // 		$.ajax({
      // 			url:'<?php echo U("Admin/Operating/equipment_edit");?>',
      // 			data:{ equipment_price:equipment_price,time_limit:time_limit,equipment_odds1:equipment_odds1,id:id},
      // 			type:'post',
      // 			dataType:'json',
      // 			success:function(response){
      // 				if (response.code != 10000) {
      // 					alert(response.msg);
      // 					return;
      // 				}else{
      // 					alert('修改完成');
      // 					window.location.href='/index.php/Admin/Operating/operating';
      // 				}
      // 			}
      // 		})
      // 	}
      // })

});
function submit_sure(){
	var gnl=confirm("此次修改将同时修改<?php echo ($equipment_name); ?>");
	if (gnl==true) {
		return true;
	}else{
		return false;
	}
}
</script>
<!--/请在上方写此页面业务相关的脚本-->

</body>
</html>