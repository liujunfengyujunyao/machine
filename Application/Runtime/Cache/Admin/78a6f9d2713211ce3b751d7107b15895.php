<?php if (!defined('THINK_PATH')) exit();?>﻿
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

<title>机台列表 - 机台管理 - Diamond后台</title>
<meta name="keywords" content="H-ui.admin v3.0,H-ui网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载">
<meta name="description" content="H-ui.admin v3.0，是一款由国人开发的轻量级扁平化网站后台模板，完全免费开源的网站后台管理系统模版，适合中小型CMS后台系统。">
</head>
<body>
<!--_header 作为公共模版分离出去-->
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="/index.php/Admin/Index/index">管理平台</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="/index.php/Admin/Equipment/Index/index">管理平台</a> <span class="logo navbar-slogan f-l mr-10 hidden-xs"></span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
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
					<li id="Hui-msg" title="消息" data-container="body" data-toggle="popover" data-placement="bottom"  data-trigger="hover" data-html="true"> 
						<a href="#" title="消息"><?php if( $_SESSION['msg_count'] > 0 ): ?><span class="badge badge-danger"><?php echo ($_SESSION["msg_count"]); ?></span><?php endif; ?><i class="Hui-iconfont" style="font-size:18px">&#xe68a;</i></a> 
					</li>
					<li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
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
<div id="modal-demo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content radius">
			<div class="modal-header">
				<h3 class="modal-title" ><i class="Hui-iconfont" style="">&#xe68a;</i>&nbsp;消息列表</h3>
				<a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a>
			</div>
			<div class="modal-body">
				<ul style='padding:0 20px'></ul>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary">确定</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
			</div>
		</div>
	</div>
</div>
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
		机台管理
		<span class="c-gray en">&gt;</span>
		机台列表
		<a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
	</nav>
	<div class="Hui-article">
		<article class="cl pd-20">
			<!-- <div class="text-c">
				<span class="select-box inline">
				<select name="" class="select">
					<option value="0">全部分类</option>
					<option value="1">分类一</option>
					<option value="2">分类二</option>
				</select>
				</span>
				日期范围：
				<input type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}'})" id="logmin" class="input-text Wdate" style="width:120px;">
				-
				<input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d'})" id="logmax" class="input-text Wdate" style="width:120px;">
				<input type="text" name="" id="" placeholder=" 机台名称" style="width:250px" class="input-text">
				<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜机台</button>
			</div> -->
			<div class="cl pd-5 bg-1 bk-gray mt-20">
				<span class="l">
				<?php if( $_SESSION['manager_info']['role_id']== 3 ): ?><a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a><?php endif; ?>
				<a class="btn btn-primary radius" data-title="添加机台" _href="add.html" href="/index.php/Admin/Equipment/add"><i class="Hui-iconfont">&#xe600;</i> 添加机台</a>
				<?php if( $_SESSION['manager_info']['id']== 1 ): ?><a class="btn btn-warning radius" data-title="添加版本" _href="version.html" href="/index.php/Admin/Equipment/version"><i class="Hui-iconfont">&#xe600;</i> 添加版本</a><?php endif; ?>
				</span>
				<span class="r">共有数据：<strong><?php echo ($row_count); ?></strong> 条</span>
			
			</div>
			<div class="mt-20">
				<table class="table table-border table-bordered table-bg table-hover table-sort">
					<thead>
						<tr class="text-c">
							<th width="25"><input type="checkbox" name="" value=""></th>
							<th width="60">ID</th>
							<th width="80">机台名称</th>
							<!-- <th width='80'>SN</th>> -->
							<th width="80">机台类型</th>
							<th width="80">存放商品</th>
							<th width="60">单次时长</th>
							<th width="80">金币单次价格</th>
							<th width="80">银币单次价格</th>
							<th width="80">机台负责人</th>
							<!-- <th width="150">机台大图</th> -->
							<th width="80">运行状态</th>
							<th width="80">锁</th>
							<th width="60">添加时间</th>
							<th width="60">机台版本</th>
							<th width="120">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr class="text-c">
							<td><input type="checkbox" value="" name=""></td>
							<td><?php echo ($v["id"]); ?></td>
							<td class="text-l"><a href="/index.php/Admin/Equipment/detail/id/<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></a></td>
							<!-- <td><?php echo ($v["sn"]); ?></td> -->
							<td class="text-l" ><center><?php echo ($v["type_name"]); ?></center></td>
							<td class="text-1"><a href="/index.php/Admin/Goods/detail/id/<?php echo ($v["goods_id"]); ?>"><center><?php echo ($v["goods_name"]); ?></center></a></td>
							<td><font color="blue"><?php echo ($v["time_limit"]); ?></font></td>
							<td><font color="red"><b><?php echo ($v["price"]); ?></b></font></td>
							<td><font color="red"><b><?php echo ($v["money"]); ?></b></font></td>
							<td><?php echo ($v["nickname"]); ?></td>
							
							  <td>
							    <?php if( $v["state"] == 1): ?><img src="/Public/Admin/paihang/images/online.png" alt="在线" title="在线" ><?php endif; ?>
							    <?php if( $v["state"] == 0): ?><img src="/Public/Admin/paihang/images/noline.png"  alt="离线" title="离线" ><?php endif; ?>
							    <?php if( $v["state"] == -1 ): ?><img src="/Public/Admin/paihang/images/fault.png"  alt="故障" title="故障" ><?php endif; ?>
								<?php if( $v["state"] == -2 ): ?><img src="/Public/Admin/paihang/images/lock.png"  alt="锁定" title="锁定" ><?php endif; ?>
							  </td>


							<td class="f-14 td-manage">
							<?php if( $v["lock"] == 1 ): ?><!-- <a style="text-decoration:none" class="ml-5-off" href="/index.php/Admin/Equipment/off/id/<?php echo ($v["id"]); ?>" title="关机"><i class="Hui-iconfont">&#xe726;关机</i></a> -->
									<a style="text-decoration:none" class="ml-5-lock" title="解锁"><i class="Hui-iconfont">&#xe63f;解锁</i></a><?php endif; ?>
								<?php if( $v["lock"] == 0 ): ?><!-- <a style="text-decoration:none" class="ml-5-off" href="/index.php/Admin/Equipment/off/id/<?php echo ($v["id"]); ?>" title="关机"><i class="Hui-iconfont">&#xe726;关机</i></a> -->
									<a style="text-decoration:none" class="ml-5-lock" title="锁定"><i class="Hui-iconfont">&#xe60e;锁定</i></a><?php endif; ?>
							</td>
							<!-- <td><img src="<?php echo ($v["equipment_small_img"]); ?>" alt=""></td> -->
							<!-- <td class="td-status"><span class="label label-success radius"><?php echo (date("Y-m-d",$v["equipment_create_time"])); ?></span></td> -->
							<td class="td-status"><span class="label label-success radius"><?php echo (date("Y-m-d",$v["create_time"])); ?></span></td>
							<td><?php echo ($v["version"]); ?></td>
							<td class="f-14 td-manage">
							<?php if( $v["state"] == 1 ): ?><!-- <a style="text-decoration:none" class="ml-5-off" href="/index.php/Admin/Equipment/off/id/<?php echo ($v["id"]); ?>" title="关机"><i class="Hui-iconfont">&#xe726;关机</i></a> -->
									<a style="text-decoration:none" class="ml-5-off" title="关机"><i class="Hui-iconfont">&#xe726;关机</i></a><?php endif; ?>
								<!-- <a style="text-decoration:none" class="ml-5-restart" href="/index.php/Admin/Equipment/restart/id/<?php echo ($v["id"]); ?>" title="重启"><i class="Hui-iconfont">&#xe6f7;重启</i></a> -->
								<a style="text-decoration:none" class="ml-5-restart" title="重启"><i class="Hui-iconfont">&#xe6f7;重启</i></a>
								<a style="text-decoration:none" class="ml-5" href="/index.php/Admin/Equipment/upload/id/<?php echo ($v["id"]); ?>" title="机台升级"><i class="Hui-iconfont">&#xe61d;升级版本</i></a>
								<a style="text-decoration:none" class="ml-5" href="/index.php/Admin/Equipment/edit/id/<?php echo ($v["id"]); ?>" title="机台编辑"><i class="Hui-iconfont">&#xe6df;编辑</i></a>
								<?php if( $_SESSION['manager_info']['role_id']== 3 ): ?><a style="text-decoration:none" class="ml-5" onClick="equipment_del(this,'<?php echo ($v["id"]); ?>')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;删除</i></a><?php endif; ?>
								</td>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					</tbody>
				</table>
			</div>
		</article>
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
<script type="text/javascript">
$(function(){
        $('#Hui-msg').click(function(){
                $("#modal-demo").modal("show");

        });
        $('#modal-demo').on('show.bs.modal', function () {
                $.ajax({
                        url:"/index.php/Admin/Common/allMsg",
                        dataType:'json',
                        success:function(res) {
                           if(res.status==1001){
                                $('#modal-demo .modal-body ul').html(res.html);
                           }else{
                                $('#modal-demo .modal-body ul').html('暂无消息');
                           }
                                
                        }
                });
        });
	var getting = {
                url:'/index.php/Admin/Common/notReadMsg',
                dataType:'json',
                success:function(res) {
                	if(res.status==1001){
                                $('#Hui-msg').attr('data-content',res.msg);
                		if(res.count > 99){
                			$('#Hui-msg .badge').text('99+');
                		}else{
                			$('#Hui-msg .badge').text(res.count);
                		}
                                if(res.renew == 1){
                                        $('#Hui-msg').popover('show');
                                        setTimeout(function(){$('#Hui-msg').popover('hide')},7000);
                                }
                	}else{
                		$('#Hui-msg .badge').text('');
                                $('#Hui-msg').removeAttr('title');
                                $('#Hui-msg').attr('data-content','暂无新消息');
                	} 
                }
	};
        $.ajax(getting);
	//Ajax定时访问服务端，不断获取数据 ，10秒请求一次。
	window.setInterval(function(){$.ajax(getting)},10000);

       
});
</script>


<!--/_footer /作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/Public/Admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/Public/Admin/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript">
$('.table-sort').dataTable({
	"aaSorting": [[ 1, "asc" ]],//默认第几个排序
	"bStateSave": true,//状态保存
	"aoColumnDefs": [
		// {"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
		{"orderable":false,"aTargets":[0,8]}// 不参与排序的列
	]
});

/*机台-编辑*/
function equipment_edit(title,url,id,w,h){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*机台-删除*/
function equipment_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$.ajax({
			type: 'POST',
			url: '/index.php/Admin/Equipment/del/id/'+id,
			dataType: 'json',
			success: function(data){
				$(obj).parents("tr").remove();
				layer.msg('已删除!',{icon:1,time:1000});
				 window.location.reload();
			},
			error:function(data) {
				console.log(data.msg);
			},
		});		
	});
}

//ajax发送关机请求
$('.ml-5-off').click(function(){
		var data = {//machineid机台ID
			"machineid":$(this).parents('tr').children().eq(1).text(),
			// "version_id":$(this).parents('tr').children().eq(1).text(),
		};
          $.ajax({
        
                    "type":"post",
                    "url":"/index.php/Admin/Equipment/off",
                    "data": data,
                    "dataType":"json",
                    "success":function(response){
                        console.log(response);
                        if(response.code != 10000){
                            //失败 code != 10000都表示失败，直接提示错误信息
                            alert("服务器繁忙,请稍后再试");
                            // console(response.msg);
                        }else{
                            //成功， 跳转到后台首页
                            // location.href = "/index.php/Admin/Equipment/index";
                             window.location.reload();
                            console.log(response);
                        }
                    }
                });
		});



//ajax发送重启请求
$('.ml-5-restart').click(function(){
		var data = {
			"machineid":$(this).parents('tr').children().eq(1).text(),
			// "version_id":$(this).parents('tr').children().eq(1).text(),
		};
          $.ajax({
        
                    "type":"post",
                    "url":"/index.php/Admin/Equipment/restart",
                    "data": data,
                    "dataType":"json",
                    "success":function(response){
                        console.log(response);
                        if(response.code != 10000){
                            //登录失败 code != 10000都表示失败，直接提示错误信息
                            // console.log(response);
                            alert("服务器繁忙,请稍后再试");
                        }else{
                            //登录成功， 跳转到后台首页
                            // location.href = "/index.php/Admin/Equipment/index";
                             window.location.reload();
                            console.log(response);
                        }
                    }
                });
		});

//锁住机台
$('.ml-5-lock').click(function(){
	var data = {
		"machineid":$(this).parents('tr').children().eq(1).text(),
	};
	$.ajax({
		'type' : "post",
		'url' : "/index.php/Admin/Equipment/lock",
		'data' : data,
		'dataType' : "json",
		"success":function(response){
			console.log(response);
			if (response.code != 10000) {
				alert("服务器繁忙,请稍后再试");
			}else{
				window.location.reload();
				console.log(response);
			}
		}
	});
});





$("test").click(function(){
	var data = {
		"machineid" : $(this).parents('tr').children().eq(1).text(),
	};
	$.ajax({
		"type" : "post",
	})
})

</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>