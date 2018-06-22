<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController{
	public function __construct(){
		parent::__construct();
		if (!session('?manager_info')) {
			$this->redirect('Admin/Login/login');
		}
	}

	public function index(){ 
		$id = session('manager_info.id');
		$today = time();
		$seven = strtotime('-2 days');

		$data = M('tbl_game_log')
		->alias("t1")
		->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,count(t1.id) count")
		->where("t1.end_time between $seven and $today && t2.pid = $id")
		->Group('days')
		->join("left join equipment as t2 on t2.id = t1.equipment_id")
		->select();
	
		// dump($data);die;
		// dump($data);die;
		//查询出游戏记录的总数
		foreach ($data as $key => $value) {
			$count[] = floatval($value['count']);
			foreach ($data as $k => $v) {
				$day[$k] = substr($v['days'],6);
			}
		}
		
		// dump($count);dump($day);die;
		$count = json_encode($count);

		//求出近7天的日期
		// $day = array(
		// 	floatval(date('d',strtotime('-6 days'))),
		// 	floatval(date('d',strtotime('-5 days'))),
		// 	floatval(date('d',strtotime('-4 days'))),
		// 	floatval(date('d',strtotime('-3 days'))),
		// 	floatval(date('d',strtotime('-2 days'))),
		// 	floatval(date('d',strtotime('-1 days'))),
		// 	floatval(date('d',time())),
		// 	);
		
		$day = array(
			
			floatval(date('d',strtotime('-2 days'))),
			floatval(date('d',strtotime('-1 days'))),
			floatval(date('d',time())),
			);

		$day = json_encode($day);
		$this->assign('day',$day);
		$this->assign('count',$count);
		$this->display();
	}
	// public function index(){
	// 	// //接收id参数
	// 	// $id = session('manager_info.id');
		
	// 	// //查询该机台的基本信息
		
	// 	// //根据goods_id 查询线上销售额
	// 	// $total = D('Total') -> where(['manager_id' => $id]) -> order('month asc') -> select();
	// 	// // dump($total);die;
	// 	// $arr2 = array_map('array_shift',$total);
		
	// 	// foreach ($total as $key => $value) {
	// 	// 	$arr[] = $value['income']+$value['offline']-$value['pay'];

	// 	// }	
	// 	// $income = array();
	// 	// $pay = array();
	// 	// $offline = array();
	// 	// foreach($total as $k => $v){
	// 	// 	//插件中要求的数据必须是数字类型的
	// 	// 	$income[] = floatval($v['income']);//遍历数据库线上收入字段的值
			
	// 	// }
	// 	// foreach ($total as $k => $v) {//遍历数据库支出字段的值
	// 	// 	$pay[] = floatval($v['pay']);
	// 	// }
	// 	// foreach ($total as $k => $v) {//遍历数据库线下收入字段的值
	// 	// 	$offline[] = floatval($v['offline']);
	// 	// }
	// 	// $money = array_sum($income)+array_sum($offline)+array_sum($pay);//用于计算的总和 不需要转换成json
	// 	// $data['pp_income'] = json_encode(array_sum($income)/$money*100);//线上百分比
	// 	// $data['pp_offline'] = json_encode(array_sum($offline)/$money*100);//线下百分比
	// 	// $data['pp_pay'] =json_encode(array_sum($pay)/$money*100);//支出百分比
	// 	// $data['arr'] = json_encode($arr);//利润
	// 	// $data['income'] = json_encode($income);//线上收入
	// 	// $data['pay'] = json_encode($pay);//支出
	// 	// $data['offline'] = json_encode($offline);//线下收入
	// 	// // dump($data);die;
	// 	// $this -> assign('data',$data);
	// 	// $this -> display();


	// 	// dump($_SESSION);die;
	// 	//接收使用者的id
	// 	$id = session('manager_info.id');

	// 	//查询他的权限等级
	// 	$role_id = session('manager_info.role_id');
	// 	$status = session('manager_info.status');
	// 	$role_id = D('Manager')->where(['id'=>$id])->getField('role_id');
	// 	session('role_id',$role_id);
	// 	if (($role_id == 3 || $role_id == 5) && $status == 2) {
			
	// 	}
	// 	// dump($role_id);die;
	// 	//查询他名下所有的机台
	// 	elseif ($role_id ==4 || $role_id ==6) {
	// 		//普通员工
	// 	 	$equipment = D('Equipment')->where(['equipment_user'=>$id])->getField('id',true);
		 	
	// 	 	// dump($role_id);die;
	// 	 	if ($equipment=='') {
	// 	 			redirect(U('Admin/login/login' ,'' ,'') ,2 ,'尚未分配机台,即将登出' );	 		
	// 	 			 	}	
	// 	 	//查询出分配给他的机台的ids集合
	// 	 	$equipment_ids = implode($equipment,',');

	// 	 	$realtime_offline = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	// dump($realtime_offline);die;
	// 	 	$totay_realtime_offline_run_count = array();//今日线下模式总运行次数
	// 	 	$yesterday_realtime_offline_run_count = array();//昨日线下模式总运行次数
	// 	 	$totay_realtime_offline_unfinish = array();//今日线下模式总失败次数
	// 	 	$yesterday_realtime_offline_unfinish = array();//昨日线下模式总失败次数
	// 	 	$today_realtime_offline_income_count = array();//今日线下模式总收入 (投币量)
	// 	 	$yesterday_realtime_offline_income_count = array();//昨日线下模式总收入 (投币量)
	// 	 	$today_realtime_offline_gift_out_count = array();//今日线下模式礼品掉落数量
	// 	 	$yesterday_realtime_offline_gift_out_count = array();//昨日线下模式礼品掉落数量
	// 	 	$today_realtime_offline_ticket_out_count = array();//今日线下模式彩票出奖数量
	// 	 	$yesterday_realtime_offline_ticket_out_count = array();//昨日线下模式彩票出奖数量
	// 	 	$today_realtime_offline_free_game_count = array();//今日的线下模式免费游戏次数
	// 	 	$yesterday_realtime_offline_free_game_count = array();//昨日的线下模式免费游戏次数
	// 	 	foreach ($realtime_offline as $key => $value) {
	// 	 		$today_realtime_offline_run_count[] = $value['run_count_today'];
	// 	 		$yesterday_realtime_offline_run_count[] = $value['run_count_yesterday'];
	// 	 		$today_realtime_offline_unfinish[] = $value['unfinish_count_today'];
	// 	 		$yesterday_realtime_offline_unfinish[] = $value['unfinish_count_yesterday'];
	// 	 		$today_realtime_offline_income_count[] = $value['income_count_today'];
	// 	 		$yesterday_realtime_offline_income_count[] = $value['income_count_yesterday'];
	// 	 		$today_realtime_offline_gift_out_count[] = $value['gift_out_count_today'];
	// 	 		$yesterday_realtime_offline_gift_out_count[] = $value['gift_out_count_yesterday'];
	// 	 		$today_realtime_offline_ticket_out_count[] = $value['ticket_out_count_today'];
	// 	 		$yesterday_realtime_offline_ticket_out_count[] = $value['ticket_gift_count_yesterday'];
	// 	 		$today_realtime_offline_free_game_count[] = $value['free_game_count_today'];
	// 	 		$yesterday_realtime_offline_free_game_count[] = $value['free_game_count_yesterday'];
	// 	 	}
	// 	 	$total['today_realtime_offline_run_count'] = array_sum($today_realtime_offline_run_count);//今日目前线下模式运行总次数
	// 		$total['today_realtime_offline_unfinish'] = array_sum($today_realtime_offline_unfinish);//今日线下模式总失败次数
	// 	 	$total['today_realtime_offline_income_count'] = array_sum($today_realtime_offline_income_count);//今日线下模式收入  (只有投币数没有收入)
	// 	 	$total['today_realtime_offline_gift_out_count'] = array_sum($today_realtime_offline_gift_out_count);//今日线下模式礼物掉落数量
	// 	 	$total['today_realtime_offline_ticket_out_count'] = array_sum($today_realtime_offline_ticket_out_count);//今日线下模式彩票出奖数量
	// 	 	$total['today_realtime_offline_free_game_count'] = array_sum($today_realtime_offline_free_game_count);//今日线下模式免费游戏次数
	// 	 	$total['yesterday_realtime_offline_run_count'] = array_sum($yesterday_realtime_offline_run_count);//昨日目前线下模式运行总次数
	// 		$total['yesterday_realtime_offline_unfinish'] = array_sum($yesterday_realtime_offline_unfinish);//昨日线下模式总失败次数
	// 	 	$total['yesterday_realtime_offline_income_count'] = array_sum($yesterday_realtime_offline_income_count);//昨日线下模式收入  (只有投币数没有收入)
	// 	 	$total['yesterday_realtime_offline_gift_out_count'] = array_sum($yesterday_realtime_offline_gift_out_count);//昨日线下模式礼物掉落数量
	// 	 	$total['yesterday_realtime_offline_ticket_out_count'] = array_sum($yesterday_realtime_offline_ticket_out_count);//昨日线下模式彩票出奖数量
	// 	 	$total['yesterday_realtime_offline_ticket_out_count'] = array_sum($yesterday_realtime_offline_free_game_count);//昨日线下模式免费游戏次数
	// 	 	//------------------------------------------------------今日and昨日线上数据---------------------------------------------------------------------
	// 	 	$realtime_online = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	// dump($realtime_online);die;
	// 	 	$totay_realtime_online_run_count = array();//今日线上模式总运行次数
	// 	 	$yesterday_realtime_online_run_count = array();//昨日线上模式总运行次数
	// 	 	$totay_realtime_online_unfinish = array();//今日线上模式总失败次数
	// 	 	$yesterday_realtime_online_unfinish = array();//昨日线上模式总失败次数
	// 	 	$today_realtime_online_income_count = array();//今日线上模式总收入 
	// 	 	$yesterday_realtime_online_income_count = array();//昨日线上模式总收入 
	// 	 	$today_realtime_online_gift_out_count = array();//今日线上模式礼品掉落数量
	// 	 	$yesterday_realtime_online_gift_out_count = array();//昨日线上模式礼品掉落数量
	// 	 	$today_realtime_online_ticket_out_count = array();//今日线上模式彩票出奖数量
	// 	 	$yesterday_realtime_online_ticket_out_count = array();//昨日线上模式彩票出奖数量
	// 	 	$today_realtime_online_free_game_count = array();//今日线下模式免费游戏次数
	// 	 	$yesterday_realtime_online_free_game_count = array();//昨日线下模式免费游戏次数
	// 	 	foreach ($realtime_online as $key => $value) {
	// 	 		$today_realtime_online_run_count[] = $value['run_count_today'];
	// 	 		$yesterday_realtime_online_run_count[] = $value['run_count_yesterday'];
	// 	 		$today_realtime_online_unfinish[] = $value['unfinish_count_today'];
	// 	 		$yesterday_realtime_online_unfinish[] = $value['unfinish_count_yesterday'];
	// 	 		$today_realtime_online_income_count[] = $value['income_count_today'];
	// 	 		$yesterday_realtime_online_income_count[] = $value['income_count_yesterday'];
	// 	 		$today_realtime_online_gift_out_count[] = $value['gift_out_count_today'];
	// 	 		$yesterday_realtime_online_gift_out_count[] = $value['gift_out_count_yesterday'];
	// 	 		$today_realtime_online_ticket_out_count[] = $value['ticket_out_count_today'];
	// 	 		$yesterday_realtime_online_ticket_out_count[] = $value['ticket_gift_count_yesterday'];
	// 	 		$today_realtime_online_free_game_count[] = $value['free_game_count_today'];
	// 	 		$yesterday_realtime_online_free_game_count[] = $value['free_game_count_yesterday'];
	// 	 	}
	// 	 	$total['today_realtime_online_run_count'] = array_sum($today_realtime_online_run_count);//今日目前线上模式运行总次数
	// 	 	// dump($today_realtime_online_run_count);die;
	// 		$total['today_realtime_online_unfinish'] = array_sum($today_realtime_online_unfinish);//今日线上模式总失败次数
	// 	 	$total['today_realtime_online_income_count'] = array_sum($today_realtime_online_income_count);//今日线上模式收入  (只有投币数没有收入)
		 	
	// 	 	$total['today_realtime_online_gift_out_count'] = array_sum($today_realtime_online_gift_out_count);//今日线上模式礼物掉落数量
	// 	 	$total['today_realtime_online_ticket_out_count'] = array_sum($today_realtime_online_ticket_out_count);//今日线上模式彩票出奖数量
	// 	 	$total['today_realtime_online_free_game_count'] = array_sum($today_realtime_online_free_game_count);//今日线上模式免费游戏次数
	// 	 	$total['yesterday_realtime_online_run_count'] = array_sum($yesterday_realtime_online_run_count);//昨日线上模式运行总次数
	// 		$total['yesterday_realtime_online_unfinish'] = array_sum($yesterday_realtime_online_unfinish);//昨日线上模式总失败次数
	// 	 	$total['yesterday_realtime_online_income_count'] = array_sum($yesterday_realtime_online_income_count);//昨日线上模式收入  
	// 	 	// dump($total['yesterday_realtime_online_income_count']);die;
	// 	 	$total['yesterday_realtime_online_gift_out_count'] = array_sum($yesterday_realtime_online_gift_out_count);//昨日线上模式礼物掉落数量
	// 	 	$total['yesterday_realtime_online_ticket_out_count'] = array_sum($yesterday_realtime_online_ticket_out_count);//昨日线上模式彩票出奖数量
	// 	 	$total['yesterday_realtime_online_free_game_count'] = array_sum($yesterday_realtime_online_free_game_count);//昨日线上模式免费游戏次数
	// 	 	//----------------------------------------------------------------------------------------------------------------------------------------------












	// 	 	//-----------------------------------------------------月数据-------------------------------------------------------------------------
	// 	 	//以下是月统计表的数据*******
		 	
		 	
	// 	 	//---------------------------------------------------这个月的线下数据------------------------------------------
	// 	 	$month_realtime_offline = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	$month_realtime_offline_run_count = array();//本月线下模式总运行次数
	// 	 	$month_realtime_offline_unfinish = array();//本月线下模式总失败次数
	// 	 	$month_realtime_offline_income_count = array();//本月显示模式收入  只有投币数量
	// 	 	$month_realtime_offline_gift_out_count = array();//本月线下模式礼物掉落数量
	// 	 	$month_realtime_offline_ticket_out_count = array();//本月线下模式彩票出奖数量
	// 		foreach ($month_realtime_offline as $key => $value) {
	// 			$month_realtime_offline_run_count[] = $value['run_count_month'];
	// 			$month_realtime_offline_unfinish[] = $value['unfinish_count_month'];
	// 	 		$month_realtime_offline_income_count[] = $value['income_count_month'];
	// 	 		$month_realtime_offline_gift_out_count[] = $value['gift_out_count_month'];
	// 	 		$month_realtime_offline_ticket_out_count[] = $value['ticket_out_count_month'];
	// 		}
	// 		$total['month_realtime_offline_run_count'] = array_sum($month_realtime_offline_run_count);//本月目前线下模式运行总次数
	// 		$total['month_realtime_offline_unfinish'] = array_sum($month_realtime_offline_unfinish);//本月线下模式总失败次数
	// 	 	$total['month_realtime_offline_income_count'] = array_sum($month_realtime_offline_income_count);//本月线下模式收入  (只有投币数没有收入)
	// 	 	$total['month_realtime_offline_gift_out_count'] = array_sum($month_realtime_offline_gift_out_count);//本月线下模式礼物掉落数量
	// 	 	$total['month_realtime_offline_ticket_out_count'] = array_sum($month_realtime_offline_ticket_out_count);//线下模式彩票出奖数量
	// 	 	//----------------------------------------------------这个月的线上数据------------------------------------------
	// 	 	$month_realtime_online = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	// dump($month_realtime_online);die;
	// 	 	$month_realtime_online_run_count = array();//本月线上模式总运行次数
	// 	 	$month_realtime_online_unfinish = array();//本月线上模式总失败次数
	// 	 	$month_realtime_online_income_count = array();//本月显示模式收入  只有投币数量
	// 	 	$month_realtime_online_gift_out_count = array();//本月线上模式礼物掉落数量
	// 	 	$month_realtime_online_ticket_out_count = array();//本月线上模式彩票出奖数量
	// 	 	$month_realtime_online_free_game_count = array();//本月线上模式免费游戏次数
	// 		foreach ($month_realtime_online as $key => $value) {
	// 			$month_realtime_online_run_count[] = $value['run_count_month'];
	// 			$month_realtime_online_unfinish[] = $value['unfinish_count_month'];
	// 	 		$month_realtime_online_income_count[] = $value['income_count_month'];
	// 	 		$month_realtime_online_gift_out_count[] = $value['gift_out_count_month'];
	// 	 		$month_realtime_online_ticket_out_count[] = $value['ticket_out_count_month'];
	// 	 		$month_realtime_online_free_game_count[] = $value['free_game_count_month'];
	// 		}
	// 		$total['month_realtime_online_run_count'] = array_sum($month_realtime_online_run_count);//本月目前线上模式运行总次数
	// 		$total['month_realtime_online_unfinish'] = array_sum($month_realtime_online_unfinish);//本月线上模式总失败次数
	// 	 	$total['month_realtime_online_income_count'] = array_sum($month_realtime_online_income_count);//本月线上模式收入  (只有投币数没有收入)
	// 	 	$total['month_realtime_online_gift_out_count'] = array_sum($month_realtime_online_gift_out_count);//本月线上模式礼物掉落数量
	// 	 	$total['month_realtime_online_ticket_out_count'] = array_sum($month_realtime_online_ticket_out_count);//线上模式彩票出奖数量
	// 	 	$total['month_realtime_online_free_game_count'] = array_sum($month_realtime_online_free_game_count);//本月线上模式总免费游戏次数
	// 	 	// dump($total['month_realtime_online_run_count']);die;

	// 	 	//---------------------------------------------------上个月的数据------------------------------------------
	// 	 	//查询出线下月统计表在ids集合中的数据
	// 	 	$month_offline = D('Month_statistics_offline')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	$month_offline_run_count = array();//线下模式总运行次数
	// 	 	$month_offline_unfinish = array();//现下模式总失败次数
	// 	 	// $month_offline_free_game_count = array();//显示模式没有免费
	// 	 	$month_offline_income_count = array();//线下收入 只有投币数没有收入
	// 	 	$month_offline_gift_out_count = array();//线下模式礼物掉落数量
	// 	 	$month_offline_ticket_out_count = array();//线下模式彩票出奖数量
	// 	 	foreach ($month_offline as $key => $value) {
	// 	 		$month_offline_run_count[] = $value['run_count'];
	// 	 		$month_offline_unfinish[] = $value['unfinish_count'];
	// 	 		$month_offline_income_count[] = $value['income_count'];
	// 	 		$month_offline_gift_out_count[] = $value['gift_out_count'];
	// 	 		$month_offline_ticket_out_count[] = $value['ticket_out_count'];
	// 	 	}

	// 	 	$total['month_offline_run_count'] = array_sum($month_offline_run_count);//线下模式运行总次数
	// 	 	$total['month_offline_unfinish'] = array_sum($month_offline_unfinish);//线下模式总失败次数
	// 	 	$total['month_offline_income_count'] = array_sum($month_offline_income_count);//线下模式收入  (只有投币数没有收入)
	// 	 	$total['month_offline_gift_out_count'] = array_sum($month_offline_gift_out_count);//现下模式礼物掉落数量
	// 	 	$total['month_offline_ticket_out_count'] = array_sum($month_offline_ticket_out_count);//线下模式彩票出奖数量
	// 	 	//查询出线上月统计表在ids集合中的数据--------------------------------------------------
	// 	 	$month_online = D('Month_statistics_online')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	$month_online_run_count = array();
	// 	 	$month_online_unfinish = array();
	// 	 	$month_online_free_game_count = array();
	// 	 	$month_online_income_count = array();
	// 	 	$month_online_gift_out_count = array();
	// 	 	$month_online_ticket_out_count = array();
	// 	 	foreach ($month_online as $key => $value) {
	// 	 		$month_online_run_count[] = $value['run_count'];
	// 	 		$month_online_unfinish[] = $value['unfinish_count'];
	// 	 		$month_online_free_game_count[] = $value['free_game_count'];
	// 	 		$month_online_income_count[] = $value['income_count'];
	// 	 		$month_online_gift_out_count[] = $value['gift_out_count'];
	// 	 		$month_online_ticket_out_count[] = $value['ticket_out_count'];
	// 	 	}
	// 	 	$total['month_unfinish'] = array_sum($month_online_unfinish)+array_sum($month_offline_unfinish);
	// 	 	$total['month_online_run_count'] = array_sum($month_online_run_count);//线上模式总运行次数
	// 	 	$total['month_online_unfinish']  = array_sum($month_online_unfinish);//线上模式总失败次数
	// 	 	$total['month_online_free_game_count'] = array_sum($month_online_free_game_count);//线上模式免费游戏总次数
	// 	 	$total['month_online_income_count'] = array_sum($month_online_income_count);//线上模式总体收入		 	
	// 	 	$total['month_online_gift_out_count'] = array_sum($month_online_gift_out_count);//线上模式礼物掉落数量
	// 	 	$total['month_online_ticket_out_count'] = array_sum($month_online_ticket_out_count);//线上模式彩票出奖数量






	// 	 	// --------------------------------json格式月度数据----------------------
	// 	 	$data['pp_month_online_unfinish'] = json_encode(round(array_sum($month_online_unfinish)/array_sum($month_online_run_count)*100,2));//上个月失败游戏次数占比
	// 	 	$data['pp_month_online_free_game_count'] = json_encode(round(array_sum($month_online_free_game_count)/array_sum($month_online_run_count)*100,2));//上个月免费游戏次数占比


	// 	 	$data['month_online_run_count'] = json_encode(array_sum($month_online_run_count));//上个月线上模式总运行次数
	// 	 	$data['month_online_unfinish'] = json_encode(array_sum($month_online_unfinish));//上个月线上模式总失败次数
	// 	 	$data['month_online_free_game_count'] = json_encode(array_sum($month_online_free_game_count));//上个月线上模式免费游戏总次数
	// 	 	$data['month_online_income_count'] = json_encode(array_sum($month_online_income_count));//上个月线上模式总体收入
	// 	 	$data['month_online_gift_out_count'] = json_encode(array_sum($month_online_gift_out_count));//上个月线上模式礼物掉落数量
	// 	 	$data['month_online_ticket_out_count'] = json_encode(array_sum($month_online_ticket_out_count));//上个月线上模式彩票出奖数量
	// 	 	// ------------------------------月度JSON数据---------------------------------------------------------------------------------

	// 	 	//混合图
	// 	 		$x = array();
	// 	 	for($i = 10; $i > 0; $i--){
 //    		$x[] =  date('Y-m-d', strtotime('-'.$i.' day'));
	// 		}
	// 		$data['day'] = json_encode($x);
	// 		// $off = D('day_statistics_offline')->field("log_date,SUM(run_count) offline_count")->where("equipment_id in ({$equipment_ids}) and  date_sub(curdate(), INTERVAL 10 DAY) <= date(`log_date`)")->group("log_date")->select();

	// 		// $on = D('day_statistics_online')->field("log_date,SUM(run_count) online_count,free_game_count")->where("equipment_id in ({$equipment_ids}) and  date_sub(curdate(), INTERVAL 10 DAY) <= date(`log_date`)")->group("log_date")->select();

	// 		foreach ($off as $key => $value) {
	// 			$offline_count[] = intval($value['offline_count']);
	// 		}
	// 		foreach ($on as $key => $value) {
	// 			$online_count[] = intval($value['online_count']);
	// 		}
	// 		foreach ($on as $key => $value) {
	// 			$free_count[] = intval($value['free_game_count']);
	// 		}
		
	// 		foreach ($off as $key1 => $value1) {
	// 				foreach ($on as $key2 => $value2) {
	// 					if ($value1['log_date'] == $value2['log_date']) {
	// 						$pin[] = intval(($value1['offline_count'] + $value2['online_count'] + $value2['free_game_count']));
	// 					}	
	// 				}
	// 		}
			
	// 		$data['p_online'] = json_encode(round(array_sum($online_count)/array_sum($pin)*100,2));//在线游戏次数平均值
	// 		$data['p_free'] = json_encode(round(array_sum($free_count)/array_sum($pin)*100,2));//免费游戏次数平均值
	// 		$data['p_offline'] = json_encode(round(array_sum($offline_count)/array_sum($pin)*100,2));//线下游戏次数平均值
	// 		$data['free_count'] = json_encode($free_count);//混合图的免费游戏次数
	// 		$data['offline_count'] = json_encode($offline_count);//混合图的线下游戏次数
	// 		$data['online_count'] = json_encode($online_count);//混合图的线上游戏次数

			
	// 	 }
	// 	 elseif ($role_id == 3 || $role_id == 5 || $role_id == 1){

	// 	 	$stat= D('Equipment')->alias('t1')->where(['t1.equipment_pid'=>$id])->join("left join stat as t2 on t2.machine_uuid = t1.uuid")->select();
		 	
	// 	 	$this->assign('stat',$stat);	
		
	// 	 }
	// 	 else{
		 	
	// 	 	//超级管理员
	// 	 	$manager = D('Manager')->where(['pid'=>$id])->getField('id',true);

	// 	 	$manager_ids = implode($manager,',');
	// 	 	//查询出他名下所有机台的ids集合
	// 	 	$equipment = D('Equipment')->where("equipment_pid in ({$manager_ids}) or equipment_pid = $id")->getField('id',true);
		 
	// 	 	$equipment_ids = implode($equipment,',');

	// 	 	//-----------------------------------------------------今日and昨日线下数据---------------------------------------------------------------------
	// 	 	$realtime_offline = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	$totay_realtime_offline_run_count = array();//今日线下模式总运行次数
	// 	 	$yesterday_realtime_offline_run_count = array();//昨日线下模式总运行次数
	// 	 	$totay_realtime_offline_unfinish = array();//今日线下模式总失败次数
	// 	 	$yesterday_realtime_offline_unfinish = array();//昨日线下模式总失败次数
	// 	 	$today_realtime_offline_income_count = array();//今日线下模式总收入 (投币量)
	// 	 	$yesterday_realtime_offline_income_count = array();//昨日线下模式总收入 (投币量)
	// 	 	$today_realtime_offline_gift_out_count = array();//今日线下模式礼品掉落数量
	// 	 	$yesterday_realtime_offline_gift_out_count = array();//昨日线下模式礼品掉落数量
	// 	 	$today_realtime_offline_ticket_out_count = array();//今日线下模式彩票出奖数量
	// 	 	$yesterday_realtime_offline_ticket_out_count = array();//昨日线下模式彩票出奖数量
	// 	 	$today_realtime_offline_free_game_count = array();//今日的线下模式免费游戏次数
	// 	 	$yesterday_realtime_offline_free_game_count = array();//昨日的线下模式免费游戏次数
	// 	 	foreach ($realtime_offline as $key => $value) {
	// 	 		$today_realtime_offline_run_count[] = $value['run_count_today'];
	// 	 		$yesterday_realtime_offline_run_count[] = $value['run_count_yesterday'];
	// 	 		$today_realtime_offline_unfinish[] = $value['unfinish_count_today'];
	// 	 		$yesterday_realtime_offline_unfinish[] = $value['unfinish_count_yesterday'];
	// 	 		$today_realtime_offline_income_count[] = $value['income_count_today'];
	// 	 		$yesterday_realtime_offline_income_count[] = $value['income_count_yesterday'];
	// 	 		$today_realtime_offline_gift_out_count[] = $value['gift_out_count_today'];
	// 	 		$yesterday_realtime_offline_gift_out_count[] = $value['gift_out_count_yesterday'];
	// 	 		$today_realtime_offline_ticket_out_count[] = $value['ticket_out_count_today'];
	// 	 		$yesterday_realtime_offline_ticket_out_count[] = $value['ticket_gift_count_yesterday'];
	// 	 		$today_realtime_offline_free_game_count[] = $value['free_game_count_today'];
	// 	 		$yesterday_realtime_offline_free_game_count[] = $value['free_game_count_yesterday'];
	// 	 	}
	// 	 	$total['today_realtime_offline_run_count'] = array_sum($today_realtime_offline_run_count);//今日目前线下模式运行总次数
	// 		$total['today_realtime_offline_unfinish'] = array_sum($today_realtime_offline_unfinish);//今日线下模式总失败次数
	// 	 	$total['today_realtime_offline_income_count'] = array_sum($today_realtime_offline_income_count);//今日线下模式收入  (只有投币数没有收入)
	// 	 	$total['today_realtime_offline_gift_out_count'] = array_sum($today_realtime_offline_gift_out_count);//今日线下模式礼物掉落数量
	// 	 	$total['today_realtime_offline_ticket_out_count'] = array_sum($today_realtime_offline_ticket_out_count);//今日线下模式彩票出奖数量
	// 	 	$total['today_realtime_offline_free_game_count'] = array_sum($today_realtime_offline_free_game_count);//今日线下模式免费游戏次数
	// 	 	$total['yesterday_realtime_offline_run_count'] = array_sum($yesterday_realtime_offline_run_count);//昨日目前线下模式运行总次数
	// 		$total['yesterday_realtime_offline_unfinish'] = array_sum($yesterday_realtime_offline_unfinish);//昨日线下模式总失败次数
	// 	 	$total['yesterday_realtime_offline_income_count'] = array_sum($yesterday_realtime_offline_income_count);//昨日线下模式收入  (只有投币数没有收入)
	// 	 	$total['yesterday_realtime_offline_gift_out_count'] = array_sum($yesterday_realtime_offline_gift_out_count);//昨日线下模式礼物掉落数量
	// 	 	$total['yesterday_realtime_offline_ticket_out_count'] = array_sum($yesterday_realtime_offline_ticket_out_count);//昨日线下模式彩票出奖数量
	// 	 	$total['yesterday_realtime_offline_ticket_out_count'] = array_sum($yesterday_realtime_offline_free_game_count);//昨日线下模式免费游戏次数
	// 	 	//------------------------------------------------------今日and昨日线上数据---------------------------------------------------------------------
	// 	 	$realtime_online = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	// dump($realtime_online);die;
	// 	 	$totay_realtime_online_run_count = array();//今日线上模式总运行次数
	// 	 	$yesterday_realtime_online_run_count = array();//昨日线上模式总运行次数
	// 	 	$totay_realtime_online_unfinish = array();//今日线上模式总失败次数
	// 	 	$yesterday_realtime_online_unfinish = array();//昨日线上模式总失败次数
	// 	 	$today_realtime_online_income_count = array();//今日线上模式总收入 
	// 	 	$yesterday_realtime_online_income_count = array();//昨日线上模式总收入 
	// 	 	$today_realtime_online_gift_out_count = array();//今日线上模式礼品掉落数量
	// 	 	$yesterday_realtime_online_gift_out_count = array();//昨日线上模式礼品掉落数量
	// 	 	$today_realtime_online_ticket_out_count = array();//今日线上模式彩票出奖数量
	// 	 	$yesterday_realtime_online_ticket_out_count = array();//昨日线上模式彩票出奖数量
	// 	 	$today_realtime_online_free_game_count = array();//今日线下模式免费游戏次数
	// 	 	$yesterday_realtime_online_free_game_count = array();//昨日线下模式免费游戏次数
	// 	 	foreach ($realtime_online as $key => $value) {
	// 	 		$today_realtime_online_run_count[] = $value['run_count_today'];
	// 	 		$yesterday_realtime_online_run_count[] = $value['run_count_yesterday'];
	// 	 		$today_realtime_online_unfinish[] = $value['unfinish_count_today'];
	// 	 		$yesterday_realtime_online_unfinish[] = $value['unfinish_count_yesterday'];
	// 	 		$today_realtime_online_income_count[] = $value['income_count_today'];
	// 	 		$yesterday_realtime_online_income_count[] = $value['income_count_yesterday'];
	// 	 		$today_realtime_online_gift_out_count[] = $value['gift_out_count_today'];
	// 	 		$yesterday_realtime_online_gift_out_count[] = $value['gift_out_count_yesterday'];
	// 	 		$today_realtime_online_ticket_out_count[] = $value['ticket_out_count_today'];
	// 	 		$yesterday_realtime_online_ticket_out_count[] = $value['ticket_gift_count_yesterday'];
	// 	 		$today_realtime_online_free_game_count[] = $value['free_game_count_today'];
	// 	 		$yesterday_realtime_online_free_game_count[] = $value['free_game_count_yesterday'];
	// 	 	}
	// 	 	$total['today_realtime_online_run_count'] = array_sum($today_realtime_online_run_count);//今日目前线上模式运行总次数
	// 	 	// dump($today_realtime_online_run_count);die;
	// 		$total['today_realtime_online_unfinish'] = array_sum($today_realtime_online_unfinish);//今日线上模式总失败次数
	// 	 	$total['today_realtime_online_income_count'] = array_sum($today_realtime_online_income_count);//今日线上模式收入  (只有投币数没有收入)
		 	
	// 	 	$total['today_realtime_online_gift_out_count'] = array_sum($today_realtime_online_gift_out_count);//今日线上模式礼物掉落数量
	// 	 	$total['today_realtime_online_ticket_out_count'] = array_sum($today_realtime_online_ticket_out_count);//今日线上模式彩票出奖数量
	// 	 	$total['today_realtime_online_free_game_count'] = array_sum($today_realtime_online_free_game_count);//今日线上模式免费游戏次数
	// 	 	$total['yesterday_realtime_online_run_count'] = array_sum($yesterday_realtime_online_run_count);//昨日线上模式运行总次数
	// 		$total['yesterday_realtime_online_unfinish'] = array_sum($yesterday_realtime_online_unfinish);//昨日线上模式总失败次数
	// 	 	$total['yesterday_realtime_online_income_count'] = array_sum($yesterday_realtime_online_income_count);//昨日线上模式收入  
	// 	 	// dump($total['yesterday_realtime_online_income_count']);die;
	// 	 	$total['yesterday_realtime_online_gift_out_count'] = array_sum($yesterday_realtime_online_gift_out_count);//昨日线上模式礼物掉落数量
	// 	 	$total['yesterday_realtime_online_ticket_out_count'] = array_sum($yesterday_realtime_online_ticket_out_count);//昨日线上模式彩票出奖数量
	// 	 	$total['yesterday_realtime_online_free_game_count'] = array_sum($yesterday_realtime_online_free_game_count);//昨日线上模式免费游戏次数
	// 	 	//----------------------------------------------------------------------------------------------------------------------------------------------












	// 	 	//-----------------------------------------------------月数据-------------------------------------------------------------------------
	// 	 	//以下是月统计表的数据*******
		 	
		 	
	// 	 	//---------------------------------------------------这个月的线下数据------------------------------------------
	// 	 	$month_realtime_offline = D('Realtime_statistics_offline')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	$month_realtime_offline_run_count = array();//本月线下模式总运行次数
	// 	 	$month_realtime_offline_unfinish = array();//本月线下模式总失败次数
	// 	 	$month_realtime_offline_income_count = array();//本月显示模式收入  只有投币数量
	// 	 	$month_realtime_offline_gift_out_count = array();//本月线下模式礼物掉落数量
	// 	 	$month_realtime_offline_ticket_out_count = array();//本月线下模式彩票出奖数量
	// 		foreach ($month_realtime_offline as $key => $value) {
	// 			$month_realtime_offline_run_count[] = $value['run_count_month'];
	// 			$month_realtime_offline_unfinish[] = $value['unfinish_count_month'];
	// 	 		$month_realtime_offline_income_count[] = $value['income_count_month'];
	// 	 		$month_realtime_offline_gift_out_count[] = $value['gift_out_count_month'];
	// 	 		$month_realtime_offline_ticket_out_count[] = $value['ticket_out_count_month'];
	// 		}
	// 		$total['month_realtime_offline_run_count'] = array_sum($month_realtime_offline_run_count);//本月目前线下模式运行总次数
	// 		$total['month_realtime_offline_unfinish'] = array_sum($month_realtime_offline_unfinish);//本月线下模式总失败次数
	// 	 	$total['month_realtime_offline_income_count'] = array_sum($month_realtime_offline_income_count);//本月线下模式收入  (只有投币数没有收入)
	// 	 	$total['month_realtime_offline_gift_out_count'] = array_sum($month_realtime_offline_gift_out_count);//本月线下模式礼物掉落数量
	// 	 	$total['month_realtime_offline_ticket_out_count'] = array_sum($month_realtime_offline_ticket_out_count);//线下模式彩票出奖数量
	// 	 	//----------------------------------------------------这个月的线上数据------------------------------------------
	// 	 	$month_realtime_online = D('Realtime_statistics_online')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	// dump($month_realtime_online);die;
	// 	 	$month_realtime_online_run_count = array();//本月线上模式总运行次数
	// 	 	$month_realtime_online_unfinish = array();//本月线上模式总失败次数
	// 	 	$month_realtime_online_income_count = array();//本月显示模式收入  只有投币数量
	// 	 	$month_realtime_online_gift_out_count = array();//本月线上模式礼物掉落数量
	// 	 	$month_realtime_online_ticket_out_count = array();//本月线上模式彩票出奖数量
	// 	 	$month_realtime_online_free_game_count = array();//本月线上模式免费游戏次数
	// 		foreach ($month_realtime_online as $key => $value) {
	// 			$month_realtime_online_run_count[] = $value['run_count_month'];
	// 			$month_realtime_online_unfinish[] = $value['unfinish_count_month'];
	// 	 		$month_realtime_online_income_count[] = $value['income_count_month'];
	// 	 		$month_realtime_online_gift_out_count[] = $value['gift_out_count_month'];
	// 	 		$month_realtime_online_ticket_out_count[] = $value['ticket_out_count_month'];
	// 	 		$month_realtime_online_free_game_count[] = $value['free_game_count_month'];
	// 		}
	// 		$total['month_realtime_online_run_count'] = array_sum($month_realtime_online_run_count);//本月目前线上模式运行总次数
	// 		$total['month_realtime_online_unfinish'] = array_sum($month_realtime_online_unfinish);//本月线上模式总失败次数
	// 	 	$total['month_realtime_online_income_count'] = array_sum($month_realtime_online_income_count);//本月线上模式收入  (只有投币数没有收入)
	// 	 	$total['month_realtime_online_gift_out_count'] = array_sum($month_realtime_online_gift_out_count);//本月线上模式礼物掉落数量
	// 	 	$total['month_realtime_online_ticket_out_count'] = array_sum($month_realtime_online_ticket_out_count);//线上模式彩票出奖数量
	// 	 	$total['month_realtime_online_free_game_count'] = array_sum($month_realtime_online_free_game_count);//本月线上模式总免费游戏次数
	// 	 	// dump($total['month_realtime_online_run_count']);die;

	// 	 	//---------------------------------------------------上个月的数据------------------------------------------
	// 	 	//查询出线下月统计表在ids集合中的数据
	// 	 	$month_offline = D('Month_statistics_offline')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	$month_offline_run_count = array();//线下模式总运行次数
	// 	 	$month_offline_unfinish = array();//现下模式总失败次数
	// 	 	// $month_offline_free_game_count = array();//显示模式没有免费
	// 	 	$month_offline_income_count = array();//线下收入 只有投币数没有收入
	// 	 	$month_offline_gift_out_count = array();//线下模式礼物掉落数量
	// 	 	$month_offline_ticket_out_count = array();//线下模式彩票出奖数量
	// 	 	foreach ($month_offline as $key => $value) {
	// 	 		$month_offline_run_count[] = $value['run_count'];
	// 	 		$month_offline_unfinish[] = $value['unfinish_count'];
	// 	 		$month_offline_income_count[] = $value['income_count'];
	// 	 		$month_offline_gift_out_count[] = $value['gift_out_count'];
	// 	 		$month_offline_ticket_out_count[] = $value['ticket_out_count'];
	// 	 	}

	// 	 	$total['month_offline_run_count'] = array_sum($month_offline_run_count);//线下模式运行总次数
	// 	 	$total['month_offline_unfinish'] = array_sum($month_offline_unfinish);//线下模式总失败次数
	// 	 	$total['month_offline_income_count'] = array_sum($month_offline_income_count);//线下模式收入  (只有投币数没有收入)
	// 	 	$total['month_offline_gift_out_count'] = array_sum($month_offline_gift_out_count);//现下模式礼物掉落数量
	// 	 	$total['month_offline_ticket_out_count'] = array_sum($month_offline_ticket_out_count);//线下模式彩票出奖数量
	// 	 	//查询出线上月统计表在ids集合中的数据--------------------------------------------------
	// 	 	$month_online = D('Month_statistics_online')->where("equipment_id in ({$equipment_ids})")->select();
	// 	 	$month_online_run_count = array();
	// 	 	$month_online_unfinish = array();
	// 	 	$month_online_free_game_count = array();
	// 	 	$month_online_income_count = array();
	// 	 	$month_online_gift_out_count = array();
	// 	 	$month_online_ticket_out_count = array();
	// 	 	foreach ($month_online as $key => $value) {
	// 	 		$month_online_run_count[] = $value['run_count'];
	// 	 		$month_online_unfinish[] = $value['unfinish_count'];
	// 	 		$month_online_free_game_count[] = $value['free_game_count'];
	// 	 		$month_online_income_count[] = $value['income_count'];
	// 	 		$month_online_gift_out_count[] = $value['gift_out_count'];
	// 	 		$month_online_ticket_out_count[] = $value['ticket_out_count'];
	// 	 	}
	// 	 	$total['month_unfinish'] = array_sum($month_online_unfinish)+array_sum($month_offline_unfinish);
	// 	 	$total['month_online_run_count'] = array_sum($month_online_run_count);//线上模式总运行次数
	// 	 	$total['month_online_unfinish']  = array_sum($month_online_unfinish);//线上模式总失败次数
	// 	 	$total['month_online_free_game_count'] = array_sum($month_online_free_game_count);//线上模式免费游戏总次数
	// 	 	$total['month_online_income_count'] = array_sum($month_online_income_count);//线上模式总体收入		 	
	// 	 	$total['month_online_gift_out_count'] = array_sum($month_online_gift_out_count);//线上模式礼物掉落数量
	// 	 	$total['month_online_ticket_out_count'] = array_sum($month_online_ticket_out_count);//线上模式彩票出奖数量






	// 	 	// --------------------------------json格式月度数据----------------------
	// 	 	$data['pp_month_online_unfinish'] = json_encode(round(array_sum($month_online_unfinish)/array_sum($month_online_run_count)*100,2));//上个月失败游戏次数占比
	// 	 	$data['pp_month_online_free_game_count'] = json_encode(round(array_sum($month_online_free_game_count)/array_sum($month_online_run_count)*100,2));//上个月免费游戏次数占比


	// 	 	$data['month_online_run_count'] = json_encode(array_sum($month_online_run_count));//上个月线上模式总运行次数
	// 	 	$data['month_online_unfinish'] = json_encode(array_sum($month_online_unfinish));//上个月线上模式总失败次数
	// 	 	$data['month_online_free_game_count'] = json_encode(array_sum($month_online_free_game_count));//上个月线上模式免费游戏总次数
	// 	 	$data['month_online_income_count'] = json_encode(array_sum($month_online_income_count));//上个月线上模式总体收入
	// 	 	$data['month_online_gift_out_count'] = json_encode(array_sum($month_online_gift_out_count));//上个月线上模式礼物掉落数量
	// 	 	$data['month_online_ticket_out_count'] = json_encode(array_sum($month_online_ticket_out_count));//上个月线上模式彩票出奖数量
	// 	 	// ------------------------------月度JSON数据---------------------------------------------------------------------------------
	// 	 	//混合图
	// 	 	$x = array();
	// 	 	for($i = 10; $i > 0; $i--){
 //    		$x[] =  date('Y-m-d', strtotime('-'.$i.' day'));
	// 		}
	// 		$data['day'] = json_encode($x);
	// 		$off = D('Day_statistics_offline')->field("log_date,SUM(run_count) offline_count")->where("equipment_id in ({$equipment_ids}) and  date_sub(curdate(), INTERVAL 10 DAY) <= date(`log_date`)")->group("log_date")->select();
	// 		$on = D('Day_statistics_online')->field("log_date,SUM(run_count) online_count,free_game_count")->where("equipment_id in ({$equipment_ids}) and  date_sub(curdate(), INTERVAL 10 DAY) <= date(`log_date`)")->group("log_date")->select();

	// 		foreach ($off as $key => $value) {
	// 			$offline_count[] = intval($value['offline_count']);
	// 		}
	// 		foreach ($on as $key => $value) {
	// 			$online_count[] = intval($value['online_count']);
	// 		}
	// 		foreach ($on as $key => $value) {
	// 			$free_count[] = intval($value['free_game_count']);
	// 		}
		
	// 		foreach ($off as $key1 => $value1) {
	// 				foreach ($on as $key2 => $value2) {
	// 					if ($value1['log_date'] == $value2['log_date']) {
	// 						$pin[] = intval(($value1['offline_count'] + $value2['online_count'] + $value2['free_game_count']));
	// 					}	
	// 				}
	// 		}
			
	// 		$data['p_online'] = json_encode(round(array_sum($online_count)/array_sum($pin)*100,2));//在线游戏次数平均值
	// 		$data['p_free'] = json_encode(round(array_sum($free_count)/array_sum($pin)*100,2));//免费游戏次数平均值
	// 		$data['p_offline'] = json_encode(round(array_sum($offline_count)/array_sum($pin)*100,2));//线下游戏次数平均值
	// 		$data['free_count'] = json_encode($free_count);//混合图的免费游戏次数
	// 		$data['offline_count'] = json_encode($offline_count);//混合图的线下游戏次数
	// 		$data['online_count'] = json_encode($online_count);//混合图的线上游戏次数
			
	// 	 } 
		
	// 	 $this->assign('total',$total);
	// 	 $this->assign('data',$data);
	// 	 $this->display();

	// }


	public function index2(){
		$this->display();
	}

}