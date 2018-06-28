<?php
namespace Admin\Controller;
use Think\Controller;
class GroupController extends CommonController{
	public function index(){
		$id = session("manager_info.id");
		$group = D('Group')->where(['pid'=>$id])->select();
		
		if ($group) {
			$group_id = D('Group')->where(['pid'=>$id])->getField('id',true);
			$group_ids = implode(',',$group_id);
			
			

			// dump($group_ids);die;
			// $data = D('Group')->alias('t1')->field("t1.group_name,t2.equipment_name,t3.nickname")->where("t1.id in ({$group_ids})")->join("left join equipment as t2 on t1.id = t2.group_id")->join("left join manager as t3 on t2.group_id = t3.group_id")->select();
		

			
			foreach ($group as $key => &$value) {
			$value['equipment_name'] = D('Equipment')->where(['group_id'=>$value['id']])->getField('name',true);
			$value['equipment_names'] = implode(',',$value['equipment_name']);
			$value['manager_nickname'] = D('Manager')->where(['group_id'=>$value['id']])->getField('nickname',true);
			$value['manager_nicknames'] = implode(',',$value['manager_nickname']); 
			$this->assign('group',$group);

		}
		// dump($group);die;


		// foreach ($data as $key => $value) {
		// 	$value['id'] = D('Group')->where("id in ({$group_ids})")->getField('id',true);
		// 	$value['group_name'] = D('Group')->where(['pid'=>$id])->getField('group_name',true);
		// 	$value['equipment_names'] = D('Equipment')->alias('t1')->where("t1.group_id in ({$group_ids}) and t2.group_id in ({$group_ids})")->join("left join manager as t2 on t1.group_id = t2.group_id")->getField('equipment_name',true);
		// 	// dump($value['equipment_names']);die;
		// 	$value['equipment_names'] = implode($value['equipment_names'],',');		
		// 	$value['manager_nicknames'] = D('Manager')->where("group_id in ({$group_ids}) and ")->getField('nickname',true);
		// 	dump($value['manager_nicknames']);die;
		// 	$value['manager_nicknames'] = implode($value['nickname'],',');
		// 	// dump($value['nickname']);die;
		// }
		// // dump($data);dump($value);die;
		// // $data['manager'] = D('Manager')->where("pid = $id and group_id = ")
		// // dump($group);die;
		// $manager_ids = D('Group')->where(['pid'=>$id])->getField('manager_ids',true);
		// $equipment_ids = D('Group')->where(['pid'=>$id])->getField('equipment_ids',true);
		// //查询出所属这个管理员的机台和人员列表
			
		
		// // dump($group);die;
		// // dump($group);die;
		// foreach ($group as $key => &$value) {
		// 	$value['equipment_names'] = D('Equipment')->where("id in ({$value['equipment_ids']})")->getField('equipment_name',true);
		// 	$value['equipment_names'] = implode($value['equipment_names'],','); 
		// 	$value['nickname'] = D('Manager')->where("id in ({$value['manager_ids']})")->getField('nickname',true);
		// 	$value['nickname'] = implode($value['nickname'],',');
		// }

		// dump($group);die;
		
		}
		// dump($group);die;
		
		$this->display();
	}

	


	public function add(){
		if (IS_POST) {
			//判定管理员权限等级

			$id = session('manager_info.id');
			$group_name = I('post.group_name');
			$res = I('post.');
		
			if ($group_name=="") {
				$this->error('群名称不能为空');
			}
			
			$group = D('Group')->where("pid = $id && group_name = '$group_name'")->find();
			
			if ($group) {
				$this->error('重复创建!');
			}
			//判断出所属于这个管理员的群组唯一性
			$data['group_name'] = $res['group_name'];
			// $data['equipment_price'] = $res['equipment_price'];
			// $data['time_limit'] = $res['time_limit'];
			$data['pid'] = $id;
			$data['group_id'] = D('Group')->add($data);

			//add方法创建成功返回id
			//ids 集合
			$equipment_ids = implode(',', $res['equipment_id']);
			$manager_ids = implode(',',$res['manager_ids']);
			
			
			if ($equipment_ids) {
				//修改equipment机台的group_id值
				// $equipment = D('Equipment')->where("id in ({$equipment_ids})")->save(['group_id'=>$group_id]);
				$equipment = D('Equipment')->where("id in ({$equipment_ids})")->save($data);

			}
			if ($manager_ids) {
				//修改manager人员的group_id的值
				$manager = D('Manager')->where("id in ({$manager_ids})")->save(['group_id'=>$data['group_id']]);
				
			}
			
			$this->redirect('Admin/Group/index');





		}else{

			$id = session('manager_info.id');
			
			$user = D('Manager')->where(['pid'=>$id])->getField('id',true);
			$manager_ids = implode(',',$user);
			$manager = D('Manager')->where("pid = $id && group_id = 0")->select();
			$equipment = D('Equipment')->where("(pid in ({$manager_ids}) or pid = $id) and group_id = 0")->select();
			// $manager = D('Manager')->where("pid = $id")->select();
			

			// if (empty($manager)) {
			// 	//普通管理员 只能分配自己拥有的机台
			// 	// $sup_manager = D('Manager')->where(['id'=>$id])->find();
			// 	// $pid = $sup_manager['pid'];
			// 	// $super = D('Manager')->where(['pid'=>$pid])->getField('id',true);
			// 	// $manager_ids = implode(',',$super);
				
			// 	// $equipment = D('Equipment')->where("equipment_pid in ({$manager_ids}) or equipment_pid = $pid")->select();
			// 	// $manager = D('Manager')->where(['pid'=>$pid])->select();
			// 	$user = D('Manager')->where(['id'=>$id])->find();
			// 	$equipment_ids = $user['equipment_ids'];
			// 	$equipment = D('Equipment')->where(['equipment_user'=>$id])->select();
				

				

			// }else{
				//超级管理员
				
				// $super = D('Manager')->where(['pid'=>$id])->getField('id',true);
				// $manager_ids = implode(',',$super);
				// $equipment = D('Equipment')->where("group_id = 0 and equipment_pid in ({$manager_ids}) or equipment_pid = $id")->select();//查出未被分配的机台
				
				// // $equipment = D('Equipment')->alias('t1')->where("t1.equipment_pid in ({$manager_ids}) s")
				// $manager = D('Manager')->where("pid = $id and group_id = 0")->select();//查出未被分配的成员
				
				// dump($goods);die;
				 
				$this->assign('equipment',$equipment);
				$this->assign('manager',$manager);
				$this->display();
			}
		
			
		// }
	}

	public function delAll(){
		$ids = I('post.ids');
		$res = D('Group')->where("id in ({$ids})")->delete();
		if($res!=false){
			$return=array(
				'code'=>10000,
				'msg'=>'success'
				);
		}else{
			$return=array(
				'code'=>10001,
				'msg'=>'删除失败'
				);
		}
		$this->ajaxReturn($return);
	}
	public function del(){
		$id = I('get.id');
		$res = D('Group')->where(['id'=>$id])->delete();
		
		

		if ($res!==false) {
			//将这个分组下的机台和人员的group_id都改为0
			$manager = D('Manager')->where(['group_id'=>$id])->save(['group_id'=>0]);
			$equipment = D('Equipment')->where(['group_id'=>$id])->save(['group_id'=>0]);
			$this->redirect('Admin/Group/index');
		}else{
			$this->error('删除失败,请稍后再试');
		}
	}

	public function edit(){
		if (IS_POST) {
			$res = I('post.');

			// dump($res);die;
			$id = $res['group_id'];
			$e = implode(',',$res['equipment_id']);
			$m = implode(',',$res['manager_ids']);
			
			//如果将所有的ids全部取消勾选的话 把manager_ids 和equipment_ids对应的group_id全部设为0
			if (!$res['equipment_id']) {
				D('Equipment')->where(['group_id'=>$id])->save(['group_id'=>0]);

			}
			if (!$res['manager_ids']) {
				D('Manager')->where(['group_id'=>$id])->save(['group_id'=>0]);
			}
			$kong_managaer = D('Manager')->where(['group_id'=>$id])->select();
			$kong_equipment = D('Equipment')->where(['group_id'=>$id])->select();
			
			
			//获取出来这个group原本的机台ids集合和人员ids集合
			$group = D('Group')->where(['id'=>$id])->find();
			$manager_id = D('Manager')->where(['group_id'=>$id])->getField('id',true);
			$group['manager_ids'] = implode(',',$manager_id);
			$equipment_id = D('Equipment')->where(['group_id'=>$id])->getField('id',true);
			$group['equipment_ids'] = implode(',',$equipment_id);
			//如果修改这个群组为空之后再分配进去东西的话
			if (!$manager_id && $m) {
				
				D('Manager')->where("id in ({$m})")->save(['group_id'=>$id]);
			}
			if (!$equipment_id && $e) {
				D('Equipment')->where("id in ({$e})")->save(['group_id'=>$id]);
			}
			//接收到的ids
			//查询出原来的id和获取到id相同的
			$bubian_manager = array_intersect($manager_id,$res['manager_ids']);//不需要修改group_id的
			$x = array_diff($manager_id,$bubian_manager);//查出他们中不同的id(需要把group_id变为0的id)
			$butong_manager = array_diff($res['manager_ids'],$manager_id);//获取到的是新增的人员 将这个人员id的group_id修改
			$bubian_ids = implode(',',$bubian_manager);
			$quxiao_ids = implode(',',$x);
			$xinzeng_ids = implode(',',$butong_manager);
			//用来防止报错
			if ($xinzeng_ids) {
				D('Manager')->where("id in ({$xinzeng_ids})")->save(['group_id'=>$id]);
			}
			if ($quxiao_ids) {
				D('Manager')->where("id in ({$quxiao_ids})")->save(['group_id'=>0]);
			}

			//用来判断是否存在值
			//(人员分配)
			//(机台分配)
			$bubian_equipment = array_intersect($equipment_id,$res['equipment_id']);//不需要修改group_id的
			$y = array_diff($equipment_id,$bubian_equipment) ;//查出他们中不同的id(需要把group_id变为0的id)
			$butong_equipment = array_diff($res['equipment_id'],$equipment_id);//获取到的新增的机台 将这个机台id的group_id修改
			$e_bubian_ids = implode(',',$bubian_manager);
			$e_quxiao_ids = implode(',',$y);
			$e_xinzeng_ids = implode(',',$butong_equipment);
			if ($e_xinzeng_ids) {
				D('Equipment')->where("id in ({$e_xinzeng_ids})")->save(['group_id'=>$id]);
			}
			if ($e_quxiao_ids) {
				D('Equipment')->where("id in ({$e_quxiao_ids})")->save(['group_id'=>0]);
			}

			//获取到的
			// $manager_ids = implode(',',$res['manager_ids']);//29.30
			// $equipment_ids = implode(',',$res['equipment_id']);//1,7,8,9
			// $equipment = D('Equipment')->where("id in ({$equipment_ids})")->save(['group_id'=>$id]);
			// $manager = D('Manager')->where("id in ({$manager_ids})")->save(['group_id'=>$id]);

			//修改名字
			$group_name = $res['group_name'];
			$up = D('Group')->where(['id'=>$id])->save(['group_name'=>$group_name]);
			if ($up !== false) {
				$this->redirect('Admin/Group/index');
			}else{

				$this->error('修改失败');
			}

		}else{
			//显示页面的方法
			$group_id = I('get.id');
			$group = D('Group')->where(['id'=>$group_id])->find();

			$manager_id = D('Manager')->where(['group_id'=>$group_id])->getField('id',true);
			$group['manager_ids'] = implode(',',$manager_id);
			$equipment_id = D('Equipment')->where(['group_id'=>$group_id])->getField('id',true);
			$group['equipment_ids'] = implode(',',$equipment_id);
			$this->assign('group',$group);
			// dump($moren);die;
			
			$role_id = session('manager_info.role_id');
			$id = session('manager_info.id');

			// if ($role_id !=3 && $role_id !=5 ) {
			// 	//普通管理员
				
				
			// 	$user = D('Manager')->where(['id'=>$id])->find();
			// 	$pid = $user['pid'];
			// 	$sup_manager = D('Manager')->where(['pid'=>$pid])->getField('id',true);//28,29,30,32
			// 	$manager_ids = implode(',',$sup_manager);
			// 	$equipment = D('Equipment')->where("equipment_pid in ({$manager_ids}) or equipment_pid = $pid")->select();
			// 	$manager = D('Manager')->where(['pid'=>$pid])->select();



			// }else{
				//超级管理员
				$manager_id = D('Manager')->where(['pid'=>$id])->getField('id',true);

				$manager_ids = implode(',',$manager_id);
				//$equipment = D('Equipment')->where("((equipment_pid in ({$manager_ids}) or equipment_pid = $id) && group_id = 0) or group_id = $group_id")->select();
				$equipment = D('Equipment')->where("pid = $id && group_id = 0 or group_id = $group_id")->select();
				$manager = D('Manager')->where("(pid = $id && group_id = 0) or group_id = $group_id")->select();
				// dump($manager);die;
			// }

			
			$this->assign('equipment',$equipment);
			$this->assign('manager',$manager);
			$this->display();



		}
	}


	public function detail(){
				/* 时间戳 */
		 //    $tomorrow = strtotime( date("Y-m-d",strtotime("+1 day")) ); //明天
		 //    $today = strtotime( date("Y-m-d") ); //今天   
		 //    $yesterday = strtotime( date("Y-m-d",strtotime("-1 day")) ); //昨天
		 //    //本月月初时间戳
		 //    $month_start=mktime(0, 0 , 0,date("m"),1,date("Y"));

		 //    //上月月初时间戳、上月月未时间戳
		 //    $lastmonth_start=mktime(0,0,0,date('m')-1,1,date('Y'));
		 //    $lastmonth_end=mktime(0,0,0,date('m'),1,date('Y'))-24*3600;
			// // select coutn(ID) from T group by trunc (to_char(time,'hh24') / 4)
			// $group_id = I('get.id');
			// $group = D('group')->where(['id'=>$group_id])->find();
			// $equipment_ids = $group['equipment_ids'];
			// //用于遍历表单中的数据
		 //    $equipment_names = D('Equipment')->where("id in ({$group['equipment_ids']})")->getField('equipment_name',true);
		 //    $group['equipment_names'] = implode($equipment_names,',');
		 //    $manager_names = D('manager')->where("id in ({$group['manager_ids']})")->getField('nickname',true);
		 //    $group['manager_names'] = implode($manager_names,',');
		 //    //highcharts
		 //    $id = session('manager_info.id');
		 //    //查询这个群组所有的机台运行情况总和
		 //    $x = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids})")->select();
		 //    // dump($x);die;
		 //    $realtime_today_offline_m = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 0 hour) and log_date < DATE_ADD(curdate(),INTERVAL 12 hour)")->select();//查询出当天上午(线下)
		 //    $realtime_today_online_m = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 0 hour) and log_date < DATE_ADD(curdate(),INTERVAL 12 hour)")->select();//查询出当天上午(线上)
		 //    $realtime_today_offline_e = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 12 hour) and log_date < DATE_ADD(curdate(),INTERVAL 24 hour)")->select();//查询出当天下午(线下)
		 //    $realtime_today_online_e = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 12 hour) and log_date < DATE_ADD(curdate(),INTERVAL 24 hour)")->select();//查询出当天下午(线上)
		 //    // $realtime_today_offline_18 = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 18 hour) and log_date < DATE_ADD(curdate(),INTERVAL 22 hour)")->select();//查询出当天18点之后的22点之前的(线下)
		 //    // $realtime_today_online_18 = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 18 hour) and log_date < DATE_ADD(curdate(),INTERVAL 22 hour)")->select();//查询出当天18点之后的22点之前的(线上)
		 //    // $realtime_today_offline_22 = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 22 hour) and log_date < DATE_ADD(curdate(),INTERVAL 2 hour)")->select();//查询出当天22点之后的2点之前的(线下)
		 //    // $realtime_today_online_22 = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 22 hour) and log_date < DATE_ADD(curdate(),INTERVAL 2 hour)")->select();//查询出当天22点之后的2点之前的(线上)
		 //    // $realtime_today_offline_2 = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 2 hour) and log_date < DATE_ADD(curdate(),INTERVAL 6 hour)")->select();//查询出当天2点之后的6点之前的(线下)
		 //    // $realtime_today_online_2 = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 2 hour) and log_date < DATE_ADD(curdate(),INTERVAL 6 hour)")->select();//查询出当天2点之后的6点之前的(线上)
		 //    // $realtime_today_offline_6 = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 6 hour) and log_date < DATE_ADD(curdate(),INTERVAL 10 hour)")->select();//查询出当天6点之后的10点之前的(线下)
		 //    // $realtime_today_online_6 = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids}) and log_date >= DATE_ADD(curdate(),INTERVAL 6 hour) and log_date < DATE_ADD(curdate(),INTERVAL 10 hour)")->select();//查询出当天6点之后的10点之前的(线上)
		 //   	//
		 //    /*  ----------------------------------------------------------------------------------------------------------------------------------------------------*/

		 //    //遍历00:00  -   12:00的	线上线下模式总和    
		 //    $run_count_today_online_m = array();//线上运行总次数
		 //    $unfinish_count_today_online_m = array();//线上运行失败总次数
		 //    $free_game_count_today_online_m = array();//线上免费游戏总次数
		 //    $income_count_today_online_m = array();//线上收入
		 //    $gift_out_count_today_online_m = array();//线上礼物掉落次数
		 //    $ticket_out_count_today_online_m = array();//线上积分奖卷掉落数量
		 //    $log_date_online_m = array();
		 //    foreach ($realtime_today_online_m as $key => $value) {
		 //    	$run_count_today_online_m[] = $value['run_count_today'];
		 //    	$unfinish_count_today_online_m[] = $value['unfinish_count_today'];
		 //    	$income_count_today_online_m[] = $value['income_count_today'];
		 //    	$gift_out_count_today_online_m[] = $value['gift_out_count_today'];
		 //    	$ticket_out_count_today_online_m[] = $value['ticket_out_count_today'];
		 //    	$free_game_count_today_online_m[] = $value['free_game_count_today'];
		 //    	$log_date_online_m[] = strtotime($value['log_date']);
		 //    }
		 //    // dump($run_count_today_online_m);die;
		 //    $run_count_today_offline_m = array();//线下运行总次数
		 //    $unfinish_count_today_offline_m = array();//线下运行失败总次数
		 //    $free_game_count_today_offline_m = array();//线下免费游戏总次数
		 //    $income_count_today_offline_m = array();//线下收入
		 //    $gift_out_count_today_offline_m = array();//线下礼物掉落次数
		 //    $ticket_out_count_today_offline_m = array();//线下积分奖卷掉落数量mp
		 //    $log_date_m = array();
		    
		 //    foreach ($realtime_today_offline_m as $key => $value) {
		 //    	$run_count_today_offline_m[] = $value['run_count_today'];
		 //    	$unfinish_count_today_offline_m[] = $value['unfinish_count_today'];
		 //    	$income_count_today_offline_m[] = $value['income_count_today'];
		 //    	$gift_out_count_today_offline_m[] = $value['gift_out_count_today'];
		 //    	$ticket_out_count_today_offline_m[] = $value['ticket_out_count_today'];
		 //    	$free_game_count_today_offline_m[] = $value['free_game_count_today'];
		 //    	$log_date_offline_m[] = strtotime($value['log_date']);
		 //    }
		 //    //计算所需的值
		    
		 //    $today_online_m = array_sum($run_count_today_online_m);//222   这个时间段线上模式的运行总次数  上午线上模式
		 //    $today_offline_m = array_sum($run_count_today_offline_m);//217   这个时间段线下模式运行的总次数  上午线下模式 
		 //    $today_m = $today_online_m + $today_offline_m;//439  上午运行次数总和
		 //    $unfinish_online_m = array_sum($unfinish_count_today_online_m);//上午线上模式失败次数
		 //    $unfinish_offline_m = array_sum($unfinish_count_today_offline_m);//上午线下模式失败次数
		 //    $unfinish_total_m = $unfinish_online_m + $unfinish_offline_m;//求出上午的失败总次数
		 //    $free_game_online_m = array_sum($free_game_count_today_online_m);//求出上午线上模式免费游戏的总次数
		 //    $pp_unfinish_m = $unfinish_total_m/$today_m*100;//求出失败次数占上午总次数的百分比
		 //   	$gift_online_m = array_sum($gift_out_count_today_online_m);//上午线上被抓走的娃娃
		 //   	$gift_offline_m = array_sum($gift_out_count_today_offline_m);//上午线下被抓走的娃娃
		 //   	$gift_total_m = $gift_online_m + $gift_offline_m;//被抓走娃娃的总和
		 //   	$pp_gift_m = $gift_total_m/$today_m*100;//所占百分比
		 //   	//上午的概况

			// //遍历12:00   -   24:00     线上线下模式总和
			// $run_count_today_online_e = array();
			// $unfinish_count_today_online_e = array();
			// $free_game_count_today_online_e = array();
			// $income_count_today_online_e = array();
			// $gift_out_count_today_online_e = array();
			// $ticket_out_count_today_online_e = array();
			// $log_date_online_e = array();
			// foreach ($realtime_today_online_e as $key => $value) {
			// 	$run_count_today_online_e[] = $value['run_count_today'];
		 //    	$unfinish_count_today_online_e[] = $value['unfinish_count_today'];
		 //    	$income_count_today_online_e[] = $value['income_count_today'];
		 //    	$gift_out_count_today_online_e[] = $value['gift_out_count_today'];
		 //    	$ticket_out_count_today_online_e[] = $value['ticket_out_count_today'];
		 //    	$free_game_count_today_online_e[] = $value['free_game_count_today'];
		 //    	$log_date_online_e[] = strtotime($value['log_date']);
			// }

			// $run_count_today_offline_e = array();//线下运行总次数
		 //    $unfinish_count_today_offline_e = array();//线下运行失败总次数
		 //    $free_game_count_today_offline_e = array();//线下免费游戏总次数
		 //    $income_count_today_offline_e = array();//线下收入
		 //    $gift_out_count_today_offline_e = array();//线下礼物掉落次数
		 //    $ticket_out_count_today_offline_e = array();//线下积分奖卷掉落数量mp
		 //    $log_date_e = array();


			// foreach ($realtime_today_offline_e as $key => $value) {
			// 	$run_count_today_offline_e[] = $value['run_count_today'];
		 //    	$unfinish_count_today_offline_e[] = $value['unfinish_count_today'];
		 //    	$income_count_today_offline_e[] = $value['income_count_today'];
		 //    	$gift_out_count_today_offline_e[] = $value['gift_out_count_today'];
		 //    	$ticket_out_count_today_offline_e[] = $value['ticket_out_count_today'];
		 //    	$free_game_count_today_offline_e[] = $value['free_game_count_today'];
		 //    	$log_date_offline_e[] = strtotime($value['log_date']);
			// }
			
			// $today_online_e = array_sum($run_count_today_online_e);//222   这个时间段线上模式的运行总次数  下午线上模式
		 //    $today_offline_e = array_sum($run_count_today_offline_e);//217   这个时间段线下模式运行的总次数  下午线下模式 
		 //    $today_e = $today_online_e + $today_offline_e;//439  下午运行次数总和
		 //    $unfinish_online_e = array_sum($unfinish_count_today_online_e);//下午线上模式失败次数
		 //    $unfinish_offline_e = array_sum($unfinish_count_today_offline_e);//下午线下模式失败次数
		 //    $unfinish_total_e = $unfinish_online_e + $unfinish_offline_e;//求出下午的失败总次数
		 //    $free_game_online_e = array_sum($free_game_count_today_online_e);//求出下午线上模式免费游戏的总次数
		 //    $pp_unfinish_e = $unfinish_total_e/$today_e*100;//求出失败次数占下午总次数的百分比
		 //   	$gift_online_e = array_sum($gift_out_count_today_online_e);//下午线上被抓走的娃娃
		 //   	$gift_offline_e = array_sum($gift_out_count_today_offline_e);//下午线下被抓走的娃娃
		 //   	$gift_total_e = $gift_online_e + $gift_offline_e;//下午被抓走娃娃的总和
		 //   	$pp_gift_e = $gift_total_e/$today_e*100;//所占百分比



		 //   	$data['free_game_online_m'] = json_encode($free_game_online_m);//上午线上模式免费游戏次数
		 //   	$data['free_game_online_e'] = json_encode($free_game_online_e);//下午线上模式免费游戏次数
		 //   	$data['today_online_m'] = json_encode($today_online_m);//上午线上模式的运行次数
		 //   	$data['today_offline_m'] = json_encode($today_offline_m);//上午线下模式的运行次数
		 //   	$data['today_m'] = json_encode($today_m); //上午运行次数总和
		 //   	$data['today_online_e'] = json_encode($today_online_e);//下午线上模式的运行次数
		 //   	$data['today_offline_e'] = json_encode($today_offline_e);//下午线下模式的运行次数
		 //   	$data['today_e'] = json_encode($today_e); //下午运行次数总和
		 //   	$data['unfinish_online_m'] = json_encode($unfinish_online_m);//上午线上运行失败次数总和
		 //   	$data['unfinish_offline_m'] = json_encode($unfinish_offline_m);//上午线下运行失败次数总和
		 //   	$data['pp_unfinish_m'] = json_encode($pp_unfinish_m);//上午失败次数占上午总运行次数百分比
		 //   	$data['unfinish_online_e'] = json_encode($unfinish_online_e);//下午线上运行失败次数总和
		 //   	$data['unfinish_offline_e'] = json_encode($unfinish_offline_e);//下午线下运行失败次数总和
		 //   	$data['pp_unfinish_e'] = json_encode($pp_unfinish_e);//下午失败次数占下午总运行次数百分比
		 //   	$data['gift_online_m'] = json_encode($gift_online_m);//上午线上被抓走的娃娃
		 //   	$data['gift_offline_m'] = json_encode($gift_offline_m);//上午线下被抓走的娃娃
		 //   	$data['gift_total_m'] = json_encode($gift_total_m);//上午被抓走的娃娃总和
		 //   	$data['pp_gift_m'] = json_encode($pp_gift_m);//上午被抓走娃娃所占运行上午运行总数的百分比
		 //   	$data['gift_online_e'] = json_encode($gift_online_e);//下午线上被抓走的娃娃
		 //   	$data['gift_offline_e'] = json_encode($gift_offline_e);//下午线下被抓走的娃娃
		 //   	$data['gift_total_e'] = json_encode($gift_total_e);//下午被抓走的娃娃总和
		 //   	$data['pp_gift_e'] = json_encode($pp_gift_e);//下午被抓走娃娃所占运行上午运行总数的百分比
		 //   	$data['today_online'] = json_encode($today_online_m+$today_online_e);//线上模式总体的机器运行次数
		 //   	$data['today_offline'] = json_encode($today_offline_m+$today_offline_e);//线下模式总体的机器运行次数


		 //   	// dump($data);die;
		 


		 //    	//              ---------------------------------------------------------
		
		   	




		 //    $data['pp_unfinish_count_total'] = json_encode(array_sum($unfinish_count_total)/$total*100);//运行失败占总百分比
		 // 	$data['pp_gift_out_count_total'] = json_encode(array_sum($gift_out_count_total)/$total*100);//掉落礼物占总运行次数的百分比	 	
		 	


			// //查询该机台的基本信息
			
			// //根据goods_id 查询线上销售额
			// $total = D('Total') -> where(['manager_id' => $id]) -> order('month asc') -> select();
			
			// $arr2 = array_map('array_shift',$total);
			
			// foreach ($total as $key => $value) {
			// 	$arr[] = $value['income']+$value['offline']-$value['pay'];

			// }	
			// $income = array();
			// $pay = array();
			// $offline = array();
			// foreach($total as $k => $v){
			// 	//插件中要求的数据必须是数字类型的
			// 	$income[] = floatval($v['income']);//遍历数据库线上收入字段的值
				
			// }
			// foreach ($total as $k => $v) {//遍历数据库支出字段的值
			// 	$pay[] = floatval($v['pay']);
			// }
			// foreach ($total as $k => $v) {//遍历数据库线下收入字段的值
			// 	$offline[] = floatval($v['offline']);
			// }
			// $money = array_sum($income)+array_sum($offline)+array_sum($pay);//用于计算的总和 不需要转换成json
			// $data['pp_income'] = json_encode(array_sum($income)/$money*100);//线上百分比
			// $data['pp_offline'] = json_encode(array_sum($offline)/$money*100);//线下百分比
			// $data['pp_pay'] =json_encode(array_sum($pay)/$money*100);//支出百分比
			// $data['arr'] = json_encode($arr);//利润
			// $data['income'] = json_encode($income);//线上收入
			// $data['pay'] = json_encode($pay);//支出
			// $data['offline'] = json_encode($offline);//线下收入
			// // dump($data);die;
			// // $this -> assign('pp_income', json_encode($data['pp_income']));
			// $this -> assign('data',$data);




		 //    $this->assign('group',$group);//遍历到表单中的数据
			// $this->display();
			
			$group_id = I('get.id');
			$group = D('Group')->where(['id'=>$group_id])->find();
			
			$equipment_ids = D('Equipment')->where(['group_id'=>$group['id']])->getField('id',true);
			$equipment_ids = implode(',',$equipment_ids);
			$group['equipment_names'] = implode(',',D('Equipment')->where(['group_id'=>$group_id])->getField('name',true));
			$group['manager_names'] = implode(',',D('Manager')->where(['group_id'=>$group_id])->getField('nickname',true));
			$this->assign('group',$group);
			$this->display();
					
			$equipment_names = D('Equipment')->where("id in ({$equipment_ids})")->getField('name',true);
		    $group['equipment_names'] = implode($equipment_names,',');

		    $manager_names = D('Manager')->where(['group_id'=>$group_id])->getField('nickname',true);
		    
		    // dump($manager_names);die;
		    // $manager_names = D('manager')->where("id in ({$group['manager_ids']})")->getField('nickname',true);
		    $group['manager_names'] = implode($manager_names,',');
		  	
			$realtime_offline = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids})")->select();
			// dump($realtime_offline);die;
			// dump($realtime_offline);die;
		 	$totay_realtime_offline_run_count = array();//今日线下模式总运行次数
		 	$yesterday_realtime_offline_run_count = array();//昨日线下模式总运行次数
		 	$totay_realtime_offline_unfinish = array();//今日线下模式总失败次数
		 	$yesterday_realtime_offline_unfinish = array();//昨日线下模式总失败次数
		 	$today_realtime_offline_income_count = array();//今日线下模式总收入 (投币量)
		 	$yesterday_realtime_offline_income_count = array();//昨日线下模式总收入 (投币量)
		 	$today_realtime_offline_gift_out_count = array();//今日线下模式礼品掉落数量
		 	$yesterday_realtime_offline_gift_out_count = array();//昨日线下模式礼品掉落数量
		 	$today_realtime_offline_ticket_out_count = array();//今日线下模式彩票出奖数量
		 	$yesterday_realtime_offline_ticket_out_count = array();//昨日线下模式彩票出奖数量
		 	$today_realtime_offline_free_game_count = array();//今日的线下模式免费游戏次数
		 	$yesterday_realtime_offline_free_game_count = array();//昨日的线下模式免费游戏次数
		 	foreach ($realtime_offline as $key => $value) {
		 		$today_realtime_offline_run_count[] = $value['run_count_today'];
		 		$yesterday_realtime_offline_run_count[] = $value['run_count_yesterday'];
		 		$today_realtime_offline_unfinish[] = $value['unfinish_count_today'];
		 		$yesterday_realtime_offline_unfinish[] = $value['unfinish_count_yesterday'];
		 		$today_realtime_offline_income_count[] = $value['income_count_today'];
		 		$yesterday_realtime_offline_income_count[] = $value['income_count_yesterday'];
		 		$today_realtime_offline_gift_out_count[] = $value['gift_out_count_today'];
		 		$yesterday_realtime_offline_gift_out_count[] = $value['gift_out_count_yesterday'];
		 		$today_realtime_offline_ticket_out_count[] = $value['ticket_out_count_today'];
		 		$yesterday_realtime_offline_ticket_out_count[] = $value['ticket_gift_count_yesterday'];
		 		$today_realtime_offline_free_game_count[] = $value['free_game_count_today'];
		 		$yesterday_realtime_offline_free_game_count[] = $value['free_game_count_yesterday'];
		 	}
		 	// dump($today_realtime_offline_unfinish);die;
		 	$total['today_realtime_offline_run_count'] = array_sum($today_realtime_offline_run_count);//今日目前线下模式运行总次数
			$total['today_realtime_offline_unfinish'] = array_sum($today_realtime_offline_unfinish);//今日线下模式总失败次数
			
		 	$total['today_realtime_offline_income_count'] = array_sum($today_realtime_offline_income_count);//今日线下模式收入  (只有投币数没有收入)
		 	$total['today_realtime_offline_gift_out_count'] = array_sum($today_realtime_offline_gift_out_count);//今日线下模式礼物掉落数量
		 	$total['today_realtime_offline_ticket_out_count'] = array_sum($today_realtime_offline_ticket_out_count);//今日线下模式彩票出奖数量
		 	$total['today_realtime_offline_free_game_count'] = array_sum($today_realtime_offline_free_game_count);//今日线下模式免费游戏次数
		 	$total['yesterday_realtime_offline_run_count'] = array_sum($yesterday_realtime_offline_run_count);//昨日目前线下模式运行总次数
			$total['yesterday_realtime_offline_unfinish'] = array_sum($yesterday_realtime_offline_unfinish);//昨日线下模式总失败次数
		 	$total['yesterday_realtime_offline_income_count'] = array_sum($yesterday_realtime_offline_income_count);//昨日线下模式收入  (只有投币数没有收入)
		 	$total['yesterday_realtime_offline_gift_out_count'] = array_sum($yesterday_realtime_offline_gift_out_count);//昨日线下模式礼物掉落数量
		 	$total['yesterday_realtime_offline_ticket_out_count'] = array_sum($yesterday_realtime_offline_ticket_out_count);//昨日线下模式彩票出奖数量
		 	$total['yesterday_realtime_offline_ticket_out_count'] = array_sum($yesterday_realtime_offline_free_game_count);//昨日线下模式免费游戏次数
		 		 	//------------------------------------------------------今日and昨日线上数据---------------------------------------------------------------------
		 	$realtime_online = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids})")->select();
		 	// dump($realtime_online);die;
		 	$totay_realtime_online_run_count = array();//今日线上模式总运行次数
		 	$yesterday_realtime_online_run_count = array();//昨日线上模式总运行次数
		 	$totay_realtime_online_unfinish = array();//今日线上模式总失败次数
		 	$yesterday_realtime_online_unfinish = array();//昨日线上模式总失败次数
		 	$today_realtime_online_income_count = array();//今日线上模式总收入 
		 	$yesterday_realtime_online_income_count = array();//昨日线上模式总收入 
		 	$today_realtime_online_gift_out_count = array();//今日线上模式礼品掉落数量
		 	$yesterday_realtime_online_gift_out_count = array();//昨日线上模式礼品掉落数量
		 	$today_realtime_online_ticket_out_count = array();//今日线上模式彩票出奖数量
		 	$yesterday_realtime_online_ticket_out_count = array();//昨日线上模式彩票出奖数量
		 	$today_realtime_online_free_game_count = array();//今日线下模式免费游戏次数
		 	$yesterday_realtime_online_free_game_count = array();//昨日线下模式免费游戏次数
		 	foreach ($realtime_online as $key => $value) {
		 		$today_realtime_online_run_count[] = $value['run_count_today'];
		 		$yesterday_realtime_online_run_count[] = $value['run_count_yesterday'];
		 		$today_realtime_online_unfinish[] = $value['unfinish_count_today'];
		 		$yesterday_realtime_online_unfinish[] = $value['unfinish_count_yesterday'];
		 		$today_realtime_online_income_count[] = $value['income_count_today'];
		 		$yesterday_realtime_online_income_count[] = $value['income_count_yesterday'];
		 		$today_realtime_online_gift_out_count[] = $value['gift_out_count_today'];
		 		$yesterday_realtime_online_gift_out_count[] = $value['gift_out_count_yesterday'];
		 		$today_realtime_online_ticket_out_count[] = $value['ticket_out_count_today'];
		 		$yesterday_realtime_online_ticket_out_count[] = $value['ticket_gift_count_yesterday'];
		 		$today_realtime_online_free_game_count[] = $value['free_game_count_today'];
		 		$yesterday_realtime_online_free_game_count[] = $value['free_game_count_yesterday'];
		 	}
		 	$total['today_realtime_online_run_count'] = array_sum($today_realtime_online_run_count);//今日目前线上模式运行总次数
		 	// dump($today_realtime_online_run_count);die;
			$total['today_realtime_online_unfinish'] = array_sum($today_realtime_online_unfinish);//今日线上模式总失败次数
		 	$total['today_realtime_online_income_count'] = array_sum($today_realtime_online_income_count);//今日线上模式收入  (只有投币数没有收入)
		 	
		 	$total['today_realtime_online_gift_out_count'] = array_sum($today_realtime_online_gift_out_count);//今日线上模式礼物掉落数量
		 	$total['today_realtime_online_ticket_out_count'] = array_sum($today_realtime_online_ticket_out_count);//今日线上模式彩票出奖数量
		 	$total['today_realtime_online_free_game_count'] = array_sum($today_realtime_online_free_game_count);//今日线上模式免费游戏次数
		 	$total['yesterday_realtime_online_run_count'] = array_sum($yesterday_realtime_online_run_count);//昨日线上模式运行总次数
			$total['yesterday_realtime_online_unfinish'] = array_sum($yesterday_realtime_online_unfinish);//昨日线上模式总失败次数
		 	$total['yesterday_realtime_online_income_count'] = array_sum($yesterday_realtime_online_income_count);//昨日线上模式收入  
		 	// dump($total['yesterday_realtime_online_income_count']);die;
		 	$total['yesterday_realtime_online_gift_out_count'] = array_sum($yesterday_realtime_online_gift_out_count);//昨日线上模式礼物掉落数量
		 	$total['yesterday_realtime_online_ticket_out_count'] = array_sum($yesterday_realtime_online_ticket_out_count);//昨日线上模式彩票出奖数量
		 	$total['yesterday_realtime_online_free_game_count'] = array_sum($yesterday_realtime_online_free_game_count);//昨日线上模式免费游戏次数
		 	//----------------------------------------------------------------------------------------------------------------------------------------------












		 	//-----------------------------------------------------月数据-------------------------------------------------------------------------
		 	//以下是月统计表的数据*******
		 	
		 	
		 	//---------------------------------------------------这个月的线下数据------------------------------------------
		 	$month_realtime_offline = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids})")->select();
		 	$month_realtime_offline_run_count = array();//本月线下模式总运行次数
		 	$month_realtime_offline_unfinish = array();//本月线下模式总失败次数
		 	$month_realtime_offline_income_count = array();//本月显示模式收入  只有投币数量
		 	$month_realtime_offline_gift_out_count = array();//本月线下模式礼物掉落数量
		 	$month_realtime_offline_ticket_out_count = array();//本月线下模式彩票出奖数量
			foreach ($month_realtime_offline as $key => $value) {
				$month_realtime_offline_run_count[] = $value['run_count_month'];
				$month_realtime_offline_unfinish[] = $value['unfinish_count_month'];
		 		$month_realtime_offline_income_count[] = $value['income_count_month'];
		 		$month_realtime_offline_gift_out_count[] = $value['gift_out_count_month'];
		 		$month_realtime_offline_ticket_out_count[] = $value['ticket_out_count_month'];
			}
			$total['month_realtime_offline_run_count'] = array_sum($month_realtime_offline_run_count);//本月目前线下模式运行总次数
			$total['month_realtime_offline_unfinish'] = array_sum($month_realtime_offline_unfinish);//本月线下模式总失败次数
		 	$total['month_realtime_offline_income_count'] = array_sum($month_realtime_offline_income_count);//本月线下模式收入  (只有投币数没有收入)
		 	$total['month_realtime_offline_gift_out_count'] = array_sum($month_realtime_offline_gift_out_count);//本月线下模式礼物掉落数量
		 	$total['month_realtime_offline_ticket_out_count'] = array_sum($month_realtime_offline_ticket_out_count);//线下模式彩票出奖数量
		 	//----------------------------------------------------这个月的线上数据------------------------------------------
		 	$month_realtime_online = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids})")->select();
		 	// dump($month_realtime_online);die;
		 	$month_realtime_online_run_count = array();//本月线上模式总运行次数
		 	$month_realtime_online_unfinish = array();//本月线上模式总失败次数
		 	$month_realtime_online_income_count = array();//本月显示模式收入  只有投币数量
		 	$month_realtime_online_gift_out_count = array();//本月线上模式礼物掉落数量
		 	$month_realtime_online_ticket_out_count = array();//本月线上模式彩票出奖数量
		 	$month_realtime_online_free_game_count = array();//本月线上模式免费游戏次数
			foreach ($month_realtime_online as $key => $value) {
				$month_realtime_online_run_count[] = $value['run_count_month'];
				$month_realtime_online_unfinish[] = $value['unfinish_count_month'];
		 		$month_realtime_online_income_count[] = $value['income_count_month'];
		 		$month_realtime_online_gift_out_count[] = $value['gift_out_count_month'];
		 		$month_realtime_online_ticket_out_count[] = $value['ticket_out_count_month'];
		 		$month_realtime_online_free_game_count[] = $value['free_game_count_month'];
			}
			$total['month_realtime_online_run_count'] = array_sum($month_realtime_online_run_count);//本月目前线上模式运行总次数
			$total['month_realtime_online_unfinish'] = array_sum($month_realtime_online_unfinish);//本月线上模式总失败次数
		 	$total['month_realtime_online_income_count'] = array_sum($month_realtime_online_income_count);//本月线上模式收入  (只有投币数没有收入)
		 	$total['month_realtime_online_gift_out_count'] = array_sum($month_realtime_online_gift_out_count);//本月线上模式礼物掉落数量
		 	$total['month_realtime_online_ticket_out_count'] = array_sum($month_realtime_online_ticket_out_count);//线上模式彩票出奖数量
		 	$total['month_realtime_online_free_game_count'] = array_sum($month_realtime_online_free_game_count);//本月线上模式总免费游戏次数
		 	// dump($total['month_realtime_online_run_count']);die;

		 	//---------------------------------------------------上个月的数据------------------------------------------
		 	//查询出线下月统计表在ids集合中的数据
		 	$month_offline = D('Month_statistics_offline')->where("equipment_id in ({$equipment_ids}) and date_format(log_date,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->select();

		 	$month_offline_run_count = array();//线下模式总运行次数
		 	$month_offline_unfinish = array();//现下模式总失败次数
		 	// $month_offline_free_game_count = array();//显示模式没有免费
		 	$month_offline_income_count = array();//线下收入 只有投币数没有收入
		 	$month_offline_gift_out_count = array();//线下模式礼物掉落数量
		 	$month_offline_ticket_out_count = array();//线下模式彩票出奖数量
		 	foreach ($month_offline as $key => $value) {
		 		$month_offline_run_count[] = $value['run_count'];
		 		$month_offline_unfinish[] = $value['unfinish_count'];
		 		$month_offline_income_count[] = $value['income_count'];
		 		$month_offline_gift_out_count[] = $value['gift_out_count'];
		 		$month_offline_ticket_out_count[] = $value['ticket_out_count'];
		 	}

		 	$total['month_offline_run_count'] = array_sum($month_offline_run_count);//线下模式运行总次数
		 	$total['month_offline_unfinish'] = array_sum($month_offline_unfinish);//线下模式总失败次数
		 	$total['month_offline_income_count'] = array_sum($month_offline_income_count);//线下模式收入  (只有投币数没有收入)
		 	$total['month_offline_gift_out_count'] = array_sum($month_offline_gift_out_count);//现下模式礼物掉落数量
		 	$total['month_offline_ticket_out_count'] = array_sum($month_offline_ticket_out_count);//线下模式彩票出奖数量
		 	//查询出线上月统计表在ids集合中的数据--------------------------------------------------
		 	// $month_online = D('Month_statistics_online')->where(['equipment_pid'])
		 	$month_online = D('Month_statistics_online')->where("equipment_id in ({$equipment_ids}) and date_format(log_date,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->select();
		 	
		 	$month_online_run_count = array();
		 	$month_online_unfinish = array();
		 	$month_online_free_game_count = array();
		 	$month_online_income_count = array();
		 	$month_online_gift_out_count = array();
		 	$month_online_ticket_out_count = array();
		 	foreach ($month_online as $key => $value) {
		 		$month_online_run_count[] = $value['run_count'];
		 		$month_online_unfinish[] = $value['unfinish_count'];
		 		$month_online_free_game_count[] = $value['free_game_count'];
		 		$month_online_income_count[] = $value['income_count'];
		 		$month_online_gift_out_count[] = $value['gift_out_count'];
		 		$month_online_ticket_out_count[] = $value['ticket_out_count'];
		 	}
		 	$total['month_unfinish'] = array_sum($month_online_unfinish)+array_sum($month_offline_unfinish);
		 	$total['month_online_run_count'] = array_sum($month_online_run_count);//线上模式总运行次数
		 	$total['month_online_unfinish']  = array_sum($month_online_unfinish);//线上模式总失败次数
		 	$total['month_online_free_game_count'] = array_sum($month_online_free_game_count);//线上模式免费游戏总次数
		 	$total['month_online_income_count'] = array_sum($month_online_income_count);//线上模式总体收入		 	
		 	$total['month_online_gift_out_count'] = array_sum($month_online_gift_out_count);//线上模式礼物掉落数量
		 	$total['month_online_ticket_out_count'] = array_sum($month_online_ticket_out_count);//线上模式彩票出奖数量






		 	// --------------------------------json格式月度数据----------------------
		 	$data['pp_month_online_unfinish'] = json_encode(round(array_sum($month_online_unfinish)/array_sum($month_online_run_count)*100,2));//上个月失败游戏次数占比
		 	$data['pp_month_online_free_game_count'] = json_encode(round(array_sum($month_online_free_game_count)/array_sum($month_online_run_count)*100,2));//上个月免费游戏次数占比


		 	$data['month_online_run_count'] = json_encode(array_sum($month_online_run_count));//上个月线上模式总运行次数
		 	$data['month_online_unfinish'] = json_encode(array_sum($month_online_unfinish));//上个月线上模式总失败次数
		 	$data['month_online_free_game_count'] = json_encode(array_sum($month_online_free_game_count));//上个月线上模式免费游戏总次数
		 	$data['month_online_income_count'] = json_encode(array_sum($month_online_income_count));//上个月线上模式总体收入
		 	$data['month_online_gift_out_count'] = json_encode(array_sum($month_online_gift_out_count));//上个月线上模式礼物掉落数量
		 	$data['month_online_ticket_out_count'] = json_encode(array_sum($month_online_ticket_out_count));//上个月线上模式彩票出奖数量
		 	// dump($group);die;
		 	//------------------------------月度JSON数据---------------------------------------------------------------------------------
		 	// $zuijin_offline = D('Day_statistics_offline')->where("equipment_id in ({$equipment_ids}) and  date_sub(curdate(), INTERVAL 7 DAY) <= date(`log_date`)")->select();
		 	// $zuijin_online = D('Day_statistics_online')->where("equipment_id in ({$equipment_ids}) and  date_sub(curdate(), INTERVAL 7 DAY) <= date(`log_date`)")->select();
		 	// dump($zuijin_online);
		 	// dump($zuijin_offline);die;
		 	//获取最近七天的日期数据转换为json
		 	$x = array();
		 	for($i = 10; $i > 0; $i--){
    		$x[] =  date('Y-m-d', strtotime('-'.$i.' day'));
			}
			$data['day'] = json_encode($x);//图表下方的时间
			
			//计算出日期相同的数据总和
			$off = D('Day_statistics_offline')->field("log_date,SUM(run_count) offline_count")->where("equipment_id in ({$equipment_ids}) and  date_sub(curdate(), INTERVAL 10 DAY) <= date(`log_date`)")->group("log_date")->select();
			$on = D('Day_statistics_online')->field("log_date,SUM(run_count) online_count")->where("equipment_id in ({$equipment_ids}) and  date_sub(curdate(), INTERVAL 10 DAY) <= date(`log_date`)")->group("log_date")->select();
			foreach ($off as $key => $value) {
				$offline_count[] = intval($value['offline_count']);
			}
			foreach ($on as $key => $value) {
				$online_count[] = intval($value['online_count']);
			}
			$data['offline_count'] = json_encode($offline_count);
			$data['online_count'] = json_encode($online_count);


		 	$group['equipment_names'] = D('Equipment')->where(['group_id'=>$group['id']])->getField('name',true);
		 	$group['equipment_names'] = implode(',',$group['names']);
		 	$group['manager_names'] = D('Manager')->where(['group_id'=>$group['id']])->getField('nickname',true);
		 	$group['manager_names'] = implode(',',$group['manager_names']);
		 
		 	$this->assign('total',$total);
		 	$this->assign('group',$group);
		 	$this->assign('data',$data);
		 	$this->display();
		}
	
	
}