<?php
namespace Admin\Controller;
use Think\Controller;
class ManagerController extends CommonController{
	//新增页面
	public function add(){
		if (IS_POST) {

			$id = session('manager_info.id');
			$data = I('post.');
			if (empty($data['email'])) {
				$this->error('邮箱地址不能为空');
			}
			if (empty($data['username'])) {
				$this->error('用户名不能为空');
			}
			if (empty($data['nickname'])) {
				$this->error('用户昵称不能为空');
			}
			// if (empty($data['role_id'])) {
			// 	$this->error('请选择权限等级');
			// }
			if ($data['password'] !== $data['repassword']) {
				$this->error('两次密码输入不一致');
			}
			//简单的表单判断
			$password = $data['password'];
			$data['password'] = encrypt_password($password);
			$data['pid'] = $id;
			$email_code = mt_rand(1000,9999);
	    	$data['email_code'] = $email_code;
			// dump($data);die;
			$model = D('Manager');
			$res = $model -> add($data);

			if ($res) {
				//主题
				$subject = "金兄弟科技有限公司激活邮件";
				//生成完整的激活地址
				$url = U('Admin/Login/jihuo',array('id'=>$res,'code'=>$email_code),'.html',true);
				//邮件内容
				$body = "欢迎注册,请点击以下链接进行账号激活 : <br><a href='$url'>$url</a><br>如果点击链接无法跳转,请复制地址到浏览器地址栏直接打开.";
	    		$result = sendmail($data['email'], $subject, $body);
	    		if ($result !==true) {
	    			//激活邮件发送失败
	    			$this->error('激活邮件发送失败');
	    		}
				$description = '添加了会员:'. '    '. $data['username']; 
				$this->operate_log($description);
				$this->success('添加成功',U('Admin/Manager/index'));
			}else{
				$this->error('添加失败');
			}
			//一开始默认的职务没有权限
			//数据库中的manager_role控制职位
		}else{
		$id = session('manager_info.id');
		$role_id = session('manager_info.role_id');
		$role = D('Role')->select();
		$manager_role = D('Manager_role')->where(['pid'=>$id])->select();
		$this->assign('manager_role',$manager_role);
		$this->assign('role',$role);
		$this->assign('role_id',$role_id);
		$this -> display();
	}
	}

	//修改页面
	// public function edit(){
	// 	//一个方法处理两个业务逻辑
	// 	if (IS_POST) {
	// 		//表单提交
	// 		//接收数据
	// 		$data = I('post.');


	// 		// dump($data);die;
	// 		if(empty($data['username'])){
	// 			$this->error('必填项不能为空');
	// 		}			
	// 		if (empty($data['role_id'])) {
	// 			$this->error('请选择管理员权限');
	// 		}

	// 		$res = D('Manager')->save($data);
			
	// 		if ($res !== false) {
	// 			$this -> success('修改成功',U('Admin/Manager/index'));
	// 		}else{
	// 			$this -> error('修改失败');
	// 		}

	// 	}else{
	// 	$id = I('get.id');
	// 	$data = D('Manager')->where(['id'=>$id])->find();
	// 	$this->assign('data',$data);
	// 	$this -> display();
	// }
	// }

		public function edit(){
		if(IS_POST){
			$id = I('post.id');
			$data = I('post.');
			
			$model = D('Manager');
			// $create = $model->create();
			// if(!$create){
			// 	$error = $model->getError();
			// 	$this->error($error);
			// }
			
			$res = $model->where(['id'=>$id])->save($data);
			if($res !== false){
				$user = D('Manager')->where(['id'=>$id])->find();
				$description = '修改了会员:'. '    '. $user['username']; 
				$this->operate_log($description);
				$this->success("修改成功",U('Admin/Manager/index'));
			}else{
				$this->error("修改失败");
			}
		}else{
			$id = session('manager_info.id');
			$manager_id = I('get.id');
			$manager = D('Manager')->where(['id'=>$manager_id])->find();
			$this->assign('manager',$manager);
			$role_id = session('manager_info.role_id');
			$role = D('Role')->select();//属于admin的权限
			$manager_role = D('Manager_role')->where(['pid'=>$id])->select();//属于实体店超级管理员的权限
			$this->assign('manager_role',$manager_role);
			// dump($manager_role);die;
			$this->assign('role',$role);//属于admin的权限
			$this->assign('role_id',$role_id);//用于判断role等级
			$this -> display();
			
		}
	}

	

		//列表页sssssss
	public function index(){
		$role_id = session('manager_info.role_id');

		$id = session('manager_info.id');
		
		if ($id == 1) {
			$data=D('Manager')->select();
		}

		elseif($role_id == 3 || $role_id == 5) {
		//判断用户的等级权限
		$data = D('Manager')->alias('t1')->field('t1.*,t2.role_name')->where("t1.pid = $id")->join("left join manager_role as t2 on t2.role_id = t1.manager_role")->select();
		// dump($data);die;
		}else{
		$user = D('Manager')->where(['id'=>$id])->find();
		$supuser = D('Manager')->where(['id'=>$user['pid']])->find();
		$data = D('Manager')->alias('t1')->field('t1.*,t2.role_name')->where(["t1.pid"=>$supuser['id']])->join("left join manager_role as t2 on t2.role_id = t1.manager_role")->select();
		
		$role = D('Manager')->where(['role_id'=>$role_id])->find();
		$role_id = $role['role_id'];
		}
		$this->assign('role_id',$role_id);
		$this->assign('data',$data);
		$this -> display();
	}
	//删除管理员
	public function del(){
		$id = I('get.id');

		$model = D('Manager');
		$data = $model->where(['id'=>$id])->find();
		$username = $data['username'];
		
		$res = $model -> where(['id'=>$id])->delete();
		if ($res!==false) {
			
			
			$description = '删除了会员:'. '    '. $username; 
			$this->operate_log($description);
			$this->success('删除成功',U('Admin/Manager/index'));
		}else{
			$this->error('删除失败');
		}
	}

	// public function list2(){
	// 	$data=D('Manager')->select();
	// 	$this->assign('data',$data);
	// 	$this->display();
	// }
	public function delAll(){
		$ids = I('post.ids');

		$res = D('Manager')->where("id in ({$ids})")->delete();
		if($res!=false){
			// $data = D('Manager')->where("id in ()")
			$return=array(
				'code'=>10000,
				'msg'=>'success'
				);
		}else{
			$return=array(
				'code'=>10001,
				'msg'=>'删除失败'
				);
		}
		$this->ajaxReturn($return);
	}

	public function repass(){
		if (IS_POST) {
			$data = I('post.');
			
			$id = session('manager_info.id');
			$model = D('Manager');
			$res = $model->where(['id'=>$id])->find();
			$password = $res['password'];

			$old = encrypt_password($data['old_password']);
			
			if ($data['new_password']!==$data['re_password']) {
				$this->error('两次密码输入不一致');
			}
			if ($old !== $password) {
				$this->error('密码错误');
			}else{
				$data['password'] = encrypt_password($data['new_password']);
				// dump($data);die;
				$new = $model->where(['id'=>$id])->save($data);
				if ($new!==false) {
						$this->success('修改成功',U('Admin/Manager/index'));
					}else{
						$this->error('修改失败');
					}	
			}
		
			
		}else{
		$id = I('get.id');
		$model = D('Manager');
		$data = $model -> where(['id'=>$id])->find();
		$this->assign('data',$data);
		$this->display();
	}
	}


	public function adstop(){
		$id = I('get.id');
		$manager = D('Manager')->where(['id'=>$id])->find();
		$nickname = $manager['nickname'];
		$manager['is_check'] = 0;
		$res = D('Manager')->save($manager);
		if ($res !== false) {
			$description = '禁用了会员:' . '    ' . $nickname;
			$this->operate_log($description);
			$this->redirect('Admin/Manager/index');
		}else{
			$this->error('操作失败');
		}
	}

	public function adstart(){
		$id = I('get.id');
		$manager = D('Manager')->where(['id'=>$id])->find();
		$nickname = $manager['nickname'];
		$manager['is_check'] = 1;
		$res = D('Manager')->save($manager);
		if ($res !== false) {
			$description = '激活了会员:' . '    ' . $nickname;
			$this->operate_log($description);
			$this->redirect('Admin/Manager/index');
		}else{
			$this->error('操作失败');
		}
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

	public function setauth(){
		$id = session('manager_info.id');
		$role_id = session('manager_info.role_id');
		if ($role_id ==3 || $role_id == 5) {
			$u_name = D('Manager')->field('username,manager_role')->where(['pid'=>$id])->select();
			
			$data = D("Manager_role")->where(['pid'=>$id])->select();
			// $row=array('one'=>1,'two'=>2);
			// foreach($row as $key=>$val){
			//     echo $key.'--'.$val;

			// }die;
			$role_auth_ids = array();
			foreach ($data as $key => $value) {
				$role_auth_ids[] = $value['role_auth_ids'];
			}		

			foreach ($role_auth_ids as $key => $value) {
				$role_auth[$key] = D('Auth')->where("id in ({$value})")->getField("auth_name",true);
				$role_auth[$key] = implode($role_auth[$key],',');
			}
			

			$this->assign('role_auth',$role_auth);
			// dump($role_auth_ids);die;
			// $m = D('Manager_role')->alias("t1")->field('t1.*,t2.auth_name')->where("t1.pid = $id and t2.id in ({$role_auth_ids})")->join("left join auth as t2 on t1.")
			foreach($u_name as $k=>$v){
				$name[$v['manager_role']][]=$v['username'];
			}

			$username = [];
			foreach($name as $key=>$val){
				$username[$key] = implode(',',$val);
			}
			// dump($data);dump($username);die;
			$this->assign('username',$username);
			$this->assign("data",$data);
			
			// $data = D("Manager_role")->alias("t1")->field('t1.*,t2.username')->where(['t1.pid'=>$id])->join("left join manager as t2 on t2.manager_role = t1.role_id")->select();
			// $role = D('Manager_role')->where(['pid'=>$id])->getField('role_name',true);
			// dump($data);die;
			// $this->assign('role',$role);
			// $this->assign('data',$data);




			$this->display();
		}else{
			$this->error('无权访问');
		}
			}

	public function role_add(){
		$role_id = session('manager_info.role_id');
		if (IS_POST) {
			$data['role_name'] = I('post.role_name');
			if (empty($data['role_name'])) {
				$this->error('角色名称不能为空');
			}
			$auth_ids = I('post.id');
			$data['role_auth_ids'] = implode(',', $auth_ids);
			$auth = D('Auth')->where("id in ({$data['role_auth_ids']})")->select();
			$data['role_auth_ac'] = '';
			foreach ($auth as $key => $value) {
				if ($value['auth_c'] && $value['auth_c']) {
					$data['role_auth_ac'] .= $value['auth_c'] . '-' . $value['auth_a'].',';
				}
			}
			$data['pid'] = session('manager_info.id');
			$data['role_auth_ac'] = trim($data['role_auth_ac'],',');
			$res = D('Manager_role')->add($data);
			if ($res) {
				$this->success('添加成功',U('Admin/Manager/setauth'));
			}else{
				$this->error('添加失败');
			}



		}else{
			if ($role_id == 3 || $role_id == 5) {
			$ids = D('Role')->where(['role_id'=>$role_id])->getField('role_auth_ids');// "2,8,9,17,13,18,19,14,15,20,31"
			
			$role = str_replace(array(",31",",15",",41",",42",",40",",17",",36",",37",",38"),"",$ids); //权限管理  创建管理员 
			
			// dump($role);die;
			$top_all = D('Auth')->where("id in ({$role}) and pid = 0")->select();
			$second_all = D('Auth')->where("id in ({$role}) and pid > 0")->select();
			
			$this->assign('top_all',$top_all);
			$this->assign('second_all',$second_all);
			$this->display();
		}else{
			$this->error('无权操作');
		}
		}
		
		

	
	}


	public function role_edit(){
		if (IS_POST) {
			
			
			$data['role_name'] = I('post.roleName');
			
			$data['role_id'] = I('post.manager_role');
			$auth_ids = I('post.id');
			$data['role_auth_ids'] = implode(',',$auth_ids);
			$auth = D('Auth')->where("id in ({$data['role_auth_ids']})")->select();
			// dump($auth);die;
			$data['role_auth_ac'] = "";
			foreach($auth as $k => $v){
				if($v['auth_c'] && $v['auth_a']){
					$data['role_auth_ac'] .= $v['auth_c'].'-'.$v['auth_a'].',';
				}
			}
			
			$data['role_auth_ac'] = trim($data['role_auth_ac'],',');
			// dump($data);die;
			$res = D('Manager_role')->save($data);
			if ($res !== false) {
				$this->success('修改成功',U('Admin/Manager/setauth'));
			}else{
				$this->error('修改失败');
			}



		}else{
			$id = session('manager_info.role_id');
			$role_id = I('get.role_id');
			$ids = D('Role')->where(['role_id'=>$id])->getField('role_auth_ids');
			$role = str_replace(array(",31",",15"),"",$ids); //权限管理  创建管理员
			// dump($role);die;
			//实体店超级管理员拥有的权限ids集合
			$manager_role = D('Manager_role')->where(['role_id'=>$role_id])->find();
			$this->assign('manager_role',$manager_role);
			$top_all = D('Auth') -> where("id in ({$role}) and pid = 0")->select();
			$second_all = D('Auth') -> where("id in ({$role}) and pid > 0")->select();

			// $three_all = D('Auth')->where(['pid'=>$top_all['id']])->select();
			// dump($three_all);die;
			$this->assign('top_all',$top_all);
			$this->assign('second_all',$second_all);
			$this->display();
		}
	}

	public function role_del(){
		$id = I('get.role_id');
		$res = D('Manager_role')->where(['role_id'=>$id])->delete();
		if ($res!==false) {
			$this->success('删除成功',U('Admin/Manager/setauth'));
		}else{
			$this->error('删除失败');
		}



	}

	//生成注册链接
	public function tuijian(){
		$id = session('manager_info.id');			
		$url = U('Admin/Login/register',array('id' => $id),'.html',true);
		$body = "<br><a href='$url'>$url</a><br>";
		


		$this->assign('body',$body);
		
		$this->display();
	}

	public function qrcode($url="",$level=3,$size=4)
    {			
              Vendor('phpqrcode.phpqrcode.phpqrcode');
              $errorCorrectionLevel =intval($level) ;//容错级别 
              $matrixPointSize = intval($size);//生成图片大小 
              $id = session('manager_info.id');
              // $url = U('Admin/Login/register',array('id'=>$id),'.html',true);
              // $url = "http://".$_SERVER['HTTP_HOST']."/Admin/Login/register?id=" .$id;
              $url = U('Admin/Login/register',array('id' => $id),'.html',true);
       			
              // $body = "<br><a href='$url'>$url</a><br>";
             //生成二维码图片 
              $object = new \QRcode();
              $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);   
    }
   
   public function qrcode2($url="",$level=3,$size=4)
    {			
              Vendor('phpqrcode.phpqrcode.phpqrcode');
              $errorCorrectionLevel =intval($level) ;//容错级别 
              $matrixPointSize = intval($size);//生成图片大小 
              $id = session('manager_info.id');
              // $url = U('Admin/Login/register',array('id'=>$id),'.html',true);
              // $url = "http://".$_SERVER['HTTP_HOST']."/Admin/Login/register?id=" .$id;
              $url = "http://192.168.1.171/#/LOGIN";
       			
              // $body = "<br><a href='$url'>$url</a><br>";
             //生成二维码图片 
              $object = new \QRcode();
              $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);   
    }
}