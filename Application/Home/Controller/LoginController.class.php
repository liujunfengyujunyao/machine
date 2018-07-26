<?php
namespace Home\Controller;
use Common\Plugin\WxLogin;
use Think\Controller;

class LoginController extends Controller{
	//你的登录页面
	public function user_login(){
		if(strstr($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')){
			//微信浏览器，公众平台
			$config = C('wx_test');
            $obj = new WxLogin($config);
            //获取微信授权  $self为回调地址
            $self = 'http://'.$_SERVER['HTTP_HOST'].'/home/login/wx?type=oauth';
            // $self = 'http://'.'192.168.1.164'.'/Home/login/wx?type=oauth';
            //第一步 通过WxLogin的getOauthurl拼接$self $url就是微信返回来的是否确认授权页面
            $url = $obj->getOauthurl($self);
            Header("Location: $url");
            exit();
	 	}else{
	 		$config = C('wx_oauth');
			$obj = new WxLogin($config);
			$wx = 'http://'.$_SERVER['HTTP_HOST'].'/home/login/wx?type=open';
			$wx_url = $obj->get_authorize_url($wx);
	 		$this->assign('wx_url',$wx_url);
	 		//把微信地址展示到前台，前台<a href="{$wx_url}">微信登录</a>,用户点击a连接，就会出现一个二维码，用户扫码之后，
	 		//微信端会返回一个code到你的$wx = 'http://'.$_SERVER['HTTP_HOST'].'/home/login/wx';这个地址
	 		$this->display('login.html');
	 	}
	}
	public function wx(){
		$code = I('get.code');
		
		$type = I('get.type');
		if(isset($code)){
			if($type == 'open'){
				//微信开放平台
				$config = C('wx_oauth');
			}else{
				//微信公众平台
				$config = C('wx_test');
			}

			$obj = new WxLogin($config);
			$wx_user = $obj->wx_log($code);
	
			
			if (!$wx_user) {
				$params = array(
					'userid' => NULL,
					'msgid' => 403,
					'chatsever' => NULL,
					'accesstoken' => $wx_user['access_token']
					);
				$return = array('msgtype'=>"auth_failed",'msgid'=>403,'params'=>$params);
			
				return json_encode($return, JSON_UNESCAPED_UNICODE);
			}
			$user = M('all_user')->where(['openid'=>$wx_user->openid])->find();
		
			if (!$user) {
				//不存在,插入
				$data['openid'] = $wx_user->openid;
				$data['nick'] = $wx_user->nickname;
				$data['head'] = $wx_user->headimgurl;
				$data['gender'] = $wx_user->sex;
				$data['type'] = $type;
				$data['addtime'] = time();
				$id = M('all_user')->add($data);
			}else{
				//存在,更新会变更的数据
				$data['nick'] = $wx_user->nickname;
				$data['head'] = $wx_user->headeimgurl;
				$data['gender'] = $wx_user->sex;
				// M('Member')->where(['openid'=>$wx_user->openid])->save($data);
				M('all_user')->where(['openid'=>$wx_user->openid])->save($data);
				$id = $user['id'];
				// $this->success('登陆成功',U('Home/Test/index',array('id'=>$id)));
			}

			$params = array(
				'userid' => $id,
				'msgid' => 200,
				'chatsever' => '',
				'accesstoken' => $wx_user->access_token
				);
			$return = array('msgtype'=>"auth_ok",'msgid'=>200,'params'=>$params);
			 dump(json_encode($return, JSON_UNESCAPED_UNICODE));die;
			dump($return);die;
			// dump($wx_user);die;
			// $wx_user为对象object形式
			//"ogD78085G9JZAEZ572UvTYlDaJS0"
			
			// $this->success('Index/index','已授权');
			// $this->redirect('Home/Test/index',array('id'=>$id));
			return json_encode($return, JSON_UNESCAPED_UNICODE);
			
		}
	}

	
}