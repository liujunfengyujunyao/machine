<?php
namespace Admin\Controller;
use Common\Plugin\WxLogin;

class WxController extends CommonController{
	public function index(){
		if (strstr($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')) {
			//判断是否是微信浏览器
			$config = array(
				//用来认证的appid 和 appSecret
				'appId'      => 'wx9e8c63fo3cbd36aa',
				'appSecret'  =>  ''
				);
			//实例化WxLogin登陆类
			$obj = new WxLogin($config);
			//判断用户是否同意授权
			if (!isset($_GET['code'])) {
				//没有授权 跳转到授权页面
				$self = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUSET_URI'];
				$url = $obj->getOauthurl($self);
				Header("Location: $url");
				exit();
			}elseif(isset($_GET['code'])){
				//微信授权回调获取用户信息
				$code = I('get.code');
				$wx_user = $obj->wx_log($code);
				//$wx_user获取到的用户信息
			}

		}else{
			$this->error('请使用微信浏览器访问');
		}
	}
}