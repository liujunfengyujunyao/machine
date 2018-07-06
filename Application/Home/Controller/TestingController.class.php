<?php
namespace Home\Controller;
use Think\Controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class TestingController extends Controller{
	public function get_current_user_info(){
		$data = array(
			'useruuid' => '2',
			'timestamp' => 111,
			'signature' => '测试',
			);
		$url = "http://wwj.94zl.com/iwawa/get_current_user_info";
		$return = json_curl($url,$data);
		dump($return);die;
	}
	public function payment_request(){
		$data = array(
			'useruuid' => 2,
			'paymentid' => 111,
			'roomid' => 4,
			'machineid' => 2,
			'price' => 2,
			'timestamp' => 111,
			'signature' => '测试',
			);
		$url = "http://wwj.94zl.com/iwawa/payment_request";
		$return = json_curl($url,$data);
		dump($return);die;
	}
	public function payment_cancel(){
		$data = array(
			'useruuid' => 1,
			'paymentid' => 111,
			'roomid' => 4,
			'machineid' => 2,
			'price' => 2,
			'timestamp' => 111,
			'signature' => '测试',
			);
		$url = "http://wwj.94zl.com/iwawa/payment_cancel";
		$return = json_curl($url,$data);
		dump($return);die;
	} 
	public function game_result(){
		$data = array(
			'useruuid' => 1,
			'roomid' => 4,
			'machineid' => 2,
			'gamelogid' => 100,
			'result' => 0,
			'timestamp' => 111,
			'signature' => '测试',
			);
		$url = "http://wwj.94zl.com/iwawa/game_result";
		$return = json_curl($url,$data);
		dump($return);die;
	}



	// public function payment(){
	// 	$data = array(
	// 		'msgtype' => 'payment_cancel',
	// 		'userid' => 2,
	// 		'paymentid' => 111,
	// 		'machineid' => 2,
	// 		'amount' => 2,
	// 		'type' => 'gold',
	// 		'timestamp' => 111,
	// 		'signature' => '测试',
	// 		);
	// 	$url = "http://www.machine.com/Home/Diliang/payment";
	// 	$return = json_curl($url,$data);
	// 	dump($return);die;
	// }

	public function get_game_logs(){
		$data = array(
			'userid' => 3,
			'timestamp' => time(),
			'signature' => '测试',
			);
		$url = "http://www.machine.com/Home/Diliang/get_game_logs";
		$return = json_curl($url,$data);
		dump($return);die;
	}

	public function payment(){

		$data = array(
			'msgtype' => 'payment_cancel',
			'userid' => 2,
			'paymentid' => "2147483647",
			'machineid' => 2,
			'amount' => 2,
			'type' => "gold",
			'timestamp' => time(),
			'signature' => "测试",
			);
		$url = "http://www.machine.com/Home/Sever/payment";
		$return = json_curl($url,$data);
		dump($return);die;
	}

	

	public function get_payment_logs(){
		$data = array(
			'userid' => 1,
			'timestamp' => time(),
			'signature' => '测试',
			);
		$url = "http://www.machine.com/Home/Useraccount/get_payment_logs";
		$return = json_curl($url,$data);
		dump($return);die;
	}

	public function create_comment(){
		$data = array(
			'userid' => 2,
			'message' => "beijingyouxikaifa",
			'timestamp' => time(),
			'signature' => "测试",
			);
		$url = "http://www.machine.com/Home/Useraccount/create_comment";
		$return = json_curl($url,$data);
		dump($return);die;
	}

	public function userlogin(){
		$data = array(
			'params' => array(
				'id' => 1,
				),
			
			);
		$url = "http://wwj.94zl.com/iwawa/client_auth";
		$return = json_curl($data,$url);
		dump($return);die;
	}

	public function game(){
		$data = array(
			'msgtype' => 'game_result',
			'userid' => 2,
			'roomid' => 4,
			'machineid' => 2,
			'result' => 0,
			);
		$url = "http://www.machine.com/Home/Diliang/payment";
		$return = json_curl($url,$data);
		dump($return);die;
	}
	
	public function user(){
		// $data = array(
		// 	'params' => array(
		// 		'id' => 3,
		// 		),
		// 	'timestamp' => 111,
		// 	'signature' => '测试',
		// 	);
		// $url = "http://wwj.94zl.com/iwawa/client_auth";测试可以
		$data = array(
			'id' => 10,
			);
		$url = "http://www.machine.com/Home/Diliang/userlogin"; 
		$return = json_curl($url,$data);
		dump($return);die;
	}

	public function game_cancel2(){
		$data = array(
			'msgtype' => 'payment_cancel',
			'userid' => 2,
			'paymentid' => 111,

			'machineid' => 2,
			'amount' => 2,
			
			);
		$url = "http://www.machine.com/Home/Diliang/payment";
		$return = json_curl($url,$data);
		dump($return);die;
	}

	public function p(){
		$data = array(
			'msgtype' => 'game_result',
			'userid' => 1000,
			'result' => 100,
			);
		$url = "http://www.machine.com/Home/Diliang/payment";
		$return = json_curl($url,$data);
		dump($return);die;
	}



	public function put(){
		$data = array(
			'msgtype' => 'payment_request',
			'userid' => 2,
			'machineid' => 2,
			'amount' => 2,
			'timestamp' => time(),
			'signature' => "测试",
			);
		$url = "http://www.machine.com/Home/Diliang/payment";
		$return = json_curl($url,$data);
		dump($return);die;
		
	}

	public function wawa(){
		$data = array(
			'id' => 1,
			);
		$url = "http://www.machine.com/Home/Diliang/userlogin";
		$return = json_curl($url,$data);
		dump($return);die;
	}

	public function wawa2(){
		$data = array(

			);
		$url = "http://www.machine.com/Home/Diliang/get_room_types";
		$return = json_curl($url,$data);
		dump($return);die;
	}


	public function available11(){
		$userid  = 1;
	  $rechargelogs = M()->db(2,"DB_CONFIG2")->table("order_log")->where("userid = $userid && status = 0")->select();
	     foreach ($rechargelogs as $key => $value) {
                    $data[$key]['rechargelogsid'] = $value['id'];
                    // $data[$key]['amount'] = M('order')->where(['id'=>$value['order_id']])->getField('money');
                    $data[$key]['amount'] = M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('money');
                    // $data[$key]['awardamount'] = M('order')->where(['id'=>$value['order_id']])->getField('amount');
                    $data[$key]['awardamount'] = M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('amount');
                    $data[$key]['awardtype'] = 'gold';
                    $data[$key]['data'] = $value['create_time'];
                }
            dump($data);die;
	}
	public function shijian(){
		$x = time();
		dump($x);die;
	}

	public function bl(){
		
		$signature = array(
			'userid' => 1,
			'timestamp' => time(),
			'access_token' => "abc",
			);
		
		$signature = sha1(json_encode($signature));
		$data = array(
			'userid' => 1,
			'timestamp' => time(),
			'signature' => $signature,
			);
		$url = "http://192.168.1.164/home/Useraccount/get_recharge_logs";
		$return = json_curl($url,$data);
		dump($return);die;
	}

	public function enter_room(){
		$params['roomid'] = 4;
		   $machines = M('Equipment')->where(['goods_id'=>$params['roomid']])->getField('id',true);
		   dump($machines);die;
	}

	public function get_banner_pictures(){
		$url = "http://192.168.1.164/home/rooms/get_banner_pictures";
		$return = json_curl($url);
		dump($return);
	}

	public function tbl(){
		$data = array(
			'msgtype' => 'game_result',
			'userid' => 1,
			'roomid' => 4,
			'machineid' => 2,
			'result' => 1,
			);
		$url = "http://192.168.1.164/Home/Sever/payment";
		$return = json_curl($url,$data);
		dump($return);die;
	}
	public function order(){
		$data = array(
			'userid' => 1,
			'gamelogid' => 525,
			'roomid' => 4,
			'name' => "王刚",
			'tel' => 18888888888,
			'address' => "美国",
			'signature' => "2d757ce2e3d09850114aa560dbf93f95f76d5b26",
			);
	}
	public function t(){
		$data = array(
			'userid' => "1",
			'gamelogid' => 525,
			'roomid' => 4,
			'name' => "李刚",
			'tel'=>13344444444,
			'addresss' => '美国',
			'timestamp' => 1528194649,
			'access_token' => "e8eda0432bda568efaa5c2c5513a8410ef2847c7",
			);
		$data = json_encode($data);
		dump(sha1($data));die;
	}

	public function again(){
		$url = "http://www.12202.com.cn/diamond/index.php/Home/index/Weixinpay_js1/userid/1";
		$return = json_curl($url);
		var_dump($return);die;
	}

	public function js(){
		$data = array(
			'userid' => 1,
			'roomid' => 4,
			'name'=>'鱼君瑶',
			'gamelogid' => array(546,547),
			'tel'=>110,
			'addresss'=>'fengtai',
			);
		$url = "http://192.168.1.164/home/Testing/js2";
		$return = json_curl($url,$data);
		dump($return);die;
	}
	public function js2(){
		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
		$params = json_decode($params,true);
		$gamelogid = implode(',',$params['gamelogid']);
		 M('tbl_game_log')->where("id in ($gamelogid)")->save(['status'=>1]);
            foreach($params['gamelogid'] as $k=>$v){
                $one['create_time'] = time();
                $one['address'] = $params['addresss'];
                $one['phone'] = $params['tel'];
                $one['userid'] = $params['userid']; 
                $one['name'] = $params['name']; 
                $one['roomid'] = $params['roomid']; 
                $one['log_id'] = $v;
                $save[] = $one;
            }
            $order_id = M('tbl_order')->addAll($save);
            $data = array(
                'orderlogid' => $order_id,
                );
		var_dump($data);die;
	}

	public function pay(){
		$data = array(
			'userid' => 1,
			'gamelogid' => 1,
			);
		$url = "http://www.12202.com.cn/diamond/index.php/Home/index/express";
		$return = json_curl($url,$data);
		dump($return);die;

	}
	public function chai(){
		$data = array(

			);
		$url = "http://www.12202.com.cn/diamond/index.php/Home/index/weixinpay_js1/userid/1";
		$return = json_curl($url,$data);
		dump($return);die;
	}

	// $get = D('tbl_game_log')
	// 	->alias('t1')
	//  	->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,count(t1.id) count")
	//  	->where("t1.end_time between $seven and $today")
	//  	->where(['t1.equipment_id'=>2,'got_gift'=>1])
	//  	->Group('days')
	//  	->select();
	//  	$date=array(
	//  		array('days'=>date('Ymd',time())),
	//  		array('days'=>date('Ymd',strtotime('-1 days'))),
	//  		array('days'=>date('Ymd',strtotime('-2 days'))),
	//  		array('days'=>date('Ymd',strtotime('-3 days'))),
	//  		array('days'=>date('Ymd',strtotime('-4 days'))),
	//  		array('days'=>date('Ymd',strtotime('-5 days'))),
	//  		array('days'=>date('Ymd',strtotime('-6 days'))),
	//  	);
	//  	$get2=$date;
	//  	foreach($get2 as $k=>&$v){
	//  		foreach($get as $val){
	//  			if($v['days'] == $val['days']){
	//  				$v['count']=$val['count'];
	//  			}
	 			
	//  		}
	//  		if(!$v['count']){
	//  			$v['count']= '0';
	//  		}
	//  	}
	
	public function k(){
		$url = "www.12202.com.cn/diamond/index.php/Home/Weixinpay/pay";
		$return = json_curl($url);
		dump($return);die;
	}

	public function iii(){
		// dump(2);die;
		$url = "http://www.12202.com.cn/diamond/index.php/Home/Useraccount/create_order";
		//
		// $url = "http://192.168.1.164/Home/useraccount/get_game_logs";
		$data = array(
			'userid' => 1,
			'gamelogid'=>560,
			'timestamp' => time(),
			);
		$return = json_curl($url,$data);
		dump($return);die;
	}

	public function huancun(){
		$params = array(
			'userid' => 1,
			);

		// $params = json_encode($params,JSON_UNESCAPED_UNICODE);
		$data = array(
			'params' => 'userid'
			'timestamp' => time(),
			'signature' => '测试', 
			);
		$url = "http://wwj.94zl.com/iwawa/client_auth";

		$return = json_curl($url,$data);
		dump($retur);die;

	}



}