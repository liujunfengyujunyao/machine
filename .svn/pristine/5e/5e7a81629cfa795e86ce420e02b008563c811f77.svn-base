<?php
namespace Home\Model;
use Think\Model;
class ReviewModel extends Model
{
	// 添加评论记录
	public function addReview($data=array())
	{
		$data['user_id'] = session('user_info.id');
		$res = D('Review') -> add($data);
		return $res !== false ? true : false;
	}

	//添加商家回复
	public function addBs($id,$data=array())
	{
		$data['user_id'] = session('user_info.id');
		$res = D('Review') -> where(['id' => $id]) -> save($data);
		return $res !== false ? true : false;

	}
}