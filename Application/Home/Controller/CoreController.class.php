<?php
/**
 * User: Junfeng
 * Date: 2018/6/26
 * Time: 13:12
 */

namespace Home\Controller;
use Think\Controller;

class CoreController extends Controller{


  //机台日志的存储过程
	public function index(){
		    $y = date("Y");
        $m = date("m");
        $d = date("d");
        $morningTime= mktime(0,0,0,$m,$d,$y);
        $end = $morningTime-1;//前一天23:59:59的时间戳
        $star = $morningTime-60*60*24;//前一天0点的时间戳

        $equipment_all=M('equipment')
        ->alias('t1')
        ->field("t1.pid as manager_id,t1.id as equipment_id ,t2.price, $star as statistics_data")
        ->join('left join goods as t2 on t1.goods_id=t2.id')
        ->select();
        //总次数
        $data_all = M('tbl_game_log')
        ->field("count(id) count ,equipment_id")
        ->where("end_time between $star and $end")
        ->Group('equipment_id')
        ->select();
        $all1 = inspirit($equipment_all,$data_all,'run_count');
         
        //全部成功次数
        $data_get = M('tbl_game_log')
        // ->alias("t1")
        ->field("count(id) count,equipment_id")
        ->where("end_time between $star and $end && got_gift=1")
        ->Group('equipment_id')
        ->select();
        $all2 = inspirit($all1,$data_get,'success_number');
        //全部失败次数
        $data_notget = M('tbl_game_log')
        ->field("count(id) count,equipment_id")
        ->where("end_time between $star and $end && got_gift=0")
        ->Group('equipment_id')
        ->select();
        $all3 = inspirit($all2,$data_notget,'fail_number');
        //收费游戏成功次数
        $data_gold_get = M('tbl_game_log')
        ->field("count(id) count,equipment_id")
        ->where("end_time between $star and $end && got_gift=1 && type=('gold')")
        ->Group('equipment_id')
        ->select();
        $all4 = inspirit($all3,$data_gold_get,'gold_game_win_times');
        //免费游戏成功次数
       $data_silver_get = M('tbl_game_log')
       ->field("count(id) count,equipment_id")
       ->where("end_time between $star and $end && got_gift=1 && type=('silver')")
       ->Group('equipment_id')
       ->select();
       $all5 = inspirit($all4,$data_silver_get,'silver_game_win_times');
       //收费游戏失败次数
       $data_gold_notget = M('tbl_game_log')
       ->field("count(id) count,equipment_id")
       ->where("end_time between $star and $end && got_gift=0 && type=('gold')")
       ->Group("equipment_id")
       ->select();
       $all6 = inspirit($all5,$data_gold_notget,'gold_game_lose_times');
       //免费游戏失败次数
       $data_silver_notget = M('tbl_game_log')
       ->field("count(id) count,equipment_id")
       ->where("end_time between $star and $end && got_gift=0 && type=('silver')")
       ->Group('equipment_id')
       ->select();
       $all7 = inspirit($all6,$data_silver_notget,'silver_game_lose_times');
       // gold收费游戏次数
        $gold_all = M('tbl_game_log')
        ->field("count(id) count,equipment_id")
        ->where("end_time between $star and $end && type=('gold')")
        ->Group('equipment_id')
        ->select();
        $all8 = inspirit($all7,$gold_all,'gold_game_times');

       //silver免费游戏次数
        $silver_all = M('tbl_game_log')
        ->field("count(id) count,equipment_id")
        ->where("end_time between $star and $end && type=('silver')")
        ->Group('equipment_id')
        ->select();
        $all9 = inspirit($all8,$silver_all,'silver_game_times');
        foreach($all9 as $k=>&$v){
            $v['income_count'] = $v['run_count']*$v['price'];
            $v['create_time'] = time();
            $v['statistics_date'] = $star;
		}
    //dump($all9);die;
		$statistics = M('equipment_day_statistics')->addAll($all9);
    echo "操作已完成 请关闭页面";
    flush();
}
//商户日统计
public function days(){
      $manager = M("manager")->getField("id",true);
      $id = implode($manager,',');
      $start =mktime(0,0,0,date('m'),date('d')-1,date('Y'));
      $end =mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
      $equipment = M("equipment")
      ->alias("t1")
      ->field("FROM_UNIXTIME(t2.statistics_date,'%Y%m%d') days,t2.*,t1.name,t3.id as pid,t3.username")
      ->where("t1.pid in ({$id})")
      ->where("t2.statistics_date between $start and $end")
      ->join("left join equipment_day_statistics t2 on t2.equipment_id = t1.id")
      ->join("left join manager as t3 on t3.id = t1.pid")
      ->select();
      //dump($equipment);
      $returnarr = array();
      foreach($equipment as $val) {
          if(isset($returnarr[$val['pid']])) {
            //dump($val['pid']);die;
              $returnarr[$val['pid']]['statistics_date'] = $start;
              $returnarr[$val['pid']]['hardware_failure_time'] += $val['hardware_failure_time'];
              $returnarr[$val['pid']]['silver_game_times'] += $val['silver_game_times']; 
              $returnarr[$val['pid']]['gold_game_times'] += $val['gold_game_times']; 
              $returnarr[$val['pid']]['run_count'] += $val['run_count'];  
              $returnarr[$val['pid']]['success_number'] += $val['success_number']; 
              $returnarr[$val['pid']]['fail_number'] += $val['fail_number']; 
              $returnarr[$val['pid']]['silver_game_win_times'] += $val['silver_game_win_times']; 
              $returnarr[$val['pid']]['silver_game_lose_times'] += $val['silver_game_lose_times']; 
              $returnarr[$val['pid']]['gold_game_win_times'] += $val['gold_game_win_times']; 
              $returnarr[$val['pid']]['gold_game_lose_times'] += $val['gold_game_lose_times']; 
              $returnarr[$val['pid']]['gift_out_count'] += $val['gift_out_count']; 
              $returnarr[$val['pid']]['income_count'] += $val['income_count']; 
              //$returnarr[$val['managerid']]['create_time'] = $val['create_time']; 
              $returnarr[$val['pid']]['pid'] = $val['pid']; 
              //$returnarr[$val['pid']]['username'] = $val['username']; 
              $returnarr[$val['pid']]['create_time'] = time(); 
              //$returnarr[$val['managerid']]['amount'] += $val['amount']; 
          } else {
              $returnarr[$val['pid']]['statistics_date'] = $start;
              $returnarr[$val['pid']]['pid'] = $val['pid']; 
              $returnarr[$val['pid']]['hardware_failure_time'] = $val['hardware_failure_time'];
              $returnarr[$val['pid']]['silver_game_times'] = $val['silver_game_times']; 
              $returnarr[$val['pid']]['gold_game_times'] = $val['gold_game_times']; 
              $returnarr[$val['pid']]['run_count'] = $val['run_count'];  
              $returnarr[$val['pid']]['success_number'] = $val['success_number']; 
              $returnarr[$val['pid']]['fail_number'] = $val['fail_number']; 
              $returnarr[$val['pid']]['silver_game_win_times'] = $val['silver_game_win_times']; 
              $returnarr[$val['pid']]['silver_game_lose_times'] = $val['silver_game_lose_times']; 
              $returnarr[$val['pid']]['gold_game_win_times'] = $val['gold_game_win_times']; 
              $returnarr[$val['pid']]['gold_game_lose_times'] = $val['gold_game_lose_times']; 
              $returnarr[$val['pid']]['gift_out_count'] = $val['gift_out_count']; 
              $returnarr[$val['pid']]['income_count'] = $val['income_count']; 
              //$returnarr[$val['managerid']]['create_time'] = $val['create_time'];
              //$returnarr[$val['pid']]['username'] = $val['username']; 
              $returnarr[$val['pid']]['create_time'] = time(); 
              //$returnarr[$val['managerid']]['amount'] += $val['amount']; 
          }
      }
      M('partner_day_statistics')->addAll($returnarr);
  }
  //商户月统计
  public function month_month(){
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
      $star = strtotime($lastStartDay);//上个月的月初时间戳
      //dump($star);die;
      $end = strtotime($lastEndDay)+60*60*24-1;//上个月的月末时间戳
      $manager = M("manager")->getField("id",true);
      $id = implode($manager,',');
      $equipment = M("equipment")
      ->alias("t1")
      ->field("FROM_UNIXTIME(t2.statistics_date,'%Y%m%d') days,t2.*,t1.name,t3.id as pid,t3.username")
      ->where("t1.pid in ({$id})")
      ->where("t2.statistics_date between $star and $end")
      ->join("left join equipment_month_statistics t2 on t2.equipment_id = t1.id")
      ->join("left join manager as t3 on t3.id = t1.pid")
      ->select();
      //dump($equipment);
      $returnarr = array();
      foreach($equipment as $val) {
          if(isset($returnarr[$val['pid']])) {
            //dump($val['pid']);die;
            $returnarr[$val['pid']]['statistics_date'] = $end;
              $returnarr[$val['pid']]['hardware_failure_time'] += $val['hardware_failure_time'];
              $returnarr[$val['pid']]['silver_game_times'] += $val['silver_game_times']; 
              $returnarr[$val['pid']]['gold_game_times'] += $val['gold_game_times']; 
              $returnarr[$val['pid']]['run_count'] += $val['run_count'];  
              $returnarr[$val['pid']]['success_number'] += $val['success_number']; 
              $returnarr[$val['pid']]['fail_number'] += $val['fail_number']; 
              $returnarr[$val['pid']]['silver_game_win_times'] += $val['silver_game_win_times']; 
              $returnarr[$val['pid']]['silver_game_lose_times'] += $val['silver_game_lose_times']; 
              $returnarr[$val['pid']]['gold_game_win_times'] += $val['gold_game_win_times']; 
              $returnarr[$val['pid']]['gold_game_lose_times'] += $val['gold_game_lose_times']; 
              $returnarr[$val['pid']]['gift_out_count'] += $val['gift_out_count']; 
              $returnarr[$val['pid']]['income_count'] += $val['income_count']; 
              //$returnarr[$val['managerid']]['create_time'] = $val['create_time']; 
              $returnarr[$val['pid']]['pid'] = $val['pid']; 
              //$returnarr[$val['pid']]['username'] = $val['username']; 
              $returnarr[$val['pid']]['create_time'] = time(); 
              //$returnarr[$val['managerid']]['amount'] += $val['amount']; 
          } else {
            $returnarr[$val['pid']]['statistics_date'] = $end;
            $returnarr[$val['pid']]['pid'] = $val['pid']; 
              $returnarr[$val['pid']]['hardware_failure_time'] = $val['hardware_failure_time'];
              $returnarr[$val['pid']]['silver_game_times'] = $val['silver_game_times']; 
              $returnarr[$val['pid']]['gold_game_times'] = $val['gold_game_times']; 
              $returnarr[$val['pid']]['run_count'] = $val['run_count'];  
              $returnarr[$val['pid']]['success_number'] = $val['success_number']; 
              $returnarr[$val['pid']]['fail_number'] = $val['fail_number']; 
              $returnarr[$val['pid']]['silver_game_win_times'] = $val['silver_game_win_times']; 
              $returnarr[$val['pid']]['silver_game_lose_times'] = $val['silver_game_lose_times']; 
              $returnarr[$val['pid']]['gold_game_win_times'] = $val['gold_game_win_times']; 
              $returnarr[$val['pid']]['gold_game_lose_times'] = $val['gold_game_lose_times']; 
              $returnarr[$val['pid']]['gift_out_count'] = $val['gift_out_count']; 
              $returnarr[$val['pid']]['income_count'] = $val['income_count']; 
              //$returnarr[$val['managerid']]['create_time'] = $val['create_time'];
              //$returnarr[$val['pid']]['username'] = $val['username']; 
              $returnarr[$val['pid']]['create_time'] = time(); 
              //$returnarr[$val['managerid']]['amount'] += $val['amount']; 
          }
      }
      //dump($returnarr);die;
  
      M('partner_month_statistics')->addAll($returnarr);
    }
    //商户年统计
      public function year_year(){  
      $time = time();
      $date = date('Y',$time) - 1;//一年前日期
      $first=$date."-01-01";$end=$date."-12-31";
      $equipment = M("equipment")
      ->alias("t1")
      ->field("FROM_UNIXTIME(t2.statistics_date,'%Y%m%d') days,t2.*,t1.name,t3.id as pid,t3.username")
      ->where("t1.pid in ({$id})")
      ->where("t2.statistics_date between $first and $end")
      ->join("left join equipment_year_statistics t2 on t2.equipment_id = t1.id")
      ->join("left join manager as t3 on t3.id = t1.pid")
      ->select();
      //dump($equipment);
      $returnarr = array();
      foreach($equipment as $val) {
          if(isset($returnarr[$val['pid']])) {
            //dump($val['pid']);die;
            $returnarr[$val['pid']]['statistics_date'] = $end;
              $returnarr[$val['pid']]['hardware_failure_time'] += $val['hardware_failure_time'];
              $returnarr[$val['pid']]['silver_game_times'] += $val['silver_game_times']; 
              $returnarr[$val['pid']]['gold_game_times'] += $val['gold_game_times']; 
              $returnarr[$val['pid']]['run_count'] += $val['run_count'];  
              $returnarr[$val['pid']]['success_number'] += $val['success_number']; 
              $returnarr[$val['pid']]['fail_number'] += $val['fail_number']; 
              $returnarr[$val['pid']]['silver_game_win_times'] += $val['silver_game_win_times']; 
              $returnarr[$val['pid']]['silver_game_lose_times'] += $val['silver_game_lose_times']; 
              $returnarr[$val['pid']]['gold_game_win_times'] += $val['gold_game_win_times']; 
              $returnarr[$val['pid']]['gold_game_lose_times'] += $val['gold_game_lose_times']; 
              $returnarr[$val['pid']]['gift_out_count'] += $val['gift_out_count']; 
              $returnarr[$val['pid']]['income_count'] += $val['income_count']; 
              //$returnarr[$val['managerid']]['create_time'] = $val['create_time']; 
              $returnarr[$val['pid']]['pid'] = $val['pid']; 
              //$returnarr[$val['pid']]['username'] = $val['username']; 
              $returnarr[$val['pid']]['create_time'] = time(); 
              //$returnarr[$val['managerid']]['amount'] += $val['amount']; 
          } else {
            $returnarr[$val['pid']]['statistics_date'] = $end;
            $returnarr[$val['pid']]['pid'] = $val['pid']; 
              $returnarr[$val['pid']]['hardware_failure_time'] = $val['hardware_failure_time'];
              $returnarr[$val['pid']]['silver_game_times'] = $val['silver_game_times']; 
              $returnarr[$val['pid']]['gold_game_times'] = $val['gold_game_times']; 
              $returnarr[$val['pid']]['run_count'] = $val['run_count'];  
              $returnarr[$val['pid']]['success_number'] = $val['success_number']; 
              $returnarr[$val['pid']]['fail_number'] = $val['fail_number']; 
              $returnarr[$val['pid']]['silver_game_win_times'] = $val['silver_game_win_times']; 
              $returnarr[$val['pid']]['silver_game_lose_times'] = $val['silver_game_lose_times']; 
              $returnarr[$val['pid']]['gold_game_win_times'] = $val['gold_game_win_times']; 
              $returnarr[$val['pid']]['gold_game_lose_times'] = $val['gold_game_lose_times']; 
              $returnarr[$val['pid']]['gift_out_count'] = $val['gift_out_count']; 
              $returnarr[$val['pid']]['income_count'] = $val['income_count']; 
              //$returnarr[$val['managerid']]['create_time'] = $val['create_time'];
              //$returnarr[$val['pid']]['username'] = $val['username']; 
              $returnarr[$val['pid']]['create_time'] = time(); 
              //$returnarr[$val['managerid']]['amount'] += $val['amount']; 
          }
      }
      //dump($returnarr);die;
  
      M('partner_year_statistics')->addAll($returnarr);
    }

// public function partner_day(){
//       //$manager = session("manager_info.id");
//       $date = array(
//         array('days'=>(date("Ymd",strtotime("-1 day")))),//昨天时间
//         );
//        $days = M("equipment_day_statistics")->alias("t1")->field("FROM_UNIXTIME(t1.statistics_date,'%Y%m%d') days,t1.*,t2.pid")->join("left join equipment as t2 on t2.id = t1.equipment_id")->select(); 
//        //dump($days);die;
//        $days2 = $date;
//        foreach ($days2 as $key => &$value) {
//         foreach ($days as $v) {
//           if($value['days'] == $v['days']){
//             $value['hardware_failure_time']+=$v['hardware_failure_time'];//保修次数
//             $value['silver_game_times']+=$v['silver_game_times'];
//             $value['gold_game_times']+=$v['gold_game_times'];
//             $value['run_count']+=$v['run_count'];
//             $value['success_number']+=$v['success_number'];
//             $value['fail_number']+=$v['fail_number'];
//             $value['silver_game_win_times']+=$v['silver_game_win_times'];
//             $value['silver_game_lose_times']+=$v['silver_game_lose_times'];
//             $value['gold_game_win_times']+=$v['gold_game_win_times'];
//             $value['gold_game_lose_times']+=$v['gold_game_lose_times'];
//             $value['gift_out_count']+=$v['gift_out_count'];
//             $value['income_count']+=$v['income_count'];
//             $value['pid'] = $v['pid'];
//             $value['create_time'] = $v['create_time'];
//           }
//         }
//        }
//       $time = strtotime($value['days']);//时间转换成时间戳
//        $partner_day_statistics = array(
//           'statistics_date'=>$time,
//           'hardware_failure_time'=>$value['hardware_failure_time'],
//           'silver_game_times'=>$value['silver_game_times'],
//           'gold_game_times'=>$value['gold_game_times'],
//           'run_count'=>$value['run_count'],
//           'success_number'=>$value['success_number'],
//           'fail_number'=>$value['fail_number'],
//           'silver_game_win_times'=>$value['silver_game_win_times'],
//           'silver_game_lose_times'=>$value['silver_game_lose_times'],
//           'gold_game_win_times'=>$value['gold_game_win_times'],
//           'gold_game_lose_times'=>$value['gold_game_lose_times'],
//           'gift_out_count'=>$value['gift_out_count'],
//           'income_count'=>$value['income_count'],
//           'pid'=>$value['pid'],
//           'create_time'=>time(),
//         );
//        dump($partner_day_statistics);die;
//       //if(time()==$value['create_time']){
//         M("partner_day_statistics")->add($partner_day_statistics);
//         echo "操作已完成 请关闭页面";
//         flush();

//       //}     
//       //  $partner_day = M("partner_day_statistics")->alias("t1")->field("FROM_UNIXTIME(t1.statistics_date,'%Y%m%d') days,FROM_UNIXTIME(t1.create_time,'%Y%m%d') day,t1.*")->where(['t1.pid'=>$manager])->select();
//       //  $this->assign("day",$partner_day);
//       // $this->display();
//     }
//     public function month(){
//       //$manager = session("manager_info.id");
//       $thismonth = date('m');
//           $thisyear = date('Y');
//           if ($thismonth == 1) {
//            $lastmonth = 12;
//            $lastyear = $thisyear - 1;
//           } else {
//            $lastmonth = $thismonth - 1;
//            $lastyear = $thisyear;
//           }
//           $lastStartDay = $lastyear . '-' . $lastmonth . '-1';
//           $lastEndDay = $lastyear . '-' . $lastmonth . '-' . date('t', strtotime($lastStartDay));
//         //$begin_time = date('Ym',strtotime('-1 month'));//只有年月
//           $b_time = strtotime($lastStartDay);//上个月的月初时间戳
//           $e_time = strtotime($lastEndDay)+(60*60*24-1);//上个月的月末时间戳
//       $month = M("equipment_month_statistics")->alias("t1")->field("FROM_UNIXTIME(t1.statistics_date,'%Y%m%d') days,t1.*,t2.pid")->where("t1.statistics_date between $b_time and $e_time")->join("left join equipment as t2 on t2.id = t1.equipment_id")->select();
//       //dump($month);die;
//       foreach ($month as $key => $v) {
//             $Luna['hardware_failure_time']+=$v['hardware_failure_time'];//保修次数
//             $Luna['silver_game_times']+=$v['silver_game_times'];
//             $Luna['gold_game_times']+=$v['gold_game_times'];
//             $Luna['run_count']+=$v['run_count'];
//             $Luna['success_number']+=$v['success_number'];
//             $Luna['fail_number']+=$v['fail_number'];
//             $Luna['silver_game_win_times']+=$v['silver_game_win_times'];
//             $Luna['silver_game_lose_times']+=$v['silver_game_lose_times'];
//             $Luna['gold_game_win_times']+=$v['gold_game_win_times'];
//             $Luna['gold_game_lose_times']+=$v['gold_game_lose_times'];
//             $Luna['gift_out_count']+=$v['gift_out_count'];
//             $Luna['income_count']+=$v['income_count'];
//             $Luna['pid'] = $v['pid'];
//             $Luna['create_time'] = $v['create_time'];
//       }
//       //dump($Luna);die;
//       $monthlast = array(
//           'statistics_date'=>$b_time,
//           'hardware_failure_time'=>$Luna['hardware_failure_time'],
//           'silver_game_times'=>$Luna['silver_game_times'],
//           'gold_game_times'=>$Luna['gold_game_times'],
//           'run_count'=>$Luna['run_count'],
//           'success_number'=>$Luna['success_number'],
//           'fail_number'=>$Luna['fail_number'],
//           'silver_game_win_times'=>$Luna['silver_game_win_times'],
//           'silver_game_lose_times'=>$Luna['silver_game_lose_times'],
//           'gold_game_win_times'=>$Luna['gold_game_win_times'],
//           'gold_game_lose_times'=>$Luna['gold_game_lose_times'],
//           'gift_out_count'=>$Luna['gift_out_count'],
//           'income_count'=>$Luna['income_count'],
//           'pid'=>$Luna['pid'],
//           'create_time'=>time(),
//         );

//       //if(time() == $Luna['create_time']){
//         M("partner_month_statistics")->add($monthlast);
//       // }
//       // $partner_month = M("partner_month_statistics")->alias("t1")->field("FROM_UNIXTIME(t1.statistics_date) monthstart,FROM_UNIXTIME(t1.create_time) monthlast,t1.*")->where(['t1.pid'=>$manager])->select();
//       // //dump($partner_month);die;
//       // $this->assign("month",$partner_month);
//       // $this->display();
//     }
//     public function year(){
//      // $manager = session('manager_info.id');
//          $last_year_first = strtotime(date('Y-01-01', strtotime('-1 year')));//上年初
//       $last_year_last =  strtotime(date('Y-m-d',$last_year_first)."+12 month -1 day")+(60*60*24-1);//上年尾
//       $year = M("equipment_year_statistics")->alias("t1")->field("FROM_UNIXTIME(t1.statistics_date,'%Y%m%d') days,t1.*,t2.pid")->where("t1.statistics_date between $last_year_first and $last_year_last")->join("left join equipment as t2 on t2.id = t1.equipment_id")->select();
//       //dump($year);die;
//       foreach ($year as $key => $v) {
//             $year_end['hardware_failure_time']+=$v['hardware_failure_time'];//保修次数
//             $year_end['silver_game_times']+=$v['silver_game_times'];
//             $year_end['gold_game_times']+=$v['gold_game_times'];
//             $year_end['run_count']+=$v['run_count'];
//             $year_end['success_number']+=$v['success_number'];
//             $year_end['fail_number']+=$v['fail_number'];
//             $year_end['silver_game_win_times']+=$v['silver_game_win_times'];
//             $year_end['silver_game_lose_times']+=$v['silver_game_lose_times'];
//             $year_end['gold_game_win_times']+=$v['gold_game_win_times'];
//             $year_end['gold_game_lose_times']+=$v['gold_game_lose_times'];
//             $year_end['gift_out_count']+=$v['gift_out_count'];
//             $year_end['income_count']+=$v['income_count'];
//             $year_end['pid'] = $v['pid'];
//             $year_end['create_time'] = $v['create_time'];
//       }
//     //dump($year_end);die;
//       $yearlast = array(
//           'statistics_date'=>$last_year_first,
//           'hardware_failure_time'=>$year_end['hardware_failure_time'],
//           'silver_game_times'=>$year_end['silver_game_times'],
//           'gold_game_times'=>$year_end['gold_game_times'],
//           'run_count'=>$year_end['run_count'],
//           'success_number'=>$year_end['success_number'],
//           'fail_number'=>$year_end['fail_number'],
//           'silver_game_win_times'=>$year_end['silver_game_win_times'],
//           'silver_game_lose_times'=>$year_end['silver_game_lose_times'],
//           'gold_game_win_times'=>$year_end['gold_game_win_times'],
//           'gold_game_lose_times'=>$year_end['gold_game_lose_times'],
//           'gift_out_count'=>$year_end['gift_out_count'],
//           'income_count'=>$year_end['income_count'],
//           'pid'=>$year_end['pid'],
//           'create_time'=>time(),
//         );
//     //dump($yearlast);die;
//       //if(time() == $year_end['create_time']){
//         M("partner_year_statistics")->add($yearlast);
//        //}
//       // $partner_year = M("partner_year_statistics")->alias("t1")->field("FROM_UNIXTIME(t1.statistics_date) yearstart,FROM_UNIXTIME(t1.create_time) yearlast,t1.*")->where(['t1.pid'=>$manager])->select();
//       // $this->assign("year",$partner_year);
//       // $this->display();
//     }


  //机台月志的存储过程
      public function equipment_month_statistics(){
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
    $star = strtotime($lastStartDay);//上个月的月初时间戳
    $end = strtotime($lastEndDay)+60*60*24-1;//上个月的月末时间戳

    $month_all = M('equipment_day_statistics')->select();

        $equipment_all=M('equipment')
        ->field("id as equipment_id")
        ->select();
            foreach ($month_all as $key => $value) {
              foreach($equipment_all as $k => &$v){
                if($v['equipment_id']==$value['equipment_id']){

                    foreach($value as $ke => $val ){
                      if($ke != 'id' && $ke != 'equipment_id' && $ke != 'statistics_date' && $ke != 'create_time'){
                        $v[$ke] += $val;
                        $v['statistics_date'] = $star;
                        $v['create_time'] = time();
                      }
                   }

                }

              }
            }

            $statistics = M('equipment_month_statistics')->addAll($equipment_all);
             echo "操作已完成 请关闭页面";
             flush();
       
  }
}