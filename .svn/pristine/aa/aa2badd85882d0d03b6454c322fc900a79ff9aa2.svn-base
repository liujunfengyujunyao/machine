<?php
namespace Home\Controller;
use Think\Controller;
class ReviewController extends CommonController{
	// 评论 接收数据 存入数据库 将数据ajax返回到 detail页面 
	public function review(){
		// 接受数据 
		$data = I('post.');
		//$data = json_decode($data);
		$time = time();
		// 调用模型 添加记录
		$data['create_time']=$time;
		$data['user_id']=session('user_info.id');
		$res = D('Review') -> addReview($data);
		if($res){
			$return=array(
				'code'=>10000,
				'msg'=>'评论成功'
				);
		}else{
			$return=array(
				'code'=>10001,
				'msg'=>'评论失败'
				);
		}
		$this->ajaxReturn($return);
	}
}