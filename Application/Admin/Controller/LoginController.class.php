<?php
	namespace Admin\Controller;
	use Think\Controller;
	class LoginController extends Controller {
	    public function login(){
	    	if(IS_POST){
	    		$x = I('post.');
	    		$m = encrypt_password($x['password']);
	    		// dump($m);die;
	    		$username = I("post.username");
	    		$password = I("post.password");
	    		$code = I("post.verify");
	    		$verify = new \Think\Verify();
	    		$check = $verify->check($code);
	    		if(!$check){
	    			$this->error("验证码错误");
	    		}
	    		$info = D("Manager")->where(['username'=>$username])->find();
	    		
	    		// dump($info);die;
	    		$group = D('Group')->where(['pid'=>$info['id']])->select();
	    		
	    		if($info && encrypt_password($password) == $info['password'] && $info['is_check']){
	    			session("group",$group);
	    			session("manager_info",$info);
	    			$this->success('登录成功',U('Admin/Index/index'));
	    		}else{
	    			$this->error("用户名密码错误或者账号未激活");
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

	    public function register(){
	    	if (IS_POST) {
	    		//一个方法处理两个逻辑
	    		$data = I('post.');
	    		
	    		$email_code = mt_rand(1000,9999);
	    		$data['email_code'] = $email_code;
	    		$data['username'] = $data['email'];
	    		
	    		
	    		$model = D('Manager');
	    		$create = $model -> create($data);
	    		// dump($create);die;
	    		if (!$create) {
	    			//获取错误信息并提示
	    			$error = $model->getError();
	    			$this->error($error);
	    		}
	    		$res = $model -> add();
	    		if ($res) {
	    			//验证成功
	    			//发送激活邮件
	    			if ($data['email']) {
	    				//主题
	    				$subject = "金兄弟科技有限公司激活邮件";
	    				//生成完整的激活地址
	    				$url = U('Admin/Login/jihuo',array('id' => $res, 'code' => $email_code), '.html', true);
	    				//邮件内容
	    				$body = "欢迎注册,请点击以下链接进行账号激活 : <br><a href='$url'>$url</a><br>如果点击链接无法跳转,请复制地址到浏览器地址栏直接打开.";
	    				$result = sendmail($data['email'], $subject, $body);
	    				if ($result !== true) {
	    					//激活邮件发送失败
	    					$this->error('激活邮件发送失败,请联系客服');
	    				}
	    			}
	    			$this->success('注册成功,请查阅邮箱点击激活邮件',U('Admin/Login/login'));
	    		}else{
	    			$this->error('注册失败');
	    		}

	    	}else{
	    		$super = I('get.id');//23

	    		$this->assign('super',$super);
	    		$this->display();
	    	}
	    }


	    //邮箱激活方法
		public function jihuo(){
			$data = I('get.');
			
			//查询数据库
			$user = D('Manager') -> where(array('id' => $data['id'])) -> find();
			if ($user && $user['email_code'] == $data['code']) {
				//激活成功
				//修改is_check为1
				$user['is_check'] = 1;
				D('Manager') -> save($user);
				$this->success('激活成功',U('Admin/Login/login'));
			}else{
				//激活失败
				$this->error('激活失败',U('Admin/Login/register'));
			}
		}

	    public function ajaxlogin(){
	    	//ajax提交
	    	$username = I('post.username');
	    	$password = I('post.password');
	    	//验证码校验 在查询数据库之前完成 降低轰炸
	    	$code = I('post.verify');
	    	//实例化Verify类,调用check方法
	    	$verify = new \Think\Verify();
	    	$check = $verify -> check($code);
	    	if (!$check) {
	    		//验证码验证失败
	    		$return = array(
	    			'code' => 10001,
	    			'msg' => '验证码错误'
	    			);
	    		$this->ajaxReturn($return);
	    	}
	    	//登陆
	    	//先查询用户名
	    	$info = D('Manager')->where(['username'])->find();
	    	//判断用户是否存在, 当用户存在且密码正确则登陆成功
	    	if($info && $info['password'] == encrypt_password($password)){
	    		//登陆成功
	    		//设置登陆标识
	    		session('manager_info',$info);
	    		$return = array(
	    			'code'=>10000,
	    			'msg'=>'success'
	    			);
	    		$this->ajaxReturn($return);
	    	}else{
	    		//登陆失败
	    		//$this->error('用户名或者密码错误');
	    		$return = array(
	    			'code'=>10002,
	    			'msg'=>'用户名或者密码错误'
	    			);
	    		$this->ajaxReturn($return);
	    	}
	    }
	}
	
	