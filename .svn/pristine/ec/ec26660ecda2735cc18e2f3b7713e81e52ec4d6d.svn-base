<?php
namespace Admin\Controller;

class CategoryController extends CommonController{
	//展示类型列表
	public function index(){

		$model=D('Category');
		//查询数据表
		$data=$model->select();
		$row=$model->count();
		$this->assign('row',$row);
		$this->assign('data',$data);
		$this->display();
	}

	//添加分类信息
	public function add(){

		//判断是否有POST提交
		if (IS_POST) {
			$data=I('post.','','htmlspecialchars');

			//判断接收数据是否为空
			if (!empty($data['cate_name'])) {
				//实例化模型
				$res=D('Category')->add($data);

				if ($res) {
					$this->success('添加分类成功',U('Admin/Category/index'));
				}else{
					$this->error('添加失败');
				}		
			}else{
				$this->error('请填写分类名称');
			}

		}else{
			//展示分类模版
			$this->display();
		}
	}

	//删除某个分类
	public function del(){
		$id=I('post.id');

		//根据id判断类型下边有没有商品
		$goods_info=D('Goods')->where(['cate_id'=>$id])->find();

		//判断是否有数据
		if ($goods_info == NULL) {
			$res=D('Category')->where(['id'=>$id])->delete();
			if ($res) {
				$data=array(
					'code'=>10000,
					'msg'=>'删除成功'
				);
				$this->ajaxReturn($data);
			}else{
				$data=array(
					'code'=>10002,
					'msg'=>'删除失败'
				);
				$this->ajaxReturn($data);
			}
		}else{
			$data=array(
				'code'=>10001,
				'msg'=>'当前类型下有商品'
			);

			$this->ajaxReturn($data);
		}
	}

	//编辑分类
	public function edit(){
		if (IS_POST) {
			$data=I('post.');
			
			$res=D('Category')->where(['id'=>$data['id']])->save($data);
			if ($res!==false) {
				$this->success('修改成功',U('Admin/Category/index'));
			}else{
				$this->error('修改失败');
			}

		}else{	
		$id=I('get.id');

		//根据id查询数据
		$data=D('Category')->where(['id'=>$id])->find();

		//判断结果是否有数据
		if (isset($data)) {
			$this->assign('data',$data);
		}else{
			$this->error('无效参数');
		}

		//展示数据
		$this->display();
		}
	}
}