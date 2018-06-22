<?php
namespace Admin\Controller;
use Think\Controller;
class TestController extends Controller{
	//发送邮件
	public function sendmail(){
		$email = '172812959@qq.com';
		$subject = '测试用';
		$body = '呵呵呵呵';
		//调用封装的sendmail函数
		$res = sendmail($email,$subject,$body);
		if ($res === true) {
			//发送成功
			echo 'seccess';
		}else{
			//发送失败
			echo $res;
		}
	}
}





