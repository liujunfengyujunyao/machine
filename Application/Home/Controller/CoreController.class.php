<?php
/**
 * User: Junfeng
 * Date: 2018/6/26
 * Time: 13:12
 */

namespace Home\Controller;
use Think\Controller;

class CoreController extends Controller{

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
    
		$statistics = M('equipment_day_statistics')->addAll($all9);
    echo "操作已完成 请关闭页面";
}

    
}