<?php
namespace Admin\Controller;
use Think\Controller;
class DescriptionController extends CommonController{
	public function index(){
		$data = D('Operate_log')->alias('t1')->field('t1.*,t2.role_name')->join("left join manager as t3 on t3.id = t1.manager_id")->join("left join role as t2 on t3.role_id = t2.role_id")->select();
		$this->assign('data',$data);
		$this->display();
	}

	public function delAll(){
		$ids = I('post.ids');
		$res = D('Operate_log')->where("id in ({$ids})")->delete();
		if ($res !== false) {
			$return = array(
				'code'=>10000,
				'msg'=>'success'
				);
		}else{
			$return =array(
				'code'=>10001,
				'msg'=>'删除失败'
				);
		}
		$this->ajaxReturn($return);
		
	}

	public function comment(){
		
			$data = M('Comment')->alias('t1')->field("t1.id,t1.message,t1.create_time,t1.reply,t1.replydate,t2.nick")->join('left join all_user as t2 on t2.id = t1.userid')->select();
			$this->assign('data',$data);
			$this->display();
	
		
	}

	public function reply(){
		if (IS_POST) {
			$data = I('post.');
		
			$reply = $data['reply'];
			$replydate = time();
			$res = M('comment')->where(['id'=>$data['id']])->save(['reply'=>$reply,'replydate'=>$replydate]);
			if ($res!==false) {
				$this->redirect("Admin/Description/comment");
			}else{
				$this->error("回复失败");
			}


		}else{
			$id = I('get.id');
			$data = M('comment')->alias("t1")->field("t1.id,t1.message,t2.nick")->where(['t1.id'=>$id])->join("left join all_user as t2 on t2.id = t1.userid")->find();
			$this->assign('data',$data);
			$this->display();
		}
	}

	
}