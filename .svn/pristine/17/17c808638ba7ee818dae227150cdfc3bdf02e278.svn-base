<?php 
namespace Home\Controller;
use Think\Controller;
class LoginController extends CommonController
{
	public function register()
	{
		if(IS_POST){
			//接收数据
			$data = I('post.');
			if( $data == null ){
				$this -> error('参数有误');
			}
			$data['create_time'] = time();
			//判断验证码
			// $verify = new \Think\verify();
			// $Verify->entry();
			// 实例化
			$model = D('User');
			$create = $model -> create($data);
			if(!$create){
				$this -> error('参数有误');
			}
			$res = $model -> add();
			if($res){
				$this -> success('OK',U('Home/Login/login'));
			}else{
				$this -> redirect();
			}
		}else{			
		$this -> display();
		}
	}
	public function login()
		{
			if(IS_POST){

				$data = I('post.');
				// dump($data);
				if( $data == null ){
					$this -> error('参数有误');
				}
				$user = D('User') -> where(['email'=> $data['email']] ) -> find();
				// dump($user);die;
				if($user && $user['password'] == $data['password'] ){
					// 保存数据到session
					session('user_info',$user);
					$this -> success('OK',U('Home/Index/index'));
				}else{
					$this -> error('用户名或密码不正确');
				}
			}else{
				$this -> display();	
			}

		}

	public  function logout()
	{
		session(null);
		$this -> redirect('Home/Index/index');
	}
}