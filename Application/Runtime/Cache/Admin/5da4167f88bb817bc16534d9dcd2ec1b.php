<?php if (!defined('THINK_PATH')) exit();?><!--_meta 作为公共模版分离出去-->
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

<title>商品展示 - 商品管理</title>
<meta name="keywords" content="H-ui.admin v3.0,H-ui网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载">
<meta name="description" content="H-ui.admin v3.0，是一款由国人开发的轻量级扁平化网站后台模板，完全免费开源的网站后台管理系统模版，适合中小型CMS后台系统。">
<style type="text/css">  
            div img{  
                cursor: pointer;  
                transition: all 0.6s;  
            }  
            div img:hover{  
                transform: scale(1.9);  
            }  
        </style>  
</head>
<body>
<!--_header 作为公共模版分离出去-->
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="/index.php/Admin/Index/index">管理平台</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="/index.php/Admin/Group/Index/index">管理平台</a> <span class="logo navbar-slogan f-l mr-10 hidden-xs"></span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
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
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i><a href="/index.php/Admin/Index/index">首页</a> <span class="c-gray en">&gt;</span> 群组管理 <span class="c-gray en">&gt;</span> 群组详情 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="Hui-article">
		<article class="cl pd-20">
			<!-- <div class="text-c">
					<button type="submit" class="btn btn-success" id="" name=""> 下面就是商品详情啦</button>
			</div> -->
			<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l">
				<a href="/index.php/Admin/Group/edit/id/<?php echo ($group["id"]); ?>" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6df;</i>&emsp;编&emsp;辑</a> 
             				<a href="/index.php/Admin/Group/index" class="btn btn-primary radius"> <i class="Hui-iconfont">&#xe67f;</i>&emsp;返回群组列表</a></span> 
			<span class="r" >
				<a href="/index.php/Admin/Group/add" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe600;</i>&emsp;添加群组</a>
			</span>
			 </div>
			<table class="table table-border table-bordered table-bg" >
				<thead>
					<tr>
						<td width="80">群组名称:</td>
						<td><?php echo ($group["group_name"]); ?></td>
					</tr>
					<tr>
						<td>机台构成:</td>
                        
						<td><?php echo ($group["equipment_names"]); ?></td>
                      
					</tr>
					<tr>
						<td>人员构成:</td>
                       
						<td><?php echo ($group["manager_names"]); ?></td>
                       
					</tr>
                    <tr>
                        <td>单次价格</td>
                        <td><?php echo ($group["equipment_price"]); ?></td>
                    </tr>
                    <tr>
                        <td>单次时长</td>
                        <td><?php echo ($group["time_limit"]); ?></td>
                    </tr>
					<table class="table table-border table-bordered table-bg">
                <thead>
                    <tr>
                        <th colspan="7" scope="col">信息统计</th>
            </tr>
                    <tr class="text-c">
                        <th>统计</th>
                        <th>线上机台运行</th>
                        <th>线下机台运行</th>
                        <th>免费游戏次数</th>
                        <!-- <th>机台运行失败</th> -->
                        <th>线上收入</th>
                        <th>线下收入</th>
            </tr>
        </thead>
                <tbody>
            <!--        <tr class="text-c">
                        <td>总数</td>
                        <td>92</td>
                        <td>9</td>
                        <td>0</td>
                        <td>8</td>
                        <td>20元</td>
                        <td>20游戏币</td>
            </tr> -->
                    <tr class="text-c">
                        <td>今日</td>
                        <td><?php echo ($total["today_realtime_online_run_count"]); ?></td>
                        <td><?php echo ($total["today_realtime_offline_run_count"]); ?></td>
                        <td><?php echo ($total["today_realtime_online_free_game_count"]); ?></td>
                    <!--    <td>0</td> -->
                        <td><?php echo ($total["today_realtime_online_income_count"]); ?>元</td>
                        <td><?php echo ($total["today_realtime_offline_income_count"]); ?>游戏币</td>
            </tr>
                    <tr class="text-c">
                        <td>昨日</td>
                        <td><?php echo ($total["yesterday_realtime_online_run_count"]); ?></td>
                        <td><?php echo ($total["yesterday_realtime_offline_run_count"]); ?></td>
                        <td><?php echo ($total["yesterday_realtime_online_free_game_count"]); ?></td>
                        <!-- <td>0</td> -->
                        <td><?php echo ($total["yesterday_realtime_online_income_count"]); ?>元</td>

                        <td><?php echo ($total["yesterday_realtime_offline_income_count"]); ?>游戏币</td>
            </tr>
                    <tr class="text-c">
                        <td>本月</td>
                        <td><?php echo ($total["month_realtime_online_run_count"]); ?></td>
                        <td><?php echo ($total["month_realtime_offline_run_count"]); ?></td>
                        <td><?php echo ($total["month_realtime_online_free_game_count"]); ?></td>
                    
                        <td><?php echo ($total["month_realtime_online_income_count"]); ?>元</td>
                        <td><?php echo ($total["month_realtime_offline_income_count"]); ?>游戏币</td>
            </tr>
                    <tr class="text-c">
                        <td>上月</td>
                        <td><?php echo ($total["month_online_run_count"]); ?></td>
                        <td><?php echo ($total["month_offline_run_count"]); ?></td>
                        <td><?php echo ($total["month_online_free_game_count"]); ?></td>
                        <!-- <td><?php echo ($total["month_unfinish"]); ?></td> -->
                        <td><?php echo ($total["month_online_income_count"]); ?>元</td>
                        <td><?php echo ($total["month_offline_income_count"]); ?>游戏币</td>
            </tr>
        </tbody>
    </table>
					
					
			</table>
			<label>总体概况：</label>
            
 			<div id="container" style="min-width:700px;height:400px"></div>
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



<!--/_footer /作为公共模版分离出去-->


<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/Public/Admin/lib/hcharts/Highcharts/5.0.6/js/highcharts.js"></script>
<script type="text/javascript" src="/Public/Admin/lib/hcharts/Highcharts/5.0.6/js/modules/exporting.js"></script>
<script type="text/javascript" src="/Public/Admin/lib/hcharts/Highcharts/5.0.6/js/themes/gray.js"></script>
<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'areaspline'
        },
        title: {
            text: '线上线下模式运行次数分布'
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 150,
            y: 100,
            floating: true,
            // borderWidth: 1,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        xAxis: {
            categories: <?php echo ($data["day"]); ?>,
            plotBands: [{ // visualize the weekend
                from: 4.5,
                to: 6.5,
                // color: 'rgba(68, 170, 213, .2)'
            }]
        },
        yAxis: {
            title: {
                text: '机台运行次数'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' 次'
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: [{
            name: '线下',
            data: <?php echo ($data["offline_count"]); ?>
        }, {
            name: '线上',
            data: <?php echo ($data["online_count"]); ?>
        }]
    });
});


 
</script>
<!--/请在上方写此页面业务相关的脚本-->

<!-- 仿百度图片放大效果 -->

<!-- 仿百度图片放大效果 -->
</body>
</html>