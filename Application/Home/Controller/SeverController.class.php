<?php

namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");
class SeverController extends Controller{

		//HTTP://账户服务器/Server/user_auth/
		public function user_auth(){
			//获取到从服务器接收到的数据,转换成数组
			$params = $GLOBALS['HTTP_RAW_POST_DATA'];  	file_put_contents("server.txt",$params);       
    		$params = json_decode($params,true); 	
    		$user = M('all_user')->where(['id'=>$params['userid']])->find();
    		if(time()-$params['timestamp']>10){
    			$data = array(
    				'errid' => 10001,
    				'timestamp' => time(),
    				);
    		}elseif (!$user) {
    			$data = array(
    				'errid' => 10003,
    				'errmsg' => 'auth error',
    				);
    		}else{
    			//验证成功,返回用户数组ID,用户昵称,头像地址
    			$data = array(
    				'userid' => $user['id'],
    				'username' => $user['nick'],
    				'avatar'  => $user['head'],
    				); 
    		}
    		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
       		echo $data;
		}

		public function payment(){
			$params = $GLOBALS['HTTP_RAW_POST_DATA'];         
            $params = json_decode($params,true);
            $type = $params['msgtype'] ? $params['msgtype'] : "";
            //dump($type);die;
            switch ($type) {
            	case 'payment_request':
            		
            		echo $this->payment_request($params);
            		break;
            	case 'payment_cancel':
            		// echo 22222;die;
            		echo $this->payment_cancel($params);
            		break;
            	case 'game_result':
            		echo $this->game_result($params);
            		break;
            	default:
            		$data = array(
            			'msgtype' => 'error',
            			'params' => array(
            				'errid' => 403,
            				'errmsg' => 'msgtype error',
            				),
            			);
            		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
                    echo $data;
            }
		}

		// public function payment_request($params){
		// 	if (time()-$params['timestamp']>10) {
		// 		$data = array(
		// 			'errid' => 10001,
		// 			'timestamp' => time(),
		// 			);
		// 	}else{
		// 		$user = M('all_user')->where(['id'=>$params['userid']])->find();
		// 		$type = $params['type'];

		// 		if ($type == "silver") {
		// 			$silver = $user['silver'] - $params['amount'];
		// 			if ($silver<0) {
		// 				$data = array(
		// 					'errid' => 40001,
		// 					'errmsg' => 'The balance of account is insufficient',
		// 					);
		// 				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		// 				return $data;
		// 			}else{
		// 				$user['sliver'] = $user['silver'] - $params['amount'];
		// 			}
		// 		}else{
		// 			//$type == "gold"
		// 			$gold = $user['gold'] - $params['amount'];
		// 			if ($gold<0) {
		// 				$data = array(
		// 					'errid' => 40001,
		// 					'errmsg' => 'The balance of account is insufficient',
		// 					);
		// 				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		// 				return $data;
		// 			}else{
		// 				$user['gold'] = $user['gold'] - $params['amount'];
		// 			}
		// 		}
		// 		//可以扣款
		// 		// $user['"$type"'] = $user['"$type"'] - $params['amount'];
		// 		//添加消费记录
		// 		$paymentid = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);//消费记录流水号

		// 		$equipment = M('Equipment')->alias("t1")->field("t3.pics_origin,t2.name as goods_name,t2.id as roomid,t1.id as machineid")->where(['t1.id'=>$params['machineid']])->join("left join goods as t2 on t2.id = t1.goods_id")->join("left join goodspics as t3 on t3.goods_id = t2.id")->find();
		// 		M('all_user')->save($user);//修改用户的金币余额
		// 		//添加这个userid的消费记录到record表中
		// 		$record = array(
		// 			'userid' => $user['id'],
		// 			'roomid' => $equipment['roomid'],
		// 			'goodsname' =>  $equipment['goods_name'],
		// 			'photo' => $equipment['pics_origin'],
		// 			'equipment_id' => $equipment['machineid'],
		// 			'type' => $type,//消费的币种类型
		// 			'amount' => $params['amount'],
		// 			'paymentid' => $paymentid,
		// 			'cancel' => 0,  //是否被撤销扣款
		// 			);
		// 		M('Record')->add($record);
		// 		$log = array(
		// 			'userid' => $user['id'],
		// 			'type' => $params['type'],
		// 			'paymentid'=>$paymentid,

		// 			);
		// 		$gamelogid = M('tbl_game_log')->add($log);
		// 		// session('log',$gamelogid);
		// 		$user = M('all_user')->where(['id'=>$params['userid']])->find();
		// 		$data = array(
		// 			'msgtype' => 'payment_success',
		// 			'userid'  => $user['id'],
		// 			'paymentid' => $paymentid,
		// 			'machineid' => $params['machineid'],
		// 			'amount' => $params['amount'],
		// 			'type' => $params['type'],
		// 			'silver' => $user['silver'],
		// 			'gold' => $user['gold'],
		// 			);	
		// 	}
		// 	$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		// 	return $data;
		// }
		


		//第二版
		public function payment_request($params){
				$user = M('all_user')->where(['id'=>$params['userid']])->find();
				$amount = M('equipment')->where(['id'=>$params['machineid']])->getField('price');
				
			if (time()-$params['timestamp']>10) {
				$data = array(
					'errid' => 10001,
					'timestamp' => time(),
					);
			}else{

				// $type = $params['type'];

				// if ($type == "silver") {
				// 	$silver = $user['silver'] - $params['amount'];
				// 	if ($silver<0) {
				// 		$data = array(
				// 			'errid' => 40001,
				// 			'errmsg' => 'The balance of account is insufficient',
				// 			);
				// 		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				// 		return $data;
				// 	}else{
				// 		$user['sliver'] = $user['silver'] - $params['amount'];
				// 	}
				// }else{
				// 	//$type == "gold"
				// 	$gold = $user['gold'] - $params['amount'];
				// 	if ($gold<0) {
				// 		$data = array(
				// 			'errid' => 40001,
				// 			'errmsg' => 'The balance of account is insufficient',
				// 			);
				// 		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				// 		return $data;
				// 	}else{
				// 		$user['gold'] = $user['gold'] - $params['amount'];
				// 	}
				// }
				// 
				if ($user['gold']-$amount < 0 && $user['silver']-$amount < 0) {
					$data = array(
							'errid' => 40001,
							'errmsg' => 'The balance of account is insufficient',
							);
					$data = json_encode($data,JSON_UNESCAPED_UNICODE);
					return $data;
				}elseif($user['gold'] >= 0){
					$user['gold'] = $user['gold'] - $amount;
					$type = 'gold';
				}else{
					$user['silver'] = $user['silver'] - $amount;
					$type = 'silver';
				}
				//可以扣款
				// $user['"$type"'] = $user['"$type"'] - $params['amount'];
				//添加消费记录
				$paymentid = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);//消费记录流水号
				$paymentid = intval($paymentid)+mt_rand(1,100000000000);
				$equipment = M('Equipment')->alias("t1")->field("t3.pics_origin,t2.name as goods_name,t2.id as roomid,t1.id as machineid")->where(['t1.id'=>$params['machineid']])->join("left join goods as t2 on t2.id = t1.goods_id")->join("left join goodspics as t3 on t3.goods_id = t2.id")->find();
				M('all_user')->save($user);//修改用户的金币余额
				// 添加这个userid的消费记录到record表中
				$record = array(
					'userid' => $user['id'],
					'roomid' => $equipment['roomid'],
					'goodsname' =>  $equipment['goods_name'],
					'photo' => $equipment['pics_origin'],
					'equipment_id' => $equipment['machineid'],
					'type' => $type,//消费的币种类型
					// 'amount' => $params['amount'],
					'amount' => $amount,
					'paymentid' => $paymentid,
					'cancel' => 0,  //是否被撤销扣款
					);
				M('Record')->add($record);
				$log = array(
					'userid' => $user['id'],
					// 'type' => $params['type'],
					'type' => $type,
					'paymentid'=>$paymentid,

					);
				$gamelogid = M('tbl_game_log')->add($log);
				// session('log',$gamelogid);
				$user = M('all_user')->where(['id'=>$params['userid']])->find();
				$data = array(
					'msgtype' => 'payment_success',
					'userid'  => intval($user['id']),
					'paymentid' => $paymentid,
					'machineid' => $params['machineid'],
					// 'amount' => $params['amount'],
					// 'amount' => $amount,
					// 'type' => $params['type'],
					// 'type' => $type,
					// 'silver' => $user['silver'],
					// 'gold' => $user['gold'],
					);	
			}
			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			return $data;
		}


		public function payment_cancel($params){
			$user = M('all_user')->where(['id'=>$params['userid']])->find();
			$params['type'] = M('tbl_game_log')->where(['paymentid'=>$params['paymentid']])->getField('type');
			$params['amount'] = M('record')->where(['paymentid'=>$params['paymentid']])->gitField('amount');
			if ($params['type'] == "silver") {
				$user['silver'] = $user['silver'] + $params['amount'];
			}else{
				$user['gold'] = $user['gold'] + $params['amount'];
			}
			M('Record')->where(['paymentid'=>$params['paymentid']])->save(['cancel'=>1]);
			M('all_user')->where(['id'=>$params['userid']])->save($user);
			$data = array(
				'msgtype' => 'cancel_success',
				'userid'  => $user['id'],
				);
			
			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			return $data;
		}

		public function game_result($params){
			// $good = M('Goods')->where(['id'=>$params['roomid']])->find();
			$goods = M('Goods')->alias('t1')->field('t1.id as roomid')->where(['t2.id'=>$params['machineid']])->join("left join equipment as t2 on t2.goods_id = t1.id")->find();

			$res['equipment_id'] = $params['machineid'];
			$res['got_gift'] = $params['result'];
			$res['userid'] = $params['userid'];
			// $res['goods_id'] = $good['id'];
			$res['goods_id'] = $goods['roomid'];
			$res['end_time'] = time();
			
			// $res['paymentid'] = time()+100;//后期改
			// $result = M('tbl_game_log')->add($res);
			$result = M('tbl_game_log')->where(['paymentid'=>$params['paymentid']])->save($res);
			
			if ($result) {
				$data = array(
					'msgtype' => 'result_success',
					'userid'  => $params['userid'],
					);
			}
			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			var_dump($data);die;
			return $data;

		}
			public function test(){
			$equipment = M('Equipment')->alias("t1")->field("t3.pics_origin,t2.name as goods_name,t2.id as roomid,t1.id as machineid")->where(['t1.id'=>2])->join("left join goods as t2 on t2.id = t1.goods_id")->join("left join goodspics as t3 on t3.goods_id = t2.id")->find();
			$danhao = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
			dump($danhao);die;
			dump($equipment);die;
			}
			public function test_payment(){
				$data = array(
					'msgtype' => 'payment_request',
					'amount' => 2,
					'type' => 'gold',

					);
				$url = "http://www.machine.com";
			}
			
			
}	