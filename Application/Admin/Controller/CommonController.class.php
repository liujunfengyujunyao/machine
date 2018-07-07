<?php
namespace Admin\Controller;
use Think\Controller;

//这个控制器用来判定登陆用户的权限等级(admin的role_id默认为1 1为超级权限)
class CommonController extends Controller{
	//构造方法  每次登陆都会自行进行判断
	public function __construct(){
		//调用父类的构造方法
		parent::__construct();
		//登录判断
		if(!session('?manager_info')){
			//没有登录则跳转到登录页面
			$this -> redirect('Admin/Login/login');
		}
		//调用getnav获取菜单权限
		$this -> getnav();
		//调用checkauth检测权限
		$this -> checkauth();
	}

	//封装一个获取菜单权限的方法
	public function getnav(){
		//判断session中有没有菜单权限的数据
		if(session('?top') && session('?second')){
			return true;
		}
		$status = session('manager_info.status');
		// dump($_SESSION);die;
		//获取当前管理员的角色id
		
		$role_id = session('manager_info.role_id');
		$manager_role = session('manager_info.manager_role');
		$equipment_ids = session('manager_info.equipment_ids');
	
		//判断是否是超级管理员
		
		if($role_id == 1){
			// dump('xxxxxxx');die;
			//超级管理员 直接查询权限表
			//分别查询顶级权限和二级权限，用于在页面上的两次遍历输出
			//获取顶级权限
			// $top = D('Auth') -> where(['pid' => 0]) -> select();
			$top = D('Auth') -> where('pid = 0 and is_nav = 1') -> select();
			//获取二级权限
			$second = D('Auth') -> where('pid > 0 and is_nav = 1') -> select();
			// $top = D('Auth') -> where(['pid' => ['GT', 0] ]) -> select();
		}
		elseif ($manager_role > 0 && $role_id ==4 || $role_id ==6) {
			$id = session('manager_info.id');
		
			$manager = D('Manager')->where(['id'=>$id])->find();
			
			// dump($manager);die;
			//加一个判断
			// if ($manager['manager_role']==0) {
			// 	$role = D('Role')->where(['role_id'=>$role_id]) -> find();
			// 	$role_auth_ids = $role['role_auth_ids'];
			// 	$top = D('Auth')->where("pid = 0 and id in ($role_auth_ids) and is_nav = 1")->select();
			// 	$second = D('Auth')->where("pid > 0 and id in ($role_auth_ids) and is_nav = 1") -> select();
			// }
			
			$role_id = $manager['manager_role'];
			$manager_role = D('Manager_role')->where(['role_id'=>$role_id])->find();
			$role_auth_ids = $manager_role['role_auth_ids'];
			
			// dump($role_auth_ids);die;
			$top = D('Auth') -> where("pid=0 and id in ({$role_auth_ids}) and is_nav = 1") -> select();
			$second = D('Auth') -> where("pid > 0 and id in ({$role_auth_ids}) and is_nav = 1")->select();
			
		}
		elseif ($manager_role == 0 && $role_id == 4 || $role_id == 6){
			$top = D('Auth') -> where("id = 2")->select();
			$second = D('Auth') -> where("id = 9")->select();
		}
		else{
			// 先查询角色 再查询权限
			//查询角色表
			// dump(xxx);die;
			$role = D('Role') -> where(['role_id' => $role_id]) -> find();
			$role_auth_ids = $role['role_auth_ids'];//当前角色拥有的权限ids集合
			//查询权限表
			//查询顶级权限
			//is_nav 是否作为菜单显示 1是 0否
			$top = D('Auth') -> where("pid = 0 and id in ($role_auth_ids) and is_nav = 1") -> select();
			//查询二级权限
			$second = D('Auth') -> where("pid > 0 and id in ($role_auth_ids) and is_nav = 1") -> select();

		}
		//从3级用户开始 manager表中的status激活属性默认为2(未激活) 需要待添加机台后激活
		// if ($status == 2) {
		// 	// dump(zzzzz);die;
		// 	//仅显示机器管理下的机台新增
		// 	$top = D('Auth') -> where('id = 2') -> select();

		// 	$second = D('Auth') -> where('id = 9') -> select();
		// }
		if ($status == 2 && $equipment_ids == '') {
			
			$top = D('Auth') -> where("id = 2")->select();
			$second = D('Auth') -> where("id = 9")->select();
		}
		// dump($top);dump($second);die;
		//将权限数据保存到session，因为一个管理员登录之后，他的权限并不会很频繁的变化。
		//如果登录之后，权限发生了变化，退出并重新登录一次
		session('top', $top);
		session('second', $second);
	}

	//封装一个检测权限的方法s
	public function checkauth(){
		//获取当前管理员角色id
		$role_id = session('manager_info.role_id');

		//超级管理员拥有所有权限，不需要检测
		//角色表没有存入admin的role_id  直接用程序进行角色判定
		if($role_id == 1 || $role_id == 3 || $role_id == 5){
			return true;
		}
		//根据角色id获取拥有的权限
		//普通员工 权限按照上级分配所指
		$id = session('manager_info.id');
		$manager = D('Manager')->where(['id'=>$id])->find();
		$manager_role = $manager['manager_role'];
		$role = D('Manager_role') -> where(['role_id' => $manager_role]) -> find();
		//获取当前访问页面的控制器名称和方法名称
		$c = CONTROLLER_NAME; //获取控制器名称
		$a = ACTION_NAME;	//获取方法名称
		// if ($c == 'Group') {
		// 	//(3,5级别管理员可用 用来划分机台 修改机台配置)
		// 	return true;
		// }
		// dump($c);dump($a);die;
		//将当前访问页面的控制器名称和方法名称用-拼接
		$ac = $c . '-' . $a;
		
		//对于特殊页面，做特殊处理
		//首页，一般所有人都有权限访问 如果还有其他页面是所有人都可以访问的，都这么处理
		if($ac == 'Index-index' || $a == 'Manager/repass'){
			return true;
		}
		//然后判断拼接的字符串是否在 $role['role_auth_ac'] 范围中
		//explode 将字符串打散为数组
		$auth_ac = explode(',', $role['role_auth_ac']);
		
		//使用in_array函数 判断是否存在于数组中
		if(!in_array($ac, $auth_ac)){
			//没有权限访问当前页面就将其返回到首页
			$this -> redirect('Admin/Index/index',NULL,1,'权限等级不足');
		}
		
	}
	
	/**
	 * 管理员操作记录
	 */
	public function operate_log($description){
		$data = array(
			'manager_id'    => session('manager_info.id'),
			'username'  	=> session('manager_info.username'),
			'description'  	=> $description,
			'operate_time'  => date('Y-m-d H-i-s'),
			'operate_ip'    => get_client_ip(0, true),
		);
		M('operate_log')->add($data);

	}

	//查询未读消息
	public function notReadMsg(){
		$manager_id = session('manager_info.id');
		$machineid = M('equipment')->where(['pid'=>$manager_id])->getField('id',true);
		$machineids = implode(',',$machineid);
		$msg = M('error')->where("machineid in ($machineids) and status = 0")->order('time ASC')->select();
		$count = count($msg);
		session('msg_count',$count);

		if(session('ajax_time')){
			$old_time = session('ajax_time');
			$new_msg = M('error')->where("machineid in ($machineids) and status = 0 and time > $old_time ")->order('time ASC')->select();
		}else{
			$new_msg = $msg;
		}
		$renew = $new_msg ? 1 : 0;
		session('ajax_time',time());
		$li = "";
		foreach(array_slice($msg,0,5) as $k=>$v){
			$li .= "<li style='border-bottom:1px solid #eee;text-align:left;'>" .$v['machineid'] . "号机台" . $v['errmsg'] ."<br/><span style='font-size:10px;color:#555;'>" .date("Y-m-d H:i:s",$v['time']).  "</span> </li>";
		}
		if($count>5){

			$html = "<ul style='text-align:center;'>" . $li ."<span style='color:#555;'>......</span></ul>";
		}else{
			$html = "<ul>" . $li ."</ul>";
		}
		if($count == 0){
			$return = array(
				'status' => 1000 ,
				);
		}else{
			$return = array(
				'status' => 1001 ,
				'msg' => $html ,
				'count' => $count ,
				'renew' =>$renew ,
			);
		}
		$this->ajaxReturn($return);
	}
}