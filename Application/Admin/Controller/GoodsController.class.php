<?php
namespace Admin\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");
class GoodsController extends CommonController{
	//列表页
	public function index(){
		$id = session('manager_info.id');
		// dump($id);die;
		$role_id = session('manager_info.role_id');
		$goods_ids = D('Goods')->where(['pid'=>$id])->getField('id',true);
		$goods_ids = implode(',',$goods_ids);
		// $x = D('Equipment')->alias('t1')->where("t1.equipment_pid = $id && t1.goods_id = t2.id")->join("left join goods as t2 on t2.id = t1.goods_id")->select();
		// dump($x);die;
		
		if ($role_id > 3) {
			$id = D('Manager')->where(['id'=>$id])->getField('pid');
		}
		$goods = D('Goods')->alias('t1')->where(['pid'=>$id])->join("type as t2 on t2.type_id = t1.type_id")->select();
		// $goods = M('goods')->where(['pid'=>$id])->select();
		
		// dump($goods);die;
		// dump($goods);die;
		// $goodspics = D('Goodspics')->where(['equipment_id'=>$id])->select();
		foreach ($goods as $key => &$value) {
			// $value['goodspics'] = D('Goods')->alias('t1')->where(['t1.pid'=>$id])->join('left join goodspics as t2 on t2.goods_id = t1.id')->getField('pics_mid');
			$value['equipment_name'] =  implode(',',D('Equipment')->alias('t1')->where("t1.pid = $id && t1.goods_id = $value[id]")->join("left join goods as t2 on t2.id = t1.goods_id")->getField('t1.id,t1.name',true));
			
		}
		
		$this->assign('goods',$goods);
		$this->display();
	}


	public function add(){
		if (IS_POST) {

			$user = session('manager_info');
			$id = $user['id'];
			$data = I('post.');
			//dump($data);die;
			$role_id = session('manager_info.role_id');
			$model = D('Goods');
			$files = $_FILES;
			if ($role_id > 3) {
				$data['pid'] = D('Manager')->where(['id'=>$id])->getField('pid',true);
			}
			//die;
			$goods_id = $model -> add($data);
			unset($file['logo']);
			$model -> upload_pics($goods_id,$files);
			$this->success('添加成功',U('Admin/Goods/index'));
		
		}else{
			$this->display();
		}
	}

	public function edit(){
		if (IS_POST) {
			$data = I('post.');
			//dump($data);die;
			$data['equipment_odds'] = round(100/$data['equipment_odds'],2);
			//查询出被分配这个商品的机台
			$equipments = M('Equipment')->where(['goods_id'=>$data['goods_id']])->select();

			foreach ($equipments as $key => $value) {
				$uuid[] = "'".$value['uuid']."'";
			}
			$uuid = implode(',',$uuid);
			
			//查出本来就被分配这个商品的机台
			$begin_ids = D('Equipment')->where(['goods_id'=>$data['goods_id']])->getField('id',true);

			$e_ids = implode(',',$begin_ids);
			//查出提交过来的
			$now_ids = $data['equipment_id'];
			$diff = implode(',',array_diff($begin_ids,$now_ids));
			if (is_null($begin_ids)) {
				
			}else{
			//NULL为全部取消  $diff为被取消的机台的id集合
			if (is_null($diff)) {
				D('Equipment')->where("id in ({$e_ids})")->save(['goods_id'=>0,'price'=>2,'time_limit'=>30]);
			}
			if ($diff) {
				D('Equipment')->where("id in ({$diff})")->save(['goods_id'=>0,'price'=>2,'time_limit'=>30]);
			}
			}
			$model = D('Goods');

			//修改goods表中的数据
			$res = $model->where(['id'=>$data['goods_id']])->save($data);
			//修改equipment表中的数据
			$equipment = M('Equipment')->where(['goods_id'=>$data['goods_id']])->save(['price'=>$data['price'],'odds'=>$data['equipment_odds'],'time_limit'=>$data['time_limit'],'type'=>$data['type_id']]);
			//修改set表中的数据 优先等级与单独修改一致

			$data0['value'] = '(0   ,true ,true ,true ,1,true ,"'.$data['time_limit'].'")';
			$data3['value'] = '(3   ,true ,true ,true ,1,true ,"'.$data['price'].'")';
			$data20['value'] = '(20   ,true ,true ,true ,1,true ,"'.$data['equipment_odds'].'")';
			if ($uuid) {
				M('Set')->where("uuid in ({$uuid}) and `key`='TIME_OF_GAME'")->save($data0);				
				M('Set')->where("uuid in ({$uuid}) and `key`='GAME_PRICE'")->save($data3);
				M('Set')->where("uuid in ({$uuid}) and `key`='RATE'")->save($data20);
			}
			
			if ($res!==false) {
				$files = $_FILES;
				unset($files['logo']);
				$model->upload_pics($data['goods_id'],$files);
				//获取到被分配的机台
				if ($data['id']) {
					$equipment_ids = implode(',',$data['id']);
					
					//一种商品可能存放于多个机台中
					$setauth = D('Equipment')->where("id in ({$equipment_ids})")->save(['goods_id'=>$data['goods_id'],'price'=>$data['price'],'time_limit'=>$data['time_limit'],'type'=>$data['type_id']]);
				}
				
				$this->success('修改成功',U('Admin/Goods/index'));
			}else{
				//修改失败
				$this->error('修改失败');
			}
			


		}else{
			$manager_id = session('manager_info.id');
			$type = M('Type')->select();
			//获取商品的id
			$id = I('get.id');
			$goods = D('Goods')->alias('t1')->where(['id'=>$id])->join("type as t2 on t2.type_id = t1.type_id")->find();
			$goodspics = D('Goodspics')->where(['goods_id'=>$id])->select();
			
			$equipment = D('Equipment')->where("pid = $manager_id && goods_id = 0 && type = 1")->select();

			$equipment_setauth = D('Equipment')->where(['goods_id'=>$id])->select();
			$equipment_ids = implode(D('Equipment')->where(['goods_id'=>$id])->getField('id',true),',');

			
				$goods['equipment_odds'] = round(100/$goods['equipment_odds']);
				
				//误差
				if ($goods['equipment_odds'] > 256) {
					$goods['equipment_odds'] = 255;
				}
			$this->assign('type',$type);
			// dump($equipment_ids);die;
			// dump($equipment_setauth);die;
			// $equipment_ids = D('Goods')->where(['pid'=>$manager_id])->getField('id',true);
			// $equipment_ids = implode(',',$equipment_ids);
			// dump($equipment_ids);die;
			$this->assign('equipment_setauth',$equipment_setauth);
			$this->assign('equioment_ids',$equipment_ids);
			$this->assign('equipment',$equipment);
			$this->assign('goodspics',$goodspics);
			$this->assign('goods',$goods);
			$this->display();
		}
	}

	public function detail(){
		$id = I('get.id');
		// $goods = D('Goods')->alias('t1')->where(['t1.id'=>$id])->join("left join goodspics as t2 on t1.id = t2.goods_id")->select();
		$goods = D('Goods')->where(['id'=>$id])->find();
		$goodspics = D('Goodspics')->where(['goods_id'=>$id])->select();
		dump($goodspics);die;
		$this->assign('goods',$goods);
		$this->assign('goodspics',$goodspics);
		$this->display();
	}
	//商品不能删除
	// public function del(){
	// 	//接收参数
	// 	$id = I('get.id');
	// 	//删除数据
	// 	$model = D('Goods');
	// 	$goods = $model -> where(['id'=>$id]) -> find();
	// 	$goods_id = $goods['id'];
	// 	$res = $model -> where(['id'=>$id]) -> delete();
	// 	if ($res!==false) {
	// 		//删除成功
	// 		$description = "删除了商品:". '   '.$goods_id;
	// 		$this->operate_log($description);
	// 		$this->success('删除成功',U('Admin/Equipment/index'));
	// 	}else{
	// 		//删除失败
	// 		$this -> error('删除失败');
	// 	}
	// }
	//商品不能删除
	public function ajaxdel(){
		//接收数据
		$id = I('post.id');

		//先获取到要删除的图片的图片路径,用作后续删除图片
		$info = D('Goodspics')->where(['goods_id'=>$id])->find();
		$res = D('Goodspics')->where(['goods_id'=>$id])->delete();
		D('Goods')->where(['id'=>$id])->delete();
		if ($res!==false) {
			//删除成功
			unlink(ROOT_PATH . $info['pics_origin']);
			unlink(ROOT_PATH . $info['pics_mid']);
			$return = array(
				'code' => 10000,
				'msg' => 'success'
				);
			$this->ajaxReturn($return);
		}else{
			//删除失败
			$return = array(
				'code' => 10001,
				'msg' => '删除失败'
				);
			$this->ajaxReturn($return);
		}
	}
		

}