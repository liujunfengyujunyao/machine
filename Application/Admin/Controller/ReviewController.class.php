<?php
namespace Admin\Controller;
use Think\Controller;
class ReviewController extends CommonController{
	public function index(){
		$data = D('Review')->alias('t1')->field('t1.*,t2.username,t2.phone,t2.email')->join('left join dia_user as t2 on t1.user_id=t2.id')->select();
		$this->assign('data',$data);
		$this->display();
	}
	public function member(){
		$user_id = I('get.id');
		$data = D('User')->where(['id'=>$user_id])->find();
		$this->assign('data',$data);
		$this->display();
	}
	public function delAll(){
		$ids = I('post.ids');
		$res = D('Review')->where("id in ({$ids})")->delete();
		if($res!==false){
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
}