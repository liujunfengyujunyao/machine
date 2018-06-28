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

<title>Diamond后台</title>
<style>
#datatable {
        border: 1px solid #ccc;
        border-collapse: collapse;
        border-spacing: 0;
        font-size: 12px;
}
td,th {
        border: 1px solid #ccc;
        padding: 4px 20px;
}
</style>

</head>
<body>
<!--头部-->
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
    <nav class="breadcrumb"><i class="Hui-iconfont"></i> <a href="/index.php/Admin/Index/index" class="maincolor">首页</a> 
        <span class="c-999 en">&gt;</span>
        <span class="c-666">经营数据</span> 
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="Hui-article">
        <article class="cl pd-20">
            <!-- <p>登录次数：18 </p>
            <p>上次登录IP：222.35.131.79.1  上次登录时间：2014-6-14 11:19:55</p>
            <table class="table table-border table-bordered table-bg">
                <thead>
                    <tr>
                        <th colspan="7" scope="col">信息统计</th>
            </tr>
                    <tr class="text-c">
                        <th>统计</th>
                        <th>资讯库</th>
                        <th>图片库</th>
                        <th>产品库</th>
                        <th>用户</th>
                        <th>管理员</th>
            </tr>
        </thead>
                <tbody>
                    <tr class="text-c">
                        <td>总数</td>
                        <td>92</td>
                        <td>9</td>
                        <td>0</td>
                        <td>8</td>
                        <td>20</td>
            </tr>
                    <tr class="text-c">
                        <td>今日</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
            </tr>
                    <tr class="text-c">
                        <td>昨日</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
            </tr>
                    <tr class="text-c">
                        <td>本周</td>
                        <td>2</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
            </tr>
                    <tr class="text-c">
                        <td>本月</td>
                        <td>2</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
            </tr>
        </tbody>
    </table> -->
    <label>月度报表(<?php echo (date("Y-m-d",$star)); ?>---<?php echo (date("Y-m-d",$end)); ?>)：</label>
        <!-- <div id="container" style="min-width:700px;height:400px"></div> -->
        <!-- <div id="container" style="min-width:400px;height:400px"></div> -->
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                        <tr class="text-c">
                            <!-- <th width="25"><input type="checkbox" name="" value=""></th> -->
                            <th>机台ID</th>
                            <!-- <th>订单编号</th> -->
                            <th>名称</th>
                            <th>总运行次数</th>
                            <th>抓取成功</th>
                            <th>抓取失败</th>
                            <th>收费游戏</th>
                            <th>免费游戏</th>
                            <th>收费成功抓取</th>
                            <th>收费失败抓取</th>
                            <th>免费成功抓取</th>
                            <th>免费失败抓取</th>
                            <th>收取游戏币</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php if(is_array($statistics)): $i = 0; $__LIST__ = $statistics;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr class="text-c">
                            <!-- <td><input type="checkbox" value="" name=""></td> -->
                            <td><?php echo ($v["equipment_id"]); ?></td>
                            <td><?php echo ($v["name"]); ?></td>
                            <td><?php echo ($v["run_count"]); ?></td>
                            <td><?php echo ($v["success_number"]); ?></td>
                            <!-- <td class="text-l"><?php echo ($v["tag"]); ?></td> -->
                            <td><?php echo ($v["fail_number"]); ?></td>
                            <td><font color="blue"><?php echo ($v["gold_game_times"]); ?></font></td>
                            
                            <td><font color="red"><b><?php echo ($v["silver_game_times"]); ?></b></font></td>
                            <!-- <td class="td-status"><span class="label label-success radius"><?php echo (date("Y-m-d",$v["equipment_create_time"])); ?></span></td> -->
                            <!-- <td class="text-l"><a href="/index.php/Admin/Goods/detail/id/<?php echo ($v["goods_id"]); ?>"><?php echo ($v["goods_name"]); ?></a></td> -->
                            <td><?php echo ($v["gold_game_win_times"]); ?></td>
                            <td><?php echo ($v["gold_game_lose_times"]); ?></td>
                            <td><?php echo ($v["silver_game_win_times"]); ?></td>
                            <td><?php echo ($v["silver_game_lose_times"]); ?></td>
                            <td><?php echo ($v["income_count"]); ?></td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                
                    </tbody>
                </table>
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


<!--/_footer /作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->dark-unica
<script type="text/javascript" src="/Public/Admin/lib/hcharts/Highcharts/5.0.6/js/highcharts.js"></script>
<script type="text/javascript" src="/Public/Admin/lib/hcharts/Highcharts/5.0.6/js/modules/exporting.js"></script>
<script type="text/javascript" src="https://img.hcharts.cn/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="https://img.hcharts.cn/highcharts/modules/data.js"></script>
<script type="text/javascript" src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
<script src="http://cdn.hcharts.cn/highcharts/modules/data.js"></script>


<!-- <script type="text/javascript" src="/Public/Admin/lib/hcharts/Highcharts/5.0.6/js/themes/dark-unica.js"></script> -->
<script type="text/javascript">
var chart = Highcharts.chart('container',{
        chart: {
                type: 'area'
        },
        title: {
                text: '商户月统计'
        },
        subtitle: {
                text: '数据来源: <a href="https://www.goldenbrother.cn/index.php/admin">' +
                'https://www.goldenbrother.cn</a>'
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
                area: {
                        pointStart: 1,
                        marker: {
                                enabled: false,
                                symbol: 'circle',
                                radius: 2,
                                states: {
                                        hover: {
                                                enabled: true
                                        }
                                }
                        }
                }
        },
        series: [{
            name:"运行总数",
            data:<?php echo ($run_count); ?>
        },{
            name:"成功次数",
            data:<?php echo ($success_number); ?>
        },{
            name:"失败次数",
            data:<?php echo ($fail_number); ?>
        }

        ]
});

</script>
<!--/请在上方写此页面业务相关的脚本-->

<!--此乃百度统计代码，请自行删除-->


<!--/此乃百度统计代码，请自行删除-->
</body>
</html>