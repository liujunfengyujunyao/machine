<?php
	namespace Admin\Controller;
	use Think\Controller;
	class LoginController extends Controller {
	    public function login(){
	    	if(IS_POST){
	    		$username = I("post.username");
	    		$password = I("post.password");
	    		$code = I("post.verify");
	    		$verify = new \Think\Verify();
	    		$check = $verify->check($code);
	    		if(!$check){
	    			$this->error("验证码错误");
	    		}
	    		$info = D("Manager")->where(['username'=>$username])->find();
	    		
	    		if($info && encrypt_password($password) == $info['password']){
	    			session("manager_info",$info);
	    			$this->success('登录成功',U('Admin/Index/index'));
	    		}else{
	    			$this->error("用户名或密码错误");
	    		}
	    	}else{
	    		$this->display();	
	    	}

	        
	    }
	    public function logout(){
	    	session(null);
	    	$this->redirect("Admin/Login/login");

	    }
	    public function captcha(){
	    	$config = array(
	    		'useCurve'=>false,
	    		'useNoise'=>false,
	    		'length'=>4,
	    		);
	    	$verify = new \Think\Verify($config);
	    	$verify->entry();
	    }
	}
	
	