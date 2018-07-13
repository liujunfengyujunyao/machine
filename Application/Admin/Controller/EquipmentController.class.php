<?php

namespace Admin\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class EquipmentController extends CommonController{
	//列表页
	public function index(){
		// $x = "6055429003794933";
		// $p = M('Equipment')->field("t1.id as equipment_id,t2.uuid")->alias('t1')->join('left join machine as t2 on t1.uuid = t2.uuid')->select();
		// dump($p);die;
		$id = session('manager_info.id');
		$model = D('Equipment');
		$role_id = session('manager_info.role_id');
		//分页实现
		//获取总记录数
		$total = $model ->where(['pid'=>$id])-> count();
		$m = D('Manager')->where(['pid'=>$id])->find();
		
		// $pagesize = 3;
		// //实例化分页类
		// $page = new \Think\Page($total, $pagesize);//count总条数   pagesize煤业显示多少条数据
		// //定制分页栏的显示
		// $page -> setConfig('prev', '上一页');
		// $page -> setConfig('next', '下一页');
		// $page -> setConfig('first', '首页');
		// $page -> setConfig('last', '尾页');
		// $page -> setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
		// $page -> rollPage = 3;//根据实际情况
		// $page -> lastSuffix = false;
		// //调用show方法获取分页栏代码
		// $page_html = $page -> show();
		// $this -> assign('page_html', $page_html);
		//查询数据
		if ($role_id == 3 && is_null($m)) {
			$data = D('Equipment')->alias('t1')->field('t1.*,t2.*,t3.sn')->where(['t1.pid'=>$id])->join('left join type as t2 on t2.type_id = t1.type')->join("left join machine as t3 on t3.uuid = t1.uuid")->select();

			$this->assign('data',$data);
		}
		
		elseif ($role_id != 4 && $role_id !=6) {
			$manager_id = D('Manager')->where(['pid'=>$id])->getField('id',true);

			//超级管理员下的员工id   array
			$manager_ids = implode(',',$manager_id);//str 
			 $data = $model ->alias('t1')->field('t1.*,t2.nickname,t2.equipment_ids,t3.*,t4.sn,t5.name as goods_name,t5.id as goods_id,t4.isOnline as isonline')-> where("t1.pid = $id or t1.pid in ({$manager_ids})")->join("left join manager as t2 on t1.owner = t2.id")->join("left join type as t3 on t3.type_id = t1.type")->join('left join machine as t4 on t4.uuid = t1.uuid')->join("left join goods as t5 on t1.goods_id = t5.id")->limit($page -> firstRow, $page -> listRows) -> select(); 
			 // $sn = D('Machine')->alias('t1')->where()
			
			//$id为超级管理员id
			//查询出所有属于这个超级管理员的机台信息
			// $pid=($data['0']['goods_pid']);
			
			// $manager = D('Manager')->where(['pid'=>$id])->select();
			$manager = D('manager')->alias('t1')->field('t1.*,t2.*')->where(['t1.pid'=>$id])->join("left join equipment as t2 on t2.owner = t1.id ")->select();
		
			$nickname = session('manager_info.nickname');

			//寻找最新版本号
			
			$version = 'http://'.$_SERVER['HTTP_HOST']."/Public/uploads/version/test.zip";
		 	$this->assign('version',$version);
			//查询出所有普通管理员的信息
			$this->assign('nickname',$nickname);
			$this->assign('manager',$manager);
			$this->assign('data',$data);
		}else{
			//只能看到被分配到的机台
		 	// $model = D('Manager')->where(['id'=>$id])->find();
		 	$manager_id = session('manager_info.id');
		 	// $goods = D('Goods')->where(['goods_user'=>$manager_id])->select();
		 	// $this->assign('goods')
		 	// $pid = $model['pid'];
		 	// $data = D('Equipment')->where(['equipment_user'=>$manager_id])->limit($page -> firstRow, $page -> listRows) ->select();
		 	$data = D('Equipment')->alias('t1')->field("t1.*,t2.*,t3.nickname")->where(['owner'=>$manager_id])->join("left join type as t2 on t1.type = t2.type_id")->join("left join manager as t3 on t3.id = t1.owner")->select();
		 	// $data['version'] = 'http://'.$_SERVER['HTTP_HOST']."/Public/uploads/test.zip";
		 	// dump($data);die;
		 	// dump($data);die;
		 	// $nick = D('Manager')->where(['id'=>$pid])->find();
		 	// $this->assign('nick',$nick);
			$this -> assign('data', $data);

		 }

		
		$this -> display();
	}
	//新增页面
	public function add(){
		
		
			//一个方法处理两个业务逻辑 展示页面逻辑 form表单处理
		//判断请求方式
		
			//一个方法处理两个业务逻辑 展示页面逻辑 form表单处理
		//判断请求方式
		if(IS_POST){
			//处理表单提交
			//接收数据
			$user = session('manager_info');
			$id = $user['id'];
			$data = I('post.');//$_POST
			$mc = $data['mac'];
			$data['mac'] = preg_replace('/[-]+/i','',$mc);//将mac中的-去掉
			$role_id = session('manager_info.role_id');
			//验证机台的唯一验证码	
			$tag = $data['tag'];
			$data['create_time'] = strtotime(date("Y-m-d H:i:s"));
			$model = D('Equipment');
			$res = D('Machine')->where(['tag'=>$tag])->find();
			
			
					//验证设备号是否存在
			//验证上传图片
			// if ($res) {
			// 	$upload_res = $model -> upload_logo($_FILES['logo'],$data);
			// //如果返回值为false,可以获取错误信息
			// 	if (!$upload_res) {
			// 		$error = $model -> getError();
			// 		$this-> error($error);
			// 	}
			// }
			$uuid = D('Machine')->where(['tag'=>$data['tag']])->getField('tag');
			
			// 1db554a8-6557-4f81-bd9a-619e0d021191
			if (is_null($uuid)) {
				$this->error('uuid错误');
			}
			$status = D('Equipment')->where(['tag'=>$tag])->find();

			if ($status) {
				$this->error('重复添加');
			}
			//添加数据
			//user 的status为1 可用  为2禁用
			if ($res !==false && $role_id==3 && $user['status'] == 1) {
				$r['name'] = $data['machine_nickname'];
				$r['status'] = "1";
				$r['pid'] = $user['id'];
				$r['owner'] = $user['nickname'];
				$r['create_time'] = strtotime(date("Y-m-d H:i:s"));
				$r['type'] = $data['equipment_type'];
				$r['uuid'] = $res['uuid'];
				$r['live_channel1'] = $data['channel1'];
				$r['live_channel2'] = $data['channel2'];
				$r['introduce'] = $data['equipment_introduce'];
				$r['tag'] = $res['tag'];
				// dump($equipment_id);die;
				$equipment = $model -> add($r);
				// M('Equipment')->where(['id'=>$equipment])->field('uuid,unit_uuid,key,value')->selectAdd('')
				$set = Array(
				    Array(
				        'uuid'=>$r['uuid'],
				        'unit_uuid'=>$r['uuid'],
				        'key'=>'TIME_OF_GAME',
				        'value'=>'(0   ,true ,true ,true ,1,true ,"30"     )'
				    ),
				    Array(
				        'uuid'=>$r['uuid'],
				        'unit_uuid'=>$r['uuid'],
				        'key'=>'GAME_PRICE',
				        'value'=>'(3   ,true ,true ,true ,1,true ,"2"      )'
				    ),
				    Array(
				        'uuid'=>$r['uuid'],
				        'unit_uuid'=>$r['uuid'],
				        'key'=>'RATE',
				        'value'=>'(20  ,true ,true ,true ,1,true ,"50"     )'
				    ),
				    
				);
				M('Set')->addAll($set);//批量插入
				$gold = D('Group')->where(['pid'=>$id])->find();
				// if (isset($gold['equipment_ids'])) {
				// 	$equipment_ids = $gold['equipment_ids'].','.$r['id'];
				// 	$data['equipment_ids'] = $equipment_ids;
				// 	D('Group')->where(['pid'=>$id])->save($data);
				// }else{
				// 	$group['equipment_ids'] = $r['id'];
				// 	$group['pid'] = $id;
				// 	D('Group')->add($group);
				// }
				//添加图片  删除logo
				$files = $_FILES;
			
				unset($files['logo']);
			    //商品相册图片如果上传失败,商品新增还是认为成功了,不需要对相册上传结果做特殊处理
				$model ->upload_pics($equipment,$files);
				// $data = "$user添加了uuid为:" . $uuid'的机台';
				// $description = '添加了机台:'. '    '. $res['uuid']; 
				// $this->operate_log($description);
				$this->success('添加成功',U('Admin/Equipment/index'));
				
			}
			else if($res !== false && $role_id > 3){
				$pid = D('Manager')->where(['id'=>$id])->getField('pid');//58
				$r['name'] = $res['machine_nickname'];
				$r['status'] = "1";
				$r['pid'] = $pid;
				$r['owner'] = $user['nickname'];
				$r['create_time'] = strtotime(date("Y-m-d H:i:s"));
				$r['type'] = $data['equipment_type'];
				$r['uuid'] = $res['uuid'];
				$r['live_channel1'] = $data['channel1'];
				$r['live_channel2'] = $data['channel2'];
				$r['tag'] = $res['tag'];
				$equipment = $model->add($r);
				
				$files = $_FILES;
				unset($files['logo']);
				$model->upload_pics($equipment,$files);
				$this->success('添加成功',U('Admin/Equipment/index')); 
			}
			else if($res !==false && $role_id==3 && $user['status'] == 2){
				//将machine中的机台添加到equipment表中
				$res['status'] = 1;
				$res['pid'] = $user['id'];
				$res['owner'] = $user['nickname'];
				$res['create_time'] = strtotime(date("Y-m-d H:i:s"));
				$res['uuid'] = $uuid;
				$res['equipment_type'] = $data['equipment_type'];
				$res['equipment_name'] = $res['machine_nickname'];
				$res['live_channel1'] = $data['channel1'];
				$res['live_channel2'] = $data['channel2'];
				$r['tag'] = $res['tag'];
				$add = D('Equipment')->add($res);
				// $equipment = $model -> where(['equipment_id'=>$equipment_id])->save($res);
				$user['status'] = 1;
				$sql = D('Manager')->where(['id'=>$id])->save($user);
				//将管理员的status设为1 激活
				// $x['pid'] = $id;
				// $x['equipment_ids'] = $res['id'];
				// $gold = D('Group')->add($x);
				$files = $_FILES;
				unset($files['logo']);
				$model -> upload_pics($add,$files);
				
				// $description = '添加了机台:'. '    '. $equipment .'成功激活账号';
				// $description = "添加了机台:". '   '. $equipemnt .'成功激活账号';
				// $this->operate_log($description);
				$this->success('激活成功,请重新登录',U('Admin/Index/index'));
				session_destroy();
			}else{
				$this->error('已激活或编号错误');

			}
		
		 }else{
		 	//展示页面
		 	//查询所有的机台类型，用于页面显示下拉列表
		 	$type = D('Type') -> select();
		 	$this -> assign('type', $type);
		 	//查询所有的分类数据
		 	// $category = D('Category') -> select();
		 	// $this -> assign('category', $category);
		 	$this -> display();
		 }
	}


	//修改页面
	public function edit(){
		//一个方法处理两个业务逻辑
		if(IS_POST){
			//表单提交
			//接收数据
			$data = I('post.');
			 //dump($data);die;
			// dump($data);
			// dump($_FILES);
			// die;
			//对于富文本编辑器字段，单独处理,防范xss攻击
			// $data['goods_introduce'] = I('post.goods_introduce', '', 'remove_xss');
			//修改数据表中的记录
			$model = D('Equipment');
			// $goods_model = D('Goods');
			//机台logo新图片的上传
			//机台修改不一定要重新上传新图片 如果有图片需要上传再调用upload_logo
			// if( isset( $_FILES['logo'] ) && $_FILES['logo']['error'] == 0){
			// 	$upload_res = $model -> upload_logo($_FILES['logo'], $data);
			// 	if(!$upload_res){
			// 		$error = $model -> getError();
			// 		$this -> error($error);
			// 	}
			// 	//新的logo图片已上传成功，去查询旧图片的路径，用作后面的删除旧图片
			// 	$equipment = $model -> where(['id' => $data['id']]) -> find();
			// }
			// //使用create方法自动创建数据集
			// $create = $model -> create($data);
			// if(!$create){
			// 	$error = $model -> getError();
			// 	$this -> error($error);
			// }
			// $res = $model -> save();
			// $res = $model -> save($data);
			// dump($res);die;
			//$equipment = M('Equipment')->where(['goods_id'=>$data['id']])->save(['type'=>$data['type_id']]);
			
			$res = D('Equipment')->where(['id'=>$data['id']])->save($data);
			
			//$res 是受影响的记录条数
			if($res !== false){
				//修改成功
				//修改成功后删除旧logo图片及缩略图
				//判断 如果有取出旧图片数据，则删除旧图片
				if(isset($equipment)){
					unlink( ROOT_PATH . $equipment['equipment_big_img'] );
					unlink( ROOT_PATH . $equipment['equipment_small_img'] );
				}

				//继续上传新的相册图片
				$files = $_FILES;
				unset($files['logo']);
				//调用Goods模型的upload_pics完成相册图片的上传
				$model -> upload_pics($data['id'], $files);

				// //机台属性修改
				// foreach($data['attr_value'] as $k => $v){
				// 	//$k 就是attr_id 值
				// 	foreach($v as $attr){
				// 		// $attr 就是 attr_value 值
				// 		$attr_data[] = array(
				// 			//上面添加机台时返回值就是添加成功的主键id
				// 			'equipment_id' => $data['id'],
				// 			'attr_id' => $k,
				// 			'attr_value' => $attr
				// 		);
				// 	}
				// }
				// // dump($attr_data);die;
				// //先删除机台原来的属性
				// // M('EquipmentAttr') -> where("equipment_id={$data['id']}") -> delete();
				// //多条属性数据的批量添加操作
				// M('EquipmentAttr') -> addAll($attr_data);
				$equipment = D('Equipment')->where("id = ({$data['id']})")->find();
				// $equipment_id = $equipment['equipment_id'];
				$goods = M('goods')->where(['id'=>$data['goods_id']])->save(['type_id'=>$data['type']]);
				$description = '修改了机台:'. '    '. $equipment['id']; 
				$this->operate_log($description);
				$this -> success('修改成功', U('Admin/Equipment/index'));
			}else{
				//修改失败
				$this -> error('修改失败');
			}
		}else{
			//展示页面
			//接收数据
			
			$id = I('get.id');
			
			// $category = D('Category')->select();
			//查询数据
			$equipment = D('Equipment') ->alias('t1')-> where(['id' => $id])->join("left join type as t2 on t1.type = t2. type_id") -> find();
			//查询相册图片
			$equipmentpics = D('Equipmentpics')->where(['equipment_id'=>$id])->select();
			
			
			$this->assign('equipmentpics',$equipmentpics);
			$type = D('Type')->select();
			$this->assign('type',$type);
			// dump($goods);die;
			// $this->assign('ratio',$ratio);
			// $this->assign('category',$category);
			$this -> assign('equipment', $equipment);
			//查询相册图片
			// $goodspics = D('Goodspics') -> where(['goods_id' => $id]) -> select();
			// $this -> assign('goodspics', $goodspics);

			// //查询机台类型信息
			// $type = M('Type') -> select();
			// $this -> assign('type', $type);

			// //获取该机台对应的机台类型对应的所有属性（attribute表）
			// $attribute = M('Attribute') -> where("type_id={$goods['type_id']}") -> select();
			// //把每个属性中的可选值转化为数组（方便页面上遍历操作）
			// foreach($attribute as $k => &$v){
			// 	$v['attr_values'] = explode(',', $v['attr_values']);
			// }
			// unset($k, $v);
			// // dump($attribute);die;
			// $this -> assign('attribute', $attribute);
			// // dump($goods);dump($type);die;
			// //获取当前机台拥有的所有属性（gold_goods_attr表）
			// $goods_attr = M('GoodsAttr') -> where("goods_id=$id") -> select();
			// //对goods_attr做处理，转化成
			// // array('attr_id' => array('attr_value','attr_value'))即：
			// // array('10' => array('北京昌平'),'11'=>array('210g'),'12'=>array('卡通','动物','玩具'))
			// // 形式，方便页面判断
			// $new_goods_attr = array();
			// foreach($goods_attr as $k => $v){
			// 	$new_goods_attr[ $v['attr_id'] ][] = $v['attr_value'];
			// }
			// unset($k, $v);
			// // dump($new_goods_attr);die;
			// $this -> assign('new_goods_attr', $new_goods_attr);

			// //查询机台分类数据
			// $category = D('Category') -> select();
			// $this -> assign('category', $category);
			$this -> display();
		}
		
	}
	//经营策略页面
	public function detail(){
		//接收id参数
		$id = I('get.id');
		//查询该机台的基本信息
		$equipment = D('Equipment')->alias('t1')->where(['id'=>$id])->join("left join type as t2 on t1.type = t2.type_id")->find();
		$sn = M('equipment')->alias('t1')->where(['id'=>$id])->join("left join machine as t2 on t1.uuid = t2.uuid")->getField('sn');
		$this->assign('sn',$sn);
		//获取图片路径
		
		$equipmentpics = D('Equipmentpics')->where(['equipment_id'=>$id])->select();
		$res = D('Equipment')->where(['id'=>$id])->find();
		if ($res['goods_id'] == 0) {
			
		}else{

		$goodspics = D('Equipment')->alias('t1')->where(['t1.id'=>$id])->join('left join goods as t2 on t1.goods_id = t2.id')->join('left join goodspics as t3 on t3.goods_id = t2.id')->select();
	
		}
		$isonline = M('Machine')->where(['uuid'=>$equipment['uuid']])->getField('isOnline');
		$this->assign('isonline',$isonline);//显示是否在线
		$this->assign('goodspics',$goodspics);//显示商品照片
		// dump($equipmentpics);die;
		
		$this->assign('equipmentpics',$equipmentpics);//显示机台照片
		$this->assign('equipment',$equipment);//显示机台详细信息
		
		$a = strtotime(date("Y-m-d"),time());//今日零点的时间  
		$b = $a+60*60*24-1;//今日23:59:59的时间
		$price = M('equipment')->alias('t1')->where(['t1.id'=>$id])->join("left join goods as t2 on t2.id = t1.goods_id")->getField('t2.price');
		$today_all= count(M('tbl_game_log')->where("end_time between $a and $b")->where(['equipment_id'=>$id])->select());
		$today_gold = count(M('tbl_game_log')->where("end_time between $a and $b")->where(['equipment_id'=>$id])->where(['type'=>'gold'])->select());
		$today_silver = count(M('tbl_game_log')->where("end_time between $a and $b")->where(['equipment_id'=>$id])->where(['type'=>'silver'])->select());	
		$today = array(
			'count' => $today_all,
			'gold' => $today_gold,
			'silver' => $today_silver,
			'income' => $today_all * $price,
			);


		//这个月的数据
		$c = $a-60*60*24;//昨天的凌晨00:00:00
		$yesterday = M('equipment_day_statistics')->where(['statistics_date'=>$c,'equipment_id'=>$id])->find();//从日志表中查询出昨天这个机台的营业状况
		$month_star = strtotime(date('Y-m'));//本月初
		$month_end = strtotime(date('Y-m-t'))+60*60*24-1;//本月末
		$month_all = M('equipment_day_statistics')->where("statistics_date between $month_star and $month_end")->where(['equipment_id'=>$id])->select();

		foreach ($month_all as $key => &$value) {
				$month['count']+=$value['run_count'];
				$month['gold']+=$value['gold_game_times'];
				$month['silver']+=$value['silver_game_times'];
				$month['income']+=$value['income_count'];
		}
		
		
		//上个月的数据
		$thismonth = date('m');
		$thisyear = date('Y');
		if ($thismonth == 1) {
		 $lastmonth = 12;
		 $lastyear = $thisyear - 1;
		} else {
		 $lastmonth = $thismonth - 1;
		 $lastyear = $thisyear;
		}
		$lastStartDay = $lastyear . '-' . $lastmonth . '-1';
		$lastEndDay = $lastyear . '-' . $lastmonth . '-' . date('t', strtotime($lastStartDay));
		$last_month_star = strtotime($lastStartDay);//上个月的月初时间戳
		$last_month_end = strtotime($lastEndDay)+60*60*24-1;//上个月的月末时间戳
		// dump($last_month_star);die;
		$last_month = M('equipment_month_statistics')->where(['statistics_date'=>$last_month_star,'equipment_id'=>$id])->find();

		// //根据goods_id 查询线上销售额
		// $sales = D('Saleonline') -> where(['equipment_id' => $id]) -> order('month asc') -> select();
		// $data = array();
		// foreach($sales as $k => $v){
		// 	//插件中要求的数据必须是数字类型的
		// 	$data[] = floatval($v['money']);
		// }
		
		// //分析：如果能够得到 索引数组，就可以进行json转化，直接放到页面展示。
		// // $data = array(1,2,3,4,5);
		// // dump($data);die;
		// $this -> assign('data', json_encode($data) );
		// 
		$this->assign('lastmonth',$last_month);
		$this->assign('zhemonth',$month);//这个月截至到目前的数据
		$this->assign('today',$today);//今天的数据
		$this->assign('yesterday',$yesterday);//昨天的数据
		 	

		 	$date = array(
		 	array('days'=>date('Ymd',strtotime('-6 days'))),
	 		array('days'=>date('Ymd',strtotime('-5 days'))),
	 		array('days'=>date('Ymd',strtotime('-4 days'))),
	 		array('days'=>date('Ymd',strtotime('-3 days'))),
	 		array('days'=>date('Ymd',strtotime('-2 days'))),
	 		array('days'=>date('Ymd',strtotime('-1 days'))),
	 		array('days'=>date('Ymd',time())),
		 		);
		 	//machine版本的highcharts
		 	// $today = time();
		 	$today =  strtotime(date('Y-m-d',strtotime('+1 day')));//获取转天凌晨的时间
		 	
			$seven = strtotime('-6 days');//近7天的数据

			//查询出这个机台近7天的运行总次数
		 	$high =  D('tbl_game_log')
		 	->alias('t1')
		 	->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,count(t1.id) count")
		 	->where("t1.end_time between $seven and $today and t1.equipment_id = $id")
		 	->Group('days')
		 	->select();

		 	$high2=$date;
		 	foreach($high2 as $k=>&$v){
	 		foreach($high as $val){
	 			if($v['days'] == $val['days']){
	 				$v['count']=$val['count'];
	 			}
	 			
	 		}
	 		if(!$v['count']){
	 			$v['count']= '0';
	 		}
	 	}
	 	
		 	foreach ($high2 as $key => $value) {
		 		$count[] = floatval($value['count']);

		 		
		 	}
		 	foreach ($high as $k => $v) {
		 			$day[$k] = substr($v['days'],6);
		 		}

		 	$get = D('tbl_game_log')
		 	->alias('t1')
		 	->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,count(t1.id) count")
		 	->where("t1.end_time between $seven and $today")
		 	->where(['t1.equipment_id'=>$id,'got_gift'=>1])
		 	->Group('days')
		 	->select();
		 	
		 	$get2=$date;
	 		foreach($get2 as $k=>&$v){
	 		foreach($get as $val){
	 			if($v['days'] == $val['days']){
	 				$v['count']=$val['count'];
	 			}
	 			
	 		}
	 		if(!$v['count']){
	 			$v['count']= '0';
	 		}
	 	}






		 	$notget = D('tbl_game_log')
		 	->alias('t1')
		 	->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,count(t1.id) count")
		 	->where("t1.end_time between $seven and $today")
		 	->where(['t1.equipment_id'=>$id,'got_gift'=>0])
		 	->Group('days')
		 	->select();
		 

		 	$notget2=$date;

	 		foreach($notget2 as $k=>&$v){
	 		foreach($notget as $val){
	 			if($v['days'] == $val['days']){
	 				$v['count']=$val['count'];
	 			}
	 			
	 		}
	 		if(!$v['count']){
	 			$v['count']= '0';
	 		}
	 	}

	 	foreach ($date as $key => $value) {
	 		$day2[] = substr($value['days'],6);
	 	}
	 	// dump($day);die;
	


	 	

		 	// dump($get);dump($notget);dump($get2);die;
		 	// dump($high);dump($get);dump($notget);
		 	// dump($high2);dump($get2);dump($notget2);die;
		 	foreach ($get2 as $key => $value) {
		 		$win[] = floatval($value['count']);
		 	}

		 	foreach ($notget2 as $key => $value) {
		 		$lose[] = floatval($value['count']);
		 	}
		 	$res['win'] = json_encode($win);
		 	$res['lose'] = json_encode($lose);
		 	$res['count'] = json_encode($count);
		 	
			//$day = array(
			// floatval(date('d',strtotime('-6 days'))),
			// floatval(date('d',strtotime('-5 days'))),
			// floatval(date('d',strtotime('-4 days'))),
			// floatval(date('d',strtotime('-3 days'))),
			// floatval(date('d',strtotime('-2 days'))),
			// floatval(date('d',strtotime('-1 days'))),
			// floatval(date('d',time())),
			// );
			
			$day = json_encode($day);
			$day2 = json_encode($day2);
			$this->assign('day',$day2);
		 	$this->assign('res',$res);//这个机台最近7天的每天运行总次数
		 	$this->assign('data',$data);
		 	$this->display();
		
	}

	//删除方法
	public function del(){
		//接收参数
		$id = I('get.id');
		//删除数据
		$model = D('Equipment');
		$equipment = D('Equipment')->where(['id'=>$id])->find();
		$equipment_id = $equipment['equipment_id'];
		$res = $model -> where(['id' => $id]) -> delete();
		//成功时 $res 是受影响的记录条数
		if($res !== false){
			//删除成功
			$description = '删除了机台:'. '    '. $equipment_id; 
			$this->operate_log($description);
			$this -> success('删除成功', U('Admin/Equipment/index'));
		}else{
			//删除失败
			$this -> error('删除失败');
		}
	}

	//ajax删除相册图片
	public function ajaxdel(){
		//接收数据
		$id = I('post.id');
		//获取要删除的相册图片的图片路径，用作后续删除图片
		$info = D('Equipmentpics') -> where(['id' => $id]) -> find();
		//根据id删除goodspics表中的记录
		$res = D('Equipmentpics') -> where(['id' => $id]) -> delete();
		if($res !== false){
			//删除成功
			//数据表记录删除成功，需要删除对应的图片（4张）
			unlink(ROOT_PATH . $info['pics_origin']);
			unlink(ROOT_PATH . $info['pics_big']);
			unlink(ROOT_PATH . $info['pics_mid']);
			unlink(ROOT_PATH . $info['pics_sma']);
			$return = array(
				'code' => 10000,
				'msg' => 'success'
			);
			$this -> ajaxReturn($return);
		}else{
			// 删除失败
			$return = array(
				'code' => 10001,
				'msg' => '删除失败'
			);
			$this -> ajaxReturn($return);
		}
	}

	//ajax获取机台类型对应的机台属性
	public function getattr(){
		//需要根据type_id 查询属性表 attribute
		$type_id = I('post.type_id', 0, 'intval');
		if($type_id <= 0){
			//参数不合法
			$return = array(
				'code' => 10001,
				'msg' => '参数不合法'
			);
			$this -> ajaxReturn($return);
		}
		//查询属性表
		$attrs = M('Attribute') -> where(['type_id' => $type_id]) -> select();
		$return = array(
			'code' => 10000,
			'msg' => 'success',
			'attrs' => $attrs
		);
		$this -> ajaxReturn($return);
	}


	//分配列表页
	public function leader(){
			$role_id = session('manager_info.role_id');
			$id = session('manager_info.id');
			
			if ($role_id == 3 || $role_id == 5) {
				// $group = D('Group')->where(['pid'=>$id])->find();//管理员列表
			$manager = D('Manager')->where(['pid'=>$id])->select();//员工的信息
			// $manager['equipment_names'] = M('Equipment')->alias('t1')->field()->where("t2.pid = $id")->join("left join manager as t2 on t1.equipment_user = t2.id")->select();
		
			//manager表中的equipment_ids
			$data = D('Equipment')->alias('t1')->field('t1.owner,t1.id as Equipment_id,t2.*')->where(['t1.pid'=>$id])->join("left join manager as t2 on t2.id = t1.owner")->select();
			
			}else{
			
				$self = D('Manager')->where(['id'=>$id])->find();
			
				$pid = $self['pid'];
				$manager = D('Manager')->where(['pid'=>$pid])->select();//同事的信息
				$data = D('Equipment')->alias("t1")->field("t1.owner,t1.id as Equipment_id,t2.*")->where(['pid'=>$pid])->join("left join manager as t2 on t2.id = t1.owner")->select();
				
			}
			
			
			$this->assign('data',$data);
		
			// $this->assign('group',$group);
			$this->assign('manager',$manager);		
			$this->display();
		
	}

		//为员工分配机台
	public function setauth(){
		if (IS_POST) {
		$id = I('post.id');//获取到goods的id
		$ids = implode(',',$id);//分配到的机台id
	
		$manager_id = I('post.manager_id');//manager_id为员工id
		$manager = D('Manager')->where(['id'=>$manager_id])->find();
		//查询出Manager表中这个员工的数据		
		$model = D('Equipment')->where("id in ({$ids})")->select();
		$data['owner'] = $manager['id'];
		$data['status'] = 3;		
		$res=D('Equipment')->where("id in ({$ids})")->save($data);
		//将Goods表中的goods_user改为员工id
		 if ($res!==false) {
		 		// $pid = $manager['pid'];//超级管理员id
		 		// $group['manager_nick'] = $manager['nickname'];//员工的nickname
		 		// $group['manager_id'] = $manager['id'];//员工的eid
		 		// $group['goods_ids'] = $ids;//获取到的goods的id

		 		// D('Group')->where(['pid'=>$pid])->save($group);
		 		$manager_ids = D('Manager')->where(['id'=>$manager_id])->find();
		 		
		 		if ($manager_ids['equipment_ids'] == "") {
		 			$equipment_ids['equipment_ids'] = $ids;
					D('Manager')->where(['id'=>$manager_id])->save($equipment_ids); 
		 		}else{
		 			$equipment_ids['equipment_ids'] = $manager_ids['equipment_ids'] . ',' . $ids;
		 			D('Manager')->where(['id'=>$manager_id])->save($equipment_ids);
		 		}
		 	
		 		// $goods['goods_status'] = 3;
		 		// D('Goods')->where(['goods'])
		 		// $goods['goods_status'] = 3;//goods_status为3正名为已经被分配
		 	
		 		$this->success('分配成功',U('Admin/Equipment/leader'));
		 }else{
		 	$this->error('分配失败,请重试');
		 }
	
		



		}else{
		$role_id = session('manager_info.role_id');
		if ($role_id == 3 || $role_id == 5) {
			$user_id = session('manager_info.id');//23
			$manager_ids = D('Manager')->where(['pid'=>$user_id])->getField('id',true);
			$ids = implode(",",$manager_ids);//28,29,30,32  下属管理员的id
			//$user_id为超级管理员的id
			$id = I('get.id');//28
			//$id为正在被分配机台的员工的id
			$manager = D('Manager')->where(['id'=>$id])->find();
			
			$equipment = D('Equipment')->where("pid=$user_id and status !=3 or pid in ({$ids}) and status !=3")->select();
		}else{
			$user_id = session('manager_info.id');
			$self = D('Manager')->where(['id'=>$user_id])->find();
			$pid = $self['pid'];

			$manager_ids = D('Manager')->where(['pid'=>$pid])->getField('id',true);
			$ids = implode(",",$manager_ids);//"28,29,30,32,35,36"
			$id = I('get.id');//32  $id为正在被分配机台的员工
			$manager = D('Manager')->where(['id'=>$id])->find();
			
			$equipment = D('Equipment')->where("pid=$pid and status !=3 or pid in ({$ids}) and status !=3")->select();
			

		}
		
		
		// dump($goods);die;
		$this->assign('equipment',$equipment);
		$this->assign('manager',$manager);
		$this->display();
		}
	}

	public function delsetauth(){
		if (IS_POST) {
				$id = I('post.id');//数组
				// dump($id);die;
				$ids = implode(',',$id);//获取到的取消分配的ids集合  字符串	
			
				$manager_id = I('post.manager_id');//manager_id为员工id
				$manager = D('Manager')->where(['id'=>$manager_id])->find();//goods_ids需要清除
				$equipment_ids = $manager['equipment_ids'];//str

				$arr = explode(",",$equipment_ids);
					foreach ($arr as $key => $value) {
					  // echo $key."=>". $value."<br />";
					  foreach ($id as $key1 => $value2) {
					  		if ($value==$value2) {
					  			unset($arr[$key]);
					  			// unset($id[$key2]);
					  		}
					  }
				}

					// dump($id);dump($arr);die;
					// 						array(2) {
					// 	  [0] => string(1) "1"
					// 	  [1] => string(1) "7"
					// 	}

					// 	array(1) {
					// 	  [2] => string(1) "8"
					// 	}

				
				$data['user'] = '';
				$data['owner'] = '';
				$data['status'] = 1;
				// $data['goods_pid'] = '';
				$equipment =D('Equipment')->where("id in ({$ids})")->save($data);
				
		
						
			

				$res['equipment_ids'] = implode(',',$arr);
				$x=D('Manager')->where(['id'=>$manager_id])->save($res); 

				if ($x !== false) {
					$this->success('修改成功',U('Admin/Equipment/leader'));
				}else{
					$this->error('修改失败');
				}
				

		}else{
			$user_id = session('manager_info.id');

			$id = I('get.id');

			$manager = D('Manager')->where(['id'=>$id])->find();
			
			$equipment = D('Equipment')->where(['owner'=>$id])->select();
			
			$this->assign('equipment',$equipment);
			$this->assign('manager',$manager);
			$this->display();
		}
	}

	public function version(){
		if (IS_POST) {
			$data = I('post.');
			dump($data);die;
			$uplode= new \Think\Upload();//造实例化对象：造一个上传文件的类     
		    $uplode->maxSize="31457280";//上传文件的大小
		    $uplode->exts=array('jpg','gif','png','jpeg');//设置图片格式
		    $uplode->autoSub=true;//自动使用子目录保存上传文件 默认为true    
		    $uplode->subName=array('date','Ymd');//文件命名方式已时期时间戳命名
		    $uplode->rootPath="./public/";//表示在public文件夹下
		    $uplode->savePath="./Uploads/version";//设置附件上传目录:表示在public文件夹下自动建一个Uploads文件夹
          // 上传文件
               $info   =   $uplode->upload();  
                 if(!$info)
             {
                // 上传错误提示错误信息     
                $this->error($uplode->getError());   
                 }else{
                     // 上传成功 获取上传文件信息
            foreach($info as $file)
            echo $file['savepath'].$file['savename'];
                }        
		}else{
			$this->display();
		}
	}



	public function test(){
	$a = strtotime(date("Y-m-d"),time());  
	$b = $a+60*60*24-1;

	$data = M('tbl_game_log')->where("end_time between $a and $b")->where(['equipment_id'=>2])->where(['got_gift'=>1])->select();
	dump($data);die;
	}
}

