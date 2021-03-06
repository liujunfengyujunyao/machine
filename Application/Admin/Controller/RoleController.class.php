<?php  
namespace Admin\Controller;
use Think\Controller;
class RoleController extends CommonController{
	public function index(){
		$u_name = D('Manager')->field('username,role_id')->select();
		
		$data = D("Role")->select();
		$name=array();
		foreach($u_name as $k=>$v){
			$name[$v['role_id']][]=$v['username'];
		}
		$username = [];
		foreach($name as $key=>$val){
			$username[$key] = implode(',',$val);
		}
		$this->assign('username',$username);
		$this->assign("data",$data);
		$this->display();
	}
	public function edit(){
		if(IS_POST){
			$data['role_user'] = I('post.roleName');
			$data['role_id'] = I('post.role_id');
			$role_id = I('post.role_id');
			
			$auth_ids = I('post.id');

			$data['role_auth_ids'] = implode(',' , $auth_ids);
			$auth = D('Auth') -> where("id in({$data['role_auth_ids']})")->select();
			$data['role_auth_ac']="";
			foreach($auth as $k => $v){
				if($v['auth_c'] && $v['auth_a']){
					$data['role_auth_ac'] .= $v['auth_c'].'-'.$v['auth_a'].',';
				}
			}
			$data['role_auth_ac'] = trim($data['role_auth_ac'],',');
			
			// dump($data['role_auth_ac']);die;
			$res = D('Role')->save($data);
			if($res!== false){
				$this->success('修改成功',U('Admin/Role/index'));
			}else{
				$this->error('修改失败');
			}
			
		}else{
			$role_id = I('get.role_id');
			$role = D('Role')->where(['role_id'=>$role_id])->find();

			$this->assign('role',$role);
			$top_all = D('Auth') -> where('pid=0')->select();
			$second_all = D('Auth') -> where('pid>0')->select();

			$three_all = D('Auth')->where(['pid'=>$top_all['id']])->select();

			$this->assign('top_all',$top_all);
			$this->assign('second_all',$second_all);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data['role_name'] = I('post.role_name');
			if(empty($data['role_name'])){
				$this->error('角色名称不可为空');
			}
			$auth_ids = I('post.id');
			$data['role_auth_ids'] = implode(',',$auth_ids);
			$auth = D("Auth")->where("id in ({$data['role_auth_ids']})")->select();
			$data['role_auth_ac'] = "";
			foreach($auth as $k=>$v){
				if($v['auth_c'] && $v['auth_a']){
					$data['role_auth_ac'] .= $v['auth_c'].'-'.$v['auth_a'].',';
				}
			}
			$data['role_auth_ac'] = trim($data['role_auth_ac'],',');
			$res = D('Role')->add($data);
			if($res){
				$this->success("添加成功",U('Admin/Role/index'));
			}else{
				$this->error('添加失败');
			}	
		}else{
			$top_all = D('Auth') -> where('pid=0')->select();
			$second_all = D('Auth') -> where('pid>0')->select();
			$this->assign('top_all',$top_all);
			$this->assign('second_all',$second_all);
			$this->display();
		}
	}

	public function del(){
		$role_id = I('get.role_id');
		$manager = D('Manager') ->where(['role_id'=>$role_id])->select();
		if($manager){
			$this->error('角色使用中不可删除');
		}else{
			$res = D('Role')->where(['role_id'=>$role_id])->delete();
			if($res !== false){
				$this->success("删除成功",U('Admin/Role/index'));
			}else{
				$this->error("删除失败");
			}
		}
	}

	public function delAll(){
		$ids = I('post.ids');
		$res = D('Manager')->where("role_id in ({$ids})")->select();
		if($res){
			$return=array(
				'code'=>10001,
				'msg'=>'角色使用中不可删除'
				);

		}else{
			$result = D('Role')->where("role_id in ({$ids})")->delete();
			if($result!=false){
				$return=array(
					'code'=>10000,
					'msg'=>'success'
					);
			}else{
				$return=array(
					'code'=>10002,
					'msg'=>'删除失败'
					);
			}
		}
		$this->ajaxReturn($return);
     }  



}

?>