<?php

namespace Home\Controller;
use Think\Controller;
use Common\Plugin\WxLogin;
header("Content-type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class UserloginController extends Controller{

	public function get_code(){
		
		$config = C('wx_open');
		$obj = new Wxlogin($config);
		
		//前端接收code的地址
		// $redirect_url = "http://www.12202.com.cn/vue/#roomList/list1";
		// $redirect_url =  "http://192.168.1.171/#/roomList";
		// $redirect_url =  "https://www.goldenbrother.cn/app/index.html/#/roomList";
		$redirect_url =  "https://www.goldenbrother.cn/app/#/roomList";
		// $redirect_url = 'https://'.$_SERVER['HTTP_HOST'].'/app/index.html/#/roomlist';
		// $redirect_url = 'https://'.$_SERVER['HTTP_HOST'].'/app/#/roomlist';

		
		$url = $obj->getOauthurl($redirect_url);
		
		$data = array(
			'msgtype'=>'wechat_url',
			'wechat_url' => $url,
			);
		$this->ajaxReturn($data);//返回给前端获取code的回调地址
		// $data = json_encode($data,JSON_UNESCAPED_UNICODE);
		// dump($data);die;
		// return $data;
		// return $data;
	}

	public function login_auth(){
		
		$a = $GLOBALS['HTTP_RAW_POST_DATA']; 
	
		$a = json_decode($a,true); 
		$code = $a['wechat_code'];
		       
       	$referee = $a['referee'];
       	$model = $a['model'];
		// $config = C('wx_test');
		$config = C('wx_open');
        $obj = new Wxlogin($config);
		if (!$code) {
			$data = array(
				'msgtype'=>'error',
				'params'=>array(
					'errid' =>10002,
					'errmsg' => 'code error',
					),
				);
		}else{
			$wx_user = $obj->wx_log($code);
			if (!$wx_user) {
				$data = array(
					'msgtype' => 'error',
					'params' => array(
						'errid' => 10003,
						'errmsg' => 'auth error',
						),
					);
			}else{
			// $user = M('wx_user')->where(['openid'=>$wx_user->openid])->find();
			$user = M('all_user')->where(['openid'=>$wx_user->openid])->find();
			if (!$user&&$referee) {
				// $referee = M('wx_user')->where(['id'=>$referee])->find();
				$referee = M('all_user')->where(['id'=>$referee])->find();
				//奖励给推荐人的银币数量
				$referee_silver = 5;
				$referrals = $referee['referrals']+1;
				//不存在用户但(有推荐人),插入
				// $r = M('wx_user')->where(['id'=>$referee])->save(['referrals'=>$referrals]);
				$r = M('all_user')->where(['id'=>$referee])->save(['referrals'=>$referrals]);
				$data['openid'] = $wx_user->openid;
				$data['nick'] = $wx_user->nickname;
				$data['head'] = $wx_user->headimgurl;
				$data['gender'] = $wx_user->sex;//1:男 2:女 3:保密
				$data['type'] = 'oauth';
				$data['addtime'] = time();
				$data['referee'] = $referee;
				$data['model'] = $a['model'];
				$data['vendor'] = $a['vendor'];
				$data['os'] = $a['os'];
				$data['version'] = $a['version'];
				// $data['uuid'] = $model['uuid'];
				$data['access_token'] = encrypt_password(time());
				$access_token = $data['access_token'];
				// $id = M('wx_user')->add($data);
				$id = M('all_user')->add($data);
			}elseif(!$user&&!$referee){
				//不存在,插入
				$data['openid'] = $wx_user->openid;
				$data['nick'] = $wx_user->nickname;
				$data['head'] = $wx_user->headimgurl;
				$data['gender'] = $wx_user->sex;//1:男 2:女 3:保密
				$data['type'] = 'oauth';
				$data['addtime'] = time();
				// $data['model'] = $model['model'];
				$data['model'] = $a['model'];
				// $data['vendor'] = $model['vendor'];
				$data['vendor'] = $a['vendor'];
				// $data['os'] = $model['os'];
				$data['os'] = $a['os'];
				$data['version'] = $a['version'];
				// $data['uuid'] = $model['uuid'];
				$data['access_token'] = encrypt_password(time());
				$access_token = $data['access_token'];
				// $id = M('wx_user')->add($data);
				$id = M('all_user')->add($data);
			}else{
				//存在,更新会变更的数据
				$data['nick'] = $wx_user->nickname;
				$data['head'] = $wx_user->headimgurl;
				$data['gender'] = $wx_user->sex;
				// $data['model'] = $model['model'];
				$data['model'] = $a['model'];
				// $data['vendor'] = $model['vendor'];
				$data['vendor'] = $a['vendor'];
				// $data['os'] = $model['os'];
				$data['os'] = $a['os'];
				// $data['versaion'] = $model['versaion'];
				$data['version'] = $a['version'];
				// $data['uuid'] = $model['uuid'];
				// M('wx_user')->where(['openid'=>$wx_user->openid])->save($data);
				M('all_user')->where(['openid'=>$wx_user->openid])->save($data);
				$id = $user['id'];
			}
			$access_token = sha1(time());
			$_SESSION['accesstoken'] = $access_token;
			
			$_SESSION['userid'] = $id;
			S($id,$access_token);
			// M('all_user')->where(['id'=>$id])->save(['token'=>$access_token]);
			
			// file_put_contents('session.txt',$);
			$data = array(
				'msgtype' => 'userinfo',
				'params'  => array(
					'userid' => $id,
					'accesstoken' => $access_token,
					'chatserver' => "wss://www.goldenbrother.cn:5003/chat_server",
					),
				);
		}
		}
		// $this->ajaxReurn($data);
		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		echo $data;
		// return $data;
	}

	public function get_current_user_info(){
		$params = $GLOBALS['HTTP_RAW_POST_DATA'];

		$params = json_decode($params,true);
		
		
		$signature = array(
			'userid' => $params['userid'],
			'timestamp' => $params['timestamp'],
			'access_token' => $_SESSION['accesstoken'],
			// 'access_token' => "abc",
			);

		$signature = json_encode($signature);
		
		$signature = sha1($signature);
		// var_dump($params);var_dump($signature);die;
		
		if (!$params['userid']) {
			//参数为空
			$data = array(
				'msgtype' => 'error',
				'params' => array(
					'errid' => 403,
					'errmsg' => 'params error',
					),
				);
		}
		// elseif($params['signature'] != $signature){
		// 	$data = array(
		// 		'msgtype' => 'error',
		// 		'params' => array(
		// 			'errid' => 10003,
		// 			'errmsg' => 'signature error',
		// 			),
		// 		);
		// }
		elseif(time()-$params['timestamp']>30){
			//超时
			$data = array(
				'errid' => 10001,
				'timestamp' => time(),
				);
		}else{
			// $user = M('wx_user')->where(['id'=>$params['userid']])->find();
			$user = M('all_user')->where(['id'=>$params['userid']])->find();
			if (!$user) {
				//用户id不存在
				$data = array(
					'msgtype' => 'error',
					'params' => array(
						'errid' => 10003,
						'errmsg' => 'userid not exist',
						),
					);
			}else{
				//正确
				$roomtype = M('Goods')->alias('t1')->join("left join type as t2 on t1.type_id = t2.type_id")->getField("t2.type_id,t2.type_name",true);
				$success_count = count(M('tbl_game_log')->where(['userid'=>$params['userid'],'got_gift'=>1])->select());
                $count = count(M('tbl_game_log')->where(['userid'=>$params['userid']])->select());
                $stock_count = count(M('tbl_game_log')->where(['userid'=>$params['userid'],'got_gift'=>1,'status'=>0])->select());
             
				$data = array(
					'msgtype' => 'current_user_info',
					'success_count' => $success_count,//游戏成功次数
                    'count'    => $count,//游戏总次数
                    'stock_count' => $stock_count,//抓中娃娃,还没有申请发货的数量
					'params' => array(
						'userid' => $user['id'],//id
						'username'=> $user['nick'],//昵称
						'avatar' => $user['head'],//头像地址
						'asset'=>array(
						    ['type'=>'金币','value'=>$user['gold']],
						    ['type'=>'银币','value'=>$user['silver']],
							),
						// 'silver' => $user['silver'],//银币
						// 'gold'	=> $user['gold'],//金币
						'rank' => $user['rank'],//用户等级
						'referee' => $user['referee'],//被谁推荐过来的
						'referrals'=> $user['referrals'], //推荐的人数
						'referralreward'=> $user['referralreward'],//推荐奖励
						'availamount' => $user['availamount'],//可提现的金额
						'loginaward' => array(
							'type' => NULL,//奖励描述
							'amount' => NULL,//奖励数量
							),
						),
					);
			}
		}
		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
        // return $data;
        echo $data;
		// $this->ajaxreturn($data);
	}

	public function index(){
		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
		// $url = "http://wwj.94zl.com/iwawa/client_auth";
		$url = "http://www.machine.com/Home/Userlogin/c";
		$return = json_curl($url,$params);	
		$return = json_decode($return,true);

		if ($return) {
			//成功
			$useruuid = M('all_user')->where(['uuid'=>$return])->find();
			if ($useruuid) {
				$data = array(
					'userid' => $useruuid['id'],
					'accesstoken' => md5(time()),
					);
			}else{
				$user = M('all_user')->add(array('uuid'=>$return));
				$data = array(
					'userid' => $user,
					'accesstoken' => md5(time()),
					);
			}
			
		}else{
			$data = array(
				'errid' => 10003,
				);
			
		}
		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		echo $data;



	}




	//新增重定向后获取access_token
	public function again(){
		$return = array(
			'code' => 10000,
			'msg' => 'success',
			);
			// $this->ajaxReturn($return);die;
		//丢失access_token重新向我请求
		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
		
		$params = json_decode($params,true);
		$signature = sha1($params['timestamp']*$params['userid']);
		if (time()-$params['timestamp']>30) {
			$data = array(
				'errid' => 10001,
				'timestamp' => time(),
				);
		}else{
			$user = M('all_user')->where(['id'=>$params['userid']])->find();
			$order = M('order')->where(['id'=>$params['amount']])->find();
			$data = array(
			'get_gold' => $order['money'],
			'get_silver' => $order['silver'],
			'gold' => $user['gold'],
			'silver' => $user['silver'],
			'head' => $user['head'],
			'access_token' => $_SESSION['accesstoken'],
			);
			S($userid['id'],$_SESSION['accesstoken']);
			// S("$params['userid']",$_SESSION['accesstoken']);
			file_put_contents("session.txt",$data);
			// $_SESSION['accesstoken'] = $_SESSION['accesstoken'];
		 		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		echo $data;
	}
}
	// public function m(){
	// 	$arr = array ('username'=>'jack','age'=>21,'gender'=>'male'); 
	// 	echo $_GET['callback']."(".json_encode($arr).")";
	// }


	//签到分为三种状态 正常连续签到/当日已签/断签(count+1)
	// /**连续签到的实现方式*/
		public function sign(){
			//params['HTTP_RAW_POST_DATA'];
			$params = $GLOBALS['HTTP_RAW_POST_DATA'];
			$params = json_decode($params,true);
			/**先查到是否有这个用户*/
			// $m_id = $_GET['m_id'];
			$userid = $params['userid'];
			$sign = D('Sign')->where(['userid'=>$userid])->find();
		if ($sign['count']>=7) {
			$data = array(
				'msgtype' => 'null',
				);
			$this->ajaxReturn($data);die;
		}
		/**如果有就进行判断时间差，然后处理签到次数*/
		if($sign){
		/**昨天的时间戳时间范围*/
			$t = time();
			$last_start_time = mktime(0,0,0,date("m",$t),date("d",$t)-1,date("Y",$t));
			$last_end_time = mktime(23,59,59,date("m",$t),date("d",$t)-1,date("Y",$t));
			/**今天的时间戳时间范围*/
			$now_start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
			$now_end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
			/**判断最后一次签到时间是否在昨天的时间范围内*/
		if($last_start_time<$sign['time']&&$sign['time']<$last_end_time){
			$da['time'] = time();
			$da['count'] = $sign['count']+1;
			/**这里还可以加一些判断连续签到几天然后加积分等等的操作*/
			D('Sign')->where(array("userid"=>$userid))->save($da);
			//添加奖励逻辑
			switch ($da['count']) {
			case 2:
				M('all_user')->where(['id'=>$userid])->setInc('gold',1);
				$data = array(
				'day' => 2,
				'gold' => 1,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;
				break;
			case 3:
				M('all_user')->where(['id'=>$userid])->setInc('gold',2);
				$data = array(
				'day' => 3,
				'gold' => 2,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;
				break;
			case 4:
				M('all_user')->where(['id'=>$userid])->setInc('gold',2);
				$data = array(
				'day' => 4,
				'gold' => 2,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;
				break;
			case 5:
				M('all_user')->where(['id'=>$userid])->setInc('gold',3);
				$data = array(
				'day' => 5,
				'gold' => 3,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;
				break;
			case 6:
				M('all_user')->where(['id'=>$userid])->setInc('gold',3);
				$data = array(
				'day' => 6,
				'gold' => 3,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;
				break;
			default:
				M('all_user')->where(['id'=>$userid])->setInc('gold',5);
				$data = array(
				'day' => 7,
				'gold' => 5,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;die;
			}
		}elseif($now_start_time<$sign['time']&&$sign['time']<$now_end_time){//今天已经签到了
			$data = array(
				'day' => M('sign')->where(['userid'=>$params['userid']])->getField("count"),
				'msgtype' => 'repeat',
				);
			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			echo $data;die;
		}else{//以前的签到操作
		/**返回已经签到的操作*/
			$da['time'] = time();
			$da['count'] = $sign['count']+1;
			D('Sign')->where(array("userid"=>$userid))->save($da);
			//添加奖励逻辑
			switch ($da['count']) {
			case 2:
				M('all_user')->where(['id'=>$userid])->setInc('gold',1);
				$data = array(
				'day' => 2,
				'gold' => 1,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;
				break;
			case 3:
				M('all_user')->where(['id'=>$userid])->setInc('gold',2);
				$data = array(
				'day' => 3,
				'gold' => 2,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;
				break;
			case 4:
				M('all_user')->where(['id'=>$userid])->setInc('gold',2);
				$data = array(
				'day' => 4,
				'gold' => 2,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;
				break;
			case 5:
				M('all_user')->where(['id'=>$userid])->setInc('gold',3);
				$data = array(
				'day' => 5,
				'gold' => 3,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;
				break;
			case 6:
				M('all_user')->where(['id'=>$userid])->setInc('gold',3);
				$data = array(
				'day' => 6,
				'gold' => 3,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;
				break;
			default:
				M('all_user')->where(['id'=>$userid])->setInc('gold',5);
				$data = array(
				'day' => 7,
				'gold' => 5,
				);
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;die;
			// $da['count'] = 0;
			D('Sign')->where(array("userid"=>$userid))->save($da);
		}
		}
		}else{
			//第一次登陆签到
			$data['userid'] = $userid;
			$data['time'] = time();
			$data['count'] = 1;
			$res = D("Sign")->add($data);
			//添加签到奖励

		if($res){
		/**成功**/
			M('all_user')->where(['id'=>$userid])->setInc('gold',1);
			$data = array(
				'day' => 1,
				'gold' => 1,
				);
		 		}else{
		 	$data = array(
		 		'msgtype' => 'date error',
		 		);

		 		}
		 	$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			echo $data;
			}

			
		 } 
}