<?php
namespace Home\Model;
use Think\Model;
class WishModel extends Model
{
	// 添加到收藏夹模型
	public function addW($id)
	{
		// 分析: 
		// 1.点击小红心 添加到收藏夹 首页 商品详情页
		// 2.HTML页面
		// 3.保存数据到数据库
		// 4.创建数据库  有用户信息 商品信息
		//判断登录状态
		if(session('?user_info')){
			// 登录 查询数据信息
			$goods = D('goods') -> where(['id' => $id]) -> find();
			//将数据保存到数据库
			$data = array(
				'user_id' => session('user_info.id'),
				'goods_name' => $goods['goods_name'],
				'goods_id' => $id,
				'goods_small_img' => $goods['goods_small_img'],
				'goods_number' => $goods['goods_number'],
				'goods_introduce' => $goods['goods_introduce']
			);

			$res = D('Wish')  -> add($data);
			return $res !== false ? true : false;
		}else{
			return false;
		}
	}
}