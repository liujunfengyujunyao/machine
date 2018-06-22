<?php
namespace Admin\Controller;
use Think\Controller;
class StatisticsController extends CommonController{

		public function day(){
			$manager = session("manager_info.id");
			 $days = M("equipment_day_statistics")->alias("t1")->field("FROM_UNIXTIME(t1.statistics_date,'%Y%m%d') days,FROM_UNIXTIME(t1.create_time,'%Y%m%d') day,t1.*")->where(['t2.pid'=>$manager])->join("left join equipment as t2 on t2.id = t1.equipment_id")->select();
			 $this->assign("day",$days);
			$this->display();
		}
		public function partner_day(){
			$manager = session("manager_info.id");
			$date = array(
				array('days'=>(date("Ymd",strtotime("-2 day")))),//昨天时间
				);
			 $days = M("equipment_day_statistics")->alias("t1")->field("FROM_UNIXTIME(t1.statistics_date,'%Y%m%d') days,t1.*,t2.pid")->where(['t2.pid'=>$manager])->join("left join equipment as t2 on t2.id = t1.equipment_id")->select();	
			 //$dates = date("Y-m-d H:i:s",$days['statistics_date']);
			 //dump($dates);die;
			 $days2 = $date;
			 foreach ($days2 as $key => &$value) {
			 	foreach ($days as $v) {
			 		if($value['days'] == $v['days']){
			 			$value['hardware_failure_time']+=$v['hardware_failure_time'];//保修次数
			 			$value['silver_game_times']+=$v['silver_game_times'];
			 			$value['gold_game_times']+=$v['gold_game_times'];
			 			$value['run_count']+=$v['run_count'];
			 			$value['success_number']+=$v['success_number'];
			 			$value['fail_number']+=$v['fail_number'];
			 			$value['silver_game_win_times']+=$v['silver_game_win_times'];
			 			$value['silver_game_lose_times']+=$v['silver_game_lose_times'];
			 			$value['gold_game_win_times']+=$v['gold_game_win_times'];
			 			$value['gold_game_lose_times']+=$v['gold_game_lose_times'];
			 			$value['gift_out_count']+=$v['gift_out_count'];
			 			$value['income_count']+=$v['income_count'];
			 			$value['pid'] = $v['pid'];
			 			$value['create_time'] = $v['create_time'];
			 		}
			 	}
			 }
			 $partner_day_statitics = array(
			 		'statistics_date'=>$value['days'],
			 		'hardware_failure_time'=>$value['hardware_failure_time'],
			 		'silver_game_times'=>$value['silver_game_times'],
			 		'gold_game_times'=>$value['gold_game_times'],
			 		'run_count'=>$value['run_count'],
			 		'success_number'=>$value['success_number'],
			 		'fail_number'=>$value['fail_number'],
			 		'silver_game_win_times'=>$value['silver_game_win_times'],
			 		'silver_game_lose_times'=>$value['silver_game_lose_times'],
			 		'gold_game_win_times'=>$value['gold_game_win_times'],
			 		'gold_game_lose_times'=>$value['gold_game_lose_times'],
			 		'gift_out_count'=>$value['gift_out_count'],
			 		'income_count'=>$value['income_count'],
			 		'pid'=>$value['pid'],
			 		'create_time'=>time(),
			 	);
			//if(time()==$value['create_time']){
				M("partner_day_statistics")->add($partner_day_statitics);
			//}			
			 $partner_day = M("partner_day_statistics")->alias("t1")->field("FROM_UNIXTIME(t1.create_time,'%Y%m%d') day,t1.*")->where(['t1.pid'=>$manager])->select();
			 $this->assign("day",$partner_day);
			$this->display();
		}
		public function month(){
			$manager = session("manager_info.id");
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
	     	$begin_time = date('Ym',strtotime('-1 month'));//只有年月
	        $b_time = strtotime($lastStartDay);//上个月的月初时间戳
	        $e_time = strtotime($lastEndDay)+(60*60*24-1);//上个月的月末时间戳
			$month = M("equipment_month_statistics")->alias("t1")->field("FROM_UNIXTIME(t1.statistics_date,'%Y%m%d') days,t1.*,t2.pid")->where(['t2.pid'=>$manager])->where("t1.statistics_date between $b_time and $e_time")->join("left join equipment as t2 on t2.id = t1.equipment_id")->select();
			//dump($month);die;
			foreach ($month as $key => $v) {
						$Luna['hardware_failure_time']+=$v['hardware_failure_time'];//保修次数
			 			$Luna['silver_game_times']+=$v['silver_game_times'];
			 			$Luna['gold_game_times']+=$v['gold_game_times'];
			 			$Luna['run_count']+=$v['run_count'];
			 			$Luna['success_number']+=$v['success_number'];
			 			$Luna['fail_number']+=$v['fail_number'];
			 			$Luna['silver_game_win_times']+=$v['silver_game_win_times'];
			 			$Luna['silver_game_lose_times']+=$v['silver_game_lose_times'];
			 			$Luna['gold_game_win_times']+=$v['gold_game_win_times'];
			 			$Luna['gold_game_lose_times']+=$v['gold_game_lose_times'];
			 			$Luna['gift_out_count']+=$v['gift_out_count'];
			 			$Luna['income_count']+=$v['income_count'];
			 			$Luna['pid'] = $v['pid'];
			 			$Luna['create_time'] = $v['create_time'];
			}
			//dump($Luna);die;
			$monthlast = array(
					'statistics_date'=>$begin_time,
			 		'hardware_failure_time'=>$Luna['hardware_failure_time'],
			 		'silver_game_times'=>$Luna['silver_game_times'],
			 		'gold_game_times'=>$Luna['gold_game_times'],
			 		'run_count'=>$Luna['run_count'],
			 		'success_number'=>$Luna['success_number'],
			 		'fail_number'=>$Luna['fail_number'],
			 		'silver_game_win_times'=>$Luna['silver_game_win_times'],
			 		'silver_game_lose_times'=>$Luna['silver_game_lose_times'],
			 		'gold_game_win_times'=>$Luna['gold_game_win_times'],
			 		'gold_game_lose_times'=>$Luna['gold_game_lose_times'],
			 		'gift_out_count'=>$Luna['gift_out_count'],
			 		'income_count'=>$Luna['income_count'],
			 		'pid'=>$Luna['pid'],
			 		'create_time'=>time(),
				);

			//if(time() == $Luna['create_time']){
				M("partner_month_statitiscs")->add($monthlast);
			//}
			$partner_month = M("partner_month_statitics")->alias("t1")->field("FROM_UNIXTIME(t1.create_time) monthlast,t1.*")->where(['t1.pid'=>$manager])->select();
			$this->assign("month",$partner_month);
			$this->display();
		}
	// public function rijiaoyue(){
			 // $item =array();
			 // foreach ($days as $k => $v) {
			 // 		if(!isset($item[$v["equipment_id"]])){
			 // 			$item[$v['equipment_id']] = $v;
			 // 		}else{
			 // 			$item[$v['equipment_id']]['hardware_failure_time']+=$v['hardware_failure_time'];
			 // 			$item[$v['equipment_id']]['silver_game_times']+=$v['silver_game_times'];
			 // 			$item[$v['equipment_id']]['gold_game_times']+=$v['gold_game_times'];
			 // 			$item[$v['equipment_id']]['run_count']+=$v['run_count'];
			 // 			$item[$v['equipment_id']]['success_number']+=$v['success_number'];
			 // 			$item[$v['equipment_id']]['fail_number']+=$v['fail_number'];
			 // 			$item[$v['equipment_id']]['silver_game_win_times']+=$v['silver_game_win_times'];
			 // 			$item[$v['equipment_id']]['silver_game_lose_times']+=$v['silver_game_lose_times'];
			 // 			$item[$v['equipment_id']]['gold_game_win_times']+=$v['gold_game_win_times	'];
			 // 			$item[$v['equipment_id']]['gold_game_lose_times']+=$v['gold_game_lose_times'];
			 // 			$item[$v['equipment_id']]['gift_out_count']+=$v['gift_out_count'];
			 // 			$item[$v['equipment_id']]['income_count']+=$v['income_count'];
			 // 		}
			 // }
			 //  dump($item);die;
	// }
	// public function index(){
	// 	$manager = session("manager_info.id");//管理员id
	// 	$equipment = M("equipment")->where(['pid'=>$manager])->getField('id',true);//机台总数
	// 	//dump($equipment);die;
	// 	$equipment_id = implode($equipment,',');
	// 	$date = array(
	// 			array('days'=>date('Ymd',time())),//日期
	// 		);
	// 	$high = M("tbl_game_log")->alias("t1")->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,t1.equipment_id")->where("equipment_id in ({$equipment_id})")->where(['t1.type' => gold])->join("left join equipment as t2 on t2.id = t1.equipment_id")->select();
	// 	//dump($high);
	// 	//$ad = array_unique($high['equipment_id']);

	// 	// $m = 0;
	// 	// foreach ($high as $key => $value) {
	// 	// 	$a[$key]['days'] = $value['days'];
	// 	// 	 $a[$key]['equipment_id'] = $value['equipment_id'];
	// 	// }
	// 	 dump($high);die;
	// 	die;
	// }

	// public function index(){
	// 	if(IS_POST){

	// 	}else{
		
	// 		$manager = session("manager_info.id");
	// 		//$manager_name = session("manager_info.username");
	// 		//dump($manager_name);die;
	// 		$equipment = M('equipment')->where(['pid'=>$manager])->getField('id',true);
	// 		$count = count($equipment);//机台总数
	// 		//机台游戏成功-失败-全部
	// 		$high = M("tbl_game_log")->alias("t1")->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,count(t1.id) count")->where("t2.pid = $manager")->join("left join equipment as t2 on t2.id = t1.equipment_id")->Group('days')->select();

	// 		$get = M('tbl_game_log')->alias('t1')->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,count(t1.id) count")->where("t2.pid = $manager")->where(['got_gift'=>1])->join("left join equipment as t2 on t2.id = t1.equipment_id")->Group('days')->select();

	// 	 	$notget = M('tbl_game_log')->alias('t1')->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,count(t1.id) count")->where("t2.pid = $manager")->where(['got_gift'=>0])->join("left join equipment as t2 on t2.id = t1.equipment_id")->Group('days')->select();

	// 		$date = array(
	// 				array('days'=>date('Ymd',time())),
	// 			);
	// 		$date6 = array(
	// 				array('days'=>date('Y-m-d',time())),
	// 			);
	// 		//dump($date);die;
	// 		foreach ($date6 as $key => $value){
	// 			$day[] = $value['days'];
	// 		}
	// 		$days = implode($day,',');
	// 		//dump($days);die;
	// 	$high2=$date;
	// 	//dump($high2);die;
	//  	foreach($high2 as $k=>&$Total){
	//  		foreach($high as $val){
	//  			if($Total['days'] == $val['days']){
	//  				$Total['count']=$val['count'];
	//  			}	
	//  		}
	//  		if(!$Total['count']){
	//  			$Total['count']= '0';
	//  		}
	//  	}
	//  	$get2 = $date;
	//  	foreach ($get2 as $k => &$success) {
	//  		foreach ($get as $val) {
	//  			if($success['days'] == $val['days']){
	//  				$success['count'] = $val['count'];
	//  			}
	//  		}
	//  		if(!$success['count']){
	//  			$success['count'] = "0";
	//  		}
	//  	}
	//  	$notget2 = $date;
	//  	foreach ($notget2 as $k => &$fail) {
	//  		foreach ($notget as $d =>$val) {
	//  			if($fail['days'] == $val['days']){
	//  				$fail['count'] = $val['count'];
	//  			}
	//  		}
	//  		if(!$fail['count']){
	//  			$fail['count'] = "0";
	//  		}
	//  	}
	//  	//dump($fail['count']);die;
	//  	//输出商品
	// 	$data = M("tbl_order")->alias("t1")->field("FROM_UNIXTIME(t1.send_time,'%Y%m%d') days,count(t1.id) count")->where(['t3.pid'=>$manager,'t1.status'=>1])->join("left join tbl_game_log as t2 on t2.id = t1.log_id")->join("left join equipment as t3 on t3.id = t2.equipment_id")->Group('days')->select();
	// 	//dump($data);die;
	// 	$data2 = $date;
	// 	foreach ($data2 as $k => &$merchant) {
	// 		foreach ($data as $key => $value) {
	// 			if($merchant['days'] == $value['days']){
	// 				$merchant['count'] = $value['count'];
	// 			}
	// 		}
	// 		if(!$merchant['count']){
	// 			$merchant['count'] = "0";
	// 		}
	// 	}
	// 	//dump($data2);
	// 	//收入
	//  $id = implode($equipment,',');
	// $zuijin_online = M('Day_statistics_online')->field("log_date,income_count")->where("equipment_id = 2")->select();
	// $date2 = array(
	// 	array('days'=>date('Y-m-d',time())),
	// 	);
	// $zuijin_online2 = $date2;
	// foreach ($zuijin_online2 as $k => &$income) {
	// 		foreach ($zuijin_online as $key => $value) {
	// 			if($income['days'] == $value['log_date']){
	// 				$income['income_count'] = $value['income_count'];
	// 			}
	// 		}
	// 		if(!$income['income_count']){
	// 			$income["income_count"] = "0";
	// 		}
	// 	}
	
	// //dump($zuijin_online2);die;
	// foreach ($zuijin_online2 as $k => &$income) {
	// 	foreach ($zuijin_online as $key => $value) {
	// 		if($income['days'] == $value['log_date']){
	// 			$income['income_count'] = $value['income_count'];
	// 		}
	// 	}
	// 	if(!$income['income_count']){
	// 		$income["income_count"] = "0";
	// 	}
	// }
	// //dump($zuijin_online2);die;
	// //用户
	// $Customer = M("tbl_game_log")->alias("t1")->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,t1.userid")->where(['t2.pid'=>$manager])->join("left join equipment as t2 on t2.id = t1.equipment_id")->select();
	// //dump($Customer);
	// $Customer2 = $date;
	// foreach ($Customer2 as $k => $user) {
	// 		foreach ($Customer as $key => $value) {
	// 			if($user['days'] == $value['days']){
	// 				$user['userid'] += count($value['userid']);
	// 			}
	// 		}
	// 		if(!$user['userid']){
	// 			$user['userid'] = "0";
	// 		}
	// }	
	// 				$statistics =array(
	// 					 'day_count' => $days,//日期
	// 					 'manager_id' => $manager,//管理员id
	// 					 'equipment_id'=> $count,//机台
	// 					 'success_number' => $success['count'],//游戏成功
	// 					 'fail_numbr' => $fail['count'],//游戏失败
	// 					 'run_count_total' => $Total['count'],//机台游戏总数
	// 					 'gift_out_count_total' => $merchant['count'],//商品输出
	// 					 'all_user_id_count' => $user['userid'],//用户
	// 					 'income_day_count' => $income['income_count'],//收入
	// 					 'create_time' => date("Y-m-d H:i:s",time()),//统计时间
	// 					);

	// 				$year = date("Y");$month = date("m");$day = date("d");
	// 				$end= mktime(10,20,59,$month,$day,$year);//当天结束时间戳
	// 				$times = date("Y/m/d H:i:s",time());
	// 				$date9 = date("Y/m/d H:i:s",$end);
 //                    if($times == $date9){
	// 					M("day_count_data")->add($statistics);
	// 				}
	// 				$weather = M('day_count_data')->where(['manager_id'=>$manager])->select();
	// 				$this->assign('weather',$weather);
	// 				$this->display();	
	// 	}
	// }

	// 	public function month(){
	// 			$date6 = array(
	// 				array('days'=>date('Y-m-d',time())),
	// 			);//当前时间
	// 			$date2 = array(
	// 		 		array('month'=>date('Ymd',strtotime(date('Y-m-1',strtotime('next month')).'-1 day'))),
	// 		 	);//月份的最后一天
	// 			foreach ($date6 as $key => $value){
	// 				$day[] = $value['days'];
	// 			}

	// 		 $days = implode($day,',');			
	// 		$manager = session("manager_info.id");
	// 		$equipment = M("equipment")->where(['pid'=>$manager])->getField('id',true);
	// 		$count = count($equipment);//机台总数
	// 		if($date6 == $date2){
	// 			$weather = M('day_count_data')->where("MONTH(day_count) = MONTH(CURDATE()) && manager_id = $manager")->order("id asc")->select();
	// 			//dump($weather);die;
	// 			foreach ($weather as $key => $value) {
	// 					$success_number['success_number'] += $value["success_number"];
	// 					$fail_numbr["fail_numbr"] += $value["fail_numbr"];
	// 					$run_count_total['run_count_total'] += $value['run_count_total'];
	// 					$gift_out_count_total['gift_out_count_total'] += $value['gift_out_count_total'];
	// 					$all_user_id_count['all_user_id_count'] += $value['all_user_id_count'];
	// 					$Income_day_count['income_day_count'] += $value['income_day_count'];

	// 			}
	// 			//dump($fail_numbr);die;
	// 			$daymonth = array(
	// 				'month_count' => $days,//月期
	// 				'manager_id' => $manager,//管理员id
	// 				 'equipment_id'=> $count,//机台
	// 				 'success_number' => $success_number['success_number'],//游戏成功
	// 				 'fail_numbr' => $fail_numbr['fail_numbr'],//游戏失败
	// 				 'run_count_total' => $run_count_total['run_count_total'],//机台游戏总数
	// 				 'gift_out_count_total' => $gift_out_count_total['gift_out_count_total'],//商品输出
	// 				 'all_user_id_count' => $all_user_id_count['all_user_id_count'],//用户
	// 				 'income_day_count' => $Income_day_count['income_day_count'],//收入
	// 				 'create_time' => date("Y-m-d H:i:s",time()),//统计时间
	// 			);
	// 		 	M("month_count_data")->add($daymonth);
	// 		}else{
	// 			$month_month = M("month_count_data")->select();
	// 			$this->assign("month",$month_month);
	// 			$this->display();
	// 		}				
	// 	}
	// 	public function year(){
	// 			$date6 = array(
	// 				array('days'=>date('Y-m-d',time())),
	// 			);//当前时间
	// 			// $date2 = array(
	// 		 // 		array('month'=>date('Ymd',strtotime(date('Y-m-1',strtotime('next month')).'-1 day'))),
	// 		 // 	);//月份的最后一天
	// 		 	$year=date("Y",time());
	// 			$end=$year."-12-31";//年的最后一天
	// 			foreach ($date6 as $key => $value){
	// 				$day[] = $value['days'];
	// 			}
	// 		 $days = implode($day,',');
	// 		$manager = session("manager_info.id");
	// 		$equipment = M("equipment")->where(['pid'=>$manager])->getField('id',true);
	// 		$count = count($equipment);//机台总数
	// 		if($date6 == $end){
	// 			$weather = M('month_count_data')->where("YEAR(month_count) = YEAR(CURDATE()) && manager_id = $manager")->order("id asc")->select();
	// 			//dump($weather);die;
	// 			foreach ($weather as $key => $value) {
	// 					$success_number['success_number'] += $value["success_number"];
	// 					$fail_numbr["fail_numbr"] += $value["fail_numbr"];
	// 					$run_count_total['run_count_total'] += $value['run_count_total'];
	// 					$gift_out_count_total['gift_out_count_total'] += $value['gift_out_count_total'];
	// 					$all_user_id_count['all_user_id_count'] += $value['all_user_id_count'];
	// 					$Income_day_count['income_day_count'] += $value['income_day_count'];

	// 			}
	// 			//dump($fail_numbr);die;
	// 			$daymonth = array(
	// 				 'year_count' => $days,//年期
	// 				 'manager_id' => $manager,//管理员id
	// 				 'equipment_id'=> $count,//机台
	// 				 'success_number' => $success_number['success_number'],//游戏成功
	// 				 'fail_numbr' => $fail_numbr['fail_numbr'],//游戏失败
	// 				 'run_count_total' => $run_count_total['run_count_total'],//机台游戏总数
	// 				 'gift_out_count_total' => $gift_out_count_total['gift_out_count_total'],//商品输出
	// 				 'all_user_id_count' => $all_user_id_count['all_user_id_count'],//用户
	// 				 'income_day_count' => $Income_day_count['income_day_count'],//收入
	// 				 'create_time' => date("Y-m-d H:i:s",time()),//统计时间
	// 			);
	// 		 	M("year_count_data")->add($daymonth);
	// 		}else{
	// 			$yearg = M("year_count_data")->where(['manager_id'=>$manager])->select();
	// 			$this->assign("year",$yearg);
	// 			$this->display();
	// 		}	
	// 	}


}

