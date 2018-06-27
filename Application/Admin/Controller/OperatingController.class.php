<?php
namespace Admin\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");
class OperatingController extends CommonController{
	// public function ggg(){
	// 	$id = session("manager_info.id");
	// 	$ddd = M("equipment_day_statistics")->alias("t1")->field("t1.*,t2.id as rootid,t2.pid")->where(['t2.pid'=>$id])->join("left join equipment as t2 on t2.id = t1.equipment_id")->select();
	// 	dump($ddd);die;

	// }
	//列表页
		// public function index(){
		// 	$id = session('manager_info.id');//获取超级管理员id
		// 	$data = D('Saleonline')->where(['pid'=>$id])->order('month asc')->select();
			
		// 	foreach ($data as $key => $value) {

		// 				$total = floatval($value['money']);
		// 				// dump($total);die;
		// 	}
			

		// 	$this->display();
		// }
		// 
		// 
		//经营数据页面  
	public function index(){
		$manager_id = session('manager_info.id');
	
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

		$meiintime = strtotime($lastStartDay);$endtime = strtotime($lastEndDay);//每一天的时间
		for ($start = $meiintime; $start <= $endtime; $start += 24 * 3600) {
   				$daysd[]['month'] = date("Ymd", $start);"<br/>";
   				//  //$daysd[] = $start;
   			}
		//dump($daysd);die;
		$star = strtotime($lastStartDay);//上个月的月初时间戳
		//dump($star);die;
		$end = strtotime($lastEndDay)+60*60*24-1;//上个月的月末时间戳
		$id = session('manager_info.id');
		$equipment_ids = M('Equipment')->where(['pid'=>$id])->getField("id",true);
		$equipment_ids = implode(",",$equipment_ids);
		$equipment = M('equipment')->where("id in ({$equipment_ids})")->select();
		//查询出名下的机台上个月的统计情况
		$statistics = M('equipment_month_statistics')
		->where("equipment_id in ({$equipment_ids})")
		->where("statistics_date = $star")
		->select();
		//将机台名称放入数组中
		foreach ($equipment as $key => $value) {
			$statistics[$key]['name'] = $value['name'];
		}
		//dump($statistics);die;
		//表格的遍历数据
		$this->assign("star",$star);
		$this->assign("end",$end);
		$this->assign("statistics",$statistics);
		$this->assign("equipment",$equipment);

		$partner_day_statistics = M('partner_day_statistics')->field("FROM_UNIXTIME(statistics_date,'%Y%m%d') month,run_count,success_number,fail_number")->where("pid = $manager_id")->where("statistics_date between $star and $end")->select();
		$partner_day_statistics2 = $daysd;
		foreach ($partner_day_statistics2 as $key => &$value) {
			foreach ($partner_day_statistics as $k => $v) {
				if($value['month'] == $v['month']){
					$value['month'] = $value['month'];
					$value['run_count'] = $v['run_count'];
					$value['success_number'] = $v['success_number'];
					$value['fail_number'] = $v['run_count'] - $v['success_number'];
				}
			}
			if(!$value['month']){
				$value['month'] = '0';
			}
		}
		//dump($partner_day_statistics2);die;
		foreach ($partner_day_statistics2 as $key => &$value) {
		if($key == 0){
				$number['month'] = $value['month'];
				$number['run_count'] = $value['run_count'];
	            $number['success_number'] = $value['success_number'];
	            $number['fail_number'] = $value['fail_number'];
          }else{
          	$number['month'] .= ',' . $value['month'];
            $number['run_count'] .=  ',' . $value['run_count'];
            $number['success_number'] .= ',' . $value['success_number'];
            $number['fail_number'] .= ',' . $value['fail_number'];
          }
		}
		$number['month'] = explode(',', $number['month']);
		$number['run_count'] = explode(',',$number['run_count']);
		$number['success_number'] = explode(",",$number['success_number']);
		$number['fail_number'] = explode(",",$number['fail_number']);

		 
	 	if($daysd == $number['month']){
			foreach ($daysd as $key => $value) {
		 		$day[] = substr($value['month'],6);
		 	}
	 	}
		foreach ($number['run_count'] as $key => $value) {
			$run_count[] = intval($value);
		}
		foreach ($number['success_number'] as $key => $value) {
			$success_number[] = intval($value);
		}
		foreach ($number['fail_number'] as $key => $value) {
			$fail_number[] = intval($value);
		}
		$day = json_encode($day);
		$run_count = json_encode($run_count);
		$success_number = json_encode($success_number);
		$fail_number = json_encode($fail_number);

		$this->assign('day',$day);
		$this->assign('run_count',$run_count);
		$this->assign('success_number',$success_number);
		$this->assign('fail_number',$fail_number);
		$this -> display();
	}









	//详细日志
	public function log(){
		$id = session('manager_info.id');
		//查出超级管理员所有的机器
		$manager = D('Manager')->where(['pid'=>$id])->getField('id',true);//28.29.30.32
		$manager_id = implode($manager,',');
		$manager_equipment = D('Equipment')->where("equipment_pid in ({$manager_id})")->select();

		// dump($manager_goods);die;
		$equipment_ids = array();
		//查出这个超级管理员所有的普通管理员 所拥有的机器
	foreach ($manager_equipment as $key => $value) {
			$equipment_ids[] = $value['id'];
	}
		//10,11
		$equipments_id = D('Equipment')->where(['equipment_pid'=>$id])->getField('id',true);//1,7,8,9
		$equipment_id = array_merge($equipments_id,$equipment_ids);//1,7,8,9,10,11
		//将所有机器id合并为一个数组
		$ids = implode(',',$equipment_id);
		//将数组拆分为字符串
		$data = D('Log')->alias('l')->field('l.*,t.type_name')->join('left join equipment as j on l.equipment_id=j.id')->join('left join type as t on t.type_id = j.equipment_type')->where("l.equipment_id in ({$ids})")->select();
		//将日志表 机台表  机台类型表 进行三表联查
		
		$this->assign('data',$data);
		
		$this->display();
	}


	//机台运行状况 (次数)highcharts
	public function statistics(){
		//求出这个管理员所有的机台id集合
		$id = session('manager_info.id');
		$manager_id = D('Manager')->where(['pid'=>$id])->getField('id',true);
		$manager_ids = implode($manager_id,',');
		$equipment_id = D('Equipment')->where("equipment_pid in ({$manager_ids}) or equipment_pid = $id")->getField('id',true);
		$equipment_ids = implode($equipment_id,',');
		
		//需要算出时间   给出昨天的数据



		$this->display();
	}

	public function group(){
		$this->display();
	}


	public function operating(){
		//列表页
			$id = session('manager_info.id');
			$role_id = session('manager_info.role_id');
			
			if ($role_id == 3 || $role_id == 5) {
				//超级管理员 查询所属所有的机台
				$manager = D('Manager')->where(['pid'=>$id])->select();
				$manager_id = array();
				foreach ($manager as $key => $value) {
					$manager_id[] = $value['id'];
				}
				$manager_ids = implode($manager_id,',');
				if($manager_ids){
				$data = D('Equipment')->alias('t1')->field('t1.*,t2.nickname,t2.equipment_ids,t3.*,t4.sn')->where("t1.pid = $id or t1.pid in ({$manager_ids})")->join("left join manager as t2 on t1.owner = t2.id")->join("left join type as t3 on t3.type_id = t1.type")->join("left join machine as t4 on t4.uuid = t1.uuid")->select();
			}else{
				$data = D('Equipment')->alias('t1')->field('t1.*,t2.nickname,t2.equipment_ids,t3.*,t4.sn')->where("t1.pid = $id")->join("left join manager as t2 on t1.owner = t2.id")->join("left join type as t3 on t3.type_id = t1.type")->join("left join machine as t4 on t4.uuid = t1.uuid")->select();
				
			}
				// dump($data);die;
				//机台数据
				//组群数据
				$group = D('Group')->where(['pid'=>$id])->select();	
				// dump($group);die;
				// dump($group);die;
				// $manager_ids = D('Group')->where(['pid'=>$id])->getField('manager_ids',true);
				// $equipment_ids = D('Group')->where(['pid'=>$id])->getField('equipment_ids',true);

				// // dump($group);die;
				// // dump($group);die;
				// foreach ($group as $key => &$value) {
				// 	$value['equipment_names'] = D('Equipment')->where("id in ({$value['equipment_ids']})")->getField('equipment_name',true);
				// 	$value['equipment_names'] = implode($value['equipment_names'],','); 
				// 	$value['nickname'] = D('Manager')->where("id in ({$value['manager_ids']})")->getField('nickname',true);
				// 	$value['nickname'] = implode($value['nickname'],',');
				// }

				
				foreach($group as $key => &$value){
					$value['equipment_names'] = D('Equipment')->where(['group_id'=>$value['id']])->getField('name',true);
					$value['equipment_names'] = implode($value['equipment_names'],',');
					$value['nickname'] = D('Manager')->where(['group_id'=>$value['id']])->getField('nickname',true);
					$value['nickname'] = implode($value['nickname'],',');
				}
				// dump($group);die;


			}else{
				//普通管理员 查出所属自己的机台和群
				$data = D('Equipment')->alias("t1")->where(['t1.owner'=>$id])->join("left join type as t2 on t1.type = t2.type_id")->select();
					
				//机台数据
				//群组数据
				$group = D('Group')->where(['pid'=>$id])->select();

				// dump($group);die;
				$manager_ids = D('Group')->where(['pid'=>$id])->getField('manager_ids',true);
				$equipment_ids = D('Group')->where(['pid'=>$id])->getField('equipment_ids',true);
			
				// dump($equipment_ids);die;
				
				foreach ($group as $key => &$value) {
					$value['equipment_names'] = D('Equipment')->where("id in ({$value['equipment_ids']})")->getField('name',true);
					$value['equipment_names'] = implode($value['equipment_names'],','); 
					$value['nickname'] = D('Manager')->where("id in ({$value['manager_ids']})")->getField('nickname',true);
					$value['nickname'] = implode($value['nickname'],',');
				}
				
			}

			$this->assign('group',$group);
			$this->assign('data',$data);
			$this->display();
		
	}

	public function equipment_edit(){
		if (IS_POST) {

			$data = I('post.');

			$id = $data['id'];
			$model = D('Equipment');		
			//娃娃机的百分比
			if (isset($data['odds1'])) {
				$data['odds'] = round(100/$data['odds1'],2);	
				$data['odds2'] = round(100/$data['odds1lv2'],2);		
				$data['odds3'] = round(100/$data['odds1lv3'],2);		
				$data['odds4'] = round(100/$data['odds1lv4'],2);		
				$data['odds5'] = round(100/$data['odds1lv5'],2);		
				unset($data['odds1']);
				unset($data['odds1lv2']);
				unset($data['odds1lv3']);
				unset($data['odds1lv4']);
				unset($data['odds1lv5']);
			
			}//彩票机的出票数量
			elseif (isset($data['odds2'])) {
				
				$data['odds'] = $data['odds2'];
				
				unset($data['odds2']);
			}//推币机的返还率
			elseif (isset($data['odds3'])){
				$data['odds'] = $data['odds3'];

				unset($data['odds3']);
			}else{		
				$data['odds'] = 0;		
			}
			//获取到机台的uuid
			$uuid = $model->where(['id'=>$id])->getField('uuid');
			$time_limit = $data['time_limit'];
			$equipment_price = $data['price'];
			$equipment_odds = $data['odds'];
			$equipment_odds_lv2 = $data['odds2'];
			$equipment_odds_lv3 = $data['odds3'];
			$equipment_odds_lv4 = $data['odds4'];
			$equipment_odds_lv5 = $data['odds5'];
			//添加优先等级
			$data['level'] = 2;
			//将所有goods_id相同的机器设置全部修改
			$equipment = $model->where(['id'=>$id])->find();
			//获取机台的goods_id
			if($equipment['goods_id']>0){

				$set = $model->where(['goods_id'=>$equipment['goods_id']])->select();
				foreach ($set as $key => $value) {
					$equipment_uuid[] = "'".$value['uuid']."'";
					$equipment_ids[] = $value['id'];
				}
				$equipment_uuid = implode(',',$equipment_uuid);
				$equipment_ids = implode(',',$equipment_ids);
				$res = $model->where(['id'=>$id])->save($data);
				//将配置内容存入set表中
				$data0['value'] = '(0   ,true ,true ,true ,1,true ,"'.$time_limit.'")';
				$data3['value'] = '(3   ,true ,true ,true ,1,true ,"'.$equipment_price.'")';
				$data20['value'] = '(20   ,true ,true ,true ,1,true ,"'.$equipment_odds.'")';			
				M('Setting')->where("uuid in ({$equipment_uuid}) and `key` = 'TIME_OF_GAME'")->save($data0);
				M('Setting')->where("uuid in ({$equipment_uuid}) and `key` = 'GAME_PRICE'")->save($data3);
				M('Setting')->where("uuid in ({$equipment_uuid}) and `key` = 'RATE'")->save($data20);
				$model->where("id in ({$equipment_ids})")->save(['price'=>$equipment_price,'time_limit'=>$time_limit,'odds'=>$equipment_odds,'odds2'=>$equipment_odds_lv2,'odds3'=>$equipment_odds_lv3,'odds4'=>$equipment_odds_lv4,'odds5'=>$equipment_odds_lv5]);
				$this->success('修改完成',U('Admin/Operating/operating'));
			}else{
				//不大于0，单独改
				
				$res = $model->where(['id'=>$id])->save($data);
				
				$equipment_uuid = $equipment['uuid'];
				$equipment_id = $equipment['id'];
				$data0['value'] = '(0   ,true ,true ,true ,1,true ,"'.$time_limit.'")';
				$data3['value'] = '(3   ,true ,true ,true ,1,true ,"'.$equipment_price.'")';
				$data20['value'] = '(20   ,true ,true ,true ,1,true ,"'.$equipment_odds.'")';

				// M('Set')->where("uuid = $equipment_uuid and `key` = 'TIME_OF_GAME'")->save($data0);
				M('Setting')->where(['uuid'=>$equipment_uuid,`key`=>'TIME_OF_GAME'])->save($data0);
				
				// M('Set')->where("uuid = $equipment_uuid and `key` = 'GAME_PRICE'")->save($data3);
				M('Setting')->where(['uuid'=>$equipment_uuid,`key`=>'GAME_PRICE'])->save($data3);
				M('Setting')->where(['uuid'=>$equipment_uuid,`key`=>'RATE'])->save($data20);
				// M('Set')->where("uuid = $equipment_uuid and `key` = 'RATE'")->save($data20);
				$model->where(['id'=>$equipment_id])->save(['price'=>$equipment_price,'time_limit'=>$time_limit,'odds'=>$equipment_odds,'odds2'=>$equipment_odds_lv2,'odds3'=>$equipment_odds_lv3,'odds4'=>$equipment_odds_lv4,'odds5'=>$equipment_odds_lv5]);
				$this->success('修改完成',U('Admin/Operating/operating'));
			}
			
		}else{
			$id = I('get.id');
			
			
			//查询数据
			$equipment = D('Equipment') ->alias('t1')-> where(['id' => $id])->join("left join type as t2 on t1.type = t2. type_id") -> find();
			if ($equipment['goods_id'] == 0) {
				$equipment_name = $equipment['name'];
			}else{
			//将goods_id相同的机台查找出来一起修改
			$set = M('Equipment')->where(['goods_id'=>$equipment['goods_id']])->select();
			
			foreach ($set as $key => $value) {
				$equipment_name[] = $value['name'];
			}
			$equipment_name = implode('与',$equipment_name);
		}
			$this->assign('equipment_name',$equipment_name);
			
			if ($equipment['type_id'] == 1) {
				$equipment['odds'] = floor(100/$equipment['odds']);
				
				//误差
				if ($equipment['odds'] == 256) {
					$equipment['odds'] = 255;
				}
			}
			// dump($equipment);die;
			$type = D('Type')->select();
	
			$this->assign('type',$type);
			
			$this -> assign('equipment', $equipment);
			
			$this -> display();
		}
	}

	public function group_edit(){
		if (IS_POST) {
			$data = I('post.');
			$id = $data['group_id'];//群组id
			//群组配置将level等级设为1
			$data['level'] = 1;	
			
			//查询出这个群组中所有的娃娃机
			if ($data['type1_time_limit']) {
				$data['type1_equipment_odds'] = round(100/$data['type1_equipment_odds'],2);
			
				//修改娃娃机的配置
				//修改equipment表的数据
				$equipment_type1 = D('Equipment')->where(['group_id'=>$id,'equipment_type'=>"1",'level'=>array(lt,2)])->save(['price'=>$data['type1_equipment_price'],'odds'=>$data['type1_equipment_odds'],'time_limit'=>$data['type1_time_limit'],'level'=>$data['level']]);
				//遍历处理数据
				$a = D('Equipment')->where(['group_id'=>$id,'equipment_type'=>"1",'level'=>array(lt,2)])->select();
				// $uuid = implode(',',$a);
				// dump($uuid);die;
				// dump($uuid);die;
				foreach ($a as $key => $value) {
					$type1_uuid[] = "'".$value['uuid']."'";
				}
				$type1_uuid = implode(',',$type1_uuid);
				
				// dump($type1_uuid);die;
				$data0['value'] = '(0   ,true ,true ,true ,1,true ,"'.$data['type1_time_limit'].'")';
				$data3['value'] = '(3   ,true ,true ,true ,1,true ,"'.$data['type1_equipment_price'].'")';
				$data20['value'] = '(20   ,true ,true ,true ,1,true ,"'.$data['type1_equipment_odds'].'")';
				// $z = D('Set')->where("uuid in ({$type1_uuid}) and 'key'='TIME_OF_GAME'")->select();
				// $z = D('Set')->where(['key'=>TIME_OF_GAME])->select();
				// dump($uuid);die;
				// $where['uuid'] = array("in",$)
				// $z = M('Set')->where("uuid in ({$type1_uuid}) and `key`='TIME_OF_GAME'")->select();
				//修改set表中的数据
				M('Setting')->where("uuid in ({$type1_uuid}) and `key`='TIME_OF_GAME'")->save($data0);
				
				M('Setting')->where("uuid in ({$type1_uuid}) and `key`='GAME_PRICE'")->save($data3);
				M('Setting')->where("uuid in ({$type1_uuid}) and `key`='RATE'")->save($data20);
			}
		
			if ($data['type2_time_limit']) {
				
				//修改彩票机的配置
				$equipment_type2 = D('Equipment')->where(['group_id'=>$id,'type'=>"2",'level'=>array(lt,2)])->save(['price'=>$data['type2_equipment_price'],'odds'=>$data['type2_equipment_odds'],'time_limit'=>$data['type2_time_limit'],'level'=>$data['level']]);
				$a = D('Equipment')->where(['group_id'=>$id,'type'=>"2",'level'=>array(lt,2)])->select();
				
				foreach ($a as $key => $value) {
					$type2_uuid[] = "'".$value['uuid']."'";
				}
				$type2_uuid = implode(',',$type2_uuid);
				$data0['value'] = '(0   ,true ,true ,true ,1,true ,"'.$data['type2_time_limit'].'")';
				$data3['value'] = '(3   ,true ,true ,true ,1,true ,"'.$data['type2_equipment_price'].'")';
				$data20['value'] = '(20   ,true ,true ,true ,1,true ,"'.$data['type2_equipment_odds'].'")';
				M('Setting')->where("uuid in ({$type2_uuid}) and `key`='TIME_OF_GAME'")->save($data0);
				M('Setting')->where("uuid in ({$type2_uuid}) and `key`='GAME_PRICE'")->save($data3);
				M('Setting')->where("uuid in ({$type2_uuid}) and `key`='RATE'")->save($data20);
			}
			if ($data['type3_time_limit']) {
				//修改推币机的配置
				$equipment_type3 = D('Equipment')->where(['group_id'=>$id,'type'=>"3",'level'=>array(lt,2)])->save(['price'=>$data['type3_equipment_price'],'odds'=>$data['type3_equipment_odds'],'time_limit'=>$data['type3_time_limit'],'level'=>$data['level']]);
				$a = D('Equipment')->where(['group_id'=>$id,'type'=>"3",'level'=>array(lt,2)])->select();
				
				foreach ($a as $key => $value) {
					$type3_uuid[] = "'".$value['uuid']."'";
				}
				$type3_uuid = implode(',',$type3_uuid);
				$data0['value'] = '(0   ,true ,true ,true ,1,true ,"'.$data['type3_time_limit'].'")';
				$data3['value'] = '(3   ,true ,true ,true ,1,true ,"'.$data['type3_equipment_price'].'")';
				$data20['value'] = '(20   ,true ,true ,true ,1,true ,"'.$data['type3_equipment_odds'].'")';
				M('Setting')->where("uuid in ({$type3_uuid}) and `key`='TIME_OF_GAME'")->save($data0);
				M('Setting')->where("uuid in ({$type3_uuid}) and `key`='GAME_PRICE'")->save($data3);
				M('Setting')->where("uuid in ({$type3_uuid}) and `key`='RATE'")->save($data20);
			}
			


			// $group = D('Group')->where(['id'=>$id])->find();
			// $equipment = D('Equipment')->where("id in ({$group['equipment_ids']})")->save($data);
			// $group = D('Group')->where(['id'=>$id])->save($data);
			if ($equipment_type3 !== false) {
				$this->success('群组属性修改完成',U('Admin/Operating/operating'));
			}else{
				$this->error('修改失败');
			}
			
		}else{
			// $group_id = I('get.group_id');
			// $group = D('Group')->where(['id'=>$group_id])->find();
			// // $equipment_ids = $group['equipment_ids'];
			// $equipment_ids = D('Equipment')->where(['group_id'=>$group_id])->getField('id',true);
			// $equipment_ids = implode(',',$equipment_ids);
			// // $equipment_names = D('Equipment')->where("id in ({$equipment_ids})")->getField('equipment_name',true);
			// // $group['equipment_names'] = implode($equipment_names,',');
			// // $manager_ids = D('Manager')->where(['group_id'=>$group_id])->getField('id',true);
			// // $manager_ids = implode(',',$manager_ids);

			// $manager_names = D('Manager')->where("id in ({$manager_ids})")->getField('nickname',true);
			// $group['manager_names'] = implode($manager_names,',');
			
			// $this->assign('group',$group);
			$group_id = I('get.group_id');
			
			//查询出这个群组中的娃娃机
			$data['equipment_type1'] = D('equipment')->where(['group_id'=>$group_id,'type'=>"1"])->select();
			// dump($data['equipment_type1']);die;
			//遍历出娃娃机的名称		
			foreach ($data['equipment_type1'] as $key => $value) {
				$type1_name[] = $value['name'];
			}
			$data['type1_name'] = implode(',',$type1_name);

			//查询出这个群组中的彩票机
			$data['equipment_type2'] = D('equipment')->where(['group_id'=>$group_id,'type'=>"2"])->select();
			//遍历出彩票机的名称
			foreach ($data['equipment_type2'] as $key => $value) {
				$type2_name[] = $value['name'];
			}
			$data['type2_name'] = implode(',',$type2_name);
			//查询出这个群组中的推币机
			$data['equipment_type3'] = D('equipment')->where(['group_id'=>$group_id,'type'=>"3"])->select();
			//遍历出推币机的名称
			foreach ($data['equipment_type3'] as $key => $value) {
				$type3_name[] = $value['name'];
			}
			$data['type3_name'] = implode(',',$type3_name);
			// dump($data);die;
			$this->assign('group_id',$group_id);
			$this->assign('data',$data);
			$this->display();
		}
	}

	

}	