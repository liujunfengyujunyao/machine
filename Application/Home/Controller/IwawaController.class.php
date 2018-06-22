<?php

namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class IwawaController extends Controller{
	
	public function __construct(){
		$this->sever_login();
	}
	
	public function iwawa(){

		$params = $GLOBALS['HTTP_RAW_POST_DATA'];

		$params = json_decode($params,true);//解析
		
		// var_dump($params);die;
		$type = $params['msgtype'] ? $params['msgtype'] : "";
		$result = $params['params'];

		// $params = json_encode($params,JSON_UNESCAPED_UNICODE);
		// print($params);die;
		switch ($type) {
			case 'client_auth':
				echo $this->client_auth($result);
				break;
			case 'get_current_user_info':
				echo $this->get_current_user_info($result);
				break;
			case 'payment_request':
				echo $this->payment_request($result);
				// echo "xxxx";
				break;
			case 'payment_cancel':
				echo $this->payment_cancel($result);
				break;
			case 'game_result':
				echo $this->game_result($result);
				break;
			default:
				$data = array(
					'msgtype'=>'error',
					'params' => array(
						'errid' => 403,
						'errmsg' => 'msgtype error'
						),
					);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;
				
		}
	}


	public function sever_login(){
		// $url = "http://47.96.66.139/iwawa/server_login";
		$url ="http://wwj.94zl.com/request";
		$data = array(
			'appid' => 'iwawa',
			'timestamp' => time(),
			);
		$return = json_curl($url,$data);

		return $return;
	}

	public function client_auth($result){

		// if ($result['owner']==9) {
		// 	$url = "http://wwj.94zl.com/iwawa/client_auth";
		// }else{
		// 	$url = "http://wwj2.94zl.com/iwawa/client_auth"
		// }
		$url = "http://wwj.94zl.com/iwawa/client_auth";
		//params中的数据是客户端发送给爱娃娃服务器的登陆数据
		
		$data = array(
       		 'params' => $result,
       		 'timestamp' => 111,
       		 "signature" => "测试",
			);

		$return = json_curl($url,$data);

		// $return = json_decode($return,1);
		//接入方返回用户的uuid(唯一标识)
		// $useruuid = $return['useruuid'];
		// return $useruuid;
		return $return;
	}

	public function get_current_user_info($result){

		
		$data = array(
			'useruuid' => $result['userid'],
			'timestamp' => time(),
			'signature' => "测试",
			); 
		$url = "http://wwj.94zl.com/iwawa/get_current_user_info";
		$return = json_curl($url,$data);
		// $return = json_decode($return,1);
		return $return;
	}

	//扣款
	public function payment_request($result){
		
		$data['useruuid'] = M('all_user')->where(['id'=>$result['userid']])->getField('uuid');
		$data['paymentid'] = 111;
		$data['roomid'] = M('Equipment')->where(['id'=>$result['machineid']])->getField('goods_id');
		$data['machineid'] = $result['machineid'];
		$data['price'] = $result['amount'];
		$data['timestamp'] = $result['timestamp'];
		$data['signature'] = $result['signature'];
		
		
		// var_dump($result,JSON_UNESCAPED_UNICODE);die;
		$url = 'http://wwj.94zl.com/iwawa/payment_request';
		// $url = "http://www.machine.com/Home/Diliang/test2";
		$return = json_curl($url,$data);

		// var_dump($return);die;
		return $return;
	}
	//取消扣款
	public function payment_cancel($result){
		
		$data['useruuid'] = M('all_user')->where(['id'=>$result['userid']])->getField('uuid');
		$data['paymentid'] = $result['paymentid'];
		$data['roomid'] = M('Equipment')->where(['id'=>$result['machineid']])->getField('goods_id');
		$data['machineid'] = $result['machineid'];
		$data['price'] = $result['amount'];
		// $data['timestamp'] = $result['timestamp'];
		// $data['signature'] = $result['signature'];
		$data['timestamp'] = time();
		$data['signature'] = "测试";
		
		$url = "http://wwj.94zl.com/iwawa/payment_cancel";
		// var_dump($data);die;
		// $url = "http://www.machine.com/Home/Diliang/test3";
		$return = json_curl($url,$data);
		return $return;
	}


	//发送游戏结果
	public function game_result($result){
		

		//添加客户端的判断
		// if ($result['uuid']) {
		
		// }
		// $good = M('Goods')->where(['id'=>$result['roomid']])->find();
		// $res['equipment_id'] = $result['machineid'];
		// $res['got_gift'] = $result['result'];
		// $res['userid'] = $result['userid'];
		// $res['uuid'] = M('All_user')->where(['id'=>$result['userid']])->getField('uuid');
		// $res['goods_id'] = $good['id'];
		// $res['tag'] = time()+100;//后期改
		// $result = M('tbl_game_log')->add($res);
		$url = 'http://wwj.94zl.com/iwawa/game_result';
		// $url = "http://www.machine.com/Home/Diliang/test";
		$return = json_curl($url,$result);
		
		return $return;
	}

	
	

}

