<?php if (!defined('THINK_PATH')) exit();?>﻿<!--_meta 作为公共模版分离出去-->
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

<title>Machine后台</title>

</head>
<body>
<!--头部-->
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="/index.php/Admin/Index/index">管理平台</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="/index.php/Admin/Index/Index/index">管理平台</a> <span class="logo navbar-slogan f-l mr-10 hidden-xs"></span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
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
<!--头部-->

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
	<!-- <nav class="breadcrumb"><i class="Hui-iconfont"></i> <a href="/index.php/Admin/Index/index" class="maincolor">首页</a> 
		<span class="c-999 en">&gt;</span>
		<span class="c-666">我的桌面</span> 
		<a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	 -->
	<div class="Hui-article">
		<article class="cl pd-20">
	
   <!-- 	<div style="width: 600px; height: 200px; margin: 0 auto">
    <div id="container-speed" style="width: 300px; height: 200px; float: left"></div>
    <div id="container-rpm" style="width: 300px; height: 200px; float: left"></div>
	</div>
 -->
			<!-- <p>登录次数：18 </p>
			<p>上次登录IP：222.35.131.79.1  上次登录时间：2014-6-14 11:19:55</p> -->
            <!-- <div id="container" style=""></div> -->
             <div id="container" style="min-width:400px;height:400px"></div>
<!--             <button id="large">放大</button>
			<button id="small">缩小</button> -->
			<div class="mt-20">
               	<table class="table table-border table-bordered table-bg table-sort">
				<thead>
					<tr>
						<th scope="col" colspan="9">统计列表</th>
					</tr>
					<tr class="text-c">
						<!-- <th>ID</th> -->
						<!-- <th>管理员</th> -->
						<th>日期</th>
						<th>机台运行游戏总次数</th>
						<th>抓取成功次数</th>
						<th>抓取失败次数</th>
						<th>每天的收入</th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($equipment_all2)): $k = 0; $__LIST__ = $equipment_all2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><tr class="text-c">
						<!-- <td><?php echo ($v["id"]); ?></td> -->
						<td><?php echo ($v["statistics_date"]); ?></td>
						<td><?php echo ($v["run_count"]); ?></td>
						<td><?php echo ($v["success_number"]); ?></td>
						<td><?php echo ($v["fail_number"]); ?></td>
						<td><?php echo ($v["income_count"]); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody>
			</table>
            </div>
	<!-- <label>实时概况：</label> -->
        
      
        </div>
		
	
</article>
		<!-- <footer class="footer">
			<p>感谢jQuery、layer、laypage、Validform、UEditor、My97DatePicker、iconfont、Datatables、WebUploaded、icheck、highcharts、bootstrap-Switch<br> Copyright &copy;2015 H-ui.admin v3.0 All Rights Reserved.<br> 本后台系统由<a href="http://www.h-ui.net/" target="_blank" title="H-ui前端框架">H-ui前端框架</a>提供前端技术支持</p>
</footer> -->
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
<script type="text/javascript" src="/Public/Admin/lib/hcharts/Highcharts/5.0.6/js/highcharts.js"></script>
<script type="text/javascript" src="/Public/Admin/lib/hcharts/Highcharts/5.0.6/js/modules/exporting.js"></script>
<!-- <script type="text/javascript" src="/Public/Admin/lib/hcharts/Highcharts/5.0.6/js/themes/dark-unica.js"></script> -->
<script type="text/javascript" src="https://img.hcharts.cn/highcharts/highcharts.js"></script>
<script type="text/javascript" src="https://img.hcharts.cn/highcharts/highcharts-more.js"></script>
<script type="text/javascript" src="https://img.hcharts.cn/highcharts/modules/solid-gauge.js"></script>
<script type="text/javascript" src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>

<script type="text/javascript">
// $(function () {
//     var chart = Highcharts.chart('container', {
//         chart: {
//             type: 'column'
//         },
//         title: {
//             text: '商户日统计'
//         },
//         subtitle: {
//             text: ''
//         },
//         xAxis: {
//             categories: <?php echo ($day); ?>
//         },
//         yAxis: {
//             labels: {
//                 x: -15
//             },
//             title: {
//                 text: '统计'
//             }
//         },
//         series: [{
//             name: '销售',
//             data: <?php echo ($count); ?>
//         }],
//         responsive: {
//             rules: [{
//                 condition: {
//                     maxWidth: 500
//                 },
//                 // Make the labels less space demanding on mobile
//                 chartOptions: {
//                     xAxis: {
//                         labels: {
//                             formatter: function () {
//                                 return this.value.replace('号', '')
//                             }
//                         }
//                     },
//                     yAxis: {
//                         labels: {
//                             align: 'left',
//                             x: 0,
//                             y: -2
//                         },
//                         title: {
//                             text: ''
//                         }
//                     }
//                 }
//             }]
//         }
//     });
//     $('#small').click(function () {
//         chart.setSize(400, 300);
//     });
//     $('#large').click(function () {
//         chart.setSize(800, 300);
//     });
// });
</script>
<script type="text/javascript">
 $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '商户日统计'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: <?php echo ($day); ?>
         },
         yAxis: {
                title: {
                        text: '浮动对比'
                },
                labels: {
                        formatter: function () {
                                return this.value  + '次';
                        }
                }
        },
        tooltip: {
              pointFormat: '{series.name} 运行 <b>{point.y:,.0f}</b>次' 
        },
        plotOptions: {
            column: {
                pointPadding: 0.1,
                borderWidth: 0
            }
        },
        series: [{
            name: '运行',
            data: <?php echo ($run_count); ?>

        }, {
            name: '成功',
            data: <?php echo ($success_number); ?>
         },{
			name: '失败',
            data: <?php echo ($fail_number); ?>
        }]
    });


 
</script>

<!--/请在上方写此页面业务相关的脚本-->

<!--此乃百度统计代码，请自行删除-->


<!--/此乃百度统计代码，请自行删除-->
</body>
</html>